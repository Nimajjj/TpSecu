<?php
namespace App\ModelFabric;

use App\Dal\Query;
use App\Model\AccountAttempts;

class AccountAttemptsFabric extends A_ModelFabric {
  // Select
  public static function SelectByGUID(string $_guid): AccountAttempts {
    // prepare query
    $query = new Query("accountattempts");
    $query->Condition("guid", "=", $_guid);

    // execute query
    $row = self::$dal->DbSelect($query);

    // if select return null
    if (!$row) {
      return null;
    }

    // create user model
    $accountAttempts = new AccountAttempts(
      $row["attempt_at"],
      $row["guid"]
    );

    return $accountAttempts;

  }

  public static function CountAttemptsOfGuid(string $_guid): int {
    $query = new Query("accountattempts");
    $query->Condition("guid", "=", $_guid);
    $query->Condition("attempt_at", ">", date("Y-m-d H:i:s", time() - 300));  // less than 5 minutes ago
    $query->select = "COUNT(*) as count";  

    $result = self::$dal->DbSelect($query);
    return (int) $result['count'];
  }


  public static function SelectByEmail(string $_email) {
    $user = UserFabric::SelectByEmail($_email);

    return self::SelectByGUID($user->guid);
  }


  // Insert
  public static function Insert(mixed $_model): bool {
    $query = new Query("accountattempts");
    $query->Parameter([
      "attempt_at" => $_model->attempt_at,
    ]);

    if ($_model->guid) {
      $query->Parameter(["guid" => $_model->guid]);
    }

    return self::$dal->DbInsert($query);
  }
}

