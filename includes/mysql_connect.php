<?php
// This file contains the database access information.
// This file also establishes a connection to MySQL and selects the database.

// Set the database access information as constants.


// echo "<pre>";
// print_r($_SERVER);
// echo "</pre>";

// $base_url = sprintf(
//     "%s://%s%s",
//     isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
//     $_SERVER['SERVER_NAME'],
//     $_SERVER['REQUEST_URI']
// );

ini_set( "display_errors", 0); 
	
$req_server = explode('/',$_SERVER['REQUEST_URI']);

// echo "<pre>";
// print_r($req_server);
// echo "</pre>";
// exit;

$base_url = sprintf(
    "%s://%s%s",
    isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
    $_SERVER['SERVER_NAME'],
    '/'.$req_server[1].'/'
);

DEFINE ('BASE_URL', $base_url);
DEFINE ('SITE_URL', $base_url);

// echo SITE_URL;exit;
$user = "root";
$pass = "";
$host = "localhost:3308";
$dbname = "tambwksf_theagency";

DEFINE ('DB_USER', $user);
DEFINE ('DB_PASSWORD', $pass);
DEFINE ('DB_HOST', $host);
DEFINE ('DB_NAME', $dbname);

if ($dbc = mysql_connect (DB_HOST, DB_USER, DB_PASSWORD)) { // Make the connnection.

	if (!mysql_select_db (DB_NAME)) { // If it can't select the database.

		// Handle the error.
		trigger_error("Could not select the database!\n<br />MySQL Error: " . mysql_error());

		// Print a message to the user, include the footer, and kill the script.
		// include ('./includes/footer.html');
		exit();

	} // End of mysql_select_db IF.

	// echo "connect0";exit;

} else { // If it couldn't connect to MySQL.

	// Print a message to the user, include the footer, and kill the script.
	trigger_error("Could not connect to MySQL!\n<br />MySQL Error: " . mysql_error());
	// include ('./includes/footer.html');
	exit();

} // End of $dbc IF.

// Create a function for escaping the data.
if(!function_exists('escape_data')) {
	function escape_data ($data) {
	
		if(function_exists('utf8_normalize_nfc')) {
			$data = utf8_normalize_nfc($data);
		}
		
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
		// $data = preg_replace('/[^\r\n\t\x20-\x7E\xA0-\xFF]/', ' ', $data); 
		return $data;
	
	} // End of function.
}
?>