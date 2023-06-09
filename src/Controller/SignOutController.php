<?php
namespace App\Controller;

use App\Controller\Authentificator\Authentificator;
use App\Controller\Authentificator\SessionState;
use App\ModelFabric\SessionFabric;

class SignOutController {
  public static function Execute() {
    // Verify if is connected
    $guid = "";
    if (Authentificator::GetSessionState($guid) != SessionState::SignedIn) {
      return "aslk;jdasl;kdj";
    }
    
    // Clear cookie
    unset($_COOKIE['session[id]']);
    setcookie("session[id]", "", -1);
    unset($_COOKIE['session[token]']);
    setcookie("session[token]", "", -1);
    
    // Clear session db
    SessionFabric::DeleteOfGuid($guid);
  }
}

