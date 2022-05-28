<?php
  session_start();
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Login</title>
  </head>

<?php
  include_once 'header.php' //top banner
?>

<section class="loginScreen">
  <h2>Login</h2>
  <form action="backEnd/loginBackEnd.php" method="post">
    <input name="username" type="text" placeholder="Username/Email">
    <input name="password" type="password" placeholder="Password">
    <button name="submit" type="submit">Login</button>
  </form>
</section>

<div>
  <h4>Don't have an account?</h4>
  <a href="createAccount.php">Create Account</a>
</div>

<?php
  if (isset($_GET["error"])) { //isset() checks for content in the URL
    if ($_GET["error"] == "emptyInput") {
      echo "<p>At least one of the fields are empty</p>";
    }
    else if ($_GET["error"] == "wrongLogin") {
      echo "<p>You failed</p>";
    }
    else {
      echo "<p>Something went wrong, please try again!</p>";
    }
  }

  include_once 'footer.php'
?>
