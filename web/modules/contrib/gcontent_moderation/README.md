Installation:
-------------
After installing the module, make sure the "Moderated group content" view works with your content moderation workflow:
- Go to /admin/structure/views/view/moderated_group_content/edit/moderated_content
- Go to Filter criteria:
  > Content revision: Moderation state (exposed): Operator: Is one of > Select the states that represent your pending versions (NOT published state)
  > Content revision: Moderation state: Operator: Is none of > Select the published state from your workflows

For general guide about settings up content moderation see https://www.drupal.org/docs/8/core/modules/content-moderation/overview
