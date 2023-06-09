<?php
namespace App\Controller;

use App\Dal\Dal;
use App\Dal\Query;
use App\Model\User;
use App\Model\AccountTmp;
use App\ModelFabric\UserFabric;
use App\ModelFabric\AccountTmpFabric;
use App\Controller\Authentificator\Authentificator;
use App\Controller\Authentificator\Identifier;
use App\Controller\Authentificator\IdentifyCase;
use App\Controller\SecuredActioner\SecuredActioner;

class SignUpController {
  public static function Execute() : string {
    $response = "";
    
    $email = $_POST["email"];
    $pwd = $_POST["pwd"];
    $pwd_conf = $_POST["confirm_pwd"];

    // Check input integrity
    if ($pwd != $pwd_conf) {
      $response = "<br>Passwords don't match, please try again.";
      return $response;
    }

    // Password is at least 8 char long
    if (strlen($pwd) < 7) {
      $response = "<br>Password must be at least 8 characters long.";
      return $response;
    }

    // Contains lowercase letter 
    if (!preg_match('/[a-z]/', $pwd)) {
      $response = "<br>Password must contains at least 1 lowercase letter";
      return $response;
    }

    // Contains uppercase letter
    if (!preg_match('/[A-Z]/', $pwd)) {
      $response = "<br>Password must contains at least 1 uppercase letter";
      return $response;
    }

    // Contains digits 
    if (!preg_match('/\d/', $pwd)) {    
      $response = "<br>Password must contains at least 1 digit";
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
    $verification_link = "/verify?otp=" . SecuredActioner::ProvideOTP($guid);
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
    $salt = uniqid();
    
    $accountTmp = new AccountTmp(
      hash("sha512", $_pwd . $salt),
      $salt,
      $guid
    );

    if (!AccountTmpFabric::Insert($accountTmp)) {
      // problem
      return "An error occured during accountTmp creation";
    }

    $dal = DAL::GetInstance();
    $query = new Query("accountauthorization");
    $query->Parameter([
      "guid" => $guid,
      "web_service" => '["Root", "SignOut", "ChangePwd", "DeleteAccount"]'
    ]);
    $dal->DbInsert($query);

    $_guid = $guid;
    return null;
  }
}
