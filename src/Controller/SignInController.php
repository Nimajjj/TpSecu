<?php
namespace App\Controller;

use App\Model\Account;
use App\ModelFabric\AccountFabric;
use App\Controller\Authorizer;

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
    // Verify is attempts does not exceed max.

    // Compare password
    if (!self::comparePwd($targetAccount, $pwd)) {
      $response = "<br>incorrect password. <br>";
      return $response;
    }

    // Grant session
    $token = Authorizer::ProvideToken($targetAccount->guid);
    setcookie("session", $token);
    

    $response = "You are connected!";
    return $response;
  }


  private static function comparePwd(Account $_target, string $_pwd): bool {
    return (hash("sha512", $_pwd . $_target->salt) == $_target->pwd);
  }

}
