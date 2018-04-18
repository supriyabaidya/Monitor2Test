<?php

$client = new SoapClient("http://web-service-android-sensor-web-service.1d35.starter-us-east-1.openshiftapps.com/AndroidWebService?wsdl"); // soap webservice call
//$client = new SoapClient("http://localhost:8081/android/AndroidWebService?wsdl"); // (local) soap webservice call

$response = $client->getUsers();

$form = '<div class="dataset">
		<h1 class="title">List of Users</h1>
			<form action="sendNotification.php" method="POST">
				<table>
					<tr><th>select</th><th>username</th><th>longitude</th><th>latitude</th><th>status</th><th>proximity</th><th>light</th></tr>';

$places = array();
foreach ($response as $object) {
    $size = sizeof($object);

    for ($i = 0; $i < $size; $i ++) {
        foreach ($object [$i] as $rows) {
            $form = $form . '<tr><td> <input type="checkbox" name="selected[]" value= "' . $rows [0] . '"/></td><td>' . $rows [1] . '</td><td>' . $rows [2] . '</td><td>' . $rows [3] . '</td><td>' . $rows [4] . '</td><td>' . $rows [5] . '</td><td>' . $rows [6] . '</td></tr>';

            $places [] = array(
                'latitude' => $rows [3],
                'longitude' => $rows [2],
                'name' => $rows [1],
                'status' => $rows [4]
            );
        }
    }
}

$form = $form . '</table>
				<input type="submit" name="send_request" value="send request" />
			</form>
		</div>';

// Handle Success Message
echo json_encode(array(
    'html' => $form,
    'places' => $places
));
?>