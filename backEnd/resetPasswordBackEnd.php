<?php

if (isset($_POST["submit"])) {
  $selector = $_POST["selector"];
  $validator = $_POST["validator"];
  $password = $_POST["password"];
  $confirmPassword = $_POST["confirmPassword"];

  if (empty($password) || empty($confirmPassword)) {
    header("location: ../createNewPassword.php?newPassword=empty&selector=" . $selector . "&validator=" . $validator);
    exit()
  }
  else if ($password != $confirmPassword) {
    header("location: ../createNewPassword.php?newPassword=passwordsDoNotMatch&selector=" . $selector . "&validator=" . $validator);
    exit()
  }

  $currentDate = date("U");

  require 'databaseHandler.php';

  $sql = "SELECT * FROM passwordreset WHERE selector=? AND expires>=$currentDate;"; //use question marks for when the USER sends data
  $preparedStatement = mysqli_stmt_init($conn);

  if (!mysqli_stmt_prepare($preparedStatement, $sql)) {
    echo "error";
    exit();
  }
  else {
    mysqli_stmt_bind_param($preparedStatement, "s", $selector);
    mysqli_stmt_execute($preparedStatement);

    $result = mysqli_stmt_get_result($preparedStatement);
    if (!$row = mysqli_fetch_assoc($result)) { //if there were no rows
      echo "Resubmit Password request"; //make sure to let user know and send them back to login page
      exit();
    }
    else {
      $tokenBin = hex2bin($validator);
      $tokenCheck = password_verify($tokenBin, $row["token"]);

      if ($tokenCheck === false) {
        echo "Resubmit Password request"; //make sure to let user know and send them back to login page
        exit();
      }
      else if ($tokenCheck === true) {
        $tokenEmail = $row["email"];
        $sql = "SELECT * FROM user WHERE email=?;";
        $preparedStatement = mysqli_stmt_init($conn);

        if (!mysqli_stmt_prepare($preparedStatement, $sql)) {
          echo "error";
          exit();
        }
        else {
          mysqli_stmt_bind_param($preparedStatement, "s", $tokenEmail);
          mysqli_stmt_execute($preparedStatement);

          $result = mysqli_stmt_get_result($preparedStatement);
          if (!$row = mysqli_fetch_assoc($result)) { //if there were no rows
            echo "error";
            exit();
          }
          else {
            $sql = "UPDATE users SET password=? WHERE email=?;";
            $preparedStatement = mysqli_stmt_init($conn);

            if (!mysqli_stmt_prepare($preparedStatement, $sql)) {
              echo "error";
              exit();
            }
            else {
              $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
              mysqli_stmt_bind_param($preparedStatement, "ss", $hashedPassword), $tokenEmail);
              mysqli_stmt_execute($preparedStatement);

              $sql = "DELETE FROM passwordReset WHERE email=?";
              $preparedStatement = mysqli_stmt_init($conn);

              if (!mysqli_stmt_prepare($preparedStatement, $sql)) {
                echo "error";
                exit();
              }
              else {
                mysqli_stmt_bind_param($preparedStatement, "s", $tokenEmail);
                mysqli_stmt_execute($preparedStatement);
                header("location: ../login.php?newPassword=updated");
              }
            }
          }
        }
      }
    }
  }

  //do we need?
  mysqli_stmt_close($preparedStatement);
  mysqli_close();
}
else {
  header("location: ../index.php");
}
