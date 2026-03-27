from __future__ import annotations

import logging
import re
from collections import defaultdict
from typing import Dict, List, Tuple
from urllib.parse import urljoin, urlparse

from bs4 import BeautifulSoup  # type: ignore

from ..http_client import Fetcher
from ..utils import US_STATE_ABBR, US_STATE_NUM, extract_date

log = logging.getLogger("scraper.agcensus")

_PDF_RE = re.compile(r"\.pdf$", re.IGNORECASE)

# Example: st01_1_0001_0001.pdf
_STATE_TABLE_RE = re.compile(r"st(?P<num>\d{2})_(?P<chapter>\d+)_(?P<table>\d{4})_\d{4}\.pdf", re.IGNORECASE)


def _soup(fetcher: Fetcher, url: str) -> BeautifulSoup:
    res = fetcher.get(url)
    return BeautifulSoup(res.text, "lxml")


def generate_state_and_county_profiles(fetcher: Fetcher, base: str) -> Dict[str, List[Dict[str, str]]]:
    """County profiles: one CSV per state with columns url|county|state."""
    index = urljoin(base, "/Publications/AgCensus/2017/Online_Resources/County_Profiles/index.php")
    soup = _soup(fetcher, index)

    # Discover state folder links
    state_pages: Dict[str, str] = {}
    for a in soup.select("a[href]"):
        href = urljoin(index, a.get("href") or "")
        if "/County_Profiles/" in href and href.endswith("/index.php"):
            # .../County_Profiles/Alabama/index.php
            state = href.split("/County_Profiles/")[-1].split("/")[0]
            state_pages[state] = href

    by_state: Dict[str, List[Dict[str, str]]] = defaultdict(list)

    for state, url in state_pages.items():
        s = _soup(fetcher, url)
        for a in s.select("a[href]"):
            pdf = urljoin(url, a.get("href") or "")
            if not _PDF_RE.search(pdf):
                continue
            county = a.get_text(" ", strip=True) or pdf.split("/")[-1]
            by_state[state].append({"url": pdf, "county": county, "state": state})

        # De-dup
        uniq = {}
        for r in by_state[state]:
            uniq[r["url"]] = r
        by_state[state] = list(uniq.values())

    return dict(by_state)


def generate_race_ethnicity_gender_profiles(fetcher: Fetcher, base: str) -> Dict[str, List[Dict[str, str]]]:
    """Race/Ethnicity/Gender profiles: one CSV per state with columns title|pdf|state."""
    index = urljoin(base, "/Publications/AgCensus/2017/Online_Resources/Race,_Ethnicity_and_Gender_Profiles/index.php")
    soup = _soup(fetcher, index)

    state_pages: Dict[str, str] = {}
    for a in soup.select("a[href]"):
        href = urljoin(index, a.get("href") or "")
        if "/Race,_Ethnicity_and_Gender_Profiles/" in href and href.endswith("/index.php"):
            state = href.split("/Race,_Ethnicity_and_Gender_Profiles/")[-1].split("/")[0]
            state_pages[state] = href

    by_state: Dict[str, List[Dict[str, str]]] = defaultdict(list)
    for state, url in state_pages.items():
        s = _soup(fetcher, url)
        for a in s.select("a[href]"):
            pdf = urljoin(url, a.get("href") or "")
            if not _PDF_RE.search(pdf):
                continue
            title = a.get_text(" ", strip=True) or pdf.split("/")[-1]
            by_state[state].append({"title": title, "pdf": pdf, "state": state})

        uniq = {}
        for r in by_state[state]:
            uniq[r["pdf"]] = r
        by_state[state] = list(uniq.values())

    return dict(by_state)


def generate_congressional_district_profiles(fetcher: Fetcher, base: str) -> Dict[str, List[Dict[str, str]]]:
    """Congressional district profiles: one CSV per state with columns url|filename|state."""
    index = urljoin(base, "/Publications/AgCensus/2017/Online_Resources/Congressional_District_Profiles/index.php")
    soup = _soup(fetcher, index)

    by_state: Dict[str, List[Dict[str, str]]] = defaultdict(list)

    for a in soup.select("a[href]"):
        pdf = urljoin(index, a.get("href") or "")
        if not _PDF_RE.search(pdf):
            continue
        title = a.get_text(" ", strip=True) or pdf.split("/")[-1]
        # State is usually first word in link text (e.g., "Alabama 1st District")
        state_guess = title.split()[0].strip()
        # Normalize (case)
        state = None
        for st in US_STATE_ABBR.keys():
            if title.lower().startswith(st.lower()):
                state = st
                break
        if not state:
            state = state_guess
        by_state[state].append({"url": pdf, "filename": title, "state": state})

    # De-dup
    out = {}
    for state, rows in by_state.items():
        uniq = {}
        for r in rows:
            uniq[r["url"]] = r
        out[state] = list(uniq.values())
    return out


