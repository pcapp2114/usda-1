# Census module

This module creates:
- Content types: `census`, `census_publication`
- Paragraph types: `census_section`, `census_group`, `census_item`, `census_action`
- Shared fields, entity references, list vocabularies, and form display groupings
- Field Group tabs on node forms and grouped sections on paragraph forms

## Assumptions
- The site already has the required contributed modules enabled: Paragraphs, Entity Reference Revisions, Field Group, Media Library.
- Media fields are left generic and point to existing media types on the site.

## What it creates
### Nodes
- `Census`: light year landing page
- `Census Publication`: child/detail pages such as Alabama, Watersheds, profiles, and special studies

### Paragraphs
- `census_section`
- `census_group`
- `census_item`
- `census_action`

## Notes
- The installer is idempotent. Existing content types, bundles, and fields are not recreated.
- Form displays are configured with Field Group tabs and logical field ordering.
- View displays are kept simple and can be adjusted later in the UI if needed.
