#!/usr/bin/php
<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

function doDbconnect($logs) {
	$mydb = new mysqli('127.0.0.1','admin','admin','newDb');
	if ($mydb->errno != 0)
	{
		$msg = "Failed to connect to database: ". $mydb->error . PHP_EOL;
		array_push($logs,$msg);
		return $logs;
	}
	else
	{
		return $mydb;
	}
}

function doVoting($recipeName,$review,$logs)
{
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    try {
	$mydb = doDbconnect($logs);
	$msg = "Connected to database".PHP_EOL;
	array_push($logs,$msg);
	if ($review == "up")
       	{
		$query = "UPDATE recipes SET score = score + 1 WHERE recipe_title = '$recipeName'";
		$result = $mydb->query($query);
		if ($mydb->errno != 0)
		{
			$msg = "Failed to execute query: ".PHP_EOL;
			$msg1 = __FILE__.':'.__LINE__.":error: ".$mydb->error.PHP_EOL;
			array_push($logs,$msg,$msg1);
			return $logs;
		}
		if ($result)
		{
			$msg = "Recipe ". $recipeName . "was upvoted".PHP_EOL;
			$logs['returnCode'] = '1';
			array_push($logs,$msg);
		}
	}
	else if ($review == "down")
       	{
		$query = "UPDATE recipes SET score = score - 1 WHERE recipe_title = '$recipeName'";
		$result = $mydb->query($query);
		if ($mydb->errno != 0)
		{
			$msg = "Failed to execute query: ".PHP_EOL;
			$msg1 = __FILE__.':'.__LINE__.":error: ".$mydb->error.PHP_EOL;
			array_push($logs,$msg,$msg1);
			return $logs;
		}
		if ($result)
		{
			$msg = "Recipe ". $recipeName . "was downvoted".PHP_EOL;
			$logs['returnCode'] = '1';
			array_push($logs,$msg);
		}
	}
	else
       	{
		$msg = "Incorrect value for review".PHP_EOL;
		array_push($logs,$msg);	
		return $logs;
	}
	$query = "SELECT score FROM recipes WHERE recipe_title = '$recipeName'";
	$result = $mydb->query($query);
	if ($mydb->errno != 0)
	{
		$msg = "Failed to execute query: ".PHP_EOL;
		$msg1 = __FILE__.':'.__LINE__.":error: ".$mydb->error.PHP_EOL;
		array_push($logs,$msg,$msg1);
		return $logs;
	}
	if ($result)
	{
		$row = mysqli_fetch_array($result);
		$logs['voteCount'] = $row['score'];
	}
	return $logs;
    }
    catch(mysqli_sql_exception $e) {
	    array_push($logs,$e->getMessage());
	    return $logs;
    }
}

function doAddRec($recipeName,$logs)
{	
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);   
    try {    
	$mydb = doDbconnect($logs);
	$msg = "Connected to Database, Attempting to Add Recipe".PHP_EOL;
	array_push($logs,$msg);
	$query = "INSERT INTO recipes (recipe_title) VALUES ('$recipeName')";
	$result = $mydb->query($query);
	if ($mydb->errno != 0)
	{
		$msg = "Failed to execute query: ".PHP_EOL;
		$msg1 = __FILE__.':'.__LINE__.":error: ".$mydb->error.PHP_EOL;
		array_push($logs,$msg,$msg1);
		return $logs;
	}
	if ($result)
	{
		$msg = "Recipe Added".PHP_EOL;
		$logs['returnCode'] = '1';
		array_push($logs,$msg);
	}
	return $logs;
    }
    catch(mysqli_sql_exception $e) {
	    array_push($logs,$e->getMessage());
	    return $logs;
    }
}

