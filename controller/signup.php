<?php
require_once $_SERVER['DOCUMENT_ROOT'] . ("/model_fabric/user_fabric.php");
require_once $_SERVER['DOCUMENT_ROOT'] . ("/model_fabric/account_tmp_fabric.php");
require_once("authentificator/authentificator.php");
require_once("authentificator/identifier.php");

class SignUp {
  public static function Execute() : string {
    $response = null;
    
    $email = $_POST["email"];
    $pwd = $_POST["pwd"];
    $pwd_conf = $_POST["confirm_pwd"];

    // Check input integrity
    if ($pwd != $pwd_conf) {
      $response = "<br>Passwords don't match, please try again.";
      return $response;
    }

    // Run chain of responsability

    // Identify
    $identified = Authentificator::Identify($email, $pwd);
    if ($identified != IdentifyCase::UserNotFound) {
     $response = "<br>This email is already used.";
     return $response;
    }

    // Register
    $response = self::registerNewUser($email, $pwd);
    if ($response) {
      return $response;
    }
    
    // Notify
    // The notifier should call the mailer here instead of the message below.
    $verification_link = Authentificator::SecuredActioner($email);
    $response = "<br><br>A verification email as been sent to : " 
      . $email
      . "<br>=== EMAIL ===<br>"
      . $verification_link
      . "<br>=============<br>";

    return $response;
  }


  private static function registerNewUser(string $_email, string $_pwd) : ?string {
    // register new user
    $user = new User($_email);
    if (!UserFabric::Insert($user)) {
      // problem
      return "An error occured during user creation";
    }

    // create temp account and hash pwd
    $guid = UserFabric::SelectByEmail($_email)->guid;
    $salt = random_bytes(16);
    
    $accountTmp = new AccountTmp(
      hash("sha512", $_pwd . $salt),
      $salt,
      $guid
    );

    if (!AccountTmpFabric::Insert($accountTmp)) {
      // problem
      return "An error occured during accountTmp creation";
    }

    return null;
  }
}
