<?php

session_start();

//$client = new SoapClient("http://web-service-android-sensor-web-service.1d35.starter-us-east-1.openshiftapps.com/AndroidWebService?wsdl"); // soap webservice call
$client = new SoapClient("http://localhost:8081/WebserviceTestOpenshift/Test?wsdl"); // (local) soap webservice call
//$response = $client->getUsers();
$showOutput = $client->showOutput(array('service_usersUsername' => $_SESSION['username'] . '_1'));
$exception = 'Error: ';
$output = '<table>
				<tr><th>Sensor Id</th><th>proximity</th><th>light</th><th>Time</th></tr></br>';

foreach ($showOutput as $row) {
//        echo '$row ' . sizeof($row) . '</br>';

    foreach ($row as $object) {
//            echo '$object ' . sizeof($object) . '</br>';

        if (sizeof($row) === 1 && sizeof($object) === 1) {         // for cacthing error,which is only one string value in 2d array
            $exception = $exception . $row->item;
        } elseif (sizeof($row) === 1 && sizeof($object) === 4) {        // if there is only one row in the generated temp_output file
            $output = $output . '<tr><th>' . $object[0] . '</th><th>' . $object[1] . '</th><th>' . $object[2] . '</th><th>' . $object[3] . '</th></tr></br>';
        } else {
            foreach ($object as $value) {
//                    echo '$value ' . sizeof($value) . '</br>';

                $output = $output . '<tr><th>' . $value[0] . '</th><th>' . $value[1] . '</th><th>' . $value[2] . '</th><th>' . $value[3] . '</th></tr></br>';
            }
        }
    }
}
//foreach ($showOutput as $object) {
//    foreach ($object as $row) {
//        foreach ($row as $value) {
//            $output = $output . '<tr><th>' . $value[0] . '</th><th>' . $value[1] . '</th><th>' . $value[2] . '</th><th>' . $value[3] . '</th></tr></br>';
//        }
//    }
//}
$output = $output . '</table></br>';

$no_of_sensors = $_POST['no_of_sensors'];
$no_of_targets = $_POST['no_of_targets'];

$sensors = '<div class="dataset"></br>
		<h1 class="title"> List of Users </h1></br>
			<form action="../sendNotification.php" method="POST"></br>
                <input type="hidden" name="no_of_sensors" value="' . $no_of_sensors . '" /></br>
                <input type="hidden" name="no_of_targets" value="' . $no_of_targets . '" /></br>
				<table>
					<tr><th>select</th><th>username</th><th>longitude</th><th>latitude</th><th>status</th><th>proximity</th><th>light</th></tr></br>';

$places = array();
//foreach ($response as $object) {
//    $size = sizeof($object);
//
//    for ($i = 0; $i < $size; $i ++) {
//        foreach ($object [$i] as $rows) {
//            $sensors = $sensors . '<tr><td> <input type="checkbox" name="selected[]" value= "' . $rows [0] . '"/></td><td>' . $rows [1] . '</td><td>' . $rows [2] . '</td><td>' . $rows [3] . '</td><td>' . $rows [4] . '</td><td>' . $rows [5] . '</td><td>' . $rows [6] . '</td></tr></br>';
//
//            $places [] = array(
//                'latitude' => $rows [3],
//                'longitude' => $rows [2],
//                'name' => $rows [1],
//                'status' => $rows [4]
//            );
//        }
//    }
//}

$sensors = $sensors . '</table></br>
				<input type="submit" name="send_request" value="send request" /></br>
			</form></br>
		</div></br>';

// Handle Success Message
echo json_encode(array(
    'sensors' => $sensors,
    'places' => $places,
    'output' => $output,
    'exception' => $exception
));
?>