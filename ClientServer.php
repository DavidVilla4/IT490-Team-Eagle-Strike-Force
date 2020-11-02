#!/usr/bin/php
<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

$client = new rabbitMQClient("dataPull.ini","testServer");
$conn = mysqli_connect('localhost','admin','admin','newDb');

if (!$conn) {
    die('Could not connect: ' . $mysqli_connect_error($conn));
}

$request = array();

//search by recipe
$request['type'] = $argv[1];
$request['query'] = $argv[2];

$response = $client->send_request($request);
print_r($response);

if(is_array($response)){

    for($i = 0; $i < count($response); $i++){ 
	    $id = $response[$i]['id'];
	    $title = $response[$i]['title'];
	    $sql = "INSERT IGNORE INTO recipes(recipe_id, recipe_title) VALUES('$id','$title')";
	    mysqli_query($sql) or exit(mysqli_error());
    }
    
}

else{
	echo 'An error occurred ';
}

//search by ingredient
/*
if(is_array($response)){

    foreach ($response as $row){
            $id = (int)$row['recipe_id'];
            $title = mysqli_real_escape_string($row['recipe_title']);
            $sql = "INSERT IGNORE INTO recipes(recipe_id, recipe_title) VALUES('$id','$title')";
    	    mysqli_query($sql) or exit(mysqli_error());
	}
}

else{
        echo 'An error occurred ';
}
*/

echo "client received response: ".PHP_EOL;

echo "\n\n";

echo $argv[0]." END".PHP_EOL;

