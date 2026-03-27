from __future__ import annotations

import re
from dataclasses import dataclass
from datetime import datetime
from typing import Any, Dict, Iterable, Optional
from urllib.parse import urlparse

from dateutil import parser as dateparser


DATE_PATTERNS = [
    # Common NASS patterns
    # 11/28/2012
    re.compile(r"\b(\d{1,2}/\d{1,2}/\d{4})\b"),
    # 11-28-2012
    re.compile(r"\b(\d{1,2}-\d{1,2}-\d{4})\b"),
    # Nov. 27, 2012 or November 27, 2012
    re.compile(r"\b([A-Za-z]{3,9}\.?,?\s+\d{1,2},\s+\d{4})\b"),
]


def slugify(text: str) -> str:
    text = (text or "").strip().lower()
    text = re.sub(r"[^a-z0-9]+", "-", text)
    text = re.sub(r"-{2,}", "-", text).strip("-")
    return text


def extract_date(text: str) -> Optional[str]:
    """Try to extract a date string and normalize to MM/DD/YYYY (matching many legacy CSVs)."""
    if not text:
        return None
    for pat in DATE_PATTERNS:
        m = pat.search(text)
        if m:
            raw = m.group(1)
            try:
                dt = dateparser.parse(raw, fuzzy=True)
                return dt.strftime("%m/%d/%Y")
            except Exception:
                return raw
    return None


def url_path(url: str) -> str:
    try:
        return urlparse(url).path
    except Exception:
        return ""


def first_nonempty(*vals: Optional[str]) -> str:
    for v in vals:
        if v is not None and str(v).strip() != "":
            return str(v).strip()
    return ""


US_STATE_ABBR = {
    "Alabama": "AL", "Alaska": "AK", "Arizona": "AZ", "Arkansas": "AR", "California": "CA",
    "Colorado": "CO", "Connecticut": "CT", "Delaware": "DE", "Florida": "FL", "Georgia": "GA",
    "Hawaii": "HI", "Idaho": "ID", "Illinois": "IL", "Indiana": "IN", "Iowa": "IA",
    "Kansas": "KS", "Kentucky": "KY", "Louisiana": "LA", "Maine": "ME", "Maryland": "MD",
    "Massachusetts": "MA", "Michigan": "MI", "Minnesota": "MN", "Mississippi": "MS", "Missouri": "MO",
    "Montana": "MT", "Nebraska": "NE", "Nevada": "NV", "New Hampshire": "NH", "New Jersey": "NJ",
    "New Mexico": "NM", "New York": "NY", "North Carolina": "NC", "North Dakota": "ND", "Ohio": "OH",
    "Oklahoma": "OK", "Oregon": "OR", "Pennsylvania": "PA", "Rhode Island": "RI", "South Carolina": "SC",
    "South Dakota": "SD", "Tennessee": "TN", "Texas": "TX", "Utah": "UT", "Vermont": "VT",
    "Virginia": "VA", "Washington": "WA", "West Virginia": "WV", "Wisconsin": "WI", "Wyoming": "WY",
    "United States": "US",
}

# Numeric FIPS-like two-digit codes used in many AgCensus PDFs (st01 = AL, st02 = AK, ...)
US_STATE_NUM = {
    "Alabama": "01", "Alaska": "02", "Arizona": "04", "Arkansas": "05", "California": "06",
    "Colorado": "08", "Connecticut": "09", "Delaware": "10", "Florida": "12", "Georgia": "13",
    "Hawaii": "15", "Idaho": "16", "Illinois": "17", "Indiana": "18", "Iowa": "19",
    "Kansas": "20", "Kentucky": "21", "Louisiana": "22", "Maine": "23", "Maryland": "24",
    "Massachusetts": "25", "Michigan": "26", "Minnesota": "27", "Mississippi": "28", "Missouri": "29",
    "Montana": "30", "Nebraska": "31", "Nevada": "32", "New Hampshire": "33", "New Jersey": "34",
    "New Mexico": "35", "New York": "36", "North Carolina": "37", "North Dakota": "38", "Ohio": "39",
    "Oklahoma": "40", "Oregon": "41", "Pennsylvania": "42", "Rhode Island": "44", "South Carolina": "45",
    "South Dakota": "46", "Tennessee": "47", "Texas": "48", "Utah": "49", "Vermont": "50",
    "Virginia": "51", "Washington": "53", "West Virginia": "54", "Wisconsin": "55", "Wyoming": "56",
    "United States": "99",
}


def ensure_required(row: Dict[str, Any], required: Iterable[str]) -> Dict[str, str]:
    out: Dict[str, str] = {}
    for k in required:
        out[k] = "" if row.get(k) is None else str(row.get(k))
    # keep extra columns too (stable for debugging)
    for k, v in row.items():
        if k not in out:
            out[k] = "" if v is None else str(v)
    return out


def utc_iso() -> str:
    return datetime.utcnow().replace(microsecond=0).isoformat() + "Z"
