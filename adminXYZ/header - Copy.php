<?php
// Start output buffering.
ob_start();
// Initialize session.
session_start();

/* ========= THIS IS TO SHOW ERROS FOR TESTING.  NEEDS A CODE BUT SHOULD STILL BE REMOVED WHEN NOT TESTING ======== */
if(isset($_GET['showerrors'])) {
	if($_GET['showerrors'] == 'rex39') {
		$_SESSION['showerrors'] = 'rex39';
	}
}
if(isset($_SESSION['showerrors'])) { // this should be removed when done testing
	if($_SESSION['showerrors'] == 'rex39') {
		error_reporting(E_ALL);
		ini_set('display_errors', '1');
	}
}
/* ============================================  END TESTING CODE ==================================================*/

if(!empty($_GET['killPEM'])) {
	$_SESSION['user_id'] = (int) $_SESSION['admin'];
}

include('../includes/vars.php');
include('../includes/mysql_connect.php');
include('../includes/agency_functions.php');
include('../forms/definitions.php');

// if(empty($_SESSION['superadmin'])) {
// 	$url = '../index.php';
// 	ob_end_clean(); // Delete the buffer.
// 	header("Location: $url");
// 	exit(); // Quit the script.
// }

$superadmin = true;

// determine if there are pending experience change requests:
$query = "SELECT user_id, firstname, lastname FROM agency_profiles WHERE experience<>exp_request AND exp_request IS NOT NULL";
$exp_result = mysql_query($query);
if(mysql_num_rows($exp_result) > 0) {
	$exp_highlight = true;
} else {
	$exp_highlight = false;
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Admin Area</title>
<link rel="stylesheet" type="text/css" href="adminstyles.css" />
</head>
<body>
<div id="wrapper" style="margin-left:20px">
	<div id="WholePage">
	<div id="Logo">
		<?php if (file_exists('../images/banner.jpg')) echo '<img src="../images/banner.jpg">'; else if (file_exists('../images/banner.gif')) echo '<img src="../images/banner.gif">'; ?></div>
		<div id="PageMiddle">
		  <div style="float:left">
			<div id="menu"  class="menu">
			  <a href="../home.php">VIEW SITE</a>
			  <a href="newpage.php">Add new Page</a>
			  <a href="pages.php">Manage Pages</a>
			  <a href="news.php">News</a>
			  <a href="blocks.php">Ads/Blocks</a>
			  <a href="testimonials.php">Testimonials</a>
			  <a href="members.php"<?php if($exp_highlight) echo ' style="color:red"'; ?>>Members</a>
			  <a href="mentors.php">Mentors</a>
			  <a href="discounts.php">Discount Codes</a>
			  <a href="payment.php">Payment</a>
			  <a href="index.php">Help</a>
			  <a href="../logout.php">Log Out</a>

			 </div>
			</div>
			<div id="main" class="main">
