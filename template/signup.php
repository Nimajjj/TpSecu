<?php ob_start(); ?>

<h1>Sign up</h1>
<br><br>

<form action="" method="POST">
  <input type="email" name="email" placeholder="email" required/>
  <input type="password" name="pwd" placeholder="pwd" required/>
  <input type="password" name="confirm_pwd" placeholder="confirm pwd" required/>
  <input type="submit" name="signup" value="Submit"/>
</form>

<?php
use App\Controller\SignUpController;

if (isset($_POST["signup"])) {
  echo SignUpController::Execute();
}

$content = ob_get_clean();
require('base.php')
?>
