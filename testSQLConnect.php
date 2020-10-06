#!/usr/bin/php
<?php

$mydb = new mysqli('localhost','admin','admin','newDb');

if ($mydb->errno != 0)
{
        echo "failed to connect to database: ". $mydb->error . PHP_EOL;
        exit(0);
}

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


