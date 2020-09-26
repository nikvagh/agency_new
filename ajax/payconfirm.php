<?php
session_start();

include('../includes/mysql_connect.php');
include('../includes/agency_functions.php');
		
if(!empty($_GET['promocode'])) {
	$promocode = escape_data($_GET['promocode']);
	// FIRST check for "Discount" code
	$query = "SELECT * FROM agency_discounts WHERE discount_code='$promocode'";
	$result = mysql_query ($query);
	if ($row = mysql_fetch_array ($result, MYSQL_ASSOC)) {
		// this is a discount code
		$discount_type = $row['discount_type'];
		
		if($discount_type == '6weeksLA') { // check if area is set to LA
			$loggedin = (int)$_SESSION['user_id'];
			if(mysql_result(mysql_query("SELECT location FROM agency_profiles WHERE user_id='$loggedin'"), 0, 'location') == 'Los Angeles/Southern Cal.') {
				// their location is LA, so it's ok.  continue.
			} else {
				unset($discount_type);
				echo '<font color="red">The discount code you entered is not valid.  It only is valid for member who select "Los Angeles/Southern Cal." for their region.</font><br><br>';				
			}
		}
			
		
	} else {
		$query = "SELECT * FROM agency_mentors WHERE mentor_code='$promocode'";
		$result = mysql_query ($query);
		if ($row = mysql_fetch_array ($result, MYSQL_ASSOC)) {
			$mentorid = $row['mentor_id'];
		} else {
			echo '<font color="red">The discount code you entered is not valid.</font><br><br>';
		}

		if(!empty($mentorid)) { // if this user has a mentor, apply discount
			$agencydiscount = .1;
		}
	}
}
			
if($discount_type) {
	if($discount_type == '14daysfree') {
		echo '<span style="font-size:18px; font-weight:bold; color:#5a74CC">- Completely Free for 14 Days -</span><br /><br />
				<span style="font-size:13px; color:#5e5e6d">';
				
		$final_line = '<br>will be in 14 days, if you don\'t cancel<br>before that. You can cancel anytime.)</span><br />';
	
	} else if($discount_type == 'freemonthwithcc') {
		echo '<span style="font-size:18px; font-weight:bold; color:#5a74CC">- Completely Free for 30 Days -</span><br /><br />
				<span style="font-size:13px; color:#5e5e6d">';
				
		$final_line = '<br>will be in 30 days, if you don\'t cancel<br>before that. You can cancel anytime.)</span><br />';
	
	} else if($discount_type == '6weeksLA') {
		echo '<span style="font-size:18px; font-weight:bold; color:#5a74CC">- Completely Free for 45 Days -</span><br /><br />
				<span style="font-size:13px; color:#5e5e6d">';
				
		$final_line = '<br>will be in 45 days, if you don\'t cancel<br>before that. You can cancel anytime.)</span><br />';
	
	}
		switch($_GET['payterm']) {
		case '9.95:1:M':	// $9.95 per Month
			echo '(Your first monthly payment of $';
			echo number_format((9.95 - ($agencydiscount * 9.95)), 2);
			break;
		case '24.95:3:M': // $24.95 for 3 months
			echo '(Your first quarterly payment of $';
			echo number_format((24.95 - ($agencydiscount * 24.95)), 2);
			break;
		case '89.95:1:Y': // $89.95 per Year
			echo '(Your first yearly payment of $';
			echo number_format((89.95 - ($agencydiscount * 89.95)), 2);
			break;
		default:
			echo 'There has been an error calculating your monthly charge.';
			break;
	}
	echo $final_line;
} else {
	if($agencydiscount == .1) {
		echo '<span style="font-size:18px; font-weight:bold; color:#5a74CC">- You get 10% off! -</span><br /><br />';
	}

	echo '<span style="font-size:16px; font-weight:bold">' . $_GET['name'] . ', you will be charged $';
	switch($_GET['payterm']) {
		case '9.95:1:M':	// $9.95 per Month
			echo number_format((9.95 - ($agencydiscount * 9.95)), 2);
			break;
		case '24.95:3:M': // $24.95 for 3 months
			echo number_format((24.95 - ($agencydiscount * 24.95)), 2);
			break;
		case '89.95:1:Y': // $89.95 per Year
			echo number_format((89.95 - ($agencydiscount * 89.95)), 2);
			break;
		default:
			echo 'There has been an error calculating your monthly charge.';
			break;
	}
	echo '.</span><br>';
}


?>
<br>
<span style="font-size:14px; font-weight:bold; color:#5a74CC">Click 'submit' to get started today.</span><br><br><br>
<div align="center">
    <input style="font-weight:bold; background-color:#FFFFFF" type="submit" value="SUBMIT" name="submit" onClick="if(!termscheck.checked) { alert('You must read the Terms and Conditions before submitting this form'); return false; }" /> *
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<input style="font-weight:bold; background-color:#FFFFFF" type="button" value="CANCEL" onClick="document.getElementById('payconfirm').style.display='none';" />
	</div>
	<br><br>
<?php
mysql_close(); // Close the database connection.
?>
