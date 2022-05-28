<?php
  session_start();
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Create Account</title>
  </head>

<?php
  include_once 'header.php' //top banner
?>

<section class="createAccount">
  <h2>Create an Account</h2>
  <form action="backEnd/createAccountBackEnd.php" method="post">
    <input name="username" type="text" placeholder="Username">
    <input name="email" type="text" placeholder="Email">
    <input name="password" type="password" placeholder="Password">
    <input name="confirmPassword" type="password" placeholder="Confirm Password">
    <button name="submit" type="submit">Create Account</button>
  </form>
</section>

<?php
  if (isset($_GET["error"])) { //isset() checks for content in the URL
    if ($_GET["error"] == "emptyInput") {
      echo "<p>At least one of the fields are empty</p>";
    }
    else if ($_GET["error"] == "invalidUsername") {
      echo "<p>Username needs to include letters or numbers</p>";
    }
    else if ($_GET["error"] == "invalidEmail") {
      echo "<p>Invalid Email</p>";
    }
    else if ($_GET["error"] == "passwordsDoNotMatch") {
      echo "<p>Confirm Password does not match Password</p>";
    }
    else if ($_GET["error"] == "usernameOrEmailExists") {
      echo "<p>That username or email is already taken!</p>";
    }
    else if ($_GET["error"] == "stmtfailed") {
      echo "<p>Something went wrong, please try again!</p>";
    }
    else if ($_GET["error"] == "none") {
      echo "<p>Account has been created</p>"; //perhaps send user to the home page
    }
    else {
      echo "<p>Something went wrong, please try again!</p>";
    }
  }

  include_once 'footer.php'
?>
