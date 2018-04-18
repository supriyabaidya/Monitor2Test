<?php
if (isset($_POST['create_cookie'])) {
    echo setcookie('test', 'cookie', time() + 86400);
}



/*
* To change this license header, choose License Headers in Project Properties.
* To change this template file, choose Tools | Templates
* and open the template in the editor.
*/
?>


<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset = "UTF-8">
        <title>Monitor</title>
    </head>
    <body>
        <form action="" method="post" >
            <input type="submit" name="create_cookie" value="create cookie" />
        </form>
        <form action="test.php" method="post" >
            <input type="submit" name="destroy_cookie" value="destroy cookie" />
        </form>
    </body>
</html>