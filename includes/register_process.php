<?php
$message_client = '';
if(is_banned()) {
	$message_client .= '<p><font color="red">Your IP address has been blocked from creating new accounts.  If you feel this is in error please contact us to remove the block.</font></p>';
	
} else if(isset($_POST['submitclient'])) {
	
		 
	$verify=file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=6LeZ-EkUAAAAAExfx2oorfIEKkNHkXu7R5--j-VQ}&response=' . $_POST['g-recaptcha-response']);
	$captcha_success=json_decode($verify);

	
	/*
	$response = $_POST["recaptcha_response"];
	$url = 'https://www.google.com/recaptcha/api/siteverify';
	$data = array(
		'secret' => '6LcFTN0SAAAAAJIhKf8ZyomJ_5LPZShjBk1GRYvY',
		'response' => $_POST["recaptcha_response"]
	);
	$options = array(
		'http' => array (
			'method' => 'POST',
			'content' => http_build_query($data)
		)
	);
	$context  = stream_context_create($options);
	$verify = file_get_contents($url, false, $context);
	$captcha_success=json_decode($verify);
	if ($captcha_success->success==false) {
		echo "<p>You are a bot! Go away!</p>";
	} else if ($captcha_success->success==true) {
		echo "<p>You are not not a bot!</p>";
	}*/
	
	if ($captcha_success->success==true) { // Handle the form.
		$message_client = '';
		// Check for a username.
		if (eregi ('^[[:alnum:]]{4,30}$', stripslashes(trim($_POST['username'])))) {
			$un = escape_data($_POST['username']);
			$sql = "SELECT user_id FROM forum_users WHERE username='$un'";
			if(mysql_num_rows(mysql_query($sql)) != 0) {
				$un = FALSE;
				$message_client .= '<p><font color="red">The Username you selected has already been taken by another member.  Please select a different Username.</font></p>';
			}
		} else {
			$un = FALSE;
			$message_client .= '<p><font color="red">Please enter a valid username (between 4 and 30 alphanumeric characters, no spaces).</font></p>';
		}
	
		// Check for a first name.
		if (!empty($_POST['firstname'])) {
			$fn = escape_data($_POST['firstname']);
		} else {
			$fn = FALSE;
			$message_client .=  '<p><font color="red">Please fill the First Name field.</font></p>';
		}
	
		// Check for a last name.
		$ln = escape_data($_POST['lastname']);
	
		// Check for an email address.
		if (eregi ('^[[:alnum:]][a-z0-9_\.\-]*@[a-z0-9\.\-]+\.[a-z]{2,4}$', stripslashes(trim($_POST['email'])))) {
			if($_POST['email'] == $_POST['confirmemail']) {
				$e = escape_data($_POST['email']);
				$sql = "SELECT user_id FROM forum_users WHERE user_email='$e'";
				if(mysql_num_rows(mysql_query($sql)) != 0) {
					$e = FALSE;
					$message_client .= '<p><font color="red">The Email you entered is already being used by another account.  Each account must have a unique email.  If you have forgotten your password, you may retrieve it <a href="forgotpassword.php">here</a>.</font></p>';
				}
			} else {
				$e = FALSE;
				$message_client .=  '<p><font color="red">Your confirmation email did not match.</font></p>';
			}
		} else {
			$e = FALSE;
			$message_client .=  '<p><font color="red">Please enter a valid email address.</font></p>';
		}
	
		// Check for a password and match against the confirmed password.
		if (eregi ('^[[:alnum:]]{6,20}$', stripslashes(trim($_POST['joinpassword'])))) {
			$p = escape_data($_POST['joinpassword']);
		} else {
			$p = FALSE;
			$message_client .=  '<p><font color="red">Please enter a valid password (between 6 and 20 alphanumeric characters)</font></p>';
		}
	
	
		if(($_POST['location'] == 'Other') && !empty($_POST['otherlocation'])) { 
			$location = escape_data($_POST['otherlocation']);
		} else if(!empty($_POST['location']) && $_POST['location'] != 'Other') {
			$location = escape_data($_POST['location']);
		} else {
			$location = FALSE;
			$message_client .= '<p style="color:red">Please enter your primary region.</p>';
		}
	
	
	
		if ($fn && $un && $e && $p) {
			
			$pass_orig = $p;
	
			$p = _hash($p);
			$user_type = 1;
			$user_ip = getRealIpAddr();
			$user_regdate = time();
	
			$query = "INSERT INTO forum_users (username, username_clean, user_email, user_password, user_type, user_ip, user_regdate) VALUES ('$un', '$un', '$e', '$p', '$user_type', '$user_ip', '$user_regdate')";
			mysql_query($query);
			if(mysql_affected_rows() == 1) {
				// Register user...
				$user_id = mysql_insert_id();
	
				if(is_int($user_id)) {	
					// place firstname and lastname (profile vars) in agency_users
					$firstname = request_var('firstname', '', true);
					$lastname = request_var('lastname', '', true);
					$company = request_var('company', '', true);
					$profession = request_var('profession', '', true);
		
					$type = 'client';
					$registration_date = time();
		
					mysql_query("INSERT INTO agency_profiles (user_id, firstname, lastname, account_type, location, client_profession, client_company, registration_date) VALUES ('$user_id', '$firstname', '$lastname', '$type', '$location', '$profession', '$company', '$registration_date')");
					// create default lightbox
					$timecode = strtotime("NOW");
					$query = "INSERT INTO agency_lightbox (client_id, lightbox_name, lightbox_description, timecode) VALUES ('$user_id', 'my lightbox', 'This is your first lightbox', '$timecode')";
					mysql_query($query);
				}
	
	
				// SEND WELCOME EMAIL!
				$subject = 'Welcome to The Agency Online';
				$message = file_get_contents('./adminXYZ/email_templates/admin_client_welcome_inactive.txt');
				$message = str_replace("{USERNAME}", $un, $message);
				$message = str_replace("{PASSWORD}", $pass_orig, $message);
				// echo $message;
				$headers = 'From: info@theagencyonline.com' . "\r\n" .
					'Reply-To: info@theagencyonline.com' . "\r\n";
				
				mail($e, $subject, $message, $headers);		

				$_SESSION['user_id'] = $user_id;
				$url = 'myaccount.php';
				ob_end_clean(); // Delete the buffer.
				header("Location: $url");
				exit(); // Quit the script.
			} else {
				$message_client .= 'THERE WAS A PROBLEM CREATING YOUR ACCOUNT.  PLEASE BE SURE TO ENTER VALID INFORMATION INTO THE REGISTRATION FORM.  IF YOU CONTINUE TO EXPERIENCE PROBLEMS PLEASE CONTACT US.';
			}
		} else {
			$message_client .= '<p><font color="red">Something went wrong.  Sorry.</font></p>';
		}
	} else {
		$message_client .= '<p><font color="red">You did not pass reCaptcha verification.</font></p>';
	}
}
echo $message_client;
?>