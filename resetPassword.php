<?php
  session_start();
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Reset Password</title>
  </head>

<?php
  include_once 'header.php' //top banner
?>

<section>
  <h1>Reset your Password</h1>
  <p>Enter your email</p>
  <form action="backEnd/requestPasswordReset.php" method="post">
    <input name="email" type="text" placeholder="Email Address">
    <button name="submitResetPassword" type="submit">Request Password Change</button>
  </form>

  <?php
    if (isset($_GET["reset"])) {
      if ($_GET["reset"] == "success") {
        echo "<p>Check your email!</p>";
      }
      else if ($_GET["reset"] == "fail") {
        echo "<p>Request failed. Try again</p>";
      }
    }
  ?>
</section>

<?php
  include_once 'footer.php'
?>
