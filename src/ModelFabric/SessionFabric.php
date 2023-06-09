<?php
namespace App\ModelFabric;

use App\Dal\Query;
use App\Model\Session;

class SessionFabric extends A_ModelFabric {
  // Select
  public static function SelectByGUID(string $_guid) {
    return null;
  }

  public static function SelectById(int $_id) {
    $query = new Query("session");
    $query->Condition("id", "=", $_id);

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
      $row["validity"],
      $row["guid"]
    );
    $session->id = $row["id"];

    return $session;
  }

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
      $row["validity"],
      $row["guid"]
    );
    $session->id = $row["id"];

    return $session;

  }

  // Insert
  public static function Insert($_model) {
    $query = new Query("session");
    $query->Parameter([
      "token" => $_model->token,
      "salt" => $_model->salt,
      "validity" => $_model->validity
    ]);

    if ($_model->guid) {
      $query->Parameter(["guid" => $_model->guid]);
    }

    return self::$dal->DbInsert($query);
  }

  // Delete
  public static function DeleteOfGuid($_guid) {
    $query = new Query("session");
    $query->Condition("guid", "=", $_guid);

    return self::$dal->DbDelete($query);
  }
}
