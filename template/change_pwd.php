<?php ob_start(); ?>

<h1>Change pwd</h1>
<br><br>

<form action="" method="POST">
  <input type="password" name="old" placeholder="old password" required/>
  <input type="password" name="new" placeholder="new password" required/>
  <input type="submit" name="change" value="Submit" required/>
</form>

<?php
use App\Controller\ChangePwdController;

if (isset($_POST["change"])) {
  echo ChangePwdController::Execute();
}

$content = ob_get_clean();
require('base.php')
?>

