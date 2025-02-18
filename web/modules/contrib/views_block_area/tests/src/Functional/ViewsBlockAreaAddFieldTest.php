<?php

namespace Drupal\Tests\views_block_area\Functional;

/**
 * Block as a view field usage functional tests.
 */
class ViewsBlockAreaAddFieldTest extends ViewsBlockAreaTestBase {

  /**
   * Tests 'Powered by Drupal' block usage in the People view field.
   *
   * @throws \Behat\Mink\Exception\ResponseTextException
   */
  public function testAddBlockArea(): void {
    $this->drupalGet('/admin/structure/views/nojs/add-handler/user_admin_people/page_1/field');
    $this->submitForm(['name[views.views_block_field]' => 1], 'Add and configure fields');
    $this->submitForm([
      'options[custom_label]' => 1,
      'options[label]' => 'Block Field',
      'options[block_id]' => 'system_powered_by_block',
      'options[block_title]' => 'Test Field',
    ], 'Apply');
    $this->submitForm([], 'Save');
    $this->drupalGet('/admin/people');
    // Checking if the 'Block Field' field label was rendered.
    $this->assertSession()->pageTextContains('Block Field');
    // Checking if the 'Test Field' block title was rendered.
    $this->assertSession()->pageTextContains('Test Field');
    // Checking if the 'Powered by Drupal' block title was rendered.
    $block_text = strip_tags($this->buildBlock('system_powered_by_block')['#markup']);
    $this->assertSession()->pageTextContains($block_text);
  }

}
