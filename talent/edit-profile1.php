<?php
	$page = "profile_edit";
	$page_selected = "profile_edit";

	include('header.php');
  	include('../includes/agency_dash_functions.php');

  	use \Gumlet\ImageResize;
	use \Gumlet\ImageResizeException;
	include('../ImageResize/ImageResize.php');

	// login user info
	$profileid = $user_id = $_SESSION['user_id'];

	// =====================================================
	function dlt_profile_pic($id,$folder,$thumb){
		$old_file = mysql_result(mysql_query("SELECT user_avatar FROM forum_users WHERE user_id =".$id.""), 0, 'user_avatar');
		$old_file_link = $folder. $old_file;
		if(unlink($old_file_link)){
			foreach($thumb as $height=>$width){
				unlink($folder.'thumb/'.$height.'x'.$width.'_'. $old_file);
			}
		}
		mysql_query("UPDATE forum_users SET user_avatar='' WHERE user_id=".$id."");
	}

	function dlt_headshot($id,$folder,$thumb){
		// echo "<br/>";
		$old_file = mysql_result(mysql_query("SELECT headshot FROM agency_profiles WHERE user_id =".$id.""), 0, 'headshot');
		$old_file_link = $folder. $old_file;
		if(unlink($old_file_link)){
			foreach($thumb as $height=>$width){
				unlink($folder.'thumb/'.$height.'x'.$width.'_'. $old_file);
			}
		}
		mysql_query("UPDATE agency_profiles SET headshot='' WHERE user_id = ".$id."");
	}

	function dlt_card($id,$folder,$thumb,$image_id){
		// echo "<br/>";
		$old_file = mysql_result(mysql_query("SELECT filename FROM agency_photos WHERE image_id =".$image_id.""), 0, 'filename');
		$old_file_link = $folder. $old_file;
		if(unlink($old_file_link)){
			foreach($thumb as $height=>$width){
				unlink($folder.'thumb/'.$height.'x'.$width.'_'. $old_file);
			}
		}
		mysql_query("DELETE FROM agency_photos WHERE image_id = ".$image_id."");
	}

	function dlt_portfolio($id,$folder,$thumb,$image_id){
		// echo "<br/>";
		$old_file = mysql_result(mysql_query("SELECT filename FROM agency_photos WHERE image_id =".$image_id.""), 0, 'filename');
		$old_file_link = $folder. $old_file;
		if(unlink($old_file_link)){
			foreach($thumb as $height=>$width){
				unlink($folder.'thumb/'.$height.'x'.$width.'_'. $old_file);
			}
		}
		mysql_query("DELETE FROM agency_photos WHERE image_id = ".$image_id."");
	}

	function dlt_video($reel_id){
		mysql_query("DELETE FROM agency_reel WHERE reel_id = ".$reel_id."");
	}

	function dlt_audio($vo_id,$folder){
		// echo "<br/>";
		$old_file = mysql_result(mysql_query("SELECT vo_file FROM agency_vo WHERE vo_id =".$vo_id.""), 0, 'vo_file');
		$old_file_link = $folder. $old_file;
		unlink($old_file_link);
		mysql_query("DELETE FROM agency_vo WHERE vo_id = ".$vo_id."");
	}

	function dlt_resume($id,$folder){
		// echo "<br/>";
		$old_file = mysql_result(mysql_query("SELECT resume FROM agency_profiles WHERE user_id =".$id.""), 0, 'resume');
		$old_file_link = $folder. $old_file;
		unlink($old_file_link);
		mysql_query("UPDATE agency_profiles SET resume='' WHERE user_id = ".$id."");
	}

	// delete profile pic
	$folder_profile_pic = '../uploads/users/' . $user_id . '/profile_pic/';
	$folder_profile_pic_thumb = $folder_profile_pic . 'thumb/';

	$filename_profile_pic_db = "";
	if(isset($_POST['profile_pic_del'])){
		dlt_profile_pic($user_id,$folder_profile_pic,$profile_pic_thumb);
	}else{
		if(isset($_POST['profile_pic_old'])){
			$filename_profile_pic_db = $_POST['profile_pic_old'];
		}
	}

	// delete headshot
	$folder_headshot = '../uploads/users/' . $user_id . '/headshot/';
	$folder_headshot_thumb = $folder_headshot . 'thumb/';

	$filename_headshot_db = "";
	if(isset($_POST['headshot_del'])){
		dlt_headshot($user_id,$folder_headshot,$headshot_thumb);
	}else{
		if(isset($_POST['headshot_old'])){
			$filename_headshot_db = $_POST['headshot_old'];
		}
	}

	// delete card thumb
	// $folder_card = '../uploads/users/' . $user_id . '/card/';
	$folder_card = '../uploads/users/' . $user_id . '/portfolio/';
	$folder_card_thumb = $folder_card . 'thumb/';

	$filename_card_db = "";
	if(isset($_POST['card_del'])){
		// echo "<pre>";
		// print_r($_POST);
		// echo "</pre>";
		foreach($_POST['card_del'] as $image_id=>$val){
			dlt_card($user_id,$folder_card,$card_thumb,$image_id);
		}
	}

	// delete video
	if(isset($_POST['video_del'])){
		foreach($_POST['video_del'] as $reel_id=>$val){
			dlt_video($reel_id);
		}
	}

	// delete audio
	$folder_audio = '../uploads/users/' . $user_id . '/audio/';
	$filename_audio_db = "";
	if(isset($_POST['audio_del'])){
		foreach($_POST['audio_del'] as $audio_id=>$val){
			dlt_audio($audio_id,$folder_audio);
		}
	}


	// delete portfolio
	$folder_portfolio = '../uploads/users/' . $user_id . '/portfolio/';
	$folder_portfolio_thumb = $folder_portfolio . 'thumb/';

	$filename_portfolio_db = "";
	if(isset($_POST['portfolio_del'])){
		foreach($_POST['portfolio_del'] as $image_id=>$val){
			dlt_portfolio($user_id,$folder_portfolio,$portfolio_thumb,$image_id);
		}
	}

	// delete resume
	$folder_resume = '../uploads/users/' . $user_id . '/resume/';
	$filename_resume_db = "";
	if(isset($_POST['resume_del'])){
		dlt_resume($user_id,$folder_resume);
	}else{
		if(isset($_POST['resume_old'])){
			$filename_resume_db = $_POST['resume_old'];
		}
	}

	// ===================
	$notification = array();
	if(isset($_POST['photo_tab']) && $_POST['photo_tab'] == 'photo_tab'){

		// profile pictures ==============
		$allowed_profie_pic = array ('image/jpeg', 'image/jpg', 'image/pjpeg', 'image/JPG');
		
		if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['name'] != "") {
			if (in_array($_FILES['profile_pic']['type'], $allowed_profie_pic)) {

				// if(!is_dir($folder_profile_pic)) {
				// 	mkdir($folder_profile_pic, 0777, true);
				// }
				if(!is_dir($folder_profile_pic_thumb)) {
					mkdir($folder_profile_pic_thumb, 0777, true);
				}
				dlt_profile_pic($user_id,$folder_profile_pic,$profile_pic_thumb);

				// Move the file over.
				$filename_profile_pic = filename_new($_FILES['profile_pic']['name']);
				$destination_profile_pic = $folder_profile_pic.$filename_profile_pic;
				if (move_uploaded_file($_FILES['profile_pic']['tmp_name'], "$destination_profile_pic")) {
					foreach($profile_pic_thumb as $height=>$width){
						$image = new ImageResize($destination_profile_pic);
		                $image->resizeToHeight($height);
		                $image->save($folder_profile_pic_thumb.$height.'x'.$width.'_'. $filename_profile_pic);
		            }
		            $filename_profile_pic_db = $filename_profile_pic;
				}else{
					$notification['error'][] = "Something Wrong With Profile Picture.";
				}

			} else { // Invalid type.
				$notification['error'][] = "Something Wrong With Profile Picture.";
			}
		}

		$sql_photo_update = "UPDATE forum_users 
			SET
			user_avatar = '".$filename_profile_pic_db."'
			WHERE  
			user_id = ".$user_id."
		";
		mysql_query($sql_photo_update);
		// if(mysql_query($sql_photo_update)){
		// 	$notification['success'][] = "Profile Updated Successfully.";
		// }

		// headshot ==============
		$allowed_headshot = array ('image/jpeg', 'image/jpg', 'image/pjpeg', 'image/JPG');
		
		if (isset($_FILES['headshot']) && $_FILES['headshot']['name'] != "") {
			if (in_array($_FILES['headshot']['type'], $allowed_headshot)) {

				if(!is_dir($folder_headshot_thumb)) {
					mkdir($folder_headshot_thumb, 0777, true);
				}
				dlt_headshot($user_id,$folder_headshot,$headshot_thumb);

				// Move the file over.
				$filename_headshot = filename_new($_FILES['headshot']['name']);
				$destination_headshot = $folder_headshot.$filename_headshot;
				if (move_uploaded_file($_FILES['headshot']['tmp_name'], "$destination_headshot")) {
					foreach($headshot_thumb as $height=>$width){
						$image = new ImageResize($destination_headshot);
		                $image->resizeToHeight($height);
		                $image->save($folder_headshot_thumb.$height.'x'.$width.'_'. $filename_headshot);
		            }
		            $filename_headshot_db = $filename_headshot;
				}else{
					$notification['error'][] = "Something Wrong With Headshot Picture.";
				}

			} else { // Invalid type.
				$notification['error'][] = "Something Wrong With Headshot Picture.";
			}
		}

		$sql_headshot_update = "UPDATE agency_profiles 
			SET
			headshot = '".$filename_headshot_db."'
			WHERE  
			user_id = ".$user_id."
		";
		mysql_query($sql_headshot_update);
		// if(mysql_query($sql_headshot_update)){
		// 	$notification['success'][] = "Headshot Updated Successfully.";
		// }


		// card =====================
		$allowed_card = array ('image/jpeg', 'image/jpg', 'image/pjpeg', 'image/JPG');

		if(isset($_FILES['card']) && $_FILES['card']['name'][0] != ""){
			if(!is_dir($folder_card_thumb)) {
				mkdir($folder_card_thumb, 0777, true);
			}

			foreach($_FILES['card']['name'] as $key=>$val){
				if (in_array($_FILES['card']['type'][$key], $allowed_card)) {

					// dlt_card($user_id,$folder_card,$card_thumb);

					// Move the file over.
					$filename_card = filename_new($_FILES['card']['name'][$key]);
					$destination_card = $folder_card.$filename_card;
					if (move_uploaded_file($_FILES['card']['tmp_name'][$key], "$destination_card")) {
						foreach($card_thumb as $height=>$width){
							$image = new ImageResize($destination_card);
			                $image->resizeToHeight($height);
			                $image->save($folder_card_thumb.$height.'x'.$width.'_'. $filename_card);
			            }
			            $filename_card_db = $filename_card;


			            $sql_thumb_ins = "INSERT into agency_photos 
							SET
							user_id = '".$user_id."',
							filename = '".$filename_card_db."',
							headshot_thumb = 'Y'
						";
						mysql_query($sql_thumb_ins);

					}else{
						$notification['error'][] = "Something Wrong With Card Picture.";
					}

				} else { // Invalid type.
					$notification['error'][] = "Something Wrong With Card Picture.";
				}
			}
		}

		if(!isset($notification['error'])){
			$notification['success'][] = 'Profile Details Updated';
		}

	}


	if(isset($_POST['video_tab']) && $_POST['video_tab'] == 'video_tab'){
		if(isset($_POST['submitreel']) && isset($_POST['videourl']) ) {

				if(!empty($_POST['videourl'])) {

					$url_dirty = $_POST['videourl'];
					// find host site
					if(strstr(strtolower($url_dirty),'youtu')) {
						$hostsite = 'youtube';
						// if(preg_match('#(?<=(?:v|i)=)[a-zA-Z0-9-_]+(?=&)|(?<=(?:v|i)\/)[^&\n]+|(?<=embed\/)[^"&\n]+|(?<=‌​(?:v|i)=)[^&\n]+|(?<=youtu.be\/)[^&\n]+#', $url_dirty, $matches)) {
						if(preg_match('#(?<=(?:v|i)=)[a-zA-Z0-9-\_]+(?=&)|(?<=(?:v|i)\/)[^&\n]+|(?<=embed\/)[^"&\n]+|(?<=(?:v|i)=)[^&\n]+|(?<=youtu.be\/)[^&\n]+#', $url_dirty, $matches)) {
							$video_id = $matches[0];
						} else {
							$notification['error'][] = "Unable to extract ID from info provided.  Please check your link.  If you are unable to submit your YouTube video please contact us.";
						}
					} else if(strstr(strtolower($url_dirty),'vimeo')) {
						$hostsite = 'vimeo';
						
						if(preg_match('/vimeo\.com\/([0-9]{1,10})/', $url_dirty, $matches)) {
							$video_id = $matches[1];
						} else if(preg_match('/player\.vimeo\.com\/video\/([0-9]*)"/', $url_dirty, $matches)) {
							$video_id = $matches[1];
						} else {
							$notification['error'][] = "Unable to extract ID from info provided. Please check your link.  If you are unable to submit your Vimeo video please contact us.";
						}
					}
					if(!empty($hostsite)) {
						if(!empty($video_id)) {
							$url_clean = escape_data($url_dirty);
							
							$query = "INSERT INTO agency_reel (user_id, reel_host, reel_link_id, user_input) VALUES ('$user_id', '$hostsite', '$video_id', '$url_clean')";
							if(mysql_query($query)){
								$notification['success'][] = "Video Link Saved Successfully";
							}					
						
						} else {
							$notification['error'][] = "Unable to extract ID from info provided.  Please check your link.  If you are unable to submit your video please contact us.";
						}
					} else {
						$notification['error'][] = "Your video must be either on YouTube or Vimeo.";
					}	

				} else {
					$notification['error'][] = "Please enter the URL for your video.";
				}

		}
	}


	if(isset($_POST['audio_tab']) && $_POST['audio_tab'] == 'audio_tab'){

		if(isset($_POST['submitvo']) && (isset($_FILES['mp3file']) && $_FILES['mp3file']['name'] != "") ) {
			if($_POST['MAX_FILE_SIZE'] != '10000000') {
				$notification['error'][] = "upload form has been tampered with!";
				// die();
			}
			if(!empty($_POST['mp3name'])) {

				if (isset($_FILES['mp3file']) && $_FILES['mp3file']['name'] != "") {
					if (pathinfo($_FILES['mp3file']['name'], PATHINFO_EXTENSION) == 'mp3') {

						if(!is_dir($folder_audio)) {
							mkdir($folder_audio, 0777, true);
						}

						// Move the file over.
						$filename_audio = filename_new($_FILES['mp3file']['name']);
						$destination_audio = $folder_audio.$filename_audio;
						if (move_uploaded_file($_FILES['mp3file']['tmp_name'], "$destination_audio")) {
				            $filename_audio_db = $filename_audio;
						}else{
							
							switch ($_FILES['mp3file']['error']) {
								case 1:
									$notification['error'][] = 'The file exceeds the upload_max_filesize setting in php.ini.';
									break;
								case 2:
									$notification['error'][] = 'The file must be less than 10MB.';
									break;
								case 3:
									$notification['error'][] = 'The file was only partially uploaded.';
									break;
								case 4:
									$notification['error'][] = 'No file was uploaded.';
									break;
								case 6:
									$notification['error'][] = 'No temporary folder was available.';
									break;
								default:
									$notification['error'][] = 'A system error occurred.';
									break;
							}

						}

					} else { // Invalid type.
						$notification['error'][] = 'It appears the file you are uploading is not an MP3 or the file may be too large.  If you feel you have received this message in error please contact us.';
					}
				}else{
					$notification['error'][] = 'Please select an MP3 file to upload from your computer.';
				}

				if($filename_audio_db != ""){
					$mp3name = escape_data($_POST['mp3name']);
					$query_audio_ins = "INSERT INTO agency_vo (user_id, vo_name, vo_file) VALUES ('$user_id','$mp3name','$filename_audio_db')";
					if(mysql_query($query_audio_ins)){
						$notification['success'][] = 'Audio Uploaded Successfully';
					}
				}
						
			} else {
				$notification['error'][] = 'Please enter a Title for your Voice over as you would like it displayed on your page.';
			}
		}

	}


	if(isset($_POST['portfolio_tab']) && $_POST['portfolio_tab'] == 'portfolio_tab'){

		// portfolio =====================
		$allowed_portfolio = array ('image/gif', 'image/jpeg', 'image/jpg', 'image/pjpeg', 'image/JPG');

		if(isset($_FILES['portfolio']) && $_FILES['portfolio']['name'][0] != ""){
			if(!is_dir($folder_portfolio_thumb)) {
				mkdir($folder_portfolio_thumb, 0777, true);
			}

			$upload_portfoilo = 0;
			foreach($_FILES['portfolio']['name'] as $key=>$val){
				if (in_array($_FILES['portfolio']['type'][$key], $allowed_portfolio)) {

					// dlt_portfolio($user_id,$folder_portfolio,$portfolio_thumb);

					// Move the file over.
					$filename_portfolio = filename_new($_FILES['portfolio']['name'][$key]);
					$destination_portfolio = $folder_portfolio.$filename_portfolio;
					if (move_uploaded_file($_FILES['portfolio']['tmp_name'][$key], "$destination_portfolio")) {

						foreach($portfolio_thumb as $height=>$width){
							$image = new ImageResize($destination_portfolio);
			                $image->resizeToHeight($height);
			                $image->save($folder_portfolio_thumb.$height.'x'.$width.'_'. $filename_portfolio);
			            }
			            $filename_portfolio_db = $filename_portfolio;

			            $sql_portfolio_ins = "INSERT into agency_photos 
							SET
							user_id = '".$user_id."',
							filename = '".$filename_portfolio_db."'
						";
						if(mysql_query($sql_portfolio_ins)){
							$upload_portfoilo++;
						}

					}else{
						$notification['error'][] = "Something Wrong With Portfolio Picture.";
					}

				} else { // Invalid type.
					$notification['error'][] = "Something Wrong With Portfolio Picture.";
				}
			}

			if($upload_portfoilo > 0){
				$notification['success'][] = $upload_portfoilo ." Images Saved Successfully.";
			} 

		}

	}


	if(isset($_POST['resume_tab']) && $_POST['resume_tab'] == 'resume_tab'){

		if(isset($_POST['submit_resume']) && (isset($_FILES['resumefile']) && $_FILES['resumefile']['name'] != "") ) {

			$allowedExt_resume = array("txt","doc","docx","rtf","pdf","jpg","jpeg","gif","png","bmp","tiff","tif");
			$resume_name_ary = explode(".", strtolower($_FILES['resumefile']['name']));
			if (in_array(end($resume_name_ary), $allowedExt_resume)) {

				if(!is_dir($folder_resume)) {
					mkdir($folder_resume, 0777, true);
				}
				dlt_resume($user_id,$folder_resume);

				$filename_resume = filename_new($_FILES['resumefile']['name']);
				$destination_resume = $folder_resume.$filename_resume;
				if (move_uploaded_file($_FILES['resumefile']['tmp_name'], "$destination_resume")) {
					$filename_resume_db = $filename_resume;
				}else{
					$err_common_resume = 'The file could not be uploaded because: ';

					switch ($_FILES['resumefile']['error']) {
						case 1:
							$notification['error'][] = $err_common_resume.'The file exceeds the upload_max_filesize setting in php.ini.';
							break;
						case 2:
							$notification['error'][] = $err_common_resume.'The file must be less than 5MB';
							break;
						case 3:
							$notification['error'][] = $err_common_resume.'The file was only partially uploaded.';
							break;
						case 4:
							$notification['error'][] = $err_common_resume.'No file was uploaded.';
							break;
						case 6:
							$notification['error'][] = $err_common_resume.'No temporary folder was available.';
							break;
						default:
							$notification['error'][] = $err_common_resume.'A system error occurred.';
							break;
					}

				}

				if($filename_resume_db != ""){
					$query_resume_ins = "UPDATE agency_profiles ap
										SET 
										resume = '".$filename_resume_db."'
										WHERE 
										ap.user_id ='$user_id'
									";
					if(mysql_query($query_resume_ins)){
						$notification['success'][] = 'Resume Uploaded Successfully';
					}
				}

			}else{
				$notification['error'][] = 'This file type is not allowed.';
			}
		}
	}

	if(isset($_POST['physical_tab']) && $_POST['physical_tab'] == 'physical_tab'){

		$birthdate = date('Y-m-d',strtotime($_POST['birthdate']));
		$height_ft = $_POST['height_ft'];
		$height_inch = $_POST['height_inch'];
		$height = ($height_ft*12) + $height_inch;

		if($_POST['ethnicity'] != ""){
			$ethnicity = $_POST['ethnicity'];
		}else{
			$ethnicity = $_POST['ethnicity_other'];
		}

		$query_physical_update = "UPDATE agency_profiles ap 
									SET 
									gender = '".$_POST['gender']."',
									birthdate = '".$birthdate."',
									weight = '".$_POST['weight']."',
									height_ft = '".$height_ft."',
									height_inch = '".$height_inch."',
									height = '".$height."',
									ethnicity = '".$ethnicity."',
									nationality = '".$_POST['nationality']."',
									hair_color = '".$_POST['hair_color']."',
									hair_length = '".$_POST['hair_length']."',
									eye_color = '".$_POST['eye_color']."',
									eye_shape = '".$_POST['eye_shape']."'
									WHERE 
									ap.user_id='$user_id'
								";
		if(mysql_query($query_physical_update)){
			$notification['success'][] = 'Details Updated Successfully';
		}
	}

	if(isset($_POST['social_link_tab']) && $_POST['social_link_tab'] == 'social_link_tab'){
		// echo "<pre>";
		// print_r($_POST['social_link']);
		// echo "</pre>";

		foreach($_POST['social_link'] as $key=>$link){
			$sql_social_check = "SELECT * from agency_profile_links 
										WHERE user_id='$user_id' AND social_media = '".$key."'
									";
						
			$query_social_check = mysql_query($sql_social_check);
			if(mysql_num_rows($query_social_check) > 0){
				$query_social_update = "UPDATE agency_profile_links
										SET 
										link = '".$link."'
										WHERE
										user_id='$user_id' AND social_media = '".$key."'

									";
				mysql_query($query_social_update);
			}else{
				$query_social_insert = "INSERT INTO agency_profile_links
										SET 
										user_id = ".$user_id.",
										social_media = '".$key."',
										link = '".$link."'
									";
				mysql_query($query_social_insert);
			}
		}

		$notification['success'][] = 'Social Links Updated Successfully';
	}

	if(isset($_POST['bio_tab']) && $_POST['bio_tab'] == 'bio_tab'){
		$query_resume_ins = "UPDATE agency_profiles ap
							SET 
							bio = '".$_POST['bio']."'
							WHERE 
							ap.user_id ='$user_id'
						";
		if(mysql_query($query_resume_ins)){
			$notification['success'][] = 'Bio Details Changed Successfully';
		}
	}
	


	$sql = "SELECT ap.*,fu.* FROM agency_profiles ap 
			INNER JOIN forum_users fu ON fu.user_id = ap.user_id
			WHERE ap.user_id='$profileid'";
	$result=mysql_query($sql);
	$userInfo = sql_fetchrow($result);
