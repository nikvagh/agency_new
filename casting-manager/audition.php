<?php
	$page = "submission";
	$page_selected = "submission";
	include('header.php');
	include('../forms/definitions.php');
	include('../includes/agency_dash_functions.php');
?>

<?php
	// echo "<br/>";

	// ================

	if(isset($_GET['casting_id']) && isset($_GET['role_id'])){
		$casting_id = $_GET['casting_id'];
		$role_id = $_GET['role_id'];
	}else{
		$url_location="casting-call.php";
		header('location:'.$url_location);
	}


	// ===================

	if(isset($_POST['remove_from_audition']) && $_POST['remove_from_audition'] == 'Remove from Audition List'){
		// echo "<pre>";
		// print_r($_POST);
		// echo "</pre>";

		$add_audition = 0;
		foreach ($_POST['addme'] as $key => $value) {
			$user_audition_list_sql = "UPDATE agency_mycastings
									SET 
									audition_list = 'N'  
									WHERE  user_id = ".$value."
									AND role_id = ".$role_id."
									";
			if(mysql_query($user_audition_list_sql)){
				$add_audition++;
			}
		}

		if($add_audition > 0){
			$notification['success'] = "Talent remove From Audition Successfully";
		}
	}

	// ================= get casting

	$get_casting_sql = "SELECT ac.* FROM agency_castings ac
								WHERE ac.casting_id = ".$casting_id."
							";
	$get_casting_res = mysql_query($get_casting_sql);
	$casting = array();
	while($row = sql_fetchrow($get_casting_res)) {
		$casting = $row;
	}

	// ================

	if(isset($_POST['book_for_audition']) && $_POST['book_for_audition'] == 'Book For Audition'){
		// echo "<pre>";
		// print_r($_POST);
		// echo "</pre>";

		$add_booked_audition = 0;
		foreach ($_POST['addme'] as $key => $value) {
			$user_booked_audition_sql = "UPDATE agency_mycastings
									SET 
									audition_book = 'Y'  
									WHERE  user_id = ".$value."
									AND role_id = ".$role_id."
									";
			if(mysql_query($user_booked_audition_sql)){

				$sqlIns = "INSERT INTO agency_talent_request
	    		SET casting_id = '".$_POST['casting_id']."',
	    			user_id = ".$value.",
	    			request_by = ".$_SESSION['user_id'].",
	    			request_date = '".date('Y-m-d', strtotime($casting['casting_date']))."',
	    			request_time = '".$_POST['req_time']."',
	    			request_for = 'casting',
	    			request_location = '".$casting['location_casting']."',
	    			request_description = '',
	    			request_instruction = '',
	    			request_status = 'pending'
	    		";

				mysql_query($sqlIns);
				$add_booked_audition++;
			}
		}

		if($add_booked_audition > 0){
			$notification['success'] = "Talent Booked For Audition Successfully";
		}
	}

	// ===============

	$get_audition_sql = "SELECT am.*,ap.*,fu.* FROM agency_mycastings am
								LEFT JOIN agency_castings_roles acr ON acr.role_id = am.role_id
								LEFT JOIN agency_profiles ap ON ap.user_id = am.user_id
								LEFT JOIN forum_users fu ON fu.user_id = ap.user_id
								WHERE am.audition_list = 'Y'  
								AND acr.casting_id = ".$casting_id."
								AND am.role_id = ".$role_id."
								GROUP BY am.submission_id
							";
	$get_audition_res = mysql_query($get_audition_sql);
	$audition = array();
	while($row = sql_fetchrow($get_audition_res)) {
		$audition[] = $row;
	}

	

	// =============get booked

	$get_book_audition_sql = "SELECT am.*,ap.* FROM agency_mycastings am
								LEFT JOIN agency_castings_roles acr ON acr.role_id = am.role_id
								LEFT JOIN agency_profiles ap ON ap.user_id = am.user_id
								LEFT JOIN forum_users fu ON fu.user_id = ap.user_id
								WHERE am.audition_list = 'Y' AND am.audition_book = 'Y' 
								AND acr.casting_id = ".$casting_id."
								GROUP BY am.submission_id
							";
	$get_book_audition_res = mysql_query($get_book_audition_sql);
	$book_audition = array();
	while($row = sql_fetchrow($get_book_audition_res)) {
		$book_audition[] = $row;
	}

?>

