<?php ob_start(); ?>

<h1>Signout</h1>
<br><br>

<?php
use App\Controller\SignOutController;

if (!App\Controller\Authorizer\Authorizer::IsAllowed("SignOut")) {
  header("Location: /not_allowed");
  return;
}
echo SignOutController::Execute();

$content = ob_get_clean();
header("Location: /");
require('base.php')
?>


