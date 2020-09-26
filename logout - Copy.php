<?php
@include('./includes/header.php');
	if(!empty($_SESSION['user_id'])) {
		$uid = (int) $_SESSION['user_id'];
	}
	if(!empty($uid)) {
		$query = "DELETE FROM agency_rememberme WHERE user_id='$uid'";
		@mysql_query($query);
	}

mysql_close(); // Close the database connection.

// session_start();
$_SESSION = array(); // Destroy the variables.
$set_time = time() - 31536000;
setcookie('agency_arm', '', $set_time);
session_destroy(); // Destroy the session itself.
setcookie (session_name(), '', time()-300, '/', '', 0); // Destroy the cookie.
$url = "home.php";
// ob_end_clean(); // Delete the buffer.
header("Location: $url");
exit(); // Quit the script.
?>
