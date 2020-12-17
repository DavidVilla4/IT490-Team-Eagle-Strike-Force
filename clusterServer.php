#!/usr/bin/php
<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

function doDbconnect($logs) {
	$mydb = new mysqli('25.2.225.100','admin','admin','newDb');
	if (!$mydb)
	{
		$msg = "Failed to connect to database: ". mysqli_connect_error() . PHP_EOL;
		array_push($logs,$msg);
		die($logs);
	}
	else
	{
		return $mydb;
	}
}

function doDevToQA($data,$logs)
{
	$newClient = new rabbitMQClient("QARecieve.ini","testServer");
	$newRequest = array();
	$newRequest['data'] = $data;
	$response = $newClient->send_request($newRequest);
	if ($response['code'] == 1)
	{
		$logs['returnCode'] = 1;
	}
	return $logs
}

function doDevToQA($data,$logs)
{
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);   
    try {    
	$mydb = doDbconnect($logs);
	$msg = "Connected to Database, Checking database".PHP_EOL;
	array_push($logs,$msg);
	$query = "UPDATE tablename SET currentBuild = $data";
	$result = $mydb->query($query);
	if ($mydb->errno != 0)
	{
		$msg = "Failed to execute query: ".PHP_EOL;
		$msg1 = __FILE__.':'.__LINE__.":error: ".$mydb->error.PHP_EOL;
		array_push($logs,$msg,$msg1);
		return $logs;
	}
	if ($result)
	{
		$newClient = new rabbitMQClient("prodRecieve.ini","testServer");
		$newClient->publish($data);
		$msg = "Data Sent to Production".PHP_EOL;
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
    case "DevToQA":
      $msg = "Attempting To Send Data".PHP_EOL;
      array_push($logArray,$msg);
      return doSendToQA($request['data'],$logArray);
    case "QAToProd":
      $msg = "Attempting To Send Data".PHP_EOL;
      array_push($logArray,$msg);
      return doSendToProd($request['data'],$logArray);
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
