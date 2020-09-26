<?php
	include('header_code.php');
	include('includes/agency_dash_functions.php');

	// echo "<pre>";print_r($_SESSION);
	// exit;

	$notification = array();

	if($_GET['lightbox_id']){
		$lightbox_id = $_GET['lightbox_id'];
	}

	// remove talent
	if(isset($_POST['submit']) && $_POST['submit'] == "remove"){
		foreach($_POST['addme'] as $key=>$val){
			// echo $val;
			$dlt_sql = "DELETE FROM agency_lightbox_users WHERE entry_id = ".$val;
			mysql_query($dlt_sql);
		}
		// $_POST['addme'];

		$notification['success'] = "Talent Remove from lightbox successfully";
	}

	// send lbox to frd
	if(isset($_POST['send_lightbox_to_frd']) && $_POST['send_lightbox_to_frd'] == "send"){
		// echo "<pre>";
		// print_r($_POST);
		// echo "</pre>";
		$to_email = $_POST['email_recipient'];
		$subject = 'lightbox';
		$msg = $_POST['email_message'];
		if(send_mail($to_email,$subject,$msg)){
			$notification['success'] = "Notification sent successfully";
		}
	}

	// copy lightbox
	if(isset($_POST['copy_lbox']) && $_POST['copy_lbox'] == "COPY"){
		// echo "<pre>";
	  	// print_r($_POST);
		// echo "</pre>";
		// exit;

		$sql_lightbox = "SELECT * FROM agency_lightbox WHERE lightbox_id=".$lightbox_id."";
		$query_lightbox = mysql_query($sql_lightbox);

		if (mysql_num_rows($query_lightbox) > 0) {
			if ($row = mysql_fetch_assoc($query_lightbox)) {
				$lightbox = $row;
			}
		}

	  	$time = time();
	  	$casting_id_ins = "";
	  	// if(isset($_POST['keep_roles'])){
	  		if($lightbox['casting_id'] != ""){
			  	$casting_id_ins = "casting_id = ".$lightbox['casting_id'].",";
			}
		// }

	  	$sql_lightbox_ins = "INSERT INTO agency_lightbox 
	  				SET 
	  				client_id = ".$_SESSION['user_id'].",
	  				lightbox_name = '".$_POST['copy_name']."',
	  				lightbox_description = '".$_POST['copy_description']."',
	  				".$casting_id_ins."
	  				timecode = '".$time."'
	  			";
	  	if(mysql_query($sql_lightbox_ins)){
			$new_lightbox_id = mysql_insert_id();

			if(isset($_POST['keep_roles'])){
				$lightbox_users_sql = "select alu.*,al.*,ap.*,fu.*,group_concat(apu.union_name) as union_name from agency_lightbox_users alu
								LEFT JOIN agency_lightbox al ON al.lightbox_id = alu.lightbox_id
								LEFT JOIN agency_profiles ap ON alu.user_id = ap.user_id
								LEFT JOIN forum_users fu ON fu.user_id = ap.user_id
								LEFT JOIN agency_profile_unions apu ON apu.user_id = ap.user_id
								WHERE alu.lightbox_id = ".$lightbox_id."
								GROUP BY ap.user_id";

				$result_lightbox_users = mysql_query($lightbox_users_sql);
				$lightbox_users = array();
				while($row = sql_fetchrow($result_lightbox_users)) { 
					$lightbox_users[] = $row;
				}

				foreach($lightbox_users as $l_user_ins){

					$role_id_ins = "";
					// if(isset($_POST['keep_roles'])){
						// if($l_user_ins['role_id'] != ""){
						// 	$role_id_ins = "role_id = ".$l_user_ins['role_id'].",";
						// }
					// }

					// ".$role_id_ins."
					$sql_lightbox_user_ins = "INSERT INTO agency_lightbox_users 
						SET 
						role_id = ".$l_user_ins['role_id'].",
						lightbox_id = ".$new_lightbox_id.",
						user_id = ".$l_user_ins['user_id']."
					";

					mysql_query($sql_lightbox_user_ins);
				}

			}

	  		$notification['success'] = "Lightbox Copied Successfully";

	  	}
	}

	// send message
	if(isset($_POST['msg_lbox']) && $_POST['msg_lbox'] == "SEND"){
		$add_msg_cnt = 0;

		$lightbox_users_sql = "select alu.*,al.*,ap.*,fu.*,group_concat(apu.union_name) as union_name from agency_lightbox_users alu
							LEFT JOIN agency_lightbox al ON al.lightbox_id = alu.lightbox_id
							LEFT JOIN agency_profiles ap ON alu.user_id = ap.user_id
							LEFT JOIN forum_users fu ON fu.user_id = ap.user_id
							LEFT JOIN agency_profile_unions apu ON apu.user_id = ap.user_id
							WHERE alu.lightbox_id = ".$lightbox_id."
							GROUP BY ap.user_id";

		$result_lightbox_users = mysql_query($lightbox_users_sql);
		$lightbox_users = array();
		while($row = sql_fetchrow($result_lightbox_users)) { 
			$lightbox_users[] = $row;
		}

		foreach($lightbox_users as $l_user_ins){

			$send_array = array(
				'message' => $_POST['light_message'],
				'subject' => $_POST['light_subject'],
				'user' => $l_user_ins['user_id'],
				'lightbox_id' => $lightbox_id
			);

			if(send_message_dash($send_array)){
				$add_msg_cnt++;
			}

			if($add_msg_cnt > 0){
				$notification['success'] = "Message Send Successfully";
			}

		}
	}

	// auto_notify
	if(isset($_POST['auto_notify'])  && $_POST['auto_notify'] == "admin: auto-notify"){

		if($_POST['lightbox_type'] == "auto_find"){

			$sql_lightbox = "SELECT al.*,ac.job_title FROM agency_lightbox al
						LEFT JOIN agency_castings ac ON ac.casting_id = al.casting_id
						WHERE lightbox_id=".$lightbox_id."";
			$query_lightbox = mysql_query($sql_lightbox);

			if (mysql_num_rows($query_lightbox) > 0) {
				if ($row = mysql_fetch_assoc($query_lightbox)) {
					$lightbox = $row;
				}
			}

			$lightbox_users_sql = "select alu.*,al.*,ap.*,fu.*,group_concat(apu.union_name) as union_name from agency_lightbox_users alu
								LEFT JOIN agency_lightbox al ON al.lightbox_id = alu.lightbox_id
								LEFT JOIN agency_profiles ap ON alu.user_id = ap.user_id
								LEFT JOIN forum_users fu ON fu.user_id = ap.user_id
								LEFT JOIN agency_profile_unions apu ON apu.user_id = ap.user_id
								WHERE alu.lightbox_id = ".$lightbox_id."
								GROUP BY ap.user_id";

			$result_lightbox_users = mysql_query($lightbox_users_sql);
			$lightbox_users = array();
			while($row = sql_fetchrow($result_lightbox_users)) { 
				$lightbox_users[] = $row;
			}

			foreach($lightbox_users as $l_user_ins){
				$to_email = $l_user_ins['user_email'];
				$subject = 'New Casting: '.$lightbox['job_title'];
				$msg = $l_user_ins['firstname'].' '.$l_user_ins['lastname'].', <br/>';
				$msg .= 'We are informing you that new casting '.$lightbox['job_title'].' is uploaded. your profile is match with this casting requirements. <br/>';
				$msg .= 'Thank you, <br/>';
				$msg .= 'TheAgencyOnline.com';
				if(send_mail($to_email,$subject,$msg)){
					$notification['success'] = "Notification sent successfully";
				}
			}

		}elseif($_POST['lightbox_type'] == "auto_submit"){

			$sql_lightbox = "SELECT al.*,ac.job_title FROM agency_lightbox al
						LEFT JOIN agency_castings ac ON ac.casting_id = al.casting_id
						WHERE lightbox_id=".$lightbox_id."";
			$query_lightbox = mysql_query($sql_lightbox);

			if (mysql_num_rows($query_lightbox) > 0) {
				if ($row = mysql_fetch_assoc($query_lightbox)) {
					$lightbox = $row;
				}
			}

			$lightbox_users_sql = "select alu.*,al.*,ap.*,fu.*,group_concat(apu.union_name) as union_name from agency_lightbox_users alu
								LEFT JOIN agency_lightbox al ON al.lightbox_id = alu.lightbox_id
								LEFT JOIN agency_profiles ap ON alu.user_id = ap.user_id
								LEFT JOIN forum_users fu ON fu.user_id = ap.user_id
								LEFT JOIN agency_profile_unions apu ON apu.user_id = ap.user_id
								WHERE alu.lightbox_id = ".$lightbox_id."
								GROUP BY ap.user_id";

			$result_lightbox_users = mysql_query($lightbox_users_sql);
			$lightbox_users = array();
			while($row = sql_fetchrow($result_lightbox_users)) { 
				$lightbox_users[] = $row;
			}

			foreach($lightbox_users as $l_user_ins){
				$to_email = $l_user_ins['user_email'];
				$subject = 'New Casting: '.$lightbox['job_title'];
				$msg = $l_user_ins['firstname'].' '.$l_user_ins['lastname'].', <br/>';
				$msg .= 'Thank you for submit your profile for casting '.$lightbox['job_title'].'. Our Team Will contact to you soon. <br/>';
				$msg .= 'Thank you, <br/>';
				$msg .= 'TheAgencyOnline.com';
				if(send_mail($to_email,$subject,$msg)){
					$notification['success'] = "Notification sent successfully";
				}
			}
			
		}

	}

	// email to
	if(isset($_POST['email_to'])  && $_POST['email_to'] == "admin: email to"){
		// echo "<pre>";
		// print_r($_POST);
		// echo "</pre>";
		// exit;

		if($_POST['lightbox_type'] == "auto_find"){

			$sql_lightbox = "SELECT al.*,ac.job_title FROM agency_lightbox al
							LEFT JOIN agency_castings ac ON ac.casting_id = al.casting_id
							WHERE lightbox_id=".$lightbox_id."";
			$query_lightbox = mysql_query($sql_lightbox);

			if (mysql_num_rows($query_lightbox) > 0) {
				if ($row = mysql_fetch_assoc($query_lightbox)) {
					$lightbox = $row;
				}
			}

			$lightbox_users_sql = "select alu.*,al.*,ap.*,fu.*,group_concat(apu.union_name) as union_name from agency_lightbox_users alu
								LEFT JOIN agency_lightbox al ON al.lightbox_id = alu.lightbox_id
								LEFT JOIN agency_profiles ap ON alu.user_id = ap.user_id
								LEFT JOIN forum_users fu ON fu.user_id = ap.user_id
								LEFT JOIN agency_profile_unions apu ON apu.user_id = ap.user_id
								WHERE alu.lightbox_id = ".$lightbox_id."
								AND alu.entry_id IN (".implode(',',$_POST['addme']).")
								GROUP BY ap.user_id";

			$result_lightbox_users = mysql_query($lightbox_users_sql);
			$lightbox_users = array();
			while($row = sql_fetchrow($result_lightbox_users)) { 
				$lightbox_users[] = $row;
			}

			foreach($lightbox_users as $l_user_ins){
				$to_email = $l_user_ins['user_email'];
				$subject = 'New Casting: '.$lightbox['job_title'];
				$msg = $l_user_ins['firstname'].' '.$l_user_ins['lastname'].', <br/>';
				$msg .= 'We are informing you that new casting '.$lightbox['job_title'].' is uploaded. your profile is match with this casting requirements. <br/>';
				$msg .= 'Thank you, <br/>';
				$msg .= 'TheAgencyOnline.com';
				if(send_mail($to_email,$subject,$msg)){
					$notification['success'] = "Notification sent successfully";
				}
			}

		}else if($_POST['lightbox_type'] == "auto_submit"){
			
			$sql_lightbox = "SELECT al.*,ac.job_title FROM agency_lightbox al
							LEFT JOIN agency_castings ac ON ac.casting_id = al.casting_id
							WHERE lightbox_id=".$lightbox_id."";
			$query_lightbox = mysql_query($sql_lightbox);

			if (mysql_num_rows($query_lightbox) > 0) {
				if ($row = mysql_fetch_assoc($query_lightbox)) {
					$lightbox = $row;
				}
			}

			$lightbox_users_sql = "select alu.*,al.*,ap.*,fu.*,group_concat(apu.union_name) as union_name from agency_lightbox_users alu
								LEFT JOIN agency_lightbox al ON al.lightbox_id = alu.lightbox_id
								LEFT JOIN agency_profiles ap ON alu.user_id = ap.user_id
								LEFT JOIN forum_users fu ON fu.user_id = ap.user_id
								LEFT JOIN agency_profile_unions apu ON apu.user_id = ap.user_id
								WHERE alu.lightbox_id = ".$lightbox_id."
								AND alu.entry_id IN (".implode(',',$_POST['addme']).")
								GROUP BY ap.user_id";

			$result_lightbox_users = mysql_query($lightbox_users_sql);
			$lightbox_users = array();
			while($row = sql_fetchrow($result_lightbox_users)) { 
				$lightbox_users[] = $row;
			}

			foreach($lightbox_users as $l_user_ins){
				$to_email = $l_user_ins['user_email'];
				$subject = 'New Casting: '.$lightbox['job_title'];
				$msg = $l_user_ins['firstname'].' '.$l_user_ins['lastname'].', <br/>';
				$msg .= 'Thank you for submit your profile for casting '.$lightbox['job_title'].'. Our Team Will contact to you soon. <br/>';
				$msg .= 'Thank you, <br/>';
				$msg .= 'TheAgencyOnline.com';
				if(send_mail($to_email,$subject,$msg)){
					$notification['success'] = "Notification sent successfully";
				}
			}

		}

	}

	// =====================
	$sql_lightbox = "SELECT al.*,ac.job_title FROM agency_lightbox al
					LEFT JOIN agency_castings ac ON ac.casting_id = al.casting_id
					WHERE lightbox_id=".$lightbox_id."";
    $query_lightbox = mysql_query($sql_lightbox);

    if (mysql_num_rows($query_lightbox) > 0) {
      if ($row = mysql_fetch_assoc($query_lightbox)) {
		$lightbox = $row;
	  }
	}

	// echo "<pre>";
	// print_r($lightbox);
