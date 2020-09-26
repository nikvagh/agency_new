<?php
include('header.php');
include('../forms/definitions.php');
?>
<script language="javascript" type="text/javascript">
function checkAll(current, filter) {
	var c = new Array();
	c = document.getElementsByTagName('input');
	for (var i = 0; i < c.length; i++) 	{
		if(filter) {
			if (c[i].type == 'checkbox' && c[i].name == filter+'[]') {
				c[i].checked = current.checked;
			}
		} else {
			if (c[i].type == 'checkbox') {
				c[i].checked = current.checked;
			}
		}
	}
}

</script>

<div align="center">
	<a href="userlist.php?filter=unapprovedtalent" class="viewbutton" style="text-decoration:none; ">Unapproved Talent</a>&nbsp;&nbsp;
	<a href="userlist.php?filter=unapprovedclients" class="viewbutton" style="text-decoration:none;">Unapproved Clients</a>&nbsp;&nbsp;
	<a href="userlist.php?filter=approvedclients" class="viewbutton" style="text-decoration:none; ">Approved Clients</a>&nbsp;&nbsp;
	<a href="userlist.php?filter=approvedunpaidtalent" class="viewbutton" style="text-decoration:none;">Approved UNPAID Talent</a>&nbsp;&nbsp;
	<a href="userlist.php?filter=cclist" class="viewbutton" style="text-decoration:none;">Unprocessed Credit Cards</a><br /><br />
	<a href="userlist.php?filter=unapprovedtalentwithcc" class="viewbutton" style="text-decoration:none;">Unapproved Talent who have entered Credit Card info</a>&nbsp;&nbsp;
	<a href="userlist.php?filter=unapprovedtalentwithoutcc" class="viewbutton" style="text-decoration:none;">Unapproved Talent who have NOT entered Credit Card info</a><br /><br />
	<a href="userlist.php?filter=unapprovedtalentwithccandpics" class="viewbutton" style="text-decoration:none; font-weight:bold">Unapproved Talent who have entered Unprocessed Credit Card info and have portfolio OR headshot</a><br /><br />
	<a href="userlist.php?filter=unapprovedtalentwithpaymentprocessed" class="viewbutton" style="text-decoration:none; font-weight:bold">Unapproved Talent who had their payment processed</a>&nbsp;&nbsp;
	<a href="userlist.php?filter=approvedpaidtalent" class="viewbutton" style="text-decoration:none;">Paid Approved Talent</a>&nbsp;&nbsp;
	<a href="userlist.php?filter=failedpayments" class="viewbutton" style="text-decoration:none;">Failed Payments</a><br /><br />
<a href="userlist.php?filter=allrequiredwithcc" class="viewbutton" style="text-decoration:none; font-weight:bold">Unapproved Talent who have entered unprocessed Credit Card info and all required info</a>
<br /><br />
<a href="userlist.php?filter=date200" class="viewbutton" style="text-decoration:none; font-weight:bold">Last 200 By Date</a>
<br /><br />
<a href="z_current.php" class="viewbutton" style="text-decoration:none; font-weight:bold; background-color:lightgreen; color:black" onclick="alert('This report takes about 20 seconds to generate.  Please click OK and be patient.')">Members with up to date payments</a>
<br />
<br />


<br /><br />
<?php

