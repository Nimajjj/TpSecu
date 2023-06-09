<?php 
require_once("vendor/autoload.php");

use App\Router\Router;
use App\Controller\Authorizer\Authorizer;

// load .env file
Dotenv\Dotenv::createImmutable(__DIR__)->load();

// handle routes
new Router();

var_dump(Authorizer::GetSessionState());
