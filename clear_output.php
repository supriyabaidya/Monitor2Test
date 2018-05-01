<?php
session_start();

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

if (isset($_POST['submit_clear_output']) && isset($_SESSION['username'])) {
    echo 'hi';
    ini_set("soap.wsdl_cache_enabled", "0");
    $client = new SoapClient("http://localhost:8081/WebserviceTestOpenshift/Test?wsdl"); // soap webservice call
//    $client = new SoapClient("http://web-service-android-sensor-web-service.1d35.starter-us-east-1.openshiftapps.com/Test?wsdl"); // soap webservice call
    $client->clearOutput(array('service_usersUsername' => $_SESSION['username'] . '_1'));
    header('location:profiles/');
}

