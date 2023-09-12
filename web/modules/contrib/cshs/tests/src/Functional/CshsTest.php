<?php

namespace Drupal\Tests\cshs\Functional;

use Drupal\node\NodeInterface;
use Drupal\taxonomy\TermInterface;
use Drupal\Tests\BrowserTestBase;

/**
 * Tests the CSHS module.
 *
 * @group cshs
 */
class CshsTest extends BrowserTestBase {

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stable9';

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'cshs',
    'cshs_test',
    'taxonomy',
    'user',
    'node',
    'views',
    'views_ui',
  ];

  /**
   * The ID of the vocabulary used in this test.
   *
   * @var string
   */
  protected $vocabularyId = 'tags';

  /**
   * The term storage.
   *
   * @var \Drupal\taxonomy\TermStorageInterface
   */
  protected $termStorage;

  /**
   * Test views integration.
   */
  public function testCshsViews(): void {
    $user = $this->drupalCreateUser([
      'access content',
    ]);
    $ct = $this->drupalCreateContentType([
      'type' => 'page',
      'name' => 'Basic page',
    ]);
    // Create a node to display by the view.
    $this->drupalCreateNode([
      'uid' => $user->id(),
      'type' => $ct->id(),
      'status' => NodeInterface::PUBLISHED,
    ]);

    $this->drupalLogin($user);

    $this->drupalGet('cshs');
    $assert = $this->assertSession();

    $assert->statusCodeEquals(200);
    $assert->pageTextContains('CSHS view');

    $assert->elementExists('css', '#edit-tid');
    $assert->pageTextContains('Term ID');

    $assert->elementExists('css', '#edit-tid-depth');
    $assert->pageTextContains('Term ID (Depth)');
  }

  /**
   * Test that first tree level can be selected.
   *
   * @see \Drupal\Tests\taxonomy\Functional\testAddWithParents()
   */
  public function testCshsViewsFirstTreeLevel(): void {
    /** @var \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager */
    $entity_type_manager = $this->container->get('entity_type.manager');
    $this->termStorage = $entity_type_manager->getStorage('taxonomy_term');

    $admin = $this->drupalCreateUser([
      'administer taxonomy',
      'administer views',
    ]);
    $this->drupalLogin($admin);

    $this->drupalGet("/admin/structure/taxonomy/manage/{$this->vocabularyId}/add");
    $page = $this->getSession()->getPage();


    $no_parent = [['target_id' => 0]];
    // Create first parent term.
    $parent_1 = $this->submitAddTermForm('Parent 1');
    $this->assertEquals($no_parent, $parent_1->get('parent')->getValue());

    // Create second parent term.
    $parent_2 = $this->submitAddTermForm('Parent 2');
    $this->assertEquals($no_parent, $parent_2->get('parent')->getValue());

    // Create two child terms for each previously created parent.
    $page->selectFieldOption('Parent terms', 'Parent 1');
    $child_1 = $this->submitAddTermForm('Child 1');
    $first_parent = [['target_id' => $parent_1->id()]];
    $this->assertEquals($first_parent, $child_1->get('parent')->getValue());
    $page->selectFieldOption('Parent terms', 'Parent 1');
    $child_2 = $this->submitAddTermForm('Child 2');
    $this->assertEquals($first_parent, $child_2->get('parent')->getValue());

    $page->selectFieldOption('Parent terms', 'Parent 2');
    $child_3 = $this->submitAddTermForm('Child 3');
    $second_parent = [['target_id' => $parent_2->id()]];
    $this->assertEquals($second_parent, $child_3->get('parent')->getValue());
    $page->selectFieldOption('Parent terms', 'Parent 2');
    $child_4 = $this->submitAddTermForm('Child 4');
    $this->assertEquals($second_parent, $child_4->get('parent')->getValue());

    // Actual test below.
    $assert = $this->assertSession();
    $this->drupalGet('/admin/structure/views/view/cshs');
    $assert->statusCodeEquals(200);
    $this->clickLink('Content: Has taxonomy term (exposed)');
    $this->getSession()->getPage()->fillField('Hierarchy depth', 1);
    $this->submitForm([], 'Apply');
    $assert->statusCodeEquals(200);
    $assert->pageTextNotContains('The hierarchy depth cannot be 1 because the selection list has 0 levels.');
  }


  /**
   * Creates a term through the user interface and returns it.
   *
   * @param string $name
   *   The name of the term to create.
   *
   * @return \Drupal\taxonomy\TermInterface
   *   The newly created taxonomy term.
   */
  protected function submitAddTermForm($name) {
    $this->getSession()->getPage()->fillField('Name', $name);

    $this->submitForm([], 'Save');

    $result = $this->termStorage
      ->getQuery()
      ->accessCheck(FALSE)
      ->condition('name', $name)
      ->execute();
    /** @var \Drupal\taxonomy\TermInterface $term_1 */
    $term_1 = $this->termStorage->load(reset($result));
    $this->assertInstanceOf(TermInterface::class, $term_1);
    return $term_1;
  }

}
