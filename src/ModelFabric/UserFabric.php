<?php
namespace App\ModelFabric;

use App\Dal\Query;
use App\Model\User;

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


  public static function SelectByEmail(string $_email) {
    // prepare query
    $query = new Query("user");
    $query->Condition("email", "=", $_email);

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
  public static function Insert(mixed $_model): bool {
    $query = new Query("user");
    $query->Parameter([
      "guid" => $_model->guid,
      "email" => $_model->email
    ]);

    return self::$dal->DbInsert($query);
  }
}
