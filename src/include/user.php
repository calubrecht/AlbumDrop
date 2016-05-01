<?php

function getCurrentUserId()
{
  return isset($_SESSION["userID"]) ? $_SESSION["userID"] : null;
}

function getCurrentName()
{
  return isset($_SESSION["user"]) ? $_SESSION["user"] : null;
}


?>
