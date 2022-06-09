<?php
session_start();
$userId = $_SESSION["userId"];

if (isset($_POST["submit"])) {
  $pieceName = $_POST["pieceName"];
  $description = $_POST["description"];
  $privateBool = $_POST["privateBool"];
  $tags = $_POST["tags"];

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

        //UPLOAD IMAGE
        require_once 'databaseHandler.php'; //connect to the database

        $sql = "INSERT INTO pieces (artistId, name, description, private) VALUES (?, ?, ?, ?);";
        $preparedStatement = mysqli_stmt_init($conn);

        if (!mysqli_stmt_prepare($preparedStatement, $sql)) {
          header("location: ../upload.php?error=stmtfailed");
          exit();
        }

        //check if piece is public or private
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

        //TAGS
        $tagsNoWhiteSpace = preg_replace('/\s+/', '', strtolower($tags));
        $tagsArray = explode(',', $tagsNoWhiteSpace);
        $uniqueArray = array_unique($tagsArray);

        foreach ($uniqueArray as $tag) {
          //require_once 'databaseHandler.php'; //connect to the database
          $sql = "SELECT * FROM tags WHERE name=?;";
          $preparedStatement = mysqli_stmt_init($conn);

          if (!mysqli_stmt_prepare($preparedStatement, $sql)) {
            header("location: ../upload.php?error=stmtfailed");
            exit();
          }

          mysqli_stmt_bind_param($preparedStatement, "s", $tag);
          mysqli_stmt_execute($preparedStatement);

          $result = mysqli_stmt_get_result($preparedStatement);

          $tagId;
          if ($row = mysqli_fetch_assoc($result)) { //add to timesUsed
            $sql = "UPDATE tags SET timesUsed=timesUsed+1 WHERE name=?;";
            $preparedStatement = mysqli_stmt_init($conn);

            if (!mysqli_stmt_prepare($preparedStatement, $sql)) {
              header("location: ../upload.php?error=stmtfailed");
              exit();
            }

            mysqli_stmt_bind_param($preparedStatement, "s", $tag);
            mysqli_stmt_execute($preparedStatement);
            $tagId = $row["id"];
          }
          else { //create a new tag in the database
            $sql = "INSERT INTO tags (name, timesUsed) VALUES (?, ?);";
            $preparedStatement = mysqli_stmt_init($conn);

            if (!mysqli_stmt_prepare($preparedStatement, $sql)) {
              header("location: ../upload.php?error=stmtfailed");
              exit();
            }

            $one = 1;
            mysqli_stmt_bind_param($preparedStatement, "si", $tag, $one);
            mysqli_stmt_execute($preparedStatement);
            $tagId = mysqli_insert_id($conn);
          }

          //make a relation between the piece and the tag
          $sql = "INSERT INTO tagspieces (pieceId, tagId) VALUES (?, ?);";
          $preparedStatement = mysqli_stmt_init($conn);

          if (!mysqli_stmt_prepare($preparedStatement, $sql)) {
            header("location: ../upload.php?error=stmtfailed");
            exit();
          }

          mysqli_stmt_bind_param($preparedStatement, "ii", $fileId, $tagId);
          mysqli_stmt_execute($preparedStatement);

          mysqli_stmt_close($preparedStatement);
        }

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