function doAddRecInfo($recipeName,$ingredient,$measureUnit,$measureAmount,$logs)
{	
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);   
    try {    
	$mydb = doDbconnect($logs);	
	$msg = "Connected to Database, Attempting to Add Information".PHP_EOL;
	array_push($logs,$msg);
	$query = "INSERT IGNORE INTO ingredients (ingredient_name) VALUES ('$ingredient')";
	$result = $mydb->query($query);
	if ($mydb->errno != 0)
	{
		$msg = "Failed to execute query: ".PHP_EOL;
		$msg1 = __FILE__.':'.__LINE__.":error: ".$mydb->error.PHP_EOL;
		array_push($logs,$msg,$msg1);
		$logs['returnCode'] = '0';
		return $logs;
	}
	if ($result)
       	{
		$msg = "Ingredient Added".PHP_EOL;
		array_push($logs,$msg);
		$logs['returnCode'] = '1';
	}
	$query = "INSERT IGNORE INTO measure_units (measurement_desc) VALUES ('$measureUnit')";
	$result = $mydb->query($query);
	if ($mydb->errno != 0)
	{
		$msg = "Failed to execute query: ".PHP_EOL;
		$msg1 = __FILE__.':'.__LINE__.":error: ".$mydb->error.PHP_EOL;
		array_push($logs,$msg,$msg1);
		$logs['returnCode'] = '0';
		return $logs;
	}
	if ($result)
	{
		$msg = "Measurement Unit Added".PHP_EOL;
		array_push($logs,$msg);
		$logs['returnCode'] = '1';
	}
	$query = "INSERT IGNORE INTO measure_quant (quant) VALUES ('$measureAmount')";
	$result = $mydb->query($query);
	if ($mydb->errno != 0)
	{
		$msg = "Failed to execute query: ".PHP_EOL;
		$msg1 = __FILE__.':'.__LINE__.":error: ".$mydb->error.PHP_EOL;
		array_push($logs,$msg,$msg1);
		$logs['returnCode'] = '0';
		return $logs;
	}
	if ($result)
       	{
		$msg = "Measurement Amount Added".PHP_EOL;
		array_push($logs,$msg);
		$logs['returnCode'] = '1';
	}
	return $logs;
    }
   catch(mysqli_sql_exception $e) {
	    array_push($logs,$e->getMessage());
	    return $logs;
    }
}

function doGetRecInfo($recipeName,$logs)
{	
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    try {
	$mydb = doDbconnect($logs);
	$msg = "Connected to Database, Checking database for recipe info".PHP_EOL;
	array_push($logs,$msg);
	query:
	$query = "SELECT r.recipe_title AS Recipe Name, q.measurement_qty_id AS Amount, u.measurement_desc AS Unit, i.ingredient_name AS Ingredient FROM recipes r LEFT JOIN recipe_ingredients q on r.recipe_id = q.recipe_id LEFT JOIN ingredients i on i.ingredient_id = q.ingredient_id LEFT JOIN measure_units u on u.measurement_id = q.measurement_id";
	$result = $mydb->query($query);
	if ($mydb->errno != 0)
	{
		$msg = "Failed to execute query: ".PHP_EOL;
		$msg1 = __FILE__.':'.__LINE__.":error: ".$mydb->error.PHP_EOL;
		array_push($logs,$msg,$msg1);
	}
	if ($result)
	{
		$msg = "Sending Recipe Info".PHP_EOL;
		array_push($logs,$msg);
		$logs['recipeInfo'] = array();
		while($row = $result->fetch_assoc())
		{
			$logs['recipeInfo'][] = $row['Amount']." ".$row['Unit']." ".$row['Ingredient'];
		}
		$logs['returnCode'] = '1';
	}
	else if (mysql_num_rows($result) == 0 && $result)
	{
		$query = "SELECT recipe_id FROM recipes WHERE recipe_title = '$recipeName'";
		$result = $mydb->query($query);
		if ($mydb->errno != 0)
		{
			$msg = "Failed to execute query: ".PHP_EOL;
			$msg1 = __FILE__.':'.__LINE__.":error: ".$mydb->error.PHP_EOL;
			array_push($logs,$msg,$msg1);
		}
		if ($result)
		{	
			$msg = "Getting Recipe Info from API".PHP_EOL;
			array_push($logs,$msg);
			include 'ClientServer.php';
			goto query;
		}
	}
	return $logs;
    }
    catch(mysqli_sql_exception $e) {
	    array_push($logs,$e->getMessage());
	    return $logs;
    }
}

