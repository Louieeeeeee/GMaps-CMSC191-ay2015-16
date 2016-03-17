<!-- 
	Exercise 5: Google Maps
	Collaborated with Jhubielyn Garachico
	CMSC 191 UV
	2nd Semester AY 2015-2016
-->

<html>
	<head>
		<script src="http://maps.googleapis.com/maps/api/js?key=YOUR_KEY_HERE"></script>
		<script>
			var myUPLB=new google.maps.LatLng(14.167525, 121.243368);

			function initialize() {
				var mapProp = {
					center:myUPLB,
					zoom:30,
					mapTypeId:google.maps.MapTypeId.ROADMAP
				};

				var map=new google.maps.Map(document.getElementById("googleMap"),mapProp);
				var bounds = new google.maps.LatLngBounds();

				<?php
					function utf8ize($d) {
						if (is_array($d)) {
							foreach ($d as $k => $v) {
								$d[$k] = utf8ize($v);
							}
						} else if (is_string ($d)) {
							return utf8_encode($d);
						}
						return $d;
					}

					$servername = "localhost";
					$username = "root";
					$password = "";
					$dbname = "googlemaps";

					// Create connection
					$conn = new mysqli($servername, $username, $password, $dbname);

					// Check connection
					if($conn->connect_error) { die("Connection failed: " . $conn->connect_error); } 

					$markers = $conn->query("SELECT * FROM markers");
					$rows = array();
					while($r = mysqli_fetch_assoc($markers)){
						$rows[] = $r;
					}
				?>

				var loc = <?php echo json_encode(utf8ize($rows)); ?>;

				var markers = new Array();
				var infoWindow = new google.maps.InfoWindow(), marker, i;

				var flightPlanCoordinates = new Array();
				var tempFlightPlanCoordinates = new Array();
				var SMCalamba;

				for(i = 0; i < loc.length; i++) {
					var m = new Array();
					m.push(loc[i].name);
					m.push(loc[i].address);
					m.push(loc[i].lat);
					m.push(loc[i].lng);
					m.push(loc[i].type);
					markers.push(m);

					if(loc[i].type == "Mall"){
						var mall = new google.maps.LatLng(loc[i].lat, loc[i].lng)
						tempFlightPlanCoordinates.push(mall);
					}

					if(loc[i].name == "SM City Calamba"){
						var center = new google.maps.LatLng(loc[i].lat, loc[i].lng);
						SMCalamba = new google.maps.Circle({
							strokeColor: '#0000FF',
							strokeWeight: 1,
							strokeOpacity: 0.75,
							fillColor: '#0000FF',
							fillOpacity: 0.5,
							map: map,
							center: center,
							radius: 250
						});
						SMCalamba.setMap(map);
					}
				}

				for (i = 0; i < tempFlightPlanCoordinates.length-1; i ++) {
					for (var j = i+1; j < tempFlightPlanCoordinates.length; j ++) {
						flightPlanCoordinates.push(tempFlightPlanCoordinates[i]);
						flightPlanCoordinates.push(tempFlightPlanCoordinates[j]);
					}
				}

				var flightPath = new google.maps.Polyline({
					path: flightPlanCoordinates,
					strokeColor: "#FF0000",
					strokeOpacity: 1.0,
					strokeWeight: 2
				});

				flightPath.setMap(map);

				for( i = 0; i < markers.length; i++ ) {
					var position = new google.maps.LatLng(markers[i][2], markers[i][3]);
					bounds.extend(position);

					switch(loc[i].type){
						case "Restaurant":
							var pinColor = "00FFFF";
							break;
						case "Auditorium":
							var pinColor = "00FF80";
							break;
						case "Mall":
							var pinColor = "00FF00";
							break;
						case "Inn":
							var pinColor = "0080FF";
							break;
						case "Bank":
							var pinColor = "7F00FF";
							break;
						case "Municipal Hall":
							var pinColor = "FFFF00";
							break;
						case "Resort":
							var pinColor = "FF0000";
							break;
						case "Amusement Park":
							var pinColor = "FF007F";
							break;
					}

					var pinImage = new google.maps.MarkerImage("http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=%E2%80%A2|" + pinColor,
						new google.maps.Size(21, 34),
						new google.maps.Point(0,0),
						new google.maps.Point(10, 34));

					marker = new google.maps.Marker({
						position: position,
						map: map,
						icon: pinImage
					});
				}

				var marker=new google.maps.Marker({
					position:myUPLB,
				});

				marker.setMap(map);
			}

			google.maps.event.addDomListener(window, 'load', initialize);

		</script>
	</head>

	<body>
		<div id="googleMap" style="width:1500px;height:1500px;"></div>
	</body>
</html>