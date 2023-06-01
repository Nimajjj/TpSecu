<?php

require_once("a_model_fabric.php");
require_once $_SERVER['DOCUMENT_ROOT'] . ("/model/user.php");

class UserFabric extends A_ModelFabric {
  // Select
  public static function SelectByGUID(string $_guid) {
    // prepare query
    $query = new Query("user");
    $query->Condition("guid", "=", $_guid);

    // execute query
    $row = self::$dal->DbSelect($query);

    // if select return null
    if (!$row) {
      return null;
    }

    // create user model
    $user = new User(
      $row["email"],
      $row["guid"]
    );

    return $user;
  }

  // Insert
  public static function Insert($_model) {
    $query = new Query("user");
    $query->Parameter([
      "email" => $_model->email
    ]);

    return self::$dal->DbInsert($query);
  }
}
