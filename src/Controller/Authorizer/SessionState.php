<?php
namespace App\Controller\Authorizer;

enum SessionState {
  case SignedIn;
  case SessionExpired;
  case WrongToken;
  case NoSessionSetted;
}
