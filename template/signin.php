<?php ob_start(); ?>

<h1>Sign in</h1>
<br><br>

<form action="" method="POST">
  <input type="email" name="email" placeholder="email" required/>
  <input type="password" name="pwd" placeholder="pwd" required/>
  <input type="submit" name="signin" value="Submit" required/>
</form>

<?php
use App\Controller\SignInController;

if (isset($_POST["signin"])) {
  echo SignInController::Execute();
}

$content = ob_get_clean();
require('base.php')
?>
