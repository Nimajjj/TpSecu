<h1>Hello World!</h1>

<?php 
require_once("dal/dal.php");
require_once("dal/query.php");
require_once("model_fabric/user_fabric.php");



// DAL
$u1 = UserFabric::SelectByGUID(1);
var_dump($u1);
echo "<br>";

$u2 = UserFabric::SelectByEmail("garfield@wanadoo.com");
var_dump($u2);
echo "<br>";
