<?php

require_once("functions.php");
html_header("Reset Password");
?>
<div id="Body">
<div class="navBar"><span class="tab active" >Reset Password</span></div>
<div id ="resetPasswordTab" class="tabBody narrow">
  <div class="error" id="resetPasswordError"></div>
  <form action="" method="post" id="resetPasswordForm">
  <div><div class="login firstColumn">Username:</div><div class="secondColumn"><?= $username ?></div></div>
  <div><div class="password firstColumn">Password:</div> <input type="password" name="password" class="secondColumn"></div>
  <div><div class="password firstColumn">Confirm Password:</div> <input type="password" name="confirmPassword" class="secondColumn"></div>
  <div><input type="button" class="ResetPasswordButton" value="ResetPassword" onclick="resetPassword()"></div>
  <input type="hidden" name="token" value="<?= $token ?>" >
  </form>
</div>
