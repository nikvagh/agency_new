<?php
  include('header_code.php');
  include('includes/agency_dash_functions.php');
?>

<!DOCTYPE html>
<html>

<head>
  	<title>Profile</title>
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
    <div class="container-fluid breadcrumb-box text-center">
        <ul class="btn-group breadcrumb">
            <li><a href="<?php echo $base_url; ?>" class="">Home</a></li>
            <li><a class="">profile</a></li>
        </div>
    </div>

<?php
	$page = "profile_view";
	$page_selected = "unapproved_accounts";
	// include('header.php');
	// include('../forms/definitions.php');
  	// include('../includes/agency_dash_functions.php');

	$notification = array();
	if(isset($_GET['user_id']) && $_GET['user_id'] != ""){

		$user_id = $_GET['user_id'];
		$profileid= (int) trim($_GET['user_id']);

		$sql = "SELECT ap.*,fu.* FROM agency_profiles ap 
				INNER JOIN forum_users fu ON fu.user_id = ap.user_id
				WHERE ap.user_id='$profileid'";
		$result=mysql_query($sql);
		$userinfo = sql_fetchrow($result);

	}

	$friends = get_agency_friends_normal($userinfo['user_id']);
	$folder_user = 'uploads/users/'.$userinfo['user_id'];
?>

