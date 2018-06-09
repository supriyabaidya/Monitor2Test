<?php

$user = "root";
$pwd = "";
$db = "sensor_cloud";

$link = mysqli_connect('localhost', $user, $pwd) or die("Could not connect to MySQL server !!");

mysqli_select_db($link, $db) or die("'" . $db . "' database is not found.");
