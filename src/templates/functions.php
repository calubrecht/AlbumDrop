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
  echo "<script type='text/javascript'>";
  echo "function pickTab(tab)";
  echo "{";
  echo "  var tabs = document.getElementsByClassName('tabBody');";
  echo "  for (i = 0; i < tabs.length; i++)";
  echo "  {";
  echo "    if (tabs[i].id == tab)"; 
  echo "      tabs[i].style.display = 'block';";
  echo "    else";
  echo "      tabs[i].style.display = 'none';";
  echo "  }";
  echo "}";

  echo "</script>";
}

?>
