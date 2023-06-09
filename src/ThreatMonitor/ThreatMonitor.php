<?php
namespace App\ThreatMonitor;

use App\Model\AccountAttempts;
use App\ModelFabric\AccountAttemptsFabric;

class ThreatMonitor {
  public static function AddSigninAttempt(string $_guid) {
    $accountAttempts = new AccountAttempts(
      date("Y-m-d H:i:s", time()),
      $_guid
    );
    AccountAttemptsFabric::Insert($accountAttempts);
  }

  public static function IsSigninAttemptsExceed(string $_guid): int {
    return AccountAttemptsFabric::CountAttemptsOfGuid($_guid);
  }
}
