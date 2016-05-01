<?php 

function getUserImages($ownerId)
{
  global $db; 
  $results =
    $db->queryAll("SELECT id FROM images WHERE owner=?", $ownerId);
  $ret = array();
  foreach ($results as $row)
  {
    $ret[] = $row["id"];
  }
  return $ret;
}



?>
