<?php 
	$page = "requests";
	$page_selected = "requests";

	include('header.php');
	include('../includes/agency_dash_functions.php');

	if(isset($_POST['filter']) && $_POST['filter'] = "filter"){
		// echo "<pre>";
		// print_r($_POST);
		// echo "</pre>";
		$_SESSION['filter']['request']['booking_type'] = $_POST['booking_type'];
	}

	if(isset($_POST['clear']) && $_POST['clear'] = "clear"){
		// echo "<pre>";
		// print_r($_POST);
		// echo "</pre>";
		unset($_SESSION['filter']['request']);
	}

	$notification = array();
	if(isset($_POST['accept_booking_request'])){
		// echo "<pre>";
		// print_r($_POST);
		// echo "</pre>";
		$sql_confirm_booking_update = "UPDATE agency_talent_request 
										SET
										scheduled = 'Y'
										WHERE talent_request_id = ".$_POST['talent_request_id']."";

		if(mysql_query($sql_confirm_booking_update)){
			$notification['success'] = "Booking Request Accepted Successfully.";
		}
	}

	if(isset($_POST['friend_req_accept'])){
		$sql_confirm_friend_update = "UPDATE agency_friends 
										SET
										confirmed = 1
										WHERE friends_id = ".$_POST['friends_id']."";
		if(mysql_query($sql_confirm_friend_update)){
			$notification['success'] = "Friend Request Accepted Successfully.";
		}
	}

	if(isset($_POST['friend_req_delete'])){
		$sql_confirm_friend_update = "UPDATE agency_friends 
										SET
										denied = 1
										WHERE friends_id = ".$_POST['friends_id']."";
		if(mysql_query($sql_confirm_friend_update)){
			$notification['success'] = "Friend Request Deleted Successfully.";
		}
	}


	// echo "<pre>";
	// print_r($_POST);
	// echo "</pre>";
?>

