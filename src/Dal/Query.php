<?php
namespace App\Dal;

class Query {
  public $table;
  public $conditions = [];
  public $parameters = [];
  
  public function __construct($_table) {
    $this->table = $_table; 
    return $this;
  }

  public function Condition($_column, $_condition, $_value) {
    $this->conditions = [
        'column' => $_column,
        'condition' => $_condition, 
        'value' => $_value
    ];
    return $this;     
  }

  public function Parameter($_parameters) {
    foreach ($_parameters as $key => $value) {
      $this->parameters[$key] = $value;
    }
    return $this;
  }
}