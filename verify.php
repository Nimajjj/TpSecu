<h1>Verify</h1>

<a href="index.php">Index</a>
<a href="signin.php">Sign in</a>
<a href="signup.php">Sign up</a>
<a href="change_pwd.php">Change pwd</a>
<a href="signedup.php">Signed up</a>
<br><br>

<?php
require_once $_SERVER['DOCUMENT_ROOT'] . ("/controller/secured_actioner/secured_actioner.php");

if (isset($_GET["otp"])) {
  $response = SecuredActioner::CheckOTP($_GET["otp"]);

  switch ($response) {
    case OtpState::WaitingForValidation:
      echo "Waiting for validation";
      break;

    case OtpState::AlreadyValidated:
      echo "Already validated";
      break;

    case OtpState::NotFound:
      echo "Not found";
      break;
    
    default:
      echo "yo wtf";
      break;
  }
}
