# Census export test module v1.4

This test module provides a Drush command that scrapes NASS Census publication pages from `div.contentRight`, downloads linked files locally, normalizes folder/file names, and writes JSON manifests.

## Command

```bash
drush census_export --url="https://www.nass.usda.gov/Publications/AgCensus/2022/index.php" --year=2022
```

Options:

- `--url`: Required. Full NASS Census page URL.
- `--year`: Required. Four-digit Census year.
- `--dry-run`: Scrapes and plans without writing files.
- `--no-overwrite`: Skips files that already exist locally.

## v1.4 changes

- Keeps the v1.1 `div.contentRight` scope.
- Keeps the v1.3 protection that downloadable filenames such as `cp01001.pdf` and `cd0101.pdf` are never used as folder names.
- Flattens single-file state assets into the state folder.
- County profiles now export as `state/[state]/county_[county].pdf`.
- Congressional district profiles now export as `state/[state]/district_[ordinal].pdf`.
- Typology now exports as `state/[state]/typology.pdf`.
- Hemp now exports as `state/[state]/hemp.pdf`.
- Statewide Market Value files now export as `state/[state]/market_value.pdf`.
- Multi-file Market Value groups still export under `state/[state]/market_value/`.
- Congressional District Rankings now export under `state/[state]/district_rankings/`.
- Manifest entries preserve original source URLs and original filenames for auditability.

## Route-aware placement

Known routes are placed as follows:

- `Watersheds` -> `public://agcensus/[year]/watershed/[normalized-file]`
- `Full_Report/Volume_1,_Chapter_1_State_Level/[State]` -> `public://agcensus/[year]/state/[state-name]/[normalized-file]`
- `County_Profiles/[State]` -> `public://agcensus/[year]/state/[state-name]/county_[county-name].pdf`
- `Congressional_District_Profiles` -> `public://agcensus/[year]/state/[state-name]/district_[ordinal].pdf`
- `Typology` -> `public://agcensus/[year]/state/[state-name]/typology.pdf`
- `Rankings_of_Market_Value` -> `public://agcensus/[year]/state/[state-name]/market_value.pdf` for single statewide files, or `public://agcensus/[year]/state/[state-name]/market_value/[normalized-file]` for multi-file/district groups
- `Congressional_District_Rankings` -> `public://agcensus/[year]/state/[state-name]/district_rankings/[normalized-file]`
- `Hemp` -> `public://agcensus/[year]/state/[state-name]/hemp.pdf`

## Output

The command writes page-level files under the route-aware folder:

- `manifest.json`
- `migration-report.json`

It also updates:

- `public://agcensus/[year]/manifest.json`
