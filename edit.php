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
    $type = 'recipeEdit';
    $edit = $_POST["edit"];
    
    
}
    
    
echo $type;
echo $edit;
$request = array();
$request['type'] = $type;
$request['ingredient'] = $ingredient;
$response = $client->send_request($request);
    
echo $edit;
$myfile = fopen("recipe.txt", "w") or die("unable to open file");
fwrite($myfile,$edit);
fclose($myfile);
include 'cookbook.html';
header('Location:cookbook.html');
    
?>
</body>
</html>
