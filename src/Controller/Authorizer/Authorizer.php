<?php
namespace App\Controller\Authorizer;

use App\Dal\Dal;
use App\Dal\Query;
use App\Model\Session;
use App\ModelFabric\SessionFabric;
use App\Controller\Authentificator\Authentificator;
use App\Controller\Authentificator\SessionState;

class Authorizer {
  public static function IsAllowed(string $_ws): bool {
    $guid = "";
    $dal = DAL::GetInstance();

    // Guest
    if (Authentificator::GetSessionState($guid) != SessionState::SignedIn) {
      $query = new Query("publicauthorization");
      $query->Condition("web_service", "=", $_ws);
      $query->select = "web_service";

      $webServices = $dal->DbSelect($query);
      
      return ($webServices != NULL);
    }

    // Signed in
    $query = new Query("accountauthorization");
    $query->Condition("guid", "=", $guid);
    $query->select = "web_service";

    $rawWebServices = $dal->DbSelect($query)["web_service"];
    $webServices = json_decode($rawWebServices, true);
    
    foreach ($webServices as $ws) {
      if ($ws == $_ws) {
        return true;
      }
    }
  
    return false;
  }

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
}
