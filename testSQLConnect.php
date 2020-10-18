#!/usr/bin/php
<?php

$mydb = new mysqli('localhost','admin','admin','newDb');

if ($mydb->errno != 0)
{
        echo "failed to connect to database: ". $mydb->error . PHP_EOL;
        exit(0);
}

/*
if (!mysqli_set_charset($mydb, 'utf8'))
{
	$output = 'Unable to set database encoding.';
}

if (!mysqli_select_db($mydb, 'newDb'))
{
	$error = 'Cannot locate the database.';
	exit(0);
}


$result = mysqli_query($mydb, 'SELECT
        r.recipe_title AS Recipe Name,
        r.recipe_description AS Recipe Description,
        z.measurement_qty_id AS Amount,
        u.measurement_desc AS Unit,
        i.ingredient_name AS Ingredient
FROM
        recipes r
LEFT JOIN
        recipe_ingredients z on r.recipe_id = z.recipe_id
LEFT JOIN
        measure_units u on r.measurement_id = u.measurement_id
LEFT JOIN
	ingredients i on r.ingredient_id = i.ingredient_id');

if (!result)
{
	$error = 'Error fetching recipes.. ' . mysqli_error($mydb);
	exit(0);
}

while ($row = mysqli_fetch_array($result))
{
	$recipes[] = $row['']
*/

echo "successfully connected to database".PHP_EOL;

/*
$email = mysqli_real_escape_string($mydb,$_REQUEST['email']);
$password = mysqli_real_escape_string($mydb,$_REQUEST['password']);

$query = "INSERT INTO USERS (email,password) VALUES ('$email','$password')";

$response = $mydb->query($query);
if ($mydb->errno != 0)
{
        echo "failed to execute query:".PHP_EOL;
        echo __FILE__.':'.__LINE__.":error: ".$mydb->error.PHP_EOL;
       exit(0);
}
*/

?>

