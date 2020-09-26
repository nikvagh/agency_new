<?php
	$page = "profile_view";
	$page_selected = "unapproved_accounts";
	include('header.php');
	include('../forms/definitions.php');
  	include('../includes/agency_dash_functions.php');

	$notification = array();
	if(isset($_GET['user_id']) && $_GET['user_id'] != ""){

		$user_id = $_GET['user_id'];
		$profileid= (int) trim($_GET['user_id']);

		$sql = "SELECT ap.*,fu.* FROM agency_profiles ap 
				INNER JOIN forum_users fu ON fu.user_id = ap.user_id
				WHERE ap.user_id='$profileid'";
		$result=mysql_query($sql);
		$userInfo = $userinfo = sql_fetchrow($result);

		$profile_id = $_GET['user_id'];

		// echo "<pre>";
		// print_r($_POST);
		// echo "</pre>";
		// exit;

		// }
		// exit;
	}
?>

<div id="page-wrapper">
    <div class="" id="main">

    		<h3><?php echo $userinfo['firstname']; ?> Profile</h3>
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
		        <div class="col-md-3">
			        <!-- Profile Image -->
			        <div class="box box-theme">
			            <div class="box-body box-profile">
			            	<?php 
                                if(file_exists('../uploads/users/' . $profile_id . '/profile_pic/thumb/128x128_'.$userInfo['user_avatar']) ){
                                    $profile_thumb = '../uploads/users/' . $profile_id . '/profile_pic/thumb/128x128_'.$userInfo['user_avatar'];
                                }else{
                                    $profile_thumb = '../images/friend.gif';
                                }
                            ?>
			                <img class="profile-user-img img-responsive img-circle" src="<?php echo $profile_thumb; ?>" alt="User profile picture" style="height:84px;width:84px;"/>

			              	<h3 class="profile-username text-center">
			              	<?php echo $userinfo['firstname'];
								// if(agency_privacy($profileid, 'lastname')) {
									echo ' ' . $userinfo['lastname'];
								// }
							?>
							</h3>

			              	<p class="text-muted text-center"><?php echo $userinfo['username']; ?></p>

				            <ul class="list-group list-group-unbordered">
				                <li class="list-group-item">
				                  <b>Profile Visits</b> <a class="pull-right"><?php echo $userinfo['visits']; ?></a>
				                </li>
				                <!-- <li class="list-group-item">
				                  <b>Following</b> <a class="pull-right">543</a>
				                </li> -->
				                <?php
				                	$friends = get_agency_friends(98);

				                	// echo "<pre>";print_r($friends);

				     //            	$sql_frd_total = "SELECT friends_id FROM agency_friends where (user_id = ".$userinfo['user_id']." OR friend_id = ".$userinfo['user_id'].") AND confirmed = 1";
									// $result_frd_total = mysql_query($sql_frd_total);
								?>
				                <li class="list-group-item">
				                  <b>Friends</b> <a class="pull-right"><?php echo count($friends); ?></a>
				                </li>
				            </ul>
				            <!-- <a class="a2a_dd" href="javascript:void(0)"><img src="images/share.gif" border="0" alt="Share/Save/Bookmark"/></a>
				            	<script type="text/javascript">a2a_linkname=document.title;a2a_linkurl=location.href;</script><script type="text/javascript" src="http://static.addtoany.com/menu/page.js"></script> -->

			              	<a href="javascript:void(0)" class="btn btn-theme btn-block a2a_dd">Share</a>
			              	<script type="text/javascript">a2a_linkname=document.title;a2a_linkurl=location.href;</script>
			              	<script type="text/javascript" src="http://static.addtoany.com/menu/page.js"></script>
			            </div>
			            <!-- /.box-body -->
			        </div>
			       	<!-- /.box -->


		        	<?php
						$sql = "SELECT * FROM agency_profile_highlights WHERE user_id='$profileid' AND highlight !='' AND highlight IS NOT NULL ORDER BY highlight_ID DESC LIMIT 3";
						$result = mysql_query($sql);
						$num_highlights = mysql_num_rows($result);
						if($num_highlights > 0){
					?>
						<div class="box box-theme">
							<div class="box-header with-border">
								<h3 class="box-title">Highlights</h3>
							</div>
          					<div class="box-body">
								<?php
									while ($row = sql_fetchrow($result)) {
										$highlightid = $row['highlight_ID'];
										$highlight = $row['highlight'];
										echo $highlight."<br/><br/>"; 
									}
								?>
          					</div>
          				</div>
          			<?php } ?>

		        </div>

		        <div class="col-md-9">
		          	<div class="nav-tabs-custom tab-theme">
			            <ul class="nav nav-tabs">
			              <li class="active"><a href="#general" data-toggle="tab" aria-expanded="true">General</a></li>
			              <li class=""><a href="#friends" data-toggle="tab" aria-expanded="false">Friends</a></li>
			              <li class=""><a href="#wall" data-toggle="tab" aria-expanded="false">Wall</a></li>
			              <!-- <li class=""><a href="#reel" data-toggle="tab" aria-expanded="false">Reel/Vo</a></li> -->
			            </ul>
			            <div class="tab-content">

			            	<div class="tab-pane active" id="general">
				            	<strong> Email</strong>
					            <p class="text-muted"><?php echo $userinfo['user_email']; ?></p>
					            <hr>

					            <strong> Phone</strong>
					            <p class="text-muted"><?php echo $userinfo['phone']; ?></p>
					            <hr>

					            <strong> Location</strong>
					            <p class="text-muted"><?php echo $userinfo['location']; ?></p>
					            <hr>

					            <strong> City</strong>
					            <p class="text-muted"><?php echo $userinfo['city']; ?></p>
					            <hr>

					            <strong> State</strong>
					            <p class="text-muted"><?php echo $userinfo['state']; ?></p>
					            <hr>

					            <strong> Country</strong>
					            <p class="text-muted"><?php echo $userinfo['country']; ?></p>
					            <hr>
					        </div>

			            	<div class="tab-pane" id="friends">
				            	<?php
				     				//        		$sql = "SELECT DISTINCT friend_id FROM agency_friends, forum_users WHERE agency_friends.friend_id=forum_users.user_id AND agency_friends.user_id='$profileid' ";
									// $result=mysql_query($sql);
									if(count($friends) == 0) { // no requests
									 	echo '<br /><br /><p align="center">No friends at this time.</p>';
									} else {
								?>
									<div class="row">
										<?php
										 	// $count = 8;
											// while($row = sql_fetchrow($result)) {
										 // 		 $friendid = $row['friend_id'];

											foreach ($friends as $key => $value) {
										?>
									 		<div class="col-sm-2">
												<?php
													// get avatar
													// $sql2 = "SELECT registration_date FROM agency_profiles WHERE user_id='$friendid'";
													// $result2=mysql_query($sql2);
											 		// 	if($row2 = sql_fetchrow($result2)) {
													// 	$posterfolder = '../talentphotos/' . $friendid . '_' . $row2['registration_date'] . '/';
													// 	echo '<a href="profile-view.php?user_id=' . $friendid . '">
													// 		<img src="';
													// 		if(file_exists($posterfolder . 'avatar.jpg')) {
													// 			echo   $posterfolder . 'avatar.jpg';
													// 		} else if(file_exists($posterfolder . 'avatar.gif')) {
													// 			echo   $posterfolder . 'avatar.gif';
													// 		} else {
													// 			echo '../images/friend.gif';
													// 		}
													// 	echo '" /></a>';
													// }
												?>	

												<?php 
					                                if(file_exists('../uploads/users/' . $value['user_id'] . '/profile_pic/thumb/128x128_'.$value['user_avatar']) ){
					                                    $friend_thumb = '../uploads/users/' . $value['user_id'] . '/profile_pic/thumb/128x128_'.$value['user_avatar'];
					                                }else{
					                                    $friend_thumb = '../images/friend.gif';
					                                }
					                            ?>

												<a href="<?php echo 'profile-view.php?user_id='.$value['user_id']; ?>">
													<img src="<?php echo $friend_thumb; ?>"/>
												</a>
											</div>

											<?php
												// if($count == 1) {
												// 	$count = 8;
												// 	echo '<br clear="all" />';
												// } else {
												// 	$count--;
												// }
											?>

										<?php } ?>
									</div>

								<?php } ?>
				            </div>

				            <div class="tab-pane" id="wall">
					            <ul class="timeline timeline-inverse">
									<?php
						            	$sql = "SELECT * FROM agency_wall WHERE user_id='$profileid' ORDER BY date DESC LIMIT 10";
										$result=mysql_query($sql);
										while($row = sql_fetchrow($result)) {
								
											//get poster information
											$postid = $row['post_id'];
											$posterid = $row['poster_id'];
											$postername = $row['poster_fname'];
											if(agency_privacy($posterid, 'lastname')) {
												$postername .= ' ' . $row['poster_lname'];
											}
											$message = $row['message'];
											$postdate = date("l F jS, Y g:ia", strtotime($row['date']));
										?>
											<!-- echo '<div class="AGENCYWallPrimary"><div class="AGENCYWallThumbnail">'; -->

												<li>
													<?php
														// get avatar
														// $sql2 = "SELECT registration_date FROM agency_profiles WHERE user_id='$posterid'";
														// $result2=mysql_query($sql2);
														// if($row2 = sql_fetchrow($result2)) {
														// 	$posterfolder = '../talentphotos/' . $posterid . '_' . $row2['registration_date'] . '/';
											
														// 	echo '<a href="profile.php?u=' . $posterid . '"><img src="';
														// 		if(file_exists($posterfolder . 'avatar.jpg')) {
														// 			echo   $posterfolder . 'avatar.jpg';
														// 		} else if(file_exists($posterfolder . 'avatar.gif')) {
														// 			echo   $posterfolder . 'avatar.gif';
														// 		} else {
														// 			echo '../images/friend.gif';
														// 		}
														// 	echo '" /></a>';
														// }
													?>
								                    <i class="fa fa-user bg-blue"></i>
								                    <div class="timeline-item">
								                      	<span class="time"><i class="fa fa-clock-o"></i> <?php echo $postdate; ?></span>
								                      	<h3 class="timeline-header"><a href="profile-view.php?user_id=<?php echo $posterid; ?>"><?php echo $postername; ?></a></h3>
								                      	<div class="timeline-body">
								                        	<?php echo $message; ?>
								                      	</div>
								                    </div>
								                </li>

												<!-- <li><i class="fa fa-envelope bg-blue"></i> -->
													<?php
														// get avatar
														// $sql2 = "SELECT registration_date FROM agency_profiles WHERE user_id='$posterid'";
														// $result2=mysql_query($sql2);
														// if($row2 = sql_fetchrow($result2)) {
														// 	$posterfolder = 'talentphotos/' . $posterid . '_' . $row2['registration_date'] . '/';
											
														// 	echo '<a href="profile.php?u=' . $posterid . '"><img src="';
														// 		if(file_exists($posterfolder . 'avatar.jpg')) {
														// 			echo   $posterfolder . 'avatar.jpg';
														// 		} else if(file_exists($posterfolder . 'avatar.gif')) {
														// 			echo   $posterfolder . 'avatar.gif';
														// 		} else {
														// 			echo 'images/friend.gif';
														// 		}
														// 	echo '" /></a>';
														// }
											
														// echo '</div>'; // close div for thumbnail

													?>

													<!-- <div class="timeline-item">
								                      	<span class="time"><i class="fa fa-clock-o"></i> <?php echo $postdate; ?></span>
								                      	<h3 class="timeline-header"><a href="profile.php?u='<?php echo $posterid; ?>"><?php echo $postername; ?></a></h3>
								                      	<div class="timeline-body">
								                        	<?php echo $message; ?>
								                      	</div>
								                    </div> -->
												<!-- </li> -->
								
										<?php } ?>

										<li>
					                    	<i class="fa fa-clock-o bg-gray"></i>
					                  	</li>
								</ul>

								<?php 
									// if(is_active()) {
									// 	echo '<form style="padding:20px 0" method="post" action="profile.php?tab=Wall&amp;u=' . $profileid. '" name="postonwall">' .
									// 		'<input type="text" name="wallpost" style="width:370px" /> <input type="submit" value="Post" />' .
									// 		'<input type="hidden" value="' . time() . '" name="creation_time"/>' .
									// 		'<input type="hidden" value="' . agency_add_form_key('postonwall') . '" name="form_token"/>';
									// 		if(showcaptcha($_SESSION['user_id'])) {
									// 			echo recaptcha_get_html($publickey, $error);
									// 		}
									// 		echo '</form>';
									// } 
								?>
				            </div>

			            </div>
			            <!-- /.tab-content -->
		          	</div>

	          			
		        </div>
		        
		    </div>
		
	</div>
