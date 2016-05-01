<?php

function html_header($title)
{
  echo "<html><head><title>".$title."</title>";
  echo '<link rel="stylesheet" type="text/css" href="css/basic.css" >';
  echo scriptTag();
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
