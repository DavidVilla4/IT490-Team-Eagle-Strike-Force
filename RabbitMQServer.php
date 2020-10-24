#!/usr/bin/php
<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

function apiCall($type, $url, $data=false)
{
  $curl = curl_init();
  switch ($type)
  {
  	case "POST":
		curl_setopt($curl, CURLOPT_POST, 1);

		if ($data)
		{
			curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
		}
		break;
  	default:
		if ($data)
			$url = sprintf("%s?%s", $url, http_build_query($data));
  }
  
  curl_setopt($curl, CURLOPT_URL, $url);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
  $response = curl_exec($curl);
  curl_close($curl);

  return $response;
}
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
		$get_data = apiCall('GET', "https://api.spoonacular.com/recipes/" + id + "/information?apiKey=fe22f905e5ec4e7b8947c5351698686c&number=25&ranking=1");
		$response = json.decode($get_data, true);

	       	break;
  
  	case "recipeByIngredient":
		$get_data = callApi('GET',"https://api.spoonacular.com/recipes/findByIngredients?apiKey=fe22f905e5ec4e7b8947c5351698686c&number=25&ranking=1&ingredients=",false);
		$response = json.decode($get_data, true);

                break;
  }
  return array("returnCode" => '0', 'message'=>"Server received request and processed");
}

$server = new rabbitMQServer("RabbitMQ.ini","Server");

echo "RabbitMQServer BEGIN".PHP_EOL;
$server->process_requests('requestProcessor');
echo "RabbitMQServer END".PHP_EOL;
exit();
?>
