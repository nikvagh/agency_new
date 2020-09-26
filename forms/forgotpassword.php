<?php
@include('./includes/header.php');

$success = false;

if (isset($_POST['submit'])) { // Handle the form.
	if ($_SESSION['form_token'] == $_POST['form_token']) {
		unset($_SESSION['form_token']);
		$email = escape_data($_POST['email']);
		$sql = "SELECT username FROM forum_users WHERE user_email='$email'";
		$result = mysql_query($sql);
		if($row = @mysql_fetch_array ($result, MYSQL_ASSOC)) {
			// create session variable with userid and redirect
		 	$username = $row['username'];
			
			// make new password
			$password = md5(time());  
			$password = substr($password, 0, 10);  
			
			
			
			$message = '<html>
				  <body>Your login for http://www.TheAgencyOnline.com is:

Username: ' . $username . '
Password: ' . $password . '


Your password has been reset to the above.  It is recommended that you change your password to something you will remember after you log in.

Thank you for using The Agency.
				  </body>
				  </html>';
			
			$from = "info@theagencyonline.com";
			$subject = "The Agency: Username and Password";
		
			$headers  = "From: $from\r\n";
			$headers .= "Content-type: text/html\r\n";
			
			// now lets send the email.
			mail($email, $subject, $message, $headers);
			// mail("ungabo@yahoo.com", $subject, $message, $headers);

			
			// send the info
			$hashed = _hash($password);
		 	
			
			$query = "UPDATE forum_users SET user_password='$hashed' WHERE username='$username'";
			mysql_query($query);
			
			$success = true;	
						 
		} else {
			$fail = true;
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
<br />The email address is not in our database.  Please enter the email address that is associated with your account.
<br /><br /><br />
<a href="javascript:void(0)" onclick="document.getElementById('failmessage').style.display='none'">close</a>
<br /><br />
</div>
<?php
		}
?>

<?php
   		if($success) {
?>
<div id="message" style="padding:10px; border:1px solid black">
<br />Your Username and new Password has been sent to your email.  Please check your email for your login information.
<br /><br /><br />
</div>
<?php
		} else {
?>

        <form method="post" action="forgotpassword.php">
          <table align="center" border="0" cellspacing="0" cellpadding="4">
            <tr>
              <td colspan="2"><div align="center"><strong>Please enter your email address.  Your username will be emailed to you.<br />Your password will be reset and sent to you as well.  Your old password will no longer be valid.</strong></div><br /></td>
            </tr>
            <tr>
              <td align="right">Email:</td>
              <td><input name="email" type="text" size="25" /></td>
            </tr>
            <tr>
              <td class="p3">&nbsp;</td>
              <td>
              
				<input type="hidden" value="<?php $_SESSION['form_token'] = rand('100000', '999999'); echo $_SESSION['form_token']; ?>" name="form_token"/>
                <input type="submit" name="submit" value="SEND NEW PASSWORD" />
              </td>
            </tr>
          </table>
        </form>
<?php
		}
?>
        
<?php
@include('./includes/footer.php');
?>
