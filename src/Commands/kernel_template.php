<?php

namespace Drupal\Tests\snake_name\Kernel;

use MPNDEV\D8TDD\KernelTestBase;

abstract class PascalNameKernelTestBase extends KernelTestBase {

  public static $modules = [
    'node',
    'field',
    'entity_reference_revisions',
    'file',
    'menu_ui',
    'snake_name',
  ];

  public function setUp() {
    parent::setUp();
    $this->installEntitySchema('node');
    $this->installEntitySchema('file');
    $this->installConfig(['field', 'node', 'snake_name']);
  }

}
