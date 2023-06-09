<?php
namespace App\Controller;

use App\Controller\Authentificator\Authentificator;
use App\Controller\Authentificator\SessionState;
use App\ModelFabric\AccountFabric;
use App\Dal\Dal;
use App\Dal\Query;

class ChangePwdController {
  public static function Execute() {
    $guid = "";
    if (Authentificator::GetSessionState($guid) != SessionState::SignedIn) {
      return "you are not connected";
    }

    $account = AccountFabric::SelectByGuid($guid);
    if (!$account) {
      return "account does not exists";
    }

    if (hash("sha512", $_POST["old"] . $account->salt) != $account->pwd) {
      return "old password is incorrect";
    }

    $salt = uniqid();
    $newPwd = hash("sha512", $_POST["new"] . $salt);

    $dal = DAL::GetInstance();

    $query = new Query("account");
    $query->Condition("guid", "=", $guid);
    $query->column = "pwd";
    $query->val = $newPwd;
    $dal->DbUpdate($query);

    $query->Condition("guid", "=", $guid);
    $query->column = "salt";
    $query->val = $salt;
    $dal->DbUpdate($query);
  }
}

