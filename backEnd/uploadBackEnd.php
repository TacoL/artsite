<?php
session_start();
$userId = $_SESSION["userId"];

if (isset($_POST["submit"])) {
  $pieceName = $_POST["pieceName"];

  if (empty($pieceName)) {
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
        $fileId = uniqid('', true) . "." . $fileActualExt; //makes an id based on the timestamp of the upload
         if (!file_exists('../Uploads/' . $userId)) {
           mkdir('../Uploads/' . $userId);
         }
         mkdir('../Uploads/' . $userId . '/' . $pieceName);
         $fileDestination = '../Uploads/' . $userId . '/' . $pieceName . '/' . $fileId;
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
