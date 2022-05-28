<?php

if (isset($_POST["submit"])) {
  $username = $_POST["username"];
  $email = $_POST["email"];
  $password = $_POST["password"];
  $confirmPassword = $_POST["confirmPassword"];

  require_once 'databaseHandler.php';
  require_once 'functions.php'; //perhaps change later

  if (emptyInputCreateAccount($username, $email, $password, $confirmPassword) !== false) {
    header("location: ../createAccount.php?error=emptyInput");
    exit();
  }
  if (invalidUsername($username) !== false) {
    header("location: ../createAccount.php?error=invalidUsername");
    exit();
  }
  if (invalidEmail($email) !== false) {
    header("location: ../createAccount.php?error=invalidEmail");
    exit();
  }
  if (passwordsDoNotMatch($password, $confirmPassword) !== false) {
    header("location: ../createAccount.php?error=passwordsDoNotMatch");
    exit();
  }
  if (usernameOrEmailExists($conn, $username, $email) !== false) {
    header("location: ../createAccount.php?error=usernameOrEmailExists");
    exit();
  }

  createUser($conn, $username, $email, $password);
}
else {
  header("location: ../createAccount.php");
  exit();
}
