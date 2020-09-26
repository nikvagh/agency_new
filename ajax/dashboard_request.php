<?php
session_start();

include('../includes/mysql_connect.php');
include('../includes/vars.php');
include('../includes/agency_functions.php');
include('../includes/agency_dash_functions.php');

unset($loggedin);
if(!empty($_SESSION['user_id'])) { // check if user is logged in
	$loggedin = $_SESSION['user_id'];
}

if(isset($_POST['name'])) {

	if($_POST['name'] == 'get_talent'){
		$result = get_talent();
		echo json_encode($result);
	}

	if($_POST['name'] == 'get_message_list'){
		
		if($_POST['msg_type'] == "inbox"){
			$result = get_message_inbox_list($_POST);

			// echo "<pre>";print_r($result);
			// echo "</pre>";
?>			
			<br/>
			<table class="table table-responsive datatable_inbox table-striped">
				<thead>
					<tr>
						<td>Sent By</td>
						<td>Subject</td>
						<td>Date Sent</td>
						<td></td>
					</tr>
				</thead>
				<tbody>
					<?php
						foreach($result as $row){
							$message_id = $row['message_id'];
							$subject = stripslashes($row['subject']);
							$sender_id = $row['from_id'];
							$sender_name = stripslashes($row['from_name']);
							$message = stripslashes(nl2br($row['message']));
							// $date = date('m/d/Y',strtotime($row['date_entered']));
							$date = $row['date_entered'];
							
							// $sql = "SELECT registration_date FROM agency_profiles WHERE user_id='$sender_id'";
							// $result=mysql_query($sql);
							// if($userinfo = sql_fetchrow($result)) {  // "$userinfo" array will be available through file, so no need to access database again
							// 	$folder = 'talentphotos/' . $sender_id. '_' . $userinfo['registration_date'] . '/';
							// }
					?>
							<tr>
							 	<td>
							 		<!-- profile.php?u=' . $sender_id . ' -->
									<a href="profile-view.php?user_id=<?php echo $sender_id; ?>"><?php echo $sender_name; ?></a>
								</td>

								<td align="left">
									<a href="javascript:void(0)" onClick="view_message('message_id','<?php echo $message_id; ?>')"><?php echo $subject; ?></a>
									<?php if($row['viewed'] == '0'){ ?>
										<small class="label pull-right bg-red">New</small>
									<?php } ?>
								</td>

								<td><?php echo $date; ?></font></td>
								<td>
									<a href="javascript:void(0)" onClick="delete_message('deletemessage','<?php echo $message_id; ?>')">
										<i class="fa fa-times text-danger"></i>
									</a>
								</td>
							</tr>
					<?php } ?>

				</tbody>
			</table>

		<?php

		}else if($_POST['msg_type'] == "sent"){
			$result = get_message_sent_list($_POST);

			// echo "<pre>";
			// print_r($result);
			// echo "</pre>";
		?>				
					<br/>
					<table class="table table-responsive datatable_sent table-striped">
						<thead>
							<tr>
								<td>Sent To</td>
								<td>Subject</td>
								<td>Date Sent</td>
								<td></td>
							</tr>
						</thead>
						<tbody>

						<?php foreach($result as $row){
							$sent_id = $row['sent_id'];
							$subject = stripslashes($row['subject']);
							$to_id = $row['to_id'];
							$lightbox_id = $row['lightbox_id'];
							$to_name = stripslashes($row['to_name']);
							$message = stripslashes(nl2br($row['message']));
							// $date = date('m/d/Y',strtotime($row['date_entered']));
							$date = $row['date_entered'];
							
							// get the sender's folder name
							// $sql = "SELECT registration_date FROM agency_profiles WHERE user_id='$to_id'";
							// $result=mysql_query($sql);
							// if($userinfo = sql_fetchrow($result)) {  
							// 	// "$userinfo" array will be available through file, so no need to access database again
							// 	$folder = 'talentphotos/' . $to_id. '_' . $userinfo['registration_date'] . '/';
							// } 
						?>

							<tr>
							 	<td>
							 		<?php
						 				echo '<a href="profile-view.php?user_id=' . $to_id . '">';
										// echo avatar_link($to_id) . '<img src="';
										// if(file_exists('../../' . $folder . 'avatar.jpg')) {
										// 	echo   $folder . 'avatar.jpg';
										// } else if(file_exists('../' . $folder . 'avatar.gif')) {
										// 	echo  $folder . 'avatar.gif';
										// } else {
										// 	echo 'images/friend.gif';
										// }
										// echo '" border="0" width="40" />';
										echo $to_name;
										echo '</a>';
										// echo "<br/>";
										
									?>
								</td>
								<td><a href="javascript:void(0)" onClick="view_message('sent_id','<?php echo $sent_id; ?>')"><?php echo $subject; ?></a></td>

								<td><?php echo $date; ?></td>
								<td><a href="javascript:void(0)" onClick="delete_message('deletesent','<?php echo $sent_id; ?>')" style=""><i class="fa fa-times text-danger"></i></a></td>

							</tr>
					<?php } ?>

				</tbody>
			</table>
	<?php
		}

		// echo json_encode($result);
	}	


	if($_POST['name'] == 'get_users_by_role'){
		$result = get_users_by_role($_POST['role']);
		echo json_encode($result);
	}

	if($_POST['name'] == 'send_msg_from_admin'){
		// print_r($_POST);
		// exit;
		$result = send_message_admin($_POST);
		echo json_encode($result);
	}

	if($_POST['name'] == 'send_msg_reply_from_admin'){
		$result = send_message_reply_admin($_POST);
		echo json_encode($result);
	}

	if($_POST['name'] == 'get_talent_request'){
		$result = get_talent_request($_POST['talent_request_id']);
		echo json_encode($result);
	}

	if($_POST['name'] == "dicount_code_unique_insert"){
		// echo "<pre>";print_r($_POST);
		$total_results = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM agency_discounts WHERE code = '".$_POST['code']."' "),0);
		if($total_results > 0) {
			echo json_encode(false);
		}else{
			echo json_encode(true);
		}
	}

	if($_POST['name'] == "dicount_code_unique_upadte"){
		// echo "<pre>";print_r($_POST);
		$total_results = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM agency_discounts WHERE code = '".$_POST['code']."' AND discount_id != '".$_POST['discount_id']."' "),0);
		if($total_results > 0) {
			echo json_encode(false);
		}else{
			echo json_encode(true);
		}
	}

	if($_POST['name'] == "user_email_unique_upadte"){
		// echo "<pre>";print_r($_POST);
		$total_results = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM forum_users WHERE user_email = '".$_POST['email']."' AND user_id != '".$_POST['user_id']."' "),0);
		if($total_results > 0) {
			echo json_encode(false);
		}else{
			echo json_encode(true);
		}
	}

	if($_POST['name'] == "user_email_unique_insert"){
		// echo "<pre>";print_r($_POST);
		$total_results = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM forum_users WHERE user_email = '".$_POST['email']."' "),0);
		if($total_results > 0) {
			echo json_encode(false);
		}else{
			echo json_encode(true);
		}
	}

	if($_POST['name'] == "user_username_unique_insert"){
		// echo "<pre>";print_r($_POST);
		$total_results = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM forum_users WHERE username = '".$_POST['username']."' "),0);
		if($total_results > 0) {
			echo json_encode(false);
		}else{
			echo json_encode(true);
		}
	}

	if($_POST['name'] == "get_sales_year_analytics"){
		$result = get_sales_year_analytics();
		echo json_encode($result);
	}

	if($_POST['name'] == "get_sales_month_analytics"){
		$result = get_sales_month_analytics();
		echo json_encode($result);
	}

	if($_POST['name'] == "get_sales_week_analytics"){
		$result = get_sales_week_analytics();
		echo json_encode($result);
	}

	if($_POST['name'] == "get_sales_day_analytics"){
		$result = get_sales_day_analytics();
		echo json_encode($result);
	}

	if($_POST['name'] == "failed_notification"){

		// echo "<pre>";print_r($_POST);
		// exit;

		$query = mysql_query("SELECT ap.*,fu.user_email FROM agency_profiles ap
							LEFT JOIN forum_users fu ON fu.user_id = ap.user_id
							WHERE ap.account_type = 'talent' AND ap.payFailed = '1' ");
		$result = array();
		$ccAry = array();
		if (mysql_num_rows($query) > 0) {
			$cnt = 0;
			while ($row = mysql_fetch_array($query, MYSQL_ASSOC)) {
				$result = $row;
				if($cnt == 0){
					$to_email = $row['user_email'];
				}else{
					$ccAry[] = $row['user_email'];
				}
				$cnt++;
			}

			// $ccAry[] = "niravmv103@gmail.com";
			// $ccAry[] = "nikul@kartuminfotech.com";
			$cc = array();
			if(!empty($ccAry)){
				$cc = implode(', ', $ccAry);
			}

			$subject = $_POST['subject'];
			$msg = $_POST['message'];

			// $to_email = "nikul@kartuminfotech.com";

			if(send_mail($to_email,$subject,$msg,$cc)){
				$res = array("success"=>"reuqest sent successfully.");
			}else{
				$res = array("error"=>"reuqest sending failed.");
			}
		}else{
			$res = array("error"=>"Not found any payment failed account.");
		}
		echo json_encode($res);

	}

	if($_POST['name'] == "get_role_byId"){
		$rol_data = get_role_byId($_POST['role_id']);
		$user_id = $_POST['user_id'];

		  $folder_profile_pic = '../uploads/users/' . $user_id . '/profile_pic/';
		  $folder_profile_pic_thumb = $folder_profile_pic . 'thumb/';
		  $folder_headshot = '../uploads/users/' . $user_id . '/headshot/';
		  $folder_headshot_thumb = $folder_headshot . 'thumb/';
		  $folder_card = '../uploads/users/' . $user_id . '/portfolio/';
		  $folder_card_thumb = $folder_card . 'thumb/';
		  $folder_audio = '../uploads/users/' . $user_id . '/audio/';
		  $folder_portfolio = '../uploads/users/' . $user_id . '/portfolio/';
		  $folder_portfolio_thumb = $folder_portfolio . 'thumb/';

		?>
			<label>Required Assets</label>
      		<div class="required_doc">

      		</div>
            <div class="form-group">
             	<label>Photo </label>
                <?php
                  $sql_portfolio = "SELECT * FROM agency_photos WHERE user_id=".$user_id." AND headshot_thumb ='N' ";
                  $result_portfolio = mysql_query($sql_portfolio);
                ?>
                <?php if(mysql_num_rows($result_portfolio) > 0){ ?>
                  <div class="row text-center">

                    <?php while ($row = sql_fetchrow($result_portfolio)) { ?>
                      <?php if(file_exists($folder_portfolio_thumb. '128x128_' . $row['filename'])){ ?>

                              <div class="col-md-3 margin-btm-15">
                                <div class="card-no-padding">

                                <a href="<?php echo $folder_portfolio . $row['filename']; ?>" class="block" style="height:128px">
                                  <img src="<?php echo $folder_portfolio_thumb. '128x128_' . $row['filename']; ?>">
                                </a>
                                <input type="checkbox" name="portfolio_del[<?php echo $row['image_id']; ?>]">

                              </div>
                            </div>

                      <?php } ?>
                    <?php } ?>

                  </div>
                <?php }else{ ?>
                    <br/>
                    <label class="text-center">You have't Any Photo Upload</label>
                <?php } ?>

                <div class="form-group">
                  <label class="file-box">
                    <span class="name-box">Drag and Drop Files</span>
                    <input type="file" name="portfolio[]" class="form-control" multiple="" />
                  </label>
                </div>

                <label>Reel</label>
                <?php 
                	$query_reel = "SELECT * FROM agency_reel WHERE user_id='$profileid'";
                  	$result_reel = mysql_query ($query_reel);
                  	$num_reels = mysql_num_rows($result_reel);
                ?>

                <?php if($num_reels > 0) { ?>
                    <div class="box-body">
                        <label>Uploaded Videos</label>
                        <br/>
                        <?php while ($row = mysql_fetch_array ($result_reel, MYSQL_ASSOC)) { ?>

                          <?php if($row['reel_host'] == 'youtube') { ?>
                            <a href="<?php echo 'http://www.youtube-nocookie.com/embed/' . $row['reel_link_id']; ?>" target="_blank">view </a>
                          <?php } else if($row['reel_host'] == 'vimeo') { ?>
                            <a href="<?php echo 'http://player.vimeo.com/video/' . $row['reel_link_id']; ?>" target="_blank">view </a>
                          <?php } ?>

                          &nbsp;&nbsp;&nbsp;&nbsp;
                          <label><input type="checkbox" name="video_del[<?php echo $row['reel_id']; ?>]"> check for submit</label>
                          <br/>
                        <?php } ?>
                    </div>
                <?php } ?>

                <?php if($num_reels < 3) { ?>
                    <div class="box-body">
                      <div class="form-group">
                          <label>Embed New Video</label>
                          <br/>
                          <label class="text-alert">
                            <i class="fa fa-bell"></i> Please upload your video to either <a href="http://www.youtube.com" target="_blank">YouTube</a> or <a href="http://www.vimeo.com" target="_blank">Vimeo</a>.
                            <br/>
                            Once you have your video uploaded, please copy the URL (Link) to your video and paste (or type) it in the box below.
                          </label>
                          <input type="text" name="videourl" class="form-control"/>
                      </div>
                    </div>
                <?php } ?>

                <label>Self Tapes</label>
                <?php 
                  $query_vo = "SELECT * FROM agency_vo WHERE user_id='$profileid'";
                  $result_vo = mysql_query ($query_vo);
                  $num_vos = mysql_num_rows($result_vo);
                ?>
                <?php if($num_vos > 0) { ?>
                  <div class="box-body">
                    <label>Uploded Audio</label>
                    <br/>
                    <?php while ($row = mysql_fetch_array ($result_vo, MYSQL_ASSOC)) { ?>
                      <div class="row">
                        <?php $vofile = $folder_audio . $row['vo_file']; ?>
                        <?php if(file_exists($vofile)) { ?>
                          
                          <div class="col-md-7">
                            <audio controls>
                              <source src="<?php echo $vofile; ?>" type="audio/mpeg">
                            </audio>
                          </div>
                          <div class="col-md-5">
                            <?php echo $row['vo_name']; ?> 
                            <br/>
                            <label>
                              <input type="checkbox" name="audio_del[<?php echo $row['vo_id']; ?>]"> check for submit
                            </label>
                          </div>

                        <?php } ?>
                      </div>
                    <?php } ?>
                  </div>
                <?php } ?>

                <?php if($num_vos < 3) { ?>
                 	<div class="box-body">
                      
	                    <input type="hidden" name="MAX_FILE_SIZE" value="10000000" />
	                    <label>Upload New Voice Over</label>
	                    <br/>
	                    <label class="text-alert"> <i class="fa fa-bell"></i> Please upload an <u>MP3</u> file of your Voice Over audio.</label>
	                    
	                    <div class="form-group">
	                      <label class=""> Title</label>
	                      <input type="text" name="mp3name" class="form-control" value="<?php if(isset($_POST['mp3name'])){ echo $_POST['mp3name']; } ?>"/>
	                    </div>

	                    <div class="form-group">
	                      	<label class="file-box">
	                            <span class="name-box">Drag and Drop Files</span>
	                        	<input type="file" name="mp3file" class="form-control" />
	                      	</label>
	                      	<label class="text-alert"><i class="fa fa-bell"></i> Select an MP3 file from your computer (max size: 10MB) </label>
	                    </div>

                  	</div>
                <?php } else { ?>
                  <div class="box-body">
                        <label class="text-danger">You may have a maximum of 3 Voice Overs on your page.  If you would like to add a new one, please delete one of your existing Voice Overs.</label>
                    </div>
                <?php } ?>


                <label>Resume</label>
                <div class="form-group">
                  <label class="file-box">
                    <span class="name-box">Drag and Drop Files</span>
                    <input type="file" name="portfolio[]" class="form-control" multiple="" />
                  </label>
                </div>

                <label>Note</label>
                <div class="form-group">
                  <textarea name="note" id="note" class="form-control"></textarea>
                </div>
            </div>
            <input type="hidden" name="role_id" id="role_id" value="<?php echo $_POST['role_id']; ?>" />

		<?php

		// echo json_encode($res);
	}

	if($_POST['name'] == "autofind_admin_by_role"){
	?>
		<div class="modal-dialog modal-lg">
			<?php 
				// echo "<pre>";
				// print_r($_POST);
				// echo "</pre>";
				$role = get_role_byId($_POST['role_id']);
			?>
	      	<!-- <form role="form" id="" class="talent_add_form" method="post" action=""> -->
	          	<div class="modal-content">
		            <!-- Modal Header -->
		            <div class="modal-header">
		                <button type="button" class="close" data-dismiss="modal">
		                    <span aria-hidden="true">&times;</span>
		                    <span class="sr-only">Close</span>
		                </button>
		                <h4 class="modal-title" id="myModalLabel"><?php echo $role['role']['name']; ?> Auto Find</h4>
		            </div>
		              
		            <!-- Modal Body -->
		            <div class="modal-body">
						<?php 
							$talents = autofind_by_role($_POST['role_id']);
						?>
	                    <?php if(count($talents) > 0){ ?>
		                  	<div class="row-flex text-center">
			                    <?php foreach($talents as $talent) { ?>

			                    	<?php $submission = get_submission_role_user($_POST['role_id'],$talent['user_id']); ?>
				                       	<?php 
				                       		if(file_exists('../uploads/users/' . $talent['user_id'] . '/profile_pic/thumb/'. '128x128_' . $talent['user_avatar'])){
				                       			$profile_pic = '../uploads/users/' . $talent['user_id'] . '/profile_pic/thumb/'. '128x128_' . $talent['user_avatar'];
			                      			}else{
			                      				$profile_pic = '../images/friend.gif';
			                      			}
			                      		?>
			                            <div class="col-md-3 margin-btm-15">
			                                <div class="card-no-padding">
			                                	<label>
				                                	<a class="block" style="height:128px">
					                                  <img src="<?php echo $profile_pic; ?>">
					                                </a>
					                                <?php if(!empty($submission)){ ?>
					                                	<?php if($submission['removed'] == 1){ ?>
					                                		<!-- <label class="label label-danger">Removed <i class="fa fa-times"></i></label><br/>
					                                		<input type="checkbox" name="user_talent[<?php echo $talent['user_id']; ?>]"> -->
					                                	<?php }else{ ?>
					                                		<!-- <label class="label label-success">Submitted <i class="fa fa-check"></i></label> -->
					                                	<?php } ?>
				                                	<?php }else{ ?>
				                                		<!-- <input type="checkbox" name="user_talent[<?php echo $talent['user_id']; ?>]"> -->
				                                	<?php } ?>
					                            </label>
					                            <h4><a><?php echo $talent['firstname'].' '.$talent['lastname']; ?></a></h4>
					                            <p><?php echo $talent['height_ft']."'".$talent['height_inch'].'"'.' '.$talent['weight'].' lbs'; ?></p>
				                            </div>
			                            </div>
			                      	<?php ?>

			                    <?php } ?>
		                  	</div>
		                <?php }else{ ?>
							<br/>
							<?php if($_POST['buttonpressed'] == 'autofind'){ ?>
								<label class="text-center">Not found matching talent to casting.</label>
							<?php }elseif($_POST['buttonpressed'] == 'search'){ ?>
								<label class="text-center">Not found talents for search criteria</label>
							<?php } ?>
		                <?php } ?>
		            </div> 
		              	
		            <!-- Modal Footer -->
		            <div class="modal-footer">
		                <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Close</button>
		            </div>
	          	</div>
	        <!-- </form> -->
	    </div>
	<?php
	}

	if($_POST['name'] == "user_serach_tm"){
		// echo "<pre>";
		// print_r($_POST);
		// echo "</pre>";
	?>
		<div class="modal-dialog modal-lg">
			<?php 
				// echo "<pre>";
				// print_r($_POST);
				// echo "</pre>";
				$role = get_role_byId($_POST['role_id']);
			?>
	      	<form role="form" id="" class="talent_add_form" method="post" action="">
	          	<div class="modal-content">
		            <!-- Modal Header -->
		            <div class="modal-header">
		                <button type="button" class="close" data-dismiss="modal">
		                    <span aria-hidden="true">&times;</span>
		                    <span class="sr-only">Close</span>
		                </button>
		                <h4 class="modal-title" id="myModalLabel"><?php echo $role['role']['name']; ?> Submission Summary</h4>
		            </div>
		              
		            <!-- Modal Body -->
		            <div class="modal-body">
						<?php 
							if($_POST['buttonpressed'] == 'autofind'){
								$talents = autofind_by_role($_POST['role_id'],$_POST['user_id']); 
							}elseif($_POST['buttonpressed'] == 'search'){
								$talents = get_talent_byTmId_serach($_POST['user_id'],$_POST); 
							}
						?>
	                    <?php if(count($talents) > 0){ ?>
		                  	<div class="row-flex text-center">
			                    <?php foreach($talents as $talent) { ?>

			                    	<?php $submission = get_submission_role_user($_POST['role_id'],$talent['user_id']); ?>
				                       	<?php 
				                       		if(file_exists('../uploads/users/' . $talent['user_id'] . '/profile_pic/thumb/'. '128x128_' . $talent['user_avatar'])){
				                       			$profile_pic = '../uploads/users/' . $talent['user_id'] . '/profile_pic/thumb/'. '128x128_' . $talent['user_avatar'];
			                      			}else{
			                      				$profile_pic = '../images/friend.gif';
			                      			}
			                      		?>
			                            <div class="col-md-3 margin-btm-15">
			                                <div class="card-no-padding">
			                                	<label>
				                                	<a class="block" style="height:128px">
					                                  <img src="<?php echo $profile_pic; ?>">
					                                </a>
					                                <?php if(!empty($submission)){ ?>
					                                	<?php if($submission['removed'] == 1){ ?>
					                                		<label class="label label-danger">Removed <i class="fa fa-times"></i></label><br/>
					                                		<input type="checkbox" name="user_talent[<?php echo $talent['user_id']; ?>]">
					                                	<?php }else{ ?>
					                                		<label class="label label-success">Submitted <i class="fa fa-check"></i></label>
					                                	<?php } ?>
				                                	<?php }else{ ?>
				                                		<input type="checkbox" name="user_talent[<?php echo $talent['user_id']; ?>]">
				                                	<?php } ?>
					                            </label>
					                            <h4><a><?php echo $talent['firstname'].' '.$talent['lastname']; ?></a></h4>
					                            <p><?php echo $talent['height_ft']."'".$talent['height_inch'].'"'.' '.$talent['weight'].' lbs'; ?></p>
				                            </div>
			                            </div>
			                      	<?php ?>

			                    <?php } ?>
		                  	</div>
		                <?php }else{ ?>
							<br/>
							<?php if($_POST['buttonpressed'] == 'autofind'){ ?>
								<label class="text-center">Not found matching talent to casting.</label>
							<?php }elseif($_POST['buttonpressed'] == 'search'){ ?>
								<label class="text-center">Not found talents for search criteria</label>
							<?php } ?>
		                <?php } ?>
		            </div> 
		              	
		            <!-- Modal Footer -->
		            <div class="modal-footer">
		                <input type="hidden" name="role_id" id="role_id" value="<?php echo $_POST['role_id']; ?>" />
		                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		                <?php if(count($talents) > 0){ ?>
			                <input type="submit" class="btn btn-success" name="submission_add" value="Add Talent to Submission"/>
			            <?php } ?>
		            </div>
	          	</div>
	        </form>
	    </div>
	<?php
	}

	if($_POST['name'] == "user_serach_tm_autofind_all"){
		// echo "<pre>";
		// print_r($_POST);
		// echo "</pre>";
	?>
		<div class="modal-dialog modal-lg">
			<?php 
				// echo "<pre>";
				// print_r($_POST);
				// echo "</pre>";
				// $role = get_role_byId($_POST['role_id']);
				// $casting_id = $_POST['casting_id'];
			?>

	      	<form role="form" id="" class="talent_add_form" method="post" action="">
	          	<div class="modal-content">
		            <!-- Modal Header -->
		            <div class="modal-header">
		                <button type="button" class="close" data-dismiss="modal">
		                    <span aria-hidden="true">&times;</span>
		                    <span class="sr-only">Close</span>
		                </button>
		                <h4 class="modal-title" id="myModalLabel"><?php echo $role['role']['name']; ?> Auto Find </h4>
					</div>
					
					<div class="modal-body">
						<?php

							$casting_id = $_POST['casting_id'];
							$age_lower_matched = array();
							$age_upper_matched = array();
							$height_lower_matched = array();
							$height_upper_matched = array();
							$gender_matched = array();

							$casting_q = mysql_query("select ac.*,ap.firstname,ap.lastname from agency_castings ac
													LEFT JOIN agency_profiles as ap ON ap.user_id = ac.casting_director
													WHERE ac.casting_id =".$casting_id." GROUP BY casting_id
													");
							if (mysql_num_rows($casting_q) > 0) {
								while ($row = mysql_fetch_assoc($casting_q)) {

									//insert lightnox
									// $lightbox_ins = "INSERT INTO agency_lightbox 
									// 				SET 
									// 				client_id = ".$_POST['client_id'].",
									// 				lightbox_name = '".$_POST['lightbox_name']."',
									// 				lightbox_description = 'auto-find results',
									// 				casting_id = '".$casting_id."',
									// 				lightbox_type = 'auto_find',
									// 				timecode = '".$time."'
									// 				";
									// mysql_query($lightbox_ins);
									// $lightbox_id = mysql_insert_id();
									// ====================

									$casting_role_q = mysql_query("select * from agency_castings_roles WHERE casting_id =".$casting_id." ");
									if (mysql_num_rows($casting_role_q) > 0) {
										while ($role_row = mysql_fetch_assoc($casting_role_q)) {
										?>
											<h3><?php echo $role_row['name']; ?></h3>
										<?php
										
										// $age_lower_matched[] = $role_row['age_lower'];
										// $age_upper_matched[] = $role_row['age_upper'];
										// $height_lower_matched[] = $role_row['height_lower'];
										// $height_upper_matched[] = $role_row['height_upper'];

										$gender_q = mysql_query("select * from agency_castings_roles_vars
																	WHERE casting_id =".$casting_id." AND role_id = ".$role_row['role_id']." AND var_type = 'gender'
																");
										if (mysql_num_rows($gender_q) > 0) {
											while ($gender_row = mysql_fetch_assoc($gender_q)) {
											$gender_matched[] = $gender_row['var_value'];

											}
										}

										$match['gender'] = array_unique($gender_matched);

										$gender_cond = array();
										if(in_array("M",$match['gender'])){
											$gender_cond[] = "gender = 'M'";
										}
										if(in_array("F",$match['gender'])){
											$gender_cond[] = "gender = 'F'";
										}
										if(in_array("Transgender",$match['gender'])){
											$gender_cond[] = "gender = 'Transgender'";
										}
										if(in_array("Other",$match['gender'])){
											$gender_cond[] = "gender = 'Other'";
										}

										if(!empty($gender_cond)){
											$gender_str = implode(' OR ',$gender_cond);
											$cond .= ' AND ('.$gender_str.')';
										}

										$matched_q = mysql_query("select *,YEAR(CURDATE()) - YEAR(birthdate) as age from agency_profiles ap
																LEFT JOIN forum_users fu on ap.user_id = fu.user_id 
																WHERE 1 AND height >= '".$role_row['height_lower']."' AND height <= '".$role_row['height_upper']."' ". 
																		$cond. " 
																		AND (YEAR(CURDATE()) - YEAR(birthdate)) >= '".$role_row['age_lower']."'
																		AND (YEAR(CURDATE()) - YEAR(birthdate)) <= '".$role_row['age_upper']."'
																");
											if (mysql_num_rows($matched_q) > 0) {
												?>

												<div class="row-flex text-center">
													<?php while ($matched_row = mysql_fetch_assoc($matched_q)) { ?>	
														<?php $submission = get_submission_role_user($role_row['role_id'],$matched_row['user_id']); ?>
														<?php 
															if(file_exists('../uploads/users/' . $matched_row['user_id'] . '/profile_pic/thumb/'. '128x128_' . $matched_row['user_avatar'])){
																$profile_pic = '../uploads/users/' . $matched_row['user_id'] . '/profile_pic/thumb/'. '128x128_' . $matched_row['user_avatar'];
															}else{
																$profile_pic = '../images/friend.gif';
															}
														?>
														<div class="col-md-3 margin-btm-15">
															<div class="card-no-padding">
																<label>
																	<a class="block" style="height:128px">
																		<img src="<?php echo $profile_pic; ?>">
																	</a>
																	<?php if(!empty($submission)){ ?>
																		<?php if($submission['removed'] == 1){ ?>
																			<label class="label label-danger">Removed <i class="fa fa-times"></i></label><br/>
																			<label>Add <input type="checkbox" name="user_talent[<?php echo $matched_row['user_id']; ?>][<?php echo $role_row['role_id']; ?>]"></label>
																		<?php }else{ ?>
																			<label class="label label-success">Submitted <i class="fa fa-check"></i></label><br/>
																			<label>Remove <input type="checkbox" name="user_talent_remove[<?php echo $matched_row['user_id']; ?>][<?php echo $role_row['role_id']; ?>]"></label>
																		<?php } ?>
																	<?php }else{ ?>
																		<label>Add <input type="checkbox" name="user_talent[<?php echo $matched_row['user_id']; ?>][<?php echo $role_row['role_id']; ?>]"></label>
																	<?php } ?>
																</label>
																<h4><a href="<?php echo '../profile-view-talent.php?user_id='.$matched_row['user_id']; ?>"><?php echo $matched_row['firstname'].' '.$matched_row['lastname']; ?></a></h4>
																<p><?php echo $matched_row['height_ft']."'".$matched_row['height_inch'].'"'.' '.$matched_row['weight'].' lbs'; ?></p>


																<p>
																	<img src="<?php echo '../images/' . $matched_row['experience'] . '.gif'; ?>">
																</p>

																<p>
																	<!-- <a href="./ajax/compcard_mini.php?u=<?php //echo $uid; ?>&height=400&amp;width=450" class="thickbox">Comp Card</a> -->
																	<a href="<?php echo '../pdf_compcard.php?u='.$matched_row['user_id'].'&card_type='.$matched_row['compcard_type']; ?>" class="thickbox">Comp Card</a>
																</p>
																<p>
																	<a href="<?php echo 'mailto:' . $matched_row['user_email']; ?>"><?php echo $matched_row['user_email']; ?></a>
																</p>

																<?php 
																	if(!empty($matched_row['phone']) && agency_privacy($matched_row['user_id'], 'phone')) {
																		echo '<p>' . $matched_row['phone'] .'</p>';
																	}
																?>

																<?php if(!empty($matched_row['resume'])) { ?>
																	<?php
																		$resume_file = "";
																		if(file_exists('../uploads/users/' . $matched_row['user_id'] . '/resume/'. $matched_row['resume'])){
																			$resume_file = '../uploads/users/' . $matched_row['user_id'] . '/resume/'. $matched_row['resume'];
																			
																		}
																	?>
																	<?php if($resume_file != "") { ?>
																		<p>
																			<a href="<?php echo $resume_file; ?>" target="_blank">
																				<img src="../images/resume1.gif" style="padding-top:5px;" >
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

																
															<?php
															// echo "111";
															// //insert lightnox
															// $lightbox_user_ins = "INSERT INTO agency_lightbox_users 
															// 			SET 
															// 			lightbox_id = ".$lightbox_id.",
															// 			user_id = ".$matched_row['user_id'].",
															// 			role_id = ".$role_row['role_id']."
															// 		";
															// mysql_query($lightbox_user_ins);
															// // ====================
														}
													?>
												</div>

												<?php
											}else{
												?>
													<label>Not Found Matching talent for this role</label>
												<?php
											}

										}
									}


								}
							}
		
						?>
					</div>


		              
		            <!-- Modal Body -->
		            <!-- <div class="modal-body">
						<?php 
							if($_POST['buttonpressed'] == 'autofind'){
								$talents = autofind_by_role($_POST['role_id'],$_POST['user_id']); 
							}
						?>
						<?php if(count($talents) > 0){ ?>
							
		                  	<div class="row-flex text-center">
			                    <?php foreach($talents as $talent) { ?>

			                    	<?php $submission = get_submission_role_user($_POST['role_id'],$talent['user_id']); ?>
				                       	<?php 
				                       		if(file_exists('../uploads/users/' . $talent['user_id'] . '/profile_pic/thumb/'. '128x128_' . $talent['user_avatar'])){
				                       			$profile_pic = '../uploads/users/' . $talent['user_id'] . '/profile_pic/thumb/'. '128x128_' . $talent['user_avatar'];
			                      			}else{
			                      				$profile_pic = '../images/friend.gif';
			                      			}
			                      		?>
			                            <div class="col-md-3 margin-btm-15">
			                                <div class="card-no-padding">
			                                	<label>
				                                	<a class="block" style="height:128px">
					                                  <img src="<?php echo $profile_pic; ?>">
					                                </a>
					                                <?php if(!empty($submission)){ ?>
					                                	<?php if($submission['removed'] == 1){ ?>
					                                		<label class="label label-danger">Removed <i class="fa fa-times"></i></label><br/>
					                                		<input type="checkbox" name="user_talent[<?php echo $talent['user_id']; ?>]">
					                                	<?php }else{ ?>
					                                		<label class="label label-success">Submitted <i class="fa fa-check"></i></label>
					                                	<?php } ?>
				                                	<?php }else{ ?>
				                                		<input type="checkbox" name="user_talent[<?php echo $talent['user_id']; ?>]">
				                                	<?php } ?>
					                            </label>
					                            <h4><a><?php echo $talent['firstname'].' '.$talent['lastname']; ?></a></h4>
					                            <p><?php echo $talent['height_ft']."'".$talent['height_inch'].'"'.' '.$talent['weight'].' lbs'; ?></p>
				                            </div>
			                            </div>
			                      	<?php ?>

			                    <?php } ?>
							</div>
							  
		                <?php }else{ ?>
							<br/>
							<?php if($_POST['buttonpressed'] == 'autofind'){ ?>
								<label class="text-center">Not found matching talent to casting.</label>
							<?php } ?>
		                <?php } ?>
		            </div>  -->
		              	
		            <!-- Modal Footer -->
		            <div class="modal-footer">
		                <input type="hidden" name="role_id" id="role_id" value="<?php echo $_POST['role_id']; ?>" />
		                <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Close</button>
		                <?php //if(count($talents) > 0){ ?>
			                <input type="submit" class="btn btn-success btn-flat" name="submission_add" value="Save Submission"/>
			            <?php //} ?>
		            </div>
	          	</div>
			</form>
			
	    </div>
	<?php
	}

	if($_POST['name'] == "submission_box_tm"){

		// $folder_profile_pic = '../uploads/users/' . $user_id . '/profile_pic/';
	 //    $folder_profile_pic_thumb = $folder_profile_pic . 'thumb/';
	  	// $folder_headshot = '../uploads/users/' . $user_id . '/headshot/';
	  	// $folder_headshot_thumb = $folder_headshot . 'thumb/';
	  	// $folder_card = '../uploads/users/' . $user_id . '/portfolio/';
	  	// $folder_card_thumb = $folder_card . 'thumb/';
	  	// $folder_audio = '../uploads/users/' . $user_id . '/audio/';
	  	// $folder_portfolio = '../uploads/users/' . $user_id . '/portfolio/';
	  	// $folder_portfolio_thumb = $folder_portfolio . 'thumb/';
	?>

		<div class="modal-dialog modal-lg">
			<?php 
				// echo "<pre>";
				// print_r($_POST);
				// echo "</pre>";
				$role = get_role_byId($_POST['role_id']);
			?>
	      	<form role="form" id="" class="talent_submit_form" method="post" action="">
	          	<div class="modal-content">
		            <!-- Modal Header -->
		            <div class="modal-header">
		                <button type="button" class="close" data-dismiss="modal">
		                    <span aria-hidden="true">&times;</span>
		                    <span class="sr-only">Close</span>
		                </button>
		                <h4 class="modal-title" id="myModalLabel"><?php echo $role['role']['name']; ?> Submission Summary</h4>
		            </div>
		              
		            <!-- Modal Body -->
		            <div class="modal-body">
		            	<?php $talents = get_talent_submission_tm_byRoleId($_POST['user_id'],$_POST['role_id']); ?>
	                    <?php if(count($talents) > 0){ ?>
		                  	<div class="row-flex text-center">
			                    <?php foreach($talents as $talent) { ?>
			                       	<?php 
			                       		if(file_exists('../uploads/users/' . $talent['user_id'] . '/profile_pic/thumb/'. '128x128_' . $talent['user_avatar'])){
			                       			$profile_pic = '../uploads/users/' . $talent['user_id'] . '/profile_pic/thumb/'. '128x128_' . $talent['user_avatar'];
		                      			}else{
		                      				$profile_pic = '../images/friend.gif';
		                      			}
		                      		?>
		                            <div class="col-md-3 margin-btm-15">
		                                <div class="card-no-padding">
		                                	<label>
			                                	<a class="block" style="height:128px">
				                                  <img src="<?php echo $profile_pic; ?>">
				                                </a>			                                	
				                                <input type="checkbox" name="user_talent[<?php echo $talent['user_id']; ?>]" <?php if($talent['removed'] == 0){ echo "checked"; } ?>>
				                            </label>
				                            <h4><a><?php echo $talent['firstname'].' '.$talent['lastname']; ?></a></h4>
				                            <p><?php echo $talent['height_ft']."'".$talent['height_inch'].'"'.' '.$talent['weight'].' lbs'; ?></p>
			                            </div>
			                            <a class="note-btn btn btn-xs btn-default" submission="<?php echo $talent['submission_id']; ?>">Note</a>
		                            </div>
			                      
			                    <?php } ?>
		                  	</div>
		                <?php }else{ ?>
		                    <br/>
		                    <label class="text-center">No submission are available</label>
		                <?php } ?>
		            </div> 
		              	
		            <!-- Modal Footer -->
		            <div class="modal-footer">
		                <input type="hidden" name="role_id" id="role_id" value="<?php echo $_POST['role_id']; ?>" />
		                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		                <?php if(count($talents) > 0){ ?>
			                <input type="submit" class="btn btn-success" name="submission_submit" value="Change Submission"/>
			            <?php } ?>
		            </div>
	          	</div>
	        </form>
	    </div>

	<?php
	}

	if($_POST['name'] == "submission_note_box_tm"){
	?>

		<div class="modal-dialog modal-lg">
			<?php 
				// echo "<pre>";
				// print_r($_POST);
				// echo "</pre>";
				$submission = get_submission_byId($_POST['submission_id']);
			?>
	      	<form role="form" id="" class="note_frm" method="post" action="">
	          	<div class="modal-content">
		            <!-- Modal Header -->
		            <div class="modal-header">
		                <button type="button" class="close" data-dismiss="modal">
		                    <span aria-hidden="true">&times;</span>
		                    <span class="sr-only">Close</span>
		                </button>
		                <h4 class="modal-title" id="">Note</h4>
		            </div>
		              
		            <!-- Modal Body -->
		            <div class="modal-body">
		            	<div class="form-group">
		            		<textarea name="note" id="note" class="form-control"><?php echo $submission['message_tm_2_talent']; ?></textarea>
		            	</div>
		            </div> 
		              
		            <!-- Modal Footer -->
		            <div class="modal-footer">
		                <input type="hidden" name="role_id" id="role_id" value="<?php echo $submission['role_id']; ?>" />
		                <input type="hidden" name="talent_id" id="talent_id" value="<?php echo $submission['user_id']; ?>" />
		                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		                <input type="submit" class="btn btn-success" name="note_submit" value="Save"/>
		            </div>
	          	</div>
	        </form>
	    </div>

	<?php
	}

	if($_POST['name'] == "submission_add_talent_tm"){
		// echo "<pre>";
		// print_r($_POST);
		// echo "</pre>";
		// exit;

		// $sql_select = "select user_id from agency_profiles ap where roster_id = ".$_SESSION['user_id']."";
		// $res_select = mysql_query($sql_select);
		// $user_ids = array();
		// while ($row = mysql_fetch_array ($res_select, MYSQL_ASSOC)) {
		// 	$user_ids[] = $row['user_id'];
		// }

		// $user_str = "";
		// if(!empty($user_ids)){
		// 	$user_str = implode(',',$user_ids);
		// }

		// $query_remove = "UPDATE agency_mycastings SET removed = 1 WHERE user_id IN (".$user_str.") AND role_id = ".$_POST['role_id']." ";
		// if(mysql_query($query_remove)){

			if(isset($_POST['user_talent'])){
				foreach($_POST['user_talent'] as $user_id_talent=>$val){

					foreach($val as $key1=>$val1){
						$role_id = $key1;
						break;
					}

					$sql_select = "select * from agency_mycastings where user_id = ".$user_id_talent." AND role_id = ".$role_id." ";
					$res_select = mysql_query($sql_select);
					if (mysql_num_rows($res_select) > 0) {
						$query_update = "UPDATE agency_mycastings 
									SET 		
									removed = 0
									WHERE						
									user_id = ".$user_id_talent." 
									AND
									role_id = ".$role_id."";
						mysql_query($query_update);
					}else{
						$query_add = "INSERT INTO agency_mycastings 
									SET 
									user_id = ".$user_id_talent.",
									role_id = ".$role_id."";
						mysql_query($query_add);
					}
				}
			}


			if(isset($_POST['user_talent_remove'])){
				foreach($_POST['user_talent_remove'] as $user_id_talent=>$val){

					foreach($val as $key1=>$val1){
						$role_id = $key1;
						break;
					}

					$sql_select = "select * from agency_mycastings where user_id = ".$user_id_talent." AND role_id = ".$role_id." ";
					$res_select = mysql_query($sql_select);
					if (mysql_num_rows($res_select) > 0) {
						$query_update = "UPDATE agency_mycastings 
									SET 		
									removed = 1
									WHERE						
									user_id = ".$user_id_talent." 
									AND
									role_id = ".$role_id."";
						mysql_query($query_update);
					}
				}
			}

		// }
	}

	if($_POST['name'] == "submission_talent_tm"){
		// echo "<pre>";
		// print_r($_POST);
		// echo "</pre>";

		$sql_select = "select user_id from agency_profiles ap where roster_id = ".$_SESSION['user_id']."";
		$res_select = mysql_query($sql_select);
		$user_ids = array();
		while ($row = mysql_fetch_array ($res_select, MYSQL_ASSOC)) {
			$user_ids[] = $row['user_id'];
		}

		$user_str = "";
		if(!empty($user_ids)){
			$user_str = implode(',',$user_ids);
		}

		$query_remove = "UPDATE agency_mycastings SET removed = 1 WHERE user_id IN (".$user_str.") AND role_id = ".$_POST['role_id']." ";
		if(mysql_query($query_remove)){

			if(isset($_POST['user_talent'])){
				foreach($_POST['user_talent'] as $user_id_talent=>$val){
					$query_change = "UPDATE agency_mycastings SET removed = 0 WHERE user_id =".$user_id_talent." AND role_id = ".$_POST['role_id'];
					mysql_query($query_change);
				}
			}

		}

	}

	if($_POST['name'] == "submission_note_tm"){
		// echo "<pre>";
		// print_r($_POST);
		// echo "</pre>";

		$query = "UPDATE agency_mycastings SET message_tm_2_talent = '".$_POST['note']."' WHERE user_id = ".$_POST['talent_id']." AND role_id = ".$_POST['role_id']." ";
        if(mysql_query($query)){
        	echo json_encode(true);
        }else{
        	echo json_encode(false);
        }
	}

	if($_POST['name'] == "lightbox_form_box"){
	?>

		<div class="modal-dialog">
			<?php 
				// echo "<pre>";
				// print_r($_POST);
				// echo "</pre>";
			?>
	      	<form role="form" id="" class="lightbox_form" method="post" action="">
	          	<div class="modal-content">
		            <!-- Modal Header -->
		            <div class="modal-header">
		                <button type="button" class="close" data-dismiss="modal">
		                    <span aria-hidden="true">&times;</span>
		                    <span class="sr-only">Close</span>
		                </button>
		                <h4 class="modal-title" id="">Lightbox</h4>
		            </div>
		              
		            <!-- Modal Body -->
		            <div class="modal-body">
		            	<label class="alert alert-danger" id="user-to-lightbox-err" style="display: none;"></label>
		            	<h4 class="">Create New LightBox</h4>
		            	<div class="form-group">
		            		<label>Title</label>
		            		<input type="text" name="title" id="title" description="title" class="form-control" />
		            	</div>
		            	<div class="form-group">
		            		<label>Description</label>
		            		<textarea name="description" id="description" class="form-control"></textarea>
		            	</div>

		            	<h3 class="text-center">OR</h3>

		            	<h4 class="">Select LightBox</h4>
		            	<div class="form-group">
		            		<select name="lightbox_id" id="lightbox_id" class="form-control">
		            			<option value="">-- Select Lightbox --</option>
		            			<?php 
			            			$sql_lightbox = "select * from agency_lightbox al where client_id = ".$_SESSION['user_id']."";
									$res_lightbox = mysql_query($sql_lightbox);
									while ($row = mysql_fetch_array ($res_lightbox, MYSQL_ASSOC)) {
								?>
									<option value="<?php echo $row['lightbox_id']; ?>"><?php echo $row['lightbox_name']; ?></option>
								<?php } ?>
		            		</select>
		            	</div>
		            </div>
		              
		            <!-- Modal Footer -->
		            <div class="modal-footer">
		                <input type="hidden" name="users" id="users" value="<?php echo $_POST['check_users']; ?>" />
		                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		                <input type="submit" class="btn btn-success" name="user_to_lightbox_submit" value="Save"/>
		            </div>
	          	</div>
	        </form>
	    </div>

	<?php
	}

	if($_POST['name'] == "check_autofind_lightbox"){
		?>
	
			<div class="modal-dialog">
				<?php 
					// echo "<pre>";
					// print_r($_POST);
					// echo "</pre>";
				?>
				  <form role="form" id="" class="lightbox_form" method="post" action="">
					  <div class="modal-content">
						<!-- Modal Header -->
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal">
								<span aria-hidden="true">&times;</span>
								<span class="sr-only">Close</span>
							</button>
							<h4 class="modal-title" id="">Lightbox - Auto Find</h4>
						</div>
						  
						<!-- Modal Body -->
						<div class="modal-body">
							<label class="alert alert-danger" id="user-to-lightbox-err" style="display: none;"></label>
							<h4 class="">Create New LightBox</h4>
							<div class="form-group">
								<label>Title</label>
								<input type="text" name="lightbox_name" id="lightbox_name" class="form-control" value=""/>
							</div>
							<div class="form-group">
								<label>Description</label>
								<textarea name="description" id="description" class="form-control">auto-find results</textarea>
							</div>
	
							<h3 class="text-center">OR</h3>
	
							<h4 class="">Select LightBox</h4>
							<div class="form-group">
								<select name="lightbox_id" id="lightbox_id" class="form-control">
									<option value="">-- Select Lightbox --</option>
									<?php 
										$sql_lightbox = "select * from agency_lightbox al where casting_id = ".$_POST['casting_id']." AND lightbox_type = 'auto_find'";
										$res_lightbox = mysql_query($sql_lightbox);
										while ($row = mysql_fetch_assoc($res_lightbox)) {
									?>
										<option value="<?php echo $row['lightbox_id']; ?>"><?php echo $row['lightbox_name']; ?></option>
									<?php } ?>
								</select>
							</div>
						</div>
						  
						<!-- Modal Footer -->
						<div class="modal-footer">
							<!-- <input type="hidden" name="users" id="users" value="<?php echo $_POST['check_users']; ?>" /> -->
							<input type="hidden" name="casting_id" value="<?php echo $_POST['casting_id']; ?>" />
							<input type="hidden" name="client_id" value="<?php echo $_POST['client_id']; ?>" />
							<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
							<input type="submit" class="btn btn-success" name="user_to_lightbox_submit" value="Auto Find"/>
						</div>
					  </div>
				</form>
			</div>
	
		<?php
		}

		if($_POST['name'] == "check_autosubmit_lightbox"){
		?>
	
			<div class="modal-dialog">
				<?php 
					// echo "<pre>";
					// print_r($_POST);
					// echo "</pre>";
				?>
					<form role="form" id="" class="lightbox_form" method="post" action="">
						<div class="modal-content">
						<!-- Modal Header -->
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal">
								<span aria-hidden="true">&times;</span>
								<span class="sr-only">Close</span>
							</button>
							<h4 class="modal-title" id="">Lightbox - Auto Submit</h4>
						</div>
							
						<!-- Modal Body -->
						<div class="modal-body">
							<label class="alert alert-danger" id="user-to-lightbox-err" style="display: none;"></label>
							<h4 class="">Create New LightBox</h4>
							<div class="form-group">
								<label>Title</label>
								<input type="text" name="lightbox_name" id="lightbox_name" class="form-control" value=""/>
							</div>
							<div class="form-group">
								<label>Description</label>
								<textarea name="description" id="description" class="form-control">auto-submit results</textarea>
							</div>
	
							<h3 class="text-center">OR</h3>
	
							<h4 class="">Select LightBox</h4>
							<div class="form-group">
								<select name="lightbox_id" id="lightbox_id" class="form-control">
									<option value="">-- Select Lightbox --</option>
									<?php 
										$sql_lightbox = "select * from agency_lightbox al where casting_id = ".$_POST['casting_id']." AND lightbox_type = 'auto_submit'";
										$res_lightbox = mysql_query($sql_lightbox);
										while ($row = mysql_fetch_assoc($res_lightbox)) {
									?>
										<option value="<?php echo $row['lightbox_id']; ?>"><?php echo $row['lightbox_name']; ?></option>
									<?php } ?>
								</select>
							</div>
						</div>
							
						<!-- Modal Footer -->
						<div class="modal-footer">
							<!-- <input type="hidden" name="users" id="users" value="<?php echo $_POST['check_users']; ?>" /> -->
							<input type="hidden" name="casting_id" value="<?php echo $_POST['casting_id']; ?>" />
							<input type="hidden" name="client_id" value="<?php echo $_POST['client_id']; ?>" />
							<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
							<input type="submit" class="btn btn-success" name="user_to_lightbox_submit" value="Auto Submit"/>
						</div>
						</div>
				</form>
			</div>
	
		<?php
		}


	if($_POST['name'] == 'get_submission_byId'){
		$submission = get_submission_byId($_POST['submission_id']);
		echo json_encode($submission);
	}

	if($_POST['name'] == 'add_event_tm'){
		// echo "<pre>";
		// print_r($_POST);

		$sql = "INSERT INTO agency_event
				SET
				event_color_id = ".$_POST['event_color_id'].",
				title = '".$_POST['title']."',
				start = '".$_POST['start']."',
				end = '".$_POST['end']." 23:59:59'
				";

		if(mysql_query($sql)){
			echo true;
		}else{
			echo false;
		}
	}

	if($_POST['name'] == "sch_edit_Modal"){
	?>

		<div class="modal-dialog">
			<?php
				// echo "<pre>";
				// print_r($_POST);
				// echo "</pre>";

				$event_sql = "select ae.event_id,ae.title,ae.notes,ae.start,ae.end,aec.color_code from agency_event ae
									  LEFT JOIN agency_event_color aec ON aec.event_color_id = ae.event_color_id
									  WHERE ae.event_id = ".$_POST['event_id']."
                                    ";
				$event_query = mysql_query($event_sql);
				$event = array();
				while ($row = mysql_fetch_assoc($event_query)) {
					$event = $row;
				}
		  
			?>
			<form role="form" id="event_edit_frm" class="_form" method="post" action="">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">
							<span aria-hidden="true">&times;</span>
							<span class="sr-only">Close</span>
						</button>
						<h4 class="modal-title" id="">Edit Event <?php echo $event['title']; ?></h4>
					</div>
						
					
					<div class="modal-body">
						<label class="alert alert-danger" id="user-to-lightbox-err" style="display: none;"></label>
						<div class="form-group">
							<label>Event Title</label>
							<input type="text" name="title" id="title" class="form-control" value="<?php echo $event['title']; ?>"/>
						</div>

						<div class="form-group">
							<label>Event Start Date</label>
							<input name="start" id="event_start_date_edit" type="text" class="form-control" placeholder="Event Start Date" value="<?php echo date('Y-m-d',strtotime($event['start'])); ?>">
						</div>

						<div class="form-group">
							<label>Event End Date</label>
							<input name="end" id="event_end_date_edit" type="text" class="form-control" placeholder="Event End Date" value="<?php echo date('Y-m-d',strtotime($event['end'])); ?>">
						</div>

						<div class="form-group">
							<label>Event Notes</label>
							<textarea name="notes" id="notes" cols="30" rows="4" class="form-control"><?php echo $event['notes']; ?></textarea>
						</div>
					</div>
						
					<div class="modal-footer">
						<div class="row">
							<div class="text-left col-md-6">
								<input type="submit" class="btn btn-danger btn-flat" name="delete_event_submit" value="Delete"/>
							</div>
							<div class="text-right col-md-6">
								<input type="hidden" name="event_id" value="<?php echo $event['event_id']; ?>" />
								<button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Close</button>
								<input type="submit" class="btn btn-success btn-flat" name="edit_event_submit" value="Save"/>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>

		<script>

			$("#event_edit_frm").validate({
				rules: {
					"title":"required",
					"start":"required",
					"end":"required",
				},
				errorElement: "em",
				errorPlacement: function ( error, element ) {
					// Add the `help-block` class to the error element
					error.addClass( "help-block" );

					if ( element.prop("type") === "checkbox") {
						// error.insertAfter(element.parent("label"));
						error.insertAfter(element.parents('label').siblings('.checkbox_err'));
					} else {
						error.insertAfter(element);
					}
				},
			});

			$("#event_start_date_edit").datepicker({
			    dateFormat: "yy-mm-dd",
			    changeMonth: true,
			    changeYear: true,
			    minDate: 0,
			    onSelect: function () {
			        var dt2 = $('#event_end_date_edit');
			        var startDate = $(this).datepicker('getDate');
			        // startDate.setDate(startDate.getDate() + 30);
			        var minDate = $(this).datepicker('getDate');
			        var dt2Date = dt2.datepicker('getDate');
			        dt2.datepicker('option', 'minDate', minDate);
			    }
			});

			$('#event_end_date_edit').datepicker({
			    dateFormat: "yy-mm-dd",
			    changeMonth: true,
			    changeYear: true,
			    minDate: 0
			});
		</script>

	<?php
	}

}

if(isset($_GET['name'])) {
	if($_GET['name'] == 'view_msg'){
		view_message($_GET);
	}

	if($_GET['name'] == 'delete_msg'){
		$res = delete_message($_GET);
		echo $res;
		exit;
	}
}

mysql_close(); // Close the database connection.
?>
