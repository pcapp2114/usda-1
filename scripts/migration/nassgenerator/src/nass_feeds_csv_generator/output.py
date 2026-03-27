from __future__ import annotations

import csv
import json
import logging
from dataclasses import asdict
from pathlib import Path
from typing import Any, Dict, Iterable, List, Sequence

from .drupal_feeds import FeedType
from .utils import ensure_required, utc_iso

log = logging.getLogger("output")


def write_meta(feed_dir: Path, feed_type: FeedType, extra: Dict[str, Any]) -> None:
    feed_dir.mkdir(parents=True, exist_ok=True)
    meta = {
        "generated_at": utc_iso(),
        "feed_type": asdict(feed_type),
        **extra,
    }
    (feed_dir / "meta.json").write_text(json.dumps(meta, indent=2, sort_keys=True), encoding="utf-8")


def write_csv(path: Path, rows: List[Dict[str, Any]], header: Sequence[str]) -> None:
    path.parent.mkdir(parents=True, exist_ok=True)
    with path.open("w", newline="", encoding="utf-8") as f:
        w = csv.DictWriter(f, fieldnames=list(header), extrasaction="ignore")
        w.writeheader()
        for r in rows:
            w.writerow({k: ("" if r.get(k) is None else r.get(k)) for k in header})


def validate_rows(feed_type: FeedType, rows: List[Dict[str, Any]]) -> List[Dict[str, str]]:
    required = feed_type.required_sources or []
    out: List[Dict[str, str]] = []
    for r in rows:
        out.append(ensure_required(r, required))
    return out


def header_for(feed_type: FeedType, rows: List[Dict[str, Any]]) -> List[str]:
    # Prefer exact required_sources order, then append any extra columns observed.
    required = list(feed_type.required_sources or [])
    extras = []
    seen = set(required)
    for r in rows:
        for k in r.keys():
            if k not in seen:
                extras.append(k)
                seen.add(k)
    return required + extras
