from __future__ import annotations

import argparse
import logging
import sys
from pathlib import Path
from typing import Dict, List, Optional

import yaml  # type: ignore

from .drupal_feeds import FeedType, load_feed_types_from_db_dump, load_feed_types_from_summary_json
from .http_client import Fetcher
from .output import header_for, validate_rows, write_csv, write_meta
from .utils import utc_iso

from .scrapers.state_releases import generate_state_releases
from .scrapers.crop_progress import generate_crop_progress_and_condition
from .scrapers.agcensus import (
    generate_state_and_county_profiles,
    generate_race_ethnicity_gender_profiles,
    generate_congressional_district_profiles,
    generate_state_census,
    generate_national_census,
)
from .scrapers.rankings_market_value import generate_rankings_market_value
from .scrapers.special_tabulations import generate_special_tabulations
from .scrapers.newsroom import generate_newsroom
from .scrapers.surveys import generate_surveys
from .scrapers.landing_pages import generate_landing_pages
from .scrapers.reports import generate_reports


log = logging.getLogger("generator")


SUPPORTED = {
    "state_releases",
    "state_releases_filtered",
    "crop_progress_and_condition_grap",
    "state_census",
    "national_census",
    "state_and_county_profiles",
    "race_ethnicity_and_gender_profil",
    "congressional_district_profiles",
    "ranking_of_market_value_of_ag_pr",
    "special_tabulations",
    "news_releases",
    "asb_notice",
    "asb_notice_feed",
    "asb_briefings",
    "survey",
    "landing_page",
    "report",
}


def _parse_csv_list(val: str) -> List[str]:
    return [v.strip() for v in (val or "").split(",") if v.strip()]


def _load_seeds(path: Optional[str]) -> Dict[str, Dict]:
    if not path:
        return {}
    p = Path(path)
    if not p.exists():
        raise FileNotFoundError(f"Seeds file not found: {path}")
    return yaml.safe_load(p.read_text(encoding="utf-8")) or {}


def _year_range(val: str) -> List[int]:
    # e.g. 2010-2026 or 2012
    val = (val or "").strip()
    if not val:
        return []
    if "-" in val:
        a, b = val.split("-", 1)
        return list(range(int(a), int(b) + 1))
    return [int(val)]


