<?php
namespace App\Controller;

use App\Controller\SecuredActioner\SecuredActioner;
use App\Controller\SecuredActioner\OtpState;

class VerifyController {
  public static function Execute(): string {
    $response = "";

    $otpState = SecuredActioner::CheckOTP($_GET["otp"]);
   
    switch ($otpState) {
      case OtpState::WaitingForValidation:
        SecuredActioner::TransfertTMP($_GET["otp"]);
        SecuredActioner::ClearOTP($_GET["otp"]);
        $response = "Your account has been validated<br>Please Sign in";
        break;

      case OtpState::ValidityExpired: // write err
        $response = "Validity expired";
        break;
          
      case OtpState::NotFound: // write warning
        $response = "The OTP does not exists.";
        break;
      
      default:
        $response = "yo wtf";
        break;
    }

    return $response;
  }
}
