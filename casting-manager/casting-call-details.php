<?php
  // TESTING
  /* 
    if(is_admin()) {
      echo '<div id="debug" style="position:fixed; left:0; top:20px; overflow:auto; width:1200px; height:50px; color: white; background-color:black; padding:10px;">' . $_COOKIE['lightbox'] . '</div>';
    } */
	$page = "casting_call_list";
	$page_selected = "casting_calls";
	include('header.php');
	include('../forms/definitions.php');
	include('../includes/agency_dash_functions.php');

    if(isset($_GET['lightbox'])){
    	$lightbox_id = $_GET['lightbox'];
    }
?>

    <div id="page-wrapper">
        <div class="" id="main">

	        <div class="row">
	            <div class="col-sm-12 col-md-12">
	              	
	              	
							<div class="casting-details">
								<div class="col-sm-7">

									<div class="box no-border">
										<div class="box-header with-border">
											<div class="row">
												<div class="col-sm-6">
													<h3 class="box-title">MY CASTINGS</h3>
												</div>
												<div class="col-sm-6 text-right">
													<a href="casting-update.php" class="btn btn-primary btn-sm"><i class="fa fa-plus"> </i> New Casting</a>
												</div>
											</div>
										</div>
		              					<div class="box-body">
		              						<table class="datatable table-striped table">
		              							<thead>
		              								<tr>
		              									<td>Project</td>
		              									<td>Submission</td>
		              									<td>Status</td>
		              									<td></td>
		              								</tr>
		              							</thead>
		              							<tbody>
		              								<?php
		              									$sql_casting = "SELECT * FROM agency_castings WHERE casting_director=".$_SESSION['user_id']." AND deleted='0' ORDER BY post_date DESC";
		              									$res_casting = mysql_query($sql_casting);
		              								?>
		              								<?php if(mysql_num_rows($res_casting) == 0){ ?>
		              									<tr>
			              									<td colspan="4">You have not created any castings yet.</td>
			              								</tr>
		              								<?php }else{ ?>
		              									<?php while($row = sql_fetchrow($res_casting)) { ?> 
															<tr>
				              									<td><?php echo $row['job_title']; ?></td>
				              									<td>
				              										<?php
				              											$sql_submission = "SELECT * FROM agency_mycastings, agency_castings_roles WHERE agency_mycastings.role_id=agency_castings_roles.role_id AND agency_castings_roles.casting_id=".$row['casting_id']." AND agency_mycastings.removed='0'";
																			$res_submission=mysql_query($sql_submission);
																			$num_submission = mysql_num_rows($res_submission);	
																	?>
																	<label class="label label-primary"><?php echo $num_submission; ?></label>
																	<?php if($num_submission > 0){ ?>
																		<?php 
																			$sql_submission_new = "SELECT * FROM agency_mycastings, agency_castings_roles WHERE agency_mycastings.role_id=agency_castings_roles.role_id AND agency_castings_roles.casting_id=".$row['casting_id']." AND agency_mycastings.new_submission='1' AND agency_mycastings.removed='0'";
																				$res_submission_new=mysql_query($sql_submission_new);
																				$num_submission_new = mysql_num_rows($res_submission_new);
																		?>
																		<?php if($num_submission_new > 0){ ?>
																			<label class="label label-danger"><?php echo $num_submission_new; ?> New</label>
																		<?php } ?>
																	<?php } ?>
				              									</td>
				              									<td>
				              										<?php if($row['live'] == '1'){ ?> 
				              											<label class="label label-success">Live</label>
				              										<?php }else{ ?>
				              											<label class="label label-alert">Not Live</label>
				              										<?php } ?>
				              									</td>
				              									<td>
				              										<a class="btn btn-warning btn-sm btn-flat" href="<?php echo 'casting-view.php?casting_id='.$row['casting_id']; ?>">View</a>
				              										<a class="btn btn-info btn-sm btn-flat" href="<?php echo 'casting-update.php?casting_id='.$row['casting_id']; ?>">Edit</a>
				              									</td>
				              								</tr>
			              								<?php } ?>
		              								<?php } ?>
		              								
		              							</tbody>
		              						</table>
		              					</div>
		              				</div>

		              				<div class="box no-border">
	              						<div class="box-header with-border">
											<div class="row">
												<div class="col-sm-6">
													<h3 class="box-title">MY LIGHTBOXES</h3>
												</div>
												<div class="col-sm-6 text-right">
													<a href="clienthome.php" class="btn btn-primary btn-sm"><i class="fa fa-plus"> </i> Add Talent To Lightbox</a>
												</div>
											</div>
										</div>
										<div class="box-body">
											<?php
												$sql_lightbox = "SELECT * FROM agency_lightbox WHERE client_id=".$_SESSION['user_id']."";
												$result_lightbox = mysql_query($sql_lightbox);
												if(mysql_num_rows($result_lightbox) == 0) {
													echo '<br /><br />You have not created any lightbox yet.<br /><br />';
												}
											?>

											<table class="datatable_lightbox table-striped table">
		              							<thead>
		              								<tr>
		              									<td>Lightbox</td>
		              									<td></td>
		              								</tr>
		              							</thead>
		              							<tbody>
		              								<?php
		              									$sql_lightbox = "SELECT * FROM agency_lightbox WHERE client_id=".$_SESSION['user_id']."";
														$result_lightbox = mysql_query($sql_lightbox);
		              								?>
		              								<?php if(mysql_num_rows($result_lightbox) == 0){ ?>
		              									<tr>
			              									<td colspan="2">You have not created any lightbox yet.</td>
			              								</tr>
		              								<?php }else{ ?>
		              									<?php while($row = sql_fetchrow($result_lightbox)) { ?> 
															<tr>
				              									<td><?php echo $row['lightbox_name']; ?></td>
				              									<td><a class="btn btn-sm btn-flat bg-navy" href="lightbox.php?lightbox=<?php echo $row['lightbox_id']; ?>">View</a></td>
				              								</tr>
			              								<?php } ?>
		              								<?php } ?>
		              							</tbody>
		              						</table>
										</div>
									</div>

								</div>
							   
								<div class="col-sm-5">
									<?php include('quick_search.php'); ?>

									<div class="box no-border">
	              						<div class="box-header with-border">
											<h3 class="box-title">Newest Talent</h3>
										</div>
										<div class="box-body">
											<?php 
												$sql_new_talent = "SELECT * FROM forum_users fu 
																	LEFT JOIN agency_profiles ap ON ap.user_id = fu.user_id
																	WHERE account_type = 'talent'
																	ORDER BY ap.created_at DESC LIMIT 20";
												$result_new_talent = mysql_query($sql_new_talent);
											?>
											<?php while($row = sql_fetchrow($result_new_talent)) { ?>
												<a href="profile-view.php?user_id=<?php echo $row['user_id']; ?>" style="" class="new-talent-img-box">
													<?php 
														if(file_exists('../uploads/users/' . $row['user_id'] . '/profile_pic/thumb/128x128_' . $row['user_avatar'])) {
															$user_avatar = '../uploads/users/' . $row['user_id'] . '/profile_pic/thumb/128x128_' . $row['user_avatar'];
														}else{
															$user_avatar = '../images/friend.gif';
														}
													?>
													<img src="<?php echo $user_avatar; ?>" />
												</a>
											<?php } ?>
										</div>
									</div>

									<!-- <div class="Newest"> -->
										<!-- <div class="casted">
										    <div style="width: 100%; float: left;">
										    <h3 style="float: left;color: #FF5722">Newest Talent</h3>
											<h4 style="float: right;color: #00BCD4;padding-top: 15px;"></h4>
										</div> -->
									    <?php
											// $columns = 3;

											// $sql = "SELECT searchquery FROM agency_search_matches WHERE user_id='$profileid'";
											// $result = mysql_query($sql);
											// if($row = sql_fetchrow($result)) {
											// 	$sql2 = $row['searchquery'];
											// } else {
											// 	$sql2 = "SELECT p.user_id, p.registration_date FROM agency_profiles p, forum_users u WHERE p.account_type='talent' AND p.user_id=u.user_id AND u.user_type='0' ORDER BY p.payProcessedDate DESC, p.user_id DESC LIMIT 27";
											// }
											// $result2=mysql_query($sql2);
											// while($row2 = sql_fetchrow($result2)) {
											// 	$friendid = $row2['user_id'];
											// 	$posterfolder = 'images/' . $friendid . '_' . $row2['registration_date'] . '.jpg';
											// 	echo '<div class="AGENCYTalentThumbnail"><a href="#"><img src="';
											// 		if(file_exists($posterfolder . 'avatar.jpg')) {
											// 			echo   $posterfolder . 'avatar.jpg';
											// 		} else if(file_exists($posterfolder . 'avatar.gif')) {
											// 			echo   $posterfolder . 'avatar.gif';
											// 		} else {
											// 			echo $posterfolder;
											// 		}
											// 	echo '" /></a></div>';
											// 	$columns--;
											// 	if($columns == 0) {
											// 		$columns = 3;
											// 		echo '<br clear="all" />';
											// 	}
											// }
										?>
									<!-- </div> -->

									<div class="box no-border">
										<div class="box-body text-center">
											<a class="padding-5 text-theme" href="<?php echo get_agency_var('fb_link'); ?>"><i class="fa fa-facebook fa-2x"></i></a>
											<a class="padding-5 text-theme" href="<?php echo get_agency_var('twitter_link'); ?>"><i class="fa fa-twitter fa-2x"></i></a>
											<a class="padding-5 text-theme" href="<?php echo get_agency_var('youtube_link'); ?>"><i class="fa fa-youtube fa-2x"></i></a>
											<a class="padding-5 text-theme" href="<?php echo get_agency_var('instagram_link'); ?>"><i class="fa fa-instagram fa-2x"></i></a>
										</div>
									</div>

								</div>

							</div>

	           
	          	</div>
	        </div>

        </div>
	</div>

