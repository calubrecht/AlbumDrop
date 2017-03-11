<?php

function logwrite($info)
{
  $file = fopen("log/debug.log","a");
  fwrite($file,$info);
  fwrite($file,"\n");
  fclose($file);
}

require_once("db.php");
function checkLogin($user, $password)
{
  global $db; 
  $results = getLoginInfo($user);
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
  http_response_code(404);
  require_once("404.php");
  die();
}

function send403()
{
  http_response_code(403);
  require_once("403.php");
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
  $isVisible = $results["isVisible"] ==1;
  $isPublic = $results["isPublic"] ==1;
  
  if (null == getCurrentUserId())
  {
    if (!$isVisible)
    {
      send404();
    }
  }
  if ($results["owner"] != getCurrentUserId())
  {
    if (!$isVisible && !$isPublic)
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

$alpha = array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z');
$numChar = array('0','1','2','3','4','5','6','7','8','9');


function makeID($numAlphas = 5, $numNums = 6)
{
  global $alpha;
  global $numChar;
  $id ='';
  for ($i = 0; $i < $numAlphas; $i++)
  {
    $id = $id . $alpha[rand(0,25)];
  }
  for ($i = 0; $i < $numNums; $i++)
  {
    $id = $id . $numChar[rand(0,9)];
  }
  return $id; 
}

function get_include_contents($filename, $data)
{
  if (is_file($filename))
  {
    ob_start();
    include $filename;
    return ob_get_clean();
  }
  return false;
}

?>
