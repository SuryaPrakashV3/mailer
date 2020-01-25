<?php

namespace Drupal\mailer\Tests;

use Drupal\simpletest\WebTestBase;

/**
 * Provides automated tests for the mailer module.
 */
class FormControllerTest extends WebTestBase {


  /**
   * {@inheritdoc}
   */
  public static function getInfo() {
    return [
      'name' => "mailer FormController's controller functionality",
      'description' => 'Test Unit for module mailer and controller FormController.',
      'group' => 'Other',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    parent::setUp();
  }

  /**
   * Tests mailer functionality.
   */
  public function testFormController() {
    // Check that the basic functions of module mailer.
    $this->assertEquals(TRUE, TRUE, 'Test Unit Generated via Drupal Console.');
  }

}
