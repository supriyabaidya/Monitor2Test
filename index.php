<?php
$session_name = "SERVICE-SESSID";
$session_id = "LOGIN";
session_name($session_name);
session_id($session_id);
session_start();

include './database.php';

if (isset($_POST['submit_login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
//    echo 'username ' . $username . ' , password ' . $password . '</br>';
    $result1 = mysqli_query($link, "select * from service_users where username='" . $username . "' ;");
    $result2 = mysqli_query($link, "select * from service_users where username='" . $username . "' and password='" . $password . "';");
//    echo 'no @mysqli_num_rows($result) '.@mysqli_num_rows($result1);
    if (@mysqli_num_rows($result1) < 1) {
        mysqli_close($link);
        echo 'Username doesn\'t exist</br>';
    } elseif (@mysqli_num_rows($result2) < 1) {
        mysqli_close($link);
        echo 'Incorrect password</br>';
    } elseif (!isset($_SESSION['username'])) {
        mysqli_close($link);
        $_SESSION['username'] = $username;

        header('location:profile/' . $_SESSION['username']);
    }
}

if (isset($_SESSION['message'])) {
    echo $_SESSION['message'] . '</br>';
    unset($_SESSION['message']);
    mysqli_close($link);
}

if (isset($_SESSION['username'])) {
    mysqli_close($link);

    header('location:profile/' . $_SESSION['username']);
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
        <!--        <a href="tests/1a" >1a</a></br>
                <a href="tests/2z" >2z</a>-->
        Login here :
        <form action="" method="post" >
            Username :
            <input type="text" name="username" placeholder="email" /></br>
            Password :
            <input type="password" name="password" placeholder="********" /></br>
            <input type="submit" name="submit_login" value="login"/>
        </form>
        New user ? <a href="register">Register here</a>
    </body>
</html>
