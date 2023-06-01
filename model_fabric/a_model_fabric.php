<?php

require_once("dal/dal.php");

abstract class A_ModelFabric {
  static protected $dal = null;

  public static function Init() {
    self::$dal = DAL::GetInstance();
  }

  // Select
  abstract static function SelectByGUID(string $_guid);

  // Insert
  abstract public function Insert($_model);
}
A_ModelFabric::Init();
