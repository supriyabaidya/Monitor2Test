<?php
session_start();

//header('location:test');

include './database.php';

if (isset($_POST['submit_login'])) {    // when user logged in
    $username = $_POST['username'];
    $password = $_POST['password'];
//    echo 'username ' . $username . ' , password ' . $password . '</br>';
    $result1 = mysqli_query($link, "select * from service_users where username='" . $username . "' ;");  //query for checking Username existance
    $result2 = mysqli_query($link, "select * from service_users where username='" . $username . "' and password='" . $password . "';");  //query for checking Username and password are matched or not
//    echo 'no @mysqli_num_rows($result) '.@mysqli_num_rows($result1);
    if (@mysqli_num_rows($result1) < 1) {       //checking Username existance
        echo 'Username doesn\'t exist</br>';
    } elseif (@mysqli_num_rows($result2) < 1) {     //checking Username and password are matched or not
        echo 'Incorrect password</br>';
    } elseif (!isset($_SESSION['username'])) {
        mysqli_close($link);

        $_SESSION['username'] = $username;      // setting session after login
        setcookie('username', $_SESSION['username'], time() + 60 * 60 * 24 * 180, '/');     //setting cookies for not to get logged out

        header('location:profiles/' . $_SESSION['username']);       //redirecting to profile page ,since credentials are validated to correct
    }
}

if (isset($_SESSION['message'])) {       //showing some message after registration , `$_SESSION['message']` is set in `payment.php` page
    echo $_SESSION['message'] . '</br>';
    unset($_SESSION['message']);
}

if (isset($_COOKIE['username'])) {      // if cookie is found redirect to profile page
    mysqli_close($link);

    $_SESSION['username'] = $_COOKIE['username'];           // setting session after reopening browser ,since user closed browser without logged out 

    header('location:profiles/' . $_SESSION['username']);      //redirecting to profile page ,since user is already logged in and didn't logged out
}

mysqli_close($link);
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
