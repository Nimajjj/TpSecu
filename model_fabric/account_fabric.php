<?php

require_once("a_model_fabric.php");
require_once("user_fabric.php");
require_once $_SERVER['DOCUMENT_ROOT'] . ("/model/account.php");

class AccountFabric extends A_ModelFabric {
  // Select
  public static function SelectByGUID(int $_guid) {
    // prepare query
    $query = new Query("account");
    $query->Condition("guid", "=", $_guid);

    // execute query
    $row = self::$dal->DbSelect($query);

    // if select return null
    if (!$row) {
      return null;
    }

    // create user model
    $account = new Account(
      $row["pwd"],
      $row["salt"],
      $row["guid"]
    );

    return $account;

  }


  public static function SelectByEmail(string $_email) {
    $user = UserFabric::SelectByEmail($_email);

    return self::SelectByGUID($user->guid);
  }


  // Insert
  public static function Insert($_model) {
    $query = new Query("account");
    $query->Parameter([
      "pwd" => $_model->pwd,
      "salt" => $_model->salt,
    ]);

    if ($_model->guid) {
      $query->Parameter(["guid" => $_model->guid]);
    }

    return self::$dal->DbInsert($query);
  }
}
