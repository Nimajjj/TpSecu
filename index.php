<?php 
require_once("vendor/autoload.php");

use App\Dal\Dal;
use App\Dal\Query;
use App\Router\Router;
use App\Controller\Authentificator\Authentificator;
use App\Controller\Authentificator\SessionState;
use App\Controller\Authorizer\Authorizer;
use App\ModelFabric\UserFabric;

// load .env file
Dotenv\Dotenv::createImmutable(__DIR__)->load();

// handle routes
new Router();

$guid = "";
if (Authentificator::GetSessionState($guid) == SessionState::SignedIn) {
  echo "<br>Welcome " . UserFabric::SelectByGuid($guid)->email . "<br>";
}
