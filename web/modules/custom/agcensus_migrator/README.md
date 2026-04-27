# Ag Census Migrator

This package gives you a starting point for migrating Census of Agriculture landing pages into Drupal using one content type plus nested Paragraphs.

## What it creates

### Node type
- `ag_census_page`

### Node fields
- `field_census_year`
- `field_source_variant` (`current`, `publication`, `archive`)
- `field_source_url`
- `field_release_date`
- `field_summary`
- `field_legacy_html`
- `field_sections` → paragraph references

### Paragraph bundles
1. `agc_section`
   - `field_heading`
   - `field_intro`
   - `field_items`

2. `agc_resource_item`
   - `field_heading`
   - `field_intro`
   - `field_group_label`
   - `field_item_release_date`
   - `field_links`

3. `agc_action_link`
   - `field_heading`
   - `field_link_url`
   - `field_file_asset`
   - `field_file_format`

## Why this model

The current Census landing page includes quick links, historical year selectors, and current-data entry points. The 2022 publication page has grouped publication sections and release dates. The 2012 archive page is flatter and more link-list oriented. Those three patterns are visible on the live site and archive pages. citeturn794249view0turn210282view1turn278048view0

A single long rich text field would preserve HTML but would make filtering, theming, and reusing content difficult. The nested paragraph approach keeps section headings, resource items, release dates, and download links discrete, which fits the structure of the 2022 and 2012 pages especially well. citeturn210282view1turn278048view1

## Suggested workflow

1. Save source HTML locally.
2. Run `scripts/extract_agcensus.py` to normalize it into JSON.
3. Install the Drupal module.
4. Run:
   ```bash
   drush en agcensus_migrator -y
   drush cr
   drush agcensus:import /path/to/normalized.json
   ```
5. Review imported nodes and adjust section parsing rules if a source page has unusual markup.

## Notes

- The Python extractor is heuristic. It is meant to get you 70–90% of the way there for the three known page families.
- The importer can optionally download linked PDFs, TXTs, CSVs, and spreadsheets into managed Drupal files.
- For production, you may want to add one or both of these next:
  - a true Migrate API source/process/destination plugin chain
  - a site-specific normalizer per page family (`current`, `publication`, `archive`)
