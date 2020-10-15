#!/usr/bin/php
<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

function requestProcessor($request)
{
	var_dump($request);
	return $request['logs'];
}

$server = new rabbitMQServer("logging.ini","testServer");
$server->process_requests('requestProcessor');
exit();

?>