def main(argv: Optional[List[str]] = None) -> int:
    ap = argparse.ArgumentParser(description="Generate NASS CSVs aligned to Drupal Feeds feed types.")
    ap.add_argument("--base", required=True, help="Base URL (e.g., https://www.nass.usda.gov)")
    ap.add_argument("--db-dump", help="Path to Drupal db.sql.gz (preferred) for reading feeds.feed_type configs")
    ap.add_argument("--feeds-summary", help="Optional pre-extracted feeds summary JSON (fallback)")
    ap.add_argument("--out", required=True, help="Output folder")
    ap.add_argument("--feeds", required=True, help="Comma list of feed_type_ids to generate, or ALL")
    ap.add_argument("--delay", type=float, default=0.25, help="Delay (seconds) between HTTP requests")
    ap.add_argument("--states", default="", help="Comma list of states to limit Statistics-by-State & AgCensus runs")
    ap.add_argument("--news-years", default="2010-2026", help="Year range for Newsroom scraping (e.g., 2012-2026)")
    ap.add_argument("--seeds", default=None, help="YAML file with seed URLs for landing_page/report/etc")
    ap.add_argument("--log", default="INFO", help="Log level (DEBUG,INFO,WARNING,ERROR)")
    args = ap.parse_args(argv)

    logging.basicConfig(
        level=getattr(logging, args.log.upper(), logging.INFO),
        format="%(asctime)s %(levelname)s %(name)s: %(message)s",
    )

    base = args.base.rstrip("/")
    out_root = Path(args.out)
    out_root.mkdir(parents=True, exist_ok=True)

    # Load feed types from DB config
    feed_types: Dict[str, FeedType]
    if args.db_dump:
        feed_types = load_feed_types_from_db_dump(args.db_dump)
    elif args.feeds_summary:
        feed_types = load_feed_types_from_summary_json(args.feeds_summary)
    else:
        ap.error("Provide --db-dump or --feeds-summary so we can validate CSV schemas against Drupal Feeds.")

    requested = args.feeds.strip()
    if requested.upper() == "ALL":
        selected = sorted([f for f in feed_types.keys() if f in SUPPORTED])
        # Also include supported even if not present in DB
        for f in sorted(SUPPORTED):
            if f not in selected and f in feed_types:
                selected.append(f)
    else:
        selected = _parse_csv_list(requested)

    seeds = _load_seeds(args.seeds)

    # Polite fetcher
    fetcher = Fetcher(base_delay=args.delay)

    # Run
    states = _parse_csv_list(args.states) if args.states else None
    news_years = _year_range(args.news_years)

    for feed_id in selected:
        if feed_id not in feed_types:
            log.warning("Feed type not found in Drupal config: %s (skipping)", feed_id)
            continue
        if feed_id not in SUPPORTED:
            log.warning("Feed type not supported by this generator yet: %s (skipping)", feed_id)
            continue

        ft = feed_types[feed_id]
        feed_dir = out_root / feed_id
        feed_dir.mkdir(parents=True, exist_ok=True)

        log.info("Generating feed: %s (%s)", feed_id, ft.label)

        if feed_id in {"state_releases", "state_releases_filtered"}:
            media_by_state, node_by_state = generate_state_releases(fetcher, base, states=states)
            if feed_id == "state_releases":
                write_meta(feed_dir, ft, {"base": base, "states": list(media_by_state.keys())})
                for state, rows in media_by_state.items():
                    rows_v = validate_rows(ft, rows)
                    header = header_for(ft, rows_v)
                    out_path = feed_dir / f"{state} - State Releases.csv"
                    write_csv(out_path, rows_v, header)
            else:
                write_meta(feed_dir, ft, {"base": base, "states": list(node_by_state.keys())})
                for state, rows in node_by_state.items():
                    rows_v = validate_rows(ft, rows)
                    header = header_for(ft, rows_v)
                    out_path = feed_dir / f"{state}-Releases (Delta Version).csv"
                    write_csv(out_path, rows_v, header)

        elif feed_id == "crop_progress_and_condition_grap":
            by_year = generate_crop_progress_and_condition(fetcher, base)
            write_meta(feed_dir, ft, {"base": base, "years": sorted(by_year.keys())})
            for year, rows in by_year.items():
                rows_v = validate_rows(ft, rows)
                header = header_for(ft, rows_v)
                out_path = feed_dir / f"{year} - {year}.csv"
                write_csv(out_path, rows_v, header)

        elif feed_id == "state_census":
            by_state = generate_state_census(fetcher, base)
            write_meta(feed_dir, ft, {"base": base, "states": sorted(by_state.keys())})
            for state, rows in by_state.items():
                rows_v = validate_rows(ft, rows)
                header = header_for(ft, rows_v)
                out_path = feed_dir / f"{state} - State County Census Data.csv"
                write_csv(out_path, rows_v, header)

        elif feed_id == "national_census":
            rows = generate_national_census(fetcher, base)
            write_meta(feed_dir, ft, {"base": base})
            rows_v = validate_rows(ft, rows)
            header = header_for(ft, rows_v)
            write_csv(feed_dir / "national_census.csv", rows_v, header)

        elif feed_id == "state_and_county_profiles":
            by_state = generate_state_and_county_profiles(fetcher, base)
            write_meta(feed_dir, ft, {"base": base, "states": sorted(by_state.keys())})
            for state, rows in by_state.items():
                rows_v = validate_rows(ft, rows)
                header = header_for(ft, rows_v)
                write_csv(feed_dir / f"{state} - State and county profile.csv", rows_v, header)

        elif feed_id == "race_ethnicity_and_gender_profil":
            by_state = generate_race_ethnicity_gender_profiles(fetcher, base)
            write_meta(feed_dir, ft, {"base": base, "states": sorted(by_state.keys())})
            for state, rows in by_state.items():
                rows_v = validate_rows(ft, rows)
                header = header_for(ft, rows_v)
                write_csv(feed_dir / f"{state.lower()} - {state.lower()}.csv", rows_v, header)

        elif feed_id == "congressional_district_profiles":
            by_state = generate_congressional_district_profiles(fetcher, base)
            write_meta(feed_dir, ft, {"base": base, "states": sorted(by_state.keys())})
            for state, rows in by_state.items():
                rows_v = validate_rows(ft, rows)
                header = header_for(ft, rows_v)
                write_csv(feed_dir / f"{state} - Congressional District Profiles.csv", rows_v, header)

        elif feed_id == "ranking_of_market_value_of_ag_pr":
            rows = generate_rankings_market_value(fetcher, base)
            write_meta(feed_dir, ft, {"base": base, "row_count": len(rows)})
            rows_v = validate_rows(ft, rows)
            header = header_for(ft, rows_v)
            write_csv(feed_dir / "ranking_of_market_value.csv", rows_v, header)

        elif feed_id == "special_tabulations":
            by_year = generate_special_tabulations(fetcher, base)
            write_meta(feed_dir, ft, {"base": base, "years": sorted(by_year.keys())})
            for year, rows in by_year.items():
                rows_v = validate_rows(ft, rows)
                header = header_for(ft, rows_v)
                write_csv(feed_dir / f"sp_tab_{year}.csv", rows_v, header)

        elif feed_id in {"news_releases", "asb_notice", "asb_notice_feed", "asb_briefings"}:
            rows_by_feed = generate_newsroom(fetcher, base, years=news_years)
            rows = rows_by_feed.get(feed_id, [])
            write_meta(feed_dir, ft, {"base": base, "years": news_years, "row_count": len(rows)})

            # WARNING: some configs reference by fid; we cannot know fids pre-import
            if feed_id == "asb_briefings":
                log.warning(
                    "Feed %s references a file by fid in your Drupal config. "
                    "This generator fills the reference column with the PDF URL. "
                    "You may need to adjust the Feeds mapping to reference by filename or allow file download.",
                    feed_id,
                )

            rows_v = validate_rows(ft, rows)
            header = header_for(ft, rows_v)
            # Single CSV for each feed (can be split if you want)
            write_csv(feed_dir / f"{feed_id}.csv", rows_v, header)

        elif feed_id == "survey":
            rows = generate_surveys(fetcher, base)
            write_meta(feed_dir, ft, {"base": base, "row_count": len(rows)})
            rows_v = validate_rows(ft, rows)
            header = header_for(ft, rows_v)
            write_csv(feed_dir / "ag_survey.csv", rows_v, header)

        elif feed_id == "landing_page":
            urls = ((seeds.get("landing_page") or {}).get("urls") or [])
            if not urls:
                log.warning("landing_page requires seed URLs (use --seeds config/seeds.yaml)")
                continue
            rows = generate_landing_pages(fetcher, urls)
            write_meta(feed_dir, ft, {"base": base, "row_count": len(rows), "seed_urls": urls})
            rows_v = validate_rows(ft, rows)
            header = header_for(ft, rows_v)
            write_csv(feed_dir / "landing_pages.csv", rows_v, header)

        elif feed_id == "report":
            urls = ((seeds.get("report") or {}).get("urls") or [])
            if not urls:
                log.warning("report requires seed URLs (use --seeds config/seeds.yaml)")
                continue
            rows = generate_reports(fetcher, urls)
            write_meta(feed_dir, ft, {"base": base, "row_count": len(rows), "seed_urls": urls})
            rows_v = validate_rows(ft, rows)
            header = header_for(ft, rows_v)
            write_csv(feed_dir / "reports.csv", rows_v, header)

        else:
            log.warning("No generator wired for %s (supported list may be stale)", feed_id)

    log.info("Done. Output: %s", out_root.resolve())
    return 0


if __name__ == "__main__":
    raise SystemExit(main())
