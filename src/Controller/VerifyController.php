<?php
namespace App\Controller;

use App\Controller\SecuredActioner\SecuredActioner;
use App\Controller\SecuredActioner\OtpState;

class VerifyController {
  public static function Execute(): ?string {
    $response = null;

    $otpState = SecuredActioner::CheckOTP($_GET["otp"]);
   
    switch ($otpState) {
      case OtpState::WaitingForValidation:
        $response = "Waiting for validation";
        SecuredActioner::TransfertTMP($_GET["otp"]);
        SecuredActioner::ClearOTP($_GET["otp"]);
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