?>

<div id="page-wrapper">
    <div class="" id="main">

    		<div class="row">
    			<div class="col-md-6">
	    			<h3>User Profile</h3>
	    		</div>
	    		<div class="col-md-6 text-right">
	    			<button class="btn btn-theme" data-target="#print_modal" data-toggle="modal"><i class="fa fa-print"></i></button>
	    		</div>
	    	</div>
    		<?php if(isset($notification['success'])){ ?>
		        <div class="alert alert-success" role="alert">
		        	<ul>
		                <?php foreach($notification['success'] as $val){ ?>
		                	<li><?php echo $val; ?></li>
		                <?php } ?>
		            </ul>
		        </div>
	        <?php } ?>
	        <?php if(isset($notification['error'])){ ?>
	            <div class="alert alert-danger" role="alert">
	            	<ul>
		                <?php foreach($notification['error'] as $val){ ?>
		                	<li><?php echo $val; ?></li>
		                <?php } ?>
		            </ul>
	            </div>
	        <?php } ?>

			<div class="row">

				<div class="col-md-4">
					<form method="post" action="" name="photo_frm" id="photo_frm" enctype="multipart/form-data">
				        <div class="box box-default collapsed-box">
				            <div class="box-header with-border">
				                <h3 class="no-margin">Photos</h3>
				                <p>Profile Photo, Headshot, Wall Thumbnails </p>

				                <div class="box-tools pull-right">
				                	<button type="button" class="btn btn-sm btn-warning" data-widget="collapse"><i class="fa fa-plus"></i></button>
				              	</div>
				            </div>
				            <div class="box-body">
				              		<div class="form-group">
				              			<label>Profile Picture</label>
				              			<label class="file-box">
				              				<span class="name-box">Drag and Drop Files</span>
				              				<input type="hidden" name="profile_pic_old" id="profile_pic_old" value="<?php echo $userInfo['user_avatar']; ?>"/>
											<input type="file" name="profile_pic" class="form-control" />
										</label>

										<?php if($userInfo['user_avatar'] != ""){ ?>
					                    	<a href="<?php echo '../uploads/users/' . $userInfo['user_id'] . '/profile_pic/' . $userInfo['user_avatar']; ?>" target="_blank" data-fancybox="profile_pic">View Profile Image</a>&nbsp;&nbsp;&nbsp;&nbsp;
											<label><input type="checkbox" name="profile_pic_del"> check to delete</label>
					                    <?php } ?>
									</div>

									<div class="form-group">
				              			<label>Headshot</label>
				              			<br/>
				              			<label class="text-alert"><i class="fa fa-bell"></i> Follow Following Details To Upload Hadshot.</label>
				              			<div class="row">
											<div class="col-md-3">
												<a href="../dashboard/assets/images/headshot_tip1.png" data-fancybox="headshot" title="Click to zoom">
													<img src="../dashboard/assets/images/headshot_tip1.png" class="img-responsive"/>
												</a>
											</div>
											<div class="col-md-3">
												<a href="../dashboard/assets/images/headshot_tip2.png" data-fancybox="headshot" title="Click to zoom">
													<img src="../dashboard/assets/images/headshot_tip2.png" class="img-responsive"/>
												</a>
											</div>
											<div class="col-md-3">
												<a href="../dashboard/assets/images/headshot_tip3.png" data-fancybox="headshot" title="Click to zoom">
													<img src="../dashboard/assets/images/headshot_tip3.png" class="img-responsive"/>
												</a>
											</div>
											<div class="col-md-3">
												<a href="../dashboard/assets/images/headshot_tip4.png" data-fancybox="headshot" title="Click to zoom">
													<img src="../dashboard/assets/images/headshot_tip4.png" class="img-responsive"/>
												</a>
											</div>
										</div>
										<br/>

				              			<input type="hidden" name="headshot_old" id="headshot_old" value="<?php echo $userInfo['headshot']; ?>"/>
				              			<label class="file-box">
				              				<span class="name-box">Drag and Drop Files</span>
											<input type="file" name="headshot" class="form-control"/>
										</label>
										<?php if($userInfo['headshot'] != ""){ ?>
					                    	<a href="<?php echo '../uploads/users/' . $userInfo['user_id'] . '/headshot/' . $userInfo['headshot']; ?>" target="_blank" data-fancybox="headshot_upload">View Headshot</a>&nbsp;&nbsp;&nbsp;&nbsp;
											<label><input type="checkbox" name="headshot_del"> check to delete</label>
					                    <?php } ?>
									</div>

									<div class="form-group">
				              			<label>Thumbnails</label>
				              			<label class="file-box">
				              				<span class="name-box">Drag and Drop Files</span>
											<input type="file" name="card[]" class="form-control" multiple="" />
										</label>
										<?php 
											$sql_card = "SELECT * FROM agency_photos WHERE user_id=".$user_id." AND headshot_thumb ='Y'";
											$result_card = mysql_query($sql_card);
										?>
										<label class="text-alert">
											<i class="fa fa-bell"></i> You Can Select Multiple Images. <?php echo mysql_num_rows($result_card); ?> uploded. only 3 images used for thumbnails.
										</label>
										<br/>
										<?php if(mysql_num_rows($result_card) > 0){ ?>
											<?php while ($row = sql_fetchrow($result_card)) { ?>
												<a href="<?php echo $folder_card . $row['filename']; ?>" target="_blank" data-fancybox="card_thumb">view attachment</a>&nbsp;&nbsp;&nbsp;&nbsp;
												<label><input type="checkbox" name="card_del[<?php echo $row['image_id']; ?>]"> check to delete</label>
												<br/>
											<?php } ?>
										<?php } ?>
									</div>
				              	
				            </div>
				            <div class="box-footer">
				            	<input type="hidden" name="photo_tab" value="photo_tab"/>
				            	<input type="submit" name="submit" value="Save" class="btn btn-success pull-right"/>
				            </div>
				        </div>
				    </form>


				    <form action="" name="video_frm" id="video_frm" method="post">
				        <div class="box box-default collapsed-box">
				            <div class="box-header with-border">
				                <h3 class="no-margin">Videos</h3>
				                <p>Videos (YouTube, Vimeo)</p>

				                <div class="box-tools pull-right">
				                	<button type="button" class="btn btn-sm btn-warning" data-widget="collapse"><i class="fa fa-plus"></i></button>
				              	</div>
				            </div>

				            <?php 
					            $query_reel = "SELECT * FROM agency_reel WHERE user_id='$profileid'";
								$result_reel = mysql_query ($query_reel);
								$num_reels = mysql_num_rows($result_reel);
							?>

							<?php if($num_reels > 0) { ?>
				            	<div class="box-body">
				            		<label>Uploaded Videos</label>
				            		<br/>
				            		<?php while ($row = mysql_fetch_array ($result_reel, mysql_ASSOC)) { ?>

				            			<?php if($row['reel_host'] == 'youtube') { ?>
				            				<a href="<?php echo 'http://www.youtube-nocookie.com/embed/' . $row['reel_link_id']; ?>" target="_blank" data-fancybox="video_fancy">view </a>
			            				<?php } else if($row['reel_host'] == 'vimeo') { ?>
			            					<a href="<?php echo 'http://player.vimeo.com/video/' . $row['reel_link_id']; ?>" target="_blank" data-fancybox="video_fancy">view </a>
			            				<?php } ?>

				            			&nbsp;&nbsp;&nbsp;&nbsp;
					            		<label><input type="checkbox" name="video_del[<?php echo $row['reel_id']; ?>]"> check to delete</label>
					            		<br/>
					            	<?php } ?>
				            	</div>
				            <?php } ?>

				            <?php if($num_reels < 3) { ?>
					            <div class="box-body">
					            	<div class="form-group">
				              			<label>Embed New Video</label>
				              			<label class="text-alert">
											<i class="fa fa-bell"></i> Please upload your video to either <a href="http://www.youtube.com" target="_blank">YouTube</a> or <a href="http://www.vimeo.com" target="_blank">Vimeo</a>.
											<br/>
											Once you have your video uploaded, please copy the URL (Link) to your video and paste (or type) it in the box below.
										</label>
										<input type="text" name="videourl" class="form-control"/>
									</div>
					            </div>

				            <?php }else{ ?>
				            	<div class="box-body">
					                <label class="text-danger">You may have a maximum of 3 Videos on your page.  If you would like to add a new one, please delete one of your existing videos.</label>
					            </div>
				            <?php } ?>

				            <div class="box-footer">
				            	<input type="hidden" name="video_tab" value="video_tab"/>
				            	<input type="submit" name="submitreel" value="Save" class="btn btn-success pull-right"/>
				            </div>

				        </div>
				    </form>


				    <form enctype="multipart/form-data" action="" name="audio_frm" id="audio_frm" method="post">
				        <div class="box box-default collapsed-box">
				            <div class="box-header with-border">
				                <h3 class="no-margin">Audio</h3>
				                <p>Audio / Voice</p>
				                <div class="box-tools pull-right">
				                	<button type="button" class="btn btn-sm btn-warning" data-widget="collapse"><i class="fa fa-plus"></i></button>
				              	</div>
				            </div>

				            <?php 
					            $query_vo = "SELECT * FROM agency_vo WHERE user_id='$profileid'";
								$result_vo = mysql_query ($query_vo);
								$num_vos = mysql_num_rows($result_vo);
							?>

							<?php if($num_vos > 0) { ?>
				            	<div class="box-body">
				            		<label>Uploded Audio</label>
				            		<br/>
				            		<?php while ($row = mysql_fetch_array ($result_vo, mysql_ASSOC)) { ?>
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
					            						<input type="checkbox" name="audio_del[<?php echo $row['vo_id']; ?>]"> check to delete
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
										<label> Title</label>
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

							<div class="box-footer">
				            	<input type="hidden" name="audio_tab" value="audio_tab"/>
								<input type="submit" name="submitvo" value="Upload MP3 File" class="btn btn-success pull-right"/>
				            </div>
							
				        </div>
			        </form>

			        <form method="post" action="" name="portfolio_frm" id="portfolio_frm" enctype="multipart/form-data">
				        <div class="box box-default collapsed-box">
				            <div class="box-header with-border">
				                <h3 class="no-margin">Photography (Portfolio)</h3>
				                <p>Portfolio</p>

				                <div class="box-tools pull-right">
				                	<button type="button" class="btn btn-sm btn-warning" data-widget="collapse"><i class="fa fa-plus"></i></button>
				              	</div>
				            </div>
				            <div class="box-body">
				             	<div class="form-group">
			              			<label class="file-box">
			              				<span class="name-box">Drag and Drop Files</span>
										<input type="file" name="portfolio[]" class="form-control" multiple="" />
									</label>
									<?php 
										$sql_portfolio = "SELECT * FROM agency_photos WHERE user_id=".$user_id." AND headshot_thumb ='N'";
										$result_portfolio = mysql_query($sql_portfolio);
									?>
									<label class="text-alert"><i class="fa fa-bell"></i> You Can Select Multiple Images.</label>
									<br/>
									<?php if(mysql_num_rows($result_portfolio) > 0){ ?>
										<?php while ($row = sql_fetchrow($result_portfolio)) { ?>
											<a href="<?php echo $folder_portfolio . $row['filename']; ?>" target="_blank" data-fancybox="portfolio_thumb">view attachment</a>&nbsp;&nbsp;&nbsp;&nbsp;
											<label><input type="checkbox" name="portfolio_del[<?php echo $row['image_id']; ?>]"> check to delete</label>
											<br/>
										<?php } ?>
									<?php } ?>
								</div>
				            </div>
				            <div class="box-footer">
				            	<input type="hidden" name="portfolio_tab" value="portfolio_tab"/>
				            	<input type="submit" name="submit" value="Save" class="btn btn-success pull-right"/>
				            </div>
				        </div>
				    </form>

		        </div>

		        <div class="col-md-4">
		        	<form method="post" action="" name="resume_frm" id="resume_frm" enctype="multipart/form-data">
				        <div class="box box-default collapsed-box">
				            <div class="box-header with-border">
				                <h3 class="no-margin">Resume</h3>
				                <p>Upload Doc, Pdf, Image</p>

				                <div class="box-tools pull-right">
				                	<button type="button" class="btn btn-sm btn-warning" data-widget="collapse"><i class="fa fa-plus"></i></button>
				              	</div>
				            </div>
				            <div class="box-body">
				             	<div class="form-group">
			              			<label class="file-box">
			              				<span class="name-box">Drag and Drop Files</span>
										<input type="file" name="resumefile" class="form-control" />
									</label>
									<?php if($userInfo['resume'] != ""){ ?>
				                    	<a href="<?php echo $folder_resume . $userInfo['resume']; ?>" target="_blank">View Resume</a>&nbsp;&nbsp;&nbsp;&nbsp;
										<label><input type="checkbox" name="resume_del"> check to delete</label>
				                    <?php } ?>

								</div>
				            </div>
				            <div class="box-footer">
				            	<input type="hidden" name="resume_tab" value="resume_tab" />
				            	<input type="submit" name="submit_resume" value="Save" class="btn btn-success pull-right" />
				            </div>
				        </div>
				    </form>

				    <form method="post" action="" name="bio_frm" id="bio_frm" enctype="multipart/form-data">
				        <div class="box box-default collapsed-box">
				            <div class="box-header with-border">
				                <h3 class="no-margin">BIO</h3>
				                <p>Biograpgy - Display With Profile</p>

				                <div class="box-tools pull-right">
				                	<button type="button" class="btn btn-sm btn-warning" data-widget="collapse"><i class="fa fa-plus"></i></button>
				              	</div>
				            </div>
				            <div class="box-body">
				             	<div class="form-group">
				             		<textarea name="bio" id="bio" class="form-control"><?php echo $userInfo['bio']; ?></textarea>
								</div>
				            </div>
				            <div class="box-footer">
				            	<input type="hidden" name="bio_tab" value="bio_tab" />
				            	<input type="submit" name="submit_bio" value="Save" class="btn btn-success pull-right" />
				            </div>
				        </div>
				    </form>

				    <form method="post" action="" name="social_link_frm" id="social_link_frm" enctype="multipart/form-data" class="form-horizontal">
				        <div class="box box-default collapsed-box">
				            <div class="box-header with-border">
				                <h3 class="no-margin">Social Links</h3>
				                <p>Instagram, Facebook, Twitter, Youtube, Linkedin, Custom Link</p>

				                <div class="box-tools pull-right">
				                	<button type="button" class="btn btn-sm btn-warning" data-widget="collapse"><i class="fa fa-plus"></i></button>
				              	</div>
				            </div>
				            <?php 
				            	$social = array();
								$sql_social_link = "SELECT * FROM agency_profile_links WHERE user_id=".$user_id." 
													GROUP BY social_media";
								$result_social = mysql_query($sql_social_link);
								while ($row = sql_fetchrow($result_social)) {
									$social[$row['social_media']] = $row['link'];
								}
								// print_r($social);
							?>
				            <div class="box-body">
				             	<div class="form-group">
			              			<div class="col-md-2 text-right">
			              				<span class="btn btn-default btn-block"><i class="fa fa-instagram"></i></span>
			              			</div>
			              			<div class="col-md-10">
				              			<input type="text" class="form-control" name="social_link[instagram]" id="" value="<?php if(isset($social['instagram'])){ echo $social['instagram']; }; ?>" />
				              		</div>
								</div>

								<div class="form-group">
			              			<div class="col-md-2 text-right">
			              				<span class="btn btn-default btn-block"><i class="fa fa-facebook"></i></span>
			              			</div>
			              			<div class="col-md-10">
				              			<input type="text" class="form-control" name="social_link[facebook]" id="" value="<?php if(isset($social['facebook'])){ echo $social['facebook']; }; ?>" />
				              		</div>
								</div>

								<div class="form-group">
			              			<div class="col-md-2 text-right">
			              				<span class="btn btn-default btn-block"><i class="fa fa-youtube"></i></span>
			              			</div>
			              			<div class="col-md-10">
				              			<input type="text" class="form-control" name="social_link[youtube]" id="" value="<?php if(isset($social['youtube'])){ echo $social['youtube']; }; ?>" />
				              		</div>
								</div>

								<div class="form-group">
			              			<div class="col-md-2 text-right">
			              				<span class="btn btn-default btn-block"><i class="fa fa-twitter"></i></span>
			              			</div>
			              			<div class="col-md-10">
				              			<input type="text" class="form-control" name="social_link[twitter]" id="" value="<?php if(isset($social['twitter'])){ echo $social['twitter']; }; ?>" />
				              		</div>
								</div>

								<div class="form-group">
			              			<div class="col-md-2 text-right">
			              				<span class="btn btn-default btn-block"><i class="fa fa-linkedin"></i></span>
			              			</div>
			              			<div class="col-md-10">
				              			<input type="text" class="form-control" name="social_link[linkedin]" id="" value="<?php if(isset($social['linkedin'])){ echo $social['linkedin']; }; ?>" />
				              		</div>
								</div>

								<div class="form-group">
			              			<div class="col-md-2 text-right">
			              				<span class="btn btn-default btn-block"><i class="fa fa-globe"></i></span>
			              			</div>
			              			<div class="col-md-10">
				              			<input type="text" class="form-control" name="social_link[website]" id="" value="<?php if(isset($social['website'])){ echo $social['website']; }; ?>" />
				              		</div>
								</div>
				            </div>

				            <div class="box-footer">
				            	<input type="hidden" name="social_link_tab" value="social_link_tab"/>
				            	<input type="submit" name="submit" value="Save" class="btn btn-success pull-right"/>
				            </div>

				        </div>
				    </form>

		        	<form method="post" action="" name="physical_frm" id="physical_frm" enctype="multipart/form-data">
				        <div class="box box-default collapsed-box">
				            <div class="box-header with-border">
				                <h3 class="no-margin">Physical Details</h3>
				                <p>Biographical Data</p>

				                <div class="box-tools pull-right">
				                	<button type="button" class="btn btn-sm btn-warning" data-widget="collapse"><i class="fa fa-plus"></i></button>
				              	</div>
				            </div>
				            <div class="box-body">

				            	<div class="form-group">
			              			<label>Gender <span class="text-danger">*</span></label>
			              			<br/>
			              			<label><input type="radio" name="gender" value="M" <?php if($userInfo['gender'] == "M"){ echo "checked"; } ?>/> Male</label>
			              			<label><input type="radio" name="gender" value="F" <?php if($userInfo['gender'] == "F"){ echo "checked"; } ?>/> Female</label>
			              			<label><input type="radio" name="gender" value="Transgender" <?php if($userInfo['gender'] == "Transgender"){ echo "checked"; } ?>/> Transgender</label>
			              			<span class="radio_err"></span>
								</div>

				            	<div class="form-group">
			              			<label>Birth Date</label>
			              			<input type="text" class="form-control" name="birthdate" id="birthdate" value="<?php echo date('m/d/Y',strtotime($userInfo['birthdate'])); ?>" />
								</div>

								<div class="form-group">
			              			<label>Weight (lbs)</label>
			              			<select name="weight" id="weight" class="form-control">
			              				<option value=""></option>
			              				<?php for($i=0;$i<=300;$i++){ ?>
			              					<option value="<?php echo $i; ?>" <?php if($userInfo['weight'] == $i){ echo "selected"; } ?>><?php echo $i; ?></option>
			              				<?php } ?>
			              			</select>
								</div>

								<label>height</label>
								<div class="row">
									<div class="col-md-6">
						             	<div class="form-group">
					              			<label>Feet</label>
					              			<select name="height_ft" id="height_ft" class="form-control">
					              				<?php for($i=1;$i<=10;$i++){ ?>
					              					<option value="<?php echo $i; ?>" <?php if($userInfo['height_ft'] == $i){ echo "selected"; } ?>><?php echo $i; ?></option>
					              				<?php } ?>
					              			</select>
										</div>
									</div>
									<div class="col-md-6">
						             	<div class="form-group">
					              			<label>Inch</label>
					              			<select name="height_inch" id="height_inch" class="form-control">
					              				<?php for($i=0;$i<=11;$i++){ ?>
					              					<option value="<?php echo $i; ?>" <?php if($userInfo['height_inch'] == $i){ echo "selected"; } ?>><?php echo $i; ?></option>
					              				<?php } ?>
					              			</select>
										</div>
									</div>
								</div>

								<label>Portrayable Ethnicity <span class="text-danger">*</span></label>
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
					              			<select name="ethnicity" id="ethnicity" class="form-control">
					              				<option value="">Select</option>
					              				<?php $et_other = "Y"; ?>
					              				<?php foreach($ethnicityarray as $val){ ?>
					              					<?php 
					              						$et_select = "";
					              						if($userInfo['ethnicity'] == $val){
					              							$et_select = "selected"; 
					              							$et_other = "N";
					              						} 
					              					?>
					              					<option value="<?php echo $val; ?>" <?php echo $et_select; ?> ><?php echo $val; ?></option>
					              				<?php } ?>
					              			</select>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<input type="text" name="ethnicity_other" id="ethnicity_other" class="form-control" value="<?php if($et_other == "Y"){ echo $userInfo['ethnicity']; } ?>" placeholder="other"/>
										</div>
									</div>
								</div>

								<div class="form-group">
			              			<label>Nationality</label>
			              			<select name="nationality" id="nationality" class="form-control">
			              				<option value="">Select</option>
			              				<?php foreach($countryarray as $key=>$val){ ?>
			              					<option value="<?php echo $val; ?>" <?php if($userInfo['nationality'] == $val){ echo "selected"; } ?> ><?php echo $val; ?></option>
			              				<?php } ?>
			              			</select>
								</div>

								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label>Hair Color</label>
					              			<select name="hair_color" id="hair_color" class="form-control">
					              				<option value="">Select</option>
					              				<?php foreach($haircolorarray as $val){ ?>
					              					<option value="<?php echo $val; ?>" <?php if($userInfo['hair_color'] == $val){ echo "selected"; } ?> ><?php echo $val; ?></option>
					              				<?php } ?>
					              			</select>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
					              			<label>Hair Length</label>
					              			<input type="text" name="hair_length" id="hair_length" value="<?php echo $userInfo['hair_length']; ?>" class="form-control"/>
										</div>
									</div>
								</div>

								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
					              			<label>Eye Color</label>
					              			<select name="eye_color" id="eye_color" class="form-control">
					              				<option value="">Select</option>
					              				<?php foreach($eyecolorarray as $val){ ?>
					              					<option value="<?php echo $val; ?>" <?php if($userInfo['eye_color'] == $val){ echo "selected"; } ?> ><?php echo $val; ?></option>
					              				<?php } ?>
					              			</select>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
					              			<label>Eye Shape</label>
					              			<select name="eye_shape" id="eye_shape" class="form-control">
					              				<option value="">Select</option>
					              				<?php foreach($eyeShapeArray as $val){ ?>
					              					<option value="<?php echo $val; ?>" <?php if($userInfo['eye_shape'] == $val){ $et_select = "selected"; } ?> ><?php echo $val; ?></option>
					              				<?php } ?>
					              			</select>
										</div>
									</div>
								</div>


								<!-- <div class="form-group">
			              			<label>Waist</label>
			              			<input type="text" class="form-control" name="waist" id="waist" value="<?php echo $userInfo['waist']; ?>" />
								</div>

								<div class="form-group">
			              			<label>Suit</label>
			              			<input type="text" class="form-control" name="suit" id="suit" value="<?php echo $userInfo['suit']; ?>" />
								</div>
								
								<div class="form-group">
			              			<label>Shirt</label>
			              			<input type="text" class="form-control" name="shirt" id="shirt" value="<?php echo $userInfo['shirt']; ?>" />
								</div>

								<div class="form-group">
			              			<label>Neck</label>
			              			<input type="text" class="form-control" name="neck" id="neck" value="<?php echo $userInfo['neck']; ?>" />
								</div>

								<div class="form-group">
			              			<label>Sleeve</label>
			              			<input type="text" class="form-control" name="sleeve" id="sleeve" value="<?php echo $userInfo['sleeve']; ?>" />
								</div>

								<div class="form-group">
			              			<label>Inseam</label>
			              			<input type="text" class="form-control" name="inseam" id="inseam" value="<?php echo $userInfo['inseam']; ?>" />
								</div>

								<div class="form-group">
			              			<label>Shoe</label>
			              			<input type="text" class="form-control" name="shoe" id="shoe" value="<?php echo $userInfo['shoe']; ?>" />
								</div>

								<div class="form-group">
			              			<label>Weight</label>
			              			<input type="text" class="form-control" name="weight" id="weight" value="<?php echo $userInfo['weight']; ?>" />
								</div>

								<div class="form-group">
			              			<label>Hair</label>
			              			<input type="text" class="form-control" name="hair" id="hair" value="<?php echo $userInfo['hair']; ?>" />
								</div>

								<div class="form-group">
			              			<label>Eyes</label>
			              			<input type="" class="form-control" name="eyes" id="eyes" value="<?php echo $userInfo['eyes']; ?>" />
								</div>

								<div class="form-group">
			              			<label>Bust</label>
			              			<input type="" class="form-control" name="bust" id="bust" value="<?php echo $userInfo['bust']; ?>" />
								</div>

								<div class="form-group">
			              			<label>Cup</label>
			              			<input type="" class="form-control" name="cup" id="cup" value="<?php echo $userInfo['cup']; ?>" />
								</div>

								<div class="form-group">
			              			<label>Hips</label>
			              			<input type="" class="form-control" name="hips" id="hips" value="<?php echo $userInfo['hips']; ?>" />
								</div>

								<div class="form-group">
			              			<label>Dress</label>
			              			<input type="" class="form-control" name="dress" id="dress" value="<?php echo $userInfo['dress']; ?>" />
								</div>

								<div class="form-group">
			              			<label>Tattoos</label>
			              			<input type="" class="form-control" name="tattoos" id="tattoos" value="<?php echo $userInfo['tattoos']; ?>" />
								</div>

								<div class="form-group">
			              			<label>Piercings</label>
			              			<input type="" class="form-control" name="piercings" id="piercings" value="<?php echo $userInfo['piercings']; ?>" />
								</div> -->
				            </div>

				            <div class="box-footer">
				            	<input type="hidden" name="physical_tab" value="physical_tab"/>
				            	<input type="submit" name="submit" value="Save" class="btn btn-success pull-right"/>
				            </div>

				        </div>
				    </form>

				    
		        </div>
		        
		    </div>
		
	</div>
</div>


<div class="modal fade" id="print_modal" role="dialog">
    <div class="modal-dialog modal-lg">
	<style>
		.padding-100{
			padding:100px 0px;
		}
		.padding-50{
			padding:45px 0px;
		}
		.padding-comp2{
			padding:37px 0px;
		}
	</style>
      <form role="form" id="print_Form" method="post" action="">
          <div class="modal-content">
              <!-- Modal Header -->
              <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">
                      <span aria-hidden="true">&times;</span>
                      <span class="sr-only">Close</span>
                  </button>
                  <h4 class="modal-title" id="myModalLabel">Print Option</h4>
              </div>

              <div class="Required Documnets">
              </div>
              
              <!-- Modal Body -->
              <div class="modal-body">
              	<h3 class="text-center">SELECT A PRINT OPTION</h3>
              	<div class="row">
              		<div class="col-xs-4">
              			<div class="container-fluid border-box-custom padding-15">
              				<div class="col-xs-6" style="padding-left:0;padding-right:8px;">
              					<div class="border-box-custom padding-100" style=""></div>
              				</div>
              				<div class="col-xs-6" style="padding-left:8px;padding-right:0">
              					<div class="border-box-custom padding-100" style=""></div>
              				</div>
              			</div>
              			<div class="text-center">
	              			<h4 class="text-center">LARGE</h4>
	              			<a href="pdf_compcard.php?u=<?php echo $user_id; ?>&card_type=large" target="_blank" class="btn btn-theme"> View</a>
	              		</div>
              		</div>
              		<div class="col-xs-4">
              			<div class="container-fluid border-box-custom padding-15" style="border-bottom: 0px;padding-bottom:8px;padding-left:8px;padding-right:8px;">
              				<div class="col-xs-3" style="padding-left:8px;padding-right:8px;">
              					<div class="border-box-custom padding-50" style=""></div>
              				</div>
              				<div class="col-xs-3" style="padding-left:8px;padding-right:8px;">
              					<div class="border-box-custom padding-50" style=""></div>
              				</div>
              				<div class="col-xs-3" style="padding-left:8px;padding-right:8px;">
              					<div class="border-box-custom padding-50" style=""></div>
              				</div>
              				<div class="col-xs-3" style="padding-left:8px;padding-right:8px">
              					<div class="border-box-custom padding-50" style=""></div>
              				</div>
              			</div>
              			<div class="container-fluid border-box-custom padding-15" style="border-top: 0px;padding-top:8px;padding-left:8px;padding-right:8px;">
              				<div class="col-xs-3" style="padding-left:8px;padding-right:8px;">
              					<div class="border-box-custom padding-50" style=""></div>
              				</div>
              				<div class="col-xs-3" style="padding-left:8px;padding-right:8px;">
              					<div class="border-box-custom padding-50" style=""></div>
              				</div>
              				<div class="col-xs-3" style="padding-left:8px;padding-right:8px;">
              					<div class="border-box-custom padding-50" style=""></div>
              				</div>
              				<div class="col-xs-3" style="padding-left:8px;padding-right:8px">
              					<div class="border-box-custom padding-50" style=""></div>
              				</div>
              			</div>
              			<div class="text-center">
	              			<h4 class="text-center">THUMBNAILS</h4>
	              			<a href="pdf_compcard.php?u=<?php echo $user_id; ?>&card_type=thumbnails" target="_blank" class="btn btn-theme"> View</a>
	              		</div>

              		</div>
              		<div class="col-xs-4 ">
              			<div class="container-fluid border-box-custom padding-15" style="padding-left:8px;padding-right:8px;">
              				<div class="col-xs-6" style="padding-left:8px;padding-right:8px;">
              					<div class="border-box-custom padding-100" style=""></div>
              				</div>
              				<div class="col-xs-6" style="padding-left:8px;padding-right:8px;">
              					<div class="container-fluid border-box-custom padding-15" style="border-bottom: 0px;padding-bottom:8px;padding-left:8px;padding-right:8px;">
		              				<div class="col-xs-6" style="padding-left:8px;padding-right:8px;">
		              					<div class="border-box-custom padding-comp2" style=""></div>
		              				</div>
		              				<div class="col-xs-6" style="padding-left:8px;padding-right:8px">
		              					<div class="border-box-custom padding-comp2" style=""></div>
		              				</div>
		              			</div>
		              			<div class="container-fluid border-box-custom padding-15" style="border-top: 0px;padding-top:8px;padding-left:8px;padding-right:8px;">
		              				<div class="col-xs-6" style="padding-left:8px;padding-right:8px;">
		              					<div class="border-box-custom padding-comp2" style=""></div>
		              				</div>
		              				<div class="col-xs-6" style="padding-left:8px;padding-right:8px">
		              					<div class="border-box-custom padding-comp2" style=""></div>
		              				</div>
		              			</div>
              				</div>
              			</div>

              			<div class="text-center">
	              			<h4 class="text-center">COMPCARD</h4>
	              			<a href="pdf_compcard.php?u=<?php echo $user_id; ?>&card_type=compcard" target="_blank" class="btn btn-theme"> View</a>
	              		</div>
              		</div>
              	</div>
              </div>
              
              <!-- Modal Footer -->
              <!-- <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  <input type="hidden" name="role_id" id="role_id" value="" />
                  <input type="submit" class="btn btn-theme submitBtn" name="submission_Save" value="Send" />
              </div> -->
          </div>
      </form>
    </div>
</div>

<?php include('footer_js.php'); ?>
<script src="../dashboard/assets/DataTables/datatables.min.js"></script> 
<script src="../dashboard/assets/DataTables/dataTables.bootstrap.min.js"></script>

<!-- <script src="../dashboard/assets/OwlCarousel/owl.carousel.min.js"></script> -->
<!-- <script src="../dashboard/assets/fancybox/jquery.fancybox.min.js"></script> -->

<script src="../dashboard/assets/js/app.min.js"></script>
<script src="../dashboard/assets/fileStyle/fileStyle.js"></script>
<script src="../dashboard/assets/fancybox/jquery.fancybox.min.js"></script>

<script type="text/javascript" src="https://code.jquery.com/ui/1.11.0/jquery-ui.min.js"></script>
<script type="text/javascript" src="../dashboard/assets/jQuery-DateTimepicker-Addon/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript" src="../dashboard/assets/jQuery-DateTimepicker-Addon/i18n/jquery-ui-timepicker-addon-i18n.min.js"></script>
<script type="text/javascript" src="../dashboard/assets/jQuery-DateTimepicker-Addon/jquery-ui-sliderAccess.js"></script>

<script type="text/javascript" src="../dashboard/assets/js/jquery.validate.js"></script>
<script>
	$('#birthdate').datepicker({
    	changeMonth: true,
    	changeYear: true,
    	// minDate: 0,
    });

	$.validator.addMethod("ethnicity_check", function(value, element) {
	  	eth = $("#ethnicity").val();
	  	eth_other = $("#ethnicity_other").val();
	  	// console.log(eth);
	  	// console.log(eth_other);
	  	if (eth == "" && eth_other == "") {
	        return false;
	    } else {
	        return true;
	    };
	}, "Please select ethnicity or enter other.");

    $("#physical_frm").validate({
		rules: {
			gender: "required",
			ethnicity: {ethnicity_check: true,}
		},
		messages: {
			// lastname: "Please +enter your lastname",
		},
		errorElement: "em",
		errorPlacement: function ( error, element ) {
			// Add the `help-block` class to the error element
			error.addClass( "help-block" );

			if ( element.prop( "type" ) === "radio") {
				error.insertAfter(element.parents('label').siblings('.radio_err'));
			} else {
				error.insertAfter(element);
			}
		},
		highlight: function ( element, errorClass, validClass ) {
			$( element ).parents( ".col-sm-5" ).addClass( "has-error" ).removeClass( "has-success" );
		},
		unhighlight: function (element, errorClass, validClass) {
			$( element ).parents( ".col-sm-5" ).addClass( "has-success" ).removeClass( "has-error" );
		}

		// submitHandler: function (){
		// 	alert( "submitted!" );
		// }
	});
</script>
<script>
	if (window.history.replaceState) {
	  window.history.replaceState( null, null, window.location.href );
	}
</script>
<?php include('footer.php'); ?>