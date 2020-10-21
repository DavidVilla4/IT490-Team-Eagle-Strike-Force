#!/usr/bin/php
<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

function doVoting($recipeName,$review,$logs)
{
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    try {
	$mydb = new mysqli('127.0.0.1','admin','admin','newDb');
	if ($mydb->errno != 0)
	{
		$msg = "Failed to connect to database: ". $mydb->error . PHP_EOL;
		array_push($logs,$msg);
		exit($logs);
	}
	$msg = "Connected to database".PHP_EOL;
	array_push($logs,$msg);
	if ($review == "up")
       	{
		$query = "UPDATE field_name SET vote = vote + 1 WHERE recipeName = $recipeName";
		$result = $mydb->query($query);
		if ($mydb->errno != 0)
		{
			$msg = "Failed to execute query: ".PHP_EOL;
			$msg1 = __FILE__.':'.__LINE__.":error: ".$mydb->error.PHP_EOL;
			array_push($logs,$msg,$msg1);
			exit($logs);
		}
		if ($result)
		{
			$msg = "Recipe ". $recipeName . "was upvoted".PHP_EOL;
			array_push($logs,$msg);
		}
	}
	else if ($review == "no")
       	{
		$query = "UPDATE field_name SET vote = vote - 1 WHERE recipeName = $recipeName";
		$result = $mydb->query($query);
		if ($mydb->errno != 0)
		{
			$msg = "Failed to execute query: ".PHP_EOL;
			$msg1 = __FILE__.':'.__LINE__.":error: ".$mydb->error.PHP_EOL;
			array_push($logs,$msg,$msg1);
			exit($logs);
		}
		if ($result)
		{
			$msg = "Recipe ". $recipeName . "was downvoted".PHP_EOL;
			array_push($logs,$msg);
		}
	}
	else
       	{
		$msg = "Incorrect value for review".PHP_EOL;
		array_push($logs,$msg);
		exit($logs);
	}
	$query = "SELECT field_name FROM Recipes WHERE recipeName = '$recipeName'";
	$result = $mydb->query($query);
	if ($mydb->errno != 0)
	{
		$msg = "Failed to execute query: ".PHP_EOL;
		$msg1 = __FILE__.':'.__LINE__.":error: ".$mydb->error.PHP_EOL;
		array_push($logs,$msg,$msg1);
		exit($logs);
	}
	if ($result)
	{
		$row = mysqli_fetch_array($result);
		$logs['voteCount'] = $row['field_name'];
	}
	return $logs;
    }
    catch(mysqli_sql_exception $e) {
	    array_push($logs,$e->getMessage());
	    return $logs;
    }
}

function doAddRec($recName,$logs)
{	
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);   
    try {    
	$mydb = new mysqli('127.0.0.1','admin','admin','newDb');
	if ($mydb->errno != 0)
	{
		$msg = "Failed to connect to database: ". $mydb->error . PHP_EOL;
		array_push($logs,$msg);
		exit($logs);
	}
	$msg = "Connected to Database, Checking database for user".PHP_EOL;
	array_push($logs,$msg);
	$query = "INSERT INTO Recipes (name) VALUES ('$recipeName')";
	$result = $mydb->query($query);
	if ($mydb->errno != 0)
	{
		$msg = "Failed to execute query: ".PHP_EOL;
		$msg1 = __FILE__.':'.__LINE__.":error: ".$mydb->error.PHP_EOL;
		array_push($logs,$msg,$msg1);
		exit($logs);
	}
	if ($result)
	{
		$msg = "Recipe Added".PHP_EOL;
		array_push($logs,$msg);
		exit($logs);
	}
    }
    catch(mysqli_sql_exception $e) {
	    array_push($logs,$e->getMessage());
	    return $logs;
    }
}

