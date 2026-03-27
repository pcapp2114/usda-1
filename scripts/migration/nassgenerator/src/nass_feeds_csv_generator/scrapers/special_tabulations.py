from __future__ import annotations

import logging
import re
from collections import defaultdict
from typing import Dict, List
from urllib.parse import urljoin

from bs4 import BeautifulSoup  # type: ignore

from ..http_client import Fetcher
from ..utils import extract_date, first_nonempty

log = logging.getLogger("scraper.special_tabulations")

_RECORDS_RE = re.compile(r"data-lab-records\d+\.php$", re.IGNORECASE)


def _discover_record_pages(fetcher: Fetcher, base: str) -> List[str]:
    root = urljoin(base, "/Data_and_Statistics/Special_Tabulations/Completed_Tabulations/")
    # Try index.php, and fall back to root directory listing
    candidates = [urljoin(root, "index.php"), root]
    pages = set()
    for c in candidates:
        try:
            res = fetcher.get(c)
        except Exception:
            continue
        soup = BeautifulSoup(res.text, "lxml")
        for a in soup.select("a[href]"):
            href = urljoin(c, a.get("href") or "")
            if _RECORDS_RE.search(href):
                pages.add(href)
    if not pages:
        # Known common file
        pages.add(urljoin(root, "data-lab-records6.php"))
    return sorted(pages)


def _normalize_header(h: str) -> str:
    h = (h or "").strip().lower()
    h = re.sub(r"\s+", " ", h)
    return h


def _row_from_cells(headers: List[str], cells: List[str]) -> Dict[str, str]:
    data = {headers[i]: cells[i] for i in range(min(len(headers), len(cells)))}

    # Map likely header labels to required Feeds sources
    # Required: description,file_title,geography,reference_num,release_date,request_contact,requesting_org,tab_id,title,year
    tab_id = first_nonempty(data.get("id"), data.get("tab id"), data.get("tab_id"), data.get("number"))

    file_title = first_nonempty(data.get("file title"), data.get("file"), data.get("file_name"))
    geography = first_nonempty(data.get("geography"), data.get("area"))

    requesting_org = first_nonempty(data.get("requesting organization"), data.get("requesting org"), data.get("organization"))

    description = first_nonempty(data.get("description"), data.get("details"))

    reference_num = first_nonempty(data.get("reference number"), data.get("reference #"), data.get("reference"), data.get("ref"))

    release_date = first_nonempty(data.get("release date"), data.get("date"), "")
    # normalize to MM/DD/YYYY where possible
    release_date_norm = extract_date(release_date or "") or release_date

    request_contact = first_nonempty(data.get("request contact"), data.get("contact"), data.get("contacts"))

    # Year sometimes appears as a column or is embedded in description/table group
    year = first_nonempty(data.get("year"), "")

    # Title: try explicit title, else build from id + file_title
    title = first_nonempty(data.get("title"), "")
    if not title and tab_id and file_title:
        title = f"{tab_id}-{file_title}"
    elif not title and file_title:
        title = file_title

    row = {
        "tab_id": tab_id,
        "requesting_org": requesting_org,
        "geography": geography,
        "file_title": file_title,
        "description": description,
        "reference_num": reference_num,
        "release_date": release_date_norm,
        "request_contact": request_contact,
        "title": title,
        "year": year,
    }
    return row


def generate_special_tabulations(fetcher: Fetcher, base: str) -> Dict[str, List[Dict[str, str]]]:
    """Returns dict year->rows (one CSV per year)."""
    pages = _discover_record_pages(fetcher, base)

    by_year: Dict[str, List[Dict[str, str]]] = defaultdict(list)

    for page_url in pages:
        res = fetcher.get(page_url)
        soup = BeautifulSoup(res.text, "lxml")
        table = soup.find("table")
        if not table:
            log.warning("No table found on %s", page_url)
            continue

        header_cells = table.find("tr").find_all(["th", "td"])
        headers = [_normalize_header(c.get_text(" ", strip=True)) for c in header_cells]

        for tr in table.find_all("tr")[1:]:
            cells = [c.get_text(" ", strip=True) for c in tr.find_all(["td", "th"])]
            if not cells or all(not c for c in cells):
                continue
            row = _row_from_cells(headers, cells)
            # If year column missing, try to infer from page itself
            if not row.get("year"):
                m = re.search(r"(19\d{2}|20\d{2})", soup.get_text(" ", strip=True))
                if m:
                    row["year"] = m.group(1)
            if not row.get("year"):
                row["year"] = "unknown"

            row["source_page"] = page_url
            by_year[str(row["year"])].append(row)

    # De-dup within each year by tab_id
    out = {}
    for year, rows in by_year.items():
        uniq = {}
        for r in rows:
            key = r.get("tab_id") or r.get("title") or str(hash(str(r)))
            uniq[key] = r
        out[year] = list(uniq.values())
    return out
