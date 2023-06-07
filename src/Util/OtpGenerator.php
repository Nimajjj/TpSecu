<?php
namespace App\Util;

class OtpGenerator {
  public static function Gen(): string {
    return bin2hex(random_bytes(32));
  }
}
