from __future__ import annotations

import logging
from typing import Dict, List
from urllib.parse import urlparse

from bs4 import BeautifulSoup  # type: ignore

from ..http_client import Fetcher
from ..utils import slugify

log = logging.getLogger("scraper.landing_pages")


def scrape_landing_page(fetcher: Fetcher, url: str) -> Dict[str, str]:
    res = fetcher.get(url)
    soup = BeautifulSoup(res.text, "lxml")

    title_el = soup.find("h1") or soup.find("h2") or soup.find("title")
    title = title_el.get_text(" ", strip=True) if title_el else url

    main = soup.find("main") or soup.find(id="content") or soup.find("body")
    content_html = str(main) if main else res.text

    old_path = urlparse(url).path
    # Default: keep the same path (safe). You can override via seeds.yaml mapping.
    new_path = old_path
    if not new_path or new_path == "/":
        new_path = f"/landing/{slugify(title)}"

    return {
        "title": title,
        "field_content": content_html,
        "old_path": old_path,
        "new_path": new_path,
        "source_url": url,
    }


def generate_landing_pages(fetcher: Fetcher, urls: List[str]) -> List[Dict[str, str]]:
    out: List[Dict[str, str]] = []
    for u in urls:
        try:
            out.append(scrape_landing_page(fetcher, u))
        except Exception as e:
            log.warning("Landing page scrape failed: %s (%s)", u, e)
    return out
