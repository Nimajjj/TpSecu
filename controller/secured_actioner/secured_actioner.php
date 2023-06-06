<?php
namespace App\Controller\SecuredActioner;

require_once $_SERVER['DOCUMENT_ROOT'] . ("/model/account_otp.php");
require_once $_SERVER['DOCUMENT_ROOT'] . ("/model_fabric/account_otp_fabric.php");
require_once $_SERVER['DOCUMENT_ROOT'] . ("/model_fabric/user_fabric.php");

use App\Model\User;
use App\ModelFabric\UserFabric;
use App\ModelFabric\AccountOTPFabric;

enum OtpState {
  case WaitingForValidation;
  case ValidityExpired;
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
    $accountOtp = AccountOTPFabric::SelectByOtp($_otp);

    if (!$accountOtp) {
      return OtpState::NotFound;
    }

    $user = UserFabric::SelectByGUID($accountOtp->guid);

    $max_validity_time = strtotime("1 minute");
    if (time() - strtotime($accountOtp->validity) > $max_validity_time) {
      self::clearOTP($user);
      return OtpState::ValidityExpired;
    }

    self::clearOTP($user);
    self::transfertTMP($user);

    return OtpState::WaitingForValidation;
  }

  private static function clearOTP(User $_user) {

  }

  private static function transfertTMP(User $_user) {

  }
}
