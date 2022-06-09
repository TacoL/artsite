<?php
  session_start();
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Upload</title>
  </head>

<?php
  include_once 'header.php' //top banner
?>

<p>File types allowed: jpg, jpeg, png</p>
<p>Max Upload Size: 100 MB</p>
<form action="backEnd/uploadBackEnd.php" method="post" enctype="multipart/form-data">
  <input name="pieceName" type="text" placeholder="Piece Name" maxlength="128">
  <input name="description" type="text" placeholder="Description" maxlength="1000">
  <br>
  <p>Separate tags using commas: "tag1,tag2, ..."</p>
  <input name="tags" type="text" placeholder="Tags" maxlength="70">
  <br>
  <p>Private?</p>
  <input name="privateBool" type="checkbox">
  <br><br>
  <input name="file" type="file">
  <button name="submit" type="submit">Upload</button>
</form>

<?php

  if (isset($_GET["error"])) {
    $errorName = $_GET["error"];
    if ($errorName == "invalidtype") {
      echo "<p>Invalid file type!</p>";
    }
    else if ($errorName == "error") {
      echo "<p>There was an error uploading your file</p>";
    }
    else if ($errorName == "sizeexceedslimit") {
      echo "<p>File size cannot exceed 100 MB</p>";
    }
    else if ($errorName == "noName") {
      echo "<p>Piece name or description are empty!</p>";
    }
    else {
      echo "<p>There was an error</p>";
    }
  }
  else if (isset($_GET["uploadsuccess"])) {
    echo "<p>Upload Complete!</p>";
  }
  include_once 'footer.php'
?>
