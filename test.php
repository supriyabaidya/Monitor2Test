<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


if (isset($_GET['id'])) {
    $id = $_GET['id'];
} else {
    $id = 'nothing';
}
echo 'id - ' . $id;

$file=  fopen("testFiles\\".$id.".txt", "w");

fwrite($file, "hello ".$id);
//fputs($file, "hello ".$id);

fclose($file);

header('location:../index.php');