from __future__ import annotations

import logging
import re
from typing import Dict, List
from urllib.parse import urljoin

from bs4 import BeautifulSoup  # type: ignore

from ..http_client import Fetcher
from ..utils import extract_date

log = logging.getLogger("scraper.reports")

_PDF_RE = re.compile(r"\.pdf$", re.IGNORECASE)


def scrape_reports_index(fetcher: Fetcher, url: str, default_section: str = "") -> List[Dict[str, str]]:
    res = fetcher.get(url)
    soup = BeautifulSoup(res.text, "lxml")

    rows: List[Dict[str, str]] = []

    # Walk through headings; assign section/type from nearest heading.
    current_section = default_section or ""
    current_type = ""

    for el in soup.find_all(["h1", "h2", "h3", "a", "p", "li"]):
        if el.name in {"h1", "h2", "h3"}:
            txt = el.get_text(" ", strip=True)
            if txt:
                # Very rough heuristic: h2/h3 tends to be a section
                current_section = txt
            continue

        if el.name == "a" and el.get("href"):
            href = urljoin(url, el.get("href") or "")
            if not _PDF_RE.search(href):
                continue
            title = el.get_text(" ", strip=True) or href.split("/")[-1]
            context_text = (el.find_parent("li") or el.find_parent("p") or el).get_text(" ", strip=True)
            date = extract_date(context_text) or ""
            rows.append(
                {
                    "title": title,
                    "report": href,
                    "date": date,
                    "authors": "",
                    "type": current_type,
                    "section": current_section,
                    "source_page": url,
                }
            )

    # De-dup by report URL
    uniq = {}
    for r in rows:
        uniq[r["report"]] = r
    return list(uniq.values())


def generate_reports(fetcher: Fetcher, urls: List[str]) -> List[Dict[str, str]]:
    all_rows: List[Dict[str, str]] = []
    for u in urls:
        try:
            all_rows.extend(scrape_reports_index(fetcher, u))
        except Exception as e:
            log.warning("Report scrape failed: %s (%s)", u, e)
    return all_rows
