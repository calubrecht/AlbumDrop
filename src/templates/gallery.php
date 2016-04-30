<?php

require_once("functions.php");
html_header("Gallery")
?>
<h1>Welcome <?php echo $user; ?>!</h1>
<div>Image list goes here.</div>
<?php foreach (getUserImages() as $imgId)
{
  echo '<img src="'.$AD_CONFIG["PageRoot"] .'/images/' . $imgId . '">';
} ?>
<form action="" method="post" >
<div><input type="submit" name="logout" value="Logout"></div>
</form>

<?php
html_footer();
?>
