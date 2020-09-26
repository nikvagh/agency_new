<?php
@include('includes/header.php');

require_once('./includes/recaptchalib.php');
$publickey = "6LcFTN0SAAAAAFitjQtOP2bVrB1YGlFr2WS1R07-";  
$privatekey = "6LcFTN0SAAAAAJIhKf8ZyomJ_5LPZShjBk1GRYvY";
$resp = null;
// the error code from reCAPTCHA, if any
$error = null;
?>
<div id="AGENCYprofilepage">
<?php
if(!empty($_GET['u'])) {
	// see if this is an approved user
	$profileid= (int) escape_data(trim($_GET['u']));
	$userid = (int) $_SESSION['user_id'];
	if(!is_active() && !isset($_SESSION['user_id']) && !is_admin() && mysql_result(mysql_query("SELECT COUNT(*) as 'Num' FROM forum_users WHERE user_id='$profileid' AND user_id<>'$userid' AND user_type='0'"),0) == 0) {
		// echo "SELECT COUNT(*) as 'Num' FROM forum_users WHERE user_id='$userid' AND user_id<>'$loggedin' AND user_type='0'";
		// exit();
		unset($profileid);
	}
	if(isset($_GET['publicview'])) { // this is to show the user how others will see their profile
		unset($_SESSION['user_id']);
	}
}

if(empty($profileid)) {
	if($_SESSION['user_id']) { // check if user is logged in
		$profileid = $_SESSION['user_id'];
		if(agency_account_type() == 'talent') {
			$url = 'profile.php?u=' . $profileid;
			ob_end_clean(); // Delete the buffer.
			header("Location: $url");
			exit(); // Quit the script.
		}
		
	} else {
		$url = 'home.php';
		ob_end_clean(); // Delete the buffer.
		header("Location: $url");
		exit(); // Quit the script.
	}
}

/* 
if(isset($_GET['payment'])) {
	echo '<div class="AGENCYsubmitmessage">Thank you for entering your payment information.<br><br>We will not charge your card until your account has been activated.  We will be reviewing your profile shortly.<br><br>Please fill out all information and upload photos if you haven\'t already done so.</div>';
}
*/


if(!is_active() && !profile_ready()) {
	if($profileid == $_SESSION['user_id']) {
		echo '<div class="AGENCYsubmitmessage">';
		echo @mysql_result(@mysql_query("SELECT varvalue FROM agency_vars WHERE varname='waiting'"), 0, 'varvalue');
		echo '</div>';
	}
}

if(!is_active() && profile_ready()) {
	if($profileid == $_SESSION['user_id']) {
		echo '<div class="AGENCYsubmitmessage">';
		echo @mysql_result(@mysql_query("SELECT varvalue FROM agency_vars WHERE varname='ready'"), 0, 'varvalue');
		echo '</div>';
	}
}

/* else if ($_SESSION['editmode']) {
	$sql = "SELECT * FROM forum_users WHERE user_id='$profileid'";
	$result=mysql_query($sql);
	if($row = sql_fetchrow($result)) {
		if($row['user_type'] == '1') {
			echo '<div class="AGENCYsubmitmessage">';
			echo @mysql_result(@mysql_query("SELECT varvalue FROM agency_vars WHERE varname='waiting'"), 0, 'varvalue');
			echo '</div>';
		}
	}
} */

// first get the folder name
$sql = "SELECT * FROM agency_profiles WHERE user_id='$profileid'";
$result=mysql_query($sql);
if($userinfo = sql_fetchrow($result)) {  // "$userinfo" array will be available through file, so no need to access database again
  	if($userinfo['account_type'] == 'client') {
		// if(isset($_GET['login'])) {
			$url = 'clienthome.php';
		/* } else {
			$url = 'clientlist.php?id=' . $profileid . '#id' . $profileid;
		} */
		ob_end_clean(); // Delete the buffer.
		header("Location: $url");
		exit(); // Quit the script.
	}
	$folder = 'talentphotos/' . $profileid. '_' . $userinfo['registration_date'] . '/';
	$profilecode = $userinfo['registration_date'];
}

if($_SESSION['user_id'] == $profileid) { // if this is the logged in person's profile
	// check if they are PAID
	if(!$userinfo['payProcessed']) { // if not paid
		// see if they entered a credit card
		$sql = "SELECT * FROM agency_cc WHERE user_id='$profileid'";
		$result=mysql_query($sql);
		if(mysql_num_rows($result) == 0) {
			echo '<div class="AGENCYsubmitmessage" style="font-weight:normal">';
			echo @mysql_result(@mysql_query("SELECT varvalue FROM agency_vars WHERE varname='unpaid'"), 0, 'varvalue');
			echo '</div>';
		}
	}
}


if(agency_account_type() == 'talent') { // talent will see this on any talent page
	$alltalent = showbox('alltalent');
	if($alltalent) {
		echo '<div style="clear:both">' . $alltalent . '</div>';
	}
} else if(agency_account_type() == 'client') { // talent will see this on any talent page
	$clientontalent = showbox('clientontalent');
	if($clientontalent) {
		echo '<div style="clear:both">' . $clientontalent . '</div>';
	}
} else {
	$profileguest = showbox('profileguest');
	if($profileguest) {
		echo '<div style="clear:both">' . $profileguest . '</div>';
	}
}



