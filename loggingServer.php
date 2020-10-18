#!/usr/bin/php
<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

function requestProcessor($request)
{
	echo date("l F jS Y h:i:s A").PHP_EOL;
	return var_dump($request);
}

$server = new rabbitMQServer("logging.ini","testServer");
$server->process_requests('requestProcessor');
exit();
?>
