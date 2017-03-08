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


function sendRecoveryEmail($email)
{
  global $AD_CONFIG; 
  $fromAddress = $AD_CONFIG["PASSWORD_RECOVERY_FROM"];
  $headers = "From: " . $fromAddress;
  $passwordToken = makeID(25, 0);
  mail(
    $email,
    "Password Recovery for " .$AD_CONFIG["BANNER_NAME"],
    "Someone has requested to reset your password for " .
      $AD_CONFIG["BANNER_NAME"] . " if this was not you, " .
      "you do not need to take any action.\n" .
      "If you do wish to reset your password, follow the link below.\n" .
      $AD_CONFIG["host"] . '/resetPassword/' . $passwordToken,
    $headers);
}

function resetPassword($userData)
{
  global $db; 
  global $AD_CONFIG; 
  $ret = array();
  $user = $userData["username"];
  try
  {
    $res = $db->queryAll("SELECT login, email from users where login=?", $user);
    if ($res && count($res) > 0)
    {
      $row = $res[0];
      if ($row["email"] && $row["email"] != "")
      {
        sendRecoveryEmail($row["email"]);
        $ret["success"] = true;
        $ret["message"] =
          "An email has been sent to the email associated with this account";
        echo json_encode($ret);
        return;
      }
    }
    $ret["success"] = false;
    $ret["error"] = "This account does not exist, or no email address is associated with the account.";
  }
  catch (exception $e)
  {
    $ret["success"] = false;
    $ret["error"] = 'exception' . e.getMessage();
    $ret["errorCode"] = e.getCode();
  }
  echo json_encode($ret);
  die();
}

?>
