<?php

namespace Drupal\Tests\gcontent_moderation\Functional;

use Drupal\group\PermissionScopeInterface;
use Drupal\Tests\content_moderation\Traits\ContentModerationTestTrait;
use Drupal\Tests\group\Functional\GroupBrowserTestBase;
use Drupal\user\RoleInterface;
use Drupal\workflows\WorkflowInterface;

/**
 * Tests integration with the core Content Moderation module.
 *
 * @group group
 */
class ContentModerationIntegrationTest extends GroupBrowserTestBase {

  use ContentModerationTestTrait;

  /**
   * A group for testing purposes.
   *
   * @var \Drupal\group\Entity\GroupInterface
   */
  protected $group;

  /**
   * A normal group member.
   *
   * @var \Drupal\user\UserInterface
   */
  protected $groupMember;

  /**
   * A admin group member.
   *
   * @var \Drupal\user\UserInterface
   */
  protected $groupAdmin;

  /**
   * A non-group member.
   *
   * @var \Drupal\user\UserInterface
   */
  protected $nonGroupMember;

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'content_moderation',
    'gcontent_moderation',
    'gcontent_moderation_test',
    // @todo Ideally this would test with a non-node content enabler.
    'gnode',
    'group',
    'group_test_config',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();

    // Ensure the editorial workflow.
    $workflow = $this->entityTypeManager->getStorage('workflow')->load('editorial');

    if (!$workflow instanceof WorkflowInterface) {
      $workflow = $this->createEditorialWorkflow();
    }

    // Set permissions for content moderation in the default group type.
    $member_permissions = [
      'update own group_node:article entity',
      'use editorial transition create_new_draft',
      'view unpublished group_node:article entity',
      'view latest version',
    ];
    /** @var \Drupal\group\Entity\GroupTypeInterface $type */
    $type = $this->entityTypeManager->getStorage('group_type')->load('default');
    $this->createGroupRole([
      'group_type' => $type->id(),
      'scope' => PermissionScopeInterface::INSIDER_ID,
      'global_role' => RoleInterface::AUTHENTICATED_ID,
      'permissions' => $member_permissions,
    ]);

    $administrator_permissions = [
      'update any group_node:article entity',
      'use editorial transition create_new_draft',
      'use editorial transition publish',
      'view unpublished group_node:article entity',
      'view latest version',
    ];
    $adminRole = $this->createGroupRole([
      'group_type' => $type->id(),
      'scope' => PermissionScopeInterface::INDIVIDUAL_ID,
      'admin' => TRUE,
    ]);

    // Add the article content type to the group type, and enable workflow.
    $this->createContentType(['type' => 'article']);
    /** @var \Drupal\group\Entity\Storage\GroupRelationshipTypeStorageInterface $storage */
    $storage = $this->entityTypeManager->getStorage('group_content_type');
    $storage->createFromPlugin($type, 'group_node:article')->save();
    $workflow->getTypePlugin()->addEntityTypeAndBundle('node', 'article');
    $workflow->save();

    // Add a group.
    $this->group = $this->createGroup(['type' => $type->id()]);

    $this->nonGroupMember = $this->createUser();
    // Utilize global permission to ensure those are merged in access decorator.
    $this->groupMember = $this->createUser([
      'use editorial transition create_new_draft',
    ]);
    $this->groupAdmin = $this->createUser([
      'use editorial transition create_new_draft',
      'use editorial transition publish',
    ]);

    $this->group->addMember($this->groupMember);
    $this->group->addMember($this->groupAdmin, ['group_roles' => [$adminRole->id()]]);

    node_access_rebuild();
  }

  /**
   * Tests access to the latest version tab of non group nodes.
   *
   * This is a basic sanity check to ensure the logic in group is not changing
   * the behavior of content moderation for non-group entities.
   */
  public function testLatestVersionAccessNonGroupNode() {
    $node = $this->createNode(['type' => 'article']);

    // A non-member should not have access to this draft state.
    $this->drupalLogin($this->nonGroupMember);
    $this->drupalGet($node->toUrl());
    $this->assertSession()->statusCodeEquals(403);
    $this->drupalGet($node->toUrl('edit-form'));
    $this->assertSession()->statusCodeEquals(403);

    // The group member should not have access.
    $this->drupalLogin($this->groupMember);
    $this->drupalGet($node->toUrl());
    $this->assertSession()->statusCodeEquals(403);
    $this->drupalGet($node->toUrl('edit-form'));
    $this->assertSession()->statusCodeEquals(403);
  }

  /**
   * Tests access for group nodes.
   */
  public function testLatestVersionAccessGroupNode() {
    $node = $this->createNode(['type' => 'article', 'uid' => $this->groupMember->id()]);
    $this->group->addRelationship($node, 'group_node:article');

    // A non-member should not have access to this draft state.
    $this->drupalLogin($this->nonGroupMember);
    $this->drupalGet($node->toUrl());
    $this->assertSession()->statusCodeEquals(403);
    $this->drupalGet($node->toUrl('edit-form'));
    $this->assertSession()->statusCodeEquals(403);

    // The group member should have access.
    $this->drupalLogin($this->groupMember);
    $this->drupalGet($node->toUrl());
    $this->assertSession()->statusCodeEquals(200);
    $this->drupalGet($node->toUrl('edit-form'));
    $this->assertSession()->statusCodeEquals(200);

    // Create a forward revision and make sure the member can not publish it.
    $edit = [
      'moderation_state[0][state]' => 'published',
    ];
    try {
      $this->submitForm($edit, t('Save'));
    }
    catch (\InvalidArgumentException $exception) {
      $expectedException = TRUE;
    }

    if (!isset($expectedException)) {
      // Trigger an error.
      self::assertFalse(TRUE, 'The published state should not be available in the form.');
    }

    // Create a forward revision and ensure access to that as well.
    $edit = [
      'moderation_state[0][state]' => 'draft',
    ];
    $this->submitForm($edit, t('Save'));
    $this->drupalGet($node->toUrl('edit-form'));
    $this->submitForm(['title[0][value]' => 'New draft'], t('Save'));
    $this->assertSession()->statusCodeEquals(200);

    // The group admin should have access.
    $this->drupalLogin($this->groupAdmin);
    $this->drupalGet($node->toUrl());
    $this->assertSession()->statusCodeEquals(200);

    // Let's publish it.
    $this->drupalGet($node->toUrl('edit-form'));
    $this->assertSession()->statusCodeEquals(200);
    $edit = [
      'moderation_state[0][state]' => 'published',
    ];
    $this->submitForm($edit, t('Save'));
    $this->assertSession()->statusCodeEquals(200);
  }

}
