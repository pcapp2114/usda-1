from __future__ import annotations

import logging
from typing import Dict, List
from urllib.parse import urljoin

from bs4 import BeautifulSoup  # type: ignore

from ..http_client import Fetcher
from ..utils import US_STATE_ABBR

log = logging.getLogger("scraper.rankings_market_value")


def generate_rankings_market_value(fetcher: Fetcher, base: str) -> List[Dict[str, str]]:
    """Scrape Rankings of Market Value pages and store the HTML table.

    Feed expects: state_title | table | year
    """
    root = urljoin(base, "/Publications/AgCensus/2017/Online_Resources/Rankings_of_Market_Value/")
    index = urljoin(root, "index.php")
    res = fetcher.get(index)
    soup = BeautifulSoup(res.text, "lxml")

    pages: Dict[str, str] = {}

    # Discover subpages (United_States + states)
    for a in soup.select("a[href]"):
        href = urljoin(index, a.get("href") or "")
        if "/Rankings_of_Market_Value/" in href and href.endswith("/index.php"):
            key = href.split("/Rankings_of_Market_Value/")[-1].split("/")[0]
            pages[key] = href

    rows: List[Dict[str, str]] = []
    for key, page_url in sorted(pages.items()):
        page = fetcher.get(page_url)
        ps = BeautifulSoup(page.text, "lxml")

        table = ps.find("table")
        if not table:
            log.warning("No table found on %s", page_url)
            continue

        # Get a nice title
        h1 = ps.find(["h1", "h2"])
        state_title = (h1.get_text(" ", strip=True) if h1 else "").strip()
        if not state_title:
            if key == "United_States":
                state_title = "United States"
            else:
                state_title = key.replace("_", " ")

        rows.append(
            {
                "state_title": state_title,
                "table": str(table),
                "year": "2017",
                "source_page": page_url,
            }
        )

    return rows
