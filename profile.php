<?php
session_start();

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templatess
 * and open the template in the editor.
 */

if (isset($_SESSION['username']) && $_SESSION['username'] === $_SERVER['QUERY_STRING']) {       // if session is set && session value and QUERY_STRING are same then show user profile
    echo 'Welcome ' . $_SESSION['username'] . ' ,</br>';

//    ini_set("soap.wsdl_cache_enabled", "0");
////    $client = new SoapClient("http://web-service-target-coverage.7e14.starter-us-west-2.openshiftapps.com/Test?wsdl");
    $client = new SoapClient("http://sensor-target-coverage.ap-south-1.elasticbeanstalk.com/Test?wsdl");
//    $response1 = $client->hello(array('name' => 'Test hello'));
//////    
//    $fn=$client->__getFunctions();
//    var_dump($fn);
    $response2 = $client->databaseCheck(array('name' => 'DatabaseCheck : '));
//    var_dump($response1);
    var_dump($response2);

//    exit();
} else {    // else redirect to `final_sem_project.php`
    header('location:../final_sem_project.php');
}

if (isset($_POST['submit_logout'])) {
    setcookie('username', $_SESSION['username'], time() - 3600, '/');
    unset($_COOKIE['username']);
    session_destroy();

    header('location:../final_sem_project.php');
}
?>

