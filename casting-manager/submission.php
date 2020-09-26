<?php
	// TESTING
	/* 
	if(is_admin()) {
		echo '<div id="debug" style="position:fixed; left:0; top:20px; overflow:auto; width:1200px; height:50px; color: white; background-color:black; padding:10px;">' . $_COOKIE['lightbox'] . '</div>';
	} */
	$page = "submission";
	$page_selected = "submission";
	include('header.php');
	include('../forms/definitions.php');
	include('../includes/agency_dash_functions.php');
?>

<?php
	// echo "<br/>";

	// ================

	$get_cond = "";
	if(isset($_GET['casting_id']) && isset($_GET['role_id'])){
		$casting_id = $_GET['casting_id'];
		$get_cond = " AND acr.casting_id = ".$casting_id."";

		$role_id = $_GET['role_id'];
	}else{
		$url_location="casting-call.php";
		header('location:'.$url_location);
	}

	// ================== view submission (remove from new submission)

	$remove_from_new_sql = "UPDATE agency_mycastings
							SET new_submission = 0
							WHERE role_id = ".$role_id."
							";
	$remove_from_new_res = mysql_query($remove_from_new_sql);

	// ==================

	$get_casting_submission_sql = "select am.*,acr.name from agency_mycastings am
							LEFT JOIN agency_castings_roles acr ON acr.role_id = am.role_id
							WHERE am.removed = 0 ".$get_cond."
							GROUP BY am.role_id
							";

	$get_casting_submission_res = mysql_query($get_casting_submission_sql);
	$submission = array();
	while($row = sql_fetchrow($get_casting_submission_res)) {
		$submission[] = $row;
	}

	$casting = array();
	$get_casting_sql = "select ac.* from agency_castings ac
							WHERE ac.casting_id = ".$casting_id."
							";
	$get_casting_res = mysql_query($get_casting_sql);
	while($row = sql_fetchrow($get_casting_res)) {
		$casting = $row;
	}

	// =====================

	if(isset($_POST['user_to_lightbox_submit'])){

		// echo "1111";
		// echo "<br/>";
		// echo "<pre>";
		// print_r($_POST);
		// echo "</pre>";

		$result_box = "Y";

		if($_POST['lightbox_id'] != ""){
			// old lightbox
			$lightbox_id = $_POST['lightbox_id'];
		}else{
			$timecode = time();
			$sql_lightbox_add = "INSERT INTO agency_lightbox
					SET 
					client_id = ".$_SESSION['user_id'].",
					lightbox_name = '".$_POST['title']."',
					lightbox_description = '".$_POST['description']."',
					casting_id = '".$casting_id."',
					timecode = '".$timecode."'
					";
			if(mysql_query($sql_lightbox_add)){
				$lightbox_id = mysql_insert_id();
			}
		}

		$l_users = explode(',',$_POST['users']);

		foreach($l_users as $usr){
			$lightvbox_user_check_sql = "select * from agency_lightbox_users alu
									WHERE lightbox_id = ".$lightbox_id."
									AND user_id = ".$usr."
									AND role_id = ".$role_id."
									";
			$lightvbox_user_check_res = mysql_query($lightvbox_user_check_sql);
			if(mysql_num_rows($lightvbox_user_check_res) > 0){
				// as it is
				// echo "1111";
				// echo "<br/>";
				// echo "<pre>";
				// print_r($usr);
				// echo "</pre>";
				// $add_success = "Y";
			}else{
				$sql_user_to_lightbox_add = "INSERT INTO agency_lightbox_users
					SET 
					lightbox_id = ".$lightbox_id.",
					user_id = '".$usr."',
					role_id = ".$role_id."
					";
				if(mysql_query($sql_user_to_lightbox_add)){
					$add_success = "Y";
				}
			}

			if($add_success == "Y"){
				$notification['success'] = "Users added To Lightbox Successfully";
			}
		}

		// echo "<pre>";
		// print_r($_POST);
		// exit;
	}

	// ==============

	if(isset($_POST['remove_from_submit']) && $_POST['remove_from_submit'] == 'Remove Checked From This Role'){
		// echo "<pre>";
		// print_r($_POST);
		// echo "</pre>";

		$remove_count = 0;
		foreach ($_POST['addme'] as $key => $value) {
			$user_remove_sub_sql = "UPDATE agency_mycastings
									SET 
									removed = '1'  
									WHERE  user_id = ".$value."
									AND role_id = ".$role_id."
									";
			if(mysql_query($user_remove_sub_sql)){
				$remove_count++;
			}
		}

		if($remove_count > 0){
			$notification['success'] = "Talent Submission Removed Successfully";
		}
	}

	// ================

	if(isset($_POST['add_to_audition']) && $_POST['add_to_audition'] == 'Add to Audition List'){
		// echo "<pre>";
		// print_r($_POST);
		// echo "</pre>";

		$add_audition = 0;
		foreach ($_POST['addme'] as $key => $value) {
			$user_audition_list_sql = "UPDATE agency_mycastings
									SET 
									audition_list = 'Y'  
									WHERE  user_id = ".$value."
									AND role_id = ".$role_id."
									";
			if(mysql_query($user_audition_list_sql)){
				$add_audition++;
			}
		}

		if($add_audition > 0){
			$notification['success'] = "Talent Added For Audition Successfully";
		}
	}

	// =============get audition

	$get_audition_sql = "SELECT am.*,ap.* FROM agency_mycastings am
								LEFT JOIN agency_castings_roles acr ON acr.role_id = am.role_id
								LEFT JOIN agency_profiles ap ON ap.user_id = am.user_id
								WHERE am.audition_list = 'Y'  
								AND acr.casting_id = ".$casting_id."
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
					<h3>Submissison<?php echo " - ".$casting['job_title']; ?></h3>
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
			        	<?php foreach($submission as $sub) { ?>
			        		<?php
			        			$display_role = "Y";
			        			if(isset($_GET['role_id'])){
			        				if($sub['role_id'] != $_GET['role_id']){
			        					$display_role = "N";
			        				}
			        			}
			        		?>

			        		<?php if($display_role == "Y"){ ?>
								<div class="box">
									<div class="box-header with-border">
										<h3 class="box-title">Role : <?php echo $sub['name']; ?></h3>
									</div>
									<div class="box-body">

										<div class="row-flex">
											<?php 
												$current = 1;
												$number_across = 3;
												$varemail="bookings@theagencyonline.com";
												$varphone="212-944-0801";

												$get_cond_role = "";
												if(isset($_GET['role_id'])){
													$role_id = $_GET['role_id'];
													$get_cond_role = " AND am.role_id = ".$role_id."";
												}

												$get_submission_role_sql = "select am.*,ap.*,fu.*,acr.name from agency_mycastings am
																		LEFT JOIN agency_castings_roles acr ON acr.role_id = am.role_id
																		LEFT JOIN agency_profiles ap ON ap.user_id = am.user_id
																		LEFT JOIN forum_users fu ON fu.user_id = ap.user_id
																		WHERE am.removed = 0 AND am.role_id = ".$sub['role_id']." 
																		".$get_cond_role."
																		GROUP BY am.user_id 
												";

												$get_submission_role_res = mysql_query($get_submission_role_sql);
												$submission_role = array();
											?>
											<?php while($row = sql_fetchrow($get_submission_role_res)) { ?>
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

														<p>
															<!-- onmouseout="document.getElementById('experience_popup').style.display='none'" 
															onmouseover="document.getElementById('experience_popup').style.display='' " -->
															<img src="<?php echo '../images/' . $experienceimages[$row['experience']] . '.gif'; ?>">
															<br/>
															submitted : <?php echo date('Y-m-d',strtotime($row[''])); ?>
														</p>
														<br />

														<p>
															<a href="./ajax/compcard_mini.php?u=<?php echo $uid; ?>&height=400&amp;width=450" class="thickbox"></a>
														</p>
														<p>
															<a href="<?php echo 'mailto:' . $row['user_email']; ?>"><?php echo $row['user_email']; ?></a>
														</p>

														<?php 
															if(!empty($row['phone']) && agency_privacy($uid, 'phone')) {
																echo '<p>' . $row['phone'] .'</p>';
															}
														?>

														<?php if(!empty($row['resume'])) { ?>
															<?php
																$resume_file = "";
																if(file_exists('../uploads/users/' . $row['user_id'] . '/resume/'. $row['resume'])){
									                       			$resume_file = '../uploads/users/' . $row['user_id'] . '/resume/'. $row['resume'];
									                       			
								                      			}
															?>
															<?php if($resume_file != "") { ?>
																<p>
																	<a href="<?php echo $resume_file; ?>" target="_blank">
																		<img src="../images/resume1.gif" border="0" style="padding-top:5px;" >
																	</a>
																</p>
															<?php } ?>
														<?php } ?>

														<?php 
														// check for reel/vo
														if( mysql_result(mysql_query("SELECT COUNT(*) as 'Num' FROM agency_vo WHERE user_id='$uid'"),0) || mysql_result(mysql_query("SELECT COUNT(*) as 'Num' FROM agency_reel WHERE user_id='$uid'"),0 )) {
															?>	
																<p>
																	<a target="_blank" href="<?php echo 'profile-view.php?user_id='.$uid; ?>#reel">
																		<img src="images/reelVO.gif" border="0" style="padding-top:5px;" >
																	</a>
																</p>
														<?php } ?>

														<?php 
															// UNION STATUS
															$sql4 = "SELECT * FROM agency_profile_unions WHERE user_id='$uid'";
															$result4 = mysql_query($sql4);
															$num_results4 = mysql_num_rows($result4);
															$current4 = 1;
														?>
														<?php if($num_results4) { ?>
															<p>
																<span class="AGENCYCompCardLabel">Union: </span>
																<span class="AGENCYCompCardStat">
																	<?php
																		while($row4 = sql_fetchrow($result4)) {
																			echo escape_data($row4['union_name']);
																			if($current4 < $num_results4) echo ', ';
																			$current4++;
																		}
																	?>
																</span>
															</p>
														<?php } ?>

													</div>
												</div>
											<?php } ?>

											<?php
												if($current == $number_across) {
													$current = 1;
												} else {
													$current++;
												}
											?>

										</div>

									</div>
								</div>
							<?php } ?>

						<?php } ?>

						<div class="box no-border">
							<div class="box-body">

								<div class="row text-center">
									<div class="col-sm-12" style="margin-bottom:10px;">
										<button class="btn btn-theme btn-flat check_all_btn btn-sm" value="">Check All</button>
										<button class="btn btn-theme btn-flat uncheck_all_btn btn-sm" value="">Uncheck All</button>
									</div>
									<div class="col-sm-12">
										<button class="btn btn-success btn-flat add_to_lightbox_btn btn-sm" value="">Add Checked To Lightbox</button>
										<input type="submit" name="remove_from_submit" class="btn btn-danger btn-flat remove_user_from_submission btn-sm" value="Remove Checked From This Role">
										<input type="submit" name="add_to_audition" class="btn btn-success btn-flat add_to_audition btn-sm" value="Add to Audition List">
									</div>
								</div>

							</div>
						</div>
					</form>
					
				</div>

				<div class="col-sm-3 col-md-3 col-xs-12">

					<div class="box">
						<div class="box-header with-border">
							<h3 class="box-title">Audition List</h3>
						</div>
						<div class="box-body">
							<table class="table table-striped">
								<?php if(!empty($audition)){ ?>
									<?php foreach($audition as $row){ ?>
										<tr>
											<td>
												<?php echo $row['firstname'].' '.$row['lastname']; ?>
											</td>
										</tr>
									<?php } ?>
									<tr>
										<td class="text-center">
											<a href="<?php echo 'audition.php?casting_id='.$casting_id.'&role_id='.$role_id;?>" class="btn btn-sm btn-theme btn-flat pull-right">View Audition </a>
										</td>
									</tr>
								<?php }else{ ?>
									
									<tr>
										<td> Audition List Is Empty </td>
									</tr>
								<?php } ?>
							</table>
						</div>
					</div>

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
											<a href="<?php echo 'booked_audition.php?casting_id='.$casting_id.'&role_id='.$role_id;?>" class="btn btn-sm btn-theme btn-flat pull-right">View Booked Audition </a>
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

	    $(".add_to_lightbox_btn").click(function(e){
	    	e.preventDefault();

	    	total_check = 0;
	    	check_users = [];
	    	$('.user_check').each(function(index, value) {
	    		if($(this).prop("checked") == true){
	    			total_check++;
	    			check_users.push($(this).val());
	            }
		    });

	    	if(total_check > 0){

				$.ajax({
			        url: '../ajax/dashboard_request.php',
			        type: 'post',
			        data: 'check_users='+check_users+'&name=lightbox_form_box',
			        // dataType: 'json',
			        success: function(response){
			          // console.log(response);
			          $('#lightbox_form_Modal').html(response);

			          // Display Modal
			          $('#lightbox_form_Modal').modal('show');
			        }
			    }); 

	    	}else{
	    		alert('Please Select Atleast One Talent');
	    	}

	    });

	    $(document).on('submit', '.lightbox_form', function(e) {
			// e.preventDefault();

			title = $('#title').val();
			lightbox_id = $('#lightbox_id').val();

			if(title == "" && lightbox_id ==""){
				e.preventDefault();
				$('#user-to-lightbox-err').html('Please add New lightbox or select any one.');
				$('#user-to-lightbox-err').css('display','block');
				return false;
			}else{
				$('.lightbox_form').submit();
			}
		});

	    $(".remove_user_from_submission").click(function(e){
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
		
		$(".add_to_audition").click(function(e){
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