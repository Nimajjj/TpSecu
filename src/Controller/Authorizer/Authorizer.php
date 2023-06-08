<?php
namespace App\Controller\Authorizer;

use App\Model\Session;
use App\ModelFabric\SessionFabric;

class Authorizer {
  public static function ProvideToken($_guid): string {
    $salt = uniqid();
    $token = hash("sha512", uniqid() . $salt);

    $session = new Session(
      $token,
      $salt,
      time() + 3600,
      $_guid
    );
    
    SessionFabric::Insert($session);

    return token;
  }
}
