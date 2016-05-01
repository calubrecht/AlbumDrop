<?php

require_once("include/config.php");
require_once("include/funcs.php");
require_once("include/user.php");
require_once("include/imageInfo.php");
session_start();


logwrite("itbegins");
$loggedIn = false;
$error = "";
if (isset($_GET["action"]))
{
  if ($_GET["action"] == "display")
  {
    logwrite("getFileByID(".$_GET["id"]. ")");
    getFileByID($_GET["id"]);
    die();
  }
}
elseif (isset($_POST["username"]))
{
  logwrite("a post");
  logwrite("login=" .$_POST["username"] . " p=" . $_POST["password"]);
  $userId = checkLogin($_POST["username"], $_POST["password"]);
  if ($userId != -1)
  {
    $loggedIn = true;
    $user = $_POST["username"];
    $_SESSION["user"] = $user;
    $_SESSION["userID"] = $userId;
    refreshPage();
  }
  else
  {
    $error = "Incorrect Credentials";
  }
}
else if (isset($_POST["logout"]))
{
  $loggedIn = false;
  session_destroy();
  refreshPage();
}
else if (isset($_SESSION["user"]))
{
  $loggedIn = true;
  $user = $_SESSION["user"];
}
if ($loggedIn)
{
  require_once("templates/gallery.php");
}
else
{
  require_once("templates/login.php");
}

?>
