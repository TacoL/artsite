<?php
  session_start();
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Create New Password</title>
  </head>

<?php
  include_once 'header.php' //top banner

  //make sure to check if selector and validator are both in the URL w/ isset()
  $selector = $_GET["selector"];
  $validator = $_GET["validator"];

  if (empty($selector) || empty($validator)) {
    echo "Could not validate your request";
  }
  else {
    if (ctype_xdigit($selector) !== false && ctype_xdigit($validator) !== false) { //checks if in valid hexidecimal form
      ?>
      <form action="backEnd/resetPasswordBackEnd.php" method="post">
        <input name="selector" type="hidden" value="<?php echo $selector; ?>">
        <input name="validator" type="hidden" value="<?php echo $validator; ?>">
        <input name = "password" type="password" placeholder="New Password">
        <input name = "confirmPassword" type="password" placeholder="Confirm New Password">
        <button name="submit" type="submit">Reset Password</button>
      </form>
      <?php
    }
  }
?>


<?php
  include_once 'footer.php'
?>