if(!empty($_POST['deleteid'])) {
	foreach($_POST['deleteid'] as $delete) {
		$query = "DELETE FROM forum_users WHERE user_id='$delete' LIMIT 1";
		$result = @mysql_query($query);
		$query = "DELETE FROM agency_profiles WHERE user_id='$delete' LIMIT 1";
		$result = @mysql_query($query);
	}
}
if(!empty($_POST['unpaidid'])) {
	$upid = (int) escape_data($_POST['unpaidid']);
	$query = "UPDATE agency_profiles SET payProcessed='0' WHERE user_id='$upid'";
	mysql_query($query);
	$query = "DELETE FROM agency_cc WHERE user_id='$upid'";
	mysql_query($query);
	if(mysql_affected_rows() > 0) {
		echo '<b>User ID: ' . $upid . ' has been reset to Unpaid.</b><br /><br />';
	}
}
if(!empty($_POST['settopaidid'])) {
	$pid = (int) escape_data($_POST['settopaidid']);
	$query = "UPDATE agency_profiles SET payProcessed='1' WHERE user_id='$pid'";
	mysql_query($query);
	if(mysql_affected_rows() > 0) {
		echo '<b>User ID: ' . $pid . ' has been set to Paid.</b><br /><br />';
	}
	mysql_query("DELETE FROM agency_cc WHERE user_id='$pid'");
	
	// if this is the first time member has paid, mark the date for tagging them as a new member
	$query = "SELECT payProcessedDate FROM agency_profiles WHERE user_id='$pid' AND payProcessedDate='2000-01-01 00:00:01'"; // 2000-01-01 00:00:01 is the default value, so if it's still set as this then it has not been set.
	$result = mysql_query($query);
	if(mysql_num_rows($result) == 1) {
		mysql_query("UPDATE agency_profiles SET payProcessedDate=NOW() WHERE user_id='$pid' LIMIT 1");
	}
}
if(!empty($_GET['unapproveid'])) {
	$uaid = (int) escape_data($_GET['unapproveid']);
	$query = "UPDATE forum_users SET user_type='1' WHERE user_id='$uaid'";
	mysql_query($query);
	if(mysql_affected_rows() > 0) {
		echo '<b>User ID: ' . $upid . ' has been reset to Unapproved.</b><br /><br />';
	}
}
if(!empty($_GET['unfailid'])) {
	$ufid = (int) escape_data($_GET['unfailid']);
	$query = "UPDATE agency_profiles SET payFailed='0' WHERE user_id='$ufid'";
	mysql_query($query); // set to unpaid
	if(mysql_affected_rows() > 0) {
		echo '<b>User ID: ' . $upid . ' has had the Failed Payment tag removed.</b><br /><br />';
	}
}	
if(!empty($_GET['approveid'])) {
	$uid = (int) escape_data($_GET['approveid']);
	$query = "UPDATE forum_users SET user_type='0' WHERE user_id='$uid'";
	mysql_query($query);
	if(mysql_affected_rows() > 0) {
		echo '<b>User ID: ' . $upid . ' has been Approved.</b><br /><br />';
		
		// SEND WELCOME EMAIL!
		$query = "SELECT user_email, username FROM forum_users WHERE user_id='$upid'";
		$result = mysql_query ($query);
		if ($row = mysql_fetch_array ($result, MYSQL_ASSOC)) {
			$e = $row['user_email'];
			$un = $row['username'];
		
			$subject = 'Account activated';
			$message = file_get_contents('../adminXYZ/email_templates/admin_welcome_activated.txt');
			$message = str_replace("{USERNAME}", $un, $message);
			// echo $message;
			$headers = 'From: info@theagencyonline.com' . "\r\n" .
				'Reply-To: info@theagencyonline.com' . "\r\n";
			
			mail($e, $subject, $message, $headers);		
		}

	}
}

if(!empty($_GET['remind'])) {
	$uid = (int) escape_data($_GET['remind']);
	$query = "SELECT user_email FROM forum_users WHERE user_id='$uid'";
	$result = mysql_query ($query);
	if ($row = mysql_fetch_array ($result, MYSQL_ASSOC)) {
		$to = $row['user_email'];
		$message = '<html>
			  <body>
			  ' .
			  mysql_result(mysql_query("SELECT varvalue FROM agency_vars WHERE varname='email_payment_failed'"), 0, 'varvalue') .
			  '
			  </body>
			  </html>';
		
		// $to = mysql_result(mysql_query("SELECT user_email FROM forum_users WHERE user_id='$user_id'"), 0, 'user_email');
		$from = "info@theagencyonline.com";
		$subject = "Your Pictures and Profile at The AGENCY";
	
		$headers  = "From: $from\r\n";
		$headers .= "Content-type: text/html\r\n";
	
		//options to send to cc+bcc
		//$headers .= "Cc: [email]email@email.com[/email]";
		//$headers .= "Bcc: [email]email@email.com[/email]";
		
		// now lets send the email.
		mail($to, $subject, $message, $headers);
		// mail("ungabo@yahoo.com", $subject, $message, $headers);
		
		echo '<b>User ID: ' . $uid . ' has been sent a reminder email.</b><br /><br />';
	}
}

// bulk reminders
if(!empty($_POST['remind']) && ($_GET['filter'] == 'failedpayments' || $_GET['filter'] == 'failedpaymentsunapproved')) {
	$remind_a = array();
	$remind_a = $_POST['remind'];
	
	foreach($remind_a as $uid) {
		$uid = (int) escape_data($uid);
		$query = "SELECT user_email FROM forum_users WHERE user_id='$uid'";
		$result = mysql_query ($query);
		if ($row = mysql_fetch_array ($result, MYSQL_ASSOC)) {
			$to = $row['user_email'];
			$message = '<html>
				  <body>
				  ' .
				  mysql_result(mysql_query("SELECT varvalue FROM agency_vars WHERE varname='email_payment_failed'"), 0, 'varvalue') .
				  '
				  </body>
				  </html>';
			
			// $to = mysql_result(mysql_query("SELECT user_email FROM forum_users WHERE user_id='$user_id'"), 0, 'user_email');
			$from = "info@theagencyonline.com";
			$subject = "Your Pictures and Profile at The AGENCY";
		
			$headers  = "From: $from\r\n";
			$headers .= "Content-type: text/html\r\n";
		
			//options to send to cc+bcc
			//$headers .= "Cc: [email]email@email.com[/email]";
			//$headers .= "Bcc: [email]email@email.com[/email]";
			
			// now lets send the email.
			mail($to, $subject, $message, $headers);
			// mail("ungabo@yahoo.com", $subject, $message, $headers);
			
			echo '<b>User ID: ' . $uid . ' has been sent a reminder email.</b><br /><br />';
		}
	}
}


