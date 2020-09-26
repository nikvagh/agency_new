<?php
session_start();

include('../includes/mysql_connect.php');
include('../includes/vars.php');
include('../includes/agency_functions.php');

unset($loggedin);
if(!empty($_SESSION['user_id'])) { // check if user is logged in
	$loggedin = $_SESSION['user_id'];
}
	
if($loggedin && !empty($_GET['exp'])) { // check if user is logged in
	$exp = escape_data((int) $_GET['exp']);
	$query = "SELECT experience, exp_request, exp_request_date FROM agency_profiles WHERE user_id='$loggedin'";  // check to see if user can access message_id.
	$result = mysql_query ($query);
	if ($row = @mysql_fetch_array ($result, MYSQL_ASSOC)) {
		$experience = $row['experience'];
		$exp_request = $row['exp_request'];
		$exp_request_date = $row['exp_request_date'];
		
		$today = date('Y-m-d');
		// echo $today . ' - ' . strtotime($today);
		$date = strtotime($today) - (30*24*60*60);
		// echo ' - ' . $date . ' - ' . date('Y-m-d', $date) ;
		
		if($exp_request == $exp) {
			echo '<b>Your request has already been submitted and is pending review.</b>';
		} else if($date > strtotime($exp_request_date)) {
			// submit request
			@mysql_query("UPDATE agency_profiles SET exp_request='$exp', exp_request_date='$today' WHERE user_id='$loggedin'");
			if(mysql_affected_rows() == 1) {
				echo '<b>Your request has been submitted.  Thank you. We look forward to reviewing your profile.</b>';
			} else {
				echo '<b>There was a problem executing this action. #3</b>';
			}
		} else {
			echo '<b>It appears your last experience change request was within the last 30 days.  Please wait until 30 days have passed.</b>';
		}
		unset($row);
		unset($experience);
		unset($exp_request);
		unset($exp_request_date);
	} else {
		echo "<b>There was a problem executing this action. #2</b>";
	}
	unset($query);
	unset($result);
} else {
	echo "<b>There was a problem executing this action. #1</b>.";
}

mysql_close(); // Close the database connection.
?>
