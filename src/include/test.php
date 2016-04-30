<?php

require_once("funcs.php");

if (checkLogin("loser", "password1"))
{
  echo "Check\n";
}
else
{
  echo "NoCheck\n";
}

?>
