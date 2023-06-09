<?php
namespace App\Controller\Authentificator;

use App\ModelFabric\UserFabric;
use App\ModelFabric\AccountFabric;
use App\ModelFabric\AccountTmpFabric;

enum IdentifyCase {
  case UserNotFound;
  case ValidAccount;
  case ValidAccountTmp;
  case WrongPwd;
  case WrongPwdTmp;
  case WTF;
}

class Identifier {
  public static function Identify(string $_email, string $_pwd) : IdentifyCase {
    // get user
    $user = UserFabric::SelectByEmail($_email);
    if (!$user) {
      $accountTmp = AccountTmpFabric::SelectByEmail($_email);  // get accoutnTmp

      if ($accountTmp) { 
        if ($accountTmp->pwd == $_pwd) {
          return IdentifyCase::ValidAccountTmp; // correct accountTmp
        }
        return IdentifyCase::WrongPwdTmp; // wrong pwd accountTmp
      }

      return IdentifyCase::UserNotFound;  // no user
    }

    // get account
    $account = AccountFabric::SelectByGUID($user->guid);
    if ($account) {
      if ($account->pwd == $_pwd) {
        return IdentifyCase::ValidAccount;
      }
      return IdentifyCase::WrongPwd;
    }

    return IdentifyCase::WTF;   // should not get here
  }
}