?>

<!DOCTYPE html>
<html>

<head>
  	<title>Lightboxes</title>
  	<?php include('head.php'); ?>
  	<?php include('common_css.php'); ?>
  	<link rel="stylesheet" href="<?php echo $base_url; ?>dashboard/assets/css/AdminLTE.min.css" type="text/css">

   	<link href="dashboard/assets/OwlCarousel/owl.carousel.min.css" rel="stylesheet" />
	<link href="dashboard/assets/fancybox/jquery.fancybox.min.css" rel="stylesheet" />
	<style>
		.portfolio-owl-carousel .owl-stage{
			width: 20000px!important;
		}
	</style>
</head>

<body style="background: #ECF0F5;">
  <?php include('header.php'); ?>
    <!-- <div class="container-fluid breadcrumb-box text-center">
        <ul class="btn-group breadcrumb">
            <li><a href="<?php //echo $base_url; ?>" class="">Home</a></li>
            <li><a class="">profile</a></li>
        </div>
    </div> -->

<?php
	$page = "";
	$page_selected = "";
	// if(isset($_GET['user_id']) && $_GET['user_id'] != ""){

	// 	$user_id = $_GET['user_id'];
	// 	$profileid= (int) trim($_GET['user_id']);

	// 	$sql = "SELECT ap.*,fu.* FROM agency_profiles ap 
	// 			INNER JOIN forum_users fu ON fu.user_id = ap.user_id
	// 			WHERE ap.user_id='$profileid'";
	// 	$result=mysql_query($sql);
	// 	$userinfo = sql_fetchrow($result);
	// }

	// $folder_user = 'uploads/users/'.$userinfo['user_id'];
