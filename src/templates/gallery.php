<?php

require_once("functions.php");
require_once("imgFuncs.php");
html_header("Gallery", "onload=\"updateGallery('gallery')\"");
?>
<h2>Welcome <?php echo $user; ?>!</h2>
<div class="navBar"><span class="tab active" onClick="pickTab('galleryTab')">Gallery</span><span class="tab" onClick="pickTab('uploadTab')">Upload</span></div>
<div id = "galleryTab" class="tabBody">
  <?= getInfoBox() ?>
  <?= getZoomBox() ?>
  <div id="galleryError" class="error"></div>
  <form class="LogoutButton" action="" method="post" >
  <div><input type="submit" name="logout" value="Logout"></div>
  </form>
</div>
<?php
require_once("templates/upload.php");
html_footer();
?>
