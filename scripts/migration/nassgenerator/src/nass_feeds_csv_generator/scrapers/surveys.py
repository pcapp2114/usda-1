from __future__ import annotations

import logging
from typing import Dict, List
from urllib.parse import urljoin

from bs4 import BeautifulSoup  # type: ignore

from ..http_client import Fetcher

log = logging.getLogger("scraper.surveys")


def discover_survey_pages(fetcher: Fetcher, base: str) -> Dict[str, str]:
    index = urljoin(base, "/Surveys/Guide_to_NASS_Surveys/index.php")
    res = fetcher.get(index)
    soup = BeautifulSoup(res.text, "lxml")
    pages: Dict[str, str] = {}
    for a in soup.select("a[href]"):
        href = urljoin(index, a.get("href") or "")
        if "/Surveys/Guide_to_NASS_Surveys/" in href and href.endswith("/index.php"):
            key = href.split("/Guide_to_NASS_Surveys/")[-1].split("/")[0]
            if key and key.lower() not in {"index.php", ""}:
                pages[key] = href
    return pages


def _extract_tab_like_sections(soup: BeautifulSoup) -> List[str]:
    # Try common bootstrap tabs
    tab_panes = soup.select(".tab-content .tab-pane") or soup.select(".tab-pane") or []
    sections: List[str] = []
    for p in tab_panes:
        html = str(p)
        if html.strip():
            sections.append(html)
    if sections:
        return sections

    # Fallback: split by headings in main content
    main = soup.find("main") or soup.find(id="content") or soup.find("body")
    if not main:
        return [str(soup)]
    # Split by h2
    parts: List[str] = []
    current: List[str] = []
    for el in main.descendants:
        if getattr(el, "name", None) == "h2":
            if current:
                parts.append("".join(current))
                current = []
        if getattr(el, "name", None) in {"p", "div", "ul", "ol", "table", "h2", "h3"}:
            current.append(str(el))
    if current:
        parts.append("".join(current))
    return [p for p in parts if p.strip()]


def generate_surveys(fetcher: Fetcher, base: str) -> List[Dict[str, str]]:
    pages = discover_survey_pages(fetcher, base)
    rows: List[Dict[str, str]] = []
    for key, url in sorted(pages.items()):
        try:
            res = fetcher.get(url)
        except Exception as e:
            log.warning("Survey fetch failed: %s (%s)", url, e)
            continue
        soup = BeautifulSoup(res.text, "lxml")
        title_el = soup.find("h1") or soup.find("h2")
        survey_name = title_el.get_text(" ", strip=True) if title_el else key.replace("_", " ")

        sections = _extract_tab_like_sections(soup)
        # Feed expects 7 tab_content columns; pad or trim.
        padded = (sections + [""] * 7)[:7]

        row = {
            "survey_name": survey_name,
            "tab_content_1": padded[0],
            "tab_content_2": padded[1],
            "tab_content_3": padded[2],
            "tab_content_4": padded[3],
            "tab_content_5": padded[4],
            "tab_content_6": padded[5],
            "tab_content_7": padded[6],
            # helpful extras
            "referring_url": url,
        }
        rows.append(row)
    return rows
