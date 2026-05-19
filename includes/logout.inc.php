<?php

session_start();


$_SESSION = array();


session_destroy();


header("Location: ../home_user/login.php");
exit();
?>
