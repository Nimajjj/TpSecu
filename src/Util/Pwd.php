<?php
namespace App\Util;

function HashPassword($_pwd, $_salt) : string {
  return hash("sha512", $_pwd . $_salt);
}

function ComparePassword($_hashed_pwd, $_salt, $_pwd) : bool {
  return (HashPassword($_pwd, $_salt) === $_hashed_pwd)
}
