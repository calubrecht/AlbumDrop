<html>
<head>
<style>
  body { background:lightblue}
  div.main { background:lightblue}
  div.tabBody {background:white; border-width: 1px; border-style: solid; padding: 5px; margin:1px; border-radius: 15px}
</style>
</head>
<body>
<div class="main">
<div class="tabBody">
  <p>
  Someone has requested to reset your password for <?= $data["userName"] ?> at <b><i><?= $data["BANNER_NAME"] ?></i></b>  if this was not you, you do not need to take any action.
  </p>
  <p>
  If you do wish to reset your password, follow the link below:
  </p>
  <p>
  <a href="<?= $data["host"] ?>/resetPassword/<?= $data["passwordToken"] ?>">
  <?= $data["host"] ?>/resetPassword/<?= $data["passwordToken"] ?></a>
  </p>
</div>
</div>
</body>
</html>
