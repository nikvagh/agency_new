<?php
session_start();

include('../includes/mysql_connect.php');
include('../includes/vars.php');
include('../includes/agency_functions.php');

echo '<div id="invitebox" align="center" style="clear:both; padding-top:20px">';
if(is_active()) { // check if user is logged in
	unset($loggedin);
	if(is_active()) { // check if user is logged in
		$loggedin = $_SESSION['user_id'];
	}
	// get name
	$sql3 = "SELECT firstname FROM agency_profiles WHERE user_id='$loggedin'";
	$result3=mysql_query($sql3);
	if($row3 = sql_fetchrow($result3)) {
		$name = $row3['firstname'] . ' ' . $row3['lastname'];
	}	
	
	$subject = $name . 'has invited you to join The Agency Online';
	
	if(!empty($_POST['invitemessage']) && !empty($_POST['email'])) {
		$message = $_POST['invitemessage'];
		$email = $_POST['email'];
		$from = 'info@theagencyonline.com';
		if(mail($email, $subject, $message, "From: $from")) {
			echo '<b>THANK YOU.</b>';
		} else {
			echo '<b>There was a problem sending the email.  Please try again and make sure all email addresses are valid.</b>';
		}
	} else {
?>
    <form action="javascript:void" name="invite" id="invite" method="post">
	Enter your friend's email address<br>
	<input type="text" name="email" size="70">
	<br><br>
	Subject: <b><?php echo $subject; ?></b><br /><br />
	Enter your message:<br />
    <textarea name="invitemessage" style="font-size:10px" rows="5" cols="80">Check out http://www.theagencyonline.com.  It's been great for me!</textarea>
    <br /><br />
	<input type="button" value="Invite" onclick="submitform (document.getElementById('invite'),'ajax/invite_friend.php','invitebox',validatetask); return false;" />
    </form>
<?php

	}

} else {
	echo "You must be logged in and approved to use this feature.";
}

echo '</div>';

mysql_close(); // Close the database connection.
?>
