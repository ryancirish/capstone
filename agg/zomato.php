
<?php
//glassboro: city id -> 10721

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://developers.zomato.com/api/v2.1/search?entity_id=10721&entity_type=city&count=50");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
$headers = array(
  "Accept: application/json",
  "User-Key: 3fc90927eb9decac337a0dd903638596"
  );
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
$result = curl_exec($ch);
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}
curl_close ($ch);

//echo $result;

$user = 'root';
$password = 'root';
$db = 'capstone';
$host = 'localhost:8889';
$port = 8889;

$r = json_decode($result, true);
$r = $r["restaurants"];

$conn2 = mysqli_connect($host, $user, $password, $db);
// Check connection
if (!$conn2) {
	die("Connection failed: " . mysqli_connect_error());
}else{
	echo 'connection success <br>';
}

foreach ($r as $key => $value) {
	foreach ($value as $key => $val) {
		$name = $val['name'];
		$name = mysqli_real_escape_string($conn2, $name);

		$cuisines = $val['cuisines'];
		$searchString = ',';

		 if( strpos($cuisines, $searchString) !== false ) {
			$myArray = explode(', ', $cuisines);			
			$cuisines = $myArray[0];
			if ($cuisines === 'Fast Food') {
				$cuisines = 'Fast';
			}elseif ($cuisines === 'Healthy Food') {
				$cuisines = 'Healthy';
			}
			$cuisines = mysqli_real_escape_string($conn2, $cuisines);     
		 }else{
			if ($cuisines === 'Fast Food') {
				$cuisines = 'Fast';
			}elseif ($cuisines === 'Healthy Food') {
				$cuisines = 'Healthy';
			}		 	
		 	$cuisines = mysqli_real_escape_string($conn2, $cuisines);     
		 }

		$out = '';
		for ($i=0; $i < $val['price_range']; $i++) { 
			$out .= '$';
		}
		$price_range = mysqli_real_escape_string($conn2, $out);

		$menu_url = $val['menu_url'];
		$menu_url = mysqli_real_escape_string($conn2, $menu_url);

		$has_online_delivery = $val['has_online_delivery'];
		if ($has_online_delivery == 0) {
			$has_online_delivery = 'no';
		}else{
			$has_online_delivery = 'yes';			
		}
		$has_online_delivery = mysqli_real_escape_string($conn2, $has_online_delivery);
		
		
		$sql = "INSERT INTO zom (name, cuisines, price_range, menu_url, has_online_delivery)
		VALUES ('$name', '$cuisines', '$price_range', '$menu_url', '$has_online_delivery')";

		if ($conn2->query($sql) === TRUE) {
		    echo "New record created successfully (" . $key . ") <br>";
		} else {
		    echo "Error: " . $sql . "<br>" . $conn2->error;
		}
		
		

	}
}



?>
