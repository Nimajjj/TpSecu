<?php 
require_once("vendor/autoload.php");

$uri = substr($_SERVER['REQUEST_URI'], 1);
$method = $_SERVER['REQUEST_METHOD'];
$isGetMethod = $method === 'GET';

$view = "template/";

if ($uri === 'signup' && $isGetMethod) {
  $view .= "signup.php";
}
else {
  $view .= "root.php";
}

require_once($view);
