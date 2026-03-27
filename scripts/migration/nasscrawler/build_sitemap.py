#!/usr/bin/env python3
"""
build_sitemap.py

Purpose
-------
Crawl a website and generate a lightweight "sitemap package" that can be used for:
- content inventory during a migration
- identifying internal links / navigation structure
- spot-checking response status and content-types
- discovering orphaned or hard-to-find pages

Outputs (written to the current working directory by default)
------------------------------------------------------------
1) <prefix>_urls.json
   A list of fetched URLs and basic fetch metadata (HTTP status, Content-Type, final URL after redirects, errors).

2) <prefix>_edges.csv
   A link graph written incrementally as the crawler runs:
     parent_url, child_url
   Each row means: "parent_url contains a hyperlink to child_url".

3) <prefix>_children.json
   A convenience structure built from edges.csv at the end of the run:
     { "parent_url": ["child_url1", "child_url2", ...], ... }

4) <state-file>
   A checkpoint/resume file containing:
   - the crawl queue
   - the seen set
   - accumulated URL metadata
   - basic stats

Key behavior
------------
- Same-host crawling: by default, only links on the same netloc (host) as --base are crawled.
- Query-string handling: query strings are stripped by default to prevent infinite URL variants.
  You can keep specific query keys (e.g., pagination) with --keep-query-key.
- Streaming edges: edges are appended to CSV as the crawl runs (keeps memory usage low).
- Resume support: use --resume to continue from a previous --state-file checkpoint.

Example
-------
python build_sitemap.py --base https://www.nass.usda.gov/ --out-prefix nass_sitemap --max-pages 10000 --delay 0.25

"""

from __future__ import annotations

import argparse
import csv
import json
import logging
import os
import re
import sys
import time
from collections import deque
from dataclasses import asdict, dataclass
from html.parser import HTMLParser
from typing import Deque, Dict, List, Optional, Pattern, Set, Tuple
from urllib.parse import parse_qsl, urlencode, urldefrag, urljoin, urlparse, urlunparse

import requests

try:
    # Optional but recommended: more robust link extraction than stdlib HTMLParser.
    # requirements.txt includes beautifulsoup4 + lxml, so in normal usage this will be available.
    from bs4 import BeautifulSoup  # type: ignore
except Exception:  # pragma: no cover
    BeautifulSoup = None  # type: ignore


@dataclass
class UrlInfo:
    """Metadata captured for each fetched URL.

    Attributes
    ----------
    url:
        The normalized URL used as a key (what the crawler attempted to fetch).
    status:
        HTTP status code (e.g., 200, 301, 404). None if the request failed before a response.
    content_type:
        Content-Type header value (if present), e.g. 'text/html; charset=utf-8'.
    final_url:
        The final URL after redirects (requests follows redirects by default).
    error:
        Exception string if the request failed, or if HTML parsing failed.
    """

    url: str
    status: Optional[int] = None
    content_type: Optional[str] = None
    final_url: Optional[str] = None
    error: Optional[str] = None


class LinkExtractor(HTMLParser):
    """Minimal HTML <a href="..."> extractor (stdlib-only fallback).

    Notes
    -----
    - This is intentionally conservative and only extracts anchor hrefs.
    - For best results install dependencies in requirements.txt so BeautifulSoup is used instead.
    """

    def __init__(self) -> None:
        super().__init__()
        self.links: List[str] = []

    def handle_starttag(self, tag: str, attrs: List[Tuple[str, Optional[str]]]) -> None:
        if tag.lower() != "a":
            return
        for k, v in attrs:
            if k.lower() == "href" and v:
                self.links.append(v)


def extract_links(html: str) -> List[str]:
    """Extract href values from HTML.

    Prefers BeautifulSoup when available for robustness. Falls back to stdlib HTMLParser.
    """
    if BeautifulSoup is not None:
        soup = BeautifulSoup(html, "lxml")  # lxml is faster and handles malformed HTML better
        out: List[str] = []
        for a in soup.find_all("a", href=True):
            href = a.get("href")
            if href:
                out.append(str(href))
        return out

    # Fallback: stdlib parser
    extractor = LinkExtractor()
    extractor.feed(html)
    return extractor.links


