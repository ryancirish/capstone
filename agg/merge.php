<!DOCTYPE html>
<html>
<head>
	<title>merge</title>
</head>
<body>
<?php
	$user = 'root';
	$password = 'root';
	$db = 'capstone';
	$host = 'localhost:8889';
	$port = 8889;
	$host2 = 'localhost';

	try {
	    $conn = new PDO("mysql:host=$host;dbname=$db", $user, $password);
	    // set the PDO error mode to exception
	    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	    // sql to create table
	    $sql = "CREATE TABLE step (
	    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	    name VARCHAR(300) NOT NULL, 
	    image VARCHAR(300) NOT NULL,
	    url VARCHAR(300) NOT NULL,
	    review_count int(6) NOT NULL,
	    rating float(2, 1) NOT NULL,
	    address VARCHAR(300) NOT NULL,
	    city VARCHAR(300) NOT NULL,
	    zip VARCHAR(5) NOT NULL,
	    state VARCHAR(2) NOT NULL,
	    lat float(12, 2) NOT NULL,
	    lng float(12, 2) NOT NULL,
	    cuisines VARCHAR(300),
	    price_range VARCHAR(6),
	    menu_url VARCHAR(300),
	    has_online_delivery VARCHAR(3)
	    )";

	    // use exec() because no results are returned
	    $conn->exec($sql);
	    echo "Table locale created successfully" . "<br>";
	    }
	catch(PDOException $e){
		    echo $sql . "<br>" . $e->getMessage() . "<br/>" . "<br/>";
		}
	
		$conn = null;



	$conn2 = mysqli_connect($host, $user, $password, $db);
	// Check connection
	if (!$conn2) {
		die("Connection failed: " . mysqli_connect_error());
	}else{
		echo 'connection success <br>';
	}

	$sql = "SELECT * FROM locales l JOIN (SELECT * FROM zom) z on l.name = z.name";
	$result = $conn2->query($sql);
	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			$name = mysqli_real_escape_string($conn2, $row["name"]);
			$image = mysqli_real_escape_string($conn2, $row["image"]);
			$url = mysqli_real_escape_string($conn2, $row["url"]);
			$name = mysqli_real_escape_string($conn2, $row["name"]);
			$review_count = $row["review_count"];
			$rating = $row["rating"];
			$address = mysqli_real_escape_string($conn2, $row["address"]);
			$city = mysqli_real_escape_string($conn2, $row["city"]);
			$name = mysqli_real_escape_string($conn2, $row["name"]);
			$zip = $row["zip"];
			$state = $row["state"];
			$lat = $row["lat"]; 
			$long = $row["lng"];
			$cuisines = mysqli_real_escape_string($conn2, $row["cuisines"]);
			$price_range = mysqli_real_escape_string($conn2, $row["price_range"]);
			$menu_url = mysqli_real_escape_string($conn2, $row["menu_url"]);
			$has_online_delivery = mysqli_real_escape_string($conn2, $row["has_online_delivery"]);

			$sql = "INSERT INTO step (name, image, url, review_count, rating, address, city, zip, state, lat, lng, cuisines, price_range, menu_url, has_online_delivery)
			VALUES ('$name', '$image', '$url', $review_count, $rating, '$address', '$city', '$zip', '$state', $lat, $long, '$cuisines', '$price_range', '$menu_url', '$has_online_delivery')";

			if ($conn2->query($sql) === TRUE) {
			    echo "New record created successfully (" . $key . ") <br>";
			} else {
			    echo "Error: " . $sql . "<br>" . $conn2->error . "<br/>";
			}			
		}
	}	


	/*
	//$sql = "SELECT * FROM locales l JOIN zom on l.name = zom.name";
	$list = []; 
	//$sql = "SELECT * FROM locales l JOIN (SELECT * FROM zom) z on l.name = z.name";
	//$sql = "SELECT * FROM recipies r JOIN (SELECT * FROM zom) z on r.type = z.cuisines";

	$result = $conn2->query($sql);
	if ($result->num_rows > 0) {
	    // output data of each row
	    while($row = $result->fetch_assoc()) {	
	    	print_r($row);

	    
	       foreach ($row as $key => $value) {
	       	echo $key . ": " . $value . "<br/>";
	       	
	       	if ($key == 'cuisines') {
	       		$build = "SELECT * FROM recipies WHERE cuisines = " . $value;
	       		$list[$value] = $build;
	       	}	       	
	       }
	       echo "<br/>";
	     
	    }
	    $result->close();
	} else {
	    echo "0 results";
	     printf("Error: %s\n", $mysqli->error);
	}
	*/





?>
</body>
</html>