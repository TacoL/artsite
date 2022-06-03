<?php

//note: when uploading website to the internet, make sure to change the database credentials below
$serverName = "localhost";
$databaseUsername = "root";
$databasePassword = "";
$databaseName = "artistSiteDB";

$conn = mysqli_connect($serverName, $databaseUsername, $databasePassword, $databaseName);

if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}
