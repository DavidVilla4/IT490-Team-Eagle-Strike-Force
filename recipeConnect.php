#!/usr/bin/php
<?php
$servername = "testserver";
$dbname = "dbname";
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

$conn = new RabbitMQClient("testRabbitMQ.ini","testServer");

?>
