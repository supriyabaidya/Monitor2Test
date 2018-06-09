<?php

session_start();

ini_set("soap.wsdl_cache_enabled", "0");

$client = new SoapClient("http://sensor-target-coverage.ap-south-1.elasticbeanstalk.com/Test?wsdl"); // soap webservice call
//$client = new SoapClient("http://localhost:8081/WebserviceTestOpenshift/Test?wsdl"); // (local) soap webservice call

$displaySensors = $client->displaySensors();
$showOutput = $client->showOutput(array('service_usersUsername' => $_SESSION['username']));
$exception = 'Error: ';


$no_of_sensors = $_POST['no_of_sensors'];
$no_of_targets = $_POST['no_of_targets'];

$sensors = '</br><h3> List of Sensors: </h3>
            <input type="hidden" name="no_of_sensors" value="' . $no_of_sensors . '" />
            <input type="hidden" name="no_of_targets" value="' . $no_of_targets . '" />
			<table>
				<tr><th>select</th><th>Sensor Id</th><th>username</th><th>latitude</th><th>longitude</th><th>status</th></tr>';

$places = array();

foreach ($displaySensors as $row) {
//        echo '$row ' . sizeof($row) . '</br>';

    foreach ($row as $object) {
//            echo '$object ' . sizeof($object) . '</br>';

        if (sizeof($row) === 1 && sizeof($object) === 1) {         // for cacthing error,which is only one string value in 2d array
            $exception = $exception . ' , 2) ' . $row->item;
        } elseif (sizeof($row) === 1 && sizeof($object) === 6) {        // if there is only one row in the generated temp_output file
            $sensors = $sensors . '<tr><td> <input type="checkbox" name="selected[]" value= "' . $object [0] . '"/></td><td>' . $object [1] . '</td><td>' . $object [2] . '</td><td>' . $object [3] . '</td><td>' . $object [4] . '</td><td>' . $object [5] . '</td>';

            $places [] = array(
                'latitude' => $object [3],
                'longitude' => $object [4],
                'name' => $object [2],
                'id' => $object [1],
                'status' => $object [5]
            );
        } else {
            foreach ($object as $value) {
//                echo '$value ' . sizeof($value) . '</br>';

                $sensors = $sensors . '<tr><td> <input type="checkbox" name="selected[]" value= "' . $value [0] . '"/></td><td>' . $value [1] . '</td><td>' . $value [2] . '</td><td>' . $value [3] . '</td><td>' . $value [4] . '</td><td>' . $value [5] . '</td>';

                $places [] = array(
                    'latitude' => $value [3],
                    'longitude' => $value [4],
                    'name' => $value [2],
                    'id' => $value [1],
                    'status' => $value [5]
                );
            }
        }
    }
}


$sensors = $sensors . '</table>';

$output = ' </br><h3> Output: </h3>
            <table>
                <tr><th>Sensor Id</th><th>proximity</th><th>light</th><th>Time</th></tr>';

foreach ($showOutput as $row) {
//        echo '$row ' . sizeof($row) . '</br>';

    foreach ($row as $object) {
//            echo '$object ' . sizeof($object) . '</br>';

        if (sizeof($row) === 1 && sizeof($object) === 1) {         // for cacthing error,which is only one string value in 2d array
            $exception = $exception . '1) ' . $row->item;
        } elseif (sizeof($row) === 1 && sizeof($object) === 4) {        // if there is only one row in the generated temp_output file
            $output = $output . '<tr><td>' . $object[0] . '</td><td>' . $object[1] . '</td><td>' . $object[2] . '</td><td>' . $object[3] . '</td></tr>';
        } else {
            foreach ($object as $value) {
//                    echo '$value ' . sizeof($value) . '</br>';

                $output = $output . '<tr><td>' . $value[0] . '</td><td>' . $value[1] . '</td><td>' . $value[2] . '</th><td>' . $value[3] . '</td></tr>';
            }
        }
    }
}

$output = $output . '</table></br>';

// Handle Success Message and sending requested data
echo json_encode(array(
    'sensors' => $sensors,
    'places' => $places,
    'output' => $output,
    'exception' => $exception
));
