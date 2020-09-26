<?php
	$page = "account";
	$page_selected = "account";

	session_start();
	@include ('header.php');
	include('../includes/agency_dash_functions.php');

	unset($loggedin);
	if (!empty($_SESSION['user_id'])) {
	   $loggedin = $_SESSION['user_id'];
	} else { // if not logged in, redirect to login page
	  $url = 'login.php';
	  ob_end_clean(); // Delete the buffer.
	  header("Location: $url");
	  exit(); // Quit the script.
	}
	$user_id = $userid = $profileid = $loggedin;

	$notification = array();
	if(isset($_POST['profileUpdate'])){
		// echo "<pre>";
		// print_r($_POST);
		// echo "</pre>";

		$query_profile_status_update = "UPDATE agency_profiles
								SET 
								profile_status = '".$_POST['profile_status']."'
								WHERE
								user_id='$user_id'
							";
		if(mysql_query($query_profile_status_update)){
			$notification['success'] = "Profile Status Updated Successfully";
		}
	}

	if(isset($_POST['cancel_account'])){
		// echo "<pre>";
		// print_r($_POST);
		// echo "</pre>";

		$query_account_status_update = "UPDATE agency_profiles
								SET 
								account_status = 'cancel'
								WHERE
								user_id='$user_id'
							";
		if(mysql_query($query_account_status_update)){
			// $notification['success'] = "Account Cancelled Successfully";
			$url = '../logout.php';
			header("Location: $url");
			exit();
		}
	}

	if(isset($_POST['ApplyNew'])){
		// echo "<pre>";
		// print_r($_POST);
		// echo "</pre>";

		$password = _hash($_POST['password']);
		$user_type = 1;
		$user_ip = getRealIpAddr();
		$user_regdate = time();

		$sql_talent_ins = "INSERT into forum_users 
							SET
							username = '".$_POST['username']."',
							username_clean = '".$_POST['username']."',
							user_email = '".$_POST['email']."',
							user_password = '".$password."',
							user_ip = '".$user_ip."',
							user_regdate = '".$user_regdate."'
						";
		if(mysql_query($sql_talent_ins)){

			$user_id_ins = mysql_insert_id();
			$sql_t_profile_ins = "INSERT into agency_profiles 
							SET
							user_id = '".$user_id_ins."',
							account_type = 'talent',
							firstname = '".$_POST['firstname']."',
							lastname = '".$_POST['lastname']."',
							phone = '".$_POST['phone']."',
							pay_term = '".$_POST['payment_term']."'
						";
			if(mysql_query($sql_t_profile_ins)){

				$payment_term = agency_payment_term_byId($_POST['payment_term']);
				$amount = $payment_term['total_amount'] - ($payment_term['total_amount']*10)/100;
				$total_month = $payment_term['total_month'];

				$sql_payment_ins = "INSERT into agency_payment 
							SET
							user_id = '".$user_id."',
							amount = '".$amount."',
							description = 'Link New Account',
							status = 'success'
						";

				if(mysql_query($sql_payment_ins)){

					$next_payment_date = date('Y-m-d h:i:s', strtotime("+".$total_month." months"));
					$sql_profile_upadte = "UPDATE agency_profiles 
							SET
							next_payment_date = '".$next_payment_date."'
							WHERE
							user_id = '".$user_id_ins."'
						";

					if(mysql_query($sql_profile_upadte)){
						$notification['success'] = "User Created Successfully";
					}
					
				}
			}
		}
	}
	

	$sql_user = "SELECT ap.*,fu.*,apt.name as membership_level FROM agency_profiles ap 
			INNER JOIN forum_users fu ON fu.user_id = ap.user_id
			LEFT JOIN agency_payment_term apt ON ap.pay_term = apt.payment_term_id
			WHERE ap.user_id='$profileid'";
	$result_user = mysql_query($sql_user);
	$userInfo = sql_fetchrow($result_user);
	
?>

