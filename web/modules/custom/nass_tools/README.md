# NASS Tools consolidated module

This package consolidates the uploaded NASS custom modules into one Drupal 11-compatible module named `nass_tools` while preserving legacy route names, config object names, service IDs, block plugin IDs, templates, libraries, and public paths.

Merged source modules:

- `nass_cropland` placeholder/commented logic
- `nass_eauth_login`
- `nass_maps`
- `nass_market_value`
- `nass_quick_stats`
- `nass_regional_office`
- `nass_region_map`
- `nass_release`
- legacy `nass_tools` Drush command module
- `nass_view_alter`
- `nass_watershed`


## Duplicate release cleanup Drush command

This version includes a generalized Drush command based on the Tennessee duplicate cleanup script. It is safe by default and requires an explicit `remove` mode before any nodes are deleted.

Dry run / show duplicates:

```bash
drush duplicate-cleanup Tennessee show-duplicate
drush duplicate-cleanup TN show-duplicate
```

Execute deletion for confirmed-identical duplicates:

```bash
drush duplicate-cleanup Tennessee remove
drush duplicate-cleanup TN remove
```

Optional overrides when field or vocabulary machine names differ:

```bash
drush duplicate-cleanup Tennessee show-duplicate --content-type=release --vocabulary=states --state-field=field_state
```

Cleanup rules:

- Finds duplicate `release` nodes for the selected state by matching titles.
- Only processes title groups with exactly two matching nodes.
- Keeps the lower node ID and targets the higher node ID.
- Re-checks the pair field-by-field before deletion.
- Ignores expected metadata differences such as node ID, UUID, revision metadata, path, and timestamps.
- Skips and reports pairs that diverged on content fields.

Recommended workflow:

```bash
ddev snapshot
ddev drush duplicate-cleanup TN show-duplicate
ddev drush duplicate-cleanup TN remove
ddev drush cr
```

## Safe cutover approach

Do not uninstall the old modules first. Drupal uninstall removes module-owned config and can delete placed block config. Enable `nass_tools`, run database updates, confirm routes/blocks/assets, then remove or uninstall the old modules only after config dependencies have been moved and exported.

Recommended one-line DDEV sequence:

```powershell
Copy-Item .\nass_tools .\web\modules\custom\nass_tools -Recurse -Force; ddev drush cr; ddev drush en nass_tools -y; ddev drush updb -y; ddev drush cr; ddev drush pml --status=enabled --type=module | findstr "nass_"; ddev drush route | findstr "nass_release nass_quick_stats signin-oidc"; ddev drush config:export -y
```

After validation, uninstall the old split modules in a maintenance window:

```powershell
ddev drush pmu nass_cropland nass_eauth_login nass_maps nass_market_value nass_quick_stats nass_regional_office nass_region_map nass_release nass_view_alter nass_watershed -y; ddev drush updb -y; ddev drush cr; ddev drush config:export -y
```

If Drupal refuses to uninstall because config still depends on a legacy module, inspect dependencies before forcing anything:

```powershell
ddev drush config:export -y; Select-String -Path .\config\sync\*.yml -Pattern "nass_maps|nass_market_value|nass_quick_stats|nass_regional_office|nass_region_map|nass_release|nass_watershed|nass_view_alter|nass_eauth_login|nass_cropland"
```

## Smoke-test URLs

- `/data-and-statistics/todays-releases`
- `/data-and-statistics/calendar/release-calendar`
- `/nass-release/current`
- `/nass-release/upcoming`
- `/nass-release/calendar`
- `/quick-stats-sector`
- `/quick-stats-groups`
- `/quick-stats-commodities`
- `/quick-stats-results`
- `/quick-stats-short-desc`
- `/quick-stats-uuid-decode`
- `/signin-oidc`