</div>


<div class="modal fade" id="generalInfoModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">General Info</h4>
            </div>
            
            <!-- Modal Body -->
            <div class="modal-body">

            	<?php
					if(!empty($userinfo['experience'])) {
						echo 'Experience Level: <img src="../images/' . $experienceimages[$userinfo['experience']] . '.gif" /><br /><br />';
					}
					if(!empty($userinfo['gender'])) {
						echo 'Gender: <b>';
						switch ($userinfo['gender']) {
							case 'F':
								echo 'Female';
								break;
							case 'M':
								echo 'Male';
								break;
							default:
								echo 'Other';
								break;
						}
						echo '</b><br /><br />';
					}
					$sql = "SELECT ethnicity FROM agency_profile_ethnicities WHERE user_id='$profileid'";
					$result = mysql_query($sql);
					if(mysql_num_rows($result) > 0) {
						$num_cats = mysql_num_rows($result);
						echo 'Ethnicity: <b>';
						while ($row = sql_fetchrow($result)) {
							echo $row['ethnicity'];
							if($num_cats > 1) echo ', ';
							$num_cats--;
						}
						echo '</b><br /><br />';
					}
					echo 'Location: <b>' . $userinfo['city']; if(!empty($userinfo['city'])) { echo ', '; } echo $userinfo['state'] . ' ' . $userinfo['country'] . '</b><br /><br />';

					$sql = "SELECT category FROM agency_profile_categories WHERE user_id='$profileid'";
					$result = mysql_query($sql);
					if(mysql_num_rows($result) > 0) {
						$num_cats = mysql_num_rows($result);
						echo 'Categories: <b>';
						while ($row = sql_fetchrow($result)) {
							echo $row['category'];
							if($num_cats > 1) echo ', ';
							$num_cats--;
						}
						echo '</b><br /><br />';
					}

					$sql = "SELECT union_name FROM agency_profile_unions WHERE user_id='$profileid'";
					$result = mysql_query($sql);
					if(mysql_num_rows($result) > 0) {
						$num_cats = mysql_num_rows($result);
						echo 'Unions: <b>';
						while ($row = sql_fetchrow($result)) {
							echo $row['union_name'];
							if($num_cats > 1) echo ', ';
							$num_cats--;
						}
						echo '</b><br /><br />';
					}

					echo 'Height: <b>' . floor($userinfo['height']/12) . '\' ' . $userinfo['height'] % 12 . '"</b><br /><br />
						Waist: <b>' . escape_data($userinfo['waist']) . '"</b><br /><br />';

					if($userinfo['gender'] != 'M') { // if female or "other"
					echo 'Bust: <b>' . escape_data($userinfo['bust']) . '"</b><br /><br />
						Cup Size <b>: ' . escape_data($bracups[$userinfo['cup']]) . '</b><br /><br />
						Hips: <b>' . escape_data($userinfo['hips']) . '"</b><br /><br />
						Dress: <b>' . escape_data($userinfo['dress']) . '"</b><br /><br />';
					}

					if($userinfo['gender'] != 'F') { // if male or "other"
					echo 'Suit: <b>' . agency_print_suit(escape_data($userinfo['suit'])) . '</b><br /><br />
						Neck: <b>' . escape_data($userinfo['neck']) . '"</b><br /><br />
						Shirt: <b>' . escape_data($userinfo['shirt']) . '"</b><br /><br />
						Inseam: <b>' . escape_data($userinfo['inseam']) . '"</b><br /><br />';
					}

					echo 'Shoe: <b>' . escape_data($userinfo['shoe']) . '</b><br /><br />
						Hair: <b>' . $userinfo['hair'] . '</b><br /><br />
						Eyes: <b>' . escape_data($userinfo['eyes']) . '</b><br /><br />';

					if($_SESSION['user_id'] == $profileid) { // if this is the profile of the logged in user
						echo '<br><br><a class="AGENCY_graybutton" href="myaccount.php">edit</a>';
					}
				?>

            </div>
            
            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="resumeModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Resume</h4>
            </div>
            
            <!-- Modal Body -->
            <div class="modal-body">
                <?php
					if($_SESSION['user_id'] == $profileid) { // if this is the profile of the logged in user
						echo '<div align="center">Click <a class="AGENCY_graybutton" href="myaccount.php?tab=bio#resumeanchor">EDIT</a> to type in your resume for easy viewing';
						echo '<br /><br />OR use the form below to upload a document</div>';
					}
				?>

				<hr>
				<?php
					// if((agency_account_type() == 'client' && is_active()) || $loggedin == $profileid) {
						if(!empty($userinfo['resume'])) {
							if(file_exists($folder . '/' . $userinfo['resume'])) {
								// if($loggedin == $profileid) {
								// 	echo '<b>YOU CURRENTLY HAVE A RESUME UPLOADED</b><br /><br />';
								// }		
								echo '<a href="' . $folder . $userinfo['resume'] . '" target="_blank" style="color:#333333"><b>CLICK HERE TO DOWNLOAD/PRINT UPLOADED RESUME FILE</b></a><br /><a href="' . $folder . $userinfo['resume'] . '" target="_blank" style="text-decoration:none"><img src="../images/resume1.gif" border="0" style="padding-top:5px;"></a>';
								
								// if($loggedin == $profileid) {
								// 	echo '<br /><br /><a href="profile.php?u=' . $loggedin . '&delfile=resume">[delete resume file]</a>';
								// }				
								
							}
						} else {
							echo 'no resume on file';
						}
					// } else {
					// 	echo 'As a member of our Talent pool, you have the option to show your resume to thousands of Clients.  For privacy reasons your resume will only be available to our screened Clients and not to other members or the general public.';
					// }

					// if($_SESSION['user_id'] == $profileid) { // if this is the profile of the logged in user
					// 	echo '<div style="padding:30px 0">
					// 		<form enctype="multipart/form-data" action="profile.php?u=' . $profileid . '" method="post">
					// 			<input type="hidden" name="MAX_FILE_SIZE" value="5000000" />
					// 			<input type="file" name="resumefile" /><br /><br />
					// 			<input type="submit" name="submit" value="Upload New Resume (<5MB)" />
					// 		</form>
					// 		<br /><font color="gray">*for better security, do not use "resume"<br />or your name for the resume filename</font>
					// 		</div>';
					// }
				?>
            </div>
            
            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="headshotModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title" id="headshotModalLabel">Headshot</h4>
            </div>
            
            <!-- Modal Body -->
            <div class="modal-body">
                <?php
					// if($_SESSION['user_id'] == $profileid || (agency_account_type() == 'client' && is_active())) {
						if(!empty($userinfo['headshot'])) {
							if(file_exists($folder . '/' . $userinfo['headshot'])) {
								echo '<a href="' . $folder . $userinfo['headshot'] . '" target="_blank"><b>click here to view my headshot</b></a>';
							} else {
								echo 'headshot not found';
							}
						} else {
							echo 'no headshot on file';
						}
						
						// if($_SESSION['user_id'] == $profileid) { // if this is the profile of the logged in user
						// 	echo '<div style="padding:50px 0">
						// 		<form enctype="multipart/form-data" action="profile.php?u=' . $profileid . '" method="post">
						// 			<input type="hidden" name="MAX_FILE_SIZE" value="5000000" />
						// 			<input type="file" name="headshotfile" /><br /><br />
						// 			<input type="submit" name="submit" value="Upload New Headshot (<5MB)" />
						// 		</form>
						// 		</div>';
						// }
					// } else {
					// 	echo 'As a member of our Talent pool, you have the option to show your headshot to thousands of Clients.  For privacy reasons, because often headshots contain personal information some people do not wish to make public, your headshot will only be available to our screened Clients and not to other members or the general public.';
					// }
				?>
            </div>
            
            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="bioModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Biography</h4>
            </div>
            
            <!-- Modal Body -->
            <div class="modal-body">
                <?php 
					// if((agency_privacy($profileid, 'bio') || (agency_account_type() == 'client' && is_active())) && !empty($userinfo['bio'])) {
					if(!empty($userinfo['bio'])) {
						echo nl2br($userinfo['bio']) . '<br /><div align="center"><a href="../pdf_bio.php?u=' . $profileid . '" target="_blank" style="text-decoration:none"><img src="../images/biography1.gif" border="0" style="padding-top:5px;"></a></div>'; 
					} else {
						echo 'This user has has not entered a biography or has set their biography to private.';
					}
					// if($_SESSION['user_id'] == $profileid) { // if this is the profile of the logged in user
					// 	echo '<br><br><a class="AGENCY_graybutton" href="myaccount.php?tab=bio">edit</a>';
					// }
				?>
            </div>
            
            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="linksModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Links</h4>
            </div>
            
            <!-- Modal Body -->
            <div class="modal-body">
                <?php
					if(agency_privacy($profileid, 'links') || (agency_account_type() == 'client' && is_active())) {
						$sql = "SELECT * FROM agency_profile_links WHERE user_id='$profileid' AND link !='' AND link IS NOT NULL";
						$result = mysql_query($sql);
						while ($row = sql_fetchrow($result)) {
							$link = $row['link'];
							$link_desc = $row['link_desc'];
							echo '<div class="AGENCYleftlink"><a target="_blank" href="http://' . $link . '">' . $link . '</a>';
							if(!empty($link_desc)) {
								echo ' - ' . $link_desc;
							}
							echo '</div><br />';
						}
						if($_SESSION['user_id'] == $profileid) { // if this is the profile of the logged in user
							echo '<br><br><a class="AGENCY_graybutton" href="myaccount.php?tab=links">edit</a>';
						}
					} else {
						echo 'This user has set their links to private.';
					}
				?>
            </div>
            
            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="skillModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Skills</h4>
            </div>
            
            <!-- Modal Body -->
            <div class="modal-body">
                <?php
					if(agency_privacy($profileid, 'skills') || (agency_account_type() == 'client' && is_active())) {
						if(!empty($userinfo['skills_language'])) {
							echo 'Languages: <b>' . nl2br($userinfo['skills_language']) . '</b><br /><br />';
						}
						if(!empty($userinfo['skills_sports_music'])) {
							echo 'Sports & Music: <b>' . nl2br($userinfo['skills_sports_music']) . '</b><br /><br />';
						}
						if(!empty($userinfo['skills_other'])) {
							echo 'Other: <b>' . nl2br($userinfo['skills_other']) . '</b>';
						}
						// echo nl2br($userinfoskills']);
						if($_SESSION['user_id'] == $profileid) { // if this is the profile of the logged in user
							echo '<br><br><a class="AGENCY_graybutton" href="myaccount.php?tab=experience">edit</a>';
						}
					} else {
						echo 'This user has set their skills to private.';
					}
				?>
            </div>
            
            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<?php include('footer_js.php'); ?>
