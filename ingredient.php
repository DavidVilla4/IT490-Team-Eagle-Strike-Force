
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
    $type = "addRecipeInfo";
    $ingredient = $_POST["ingredient"];
    $unit= $_POST["unit"];
    $amount= $_POST["amount"];
    
    
}
echo $type;
echo $recipe;
if($unit !=null)
{
    $request = array();
    $request['type'] = $type;
    $request['ingredient'] = $ingredient;
    $request['amount']=$amount;
    $request['unit']=$unit;
}
else
{
    $request = array();
    $request['type'] = $type;
    $request['ingredient'] = $ingredient;
    $request['amount']=$amount;
}



$response = $client->send_request($request);
//$response = $client->publish($request);

echo "client received response: ".PHP_EOL;

print_r($response);
//include 'recipe.html';
//header('Location:recipe.html');
if($response['returnCode'] == "1")
{
    $logging  = new rabbitMQClient("logging.ini","testServer");
   	$logging ->publish($response);
	include 'cookbook.html';
	header('Location:cookbook.html');
	print_r($response);
}

echo "\n\n";

echo $argv[0]." END".PHP_EOL;
?>
</body>
</html>