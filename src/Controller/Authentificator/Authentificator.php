<?php
namespace App\Controller\Authentificator;

use App\Model\Session;
use App\ModelFabric\SessionFabric;

class Authentificator {
  public static function Identify(string $_email, string $_pwd) : IdentifyCase {
    return Identifier::Identify($_email, $_pwd);
  }

  public static function SecuredActioner(string $_email) : string {
    return '<a href="http://www.tp2.localhost/veryfy/ev096DHn323PWw0skjc1"> Verify my account </a>';
  }

  public static function GetSessionState(string &$_guid): SessionState {
    // if no session is not set
    if (!isset($_COOKIE["session"])) {
      return SessionState::NoSessionSetted;
    }

    $sessionId = $_COOKIE['session']['id'];   
    $sessionToken = $_COOKIE['session']['token'];

    // get session from db
    $session = SessionFabric::SelectById($sessionId);
    if (!$session) {
      return SessionState::NoSessionSetted;
    }
    $_guid = $session->guid;

    // cipher magic
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
        
    // problem
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