<script src="../dashboard/assets/DataTables/datatables.min.js"></script> 
<script src="../dashboard/assets/DataTables/dataTables.bootstrap.min.js"></script>

<script src="../dashboard/assets/OwlCarousel/owl.carousel.min.js"></script>
<script src="../dashboard/assets/fancybox/jquery.fancybox.min.js"></script>

<!-- <script type="text/javascript" src="http://code.jquery.com/ui/1.11.0/jquery-ui.min.js"></script>
<script type="text/javascript" src="../dashboard/assets/jQuery-DateTimepicker-Addon/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript" src="../dashboard/assets/jQuery-DateTimepicker-Addon/i18n/jquery-ui-timepicker-addon-i18n.min.js"></script>
<script type="text/javascript" src="../dashboard/assets/jQuery-DateTimepicker-Addon/jquery-ui-sliderAccess.js"></script>
<script type="text/javascript" src="../dashboard/assets/js/jquery.validate.js"></script> -->

<script>
	$(document).ready( function () {
	  //   $('.datatable').DataTable({
	  //       "order": [[ 0, "desc" ]],
	  //       'columnDefs': [{
			//     'targets': [5], /* column index */
			//     'orderable': false, /* true or false */
			// }]
	  //   });	

	  
		  	// mianSrcDefault = $('.photo-main').attr('src');
		  	// photoElement = $('.photo-main');

		   //  $('.photo-thumb').bind("mouseover", function(e) {
		   //  	new_src = $(this).attr('src');
		   //      $(photoElement).attr('src',new_src);
		   //  });

		   //  $('.photo-thumb').bind("mouseout", function(e) {
		   //       $(photoElement).attr('src',mianSrcDefault);
		   //  });
	});

	function cardimageswap(image) {
		// $("#primaryspot1").css('display','none');
		$("#primaryspot1").html('<img class="img-responsive img-thumbnail photo-main" id="primarypic" src="'+image+'" alt="Photo" />');
		// $("#primaryspot2").css('display','block');
	}
	function cardimageswapout() {
		// document.getElementById('primaryspot').style.display='none';
		// $("#primarypic2").css('display','none');
		// $("#primarypic1").css('display','block');
	}

  	var owl = $('.owl-carousel');
  	owl.owlCarousel({
  		autoWidth:true,
	    margin: 10,
	    nav: false,
	    dots:true,
	    loop: false,
	    responsive: {
	      0: {
	        items: 1
	      },
	      600: {
	        items: 3
	      },
	      1000: {
	        items: 5
	      }
    	}
 	});

</script>
<?php include('footer.php'); ?>