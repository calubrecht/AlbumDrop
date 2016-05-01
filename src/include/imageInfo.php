<?php 

function getUserImages($ownerId)
{
  global $db; 
  $results =
    $db->queryAll("SELECT id FROM images WHERE owner=?", $ownerId);
  $ret = array();
  foreach ($results as $row)
  {
    $ret[] = $row["id"];
  }
  return $ret;
}

$alpha = array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z');
$numChar = array('0','1','2','3','4','5','6','7','8','9');

function makeID()
{
  global $alpha;
  global $numChar;
  $id ='';
  for ($i = 0; $i < 5; $i++)
  {
    $id = $id . $alpha[rand(0,25)];
  }
  for ($i = 0; $i < 6; $i++)
  {
    $id = $id . $numChar[rand(0,9)];
  }
  return $id; 
}

function scrubName($fileName)
{
  $fileName = preg_replace("([^\w\s\d\-_~,;\[\]\(\).])", '', $fileName);
  // Remove any runs of periods
  return preg_replace("([\.]{2,})", '', $fileName);
}

function uploadImage($fileName, $tmpFileName)
{
  global $db; 
  $fileName = scrubName($fileName);
  $destFileName = "data/images/".$fileName;
  $mimeType = mime_content_type($tmpFileName);
  if (substr($mimeType,0,6) != "image/")
  {
    sendError("Only images can be uploaded.");
  }
  while (file_exists($destFileName))
  {
    $destFileName = $destFileName . "1";
  }
  copy($tmpFileName, $destFileName);

  $id = makeID();
  if (!$db->execute("INSERT INTO images (id, fileLoc, thumbLoc, originalName, owner) VALUES (?, ?, ?, ?, ?)", array($id, $destFileName, $destFileName, $fileName, getCurrentUserId())))
  {
    logwrite($db->error); 
    sendError("Upload failed. Database Error");
  }
}

?>
