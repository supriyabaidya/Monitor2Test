<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>Monitor</title>
<link type="text/css" href="styles/styles.css" rel="stylesheet" />

<style>
table {
	background-color: white
}

html, html body, #map {
	height: 80%;
	width: 100%;
	padding: 0;
	margin: 0;
}
</style>

<script src="jquery-1.10.2.js"></script>
<script
	src='https://maps.google.com/maps/api/js?key=AIzaSyAXcmVPT65nQMHpdbOg8QpHryyEXyhgnrY'
	type='text/javascript'></script>
<script type='text/javascript'>
            (function(){

                var map,marker,latlng,bounds,infowin;
                /* initial locations for map */
                var _lat=56.61543329027024;
                var _lng=-2.9266123065796137;

                var getacara=0; /* What should this be? is it a function, a variable ???*/

                function showMap(){
                    /* set the default initial location */
                    latlng={ lat: _lat, lng: _lng };

                    bounds = new google.maps.LatLngBounds();
                    infowin = new google.maps.InfoWindow();

                    /* invoke the map */
                    map = new google.maps.Map( document.getElementById('map'), {
                       center:latlng,
                       zoom: 10
                    });

                    /* show the initial marker */
                    marker = new google.maps.Marker({
                       position:latlng,
                       map: map,
                       title: 'Hello World!'
                    });
                    bounds.extend( marker.position );


                    /* I think you can use the jQuery like this within the showMap function? */
                    $.ajax({
                        /* 
                            I'm using the same page for the db results but you would 
                            change the below to point to your php script ~ namely
                            phpmobile/getlanglong.php
                        */
                        url: document.location.href,/*'phpmobile/getlanglong.php'*/
                        data: { 'map': null },
                        dataType: 'json',
                        success: function( data, status ){
                        	document.getElementById("test").innerHTML=data.html;
                            $.each( data.places, function( i,item ){
                            	document.getElementById("test"+i).innerHTML=item.name;
                            });
//                         	document.getElementById("test1").innerHTML="data";
//                             $.each( data, function( i,item ){
//                                 /* add a marker for each location in response data */ 
//                                 document.getElementById("test1").innerHTML = item.latitude;
//                                 document.getElementById("test1").innerHTML = item.longitude;
//                                 addMarker( item.latitude, item.longitude, item.name );
//                             });
                        },
                        error: function( status,error){
                        	document.getElementById("test1").innerHTML="status "+status+" , error "+error;
                        }
                    });                 
                }

                /* simple function just to add a new marker */
                function addMarker(lat,lng,title){
                    marker = new google.maps.Marker({/* Cast the returned data as floats using parseFloat() */
                       position:{ lat:parseFloat( lat ), lng:parseFloat( lng ) },
                       map:map,
                       title:title
                    });

                    google.maps.event.addListener( marker, 'click', function(event){
                        infowin.setContent(this.title);
                        infowin.open(map,this);
                        infowin.setPosition(this.position);
                    }.bind( marker ));

                    bounds.extend( marker.position );
                    map.fitBounds( bounds );
                }


                document.addEventListener( 'DOMContentLoaded', showMap, false );
            }());
        </script>
<script type="text/javascript">

            var interval = 5000;

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
                
                if (noOfCheckedItems === 0) {

                	$.ajax({
                        type: "GET",
                        url: "monitor.php",
                        data: {"ajax" :null},
                        datatype: "html",
                        async: true,
                        success: function (data) {
                        	document.getElementById("output").innerHTML = data;
			                document.getElementById("time").innerHTML="time : "+new Date();
			                window.scrollTo(0,document.body.scrollHeight);
                        }
                    });
                }
            }

            displayUsers();
            setInterval("displayUsers()", interval);

//             jQuery( document ).ready( function($) {
            	 
//                 /* Create Map. */
//                 var map = new GMaps({
//                     el: '#google-map',
//                     lat: '',
//                     lng: '',
//                     scrollwheel: false
//                 });
             
//                 /* Create Marker Here... */
//             });

        </script>
</head>
<body>


	<div id="output"></div>
	
    <?php
				
				// ini_set ( "display_errors", "1" );
				if (array_key_exists ( 'ajax', $_GET )) {
					$form = '<div class="dataset">
         <h1 class="title">List of Data</h1>
         <table width="100%" align="center" class="datatable" >
    <tr>
        <td class="dataField" ><label>Data 1</label></td>
        <td class="dataValue">Value 1</td>
    </tr>
    <tr>
        <td class="dataField" ><label>Data 2</label></td>
        <td class="dataValue">Value 2</td>
    </tr>
    <tr>
        <td class="dataField" ><label>Data 3</label></td>
        <td class="dataValue">Value 3</td>
    </tr>
</table>
</div>';
					
					$places = array ();
					$places [] = array (
							'latitude' => '22.5772364',
							'longitude' => '88.27759891',
							'name' => 'test on map' 
					);
					
					$places [] = array (
							'latitude' => '22.567662',
							'longitude' => '88.4158846',
							'name' => 'test2 on map' 
					);
					
					// Handle Success Message
					echo json_encode ( array (
							'status' => 'success',
							'message' => 'Received data successfully',
							'html' => $form,
							'places' => $places 
					) );
					// $m='Monitor';
					// echo json_encode($m);
					// include 'menu.html';
					
					// $client = new SoapClient ( "http://web-service-android-sensor-web-service.1d35.starter-us-east-1.openshiftapps.com/AndroidWebService?wsdl" ); // soap webservice call
					
					// $response = $client->getUsers ();
					// // var_dump($response);
					// // print_r ( $response );
					// // echo "</br>";
					
					// echo json_encode("<h2>Display Users :</h2>");
					// echo "<form action=\"sendNotification.php\" method=\"POST\">";
					// echo "<table>";
					// echo "<tr><th>select</th><th>username</th><th>longitude</th><th>latitude</th><th>status</th><th>proximity</th><th>light</th></tr>";
					
					// $places = array ();
					
					// foreach ( $response as $object ) {
					// $size = sizeof ( $object );
					
					// for($i = 0; $i < $size; $i ++) {
					// foreach ( $object [$i] as $rows ) {
					// echo "<tr><td> <input type=\"checkbox\" name=\"selected[]\" value=\"" . $rows [0] . "\"></td><td>" . $rows [1] . "</td><td>" . $rows [2] . "</td><td>" . $rows [3] . "</td><td>" . $rows [4] . "</td><td>" . $rows [5] . "</td><td>" . $rows [6] . "</td></tr>";
					// $places [] = array (
					// 'latitude' => $rows [3],
					// 'longitude' => $rows [2],
					// 'name' => $rows [1]
					// );
					// }
					// }
					// }
					// echo "</table>";
					// echo "<input type=\"submit\" name=\"send_request\" value=\"send request\" />\n";
					// echo "</form>";
					
					// header ( 'Content-Type: application/json' );
					// echo json_encode ( $places, JSON_FORCE_OBJECT );
					
					exit ();
				}
				?>

	<div id="time"></div>


<div id='test0' ></div>
<!-- 	<div id='test1' ></div> -->
	<div id='test1'></div>
	<div id='test2'></div>
	<div id='map'></div>



</body>

</html>