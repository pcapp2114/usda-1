from __future__ import annotations

import logging
import re
from typing import Dict, List, Optional, Set
from urllib.parse import urljoin, urlparse

from bs4 import BeautifulSoup  # type: ignore

from ..http_client import Fetcher
from ..utils import extract_date, slugify, first_nonempty

log = logging.getLogger("scraper.newsroom")

_STORY_RE = re.compile(r"/Newsroom/(archive|Notices)/(?P<year>\d{4})/[^\s]+\.php\b")
_NOTICE_RE = re.compile(r"/Newsroom/Notices/(?P<year>\d{4})/[^\s]+\.php\b")
_BRIEFING_PDF_RE = re.compile(r"/Newsroom/Executive_Briefings/(?P<year>\d{4})/[^\s]+\.pdf\b", re.IGNORECASE)


def _extract_main_html(soup: BeautifulSoup) -> str:
    main = soup.find("main") or soup.find(id="content") or soup.find("div", id="content-area")
    if main:
        return str(main)
    # fallback: body
    body = soup.find("body")
    return str(body) if body else ""


def _extract_title(soup: BeautifulSoup) -> str:
    h1 = soup.find("h1") or soup.find("h2")
    if h1:
        return h1.get_text(" ", strip=True)
    # fallback: page <title>
    t = soup.find("title")
    return t.get_text(" ", strip=True) if t else ""


def _extract_contact(text: str) -> str:
    # Very lightweight heuristic: look for "Contact:" or a phone number line
    m = re.search(r"Contact\s*:\s*(.+)", text, re.IGNORECASE)
    if m:
        return m.group(1).strip()
    # try phone pattern
    m2 = re.search(r"\b\d{3}[-\s]\d{3}[-\s]\d{4}\b", text)
    return m2.group(0).strip() if m2 else ""


def _default_news_alias(title: str, prefix: str) -> str:
    s = slugify(title)
    if not s:
        s = "untitled"
    return f"/{prefix}/{s}"


def discover_archive_story_urls(fetcher: Fetcher, base: str, year: int) -> List[str]:
    index = urljoin(base, f"/Newsroom/archive/{year}/index.php")
    try:
        res = fetcher.get(index)
    except Exception:
        return []
    soup = BeautifulSoup(res.text, "lxml")
    urls: Set[str] = set()
    for a in soup.select("a[href]"):
        href = urljoin(index, a.get("href") or "")
        if _STORY_RE.search(href):
            urls.add(href)
    return sorted(urls)


def discover_notice_urls(fetcher: Fetcher, base: str, year: int) -> List[str]:
    index = urljoin(base, f"/Newsroom/Notices/{year}/index.php")
    try:
        res = fetcher.get(index)
    except Exception:
        return []
    soup = BeautifulSoup(res.text, "lxml")
    urls: Set[str] = set()
    for a in soup.select("a[href]"):
        href = urljoin(index, a.get("href") or "")
        if _NOTICE_RE.search(href):
            urls.add(href)
    return sorted(urls)


def discover_executive_briefing_pdfs(fetcher: Fetcher, base: str, year: int) -> List[str]:
    index = urljoin(base, f"/Newsroom/Executive_Briefings/{year}/index.php")
    try:
        res = fetcher.get(index)
    except Exception:
        return []
    soup = BeautifulSoup(res.text, "lxml")
    urls: Set[str] = set()
    for a in soup.select("a[href]"):
        href = urljoin(index, a.get("href") or "")
        if _BRIEFING_PDF_RE.search(href):
            urls.add(href)
    return sorted(urls)


def scrape_news_release(fetcher: Fetcher, url: str) -> Dict[str, str]:
    res = fetcher.get(url)
    soup = BeautifulSoup(res.text, "lxml")
    title = _extract_title(soup)
    html = _extract_main_html(soup)
    text = soup.get_text(" ", strip=True)

    date = extract_date(text) or ""
    contact = _extract_contact(text) or ""

    # Build alias similar to legacy: /newsroom/news-release/<slug>
    alias = _default_news_alias(title, "newsroom/news-release")

    return {
        "news_title": title,
        "content": html,
        "date": date,
        "contact": contact,
        "news_new_url": alias,
        # helpful extras
        "referring_url": url,
    }


def scrape_notice(fetcher: Fetcher, url: str) -> Dict[str, str]:
    res = fetcher.get(url)
    soup = BeautifulSoup(res.text, "lxml")
    title = _extract_title(soup)
    html = _extract_main_html(soup)
    text = soup.get_text(" ", strip=True)
    date = extract_date(text) or ""
    alias = _default_news_alias(title, "newsroom/notice")
    return {
        "news_title": title,
        "content": html,
        "date": date,
        "news_new_url": alias,
        "referring_url": url,
    }


def scrape_asb_feed_row(fetcher: Fetcher, url: str) -> Dict[str, str]:
    # asb_notice_feed expects title|url for each notice page
    res = fetcher.get(url)
    soup = BeautifulSoup(res.text, "lxml")
    title = _extract_title(soup)
    return {"title": title, "url": url}


def scrape_executive_briefing_pdf(url: str) -> Dict[str, str]:
    # legacy: date|title|referring_url
    # drupal feed in DB may require fid; we cannot know it; we put URL in the reference column.
    fname = url.split("/")[-1]
    date = extract_date(fname.replace("-", "/")) or ""
    title = fname.replace(".pdf", "")
    return {
        "date": date,
        "news_title": title,
        "referring_asb_url2": url,  # NOTE: may need Feeds mapping adjustment
        "referring_url": url,
    }


def generate_newsroom(
    fetcher: Fetcher,
    base: str,
    years: List[int],
) -> Dict[str, List[Dict[str, str]]]:
    """Returns dict of feed_type_id -> list of rows.

    Feed types:
      - news_releases
      - asb_notice
      - asb_notice_feed
      - asb_briefings
    """
    news_rows: List[Dict[str, str]] = []
    notice_rows: List[Dict[str, str]] = []
    notice_feed_rows: List[Dict[str, str]] = []
    briefing_rows: List[Dict[str, str]] = []

    for y in years:
        for url in discover_archive_story_urls(fetcher, base, y):
            try:
                news_rows.append(scrape_news_release(fetcher, url))
            except Exception as e:
                log.warning("Failed news scrape: %s (%s)", url, e)

        for url in discover_notice_urls(fetcher, base, y):
            try:
                notice_rows.append(scrape_notice(fetcher, url))
                notice_feed_rows.append(scrape_asb_feed_row(fetcher, url))
            except Exception as e:
                log.warning("Failed notice scrape: %s (%s)", url, e)

        for pdf in discover_executive_briefing_pdfs(fetcher, base, y):
            briefing_rows.append(scrape_executive_briefing_pdf(pdf))

    return {
        "news_releases": news_rows,
        "asb_notice": notice_rows,
        "asb_notice_feed": notice_feed_rows,
        "asb_briefings": briefing_rows,
    }
