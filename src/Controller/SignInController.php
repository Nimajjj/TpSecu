<?php
namespace App\Controller;

use App\Model\Account;
use App\ModelFabric\AccountFabric;

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

    // Grant access

    // Ask for token to Authorizer
    // Ciffer token
    // Provide token
    
    header("X-Token: NoupieLaSourie");
    header("Location: /");

    setcookie("session", "NouepieLaSourie", time() + 3600);

    $response = "You are connected!";
    return $response;
  }


  private static function comparePwd(Account $_target, string $_pwd): bool {
    return (hash("sha512", $_pwd . $_target->salt) == $_target->pwd);
  }

}
