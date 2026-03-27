# NASS → Drupal Feeds CSV Generator

This repository generates **Feeds-compatible CSVs** for the `nass.usda.gov` Drupal migration.

It is designed to:
- **Scrape** the same NASS pages/sections your legacy CSVs came from,
- **Emit CSVs per Drupal Feeds feed type**, using the **exact input columns** the feed type expects,
- **Organize output** into predictable folders and filenames, and
- **Validate** that generated rows contain all required columns from the Drupal Feeds configuration.

> Independence note: this project does **not** import or depend on any external “NASS crawler” repository.
> It contains its own HTTP layer and scraping logic.

---

## What this script reads from Drupal (and why)

Feeds feed types (and their expected CSV columns) are stored in the Drupal database as serialized config.
This generator can load them directly from a `db.sql.gz` dump:

- Extracts `feeds.feed_type.*` rows from `cache_config`
- Decodes the hex-encoded PHP-serialized blob
- Builds a minimal feed-type definition:
  - `feed_type_id`
  - `label`
  - `processor` / bundle (destination)
  - `required_sources` (CSV columns)
  - mappings (for reference and debugging)

That means the generator stays aligned with your **actual Drupal Feeds configuration**.

---

## Quick start

### 1) Create a virtualenv and install deps
```bash
python -m venv .venv
source .venv/bin/activate  # Windows: .venv\Scripts\activate
pip install -r requirements.txt
```

### 2) Run the generator
```bash
python -m nass_feeds_csv_generator.generate \
  --base https://www.nass.usda.gov \
  --db-dump /path/to/db.sql.gz \
  --out ./out \
  --feeds state_releases,state_releases_filtered,crop_progress_and_condition_grap,state_census
```

### 3) Generate everything that this repo supports
```bash
python -m nass_feeds_csv_generator.generate \
  --base https://www.nass.usda.gov \
  --db-dump /path/to/db.sql.gz \
  --out ./out \
  --feeds ALL
```

---

## Output structure

Output is organized by feed type:

```
out/
  state_releases/
    meta.json
    Alabama - State Releases.csv
    Alaska - State Releases.csv
    ...
  crop_progress_and_condition_grap/
    meta.json
    2005 - 2005.csv
    2006 - 2006.csv
    ...
```

Each feed folder contains:
- `meta.json`: timestamp, seed URLs, and the feed schema used for validation
- One or more CSVs

---

## Feed types implemented in this repo

High-volume / primary migration feeds:
- `state_releases`
- `state_releases_filtered`
- `crop_progress_and_condition_grap`
- `state_census`
- `national_census`
- `state_and_county_profiles`
- `congressional_district_profiles`
- `race_ethnicity_and_gender_profil`
- `ranking_of_market_value_of_ag_pr`
- `special_tabulations`
- `news_releases`
- `asb_notice`
- `survey`
- `report`
- `landing_page` (requires seed URL list; see below)

### Feed types not implemented (stubs only)
If your Drupal DB contains additional feed types not listed above, the CLI will warn and skip them.
You can add new scrapers by copying the patterns in `src/nass_feeds_csv_generator/scrapers/`.

---

## Config file for seed URLs (landing pages, reports)

Some feeds can’t be safely auto-discovered without a crawl (risk: tens of thousands of pages).
For these, provide seed URLs in `config/seeds.yaml`.

Example:

```yaml
landing_page:
  urls:
    - https://www.nass.usda.gov/About_NASS/index.php
    - https://www.nass.usda.gov/Surveys/index.php
report:
  urls:
    - https://www.nass.usda.gov/research/reports/index.php
```

Then run:

```bash
python -m nass_feeds_csv_generator.generate \
  --base https://www.nass.usda.gov \
  --db-dump /path/to/db.sql.gz \
  --out ./out \
  --feeds landing_page,report \
  --seeds ./config/seeds.yaml
```

---

## Notes on “problematic” Feeds configs (FID vs URL)

A couple of feed types in your DB reference files by `fid` (Drupal internal file ID).
A CSV generator **cannot** know FIDs before import.

This repo will still generate CSVs for those feeds, but:
- it fills the file-reference column with the **URL**, and
- logs a warning explaining you likely need to adjust the Feeds mapping to reference-by `filename` (or allow file download).

See console output for those warnings.

---

## Troubleshooting

- **403 / throttling**: add `--delay 0.25` or higher.
- **Changed HTML layout**: NASS pages are mostly stable, but occasionally templates differ.
  This repo uses resilient heuristics (tables + link harvesting). If a page breaks parsing, capture the HTML and add a special-case parser.
- **Validation errors**: the generator checks each row contains all `required_sources` columns from Drupal.
  Missing data will be emitted as empty strings, and a warning will be logged with the URL.