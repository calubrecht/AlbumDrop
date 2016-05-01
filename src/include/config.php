<?php

/**
 * Please Do Not Modify this file. To change local configs
 * over variables in config_local.php
 */

$AD_CONFIG = array();


// Protocol and host
$AD_CONFIG["host"] = "https://example.com";
// Root directory of URL
$AD_CONFIG["PageRoot"] = "";

$AD_CONFIG["DB_HOST"] = "localhost";
$AD_CONFIG["DB_NAME"] = "albumdrop";
$AD_CONFIG["DB_PORT"] = "3306";
$AD_CONFIG["DB_USER"] = "";
$AD_CONFIG["DB_PASSWORD"] = "";

if(file_exists("include/config_local.php"))
{
  include "include/config_local.php";
}


?>
