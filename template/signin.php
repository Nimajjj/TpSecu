<?php ob_start(); ?>

<h1>Sign in</h1>
<br><br>

<form action="" method="POST">
  <input type="email" name="email" placeholder="email" required/>
  <input type="password" name="pwd" placeholder="pwd" required/>
  <input type="submit" name="submit" value="Submit" required/>
</form>

<?php
$content = ob_get_clean();
require('base.php')
?>