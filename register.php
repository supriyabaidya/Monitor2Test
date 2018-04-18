<?php
session_start();

if (isset($_POST['submit_register'])) {         // user registered using credentials

    /* the credentials are stored in session */
    $_SESSION['name'] = $_POST['name'];
    $_SESSION['username'] = $_POST['username'];
    $_SESSION['password'] = $_POST['password'];
    $_SESSION['phone'] = $_POST['phone'];

    header('location:payment');     // then redirecting to `payment.php` page
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

        Register here :
        <form action="" method="post" onsubmit="return validate()" >
            <pre style="font-size: 16px">
Name       : <input type="text" name="name" placeholder="full name" /></br>
Username   : <input type="text" name="username" placeholder="email" onblur="validate_username(this.value)"/><div id='username_error'></div></br>
Password   : <input type="password" name="password" placeholder="********" /></br>
Phone no.  : <input type="number" name="phone" placeholder="phone no." /></br>
             <input type="submit" name="submit_register" value="register"/>
            </pre>
        </form>
        Already registered ? <a href="index">login here</a>
    </body>
    <script type="text/javascript">
        var valid = false;
        function validate() {
            return valid;
        }
        function validate_username(username) {

//            var regExpString = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/g;
//            var regExpString = /^[a-zA-Z0-9\.]+$/g;
            var regExpString = /^((.)+)$/g;

            if (regExpString.test(username)) {
                document.getElementById("username_error").innerHTML = "";
                valid = true;
            } else
                document.getElementById("username_error").innerHTML = "invalid email";
        }
    </script>
</html>
