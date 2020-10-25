#!/usr/bin/php
<html>
<body>
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
    $type = "Create";
    $email = $_POST["email"];
    $password = $_POST["password"];
}
echo $type;
echo $email;
echo $password;
$request = array();
$request['type'] = "Create";
$request['email'] = $email;
$request['password'] = $password;

$response = $client->send_request($request);
//$response = $client->publish($request);

echo "client received response: ".PHP_EOL;
print_r($response);
echo "\n\n";

echo $argv[0]." END".PHP_EOL;
?>
</body>
</html>
