<?php

function getCurrentUserId()
{
  return isset($_SESSION["userID"]) ? $_SESSION["userID"] : null;
}

function isLoggedIn()
{
  return isset($_SESSION["userID"]);
}

function getCurrentName()
{
  return isset($_SESSION["user"]) ? $_SESSION["user"] : null;
}


?>
