<?php
namespace App\Controller;

use App\Model\Session;
use App\ModelFabric\SessionFabric;

class Controller {
  public static function SignedIn(): bool {
    $token = $_COOKIE["session"];
    if (!token) {
      return false;
    }

    $session = SessionFabric::SelectByToken($token);
    
  }
}
