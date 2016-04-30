<?php

require_once("functions.php");
html_header("Login")
?>
<div class="error"><?php echo $error; ?></div>
<form action="" method="post" >
<div class="login">Username: <input type="text" name="username"></div>
<div class="password">Password: <input type="password" name="password"></div>
<div><input type="submit" value="Login"></div>
</form>
<?php

html_footer();
?>
