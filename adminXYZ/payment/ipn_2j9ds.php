<?php 
// error_reporting(E_ALL ^ E_NOTICE); 
// $email = $_GET['ipn_email']; 
$email = "paylog@theagencyonline.com";
// $email = "skit@skitterskatter.com";
$header = ""; 
$emailtext = ""; 
$res_summary = "";
 
// Read the post from PayPal and add 'cmd' 
$req = 'cmd=_notify-validate'; 
if(function_exists('get_magic_quotes_gpc')) {
	$get_magic_quotes_exits = true;
} 


foreach ($_POST as $key => $value) {
  // Handle escape characters, which depends on setting of magic quotes 
	if($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) {
		$value = urlencode(stripslashes($value)); 
  	} else { 
    	$value = urlencode($value); 
  	}  
  	$req .= "&$key=$value";  
} 

include('../../includes/mysql_connect.php');
$txn_type = escape_data($_POST['txn_type']);
$firstname = escape_data($_POST['first_name']);
$lastname = escape_data($_POST['last_name']);
$product_name = urldecode($_POST['product_name']);
$stringend = strpos($product_name, ':');

$user_id = (int) substr($product_name, 0, $stringend);

if(!empty($_POST['recurring_payment_id'])) {
	$rpid = escape_data($_POST['recurring_payment_id']);
} else {
	$rpid = '';
}


// Post back to PayPal to validate 
/* $header .= "POST /cgi-bin/webscr HTTP/1.0\r\n"; 
$header .= "Content-Type: application/x-www-form-urlencoded\r\n"; 
$header .= "Content-Length: " . strlen($req) . "\r\n\r\n"; 
*/

// post back to PayPal system to validate
$header .= "POST /cgi-bin/webscr HTTP/1.1\r\n";
$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
$header .= "Host: www.paypal.com\r\n";
$header .= "Content-Length: " . strlen($req) . "\r\n";
$header .= "Connection: close\r\n\r\n";

// $fp = fsockopen ('www.paypal.com', 80, $errno, $errstr, 30); 
$fp = fsockopen ('ssl://www.paypal.com', 443, $errno, $errstr, 30);

