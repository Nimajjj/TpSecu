<?php
namespace App\ModelFabric;

use App\Dal\Dal;

abstract class A_ModelFabric {
  static protected $dal = null;

  public static function Init() {
    self::$dal = DAL::GetInstance();
  }

  // Select
  abstract static function SelectByGUID(string $_guid);

  // Insert
  abstract static function Insert(mixed $_model): bool;
}

// Only exception where i use a public (static) method from an abstract class
// Will not break if a moron call it multiple time...
A_ModelFabric::Init();
