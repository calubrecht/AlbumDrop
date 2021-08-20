<?php

require_once("include/config.php");
require_once("include/funcs.php");
require_once("include/user.php");
require_once("include/imageInfo.php");
require_once("templates/imgFuncs.php");
ini_set("session.gc_maxlifetime", 30*86400);  // PHP Session lifetime (30 days)
ini_set("session.cookie_lifetime", 30*86400);  // PHP Session cookie lifetime
session_start();

$loggedIn = false;
$error = "";
$forgotPassword = false;
$postData = file_get_contents("php://input");

if (isset($_GET["action"]))
{
  if (isset($_GET["id"]))
  {
    $id = preg_replace('/\\.[^.\\s]{3,4}$/', '', $_GET["id"]);
  }
  if ($_GET["action"] == "display")
  {
    getFileByID($id);
    die();
  }
  if ($_GET["action"] == "thumb")
  {
    getFileByID($id, true);
    die();
  }
  if ($_GET["action"] == "resetPassword")
  {
    $username = getUsernameFromToken($_GET["token"]);
    if (!$username)
    {
      require_once("templates/expiredToken.php");
      die();
    }
    $token= $_GET["token"];
    $_SESSION["token"] = $token;
    require_once("templates/resetPassword.php");
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
    setUser($user, $userId);
    refreshPage();
  }
  else
  {
    $error = "Incorrect Credentials";
    $forgotPassword = true;
  }
}
else if (isset($_POST["uploadFiles"]))
{
  if (!isLoggedIn())
  {
    send403();
  }
  $files = $_FILES["files"];
  $doRedirect = !(isset($_POST["async"]) && $_POST["async"]=="y");
  if (is_array($files["name"]))
  {
    for ($index = 0; $index < count($files["name"]); $index++)
    {
      uploadImage($files["name"][$index], $files["tmp_name"][$index], $_POST["isVisible"]);
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
    uploadImage($files["name"], $files["tmp_name"], $_POST["isVisible"]);
  }
  if ($doRedirect)
  {
    refreshPage();
  }
  else
  {
    echo "Upload success";
    die();
  }
}
else if (isset($_POST["logout"]))
{
  $loggedIn = false;
  session_destroy();
  refreshPage();
}
else if ($postData != "")
{
  $data = json_decode($postData, true);
  if ($data != NULL)
  {
    // json post data
    if ($data["action"] == "register")
    {
      register($data);
      die();
    }
    if ($data["action"] == "resetPassword")
    {
      resetPassword($data);
      die();
    }
    if ($data["action"] == "doResetPassword")
    {
      doResetPassword($data);
      die();
    }
    if ($data["action"] == "delete")
    {
      echo deleteImg($data["imgId"]);
      die();
    }
    if ($data["action"] == "updateGallery")
    {
      $items = array();
      if ($data["gallery"] == "public")
      {
        $imgs = getPublicImages();
      }
      else
      {
        $imgs = getUserImages(getCurrentUserId());
      }
      foreach ($imgs as $imgId)
      {
        array_push($items, getImgThumbInfo($imgId, $data["gallery"]));
      }
      echo json_encode(["success"=>true, "data"=>$items]);
      die();
    }
  }
}
else if (isset($_SESSION["user"]))
{
  $loggedIn = true;
  $user = $_SESSION["user"];
}
$csrf_token=bin2hex(random_bytes(20));
setcookie("XSRF_TOKEN", $csrf_token, 0, '/');
if ($loggedIn)
{
  require_once("templates/gallery.php");
}
else
{
  require_once("templates/login.php");
}

?>
