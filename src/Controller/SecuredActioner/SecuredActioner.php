<?php
namespace App\Controller\SecuredActioner;

use App\Util\Debug;
use App\Util\OtpGenerator;
use App\Model\User;
use App\Model\Account;
use App\Model\AccountOTP;
use App\ModelFabric\UserFabric;
use App\ModelFabric\AccountFabric;
use App\ModelFabric\AccountOTPFabric;
use App\ModelFabric\AccountTMPFabric;

class SecuredActioner {
  public static function ProvideOTP(string $_guid) : string {
    $otp = OtpGenerator::Gen();

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

    $max_validity_time = new \DateTime();
    $max_validity_time->modify("-2 hours");   // the user has 2 hours to validate otp
    if ($accountOtp->validity > $max_validity_time) {
      self::clearOTP($accountOtp->otp);
      return OtpState::ValidityExpired;
    }

    return OtpState::WaitingForValidation;
  }

  // return:
  //   0: success
  //   1: error
  public static function ClearOTP(string $_otp): int {
    return AccountOTPFabric::DeleteByOtp($_otp);
  }

  // Must be called before ClearOTP
  // return:
  //   0: success
  //   1: error
  public static function TransfertTMP(string $_otp): int {
    // get account OTP 
    $accountOtp = AccountOTPFabric::SelectByOtp($_otp);

    if (!$accountOtp) {
      return 1;
    }

    // get account TMP
    $accountTmp = AccountTmpFabric::SelectByGUID($accountOtp->guid);

    $account = new Account(
      $accountTmp->pwd,
      $accountTmp->salt,
      $accountTmp->guid
    );
    
    // Delete account TMP
    AccountTmpFabric::DeleteByGuid($accountTmp->guid);

    return AccountFabric::Insert($account);
  }
}
