#!/usr/bin/php
<?php
$servername = "testserver";
$dbname = "newDb";
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

$client = new RabbitMQClient("testRabbitMQ.ini","testServer");

?>
