<?php
require_once("identifier.php");

class Authentificator {
  public static function Identify(string $_email, string $_pwd) : IdentifyCase {
    return Identifier::Identify($_email, $_pwd);
  }

  public static function SecuredActioner(string $_email) : string {
    return '<a href="http://www.tp2.localhost/veryfy/ev096DHn323PWw0skjc1"> Verify my account </a>';
  }
}
