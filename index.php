<?php 
require_once("vendor/autoload.php");

use App\Controller\SignUpController;
use App\Controller\VerifyController;

$uri = substr(parse_url($_SERVER["REQUEST_URI"])["path"], 1);
$method = $_SERVER['REQUEST_METHOD'];
$isGetMethod = $method === 'GET';

$view = "template/";

if ($uri === 'signup') {
  $view .= "signup.php";
  if (isset($_POST["signup"])) {
    echo SignUpController::Execute();
  }
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
  if (isset($_GET["otp"])) {
    echo VerifyController::Execute();
  }
}
else {
  $view .= "root.php";
}

require_once($view);
