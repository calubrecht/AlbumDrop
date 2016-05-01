<?php

function getInfoBox()
{
  return "<div id=\"imageInfoBox\">
   <img src=\"icons\\delete.png\" class=\"icon\" onclick=\"hideInfoBox()\">
   <form>
     <div>Image Name: <input></input></div>
     <div>isPublic: <input type=\"checkbox\"></div>
     <div>isVisible: <input type=\"checkbox\"></div>
     <div><input type=\"submit\" value=\"Update\"></div>
     <div>Direct Link : <span id=\"DirectLink\" onclick=\"selectText(this)\">/images/1234</span></div>
     <div>Thumbnail Link : <span id=\"ThumbnailLink\" onclick=\"selectText(this)\">/thumbs/1234</span></div>
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
   <div class=\"thumb\"><div class=\"mainImage\"><img  src=\"$pageRoot/thumbs/$imgId\" alt=\"$fileName\"></div><div class=\"overlay\"><img src=\"icons\\delete.png\" class=\"icon\"><img src=\"icons\\info.png\" onclick=\"displayInfoBox('$imgId')\" class=\"icon\"></div></div>
   <div class=\"fileName\">$fileName</div>
   <div class=\"fileOwner\">owner: $owner</div></span>";
}


?>