function doPullRecByName($recipeName,$logs)
{
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);   
    try {    
	$mydb = doDbconnect($logs);
	$msg = "Connected to Database, Checking database for recipes".PHP_EOL;
	array_push($logs,$msg);
	query:
	$query = "SELECT * FROM recipes WHERE recipe_title = '$recipeName'";
	$result = $mydb->query($query);
	if ($mydb->errno != 0)
	{
		$msg = "Failed to execute query: ".PHP_EOL;
		$msg1 = __FILE__.':'.__LINE__.":error: ".$mydb->error.PHP_EOL;
		array_push($logs,$msg,$msg1);
	}
	if (mysql_num_rows($result) == 0 && $result)
	{
		$msg = "Checking API for recipes".PHP_EOL;
		array_push($logs,$msg);
		include 'ClientServer.php';
		goto query;
	}
	else
	{
		$msg = "Gathering and Sending Recipes".PHP_EOL;
		while ($row = $result->fetch_assoc())
		{
			$logs['searchRec'][] = $row['recipe_title'];
		}
		array_push($logs,$msg);
		$logs['returnCode'] = '1';
	}
	return $logs;
    }	
    catch(mysqli_sql_exception $e) {
	    array_push($logs,$e->getMessage());
	    return $logs;
    }
}

function doPullRecByIngredient($ingredientName,$logs)
{
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    try {
        $mydb = doDbconnect($logs);
        $msg = "Connected to Database, Checking database for recipes".PHP_EOL;
        array_push($logs,$msg);
        query:
        $query = ""; //Select all recipe names where they have ingredientName
        $result = $mydb->query($query);
        if ($mydb->errno != 0)
        {
                $msg = "Failed to execute query: ".PHP_EOL;
                $msg1 = __FILE__.':'.__LINE__.":error: ".$mydb->error.PHP_EOL;
                array_push($logs,$msg,$msg1);
        }
        if (mysql_num_rows($result) == 0 && $result)
        {
                $msg = "Checking API for recipes".PHP_EOL;
                array_push($logs,$msg);
                include 'ClientServer.php';
                goto query;
        }
        else
        {
                $msg = "Gathering and Sending Recipes".PHP_EOL;
                while ($row = $result->fetch_assoc())
		{
                        $logs['searchRec'][] = $row['recipe_title'];
                }
                array_push($logs,$msg);
                $logs['returnCode'] = '1';
        }
        return $logs;
    }
    catch(mysqli_sql_exception $e) {
            array_push($logs,$e->getMessage());
            return $logs;
    }
}
function viewAllRec($logs)
{
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);   
    try {    
	$mydb = doDbconnect($logs);
	$msg = "Connected to Database, Checking database for recipes".PHP_EOL;
	array_push($logs,$msg);
	$query = "SELECT * FROM recipes";
	$result = $mydb->query($query);
	if ($mydb->errno != 0)
	{
		$msg = "Failed to execute query: ".PHP_EOL;
		$msg1 = __FILE__.':'.__LINE__.":error: ".$mydb->error.PHP_EOL;
		array_push($logs,$msg,$msg1);
		return $logs;
	}
	if ($result)
	{
		$msg = "Sending All Recipes".PHP_EOL;
		array_push($logs,$msg);
		$logs['allRecipes'] = array();
		while($row = $result->fetch_assoc())
		{
			$logs['allRecipes'][] = $row['recipe_title'];
		}
		$logs['returnCode'] = '1';
	}
	return $logs;
    }
    catch(mysqli_sql_exception $e) {
	    array_push($logs,$e->getMessage());
	    return $logs;
    }
}

function viewUserRec($username, $logs)
{
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);   
    try {    
	$mydb = doDbconnect($logs);
	$msg = "Connected to Database, Checking database for user's recipes".PHP_EOL;
	array_push($logs,$msg);
	$query = "SELECT * FROM Recipes WHERE email = '$username'";
	$result = $mydb->query($query);
	if ($mydb->errno != 0)
	{
		$msg = "Failed to execute query: ".PHP_EOL;
		$msg1 = __FILE__.':'.__LINE__.":error: ".$mydb->error.PHP_EOL;
		array_push($logs,$msg,$msg1);
		return $logs;
	}
	if ($result) {
		$msg = "Sending Recipes".PHP_EOL;
		array_push($logs,$msg);
		$logs['userRecipes'] = array();
		while($row = $result->fetch_assoc())
		{
			$logs['userRecipes'][] = $row['recipe_title'];
		}
		$logs['returnCode'] = '1';
	}
	return $logs;
    }	
    catch(mysqli_sql_exception $e) {
	    array_push($logs,$e->getMessage());
	    return $logs;
    }
}