<div id="page-wrapper">
	<div class="" id="main">

			<div class="row">

				<div class="col-sm-12 col-md-12 col-xs-12">
					<h3>Audition<?php echo " - ".$casting['job_title']; ?></h3>
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
				</div>

				<div class="col-sm-9 col-md-9 col-xs-12">
			        <form action="" method="post" name="resultform">

			        	
				        	<div class="box">
								<!-- <div class="box-header with-border">
									<h3 class="box-title">Role : <?php //echo $sub['name']; ?></h3>
								</div> -->
								<div class="box-body">
									<div class="row-flex">

										<?php if(!empty($audition)) { ?>
								        	<?php foreach($audition as $row) { ?>
												<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12" style="margin-bottom:30px;">
													<div class="card" style="height: 100%">
														<?php
															$uid = $row['user_id'];
															$email = $row['user_email'];
															$displayname = $row['firstname'].' '.$row['lastname'];

															if(file_exists('../uploads/users/' . $row['user_id'] . '/profile_pic/thumb/'. '128x128_' . $row['user_avatar'])){
								                       			$profile_pic = '../uploads/users/' . $row['user_id'] . '/profile_pic/thumb/'. '128x128_' . $row['user_avatar'];
							                      			}else{
							                      				$profile_pic = '../images/friend.gif';
							                      			}
														?>
														<a style="height: 128px;"><img src="<?php echo $profile_pic; ?>" /></a>
														<br />
														
														<label>
															<input type="checkbox" id="<?php echo 'addme_' . $uid; ?>" name="addme[<?php echo $uid; ?>]" value="<?php echo $uid; ?>" class="user_check" />
															<?php echo $displayname; ?>
														</label>

													</div>
												</div>	
											<?php } ?>
										<?php }else{ ?>
												<div class="col-md-12 text-center">
													NO Audition Available For This Role.
												</div>
										<?php } ?>

									</div>
								</div>
							</div>
						

						<?php if(!empty($audition)) { ?>
							<div class="box no-border">
								<div class="box-body">

									<div class="row text-center">
										<div class="col-sm-12" style="margin-bottom:10px;">
											<button class="btn btn-theme btn-flat check_all_btn btn-sm" value="">Check All</button>
											<button class="btn btn-theme btn-flat uncheck_all_btn btn-sm" value="">Uncheck All</button>
										</div>
										<div class="col-sm-12">
											<input type="submit" name="remove_from_audition" class="btn btn-danger btn-flat remove_from_audition btn-sm" value="Remove from Audition List">
											<input type="submit" name="book_for_audition" class="btn btn-success btn-flat book_for_audition btn-sm" value="Book For Audition">
										</div>
									</div>

								</div>
							</div>
						<?php } ?>
					</form>
					
				</div>

				<div class="col-sm-3 col-md-3 col-xs-12">

					<?php if(!empty($audition)) { ?>
						<div class="box">
							<div class="box-header with-border">
								<h3 class="box-title">Book For Audition</h3>
							</div>
							<div class="box-body">
								<table class="table table-striped">
									<?php if(!empty($book_audition)){ ?>
										<?php foreach($book_audition as $row){ ?>
											<tr>
												<td>
													<?php echo $row['firstname'].' '.$row['lastname']; ?>
												</td>
											</tr>
										<?php } ?>
										<tr>
											<td class="text-center">
												<a href="<?php echo 'booked_audition.php?casting_id='.$casting_id.'&role_id='.$role_id;?>" class="btn btn-sm btn-theme btn-flat pull-right" target="_blank">View Booked Audition </a>
											</td>
										</tr>
									<?php }else{ ?>
										<tr>
											<td> Booking List Is Empty </td>
										</tr>
									<?php } ?>
								</table>
							</div>
						</div>
					<?php } ?>

				</div>

			</div>

	</div>
</div>

<div class="modal fade" id="lightbox_form_Modal" role="dialog"></div>

<?php include('footer_js.php'); ?>
<!-- <script src="../dashboard/assets/DataTables/datatables.min.js"></script> 
<script src="../dashboard/assets/DataTables/dataTables.bootstrap.min.js"></script> 
 -->
<!-- <script type="text/javascript" src="../dashboard/assets/jQuery-DateTimepicker-Addon/jquery-ui.min.js"></script> -->

<!-- <script type="text/javascript" src="http://code.jquery.com/jquery-1.11.1.min.js"></script> -->
<!-- <script type="text/javascript" src="http://code.jquery.com/ui/1.11.0/jquery-ui.min.js"></script>
<script type="text/javascript" src="../dashboard/assets/jQuery-DateTimepicker-Addon/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript" src="../dashboard/assets/jQuery-DateTimepicker-Addon/i18n/jquery-ui-timepicker-addon-i18n.min.js"></script>
<script type="text/javascript" src="../dashboard/assets/jQuery-DateTimepicker-Addon/jquery-ui-sliderAccess.js"></script> -->

<!-- <script type="text/javascript" src="../dashboard/assets/js/jquery.validate.js"></script> -->

<script>
	$(document).ready( function () {
	  //   $('.datatable').DataTable({
	  //       "order": [[ 0, "desc" ]],
	  //       'columnDefs': [{
			//     'targets': [3], /* column index */
			//     'orderable': false, /* true or false */
			// }]
	  //   });

	    $(".check_all_btn").click(function(e){
	    	e.preventDefault();
	    	$('.user_check').prop('checked', true);
	    });

	    $(".uncheck_all_btn").click(function(e){
	    	e.preventDefault();
	    	$('.user_check').prop('checked', false);
	    });

	    $(".remove_from_audition").click(function(e){
		    total_check = 0;
		    $('.user_check').each(function(index, value) {
				if($(this).prop("checked") == true){
					total_check++;
		        }
		    });
		    // console.log(total_check);

		    if(total_check > 0){
		    	// console.log(total_check);
		    	return true;
		    }else{
	    		alert('Please Select Atleast One Talent');
	    		return false;
	    	}
	    });
		
		$(".book_for_audition").click(function(e){
		    total_check = 0;
		    $('.user_check').each(function(index, value) {
				if($(this).prop("checked") == true){
					total_check++;
		        }
		    });
		    if(total_check > 0){
		    	// console.log(total_check);
		    	return true;
		    }else{
	    		alert('Please Select Atleast One Talent');
	    		return false;
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