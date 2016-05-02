<?php

require_once("functions.php");
require_once("imgFuncs.php");
html_header("Gallery")
?>
<h1>Welcome <?php echo $user; ?>!</h1>
<div class="navBar"><span class="tab" onClick="pickTab('galleryTab')">Gallery</span> <span class="tab" onClick="pickTab('uploadTab')">Upload</span></div>
<div id = "galleryTab" class="tabBody">
  <?= getInfoBox() ?>
  <?= getZoomBox() ?>
  <div>Image list goes here.</div>
  <?php foreach (getUserImages(getCurrentUserId()) as $imgId)
  {
    imgThumb($imgId);
  } ?>
  <form class="LogoutButton" action="" method="post" >
  <div><input type="submit" name="logout" value="Logout"></div>
  </form>
</div>
<?php
require_once("templates/upload.php");
html_footer();
?>
