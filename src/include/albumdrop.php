<?php

require_once("include/funcs.php");

if (isset($_GET["action"]))
{
  echo 'action=' . $_GET["action"];
  if (isset($_GET["id"]))
  {
    echo 'id=' . $_GET["id"];
  }
}
else
{
  // Check login
  require_once("templates/login.php");
}

?>
