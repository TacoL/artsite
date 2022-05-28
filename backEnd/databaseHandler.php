<?php

$serverName = "localhost";
$databaseUsername = "root";
$databasePassword = "";
$databaseName = "artistSiteDB";

$conn = mysqli_connect($serverName, $databaseUsername, $databasePassword, $databaseName);

if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}
