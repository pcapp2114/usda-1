<?php

namespace Drupal\Tests\gcontent_moderation\Kernel\Views;

use Drupal\group\Entity\Group;
use Drupal\group\Entity\Storage\GroupRelationshipTypeStorageInterface;
use Drupal\group\PermissionScopeInterface;
use Drupal\node\Entity\Node;
use Drupal\Tests\content_moderation\Traits\ContentModerationTestTrait;
use Drupal\Tests\views\Kernel\ViewsKernelTestBase;
use Drupal\user\Entity\Role;
use Drupal\user\Entity\User;
use Drupal\views\Views;

/**
 * Tests the group_content_respect_unpublished argument handler.
 *
 * @see \Drupal\gcontent_moderation\Plugin\views\filter\GroupContentRespectUnpublished
 *
 * @group group
 */
class GroupContentRespectUnpublishedArgumentTest extends ViewsKernelTestBase {

  use ContentModerationTestTrait;

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'content_moderation',
    'gcontent_moderation',
    'gcontent_moderation_test',
    'gnode',
    'group',
    'group_test_config',
    'field',
    'text',
    'workflows',
    'node',
    'entity',
    'flexible_permissions',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp($import_test_views = FALSE): void {
    parent::setUp($import_test_views);

    $this->installEntitySchema('content_moderation_state');
    $this->installEntitySchema('user');
    $this->installEntitySchema('node');
    $this->installEntitySchema('node_type');
    $this->installEntitySchema('group');
    $this->installEntitySchema('group_type');
    $this->installEntitySchema('group_content');
    $this->installEntitySchema('group_content_type');
    $this->installEntitySchema('workflow');
    $module_configs = [
      'content_moderation',
      'gcontent_moderation',
      'gcontent_moderation_test',
      'group',
      'group_test_config',
      'field',
      'node',
      'text',
      'workflows',
    ];
    $this->installConfig($module_configs);
    $this->installSchema('node', ['node_access']);

    // Set the current user so group creation can rely on it.
    $account = User::create(['name' => $this->randomString()]);
    $account->save();
    $this->container->get('current_user')->setAccount($account);

    /** @var \Drupal\group\Entity\GroupType $type */
    $type = $this->container->get('entity_type.manager')->getStorage('group_type')->load('default');

    /** @var \Drupal\group\Entity\Storage\GroupRelationshipTypeStorageInterface $storage */
    $storage = $this->container->get('entity_type.manager')->getStorage('group_content_type');
    assert($storage instanceof GroupRelationshipTypeStorageInterface);
    $storage->save($storage->createFromPlugin($type, 'group_node:default'));
    /** @var \Drupal\workflows\WorkflowInterface $workflow */
    $workflow = $this->container->get('entity_type.manager')->getStorage('workflow')->load('editorial');
    $workflow->getTypePlugin()->addEntityTypeAndBundle('node', 'default');
    $workflow->save();

    // Group role storage.
    $role_storage = $this->container->get('entity_type.manager')->getStorage('group_role');

    // Create global role.
    Role::create(['id' => 'test_role', 'label' => 'Test role']);

    // Create an outsider role for authenticated uses with access to own.
    $outsider_role_permissions = [
      'create group_node:default entity',
      'update own group_node:default entity',
      'use editorial transition create_new_draft',
      'view group',
      'view group_node:default entity',
      'view latest version',
      'view own unpublished group_node:default entity',
    ];
    $outsider_role = $role_storage->create([
      'group_type' => $type->id(),
      'scope' => PermissionScopeInterface::OUTSIDER_ID,
      'global_role' => 'test_role',
      'permissions' => $outsider_role_permissions,
      'id' => $this->randomMachineName(),
      'label' => $this->randomString(),
    ]);
    $role_storage->save($outsider_role);

    // Create an insider role for authenticated users with access to all
    // unpublished nodes.
    $insider_role_permissions = [
      'create group_node:default entity',
      'update any group_node:default entity',
      'update own group_node:default entity',
      'use editorial transition create_new_draft',
      'use editorial transition publish',
      'view group',
      'view group_node:default entity',
      'view latest version',
      'view unpublished group_node:default entity',
    ];
    $insider_role = $role_storage->create([
      'group_type' => $type->id(),
      'scope' => PermissionScopeInterface::INSIDER_ID,
      'global_role' => 'test_role',
      'permissions' => $insider_role_permissions,
      'id' => $this->randomMachineName(),
      'label' => $this->randomString(),
    ]);
    $role_storage->save($insider_role);

    // Create a role for individual member with access to own.
    $individual_member_role_permissions = [
      'create group_node:default entity',
      'update own group_node:default entity',
      'use editorial transition create_new_draft',
      'use editorial transition publish',
      'view group',
      'view group_node:default entity',
      'view latest version',
      'view own unpublished group_node:default entity',
    ];
    $individual_member_role = $role_storage->create([
      'group_type' => $type->id(),
      'scope' => PermissionScopeInterface::INDIVIDUAL_ID,
      'permissions' => $individual_member_role_permissions,
      'id' => 'member_role',
      'label' => $this->randomString(),
    ]);
    $role_storage->save($individual_member_role);

    // Create a role for group moderator with access to all unpublished nodes.
    $individual_moderator_role_permissions = [
      'create group_node:default entity',
      'update any group_node:default entity',
      'update own group_node:default entity',
      'use editorial transition create_new_draft',
      'use editorial transition publish',
      'view group',
      'view group_node:default entity',
      'view latest version',
      'view unpublished group_node:default entity',
    ];
    $individual_moderator_role = $role_storage->create([
      'group_type' => $type->id(),
      'scope' => PermissionScopeInterface::INDIVIDUAL_ID,
      'permissions' => $individual_moderator_role_permissions,
      'id' => 'moderator_role',
      'label' => $this->randomString(),
    ]);
    $role_storage->save($individual_moderator_role);
  }

