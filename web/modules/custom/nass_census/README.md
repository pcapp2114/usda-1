# NASS Census

`nass_census` is the consolidated Census module for the NASS Drupal migration.
It preserves the existing Census content model and now also contains the legacy
`census` export tooling and `agcensus_migrator` JSON import tooling.

## Merged functionality

This package now contains:

- The existing `nass_census` content model, display helpers, templates, CSS, and
  Field Group repair update hooks.
- The former `census` Drush export command and services:
  - `drush census_export`
  - `drush census:export`
  - `census.exporter`
  - `census.path_normalizer`
- The former `agcensus_migrator` Drush import command and services:
  - `drush agcensus:import`
  - `drush agci`
  - `agcensus_migrator.importer`
- Supplemental migration/reference files:
  - `schema/census-export-manifest-v1.example.json`
  - `scripts/extract_agcensus.py`
  - `data/agcensus/CensusStructure.xlsx`
  - `data/agcensus/CensusStructureSplit.xlsx`
  - `data/agcensus/nass_2017_crawl.json`
  - `data/agcensus/nass_2022_crawl.json`

The legacy service IDs and Drush command names are intentionally retained so
existing command usage does not change after the merge.

## Main content model

The baseline NASS Census model still creates and maintains:

- Node type: `census`
- Node type: `census_publication`
- Paragraph type: `census_section`
- Paragraph type: `census_group`
- Paragraph type: `census_item`
- Paragraph type: `census_action`
- Shared configurable fields, list values, paragraph references, media
  references, node references, form displays, view displays, and Field Group
  editor groupings.

## Ag Census importer model

The merged Agriculture Census importer also preserves its original model:

- Node type: `ag_census_page`
- Paragraph type: `agc_section`
- Paragraph type: `agc_resource_item`
- Paragraph type: `agc_action_link`

These machine names are not renamed because the importer code and any existing
content may rely on them.

## Install / update

For an already-enabled `nass_census` site, replace the module folder and run:

```bash
ddev drush cr
ddev drush updb -y
ddev drush cr
```

Update hook `nass_census_update_10007()` creates the merged Ag Census importer
content structures on existing installs.

## Version history

### v1.6 - Census tooling consolidation

- Merges the legacy `census` and `agcensus_migrator` modules into
  `nass_census`.
- Preserves existing service IDs and Drush command names for compatibility.
- Adds `nass_census.services.yml` and `drush.services.yml` for the merged tools.
- Adds update hook `nass_census_update_10007()` for existing enabled sites.
- Keeps the known-good v1.4/v1.5 Census content model and Field Group behavior.

### v1.5 - Version-tracking cleanup baseline

- Treats `v1.4` as the current known-good functional baseline.
- Updates version and module description metadata.
- Makes no content model, field, Field Group, paragraph, taxonomy, or update-hook
  behavior changes.

### v1.4 - Field Group rendering fix

- Adds matching `third_party_settings.field_group` metadata inside the relevant
  `core.entity_form_display.node.*.default.yml` config files.
- Aligns standalone `field.group.*` config entities with the exported details
  groups used by the site.
- Adds update hook `nass_census_update_10006()`.
