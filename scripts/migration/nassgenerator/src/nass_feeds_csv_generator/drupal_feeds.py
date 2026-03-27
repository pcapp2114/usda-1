from __future__ import annotations

import gzip
import json
import logging
import re
from dataclasses import dataclass
from typing import Any, Dict, List, Optional, Tuple

from phpserialize import loads as php_loads  # type: ignore


log = logging.getLogger("drupal_feeds")


@dataclass
class FeedType:
    feed_type_id: str
    label: str
    processor: str
    bundle: Optional[str]
    fetcher: str
    parser: str
    required_sources: List[str]
    mappings: List[Dict[str, Any]]


_FEEDS_CID_RE = re.compile(r"\('(?P<cid>feeds\.feed_type\.[^']+)',\s*(?P<data>0x[0-9A-Fa-f]+)\b")


def _to_py(obj: Any) -> Any:
    """Convert phpserialize output (bytes, OrderedDict-like) to plain Python types."""
    if isinstance(obj, bytes):
        try:
            return obj.decode("utf-8")
        except Exception:
            return obj.decode("latin1", errors="replace")
    if isinstance(obj, dict):
        return { _to_py(k): _to_py(v) for k, v in obj.items() }
    if isinstance(obj, (list, tuple)):
        return [ _to_py(v) for v in obj ]
    return obj


def _decode_php_serialized_hex(hex_blob: str) -> Any:
    if hex_blob.startswith("0x"):
        hex_blob = hex_blob[2:]
    raw = bytes.fromhex(hex_blob)
    parsed = php_loads(raw, decode_strings=False)
    return _to_py(parsed)


def _extract_required_sources(feed_cfg: Dict[str, Any]) -> List[str]:
    # Feeds config stores sources under parser configuration in most builds.
    parser = feed_cfg.get("parser") or {}
    pconf = parser.get("configuration") or {}
    sources = pconf.get("sources")
    if isinstance(sources, dict):
        # sources is a map {source_key: label}
        return sorted([str(k) for k in sources.keys()])
    # Fallback: infer from mappings
    required = set()
    mappings = ((feed_cfg.get("processor") or {}).get("configuration") or {}).get("mappings") or []
    for m in mappings:
        mp = m.get("map") or {}
        for k, v in mp.items():
            if isinstance(v, str):
                required.add(v)
    # Remove non-source keys
    required.discard("pathauto")
    required.discard("alias")
    return sorted(required)


def _summarize_feed(feed_cfg: Dict[str, Any], feed_type_id: str) -> FeedType:
    label = str(feed_cfg.get("label") or feed_type_id)
    processor = str((feed_cfg.get("processor") or {}).get("plugin_id") or "")
    bundle = None
    proc_conf = (feed_cfg.get("processor") or {}).get("configuration") or {}
    if isinstance(proc_conf, dict):
        bundle = proc_conf.get("bundle") or proc_conf.get("type")
        if bundle is not None:
            bundle = str(bundle)
    fetcher = str((feed_cfg.get("fetcher") or {}).get("plugin_id") or "")
    parser = str((feed_cfg.get("parser") or {}).get("plugin_id") or "")
    mappings = proc_conf.get("mappings") or []
    if not isinstance(mappings, list):
        mappings = []
    required_sources = _extract_required_sources(feed_cfg)
    return FeedType(
        feed_type_id=feed_type_id,
        label=label,
        processor=processor,
        bundle=bundle,
        fetcher=fetcher,
        parser=parser,
        required_sources=required_sources,
        mappings=mappings,
    )


def load_feed_types_from_db_dump(db_sql_gz_path: str) -> Dict[str, FeedType]:
    """Extract Feeds feed types from a Drupal SQL dump (gzipped).

    The dump contains rows in `cache_config` where:
      cid = 'feeds.feed_type.<machine_name>'
      data = 0x... hex of PHP-serialized config array

    Returns a dict keyed by feed_type_id (machine_name).
    """
    feed_types: Dict[str, FeedType] = {}

    with gzip.open(db_sql_gz_path, "rt", errors="replace") as f:
        for line in f:
            if "feeds.feed_type." not in line:
                continue
            m = _FEEDS_CID_RE.search(line)
            if not m:
                continue
            cid = m.group("cid")
            data_hex = m.group("data")
            try:
                cfg = _decode_php_serialized_hex(data_hex)
                # cid looks like feeds.feed_type.state_releases
                feed_type_id = cid.split("feeds.feed_type.", 1)[1]
                feed_types[feed_type_id] = _summarize_feed(cfg, feed_type_id)
            except Exception as e:
                log.warning("Failed parsing %s: %s", cid, e)

    if not feed_types:
        raise RuntimeError(
            "No feeds.feed_type.* configs found in the dump. "

            "Confirm the dump includes the cache_config rows, and that it is a Drupal DB dump."
        )

    return feed_types


def load_feed_types_from_summary_json(summary_json_path: str) -> Dict[str, FeedType]:
    """Load feed types from a pre-extracted summary json.

    Expected format: list of dicts with keys matching FeedType fields.
    """
    with open(summary_json_path, "r", encoding="utf-8") as f:
        data = json.load(f)
    out: Dict[str, FeedType] = {}
    for row in data:
        ft = FeedType(
            feed_type_id=row["feed_type_id"],
            label=row.get("label") or row["feed_type_id"],
            processor=row.get("processor") or "",
            bundle=row.get("bundle"),
            fetcher=row.get("fetcher") or "",
            parser=row.get("parser") or "",
            required_sources=list(row.get("required_sources") or []),
            mappings=list(row.get("mappings") or []),
        )
        out[ft.feed_type_id] = ft
    return out
