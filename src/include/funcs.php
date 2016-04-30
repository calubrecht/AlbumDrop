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

function getUserImages()
{
  return array("z45", "a21");
}

function send404()
{
  header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");
  require_once("templates/404.php");
  die();
}

function getFileByID($id, $thumb=false)
{
  global $db; 
  $results =
    $db->queryOneRow("SELECT fileLoc,thumbLoc,isPublic,isVisible,owner FROM images WHERE id=?", $id);
  if (!$results)
  {
    send404();
  }
  $fileName = $thumb ? $results["thumbLoc"] : $results["fileLoc"];
  if ($results["isVisible"] != 1)
  {
    if (!isset($_SESSION["userID"]))
    {
      send404();
    }
  }
  if ($results["isPublic"] != 1)
  {
    if ($results["owner"] != $_SESSION["userID"])
    {
      send404();
    }
  }
  getFileByName($fileName);
}

function getFileByName($fileName, $originalName)
{
  if (file_exists($fileName))
  {
    header('Content-Description: File Transfer');
    header('Content-Type: application/image-jpeg');
    header('Content-Disposition: attachment; filename="'.basename($fileName).'"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($fileName));
    readfile($fileName);
  }
  else
  {
    send404();
  }
  die();
}
?>
