<?php

function getCurrentUserId()
{
  return isset($_SESSION["userID"]) ? $_SESSION["userID"] : null;
}

function isLoggedIn()
{
  return isset($_SESSION["userID"]);
}

function getCurrentName()
{
  return isset($_SESSION["user"]) ? $_SESSION["user"] : null;
}

function getLoginInfo($user)
{
  global $db; 
  return $db->queryOneRow("SELECT pwHash, idusers  FROM users WHERE login=?", "$user");
}

function setUser($user, $userId)
{
  $_SESSION["user"] = $user;
  $_SESSION["userID"] = $userId;
}

function register($userData)
{
  global $db; 
  global $AD_CONFIG; 
  $ret = array();
  if (!$AD_CONFIG["ALLOW_REGISTRATION"])
  {
    $ret["success"] = false;
    $ret["error"] = "Registration at this site is currently disabled.";
    echo json_encode($ret);
    die();
  }
  $user = $userData["username"];
  $displayName = $userData["displayName"];
  $pw = $userData["password"];
  $email = $userData["email"];
  $pwHash = password_hash($pw, PASSWORD_DEFAULT);
  try
  {
    $res = $db->execute("INSERT into users (login, pwHash, fullName, email, isAdmin) VALUES (?, ?, ?, ?, 0)", array($user, $pwHash, $displayName, $email));
    if (!$res)
    {
      if ($db->errorCode == 23000)
      {
        $ret["success"] = false;
        $ret["error"] = "The user $user already exists.";
      }
      else
      {
        $ret["success"] = false;
        $ret["error"] = $db->error;
      }
    }
    else
    {
      $ret["success"] = true;
      setUser($user, getLoginInfo($user)["idusers"]);
    }
  }
  catch (exception $e)
  {
    $ret["success"] = false;
    $ret["error"] = e.getMessage();
    $ret["errorCode"] = e.getCode();
  }
  echo json_encode($ret);
  die();
}

?>
