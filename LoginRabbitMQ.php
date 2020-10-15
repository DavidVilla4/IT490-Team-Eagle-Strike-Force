#!/usr/bin/php
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
    $type = "Login";
    $email = $_POST["email"];
    $password = $_POST["password"];
}
$request = array();
$request['type'] = $type;
$request['email'] = $email;
$request['password'] = $password;
$request['message'] = $msg;

$response = $client->send_request($request);
//$response = $client->publish($request);

echo "client received response: ".PHP_EOL;
print_r($response);

if ($response == "1")
{
	// $logging  = new rabbitMQClient("logging.ini","testServer");
   	// $logging ->publish($response);
	include 'cookbook.hmtl';
	header('Location: cookbook.html');
	echo "going to new website".PHP_EOL;
}
$log_rep= "going to new website";
$response = $client->publish($request);


echo "\n\n";

echo $argv[0]." END".PHP_EOL;


