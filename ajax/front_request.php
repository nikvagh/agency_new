<?php
session_start();
include('../includes/mysql_connect.php');
include('../includes/vars.php');
include('../includes/agency_functions.php');

if(isset($_POST['name'])) {
	
	if($_POST['name'] == 'user_email_unique_insert'){
		// echo "<pre>";print_r($_POST);
		$total_results = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM forum_users WHERE user_email = '".$_POST['email']."' "),0);
		if($total_results > 0) {
			echo json_encode(false);
		}else{
			echo json_encode(true);
		}
	}

	if($_POST['name'] == "user_username_unique_insert"){
		// echo "<pre>";print_r($_POST);
		$total_results = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM forum_users WHERE username = '".$_POST['username']."' "),0);
		if($total_results > 0) {
			echo json_encode(false);
		}else{
			echo json_encode(true);
		}
	}

}

if(isset($_GET['name'])) {
	if($_GET['name'] == 'view_msg'){

	}
}

mysql_close(); // Close the database connection.
?>