def setup_logger(level: str, log_file: Optional[str]) -> logging.Logger:
    """Create a console logger (and optional file logger) for the crawler."""
    logger = logging.getLogger("sitemap_crawler")
    logger.setLevel(getattr(logging, level.upper(), logging.INFO))
    logger.handlers.clear()

    fmt = logging.Formatter("%(asctime)s | %(levelname)s | %(message)s")

    stream_handler = logging.StreamHandler(sys.stdout)
    stream_handler.setFormatter(fmt)
    stream_handler.setLevel(getattr(logging, level.upper(), logging.INFO))
    logger.addHandler(stream_handler)

    if log_file:
        file_handler = logging.FileHandler(log_file, encoding="utf-8")
        file_handler.setFormatter(fmt)
        file_handler.setLevel(getattr(logging, level.upper(), logging.INFO))
        logger.addHandler(file_handler)

    logger.propagate = False
    return logger


def shorten(s: str, max_len: int = 140) -> str:
    """Shorten long strings for log readability."""
    if len(s) <= max_len:
        return s
    return s[: max_len - 3] + "..."


def normalize_url(
    base: str,
    href: str,
    *,
    strip_query: bool,
    keep_query_keys: Set[str],
) -> Optional[str]:
    """Resolve href against base, remove fragments, and normalize the URL.

    Parameters
    ----------
    base:
        The page URL the link was found on (used for urljoin).
    href:
        Raw href string extracted from HTML.
    strip_query:
        If True, removes the entire query string unless keep_query_keys is provided.
    keep_query_keys:
        If strip_query is True and keep_query_keys is non-empty, only these query keys are retained.

    Returns
    -------
    A normalized absolute URL (http/https only), or None if it should be ignored.
    """
    abs_url = urljoin(base, href)
    abs_url, _frag = urldefrag(abs_url)

    p = urlparse(abs_url)
    if p.scheme not in ("http", "https"):
        return None

    # Normalize netloc casing + strip default ports
    netloc = p.netloc.lower()
    if netloc.endswith(":80"):
        netloc = netloc[:-3]
    if netloc.endswith(":443"):
        netloc = netloc[:-4]

    # Ensure path is never empty (urlunparse would otherwise yield ...//?query)
    path = p.path or "/"

    query = p.query
    if strip_query:
        if keep_query_keys:
            # Keep only whitelisted keys, sorted for stability.
            pairs = [
                (k, v)
                for (k, v) in parse_qsl(query, keep_blank_values=True)
                if k in keep_query_keys
            ]
            pairs.sort(key=lambda x: (x[0], x[1]))
            query = urlencode(pairs, doseq=True)
        else:
            query = ""

    return urlunparse((p.scheme, netloc, path, p.params, query, ""))


def is_allowed(url: str, allowed_netlocs: Set[str], exclude_re: List[Pattern[str]]) -> bool:
    """Return True if URL is within allowed hosts and not excluded by path regexes."""
    p = urlparse(url)
    if p.netloc.lower() not in allowed_netlocs:
        return False
    for rx in exclude_re:
        if rx.search(p.path):
            return False
    return True


def fetch(
    session: requests.Session, url: str, timeout: int
) -> Tuple[Optional[requests.Response], Optional[str]]:
    """Fetch a URL with requests, returning (response, error_string)."""
    try:
        resp = session.get(url, timeout=timeout, allow_redirects=True)
        return resp, None
    except Exception as e:
        return None, str(e)


def write_urls_json(prefix: str, urls: Dict[str, UrlInfo], logger: logging.Logger) -> str:
    """Write <prefix>_urls.json from accumulated UrlInfo records."""
    out_urls = f"{prefix}_urls.json"
    with open(out_urls, "w", encoding="utf-8") as f:
        json.dump([asdict(v) for v in urls.values()], f, indent=2)
    logger.info(f"Wrote: {out_urls}")
    return out_urls


def edges_writer_open(prefix: str) -> Tuple[str, csv.writer, object]:
    """Open <prefix>_edges.csv in append mode and return (path, writer, file_handle)."""
    out_edges = f"{prefix}_edges.csv"
    file_exists = os.path.exists(out_edges)
    f = open(out_edges, "a", newline="", encoding="utf-8")
    w = csv.writer(f)
    if not file_exists:
        w.writerow(["parent_url", "child_url"])
    return out_edges, w, f


