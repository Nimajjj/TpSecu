<?php
namespace App\Model;

class AccountAttempts {

  public $guid;   
  public $attempt_at;

  public function __construct(string $_attempt_at, string $_guid = null) {
    $this->attempt_at = $_attempt_at;  
    if ($_guid) {
      $this->guid = $_guid;
    }
  }
}
