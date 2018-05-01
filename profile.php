<?php
session_start();

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templatess
 * and open the template in the editor.
 */

if (isset($_SESSION['username']) && $_SESSION['username'] === $_SERVER['QUERY_STRING']) {       // if session is set && session value and QUERY_STRING are same then show user profile
    echo 'Welcome ' . $_SESSION['username'] . ' ,</br>';

    ini_set("soap.wsdl_cache_enabled", "0");

    $client = new SoapClient("http://localhost:8081/WebserviceTestOpenshift/Test?wsdl"); // soap webservice call
//    $client = new SoapClient("http://web-service-android-sensor-web-service.1d35.starter-us-east-1.openshiftapps.com/Test?wsdl"); // soap webservice call

    $functions = $client->__getFunctions();
    $types = $client->__getTypes();

    $matrix = array(array('i', 'j', 'S'), array('1', '1', '1'), array('1', '2', '0'), array('2', '1', '1'), array('2', '2', '0'), array('3', '1', '0'), array('3', '2', '1'));
    $mainComputations1 = $client->mainComputations(array('service_usersUsername' => $_SESSION['username'] . '_1', 'noOfSensors' => '3', 'noOfTargets' => '2', 'matrix' => $matrix));
    $mainComputations2 = $client->mainComputations(array('service_usersUsername' => $_SESSION['username'] . '_2', 'noOfSensors' => '3', 'noOfTargets' => '2', 'matrix' => $matrix));
//$response1 = $client->__soapCall('twoDimesionArray', array('parameters' => array('name' => 'Test __soapCall')));
//    $response1 = $client->hello(array('name' => 'Test hello (finally from netbeans).'));
//    $response2 = $client->hello(array('name' => 'Test hello (finally from netbeans).'));
//    $response2dArray1 = $client->twoDimesionArray(array('username' => $_SESSION['username'].'_1', 'array' => array(array('12', '34'), array('56', '78'))));
//    $response2dArray2 = $client->twoDimesionArray(array('username' => $_SESSION['username'].'_2', 'array' => array(array('12', '34'), array('56', '78'))));
//    $clearOutput = $client->clearOutput(array('service_usersUsername' => $_SESSION['username'].'_1'));

    echo '</br>response</br>';
    $clear_output_form = '<form action="../clear_output" method="post" >
            <input type="submit" name="submit_clear_output" value="clear output"/>
        </form>';

    echo $clear_output_form;

    var_dump($functions);

    var_dump($types);

//    var_dump($response1);
//    var_dump($response2);
//
//    var_dump($response2dArray1);
//    var_dump($response2dArray2);
//    var_dump($mainComputations1);
//    var_dump($mainComputations2);
//    var_dump($clearOutput);

    exit();
} else {    // else redirect to `index.php`
    header('location:../index');
}