?>

<div id="page-wrapper">
    <div class="profile-page" id="main">

			<div class="container-fluid">
				<div class="col-md-12">
					<?php if(isset($notification['success'])){ ?>
						<br>
						<div class="alert alert-success" role="alert">
							<?php echo $notification['success']; ?>
						</div>
					<?php } ?>
					<?php if(isset($notification['error'])){ ?>
						<br>
						<div class="alert alert-danger" role="alert">
							<?php echo $notification['error']; ?>
						</div>
					<?php } ?>

					<h3>Lightbox : <?php echo strtoupper($lightbox['lightbox_name']); ?></h3>
					<p><?php echo $lightbox['lightbox_description']; ?></p>
					<p><?php echo 'A lightbox is linked with the casting <a href="casting-call-details.php?casting_id='.$lightbox['casting_id'].'">'.strtoupper($lightbox['job_title']).'</a>'; ?></p>
				</div>
						
		        <div class="col-md-12">
					
					<form action="" method="post" id="lightbox_frm">
						<input type="hidden" name="lightbox_type" value="<?php echo $lightbox['lightbox_type']; ?>" />
						<?php
							// $sql_lightbox_roles1 = "SELECT alu.*,acr.name FROM agency_lightbox_users alu
							// 						LEFT JOIN agency_castings_roles acr ON acr.role_id = alu.role_id
							// 						WHERE lightbox_id = ".$lightbox_id." GROUP BY role_id ";

							$sql_lightbox_roles1 = "SELECT acr.name,acr.role_id FROM agency_castings_roles acr
													WHERE casting_id = ".$lightbox['casting_id']." ";
							$query_lightbox_roles1 = mysql_query($sql_lightbox_roles1);
							
							if (mysql_num_rows($query_lightbox_roles1) > 0) {
								while($row1 = mysql_fetch_assoc($query_lightbox_roles1)) {
										// echo "<pre>";print_r($row1);
									?>

									<div class="box text-black box-lightox">
										<div class="box-header with-border"><h3 class="box-title">Role Name: <?php echo $row1['name']; ?></h3></div>
										<div class="box-body">
											<div class="row-flex text-center">

												<?php
													$sql_lightbox_roles2 = "SELECT alu.*,ap.*,fu.* FROM agency_lightbox_users alu 
													LEFT JOIN agency_profiles ap ON ap.user_id = alu.user_id
													LEFT JOIN forum_users fu ON fu.user_id = ap.user_id
													WHERE lightbox_id = ".$lightbox_id." and role_id = ".$row1['role_id']." ";
													$query_lightbox_roles2 = mysql_query($sql_lightbox_roles2);
													if (mysql_num_rows($query_lightbox_roles2) > 0) {
														while($row2 = mysql_fetch_assoc($query_lightbox_roles2)) {
														?>
															
															<div class="col-lg-2 col-md-3 col-sm-6 col-xs-12" style="margin-bottom:30px;border: 1px dotted #a8a8a8;padding: 15px;">
																<div class="card" style="height: 100%">

																	<?php
																		$uid = $row2['user_id'];
																		$email = $row2['user_email'];
																		$displayname = $row2['firstname'].' '.$row2['lastname'] ;

																		if(file_exists('uploads/users/' . $row2['user_id'] . '/profile_pic/thumb/'. '128x128_' . $row2['user_avatar'])){
																			$profile_pic = 'uploads/users/' . $row2['user_id'] . '/profile_pic/thumb/'. '128x128_' . $row2['user_avatar'];
																		}else{
																			$profile_pic = 'images/friend.gif';
																		}
																	?>
																	<a style="height: 128px;display: inline-block;width: 100%;text-align: center;"><img src="<?php echo $profile_pic; ?>" /></a>
																	<br />

																	<label style="font-weight:normal;">
																		<input type="checkbox" id="<?php echo 'addme_' . $uid; ?>" name="addme[<?php echo $uid; ?>]" value="<?php echo $row2['entry_id']; ?>" class="user_check user_check_<?php echo $row1['role_id']; ?>" />
																		<?php echo $displayname; ?>
																	</label>

																	<p>
																		<!-- <img src="<?php echo 'images/' . $experienceimages[$row2['experience']] . '.gif'; ?>" 
																			onmouseout="document.getElementById('experience_popup').style.display='none'" 
																			onmouseover="document.getElementById('experience_popup').style.display='' 
																		"> -->
																		<img src="<?php echo 'images/' . $experienceimages[$row2['experience']] . '.gif'; ?>">
																	</p>

																	<p>
																		<!-- <a href="./ajax/compcard_mini.php?u=<?php //echo $uid; ?>&height=400&amp;width=450" class="thickbox">Comp Card</a> -->
																		<a href="<?php echo 'pdf_compcard.php?u='.$uid.'&card_type='.$row2['compcard_type']; ?>" class="thickbox">Comp Card</a>
																	</p>
																	<p>
																		<a href="<?php echo 'mailto:' . $row2['user_email']; ?>"><?php echo $row2['user_email']; ?></a>
																	</p>

																	<?php 
																		if(!empty($row2['phone']) && agency_privacy($uid, 'phone')) {
																			echo '<p>' . $row2['phone'] .'</p>';
																		}
																	?>

																	<?php if(!empty($row2['resume'])) { ?>
																		<?php
																			$resume_file = "";
																			if(file_exists('uploads/users/' . $row2['user_id'] . '/resume/'. $row2['resume'])){
																				$resume_file = 'uploads/users/' . $row2['user_id'] . '/resume/'. $row2['resume'];
																				
																			}
																		?>
																		<?php if($resume_file != "") { ?>
																			<p>
																				<a href="<?php echo $resume_file; ?>" target="_blank">
																					<img src="images/resume1.gif" style="padding-top:5px;" >
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
																					<img src="images/reelVO.gif" style="padding-top:5px;" >
																				</a>
																			</p>
																	<?php } ?>

																	<?php 
																		// UNION STATUS
																		$sql4 = "SELECT * FROM agency_profile_unions WHERE user_id='$uid'";
																		$result4=mysql_query($sql4);
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
												<?php } ?>

											</div>

											
											<?php if (mysql_num_rows($query_lightbox_roles2) > 0) { ?>
												<?php if(isset($_SESSION['account_type']) && ($_SESSION['account_type'] == 'admin' || $_SESSION['account_type'] == 'client') ){ ?>
													<div class="col-sm-12 text-center color-white">
														<button class="select_role_btn btn btn-theme btn-flat" id="select_role_btn_<?php echo $row1['role_id']; ?>">select role</button>
														<button class="unselect_role_btn btn btn-theme btn-flat" id="unselect_role_btn_<?php echo $row1['role_id']; ?>">unselect role</button>
													</div>
												<?php } ?>
											<?php }else{ ?>
												<div class="text-center">
													Talent Not Found
												</div>	
											<?php } ?>

										</div>
									</div>

									<?php
								}
							}else{
								?>
									<div class="box text-black box-lightox">
										<div class="box-header with-border">Not Found Matching Roles</h3></div>
									</div>
								<?php
							}
						?>

						<div class="send_to_frd_box" style="display: none;">
							<div class="container">
								<div class="col-sm-12">
									<div class="box text-black box-lightox">
										<div class="box-body">
											<div class="form-group">
												<h4 class="text-center">Email address of Recipient *</h4>
												<input type="text" name="email_recipient" id="email_recipient" class="form-control"/>
											</div>
											<div class="form-group">
												<h4 class="text-center">Your Message *</h4>
												<textarea name="email_message" id="email_message" class="form-control" cols="30" rows="4" style="margin-left: auto;">I thought you might be interested in the some talent from TheAgencyOnline.com. Just follow the link below. &#13;&#13; <?php echo $base_url.'lightbox.php?lightbox_id='.$lightbox_id; ?></textarea>
											</div>

											<div class="form-group color-white text-center">
												<input type="submit" name="send_lightbox_to_frd" id="send_lightbox_to_frd" class="btn btn-theme btn-flat" value="send" />
												<!-- <button type="button" name="send_lightbox_to_frd" id="send_lightbox_to_frd" class="btn btn-theme btn-flat">send</button> -->
												<button class="btn btn-theme btn-flat lightbox_to_frd_cancel" id="">cancel</button>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="copy_box" style="display: none;">
							<div class="container">
								<div class="col-sm-12">
									<div class="box text-black box-lightox">
										<div class="box-body">
											<div class="form-group">
												<h4 class="text-center">New Lightbox Name *</h4>
												<input type="text" name="copy_name" id="copy_name" class="form-control">
											</div>

											<div class="form-group">
												<h4 class="text-center">Description</h4>
												<textarea name="copy_description" class="form-control"  cols="30" rows="4"></textarea>
											</div>

											<div class="form-group text-center">
												<label class="weight-normal"><input type="checkbox" name="keep_roles" id=""/> Keep Roles In New Lightbox </label>
											</div>

											<div class="form-group color-white text-center">
												<input type="submit" name="copy_lbox" id="copy_lbox" class="btn btn-theme btn-flat" value="COPY" />&nbsp;&nbsp;&nbsp;
												<input type="button" class="btn btn-theme btn-flat copy_cancel" value="CANCEL">
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="msg_box text-center" style="display: none;">
							<div class="container">
								<div class="col-sm-12">
									<div class="box text-black box-lightox">
										<div class="box-body">
											<div class="form-group">
												<h4 class="text-center">Title</h4>
												<input type="text" name="light_subject" id="light_subject" class="form-control">
											</div>
											<div class="form-group">
												<h4 class="text-center">Message</h4>
												<textarea name="light_message" id="light_message" class="form-control"></textarea>
											</div>

											<div class="form-group color-white text-center">
												<input type="submit" name="msg_lbox" id="msg_lbox" class="btn btn-theme btn-flat" value="SEND" />&nbsp;&nbsp;&nbsp;
												<input type="button" class="btn btn-theme btn-flat msg_cancel" value="CANCEL">
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<?php if(isset($_SESSION['account_type']) && ($_SESSION['account_type'] == 'admin' || $_SESSION['account_type'] == 'client') ){ ?>
							<div class="col-sm-12 text-center color-white">
								<button class="btn btn-theme btn-flat check_all_btn" id="">select all</button>
								<button class="btn btn-theme btn-flat print" id="">print/save</button>
								<button class="btn btn-theme btn-flat msg_btn">send message</button>
								<button class="btn btn-theme btn-flat lightbox_to_frd" id="">send lightbox to friend</button>
								<button class="btn btn-theme btn-flat copy_btn" id="">copy</button>
								<input type="submit" name="submit" class="btn btn-theme btn-flat" id="remove_btn" value="remove"/>
							</div>
							<br/><br/>

							<?php if($lightbox['lightbox_type'] == "auto_find"){ ?>
								<div class="col-sm-12 text-center color-white">
									<!-- <button class="btn btn-theme btn-flat" id="">admin: email to</button> -->
									<!-- <button class="btn btn-theme btn-flat" id="">admin: auto-notify</button> -->
									
									<input type="submit" name="email_to" id="email_to" class="btn btn-theme btn-flat" value="admin: email to" />
									<input type="submit" name="auto_notify" id="auto_notify_btn" class="btn btn-theme btn-flat" value="admin: auto-notify" />
								</div>
								<br/><br/>
							<?php } ?>

						<?php } ?>

					</form>
				</div>

		        
		    </div>
		
	</div>
</div>

<?php include('footer_js.php'); ?>

<script src="dashboard/assets/OwlCarousel/owl.carousel.min.js"></script>
<script src="dashboard/assets/fancybox/jquery.fancybox.min.js"></script>

<!-- <script type="text/javascript" src="http://code.jquery.com/ui/1.11.0/jquery-ui.min.js"></script>
<script type="text/javascript" src="../dashboard/assets/jQuery-DateTimepicker-Addon/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript" src="../dashboard/assets/jQuery-DateTimepicker-Addon/i18n/jquery-ui-timepicker-addon-i18n.min.js"></script>
<script type="text/javascript" src="../dashboard/assets/jQuery-DateTimepicker-Addon/jquery-ui-sliderAccess.js"></script>
<script type="text/javascript" src="../dashboard/assets/js/jquery.validate.js"></script> -->

<script>
	$(document).ready( function () {
		
	});

	var check_all = "F";
	$(".check_all_btn").click(function(e){
		e.preventDefault();
		if(check_all == "F"){
			// alert(check_all);
			// $('.user_check').prop('checked', true);
			$('.user_check').prop('checked', true);
			check_all = "T";
		}else{
			// alert(check_all);
			$('.user_check').prop('checked', false);
			check_all = "F";
		}
	});
	$(".uncheck_all_btn").click(function(e){
		e.preventDefault();
		$('.user_check').prop('checked', false);
	});

	$(".select_role_btn").click(function(e){
		e.preventDefault();
		id_str = $(this).attr('id');
		id_ary = id_str.split('_');
		role_id = id_ary[3];
		$('.user_check_'+role_id).prop('checked', true);
	});
	$(".unselect_role_btn").click(function(e){
		e.preventDefault();
		id_str = $(this).attr('id');
		id_ary = id_str.split('_');
		role_id = id_ary[3];
		$('.user_check_'+role_id).prop('checked', false);
	});

	// var frd_msg_box = 0;
	$(".lightbox_to_frd").click(function(e){
		e.preventDefault();
		// if(frd_msg_box == 0){
			$(".send_to_frd_box").css('display','block');
			// frd_msg_box = 1;
		// }else{
		// 	$(".send_to_frd_box").css('display','none');
		// 	frd_msg_box = 0;
		// }
	});

	$(".lightbox_to_frd_cancel").click(function(e){
		e.preventDefault();
		$(".send_to_frd_box").css('display','none');
	});

	$("#send_lightbox_to_frd").click(function(e){
		// e.preventDefault();
		email_recipient = $("#email_recipient").val();
		email_message = $("#email_message").val();
		
		if (email_recipient && email_message) {
			if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(email_recipient))
			{
				return (true)
			}else{
				e.preventDefault();
				alert("You have entered an invalid email address!");
				return false;
			}
			
		}else{
			e.preventDefault();
			alert('email & message both are required fileds');
			return (false)
		}
		// $("#lightbox_frm").submit();
	});


	$("#remove_btn").click(function(e){
		array1 = []
		$("input:checkbox[name*=addme]:checked").each(function(){
			array1.push($(this).val());
		});

		if(array1.length > 0){
			if(confirm('are you sure want to remove selected talent from lightbox ?')){
				// console.log('111');
			}else{
				e.preventDefault();
			}
		}else{
			e.preventDefault();
			alert('select atleast one talent to remove');
			return false;
		}
	});

	$(".copy_btn").click(function(e){
		e.preventDefault();
		$(".copy_box").css('display','block');
	});

	$(".copy_cancel").click(function(e){
		e.preventDefault();
		$(".copy_box").css('display','none');
	});

	$("#copy_lbox").click(function(e) {
		copy_name = $("#copy_name").val();
		if(copy_name === ""){
			alert('please enter lightbox name');
			return false;
		}
	});

	$(".msg_btn").click(function(e){
		e.preventDefault();
		$(".msg_box").css('display','block');
	});

	$(".msg_cancel").click(function(e){
		e.preventDefault();
		$(".msg_box").css('display','none');
	});
	
	$("#msg_lbox").click(function(e) {
		light_subject = $("#light_subject").val();
		light_message = $("#light_message").val();
		if(light_subject === "" || light_message === ""){
			alert('Please Enter Subject and Message');
			return false;
		}
	});

	$("#auto_notify_btn").click(function(e){
		if(confirm('All the people in this lightbox will be sent an automated email letting them know about the casting. Reminder: This auto feature sends to All people, not just checked people.')){
			// console.log('111');
		}else{
			// e.preventDefault();
			return false;
		}
	});

	$("#email_to").click(function(e){
		array1 = []
		$("input:checkbox[name*=addme]:checked").each(function(){
			array1.push($(this).val());
		});

		if(array1.length > 0){
			// if(confirm('are you sure want to remove selected talent from lightbox ?')){
			// 	// console.log('111');
			// }else{
			// 	e.preventDefault();
			// }
		}else{
			e.preventDefault();
			alert('select atleast one talent to email');
			return false;
		}
	});
	

	

	$(".print").click(function(e){
		e.preventDefault();
		window.print();
	});
	// $('.check_all_btn').clickToggle(function() {   
	// 	$(this).animate({
	// 		width: "260px"
	// 	}, 1500);
	// },
	// function() {
	// 	$(this).animate({
	// 		width: "30px"
	// 	}, 1500);
	// });
	

	if (window.history.replaceState) {
		window.history.replaceState(null, null, window.location.href);
	}
	
</script>
<?php include('footer.php'); ?>

</body>

</html>