<h1>Verify</h1>

<a href="/index.php">Index</a>
<a href="/signin.php">Sign in</a>
<a href="/signup.php">Sign up</a>
<a href="/change_pwd.php">Change pwd</a>
<a href="/signedup.php">Signed up</a>
<br><br>

<?php
require_once("vendor/autoload.php");

use App\Controller\SecuredActioner\SecuredActioner;
use App\Controller\SecuredActioner\OtpState;

if (isset($_GET["otp"])) {
  $otpState = SecuredActioner::CheckOTP($_GET["otp"]);
 
  echo "<br>";
  switch ($otpState) {
    case OtpState::WaitingForValidation:
      echo "Waiting for validation";
      echo SecuredActioner::TransfertTMP($_GET["otp"]);
      echo SecuredActioner::ClearOTP($_GET["otp"]);
      break;

    case OtpState::ValidityExpired: // write err
      echo "Validity expired";
      break;
        
    case OtpState::NotFound: // write warning
      echo "The OTP does not exists.";
      break;
    
    default:
      echo "yo wtf";
      break;
  }
}
