<?php

function emptyInputCreateAccount($username, $email, $password, $confirmPassword) {
  if (empty($username) || empty($email) || empty($password) || empty($confirmPassword)) {
    return true;
  }
  return false; //dunno if php terminates a function when return
}

function invalidUsername($username) {
  if (!preg_match("/^[a-zA-Z0-9]*$/", $username)) { //if username doesn't have those characters, return true. Perhaps remove
    return true;
  }
  return false;
}

function invalidEmail($email) {
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return true;
  }
  return false;
}

function passwordsDoNotMatch($password, $confirmPassword) {
  if ($password !== $confirmPassword) {
    return true;
  }
  return false;
}

function usernameOrEmailExists($conn, $username, $email) {
  $sql = "SELECT * FROM users WHERE userUsername = ? OR userEmail = ?;";
  $preparedStatement = mysqli_stmt_init($conn);

  if (!mysqli_stmt_prepare($preparedStatement, $sql)) {
    header("location: ../createAccount.php?error=stmtfailed");
    exit();
  }

  mysqli_stmt_bind_param($preparedStatement, "ss", $username, $email);
  mysqli_stmt_execute($preparedStatement);

  $resultData = mysqli_stmt_get_result($preparedStatement);

  if ($row = mysqli_fetch_assoc($resultData)) {
    return $row;
  }
  else {
    return false;
  }

  mysqli_stmt_close($preparedStatement);
}

function createUser($conn, $username, $email, $password) {
  $sql = "INSERT INTO users (userUsername, userEmail, userPassword) VALUES (?, ?, ?);";
  $preparedStatement = mysqli_stmt_init($conn);

  if (!mysqli_stmt_prepare($preparedStatement, $sql)) {
    header("location: ../createAccount.php?error=stmtfailed");
    exit();
  }

  $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
  mysqli_stmt_bind_param($preparedStatement, "sss", $username, $email, $hashedPassword);
  mysqli_stmt_execute($preparedStatement);
  mysqli_stmt_close($preparedStatement);

  header("location: ../createAccount.php?error=none"); //change this to land user on front page
  exit();
}

//login functions

function emptyInputLogin($username, $password) {
  if (empty($username) || empty($password)) {
    return true;
  }
  return false;
}

function loginUser($conn, $username, $password) {
  $usernameExists = usernameOrEmailExists($conn, $username, $username); //what if someone creates an account where they put their email in the username section

  if ($usernameExists === false) {
    header("location: ../login.php?error=wrongLogin");
    exit();
  }

  $hashedPassword = $usernameExists["userPassword"];
  $checkPassword = password_verify($password, $hashedPassword);

  if ($checkPassword === false) {
    header("location: ../login.php?error=wrongLogin");
    exit();
  }
  else if ($checkPassword === true) {
    session_start();
    $_SESSION["userId"] = $usernameExists["userId"];
    $_SESSION["userUsername"] = $usernameExists["userUsername"];

    header("location: ../index.php");
    exit();
  }
}
