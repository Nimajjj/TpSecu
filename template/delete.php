<?php ob_start(); ?>

<h1>Delete account</h1>
<br><br>

<form action="" method="POST">
  <input type="submit" name="delete" value="Delete Account" required/>
</form>

<?php
use App\Controller\DeleteController;

if (!App\Controller\Authorizer\Authorizer::IsAllowed("DeleteAccount")) {
  header("Location: /not_allowed");
}

if (isset($_POST["delete"])) {
  echo DeleteController::Execute();
}
if (isset($_GET["otp"])) {
  echo DeleteController::ApplyDeletion();
}

$content = ob_get_clean();
require('base.php')
?>
