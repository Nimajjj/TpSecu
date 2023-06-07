<?php
namespace App\Controller\SecuredActioner;

enum OtpState {
  case WaitingForValidation;
  case ValidityExpired;
  case NotFound;
}
