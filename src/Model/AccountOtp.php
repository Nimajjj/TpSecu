<?php
namespace App\Model;

class AccountOtp {
  public $guid;    
  public $otp;     
  public $validity;

  public function __construct(string $_otp, string $_validity, string $_guid = null) {
    $this->otp = $_otp;  
    $this->validity = $_validity;  
    if ($_guid) {
      $this->guid = $_guid;
    }
  }
}
