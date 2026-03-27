# NASS -> Drupal Feeds migration recon (from CSV.zip + db.sql.gz)
Generated: 2026-03-05T19:14:37.247668
## High-level inventory
| Feed type | Label | Destination | CSV files | Common columns |
|---|---|---:|---:|---|
| `state_releases` | State Releases | entity:media / state_release | 176 | url|filename|state|category|date |
| `state_releases_filtered` | State Releases - Filtered | entity:node / release | 125 | path|title|state|category|date |
| `state_census` | State Census | entity:media / state_census | 84 | table|url|filename|state|category|query |
| `race_ethnicity_and_gender_profil` | Race, Ethnicity and Gender Profiles | entity:node / r_e_g_profile | 54 | title|pdf|state |
| `state_and_county_profiles` | State and County Profiles | entity:node / state_county_profiles | 54 | url|county|state|category |
| `congressional_district_profiles` | Congressional District Profiles | entity:node / congressional_district_profiles | 53 | url|filename|state|category |
| `crop_progress_and_condition_grap` | Crop Progress and Condition Graphical Products | entity:node / charts_and_maps_crop_progress_an | 25 | title|year|pdf |
| `special_tabulations` | Special Tabulations | entity:node / special_tabulations | 23 | tab_id|requesting_org|geography|file_title|description|title|year |
| `news_releases` | News Releases | entity:node / news_release | 15 | news_title|news_alt_title|content|url_redirect|date|referring_url|news_new_url|contact |
| `report` | Research Reports | entity:node / research_report | 4 | Title||Type|Section|Authors|Date |
| `asb_briefings` | ASB Briefings | entity:node / asb_briefing | 3 | date|title|referring_url |
| `asb_notice_feed` | ASB Notice Feed Creator | entity:feeds_feed / asb_notice | 3 | title|url |
| `national_census` | National Census | entity:node / census | 3 | table|census|year|pdf|pdf_url|query_url_text|title |
| `national_release` | National Release | entity:node / national_release | 3 | Release DateTime|Release Name|Release Filename|Release URL|QuickStats Only Release|Release Static Link|Release Includes JSON |
| `asb_notice` | ASB Notice | entity:node / asb_notice | 2 | date|news_title|referring_url|url_redirect|news_new_url|content |
| `landing_page` | Landing Page | entity:node / landing_page | 2 | id|title|field_content|new_path|old_path| |
| `pdf` | PDF | entity:media / document | 2 | url|filename |
| `survey` | Survey | entity:node / survey | 2 | body|tab_content_7|about|tab_title_6|tab_content_2|tab_title_7|survey_name|tab_title_2|tab_content_3|tab_title_1|tab_content_1|tab_title_3|tab_title_4|tab_title_5|tab_content_5|tab_content_4|survey_new_url|referring_url|banner_image_urls|tab_content_6 |
| `census_resource_tables` | Census Resource Tables | entity:node / census | 1 | table|title|year|pdf|resource|census_level |
| `crop_progress_condition_state_re` | Crop Progress & Condition - State Release | entity:node / crop_progress_and_condition_stat | 1 | file|title|state|category|date |
| `highlights` | Highlights | entity:node / highlights | 1 | year|tag|URL|Link text|title |
| `quick_stats_system_updates` | Quick Stats System Updates | entity:node / quick_stats_system_updates | 1 | Title|Data Series|Update Period|Update Description|Implementation Date|Update Type|||| |
| `ranking_of_market_value_of_ag_pr` | Ranking of Market Value of Ag Products | entity:node / rankings_of_market_value_ | 1 | table|state|year|state_title |
| `releases_by_title_and_date_natio` | Releases By Title and Date - National | entity:node / releases_by_title_and_date_n | 1 | release date|title|url |
| `statistical_release_corrections` | Statistical Release Corrections | entity:node / statistical_release_corrections | 1 | Title|Report Name|Release Date|Correction Description|Notification Date |
| `visuals` | Visuals | entity:node / visuals | 1 | title|img_path|pdf_path|data_file|visual_category|survey|subject |

## Unknown / not matched to a feed type
- 2022_errata_gcbtb08_WEB_B.csv
- cdp.csv
- farmnumberslandsize.csv
- marketbasket.csv
- mechanization.csv
- NASS Alabama Census v2b.csv
- New_England-Releases.csv
- New_England-Releases_0.csv
- oatandsoy.csv
- pdf_urls_and_anchors_subset.csv
- soybeanmap.csv
- sp_tab_2002 - sp_tab_2002_v2.csv
- sp_tab_2002 - sp_tab_2002_v2_0.csv
- test (1).csv
- test_0 (1).csv
- test_1.csv
- test_2.csv
