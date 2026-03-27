# NASS Sitemap Crawler

A small Python crawler that generates a URL inventory and internal link graph for a website. It is primarily used for migration and content inventory work.

---

## What it generates

When you run the crawler, it produces these files (by default in the current working directory):

### `nass_sitemap_urls.json`
List of fetched URLs and basic metadata:
- `url` (normalized URL key)
- `status` (HTTP status code)
- `content_type` (from the `Content-Type` header)
- `final_url` (after redirects)
- `error` (request or parse error, if any)

### `nass_sitemap_edges.csv`
Streaming link graph in CSV form:
- Columns: `parent_url,child_url`
- Each row means `parent_url` contains a link to `child_url`

### `nass_sitemap_children.json`
Convenience JSON map built at the end of the run:
- `parent_url -> [child_url, ...]`

> Note: this is built by reading `edges.csv` into memory.

### `nass_sitemap_state.json`
Checkpoint/resume state file:
- queue
- seen set
- stats
- accumulated URL metadata

---

## Output file naming

You can change output filenames with:
- `--out-prefix` (changes the `nass_sitemap_*` prefix)
- `--state-file` (sets the state/checkpoint filename)

---

## Requirements

- Python 3.9+ (recommended: 3.11+)
- pip

---

## Install

```bash
python -m venv .venv

# Windows (PowerShell):
.venv\Scripts\activate

# macOS / Linux:
source .venv/bin/activate

python -m pip install --upgrade pip
pip install -r requirements.txt
````

---

## Running the crawler

### 1) Basic run (NASS default)

Runs with the default base URL and default seed behavior.

```bash
python build_sitemap.py
```

### 2) Crawl a different base URL

```bash
python build_sitemap.py --base https://www.nass.usda.gov/
```

### 3) Add extra seed URLs (repeatable)

Use this if you have important pages that aren’t reliably discoverable from the default seed(s).

```bash
python build_sitemap.py \
  --base https://www.nass.usda.gov/ \
  --seed https://www.nass.usda.gov/Some/Deep/Page/ \
  --seed https://www.nass.usda.gov/Another/Page/
```

### 4) Increase crawl size / tune politeness

Increase the hard cap and tune delay/timeout for production-friendly crawling.

```bash
python build_sitemap.py \
  --base https://www.nass.usda.gov/ \
  --max-pages 20000 \
  --delay 0.25 \
  --timeout 30
```

### 5) Excluding paths (repeatable)

`--exclude` matches against the URL path (not the full URL). Repeat `--exclude` for multiple patterns.

```bash
python build_sitemap.py \
  --exclude '^/search/?' \
  --exclude '^/admin/?'
```

### 6) Query-string behavior

#### 6a) Keep specific query keys (while stripping everything else)

By default, query strings are stripped. This keeps only specific keys (repeatable).

```bash
python build_sitemap.py --keep-query-key page --keep-query-key id
```

#### 6b) Keep all query strings (not recommended for large sites)

```bash
python build_sitemap.py --no-strip-query
```

### 7) Resume an interrupted crawl

If you stop the crawler (Ctrl+C) or it crashes, resume using the same state file.

```bash
python build_sitemap.py --resume --state-file nass_sitemap_state.json
```

**Notes:**

* `edges.csv` is appended to when resuming.
* The crawler restores queue, seen, and URLs from the state file.

### 8) Logging / progress

#### 8a) Control progress frequency

```bash
python build_sitemap.py --log-level INFO --progress-every 50
```

#### 8b) Verbose mode (logs each fetch)

```bash
python build_sitemap.py --verbose
```

#### 8c) Write logs to a file

```bash
python build_sitemap.py --log-file crawl.log
```

### 9) Skip building `children.json`

Building `children.json` reads the whole `edges.csv` into memory. Use this if you only need `urls.json` + `edges.csv`.

```bash
python build_sitemap.py --no-children
```

---

## Full CLI help

```bash
python build_sitemap.py --help
```

---

## Tips

* For very large sites, keep `--strip-query` enabled and use `--exclude` to avoid search pages or user-specific areas.
* If you are crawling a production site, be polite with `--delay` and consider coordinating with the site owners.