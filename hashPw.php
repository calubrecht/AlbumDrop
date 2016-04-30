<?php
if (count($argv) != 2)
{
  echo "Usage: php hashPW.php password\n";
  echo "    provides the default salted hash for given password.\n";
  exit(1);
}
$pw = $argv[1];
echo password_hash($pw, PASSWORD_DEFAULT)."\n";
?>
