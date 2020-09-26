<?php
	// you must change the variables below to those that match you system
	$db_host = "localhost"; // the name of you host, usually localhost
	$db_username = "agencyzips";
	$db_password = "agency";
	$db_name = "theagency_zipcodes";
	
	$connection = @mysql_connect($db_host,$db_username,$db_password) or die("Cannot connect to database.  Please try again later");
	if ($connection!="" and !@mysql_select_db($db_name,$connection)) die("The database is unavailable.  Please try again later");
?>