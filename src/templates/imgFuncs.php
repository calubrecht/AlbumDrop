<?php

function getInfoBox()
{
  return "<div id=\"imageInfoBox\">
   <img src=\"ad_icons\\delete.png\" class=\"icon\" onclick=\"hideInfoBox()\">
   <form>
     <div>Image Name: <input id=\"ImageName\"></input></div>
     <div>isPublic: <input id=\"IsPublic\" type=\"checkbox\"></div>
     <div>isVisible: <input id=\"IsVisible\" type=\"checkbox\"></div>
     <div><input type=\"submit\" value=\"Update\"></div>
     <div>Direct Link : <a id=\"DirectLink\" onclick=\"selectText(this)\"></a></div>
     <div>Thumbnail Link : <a id=\"ThumbnailLink\" onclick=\"selectText(this)\"></a></div>
     <div id=\"imageInfoError\"></div>
   </form>
   </div>";
}

function imgThumb($imgId)
{
  global $AD_CONFIG;
  global $db;
  $imgInfo = $db->queryOneRow("SELECT originalName, fullName as ownerName, isPublic, isVisible  FROM images, users WHERE images.id=? and images.owner=users.idusers", "$imgId");
  $fileName = $imgInfo["originalName"];
  $pageRoot = $AD_CONFIG['PageRoot'];
  $owner = $imgInfo["ownerName"];

  echo "<span class=\"imgThumb\">
   <div class=\"thumb\"><div class=\"mainImage\"><img  src=\"$pageRoot/thumbs/$imgId\" alt=\"$fileName\"></div><div class=\"overlay\"><img src=\"ad_icons\\delete.png\" class=\"icon\"><img src=\"ad_icons\\info.png\" onclick=\"displayInfoBox('$imgId')\" class=\"icon\"></div></div>
   <div class=\"fileName\">$fileName</div>
   <div class=\"fileOwner\">owner: $owner</div></span>";
}


?>
