<?php

require_once("functions.php");
html_header("Login")
?>
<div class="navBar"><span class="tab active" onClick="pickTab('loginTab')">Login</span> <span class="tab" onClick="pickTab('registerTab')">Register</span></div>
<div id = "loginTab" class="tabBody narrow">
  <div class="error" id="loginError"><?php echo $error; ?></div>
  <form action="" method="post" >
  <div class="login">Username: <input type="text" name="username"></div>
  <div class="password">Password: <input type="password" name="password"></div>
  <div><input type="submit" value="Login"></div>
  </form>
</div>
<?php
require_once("templates/register.php");
html_footer();
?>
