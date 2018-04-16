<?php
$session_name = "SERVICE-SESSID";
$session_id = "LOGIN";
session_name($session_name);
session_id($session_id);
session_start();

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

if (isset($_POST['submit_logout'])) {
    session_destroy();
    header('location:../index');
}
$username = $_SESSION['username'];
echo 'welcome ' . $username;
?>

<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Monitor</title>
    </head>
    <body>

        <form action="" method="post" >
            <input type="submit" name="submit_logout" value="logout"/>
        </form>
    </body>
</html>