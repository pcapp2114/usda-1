<?php

namespace Drupal\Tests\views_field_select_filter\Functional;

use Drupal\Tests\system\Functional\Module\GenericModuleTestBase;

/**
 * Generic module test for views_field_select_filter.
 *
 * @group views_field_select_filter
 */
class GenericTest extends GenericModuleTestBase {

  /**
   * {@inheritDoc}
   */
  protected function assertHookHelp(string $module): void {
    // Don't do anything here. We intend to implement hook_help() differently.
  }

}
