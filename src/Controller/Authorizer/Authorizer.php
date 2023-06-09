<?php
namespace App\Controller\Authorizer;

use App\Model\Session;
use App\ModelFabric\SessionFabric;

class Authorizer {
  public static function ProvideToken(string $_guid, int &$_sessionId): string {
    // generate token and salt
    $salt = uniqid();
    $token = uniqid();

    // store token and salt in new session in db
    $session = new Session(
      hash("sha512", $token . $salt),
      $salt,
      date("Y-m-d H:i:s", time() + 1800), // valid for 30 minutes
      $_guid
    );
    
    SessionFabric::Insert($session);
    $_sessionId = SessionFabric::SelectByToken(hash("sha512", $token . $salt))->id;

    // cipher token
    $first_key = base64_decode($_ENV["TOKEN_SECRET"]);
    $second_key = base64_decode($_ENV["TOKEN_SECRET_2"]);    
        
    $method = "aes-256-cbc";    
    $iv_length = openssl_cipher_iv_length($method);
    $iv = openssl_random_pseudo_bytes($iv_length);
            
    $first_encrypted = openssl_encrypt($token,$method,$first_key, OPENSSL_RAW_DATA ,$iv);    
    $second_encrypted = hash_hmac('sha3-512', $first_encrypted, $second_key, TRUE);
                
    $output = base64_encode($iv.$second_encrypted.$first_encrypted);    

    // provide token
    return $output;
  }
 
 
  public static function GetSessionState(): SessionState {
    // if no session is not set
    if (!isset($_COOKIE["session"])) {
      return SessionState::NoSessionSetted;
    }

    $sessionId = $_COOKIE['session']['id'];   
    $sessionToken = $_COOKIE['session']['token'];

    $session = SessionFabric::SelectById($sessionId);

    $first_key = base64_decode($_ENV["TOKEN_SECRET"]);
    $second_key = base64_decode($_ENV["TOKEN_SECRET_2"]);    
    $mix = base64_decode($sessionToken);
            
    $method = "aes-256-cbc";    
    $iv_length = openssl_cipher_iv_length($method);
                
    $iv = substr($mix,0,$iv_length);
    $second_encrypted = substr($mix,$iv_length,64);
    $first_encrypted = substr($mix,$iv_length+64);
                
    $data = openssl_decrypt($first_encrypted,$method,$first_key,OPENSSL_RAW_DATA,$iv);
    $second_encrypted_new = hash_hmac('sha3-512', $first_encrypted, $second_key, TRUE);
        
    if (!hash_equals($second_encrypted,$second_encrypted_new)) {
      return SessionState::HashDontMatch;
    }

    // Check token                        
    if (hash("sha512", $data . $session->salt) == $session->token) {
      return (time() > strtotime($session->validity)) ?
        SessionState::SessionExpired : 
        SessionState::SignedIn;             
    }

    return SessionState::WrongToken;       
  }
}