function doLogin($email,$password,$logs)
{
    // lookup username in databas
	// check password
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);   
    try {    
	$mydb = doDbconnect($logs);	
	$msg = "Connected to Database, Checking database for user".PHP_EOL;
	array_push($logs,$msg);
	$query = "SELECT * FROM USERS WHERE email = '$email' AND password = '$password'";
	$result = $mydb->query($query);
	if ($mydb->errno != 0)
	{
		$msg = "Failed to execute query: ".PHP_EOL;
		$msg1 = __FILE__.':'.__LINE__.":error: ".$mydb->error.PHP_EOL;
		array_push($logs,$msg,$msg1);
		return $logs;
	}
	if ($result)
	{
		$row = $result->fetch_assoc();
		if ($row["password"] == $password)
		{
			$msg = "Logging In User".PHP_EOL;
			array_push($logs,$msg);
			$logs['returnCode'] = '1';
			return $logs;
		}
		$msg = "Password does not match".PHP_EOL;
		array_push($logs,$msg);	
	}
	return $logs;
    }
    catch(mysqli_sql_exception $e) {
	    array_push($logs,$e->getMessage());
	    return $logs;
    }
    //return false if not valid
}

function doCreate($email,$password,$logs)
{
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    try {
	$mydb = doDbconnect($logs);	
	$msg = "Connected to Database, Creating User Account".PHP_EOL;
	array_push($logs,$msg);
	$query = "INSERT INTO USERS (email, password) VALUES ('$email','$password')";
	$result = $mydb->query($query);
	if ($mydb->errno != 0)
	{
		$msg = "Failed to execute query: ".PHP_EOL;
		$msg1 = __FILE__.':'.__LINE__.":error: ".$mydb->error.PHP_EOL;
		array_push($logs,$msg,$msg1);
		exit($logs);
	}
	if ($result) {
		$msg = "Account Created".PHP_EOL;
		array_push($logs,$msg);
		return $logs;
	}
	return $logs;
    }
    catch (mysqli_sql_exception $e) {
	    array_push($logs,$e->getMessage());
	    return $logs;
    }
}

function requestProcessor($request)
{
  $logArray = array("returnCode" => '0');
  echo "Received request".PHP_EOL;
  var_dump($request);
  if(!isset($request['type']))
  {
    $msg = "ERROR: unsupported message type";
    array_push($logArray,$msg);
  }
  switch ($request['type'])
  {
    case "Login":
      $msg = "Attempting Login".PHP_EOL;
      array_push($logArray,$msg);
      return doLogin($request['email'],$request['password'],$logArray);
    case "Create":
      $msg =  "Attempting Account Creation".PHP_EOL;
      array_push($logArray,$msg);
      return doCreate($request['email'],$request['password'],$logArray);
    case "addRecipe":
      $msg = "Attempting to Add Recipe".PHP_EOL;
      array_push($logArray,$msg);
      return doAddRec($request['recipe'],$logArray);
    case "addRecipeInfo":
      $msg = "Attempting to Add Recipe Info".PHP_EOL;
      array_push($logArray,$msg);
      return doAddRecInfo($request['recipe'],$request['ingredient'],$request['unit'],$request['amount'],$logArray);
    case "pullRecipeByName":
      $msg = "Attempting to Pull Recipes".PHP_EOL;
      array_push($logArray,$msg);
      return doPullRecByName($request['recipe'],$logArray);
    case "pullRecipeByIngredient":
      $msg = "Attempting to Pull Recipes".PHP_EOL;
      array_push($logArray,$msg);
      return doPullRecByIngredient($request['ingredient'],$logArray);
    case "viewRecipeInfo":
      $msg = "Attempting to View Recipe Info".PHP_EOL;
      array_push($logArray,$msg);
      return doGetRecInfo($request['recipe'],$logArray);
    case "viewAllRecipes":
      $msg = "Attempting to View All Recipes";
      array_push($logArray,$msg);
      return viewAllRec($logArray);
    case "viewUserRecipes":
      $msg = "Attempting to View Recipes for User";
      array_push($logArray,$msg);
      return viewUserRec($request['email'],$logArray);
    case "Voting":
      $msg = "Attempting to Adjust Vote".PHP_EOL;
      array_push($logArray,$msg);
      return doVoting($request['recipe'],$request['vote'],$logArray);
    case "validate_session":
      return doValidate($request['sessionId']);
  }
  $msg = "Server received request and processed";
  array_push($logArray,$msg);
  return $logArray;
}

$server = new rabbitMQServer("testRabbitMQ.ini","testServer");
echo "testRabbitMQServer BEGIN".PHP_EOL;
$server->process_requests('requestProcessor');
echo "testRabbitMQServer END".PHP_EOL;
exit();
?>