<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="utf-8">
        <title>Monitor</title>
        <style type="text/css">
            table {
                background-color: white;
                border: 5px solid green;
                padding: 1px;
                border-spacing: 1px
            }

            td, th {
                text-align: left;
                border: 1px solid black;
                padding: 5px;
            }

            html, html body, #map {
                height: 80%;
                width: 100%;
                padding: 0;
                margin: 0;
            }
        </style>
        <script src="../jquery-1.10.2.js"></script>
        <script
            src='https://maps.google.com/maps/api/js?key=AIzaSyAXcmVPT65nQMHpdbOg8QpHryyEXyhgnrY&libraries=geometry'
        type='text/javascript'></script>

        <script type="text/javascript">

            function calculateDistance(p1, p2) {
                return (google.maps.geometry.spherical.computeDistanceBetween(p1, p2)).toFixed(2);
            }

            var isMouseOnMap = false;

            var interval = 5000;

            var map, marker, circle, latlng, bounds, infowin;

            /* initial locations for map */
            var _lat = 22.57722797;
            var _lng = 88.27761404;

            document.addEventListener('DOMContentLoaded', myMap, false);

            function myMap() {

                latlng = {lat: _lat, lng: _lng};

                bounds = new google.maps.LatLngBounds();
                infowin = new google.maps.InfoWindow();

                /* invoke the map */
                map = new google.maps.Map(document.getElementById('map'), {center: latlng, zoom: 15});

                /* add click listener to the map */
                google.maps.event.addListener(map, 'click', function (event) {
                    addMarker('target', event.latLng.lat().toFixed(6), event.latLng.lng().toFixed(6), 'T' + targetIndex + ' =>latitude: ' + event.latLng.lat().toFixed(6) + ' , longitude: ' + event.latLng.lng().toFixed(6), null, "../red-dot.png");
                });
            }

            var sensorRadius = 1.0;
            var targetIndex = 1;
            var sensorsArray = [];
            var sensorsLocationArray = [];
            var targetsArray = [];
            var targetsLocationArray = [];

            var circleArray = [];

            var matrix;
            var matrixIndex;

            function addMarker(type, lat, lng, title, status, icon, circleNeeded = false) {

//                console.log(parseFloat(lng)+' , '+lng);
                marker = new google.maps.Marker({/* Cast the returned data as floats using parseFloat() */
                    position: {
                        lat: parseFloat(lat),
                        lng: parseFloat(lng)
                    },
                    map: map,
                    title: title,
                    icon: icon
                });

                if (type === 'sensor') {
                    sensorsArray.push(marker);
                    sensorsLocationArray.push({name: title, lat: parseFloat(lat), lng: parseFloat(lng)});
                } else if (type === 'target') {
                    targetsArray.push(marker);
                    targetsLocationArray.push({name: 'T' + targetIndex, lat: parseFloat(lat), lng: parseFloat(lng)});
                    targetIndex++;
                }

                google.maps.event.addListener(marker, 'click', function () {
                    map.setZoom(25);
                    map.setCenter(this.getPosition());
                });

                if (circleNeeded === true) {
                    var color = "";
                    if (status === 'Online') {
                        color = "green";
                    } else if (status === 'Offline') {
                        color = "red";
                    }

                    circle = new google.maps.Circle({
                        map: map,
                        radius: sensorRadius, // radius in meter 
                        strokeColor: color,
                        strokeOpacity: 0.8,
                        strokeWeight: 2,
                        fillColor: color
                    });

                    circle.bindTo('center', marker, 'position');

                    google.maps.event.addListener(circle, 'click', function (event) {       // to make the cicle clickable too,i.e, for getting target in map as well as in the circles too
                        addMarker('target', event.latLng.lat().toFixed(6), event.latLng.lng().toFixed(6), 'T' + targetIndex + ' =>latitude: ' + event.latLng.lat().toFixed(6) + ' , longitude: ' + event.latLng.lng().toFixed(6), null, "../red-dot.png");
                    });

                    circleArray.push(circle);
                }

                if (type === 'target') {
                    showCoverageR();
                }
            }

            function clearAllSensors() {
                var length = sensorsArray.length;
                for (var i = 0; i < length; i++) {
                    sensorsArray[i].setMap(null);
                    circleArray[i].setMap(null);
                }
                sensorsArray.length = 0;
                sensorsLocationArray.length = 0;

                circleArray.length = 0
            }

            function clearAllTargets() {
                var length = targetsArray.length;
                for (var i = 0; i < length; i++) {
                    targetsArray[i].setMap(null);
                }
                targetsArray.length = 0;
                targetsLocationArray.length = 0;

                targetIndex = 0;

                showCoverageR(); // to refresh the CoverageR table after clearAllTargets
            }

            function showAllSensors() {
                var sensors = 'sensors : ';
                var length = sensorsLocationArray.length;
                sensors += length + '\n';
                for (var i = 0; i < length; i++) {
                    sensors += 'lat : ' + sensorsLocationArray[i].lat + ' long : ' + sensorsLocationArray[i].lng + '\n';
                }
                console.log(sensors);
            }

            function showAllTargets() {
                var targets = 'targets : ';
                var length = targetsLocationArray.length;
                targets += length + '\n';
                for (var i = 0; i < length; i++) {
                    targets += 'lat : ' + targetsLocationArray[i].lat + ' long : ' + targetsLocationArray[i].lng + '\n';
                }
                console.log(targets);
            }


            function showCoverageR() {
                matrix = [];
                matrixIndex = -1;
                var rows = sensorsLocationArray.length;
                var columns = targetsLocationArray.length;
                var i = 0, j = 0;
                var table = 'CoverageR table:<br/>';
                table += '<table>\n';
                table += '<tr>';
                table += '<th><pre>\tTargets<br/>Sensors</pre></th>';
                for (i = 0; i < columns; i++) {
                    table += '<th>T' + (i + 1) + '</th>';
                }
                table += '</tr>\n';

                for (i = 0; i < rows; i++) {

                    var eachSensorLocation = new google.maps.LatLng(sensorsLocationArray[i].lat, sensorsLocationArray[i].lng);
                    table += '<tr><td>' + sensorsLocationArray[i].name + '</td>';
                    for (j = 0; j < columns; j++) {
                        matrixIndex++;
                        matrix[matrixIndex] = [];
                        matrix[matrixIndex].push(i + 1);
                        matrix[matrixIndex].push(j + 1);
                        var eachTargetLocation = new google.maps.LatLng(targetsLocationArray[j].lat, targetsLocationArray[j].lng);
                        if (calculateDistance(eachSensorLocation, eachTargetLocation) <= sensorRadius) {
                            table += '<td>1</td>';
                            matrix[matrixIndex].push(1);
                        } else {
                            table += '<td>0</td>';
                            matrix[matrixIndex].push(0);
                        }

                    }
                    table += '</tr>';
                }

                table += '</table>\n';
                document.getElementById('CoverageR').innerHTML = table;
                console.log(matrix);
            }

            function send_request() {
//                console.log("targetsArray.length : " + targetsArray.length);

                if (matrix.length === 0) {
                    document.getElementById("CoverageRequest").innerHTML = 'No target is selected.';
                } else {
                    jQuery.ajax({
                        type: "POST",
                        url: "../generateCoverageR.php",
                        dataType: "json",
                        data: {matrix: JSON.stringify(matrix), noOfSensors: JSON.stringify(sensorsArray.length), noOfTargets: JSON.stringify(targetsArray.length)},
                        success: function (response) {
//                        clearAllSensors();

                            document.getElementById("CoverageRequest").innerHTML = response.html;
//                        document.getElementById("error").innerHTML = 'No error';
                        },
                        error: function (status, error) {
                            document.getElementById("CoverageRequest").innerHTML = "Error status : " + status + " , error " + error;
                        }
                    });
                }
            }

            function getSelectedCheckboxesArray() {
                var ch_list = Array();
                $("input:checkbox[type=checkbox]:checked").each(function () {
                    ch_list.push($(this).val());
                });
                return ch_list;
            }

            function displaySensors() {
                var check_list = Array();
                check_list = getSelectedCheckboxesArray();
                var noOfCheckedItems = check_list.length;

                if (noOfCheckedItems === 0 && !isMouseOnMap) {
                    jQuery.ajax({
                        type: "POST",
                        url: "../call_webservice_and_load_data.php",
                        dataType: "json",
                        data: {no_of_sensors: JSON.stringify(sensorsArray.length), no_of_targets: JSON.stringify(targetsArray.length)},
                        success: function (response) {
                            clearAllSensors();

                            document.getElementById("sensors").innerHTML = response.sensors;
                            document.getElementById("output").innerHTML = response.output;
//                            document.getElementById("exception").innerHTML = response.exception;
//                            document.getElementById("time").innerHTML = "Time : " + new Date();
                            $.each(response.places, function (i, item) {

                                if (item.status === 'Online') {
                                    addMarker('sensor', item.latitude, item.longitude, item.id + ' =>' + item.name, item.status, "../greenball.png", true);
                                } else {
                                    addMarker('sensor', item.latitude, item.longitude, item.id + ' =>' + item.name, item.status, "../pinkball.png", true);
                                }
                            });

//                            window.scrollTo(0, document.body.scrollHeight);
                            document.getElementById("CoverageRequest").innerHTML = '';
                        },
                        error: function (status, error) {
                            document.getElementById("error").innerHTML = "status " + status + " , error " + error;
                        }
                    });
                }
            }

            displaySensors();

            setInterval("displaySensors()", interval);

        </script>
    </head>

    <body>
        <form action="" method="post" >
            <input type="submit" name="submit_logout" value="logout"/>
        </form>

        <div id='sensors'></div>
        <!--<div id='time'></div>-->
        <input type="button" onclick="send_request()" value="send request" />
        <div id='CoverageRequest' ></div>        

        <div id='map' onmouseover='(function () {
                    document.getElementById("mouse").innerHTML = "Mouse status : mouse is in the map ";
                    isMouseOnMap = true;
                })();' onmouseout='(function () {
                            document.getElementById("mouse").innerHTML = "Mouse status : mouse is not in the map ";
                            isMouseOnMap = false;
                        })();' ></div>


        <b style="font-size: 150%;color: red;" >N.B: Refresh rate is '1 second' , to stop refresh place mouse in the map or select any sensor from above list.</b>
        <div id='mouse'></div>

        <div id='error' >No error</div>

        <div id='output'></div>
        <form action="../clear_output" method="post" >
            <input type="submit" name="submit_clear_output" value="clear output"/>
        </form>

        <!--<div id='exception'></div>-->
        <div id='CoverageR' ></div><br/>

        <input type="button" onclick="clearAllTargets()" value="clear All Targets" />
<!--        <input type="button" onclick="showAllTargets()" value="show All Targets" />
        <input type="button" onclick="showAllSensors()" value="show All Sensors" />-->


        <script type="text/javascript">
            showCoverageR();
        </script>

        <div id='test' style="padding-bottom: 5em"></div>

        <address class="bottom" style="
                 border: 2px solid blue;
                 margin: 10px;
                 padding: 5px;
                 background-color: darkcyan;
                 position: fixed;
                 bottom: 10px;
                 left: 45%">Supriya Baidya <a style="color: cyan" href="mailto:supriyobaidya63@gmail.com"> supriyobaidya63@gmail.com</a> <br/>
            Copyright &copy; Supriya Baidya 1995-2018 all rights reserved
        </address>

    </body>

</html>