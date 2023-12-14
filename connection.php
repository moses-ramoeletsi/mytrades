<?php

$databaseServerName = "localhost";
$databaseUsername = "root";
$databasePassword = "";
$databaseName = "trading_records";

$conn = mysqli_connect($databaseServerName, $databaseUsername, $databasePassword, $databaseName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>
