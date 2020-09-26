<?php
session_start();
?>
<?php
// This file contains the database access information.
// This file also establishes a connection to MySQL and selects the database.

// Set the database access information as constants.
function maindb() {
	$user = 'theagencydb';
	$pass = 'j9If384Oi';
	$host = 'localhost';
	$dbname = 'theagency_database';
	
	if ($dbc = @mysql_connect ($host, $user, $pass)) { // Make the connnection.
	
		if (!mysql_select_db ($dbname)) { // If it can't select the database.
	
			// Handle the error.
			trigger_error("Could not select the database!\n<br />MySQL Error: " . mysql_error());
	
			// Print a message to the user, include the footer, and kill the script.
			// include ('./includes/footer.html');
			exit();
	
		} // End of mysql_select_db IF.
	
	} else { // If it couldn't connect to MySQL.
	
		// Print a message to the user, include the footer, and kill the script.
		trigger_error("Could not connect to MySQL!\n<br />MySQL Error: " . mysql_error());
		exit();
	
	} // End of $dbc IF.
	return $dbc;
}
function zipdb() {
	$user = 'agencyzips';
	$pass = 'agency';
	$host = 'localhost';
	$dbname = 'theagency_zipcodes';
	
	if ($dbc = @mysql_connect ($host, $user, $pass)) { // Make the connnection.
	
		if (!mysql_select_db ($dbname)) { // If it can't select the database.
	
			// Handle the error.
			trigger_error("Could not select the database!\n<br />MySQL Error: " . mysql_error());
	
			// Print a message to the user, include the footer, and kill the script.
			// include ('./includes/footer.html');
			exit();
	
		} // End of mysql_select_db IF.
	
	} else { // If it couldn't connect to MySQL.
	
		// Print a message to the user, include the footer, and kill the script.
		trigger_error("Could not connect to MySQL!\n<br />MySQL Error: " . mysql_error());
		exit();
	
	} // End of $dbc IF.
	return $dbc;
}
// Create a function for escaping the data.
function escape_data ($data) {

	// Address Magic Quotes.
	if (ini_get('magic_quotes_gpc')) {
		$data = stripslashes($data);
	}

	// Check for mysql_real_escape_string() support.
	if (function_exists('mysql_real_escape_string')) {
		global $dbc; // Need the connection.
		$data = mysql_real_escape_string (trim($data), $dbc);
	} else {
		$data = mysql_escape_string (trim($data));
	}

	// Return the escaped value.
	return $data;

} // End of function.
?>
<?php
// if(isset($_SESSION['admin'])) {

	// $query = "ALTER TABLE 'agency_profiles' ADD 'zip' VARCHAR( 20 ) NULL AFTER 'state'";
	// mysql_query($query);
	

	$dbc = zipdb();
	$query = "SELECT * FROM agency_zip";
	$result = $result = mysql_query($query);
	$zips = array();
	while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$zips[$row['STATE']][$row['CITY_NAME']] = $row['ZIP_CODE'];	
		/* echo $zips[$row['STATE_ABBREV']][$row['CITY_NAME']] . '<br />';
		if($row['CITY_NAME'] == 'GHENT') {
			echo '             THIS ONE                ';
		} */
	}
	/* echo 'testttt';
	echo '<br /><br /><br />' . $zips['NY']['GHENT'] . '<br /><br /><br />';
	
	foreach($zips['NY'] as $city=>$zip) {
		echo $city . ' = ' . $zips['NY'][$city] . '<br />';
	} */
	
	mysql_close();
	
	$dbc = maindb();
	
	// echo '<br /><br /><br />' . $zips['NEW YORK']['GHENT'] . '<br /><br /><br />';

	
	$query = "SELECT * FROM agency_profiles ORDER BY user_id";
	$result = mysql_query($query);
	while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$userid = $row['user_id'];
		// echo $userid . ' ';
		$zip = strtoupper($row['zip']);
		$state = strtoupper($row['state']);
		$city = strtoupper($row['city']);
		$country = $row['country'];
		// echo  $zips[$state][$city];
				// echo $state . ', ' . $city . '<br />';

		if(empty($zip)) {
			echo "<br />$userid: $city, $state, $country";
		} else {
			echo "<br />$userid: $city, $state  $zip";
		}
		/*
		if(isset($zips[$state][$city])) {
			$thiszip = $zips[$state][$city];
			if($thiszip == '10242') {
				$thiszip = '10026';
			}
			echo "<br />$userid: $city, $state  $thiszip";
			/* $query2 = "UPDATE agency_profiles SET zip='$thiszip' WHERE user_id='$userid'";
			mysql_query($query2); */
		/* } else {
			echo "<br />$userid: $city, $state, $country";
		}  */
	}
	
	
	// echo 'test';
	
// }
?>
<?php
include('footer.php');
?>