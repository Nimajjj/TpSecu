<?php 
require_once("vendor/autoload.php");

$uri = substr($_SERVER['REQUEST_URI'], 1);
$method = $_SERVER['REQUEST_METHOD'];
$isGetMethod = $method === 'GET';

$view = "template/";

if ($uri === 'signup' && $isGetMethod) {
  $view .= "signup.php";
}
else if ($uri === "signin") {
  $view .= "signin.php";
}
else if ($uri === "change_pwd") {
  $view .= "change_pwd.php";
}
else if ($uri === "signed_up") {
  $view .= "signed_up.php";
}
else if ($uri === "verify") {
  $view .= "verify.php";
}
else {
  $view .= "root.php";
}

require_once($view);
