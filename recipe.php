#!/usr/bin/php
<html>
<body>
<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

$client = new rabbitMQClient("testRabbitMQ.ini","testServer");
if (isset($argv[1]))
{
  $msg = $argv[1];
}
else
{
  $msg = "test message";
}
if(isset($_POST))
{
    $type = "recipe";
    $recipe = $_POST["demo"];
    
}
echo $type;
echo $recipe;
$request = array();
$request['type'] = "recipe";
$request['recipe'] = $recipe;

$response = $client->send_request($request);
//$response = $client->publish($request);

echo "client received response: ".PHP_EOL;
print_r($response);
//include 'recipe.html';
//header('Location:recipe.html');
if($response == "1")
{
	include 'cookbook.html';
	header('Location:cookbook.html');
	print_r($response);
}
echo "\n\n";

echo $argv[0]." END".PHP_EOL;
?>
</body>
</html>

