<?php

include('../includes/mysql_connect.php');
include('../includes/agency_functions.php');

if(isset($_GET['state'])) {
	$state = escape_data($_GET['state']);
	$query = "SELECT DISTINCT city FROM agency_cities WHERE state='$state' AND country='United States' ORDER BY city";
	$result = mysql_query ($query);
	if(mysql_num_rows($result) > 0) {
		echo '<br /><br /><select name="city"><option value=""> - Any City - </option>';
		while ($row = mysql_fetch_array ($result, MYSQL_ASSOC)) {
			echo '<option value="' . $row['city'] . '"';
			if(!empty($_COOKIE['agencysearch']['city'])) {
				if($_COOKIE['agencysearch']['city'] == $row['city']) {
					echo ' selected';
				}
			}
			echo '>' . $row['city'] . '</option>';
		}
		echo '</select>';
	}
} else if(isset($_GET['country'])) {
	$country = escape_data($_GET['country']);
	$query = "SELECT city FROM agency_cities WHERE country='$country' ORDER BY city";
	$result = mysql_query ($query);
	if(mysql_num_rows($result) > 0) {
		echo '<br /><br /><select name="city"><option value=""> - Any City - </option>';
		while ($row = mysql_fetch_array ($result, MYSQL_ASSOC)) {
			echo '<option value="' . $row['city'] . '"';
			if(!empty($_COOKIE['agencysearch']['city'])) {
				if($_COOKIE['agencysearch']['city'] == $row['city']) {
					echo ' selected';
				}
			}
			echo '>' . $row['city'] . '</option>';
		}
		echo '</select>';
	}
}
mysql_close(); // Close the database connection.
?>
