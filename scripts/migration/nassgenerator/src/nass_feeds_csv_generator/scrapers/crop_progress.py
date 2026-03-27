from __future__ import annotations

import logging
import re
from typing import Dict, List, Tuple
from urllib.parse import urljoin

from bs4 import BeautifulSoup  # type: ignore

from ..http_client import Fetcher

log = logging.getLogger("scraper.crop_progress")

_YEAR_RE = re.compile(r"/Crop_Progress_%26_Condition/(\d{4})/index\.php\b")
_PDF_RE = re.compile(r"\.pdf$", re.IGNORECASE)


def discover_year_pages(fetcher: Fetcher, base: str) -> List[Tuple[str, str]]:
    url = urljoin(base, "/Charts_and_Maps/Crop_Progress_%26_Condition/index.php")
    res = fetcher.get(url)
    soup = BeautifulSoup(res.text, "lxml")
    years: Dict[str, str] = {}
    for a in soup.select("a[href]"):
        href = urljoin(url, a.get("href") or "")
        m = _YEAR_RE.search(href)
        if m:
            years[m.group(1)] = href
    return sorted(years.items(), key=lambda x: x[0])


def parse_year_page(fetcher: Fetcher, year_url: str, year: str) -> List[Dict[str, str]]:
    res = fetcher.get(year_url)
    soup = BeautifulSoup(res.text, "lxml")

    # Many of these pages have a table with state names and PDF links
    rows: List[Dict[str, str]] = []

    # Prefer tables
    tables = soup.find_all("table")
    if tables:
        for tr in tables[0].find_all("tr"):
            tds = tr.find_all(["td", "th"])
            if not tds:
                continue
            name = tds[0].get_text(" ", strip=True)
            if not name or name.lower() in {"state", "title"}:
                continue
            pdf_url = ""
            a = tr.find("a", href=True)
            if a:
                href = urljoin(year_url, a.get("href") or "")
                if _PDF_RE.search(href):
                    pdf_url = href
            rows.append({"title": name, "year": year, "pdf": pdf_url})
    else:
        # Fallback: parse any PDF links and guess label from link text
        for a in soup.select("a[href$='.pdf'], a[href$='.PDF']"):
            href = urljoin(year_url, a.get("href") or "")
            name = a.get_text(" ", strip=True) or href.split("/")[-1]
            rows.append({"title": name, "year": year, "pdf": href})

    # De-dup
    uniq = {}
    for r in rows:
        uniq[(r["title"], r["year"])] = r
    return list(uniq.values())


def generate_crop_progress_and_condition(fetcher: Fetcher, base: str) -> Dict[str, List[Dict[str, str]]]:
    out: Dict[str, List[Dict[str, str]]] = {}
    for year, year_url in discover_year_pages(fetcher, base):
        out[year] = parse_year_page(fetcher, year_url, year)
    return out
