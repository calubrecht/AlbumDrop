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

function createThumbnail($srcFile, $destFile, $destX, $destY)
{
  $mimeType = mime_content_type($srcFile);
  if ($mimeType == "image/jpeg")
  {
    $srcImage = imagecreatefromjpeg($srcFile);
  }
  elseif ($mimeType == "image/png")
  {
    $srcImage = imagecreatefrompng($srcFile);
  }
  elseif ($mimeType == "image/gif")
  {
    $srcImage = imagecreatefromgif($srcFile);
  }
  else
  {
    logwrite("Don't know mimetype = $mimeType");
    return false;
  }
  $width = imagesx($srcImage);
  $height = imagesy($srcImage);
  if ($width <= $destX && $height <= $destY)
  {
    // No need for a thumbnail
    $res = ["height"=>$height, "width"=>$width, "thumbHeight"=>$height, "thumbWidth"=>$width, "oneFile"=>true, "mimeType"=>$mimeType];
    return $res;
  }
  $newHeight = floor ($height * ($destX / $width));
  $newWidth = $destX;
  if ($newHeight > $destY)
  {
    $newHeight = $destY;
    $newWidth = floor ($width * ($destY / $height));
  }
  $newImage = imagecreatetruecolor($newWidth, $newHeight);
  imagealphablending($newImage, FALSE);
  imagecopyresampled($newImage, $srcImage, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
  if ($mimeType == "image/jpeg")
  {
    imagejpeg($newImage, $destFile);
  }
  elseif ($mimeType == "image/png")
  {
    imagesavealpha($newImage, TRUE);
    imagepng($newImage, $destFile);
  }
  elseif ($mimeType == "image/gif")
  {
    imagegif($newImage, $destFile);
  }
  $res = ["height"=>$height, "width"=>$width, "thumbHeight"=>$newHeight, "thumbWidth"=>$newWidth, "mimeType"=>$mimeType];
  return $res;
}

function getImgInfo($imgId)
{
  global $db;
  $imgInfo = $db->queryOneRow("SELECT originalName, fullName as ownerName, isPublic, isVisible, $extension  FROM images, users WHERE images.id=? and images.owner=users.idusers", "$imgId");
  return $imgInfo;
}

function updateImgInfo($imageId, $fileName, $isPublic, $isVisible)
{
  global $db;
  $imgOwner = $db->queryOneColumn("SELECT owner  FROM images WHERE images.id=?", "owner", "$imageId");
  if (getCurrentUserId() != $imgOwner)
  {
    $ret = array();
    $ret["success"] = false;
    $ret["error"] = "Only owner may update image data";
    return json_encode($ret);
  }
  if ($db->execute("UPDATE images set originalName=?, isPublic=?, isVisible=? WHERE id=?", array($fileName, $isPublic, $isVisible, $imageId)))
  {
    $ret = array();
    $ret["success"] = true;
    return json_encode($ret);
  }
  else
  {
    $ret = array();
    $ret["success"] = false;
    $ret["error"] = $db->error;
    return json_encode($ret);
  }
}

function quiet_unlink($filename)
{
  if (file_exists($filename))
  {
    unlink($filename);
  }
}

function deleteImg($imageId)
{
  global $db;
  $db->beginTransaction();
  $row = $db->queryOneRow("SELECT * FROM images WHERE images.id=?", "$imageId");
  if (getCurrentUserId() != $row["owner"])
  {
    $ret = array();
    $ret["success"] = false;
    $ret["error"] = "Only owner may delete images.";
    $db->rollbackTransaction();
    return json_encode($ret);
  }
  $ret = $db->execute("DELETE FROM images WHERE images.id=?", "$imageId");
  if (!$ret)
  {
    $ret = array();
    $ret["success"] = false;
    $ret["error"] = $db->error;
    $db->rollbackTransaction();
    return json_encode($ret);
  }
  quiet_unlink($row["fileLoc"]);
  quiet_unlink($row["thumbLoc"]);
  $db->commitTransaction();
  $ret = array();
  $ret["success"] = true;
  return json_encode($ret);
}

function getImgInfoJson($imgId)
{
  global $AD_CONFIG;
  $imgInfo = getImgInfo($imgId);
  if (!$imgInfo)
  {
    $ret = array();
    $ret["success"] = false;
    $ret["error"] = "Image not found.";
    return json_encode($ret);
  }
  $SiteRoot = $AD_CONFIG["host"] ."/";
  if ($AD_CONFIG["PageRoot"] != "")
  {
    $SiteRoot = $SiteRoot . $AD_CONFIG["PageRoot"] ."/";
  }
  $ret = array();
  $ret["imageName"] = $imgInfo["originalName"];
  $ret["isPublic"] = $imgInfo["isPublic"];
  $ret["isVisible"] = $imgInfo["isVisible"];
  $ret["directLink"] = $SiteRoot."images/".$imgId.$ret["extension"];
  $ret["thumbLink"] = $SiteRoot."thumbs/".$imgId.$ret["extension"];
  $ret["success"] = true;
  return json_encode($ret);
}

function getExtension($mimeType)
{
  if ($mimeType == "image/jpeg")
  {
    return ".jpg";
  }
  elseif ($mimeType == "image/png")
  {
    return ".png";
  }
  elseif ($mimeType == "image/gif")
  {
    reutn ".gif";
  }
  return "";
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
  $thumbFileName = "data/thumbs/thumb".$fileName;
  while (file_exists($thumbFileName))
  {
    $thumbFileName = $thumbFileName . "1";
  }
  $res = createThumbnail($destFileName, $thumbFileName, 100, 100);
  logwrite(json_encode($res));
  if ($res == false)
  {
    $thumbFileName = $destFileName;
    $res = ["height"=>null, "width"=>null, "thumbHeight"=>null, "thumbWidth"=>null, "mimeType"=>""];
  }
  if (isset($res["oneFile"]))
  {
    $thumbFileName = $destFileName;
  }
  $extension = getExtension($res["mimeType"]);
  $id = makeID();
  if (!$db->execute("INSERT INTO images (id, fileLoc, thumbLoc, originalName, owner, height, width, thumbHeight, thumbWidth, extension) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)", array($id, $destFileName, $thumbFileName, $fileName, getCurrentUserId(), $res["height"], $res["width"], $res["thumbHeight"], $res["thumbWidth"], $extension)))
  {
    sendError("Upload failed. Database Error " . $db->error);
  }
}

?>
