<?php
include('header.php');
?>

<div id="page-wrapper">
  	<div class="container-fluid">
    	<div class="well" id="main">

			<div class="adminheading">Manage Member</div><br />
			<?php

			if(is_admin() && !empty($_POST['member'])) {
				if(is_numeric($_POST['member'])) {
					$user_id = (int) $_POST['member'];
				} else {
					$username = escape_data($_POST['member']);
					// get user id
					$user_id = mysql_result(mysql_query("SELECT user_id FROM forum_users WHERE username='$username'"), 0, 'user_id');
				}

				
				if(!empty($_POST['edituser'])) {
					echo '<div align="center"><b>';
					// Check for a username.
					if (eregi ('^[[:alnum:]]{4,30}$', stripslashes(trim($_POST['username'])))) {
						$un = escape_data($_POST['username']);
						$sql = "SELECT user_id FROM forum_users WHERE username='$un' AND user_id<>'$user_id'";
						if(mysql_num_rows(mysql_query($sql)) != 0) {
							$un = FALSE;
							echo '<p><font color="red">The Username you selected has already been taken by another member.  Please select a different Username.</font></p>';
						} else { // update username
							$query = "UPDATE forum_users SET username='$un', username_clean='$un' WHERE user_id='$user_id' LIMIT 1";
							mysql_query($query);
							if(mysql_affected_rows()) {
								echo '<p>Username updated</p>';
							}
						}
					} else {
						$un = FALSE;
						echo '<p><font color="red">Please enter a valid username (between 4 and 30 alphanumeric characters, no spaces).</font></p>';
					}
					
					// Check for an email address.
					if (eregi ('^[[:alnum:]][a-z0-9_\.\-]*@[a-z0-9\.\-]+\.[a-z]{2,4}$', stripslashes(trim($_POST['email'])))) {
						if($_POST['email'] == $_POST['confirmemail']) {
							$e = escape_data(strtolower($_POST['email']));
							$sql = "SELECT user_id FROM forum_users WHERE user_email='$e' AND user_id<>'$user_id'";
							if(mysql_num_rows(mysql_query($sql)) != 0) {
								$e = FALSE;
								echo '<p><font color="red">The Email you entered is already being used by another account.  Each account must have a unique email.</font></p>';
							} else { // update email
								$query = "UPDATE forum_users SET user_email='$e' WHERE user_id='$user_id' LIMIT 1";
								mysql_query($query);
								if(mysql_affected_rows()) {
									echo '<p>Email updated</p>';
								}
							}
						} else {
							$e = FALSE;
							echo  '<p><font color="red">Your confirmation email did not match.</font></p>';
						}
					} else {
						$e = FALSE;
						echo  '<p><font color="red">Please enter a valid email address.</font></p>';
					}	
				
					if($_POST['active'] == 0) {;
						$active = 0;
					} else {
						$active = 1;
					}
					
					$prev_active = mysql_result(mysql_query("SELECT user_type FROM forum_users WHERE user_id='$user_id'"), 0, 'user_type');

					$query = "UPDATE forum_users SET user_type='$active' WHERE user_id='$user_id' LIMIT 1";
					mysql_query($query);
					if(mysql_affected_rows()) {
						echo '<p>Active Status updated</p>';
						
						if($active == 0 && $prev_active == 1) {
							
							// SEND WELCOME EMAIL!
							$subject = 'Account activated';
							$message = file_get_contents('../adminXYZ/email_templates/admin_welcome_activated.txt');
							$message = str_replace("{USERNAME}", $un, $message);
							// echo $message;
							$headers = 'From: info@theagencyonline.com' . "\r\n" .
								'Reply-To: info@theagencyonline.com' . "\r\n";
							
							mail($e, $subject, $message, $headers);						
							
						}			
						
						
					}
					
				
					// Check for a password and match against the confirmed password.
					if(!empty($_POST['password'])) {
						if (eregi ('^[[:alnum:]]{6,20}$', stripslashes(trim($_POST['password']))) && eregi ('^[[:alnum:]]{6,20}$', stripslashes(trim($_POST['confirmpassword'])))) {
							$p = escape_data($_POST['password']);
							$p2 = escape_data($_POST['confirmpassword']);
							if($p != $p2) {
								$p = FALSE;
								echo  '<p><font color="red">Your Password entries did not match.  Please be sure both the Password and Confirm Password fields are identical.</font></p>';
							} else { // update password
								$password = _hash($p);
								if(!empty($password)) {
									$query = "UPDATE forum_users SET user_password='$password' WHERE user_id='$user_id' LIMIT 1";
									mysql_query($query);
									if(mysql_affected_rows()) {
										echo '<p>Password updated</p>';
									}
								}
							}
						} else {
							$p = FALSE;
							echo  '<p><font color="red">Please enter a valid password (between 6 and 20 alphanumeric characters)</font></p>';
						}
					}
					echo '</b></div>';
				}
				
				$query = "SELECT forum_users.username, forum_users.user_email, forum_users.user_type, agency_profiles.last_visit_IP FROM forum_users, agency_profiles WHERE forum_users.user_id=agency_profiles.user_id AND forum_users.user_id='$user_id'";  
				
				$result = @mysql_query ($query);
				if ($row = @mysql_fetch_array ($result, MYSQL_ASSOC)) {
					echo '<form action="useredit.php" method="post">
						<table bgcolor="#EEEEEE" border="1" cellspacing="2" align="center">
						<tr><td>Username:</td><td><input type="text" name="username" value="' . $row['username'] . '"></td></tr>
						<tr><td>Email:</td><td><input type="text" name="email" value="' . $row['user_email'] . '"></td></tr>
						<tr><td>Confirm Email:</td><td><input type="text" name="confirmemail" value="' . $row['user_email'] . '"></td></tr>
						<tr><td>Active:</td><td>Active:<input type="radio" name="active" value="0"' . (($row['user_type'] == 0) ?' checked' : '') . '>&nbsp;&nbsp;&nbsp;Not Active<input type="radio" name="active" value="1"' . (($row['user_type'] != 0) ? ' checked' :  '') . '></td></tr>
						<tr><td>Last Visit IP:</td><td>' . $row['last_visit_IP'] . '</td></tr>
						<tr><td>New Password:</td><td><input type="password" name="password"></td></tr>
						<tr><td>Confirm Password:</td><td><input type="password" name="confirmpassword"></td></tr>';

					echo '<tr><td colspan="2" style="padding:30px; text-align:center"><input type="hidden" name="member" value="' . $user_id . '"><input type="submit" name="edituser" value="Update User Information"></td></tr>
					</table></form>';
					
				
				} else {
					echo '<b>User Info Not Found.</b>';
				}
			} ?>

		</div>
	</div>
</div>

<?php include('footer_js.php'); ?>
<?php include('footer.php'); ?>