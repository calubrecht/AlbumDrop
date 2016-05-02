<?php

require_once("include/config.php");
require_once("include/funcs.php");
require_once("include/user.php");
require_once("include/imageInfo.php");
session_start();

$loggedIn = false;
$error = "";
if (isset($_GET["action"]))
{
  if ($_GET["action"] == "display")
  {
    getFileByID($_GET["id"]);
    die();
  }
  if ($_GET["action"] == "thumb")
  {
    getFileByID($_GET["id"], true);
    die();
  }
}
elseif (isset($_GET["getInfo"]))
{
  $imageId = $_GET["getInfo"];
  echo getImgInfoJson($imageId);
  die();
}
elseif (isset($_GET["updateInfo"]))
{
  $imageId = $_GET["updateInfo"];
  $fileName = $_GET["fileName"];
  $isPublic = $_GET["isPublic"];
  $isVisible = $_GET["isVisible"];
  logwrite("call updateImgInfp(" + $imageId + ", " + $fileName + " ," + $isPublic + ", " + $isVisible + ")");
  echo updateImgInfo($imageId, $fileName, $isPublic, $isVisible);
  die();
}
elseif (isset($_POST["username"]))
{
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
else if (isset($_POST["uploadFiles"]))
{
  if (!isLoggedIn())
  {
    send403();
  }
  $files = $_FILES["files"];
  if (is_array($files["name"]))
  {
    for ($index = 0; $index < count($files["name"]); $index++)
    {
      uploadImage($files["name"][$index], $files["tmp_name"][$index]);
    }
  }
  else
  {
    if (isset($files["error"]) && $files["error"] > 0)
    {
      $err = $files["error"];
      if ($err == 1 || $err = 2) sendError("Upload failed. File too large.");
      if ($err > 2) sendError("Upload failed. Unknown error.");
    }
    uploadImage($files["name"], $files["tmp_name"]);
  }
  refreshPage();
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
