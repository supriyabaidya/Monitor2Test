<?php
// ini_set ( "display_errors", "1" );
function send_notification($tokens, $message) {
	$url = 'https://fcm.googleapis.com/fcm/send';
	$fields = array (
			'registration_ids' => $tokens,
			'data' => $message 
	);
	$headers = array (
			'Authorization:key = AAAAgB2I4bs:APA91bFxM9j79sdSul5PUl8jxujpu0qDAJjTSZAREWomFdvLYxFs2I7t9RQcL8SgYp8Zvw7rhm814xQyihIEWrWx--UflVuTQovMMq5tLbCo4WQzqakhctq9Xfb9ffU4XHxzT8vpM7kg ',
			'Content-Type: application/json' 
	);
	
	$ch = curl_init ();
	curl_setopt ( $ch, CURLOPT_URL, $url );
	curl_setopt ( $ch, CURLOPT_POST, true );
	curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
	curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
	curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, 0 );
	curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, false );
	curl_setopt ( $ch, CURLOPT_POSTFIELDS, json_encode ( $fields ) );
	
	$result = curl_exec ( $ch );
	if ($result === FALSE) {
		die ( 'Curl failed: ' . curl_error ( $ch ) );
	}
	curl_close ( $ch );
	return $result;
}

$array_of_tokens = array ();
foreach ( $_POST ['selected'] as $value ) {
	array_push ( $array_of_tokens, $value );
}
// print_r($array_of_tokens);
$message = array (
		"message" => " SB FCM PUSH NOTIFICATION TEST MESSAGE" 
);
send_notification ( $array_of_tokens, $message );

header ( 'location:monitor.html' );
?>