<?php
namespace App\Dal;

require_once("Dbconfig.php");

use mysqli;

class DAL {
  private static $instance = null;
  private $db = null;
  
  private function __construct() {
    $this->db = new mysqli(HOST, USERNAME, PASSWORD, DB_NAME);
  }
  
  public static function GetInstance() {
    if(!self::$instance) {
      self::$instance = new DAL();
    }
    return self::$instance;
  }

  public function DbSelect(Query $_q) {
    $query = "SELECT * FROM " 
      . $_q->table 
      . " WHERE " 
      . $_q->conditions["column"]
      . $_q->conditions["condition"];

    if (gettype($_q->conditions["value"]) != "integer") {
      $query .= '"'
        . $_q->conditions["value"]
        . '";';
    }
    else {
      $query .= $_q->conditions["value"]
        . ";";
    }

    echo "<br>" . $query;

    $result = $this->db->query($query) or die("Unable to select db");
    $row = $result->fetch_assoc();

    return $row;
  }

  public function DbInsert($_q) {
    $data = [];
    $interogation = "";
    $query = "INSERT INTO " . $_q->table . " (";

    $i = 0;
    foreach ($_q->parameters as $key => $value) {
      $query .= $key;
      $interogation .= "?";
      array_push($data, $value);
      if ($i != count($_q->parameters) - 1) {
        $query .= ", ";
        $interogation .= ", ";
      }
      $i++;
    }

    $query .= ") VALUES (" . $interogation . ");";

    echo "<br>" . $query;

    $preparedQuerry = $this->db->prepare($query);
    $succeed = $preparedQuerry->execute($data);

    return $succeed;
  }

  public function DbDelete(Query $_q) {
    $query = "DELETE FROM " 
      . $_q->table 
      . " WHERE " 
      . $_q->conditions["column"]
      . $_q->conditions["condition"];

    if (gettype($_q->conditions["value"]) != "integer") {
      $query .= '"'
        . $_q->conditions["value"]
        . '";';
    }
    else {
      $query .= $_q->conditions["value"]
        . ";";
    }

    echo "<br>" . $query;

    $preparedQuerry = $this->db->prepare($query);
    $succeed = $preparedQuerry->execute();

    return $succeed;

  }
}