# NASS Release module rebuild

This version keeps the existing NASS Today/Upcoming Releases behavior but changes the module into a release data consumer.

## Main changes

- Keeps existing `national_release` node source support.
- Adds TX file source support.
- Adds future API source scaffold.
- Adds cron-driven TX scanning and snapshot caching.
- Adds sample future TX data in `data/sample_apr_2026_releases.tx`.
- Adds JSON endpoints:
  - `/nass-release/current`
  - `/nass-release/upcoming?days=7`
  - `/nass-release/calendar?days=30`
  - `/nass-release/all`
- Adds Drush commands:
  - `drush nass-release:scan-tx`
  - `drush nass-release:scan-tx --force`
  - `drush nass-release:list --scope=today`
  - `drush nass-release:list --scope=upcoming --days=14`
  - `drush nass-release:list --scope=calendar --days=30`

## Recommended setup

1. Place this module at `web/modules/custom/nass_release`.
2. Run `drush cr`.
3. Enable the module if needed: `drush en nass_release -y`.
4. Visit `/admin/config/nass-release/adminsettings`.
5. Confirm the TX source is enabled.
6. Leave `TX input directory` empty for local testing, so it reads the included `data/` folder.
7. Run `drush nass-release:scan-tx --force`.
8. Test `/nass-release/current`, `/nass-release/upcoming`, or `/nass-release/calendar`.

## Architecture

The display layer now consumes normalized release records. The records can come from TX files, an API, or existing Drupal nodes. The front end should not care where the source data originated.

