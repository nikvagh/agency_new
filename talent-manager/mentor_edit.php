
<?php 
	$page = "affiliate";
	$page_selected = "affiliate";
	include('header.php'); 
?>
<?php
	function getcode($iter)
    {
        if ($iter > 100) { // iter is there to count the number of recursive calls to this function.  If after 100 tries it still can't find a unique code, the function will quit to avoid getting stuck in a loop
            return 'error';
        } else {
            $c = rand(100000, 999999);

            $query = "SELECT * FROM agency_discounts WHERE code='$c'";
            $result = mysql_query($query);

            $query2 = "SELECT mentor_code FROM agency_mentors WHERE mentor_code='$c'";
            $result2 = mysql_query($query2);

            if (mysql_num_rows($result) > 0 || mysql_num_rows($result2) > 0) { // If this code is already being used.	
                $iter++;
                return getcode($iter);
            } else {
                return $c;
            }
        }
    }

	$notification = array();
    if (isset($_POST['submit']) && isset($_POST['id'])) {
        $id = $_POST['id'];
        $firstname = escape_data($_POST['firstname']);
        $lastname = escape_data($_POST['lastname']);
        $email = escape_data($_POST['email']);
        $code = escape_data($_POST['code']);

        // Check for an first name.
        if (stripslashes(trim($_POST['firstname']))) {
            $firstname = escape_data($_POST['firstname']);
        } else {
            $firstname = FALSE;
            $notification['error'] = 'You must enter a First Name';
        }

        // Check for an last name.
        if (stripslashes(trim($_POST['lastname']))) {
            $lastname = escape_data($_POST['lastname']);
        } else {
            $lastname = FALSE;
            $notification['error'] = 'You must enter a Last Name';
        }

        $query_dis_check = "SELECT * FROM agency_discounts WHERE code='$code'";
        $result_dis_check = mysql_query($query_dis_check);

        $query_men_check = "SELECT mentor_code FROM agency_mentors WHERE mentor_code='$code' AND mentor_id!='$id' ";
        $result_men_check = mysql_query($query_men_check);

        if (mysql_num_rows($result_dis_check) > 0 || mysql_num_rows($result_men_check) > 0) {		
            $ok = FALSE;
            $notification['error'] = 'The Code you entered is already in the database (either as a Discount code or a Mentor code).  Please select another code.';
        }else{
	        // Check if there is already an entry for this mentor.
	        if ($firstname && $lastname) {
	            $query = "SELECT * FROM agency_mentors WHERE (firstname='$firstname' AND lastname='$lastname' AND mentor_id!='$id')";  // check to see if name exists under a different ID.
	            $result = mysql_query($query);
	            if (mysql_affected_rows() > 0) {
	                $ok = FALSE;
	                $notification['error'] = 'Error: There is already an entry under this First and Last name.  Please find <b>' . $firstname . ' ' . $lastname . '</b> in the list of mentors and edit them from there.';
	            } else {
	                $ok = TRUE;
	            }
	        }
	    }


        $email = escape_data($_POST['email']);
        $code = trim(escape_data($_POST['code']));

        if ($ok) {
            $query = "UPDATE agency_mentors SET firstname='$firstname', lastname='$lastname', paypal_email='$email', mentor_code='$code' WHERE mentor_id='$id'";
            $result = mysql_query($query);
            $url = 'mentor_view.php?id=' . $id;
            ob_end_clean(); // Delete the buffer.
            header("Location: $url");
            exit(); // Quit the script.
        }
    } else {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $query = "SELECT * FROM agency_mentors WHERE mentor_id='$id'";  // check to see if name already used.
            $result = mysql_query($query);
            if ($row = mysql_fetch_assoc($result)) { // If there are projects.	
                $firstname = $row['firstname'];
                $lastname = $row['lastname'];
                $email = $row['paypal_email'];
                $code = $row['mentor_code'];
            }
        }
    }
?>

	<div id="page-wrapper">
	    <div class="" id="main">
	        <div class="row">
	        	<div class="col-md-12">
	        		<h3>Mentor</h3>

	        		<?php if(isset($notification['success'])){ ?>
				        <div class="alert alert-success" role="alert" id="alert-success-form">
				            <?php echo $notification['success']; ?>
				        </div>
			        <?php } ?>
			        <?php if(isset($notification['error'])){ ?>
			            <div class="alert alert-danger" role="alert" id="alert-danger-form">
			                <?php echo $notification['error']; ?>
			            </div>
			        <?php } ?>

		        	<div class="row">
						<div class="col-md-6">
							<form name="form1" method="post" action="mentor_edit.php">
								<div class="box box-theme">
									<div class="box-header with-border">
										<h3 class="box-title">Enter Mentor Information</h3>
				                	</div>

									<div class="box-body">
				                    	<input name="id" type="hidden" value="<?php if (isset($id)) echo $id; ?>">
				                    	<div class="form-group">
					                        <label>First Name</label>
					                        <input name="firstname" type="text" size="50" maxlength="250" <?php if (isset($firstname)) echo ' value="' . $firstname . '"'; ?> class="form-control">
					                    </div>
					                    <div class="form-group">
					                        <label>Last Name</label>
					                        <input name="lastname" type="text" size="50" maxlength="250" <?php if (isset($lastname)) echo ' value="' . $lastname . '"'; ?> class="form-control">
					                    </div>
					                    <div class="form-group">
					                        <label>PayPal Email (commisions are sent to this email address; please confirm it is their PayPal email or they will not receive commissions)</label>
					                        <input name="email" type="text" size="50" maxlength="250" <?php if (isset($email)) echo ' value="' . $email . '"'; ?>  class="form-control">
					                    </div>
					                    <div class="form-group">
					                        <label>Promo Code </label>
					                        <input name="code" type="text" size="10" maxlength="10" value="<?php echo $code; ?>" class="form-control">
					                        <label class="text-alert"><i class="fa fa-bell"></i> You may leave the default or choose your own (up to 10 characters)</label>
					                    </div>
				               		</div>

				               		<div class="box-footer text-right">
				               			<input type="submit" name="submit" value="Submit" class="btn btn-theme" />
				               		</div>
						    	</div>
					    	</form>
						</div>
					</div>

				</div>
			</div>
		</div>
	</div>

<?php include('footer_js.php'); ?>
<script>
	if (window.history.replaceState) {
		window.history.replaceState( null, null, window.location.href );
	}
</script>
<?php include('footer.php'); ?>