<?php include('footer_js.php'); ?>
<script src="../dashboard/assets/DataTables/datatables.min.js"></script> 
<script src="../dashboard/assets/DataTables/dataTables.bootstrap.min.js"></script> 

<!-- <script type="text/javascript" src="../dashboard/assets/jQuery-DateTimepicker-Addon/jquery-ui.min.js"></script> -->

<!-- <script type="text/javascript" src="http://code.jquery.com/jquery-1.11.1.min.js"></script> -->
<!-- <script type="text/javascript" src="http://code.jquery.com/ui/1.11.0/jquery-ui.min.js"></script>
<script type="text/javascript" src="../dashboard/assets/jQuery-DateTimepicker-Addon/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript" src="../dashboard/assets/jQuery-DateTimepicker-Addon/i18n/jquery-ui-timepicker-addon-i18n.min.js"></script>
<script type="text/javascript" src="../dashboard/assets/jQuery-DateTimepicker-Addon/jquery-ui-sliderAccess.js"></script> -->

<!-- <script type="text/javascript" src="../dashboard/assets/js/jquery.validate.js"></script> -->

<script>
  $(function() {
  	  $('.datatable').DataTable({
        "order": [[ 0, "desc" ]],
        'columnDefs': [{
		    'targets': [3], /* column index */
		    'orderable': false, /* true or false */
		}]
    });
  });
</script>

<?php include('footer.php'); ?>