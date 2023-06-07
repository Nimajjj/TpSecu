<?php ob_start(); ?>

<h1>Verify</h1>
<br><br>

<?php
use App\Controller\VerifyController;

if (isset($_GET["otp"])) {
  echo VerifyController::Execute();
}

$content = ob_get_clean();
require('base.php')
?>

