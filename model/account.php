<?php

class Account {
  public $guid;
  public $pwd;  
  public $salt;

  public function __construct(string $pwd, string $salt, string $guid = null) {
    $this->pwd = $pwd;  
    $this->salt = $salt;  
    if ($guid) {
      $this->guid = $guid;
    }
  }
}
