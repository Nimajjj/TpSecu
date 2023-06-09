<?php ob_start(); ?>

<h1>Verify</h1>
<br><br>

<?php
use App\Controller\VerifyController;

if (!App\Controller\Authorizer\Authorizer::IsAllowed("Verify")) {
  header("Location: /not_allowed");
}
if (isset($_GET["otp"])) {
  echo VerifyController::Execute();
}

$content = ob_get_clean();
require('base.php')
?>

