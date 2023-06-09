<?php
namespace App\Controller;

use App\Controller\SignOutController;
use App\Controller\SecuredActioner\SecuredActioner;
use App\Controller\Authentificator\Authentificator;
use App\Controller\Authentificator\SessionState;
use App\Dal\Dal;
use App\Dal\Query;

class DeleteController {
  public static function Execute(): ?string {
    $guid = "";
    if (Authentificator::GetSessionState($guid) != SessionState::SignedIn) {
      return "you are not connected";
    }

    $verification_link = "/delete?otp=" . SecuredActioner::ProvideOTP($guid);
    $response = "<br><br>A verification email as been sent to : " 
      . "<br>=== EMAIL ===<br>"
      . "You have 2 hours to complete your account deletion: "
      . '<a href="' . $verification_link . '">'
      . "DELETE NOW"
      . "</a>"
      . "<br>=============<br>";

    return $response;
  }

  public static function ApplyDeletion() {
    $guid = "";
    if (Authentificator::GetSessionState($guid) != SessionState::SignedIn) {
      return "you are not connected";
    }

    SignOutController::Execute();

    $dal = Dal::GetInstance();
    
    $query = new Query("account");
    $query->Condition("guid", "=", $guid);

    $dal->DbDelete($query);
    $query->table = "accountattempts";
    $dal->DbDelete($query);
    $query->table = "accountauthorization";
    $dal->DbDelete($query);
    $query->table = "accountotp";
    $dal->DbDelete($query);
    $query->table = "accounttmp";
    $dal->DbDelete($query);
    $query->table = "session";
    $dal->DbDelete($query);

    $query->table = "user";
    $dal->DbDelete($query);
  }
}
