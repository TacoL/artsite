<?php
session_start();
$userId = $_SESSION["userId"];

if (isset($_POST["submit"])) {
  $pieceName = $_POST["pieceName"];
  $description = $_POST["description"];
  $privateBool = $_POST["privateBool"];

  if (empty($pieceName) || empty($description)) {
    header("location: ../upload.php?error=noName");
    exit();
  }

  $file = $_FILES['file'];

  $fileName = $file['name'];
  $fileTmpName = $file['tmp_name'];
  $fileSize = $file['size'];
  $fileError = $file['error'];
  $fileType = $file['type'];

  $fileExt = explode('.', $fileName); //gets an array, object is separated by .
  $fileActualExt = strtolower(end($fileExt));

  $allowed = array('jpg', 'jpeg', 'png');

  if (in_array($fileActualExt, $allowed)) {
    if ($fileError === 0) {
      if ($fileSize < 100*1048576) { //100 MB
        require_once 'databaseHandler.php'; //connect to the database

        $sql = "INSERT INTO pieces (artistId, name, description, private) VALUES (?, ?, ?, ?)";
        $preparedStatement = mysqli_stmt_init($conn);

        if (!mysqli_stmt_prepare($preparedStatement, $sql)) {
          header("location: ../createAccount.php?error=stmtfailed");
          exit();
        }

        $boolToSend;
        if (empty($privateBool)) {
          $boolToSend = 0;
        }
        else if ($privateBool == "on") {
          $boolToSend = 1;
        }

        mysqli_stmt_bind_param($preparedStatement, "issi", $userId, $pieceName, $description, $boolToSend);
        mysqli_stmt_execute($preparedStatement);
        mysqli_stmt_close($preparedStatement);

        $fileId = mysqli_insert_id($conn);
        $fileDestination = '../Uploads/' . $fileId . '.' . $fileActualExt;
        move_uploaded_file($fileTmpName, $fileDestination);
        header("location: ../upload.php?uploadsuccess");
        exit();
      }
      else {
        header("location: ../upload.php?error=sizeexceedslimit");
        exit();
      }
    }
    else {
      header("location: ../upload.php?error=error");
      exit();
    }
  }
  else {
    header("location: ../upload.php?error=invalidtype");
    exit();
  }
}