<div id="page-wrapper">
    <div class="" id="main">

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
			<div class="col-sm-12">
				<h3>Requests </h3>
				
				<div class="row">
					<div class="col-sm-6">
						<div class="box box-theme">
							<div class="box-header with-border">
				            	<h3 class="box-title">Friend Requests</h3>
				            </div>
				            <div class="box-body">
				            	<?php 
				            		$result_friend_request = mysql_query("select af.*,ap.firstname,ap.lastname,u.user_avatar,u.user_id as request_from_id from agency_friends af
														LEFT JOIN forum_users u ON af.user_id = u.user_id
														LEFT JOIN agency_profiles ap ON ap.user_id = u.user_id
														where af.friend_id = ".$user['user_id']."
														AND confirmed = 0 AND denied = 0
													");
								?>

				            	<?php if (mysql_num_rows($result_friend_request) > 0) { ?>
				            		<?php while ($row = mysql_fetch_assoc($result_friend_request)) { ?>
						            	<div class="col-sm-3 margin-btm-15">
						            		<form action="" method="post">
							            		<div class="card text-center">
								            		<?php 
										            	if(file_exists('../uploads/users/' . $row['request_from_id'] . '/profile_pic/thumb/128x128_'.$row['user_avatar']) ){
										            		$frield_req_img = '../uploads/users/' . $row['request_from_id'] . '/profile_pic/thumb/128x128_'.$row['user_avatar'];
										            	}else{
										            		$frield_req_img = '../images/friend.gif';
										            	}
									            	?>
								            		<a class="img-a" style="height:128px;"><img src="<?php echo $frield_req_img; ?>" class="img-responsive"/></a>
								            		<p>
								            			<?php echo $row['firstname']." ".$row['lastname']; ?>
								            			<br/>
								            			<a href="<?php echo 'profile-view.php?user_id='.$row['request_from_id']; ?>">View Profile</a>
								            		</p>
								            		<input type="hidden" name="friends_id" value="<?php echo $row['friends_id']; ?>"/>
								            		<div class="btn-group">
								                    	<button type="submit" name="friend_req_accept" class="btn btn-success btn-flat btn-sm"><i class="fa fa-check-square-o"></i></button>
								                    	<button type="submit" name="friend_req_delete" class="btn btn-danger btn-flat btn-sm"><i class="fa fa-trash-o"></i></button>
								                    </div>
								            	</div>
								            </form>
						            	</div>
						            <?php } ?>
					            <?php }else{ ?>
					            	<p class="text-center">No New Friend Request.</p>
					            <?php } ?>
				            </div>

						</div>
					</div>


					<div class="col-sm-6">

						<div class="box box-theme">
							<form enctype="multipart/form-data" action="" method="post" name="article" id="service-provider-form">
								<div class="box-header with-border">
					            	<h3 class="box-title">Filter</h3>
					            </div>
					            <div class="box-body">
				            		<div class="row">
					            		<div class="col-sm-12">
											<div class="form-group">
							                  	<label class="control-label">Bookings Type</label>
							                  	<select class="form-control" name="booking_type" id="booking_type">
							                  		<option value="">Select</option>
							                  		<option value="direct" <?php if(isset($_SESSION['filter']['request']['booking_type']) && $_SESSION['filter']['request']['booking_type'] == "direct"){ echo "selected"; } ?>>Direct (Confirm)</option>
							                  		<option value="casting" <?php if(isset($_SESSION['filter']['request']['booking_type']) && $_SESSION['filter']['request']['booking_type'] == "casting"){ echo "selected"; } ?>>Casting</option>
							                  	</select>
							                </div>
						                </div>
					                </div>
								</div>
								<div class="box-footer text-right">
									<input type="submit" class="btn btn-flat btn-default" name="clear" value="clear" />
								    <input type="submit" class="btn btn-flat btn-theme" name="filter" value="filter"/>
								</div>
							</form>
						</div>

						<div class="box box-theme">
							<div class="box-header with-border">
				            	<h3 class="box-title">Booking Requests</h3>
				            </div>

							<div class="box-body">
								<table class="datatable table table-responsive table-striped table-bordered" align="center">
									<thead>
										<tr>
											<th>Name</th>
											<th>Phone</th>
											<th>Country</th>
											<th>City</th>
											<th></th>
										</tr>
									</thead>
									<tbody>
										<?php
											$cond = "";
											if(isset($_SESSION['filter']['request']['booking_type']) && $_SESSION['filter']['request']['booking_type'] == "direct"){
												$cond = " AND atr.request_for = 'booking'";
											}else if(isset($_SESSION['filter']['request']['booking_type']) && $_SESSION['filter']['request']['booking_type'] == "casting"){
												$cond = " AND atr.request_for = 'casting'";
											}
											
											$result = mysql_query("select * from agency_talent_request atr
																		LEFT JOIN forum_users u ON atr.request_by = u.user_id
																		LEFT JOIN agency_profiles ap ON ap.user_id = u.user_id
																		WHERE 
																		atr.user_id = ".$user['user_id']."
																		AND atr.request_status = 'approve'
																		AND scheduled = 'N'
																		".$cond."
																	");

											// print_r($result);
										?>

										<?php
											if (mysql_num_rows($result) > 0) {
												while ($row = mysql_fetch_assoc($result)) {
													echo '<tr>';
													echo '<td>'.$row['firstname'].' '.$row['lastname'].'</td>';
													echo '<td>'.$row['phone'].'</td>';
													echo '<td>'.$row['country'].'</td>';
													echo '<td>'.$row['city'].'</td>';
													// <a href="notes-view.php?casting_id='.$row['casting_id'].'">Delete</a></td>
													echo '<td><a class="btn btn-theme btn-confirm-request btn-sm btn-flat" data-id="'.$row['talent_request_id'].'">Confirm</a></td>';
													// echo '<form action="" method="post" class="form-inline">';
													// echo '<input type="hidden" name="reminder_email" value="'.$row['user_email'].'"/>';
													// echo '<button type="submit" class="btn btn-info" value="reminder">Send Reminder</button>';
													// echo '</form>';
													echo '</td>';
													echo '</tr>';
												}
											}
										?>
									</tbody>
								</table>
				                
				            </div>

						</div>
					</div>

				</div>

			</div>
		</div>
			
	</div>
</div>

<div class="modal fade" id="confirmModal" role="dialog">
    <div class="modal-dialog">
    	<form role="form" id="confirmModalForm" method="post" action="">
	        <div class="modal-content">
	            <!-- Modal Header -->
	            <div class="modal-header">
	                <button type="button" class="close" data-dismiss="modal">
	                    <span aria-hidden="true">&times;</span>
	                    <span class="sr-only">Close</span>
	                </button>
	                <h4 class="modal-title" id="myModalLabel">Booking Request</h4>
	            </div>
	            
	            <!-- Modal Body -->
	            <div class="modal-body">
	                
	            </div>
	            
	            <!-- Modal Footer -->
	            <div class="modal-footer">
	            	<!-- <input type="submit" name="" id="casting_id" value="" /> -->
	                <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Close</button>
	                <input type="submit" class="btn btn-success btn-flat" name="accept_booking_request" value="Accept & Confirm"/>
	            </div>
	        </div>
        </form>
    </div>
</div>

<?php include('footer_js.php'); ?>

<!-- <script type="text/javascript" src="http://code.jquery.com/ui/1.11.0/jquery-ui.min.js"></script>
<script type="text/javascript" src="../dashboard/assets/jQuery-DateTimepicker-Addon/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript" src="../dashboard/assets/jQuery-DateTimepicker-Addon/i18n/jquery-ui-timepicker-addon-i18n.min.js"></script>
<script type="text/javascript" src="../dashboard/assets/jQuery-DateTimepicker-Addon/jquery-ui-sliderAccess.js"></script> -->
<!-- <script type="text/javascript" src="../dashboard/assets/ckeditor/ckeditor.js"></script> -->

<!-- <script type="text/javascript" src="../dashboard/assets/bootstrap-tagsinput-latest/dist/bootstrap-tagsinput.min.js"></script> -->
<!-- <script type="text/javascript" src="../dashboard/assets/js/jquery.validate.js"></script> -->
<script src="../dashboard/assets/DataTables/datatables.min.js"></script> 
<script src="../dashboard/assets/DataTables/dataTables.bootstrap.min.js"></script> 
<script>
	$(document).ready( function () {
	    $('.datatable').DataTable();
	});


	$("table").on("click", ".btn-confirm-request", function(e){	
    	e.preventDefault();

	   var talent_request_id = $(this).attr('data-id');
	   // AJAX request
	   $.ajax({
		    url: '../ajax/dashboard_request.php',
		    type: 'post',
		    data: {name:'get_talent_request',talent_request_id: talent_request_id},
		    dataType: 'json',
		    success: function(res){ 
		    	// console.log(res);
		    	// return false;
		    	html = '';
		    	html += 'Request From : '+ res.sender_fname +' '+res.sender_lname+'<br/>';
		    	html += 'Date : '+ res.request_date +'<br/>';
		    	html += 'Time : '+ res.request_time +'<br/>';
		    	html += 'Location : '+ res.request_location +'<br/>';
		    	html += 'Description : '+ res.request_description +'<br/>';
		    	html += 'Instruction : '+ res.request_instruction +'<br/>';
		    	html += '<input type="hidden" name="talent_request_id" value="'+talent_request_id+'" />';

		    	$('#confirmModal .modal-body').html(html);
			    // Add response in Modal body
			    // $('.modal-body').html(response);

			    // Display Modal
			    $('#confirmModal').modal('show'); 
		    }
	  	});
	});

</script>
<script>
	if (window.history.replaceState) {
		window.history.replaceState( null, null, window.location.href );
	}
</script>
<?php include('footer.php'); ?>