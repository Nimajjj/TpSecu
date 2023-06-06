<?php

namespace App\Controller\SecuredActioner;

function generate_otp() : string {
  return bin2hex(random_bytes(32));
}
