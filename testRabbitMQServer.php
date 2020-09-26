#!/usr/bin/php
<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

function doLogin($email,$password)
{
    // lookup username in databas
    // check password
	$mydb = new mysqli('127.0.0.1','testuser','12345','testdb');
	if ($mydb->errno != 0)
	{
		echo "Failed to connect to database: ". $mydb->error . PHP_EOL;
		exit(0);
	}
	echo "Connected to Database".PHP_EOL;
	echo "Checking database for user";
	$query = "SELECT * FROM `tablename` WHERE `EMAIL` = '$email' AND `Password` = '$password'";
	$result = $mydb->query($query);
	if ($mydb->errno != 0)
	{
		echo "Failed to execute query: ".PHP_EOL;
		echo __FILE__.':'.__LINE__.":error: ".$mydb->error.PHP_EOL;
		exit(0);
	}
	if ($result) {
		return true;
	}
	return false;
    //return false if not valid
}

function requestProcessor($request)
{
  echo "received request".PHP_EOL;
  var_dump($request);
  if(!isset($request['type']))
  {
    return "ERROR: unsupported message type";
  }
  switch ($request['type'])
  {
    case "login":
      return doLogin($request['username'],$request['password']);
    case "validate_session":
      return doValidate($request['sessionId']);
  }
  return array("returnCode" => '0', 'message'=>"Server received request and processed");
}

$server = new rabbitMQServer("testRabbitMQ.ini","testServer");

echo "testRabbitMQServer BEGIN".PHP_EOL;
$server->process_requests('requestProcessor');
echo "testRabbitMQServer END".PHP_EOL;
exit();
?>

