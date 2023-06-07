<?php
namespace App\Router;

class Router {
  public function __construct() {
    $uri = substr(parse_url($_SERVER["REQUEST_URI"])["path"], 1);
    $view = "template/";
    $view .= ($uri != "") ? $uri : "root";

    require_once($view . ".php");
  }
}
