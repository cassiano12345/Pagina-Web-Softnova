<?php

if (isset($_SESSION['user']))
	include_once("modules/main/index.php");	
else
	include_once("modules/login/index.php");

?>

