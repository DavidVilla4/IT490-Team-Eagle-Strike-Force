#!/usr/bin/php
<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

$client = new rabbitMQClient("dataPull.ini","testServer");
$mysqli = mysqli_connect('localhost','admin','admin','newDb');

if (!$mysqli) {
    die('Could not connect: ' . $mysqli_connect_error($mysqli));
}

$request = array();

//search by recipe
$request['type'] = $tase;
$request['query'] = $q;

$response = $client->send_request($request);
print_r($response);

//search by recipe or ingredient
if(is_array($response)){

    for($i = 0; $i < count($response); $i++){ 
	    $id = $response[$i]['id'];
	    $title = $response[$i]['title'];
	    $sql = "INSERT IGNORE INTO recipes(recipe_id, recipe_title) VALUES('$id','$title')";
	    mysqli_query($mysqli,$sql) or exit(mysqli_error($mysqli));
    }
    
}

else{
	echo 'An error occurred ';
}

echo "client received response: ".PHP_EOL;

echo "\n\n";

echo $argv[0]." END".PHP_EOL;

