<?php
session_start();

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templatess
 * and open the template in the editor.
 */

if (isset($_POST['submit_logout'])) {
    setcookie('username', $_SESSION['username'], time() - 3600, '/');
    unset($_COOKIE['username']);
    session_destroy();

    header('location:../index');
}

if (isset($_SESSION['username']) && $_SESSION['username'] === $_SERVER['QUERY_STRING']) {       // if session is set && session value and QUERY_STRING are same then show user profile
    echo 'Welcome ' . $_SESSION['username'] . ' ,';
} else {    // else redirect to `index.php`
    header('location:../index');
}
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