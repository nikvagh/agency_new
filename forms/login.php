<?php
@include('./includes/header.php');

if(is_banned()) {
	echo '<p><font color="red">Your IP address has been blocked from creating new accounts.  If you feel this is in error please contact us to remove the block.</font></p>';	
} else if(!empty($_POST['login'])) {
	$fail = true;
	$agency_un = escape_data($_POST['username']);
	$agency_pw = escape_data($_POST['password']);
	$sql = "SELECT user_id, user_password FROM forum_users WHERE username='$agency_un'";
	$result = mysql_query($sql);
	if($row = @mysql_fetch_array ($result, MYSQL_ASSOC)) {
		// create session variable with userid and redirect
		 $password = $row['user_password'];
		 if(_check_hash($agency_pw, $password)) {

			$agency_uid = $row['user_id'];
		   // session_start();
			$_SESSION['user_id'] = $agency_uid;

			// create "remember me" cookie
			if(isset($_POST['rememberme'])) {
				// remember the session	
				// create a session id
				$salt = '8IO9H3I22x' . rand(10000,99999);
				$session_id = md5($agency_un . $agency_pw . $salt);
				$uid = $agency_uid;
				$sql2 = "DELETE FROM agency_rememberme WHERE user_id='$uid'"; // clear out old sessions
				mysql_query($sql2);
				$sql2 = "INSERT INTO agency_rememberme (user_id, session_id) VALUES ('$uid', '$session_id')";
				mysql_query($sql2);
				
				$set_time = time() + 31536000;
				setcookie('agency_arm', $session_id, $set_time);				
			}	
			 
			$IP = getRealIpAddr();
			mysql_query("UPDATE agency_profiles SET last_visit_ip='$IP' WHERE user_id='$agency_uid' LIMIT 1");




			// LOGIN AS ADMIN
			if(!empty($_SESSION['user_id'])) {
				$userid = (int) $_SESSION['user_id'];
				$sql = "SELECT * FROM agency_admins WHERE user_id='$userid'";
				$result=mysql_query($sql);
				if($row = sql_fetchrow($result)) {
					if($row['super'] == '1') {
						$_SESSION['superadmin'] = true;
					}
					$_SESSION['admin'] = $userid;
				}
			}



			$url = 'profile.php';
			ob_end_clean(); // Delete the buffer.
			header("Location: $url");
			exit(); // Quit the script.					 
		}
	}
}

?>
<br />
<br />
<?php
   		if(isset($fail)) {
?>
<div id="failmessage" style="padding:10px; border:1px solid black">
<br />There was a problem with your login information.  Please carefully type in your Username and Password again.  If you continue to experience problems please contact us.
<br /><br /><br />
<a href="javascript:void(0)" onclick="document.getElementById('failmessage').style.display='none'">close</a>
<br /><br />
</div>
<?php
		}
?>
        <form method="post" action="login.php">
          <table align="center" border="0" cellspacing="0" cellpadding="4">
            <tr>
              <td colspan="2"><div align="center"><strong>Already a Member? Sign in.</strong></div><br /></td>
            </tr>
            <tr>
              <td align="right">Username:</td>
              <td><input name="username" type="text" size="25" /><br /><br /></td>
            </tr>
            <tr>
              <td align="right">Password:</td>
              <td><input name="password" type="password" size="25" /><br /><br /></td>
            </tr>
            <tr>
              <td class="p3">&nbsp;</td>
              <td>

				<input name="redirect" value="../profile.php" type="hidden" />
				<input type="hidden" name="login" value="login" />
                <input type="submit" name="submit" value="LOGIN" /><span style="font-weight:normal; padding-left:20px;"><input type="checkbox" name="rememberme" /> remember me</span>
                <div align="right"></div></td>
            </tr>
            <tr>
              <td class="p3">&nbsp;</td>
              <td><a href="forgotpassword.php" class="p3">Forgot Your Password?</a></td>

          </table>
        </form>
<?php
@include('./includes/footer.php');
?>
