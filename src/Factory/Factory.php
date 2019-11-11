<?php

namespace Factory;

class Factory {

  private $quantity;
  private $concrete_class;
  private static $bag = [];

  public function __construct($concrete_class, $quantity) {
    if($quantity < 1) {
      throw new \Exception("Quantity must be a positive integer!");
    }
    $this->quantity = $quantity;
    $this->concrete_class = $concrete_class;
  }

  public static function for($concrete_class, $quantity = 1) {
    return (new static($concrete_class, $quantity));
  }

  public function make($concrete_type, $fields = [], $callback = FALSE) {
    $items = [];

    for ($i = 1;$i <= $this->quantity;$i++) {
      $fields_set = $this->getBag($concrete_type);
      foreach ($fields as $key => $field) {
        $fields_set[$key] = $field;
      }
      $item = $this->concrete_class::create($fields_set);
      if (is_callable($callback)) {
        $item = $callback($item);
      }
      $items[] = $item;
    }

    if (count($items) === 1) {
      return $items[0];
    }
    return $items;
  }

  public function create($concrete_type, $fields = [], $callback = FALSE) {
    $items = $this->make($concrete_type, $fields, $callback);

    if (is_object($items)) {
      $items->save();
      return $items;
    }

    foreach ($items as $item) {
      $item->save();
    }
    return $items;
  }

  public static function define($name, $arr) {
    self::$bag[$name] = $arr;
  }

  public static function getBag($name) {
    return self::$bag[$name];
  }

}
