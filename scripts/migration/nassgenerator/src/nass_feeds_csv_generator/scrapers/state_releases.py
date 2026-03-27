from __future__ import annotations

import logging
import re
from collections import defaultdict
from dataclasses import dataclass
from typing import Dict, Iterable, List, Optional, Tuple
from urllib.parse import urljoin

from bs4 import BeautifulSoup  # type: ignore

from ..http_client import Fetcher
from ..utils import extract_date, first_nonempty, url_path

log = logging.getLogger("scraper.state_releases")


_STATE_INDEX_RE = re.compile(r"/Statistics_by_State/([^/]+)/index\.php\b")
_PUB_CATEGORY_RE = re.compile(r"/Statistics_by_State/([^/]+)/Publications/([^/]+)/index\.php\b")
_PDF_RE = re.compile(r"\.pdf$", re.IGNORECASE)


def discover_states(fetcher: Fetcher, base: str) -> List[str]:
    url = urljoin(base, "/Statistics_by_State/")
    res = fetcher.get(url)
    soup = BeautifulSoup(res.text, "lxml")
    states = set()
    for a in soup.select("a[href]"):
        href = a.get("href") or ""
        m = _STATE_INDEX_RE.search(href)
        if m:
            states.add(m.group(1))
    out = sorted(states)
    if not out:
        log.warning("No states discovered at %s; HTML layout may have changed.", url)
    return out


def discover_publication_categories(fetcher: Fetcher, base: str, state: str) -> List[Tuple[str, str]]:
    """Return list of (category_label, category_url) for a state."""
    state_url = urljoin(base, f"/Statistics_by_State/{state}/index.php")
    res = fetcher.get(state_url)
    soup = BeautifulSoup(res.text, "lxml")
    categories: Dict[str, str] = {}
    for a in soup.select("a[href]"):
        href = a.get("href") or ""
        full = urljoin(state_url, href)
        m = _PUB_CATEGORY_RE.search(full)
        if not m:
            continue
        cat_seg = m.group(2)
        label = (a.get_text(" ", strip=True) or cat_seg).strip()
        # Avoid obviously non-category navigation
        if label.lower() in {"publications", "home", "back"}:
            continue
        categories[label] = full
    if not categories:
        # Fallback: heuristic scan for any /Publications/ links
        for a in soup.select("a[href*='/Publications/']"):
            href = urljoin(state_url, a.get("href") or "")
            if href.endswith("/index.php") and f"/Statistics_by_State/{state}/Publications/" in href:
                label = a.get_text(" ", strip=True) or href.split("/Publications/")[-1].split("/")[0]
                categories[label] = href
    return sorted(categories.items(), key=lambda x: x[0].lower())


def _extract_pdf_rows_from_category(fetcher: Fetcher, category_url: str, state: str, category_label: str) -> List[Dict[str, str]]:
    res = fetcher.get(category_url)
    soup = BeautifulSoup(res.text, "lxml")

    rows: List[Dict[str, str]] = []
    for a in soup.select("a[href]"):
        href = a.get("href") or ""
        full = urljoin(category_url, href)
        if not _PDF_RE.search(full):
            continue

        title = a.get_text(" ", strip=True)
        filename = full.split("/")[-1]

        # Try to find a date nearby (common patterns: in same table row or list item)
        date_text = None
        tr = a.find_parent("tr")
        if tr is not None:
            date_text = tr.get_text(" ", strip=True)
        else:
            li = a.find_parent(["li", "p", "div"])
            if li is not None:
                date_text = li.get_text(" ", strip=True)

        date_val = extract_date(date_text or "") or ""

        rows.append(
            {
                "url": full,
                "filename": first_nonempty(title, filename),
                "state": state,
                "category": category_label,
                "date": date_val,
                # Helpful extras (ignored by Feeds unless you map them)
                "path": url_path(full),
                "source_page": category_url,
            }
        )

    # De-dup by URL
    uniq = {}
    for r in rows:
        uniq[r["url"]] = r
    return list(uniq.values())


def generate_state_releases(
    fetcher: Fetcher,
    base: str,
    states: Optional[Iterable[str]] = None,
) -> Tuple[Dict[str, List[Dict[str, str]]], Dict[str, List[Dict[str, str]]]]:
    """Generate both state_releases and state_releases_filtered row-sets.

    Returns:
      (state_releases_rows_by_state, state_releases_filtered_rows_by_state)
    """
    states_list = list(states) if states is not None else discover_states(fetcher, base)
    by_state_media: Dict[str, List[Dict[str, str]]] = defaultdict(list)
    by_state_node: Dict[str, List[Dict[str, str]]] = defaultdict(list)

    for state in states_list:
        cats = discover_publication_categories(fetcher, base, state)
        for cat_label, cat_url in cats:
            pdf_rows = _extract_pdf_rows_from_category(fetcher, cat_url, state, cat_label)

            # Media feed: expects url, filename, state, category, date
            for r in pdf_rows:
                by_state_media[state].append(
                    {
                        "url": r["url"],
                        "filename": r["filename"],
                        "state": r["state"],
                        "category": r["category"],
                        "date": r["date"],
                    }
                )

            # Node feed: expects url, title, state, category, date, path
            for r in pdf_rows:
                by_state_node[state].append(
                    {
                        "url": r["url"],
                        "title": r["filename"],
                        "state": r["state"],
                        "category": r["category"],
                        "date": r["date"],
                        "path": r["path"],
                    }
                )

        # De-dup within each state
        def dedup(rows: List[Dict[str, str]], key: str) -> List[Dict[str, str]]:
            uniq = {}
            for rr in rows:
                uniq[rr[key]] = rr
            return list(uniq.values())

        by_state_media[state] = dedup(by_state_media[state], "url")
        by_state_node[state] = dedup(by_state_node[state], "url")

    return by_state_media, by_state_node
