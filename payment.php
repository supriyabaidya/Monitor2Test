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

include './database.php';

if (isset($_POST['stripeToken'])) {        //register(insert into db) after payment is done
    mysqli_query($link, "insert into service_users(username,password,name,phone) values('" . $_SESSION['username'] . "','" . $_SESSION['password'] . "','" . $_SESSION['name'] . "','" . $_SESSION['phone'] . "') ;") or die("\'insert\' query execution is failed!! ");
    mysqli_close($link);

    unset($_SESSION['name']);
    unset($_SESSION['username']);
    unset($_SESSION['password']);
    unset($_SESSION['phone']);

    $_SESSION['message'] = 'Registration(payment) is succeeded , now login please';

    header('location:index');
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
        <p>Pay INR 999 for the service</p>
        <form action="" method="POST">
            <script
                src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                data-key="pk_test_y2htyJP1Moim2lmmVjZQUIkN"
                data-currency="INR"
                data-amount="99900"
                data-name="<?php echo $_SESSION['name']; ?>"
                data-email="<?php echo $_SESSION['username']; ?>"
                data-description="service charge"
                data-locale="auto"
                >
            </script>
        </form>
    </body>
</html>