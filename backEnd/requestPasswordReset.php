<?php

if (isset($_POST["submitResetPassword"])) {
  $selector = bin2hex(random_bytes(8));
  $token = random_bytes(32);

  $url = "localhost/artsite/createNewPassword.php?selector=" . $selector . "&validator=" . bin2hex($token);
  $expires = date("U") + 1800;

  require 'databaseHandler.php';

  $userEmail = $_POST["email"];

  //delete entries of a token from the same user in the database
  $sql = "DELETE FROM passwordreset WHERE email=?;";
  $preparedStatement = mysqli_stmt_init($conn);

  if (!mysqli_stmt_prepare($preparedStatement, $sql)) {
    echo "error";
    exit();
  }
  else {
    mysqli_stmt_bind_param($preparedStatement, "s", $userEmail); //replaces the question mark in the prepared statement/sql
    mysqli_stmt_execute($preparedStatement);
  }

  //insert password reset data into the database
  $sql = "INSERT INTO passwordreset (email, selector, token, expires) VALUES (?, ?, ?, ?);";
  $preparedStatement = mysqli_stmt_init($conn);

  if (!mysqli_stmt_prepare($preparedStatement, $sql)) {
    echo "error";
    exit();
  }
  else {
    $hashedToken = password_hash($token, PASSWORD_DEFAULT);
    mysqli_stmt_bind_param($preparedStatement, "ssss", $userEmail, $selector, $hashedToken, $expires);
    mysqli_stmt_execute($preparedStatement);
  }

  mysqli_stmt_close($preparedStatement);
  mysqli_close($conn);

  //mail service
  $to = $userEmail;
  $subject = 'Password Reset';
  $message = '<p>Click the link below to reset your password!</p> </br>';
  $message .= '<a href="' . $url . '">' . $url . '</a>';

  $headers = "From: art site <artsite@gmail.com>\r\n";
  $headers .= "Reply-To: artsite@gmail.com\r\n";
  $headers .= "Content-type: text/html\r\n";

  mail($to, $subject, $message, $headers);
  header("location: ../resetPassword.php?reset=success");
}
else {
  header("location: ../resetPassword.php?reset=fail");
}