if (isset($_POST['submit_logout'])) {
    setcookie('username', $_SESSION['username'], time() - 3600, '/');
    unset($_COOKIE['username']);
    session_destroy();

    header('location:../index');
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

//            var items = [
//                [1, 2],
//                [3, 4],
//                [5, '6']
//            ];
//            
//            console.log(items);
//            var p1 = new google.maps.LatLng(22.575597, 88.351059);
//            var p2 = new google.maps.LatLng(22.577229, 88.277507);


            function calculateDistance(p1, p2) {
                return (google.maps.geometry.spherical.computeDistanceBetween(p1, p2)).toFixed(2);
            }

//            console.log('dist ' + calculateDistance(p1, p2));

            var isMouseOnMap = false;

            var interval = 5000;

            var map, marker, latlng, bounds, infowin;

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
                    addMarker('target', event.latLng.lat().toFixed(6), event.latLng.lng().toFixed(6), 'T' + targetIndex + ' ; latitude: ' + event.latLng.lat().toFixed(6) + ' , longitude: ' + event.latLng.lng().toFixed(6), "../red-dot.png");
                });
            }

            var sensorRadius = 10;
            var targetIndex = 0;
            var sensorsArray = [];
            var sensorsLocationArray = [];
            var targetsArray = [];
            var targetsLocationArray = [];

            var matrix;
            var matrixIndex;

            function addMarker(type, lat, lng, title, icon) {

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

//                markersArray.push(marker);

//                google.maps.event.addListener(marker, 'click', function (event) {
//                    infowin.setContent(this.title);
//                    infowin.open(map, this);
//                    infowin.setPosition(this.position);
//                }.bind(marker));

                google.maps.event.addListener(marker, 'click', function () {
//                    var pos = map.getZoom();
                    map.setZoom(25);
                    map.setCenter(this.getPosition());
//                    window.setTimeout(function () {
//                        map.setZoom(pos);
//                    }, 3000);
                });

//                bounds.extend(marker.position);
//                map.fitBounds(bounds);

                //     google.maps.event.addListenerOnce(map, 'bounds_changed', function(event) {
                //     	  if (this.getZoom() < 15) {
                //     	    this.setZoom(20);
                //     	  }
                //     	});
            }

            function clearAllSensors() {
                var length = sensorsArray.length;
                for (var i = 0; i < length; i++) {
                    sensorsArray[i].setMap(null);
                }
                sensorsArray.length = 0;
                sensorsLocationArray.length = 0;
            }

            function clearAllTargets() {
                var length = targetsArray.length;
                for (var i = 0; i < length; i++) {
                    targetsArray[i].setMap(null);
                }
                targetsArray.length = 0;
                targetsLocationArray.length = 0;

                targetIndex = 0;
            }

            function showAllSensors() {
                var sensors = 'sensors : ';
                var length = sensorsLocationArray.length;
                sensors += length + '\n';
                for (var i = 0; i < length; i++) {
                    sensors += 'lat : ' + sensorsLocationArray[i].lat + ' long : ' + sensorsLocationArray[i].lng + '\n';
                }
                console.log(sensors);
//                document.getElementById('error').innerHTML = sensors;
            }

            function showAllTargets() {
                var targets = 'targets : ';
                var length = targetsLocationArray.length;
                targets += length + '\n';
                for (var i = 0; i < length; i++) {
                    targets += 'lat : ' + targetsLocationArray[i].lat + ' long : ' + targetsLocationArray[i].lng + '\n';
                }
                console.log(targets);
//                document.getElementById('targets').innerHTML = targets;
            }


            function showCoverageR() {
                matrix = [];
                matrixIndex = 0;
                var rows = sensorsLocationArray.length;
                var columns = targetsLocationArray.length;
                var i = 0, j = 0;
                var table = 'CoverageR table:<br/>';
                table += '<table>\n';
                table += '<tr>';
                table += '<th><pre>\tTargets<br/>Sensors</pre></th>';
                matrix[matrixIndex] = [];
//                matrix[matrixIndex].push('Sensors\\Targets');
                matrix[matrixIndex].push('i');
                matrix[matrixIndex].push('j');
                matrix[matrixIndex].push('S');
//                matrix[matrixIndexI][matrixIndexJ] = '<pre>\tTargets<br/>Sensors</pre>';
//                matrixIndexJ++;
                for (i = 0; i < columns; i++) {
                    table += '<th>T' + i + '</th>';
//                    matrix[matrixIndex].push('T' + i);
                }
                table += '</tr>\n';

                for (i = 0; i < rows; i++) {
//                    matrixIndex++;
////                    matrixIndexJ = 0;
//                    matrix[matrixIndex] = [];
                    var eachSensorLocation = new google.maps.LatLng(sensorsLocationArray[i].lat, sensorsLocationArray[i].lng);
                    table += '<tr><td>' + sensorsLocationArray[i].name + '</td>';
//                    matrix[matrixIndex].push(sensorsLocationArray[i].name);
                    for (j = 0; j < columns; j++) {
                        matrixIndex++;
                        matrix[matrixIndex] = [];
                        matrix[matrixIndex].push(i);
                        matrix[matrixIndex].push(j);
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

            function fileTest() {
                jQuery.ajax({
                    type: "POST",
                    url: "../generateCoverageR.php",
                    dataType: "json",
                    data: {matrix: JSON.stringify(matrix)},
                    success: function (response) {
//                        clearAllSensors();

//                        document.getElementById("output").innerHTML = response.html;
                        document.getElementById("CoverageRequest").innerHTML = response.html;
                        document.getElementById("error").innerHTML = 'No error';
//                        $.each(response.places, function (i, item) {
//                            if (item.status === 'Online') {
//                                addMarker('sensor', item.latitude, item.longitude, item.name, "greenball.png");
//                            } else {
//                                addMarker('sensor', item.latitude, item.longitude, item.name, "pinkball.png");
//                            }
//                        });
//
////                        showCoverageR();
//                        window.scrollTo(0, document.body.scrollHeight);
                    },
                    error: function (status, error) {
                        document.getElementById("error").innerHTML = "status " + status + " , error " + error;
                    }
                });
//                file=fopen('1st.txt',3);


//                console.log(file);
//                fclose(file);
            }

            function getSelectedCheckboxesArray() {
                var ch_list = Array();
                $("input:checkbox[type=checkbox]:checked").each(function () {
                    ch_list.push($(this).val());
                });
                return ch_list;
            }

            function displayUsers() {
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

                            document.getElementById("output").innerHTML = response.html;
                            document.getElementById("time").innerHTML = "time : " + new Date();
                            $.each(response.places, function (i, item) {
                                if (item.status === 'Online') {
                                    addMarker('sensor', item.latitude, item.longitude, item.name, "../greenball.png");
                                } else {
                                    addMarker('sensor', item.latitude, item.longitude, item.name, "../pinkball.png");
                                }
                            });

                            showCoverageR();
                            window.scrollTo(0, document.body.scrollHeight);
                            document.getElementById("error").innerHTML = 'No error';
                            document.getElementById("CoverageRequest").innerHTML = '';
                        },
                        error: function (status, error) {
                            document.getElementById("error").innerHTML = "status " + status + " , error " + error;
                        }
                    });
                }
            }

            displayUsers();

            setInterval("displayUsers()", interval);
//            showCoverageR();

        </script>
    </head>
    <body>
        <form action="" method="post" >
            <input type="submit" name="submit_logout" value="logout"/>
        </form>

        <div id='output'></div><div id='CoverageR' ></div><br/>
        <div id='time'></div>
        <div id='map' onmouseover='(function () {
                    document.getElementById("mouse").innerHTML = "onmouseenter ";
                    isMouseOnMap = true;
                })();' onmouseout='(function () {
                            document.getElementById("mouse").innerHTML = "onmouseout ";
                            isMouseOnMap = false;
                        })();' ></div>
        <div id='mouse'></div>

        <div id='error' >No error</div>

        <input type="button" onclick="clearAllTargets()" value="clear All Targets" />
        <input type="button" onclick="showAllTargets()" value="show All Targets" />
        <input type="button" onclick="showAllSensors()" value="show All Sensors" />
        <!--<br/>-->
        <input type="button" onclick="fileTest()" value="file Test" />

        <div id='CoverageRequest' ></div>
        <script type="text/javascript">
            showCoverageR();
        </script>
    </body>

</html>