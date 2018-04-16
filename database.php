<?php

$user = "root";
$pwd = "";
$db = "server_db";

$link = mysqli_connect('localhost', $user, $pwd) or die("Could not connect to MySQL server !!!");

mysqli_select_db($link, $db) or die("'" . $db . "' database is not found");