function doLogin($email,$password,$logs)
{
    // lookup username in databas
	// check password
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);   
    try {    
	$mydb = new mysqli('127.0.0.1','admin','admin','newDb');
	if ($mydb->errno != 0)
	{
		$msg = "Failed to connect to database: ". $mydb->error . PHP_EOL;
		array_push($logs,$msg);
		exit($logs);
	}
	$msg = "Connected to Database, Checking database for user".PHP_EOL;
	array_push($logs,$msg);
	$query = "SELECT * FROM USERS WHERE email = '$email' AND password = '$password'";
	$result = $mydb->query($query);
	if ($mydb->errno != 0)
	{
		$msg = "Failed to execute query: ".PHP_EOL;
		$msg1 = __FILE__.':'.__LINE__.":error: ".$mydb->error.PHP_EOL;
		array_push($logs,$msg,$msg1);
		exit($logs);
	}
	if ($result)
	{
		$row = $result->fetch_assoc();
		if ($row["password"] == $password)
		{
			$msg = "Logging In User".PHP_EOL;
			array_push($logs,$msg);
			$logs['returnCode'] = '1';
			return $logs;
		}
		$msg = "Password does not match".PHP_EOL;
		array_push($logs,$msg);	
	}
	return $logs;
    }
    catch(mysqli_sql_exception $e) {
	    array_push($logs,$e->getMessage());
	    return $logs;
    }
    //return false if not valid
}

function doCreate($email,$password,$logs)
{
	mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    try {
	$mydb = new mysqli('127.0.0.1','admin','admin','newDb');
	if ($mydb->errno != 0)
	{
		$msg = "Failed to connect to database: ". $mydb->error . PHP_EOL;
		array_push($logs,$msg);
		exit($logs);
	}
	$msg = "Connected to Database\nCreating User Account".PHP_EOL;
	array_push($logs,$msg);
	$query = "INSERT INTO USERS (email, password) VALUES ('$email','$password')";
	$result = $mydb->query($query);
	if ($mydb->errno != 0)
	{
		$msg = "Failed to execute query: ".PHP_EOL;
		$msg1 = __FILE__.':'.__LINE__.":error: ".$mydb->error.PHP_EOL;
		array_push($logs,$msg,$msg1);
		exit($logs);
	}
	if ($result) {
		$msg = "Account Created".PHP_EOL;
		array_push($logs,$msg);
		return $logs;
	}
	return $logs;
    }
    catch (mysqli_sql_exception $e) {
	    array_push($logs,$e->getMessage());
	    return $logs;
    }
}

function requestProcessor($request)
{
  $logArray = array("returnCode" => '0');
  echo "Received request".PHP_EOL;
  var_dump($request);
  if(!isset($request['type']))
  {
    $msg = "ERROR: unsupported message type";
    array_push($logArray,$msg);
  }
  switch ($request['type'])
  {
    case "Login":
      $msg = "Attempting Login".PHP_EOL;
      array_push($logArray,$msg);
      return doLogin($request['email'],$request['password'],$logArray);
    case "Create":
      $msg =  "Attempting Account Creation".PHP_EOL;
      array_push($logArray,$msg);
      return doCreate($request['email'],$request['password'],$logArray);
    case "Recipe":
      $msg = "Attempting to Add Recipe".PHP_EOL;
      array_push($logArray,$msg);
      return doAddRec($request['recipe'],$logArray);
    case "Voting":
      $msg = "Attempting to Adjust Vote".PHP_EOL;
      array_push($logArray,$msg);
      return doVoting($request['recipe'],$request['vote'],$logArray);
    case "validate_session":
      return doValidate($request['sessionId']);
  }
  $msg = "Server received request and processed";
  array_push($logArray,$msg);
  return $logArray;
}

$server = new rabbitMQServer("testRabbitMQ.ini","testServer");
echo "testRabbitMQServer BEGIN".PHP_EOL;
$server->process_requests('requestProcessor');
echo "testRabbitMQServer END".PHP_EOL;
exit();
?>

