<?php
ini_set("soap.wsdl_cache_enabled", "0");
$client = new SoapClient("http://localhost:8081/WebserviceTestOpenshift/Test?wsdl"); // soap webservice call

$functions = $client->__getFunctions();
$types = $client->__getTypes();

//$response1 = $client->__soapCall('twoDimesionArray', array('parameters' => array('name' => 'Test __soapCall')));
$response = $client->hello(array('name' => 'Test hello(finally from netbeans).'));

$response2dArray=$client->twoDimesionArray(array('username'=>'test_php','array' => array(array('12','34'),array('56','78'))));

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
//if (isset($_POST['destroy_cookie'])) {
//    echo 'cookie ' . $_COOKIE['test'];
//    unset($_COOKIE['test']);
////    
//    echo setcookie('test', '', time() - 3600);
//    echo 'cookie ' . $_COOKIE['test'];
//}


echo 'response</br>';

var_dump($functions);

var_dump($types);

var_dump($response);

var_dump($response2dArray);