  /**
   * Tests the group content respect unpublished argument.
   */
  public function testGroupContentRespectUnpublishedArgument() {
    $view = Views::getView('test_moderated_group_content');
    $view->setDisplay();

    /** @var \Drupal\user\UserInterface $user1 */
    $user1 = $this->container->get('current_user')->getAccount();
    $user1->addRole('test_role')->save();

    /** @var \Drupal\group\Entity\GroupInterface $group1 */
    $group1 = Group::create([
      'type' => 'default',
      'label' => $this->randomMachineName(),
    ]);
    $group1->save();

    /** @var \Drupal\node\Entity\Node $node1 */
    Node::create([
      'type' => 'default',
      'title' => 'Node1',
      'moderation_state' => 'draft',
    ])->save();
    $node1 = $this->container->get('entity_type.manager')->getStorage('node')->loadByProperties(
      ['title' => 'Node1']
    );
    $node1 = current($node1);

    /** @var \Drupal\node\Entity\Node $node2 */
    Node::create([
      'type' => 'default',
      'title' => 'Node2',
      'moderation_state' => 'published',
    ])->save();
    $node2 = $this->container->get('entity_type.manager')->getStorage('node')->loadByProperties(
      ['title' => 'Node2']
    );
    $node2 = current($node2);
    $group1->addRelationship($node1, 'group_node:default');
    $group1->addRelationship($node2, 'group_node:default');
    $group1->addMember($user1);

    $view->preview();
    $this->assertEquals(0, count($view->result), 'No results when group id argument is not present.');
    $view->destroy();

    $view->preview('moderated_content', [$group1->id()]);
    $this->assertEquals(1, count($view->result), 'Insider can see their own unpublished content.');

    $user2 = User::create(['name' => $this->randomString()]);
    $user2->save();
    $this->container->get('current_user')->setAccount($user2);

    /** @var \Drupal\node\Entity\Node $node3 */
    Node::create([
      'type' => 'default',
      'title' => 'Node3',
      'moderation_state' => 'draft',
    ])->save();
    $node3 = $this->container->get('entity_type.manager')->getStorage('node')->loadByProperties(
      ['title' => 'Node3']
    );
    $node3 = current($node3);
    $group1->addRelationship($node3, 'group_node:default');

    $view->preview('moderated_content', [$group1->id()]);
    $this->assertEquals(1, count($view->result), 'Outsider can see their own unpublished content.');
    $view->destroy();

    $this->container->get('current_user')->setAccount($user1);

    $view->preview('moderated_content', [$group1->id()]);
    $this->assertEquals(2, count($view->result), 'Insider can see any (own + outsiders) unpublished content.');
    $view->destroy();

    // Test individual member access.
    $individual = User::create(['name' => $this->randomString()]);
    $individual->save();
    $this->container->get('current_user')->setAccount($individual);

    /** @var \Drupal\node\Entity\Node $node4 */
    Node::create([
      'type' => 'default',
      'title' => 'Node4',
      'moderation_state' => 'draft',
    ])->save();
    $node4 = $this->container->get('entity_type.manager')->getStorage('node')->loadByProperties(
      ['title' => 'Node4']
    );
    $node4 = current($node4);
    $group1->addRelationship($node4, 'group_node:default');

    $view->preview('moderated_content', [$group1->id()]);
    $this->assertEquals(0, count($view->result), 'Individual should not see any node because he is not member yet.');
    $view->destroy();

    $group1->addMember($individual, ['group_roles' => ['member_role']]);
    $view->preview('moderated_content', [$group1->id()]);
    $this->assertEquals(1, count($view->result), 'Individual should see own node only.');
    $view->destroy();

    // Test moderator access.
    $moderator = User::create(['name' => $this->randomString()]);
    $moderator->save();
    $this->container->get('current_user')->setAccount($moderator);

    Node::create([
      'type' => 'default',
      'title' => 'Node5',
      'moderation_state' => 'draft',
    ])->save();
    $node5 = $this->container->get('entity_type.manager')->getStorage('node')->loadByProperties(
      ['title' => 'Node5']
    );
    $node5 = current($node5);
    $group1->addRelationship($node5, 'group_node:default');

    $group1->addMember($moderator, ['group_roles' => ['moderator_role']]);
    $view->preview('moderated_content', [$group1->id()]);
    $this->assertEquals(4, count($view->result), 'Moderator should see all unpublished nodes.');
    $view->destroy();
  }

}
