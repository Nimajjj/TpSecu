<?php
namespace App\Dal;

class Query {
  public $table;
  public $conditions = [];
  public $parameters = [];
  public $select = "*";
  public $column;
  public $val;
  
  public function __construct($_table) {
    $this->table = $_table; 
    return $this;
  }

  public function Condition(string $_column,string  $_condition,string  $_value) {
    array_push(
      $this->conditions, 
      [ 
        'column' => $_column,
        'condition' => $_condition, 
        'value' => $_value
      ]
    );
    return $this;     
  }

  public function Parameter(array $_parameters) {
    foreach ($_parameters as $key => $value) {
      $this->parameters[$key] = $value;
    }
    return $this;
  }
}
