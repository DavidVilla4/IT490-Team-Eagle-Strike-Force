#!/usr/bin/php
<?php

$mydb = new mysqli('25.2.255.100','admin','admin','newDb');

if ($mydb-> errno != 0)
{
	echo "Failed to connect to database: ". $mydb->error.PHP_EOL;
	exit(0);
}

echo "Successfully connected to database".PHP_EOL;

$query = "select * from USERS;";
$response = $mydb->query($query);

if ($mydb->errno != 0)
{
	echo "Failed to execute query: ". PHP_EOL;
	echo _FILE_.':'._LINE_.":error".$mydb->error.PHP_EOL;
	exit(0);
}

?>