// bulk unapprove
if(!empty($_POST['unapprove']) && ($_GET['filter'] == 'failedpayments' || $_GET['filter'] == 'failedpaymentsunapproved')) {
	$unapprove_a = array();
	$unapprove_a = $_POST['unapprove'];
	
	foreach($unapprove_a as $uaid) {
		$uaid = (int) escape_data($uaid);
		$query = "UPDATE forum_users SET user_type='1' WHERE user_id='$uaid'";
		mysql_query($query);
		if(mysql_affected_rows() > 0) {
			echo '<b>User ID: ' . $uaid . ' has been reset to Unapproved.</b><br /><br />';
		}
	}
}


if(!empty($_GET['process'])) { // process credit card
	$userid = (int) $_GET['process'];
	$query = "SELECT * FROM agency_cc WHERE user_id='$userid' ORDER BY cc_id DESC";
	$result = mysql_query ($query);
	if ($row = mysql_fetch_array ($result, MYSQL_ASSOC)) {
		$cc_id = $row['cc_id'];
		$BillFname = $row['firstname'];
		$BillLname = $row['lastname'];
		$BillStreet = $row['street1'];
		$BillStreet2 = $row['street2'];
		$BillCity = $row['city'];
		// $BillState = $row['state'];
		if(in_array($row['state'], $stateList['US'])) {
			$BillState = array_search($row['state'], $stateList['US']);
		} else {
			$BillState = $row['state'];
		}
		$BillZip = $row['zip'];
		$BillCountry = array_search($row['country'], $countryarray); 
		$CardType = $row['type'];
		$CardNumber = $row['number'];
		$CVV = $row['cvv'];
		$ExpMonth = $row['exp_month'];
		$ExpYear = $row['exp_year'];
		$pay_term = $row['pay_term'];
		$promocode = $row['promocode'];
		$agencydiscount = 0; // default

		
		// if there's a promo code, check it for processing
		if(!empty($promocode)) {
			// FIRST check for "Discount" code
			$query = "SELECT * FROM agency_discounts WHERE discount_code='$promocode'";
			$result = mysql_query ($query);
			if ($row = mysql_fetch_array ($result, MYSQL_ASSOC)) {
				// this is a discount code
				$discount_type = $row['discount_type'];
				$query = "UPDATE agency_profiles SET discount_code='$promocode' WHERE user_id='$userid'";
				mysql_query($query);
			} else {
				// CHECK FOR "MENTOR" PROMO CODE
				$query = "SELECT mentor_id FROM agency_profiles WHERE user_id='$userid'";
				$result = mysql_query ($query);
				if ($row = mysql_fetch_array ($result, MYSQL_ASSOC)) {
					$mentorid = trim($row['mentor_id']);
					if(empty($mentorid)) { // if the mentor has not already been set previously, then assign
						$query = "SELECT * FROM agency_mentors WHERE mentor_code='$promocode'";
						$result = mysql_query ($query);
						if ($row = mysql_fetch_array ($result, MYSQL_ASSOC)) {
							$mentorid = $row['mentor_id'];
							$query = "UPDATE agency_profiles SET mentor_id='$mentorid' WHERE user_id='$userid'";
							mysql_query($query);
						}
					}
				}
				if(!empty($mentorid)) { // if this user has a mentor, apply discount
					$agencydiscount = .1;
				}
			}
		}
		
		switch($pay_term) {
			case '9.95:1:M':	// $9.95 per Month
				$periodcode = 1;
				break;
			case '24.95:3:M': // $24.95 for 3 months
				$periodcode = 2;
				break;
			case '89.95:1:Y': // $89.95 per Year
				$periodcode = 3;
				break;
			default:
				$periodcode = 1;
				break;
		}
	}
	
	echo 'Firstname: ' . $BillFname . '<br />';
	echo 'Lastname: ' . $BillLname . '<br />';
	echo 'Street: ' . $BillStreet . '<br />';
	echo 'City: ' . $BillCity . '<br />';
	echo 'State: ' . $BillState . '<br />';
	echo 'Zip: ' . $BillZip . '<br />';
	echo 'Country: ' . $BillCountry . '<br />';
	echo 'Card Type: ' . $CardType . '<br />';
	echo 'Card Number: ' . $CardNumber . '<br />';
	echo 'CVV: ' . $CVV . '<br />';
	echo 'Exp Month: ' . $ExpMonth . '<br />';
	echo 'Exp Year: ' . $ExpYear . '<br />';
	echo 'Payment Terms: ' . $pay_term . '<br />';
	echo 'Discount Code: ' . $promocode . '<br />';
	echo 'Discount: ' . $agencydiscount * 100 . '%<br />';

	if(!empty($BillFname) && !empty($BillLname) && !empty($BillStreet) && !empty($BillCity) && !empty($BillState) && !empty($BillZip) && !empty($CardType) && !empty($CardNumber) && !empty($CVV) && !empty($ExpMonth) && !empty($ExpYear) && !empty($periodcode)) {

		// process order
		$customer_first_name = $BillFname;
		$customer_last_name = $BillLname;
		$customer_credit_card_type = $CardType;
		// remove spaces from credit card
		$CardNumber = ereg_replace( '[^0-9]+', '', $CardNumber );
		$CardNumber = $CardNumber;
		$customer_credit_card_number = $CardNumber;
		$cc_expiration_month = $ExpMonth;
		$cc_expiration_year = $ExpYear;
		$cc_cvv2_number = $CVV;
		$customer_address1 = $BillStreet;
		$customer_address2 = $BillStreet2;
		$customer_city = $BillCity;
		$customer_state = $BillState;
		$customer_zip = $BillZip;
		$customer_country = $BillCountry;
		
		$superadmin = true;
		include('payment/CreateRecurringPaymentsProfile.php'); // this processes the payment
	} else {
		echo '<b>Some information was missing</b><br /><br /><br /><br />';
	}
}

