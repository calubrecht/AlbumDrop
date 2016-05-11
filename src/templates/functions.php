<?php

function html_header($title, $bodyScript = "")
{
  global $AD_CONFIG;
  echo "<html><head><title>".$title."</title>";
  echo '<link rel="stylesheet" type="text/css" href="css/basic.css" >';
  echo '<link rel="icon" type="image/x-icon" href="'.$AD_CONFIG["FAV_ICON"].'" >';
  echo scriptTag();
  echo "<meta name=viewport content=\"width=device-width, initial-scale=1\">";
  echo "</head>\n";
  echo "<body " . $bodyScript .">\n";
  echo "<h1><img src=\"".$AD_CONFIG["BANNER"]."\">".$AD_CONFIG["BANNER_NAME"]."</h1>";
}

function html_footer()
{
  echo "</body></html>\n";
}

function scriptTag()
{
  echo "<script type='text/javascript' src='js/album.js'></script>";
}

?>
