<?php
namespace App\Dal;

use mysqli;

class DAL {
  private static $instance = null;
  private $db = null;
  
  private function __construct() {
    $this->db = new mysqli(
      $_ENV["HOST"],
      $_ENV["USERNAME"],
      $_ENV["PASSWORD"],
      $_ENV["DB_NAME"]
    );
  }
  
  public static function GetInstance(): DAL {
    if(!self::$instance) {
      self::$instance = new DAL();
    }
    return self::$instance;
  }

  public function DbSelect(Query $_q): ?array {
    $query = "SELECT " . $_q->select . " FROM " 
      . $_q->table 
      . " WHERE ";

    $i = 0;
    foreach($_q->conditions as $condition) {
      $query .= $condition["column"] . $condition["condition"];

      $query .= (gettype($condition["value"]) != "integer")
        ? '"' . $condition["value"] . '"'
        : $condition["value"];

      $i += 1;
      $query .= ($i < count($_q->conditions)) 
        ? " and "
        : ";";
    }

    $result = $this->db->query($query) or die("Unable to select db");
    $row = $result->fetch_assoc();

    return $row;
  }

  public function DbInsert($_q): bool {
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

    $preparedQuerry = $this->db->prepare($query);
    $succeed = $preparedQuerry->execute($data);

    return $succeed;
  }

  public function DbDelete(Query $_q): bool {
    $query = "DELETE FROM " 
      . $_q->table 
      . " WHERE ";

    $i = 0;
    foreach($_q->conditions as $condition) {
      $query .= $condition["column"] . $condition["condition"];

      $query .= (gettype($condition["value"]) != "integer")
        ? '"' . $condition["value"] . '"'
        : $condition["value"];

      $i += 1;
      $query .= ($i < count($_q->conditions)) 
        ? " and "
        : ";";
    }

    $preparedQuerry = $this->db->prepare($query);
    $succeed = $preparedQuerry->execute();

    return $succeed;
  }

  public function DbUpdate(Query $_q): bool {
    $query = "UPDATE "
      . $_q->table
      . " SET "
      . $_q->column
      . " = "
      . '"' . $_q->val . '"'
      . " WHERE ";
      
    $i = 0;
    foreach($_q->conditions as $condition) {
      $query .= $condition["column"] . $condition["condition"];

      $query .= (gettype($condition["value"]) != "integer")
        ? '"' . $condition["value"] . '"'
        : $condition["value"];

      $i += 1;
      $query .= ($i < count($_q->conditions)) 
        ? " and "
        : ";";
    }

    $preparedQuerry = $this->db->prepare($query);
    $succeed = $preparedQuerry->execute();

    return $succeed;
  }
}