unset($query);
if(isset($_GET['filter'])) {
	
	if(isset($_REQUEST['region'])) {
		$region = escape_data($_REQUEST['region']);
		if($region == 'Other') {
			$sqlregion = "AND agency_profiles.location NOT IN ('" . implode("', '", $locationarray) . "')";
		} else {
			$sqlregion = "AND agency_profiles.location='$region'";
		}
	} else {
		$sqlregion = '';
	}
	
	switch($_GET['filter']) {
		case 'unapprovedtalent':
			echo '<div class="adminheading">Unapproved Talent</div><br />';
			$query = "SELECT forum_users.user_id, forum_users.user_email, forum_users.username, agency_profiles.firstname, agency_profiles.lastname, agency_profiles.location, agency_profiles.register_browser FROM forum_users, agency_profiles WHERE agency_profiles.user_id=forum_users.user_id AND forum_users.user_type='1' AND agency_profiles.account_type='talent' $sqlregion ORDER BY forum_users.user_id DESC";
			break;
		case 'unapprovedclients':
			echo '<div class="adminheading">Unapproved Clients</div><br />';
			$query = "SELECT forum_users.user_id, forum_users.user_email, forum_users.username, agency_profiles.firstname, agency_profiles.lastname, agency_profiles.location, agency_profiles.register_browser FROM forum_users, agency_profiles WHERE agency_profiles.user_id=forum_users.user_id AND forum_users.user_type='1' AND agency_profiles.account_type='client' $sqlregion ORDER BY forum_users.user_id DESC";
			break;
		case 'approvedclients':
			echo '<div class="adminheading">Approved Clients</div><br />';
			$query = "SELECT forum_users.user_id, forum_users.user_email, forum_users.username, agency_profiles.firstname, agency_profiles.lastname, agency_profiles.location, agency_profiles.register_browser FROM forum_users, agency_profiles WHERE agency_profiles.user_id=forum_users.user_id AND forum_users.user_type='0' AND agency_profiles.account_type='client' $sqlregion ORDER BY forum_users.user_id DESC";
			break;
		case 'approvedunpaidtalent':
			echo '<div class="adminheading">Approved UNPAID Talent</div><br />';
			$query = "SELECT forum_users.user_id, forum_users.user_email, forum_users.username, agency_profiles.firstname, agency_profiles.lastname, agency_profiles.location, agency_profiles.register_browser FROM forum_users, agency_profiles WHERE agency_profiles.user_id=forum_users.user_id AND agency_profiles.payProcessed='0' AND forum_users.user_type='0' AND agency_profiles.account_type='talent' $sqlregion ORDER BY forum_users.user_id DESC";
			break;
		case 'cclist':
			echo '<div class="adminheading">All Members with unprocessed Credit Card info</div><br />';
			$query = "SELECT forum_users.user_id, forum_users.user_email, forum_users.username, agency_profiles.firstname, agency_profiles.lastname, agency_profiles.location, agency_profiles.register_browser, agency_cc.number, agency_cc.promocode FROM forum_users, agency_profiles, agency_cc WHERE agency_profiles.payProcessed='0' AND agency_profiles.user_id=forum_users.user_id AND agency_cc.user_id=forum_users.user_id $sqlregion ORDER BY forum_users.user_id DESC";
			break;
		case 'unapprovedtalentwithcc':
			echo '<div class="adminheading">Unapproved Talent who have entered Credit Card info (unprocessed)</div><br />';
			$query = "SELECT forum_users.user_id, forum_users.user_email, forum_users.username, agency_profiles.firstname, agency_profiles.lastname, agency_profiles.location, agency_profiles.register_browser, agency_cc.number, agency_cc.promocode FROM forum_users, agency_profiles, agency_cc WHERE agency_profiles.payProcessed='0' AND agency_profiles.user_id=forum_users.user_id AND agency_cc.user_id=forum_users.user_id AND forum_users.user_type='1' AND agency_profiles.account_type='talent' $sqlregion ORDER BY forum_users.user_id DESC";
			break;
		case 'unapprovedtalentwithoutcc':
			echo '<div class="adminheading">Unapproved Talent who have NOT entered Credit Card info</div><br />';
			$query = "SELECT forum_users.user_id, forum_users.user_email, forum_users.username, agency_profiles.firstname, agency_profiles.lastname, agency_profiles.location, agency_profiles.register_browser FROM forum_users, agency_profiles WHERE agency_profiles.user_id=forum_users.user_id AND forum_users.user_type='1' AND forum_users.user_id NOT IN (SELECT user_id FROM agency_cc) AND agency_profiles.account_type='talent' $sqlregion ORDER BY forum_users.user_id DESC";
			break;
		case 'unapprovedtalentwithccandpics':
			echo '<div class="adminheading">Unapproved Talent who have entered Unprocessed Credit Card info and have at least 1 photo in Gallery OR a Headshot</div><br />';
			$query = "SELECT DISTINCT forum_users.user_id, forum_users.user_email, forum_users.username, agency_profiles.firstname, agency_profiles.lastname, agency_profiles.location, agency_profiles.register_browser, agency_cc.number, agency_cc.promocode FROM forum_users, agency_profiles, agency_cc WHERE agency_profiles.payProcessed='0' AND  agency_profiles.user_id=forum_users.user_id AND agency_cc.user_id=forum_users.user_id AND forum_users.user_type='1' AND agency_profiles.account_type='talent' AND (forum_users.user_id IN (SELECT user_id FROM agency_photos) OR agency_profiles.headshot IS NOT NULL) $sqlregion ORDER BY forum_users.user_id DESC";
			break;
			
			
			
			
			
		case 'allrequiredwithcc':
			echo '<div class="adminheading">Unapproved Talent who have entered unprocessed Credit Card info and all required info</div><br />';
			$query = "SELECT DISTINCT forum_users.user_id, forum_users.user_email, forum_users.username, agency_profiles.firstname, agency_profiles.lastname, agency_profiles.location, agency_profiles.register_browser, agency_cc.number, agency_cc.promocode FROM forum_users, agency_profiles, agency_cc WHERE agency_profiles.payProcessed='0' AND agency_profiles.user_id=forum_users.user_id AND agency_cc.user_id=forum_users.user_id AND forum_users.user_type='1' AND agency_profiles.account_type='talent' AND agency_profiles.firstname>'' AND agency_profiles.phone>'' AND agency_profiles.gender>'' AND agency_profiles.birthdate>'' AND agency_profiles.eyes>'' AND agency_profiles.hair>'' AND agency_profiles.height>'' AND agency_profiles.waist>'' AND agency_profiles.weight>'' AND agency_profiles.shoe>'' AND forum_users.user_id IN (SELECT user_id FROM agency_profile_unions) AND forum_users.user_id IN (SELECT user_id FROM agency_profile_ethnicities) AND forum_users.user_id IN (SELECT user_id FROM agency_profile_categories) AND forum_users.user_id IN (SELECT user_id FROM agency_profile_voices) AND forum_users.user_id IN (SELECT user_id FROM agency_photos) $sqlregion ORDER BY forum_users.user_id DESC";
			break;	
			
			
			
			
				
		case 'unapprovedtalentwithpaymentprocessed':
			echo '<div class="adminheading">Unapproved Talent who had their payment processed -- <font color="red">anyone in this list should have their account approved immediately!</font></div><br />';
			// $query = "SELECT forum_users.user_id, forum_users.user_email, forum_users.username, agency_profiles.firstname, agency_profiles.lastname, agency_profiles.register_browser, agency_cc.number, agency_cc.promocode FROM forum_users, agency_profiles, agency_cc WHERE agency_profiles.payProcessed='1' AND agency_profiles.user_id=forum_users.user_id AND agency_cc.user_id=forum_users.user_id AND forum_users.user_type='1' AND agency_profiles.account_type='talent' AND forum_users.user_id IN (SELECT user_id FROM agency_photos) ORDER BY forum_users.user_id DESC";
			$query = "SELECT forum_users.user_id, forum_users.user_email, forum_users.username, agency_profiles.firstname, agency_profiles.lastname, agency_profiles.location, agency_profiles.register_browser FROM forum_users, agency_profiles WHERE agency_profiles.payProcessed='1' AND agency_profiles.user_id=forum_users.user_id AND forum_users.user_type='1' AND agency_profiles.account_type='talent' $sqlregion ORDER BY forum_users.user_id DESC";
			break;
		case 'approvedpaidtalent':
			echo '<div class="adminheading">Paid Approved Talent</div><br />';
			$query = "SELECT forum_users.user_id, forum_users.user_email, forum_users.username, agency_profiles.firstname, agency_profiles.lastname, agency_profiles.location, agency_profiles.register_browser FROM forum_users, agency_profiles WHERE agency_profiles.user_id=forum_users.user_id AND agency_profiles.payProcessed='1' AND forum_users.user_type='0' AND agency_profiles.account_type='talent' $sqlregion ORDER BY forum_users.user_id ASC";
			break;
		case 'referred':
			echo '<div class="adminheading">Referred Talent (any account status)</div><br />';
			$query = "SELECT forum_users.user_id, forum_users.user_email, forum_users.username, agency_profiles.mentor_id, agency_profiles.firstname, agency_profiles.lastname, agency_profiles.location, agency_profiles.register_browser FROM forum_users, agency_profiles WHERE agency_profiles.user_id=forum_users.user_id AND agency_profiles.account_type='talent' AND agency_profiles.mentor_id IS NOT NULL $sqlregion ORDER BY forum_users.user_id ASC";
			break;
		case 'discounts':
			echo '<div class="adminheading">Talent using Discount Code (any account status)</div><br />';
			$query = "SELECT forum_users.user_id, forum_users.user_email, forum_users.username, agency_profiles.discount_code, agency_profiles.firstname, agency_profiles.lastname, agency_profiles.location, agency_profiles.register_browser FROM forum_users, agency_profiles WHERE agency_profiles.user_id=forum_users.user_id AND agency_profiles.account_type='talent' AND agency_profiles.discount_code IS NOT NULL $sqlregion ORDER BY forum_users.user_id ASC";
			break;
		case 'failedpayments':
			echo '<div class="adminheading">Failed Payments (users are still Approved)<br /><br />';
			echo '	<a href="userlist.php?filter=failedpaymentsunapproved" class="viewbutton" style="text-decoration:none;">View Unapproved Failed Payments</a><br /><br /></div>';
			$query = "SELECT DISTINCT forum_users.user_id, forum_users.user_email, forum_users.username, agency_profiles.firstname, agency_profiles.lastname, agency_profiles.location, agency_profiles.register_browser, agency_profiles.payFailedDate FROM forum_users, agency_profiles WHERE agency_profiles.payFailed='1' AND agency_profiles.user_id=forum_users.user_id AND agency_profiles.account_type='talent' AND forum_users.user_type='0' $sqlregion ORDER BY forum_users.user_id DESC";
			break;
		case 'failedpaymentsunapproved':
			echo '<div class="adminheading">Failed Payments (Unapproved users)<br /><br />';
			echo '	<a href="userlist.php?filter=failedpayments" class="viewbutton" style="text-decoration:none;">View Approved Failed Payments</a><br /><br /></div>';
			$query = "SELECT DISTINCT forum_users.user_id, forum_users.user_email, forum_users.username, agency_profiles.firstname, agency_profiles.lastname, agency_profiles.location, agency_profiles.register_browser, agency_profiles.payFailedDate FROM forum_users, agency_profiles WHERE agency_profiles.payFailed='1' AND agency_profiles.user_id=forum_users.user_id AND agency_profiles.account_type='talent' AND forum_users.user_type='1' $sqlregion ORDER BY forum_users.user_id DESC";
			break;
			
			case 'date200':
			echo '<div class="adminheading">Last 200 Signups</div><br />';
			$query = "SELECT forum_users.user_id, forum_users.user_email, forum_users.username, forum_users.user_regdate, forum_users.user_type, agency_profiles.firstname, agency_profiles.lastname, agency_profiles.location, agency_profiles.register_browser, agency_profiles.account_type FROM forum_users, agency_profiles WHERE agency_profiles.user_id=forum_users.user_id ORDER BY forum_users.user_regdate DESC LIMIT 200";
			
			
	}
	
	if(!empty($_GET['filter'])) {
		echo '<a href="export.php?filter=' . $_GET['filter'] . '" target="_blank">excel export</a> <br /><small>(note: Excel Export is not set up to filter by Region)</small><br /><br />';
	}

?>
	<b>REGION:</b> <select onchange="document.location='userlist.php?filter=<?php echo $_GET['filter']; ?>'+this.value">
    	<option value="">-- ALL --</option>
<?php
  		foreach($locationarray as $loc) {
			$locvalue = '&region=' . $loc;
			echo '<option value="' . $locvalue  . '"';
			if(!empty($_REQUEST['region'])) {
				if($_REQUEST['region'] == $loc) {
					echo ' SELECTED ';
				}
			}		
			echo '>' . $loc .'</option>';
		}
?>   
        <option value="&region=Other"
        <?php
			if(!empty($_REQUEST['region'])) {
				if($_REQUEST['region'] == 'Other') {
					echo ' SELECTED ';
				}
			}		
		?>>Other</option>
    </select>
		<br /><br />

		
		
<?php		
				
	$emaillistnames = '';
	$emaillist = '';
	$result = @mysql_query ($query);
	if (@mysql_affected_rows() > 0) { // If there are projects.
		$counter = 0; // count how many users there are
		
		if($_GET['filter'] == 'failedpayments' || $_GET['filter'] == 'failedpaymentsunapproved' || $_GET['filter'] == 'unapprovedclients') {
			echo '<form action="userlist.php?filter=' . $_GET['filter'] . '" method="post">';	
		}
		
		// echo '<p style="font-size:20px"><b>OLIVER: I AM MAKING CHANGES TO THIS PAGE SO PLEASE DO NOT USE FOR THE MOMENT.  THANKS!  :)</b></p>';
		
		echo '<table bgcolor="#EEEEEE" cellpadding="4" border="1" cellspacing="0" align="center"><tr><td width="60"><b>User Id</b></td>'.
			'<td width="120"><b>First Name</b></td><td width="120">' .
			'<b>Last Name</b></td><td width="120"><b>Username</b></td><td width="180">' . 
			'<b>Email</b></td>';
			
		if(!empty($_REQUEST['region'])) {
			if($_REQUEST['region'] == 'Other') {
				echo '<td width="80"><b>Location</b></td>';
			}
		}
		if($_GET['filter'] == 'failedpayments' || $_GET['filter'] == 'failedpaymentsunapproved') {
			echo '<td width="180"><input type="checkbox" onClick="checkAll(this, \'remind\')"> remind<br>
					<input type="checkbox" onClick="checkAll(this, \'unapprove\')"> unapprove</td>';
		} else {
			echo '<td></td>';
		}
		if($_GET['filter'] == 'unapprovedclients') {
			echo '<td><b>DEL</b></td>';
		}
		
		echo '</tr>';
		$browsers = '';
		while ($row = mysql_fetch_array ($result, MYSQL_ASSOC)) {
			$proceed = true; // this is to check if there are 4 images minimum when allrequiredwithcc
			$userid = $row['user_id'];
			if($_GET['filter'] == 'allrequiredwithcc' && (mysql_result(mysql_query("SELECT COUNT(*) as 'Num' FROM agency_photos WHERE user_id='$userid'"),0) < 4)) {
				// $proceed = false;
			}
				
			if($proceed) {
				$username = $row['username'];
				$firstname = $row['firstname'];
				$lastname = $row['lastname'];
				$location = $row['location'];
				$email = $row['user_email'];
				$emaillistnames .= '"' . $row['firstname'] . ' ' . $row['lastname'] . '" <' . $row['user_email'] . ">, ";
				$emaillist .= $row['user_email'] . ", ";
	
				$refercode = '&nbsp;';
				$refname = '&nbsp;';
				$browsers .= '<div id="browser' . $userid . '">' . nl2br($row['register_browser']) . '</div>';
				
				echo '<tr><td onmouseover="showbrowser(\'browser' . $userid . '\')" onmouseout="document.getElementById(\'browserdiv\').style.display=\'none\'"><a href="../profile.php?u=' . $userid . '" target="_blank">' . $userid . '</a></td><td>' . $firstname . '</td><td>' . $lastname . '</td><td>' . $username .
					'</td><td><a href="mailto:' . $email . '">' . $email . '</a></td>';
					
				if(!empty($_REQUEST['region'])) {
					if($_REQUEST['region'] == 'Other') {
						echo '<td>' . $location . '</td>';
					}
				}	
					
					
					
					
				echo '<td>';
				if(isset($row['number'])) {
					echo '*' . substr($row['number'], -4, 4);
					if(isset($_GET['filter'])) {
						if($_GET['filter'] == 'unapprovedtalentwithccandpics' || $_GET['filter'] == 'cclist' || $_GET['filter'] == 'allrequiredwithcc') {
							echo '(<a href="userlist.php?filter=' . $_GET['filter'] . '&process=' . $userid . '" onclick="return confirm(\'You are about to initiate the payment subscription for this member.  Please confirm.\')">process</a>)';
							if(!empty($row['promocode'])) {
								echo ' [' . $row['promocode'] . ']';
							}
						}
					}
				} else if($_GET['filter'] == 'approvedunpaidtalent') {
					echo '<a href="userlist.php?filter=' . $_GET['filter'] . '&unapproveid=' . $userid . '" onclick="return confirm(\'You are about to UNAPPROVE this member.  Please confirm.\')">UNapprove</a>';
				} else if($_GET['filter'] == 'unapprovedtalentwithpaymentprocessed') {
					echo '<a href="userlist.php?filter=' . $_GET['filter'] . '&approveid=' . $userid . '" onclick="return confirm(\'You are about to APPROVE this member.  Please confirm.\')">Approve</a>';			
				} else if($_GET['filter'] == 'referred') {
					echo '<a href="mentor_view.php?id=' . $row['mentor_id'] . '"><b>mentor</b></a>';
				} else if($_GET['filter'] == 'discounts') {
					$dcode = $row['discount_code'];
					$discountid = mysql_result(mysql_query("SELECT discount_id FROM agency_discounts WHERE discount_code='$dcode'"), 0, 'discount_id');
					echo '<a href="discount_view.php?id=' . $discountid . '"><b>' . $row['discount_code'] . '</b></a>';
				} else if($_GET['filter'] == 'failedpayments' || $_GET['filter'] == 'failedpaymentsunapproved') {
					echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="userlist.php?filter=' . $_GET['filter'] . '&unfailid=' . $userid . '" onclick="return confirm(\'You are about to remove the Failed Payment status from this user.  Please confirm.\')"><b>UN-fail</b></a><br /><input type="checkbox" name="remind[]" value="' . $userid  . '"><a href="userlist.php?filter=' . $_GET['filter'] . '&remind=' . $userid . '"><b>remind</b></a>';
					if($_GET['filter'] == 'failedpayments') {
						echo '<br /><input type="checkbox" name="unapprove[]" value="' . $userid  . '"> <a href="userlist.php?filter=' . $_GET['filter'] . '&unapproveid=' . $userid . '" onclick="return confirm(\'You are about to UNAPPROVE this member.  Please confirm.\')"><b>UNapprove</b></a>';			
					}
					if(!empty($row['payFailedDate'])) {
						echo '<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;[' . date('m/d/y', strtotime($row['payFailedDate'])) . ']';
					}
				} else {
					echo '<a href="../profile.php?u=' . $userid . '" target="_blank"><b>view</b></a>';
					if($_GET['filter'] == 'date200') {
						if($row['user_type'] == 0) echo ' [approved]';
						if($row['user_type'] == 1) echo ' [not approved]';
						echo ' [' . $row['account_type'] . '] [<strong>' . date('m/d/y', $row['user_regdate']) . '</strong>]';
					}
				}	
					
				echo '</td>';
				if($_GET['filter'] == 'unapprovedclients') {
					echo '<td><input type="checkbox" name="deleteid[]" value="' . $userid . '"></td>';
				}
				
				
				echo '</tr>'; 
				$counter++;
			}
		}
		echo '</table>';
		if($_GET['filter'] == 'failedpayments' || $_GET['filter'] == 'failedpaymentsunapproved') {
			echo '<br /><input type="submit" value="Remind/Unapprove Selected"></form>';
		} else if($_GET['filter'] == 'unapprovedclients') {
			echo '<br /><input type="submit" value="Delete Selected" onclick="return confirm(\'Permanently delete checked Clients?\')"></form>';
		}
		echo '</b><br />Accounts: ' . $counter;
	} else {
		echo '<b>No accounts in this category.</b>';
	}
}

