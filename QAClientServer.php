#!/usr/bin/php
<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');


function requestProcessor($request)
{
	$data = $request['data'];
	system("mkdir tempDir");
	system("cd tempDir");
	system("tar -xzvf $data");
	//make test dir and unpack data
	//run data
	try
	{
		//If data runs without exception then move from test dir to main dir
	}
	catch (Exception $e)
	{
		return;
	}
}

$server = new rabbitMQServer("testRabbitMQ.ini","testServer");
$server->process_requests('requestProcessor');

