<?php

session_start();

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$array = json_decode($_POST['matrix']);
$noOfSensors = json_decode($_POST['noOfSensors']);
;
$noOfTargets = json_decode($_POST['noOfTargets']);
;

$matrix = array();
foreach ($array as $row) {
    $temp = array();
    foreach ($row as $data) {
        array_push($temp, $data);
    }

    array_push($matrix, $temp);
}

ini_set("soap.wsdl_cache_enabled", "0");

$client = new SoapClient("http://sensor-target-coverage.ap-south-1.elasticbeanstalk.com/Test?wsdl"); // soap webservice call


$mainComputations1 = $client->mainComputations(array('service_usersUsername' => $_SESSION['username'], 'noOfSensors' => $noOfSensors, 'noOfTargets' => $noOfTargets, 'matrix' => $matrix));

echo json_encode(array(
    'html' => 'request is sent successfully.</br>Response : Process completion is ' . $mainComputations1->return,
));
