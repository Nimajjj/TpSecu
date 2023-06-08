<?php
namespace App\Model;

class Session {

  public $guid;    
  public $token;
  public $salt;
  
  public function __construct(string $_token, string $_salt, string $guid = null) {
    $this->token = $_token;  
    $this->salt = $_salt;  
    if ($guid) {
      $this->guid = $guid;
    }
  }

}

