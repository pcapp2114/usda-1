# NASS Census

Installs and maintains the NASS Census content model baseline.

## Creates

- Node type: `census`
- Node type: `census_publication`
- Paragraph type: `census_section`
- Paragraph type: `census_group`
- Paragraph type: `census_item`
- Paragraph type: `census_action`
- Shared configurable fields, list values, paragraph references, media references, and node references
- Basic form/view displays and Field Group tabs/details

## Install

Place this folder in `web/modules/custom/nass_census`, then run:

```bash
ddev drush en nass_census -y
ddev drush cr
```

## Notes

This module defines the Census content structure. It does not yet migrate content or theme/render the Census pages. Version `v1.5` is a metadata/version-tracking cleanup release based on the known-good `v1.4` Field Group baseline.

## Version history

### v1.5 - Version-tracking cleanup baseline

- Treats `v1.4` as the current known-good functional baseline.
- Updates `VERSION.txt` to `nass_census v1.5`.
- Updates this README so each tracked version has a clear purpose.
- Updates `nass_census.info.yml` so the module description no longer references `v1.3`.
- Makes no content model, field, Field Group, paragraph, taxonomy, or update-hook behavior changes.

### v1.4 - Field Group rendering fix

- Fixes Field Group rendering by adding matching `third_party_settings.field_group` metadata inside the relevant `core.entity_form_display.node.*.default.yml` config files.
- Aligns standalone `field.group.*` config entities with the exported details groups used by the site.
- Normalizes exported `field_*` children back to the intended `census_*` field children.
- Removes obsolete tab-style Field Group config that could cause editor form drift.
- Adds update hook `nass_census_update_10006()` to repair active configuration on already-enabled sites.

### v1.3 - Census Year defaults and Field Group repair

- Repairs the Census and Census Publication node form display Field Group tabs/details during install/update.
- Keeps Census node organization as Main Information, Other Information Sections, and Other.
- Adds the Census Year taxonomy vocabulary and creates the default term `2022` with an install/update hook.
- Makes `census_year` required on both Census content types.
- Adds update hook `nass_census_update_10005()`.

### v1.2 - Field machine-name normalization

- Normalizes UI-created content type field machine names from accidental `field_*` names back to the intended `census_*` names.
- Keeps Census form organization from the prior version.
- Adds destructive cleanup/update handling for content/config drift through `nass_census_update_10004()`.

### v1.1 - Census editor tabs cleanup

- Makes `field_group:field_group` an explicit dependency in `nass_census.info.yml`.
- Repairs active Census Field Group config through update hook `nass_census_update_10003()`, because existing sites do not re-import `config/install` after the module is already enabled.
- Rebuilds the Census edit form tabs as: Main Information, Other Information Sections, and Other.
- Hides the legacy/free-text `census_other_information` form widget so the Census edit screen only shows the requested fields. Existing field data is preserved.
- Removes old v1.0 Census tab group config names from active config: `group_sections` and `group_migration_admin`.
- Cleans stale `tara` and `renatek` theme registrations/settings from active config if those themes were removed from the codebase.

### v1.0 - Initial content model

- Creates the initial Census/Census Publication content model.
- Adds core Census fields, paragraph types, entity references, form displays, view displays, and initial Field Group structure.
