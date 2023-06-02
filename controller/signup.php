<?php
require_once("authentificator/authentificator.php");
require_once("authentificator/identifier.php");

class SignUp {
  public static function Execute() {
    $response = null;
    
    $email = $_POST["email"];
    $pwd = $_POST["pwd"];
    $pwd_conf = $_POST["confirm_pwd"];

    // Check input integrity
    if ($pwd != $pwd_conf) {
      $response = "Passwords don't match, please try again";
      return $response;
    }

    // Run chain of responsability
    // Authentificator :

    $identified = Authentificator::Identify($email, $pwd);
    if ($identified != IdentifyCase::UserNotFound) {
     $response = "This email is already used.";
     return $response;
    }
    
    // The notifier should call the mailer here instead of the message below.
    $verification_link = Authentificator::SecuredActioner($email);
    $response = "<br><br>An verification email as been sent to : " 
      . $email
      . "<br>=== EMAIL ===<br>"
      . $verification_link
      . "<br>=============<br>";

    return $response;
  }
}
