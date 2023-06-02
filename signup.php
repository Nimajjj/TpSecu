<h1>Sign up</h1>

<a href="index.php">Index</a>
<a href="signin.php">Sign in</a>
<a href="signup.php">Sign up</a>
<a href="change_pwd.php">Change pwd</a>
<a href="signedup.php">Signed up</a>
<br><br>

<form action="" method="POST">
  <input type="email" name="email" placeholder="email" required/>
  <input type="password" name="pwd" placeholder="pwd" required/>
  <input type="password" name="confirm_pwd" placeholder="confirm pwd" required/>
  <input type="submit" name="signup" value="Submit"/>
</form>

<?php
require_once $_SERVER['DOCUMENT_ROOT'] . ("/controller/signup.php");

if (isset($_POST["signup"])) {
  $response = SignUp::Execute();
  echo ($response) ? $response : "";
}