def build_children_from_edges(prefix: str, edges_path: str, logger: logging.Logger) -> str:
    """Build <prefix>_children.json from edges.csv.

    This loads edges.csv into memory (as sets). If you are crawling very large sites,
    consider using --no-children and post-processing the CSV with another tool.
    """
    children: Dict[str, Set[str]] = {}
    with open(edges_path, "r", newline="", encoding="utf-8") as f:
        r = csv.reader(f)
        header = next(r, None)
        if not header or header[:2] != ["parent_url", "child_url"]:
            raise RuntimeError(f"Unexpected edges CSV header in {edges_path}")
        for row in r:
            if len(row) < 2:
                continue
            a, b = row[0], row[1]
            children.setdefault(a, set()).add(b)

    out_tree = f"{prefix}_children.json"
    children_sorted = {k: sorted(v) for k, v in children.items()}
    with open(out_tree, "w", encoding="utf-8") as f:
        json.dump(children_sorted, f, indent=2)
    logger.info(f"Wrote: {out_tree}")
    return out_tree


def save_state(
    state_file: str,
    *,
    base: str,
    allowed_netlocs: Set[str],
    seen: Set[str],
    queue: Deque[str],
    urls: Dict[str, UrlInfo],
    stats: Dict[str, int],
    logger: logging.Logger,
) -> None:
    """Write a checkpoint file that allows resuming an interrupted crawl."""
    state = {
        "base": base,
        "allowed_netlocs": sorted(allowed_netlocs),
        "seen": list(seen),
        "queue": list(queue),
        "urls": [asdict(v) for v in urls.values()],
        "stats": stats,
        "saved_at_epoch": int(time.time()),
    }
    with open(state_file, "w", encoding="utf-8") as f:
        json.dump(state, f)
    logger.info(
        f"Checkpoint saved: {state_file} | seen={len(seen)} queue={len(queue)} urls={len(urls)}"
    )


def load_state(state_file: str, logger: logging.Logger) -> Dict:
    """Load a previously saved checkpoint state file."""
    with open(state_file, "r", encoding="utf-8") as f:
        state = json.load(f)
    logger.info(
        f"Loaded state: {state_file} | seen={len(state.get('seen', []))} queue={len(state.get('queue', []))} urls={len(state.get('urls', []))}"
    )
    return state


