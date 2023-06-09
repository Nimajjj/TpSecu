<?php
namespace App\ModelFabric;

use App\Dal\Query;
use App\Model\AccountOtp;

class AccountOtpFabric extends A_ModelFabric {
  // Select
  public static function SelectByGUID(string $_guid) : ?AccountOtp  {
    // prepare query
    $query = new Query("accountotp");
    $query->Condition("guid", "=", $_guid);

    // execute query
    $row = self::$dal->DbSelect($query);

    // if select return null
    if (!$row) {
      return null;
    }

    // create user model
    $account_otp = new AccountOtp(
      $row["otp"],
      $row["validity"],
      $row["guid"]
    );

    return $account_otp;

  }

  public static function SelectByOtp(string $_otp) : ?AccountOtp {
    // prepare query
    $query = new Query("accountotp");
    $query->Condition("otp", "=", $_otp);

    // execute query
    $row = self::$dal->DbSelect($query);

    // if select return null
    if (!$row) {
      return null;
    }

    // create user model
    $account_otp = new AccountOtp(
      $row["otp"],
      $row["validity"],
      $row["guid"]
    );

    return $account_otp;
  }


  // Insert
  public static function Insert(mixed $_model): bool {
    $query = new Query("accountotp");
    $query->Parameter([
      "otp" => $_model->otp,
      "validity" => $_model->validity
    ]);

    if ($_model->guid) {
      $query->Parameter(["guid" => $_model->guid]);
    }

    return self::$dal->DbInsert($query);
  }

  // Delete
  public static function DeleteByOTp(string $_otp): bool {
    $query = new Query("accountotp");
    $query->Condition("otp", "=", $_otp);
    
    return self::$dal->DbDelete($query);
  }
}
