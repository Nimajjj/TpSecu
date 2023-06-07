<?php
namespace App\Controller;

use App\Model\User;
use App\Model\AccountTmp;
use App\ModelFabric\UserFabric;
use App\ModelFabric\AccountTmpFabric;
use App\Controller\Authentificator\Authentificator;
use App\Controller\Authentificator\Identifier;
use App\Controller\Authentificator\IdentifyCase;
use App\Controller\SecuredActioner\SecuredActioner;

class SignUpController {
  public static function Execute() : ?string {
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
    $guid = "";
    $response = self::registerNewUser($email, $pwd, $guid);
    if ($response) {
      return $response;
    }
    
    // Notify
    // The notifier should call the mailer here instead of the message below.
    $verification_link = SecuredActioner::ProvideOTP($guid);
    $response = "<br><br>A verification email as been sent to : " 
      . $email
      . "<br>=== EMAIL ===<br>"
      . "You have 2 hours to verify your account: "
      . '<a href="' . $verification_link . '">'
      . "VERIFY NOW"
      . "</a>"
      . "<br>=============<br>";

    return $response;
  }


  private static function registerNewUser(string $_email, string $_pwd, string &$_guid) : ?string {
    // register new user
    $guid = uniqid('', true);
    $user = new User(
      $_email,
      $guid
    );
    if (!UserFabric::Insert($user)) {
      // problem
      return "An error occured during user creation";
    }

    // create temp account and hash pwd
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

    $_guid = $guid;
    return null;
  }
}
