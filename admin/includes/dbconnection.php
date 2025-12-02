<?php

$server = "localhost";
$username = "root";
$password = "";
$database = "moviemate_db";

$con = mysqli_connect($server, $username, $password, $database);

if (!$con) {
    die("Connection Failed: " . mysqli_connect_error());
}
