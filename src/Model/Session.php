<?php
namespace App\Model;

class Session {

  public $id;
  public $guid;    
  public $token;
  public $salt;
  public $validity;
  
  public function __construct(string $_token, string $_salt, string $_validity, string $guid = null) {
    $this->id = -1;
    $this->token = $_token;  
    $this->salt = $_salt;  
    $this->validity = $_validity;  
    if ($guid) {
      $this->guid = $guid;
    }
  }

}

