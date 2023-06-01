<h1>Hello World!</h1>

<?php 
require_once("dal/dal.php");
require_once("dal/query.php");
require_once("model_fabric/user_fabric.php");



// DAL
$user = UserFabric::SelectByGUID("12");
var_dump($user);