def _discover_state_level_chapter_dirs(fetcher: Fetcher, base: str) -> List[str]:
    root = urljoin(base, "/Publications/AgCensus/2017/Full_Report/")
    soup = _soup(fetcher, root)
    dirs = []
    for a in soup.select("a[href]"):
        href = urljoin(root, a.get("href") or "")
        if "/Full_Report/" not in href:
            continue
        if "_State_Level" in href and href.endswith("/index.php"):
            # directory index
            dirs.append(href.rsplit("/", 1)[0] + "/")
        if "_State_Level/" in href and href.endswith("/") and "index.php" not in href:
            dirs.append(href)
    # De-dup
    uniq = sorted(set(dirs))
    return uniq


def _human_category_from_dir(dir_url: str) -> str:
    # Example: .../Volume_1,_Chapter_1_State_Level/
    part = dir_url.rstrip("/").split("/")[-1]
    # Replace underscores with spaces and clean
    clean = part.replace("_", " ")
    clean = clean.replace(" ,", ",")
    # Make it closer to legacy labels:
    m = re.search(r"Chapter\s+(\d+)", clean)
    if m and "State Level" in clean:
        return f"Chapter {m.group(1)}: State Level Data"
    if "US" in clean:
        return "National"
    return clean


def _discover_state_pages_in_chapter(fetcher: Fetcher, chapter_dir_url: str) -> Dict[str, str]:
    # State pages are subfolders, each with index.php
    soup = _soup(fetcher, urljoin(chapter_dir_url, "index.php"))
    out = {}
    for a in soup.select("a[href]"):
        href = urljoin(chapter_dir_url, a.get("href") or "")
        if href.endswith("/index.php") and href.startswith(chapter_dir_url):
            state = href.replace(chapter_dir_url, "").split("/")[0]
            if state and state[0].isalpha():
                out[state] = href
    return out


def generate_state_census(fetcher: Fetcher, base: str) -> Dict[str, List[Dict[str, str]]]:
    """Generate state_census media rows:
       columns: table|url|filename|state|category|query
    """
    chapter_dirs = _discover_state_level_chapter_dirs(fetcher, base)
    if not chapter_dirs:
        # fallback to chapter 1 only (common)
        chapter_dirs = [urljoin(base, "/Publications/AgCensus/2017/Full_Report/Volume_1,_Chapter_1_State_Level/")]

    by_state: Dict[str, List[Dict[str, str]]] = defaultdict(list)

    for ch_dir in chapter_dirs:
        category = _human_category_from_dir(ch_dir)
        state_pages = _discover_state_pages_in_chapter(fetcher, ch_dir)
        for state, state_index in state_pages.items():
            soup = _soup(fetcher, state_index)
            for a in soup.select("a[href]"):
                pdf_url = urljoin(state_index, a.get("href") or "")
                if not _PDF_RE.search(pdf_url):
                    continue
                fname = a.get_text(" ", strip=True) or pdf_url.split("/")[-1]

                m = _STATE_TABLE_RE.search(pdf_url.split("/")[-1])
                if not m:
                    continue
                chapter = int(m.group("chapter"))
                table_num = int(m.group("table"))

                abbr = US_STATE_ABBR.get(state, "")
                query = ""
                if abbr:
                    query = f"https://www.nass.usda.gov/Quick_Stats/CDQT/chapter/{chapter}/table/{table_num}/state/{abbr}"

                by_state[state].append(
                    {
                        "table": table_num,
                        "url": pdf_url,
                        "filename": fname,
                        "state": state,
                        "category": category,
                        "query": query,
                    }
                )

    # De-dup by (category, table, url)
    out = {}
    for state, rows in by_state.items():
        uniq = {}
        for r in rows:
            uniq[(r["category"], r["table"], r["url"])] = r
        out[state] = list(uniq.values())
    return out


def generate_national_census(fetcher: Fetcher, base: str) -> List[Dict[str, str]]:
    """Generate national_census rows (single CSV):
       columns: table|census|year|pdf_url|query_url_text|title
    """
    year = "2017"
    index = urljoin(base, "/Publications/AgCensus/2017/Full_Report/Volume_1,_Chapter_1_US/index.php")
    soup = _soup(fetcher, index)

    rows: List[Dict[str, str]] = []
    for a in soup.select("a[href]"):
        pdf_url = urljoin(index, a.get("href") or "")
        if not _PDF_RE.search(pdf_url):
            continue
        title = a.get_text(" ", strip=True) or pdf_url.split("/")[-1]
        m = _STATE_TABLE_RE.search(pdf_url.split("/")[-1])
        if not m:
            continue
        chapter = int(m.group("chapter"))
        table_num = int(m.group("table"))
        query = f"https://www.nass.usda.gov/Quick_Stats/CDQT/chapter/{chapter}/table/{table_num}/state/US"
        rows.append(
            {
                "table": table_num,
                "census": "National",
                "year": int(year),
                "pdf_url": pdf_url,
                "query_url_text": query,
                "title": title,
            }
        )

    # De-dup by pdf_url
    uniq = {}
    for r in rows:
        uniq[r["pdf_url"]] = r
    return list(uniq.values())
