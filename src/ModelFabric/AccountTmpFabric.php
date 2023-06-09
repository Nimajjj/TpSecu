<?php
namespace App\ModelFabric;

use App\Dal\Query;
use App\Model\AccountTmp;

class AccountTmpFabric extends A_ModelFabric {
  // Select
  public static function SelectByGUID(string $_guid) {
    // prepare query
    $query = new Query("accounttmp");
    $query->Condition("guid", "=", $_guid);

    // execute query
    $row = self::$dal->DbSelect($query);

    // if select return null
    if (!$row) {
      return null;
    }

    // create user model
    $accountTmp = new AccountTmp(
      $row["pwd"],
      $row["salt"],
      $row["guid"]
    );

    return $accountTmp;

  }


  public static function SelectByEmail(string $_email) {
    $user = UserFabric::SelectByEmail($_email);

    return ($user) ? self::SelectByGUID($user->guid) : null;
  }


  // Insert
  public static function Insert(mixed $_model): bool {
    $query = new Query("accounttmp");
    $query->Parameter([
      "pwd" => $_model->pwd,
      "salt" => $_model->salt,
    ]);

    if ($_model->guid) {
      $query->Parameter(["guid" => $_model->guid]);
    }

    return self::$dal->DbInsert($query);
  }

  public static function DeleteByGuid(string $_guid): bool {
    $query = new Query("accounttmp");
    $query->Condition("guid", "=", $_guid);
    
    return self::$dal->DbDelete($query);
  }
}
