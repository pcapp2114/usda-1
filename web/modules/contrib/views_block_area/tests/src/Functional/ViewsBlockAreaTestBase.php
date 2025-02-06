<?php

namespace Drupal\Tests\views_block_area\Functional;

use Drupal\Tests\BrowserTestBase;

/**
 * Base class used for functional tests.
 */
class ViewsBlockAreaTestBase extends BrowserTestBase {

  /**
   * Modules to enable.
   *
   * @var array
   */
  protected static $modules = [
    'views',
    'views_ui',
    'user',
    'block',
    'views_block_area',
  ];

  /**
   * The default theme.
   *
   * @var string
   */
  protected $defaultTheme = 'stark';

  /**
   * Drupal installation profile.
   *
   * @var string
   */
  protected $profile = 'minimal';

  /**
   * The admin user.
   *
   * @var \Drupal\user\Entity\User
   */
  protected $adminUser;

  /**
   * {@inheritdoc}
   *
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  protected function setUp(): void {
    parent::setUp();
    // Creates an adminUser with required permissions for testing purposes.
    $this->adminUser = $this->drupalCreateUser([]);
    $this->adminUser->addRole($this->createAdminRole('admin', 'admin'));
    $this->adminUser->save();
    // Logs in as the Admin user and goes to the /admin.
    $this->drupalLogin($this->adminUser);
    $this->drupalGet('/admin');
  }

  /**
   * Builds block.
   */
  public function buildBlock($blockId, $parameters = []) {
    return $this->container->get('plugin.manager.block')->createInstance($blockId, $parameters)->build();
  }

}