<div id="page-wrapper">
    <div class="profile-page" id="main">

			<div class="container-fluid">

				<div class="col-md-12">
					<h3>User Profile</h3>
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
			
		        <div class="col-md-3">
					<!-- Profile Image -->
			        <div class="box box-theme">
			            <div class="box-body box-profile">
							<?php
								if(file_exists($folder_user .'/profile_pic/thumb/128x128_'. $userinfo['user_avatar'])) {
									$profile_pic = $folder_user .'/profile_pic/thumb/128x128_'. $userinfo['user_avatar'];
								} else {
									$profile_pic = 'images/friend.gif';
								}	
							?>
							<a class="text-center" style="width: 100%;display: inline-block;">
								<img class="profile-user-img img-circle" src="<?php echo $profile_pic; ?>" alt="User profile picture" style="height:128px;width:128px"/>
							</a>

			              	<h3 class="text-center text-black">
								<?php echo $userinfo['firstname'];
									if(agency_privacy($profileid, 'lastname')) {
										echo ' ' . $userinfo['lastname'];
									}
								?>
							</h3>

			              	<p class="text-muted text-center"><?php echo $userinfo['username']; ?></p>

				            <ul class="list-group list-group-unbordered">
				                <li class="list-group-item text-black text-left">
				                  Profile Visits <span class="pull-right"><?php echo $userinfo['visits']; ?></span>
				                </li>
				                <!-- <li class="list-group-item">
				                  <b>Following</b> <a class="pull-right">543</a>
				                </li> -->
				                <li class="list-group-item text-black text-left">
				                  Friends <span class="pull-right"><?php echo count($friends); ?></span>
				                </li>
				            </ul>
				            <!-- <a class="a2a_dd" href="javascript:void(0)"><img src="images/share.gif" border="0" alt="Share/Save/Bookmark"/></a>
				            	<script type="text/javascript">a2a_linkname=document.title;a2a_linkurl=location.href;</script><script type="text/javascript" src="http://static.addtoany.com/menu/page.js"></script> -->

			              	<a href="javascript:void(0)" class="btn btn-theme btn-block a2a_dd color-white btn-flat">Share</a>
			              	<script type="text/javascript">a2a_linkname=document.title;a2a_linkurl=location.href;</script>
			              	<script type="text/javascript" src="http://static.addtoany.com/menu/page.js"></script>
			            </div>
			            <!-- /.box-body -->
			        </div>
			       	<!-- /.box -->	

			        <!-- About Me Box -->
			        <div class="box box-theme info-box">
			            <div class="box-header with-border text-left">
			              <h3 class="box-title">Talent Info </h3>
			            </div>
			            <!-- /.box-header -->
			            <div class="box-body text-left">
			            	<strong class="text-black"><i class="fa fa-map-marker margin-r-5"></i> Location</strong>
				            <p class="text-muted"><?php echo $userinfo['location']; ?></p>
				            <hr>

				            <!-- <div class="row">
					            <div class="col-md-4"> -->
				              		<a href="" class="btn btn-danger btn-xs btn-flat" data-toggle="modal" data-target="#generalInfoModal">General Info</a>
				              	<!-- </div>
				              	<div class="col-md-4"> -->
				              		<a href="" class="btn btn-success btn-xs btn-flat" data-toggle="modal" data-target="#resumeModal">Resume</a>
				              	<!-- </div>
				              	<div class="col-md-4"> -->
				              		<a href="" class="btn btn-info btn-xs btn-flat" data-toggle="modal" data-target="#headshotModal">Headshot</a>
				              	<!-- </div>
				              	<div class="col-md-4"> -->
				              		<a href="" class="btn btn-warning btn-xs btn-flat" data-toggle="modal" data-target="#bioModal">BIO</a>
				              	<!-- </div>
				              	<div class="col-md-4"> -->
				              		<a href="" class="btn btn-primary btn-xs btn-flat" data-toggle="modal" data-target="#linksModal">Links</a>
				              	<!-- </div>
				              	<div class="col-md-4"> -->
				              		<!-- <a href="" class="btn bg-gray btn-xs btn-flat color-white" data-toggle="modal" data-target="#skillModal">Skills</a> -->
				              	<!-- </div> -->
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
						<div class="box box-theme text-black">
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
			              <li class="active"><a href="#Photos" data-toggle="tab" aria-expanded="true">Photos</a></li>
			              <li class=""><a href="#wall" data-toggle="tab" aria-expanded="false">Wall</a></li>
			              <li class=""><a href="#reel" data-toggle="tab" aria-expanded="false">Reel/Vo</a></li>
			              <li class=""><a href="#friends" data-toggle="tab" aria-expanded="false">Friends</a></li>
			            </ul>
			            <div class="tab-content">

				            <div class="tab-pane active" id="Photos">
				            	<div class="row margin-bottom">
				            		<div class="col-sm-8">
				            			<div class="row">
							              	<?php 
							              		// if(isset($folder)) { 
							              			$photo = "SELECT * FROM agency_photos WHERE user_id='$profileid' AND headshot_thumb = 'Y' ORDER BY card_position ASC";
													$photo_res = mysql_query($photo);
														
													while($row = sql_fetchrow($photo_res)) {
														$photos[] = $row;
													}
								              		// echo "<pre>";print_r($photos);
								            ?>

									            <?php foreach ($photos as $key => $value) { ?>
													<?php if ($key == 0){ ?>
											            <div class="col-sm-5" id="primaryspot1">
															<?php if(file_exists($folder_user .'/portfolio/'. $value['filename'])) { ?>
																<img class="img-responsive img-thumbnail photo-main" id="primarypic" src="<?php echo $folder_user .'/portfolio/'. $value['filename']; ?>" alt="Photo" />
															<?php }else{ ?>
																<img class="img-responsive img-thumbnail photo-main" id="primarypic" src="" alt="Photo" />
															<?php } ?>
									                    </div>
									                <?php break; } ?>
								              	<?php } ?>

								              	<div class="col-sm-5">
												    <div class="row">
										              	<?php foreach ($photos as $key => $value) { ?>
												            <div class="col-sm-6">
																<?php if(file_exists($folder_user .'/portfolio/thumb/300x300_'. $value['filename'])) { ?>
																	<img class="img-responsive img-thumbnail photo-thumb" src="<?php echo $folder_user .'/portfolio/thumb/300x300_'. $value['filename']; ?>" alt="Photo" onmouseover="cardimageswap('<?php echo $folder_user .'/portfolio/'. $value['filename']; ?>')" onmouseout="cardimageswapout()"/>
																<?php }else{ ?>

																<?php } ?>
										                    </div>
										              	<?php } ?>
										            </div>
										        </div>

										    <?php //} ?>
										</div>
										
										<div class="row">
											<div class="col-sm-12">
												<hr/>
												<h3>Compcard</h3>
												<a href="<?php echo 'pdf_compcard.php?u='.$userinfo['user_id'].'&card_type='.$userinfo['compcard_type']; ?>" target="_blank">Click TO View</a>
											</div>
										</div>

									    <div class="row">
								        	<div class="col-sm-12">
								        		<hr/>
								        		<h3>Portfolio</h3>
									          	<div class="owl-carousel portfolio-owl-carousel">

										            <?php 
														$sql = "SELECT * FROM agency_photos WHERE user_id='$user_id' ORDER BY order_id";
														$result = mysql_query($sql);
														while($row = sql_fetchrow($result)) {
													?>
														<?php 
														if(file_exists($folder_user .'/portfolio/thumb/128x128_'. $row['filename'])) { ?>
															<div class="item">
																<!-- data-fancybox="portfolio" -->
																<a href="<?php echo $folder_user .'/portfolio/'. $row['filename']; ?>" data-fancybox="portfolio">
																	<img src="<?php echo $folder_user .'/portfolio/thumb/128x128_'. $row['filename']; ?>" height="100" />
																</a>
															</div>
														<?php } ?>

												    <?php } ?>

												</div>
								        	</div>
								      	</div>
									</div>

								    <div class="col-sm-4">
								    	<strong> Height</strong>
							            <p class="text-muted"><?php echo floor($userinfo['height']/12); ?></p>
							            <hr>

										<?php if($userinfo['gender'] == 'F') { ?>
											<strong> Waist</strong>
											<p class="text-muted"><?php echo $userinfo['waist'].'"'; ?></p>
											<hr>
										
								            <strong> Bust</strong>
								            <p class="text-muted"><?php echo $userinfo['bust'].'"'; ?></p>
								            <hr>

								            <strong> Hips</strong>
								            <p class="text-muted"><?php echo $userinfo['hips'].'"'; ?></p>
											<hr>

											<strong> Dress</strong>
								            <p class="text-muted"><?php echo $userinfo['dress'].'"'; ?></p>
											<hr>
											
											<strong> Shoe</strong>
											<p class="text-muted"><?php echo $userinfo['shoe']; ?></p>
											<hr>
								        <?php } ?>

								        <?php if($userinfo['gender'] == 'M') { ?>
								            <strong> Shirt</strong>
								            <p class="text-muted"><?php echo $userinfo['shirt']; ?></p>
								            <hr>

								            <strong> Kids</strong>
								            <p class="text-muted"><?php echo $userinfo['kids']; ?></p>
								            <hr>

								            <strong> Glove</strong>
								            <p class="text-muted"><?php echo $userinfo['glove']; ?></p>
											<hr>
											
											<strong> Cup</strong>
								            <p class="text-muted"><?php echo $userinfo['cup']; ?></p>
											<hr>
											
											<strong> Jacket</strong>
								            <p class="text-muted"><?php echo $userinfo['jacket']; ?></p>
											<hr>
											
											<strong> Pants</strong>
								            <p class="text-muted"><?php echo $userinfo['pants']; ?></p>
											<hr>
											
											<strong> Inseam </strong>
								            <p class="text-muted"><?php echo $userinfo['inseam']; ?></p>
											<hr>
											
											<strong> Hat</strong>
								            <p class="text-muted"><?php echo $userinfo['hat']; ?></p>
								            <hr>
								        <?php } ?>

							            <strong> Hair</strong>
							            <p class="text-muted"><?php echo $userinfo['hair_color'] .', '. $userinfo['hair_length']; ?></p>
							            <hr>

							            <strong> Eyes</strong>
							            <p class="text-muted"><?php echo $userinfo['eye_color'] .', '. $userinfo['eye_shape']; ?></p>
							            <hr>

							            <?php 
								            $sql = "SELECT * FROM agency_profile_unions WHERE user_id='$profileid'";
											$result=mysql_query($sql);
										    $num_results = mysql_num_rows($result);
											$current = 1;
											if($num_results) {
										?>
											<strong> Union(s)</strong>
											<p class="text-muted">
												<?php while($row = sql_fetchrow($result)) { ?>
													<?php 
														echo $row['union_name'];
											   			if($current < $num_results){ echo ', '; } 
											   		?>
												<?php $current++; } ?>
											</p>

										<?php } ?>
								    </div>
					            </div>
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

				            <div class="tab-pane" id="reel">
				            	<?php
									if(isset($_GET['deletereel'])) {
										$deletereel = (int) $_GET['deletereel'];
										$query = "DELETE FROM agency_reel WHERE reel_id='$deletereel' AND user_id='$userid'";
										mysql_query($query);
									}
									
									if(isset($_GET['deletevo'])) {
										$deletevo = (int) $_GET['deletevo'];
										$query = "DELETE FROM agency_vo WHERE vo_id='$deletevo' AND user_id='$userid'";
										mysql_query($query);
										unlink($folder . $deletevo . '.mp3');
									}		
									
									if(isset($_POST['submitvo'])) {
										if($_POST['MAX_FILE_SIZE'] != '10000000') {
											die('upload form has been tampered with!');
										}
										if(!empty($_POST['mp3name'])) {
											if(!empty($_FILES['mp3file'])) {
												if (pathinfo($_FILES['mp3file']['name'], PATHINFO_EXTENSION) == 'mp3') {
													if(!file_exists($folder)) { // if folder doesn't exist yet, create it
														mkdir($folder);
														chmod($folder,0777);
													}
													$mp3name = escape_data($_POST['mp3name']);
													$query = "INSERT INTO agency_vo (user_id, vo_name) VALUES ('$userid', '$mp3name')";
													mysql_query($query);
													$vo_id = mysql_insert_id();
													if(is_int($vo_id)) {
														// Move the file over.
														$filename = $folder . $vo_id . '.mp3';
														if (!move_uploaded_file($_FILES['mp3file']['tmp_name'], "$filename")) {
															$submitmessage .= '<p class="AGENCYError">The file could not be uploaded because: ';
											
															// Print a message based upon the error.
															switch ($_FILES['mp3file']['error']) {
																case 1:
																	$submitmessage .= 'The file exceeds the upload_max_filesize setting in php.ini.';
																	break;
																case 2:
																	$submitmessage .= 'The file must be less than 10MB.';
																	break;
																case 3:
																	$submitmessage .= 'The file was only partially uploaded.';
																	break;
																case 4:
																	$submitmessage .= 'No file was uploaded.';
																	break;
																case 6:
																	$submitmessage .= 'No temporary folder was available.';
																	break;
																default:
																	$submitmessage .= 'A system error occurred.';
																	break;
															} // End of switch.
											
															$submitmessage .= '</p>';
															echo $submitmessage;
														}
													}
												} else {
													echo '<p class="AGENCYError">It appears the file you are uploading is not an MP3 or the file may be too large.  If you feel you have received this message in error please contact us.</p>';
												}
											} else {
												echo '<p class="AGENCYError">Please select an MP3 file to upload from your computer.</p>';
											}
										} else {
											echo '<p class="AGENCYError">Please enter a Title for your Voice over as you would like it displayed on your page.</p>';
										}
									}
									
									if(isset($_POST['submitreel'])) {
										if(!empty($_POST['videourl'])) {
											$url_dirty = $_POST['videourl'];
											// find host site
											if(strstr(strtolower($url_dirty),'youtu')) {
												$hostsite = 'youtube';
												// if(preg_match('#(?<=(?:v|i)=)[a-zA-Z0-9-_]+(?=&)|(?<=(?:v|i)\/)[^&\n]+|(?<=embed\/)[^"&\n]+|(?<=‌​(?:v|i)=)[^&\n]+|(?<=youtu.be\/)[^&\n]+#', $url_dirty, $matches)) {
												if(preg_match('#(?<=(?:v|i)=)[a-zA-Z0-9-\_]+(?=&)|(?<=(?:v|i)\/)[^&\n]+|(?<=embed\/)[^"&\n]+|(?<=(?:v|i)=)[^&\n]+|(?<=youtu.be\/)[^&\n]+#', $url_dirty, $matches)) {
													$video_id = $matches[0];
												} else {
													echo '<p class="AGENCYError">Unable to extract ID from info provided.  Please check your link.  If you are unable to submit your YouTube video please contact us.</p>';
												}
											} else if(strstr(strtolower($url_dirty),'vimeo')) {
												$hostsite = 'vimeo';
												
												if(preg_match('/vimeo\.com\/([0-9]{1,10})/', $url_dirty, $matches)) {
													$video_id = $matches[1];
												} else if(preg_match('/player\.vimeo\.com\/video\/([0-9]*)"/', $url_dirty, $matches)) {
													$video_id = $matches[1];
												} else {
													echo '<p class="AGENCYError">Unable to extract ID from info provided.  Please check your link.  If you are unable to submit your Vimeo video please contact us.</p>';
												}
											}
											if(!empty($hostsite)) {
												if(!empty($video_id)) {
													$url_clean = escape_data($url_dirty);
													
													$query = "INSERT INTO agency_reel (user_id, reel_host, reel_link_id, user_input) VALUES ('$userid', '$hostsite', '$video_id', '$url_clean')";
													mysql_query($query);					
												
												} else {
													echo '<p class="AGENCYError">Unable to extract ID from info provided.  Please check your link.  If you are unable to submit your video please contact us.</p>';
												}
											} else {
												echo '<p class="AGENCYError">Your video must be either on YouTube or Vimeo.</p>';
											}				
										} else {
											echo '<p class="AGENCYError">Please enter the URL for your video.</p>';
										}
									}
										
									echo '<script type="text/javascript" language="javascript" src="niftyplayer/niftyplayer.js"></script>';
									echo '<div align="center">';
									$query = "SELECT * FROM agency_vo WHERE user_id='$profileid'";
									$result = mysql_query ($query);
									$num_vos = mysql_num_rows($result);
									if($num_vos > 0) {
										$flag_vo = true;
										echo '<b>VOICE OVER';
										if($num_vos > 1) echo 'S';
										echo '</b><br /><br />';
										while ($row = mysql_fetch_assoc($result)) {
											$vo_id = $row['vo_id'];
											$name = $row['vo_name'];
											// $vofile = $folder . $vo_id . '.mp3';
											$vofile = $folder_user . '/audio/' . $row['vo_file'];
											
											if(file_exists($vofile)) {
												?>

												<label for="">
													<?php //echo $name; ?>
													<audio controls>
														<source src="<?php echo $vofile; ?>" type="audio/mpeg">
													</audio>
												</label>

												<?php
												// echo '<br />' . $name . '<br />';
												// echo '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0" width="165" height="37" id="niftyPlayer1" align="">
												// 		<param name=movie value="niftyplayer/niftyplayer.swf?file=' . $vofile . '&as=0">
												// 		<param name=quality value=high>
												// 		<param name=bgcolor value=#FFFFFF>
												// 		<embed src="niftyplayer/niftyplayer.swf?file=' . $vofile . '&as=0" quality=high bgcolor=#FFFFFF width="165" height="37" name="niftyPlayer1" align="" type="application/x-shockwave-flash" swLiveConnect="true" pluginspage="http://www.macromedia.com/go/getflashplayer"> </embed>
												// 	</object>';
													
												echo '<br /><br />';
											} else { // file does not exist, delete it from database
												$query = "DELETE FROM agency_vo WHERE vo_id='$vo_id' AND user_id='$userid'";
												mysql_query($query);
											}
										}
										echo '<br />';
									}
									
									$query = "SELECT * FROM agency_reel WHERE user_id='$profileid'";
									$result = mysql_query ($query);
									$num_reels = mysql_num_rows($result);
									if($num_reels > 0) {
										$flag_reel = true;
										if($flag_vo) {
											echo '<hr/>';
										}
										echo '<br /><b>REEL';
										if($num_reels > 1) echo 'S';
										echo '</b><br /><br />';
										while ($row = mysql_fetch_assoc ($result)) {
											$reel_host = $row['reel_host'];
											$reel_link_id = $row['reel_link_id'];
											$reel_id = $row['reel_id'];
											if($reel_host == 'youtube') {

												echo '<iframe width="440" height="248" src="https://www.youtube.com/embed/' . $row['reel_link_id'] . '" frameborder="0" allowfullscreen></iframe>';
												// if($_SESSION['user_id'] == $profileid) {
												// 	echo '<br /><a href="profile.php?tab=Reel/VO&deletereel=' . $reel_id . '&u=' . $profileid . '" onclick="return confirm(\'Are you sure you want to remove this video from the site?\')">delete</a>';
												// }
											} else if($reel_host == 'vimeo') {
												echo '<iframe src="https://player.vimeo.com/video/'.$reel_link_id.'" width="440" height="259" frameborder="0" title="'.$row['reel_title'].'" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>';
												// echo '<iframe src="http://player.vimeo.com/video/' . $reel_link_id . '" width="440" height="259" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>';	
												// if($_SESSION['user_id'] == $profileid) {
												// 	echo '<br /><a href="profile.php?tab=Reel/VO&deletereel=' . $reel_id . '&u=' . $profileid . '" onclick="return confirm(\'Are you sure you want to remove this video from the site?\')">delete</a>';
												// }				
											}
											echo '<br /><br />';
										}
									}
									
									if(!isset($flag_vo) && !isset($flag_reel)) {
										echo '<br />No Reels or Voice Overs have been posted yet.';
									}
									
									echo '</div>';
								
								?>
				            </div>

				            <div class="tab-pane" id="friends">
								<?php
				            		// $sql = "SELECT DISTINCT friend_id FROM agency_friends, forum_users WHERE agency_friends.friend_id=forum_users.user_id AND agency_friends.user_id='$profileid' ";
									// $result=mysql_query($sql);
									// if(mysql_num_rows($result) == 0) {
									if(count($friends) == 0){
									 	echo '<br /><br /><p align="center">No friends at this time.</p>';
									} else {

										// echo "<pre>";
										// print_r($friends);
								?>
									<div class="row">
										<?php
										 	// $count = 8;
											// while($row = sql_fetchrow($result)) {
											 // 	 $friendid = $row['friend_id'];
											 
											 foreach($friends as $key=>$val){
										?>
									 		<div class="col-sm-2">
												<?php
													if(file_exists($folder_user .'/profile_pic/thumb/128x128_'. $val['user_avatar'])) {
														$frd_pic = $folder_user .'/profile_pic/thumb/128x128_'. $val['user_avatar'];
													} else {
														$frd_pic = 'images/friend.gif';
													}	
												?>
												<a class="text-center" href="<?php echo 'profile-view.php?user_id=' . $val['user_id']; ?>" style="width: 100%;display: inline-block;">
													<img class="profile-user-img" src="<?php echo $frd_pic; ?>" alt="User profile picture" style="height:128px;width:128px"/>
												</a>
												<p class="text-center"><?php echo $val['firstname'].' '.$val['lastname']; ?></p>
												<?php
													// get avatar
													// $sql2 = "SELECT registration_date FROM agency_profiles WHERE user_id='$friendid'";
													// $result2=mysql_query($sql2);
											 		// if($row2 = sql_fetchrow($result2)) {
													// 	$posterfolder = '../talentphotos/' . $friendid . '_' . $row2['registration_date'] . '/';
													// 	echo '<a href="profile-view.php?user_id=' . $friendid . '">
													// 		<img src="';
													// 			if(file_exists($posterfolder . 'avatar.jpg')) {
													// 				echo   $posterfolder . 'avatar.jpg';
													// 			} else if(file_exists($posterfolder . 'avatar.gif')) {
													// 				echo   $posterfolder . 'avatar.gif';
													// 			} else {
													// 				echo '../images/friend.gif';
													// 			}
													// 		echo '" /></a>';
													// }
												?>	
											</div>

											<?php
												}
												// if($count == 1) {
												// 	$count = 8;
												// 	echo '<br clear="all" />';
												// } else {
												// 	$count--;
												// }
											?>

										<?php //} ?>
									</div>

								<?php } ?>
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
						echo 'Experience Level: <img src="images/' . $experienceimages[$userinfo['experience']] . '.gif" /><br /><br />';
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

					echo 'Height: <b>' . floor($userinfo['height']/12) . '\' ' . $userinfo['height'] % 12 . '"</b><br /><br />';
						

					if($userinfo['gender'] == 'F') { // if female or "other"
					echo 'Waist: <b>' . escape_data($userinfo['waist']) . '"</b><br /><br />
						Bust: <b>' . escape_data($userinfo['bust']) . '"</b><br /><br />
						Hips: <b>' . escape_data($userinfo['hips']) . '"</b><br /><br />
						Dress: <b>' . escape_data($userinfo['dress']) . '"</b><br /><br />
						Shoe: <b>' . escape_data($userinfo['shoe']) . '</b><br /><br />';
					}

					if($userinfo['gender'] == 'M') { // if male or "other"
					echo 'Shirt: <b>' . escape_data($userinfo['shirt']) . '"</b><br /><br />
						Kids: <b>' . escape_data($userinfo['kids']) . '"</b><br /><br />
						Glove: <b>' . escape_data($userinfo['glove']) . '</b><br /><br />
						Cup: <b>' . escape_data($userinfo['cup']) . '</b><br /><br />
						Jacket: <b>' . escape_data($userinfo['jacket']) . '</b><br /><br />
						Pants: <b>' . escape_data($userinfo['pants']) . '</b><br /><br />
						Inseam: <b>' . escape_data($userinfo['inseam']) . '"</b><br /><br />
						Hat: <b>' . escape_data($userinfo['hat']) . '"</b><br /><br />';
					}

					echo 'Hair: <b>' . $userinfo['hair_color'] .', '. $userinfo['hair_length'] . '"</b><br /><br />
						Eyes: <b>' . $userinfo['eye_color'] .', '. $userinfo['eye_shape'] . '</b><br /><br />';

					if($_SESSION['user_id'] == $profileid) { // if this is the profile of the logged in user
						echo '<br><br><a class="AGENCY_graybutton" href="myaccount.php">edit</a>';
					}
				?>

            </div>
            
            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Close</button>
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
						// echo '<div align="center">Click <a class="AGENCY_graybutton" href="myaccount.php?tab=bio#resumeanchor">EDIT</a> to type in your resume for easy viewing';
						// echo '<br /><br />OR use the form below to upload a document</div>';
					}
				?>

				<?php
					// if((agency_account_type() == 'client' && is_active()) || $loggedin == $profileid) {
						if(!empty($userinfo['resume'])) {
							// echo $folder_user . '/resume/' . $userinfo['resume'];
							if(file_exists($folder_user . '/resume/' . $userinfo['resume'])) {
								// if($loggedin == $profileid) {
								// 	echo '<b>YOU CURRENTLY HAVE A RESUME UPLOADED</b><br /><br />';
								// }		
								echo '<a href="' . $folder_user . '/resume/' . $userinfo['resume'] . '" target="_blank" style="color:#333333"><b>CLICK HERE TO DOWNLOAD/PRINT UPLOADED RESUME FILE</b></a><br /><a href="' . $folder_user . '/resume/' . $userinfo['resume'] . '" target="_blank" style="text-decoration:none"><img src="images/resume1.gif" border="0" style="padding-top:5px;"></a>';
								
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
                <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Close</button>
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
							if(file_exists($folder_user . '/headshot/' . $userinfo['headshot'])) {
								echo '<a href="' . $folder_user . '/headshot/' . $userinfo['headshot'] . '" target="_blank"><b>Click here to view my headshot</b></a>';
							} else {
								echo 'Headshot not found';
							}
						} else {
							echo 'No headshot on file';
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
                <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Close</button>
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
                <h4 class="modal-title" id="myModalLabel">Bio</h4>
            </div>
            
            <!-- Modal Body -->
            <div class="modal-body">
				<?php 
					echo $userinfo['bio'];
						
					// if((agency_privacy($profileid, 'bio') || (agency_account_type() == 'client' && is_active())) && !empty($userinfo['bio'])) {

					// if(!empty($userinfo['bio'])) {
					// 	echo nl2br($userinfo['bio']) . '<br /><div align="center"><a href="../pdf_bio.php?u=' . $profileid . '" target="_blank" style="text-decoration:none"><img src="../images/biography1.gif" border="0" style="padding-top:5px;"></a></div>'; 
					// } else {
					// 	echo 'This user has has not entered a biography or has set their biography to private.';
					// }

					// if($_SESSION['user_id'] == $profileid) { // if this is the profile of the logged in user
					// 	echo '<br><br><a class="AGENCY_graybutton" href="myaccount.php?tab=bio">edit</a>';
					// }
				?>
            </div>
            
            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Close</button>
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
					// if(agency_privacy($profileid, 'links') || (agency_account_type() == 'client' && is_active())) {
						$sql = "SELECT * FROM agency_profile_links WHERE user_id = '".$user_id."' AND link !='' AND link IS NOT NULL";
						$result = mysql_query($sql);
						while ($row = sql_fetchrow($result)) {
							$link = $row['link'];
							$link_desc = $row['link_desc'];

							echo '<div class="AGENCYleftlink"><a target="_blank" href="'.$link.'">' . $row['social_media'] . '</a>';
							if(!empty($link_desc)) {
								echo ' - ' . $link_desc;
							}
							echo '</div><br />';
						}
						// if($_SESSION['user_id'] == $profileid) {
							// echo '<br><br><a class="AGENCY_graybutton" href="myaccount.php?tab=links">edit</a>';
						// }
					// } else {
					// 	echo 'This user has set their links to private.';
					// }
				?>
            </div>
            
            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Close</button>
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
                <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Close</button>
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

	function cardimageswap(image) {
		$("#primaryspot1").html('<img class="img-responsive img-thumbnail photo-main" id="primarypic" src="'+image+'" alt="Photo" />');
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

</body>

</html>