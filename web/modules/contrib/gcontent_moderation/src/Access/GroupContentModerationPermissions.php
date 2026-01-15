<?php

namespace Drupal\gcontent_moderation\Access;

use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\workflows\Entity\Workflow;
use Drupal\workflows\WorkflowInterface;

/**
 * Provides dynamic content moderation permissions for all group types.
 */
class GroupContentModerationPermissions {

  use StringTranslationTrait;

  /**
   * Returns an array of content moderation permissions.
   *
   * @return array
   *   The content moderation permissions.
   */
  public function groupPermissions() {
    $perms = [];

    // Generate group permissions for all content moderation workflows.
    /** @var \Drupal\workflows\WorkflowInterface $workflow */
    foreach (Workflow::loadMultipleByType('content_moderation') as $workflow) {
      $perms += $this->buildPermissions($workflow);
    }

    return $perms;
  }

  /**
   * Returns a list of content moderation permissions for a given workflow.
   *
   * @param \Drupal\workflows\WorkflowInterface $workflow
   *   The workflow.
   *
   * @return array
   *   An associative array of permission names and descriptions.
   */
  protected function buildPermissions(WorkflowInterface $workflow) {
    $defaults['title_args']['%workflow'] = $workflow->label();

    $permissions = [];
    foreach ($workflow->getTypePlugin()->getTransitions() as $transition) {
      $defaults['title_args']['%transition'] = $transition->label();
      $permissions['use ' . $workflow->id() . ' transition ' . $transition->id()] = [
        'title' => '%workflow workflow: Use %transition transition.',
      ] + $defaults;
    }

    return $permissions;
  }

}