if(isset($profileid) && isset($folder)) { // if folder (reg date) is not found, no images will be found, something is wrong so don't display
	$submitmessage = '';
	if(isset($_GET['accountupdate'])) {
		$submitmessage = 'Your Settings Have Been Updated.';
	}
	/* =========================   Start: PROCESS OF PROFILE CHANGES ==================== */

	// ADD TO VISITOR COUNTER
	if($userinfo['last_visit_IP'] != getRealIpAddr()) {
		$ip = getRealIpAddr();
		$profilevisits = $userinfo['visits'] + 1;
		$sql = "UPDATE agency_profiles SET visits='$profilevisits', last_visit_IP='$ip' WHERE user_id='$profileid'";
		mysql_query($sql);
	} else {
		$profilevisits = $userinfo['visits']; // get info for output below
	}

	// ADD TO FANS
	$fans = $userinfo['fans']; // get info for output below
	if($_GET['addfan'] && ($profileid != $loggedin) && is_active()) {
		$sql = "SELECT * FROM agency_fans WHERE user_id='$profileid' AND fan_id='$loggedin'";
		$result = mysql_query($sql);
		if(mysql_num_rows($result) == 0) { // if not already a fan
			$fans = $userinfo['fans'] + 1;
			$sql = "INSERT INTO agency_fans (user_id, fan_id) VALUES ('$profileid', '$loggedin')";
			mysql_query($sql);
			// recount fans
			$sql = "SELECT * FROM agency_fans WHERE user_id='$profileid'";
			$result = mysql_query($sql);
			$fans = mysql_num_rows($result);
			$sql = "UPDATE agency_profiles SET fans='$fans' WHERE user_id='$profileid'";
			mysql_query($sql);
		}
	}



	// UPDATE STATUS
	if (isset($_POST['updatestatus'])) { // Handle the form.
		if($loggedin) { // check if user is logged in
			// $posterid = $_SESSION['user_id'];
		 	$message = escape_data($_POST['mystatus']);
		 	$sql = "UPDATE agency_profiles SET status='$message' WHERE user_id='$loggedin'";
		 	mysql_query($sql);
		 	if(mysql_affected_rows()) {
		 		$userinfo['status'] = htmlspecialchars($_POST['mystatus']);
			}
		}
	}

	// CLEAR STATUS
	if (isset($_GET['clearstatus'])) { // Handle the form.
		if($_SESSION['user_id']) { // check if user is logged in
			$posterid = $_SESSION['user_id'];
		 	$sql = "UPDATE agency_profiles SET status=NULL WHERE user_id='$posterid'";
		 	mysql_query($sql);
		 	if(mysql_affected_rows()) {
		 		$userinfo['status'] = NULL;
			}
		}
	}

	// POST TO WALL
	if (!empty($_POST['wallpost'])) { // Handle the form.
		if(is_active()) { // check if user is logged in
			$posterid = $_SESSION['user_id'];
			$capcha_ok = true;
			
			if(showcaptcha($posterid)) {
				// check captcha
				$capcha_ok = false;
								
				# was there a reCAPTCHA response?
				if ($_POST["recaptcha_response_field"]) {
					$resp = recaptcha_check_answer ($privatekey,
													$_SERVER["REMOTE_ADDR"],
													$_POST["recaptcha_challenge_field"],
													$_POST["recaptcha_response_field"]);
				
					if (!$resp->is_valid) {
						# set the error code so that we can display it
						// $error = $resp->error;
						echo '<script type="text/javascript">alert(\'The text entered in the Captcha box was incorrect.  Please try your post again.\'); </script>';
					} else {
						$capcha_ok = true;
					}
				}		
			}
			
			
			if($capcha_ok) {
				$message = escape_data($_POST['wallpost']);
				$sql = "SELECT firstname, lastname FROM agency_profiles WHERE user_id='$posterid'";
				$result=mysql_query($sql);
				if($row = sql_fetchrow($result)) {
					$fname = $row['firstname'];
					$lname = $row['lastname'];
					$sql = "INSERT INTO agency_wall (user_id, message, poster_id, poster_fname, poster_lname, date) VALUES ('$profileid', '$message', '$posterid', '$fname', '$lname', NOW() )";
					mysql_query($sql);
				}
			}
		}
	}
	// DELETE WALL POST
	if(!empty($_GET['walldel']) && is_active()) {
		$loggedin = $_SESSION['user_id'];
		$delete = (int) escape_data($_GET['walldel']);
		$sql = "SELECT poster_id FROM agency_wall WHERE post_id='$delete' AND (poster_id='$loggedin' OR user_id='$loggedin')";
		$result=mysql_query($sql);
		if($row = sql_fetchrow($result)) { // if logged in user is the poster or owner of the wall for this post
			$sql = "DELETE FROM agency_wall WHERE post_id='$delete' LIMIT 1";
			mysql_query($sql);
		}
	}
	
	// DELETE A FILE
	if(isset($_GET['delfile'])) {
		if($_GET['delfile'] == 'resume') {
			unlink($folder . $userinfo['resume']);
		}
	}


	// SEND FRIEND REQUEST
	if(isset($_GET['request']) && is_active()) {
		// check to see if proper request code was sent.  The request code is here to make it slightly more difficult
		// to run a script to automatically send friend requests to everyone.  It won't stop it, but might slow it down.
		$sql = "SELECT registration_date FROM agency_profiles WHERE user_id='$profileid'";
		$result=mysql_query($sql);
		if($row = sql_fetchrow($result)) {
			if($row['registration_date'] == $_GET['request']) { // code matches, continue
				$loggedin = $_SESSION['user_id'];
				// first see if user is already a friend, has been denied, or a request has already been sent
				$sql = "SELECT * FROM agency_friends WHERE user_id='$loggedin' AND friend_id='$profileid'";
				$result = mysql_query($sql);
				if($row = sql_fetchrow($result)) {
					// if there's a result, this person has already made contact, do not send request
					if($row['denied'] == '1') { // this request has already been denied
						$submitmessage .= 'Your friend request has been denied.';
					} else if($row['confirmed'] == '0') {
						$submitmessage .= 'You have already requested this friend.  They have not yet accepted your request';
					} else {
						$submitmessage .= 'This person is already your friend';
					}
				} else {
					// no friend request has previously been sent, so send it
					// make sure the requestee has not already sent a request
					$sql = "SELECT * FROM agency_friends WHERE user_id='$profileid' AND friend_id='$loggedin'";
					$result = mysql_query($sql);
					if($row = sql_fetchrow($result)) {
						// if the other person has already requested this person as a friend, simply confirm the friendship
						$sql = "UPDATE agency_friends SET confirmed='1', denied='0' WHERE user_id='$profileid' AND friend_id='$loggedin'";
						mysql_query($sql);
						$sql = "INSERT INTO agency_friends (user_id, friend_id, confirmed) VALUES ('$loggedin', '$profileid', '1')";
						mysql_query($sql);
						$submitmessage .= 'You are now friends with this person';
					} else {
						// send friend request
		   				$sql = "INSERT INTO agency_friends (user_id, friend_id) VALUES ('$loggedin', '$profileid')";
		   				mysql_query($sql);
		   				$submitmessage .= 'Your friend request has been sent';
					}
			   //	}
			   //	if(((int) $db->sql_fetchfield('num')) == 0) { // if request does not already exist
			   // 	unset($result);

				}
			}
		} else {
			$submitmessage .= 'Friend request was not properly sent.';
		}
		unset($result);




	}


	/* 
	// UPDATE AVATAR
	if (isset($_FILES['avatarfile'])) { // Handle the form.
		$posterid = $_SESSION['user_id'];
		// $file = $_FILES['avatarfile'];

		// process for sizing
		$allowed = array ('image/gif', 'image/jpeg', 'image/jpg', 'image/pjpeg', 'image/JPG');
		if (in_array($_FILES['avatarfile']['type'], $allowed)) {
			$allowed_jpg = array ('image/jpeg', 'image/jpg', 'image/pjpeg', 'image/JPG');
			$allowed_gif = array ('image/gif');
			if (in_array($_FILES['avatarfile']['type'], $allowed_jpg)) {
				$filetype = ".jpg";
				$current_pic = $folder . 'avatar' . ".gif";
			} else if (in_array($_FILES['avatarfile']['type'], $allowed_gif)) {
				$filetype = ".gif";
				$current_pic = $folder . 'avatar' . ".jpg";
			}

			if(!file_exists($folder)) { // if folder doesn't exist yet, create it
				mkdir($folder);
				chmod($folder,0777);
			}

			// Move the file over.
			$filename = $folder . 'avatar' . $filetype;
			if (move_uploaded_file($_FILES['avatarfile']['tmp_name'], "$filename")) {
				if (file_exists($current_pic)) { unlink ($current_pic); }  // delete old file if not same type

				// Set a maximum height and width
				$width = 120;

				// $height = 120;

				// Content type
				// header('Content-type: image/jpeg');

				// Get new dimensions
				list($width_orig, $height_orig) = getimagesize($filename);

				$ratio_orig = $width_orig/$height_orig;

				$height = $width/$ratio_orig;

				if($ratio_orig < 1) {
					$boardh = 90;
					$boardw = $boardh*$ratio_orig;
				} else {
					$boardw = 90;
					$boardh = $boardw/$ratio_orig;
				}

				// Resample
				$image_p = imagecreatetruecolor($width, $height);
				$image_board = imagecreatetruecolor($boardw, $boardh);

				if ($filetype == '.jpg') {
					$image = imagecreatefromjpeg($filename);
				}
				if ($filetype == '.gif') {
					$image = imagecreatefromgif($filename);
				}
				imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
				imagecopyresampled($image_board, $image, 0, 0, 0, 0, $boardw, $boardh, $width_orig, $height_orig);
				// Output

				if ($filetype == '.jpg') {
					imagejpeg($image_p, $filename, 100);
				}
				if ($filetype == '.gif') {
					imagegif($image_p, $filename, 100);
				}

			   	echo '<script type="text/javascript">document.location=\'profile.php?u=' . $profileid . '&refresh=true\';</script>';

			} else { // Couldn't move the file over.

				$submitmessage .= '<p><font color="red">The file could not be uploaded because: </b>';

				// Print a message based upon the error.
				switch ($_FILES['avatarfile']['error']) {
					case 1:
						$submitmessage .= 'The file exceeds the upload_max_filesize setting in php.ini.';
						break;
					case 2:
						$submitmessage .= 'The file must be less than 500K.';
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

				$submitmessage .= '</b></font></p>';

			} // End of move... IF.

		} else { // Invalid type.
			$submitmessage .= '<p><font color="red">Please upload a JPEG or GIF image smaller than 500KB.</font></p>';
			if (file_exists($_FILES['avatarfile']['tmp_name'])) { unlink ($_FILES['avatarfile']['tmp_name']); }  // delete temp file

			// unlink ($_FILES['upload']['tmp_name']); // Delete the file.
		}
	}
	*/

		// UPDATE RESUME
	if (isset($_FILES['resumefile'])) { // Handle the form.

		// process for sizing
	  	$allowedExtensions = array("txt","doc","docx","rtf","pdf","jpg","jpeg","gif","png","bmp","tiff","tif");
	  	if ($_FILES['resumefile']['tmp_name'] > '') {
      		if (in_array(end(explode(".", strtolower($_FILES['resumefile']['name']))), $allowedExtensions)) {  // check for valid file extension

				if(!file_exists($folder)) { // if folder doesn't exist yet, create it
					mkdir($folder);
					chmod($folder,0777);
				}

				// Move the file over.
				$fullname = $folder . $_FILES['resumefile']['name'];
				$filename = escape_data($_FILES['resumefile']['name']);
				if (move_uploaded_file($_FILES['resumefile']['tmp_name'], "$fullname")) {

					if(!empty($userinfo['resume'])) { // delete old file
			   			if ($_FILES['resumefile']['name'] != $userinfo['resume'] && file_exists($folder . $userinfo['resume'])) {
			   				unlink ($folder . $userinfo['resume']);
			   			}
					}

					$userinfo['resume'] = stripslashes($filename);

					$sql = "UPDATE agency_profiles SET resume='$filename' WHERE user_id='$loggedin'";
					mysql_query($sql);


				   $submitmessage .= 'Resume has been updated!';

				} else { // Couldn't move the file over.

					$submitmessage .= 'The file could not be uploaded because: ';

					// Print a message based upon the error.
					switch ($_FILES['resumefile']['error']) {
						case 1:
							$submitmessage .= 'The file exceeds the upload_max_filesize setting in php.ini.';
							break;
						case 2:
							$submitmessage .= 'The file must be less than 5MB';
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
				} // End of move... IF.

			} else { // Invalid type.
				$submitmessage .= 'This file type is not allowed.';
				if (file_exists($_FILES['resumefile']['tmp_name'])) { unlink ($_FILES['resumefile']['tmp_name']); }  // delete temp file
			}
		} else { // Invalid type.
			$submitmessage .= 'It appears you did not enter a file.';
		}
	}

		// UPDATE HEADSHOT
	if (isset($_FILES['headshotfile'])) { // Handle the form.

		// process for sizing
	  	$allowedExtensions = array("txt","doc","docx","rtf","pdf","jpg","jpeg","gif","png","bmp","tiff","tif");
	  	if (!empty($_FILES['headshotfile']['tmp_name'])) {
      		if (in_array(end(explode(".", strtolower($_FILES['headshotfile']['name']))), $allowedExtensions)) {  // check for valid file extension

				if(!file_exists($folder)) { // if folder doesn't exist yet, create it
					mkdir($folder);
					chmod($folder,0777);
				}

				// Move the file over.
				$fullname = $folder . $_FILES['headshotfile']['name'];
				$filename = escape_data($_FILES['headshotfile']['name']);
				if (move_uploaded_file($_FILES['headshotfile']['tmp_name'], "$fullname")) {

					if(!empty($userinfo['headshot'])) { // delete old file
			   			if ($_FILES['headshotfile']['name'] != $userinfo['headshot'] && file_exists($folder . $userinfo['headshot'])) {
			   				unlink ($folder . $userinfo['headshot']);
			   			}
					}

					$userinfo['headshot'] = stripslashes($filename);

					$sql = "UPDATE agency_profiles SET headshot='$filename' WHERE user_id='$loggedin'";
					mysql_query($sql);


				   $submitmessage .= 'Headshot has been updated!';

				} else { // Couldn't move the file over.

					$submitmessage .= 'The file could not be uploaded because: ';

					// Print a message based upon the error.
					switch ($_FILES['headshotfile']['error']) {
						case 1:
							$submitmessage .= 'The file exceeds the upload_max_filesize setting in php.ini.';
							break;
						case 2:
							$submitmessage .= 'The file must be less than 5MB';
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
				} // End of move... IF.

			} else { // Invalid type.
				$submitmessage .= 'This file type is not allowed.';
				if (file_exists($_FILES['headshotfile']['tmp_name'])) { unlink ($_FILES['headshotfile']['tmp_name']); }  // delete temp file
			}
		} else { // Invalid type.
			$submitmessage .= 'It appears you did not enter a file or the file was too large.  File must be acceptable type and less than 5MB.';
		}
	}

	/* =========================   End: PROCESS OF PROFILE CHANGES ==================== */

	if(!empty($submitmessage)) {
		echo '<div class="AGENCYsubmitmessage">' . $submitmessage . '</div>';
	}

	@include('includes/profile_left.php');
?>




















<div id="AGENCYProfileWideLeftSurround">
	<div id="AGENCYProfileMiddleSurround">

  		<div id="AGENCYProfileName" class="AGENCYUserName" style="z-index:5; position:relative">


			<div style="float:left; margin-right:10px; color:<?php echo $experiencecolors[$userinfo['experience']]; ?>">
<?php
	echo $userinfo['firstname'];
	if(agency_privacy($profileid, 'lastname')) {
		echo ' ' . $userinfo['lastname'];
	}
?>
			</div>
			<div style="float:left; position:relative">
  				<img style="padding-right:40px" src="images/<?php echo $experienceimages[$userinfo['experience']]; ?>.gif" onmouseover="document.getElementById('experience_popup').style.display=''" onmouseout="document.getElementById('experience_popup').style.display='none'" />

				<div class="AGENCYBlue" style=" font-size:12px; font-weight:bold; top:14px; position:absolute; width:300px">
<?php echo $profilevisits; ?> Profile Visits / <?php echo number_format($fans); ?>
 Fan<?php
	if($fans != 1) echo 's'; // put as at end of "Fan(s)"

	// if logged in and not a fan, put button
	if($loggedin) {
		if(is_active() && ($loggedin != $profileid)) { // cannot be fan of yourself
			$sql = "SELECT * FROM agency_fans WHERE user_id='$profileid' AND fan_id='$loggedin'";
			$result = mysql_query($sql);
			if(mysql_num_rows($result) == 0) { // if not already a fan
				echo ' <a href="profile.php?u=' . $profileid . '&amp;addfan=' . $loggedin . '" style="font-size:10px; text-decoration:none">become a fan</a>';
			} else {
				echo ' (you\'re a fan!)';
			}
		}
	}
?>

                </div>
            </div>


            <div style="position: absolute; width: 100px; text-align: right; right:0px;"><?php include('includes/addbutton.php'); ?></div>
          	<div id="experience_popup" style="position:absolute; margin-top:10px; display:none; z-index:5">
<?php
	echo @mysql_result(@mysql_query("SELECT varvalue FROM agency_vars WHERE varname='levelsExp'"), 0, 'varvalue');
?>
			</div>
<?php
	if($loggedin) {
		if($loggedin == $profileid) { // this is "my" page, show my status
			echo '<div style="font-weight: bold; position:absolute; right:-184px; top:-10px; font-size: 14px;" class="' . (is_active() ? 'AGENCYGreen">MyProfile Is ACTIVE' : 'AGENCYRed">MyProfile Is NOT ACTIVE') . '</div>';
		}
	}
?>
  		</div>
  		<br clear="all" />

		<div id="AGENCYProfileTabContainer">
<?php
	if($profileid == $_SESSION['user_id']) {
		// if this is logged in user's profile, show all tabs
		$tabs = array('Photos', 'Wall', 'Reel/VO', 'Castings', 'Messages');
	} else {
		// public tabs
		$tabs = array('Photos', 'Wall', 'Reel/VO');
	}

	if(isset($_GET['tab'])) {
		foreach($tabs as $t) {
			if($t == $_GET['tab']) {
				echo '<span class="AGENCYProfileTab AGENCYProfileTabActive">' . $t . '</span>';
			} else {
				echo '<a href="profile.php?tab=' . $t . '&amp;u=' . $profileid . '" class="AGENCYProfileTab AGENCYProfileTabInActive">' . $t . '</a>';
			}
		}

		if($profileid != $_SESSION['user_id'] && is_active()) {
?>
  <a href="ajax/message_process.php?sendto=<?php echo $profileid; ?>&amp;height=400&amp;width=450&amp;inlineId=hiddenModalContent" class="AGENCYProfileTab thickbox AGENCYProfileTabInActive">Contact</a>
<?php
		}
	} else {
?>
<span class="AGENCYProfileTab AGENCYProfileTabActive">Photos</span>
<a href="profile.php?tab=Wall&amp;u=<?php echo $profileid; ?>" class="AGENCYProfileTab AGENCYProfileTabInActive">Wall</a>
<a href="profile.php?tab=Reel/VO&amp;u=<?php echo $profileid; ?>" class="AGENCYProfileTab AGENCYProfileTabInActive">Reel/VO</a>
<?php
	if($profileid == $_SESSION['user_id']) {
?>
<a href="profile.php?tab=Castings&amp;u=<?php echo $profileid; ?>" class="AGENCYProfileTab AGENCYProfileTabInActive">Castings</a>
<a href="messages.php" class="AGENCYProfileTab AGENCYProfileTabInActive">Messages</a>
<?php
	} else if(is_active()) {
?>
  <a href="ajax/message_process.php?sendto=<?php echo $profileid; ?>&amp;height=400&amp;width=450&amp;inlineId=hiddenModalContent" class="AGENCYProfileTab thickbox AGENCYProfileTabInActive">Contact</a>
<?php
	}
}
?>
		</div>










        <div id="AGENCYProfileMiddleContent">
       
<?php
	if(isset($_GET['tab'])) {
		$tab = $_GET['tab'];
	} else {
		$tab = 'Photos';
	}

	if ($tab == 'Contact') {
		// show contact info
			/* ===================================   Start: CONTACT    =============================== */
		echo '<div align="center" style="padding-top:40px; font-size:14px; font-weight:bold">';
		if(agency_account_type() == 'client' && is_active()) {

			echo $userinfo['city'];
			if(!empty($userinfo['city'])) { echo ', '; }
			echo $userinfo['state'] . '<br />' . $userinfo['country'];

			if(agency_privacy($profileid, 'phone')) {
				echo '<br /><br />' . $userinfo['phone'] . '<br /><br />';
			}
			// get email address:
			$query = "SELECT user_email FROM forum_users WHERE user_id='$profileid'";
			$result = @mysql_query($query);
			if ($row = @mysql_fetch_array($result, MYSQL_ASSOC)) {
				if(!empty($row['user_email'])) { echo '<a href="mailto:' . $row['user_email'] . '">' . $row['user_email'] . '</a>'; }
			}
		} else {
			echo '<br /><br />Only approved Clients may view contact information<br /><br />';
		}
		echo '</div>';
			/* ===================================   End: CONTACT    =============================== */
	} else if($tab == 'Photos') {
			/* ===================================   Start: PHOTOS    =============================== */
		echo '<div class="AGENCYLtBlue2" style="font-size:medium; font-weight:bold; width:210px; float:left;">CompCard';
		if($_SESSION['user_id'] == $profileid) { // if this is the profile of the logged in user
		  	echo '&nbsp;&nbsp;<a href="myimages.php?tab=CompCard Photos" style="text-decoration:none; font-size:14px"><span class="AGENCYLtBlue">Edit</span></a>';
		}
		echo '</div>';

		if($loggedin) {
			if($loggedin == $profileid) {
				$sendwords = 'Send My Profile';
			} else {
				$sendwords = 'Send Profile';
			}
			echo '<div class="AGENCYBlue" style="font-size:small; font-weight:bold; width:120px; float:left"><a href="mailto:?subject=Check out this talent at The Agency&body=I\'ve found someone that I would like you to check out.  Here\'s the link:%0A%0Ahttp://www.theagencyonline.com/profile.php%3Ftab=Photos%26u=' . $profileid. '">' . $sendwords . '</a></div>';
			// echo '<div class="AGENCYBlue" style="font-size:small; font-weight:bold; width:60px; float:left"><a href="pdf_compcard.php?u=' . $profileid. '" target="_blank">Print/Save</a></div>';
			echo '<div class="AGENCYBlue" style="font-size:small; font-weight:bold; width:60px; float:left"><a href="javascript:void(0)" onclick="document.getElementById(\'compcard_options\').style.display=\'\'">Print/Save</a>
					<div id="compcard_options" style="display:none; background-color:white; padding:0px; position:absolute; border:1px solid gray">
						<div align="right" style="padding-right:4px"><a href="javascript:void(0)" onclick="document.getElementById(\'compcard_options\').style.display=\'none\'" style="text-decoration:none">x</a></div>
						<div style="padding: 0 10px 10px 10px">
							<a href="pdf_compcard.php?u=' . $profileid. '" style="text-decoration:none" target="_blank">PDF</a><br />
							<a href="compcard_image.php?u=' . $profileid. '" style="text-decoration:none" target="_blank">JPEG</a>
						</div>
					</div>
				  </div>';		
		
		
		} else {
			echo '<div class="AGENCYBlue" style="font-size:small; font-weight:bold; width:120px; float:left"><a href="javascript:void(0)" onClick="alert(\'Please log in to enjoy this feature.\')">Send Profile</a></div>';
			echo '<div class="AGENCYBlue" style="font-size:small; font-weight:bold; width:60px; float:left"><a href="javascript:void(0)" onClick="alert(\'Please log in to enjoy this feature.\')">Print/Save</a></div>';
		}

		echo '<br clear="all" />';
		echo '<table border="0" cellspacing="10" cellpadding="0" style="clear:both"><tr>';
	    if(isset($folder)) {
			 $sql = "SELECT * FROM agency_photos WHERE user_id='$profileid' AND card_position IS NOT NULL ORDER BY card_position ASC";
			 $result=mysql_query($sql);
			 $current = 1;

			 while(($row = sql_fetchrow($result)) && $current <= 4) {
			 	 $pos = $row['card_position'];
			 	 while($current < $pos) {
		 			switch($current) {
						case 1:
							echo '<td rowspan="2" style="width:200px"><div id="primaryspot" style="display:none; width:200px"></div></td>';
							break;
						case 2:
							echo '<td>&nbsp;</td>';
							break;
						case 3:
							echo '<td>&nbsp;</td></tr>';
							break;
						case 4:
							echo '<tr><td>&nbsp;</td>';
							break;
					}
					$current++;
				 }

		 		 switch($current) {
					case 1:
						echo '<td rowspan="2" valign="top"><div id="primaryspot" style="display:none; width:200px"></div><img id="primarypic" src="' . $folder . $row['filename'] . '" width="200" /></td>';
						break;
					case 2:
						echo '<td valign="top"><img src="' . $folder . $row['filename'] . '" width="100" onmouseover="cardimageswap(\'' . $folder . $row['filename'] . '\')" onmouseout="cardimageswapout()" /></td>';
						break;
					case 3:
						echo '<td valign="top"><img src="' . $folder . $row['filename'] . '" width="100" onmouseover="cardimageswap(\'' . $folder . $row['filename'] . '\')" onmouseout="cardimageswapout()" /></td></tr>';
						break;
					case 4:
						echo '<tr><td valign="top"><img src="' . $folder . $row['filename'] . '" width="100" onmouseover="cardimageswap(\'' . $folder . $row['filename'] . '\')" onmouseout="cardimageswapout()" /></td>';
						break;
				}
				$current++;
			 }
		}
		echo '<td valign="top"><table>
			<tr><td class="AGENCYCompCardLabel">Height: </td><td class="AGENCYCompCardStat">' . floor($userinfo['height']/12) . '\' ' . $userinfo['height'] % 12 . '"</td></tr>
			<tr><td class="AGENCYCompCardLabel">Waist: </td><td class="AGENCYCompCardStat">' . escape_data($userinfo['waist']) . '"</td></tr>';

		if($userinfo['gender'] != 'M') { // if female or "other"
		echo '<tr><td class="AGENCYCompCardLabel">Bust: </td><td class="AGENCYCompCardStat">' . escape_data($userinfo['bust']) . '"</td></tr>
			<tr><td class="AGENCYCompCardLabel">Cup Size: </td><td class="AGENCYCompCardStat">' . escape_data($bracups[$userinfo['cup']]) . '</td></tr>
			<tr><td class="AGENCYCompCardLabel">Hips: </td><td class="AGENCYCompCardStat">' . escape_data($userinfo['hips']) . '"</td></tr>';
		}

		if($userinfo['gender'] != 'F') { // if male or "other"
		echo '<tr><td class="AGENCYCompCardLabel">Suit: </td><td class="AGENCYCompCardStat">' . agency_print_suit(escape_data($userinfo['suit'])) . '</td></tr>
			<tr><td class="AGENCYCompCardLabel">Neck: </td><td class="AGENCYCompCardStat">' . escape_data($userinfo['neck']) . '"</td></tr>
			<tr><td class="AGENCYCompCardLabel">Inseam: </td><td class="AGENCYCompCardStat">' . escape_data($userinfo['inseam']) . '"</td></tr>';
		}


		echo '<tr><td class="AGENCYCompCardLabel">Shoe: </td><td class="AGENCYCompCardStat">' . escape_data($userinfo['shoe']) . '</td></tr>
			<tr><td class="AGENCYCompCardLabel">Hair: </td><td class="AGENCYCompCardStat">' . $userinfo['hair'] . '</td></tr>
			<tr><td class="AGENCYCompCardLabel">Eyes: </td><td class="AGENCYCompCardStat">' . escape_data($userinfo['eyes']) . '</td></tr>';

		 $sql = "SELECT * FROM agency_profile_unions WHERE user_id='$profileid'";
		 $result=mysql_query($sql);
		 $num_results = mysql_num_rows($result);
		 $current = 1;
		 if($num_results) {
		 	echo '<tr><td class="AGENCYCompCardLabel">Union(s): </td><td class="AGENCYCompCardStat">';
			while($row = sql_fetchrow($result)) {
		   		echo escape_data($row['union_name']);
		   		if($current < $num_results) echo ', ';
		   		$current++;
			}
			echo '</td></tr>';
		 }

		if($_SESSION['user_id'] == $profileid) { // if this is the profile of the logged in user
		  	echo '<tr><td colspan="2" align="right"><a href="myaccount.php?tab=stats" class="AGENCY_graybutton">edit stats</a></td></tr>';
		}

		echo '</table></td></tr></table>';

			/* ===================================   End: PHOTOS    =============================== */
	} else if ($tab == 'Castings' && $profileid == $_SESSION['user_id']) {
		// show castings
			/* ===================================   Start: CASTINGS    =============================== */
			echo '<div style="line-height:1.8em">';

			echo mysql_result(mysql_query("SELECT varvalue FROM agency_vars WHERE varname='codeMyCast'"), 0, 'varvalue');

			echo '<br clear="all" /><div id="AGENCYProfileLeftList" style="width:452px">
				    <div id="AGENCYProfileLeftListTopLeft"></div>
				    <div id="AGENCYProfileLeftListTopCenter" style="width:434px"></div>
				    <div id="AGENCYProfileLeftListTopRight"></div>
				    <div id="AGENCYProfileLeftListInner">
				   		<span style="font-weight:bold; color:#0066FF">Newest Job Postings</span><br />';
			$sql = "SELECT casting_id, job_title, post_date FROM agency_castings WHERE deleted='0' ORDER BY post_date DESC LIMIT 5";
			$result=mysql_query($sql);
			while($row = sql_fetchrow($result)) {
				$castingid = $row['casting_id'];
				$title = $row['job_title'];
				$postdate = date('m/d/y', strtotime($row['post_date']));
				echo '<span style="font-size:10px; color:#666666">' . $postdate . ':</span> "<a style="color:#666666; font-weight:bold; text-decoration:none" href="news.php?castingid=' . $castingid . '">' . $title . '</a>"<br />';
			}
			echo '<br /><hr><span style="font-weight:bold; color:#0066FF">My Casting Submissions</span><br />';
			$sql = "SELECT agency_castings.casting_id, agency_castings.job_title, agency_castings_roles.name, agency_castings.post_date FROM
			 agency_castings, agency_mycastings, agency_castings_roles WHERE agency_mycastings.user_id='$profileid' AND
			 agency_mycastings.role_id=agency_castings_roles.role_id AND agency_castings.casting_id=agency_castings_roles.casting_id AND deleted='0' ORDER BY  post_date DESC";
			$result=mysql_query($sql);
			while($row = sql_fetchrow($result)) {
				$castingid = $row['casting_id'];
				$title = $row['job_title'] . ': ' . $row['name'];
				$postdate = date('m/d/y', strtotime($row['post_date']));
				echo '<span style="font-size:10px; color:#666666">' . $postdate . ':</span> "<a style="color:#666666; font-weight:bold; text-decoration:none" href="news.php?castingid=' . $castingid . '">' . $title . '</a>"<br />';
			}
			echo '</div>
				    <div id="AGENCYProfileLeftListBottomLeft"></div>
				    <div id="AGENCYProfileLeftListBottomCenter" style="width:434px"></div>
				    <div id="AGENCYProfileLeftListBottomRight"></div>
				  </div>';
			echo '<br clear="all" /></div>';
			/* ===================================   End: CASTINGS    =============================== */
	
	
	} else if($tab == 'Reel/VO') {
			/* ===================================   Start: Reel/VO    =============================== */
		if(!$loggedin) {
			echo '<div align="center" style="padding:30px"><b>PLEASE LOG IN TO ENJOY VIEWING REELS AND VOICE-OVERS.</b></div>';
		} else {
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
				if($num_vos >1) echo 'S';
				echo '</b><br />';
				while ($row = mysql_fetch_array ($result, MYSQL_ASSOC)) {
					$vo_id = $row['vo_id'];
					$name = $row['vo_name'];
					$vofile = $folder . $vo_id . '.mp3';
					if(file_exists($vofile)) {
						echo '<br />' . $name . '<br />';
						echo '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0" width="165" height="37" id="niftyPlayer1" align="">
				  <param name=movie value="niftyplayer/niftyplayer.swf?file=' . $vofile . '&as=0">
				  <param name=quality value=high>
				  <param name=bgcolor value=#FFFFFF>
				  <embed src="niftyplayer/niftyplayer.swf?file=' . $vofile . '&as=0" quality=high bgcolor=#FFFFFF width="165" height="37" name="niftyPlayer1" align="" type="application/x-shockwave-flash" swLiveConnect="true" pluginspage="http://www.macromedia.com/go/getflashplayer"> </embed>
				</object>';
				
						if($_SESSION['user_id'] == $profileid) {
							echo '<a href="profile.php?tab=Reel/VO&deletevo=' . $vo_id . '&u=' . $profileid . '" onclick="return confirm(\'Are you sure you want to delete this audio file from the site?\')">delete</a>';
						}				
				
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
				while ($row = mysql_fetch_array ($result, MYSQL_ASSOC)) {
					$reel_host = $row['reel_host'];
					$reel_link_id = $row['reel_link_id'];
					$reel_id = $row['reel_id'];
					if($reel_host == 'youtube') {
						echo '<iframe width="440" height="248" src="http://www.youtube-nocookie.com/embed/' . $reel_link_id . '" frameborder="0" allowfullscreen></iframe>';
						if($_SESSION['user_id'] == $profileid) {
							echo '<br /><a href="profile.php?tab=Reel/VO&deletereel=' . $reel_id . '&u=' . $profileid . '" onclick="return confirm(\'Are you sure you want to remove this video from the site?\')">delete</a>';
						}
					} else if($reel_host == 'vimeo') {
						echo '<iframe src="http://player.vimeo.com/video/' . $reel_link_id . '" width="440" height="259" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>';
						if($_SESSION['user_id'] == $profileid) {
							echo '<br /><a href="profile.php?tab=Reel/VO&deletereel=' . $reel_id . '&u=' . $profileid . '" onclick="return confirm(\'Are you sure you want to remove this video from the site?\')">delete</a>';
						}				}
					echo '<br /><br />';
				}
			}
			
			if(!isset($flag_vo) && !isset($flag_reel)) {
				echo '<br />No Reels or Voice Overs have been posted yet.';
			}
			
			
			
			if($_SESSION['user_id'] == $profileid) {
?>
<br />
<hr />
<form enctype="multipart/form-data" style="margin:20px; padding:5px; background-image: url('images/rightmenu_bg.gif');" action="profile.php?u=<?php echo $profileid; ?>&tab=Reel/VO" method="post">
<input type="hidden" name="MAX_FILE_SIZE" value="10000000" />
<b>Upload New Voice Over</b><br />
<br />
<?php
		if($num_vos < 3) {
?>
Please upload an <u>MP3</u> file of your Voice Over audio.
<br /><br />
Title: <input type="text" name="mp3name" style="width:350px" />
<br />
<br />
Select an MP3 file from your computer (max size: 5MB):<br />
<input type="file" name="mp3file" />
<br />
<p style="display:none; font-weight:bold" id="vo_upload_msg">Your file is uploading.  <u>PLEASE WAIT</u> for the page to refresh on it's own.<br /><br />Large files and slow internet connections may take a few minutes so please be patient.</p>
<br />
<input type="submit" name="submitvo" value="Upload MP3 File" onclick="this.style.display='none'; document.getElementById('vo_upload_msg').style.display=''" />
<?php
			} else {
				echo '<i>You may have a maximum of 3 Voice Overs on your page.  If you would like to add a new one, please delete one of your existing Voice Overs.</i><br /><br />';
			}
?>
</form>

<hr />

<form style="margin:20px; padding:5px; background-image: url('images/rightmenu_bg.gif');" action="profile.php?u=<?php echo $profileid; ?>&tab=Reel/VO" method="post">
<b>Embed New Video</b><br />
<br />
<?php
			if($num_reels < 3) {
?>
Please upload your video to either <a href="http://www.youtube.com" target="_blank">YouTube</a> or <a href="http://www.vimeo.com" target="_blank">Vimeo</a>.
<br />
<br />
Once you have your video uploaded, please copy the URL (Link) to your video and paste (or type) it in the box below.
<br /><br />
<input type="text" name="videourl" style="width:350px" />
<br />
<br />
<input type="submit" name="submitreel" value="Submit Video" />
<?php
		} else {
			echo '<i>You may have a maximum of 3 Videos on your page.  If you would like to add a new one, please delete one of your existing videos.</i><br /><br />';
		}
?>
</form>
<?php		
			}
			
			echo '</div>';
		
		}
			/* ===================================   End: Reel/VO    =============================== */
		
	}  else if ($tab == 'Messages' && $profileid == $_SESSION['user_id']) {
		if(is_active($_SESSION['user_id'])) {
			$url = 'messages.php';
			ob_end_clean(); // Delete the buffer.
			header("Location: $url");
			exit(); // Quit the script.		
		} else {
			
		
			echo '<div style="line-height:1.8em">';
			echo '<br clear="all" /><div id="AGENCYProfileLeftList" style="width:452px">
				    <div id="AGENCYProfileLeftListTopLeft"></div>
				    <div id="AGENCYProfileLeftListTopCenter" style="width:434px"></div>
				    <div id="AGENCYProfileLeftListTopRight"></div>
				    <div id="AGENCYProfileLeftListInner">
					<b>You may access the Messaging system once your account has been approved</b>
					</div>
				    <div id="AGENCYProfileLeftListBottomLeft"></div>
				    <div id="AGENCYProfileLeftListBottomCenter" style="width:434px"></div>
				    <div id="AGENCYProfileLeftListBottomRight"></div>
				  </div>';
			echo '<br clear="all" /></div>';

		
		
		}
		
		
		
		
			
	} else {
			/* ===================================   Start: WALL    =============================== */
		if(!$loggedin) {
			echo '<div align="center" style="padding:30px"><b>PLEASE LOG IN TO ENJOY VIEWING WALL POSTS.</b></div>';
		} else {
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
	
				echo '<div class="AGENCYWallPrimary"><div class="AGENCYWallThumbnail">';
	
				// get avatar
				$sql2 = "SELECT registration_date FROM agency_profiles WHERE user_id='$posterid'";
				$result2=mysql_query($sql2);
				if($row2 = sql_fetchrow($result2)) {
					$posterfolder = 'talentphotos/' . $posterid . '_' . $row2['registration_date'] . '/';
	
					echo '<a href="profile.php?u=' . $posterid . '"><img src="';
						if(file_exists($posterfolder . 'avatar.jpg')) {
							echo   $posterfolder . 'avatar.jpg';
						} else if(file_exists($posterfolder . 'avatar.gif')) {
							echo   $posterfolder . 'avatar.gif';
						} else {
							echo 'images/friend.gif';
						}
					echo '" /></a>';
				}
	
				echo '</div>'; // close div for thumbnail
				echo '<div class="AGENCYWallPost"><a href="profile.php?u=' . $posterid . '" style="text-decoration:none; font-weight:bold">' . $postername . '</a> ';
				echo $message . '<div class="AGENCYWallDate">' . $postdate;
				if($profileid == $_SESSION['user_id']) {
					echo ' <a href="profile.php?u=' . $profileid . '&amp;tab=Wall&amp;walldel=' . $postid . '" style="padding:3px; font-weight:bold; text-decoration:none" onclick="return confirm(\'Are you sure you wish to delete this post?\')">X</a>';
				}
				
				echo '</div>';
				echo '</div>';
				echo '</div>';
				echo '<br clear="all" />';
	
			 }
			 if(is_active()) {
				 echo '<form style="padding:20px 0" method="post" action="profile.php?tab=Wall&amp;u=' . $profileid. '" name="postonwall">' .
					'<input type="text" name="wallpost" style="width:370px" /> <input type="submit" value="Post" />' .
					'<input type="hidden" value="' . time() . '" name="creation_time"/>' .
					'<input type="hidden" value="' . agency_add_form_key('postonwall') . '" name="form_token"/>';
					if(showcaptcha($_SESSION['user_id'])) {
						echo recaptcha_get_html($publickey, $error);
					}
					echo '</form>';
			} else {
				 echo '<input type="text" name="wallpost" style="width:370px" onClick="alert(\'You must be logged in with an Approved account before you can post on Walls.  Please log in first, or if you do not have an account, it is a great time to join our site!\')" /> <input type="button" value="Post" />';
			}
		}
			/* ===================================   End: WALL    =============================== */
	}

	if($tab == 'Photos') { // if in Photos tab, show second box with thumbnails
?>
</div>

<div id="AGENCYThumbnailBox">
<div class="AGENCYLtBlue2" style="font-size:medium; font-weight:bold">Portfolio</div>
<div style="height:110px; overflow:auto">
<table><tr>
<?php
		// get user code for folder


		// display thumbnails
		if(isset($folder)) { // if the folder was found above
			 $sql = "SELECT * FROM agency_photos WHERE user_id='$profileid' ORDER BY order_id";
			 $result=mysql_query($sql);
			 while($row = sql_fetchrow($result)) {
	 			echo '<td style="padding:3px">';
				echo '<a href="' . $folder . $row['filename'] . '" rel="lightbox[portfolio]"><img src="' . $folder . 'th_' . $row['filename'] . '" height="80" /></a>';
				echo '</td>';
			 }
		}
?>
</tr></table>
</div>
<div style="height:25px">
<?php
		if($_SESSION['user_id'] == $profileid) {
?>
<a href="myimages.php?tab=Sort"><img src="images/manage_photos.gif" width="150" height="26" /></a>
<?php
		}

		if($loggedin) {
			echo '<a href="pdf_portfolio.php?u=' . $profileid. '" style="text-decoration:none; font-weight:bold; float:right" target="_blank">Print/Save</a>';
		} else {
			echo '<a href="javascript:void(0)" onClick="alert(\'Please log in to enjoy this feature.\')" style="text-decoration:none; float:right; font-weight:bold">Print/Save</a>';
		}
?>
</div>

<?php
	} // end photo thumbnail box
?>

		</div>
	</div>





<?php
	@include('includes/profile_right.php');
?>


<br clear="all" />


  <!--  START: friends list -->
<div style="width:650px; margin-top:30px">

	<div class="AGENCYShowcaseLongMain">
		<div class="AGENCYRed AGENCYGeneralTitle" style="height:33px;">Friends <a style="color:#333; font-size:12px; font-weight:normal; float:none; height:auto; margin:0; width:auto;" href="friends.php?u=<?php echo $profileid; ?>">See all friends</a>
        
<?php 
if($_SESSION['user_id'] == $profileid) {
?>
<a style="color:#333; font-size:12px; font-weight:normal; float:right; height:auto; margin:0; width:auto;" href="messages.php?tab=Compose">Contact Friends</a>
<?php
}
?>
</div>

<?php
$sql = "SELECT DISTINCT friend_id FROM agency_friends, forum_users WHERE agency_friends.friend_id=forum_users.user_id AND forum_users.user_type='0' AND agency_friends.user_id='$profileid' AND agency_friends.confirmed='1' LIMIT 24";

 $result=mysql_query($sql);
 if(mysql_num_rows($result) == 0) { // no requests
	echo '<div align="center" style="padding:20px">no friends at this time</div>';
 } else {
	 while($row = sql_fetchrow($result)) {
		 $friendid = $row['friend_id'];
		

		// get avatar
		$sql2 = "SELECT registration_date FROM agency_profiles WHERE user_id='$friendid'";
		$result2=mysql_query($sql2);
		if($row2 = sql_fetchrow($result2)) {
			$posterfolder = 'talentphotos/' . $friendid . '_' . $row2['registration_date'] . '/';
			echo '<a href="profile.php?u=' . $friendid . '"><img src="';
				if(file_exists($posterfolder . 'avatar.jpg')) {
					echo   $posterfolder . 'avatar.jpg';
				} else if(file_exists($posterfolder . 'avatar.gif')) {
					echo   $posterfolder . 'avatar.gif';
				} else {
					echo 'images/friend.gif';
				}
			echo '" /></a>';
		}				
	 }
 }

?>

	</div>
    <br clear="all" />
<?php
$showslash = '';
if($profileid == $_SESSION['user_id'] && agency_account_type() == 'talent') {
	$sql = "SELECT user_id FROM agency_friends WHERE friend_id='$profileid' AND confirmed='0' AND denied='0'";
	$result=mysql_query($sql);
	if(mysql_num_rows($result) > 0) { // no requests
		echo '<a style="color: #0066FF; font-weight:bold; text-decoration:none; font-size:14px" href="friends.php?tab=Friend%20Requests">Friend Requests</a>';
		$showslash = ' / ';
	}
}

if($profileid != $_SESSION['user_id'] && agency_account_type() == 'talent' && is_active()) {
	// check if this person is already a friend or has sent a request
	$sql = "SELECT * FROM agency_friends WHERE user_id='$loggedin' AND friend_id='$profileid'";
	$result=mysql_query($sql);
	if(mysql_num_rows($result) == 0) { // not requested yet
		echo $showslash . '<a style="color: #0066FF; font-weight:bold; text-decoration:none; font-size:14px" href="';
		if(is_active()) {
   			echo 'profile.php?u=' . $profileid . '&amp;request=' . $profilecode . '" ';
		} else {
			echo 'javascript:void(0)" onclick="alert(\'You must be approved before you can make friend requests\')" ';
		}
		echo '>Add as Friend</a>';
	} else if($row = sql_fetchrow($result)) {
		if($row['confirmed'] == '1') {
			echo $showslash . '<span style="color: gray; font-weight:bold; text-decoration:none; font-size:14px">You\'re a friend</span>';
		}
	}
}
?>
	<br clear="all" />
</div>
  <!--  END: friends list -->
 
<?php
echo '<div style="width:650px; margin-top:30px">';
include('includes/showcase_long.php');
echo '</div>';
?>
  
  
  
  
  
  
  
</div>







<div style="clear:both">

</div>









<!-- below are content divs which are not displayed.  Content is inserted into ThickBox popup -->

<?php
	include('includes/profile_popups.php');
} /* else {
	// I think this is what was causing a lot of problems
		$url = 'logout.php';
		ob_end_clean(); // Delete the buffer.
		header("Location: $url");
		exit(); // Quit the script.
} */

?>
</div>
<?php
@include('includes/footer.php');

if($_GET['refresh'] && !$submitmessage) {
	echo '<script type="text/javascript">document.location=\'profile.php?u=' . $profileid . '\';</script>';
}
?>
