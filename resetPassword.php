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
  <form action="backEnd/resetPasswordBackEnd.php" method="post">
    <input name="email" type="text" placeholder="Email Address">
    <button name="submitResetPassword" type="submit">Request Password Change</button>
  </form>
</section>

<?php
  include_once 'footer.php'
?>
