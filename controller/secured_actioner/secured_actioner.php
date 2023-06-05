<?php
require_once $_SERVER['DOCUMENT_ROOT'] . ("/model/account_otp.php");
require_once $_SERVER['DOCUMENT_ROOT'] . ("/model_fabric/account_otp_fabric.php");
require_once("otp.php");

enum OtpState {
  case WaitingForValidation;
  case AlreadyValidated;
  case NotFound;
}

class SecuredActioner {
  public static function ProvideOTP(string $_guid) : string {
    $otp = generate_otp();

    $accountOtp = new AccountOTP(
      $otp,
      date("Y-m-d H:i:s", time()),
      $_guid
    );

    AccountOTPFabric::Insert($accountOtp);

    return "/verify.php/?otp=" . $otp;
  }

  public static function CheckOTP(string $_otp) : OtpState {
    return OtpState::NotFound;
  }

  private static function clearOTP(User $_user) {

  }

  private static function transfertTMP(User $_user) {

  }
}