// Process validation from PayPal 
if (!$fp && $_GET['codesecret'] != 'iF9e3pW52nd5Kui') { 

		$from = "info@theagencyonline.com";
	
		$headers  = "From: $from\r\n";
		$headers .= "Content-type: text/html\r\n";

		$subject = "CRITICAL ERROR: PAYPAL COMMUNICATION FAILED";
		$message = 'Contact the administrator immediately.';
		mail("ungabo@yahoo.com", $subject, $message, $headers);
		mail($from, $subject, $message, $headers);
} else { 
	// NO HTTP ERROR  
	fputs ($fp, $header . $req); 
	while (!feof($fp)) { 
		$res = fgets ($fp, 1024); 
		// $emailtext .= "\n\n" . $res . "\n\n";
		// if (strcmp ($res, "VERIFIED") == 0) { 
		if (strcmp (trim($res), 'VERIFIED') == 0) {
			// TODO: 
			// Check the payment_status is Completed 
			// Check that txn_id has not been previously processed 
			// Check that receiver_email is your Primary PayPal email 
			// Check that payment_amount/payment_currency are correct 
			// Process payment 
			 
			// If 'VERIFIED', send an email of IPN variables and values to the 
			// specified email address 
			
			foreach ($_POST as $key => $value) { 
				$emailtext .= $key . " = " .$value ."\n\n"; 
			} 
			mail($email, "VERIFIED IPN " . $txn_type . " " . $rpid, $emailtext . "\n\n" . $req); 
			// mail('skit@skitterskatter.com', "VERIFIED IPN " . $txn_type . " " . $rpid, $emailtext . "\n\n" . $req); 
			
			

			
			if($txn_type == 'recurring_payment') {
				$query = "UPDATE agency_profiles SET payProcessed='1', payFailed='0' WHERE user_id='$user_id'";
				mysql_query($query);
				
				/*========= for mentor records =============*/
				// find mentor ID
				$query = "SELECT mentor_id FROM agency_profiles WHERE user_id='$user_id'";
				$result = mysql_query ($query);
				if ($row = mysql_fetch_array ($result, MYSQL_ASSOC)) {
					$mentorid = $row['mentor_id'];
					if(!empty($mentorid) && !empty($_POST['txn_id']) && !empty($_POST['mc_gross']) && !empty($user_id)) {				
						$txn_id = escape_data($_POST['txn_id']);
						$txn_amt = escape_data($_POST['mc_gross']);
						$commission_amt = number_format((($txn_amt/.9) * .1), 2);  // commission is 10% of original price. txn_amt is original price minus 10% so have to add on this 10% before calculating commission
						$query = "INSERT INTO agency_mentor_sales (mentor_id, user_id, txn_id, txn_amt, commission_amt) VALUES ('$mentorid', '$user_id', '$txn_id', '$txn_amt', '$commission_amt')";
						mysql_query($query);
					}
				}			
				/*========= end: for mentor records =============*/
			}
			
			
			
			
		} else if (strcmp ($res, "INVALID") == 0) { 
			foreach ($_POST as $key => $value){ 
			  $emailtext .= $key . " = " . $value ."\n\n"; 
			} 
			mail($email, "INVALID IPN " . $txn_type . " " . $rpid, $emailtext . "\n\n" . $req); 
			// mail('skit@skitterskatter.com', "INVALID IPN " . $txn_type . " " . $rpid, $emailtext . "\n\n" . $req); 
		} else { // $res is not giving an expected response
			/* foreach ($_POST as $key => $value){ 
			  $emailtext .= $key . " = " . $value ."\n\n"; 
			} */
			
			
		}
		$res_summary .= '$res: ' . $res . "\n\n";
	}
	
	// mail('skit@skitterskatter.com', "IPN ISSUE " . $txn_type . " " . $rpid, $emailtext . "\n\n" . $req . "\n\n" . $res_summary); 
	
	
	
	// IF PAYMENT IS SKIPPED FOR ANY REASON, SET USER TO UNPAID
	// if($txn_type == 'recurring_payment_skipped' || $txn_type == 'recurring_payment_failed') {
	if($txn_type == 'recurring_payment_failed') {
		$query = "UPDATE agency_profiles SET payProcessed='0', payFailed='1' WHERE user_id='$user_id'";
		mysql_query($query); // set to unpaid
		// put in payFailedDate
		$payFailedDate = date('Y-m-d');
		$query = "UPDATE agency_profiles SET payFailedDate='$payFailedDate' WHERE user_id='$user_id'";
		mysql_query($query); // set to unpaid
		
		// cancel previous payment profile
		$paypal_profile_id = $rpid;
		if(!empty($paypal_profile_id)) {
			$payment_action = 'cancel';
			include('ManageRecurringPaymentsProfile.php'); // this processes the payment
		}
		
		/*
		// 3/31/10 Oliver requested to leave them approved
		$query = "UPDATE forum_users SET user_type='1' WHERE user_id='$user_id'";
		mysql_query($query); // set to unapproved
		*/
		
		if(mysql_result(mysql_query("SELECT user_type FROM forum_users WHERE user_id='$user_id'"), 0, 'user_type')) { // 1=>unapproved; 0=>approved
			// unapproved
			$failmessage = mysql_result(mysql_query("SELECT varvalue FROM agency_vars WHERE varname='email_payment_failed2'"), 0, 'varvalue');
		} else {
			// approved
			$failmessage = mysql_result(mysql_query("SELECT varvalue FROM agency_vars WHERE varname='email_payment_failed'"), 0, 'varvalue');
		}
		
		/*========= send alert email =============*/
		$message = '<html>
			  <body>
			  ' .
			  $failmessage .
			  '
			  </body>
			  </html>';
		
		$to = mysql_result(mysql_query("SELECT user_email FROM forum_users WHERE user_id='$user_id'"), 0, 'user_email');
		$from = "info@theagencyonline.com";
		$subject = "Your Pictures and Profile at The AGENCY";
	
		$headers  = "From: $from\r\n";
		$headers .= "Content-type: text/html\r\n";
	
		//options to send to cc+bcc
		//$headers .= "Cc: [email]email@email.com[/email]";
		//$headers .= "Bcc: [email]email@email.com[/email]";
		
		// now lets send the email.
		// TEMPORARILY COMMENTED OUT SO THAT USERS WITH TWO PAYMENT PROFILES DON'T GET ZAPPED
		mail($to, $subject, $message, $headers);
		mail("ungabo@yahoo.com", $subject, $message, $headers);
		
		$subject = "ALERT: FAILED PAYMENT";
		$message = $firstname . ' ' . $lastname  . ' (userid: ' . $user_id . ') has a failed or skipped payment and has been marked as FAILED.
		
failure code: ' . $txn_type;
		// mail("skit@skitterskatter.com", $subject, $message, $headers);
		mail($from, $subject, $message, $headers);

	}
} 

// $message = "INSERT INTO agency_payment_log (transaction_type, paypal_PROFILEID, user_id, firstname, lastname, ipn_summary) VALUES ('$txn_type', '$rpid', '$user_id', '$firstname', '$lastname', '$emailtext')";
	
mysql_query("INSERT INTO agency_payment_log (transaction_type, paypal_PROFILEID, user_id, firstname, lastname, ipn_summary) VALUES ('$txn_type', '$rpid', '$user_id', '$firstname', '$lastname', '$emailtext')");

// mail("skit@skitterskatter.com", $subject, $message, $headers);

mysql_close();
	
fclose ($fp); 
?>