def main() -> int:
    ap = argparse.ArgumentParser(
        description="Crawl a site and emit URL inventory + internal link graph artifacts.",
    )
    ap.add_argument("--base", default="https://www.nass.usda.gov/", help="Base URL to crawl (seed + host allowlist)")
    ap.add_argument("--seed", action="append", default=[], help="Additional seed URL(s) (repeatable)")
    ap.add_argument("--max-pages", type=int, default=5000, help="Hard cap on pages fetched")
    ap.add_argument("--delay", type=float, default=0.25, help="Delay between requests (seconds)")
    ap.add_argument("--timeout", type=int, default=20, help="Request timeout (seconds)")
    ap.add_argument("--out-prefix", default="nass_sitemap", help="Output prefix for files")

    ap.add_argument(
        "--exclude",
        action="append",
        default=[
            r"^/search/?",
            r"^/user/?",
            r"^/admin/?",
            r"^/media/oembed",
            r"\.zip$",
            r"\.gz$",
        ],
        help="Regex on PATH to exclude (repeatable)",
    )

    # Query normalization (important for "get all pages" without infinite variants)
    ap.add_argument(
        "--strip-query",
        dest="strip_query",
        action=argparse.BooleanOptionalAction,
        default=True,
        help="Strip query strings to avoid infinite variants (default: enabled)",
    )
    ap.add_argument(
        "--keep-query-key",
        action="append",
        default=[],
        help="When stripping query, keep only these key(s) (repeatable)",
    )

    # Logging / progress controls
    ap.add_argument("--log-level", default="INFO", help="DEBUG, INFO, WARNING, ERROR")
    ap.add_argument("--log-file", default=None, help="Optional log file path")
    ap.add_argument("--verbose", action="store_true", help="Log each URL fetch")
    ap.add_argument("--progress-every", type=int, default=25, help="Print summary every N pages (0 disables)")

    # Resume/checkpointing
    ap.add_argument("--state-file", default="nass_sitemap_state.json", help="Checkpoint state file")
    ap.add_argument("--resume", action="store_true", help="Resume from --state-file if it exists")
    ap.add_argument("--checkpoint-every", type=int, default=200, help="Save state every N fetched pages (0 disables)")

    # Outputs
    ap.add_argument("--no-children", action="store_true", help="Do not build children.json at the end")

    args = ap.parse_args()

    logger = setup_logger(args.log_level, args.log_file)
    keep_query_keys: Set[str] = set(args.keep_query_key)

    base = args.base if args.base.endswith("/") else args.base + "/"
    base_p = urlparse(base)
    allowed_netlocs: Set[str] = {base_p.netloc.lower()}
    exclude_re: List[Pattern[str]] = [re.compile(x) for x in args.exclude]

    # Data structures
    q: Deque[str] = deque()
    seen: Set[str] = set()
    urls: Dict[str, UrlInfo] = {}

    # Stats
    fetched = 0
    html_pages = 0
    parse_errors = 0
    request_errors = 0
    start = time.monotonic()

    # Edges output (streaming)
    edges_path, edges_csv, edges_file = edges_writer_open(args.out_prefix)

    def enqueue(u: str) -> None:
        """Add a URL to the crawl queue if new and allowed."""
        if u in seen:
            return
        if not is_allowed(u, allowed_netlocs, exclude_re):
            return
        seen.add(u)
        q.append(u)

    # Resume logic
    if args.resume and os.path.exists(args.state_file):
        state = load_state(args.state_file, logger)

        # Restore queue/seen/urls
        base = state.get("base", base)
        allowed_netlocs = set(state.get("allowed_netlocs", list(allowed_netlocs)))
        seen = set(state.get("seen", []))
        q = deque(state.get("queue", []))
        urls_list = state.get("urls", [])
        urls = {u["url"]: UrlInfo(**u) for u in urls_list if isinstance(u, dict) and "url" in u}

        st = state.get("stats", {}) or {}
        fetched = int(st.get("fetched", fetched))
        html_pages = int(st.get("html_pages", html_pages))
        parse_errors = int(st.get("parse_errors", parse_errors))
        request_errors = int(st.get("request_errors", request_errors))

        logger.info(f"Resuming crawl with queue={len(q)} seen={len(seen)} urls={len(urls)} fetched={fetched}")
        logger.info(f"Edges streaming to: {edges_path} (appending)")
    else:
        # Fresh seed: sitemap page + homepage + any additional seeds
        default_seed = urljoin(base, "Help/Site_Map/")
        seeds = [default_seed] + args.seed + [base]

        for s in seeds:
            ns = normalize_url(
                base,
                s,
                strip_query=args.strip_query,
                keep_query_keys=keep_query_keys,
            )
            if ns:
                enqueue(ns)

        logger.info("Starting crawl")
        logger.info(f"Base: {base}")
        logger.info(f"Allowed host(s): {', '.join(sorted(allowed_netlocs))}")
        logger.info(f"Max pages: {args.max_pages} | Delay: {args.delay}s | Timeout: {args.timeout}s")
        logger.info(f"Strip query: {args.strip_query} | Keep query keys: {sorted(keep_query_keys)}")
        logger.info(f"Queue initialized with {len(q)} URL(s)")
        logger.info(f"Edges streaming to: {edges_path}")

    session = requests.Session()
    session.headers.update(
        {
            "User-Agent": "NASS-Drupal-Migration-SitemapBuilder/2.1 (+internal migration tooling)",
        }
    )

    try:
        while q and fetched < args.max_pages:
            url = q.popleft()
            fetched += 1

            if args.verbose:
                logger.info(f"[{fetched}/{args.max_pages}] Fetching: {shorten(url)} | queue={len(q)}")

            resp, err = fetch(session, url, args.timeout)
            info = urls.get(url) or UrlInfo(url=url)

            if err:
                request_errors += 1
                info.error = err
                urls[url] = info
                logger.warning(f"Request error: {shorten(url)} | {err}")
                time.sleep(args.delay)
                continue

            info.status = resp.status_code
            info.content_type = resp.headers.get("Content-Type")
            info.final_url = resp.url
            urls[url] = info

            ctype = (info.content_type or "").lower()

            if args.progress_every and fetched % args.progress_every == 0:
                now = time.monotonic()
                elapsed = now - start
                rate = fetched / elapsed if elapsed > 0 else 0.0
                logger.info(
                    "Progress: fetched=%s, urls=%s, queue=%s, html_pages=%s, req_errors=%s, parse_errors=%s, rate=%.2f pages/sec"
                    % (fetched, len(urls), len(q), html_pages, request_errors, parse_errors, rate)
                )

            # Only parse HTML pages for links
            if resp.status_code == 200 and "text/html" in ctype:
                html_pages += 1

                try:
                    hrefs = extract_links(resp.text)
                except Exception as e:
                    parse_errors += 1
                    info.error = f"HTML parse error: {e}"
                    urls[url] = info
                    logger.warning(f"HTML parse error: {shorten(url)} | {e}")
                    time.sleep(args.delay)
                    continue

                new_enqueued = 0
                for href in hrefs:
                    nu = normalize_url(
                        url,
                        href,
                        strip_query=args.strip_query,
                        keep_query_keys=keep_query_keys,
                    )
                    if not nu:
                        continue
                    if is_allowed(nu, allowed_netlocs, exclude_re):
                        # Stream edge
                        edges_csv.writerow([url, nu])

                        # Enqueue newly discovered URL
                        if nu not in seen:
                            enqueue(nu)
                            new_enqueued += 1

                if args.verbose:
                    logger.info(
                        f"Parsed HTML: {shorten(url)} | links_found={len(hrefs)} | new_enqueued={new_enqueued}"
                    )

            # Checkpoint periodically
            if args.checkpoint_every and fetched % args.checkpoint_every == 0:
                stats = {
                    "fetched": fetched,
                    "html_pages": html_pages,
                    "parse_errors": parse_errors,
                    "request_errors": request_errors,
                }
                save_state(
                    args.state_file,
                    base=base,
                    allowed_netlocs=allowed_netlocs,
                    seen=seen,
                    queue=q,
                    urls=urls,
                    stats=stats,
                    logger=logger,
                )

            time.sleep(args.delay)

    except KeyboardInterrupt:
        logger.warning("Interrupted by user (Ctrl+C). Writing partial outputs + saving state...")

    finally:
        try:
            edges_file.flush()
        finally:
            edges_file.close()

    # Always write outputs (even on interrupt)
    write_urls_json(args.out_prefix, urls, logger)

    if not args.no_children:
        try:
            build_children_from_edges(args.out_prefix, edges_path, logger)
        except Exception as e:
            logger.warning(f"Failed to build children.json from edges: {e}")

    # Save final state as well
    stats = {
        "fetched": fetched,
        "html_pages": html_pages,
        "parse_errors": parse_errors,
        "request_errors": request_errors,
    }
    save_state(
        args.state_file,
        base=base,
        allowed_netlocs=allowed_netlocs,
        seen=seen,
        queue=q,
        urls=urls,
        stats=stats,
        logger=logger,
    )

    end = time.monotonic()
    elapsed = end - start
    rate = fetched / elapsed if elapsed > 0 else 0.0

    logger.info("Done.")
    logger.info(f"Fetched: {fetched} (cap: {args.max_pages})")
    logger.info(f"URLs recorded: {len(urls)}")
    logger.info(f"HTML pages parsed: {html_pages}")
    logger.info(f"Request errors: {request_errors}")
    logger.info(f"Parse errors: {parse_errors}")
    logger.info(f"Final queue size (remaining): {len(q)}")
    logger.info(f"Elapsed: {elapsed:.1f}s | Rate: {rate:.2f} pages/sec")
    logger.info(f"Edges file: {edges_path}")

    return 0


if __name__ == "__main__":
    raise SystemExit(main())