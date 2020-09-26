<style>
    .AGENCYsubmitmessage {
    margin: 80px 0px 0px 0px;
}
</style>
<?php
//session_start(); // for temporary login, a session is needed
@include ('sidebar.php');
// include('forms/definitions.php');

if($loggedin) { // only clients can access this page

	//$loggedin = (int) $_SESSION['user_id'];
	$submitmessage = '';
	$location_casting = NULL; // initialize to avoid error/notice
	$location_shoot = NULL;
	//$_GET['castingid'] = 12793;

	// if casting ID is sent for edit, make sure user has permission to edit it.
	if(!empty($_GET['castingid'])) { // if there is a casting id, the we're editing this.  retrieve information from database
		$castingid = escape_data((INT) $_GET['castingid']);
		$query = "SELECT * FROM agency_castings WHERE casting_id='$castingid' AND posted_by='$loggedin'";
		$result = @mysql_query($query);
		if (@mysql_num_rows($result) == 0) { // If user does not access to project
			unset($_GET['castingid']);
		}
	}


	if (isset($_POST['submit'])) { // Handle the form.
		
		// delete files
		if($_POST['deleteattachment'] && !empty($_GET['castingid'])) { // delete casting attachment
			$castingid = escape_data((INT) $_GET['castingid']);
			$filename = 'attachments/castings/' . $castingid . '/' . mysql_result(mysql_query("SELECT attachment FROM agency_castings WHERE casting_id='$castingid'"), 0, 'attachment');
			unlink($filename);
			mysql_query("UPDATE agency_castings SET attachment='NULL' WHERE casting_id='$castingid' LIMIT 1");			
		}
		
		if($_POST['attach_role_del'] && !empty($_GET['castingid'])) { // delete casting attachment
			$castingid = escape_data((INT) $_GET['castingid']);
			foreach($_POST['attach_role_del'] as $roleid => $value) {
				$filename = 'attachments/roles/' . $roleid . '/' . mysql_result(mysql_query("SELECT attachment FROM agency_castings_roles WHERE role_id='$roleid'"), 0, 'attachment');
				unlink($filename);
				mysql_query("UPDATE agency_castings_roles SET attachment=NULL WHERE role_id='$roleid' LIMIT 1");	
			}
		}		
		
	
		if(true) {
		    $casting_director = $_POST['casting_director'];
		    $artist = $_POST['artist'];
		    $company = $_POST['company'];
		    $job_title = $_POST['job_title'];
		    
		    $rate_day = $_POST['rate_day'];
		    $rate_usage = $_POST['rate_usage'];
		    $shoot_date = $_POST['shoot_date'];
		    $casting_date = $_POST['casting_date'];

		    $usage_type = $_POST['usage_type'];
		    $usage_time = $_POST['usage_time'];
		    $usage_location = $_POST['usage_location'];
		    $shoot_date = $_POST['shoot_date'];
		    $notes = $_POST['notes'];
			if(!empty($_POST['clientalert'])) {
				$clientalert = (int) $_POST['clientalert'];
			} else {
				$clientalert = NULL;
			}
			
			// get locations
			$location_casting = escape_data($_POST['location_casting']);
			if($location_casting == 'Other') {
				$location_casting = escape_data($_POST['location_casting_other']);
			}
			
			$location_shoot = escape_data($_POST['location_shoot']);
			if($location_shoot == 'Other') {
				$location_shoot = escape_data($_POST['location_shoot_other']);
			}
						
			
			// first, if admin and post date is set
			if(is_admin() && !empty($_POST['Year_start']) && !empty($_POST['Month_start']) && !empty($_POST['Day_start']) && !empty($_POST['Hour_start']) && !empty($_POST['Minute_start']) && !empty($_GET['castingid'])) { //  && !empty($_GET['castingid'])
				if ($_POST['AMPM_start'] == 'PM') {
					if ($_POST['Hour_start'] != 12) { // Takes care of 12 noon
						$H_S = $_POST['Hour_start'] + 12;
					} else {
						$H_S = $_POST['Hour_start'];
					}
				} else {
					if ($_POST['Hour_start'] == 12) { // if it's 12 AM then set to 00
						$H_S = "00";
					} else {
						$H_S = $_POST['Hour_start'];
					}
				}
		
				$date = escape_data($_POST['Year_start'] . '-' . $_POST['Month_start'] . '-' . $_POST['Day_start'] . ' ' .	$H_S . ':' . $_POST['Minute_start'] . ':00');			   
			  
			   
			    $sql_ary = array('casting_director' => request_var('casting_director', ''), 'artist' =>
			       request_var('artist', ''), 'company' => request_var('company', ''), 'job_title' =>
			       request_var('job_title', ''), 'location_shoot' => $location_shoot, 'location_casting' => $location_casting, 'rate_day' =>
			       request_var('rate_day', ''), 'rate_usage' => request_var('rate_usage', ''), 'usage_type' => request_var('usage_type', ''), 'usage_time' =>
			       request_var('usage_time', ''), 'usage_location' => request_var('usage_location', ''), 'shoot_date' =>
			       request_var('shoot_date', ''), 'casting_date' => request_var('casting_date', ''), 'notes' =>
				   request_var('notes', ''), 'post_date' => $date, );
			} else if(!empty($_GET['castingid'])) { // do not update "posted_by"
			    $sql_ary = array('casting_director' => request_var('casting_director', ''), 'artist' =>
			       request_var('artist', ''), 'company' => request_var('company', ''), 'job_title' =>
			       request_var('job_title', ''), 'location_shoot' => $location_shoot, 'location_casting' => $location_casting, 'rate_day' =>
			       request_var('rate_day', ''), 'rate_usage' => request_var('rate_usage', ''), 'usage_type' => request_var('usage_type', ''), 'usage_time' =>
			       request_var('usage_time', ''), 'usage_location' => request_var('usage_location', ''), 'shoot_date' =>
			       request_var('shoot_date', ''), 'casting_date' => request_var('casting_date', ''), 'notes' =>
				   request_var('notes', ''), );
			} else {
			    $sql_ary = array('posted_by' => $loggedin,
	    			'casting_director' => request_var('casting_director', ''), 'artist' =>
			       request_var('artist', ''), 'company' => request_var('company', ''), 'job_title' =>
			       request_var('job_title', ''), 'location_shoot' => $location_shoot, 'location_casting' => $location_casting, 'rate_day' =>
			       request_var('rate_day', ''), 'rate_usage' => request_var('rate_usage', ''), 'usage_type' => request_var('usage_type', ''), 'usage_time' =>
			       request_var('usage_time', ''), 'usage_location' => request_var('usage_location', ''), 'shoot_date' =>
			       request_var('shoot_date', ''), 'casting_date' => request_var('casting_date', ''), 'notes' =>
				   request_var('notes', ''), 'live' => 0);
				   
				   if(is_admin() && !empty($_POST['Year_start']) && !empty($_POST['Month_start']) && !empty($_POST['Day_start']) && !empty($_POST['Hour_start']) && !empty($_POST['Minute_start'])) { //  && !empty($_GET['castingid'])
						if ($_POST['AMPM_start'] == 'PM') {
							if ($_POST['Hour_start'] != 12) { // Takes care of 12 noon
								$H_S = $_POST['Hour_start'] + 12;
							} else {
								$H_S = $_POST['Hour_start'];
							}
						} else {
							if ($_POST['Hour_start'] == 12) { // if it's 12 AM then set to 00
								$H_S = "00";
							} else {
								$H_S = $_POST['Hour_start'];
							}
						}
				
						$date = escape_data($_POST['Year_start'] . '-' . $_POST['Month_start'] . '-' . $_POST['Day_start'] . ' ' .	$H_S . ':' . $_POST['Minute_start'] . ':00');	
				   
				   		$sql_ary['post_date'] = $date;
				   }
				   
				   
			}

			$verified = true; // flag to check required values

			$job_type = array();
			$union_status = array();
			$names = array();
		   	$roles = array();
			$roleids = array();

			if(empty($_POST['job_type'])) {
				$verified = false;
				$submitmessage .= 'Please check at least one Job Type box<br />';
				$job_type = $_POST['job_type'];
			} else {
				$job_type = $_POST['job_type'];
			}

			if(empty($_POST['union_status'])) {
				$verified = false;
				$submitmessage .= 'Please check at least one Union Status box<br />';
				$union_status = $_POST['union_status'];
			} else {
				$union_status = $_POST['union_status'];
			}

			if(empty($_POST['name'][0])) {
				$verified = false;
				$submitmessage .= 'Please enter at least one Role<br />';
				$roleRed = true;
				$names = $_POST['name'];
			} else {
				$names = $_POST['name'];
			}
			
			$ageLower = array();
			$ageLower = $_POST['ageLower'];
			$ageUpper = array();
			$ageUpper = $_POST['ageUpper'];
			$gender = array();
			$gender = $_POST['gender'];
			$ethnicity = array();
			$ethnicity = $_POST['ethnicity'];

			if(!empty($_POST['role'])) {
				$roles = $_POST['role'];
				$roleids = $_POST['roleid'];
			}

			if(empty($sql_ary['casting_director'])) {
				$verified = false;
				$submitmessage .= 'Please enter the Casting Director<br />';
			}
			if(empty($sql_ary['job_title'])) {
				$verified = false;
				$submitmessage .= 'Please enter the Project Name<br />';
			}

			if(empty($sql_ary['rate_day'])) {
				$verified = false;
				$submitmessage .= 'Please enter the Day Rate<br />';
			}
			if(empty($sql_ary['rate_usage'])) {
				$verified = false;
				$submitmessage .= 'Please enter the Usage Rate<br />';
			}
			if(empty($sql_ary['casting_date'])) {
				$verified = false;
				$submitmessage .= 'Please enter the Casting Date<br />';
			}
			if(empty($sql_ary['shoot_date'])) {
				$verified = false;
				$submitmessage .= 'Please enter the Shoot Date<br />';
			}
			
			if(empty($sql_ary['location_shoot'])) {
				$verified = false;
				$submitmessage .= 'Please enter the Shoot Location<br />';
			}
			if(empty($sql_ary['location_casting'])) {
				$verified = false;
				$submitmessage .= 'Please enter the Casting Location<br />';
			}			
			

			if(true) { // no need for verification here, there will be a redirect if $verified=false.  This is so images upload
				$badfile = false; // flag for if a message has to be displayed for one of the files not uploading.
				
				if(!empty($_GET['castingid'])) {
					$castingid = escape_data((INT) $_GET['castingid']);
					$sql = 'UPDATE agency_castings SET ' . sql_build_array('UPDATE', $sql_ary) . " WHERE casting_id='$castingid'";
				} else {
			    	$sql = 'INSERT INTO agency_castings' . sql_build_array('INSERT', $sql_ary);
				}

			    if (mysql_query($sql)) {
			       //$success = true;
			       if(empty($_GET['castingid'])) {
				   		$castingid = mysql_insert_id();
				   }
				   
					
					if(!empty($_POST['clientalert'])) { // this is the alert to send an email when a certain number of submissions has been reached
						$clientalert = (int) $_POST['clientalert'];
						if($clientalert > 0) {
							$sql = "UPDATE agency_castings SET clientalert='$clientalert' WHERE casting_id='$castingid'";
							mysql_query($sql);
						}
					}
			
					// ======================  place into job types ===============
					$sql = "DELETE FROM agency_castings_jobtype WHERE casting_id='$castingid'";
					mysql_query($sql); // delete category settings, and then reset with new ones
					foreach ($job_type as $jt) {
						if (!empty($jt)) {
						   $jt = escape_data($jt);
						   $sql = "INSERT INTO agency_castings_jobtype (casting_id, jobtype) VALUES ('$castingid', '$jt')";
						   mysql_query($sql); // insert jobtype
						}
					}
					// ======================  end: place into job types ===============

					// ======================  place into unions ===============
					$sql = "DELETE FROM agency_castings_unions WHERE casting_id='$castingid'";
					mysql_query($sql); // delete category settings, and then reset with new ones
					foreach ($union_status as $us) {
						if (!empty($us)) {
						   $us = escape_data($us);
						   $sql = "INSERT INTO agency_castings_unions (casting_id, union_name) VALUES ('$castingid', '$us')";
						   mysql_query($sql); // insert union
						}
					}
					// ======================  end: place into unions ===============


					// ======================  place into roles ===============
					// $sql = "DELETE FROM agency_castings_roles WHERE casting_id='$castingid'";
					// mysql_query($sql); // delete category settings, and then reset with new ones
					
					$allowedExtensions = array("jpg","gif","jpeg","pdf"); 


					// delete data that will be replaced for genders and ethnicities
					$sql = "DELETE FROM agency_castings_roles_vars WHERE casting_id='$castingid'";
					mysql_query($sql);

		   			$i = 0;
					
					foreach ($names as $nm) {
						$ro = escape_data($roles[$i]);
						$rid = escape_data($roleids[$i]);
						$ageL = escape_data($ageLower[$i]);
						$ageU = escape_data($ageUpper[$i]);

						if (!empty($nm)) {
						   $nm = escape_data($nm);
							if(empty($rid)) {
						 	  $sql = "INSERT INTO agency_castings_roles (casting_id, name, description, age_lower, age_upper) VALUES ('$castingid', '$nm', '$ro', '$ageL', '$ageU')";
							  mysql_query($sql); // insert role
							  $rid = mysql_insert_id();
						  	} else {
						 	  $sql = "UPDATE agency_castings_roles SET description='$ro', name='$nm', age_lower='$ageL', age_upper='$ageU' WHERE role_id='$rid' AND casting_id='$castingid'";
							  mysql_query($sql); // insert role
							}
						   
							
							// GENDERS:
							if(!empty($_POST['gender'][$i])) {
								foreach($_POST['gender'][$i] as $key=>$rg) {
									$sql = "INSERT INTO agency_castings_roles_vars (casting_id, role_id, var_type, var_value) VALUES ('$castingid', '$rid', 'gender', '$rg')";
									mysql_query($sql);
								}
							} else { // default all genders if none set
								$sql = "INSERT INTO agency_castings_roles_vars (casting_id, role_id, var_type, var_value) VALUES ('$castingid', '$rid', 'gender', 'Male'), ('$castingid', '$rid', 'gender', 'Female'), ('$castingid', '$rid', 'gender', 'Other')";
								
								mysql_query($sql);
							
							}
							
							// ETHNICITIES:
							if(empty($_POST['ethnicity'][$i])) {
								// $verified = false;
								// $submitmessage .= 'Oh No!  One of your roles has no ethnicity!<br />';
								// INSERT ALL ETHNICITIES
								foreach($ethnicityarray as $key=>$re) {
									$sql = "INSERT INTO agency_castings_roles_vars (casting_id, role_id, var_type, var_value) VALUES ('$castingid', '$rid', 'ethnicity', '$re')";
									mysql_query($sql);
								}
							} else {
								foreach($_POST['ethnicity'][$i] as $key=>$re) {
									$sql = "INSERT INTO agency_castings_roles_vars (casting_id, role_id, var_type, var_value) VALUES ('$castingid', '$rid', 'ethnicity', '$re')";
									mysql_query($sql);
								}	
							}
							
							
							// echo $_FILES['rolefile']['name'][0];
							// exit();
							
							// doc, pdf or .docx
							if($_FILES['rolefile']['name'][$i]) {
								if (in_array(end(explode(".", strtolower($_FILES['rolefile']['name'][$i]))), $allowedExtensions)) {
	
									$filename = preg_replace("/[^a-zA-Z0-9.]/", "", $_FILES['rolefile']['name'][$i]); // removes non-alphanumeric characters
							
									$folder = 'attachments/roles/' . $rid;
									if(!file_exists($folder)) { // if folder doesn't exist yet, create it
										mkdir($folder);
										chmod($folder,0777);
									}	
									
									// remove existing file
									$oldfile = mysql_result(mysql_query("SELECT attachment FROM agency_castings_roles WHERE role_id='$rid'"), 0, 'attachment');				
									if(!empty($oldfile)) {
										unlink($folder . '/' . $oldfile);
									}
									
									// put new file in folder
									$newfile = $folder . '/' . $filename;
									if (move_uploaded_file($_FILES['rolefile']['tmp_name'][$i], "$newfile")) {
										mysql_query("UPDATE agency_castings_roles SET attachment='$filename' WHERE role_id='$rid'");
									} else {
										$badfile = true;
									}
							
								} else {
									$badfile = true;
								}						   
							}
						   
						   
						} else if(!empty($rid)) { // if name is empty and there's a roleid, then delete the role
						 	 $sql = "DELETE FROM agency_castings_roles WHERE role_id='$rid' AND casting_id='$castingid'";
							 mysql_query($sql); 
						}else{
						    
						}
						
						$i++;
					}
					// ======================  end: place into roles ===============
 					
					
					// =================== PROCESS ATTACHMENTS ===================
					
					// check for Casting attachment
					
					if (!empty($_FILES['attach_casting']['name'])) {
						// doc, pdf or .docx
					 	$allowedExtensions = array("txt","docx","doc","rtf","pdf"); 
						if (in_array(end(explode(".", strtolower($_FILES['attach_casting']['name']))), $allowedExtensions)) {
							$filename = preg_replace("/[^a-zA-Z0-9.]/", "", $_FILES['attach_casting']['name']); // removes non-alphanumeric characters
							$folder = 'attachments/castings/' . $castingid;
														
							if(!file_exists($folder)) { // if folder doesn't exist yet, create it
					  			mkdir($folder);
					  			chmod($folder,0777);
							}	
							
							// remove existing file
							$oldfile = mysql_result(mysql_query("SELECT attachment FROM agency_castings WHERE casting_id='$castingid'"), 0, 'attachment');				
							if(!empty($oldfile)) {
								unlink($folder . '/' . $oldfile);
							}
							
							// put new file in folder
							$newfile = $folder . '/' . $filename;
							
							if (move_uploaded_file($_FILES['attach_casting']['tmp_name'], "$newfile")) {
								mysql_query("UPDATE agency_castings SET attachment='$filename' WHERE casting_id='$castingid'");
							} else {
								$badfile = true;
							}						
					
						} else {
							$badfile = true;
						}
					}
					
					// ============================================================
					
					
					
					
					
					
					
					
					
					
					// Image Processing:
					
					/*
					$folder = 'images/castings/';

					if($_POST['deletepic']) {
						$filename = $folder . $castingid . '.jpg';
						if (file_exists($filename)) { unlink ($filename); }
						$filename = $folder . $castingid . '.gif';
						if (file_exists($filename)) { unlink ($filename); }
					}
					
					if (!empty($_FILES['castthumb']['name'])) { // Handle the form.
						$allowed = array ('image/gif', 'image/jpeg', 'image/jpg', 'image/pjpeg', 'image/JPG');
						if (in_array($_FILES['castthumb']['type'], $allowed)) {
							$allowed_jpg = array ('image/jpeg', 'image/jpg', 'image/pjpeg', 'image/JPG');
							$allowed_gif = array ('image/gif');
							if (in_array($_FILES['castthumb']['type'], $allowed_jpg)) {
								$filetype = ".jpg";
								$current_pic = $folder . $castingid . ".gif";
							} else if (in_array($_FILES['castthumb']['type'], $allowed_gif)) {
								$filetype = ".gif";
								$current_pic = $folder . $castingid . ".jpg";
							}

							// Move the file over.
							$filename = $folder . $castingid . $filetype;
							if (move_uploaded_file($_FILES['castthumb']['tmp_name'], "$filename")) {
								if (file_exists($current_pic)) { unlink ($current_pic); }  // delete old file if not same type

								// Set a maximum height and width
								$height = 60;

								// Get new dimensions
								list($width_orig, $height_orig) = getimagesize($filename);

								if($height_orig > $height) {
		
									$ratio_orig = $width_orig/$height_orig;
									$width = $height*$ratio_orig;
			
									// Resample
									$image_p = imagecreatetruecolor($width, $height);
			
									if ($filetype == '.jpg') {
										$image = imagecreatefromjpeg($filename);
									}
									if ($filetype == '.gif') {
										$image = imagecreatefromgif($filename);
									}
									imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
								}
								
								// Output

								if ($filetype == '.jpg') {
									imagejpeg($image_p, $filename, 100);
								}
								if ($filetype == '.gif') {
									imagegif($image_p, $filename, 100);
								}
							} else { // Couldn't move the file over.

								echo '<p><font color="red">The file could not be uploaded because: </b>';

								// Print a message based upon the error.
								switch ($_FILES['castthumb']['error']) {
									case 1:
										print 'The file exceeds the upload_max_filesize setting in php.ini.';
										$success = false;
										break;
									case 2:
										print 'The file must be less than 1MB.';
										$success = false;
										break;
									case 3:
										print 'The file was only partially uploaded.';
										$success = false;
										break;
									case 4:
										print 'No file was uploaded.';
										$success = false;
										break;
									case 6:
										print 'No temporary folder was available.';
										$success = false;
										break;
									default:
										print 'A system error occurred.';
										$success = false;
										break;
								} // End of switch.

								print '</b></font></p>';

							} // End of move... IF.

						} else { // Invalid type.
							echo '<p><font color="red">Please upload a JPEG or GIF image smaller than 1MB.</font></p>';
							if (file_exists($_FILES['castthumb']['tmp_name'])) { unlink ($_FILES['castthumb']['tmp_name']); }  // delete temp file
						}
					}
					*/
			    }

			    if ($success) { // If required fields.
			       // $submitmessage .= '<b>Your Casting has been posted. Thank you.</b>';
					// $url = 'clienthome.php';
					if(!required_fields($castingid)) {
						$sql = "UPDATE agency_castings SET live='0' WHERE casting_id='$castingid'";
						mysql_query($sql);
					}
					
					
					update_dropdowns(); // update the casting filtering dropdowns to reflect current options
					
					$url = 'news.php?castingid=' . $castingid;
					if($badfile) {
						$url = 'castingupdate.php?badfile=true&castingid=' . $castingid;
					} else if(!$verified) {
						$url = 'castingupdate.php?castingid=' . $castingid;
					}					
					ob_end_clean(); // Delete the buffer.
					header("Location: $url");
					exit(); // Quit the script.
			    } else { // If one of the data tests failed.
			       $submitmessage .= '<p><font color="red">There was a problem submitting your casting.</font></p>';
			    }
			} else {
				$submitmessage .= '<br />Please correct these problems';
			}
		} else {
			$submitmessage .= '<b>The form appears to have been submitted already.  A form my only be submitted once.</b><br/>';
			$success = true;
		}

	    // echo $message;

	 } else {
	 	 $job_type = array();
	 	 $union_status = array();


		if(!empty($_GET['castingid'])) { // if there is a casting id, the we're editing this.  retrieve information from database
			$names = array();
			$roles = array();
			$roleids = array();
			$ageLower = array();
			$ageUpper = array();
			$gender = array();
			$ethnicity = array();
			$castingid = escape_data((INT) $_GET['castingid']);
			$query = "SELECT * FROM agency_castings WHERE casting_id='$castingid'";
		 	$result = @mysql_query($query);
		    if ($row = @mysql_fetch_array($result, MYSQL_ASSOC)) { // If there are projects.
			    $casting_director = $row['casting_director'];
			    $artist = $row['artist'];
			    $company = $row['company'];
			    $job_title = $row['job_title'];
			    $location = $row['location'];  // LEAVE FOR NOW, WILL BE PHASED OUT
			    
				$location_casting = $row['location_casting'];
				$location_shoot = $row['location_shoot'];
				
				$rate_day = $row['rate_day'];
			    $rate_usage = $row['rate_usage'];
			    $shoot_date = $row['shoot_date'];
			    $casting_date = $row['casting_date'];

			    $usage_type = $row['usage_type'];
			    $usage_time = $row['usage_time'];
			    $usage_location = $row['usage_location'];
			    $shoot_date = $row['shoot_date'];
			    $notes = $row['notes'];
				$date = strtotime($row['post_date']);
				$attachment_name = $row['attachment'];
				
				if(!empty($row['clientalert'])) {
					$clientalert = (int) $row['clientalert'];
				} else {
					$clientalert = '';
				}

				$sql = "SELECT jobtype FROM agency_castings_jobtype WHERE casting_id='$castingid'";
				$result = mysql_query($sql);
				while($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
					$job_type[] = $row['jobtype'];
				}

				$sql = "SELECT union_name FROM agency_castings_unions WHERE casting_id='$castingid'";
				$result = mysql_query($sql);
				while($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
					$union_status[] = $row['union_name'];
				}

				$sql = "SELECT * FROM agency_castings_roles WHERE casting_id='$castingid'";
				$result = mysql_query($sql);
				for($i=0; $row = mysql_fetch_array($result, MYSQL_ASSOC); $i++) {
					$names[] = $row['name'];
					$roles[] = $row['description'];
					$rid = $row['role_id'];
					$roleids[] = $row['role_id'];
					$rolefilename[] = $row['attachment'];
					$ageLower[] = $row['age_lower'];
					$ageUpper[] = $row['age_upper'];
					
					$sql2 = "SELECT * FROM agency_castings_roles_vars WHERE role_id='$rid'";
					$result2 = mysql_query($sql2);
					while($row2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {					
						if($row2['var_type'] == 'gender') {
							$gender[$i][] = $row2['var_value'];
						} else if($row2['var_type'] == 'ethnicity') {
							$ethnicity[$i][] = $row2['var_value'];
						}
					}
				}
			}
		}
	 }
		
	if(isset($_GET['badfile'])) {
		$submitmessage .= 'One of the files you attached did not upload properly.  It may have been too large or the incorrect file type.<br/>';
	}
	
	if(!empty($castingid)) {
		if(!required_fields($castingid)) {
			$submitmessage .= 'Please correct the missing information below. Be sure all required fields have been filled and that at least on Union and one Job Type has been selected, and at least one Role has been entered.<br /><br />';
		}
	}
      if(!empty($submitmessage)) {
		echo '<div class="AGENCYsubmitmessage" align="center"><b>' . $submitmessage . '</b></div>';
	  }
	 if(!$success) {
?>


		<div class="AGENCY_ClientPageTitle" style="margin-top: 25px; width: 100%; float: left;">Casting Information</div>
			<div align="center" style="width:100%; float:left">
    <?php
    $_GET['castingid'] = 12793;
    ?>
  <form enctype="multipart/form-data" action="castingupdate.php<?php if(!empty($_GET['castingid'])) echo '?castingid=' . escape_data((INT) $_GET['castingid']); ?>" method="post" name="casting" id="AGENCYcastingform">
    <div class="tabled" style="margin-left:23px; width:43%; float:left">
    <table border="0" cellpadding="3" cellspacing="3" align="left">
    <tr>
    <td colspan="2" align="center" style="padding:10px"><b>Casting Information</b></td>
    </tr>
       <tr>
        <td class="AGENCYregtableleft"<?php if (isset($job_title) && empty($job_title)) {echo ' style="color:#FF0000"';} ?>><b>Project Name (artist / product / campaign / magazine / etc):</b></td>
        <td class="AGENCYregtableright"><textarea name="job_title" style="height:35px"><?php if (!empty($job_title)) echo $job_title; ?></textarea>
        </td>
      </tr>
      
      
    
    
       <tr>
        <td class="AGENCYregtableleft"<?php if (isset($location_casting) && empty($location_casting)) {echo ' style="color:#FF0000"';} ?>><b>Casting Location:</b></td>
        <td class="AGENCYregtableright">
        <select name="location_casting" onchange="if(this.value=='Other') { document.getElementById('other_casting').style.display=''; } else { document.getElementById('other_casting').style.display='none'; }">
			<option value="">Select Casting Location</option>
<?php
foreach($locationarray as $loc) {
	echo '<option value="' . $loc . '"';
	if($loc == $location_casting) echo ' selected';
	echo '>' . $loc . '</option>';
}      
?>           
        	<option value="Other" <?php if(!in_array($location_casting, $locationarray) && !empty($location_casting)) echo 'selected'; ?> >Other</option>
        </select>
        
        <div id="other_casting" style="margin-top:5px; <?php if(in_array($location_casting, $locationarray) || empty($location_casting)) echo 'display:none'; ?>" >
        	Please enter your Casting Location:<br />
            <input type="text" name="location_casting_other" maxlength="50" value="<?php if(!in_array($location_casting, $locationarray)) echo $location_casting; ?>">
        </div>
        </td>
      </tr>
      
      



  <tr>
        <td class="AGENCYregtableleft"<?php if (isset($location_shoot) && empty($location_shoot)) {echo ' style="color:#FF0000"';} ?>><b>Shoot Location:</b></td>
        <td class="AGENCYregtableright">
        <select name="location_shoot" onchange="if(this.value=='Other') { document.getElementById('other_shoot').style.display=''; } else { document.getElementById('other_shoot').style.display='none'; }">
			<option value="">Select Shoot Location</option>
<?php
foreach($locationarray as $loc) {
	echo '<option value="' . $loc . '"';
	if($loc == $location_shoot) echo ' selected';
	echo '>' . $loc . '</option>';
}      
?>           
        	<option value="Other" <?php if(!in_array($location_shoot, $locationarray) && !empty($location_shoot)) echo 'selected'; ?> >Other</option>
        </select>
        
        <div id="other_shoot" style="margin-top:5px; <?php if(in_array($location_shoot, $locationarray) || empty($location_shoot)) echo 'display:none'; ?>" >
        	Please enter your Shoot Location:<br />
            <input type="text" name="location_shoot_other" maxlength="50" value="<?php if(!in_array($location_shoot, $locationarray)) echo $location_shoot; ?>">
        </div>
        </td>
      </tr>         
      
      <tr>
        <td class="AGENCYregtableleft"<?php if (isset($shoot_date) && empty($shoot_date)) {echo ' style="color:#FF0000"';} ?>><b>Shoot Date/Range:</b></td>
        <td class="AGENCYregtableright"><textarea name="shoot_date" style="height:35px"><?php if (!empty($shoot_date)) echo $shoot_date; ?></textarea>
        </td>
      </tr>
      <tr>
        <td class="AGENCYregtableleft"<?php if (isset($casting_date) && empty($casting_date)) {echo ' style="color:#FF0000"';} ?>><b>Casting Date:</b></td>
        <td class="AGENCYregtableright"><textarea name="casting_date" style="height:35px"><?php if (!empty($casting_date)) echo $casting_date; ?></textarea>
        </td>
      </tr>
      <tr>
        <td  class="AGENCYregtableleft"<?php if (isset($casting_director) && empty($casting_director)) {echo ' style="color:#FF0000"';} ?>><b>Casting Director:</b></td>
        <td class="AGENCYregtableright"><textarea name="casting_director" style="height:35px"><?php if (!empty($casting_director)) echo $casting_director; ?></textarea>
        </td>
      </tr>
       <tr>
        <td class="AGENCYregtableleft"><b>Company/Link:</b></td>
        <td class="AGENCYregtableright"><textarea name="company" style="height:35px"><?php if (!empty($company)) echo $company; ?></textarea>
        </td>
      </tr>
      <tr>
        <td class="AGENCYregtableleft"><b>Client/Artist:</b></td>
        <td class="AGENCYregtableright"><textarea name="artist" style="height:35px"><?php if (!empty($artist)) echo $artist; ?></textarea>
        </td>
      </tr>
<?php
if(is_admin()) { // if admin, allow to change post date
?>  
      <tr>
        <td class="AGENCYregtableleft"><b>Posted On (effects order of appearance):</b></td>
        <td class="AGENCYregtableright">
<?php
if(empty($date)) {
	$date = strtotime('now');
}
$strdate = $date;
$YR = date("Y", $strdate);
$MO = date("m", $strdate);
$DY = date("d", $strdate);
$HR = date("h", $strdate);
$MN = date("i", $strdate);
$AP = date("A", $strdate);

 //Create the month pull-down menu

 echo '<SELECT NAME=Month_start style="border:thin dotted #BBB; width:auto">';
 echo "<OPTION VALUE=01"; if (isset($_POST['Month_start'])) { if ($_POST['Month_start'] == "01") { echo " selected"; } } else if ($MO == "01") echo " selected"; echo ">January</OPTION>\n";
 echo "<OPTION VALUE=02"; if (isset($_POST['Month_start'])) { if ($_POST['Month_start'] == "02") { echo " selected"; } } else if ($MO == "02") echo " selected"; echo ">February</OPTION>\n";
 echo "<OPTION VALUE=03"; if (isset($_POST['Month_start'])) { if ($_POST['Month_start'] == "03") { echo " selected"; } } else if ($MO == "03") echo " selected"; echo ">March</OPTION>\n";
 echo "<OPTION VALUE=04"; if (isset($_POST['Month_start'])) { if ($_POST['Month_start'] == "04") { echo " selected"; } } else if ($MO == "04") echo " selected"; echo ">April</OPTION>\n";
 echo "<OPTION VALUE=05"; if (isset($_POST['Month_start'])) { if ($_POST['Month_start'] == "05") { echo " selected"; } } else if ($MO == "05") echo " selected"; echo ">May</OPTION>\n";
 echo "<OPTION VALUE=06"; if (isset($_POST['Month_start'])) { if ($_POST['Month_start'] == "06") { echo " selected"; } } else if ($MO == "06") echo " selected"; echo ">June</OPTION>\n";
 echo "<OPTION VALUE=07"; if (isset($_POST['Month_start'])) { if ($_POST['Month_start'] == "07") { echo " selected"; } } else if ($MO == "07") echo " selected"; echo ">July</OPTION>\n";
 echo "<OPTION VALUE=08"; if (isset($_POST['Month_start'])) { if ($_POST['Month_start'] == "08") { echo " selected"; } } else if ($MO == "08") echo " selected"; echo ">August</OPTION>\n";
 echo "<OPTION VALUE=09"; if (isset($_POST['Month_start'])) { if ($_POST['Month_start'] == "09") { echo " selected"; } } else if ($MO == "09") echo " selected"; echo ">September</OPTION>\n";
 echo "<OPTION VALUE=10"; if (isset($_POST['Month_start'])) { if ($_POST['Month_start'] == "10") { echo " selected"; } } else if ($MO == "10") echo " selected"; echo ">October</OPTION>\n";
 echo "<OPTION VALUE=11"; if (isset($_POST['Month_start'])) { if ($_POST['Month_start'] == "11") { echo " selected"; } } else if ($MO == "11") echo " selected"; echo ">November</OPTION>\n";
 echo "<OPTION VALUE=12"; if (isset($_POST['Month_start'])) { if ($_POST['Month_start'] == "12") { echo " selected"; } } else if ($MO == "12") echo " selected"; echo ">December</OPTION>\n";
 echo "</SELECT>&nbsp;&nbsp;";

 //Create the day pull-down menu.

 echo "<SELECT NAME=Day_start style=\"border:thin dotted #BBB; width:auto\">";
 $Day = 1;
 while ($Day <= 31) {
   if (strlen($Day) < 2) {
		$Day = '0' .$Day;
	}
   echo "<OPTION VALUE=$Day"; if (isset($_POST['Day_start'])) { if ($_POST['Day_start'] == $Day) { echo " selected"; } } else if ($DY == $Day) echo " selected"; echo ">$Day</OPTION>\n";
   $Day++;
 }
 echo "</SELECT>&nbsp;&nbsp;";

 //Create the year pull-down menu.
 echo "<SELECT NAME=Year_start style=\"border:thin dotted #BBB;  width:auto\">";

 $Year = date("Y") - 1;
 $Yearcount = $Year +3;
 while ($Year <= $Yearcount) {
   echo "<OPTION VALUE=$Year"; if (isset($_POST['Year_start'])) { if ($_POST['Year_start'] == $Year) { echo " selected"; } } else if ($Year == $YR) echo " selected"; echo ">$Year</OPTION>\n";
   $Year++;
 }
 echo "</SELECT><br />at&nbsp;&nbsp;";

 echo "<SELECT NAME=Hour_start style=\"border:thin dotted #BBB; width:auto \">";
 $Hour = 1;
 while ($Hour <= 12) {
   echo "<OPTION VALUE=";
   if (strlen($Hour) < 2) {
		$Hour = '0' .$Hour;
	}
   echo "$Hour";
   if (isset($_POST['Hour_start'])) { if ($_POST['Hour_start'] == $Hour) { echo " selected"; } } else if ($Hour == $HR) echo " selected";
   echo ">$Hour</OPTION>\n";
   $Hour++;
 }
 echo "</SELECT>&nbsp;&nbsp;";


 //Create the minute pull-down menu.
 echo "<SELECT NAME=Minute_start style=\"border:thin dotted #BBB; width:auto\">";
 $Minute = 0;
 while ($Minute <= 59) {
   echo "<OPTION VALUE=";
   if (strlen($Minute) < 2) {
		$Minute = '0' .$Minute;
	}
   echo "$Minute";
   if (isset($_POST['Minute_start'])) { if ($_POST['Minute_start'] == $Minute) { echo " selected"; } } else if ($Minute == $MN) echo " selected";
   echo " >$Minute</OPTION>\n";
   $Minute += 15;
 }
 echo "</SELECT>&nbsp;&nbsp;";

 //Create the AM or PM pull-down menu
 echo "<SELECT NAME=AMPM_start style=\"border:thin dotted #BBB; width:auto\">";
 echo "<OPTION VALUE=AM";
 if (isset($_POST['AMPM_start'])) { if ($_POST['AMPM_start'] == "AM") { echo " selected"; } } else if ($AP == "AM") echo ' selected';
 echo ">AM</OPTION>\n";
 echo "<OPTION VALUE=PM";
 if (isset($_POST['AMPM_start'])) { if ($_POST['AMPM_start'] == "PM") { echo " selected"; } } else if ($AP == "PM") echo ' selected';
 echo ">PM</OPTION>\n";
 echo "</SELECT>&nbsp;&nbsp;";
?>
        </td>
      </tr>

<?php  
}
?>  
      <tr>
        <td class="AGENCYregtableleft"><b>Job Type:</b></td>
        <td class="AGENCYregtableright">
		<?php
for($i=0; isset($jobtypearray[$i]); $i++) {
	echo '<input type="checkbox" class="box" name="job_type[]" value="' . $jobtypearray[$i] . '"';
	if(in_array($jobtypearray[$i], $job_type)) echo ' checked';
	echo ' /> ' . $jobtypearray[$i] . '<br />';
}

echo 'Other:<input type="text" name="job_type[]" value="';
foreach($job_type as $jt) {
	if(!in_array($jt, $jobtypearray)) {
		echo $jt;
	}
}
echo '" />';
?>
        </td>
      </tr>
        <td class="AGENCYregtableleft"><b>Union Status:</b></td>
        <td class="AGENCYregtableright">
		<?php
for($i=0; isset($jobunionarray[$i]); $i++) {
	echo '<input type="checkbox" class="box" name="union_status[]" value="' . $jobunionarray[$i] . '"';
	if(in_array($jobunionarray[$i], $union_status)) echo ' checked';
	echo ' /> ' . $jobunionarray[$i] . '<br />';
}
?>
        </td>
      </tr>
      </tr>
        <tr>
        <td class="AGENCYregtableleft"<?php if (isset($rate_day) && empty($rate_day)) {echo ' style="color:#FF0000"';} ?>><b>Day Rate:</b></td>
        <td class="AGENCYregtableright"><textarea name="rate_day" style="height:35px"><?php if (!empty($rate_day)) echo $rate_day; ?></textarea>
        </td>
      </tr>
        <tr>
        <td class="AGENCYregtableleft"<?php if (isset($rate_usage) && empty($rate_usage)) {echo ' style="color:#FF0000"';} ?>><b>Usage Rate:</b></td>
        <td class="AGENCYregtableright"><textarea name="rate_usage" style="height:35px"><?php if (!empty($rate_usage)) echo $rate_usage; ?></textarea>
        </td>
      </tr>
      <tr>
        <td class="AGENCYregtableleft">Usage - Type(s):</td>
        <td class="AGENCYregtableright"><textarea  name="usage_type" style="height:100px"><?php if (!empty($usage_type)) echo $usage_type; ?></textarea>
        </td>
      </tr>
       <tr>
        <td class="AGENCYregtableleft">Usage - Term:</td>
        <td class="AGENCYregtableright"><textarea  name="usage_time" style="height:100px"><?php if (!empty($usage_time)) echo $usage_time; ?></textarea>
        </td>
      </tr>
      <tr>
        <td class="AGENCYregtableleft">Usage - Area:</td>
        <td class="AGENCYregtableright"><textarea name="usage_location" style="height:100px"><?php if (!empty($usage_location)) echo $usage_location; ?></textarea>
        </td>
      </tr>
      <tr>
        <td class="AGENCYregtableleft">Notes:</td>
        <td class="AGENCYregtableright"><textarea name="notes" style="height:150px"><?php if (!empty($notes)) echo $notes; ?></textarea>
        </td>
      </tr>

<tr>
        <td class="AGENCYregtableright" colspan="2">
       <p align="center">
            <b>Add .doc, docx or .pdf attachment:</b><br />
            (must be less than 500K)<br />
<?php
if(isset($castingid)) {
	if(file_exists('attachments/castings/' . $castingid . '/' . $attachment_name)) {
		echo '<a href="attachments/castings/' . $castingid . '/' . $attachment_name . '" target="_blank">view attachment</a>&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="deleteattachment" style="width:10px"> check to delete<br /><br />';
	}
}
?>
          
			<input type="file" name="attach_casting" />
            
  </p>
  </td></tr>


    </table>
	</div>
    
	<div class="tabled" style="width:43%; float:right; margin-right:61px">
    <table border="0" align="left">
    <tr>
    <td colspan="2" align="center" style="padding:10px"><b>Role Descriptions</b></td>
    </tr>  
	<tr>
	<td>
	<div class="term" style="height:1794px; overflow-y:auto; overflow-x:hidden; width:100%">
	<table width="100%" border="0" cellpadding="3" cellspacing="3" align="left">
      <tr>
        <td class="AGENCYregtableleft"<?php if (isset($roleRed)) {echo ' style="color:#FF0000"';} ?>>Character Name:</td>
        <td class="AGENCYregtableright"><input type="text" name="name[]" value="<?php if (!empty($names[0])) echo $names[0]; ?>" style="width:210px; font-size:10px" />
        </td>
      </tr>
      
      
      <tr>
        <td class="AGENCYregtableleft"<?php if (isset($ageRed)) {echo ' style="color:#FF0000"';} ?>>Age Range:</td>
        <td class="AGENCYregtableright">From: <select name="ageLower[]" style="border:thin dotted #BBB; width:auto">
<?php
$maxage = 100; // define upper limit here for age dropdowns

for($j=0; $j<=$maxage; $j++) {        
	echo '<option value="' . $j . '"';
	if (!empty($ageLower[0])) {
		if ($ageLower[0] == $j) {
			echo ' selected="selected"';
		}
	}
	echo '>' . $j . '</option>';
}
?>        
        </select>&nbsp;&nbsp;&nbsp;To: <select name="ageUpper[]" style="border:thin dotted #BBB; width:auto">
<?php
for($j=0; $j<=$maxage; $j++) {        
	echo '<option value="' . $j . '"';
	if (!empty($ageUpper[0])) {
		if ($ageUpper[0] == $j) {
			echo ' selected="selected"';
		}
	} else if ($j == $maxage) {
		echo ' selected="selected"';
	}
	echo '>' . $j . '</option>';
}
?>        
        </select>
        </td>
      </tr>
      <tr>
        <td class="AGENCYregtableleft"<?php if (isset($genderRed)) {echo ' style="color:#FF0000"';} ?>>Gender:</td>
        <td class="AGENCYregtableright">
        	<input class="box" type="checkbox" name="gender[0][]" value="Male" <?php if(!empty($gender[0])) if(in_array('Male', $gender[0])) echo 'checked'; ?> />M&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
           	<input class="box" type="checkbox" name="gender[0][]" value="Female"<?php if(!empty($gender[0])) if(in_array('Female', $gender[0])) echo 'checked'; ?> />F&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        	<input class="box" type="checkbox" name="gender[0][]" value="Other" <?php if(!empty($gender[0])) if(in_array('Other', $gender[0])) echo 'checked'; ?> />Other
        </td>
      </tr>
      

 
<?php
if(!empty($names[0]) && empty($ethnicity[0])) {
	$ethnicityRed = true;
} else {
	$ethnicityRed = false;
}
?> 
      
      <tr>
        <td class="AGENCYregtableleft"<?php if ($ethnicityRed) {echo ' style="color:#FF0000"';} ?>>Ethnicity:</td>
        <td class="AGENCYregtableright">
        	<div style="height:20px; overflow:hidden;" onmouseover="this.style.height=''" onmouseout="this.style.height='20px'">
            	<div style="float:right; width:10px">&darr;</div>
<?php
if(identical_values( $ethnicityarray , $ethnicity[0] ) ) { // all ethnicities have been set
	$allflag = true;
} else {
	$allflag = false;
}
$ethboxes = '';
foreach($ethnicityarray as $key=>$e) {
	$ethboxes .= '<input id="0_' . $key . '" class="box" type="checkbox" name="ethnicity[0][]" value="' . $e . '"';
	if(!empty($ethnicity[0])) {
		if(in_array($e, $ethnicity[0])) {
			 $ethboxes .= 'checked'; 
		} else { 
			// $allflag = false;
		}
	} /* else if(!$ethnicityRed) { 
		$ethboxes .= 'checked';
		$allflag = true;
	} else {
		$allflag = false;
	} */
	
	$ethboxes .= ' onclick="if(!this.checked) { document.getElementsByName(\'ethnicity0\')[0].checked = false; }" />' . $e . '<br />';
}
?>

        		<input class="box" type="checkbox" name="ethnicity0" onclick="if(this.checked) { checkAllRoles('0'); } else { uncheckAllRoles('0'); }"<?php if($allflag) echo ' checked'; ?> />All Ethnicities<br />
<?php echo $ethboxes; ?>

        	</div>
        </td>
      </tr>      
      
      
      <tr>
        <td class="AGENCYregtableleft"<?php if (isset($roleRed)) {echo ' style="color:#FF0000"';} ?>>Description:</td>
        <td class="AGENCYregtableright"><textarea name="role[]" style="height:80px; width:210px; font-size:10px"><?php if (!empty($roles[0])) echo $roles[0]; ?></textarea>
										<input type="hidden" name="roleid[]" value="<?php if (!empty($roleids[0])) echo $roleids[0]; ?>">
        </td>
      </tr>
      <tr>
        <td class="AGENCYregtableleft">Attachment:</td>
        <td class="AGENCYregtableright">
        <?php if(!empty($rolefilename[0])) echo '<a href="attachments/roles/' . $roleids[0] . '/' . $rolefilename[0] . '" target="_blank">view attachment</a>&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="attach_role_del[' . $roleids[0] . ']" class="box"> check to delete<br /><br />'; ?>
        <input type="file" name="rolefile[]" />
        </td>
      </tr>      
      
        <tr>
        <td class="AGENCYregtableright" colspan="2" style="text-align:center">optional: attach a .gif, .jpg or .pdf (&lt;500K) for each role</td>
      </tr>       
<?php
for($i=1; $i < 40; $i++) {
?>
     
      <tr>
        <td class="AGENCYregtableleft">Character Name:</td>
        <td class="AGENCYregtableright">
        <input type="text" name="name[]" value="<?php if (!empty($names[$i])) echo $names[$i]; ?>" style="width:210px; font-size:10px" />
        </td>
      </tr>
      

      <tr>
        <td class="AGENCYregtableleft"<?php if (isset($ageRed)) {echo ' style="color:#FF0000"';} ?>>Age Range:</td>
        <td class="AGENCYregtableright">From: <select name="ageLower[]" style="border:thin dotted #BBB; width:auto">
<?php
for($j=0; $j<=$maxage; $j++) {        
	echo '<option value="' . $j . '"';
	if (!empty($ageLower[$i])) {
		if ($ageLower[$i] == $j) {
			echo ' selected="selected"';
		}
	}
	echo '>' . $j . '</option>';
}
?>        
        </select>&nbsp;&nbsp;&nbsp;To: <select name="ageUpper[]" style="border:thin dotted #BBB; width:auto">
<?php
for($j=0; $j<=$maxage; $j++) {        
	echo '<option value="' . $j . '"';
	if (!empty($ageUpper[$i])) {
		if ($ageUpper[$i] == $j) {
			echo ' selected="selected"';
		}
	} else if ($j == $maxage) {
		echo ' selected="selected"';
	}
	echo '>' . $j . '</option>';
}
?>        
        </select>
        </td>
      </tr>
      
      
      <tr>
        <td class="AGENCYregtableleft"<?php if (isset($genderRed)) {echo ' style="color:#FF0000"';} ?>>Gender:</td>
        <td class="AGENCYregtableright">
        	<input class="box" type="checkbox" name="gender[<?php echo $i; ?>][]" value="Male" <?php if(!empty($gender[$i])) if(in_array('Male', $gender[$i])) echo 'checked'; ?> />M&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
           	<input class="box" type="checkbox" name="gender[<?php echo $i; ?>][]" value="Female"<?php if(!empty($gender[$i])) if(in_array('Female', $gender[$i])) echo 'checked'; ?> />F&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        	<input class="box" type="checkbox" name="gender[<?php echo $i; ?>][]" value="Other" <?php if(!empty($gender[$i])) if(in_array('Other', $gender[$i])) echo 'checked'; ?> />Other
        </td>
      </tr>

<?php
if(!empty($names[$i]) && empty($ethnicity[$i])) {
	$ethnicityRed = true;
} else {
	$ethnicityRed = false;
}
?>
      <tr>
        <td class="AGENCYregtableleft"<?php if ($ethnicityRed) {echo ' style="color:#FF0000"';} ?>>Ethnicity:</td>
        <td class="AGENCYregtableright">
        	<div style="height:20px; overflow:hidden;" onmouseover="this.style.height=''" onmouseout="this.style.height='20px'">
            	<div style="float:right; width:10px">&darr;</div>
<?php
// $allflag = true;
$allflag = false;
$ethboxes = '';
foreach($ethnicityarray as $key=>$e) {
	$ethboxes .= '<input id="' . $i . '_' . $key . '" class="box" type="checkbox" name="ethnicity[' . $i . '][]" value="' . $e . '"';
	if(!empty($ethnicity[$i])) {
		if(in_array($e, $ethnicity[$i])) {
			$ethboxes .= 'checked'; 
		} else { 
			// $allflag = false;
		}
	} /* else if(!$ethnicityRed) { 
		$ethboxes .= 'checked';
		$allflag = true;
	} else {
		$allflag = false;
	} */
	$ethboxes .= ' alert(\'x\'); onclick="if(!this.checked) { document.getElementsByName(\'ethnicity[' . $i . ']\')[0].checked = false; }" />' . $e . '<br />';
}
?>
				<input class="box" type="checkbox" name="ethnicity[<?php echo $i; ?>]" onclick="if(this.checked) { checkAllRoles('<?php echo $i; ?>'); } else { uncheckAllRoles('<?php echo $i; ?>'); }"<?php if($allflag) echo ' checked'; ?> /> All Ethnicities<br />
<?php echo $ethboxes; ?>
        	</div>
        </td>
      </tr>        
      
      <tr>
        <td class="AGENCYregtableleft">Description:</td>
        <td class="AGENCYregtableright">
        	<textarea name="role[]" style="height:80px; width:210px; font-size:10px"><?php if (!empty($roles[$i])) echo $roles[$i]; ?></textarea>
       		<input type="hidden" name="roleid[]" value="<?php if (!empty($roleids[$i])) echo $roleids[$i]; ?>">
	    </td>
      </tr>
      <tr>
        <td class="AGENCYregtableleft">Attachment (optional):</td>
        <td class="AGENCYregtableright">
        	<?php if(!empty($rolefilename[$i])) echo '<a href="attachments/roles/' . $roleids[$i] . '/' . $rolefilename[$i] . '" target="_blank">view attachment</a>&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="attach_role_del[' . $roleids[$i] . ']" class="box"> check to delete<br /><br />'; ?>
        	<input type="file" name="rolefile[]" />
        </td>
      </tr> 
      <tr>
        <td class="AGENCYregtableright" colspan="2">&nbsp;</td>
      </tr>         
<?php
}
?>
	  </table>
	  </div>
	  </td>
	  </tr> 

    </table>
    </div>
    <div style="clear:both; padding:20px">
<?php
		if(is_admin()) {
?>
	Send notification when number of submissions reaches: <input type="text" name="clientalert" style="width:20px" maxlength="3" value="<?php if (!empty($clientalert)) echo $clientalert; ?>" /><br /><br />
<?php
}
?>
    <input type="hidden" name="MAX_FILE_SIZE" value="1000000" />
    <input type="hidden" name="stopdouble" value="<?php $_SESSION['stopdouble'] = time(); echo $_SESSION['stopdouble']; ?>" />
	<input type="hidden" value="<?php echo time(); ?>" name="creation_time"/>
	<input type="hidden" value="<?php echo agency_add_form_key('casting'); ?>" name="form_token"/>
    <input type="submit" value="Submit" name="submit" />
    </div>
  </form>

  <form action="clienthome.php" style="padding-bottom:20px">
    <input type="submit" value="Cancel" />
  </form>

<?php
		if(isset($castingid)) {
?>
	<form action="clienthome.php?mode=castings&deletecastingid=<?php echo $castingid; ?>" method="post" style="padding-bottom:30px">
		<!--<input type="submit" value="Delete" onclick="return confirm('Are you sure you wish to delete this casting?')" />-->
	</form>
<?php
		}
?>
</div>

<?php
	//	echo '<div style="width:144px; float:right; padding-top:10px">' . clientbuttons(true) . '</div>';
	 }
} else {
 	echo '<div align="center"><b>As an APPROVED Client you will be able to post castings.</b><br><br><a href="javascript:history.go(-1)">go back</a></div>';
}

//@include ('includes/footer.php');
?>
