<?php

if (isset($_POST["submit"])) {
  $username = $_POST["username"]; //can be username or email
  $password = $_POST["password"];

  require_once 'databaseHandler.php';
  require_once 'functions.php';

  if (emptyInputLogin($username, $password) !== false) {
    header("location: ../login.php?error=emptyInput");
    exit();
  }

  loginUser($conn, $username, $password);
}
else {
  header("location: ../login.php");
  exit();
}
