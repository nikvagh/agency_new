
<?php 
	$page = "affiliate";
	$page_selected = "affiliate";
	include('header.php'); 
?>
<?php
    if (isset($_POST['delete'])) {
        $delete = $_POST['delete'];
        $query = "DELETE FROM agency_mentors WHERE mentor_id='$delete'";
        $result = mysql_query($query);
    }

    if (isset($_GET['new'])) {
        $success = FALSE; // flag for showing or not showing form
    } else {
        $success = TRUE;
    }

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
    if (isset($_POST['code'])) {
        $code = $_POST['code'];
    } else {
        $code = getcode(0);
    }
    if (isset($_POST['Submit'])) {

        if (stripslashes(trim($_POST['firstname'])) && stripslashes(trim($_POST['lastname']))) {
            $firstname = escape_data($_POST['firstname']);
            $lastname = escape_data($_POST['lastname']);
            $email = escape_data($_POST['email']);
            $code = escape_data($_POST['code']);

            $query = "SELECT * FROM agency_mentors WHERE firstname='$firstname' AND lastname='$lastname'";
            $result = mysql_query($query);

            if (mysql_affected_rows() == 0) { // If it ran OK.			
                $query = "SELECT * FROM agency_discounts WHERE code='$code'";
                $result = mysql_query($query);

                $query2 = "SELECT mentor_code FROM agency_mentors WHERE mentor_code='$code'";
                $result2 = mysql_query($query2);

                if (mysql_num_rows($result) == 0 && mysql_num_rows($result2) == 0) { // If it ran OK.			

                    $query = "INSERT INTO agency_mentors (firstname, lastname, paypal_email, mentor_code) VALUES ( '$firstname', '$lastname', '$email', '$code' )";
                    $result = mysql_query($query);

                    if (mysql_affected_rows() == 1) { // If it ran OK.	
                        $success = TRUE;
                        $notification['success'] = 'Your new Mentor has been added.  Thank you.';
                    } else {
                        $success = FALSE;
                        $notification['error'] = 'Database error.  Please contact system administrator.';
                    }
                } else {
                    $success = FALSE;
                    $notification['error'] = 'The Code you entered is already in the database (either as a Discount code or a Mentor code).  Please select another code.';
                }
            } else {
                $success = FALSE;
                $notification['error'] = 'The name you entered is already in the database.';
            }
        } else {
            $success = FALSE;
            $notification['error'] = 'You must enter a First and Last Name.';
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

			        	<div class="col-md-8">
			                <div class="box box-theme">

			                	<div class="box-header with-border">
			                		<div class="text-right">
			                			<?php if($success) { ?>
								        	<a class="btn btn-theme" href="mentors.php?new=true">Add New Mentor</a>
								        <?php } ?>
							        	<a class="btn btn-theme" href="mentors_pay.php">Process Payments</a>
							        </div>
			                	</div>

			                	<div class="box-body">
							        <?php
								        if (isset($_GET['delete'])) {
								            $id = $_GET['delete'];
								            $query = "DELETE FROM agency_mentors WHERE mentor_code='$id'";
								            $result = mysql_query($query);
								        }

								        $query = "SELECT * FROM agency_mentors";
								        $result = mysql_query($query);
								     
								    ?>
							        	<table class="datatable table table-responsive table-striped mentor-list-table">
							        		<thead>
								        		<tr>
								        			<td>First Name</td>
								        			<td>Last Name</td>
								        			<td>Email</td>
								        			<td>Code</td>
								        			<td></td>
								        		</tr>
								        	</thead>
								        	<tbody>
								        		<?php 
									        		if (mysql_affected_rows() > 0) { 
									        			while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) { 
									        			$MentorID = $row['mentor_id'];
										                $Firstname = $row['firstname'];
										                $Lastname = $row['lastname'];
										                $Email = $row['paypal_email'];
										                $Code = $row['mentor_code'];
								        		?>
								        				<tr>
								        					<td><?php echo $Firstname; ?></td>
								        					<td><?php echo $Lastname; ?></td>
								        					<td><?php echo $Email; ?></td>
								        					<td><?php echo $Code; ?></td>
								        					<td>
								        						<a href="mentor_view.php?id=<?php echo $MentorID; ?>" class="btn btn-theme">View Details</a>
							        						</td>
							        					</tr>
								        			<?php } ?>
								        		<?php } ?>
								        	</tbody>
							        	</table>
							    </div>

							    <!-- <div class="box-footer with-border">
			                		<div class="">
								        Click "View Details" for each mentor to see the members who have been signed up with that mentors's code.<br />
								        Please note that even though a member has signed up using the code, they haven't necessarily paid for their membership.<br />
								        If you would like to view all members who have signed up using a code, click the link below:<br /><br />
								        <a href="userlist.php?filter=referred">view all referal signups</a>
							        </div>
			                	</div> -->

						    </div>
						</div>

						<?php if (!$success) { ?>
							<div class="col-md-4">
								<form name="form1" method="post" action="mentors.php">

									<div class="box box-theme">
										<div class="box-header with-border">
											<h3 class="box-title">Add Information For A New Mentor</h3>
					                	</div>

										<div class="box-body">
					                    
					                    	<div class="form-group">
						                        <label>First Name</label>
						                        <input name="firstname" type="text" size="50" maxlength="250" value="<?php if (isset($_POST['firstname'])) echo $_POST['firstname']; ?>" class="form-control">
						                    </div>
						                    <div class="form-group">
						                        <label>Last Name</label>
						                        <input name="lastname" type="text" size="50" maxlength="250" value="<?php if (isset($_POST['lastname'])) echo $_POST['lastname']; ?>" class="form-control">
						                    </div>
						                    <div class="form-group">
						                        <label>PayPal Email (commisions are sent to this email address; please confirm it is their PayPal email or they will not receive commissions)</label>
						                        <input name="email" type="text" size="50" maxlength="250" value="<?php if (isset($_POST['email'])) echo $_POST['email']; ?>" class="form-control">
						                    </div>
						                    <div class="form-group">
						                        <label>Promo Code </label>
						                        <input name="code" type="text" size="10" maxlength="10" value="<?php echo $code; ?>" class="form-control">
						                        <label class="text-alert"><i class="fa fa-bell"></i> You may leave the default or choose your own (up to 10 characters)</label>
						                    </div>
					                    
					               		</div>

					               		<div class="box-footer text-right">
					               			<input type="submit" name="Submit" value="Submit" class="btn btn-theme" />
					               		</div>
							    	</div>

						    	</form>
							</div>
						<?php } ?>

					</div>
				</div>
			</div>
		</div>
	</div>

<?php include('footer_js.php'); ?>
<!-- <script type="text/javascript" src="http://code.jquery.com/ui/1.11.0/jquery-ui.min.js"></script> -->
<!-- <script type="text/javascript" src="../dashboard/assets/jQuery-DateTimepicker-Addon/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript" src="../dashboard/assets/jQuery-DateTimepicker-Addon/i18n/jquery-ui-timepicker-addon-i18n.min.js"></script>
<script type="text/javascript" src="../dashboard/assets/jQuery-DateTimepicker-Addon/jquery-ui-sliderAccess.js"></script> -->
<!-- <script type="text/javascript" src="../dashboard/assets/ckeditor/ckeditor.js"></script> -->

<!-- <script type="text/javascript" src="../dashboard/assets/bootstrap-tagsinput-latest/dist/bootstrap-tagsinput.min.js"></script> -->
<!-- <script type="text/javascript" src="../dashboard/assets/js/jquery.validate.js"></script> -->
<!-- <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script> -->

<script src="../dashboard/assets/DataTables/datatables.min.js"></script> 
<script src="../dashboard/assets/DataTables/dataTables.bootstrap.min.js"></script> 

<script type="text/javascript">
    $('.datatable').DataTable({
        "order": [[ 0, "desc" ]],
            'columnDefs': [{
            'targets': [4], /* column index */
            'orderable': false, /* true or false */
        }]
    });
</script>
<script>
	if (window.history.replaceState) {
		window.history.replaceState( null, null, window.location.href );
	}
</script>
<?php include('footer.php'); ?>