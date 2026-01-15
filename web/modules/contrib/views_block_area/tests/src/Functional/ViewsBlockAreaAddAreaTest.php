<?php

namespace Drupal\Tests\views_block_area\Functional;

/**
 * Block as a view area usage functional tests.
 */
class ViewsBlockAreaAddAreaTest extends ViewsBlockAreaTestBase {

  /**
   * Tests 'Powered by Drupal' block usage in the Content view Header area.
   *
   * @throws \Behat\Mink\Exception\ResponseTextException
   */
  public function testAddFieldArea(): void {
    $this->drupalGet('/admin/structure/views/nojs/add-handler/content/page_1/header');
    $this->submitForm(['name[views.views_block_area]' => 1], 'Add and configure header');
    $this->submitForm([
      'options[empty]' => 1,
      'options[block_id]' => 'system_powered_by_block',
      'options[block_title]' => 'Test Area',
      'options[hide_label]' => 0,
    ], 'Apply');
    $this->submitForm([], 'Save');
    $this->drupalGet('/admin/content');
    // Checking if the 'Test Area' block title was rendered.
    $this->assertSession()->pageTextContains('Test Area');
    // Checking if the 'Powered by Drupal' block content was rendered.
    $block_text = strip_tags($this->buildBlock('system_powered_by_block')['#markup']);
    $this->assertSession()->pageTextContains($block_text);
  }

}
