<?php
namespace App\Controller\Authentificator;

enum SessionState {
  case SignedIn;
  case SessionExpired;
  case WrongToken;
  case NoSessionSetted;
  case HashDontMatch;
}
