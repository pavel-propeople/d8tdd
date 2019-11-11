<?php

namespace MPNDEV\D8TDD;

use Factory\Factory;
use \Drupal\KernelTests\Core\Entity\EntityKernelTestBase;
use Http\JsonRequest;

abstract class KernelTestBase extends EntityKernelTestBase {

  public function setUp() {
    parent::setUp();
  }

  public function factory($concrete_class, $quantity = 1) {
    return Factory::for($concrete_class, $quantity);
  }

  public function jsonRequest($url) {
    return JsonRequest::to($url);
  }

  public function tearDown() {
    parent::tearDown();
  }

}
