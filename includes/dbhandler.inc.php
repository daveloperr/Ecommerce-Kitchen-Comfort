<?php
	$serverName = "localhost";
	$dbUsername = "root";
	$dbUserPass = "";
	$dbName = "ecommfinaldb";


	$conn = mysqli_connect($serverName,$dbUsername,$dbUserPass,$dbName);
	if(!$conn)
	{
		die("Can't connect to the database . . .");
	}

?>