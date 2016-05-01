<?php

function logwrite($info)
{
  $file = fopen("debug.log","a");
  fwrite($file,$info);
  fwrite($file,"\n");
  fclose($file);
}

require_once("db.php");
function checkLogin($user, $password)
{
  global $db; 
  $results = $db->queryOneRow("SELECT pwHash, idusers  FROM users WHERE login=?", "$user");
  if($results)
  {
    $dbPW = $results["pwHash"];
    return password_verify($password, $dbPW) ? $results["idusers"] : -1;
  }
  else
  {
    return -1;
  }
}

function refreshPage()
{
  header("Location: http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
  die();
}

function send404()
{
  header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");
  require_once("templates/404.php");
  die();
}

function send403()
{
  header($_SERVER["SERVER_PROTOCOL"]." 403 Forbidden");
  //require_once("templates/403.php");
  die();
}

function getFileByID($id, $thumb=false)
{
  global $db; 
  $results =
    $db->queryOneRow("SELECT fileLoc,thumbLoc,originalName,isPublic,isVisible,owner FROM images WHERE id=?", $id);
  if (!$results)
  {
    send404();
  }
  $fileName = $thumb ? $results["thumbLoc"] : $results["fileLoc"];
  if ($results["isVisible"] != 1)
  {
    if (null == getCurrentUserId())
    {
      send404();
    }
  }
  if ($results["isPublic"] != 1)
  {
    if ($results["owner"] != getCurrentUserId())
    {
      send404();
    }
  }
  getFileByName($fileName, $results["originalName"]);
}

function getFileByName($fileName, $originalName)
{
  if (file_exists($fileName))
  {
    header('Content-Type: ' . mime_content_type($fileName));
    header('Content-Disposition: filename="'.basename($originalName).'"');
    header('Content-Length: ' . filesize($fileName));
    readfile($fileName);
  }
  else
  {
    send404();
  }
  die();
}

function sendError($errorText)
{
  echo $errorText;
  die();
}
?>
