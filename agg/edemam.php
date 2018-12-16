<?php

//sql connection
$dsn = 'mysql:host=localhost:8889;dbname=capstone';
$username = "root";
$password = "root";

$db = new PDO($dsn, $username, $password);

// create curl resource
$ch = curl_init();

// set url
curl_setopt($ch, CURLOPT_URL, "https://api.edamam.com/search?q=Healthy&app_id=84148da5&app_key=c79101bfa5654447d8a44af6a1770bd4&from=0&to=10");

//return the transfer as a string
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

// $output contains the output string
$output = curl_exec($ch);

// output json
echo $output;

// close curl resource to free up system resources
curl_close($ch);

//decode json output
$recipes = json_decode($output, true);

//turning json output into arrays and into variables
/*$label = $recipes["hits"][0]["recipe"]["label"];

$uri = $recipes["hits"][0]["recipe"]["uri"];

$health_label = json_encode($recipes["hits"][0]["recipe"]["healthLabels"]);

$ingredient_lines = json_encode($recipes["hits"][0]["recipe"]["ingredientLines"]);

$calories = $recipes["hits"][0]["recipe"]["calories"];

$time = $recipes["hits"][0]["recipe"]["totalTime"];
*/

//print out above code to make sure it came out right
/*print_r($label);
echo "<br>";
print_r($uri);
echo "<br>";
print_r($health_label);
echo "<br>";
print_r($ingredient_lines);
echo "<br>";
print_r($calories);
echo "<br>";
print_r($time);
*/

//loop for database

$k = 0;
do {


  $label = $recipes["hits"][$k]["recipe"]["label"];

  $uri = $recipes["hits"][$k]["recipe"]["uri"];

  $health_label = json_encode($recipes["hits"][$k]["recipe"]["healthLabels"]);

  $ingredient_lines = json_encode($recipes["hits"][$k]["recipe"]["ingredientLines"]);

  $calories = $recipes["hits"][$k]["recipe"]["calories"];

  $time = $recipes["hits"][$k]["recipe"]["totalTime"];

  $type = 'Healthy';

  $query = "INSERT INTO recipies (label, uri, healthLabel, ingredientLines, calories, totalTime, type)
  VALUES ('$label', '$uri', '$health_label', '$ingredient_lines', $calories, $time, '$type')";

  $insert_count = $db->exec($query);

  $k++;
} while ($k < sizeof($recipes));

?>
