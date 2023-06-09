<?php
namespace App\Controller;

use App\Model\Account;
use App\ModelFabric\AccountFabric;
use App\Controller\Authorizer\Authorizer;
use App\ThreatMonitor\ThreatMonitor;

class SignInController {
  public static function Execute(): ?string {
    $response = null;

    $email = $_POST["email"];
    $pwd = $_POST["pwd"];

    // get target account
    $targetAccount = AccountFabric::SelectByEmail($email);
    if (!$targetAccount) {
      $response = "this email does not exists.";
      return $response;
    }

    // Add attempts to Threat Monitor.
    ThreatMonitor::AddSigninAttempt($targetAccount->guid);

    // Verify if attempts does not exceed max.
    if (ThreatMonitor::IsSigninAttemptsExceed($targetAccount->guid)) {
      $response = "<br>You exceed max signin attempts.<br>";
      return $response;
    }

    // Compare password
    if (!self::comparePwd($targetAccount, $pwd)) {
      $response = "<br>incorrect password. <br>";
      return $response;
    }

    // Grant session (for 30 minutes)
    $sessionId = 1;
    $sessionToken = Authorizer::ProvideToken($targetAccount->guid, $sessionId);
    setcookie("session[id]", $sessionId, time() + 1800);
    setcookie("session[token]", $sessionToken, time() + 1800);
    

    $response = "You are connected!";
    header("Location: /");
    return $response;
  }

 


  private static function comparePwd(Account $_target, string $_pwd): bool {
    return (hash("sha512", $_pwd . $_target->salt) == $_target->pwd);
  }

}
