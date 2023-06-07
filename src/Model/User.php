<?php
namespace App\Model;

class User {

  public $guid;    
  public $email;
  
  public function __construct(string $email, string $guid = null) {
    $this->email = $email;  
    if ($guid) {
      $this->guid = $guid;
    }
  }

}