echo '</div>';
?>
<br />
<br />
<br />
<div class="adminheading">Email List</div><br />
copy and paste to your prefered email program<br />
<textarea id="emails" cols="70" rows="15" onFocus="this.select()">
<?php
echo $emaillist;
?>
</textarea>
<br />
<br />
<br />
<br />
<br />
With Names:<br />
<textarea cols="70" rows="15" onFocus="this.select()">
<?php
echo $emaillistnames;
?>
</textarea>
<br />
<br />
<br />
<br /><br />
<form action="userlist.php" method="post" style="border:1px solid gray">
Revert user to UNPAID<br>
<br>
User ID: <input type="text" name="unpaidid">&nbsp;&nbsp;<input type="submit" name="submit" onClick="return confirm('You are about to revert this account to Unpaid Status.  Only do this if you know what you\'re doing.  Proceed?')">
<br>
<br>
<br>
</form>
<br><br><br>
<form action="userlist.php" method="post" style="border:1px solid gray">
Set user to PAID
<br><br>
(this should only be used if there was a mistake.  Users will be automatically set to Paid when the payment is processed.  If you set a used to Paid that has not had the Payment processed through their account, if payment fails, the code CANNOT know that this user should be set to Unpaid.)<br>
<br><br>
User ID: <input type="text" name="settopaidid">&nbsp;&nbsp;<input type="submit" name="submit" onClick="return confirm('You are about to set this account to Paid Status.  Only do this if you know what you\'re doing.  Proceed?')">
<br>
<br>
<br>
</form>
<br><br><br><br>
</div>
<script type="text/javascript" language="javascript">

