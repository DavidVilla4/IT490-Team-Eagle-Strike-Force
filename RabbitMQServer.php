#!/usr/bin/php
<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');
require_once('unirest-php/src/Unirest.php');

function requestProcessor($request)
{
  echo "received request".PHP_EOL;
  var_dump($request);
  if(!isset($request['type']))
  {
    return "ERROR: unsupported message type";
  }

  switch ($request['type'])
  {
  	case "searchRecipe":

		$response = Unirest\Request::get('https://api.spoonacular.com/recipes/complexSearch?number=25&ranking=1&apiKey=fe22f905e5ec4e7b8947c5351698686c&query='.$request['query']);
	
		print_r($response -> body -> results);
		return $response -> body -> results;


	case "recipeByIngredient":

		 $response = Unirest\Request::get('https://api.spoonacular.com/recipes/findByIngredients?number=25&ranking=1&apiKey=fe22f905e5ec4e7b8947c5351698686c&ingredients='.$response['query']);


                print_r($response);
                return $response;

  }
  return array("returnCode" => '0', 'message'=>"Server received request and processed");
}

$server = new rabbitMQServer("dataPull.ini","testServer");

echo "RabbitMQServer BEGIN".PHP_EOL;
$server->process_requests('requestProcessor');
echo "RabbitMQServer END".PHP_EOL;
exit();
?>
