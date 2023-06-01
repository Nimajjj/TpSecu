<?php

require_once("a_model_fabric.php");

class UserFabric extends A_ModelFabric {
  static private $instance = null;

  // Main
  public static function GetInstance() {
    if(!self::$instance) {
      self::$instance = new UserFabric();
    }
    return self::$instance;
  }

  // Select
  public static function SelectByGUID(string $_guid) {
    $query = new Query("user");
    $query->Condition("guid", "=", $_guid);

    return self::$dal->DbSelect($query);
  }

  // Insert
}
