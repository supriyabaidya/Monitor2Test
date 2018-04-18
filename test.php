<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//
//if (isset($_GET['id'])) {
//    $id = $_GET['id'];
//} else {
//    $id = 'nothing';
//}
//echo 'id - ' . $id;
//
//$file=  fopen("testFiles\\".$id.".txt", "w");
//
//fwrite($file, "hello ".$id);
////fputs($file, "hello ".$id);
//
//fclose($file);
//
//header('location:../index.php');

if (isset($_POST['destroy_cookie'])) {
    echo 'cookie ' . $_COOKIE['test'];
    unset($_COOKIE['test']);
//    
    echo setcookie('test', '', time() - 3600);
    echo 'cookie ' . $_COOKIE['test'];
}