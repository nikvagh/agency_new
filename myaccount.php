<?php
session_start(); // for temporary login, a session is needed
@include ('includes/header.php');

unset($loggedin); // avoid XSS
if (!empty($_SESSION['user_id'])) { // check if user is logged in
   $loggedin = $_SESSION['user_id'];
} else { // if not logged in, redirect to login page
  $url = 'login.php';
  ob_end_clean(); // Delete the buffer.
  header("Location: $url");
  exit(); // Quit the script.
}
$userid = $loggedin;
$profileid = $loggedin;


// first get the folder name
$sql = "SELECT * FROM agency_profiles WHERE user_id='$userid'";
$result = mysql_query($sql);
if ($userinfo = sql_fetchrow($result)) { // "$userinfo" array will be available through file, so no need to access database again
   $folder = 'talentphotos/' . $userid . '_' . $userinfo['registration_date'] . '/';
}

if($_SESSION['user_id'] == $profileid && agency_account_type() != 'client') {
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


if (isset($loggedin)) {
 	$submitmessage = '';

	// UPDATE AVATAR
	if (isset($_FILES['avatarfile']) && !empty($_POST['updateavatar'])) { // Handle the form.
		$posterid = $_SESSION['user_id'];
	  // 	$file = $_FILES['avatarfile'];

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


				// determine forum avatar path and filename
				// $forumfile = 'user/'. $config['avatar_path'] . '/' . $config['avatar_salt'] . '_' . $posterid . $filetype;

				// Set a maximum height and width
				$width = 120;

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

				// update avatar settings in database
				$dbfilename =  $posterid . '_' . time() . $filetype;

				if (agency_account_type() == 'client') {
			  	 	echo '<script type="text/javascript">document.location=\'myaccount.php?refresh=' . rand(10000, 99999) . '\';</script>';
				} else {
			  	 	echo '<script type="text/javascript">document.location=\'edit-profile.php?u=' . $loggedin . '&refresh=' . rand(10000, 99999) . '\';</script>';
				}
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


   // ================================================  TALENT  ===========================
   if (agency_account_type() == 'talent') {
      if (isset($_POST['submit'])) { // Handle the form.
	  
	  
	  
	  	// Check for a "Region"
		if(($_POST['location'] == 'Other') && !empty($_POST['otherlocation'])) { 
			$location = escape_data($_POST['otherlocation']);
		} else if(!empty($_POST['location']) && $_POST['location'] != 'Other') {
			$location = escape_data($_POST['location']);
		} else {
			$location = FALSE;
			echo '<p style="color:red">Please enter your primary Region.</p>';
		}		  
	  
	  
		// Check for a first name.
		if (!empty($_POST['firstname'])) {
			$fn = escape_data($_POST['firstname']);
		} else {
			$fn = FALSE;
			$message .=  '<p><font color="red">Please fill the First Name field.</font></p>';
		}

		// Check for a last name.
		$ln = escape_data($_POST['lastname']);


         // Check for an email address.
         if (eregi('^[[:alnum:]][a-z0-9_\.\-]*@[a-z0-9\.\-]+\.[a-z]{2,4}$', stripslashes(trim($_POST['email'])))) {
            $e = escape_data($_POST['email']);
			$sql = "SELECT user_id FROM forum_users WHERE user_email='$e'";
			$result = mysql_query($sql);
			if(mysql_num_rows($result) != 0) {
				if ($row = sql_fetchrow($result)) {
           			if($loggedin != $row['user_id']) {
			   			$submitmessage .= '<p><font color="red">The Email you entered is already being used by another account.  Each account must have a unique email.</font></p>';
					}
				}
			} else {
				$sql = "UPDATE forum_users SET user_email='$e' WHERE user_id='$loggedin'";
				mysql_query($sql);
			}
         } else {
            $submitmessage .= '<p><font color="red">Please enter a valid email address.</font></p>';
         }

		 $phone = $_POST['phone'];
         $city = $_POST['city'];
         $state = $_POST['state'];
		 $zip = $_POST['zip'];
         $country = $_POST['country'];
         $gender = $_POST['gender'];
         $experience = $_POST['experience'];
		 
		 $teleprompter = $_POST['teleprompter'];
		 $comedy = $_POST['comedy'];
		 $hosting = $_POST['hosting'];
		 $tattoos = $_POST['tattoos'];
		 $piercings = $_POST['piercings'];
		 
         // $agegroup = $_POST['agegroup'];
         if(!empty($_POST['Year_dob']) && !empty($_POST['Month_dob']) && !empty($_POST['Day_dob'])) {
         	$birthdate = escape_data($_POST['Year_dob']) . '-' . escape_data($_POST['Month_dob']) . '-' . escape_data($_POST['Day_dob']);
		 } else {
		 	 $birthdate = NULL;
		 }

         $height = (int)($_POST['height_feet'] * 12) + $_POST['height_inches'];
         $height_feet = $_POST['height_feet'];
         $height_inches = $_POST['height_inches'];
         $weight = $_POST['weight'];
         $waist = $_POST['waist'];
         $hair = $_POST['hair'];
         $eyes = $_POST['eyes'];
         $shoe = $_POST['shoe'];


         $bio = $_POST['bio'];
         $skills_language = $_POST['skills_language'];
         $skills_sports_music = $_POST['skills_sports_music'];
         $skills_other = $_POST['skills_other'];
         $ethnicity = $_POST['ethnicity'];

         $sql_ary = array('location' => $location, 'firstname' => request_var('firstname', ''), 'lastname' =>
            request_var('lastname', ''), 'phone' => request_var('phone', ''), 'city' =>
            request_var('city', ''), 'state' => request_var('state', ''), 'zip' => request_var('zip', ''), 'country' =>
            request_var('country', ''), 'gender' => request_var('gender', ''), 'birthdate' => $birthdate,
            'height' => $height, 'weight' => request_var('weight', ''), 'waist' =>
            request_var('waist', ''), 'hair' => request_var('hair', ''), 'eyes' =>
            request_var('eyes', ''), 'shoe' => request_var('shoe', ''), 'bio' => request_var
            ('bio', ''), 'skills_language' => request_var('skills_language', ''), 'skills_other' => escape_data(request_var('skills_other', '')),
            'skills_sports_music' => request_var('skills_sports_music', ''), 'ethnicity' => request_var('ethnicity', ''), 'hosting' => request_var('hosting', ''), 'teleprompter' => request_var('teleprompter', ''), 'comedy' => request_var('comedy', ''), 'tattoos' => request_var('tattoos', ''), 'piercings' => request_var('piercings', '') );

		if (is_admin()) {
			 if (isset($_POST['experience'])) {
				$experience = $_POST['experience'];
				$sql_ary['experience'] = request_var('experience', '');
				// get current experience first to see if email should be sent
				$old_experience = mysql_result(mysql_query("SELECT experience FROM agency_profiles WHERE user_id='$profileid'"), 0, 'experience');
				if($old_experience != $experience) {
					
					$subject = 'Experience Level Changed at The Agency Online';
					$message = '<html><body>
					Dear Talent,<br /><br />
Congratulations! Your talent Experience Level at www.TheAgencyOnline.com has been changed to <b>"' . $experiencearray[$experience] . '"</b> by The Agency\'s staff, based in changes to your portfolio, resume and work. 
<br /><br />
Remember to keep an eye on your messages, and our new castings! 
<br /><br />
Best wishes, 
<br /><br />
<font color="#0796dd"><b>The Agency</b></font><br />
247 w. 38th street, 10th floor<br />
10018, New York City, New York<br />
<a href="http://www.theagencyOnline.com">www.theagencyOnline.com</a><br />
support@theagencyOnline.com
</body></html>';
					$from = 'support@theagencyonline.com';
					$from_name = 'The Agency Online';
					$headers = "From: $from_name <$from>\n";
					$headers .= "Content-type:text/html;charset=utf-8\n"; 
					mail($e, $subject, $message, $headers);
					
					
				}
				@mysql_query("UPDATE agency_profiles SET exp_request=NULL WHERE user_id='$profileid'");
			 }
		}

         if (isset($_POST['suit']) && isset($_POST['suitvariation'])) {
            $suit = $_POST['suit'];
			$suitvariation = $_POST['suitvariation'];
            $sql_ary['suit'] = request_var('suit', '') + request_var('suitvariation', '');
         }
         if (isset($_POST['shirt'])) {
            $shirt = $_POST['shirt'];
            $sql_ary['shirt'] = request_var('shirt', '');
         }
         if (isset($_POST['neck'])) {
            $neck = $_POST['neck'];
            $sql_ary['neck'] = request_var('neck', '');
         }
         if (isset($_POST['sleeve'])) {
            $sleeve = $_POST['sleeve'];
            $sql_ary['sleeve'] = request_var('sleeve', '');
         }
         if (isset($_POST['inseam'])) {
            $inseam = $_POST['inseam'];
            $sql_ary['inseam'] = request_var('inseam', '');
         }
         if (isset($_POST['bust'])) {
            $bust = $_POST['bust'];
            $sql_ary['bust'] = request_var('bust', '');
         }
         if (isset($_POST['cup'])) {
            $cup = $_POST['cup'];
            $sql_ary['cup'] = request_var('cup', '');
         }		 
         if (isset($_POST['hips'])) {
            $hips = $_POST['hips'];
            $sql_ary['hips'] = request_var('hips', '');
         }
         if (isset($_POST['dress'])) {
            $dress = $_POST['dress'];
            $sql_ary['dress'] = request_var('dress', '');
         }

         $sql = 'UPDATE agency_profiles SET ' . sql_build_array('UPDATE', $sql_ary) .
            " WHERE user_id = '$loggedin'";

         if (mysql_query($sql)) {
            $success = true;
            updateCities();
         }

         // ======================  place into categories ===============
         $sql = "DELETE FROM agency_profile_categories WHERE user_id='$loggedin'";
         mysql_query($sql); // delete category settings, and then reset with new ones
         $category = array();
         $category = $_POST['category'];
         if(!empty($category)) {
	         foreach ($category as $cat) {
	            if (!empty($cat)) {
	               $cat = escape_data($cat);
	               $sql = "INSERT INTO agency_profile_categories (user_id, category) VALUES ('$loggedin', '$cat')";
	               mysql_query($sql); // insert category
	            }
	         }
		 }
         // ======================  end: place into categories ===============



         // ======================  place into voices ===============
         $sql = "DELETE FROM agency_profile_voices WHERE user_id='$loggedin'";
         mysql_query($sql); // delete voices settings, and then reset with new ones
         $voice = array();
         $voice = $_POST['voice'];
         if(!empty($voice)) {
	         foreach ($voice as $v) {
	            if (!empty($v)) {
	               $v = escape_data($v);
	               $sql = "INSERT INTO agency_profile_voices (user_id, voice) VALUES ('$loggedin', '$v')";
	               mysql_query($sql); // insert voice
	            }
	         }
		 }
         // ======================  end: place into voices ===============



         // ======================  place into links ===============
         $sql = "DELETE FROM agency_profile_links WHERE user_id='$loggedin'";
         mysql_query($sql); // delete link settings, and then reset with new ones
         foreach($_POST['link'] as $key=>$link) {
	         if (!empty($link)) {
	            $cat = escape_data(remove_http(escape_data($link)));
				$desc = escape_data($_POST['link_desc'][$key]);
	            $sql = "INSERT INTO agency_profile_links (user_id, link, link_desc) VALUES ('$loggedin', '$cat', '$desc')";
	            mysql_query($sql);
	         }
	 	 }
         // ======================  end: place into links ===============

        
		
		 // ======================  place into ethnicities ===============
         $sql = "DELETE FROM agency_profile_ethnicities WHERE user_id='$loggedin'";
         mysql_query($sql); // delete ethnicities settings, and then reset with new ones
         $ethnicities = array();
         $ethnicities = $_POST['ethnicities'];
         foreach ($ethnicities as $eth) {
            if (!empty($eth)) {
               $eth = escape_data($eth);
               $sql = "INSERT INTO agency_profile_ethnicities (user_id, ethnicity) VALUES ('$loggedin', '$eth')";
               mysql_query($sql); // insert ethnicities
            }
         }
         // ======================  end: place into ethnicities ===============



         // ======================  place into unions ===============
		 $unionset = false; // if no unions are selected, set as non-union
         $sql = "DELETE FROM agency_profile_unions WHERE user_id='$loggedin'";
         mysql_query($sql); // delete unions settings, and then reset with new ones
         $unions = array();
         $unions = $_POST['unions'];
         foreach ($unions as $un) {
            if (!empty($un)) {
               $un = escape_data($un);
               $sql = "INSERT INTO agency_profile_unions (user_id, union_name) VALUES ('$loggedin', '$un')";
               mysql_query($sql); // insert union
			   $unionset = true;
            }
         }
		 
		 if(in_array('SAG-Eligible', $unions) && !in_array('Non-Union', $unions)) {
			$sql = "INSERT INTO agency_profile_unions (user_id, union_name) VALUES ('$loggedin', 'Non-Union')";
		 	mysql_query($sql); // insert union
		 }
		 
		 if(!$unionset) {
		   $sql = "INSERT INTO agency_profile_unions (user_id, union_name) VALUES ('$loggedin', 'Non-Union')";
		   mysql_query($sql); // insert union
		 }
         // ======================  end: place into unions ===============


         // ======================  PRIVACY SETTINGS ===============
         $sql = "DELETE FROM agency_privacy WHERE user_id='$loggedin'";
         mysql_query($sql); // delete privacy settings, and then reset with new ones
         $privacy = array();
         $privacy = $_POST['privacy'];
         foreach ($privacy as $what=>$who) {
            if (!empty($what) && !empty($who)) {
               $sql = "INSERT INTO agency_privacy (user_id, what, who) VALUES ('$loggedin', '$what', '$who')";
               mysql_query($sql);
            }
         }
         // ======================  end: privacy settings ===============


         if ($success && empty($submitmessage)) { // If required fields.
            // $submitmessage .= 'Your Settings Have Been Updated.';
			$url = 'edit-profile.php?u=' . $loggedin . '&accountupdate=true';
			ob_end_clean(); // Delete the buffer.
			header("Location: $url");
			exit(); // Quit the script.
         } else { // If one of the data tests failed.
            $submitmessage .= '<p><font color="red">Please try again.</font></p>';
         }
      }


     $query = "SELECT * FROM forum_users, agency_profiles WHERE agency_profiles.user_id=forum_users.user_id AND forum_users.user_id='$userid'"; // check to see if name already used.
     $result = @mysql_query($query);
     if ($row = @mysql_fetch_array($result, mysql_ASSOC)) {
		 $location = $row['location'];
        $firstname = $row['firstname'];
        $lastname = $row['lastname'];
        $phone = $row['phone'];
        $city = $row['city'];
        $state = $row['state'];
		$zip = $row['zip'];
        $country = $row['country'];
        $gender = $row['gender'];
        // $agegroup = $row['agegroup'];
        $birthdate = $row['birthdate'];
        $experience = $row['experience'];
		$exp_request = $row['exp_request'];

        // get stats:
        $height_feet = floor($row['height'] / 12);
        $height_inches = $row['height'] % 12;
        $weight = $row['weight'];
        $waist = $row['waist'];
        $hair = $row['hair'];
        $eyes = $row['eyes'];
        $shoe = $row['shoe'];


        $suit = $row['suit'];
		$suit = floor($row['suit']);
		$suitvariation = (string) ($row['suit'] - $suit);
        $shirt = $row['shirt'];
        $neck = $row['neck'];
		$sleeve = $row['sleeve'];
        $inseam = $row['inseam'];
        $bust = $row['bust'];
		$cup = $row['cup'];
        $hips = $row['hips'];
        $dress = $row['dress'];
		
		$teleprompter = $row['teleprompter'];
		$comedy = $row['comedy'];
		$hosting = $row['hosting'];
		$tattoos = $row['tattoos'];
		$piercings = $row['piercings'];

        $bio = $row['bio'];
        $skills_language = $row['skills_language'];
		$skills_sports_music = $row['skills_sports_music'];
		$skills_other = $row['skills_other'];
        $ethnicity = $row['ethnicity'];

		// get email:
         $sql = "SELECT user_email FROM forum_users WHERE user_id='$loggedin'";
         $result = mysql_query($sql);
         if ($row = sql_fetchrow($result)) {
           $email = $row['user_email'];
        }

        //============== get categories ====================
        $sql = "SELECT category FROM agency_profile_categories WHERE user_id='$loggedin'";
        $result = mysql_query($sql);
        $category = array();
        while ($row = sql_fetchrow($result)) {
           $category[] = $row['category'];
        }
        unset($result);
        //============== end: get categories ====================
		
		
        //============== get voice ====================
        $sql = "SELECT voice FROM agency_profile_voices WHERE user_id='$loggedin'";
        $result = mysql_query($sql);
        $voice = array();
        while ($row = sql_fetchrow($result)) {
           $voice[] = $row['voice'];
        }
        unset($result);
        //============== end: get voice ====================		



        //============== get links ====================
        $sql = "SELECT link, link_desc FROM agency_profile_links WHERE user_id='$loggedin'";
        $result = mysql_query($sql);
		$link = array();
        for($i = 1; $row = sql_fetchrow($result); $i++) {
           $link[$i] = $row['link'];
		   $link_desc[$i] = $row['link_desc'];
        }
        unset($result);
        //============== end: get links ====================



        //============== get ethnicities ====================
        $sql = "SELECT ethnicity FROM agency_profile_ethnicities WHERE user_id='$loggedin'";
        $result = mysql_query($sql);
        $ethnicities = array();
        while ($row = sql_fetchrow($result)) {
           $ethnicities[] = $row['ethnicity'];
        }
        unset($result);
        //============== end: get ethnicities ====================
		
		

        //============== get unions ====================
        $sql = "SELECT union_name FROM agency_profile_unions WHERE user_id='$loggedin'";
        $result = mysql_query($sql);
        $unions = array();
        while ($row = sql_fetchrow($result)) {
           $unions[] = $row['union_name'];
        }
        unset($result);
        //============== end: get unions ====================
     }

      if(!empty($submitmessage)) {
		echo '<div class="AGENCYsubmitmessage">' . $submitmessage . '</div>';
	  }
	if (!is_active()) {
		echo '<div class="AGENCYsubmitmessage" style="text-align:left">';
		echo mysql_result(mysql_query("SELECT varvalue FROM agency_vars WHERE varname='waiting'"), 0, 'varvalue');
		echo '</div>';
	}

     // @include ('includes/profile_left.php');

     include ('./forms/myaccount_talent.php'); // talent account edit form


      // ===================================================  AGENT   =====================================


   } else
      if (agency_account_type() == 'client') {
         if (isset($_POST['submit']) && isset($_POST['clientform'])) { // Handle the form.
            // Check for a first name.
			if (!empty($_POST['firstname'])) {
				$firstname = escape_data($_POST['firstname']);
			} else {
				$firstname = FALSE;
				$message .=  '<p><font color="red">Please fill the First Name field.</font></p>';
			}

			// Check for a last name.
			$lastname = escape_data($_POST['lastname']);


			 // Check for an email address.
			 if (eregi('^[[:alnum:]][a-z0-9_\.\-]*@[a-z0-9\.\-]+\.[a-z]{2,4}$', stripslashes(trim($_POST['email'])))) {
				$email = $_POST['email']; // for form
				$e = escape_data($_POST['email']);
				$sql = "SELECT user_id FROM forum_users WHERE user_email='$e'";
				$result = mysql_query($sql);
				if(mysql_num_rows($result) != 0) {
					if ($row = sql_fetchrow($result)) {
						if($loggedin != $row['user_id']) {
							$submitmessage .= '<p><font color="red">The Email you entered is already being used by another account.  Each account must have a unique email.</font></p>';
						}
					}
				} else {
					$sql = "UPDATE forum_users SET user_email='$e' WHERE user_id='$loggedin'";
					mysql_query($sql);
				}
			 } else {
				$submitmessage .= '<p><font color="red">Please enter a valid email address.</font></p>';
			 }

            // Check for a phone number
			$phone = escape_data($_POST['phone']);

            // Check for a company
            if (trim($_POST['company'])) {
               $company = $_POST['company'];
               $company_confirm = true;
            } else {
               $company_confirm = false;
               $submitmessage .= '<p><font color="red">Please enter your company name.</font></p>';
            }

            // Check for a profession
            if (trim($_POST['profession'])) {
               $profession = $_POST['profession'];
               $profession_confirm = true;
            } else {
               $profession_confirm = false;
               $submitmessage .= '<p><font color="red">Please enter your profession.</font></p>';
            }

            $city = $_POST['city'];
            $state = $_POST['state'];
            $country = $_POST['country'];
            $link = $_POST['link'];
            $link = remove_http(escape_data($link));
            $note = $_POST['note'];

            $sql_ary = array('firstname' => request_var('firstname', ''), 'lastname' =>
               request_var('lastname', ''), 'phone' => request_var('phone', ''), 'city' =>
               request_var('city', ''), 'state' => request_var('state', ''), 'country' =>
               request_var('country', ''), 'client_company' => request_var('company', ''), 'client_profession' =>
               request_var('profession', ''), 'client_link' => request_var('link', ''), 'client_note' =>
               request_var('note', ''),);


            $sql = 'UPDATE agency_profiles SET ' . sql_build_array('UPDATE', $sql_ary) .
               " WHERE user_id = '$loggedin'";

            if (mysql_query($sql)) {
               $success = true;
            }

            // ======================  place into castings ===============
            $sql = "DELETE FROM agency_profile_castings WHERE user_id='$loggedin'";
            mysql_query($sql); // delete castings settings, and then reset with new ones
            $castings = array();
            $castings = $_POST['castings'];
            foreach ($castings as $ca) {
               if (!empty($ca)) {
                  $sql = "INSERT INTO agency_profile_castings (user_id, casting_type) VALUES ('$loggedin', '$ca')";
                  mysql_query($sql); // insert casting type
               }
            }
            // ======================  end: place into castings ===============


            if ($success && empty($submitmessage)) { // If required fields.
               $submitmessage .= 'Your Settings Have Been Updated.';
            } else { // If one of the data tests failed.
               $submitmessage .= '<p><font color="red">Please try again.</font></p>';
            }


         } else { // End of the main Submit conditional.
            $query = "SELECT * FROM forum_users, agency_profiles WHERE agency_profiles.user_id=forum_users.user_id AND forum_users.user_id='$userid'"; // check to see if name already used.
            $result = @mysql_query($query);
            if ($row = @mysql_fetch_array($result, mysql_ASSOC)) { // If there are projects.
               $email = $row['user_email'];
               $firstname = $row['firstname'];
               $lastname = $row['lastname'];
               $phone = $row['phone'];
               $city = $row['city'];
               $state = $row['state'];
               $country = $row['country'];
               $company = $row['client_company'];
               $profession = $row['client_profession'];
               $link = $row['client_link'];
               $note = $row['client_note'];

               //============== get castings ====================
               $sql = "SELECT casting_type FROM agency_profile_castings WHERE user_id='$loggedin'";
               $result = mysql_query($sql);
               $castings = array();
               while ($row = sql_fetchrow($result)) {
                  $castings[] = $row['casting_type'];
               }
               unset($result);
               //============== end: get castings ====================
            }
         }

		  if(!empty($submitmessage)) {
			echo '<div class="AGENCYsubmitmessage">' . $submitmessage . '</div>';
		  }

		if (!is_active()) {
			echo '<div class="AGENCYsubmitmessage" style="text-align:left">';
			if(mysql_result(mysql_query("SELECT COUNT(*) as 'Num' FROM agency_profile_castings WHERE user_id='$loggedin'"),0) == 0) { // if myaccount form has never been submitted
				echo mysql_result(mysql_query("SELECT varvalue FROM agency_vars WHERE varname='waitingClient'"), 0, 'varvalue');
			} else {
				echo mysql_result(mysql_query("SELECT varvalue FROM agency_vars WHERE varname='waitingClient2'"), 0, 'varvalue');
			}
			echo '</div>';
		}
		  
		  
         include ('./forms/myaccount_agent.php'); // agent account edit form
      } else {
 		$url = 'home.php';
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
?>
<!-- below are content divs which are not displayed.  Content is inserted into ThickBox popup -->
<?php
	include('includes/profile_popups.php');
?>

<div id="ppicform" class="AGENCYfillcontent">
	<br />Please upload a photo to be used as your profile picture (less than 1MB)<br />
	<form enctype="multipart/form-data" method="post" action="myaccount.php?refresh=<?php echo rand(10000, 99999); ?>" name="updateavatar">
	<div style="border:1px solid black; width:400px; margin:20px; padding:10px">
		<b>Upload New Image:</b><br /> <br />
		<input type="hidden" name="MAX_FILE_SIZE" value="1000000" />
		<input type="file" name="avatarfile" />
		<br />
		<input type="hidden" value="<?php echo time(); ?>" name="creation_time"/>
        <input type="hidden" value="true" name="updateavatar"/>
		<input type="hidden" value="<?php echo agency_add_form_key('updateavatar'); ?>" name="form_token"/>
		<br />
			<input type="submit" name="submit" value="Submit" />
		</p>
	</div>
	</form>
</div>

<div id="expchange" class="AGENCYfillcontent">
	<br />You may submit a request to change your status one time per month.  Once submitted, our review team will determine whether you qualify for the talent level you request, and update your profile accordingly.  Be sure you put your best work up as well as fill in all information about yourself as your profile will be reviewed carefully.  Thank you for helping us keep our information accurate and up to date.<br />
<br /><br />
	<div align="center">
<?php
foreach($experiencearray as $key=>$value) {
	if($experience != $key) {
		echo '<a href="javascript:void(0)" onClick="loaddiv(\'popupcontent\', false, \'ajax/exp_request.php?exp=' . $key . '\')"" class="AGENCY_graybutton">Please change my account to ' . $value . '</a><br /><br />';
	}
}
?>
	</div>
</div>
<?php
@include ('includes/footer.php');
?>

<script type="text/javascript">
var USregions = '<select name="state" id="state">';
  <?php
foreach($stateList['US'] as $abr=>$st) { ?>
	USregions += '<option value="<?php echo $st; ?>"';
	<?php
	if(isset($state)) {
		if($state == $st) { ?>
			USregions += ' selected';
			<?php
		}
	}
	?>
	USregions +=  '><?php echo $st; ?></option>';
<?php } ?>
USregions += '</select>';

function changecountry(country) {
	var obj = document.getElementById('statediv');
	if(country == 'United States') {
		obj.innerHTML = USregions;
	} else {
		obj.innerHTML = '<input type="text" name="state" size="40" maxlength="40" value="<?php if (!empty($state)) echo $state; ?>" />';
	}
}


function checkform() {
	if(!document.getElementById('firstname').value) {
		alert('Please enter your First Name');
		changetab('General');
		return false;
	} else if(!document.getElementById('email').value) {
		alert('Please enter your Email Address');
		changetab('General');
		return false;
	/* } else if(!document.getElementById('gender').value) {
		alert('Please enter your Gender');
		changetab('General');
		return false; */
	} else if(!document.getElementById('location').value) {
		alert('Please enter your Region');
		changetab('General');
		return false;
	} else if(!document.getElementById('city').value) {
		alert('Please enter your City');
		changetab('General');
		return false;
	} else if(!document.getElementById('country').value) {
		alert('Please enter your Country');
		changetab('General');
		return false;
	} else if(!document.getElementById('Year_dob').value || !document.getElementById('Month_dob').value || !document.getElementById('Day_dob').value) {
		alert('Please enter your Birthdate.  Your actual birthdate or age will never be visible to anyone on the site, but you have to enter a birthday to make you searchable for castings.');
		changetab('General');
		return false;
	} else {	
		// check ethnicity
		var count = 0;
		var valid = false;
		for (var j=0; document.forms['myaccount'].elements['ethnicities['+j+']']; j++) {
			if (document.forms['myaccount'].elements['ethnicities['+j+']'].checked) {
				valid = true;
			}
		}	
		if(!valid) {
			alert('Please enter your Ethnicity.');
			changetab('General');
			return false;
		}
		
		/* 
		// UNIONS DEFAULT TO NON-UNION IF NOTHING IS CHECKED, NO NEED FOR VERIFICATION
		// check unions
		var count = 0;
		var valid = false;
		var j=0; 
		while (document.forms['myaccount'].elements['unions['+j+']']) {
			if (document.forms['myaccount'].elements['unions['+j+']'].checked) {
				valid = true;
			}
			j++;
		}	
		// check for last, text field
		j=j+1;
		if(document.forms['myaccount'].elements['unions['+j+']'].value) {
			valid = true;
		}
		
		if(!valid) {
			alert('Please enter your Union(s).  If you are not a member of a Union, select "Non-Union"');
			changetab('Experience');
			return false;
		}	
		*/	

		return true;
	}
}
</script>
<?php
if (isset($_GET['tab'])) {
	if ($_GET['tab'] == 'privacy') echo '<script type="text/javascript" defer="defer">changetab(\'Privacy Settings\');</script>';
	if ($_GET['tab'] == 'stats') echo '<script type="text/javascript" defer="defer">changetab(\'Measurements\');</script>';
	if ($_GET['tab'] == 'links') echo '<script type="text/javascript" defer="defer">changetab(\'Links\');</script>';
	if ($_GET['tab'] == 'experience') echo '<script type="text/javascript" defer="defer">changetab(\'Experience\');</script>';
	if ($_GET['tab'] == 'bio') echo '<script type="text/javascript" defer="defer">changetab(\'Bio\');</script>';
}
?>