function showbrowser(divwithcontent) {
   	document.getElementById('browserdiv').innerHTML = document.getElementById(divwithcontent).innerHTML;
	document.getElementById('browserdiv').style.display='';
}

// Simple follow the mouse script

var divName = 'browserdiv'; // div that is to follow the mouse
                       // (must be position:absolute)
var offX = 20;          // X offset from mouse position
var offY = -90;          // Y offset from mouse position

function mouseX(evt) {
	if (!evt) 
		evt = window.event; 
	
	if (evt.pageX) 
		return evt.pageX; 
	else if (evt.clientX)
		return evt.clientX + (document.documentElement.scrollLeft ?  document.documentElement.scrollLeft : document.body.scrollLeft);
	else return 0;
}
function mouseY(evt) {
	if (!evt) 
		evt = window.event; 
	
	if (evt.pageY) 
		return evt.pageY; 
	else if (evt.clientY)
		return evt.clientY - (document.documentElement.scrollTop ? document.documentElement.scrollTop : document.body.scrollTop);
	else return 0;
}

function follow(evt) {
	if (document.getElementById) {
		var obj = document.getElementById(divName).style; 
		obj.visibility = 'visible';
		obj.left = (parseInt(mouseX(evt))+offX) + 'px';
		obj.top = (parseInt(mouseY(evt))+offY) + 'px';
		// alert(mouseY(evt));
	}
}

document.onmousemove = follow;

</script>
<div id="browserdiv" style="position:absolute; margin-top:-150px; left:-160px; display:none; z-index:5; background-color:#FFFFCC"></div>
<?php
echo '<div style="display:none">' . $browsers . '</div>';
include('footer.php');
?>