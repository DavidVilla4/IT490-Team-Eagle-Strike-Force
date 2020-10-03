#!/usr/bin/php
<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

function doLogin($email,$password)
{
    // lookup username in databas
	// check password
	$mydb = new mysqli('127.0.0.1','tesk','tesk2020','newDb');
	if ($mydb->errno != 0)
	{
		echo "Failed to connect to database: ". $mydb->error . PHP_EOL;
		exit(0);
	}
	echo "Connected to Database".PHP_EOL;
	echo "Checking database for user".PHP_EOL;
	$query = "SELECT * FROM `USERS` WHERE `email` = '$email' AND `password` = '$password'";
	$result = $mydb->query($query);
	if ($mydb->errno != 0)
	{
		echo "Failed to execute query: ".PHP_EOL;
		echo __FILE__.':'.__LINE__.":error: ".$mydb->error.PHP_EOL;
		exit(0);
	}
	if ($result)
	{
		$row = $result->fetch_assoc();
		if ($row["password"] == $password)
		{
			echo "Logging In User".PHP_EOL;
			return true;
		}
		echo "Password does not match".PHP_EOL;	
	}
	return false;
	return true;
    //return false if not valid
}

function doCreate($email,$password)
{
	$mydb = new mysqli('127.0.0.1','tesk','tesk2020','newDb');
	if ($mydb->errno != 0)
	{
		echo "Failed to connect to database: ". $mydb->error . PHP_EOL;
		exit(0);
	}
	echo "Connected to Database".PHP_EOL;
	echo "Creating User Account".PHP_EOL;
	$query = "INSERT INTO `USERS` (`email`, `password`) VALUES ('$email','$password')";
	$result = $mydb->query($query);
	if ($mydb->errno != 0)
	{
		echo "Failed to execute query: ".PHP_EOL;
		echo __FILE__.':'.__LINE__.":error: ".$mydb->error.PHP_EOL;
		exit(0);
	}
	if ($result) {
		echo "Account Created".PHP_EOL;
		return true;
	}
	return false;
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
    case "Login":
      echo "Attempting Login".PHP_EOL;
      $var = doLogin($request['email'],$request['password']);
      if ($var) {
	      return 1;
      }
      return 0;
    case "Create":
      echo "Attempting Account Creation".PHP_EOL;
      return doCreate($request['email'],$request['password']);
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

