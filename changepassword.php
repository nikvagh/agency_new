<?php
$pagetitle = 'Change Password';
@include('includes/header.php');

?>
<div align="center">
<?php
if(isset($_SESSION['user_id'])) {
	
	$userid = (int) $_SESSION['user_id'];

	$success = false;
	if(!empty($_POST['submit'])) {
		if(!empty($_POST['original']) && !empty($_POST['new1']) && !empty($_POST['new2'])) {
			$agency_pw = escape_data($_POST['original']);
			$sql = "SELECT user_password FROM forum_users WHERE user_id='$userid'";
			$result = mysql_query($sql);
			if($row = @mysql_fetch_array ($result, mysql_ASSOC)) {
				 $password = $row['user_password'];

				 if(_check_hash($agency_pw, $password)) { // original password checks out
					
					// Check for a password and match against the confirmed password.
					if (eregi ('^[[:alnum:]]{6,20}$', stripslashes(trim($_POST['new1']))) && eregi ('^[[:alnum:]]{6,20}$', stripslashes(trim($_POST['new2'])))) {
						$p = escape_data($_POST['new1']);
						$p2 = escape_data($_POST['new2']);
						if($p == $p2) {
							
							$p = _hash($p);
							$query = "UPDATE forum_users SET user_password='$p' WHERE user_id='$userid'";
							mysql_query($query);
							$success = true;
							
						} else {
							$error =  'YOUR PASSWORD ENTRIES DID NOT MATCH.  PLEASE BE SURE BOTH THE PASSWORD AND CONFIRM PASSWORD FIELDS ARE IDENTICAL.';
						}
					} else {
						$error =  'PLEASE ENTER A VALID PASSWORD (BETWEEN 6 AND 20 ALPHANUMERIC CHARACTERS)';
					}
				 } else {
					 $error = 'THE ORIGINAL PASSWORD WAS NOT ENTERED CORRECTLY.  YOU MAY NOT CHANGE YOUR PASSWORD UNLESS YOU KNOW YOUR CURRENT PASSWORD.  REMEMBER PASSWORDS ARE CASE SENSITIVE AND MUST BE ENTERED EXACTLY AS THEY WERE CREATED.  PLEASE TRY AGAIN.';
				 }
			}
		} else {
			$error = 'NOT ALL FIELDS WERE FILLED.  PLEASE FILL ALL FIELDS.';
		}
	}
	
	if($success) {
		echo '<div style="font-weight:bold; padding:12px; border:1px solid gray;">YOUR PASSWORD HAS BEEN UPDATED.  PLEASE BE SURE TO WRITE IT DOWN IN A SAFE PLACE SO YOU DON\'T FORGET IT.</div>';
		
		
	} else {
		if(!empty($error)) {
			echo '<div style="font-weight:bold; color:red; padding:12px; border:1px solid gray;">' . $error . '</div>';
		}
?>			
	    
        
     <br />
<form action="changepassword.php" method="post" name="changepw">
     <table cellpadding="8">
     <tr>
     <td align="right">Original Password:</td><td align="left"><input type="password" name="original" /></td>
     </tr>
     <tr>
     <td align="right">New Password:</td><td align="left"><input type="password" name="new1" /></td>
     </tr>
     <tr>
     <td align="right">Confirm New Password:</td><td align="left"><input type="password" name="new2" /></td>
     </tr>
     </table>
    <input type="hidden" name="stopdouble" value="<?php $_SESSION['stopdouble'] = time(); echo $_SESSION['stopdouble']; ?>" />
	<input type="hidden" value="<?php echo time(); ?>" name="creation_time"/>
	<input type="hidden" value="<?php echo agency_add_form_key('changepw'); ?>" name="form_token"/>
    <input type="submit" value="Submit" name="submit" />
  </form>
<br />
  <form action="profile.php" style="padding-bottom:20px">
    <input type="submit" value="Cancel" />
  </form>

<?php	
	}
	
}
?>
</div>
<?php
@include('includes/footer.php');
?>