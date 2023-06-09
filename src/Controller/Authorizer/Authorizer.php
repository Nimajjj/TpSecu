<?php
namespace App\Controller\Authorizer;

use App\Model\Session;
use App\ModelFabric\SessionFabric;

class Authorizer {
  public static function ProvideToken(string $_guid, int &$_sessionId): string {
    $salt = uniqid();
    $token = uniqid();

    $session = new Session(
      hash("sha512", $token . $salt),
      $salt,
      date("Y-m-d H:i:s", time() + 1800), // valid for 30 minutes
      $_guid
    );
    
    SessionFabric::Insert($session);
    $_sessionId = SessionFabric::SelectByToken(hash("sha512", $token . $salt))->id;

    $ivlen = openssl_cipher_iv_length("aes-256-cbc");
    $iv = openssl_random_pseudo_bytes($ivlen);
    $cipherToken = openssl_encrypt(
      $token . "-" . $salt,
      "aes-256-cbc",
      $_ENV["TOKEN_SECRET"],
      0,
      $iv
    );

    return $cipherToken;
  }
 
 
  public static function GetSessionState(): SessionState {
    // if no session is not set
    if (!isset($_COOKIE["session"])) {
      return SessionState::NoSessionSetted;
    }

    $sessionId = $_COOKIE['session']['id'];
    $sessionToken = $_COOKIE['session']['token'];

    $session = SessionFabric::SelectById($sessionId);

    $ivlen = openssl_cipher_iv_length("aes-256-cbc");
    $iv = substr($sessionToken, 0, $ivlen);
    $cipherText = substr($sessionToken, $ivlen);
    $originalToken = openssl_decrypt(
      $cipherText,
      "aes-256-cbc",
      $_ENV["TOKEN_SECRET"],
      0,
      $iv
    );

    list($token, $salt) = explode('.', $originalToken);
    $hash = hash("sha512", $token . $salt);

    // if tokens match
    if ($hash == $session->token) {
      // if token is expired
      return (date("Y-m-d H:i:s", time()) > $session->validity)
        ? SessionState::SessionExpired
        : SessionState::SignedIn;
    }

    // if tokens does not match
    return SessionState::WrongToken;
  }
}
