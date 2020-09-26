<?php
// this is set to just send to ungabo@yahoo.com for testing.  That line must be removed to go live!


$limit = 500; // limit of emails sent per cron run

function send_message($to_id, $from_name, $message) {	
	$email_subject = 'The Agency Online: New Message';
	$from = "no-reply@theagencyonline.com";
	$headers  = "From: $from\r\n";
	$headers .= "Content-type: text/html\r\n";

	$query2 = "SELECT user_email FROM forum_users WHERE user_id='$to_id'";
	$result2 = mysql_query ($query2);
	if ($row2 = mysql_fetch_array ($result2, MYSQL_ASSOC)) {
		$to_email = $row2['user_email'];
		
		if(!empty($to_email) && (!empty($subject) || !empty($message))) {
			$email_message = '<html>
				  <body><p>You have received a New Message at <a href="http://www.TheAgencyOnline.com">The Agency Online</a> from <b>' . $from_name . '</b>.</p>
<p>&nbsp;</p>
<p>Subject: <b>' . $subject . '</b></p>
<p>' . $message . '</p>

<p>&nbsp;</p>
<p>DO NOT REPLY TO THIS EMAIL</p>
<p>&nbsp;</p>
<p>To view this message or to reply to the sender, please log onto your account at <a href="http://www.TheAgencyOnline.com">The Agency Online</a>.</p>
<p>&nbsp;</p>
<p>Thank you for using The Agency.</p>
				  </body>
				  </html>';			
			
			// FOR TESTING:
			// $to_email = 'oliver@theagencyonline.com';
			// mail($to_email, $email_subject, $email_message, $headers);
			
			mail($to_email, $email_subject, $email_message, $headers);
			
			
			// FOR TESTING:
			// $to_email = 'ungabo@yahoo.com';
			// mail($to_email, $email_subject, $email_message, $headers);
			
			
			// echo 'Message Sent To ' . $to_email . ': ' . $email_message . '<br /><br />';
		}
	}
}

// FOR TESTING:
// mail('ungabo@yahoo.com', 'send_emails.php was run', 'This is to inform that the send_emails.php script was run on The Agency Online');







// mail('ungabo@yahoo.com', 'test', 'testttt', $headers);


if(!empty($limit)) {
	$setlimit = 'LIMIT ' . $limit;
} else {
	$setlimit = '';
}

// include('../includes/mysql_connect.php');

// Set the database access information as constants.
DEFINE ('DB_USER', 'theagencydb');
DEFINE ('DB_PASSWORD', 'j9If384Oi');
DEFINE ('DB_HOST', 'localhost');
DEFINE ('DB_NAME', 'theagency_database');

if ($dbc = @mysql_connect (DB_HOST, DB_USER, DB_PASSWORD)) { // Make the connnection.

	if (!mysql_select_db (DB_NAME)) { // If it can't select the database.

		// Handle the error.
		trigger_error("Could not select the database!\n<br />MySQL Error: " . mysql_error());
		exit();

	} // End of mysql_select_db IF.

} else { // If it couldn't connect to MySQL.

	// Print a message to the user, include the footer, and kill the script.
	trigger_error("Could not connect to MySQL!\n<br />MySQL Error: " . mysql_error());
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







$query = "SELECT * FROM agency_messages_out WHERE email_sent='0' ORDER BY date_entered DESC $setlimit";
// $query = "SELECT * FROM agency_messages_out ORDER BY date_entered DESC $setlimit";

$result = mysql_query ($query);
while ($row = mysql_fetch_array ($result, MYSQL_ASSOC)) {
	$sent_id = $row['sent_id'];
	$to_id = $row['to_id'];
	$to_name = $row['to_name'];
	$from_name = $row['from_name'];
	$subject = $row['subject'];
	$message = $row['message'];
	$lightbox_id = $row['lightbox_id'];
	if(empty($to_id)) {
		$query2 = "SELECT user_id FROM agency_messages_out_lb WHERE sent_id='$sent_id'";
		$result2 = mysql_query ($query2);
		while ($row2 = mysql_fetch_array ($result2, MYSQL_ASSOC)) {
			$to_id = $row2['user_id'];
			send_message($to_id, $from_name, $message);
		}
	} else {
		send_message($to_id, $from_name, $message);
	}
	
	$query2 = "UPDATE agency_messages_out SET email_sent='1' WHERE sent_id='$sent_id'";
	mysql_query($query2);
}


mysql_close(); // Close the database connection.
?>
