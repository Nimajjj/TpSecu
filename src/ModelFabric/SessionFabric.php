<?php
namespace App\ModelFabric;

use App\Dal\Query;
use App\Model\Account;

class SessionFabric extends A_ModelFabric {
  // Select
  public static function SelectByToken(string $_token) {
    // prepare query
    $query = new Query("session");
    $query->Condition("token", "=", $_token);

    // execute query
    $row = self::$dal->DbSelect($query);

    // if select return null
    if (!$row) {
      return null;
    }

    // create user model
    $session = new Session(
      $row["token"],
      $row["salt"],
      $row["guid"]
    );

    return $session;

  }

  // Insert
  public static function Insert($_model) {
    $query = new Query("session");
    $query->Parameter([
      "token" => $_model->token
      "salt" => $_model->salt
    ]);

    if ($_model->guid) {
      $query->Parameter(["guid" => $_model->guid]);
    }

    return self::$dal->DbInsert($query);
  }
}
