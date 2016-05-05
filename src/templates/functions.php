<?php

function html_header($title)
{
  echo "<html><head><title>".$title."</title>";
  echo '<link rel="stylesheet" type="text/css" href="css/basic.css" >';
  echo scriptTag();
  echo "<meta name=viewport content=\"width=device-width, initial-scale=1\">";
  echo "</head>\n";
  echo "<body>\n";
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
