<?php

function getInfoBox()
{
  return "<div id=\"imageInfoBox\">
   <img src=\"ad_icons\\delete.png\" class=\"icon\" onclick=\"hideInfoBox()\" title=\"Hide\">
   <form>
     <div><div class=\"firstColumn\">Image Name:</div> <input id=\"ImageName\"></input class=\"secondColumn\"></div>
     <div><div class=\"firstColumn\">isVisible:</div> <input id=\"IsVisible\" type=\"checkbox\" class=\"secondColumnCheck\"></div>
     <div><div class=\"firstColumn\">isPublic:</div> <input id=\"IsPublic\" type=\"checkbox\" class=\"secondColumnCheck\"></div>
     <div><div class=\"firstColumn\" id=\"UpdateButton\"><input type=\"button\" value=\"Update\" onclick=\"updateImageInfo()\"></div></div>
     <div><div class=\"firstColumn\">Direct Link :</div> <a id=\"DirectLink\" onclick=\"selectText(this)\" ondblclick=\"openURL(this)\" class=\"secondColumn\"></a></div>
     <div><div class=\"firstColumn\">Thumbnail Link :</div> <a id=\"ThumbnailLink\" onclick=\"selectText(this)\" ondblclick=\"openURL(this)\" class=\"secondColumn\"></a></div>
     <div id=\"imageInfoError\"></div>
   </form>
   </div>";
}
function getZoomBox()
{
  return "<div id=\"zoomBox\">
   <img src=\"ad_icons\\delete.png\" class=\"icon\" onclick=\"hideZoomBox()\" title=\"Hide\">
   <div>
     <img id=\"zoomImage\" src=\"\"  onload=\"showZoombox()\">
   </div>
   </form>
   </div>";
}
 
function getImgThumbInfo($imgId)
{
  global $AD_CONFIG;
  global $db;
  $imgInfo = $db->queryOneRow("SELECT originalName, width, height, fullName as ownerName, isPublic, isVisible, extension  FROM images, users WHERE images.id=? and images.owner=users.idusers", "$imgId");
  $fileName = $imgInfo["originalName"];
  $owner = $imgInfo["ownerName"];
  $width = isset($imgInfo["width"]) ? $imgInfo["width"] : 0;
  $height = isset($imgInfo["height"]) ? $imgInfo["height"] : 0;
  $extension = $imgInfo["extension"];

  $html = "<div class=\"thumb\"><div class=\"mainImage\"><img  src=\"thumbs/$imgId$extension\" alt=\"$fileName\"></div><div class=\"overlay\"><img src=\"ad_icons\\delete.png\" class=\"icon\" title=\"Delete image\" onclick=\"deleteImage('$imgId')\"><img src=\"ad_icons\\info.png\" onclick=\"displayInfoBox('$imgId')\" class=\"icon\" title=\"Image Info\"><img src=\"ad_icons\\magnify.png\" onclick=\"zoom('images/$imgId', $width, $height)\" class=\"icon\"></div></div>
   <div class=\"fileName\" title=\"$fileName\">$fileName</div>
   <div class=\"fileOwner\">owner: $owner</div>";

   $item = array();
   $item["imgDivID"] = "box".$imgId;
   $item["html"] = $html;
   $item["fileName"] = $fileName; 
   return $item;
}


?>
