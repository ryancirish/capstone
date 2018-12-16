<html>
 <head>
  <title>PHP Test</title>
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
	    $sql = "CREATE TABLE locales (
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
	    lng float(12, 2) NOT NULL
	    )";

	    // use exec() because no results are returned
	    $conn->exec($sql);
	    echo "Table locale created successfully" . "<br>";
	    }
	catch(PDOException $e){
		    echo $sql . "<br>" . $e->getMessage() . "<br/>" . "<br/>";
		}
	
		$conn = null;
	

	$API_KEY = "ytyp2mySAMwXdur4C01FSym36MKK-CXf30zHkJXqr6McV4vG-a-OP6KeNbgH_55LJLmzMPD-gAm9m53dTwm3bZ6HUVhIkbNy5xue-4QDGhlD1vnuuUot8ooTQga3W3Yx";
	// Complain if credentials haven't been filled out.

	
	//assert($API_KEY, 'Please supply your API key.');
	//echo "hello";
	// API constants, you shouldn't have to change these.
	$API_HOST = "https://api.yelp.com";
	$SEARCH_PATH = "/v3/businesses/search";
	$BUSINESS_PATH = "/v3/businesses/";  // Business ID will come after slash.
	//echo "hello";
	// Defaults for our simple example.
	$DEFAULT_TERM = "dinner";
	$DEFAULT_LOCATION = "San Francisco, CA";
	$SEARCH_LIMIT = 75;
	//echo "hello";
	/** 
 * Makes a request to the Yelp API and returns the response
 * 
 * @param    $host    The domain host of the API 
 * @param    $path    The path of the API after the domain.
 * @param    $url_params    Array of query-string parameters.
 * @return   The JSON response from the request      
 */
function request($host, $path, $url_params = array()) {
    // Send Yelp API Call
    try {
        $curl = curl_init();
        if (FALSE === $curl)
            throw new Exception('Failed to initialize');
        $url = $host . $path . "?" . http_build_query($url_params);
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,  // Capture response.
            CURLOPT_ENCODING => "",  // Accept gzip/deflate/whatever.
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "authorization: Bearer " . $GLOBALS['API_KEY'],
                "cache-control: no-cache",
            ),
        ));
        $response = curl_exec($curl);
        if (FALSE === $response)
            throw new Exception(curl_error($curl), curl_errno($curl));
        $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        if (200 != $http_status)
            throw new Exception($response, $http_status);
        curl_close($curl);
    } catch(Exception $e) {
        trigger_error(sprintf(
            'Curl failed with error #%d: %s',
            $e->getCode(), $e->getMessage()),
            E_USER_ERROR);
    }
    return $response;
}
	
//var_dump(function_exists('mysqli_real_escape_string'));
	
	function search($term, $location) {
	    $url_params = array();
	    
	    $url_params['term'] = $term;
	    $url_params['location'] = $location;
	    $url_params['limit'] = $GLOBALS['SEARCH_LIMIT'];
	    #echo 'Search: ' . $term . ', ' . $location;
	    #echo '<p>' . request($GLOBALS['API_HOST'], $GLOBALS['SEARCH_PATH'], $url_params) . '<p>';
	    return request($GLOBALS['API_HOST'], $GLOBALS['SEARCH_PATH'], $url_params);
	}
	//name, image_url, url, location, review_count, rating, coordinates
	$return = search($DEFAULT_TERM, 'Glassboro, NJ');
	$r = json_decode($return, TRUE);
	//var_dump($r);
	$r = $r["businesses"];
	

	$conn2 = mysqli_connect($host, $user, $password, $db);
	// Check connection
	if (!$conn2) {
		die("Connection failed: " . mysqli_connect_error());
	}else{
		echo 'connection success <br>';
	}	
	foreach ($r as $key => $value) {
		$name = $value["name"];
		$name = mysqli_real_escape_string($conn2, $name);
		
		$image = $value["image_url"];
		$image = mysqli_real_escape_string($conn2, $image);

		$url = $value["url"];
		$url = mysqli_real_escape_string($conn2, $url);

		$review_count = $value["review_count"];
		$rating = $value["rating"];

		$address = $value["location"]["address1"];
		$address = mysqli_real_escape_string($conn2, $address);

		$city = $value["location"]["city"];
		$city = mysqli_real_escape_string($conn2, $city);

		$zip = $value["location"]["zip_code"];
		$state = $value["location"]["state"];
		$lat = $value["coordinates"]["latitude"]; 
		$long = $value["coordinates"]["longitude"];

		$sql = "INSERT INTO locales (name, image, url, review_count, rating, address, city, zip, state, lat, lng)
		VALUES ('$name', '$image', '$url', $review_count, $rating, '$address', '$city', '$zip', '$state', $lat, $long)";

		if ($conn2->query($sql) === TRUE) {
		    echo "New record created successfully (" . $key . ") <br>";
		} else {
		    echo "Error: " . $sql . "<br>" . $conn2->error;
		}

	}

	

	


 ?> 
 </body>
</html>