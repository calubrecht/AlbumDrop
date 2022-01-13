<?php

require_once("functions.php");
require_once("imgFuncs.php");
html_header("Gallery", "onload=\"loadAll()\"");
?>
<h2>Welcome <?php echo $user; ?>!</h2>
<div class="navBar"><span class="tab active" onClick="pickTab('galleryTab')">Gallery</span><span class="tab" onClick="pickTab('uploadTab')">Upload</span><span class="tab" onClick="pickAndLoadTab('publicTab')">Public Images</span></div>
  <?= getInfoBox() ?>
  <?= getZoomBox() ?>
<div id = "galleryTab" class="tabBody">
<div id="galleryError" class="error"><?= $error ?></div>
<div class="grid">
</div>
  <form class="LogoutButton" action="" method="post" >
  <div><input type="submit" name="logout" value="Logout"></div>
  </form>
</div>
<?php
require_once("templates/upload.php");
require_once("templates/public.php");
html_footer();
?>
