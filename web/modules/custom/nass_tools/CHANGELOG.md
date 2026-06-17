# NASS Tools Changelog

## v1.6.6 - Duplicate release cleanup Drush command

### Added
- Added `drush duplicate-cleanup [state] [show-duplicate]` for a safe dry run that lists duplicate state release nodes.
- Added `drush duplicate-cleanup [state] [remove]` to delete only confirmed-identical duplicate release nodes.
- Added support for state names, postal abbreviations such as `TN`, and state taxonomy term IDs.
- Added aliases `dup-cleanup` and `nass:duplicate-cleanup`.

### Safety behavior
- Groups candidate duplicates by release title for the selected state.
- Keeps the lower node ID and targets the higher node ID for deletion.
- Deletes nothing unless the two nodes match on every non-metadata field.
- Skips diverged pairs and prints the fields that differ.
- Defaults to `release`, `field_state`, and `states`, with Drush options available for different machine names.


## v1.6.5 - Upcoming Releases style enforcement

### Fixed
- Attached `assets/css/nass-release.css` to the `nass_release` library so the Upcoming Releases styling is actually loaded on the homepage and calendar pages.
- Updated the legacy JavaScript fallback rendering for Upcoming Releases so it groups the next three releases by date.
- Updated the legacy JavaScript fallback rendering so release time appears above the title, matching the Today's Releases pattern.
- Increased spacing between the last Upcoming Releases item and the linked Calendar message.

### Preserved
- `drush nass-release:scan-tx` still force scans, syncs nodes, and clears cache by default.
- Homepage Today's Releases behavior is unchanged.
- Calendar data sync remains inside `nass_tools`.
- No `nass_census` files are changed.


## v1.6.4 - Release command defaults and Upcoming Releases polish

### Changed
- `drush nass-release:scan-tx` now force rebuilds the TX snapshot by default.
- `drush nass-release:scan-tx` now syncs TX records into `national_release` nodes by default.
- `drush nass-release:scan-tx` now rebuilds Drupal caches automatically after scanning and syncing.
- Added alias `nass-releases:scan-tx` for compatibility with plural command usage.
- Styled Upcoming Releases to group records by release date, matching the Today's Releases date/time/title pattern.
- Updated the persistent Calendar message to: `For the complete release schedule, please visit the Calendar.`
- Added additional spacing between the final upcoming item and the Calendar message.

### Notes
- To skip node syncing, run `drush nass-release:scan-tx --no-sync-nodes`.
- To skip automatic cache rebuild, run `drush nass-release:scan-tx --no-clear-cache`.



## v1.6.3 - Calendar Node Sync and Homepage Spacing

### Added
- Added `nass-release:sync-nodes` to sync TX release records into `national_release` nodes.
- Added `--sync-nodes` support to `nass-release:scan-tx` so TX scan and calendar-node sync can run together.
- Added `ReleaseNodeSync` service to populate the existing release calendar View without moving calendar functionality into `nass_census`.

### Changed
- Added spacing above the persistent Calendar note under the three Upcoming Releases items on the homepage.

### Preserved
- Homepage Today's Releases remains powered by the normalized release repository.
- Homepage Upcoming Releases still shows the next three releases after today.
- Existing release calendar View/page remains in place and receives data through `national_release` nodes.
- No `nass_census` files are changed.


## v1.6.2 - Release Calendar/Homepage Safety Patch

### Fixed
- Keeps release/calendar work inside `nass_tools`.
- Restores compatibility with the existing homepage block template behavior.
- Populates `Upcoming Releases` from TX/repository data instead of the old `release_calendar` View.
- Shows the next three releases after the current day; today's releases are not duplicated in Upcoming Releases.
- Keeps the Calendar message below the three upcoming releases.
- Adds a repository fallback so older `nass_release.adminsettings` config cannot accidentally disable TX data rendering.
- Keeps `/data-and-statistics/calendar/release-calendar` backed by ingested TX/repository records.

### Module Boundary
This patch belongs to `nass_tools`, not `nass_census`.