<div id="page-wrapper">
    <div class="" id="main">

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

        <h3>My Account </h3>
        <div class="row">
			<div class="col-md-9">
				<div class="box box-theme">

					<div class="box-header with-border">
						<h3 class="box-title">Payment</h3>
					</div>
					<div class="box-body">
						<table class="datatable table table-responsive table-striped">
							<thead>
								<tr>
									<!-- <th>Id</th> -->
									<th>Amount</th>
									<th>Description</th>
									<th>Payment Date</th>
									<th>Status</th>
									<!-- <th></th> -->
								</tr>
							</thead>
							<tbody>
								<?php
									$cond = "";
									// if(isset($_GET['status'])){
									// 	$cond .= " AND aa.status = '".$_GET['status']."'";
									// }

									$result = mysql_query("select ap.* from agency_payment ap
															WHERE ap.user_id = ".$_SESSION['user_id']." ".$cond."
														");
								?>
								<?php if (mysql_num_rows($result) > 0) { ?>
									<?php while ($row = mysql_fetch_assoc($result)) { ?>
										<tr>
											<!-- <td></td> -->
											<td><?php echo $row['amount']; ?></td>
											<td><?php echo $row['description']; ?></td>
											<td><?php echo $row['payment_date']; ?></td>
											<td><?php echo $row['status']; ?></td>
											<!-- <td></td> -->
										</tr>
									<?php } ?>
								<?php } ?>
							</tbody>
						</table>
					</div>

				</div>
			</div>

			<div class="col-md-3">
				<div class="info-box">
		            <!-- <div class="box-header with-border">
		              	<h3 class="box-title">My Account</h3>
		            </div> -->
		            <div class="box-body no-padding">
		                <table class="table no-margin table-striped">
			                <tbody>
			                	<tr>
			                    	<td>Next Payment Date:</td>
			                    	<td><?php echo date('d M Y',strtotime($userInfo['next_payment_date'])); ?></td>
			                  	</tr>
			                  	<tr>
			                    	<td>Membership Level:</td>
			                    	<td><span class="text-uppercase"><?php echo $userInfo['membership_level']; ?></span></td>
			                  	</tr>
			                  	<tr>
			                    	<td>Change Password:</td>
			                    	<td><a href="changepassword.php" class="btn btn-default btn-xs btn-flat">Cick to Change </a></td>
			                  	</tr>
			                  	<tr>
			                    	<td>Member Since:</td>
			                    	<td><?php echo date('d M Y',strtotime($userInfo['created_at'])); ?></td>
			                  	</tr>
			                  	<tr>
			                    	<td>Profile Hide/Show:</td>	
			                    	<td>
			                    		<?php 
				                    		if($userInfo['profile_status'] == '1'){ 
				                    			echo "Everyone";
				                    		}else if($userInfo['profile_status'] == '2'){ 
				                    			echo "Clients Only";
				                    		}else if($userInfo['profile_status'] == '3'){ 
				                    			echo "Friends Only";
				                    		}else if($userInfo['profile_status'] == '4'){ 
				                    			echo "Clients And Friends Only";
				                    		}else if($userInfo['profile_status'] == '5'){ 
				                    			echo "Nobody";
				                    		}
			                    		?>
			                    		<button data-toggle="modal" data-target="#profile_statusModal" class="btn btn-default btn-xs btn-flat pull-right">Cick to Change </button>
			                    	</td>
			                  	</tr>
			                  	<tr>
			                  		<form action="" method="post" onsubmit="return confirm('Do you really want to cancel this account?');">
				                  		<td>Account Status:</td>
				                  		<td>
				                  			<?php if($userInfo['user_type'] == '0'){ echo 'Active'; }else if($userInfo['user_type'] == '1'){ echo 'Inactive'; } ?> 
				                  			<button type="submit" class="btn btn-danger btn-flat btn-xs pull-right" name="cancel_account">Cancel Account</button>
				                  		</td>
				                  	</form>
			                  	</tr>
			                  	<tr>
			                  		<td colspan="2" class="text-center">
			                  			<button data-toggle="modal" data-target="#apply_Modal" class="btn btn-success btn-sm btn-flat">Apply For Linked Account</button>
			                  		</td>
			                  	</tr>
		                  	</tbody>
		                </table>
		            </div>
		            <!-- <div class="box-footer text-right"></div> -->
		        </div>
		    </div>

		</div>

	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="profile_statusModal" role="dialog">
    <div class="modal-dialog">
    	<form role="form" id="profile_statusForm" method="post" action="">
	        <div class="modal-content">
	            <!-- Modal Header -->
	            <div class="modal-header">
	                <button type="button" class="close" data-dismiss="modal">
	                    <span aria-hidden="true">&times;</span>
	                    <span class="sr-only">Close</span>
	                </button>
	                <h4 class="modal-title" id="myModalLabel">Who Can Show Your Profile ?</h4>
	            </div>
	            
	            <!-- Modal Body -->
	            <div class="modal-body">
	                <p class="statusMsg"></p>
                	<div class="form-group">
                        <label>Status</label>
                        <select class="form-control" id="profile_status" name="profile_status">
                        	<option value="1" <?php if($userInfo['profile_status'] == '1'){ echo "selected"; } ?> >Everyone</option>
                        	<option value="2" <?php if($userInfo['profile_status'] == '2'){ echo "selected"; } ?> >Clients Only</option>
                        	<option value="3" <?php if($userInfo['profile_status'] == '3'){ echo "selected"; } ?> >Friends Only</option>
                        	<option value="4" <?php if($userInfo['profile_status'] == '4'){ echo "selected"; } ?> >Client And Friends Only</option>
                        	<option value="5" <?php if($userInfo['profile_status'] == '5'){ echo "selected"; } ?> >Nobody</option>
                        </select>
                    </div>
	            </div>
	            
	            <!-- Modal Footer -->
	            <div class="modal-footer">
	                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	                <input type="submit" class="btn btn-theme submitBtn" name="profileUpdate" value="Save" />
	            </div>
	        </div>
        </form>
    </div>
</div>

<div class="modal fade" id="apply_Modal" role="dialog">
    <div class="modal-dialog">
    	<form role="form" id="apply_Form" method="post" action="">
	        <div class="modal-content">
	            <!-- Modal Header -->
	            <div class="modal-header">
	                <button type="button" class="close" data-dismiss="modal">
	                    <span aria-hidden="true">&times;</span>
	                    <span class="sr-only">Close</span>
	                </button>
	                <h4 class="modal-title" id="myModalLabel">Apply For Linked Account</h4>
	            </div>
	            
	            <!-- Modal Body -->
	            <div class="modal-body">
	                <!-- <p class="statusMsg"></p> -->
	                <a class="text-theme"><h4>General Info</h4></a>
	                <div class="form-group">
                        <label>First Name</label>
                        <input type="text" name="firstname" id="firstname" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Last Name</label>
                        <input type="text" name="lastname" id="lastname" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="text" name="email" id="email" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Phone</label>
                        <input type="text" name="phone" id="phone" class="form-control">
                    </div>

                    <br/>
                    <a class="text-theme"><h4>Account Info</h4></a>
                	<div class="form-group">
                        <label>User Name</label>
                        <input type="text" name="username" id="username" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" id="password" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Password Confirm</label>
                        <input type="password" name="confirm_password" id="confirm_password" class="form-control">
                    </div>

                    <br/>
                    <a class="text-theme"><h4>Payment Info (10% OFF)</h4></a>
                	<div class="form-group">
                        <label>Select Payment Term</label>
                        <?php
                        	$sql_terms = "SELECT * FROM agency_payment_term";
							$result_terms = mysql_query($sql_terms);
                        ?>
                        <?php while ($row = sql_fetchrow($result_terms)) { ?>
                        	<br/>
                        	<label>
                        		<input type="radio" name="payment_term" id="" value="<?php echo $row['payment_term_id']; ?>"> <?php echo '$'.$row['per_month'].' per month'; ?>
                        		<?php if($row['total_amount'] != $row['per_month']){ echo '($'.$row['total_amount'].' '.$row['term_title'].')'; } ?>
                        	</label>
                        <?php } ?>
                        <span class="radio_err"></span>
                    </div>
	            </div>
	            
	            <!-- Modal Footer -->
	            <div class="modal-footer">
	                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	                <input type="submit" class="btn btn-theme submitBtn" name="ApplyNew" value="Save" />
	            </div>
	        </div>
        </form>
    </div>
</div>



<?php include ('footer_js.php'); ?>

<script src="../dashboard/assets/DataTables/datatables.min.js"></script> 
<script src="../dashboard/assets/DataTables/dataTables.bootstrap.min.js"></script> 

<script type="text/javascript" src="../dashboard/assets/js/jquery.validate.js"></script>

<script>
	// $(document).ready( function () {
	    $('.datatable').DataTable({
	  //       "order": [[ 0, "desc" ]],
	  //       'columnDefs': [{
			//     'targets': [3], /* column index */
			//     'orderable': false, /* true or false */
			// }]
	    });

	    $("#apply_Form").validate({
			rules: {
				firstname: "required",
				lastname: "required",
				email: {
                    required : true,
                    email : true,
                    remote: {
				        url: "../ajax/dashboard_request.php",
				        type: "post",
				        data: {
					        name:'user_email_unique_insert'
					    }
				    }
                },
                phone: {
                    required : true,
                    digits : true,
                },
				username : {
                    required : true,
                    remote: {
				        url: "../ajax/dashboard_request.php",
				        type: "post",
				        data: {
					        name:'user_username_unique_insert'
					    }
				    }
                },
                password : {
                    required : true,
                    minlength:6,
                    maxlength:20,
                },
				confirm_password : {
                    required : true,
                    equalTo : "#password"
                },
				payment_term: "required"
			},
			messages: {
				email: { 
					remote: "Email already exist",
				},
				username: { 
					remote: "Username already exist",
				}
			},
			errorElement: "em",
			errorPlacement: function ( error, element ) {
				// Add the `help-block` class to the error element
				error.addClass( "help-block" );

				if (element.prop("type") === "radio") {
					error.insertAfter(element.parents('label').siblings('.radio_err'));
					// error.html("#radio_err");
				} else {
					error.insertAfter(element);
				}
			},
			highlight: function ( element, errorClass, validClass ) {
				$( element ).parents( ".col-sm-5" ).addClass( "has-error" ).removeClass( "has-success" );
			},
			unhighlight: function (element, errorClass, validClass) {
				$( element ).parents( ".col-sm-5" ).addClass( "has-success" ).removeClass( "has-error" );
			},
			// submitHandler: function (){

				// return false;
				// form.submit();
				// if(error){
				// 	console.log('111');
				// 	return false;
				// }
				// alert("222!");
				// return false;
			// }
		});

	// });
</script>

<script>
	if (window.history.replaceState) {
	  window.history.replaceState( null, null, window.location.href );
	}
</script>
<?php include ('footer.php'); ?>