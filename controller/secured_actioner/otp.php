<?php

function generate_otp() : string {
  return bin2hex(random_bytes(32));
}
