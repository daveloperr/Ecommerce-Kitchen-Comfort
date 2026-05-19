<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "ecommfinaldb";


    $conn = mysqli_connect($servername, $username, $password, $dbname);
    if(!$conn){
        die("Can't connect to the database!");
    }



?>