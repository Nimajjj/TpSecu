<h1>Index.php</h1>

<a href="index.php">Index</a>
<a href="signin.php">Sign in</a>
<a href="signup.php">Sign up</a>
<a href="change_pwd.php">Change pwd</a>
<a href="signedup.php">Signed up</a>
<br><br>

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
