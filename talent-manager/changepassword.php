<?php
$page = "change_password";
$page_selected = "change_password";

include('header.php');
?>

<?php 
	$userid = (int) $_SESSION['user_id'];
	$success = false;
	if(!empty($_POST['submit'])) {
		if(!empty($_POST['original']) && !empty($_POST['new1']) && !empty($_POST['new2'])) {
			$agency_pw = escape_data($_POST['original']);
			$sql = "SELECT user_password FROM forum_users WHERE user_id='$userid'";
			$result = mysql_query($sql);
			if($row = mysql_fetch_assoc($result)) {
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
							$notification['error'] = "YOUR PASSWORD ENTRIES DID NOT MATCH.  PLEASE BE SURE BOTH THE PASSWORD AND CONFIRM PASSWORD FIELDS ARE IDENTICAL.";
						}
					} else {
						$notification['error'] = "PLEASE ENTER A VALID PASSWORD (BETWEEN 6 AND 20 ALPHANUMERIC CHARACTERS)";
					}
				 } else {
				 	$notification['error'] = "THE ORIGINAL PASSWORD WAS NOT ENTERED CORRECTLY.  YOU MAY NOT CHANGE YOUR PASSWORD UNLESS YOU KNOW YOUR CURRENT PASSWORD.  REMEMBER PASSWORDS ARE CASE SENSITIVE AND MUST BE ENTERED EXACTLY AS THEY WERE CREATED.  PLEASE TRY AGAIN.";
				 }
			}
		} else {
			$notification['error'] = "NOT ALL FIELDS WERE FILLED.  PLEASE FILL ALL FIELDS.";
		}
	}
?>

<?php if($success) { ?>
	<?php $notification['success'] = "YOUR PASSWORD HAS BEEN UPDATED.  PLEASE BE SURE TO WRITE IT DOWN IN A SAFE PLACE SO YOU DON\'T FORGET IT."; ?>
<?php } ?>
	
    <div id="page-wrapper">
    	<div class="" id="main">
    		<h3>Change Password</h3>

    		<?php if(isset($notification['success'])){ ?>
		        <div class="alert alert-success" role="alert">
		            <?php echo $notification['success']; ?>
		        </div>
	        <?php } ?>
	        <?php if(isset($notification['error'])){ ?>
	            <div class="alert alert-danger" role="alert">
	                <?php echo $notification['error']; ?>
	            </div>
	        <?php } ?>

    		<div class="row">
    			<div class="col-md-5">
    
					<form action="changepassword.php" method="post" name="changepw">
						<div class="box box-theme">
							<div class="box-body">
								<div class="form-group">
			                        <label>Password *</label>
			                        <input type="password" class="form-control" id="original" name="original" placeholder="Enter Password"/>
			                    </div>

			                    <div class="form-group">
			                        <label>Password *</label>
			                        <input type="password" class="form-control" id="new1" name="new1" placeholder="Enter New Password"/>
			                    </div>

			                    <div class="form-group">
			                        <label>Confirm New Password *</label>
			                        <input type="password" class="form-control" id="new2" name="new2" placeholder="Enter Confirm Password"/>
			                    </div>

							</div>
							<div class="box-footer">
								<input type="hidden" name="stopdouble" value="<?php $_SESSION['stopdouble'] = time(); echo $_SESSION['stopdouble']; ?>" />
								<input type="hidden" value="<?php echo time(); ?>" name="creation_time"/>
								<input type="hidden" value="<?php echo agency_add_form_key('changepw'); ?>" name="form_token"/>
							    <input type="submit" value="Submit" name="submit" class="btn btn-theme" />
							</div>
						</div>
				  	</form>

				</div>
			</div>

		</div>
	</div>


<?php include('footer_js.php'); ?>
<?php include('footer.php'); ?>