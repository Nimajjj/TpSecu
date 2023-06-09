<?php 
require_once("vendor/autoload.php");

use App\Router\Router;
use App\Controller\Authorizer\Authorizer;
use App\Controller\Authorizer\SessionState;
use App\ModelFabric\UserFabric;

// load .env file
Dotenv\Dotenv::createImmutable(__DIR__)->load();

// handle routes
new Router();

$guid = "";
if (Authorizer::GetSessionState($guid) == SessionState::SignedIn) {
  echo "<br>Welcome " . UserFabric::SelectByGuid($guid)->email . "<br>";
}
