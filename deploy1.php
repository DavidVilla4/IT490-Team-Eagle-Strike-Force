#!/usr/bin/php
<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');


$output = shell_exec('tar cvzf deploy1.tgz index.html register.html RegisterRabbitMQ.php LoginRabbitMQ.php signin.css');
echo "<pre>$output</pre>";


$client = new rabbitMQClient("devTransfer.ini","testServer");
if (isset($argv[1]))
{
  $msg = $argv[1];
}
else
{
  $msg = "1";
}

$myfile= "deploy1.tgz";
$type="DevToQA";
$request = array();
$request['type'] = $type;
$request['data'] = $myfile;
$request['number']=$msg;

$response = $client->send_request($request);
//$response = $client->publish($request);
echo "hello world";
echo "client received response: ".PHP_EOL;
echo "hello world 2".PHP_EOL;
print_r($response);

if ($response['returnCode']=="1")
{
	echo "successfully sent data to the Mount Doom";
        $logging  = new rabbitMQClient("logging.ini","testServer");
        $logging ->publish($response);
}
else
{
        echo "failed to send file".PHP_EOL;
}



$response = $client->publish($request);


echo "\n\n";

echo $argv[0]." END".PHP_EOL;
?>
