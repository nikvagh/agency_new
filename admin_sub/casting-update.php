<?php 
$page = "casting_call_form";
$page_selected = "casting_calls";
include('header.php');
include('../includes/agency_dash_functions.php');
?>

<div id="page-wrapper">
	<!-- <div class="container-fluid"> -->
    	<div class="" id="main">

    		<h3>Casting Calls </h3>
    		<?php //if(isset($notification['success'])){ ?>
		        <div class="alert alert-success" role="alert" id="alert-success-form" style="display: none;">
		            <?php //echo $notification['success']; ?>
		        </div>
	        <?php //} ?>
	        <?php //if(isset($notification['error'])){ ?>
	            <div class="alert alert-danger" role="alert" id="alert-danger-form" style="display: none;">
	                <?php //echo $notification['error']; ?>
	            </div>
	        <?php //} ?>

				<?php
				$casting_directors_list = get_all_casting_directors();
				$success = FALSE; // flag for showing or not showing form
				$form_status = "normal";

				$loggedin = $_SESSION['user_id'];
				if (isset($_POST['job_title'])) {

					$form_status = "submit";

					// echo "<pre>";
					// print_r($_POST);
					// echo "</pre>";

					// echo "<pre>";
					// print_r($_FILES);
					// echo "</pre>";
					// exit;

					$submitmessage = array();
					$verified = true; // flag to check required values

					// delete files
					// if ($_POST['deleteattachment'] && !empty($_GET['casting_id'])) { // delete casting attachment
					// 	$castingid = escape_data((int) $_GET['casting_id']);
					// 	$filename = 'attachments/castings/' . $castingid . '/' . mysql_result(mysql_query("SELECT attachment FROM agency_castings WHERE casting_id='$castingid'"), 0, 'attachment');
					// 	unlink($filename);
					// 	mysql_query("UPDATE agency_castings SET attachment='NULL' WHERE casting_id='$castingid' LIMIT 1");
					// }

					if ($_POST['reference_photo_del'] && !empty($_GET['casting_id'])) { // delete casting attachment
						$castingid = escape_data((int) $_GET['casting_id']);
						foreach ($_POST['reference_photo_del'] as $roleid => $value) {
							$filename = '../attachments/roles/' . $roleid . '/' . mysql_result(mysql_query("SELECT reference_photo FROM agency_castings_roles WHERE role_id='$roleid'"), 0, 'reference_photo');
							unlink($filename);
							mysql_query("UPDATE agency_castings_roles SET reference_photo=NULL WHERE role_id='$roleid' LIMIT 1");
						}
					}

					if ($_POST['sides_del'] && !empty($_GET['casting_id'])) { // delete casting attachment
						$castingid = escape_data((int) $_GET['casting_id']);
						foreach ($_POST['sides_del'] as $roleid => $value) {
							$filename = '../attachments/roles/' . $roleid . '/' . mysql_result(mysql_query("SELECT sides FROM agency_castings_roles WHERE role_id='$roleid'"), 0, 'sides');
							unlink($filename);
							mysql_query("UPDATE agency_castings_roles SET sides=NULL WHERE role_id='$roleid' LIMIT 1");
						}
					}

					if (true) {
						$job_title = $_POST['job_title'];
						// get locations
						// $location_casting = escape_data($_POST['location_casting']);
						// if ($location_casting == 'Other') {
						// 	$location_casting = escape_data($_POST['location_casting_other']);
						// }

						// $location_shoot = escape_data($_POST['location_shoot']);
						// if ($location_shoot == 'Other') {
						// 	$location_shoot = escape_data($_POST['location_shoot_other']);
						// }
						
						// echo "<pre>";
						// print_r($_POST['location_casting']);
						// echo "</pre>";

						$location_casting = array();
						if (isset($_POST['location_casting']) && !empty($_POST['location_casting'])) {
							$location_casting = $_POST['location_casting'];
							$location_casting_str = implode(',',$_POST['location_casting']);
						} else {
							$verified = false;
							$submitmessage[] = 'Please select Casting Location';
						}

						$location_shoot = array();
						if (isset($_POST['location_shoot']) && !empty($_POST['location_shoot'])) {
							$location_shoot = $_POST['location_shoot'];
							$location_shoot_str = implode(',',$_POST['location_shoot']);
						} else {
							$verified = false;
							$submitmessage[] = 'Please select Shoot Location';
						}

						$casting_director = $_POST['casting_director'];
						$artist = $_POST['artist'];
						$company = $_POST['company'];
						
						$rate_day = $_POST['rate_day'];
						$rate_usage = $_POST['rate_usage'];
						$shoot_date_start = $_POST['shoot_date_start'];
						$shoot_date_end = $_POST['shoot_date_end'];
						$casting_date = $_POST['casting_date'];

						$usage_type = $_POST['usage_type'];
						$usage_time = $_POST['usage_time'];
						$usage_location = $_POST['usage_location'];
						// $shoot_date = $_POST['shoot_date'];
						$notes = $_POST['notes'];
						$tags = $_POST['tags'];
						$posted_on = $_POST['posted_on'];
						if (!empty($_POST['clientalert'])) {
							$clientalert = (int) $_POST['clientalert'];
						} else {
							$clientalert = NULL;
						}

						// first, if admin and post date is set
						// if (is_admin() && !empty($_POST['Year_start']) && !empty($_POST['Month_start']) && !empty($_POST['Day_start']) && !empty($_POST['Hour_start']) && !empty($_POST['Minute_start']) && !empty($_GET['casting_id'])) { //  && !empty($_GET['casting_id'])
						// 	if ($_POST['AMPM_start'] == 'PM') {
						// 		if ($_POST['Hour_start'] != 12) { // Takes care of 12 noon
						// 			$H_S = $_POST['Hour_start'] + 12;
						// 		} else {
						// 			$H_S = $_POST['Hour_start'];
						// 		}
						// 	} else {
						// 		if ($_POST['Hour_start'] == 12) { // if it's 12 AM then set to 00
						// 			$H_S = "00";
						// 		} else {
						// 			$H_S = $_POST['Hour_start'];
						// 		}
						// 	}

						// 	$date = escape_data($_POST['Year_start'] . '-' . $_POST['Month_start'] . '-' . $_POST['Day_start'] . ' ' .	$H_S . ':' . $_POST['Minute_start'] . ':00');

						if(is_admin() && !empty($_POST['posted_on'])){

								$date = escape_data($_POST['posted_on'] . ':00');
								$sql_ary = array(
												'posted_by' => $loggedin,
												'casting_director' => request_var('casting_director', ''), 
												'artist' => request_var('artist', ''), 
												'company' => request_var('company', ''), 
												'job_title' => request_var('job_title', ''), 
												'location_shoot' => $location_shoot_str, 
												'location_casting' => $location_casting_str, 
												'rate_day' => request_var('rate_day', ''), 
												'rate_usage' => request_var('rate_usage', ''), 
												'usage_type' => request_var('usage_type', ''), 
												'usage_time' => request_var('usage_time', ''), 
												'usage_location' => request_var('usage_location', ''), 
												'shoot_date_start' => request_var('shoot_date_start', ''), 
												'shoot_date_end' => request_var('shoot_date_end', ''), 
												'casting_date' => date('Y-m-d',strtotime(request_var('casting_date', ''))),
												'notes' => request_var('notes', ''), 
												'tags' => request_var('tags', ''), 	
												'post_date' => $date,
											);

						}else if (!empty($_GET['casting_id'])) { 
							// do not update "posted_by"

							$sql_ary = array(
											'casting_director' => request_var('casting_director', ''), 
											'artist' => request_var('artist', ''), 
											'company' => request_var('company', ''), 
											'job_title' => request_var('job_title', ''), 
											'location_shoot' => $location_shoot_str, 
											'location_casting' => $location_casting_str, 
											'rate_day' => request_var('rate_day', ''), 
											'rate_usage' => request_var('rate_usage', ''), 
											'usage_type' => request_var('usage_type', ''), 
											'usage_time' => request_var('usage_time', ''), 
											'usage_location' => request_var('usage_location', ''), 
											'shoot_date_start' => request_var('shoot_date_start', ''), 
											'shoot_date_end' => request_var('shoot_date_end', ''),
											'casting_date' => date('Y-m-d',strtotime(request_var('casting_date', ''))),
											'notes' => request_var('notes', ''),
											'tags' => request_var('tags', ''), 	
										);
						} else {

							$sql_ary = array(
								'posted_by' => $loggedin,
								'casting_director' => request_var('casting_director', ''), 
								'artist' => request_var('artist', ''), 
								'company' => request_var('company', ''), 
								'job_title' => request_var('job_title', ''), 
								'location_shoot' => $location_shoot_str, 
								'location_casting' => $location_casting_str, 
								'rate_day' => request_var('rate_day', ''), 
								'rate_usage' => request_var('rate_usage', ''), 
								'usage_type' => request_var('usage_type', ''), 
								'usage_time' => request_var('usage_time', ''), 
								'usage_location' => request_var('usage_location', ''), 
								'shoot_date_start' => request_var('shoot_date_start', ''), 
								'shoot_date_end' => request_var('shoot_date_end', ''), 
								'casting_date' => date('Y-m-d',strtotime(request_var('casting_date', ''))),
								'notes' => request_var('notes', ''), 
								'tags' => request_var('tags', ''), 	
								'live' => 0
							);

							// if (is_admin() && !empty($_POST['Year_start']) && !empty($_POST['Month_start']) && !empty($_POST['Day_start']) && !empty($_POST['Hour_start']) && !empty($_POST['Minute_start'])) { //  && !empty($_GET['casting_id'])
							// 	if ($_POST['AMPM_start'] == 'PM') {
							// 		if ($_POST['Hour_start'] != 12) { // Takes care of 12 noon
							// 			$H_S = $_POST['Hour_start'] + 12;
							// 		} else {
							// 			$H_S = $_POST['Hour_start'];
							// 		}
							// 	} else {
							// 		if ($_POST['Hour_start'] == 12) { // if it's 12 AM then set to 00
							// 			$H_S = "00";
							// 		} else {
							// 			$H_S = $_POST['Hour_start'];
							// 		}
							// 	}

							// 	$date = escape_data($_POST['Year_start'] . '-' . $_POST['Month_start'] . '-' . $_POST['Day_start'] . ' ' .	$H_S . ':' . $_POST['Minute_start'] . ':00');

							// 	$sql_ary['post_date'] = $date;
							// }
							if(is_admin() && !empty($_POST['posted_on'])){
								$date = escape_data($_POST['posted_on'] . ':00');
							}
						}

						
						$job_type = array();
						$union_status = array();
						$names = array();
						$roles = array();
						$roleids = array();

						$job_ar = array_filter($_POST['job_type']);
						if (empty($job_ar)) {
							$verified = false;
							$submitmessage[] = 'Please check at least one Job Type box';
							$job_type = $_POST['job_type'];
						} else {
							$job_type = $_POST['job_type'];
						}

						if (empty($_POST['union_status'])) {
							$verified = false;
							$submitmessage[] = 'Please check at least one Union Status box';
							$union_status = $_POST['union_status'];
						} else {
							$union_status = $_POST['union_status'];
						}

						$names = array();
						if (empty($_POST['name'][0])) {
							$verified = false;
							$submitmessage[] = 'Please enter at least one Role<br />';
							$roleRed = true;
							$names = $_POST['name'];
						} else {
							$names = $_POST['name'];
						}

						$ageLower = array();
						$ageLower = $_POST['ageLower'];
						$ageUpper = array();
						$ageUpper = $_POST['ageUpper'];

						// $gender = array();
						// $gender = $_POST['gender'];

						$gender = array();
						if (empty($_POST['gender'][0])) {
							$verified = false;
							$submitmessage[] = 'Please select Gender';
							$gender = $_POST['gender'];
						} else {
							$gender = $_POST['gender'];
						}

						// $ethnicity = array();
						// $ethnicity = $_POST['ethnicity'];

						$ethnicity = array();
						if (empty($_POST['ethnicity'][0])) {
							$verified = false;
							$submitmessage[] = 'Please select ethnicity';
							$ethnicity = $_POST['ethnicity'];
						} else {
							$ethnicity = $_POST['ethnicity'];
						}

						$heightLower = array();
						$heightLower = $_POST['heightLower'];
						$heightUpper = array();
						$heightUpper = $_POST['heightUpper'];

						$requirement = array();
						$requirement = $_POST['requirement'];

						$description = array();
						$description = $_POST['description'];

						$language = array();
						$language = $_POST['language'];

						$accent = array();
						$accent = $_POST['accent'];

						$special_skills = array();
						$special_skills = $_POST['special_skills'];

						$required_material = array();
						if (empty($_POST['required_material'][0])) {
							$verified = false;
							$submitmessage[] = 'Please select materials';
							$required_material = $_POST['required_material'];
						} else {
							$required_material = $_POST['required_material'];
						}

						// ==========================

						if (empty($sql_ary['job_title'])) {
							$verified = false;
							$submitmessage[] = 'Please enter the Project Name';
						}

						// if (empty($sql_ary['location_casting'])) {
						// 	$verified = false;
						// 	$submitmessage[] = 'Please enter the Casting Location';
						// }

						// if (empty($sql_ary['location_shoot'])) {
						// 	$verified = false;
						// 	$submitmessage[] = 'Please enter the Shoot Location';
						// }

						if (empty($sql_ary['casting_date'])) {
							$verified = false;
							$submitmessage[] = 'Please enter the Casting Date';
						}

						if (empty($sql_ary['shoot_date_start']) || empty($sql_ary['shoot_date_end'])) {
							$verified = false;
							$submitmessage[] = 'Please enter the Shoot start & end dates';
						}

						if (empty($sql_ary['casting_director'])) {
							$verified = false;
							$submitmessage[] = 'Please enter the Casting Director';
						}

						if (empty($sql_ary['post_date'])) {
							$verified = false;
							$submitmessage[] = 'Please select posted on date & time';
						}

						if (empty($sql_ary['rate_day'])) {
							$verified = false;
							$submitmessage[] = 'Please enter the Day Rate';
						}

						if (empty($sql_ary['rate_usage'])) {
							$verified = false;
							$submitmessage[] = 'Please enter the Usage Rate';
						}

						if (empty($sql_ary['usage_type'])) {
							$verified = false;
							$submitmessage[] = 'Please enter the Usage - Type(s)';
						}

						if (empty($sql_ary['usage_time'])) {
							$verified = false;
							$submitmessage[] = 'Please enter the Usage - Term';
						}

						if (empty($sql_ary['usage_location'])) {
							$verified = false;
							$submitmessage[] = 'Please enter the Usage - Area';
						}

						if (empty($sql_ary['notes'])) {
							$verified = false;
							$submitmessage[] = 'Please enter Notes';
						}

						if (empty($sql_ary['tags'])) {
							$verified = false;
							$submitmessage[] = 'Please enter Tags';
						}
						
						// if (!empty($_POST['role'])) {
						// 	$roles = $_POST['role'];
						// 	$roleids = $_POST['roleid'];
						// }

						$roleids = array();
						if (!empty($_POST['roleid'])) {
							$roleids = $_POST['roleid'];
						}

						// echo $submitmessage;exit;
						if (true && $verified == true) {
							// no need for verification here, there will be a redirect if $verified=false.  This is so images upload
							$badfile = false; // flag for if a message has to be displayed for one of the files not uploading.
							if (!empty($_GET['casting_id'])) {
								$castingid = escape_data((int) $_GET['casting_id']);
								$sql = 'UPDATE agency_castings SET ' . sql_build_array('UPDATE', $sql_ary) . " WHERE casting_id='$castingid'";
							} else {
								$sql = 'INSERT INTO agency_castings' . sql_build_array('INSERT', $sql_ary);
							}

							$avail_role = array();

							if (mysql_query($sql)) {
								$success = true;
								if (empty($_GET['casting_id'])) {
									$castingid = mysql_insert_id();
								}


								if (!empty($_POST['clientalert'])) { 
								// this is the alert to send an email when a certain number of submissions has been reached
									$clientalert = (int) $_POST['clientalert'];
									if ($clientalert > 0) {
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

								$allowedExtensions = array("jpg", "gif", "jpeg", "pdf", "png");


								// delete data that will be replaced for genders and ethnicities
								$sql = "DELETE FROM agency_castings_roles_vars WHERE casting_id='$castingid'";
								mysql_query($sql);

								$i = 0;

								foreach ($names as $nm) {

									$avail_role[] = $roleids[$i];
									// $ro = escape_data($roles[$i]);
									$rid = escape_data($roleids[$i]);
									$ageL = escape_data($ageLower[$i]);
									$ageU = escape_data($ageUpper[$i]);
									$heightL = escape_data($heightLower[$i]);
									$heightU = escape_data($heightUpper[$i]);

									$description1 = escape_data($description[$i]);
									$requirement1 = escape_data($requirement[$i]);
									$language1 = escape_data($language[$i]);
									$accent1 = escape_data($accent[$i]);
									$special_skills1 = escape_data($special_skills[$i]);

									$required_material1 = "";
									if(!empty($required_material[$i])){
										$required_material1 = implode(',',$required_material[$i]);
									}

									if (!empty($nm)) {
										$nm = escape_data($nm);
										if (empty($rid)) {
											$sql = "INSERT INTO agency_castings_roles (casting_id, name, description, age_lower, age_upper, height_lower, height_upper, requirement, language, accent, special_skills, reference_photo, sides, required_materials) 
												VALUES ('$castingid', '$nm', '$description1', '$ageL', '$ageU', '$heightL', '$heightU', '$requirement1', '$language1', '$accent1', '$special_skills1', '', '', '$required_material1')";
											mysql_query($sql); // insert role
											$rid = mysql_insert_id();

											$avail_role[] = $rid;
										} else {
											$sql = "UPDATE agency_castings_roles SET 
																description='$description1', 
																name='$nm', 
																age_lower='$ageL', 
																age_upper='$ageU',
																height_lower = '$heightL',
																height_upper = '$heightU',
																requirement = '$requirement1',
																language = '$language1',
																accent = '$accent1',
																special_skills = '$special_skills1',
																required_materials = '$required_material1'
													WHERE role_id='$rid' 
													AND casting_id='$castingid'";
											mysql_query($sql); // insert role
										}

										// GENDERS:
										// echo "<pre>";print_r($_POST['gender'][$i]);

										if (!empty($_POST['gender'][$i])) {
											foreach ($_POST['gender'][$i] as $key => $rg) {
												$sql = "INSERT INTO agency_castings_roles_vars (casting_id, role_id, var_type, var_value) VALUES ('$castingid', '$rid', 'gender', '$rg')";
												mysql_query($sql);
											}
										} else { // default all genders if none set
											$sql = "INSERT INTO agency_castings_roles_vars (casting_id, role_id, var_type, var_value) VALUES ('$castingid', '$rid', 'gender', 'M'), ('$castingid', '$rid', 'gender', 'F'), ('$castingid', '$rid', 'gender', 'Other')";

											mysql_query($sql);
										}

										// ETHNICITIES:
										if (empty($_POST['ethnicity'][$i])) {
											// $verified = false;
											// $submitmessage .= 'Oh No!  One of your roles has no ethnicity!<br />';
											// INSERT ALL ETHNICITIES
											foreach ($ethnicityarray as $key => $re) {
												$sql = "INSERT INTO agency_castings_roles_vars (casting_id, role_id, var_type, var_value) VALUES ('$castingid', '$rid', 'ethnicity', '$re')";
												mysql_query($sql);
											}
										} else {
											foreach ($_POST['ethnicity'][$i] as $key => $re) {
												$sql = "INSERT INTO agency_castings_roles_vars (casting_id, role_id, var_type, var_value) VALUES ('$castingid', '$rid', 'ethnicity', '$re')";
												mysql_query($sql);
											}
										}


										// echo $_FILES['reference_photo']['name'][0];
										// exit();

										// reference_photo

										// doc, pdf or .docx
										// if ($_FILES['rolefile']['name'][$i]) {
										// 	if (in_array(end(explode(".", strtolower($_FILES['rolefile']['name'][$i]))), $allowedExtensions)) {

										// 		$filename = preg_replace("/[^a-zA-Z0-9.]/", "", $_FILES['rolefile']['name'][$i]); 
										// 		// removes non-alphanumeric characters

										// 		$folder = 'attachments/roles/' . $rid;
										// 		if (!file_exists($folder)) { 
										// 			// if folder doesn't exist yet, create it
										// 			mkdir($folder);
										// 			chmod($folder, 0777);
										// 		}

										// 		// remove existing file
										// 		$oldfile = mysql_result(mysql_query("SELECT attachment FROM agency_castings_roles WHERE role_id='$rid'"), 0, 'attachment');
										// 		if (!empty($oldfile)) {
										// 			unlink($folder . '/' . $oldfile);
										// 		}

										// 		// put new file in folder
										// 		$newfile = $folder . '/' . $filename;
										// 		if (move_uploaded_file($_FILES['rolefile']['tmp_name'][$i], "$newfile")) {
										// 			mysql_query("UPDATE agency_castings_roles SET attachment='$filename' WHERE role_id='$rid'");
										// 		} else {
										// 			$badfile = true;
										// 		}
										// 	} else {
										// 		$badfile = true;
										// 	}
										// }



										if ($_FILES['reference_photo']['name'][$i]) {

											$file_ext = end(explode(".", strtolower($_FILES['reference_photo']['name'][$i])));

											if (in_array($file_ext, $allowedExtensions)) {

												$filename = time().'_'.preg_replace("/[^a-zA-Z0-9.]/", "", $_FILES['reference_photo']['name'][$i]); 
												// removes non-alphanumeric characters

												$folder = '../attachments/roles/' . $rid;
												if (!is_dir($folder)) { 
													// if folder doesn't exist yet, create it
													mkdir($folder);
													chmod($folder, 0777);
												}
												
												// remove existing file
												$oldfile = mysql_result(mysql_query("SELECT reference_photo FROM agency_castings_roles WHERE role_id='$rid'"), 0, 'reference_photo');
												if (!empty($oldfile)) {
													unlink($folder . '/' . $oldfile);
												}

												// put new file in folder
												$newfile = $folder . '/' . $filename;
												if (move_uploaded_file($_FILES['reference_photo']['tmp_name'][$i], "$newfile")) {
													mysql_query("UPDATE agency_castings_roles SET reference_photo='$filename' WHERE role_id='$rid'");
												} else {
													$badfile = true;
												}

												// echo $newfile;exit;

											} else {
												$badfile = true;
											}
										}

										if ($_FILES['slides']['name'][$i]) {

											$file_ext = end(explode(".", strtolower($_FILES['slides']['name'][$i])));
											if (in_array($file_ext, $allowedExtensions)) {

												$filename_s = time().'_'.preg_replace("/[^a-zA-Z0-9.]/", "", $_FILES['slides']['name'][$i]); 
												// removes non-alphanumeric characters

												$folder_s = '../attachments/roles/' . $rid;
												if (!is_dir($folder_s)) { 
													// if folder doesn't exist yet, create it
													mkdir($folder_s);
													chmod($folder_s, 0777);
												}
												
												// remove existing file
												$oldfile = mysql_result(mysql_query("SELECT sides FROM agency_castings_roles WHERE role_id='$rid'"), 0, 'sides');
												if (!empty($oldfile)) {
													unlink($folder_s . '/' . $oldfile);
												}
												
												// echo $folder_s . '/' . $filename_s;exit;
												// put new file in folder
												$newfile_s = $folder_s . '/' . $filename_s;
												if (move_uploaded_file($_FILES['slides']['tmp_name'][$i], "$newfile_s")) {
													mysql_query("UPDATE agency_castings_roles SET sides='$filename_s' WHERE role_id='$rid'");
												} else {
													$fileError = $_FILES["FILE_NAME"]["error"];
													// echo $fileError;exit;
													$badfile = true;
												}

												// echo $newfile;exit;

											} else {
												$badfile = true;
											}
										}



									} else if (!empty($rid)) { // if name is empty and there's a roleid, then delete the role
										$sql = "DELETE FROM agency_castings_roles WHERE role_id='$rid' AND casting_id='$castingid'";
										mysql_query($sql);
									}

									$i++;
								}

								// ======================  end: place into roles ===============

								// ===================remove which have click delete===============
								$avail_role = array_filter($avail_role);
								if(!empty($avail_role)){
									$sql_delete_click = "DELETE FROM agency_castings_roles WHERE role_id NOT IN ( '" . implode( "', '" , $avail_role ) . "' )  AND casting_id='$castingid'";
									mysql_query($sql_delete_click);
								}


								// =================== PROCESS ATTACHMENTS ===================

								// check for Casting attachment
								// if (!empty($_FILES['attach_casting']['name'])) {
								// 	// doc, pdf or .docx
								// 	$allowedExtensions = array("txt", "docx", "doc", "rtf", "pdf");
								// 	if (in_array(end(explode(".", strtolower($_FILES['attach_casting']['name']))), $allowedExtensions)) {
								// 		$filename = preg_replace("/[^a-zA-Z0-9.]/", "", $_FILES['attach_casting']['name']); // removes non-alphanumeric characters
								// 		$folder = 'attachments/castings/' . $castingid;

								// 		if (!file_exists($folder)) { // if folder doesn't exist yet, create it
								// 			mkdir($folder);
								// 			chmod($folder, 0777);
								// 		}

								// 		// remove existing file
								// 		$oldfile = mysql_result(mysql_query("SELECT attachment FROM agency_castings WHERE casting_id='$castingid'"), 0, 'attachment');
								// 		if (!empty($oldfile)) {
								// 			unlink($folder . '/' . $oldfile);
								// 		}

								// 		// put new file in folder
								// 		$newfile = $folder . '/' . $filename;

								// 		if (move_uploaded_file($_FILES['attach_casting']['tmp_name'], "$newfile")) {
								// 			mysql_query("UPDATE agency_castings SET attachment='$filename' WHERE casting_id='$castingid'");
								// 		} else {
								// 			$badfile = true;
								// 		}
								// 	} else {
								// 		$badfile = true;
								// 	}
								// }

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
								if (!required_fields($castingid)) {
									$sql = "UPDATE agency_castings SET live='0' WHERE casting_id='$castingid'";
									mysql_query($sql);
								}

								update_dropdowns(); // update the casting filtering dropdowns to reflect current options

								// $url = 'news.php?castingid=' . $castingid;
								$url = 'casting-list.php';
								if ($badfile) {
									echo $url = 'casting-update.php?badfile=true&casting_id=' . $castingid;
								} else if (!$verified) {
									echo $url = 'casting-update.php?casting_id=' . $castingid;
								}

								ob_end_clean(); // Delete the buffer.
								header("Location: $url");
								exit(); // Quit the script.
							} else { // If one of the data tests failed.
								$submitmessage[] = 'There was a problem submitting your casting.';
							}
						} else {
							$submitmessage[] = 'Please correct these problems';
						}
					} else {
						$submitmessage[] = 'The form appears to have been submitted already.  A form my only be submitted once.';
						$success = true;
					}
					//  echo $submitmessage;exit;
					// echo $message;

				}else {

						$job_type = array();
						$union_status = array();

						if (!empty($_GET['casting_id'])) { 
							// if there is a casting id, the we're editing this.  retrieve information from database

							$form_status = "update";

							$names = array();
							$roles = array();
							$roleids = array();
							$ageLower = array();
							$ageUpper = array();
							$gender = array();
							$ethnicity = array();
							$castingid = escape_data((int) $_GET['casting_id']);

							$query = "SELECT * FROM agency_castings WHERE casting_id='$castingid'";
							$result = @mysql_query($query);
							if ($row = @mysql_fetch_array($result, MYSQL_ASSOC)) { // If there are projects.

								// echo "<pre>";
								// print_r($row);
								// echo "</pre>";
								
								$casting_director = $row['casting_director'];
								$artist = $row['artist'];
								$company = $row['company'];
								$job_title = $row['job_title'];
								$location = $row['location'];  // LEAVE FOR NOW, WILL BE PHASED OUT

								$location_casting = array();
								if(!empty($row['location_casting'])){
									$location_casting = explode(',',$row['location_casting']);
								}

								$location_shoot = array();
								if(!empty($row['location_shoot'])){
									$location_shoot = explode(',',$row['location_shoot']);
								}

								$rate_day = $row['rate_day'];
								$rate_usage = $row['rate_usage'];
								$shoot_date_start = $row['shoot_date_start'];
								$shoot_date_end = $row['shoot_date_end'];
								$casting_date = $row['casting_date'];

								$usage_type = $row['usage_type'];
								$usage_time = $row['usage_time'];
								$usage_location = $row['usage_location'];
								// $shoot_date = $row['shoot_date'];
								$notes = $row['notes'];
								$tags = $row['tags'];
								// $date = strtotime($row['post_date']);
								$posted_on = substr($row['post_date'], 0, -3);
								$attachment_name = $row['attachment'];

								$attachment_name = $row['attachment'];

								if (!empty($row['clientalert'])) {
									$clientalert = (int) $row['clientalert'];
								} else {
									$clientalert = '';
								}

								$sql = "SELECT jobtype FROM agency_castings_jobtype WHERE casting_id='$castingid'";
								$result = mysql_query($sql);
								while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
									$job_type[] = $row['jobtype'];
								}

								$sql = "SELECT union_name FROM agency_castings_unions WHERE casting_id='$castingid'";
								$result = mysql_query($sql);
								while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
									$union_status[] = $row['union_name'];
								}

								$sql = "SELECT * FROM agency_castings_roles WHERE casting_id='$castingid'";
								$result = mysql_query($sql);
								for ($i = 0; $row = mysql_fetch_array($result, MYSQL_ASSOC); $i++) {

									// print_r($row);
									$names[] = $row['name'];
									$roles[] = $row['description'];
									$rid = $row['role_id'];
									$roleids[] = $row['role_id'];
									$rolefilename[] = $row['attachment'];
									$reference_photo[] = $row['reference_photo'];
									$sides[] = $row['sides'];
									$ageLower[] = $row['age_lower'];
									$ageUpper[] = $row['age_upper'];

									$heightLower[] = $row['height_lower'];
									$heightUpper[] = $row['height_upper'];

									$requirement[] = $row['requirement'];
									$description[] = $row['description'];
									$language[] = $row['language'];
									$accent[] = $row['accent'];
									$special_skills[] = $row['special_skills'];

									$required_material[] = explode(',',$row['required_materials']);

									$sql2 = "SELECT * FROM agency_castings_roles_vars WHERE role_id='$rid'";
									$result2 = mysql_query($sql2);
									while ($row2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
										if ($row2['var_type'] == 'gender') {
											$gender[$i][] = $row2['var_value'];
										} else if ($row2['var_type'] == 'ethnicity') {
											$ethnicity[$i][] = $row2['var_value'];
										}
									}
								}
							}

						}

				}

					// print_r($required_material);

					// else {
					// 	$success = TRUE; // tag to not show form if can't extract info from database
					// 	echo '<br /><br /><div align="center"><font size="+1" color="#990000"><b>Database error.  Please contact administrator.</b></font><br /><br /><br /></div>';
					// }
					?>
					<?php if (!empty($submitmessage)) { ?>
						<div class="alert alert-danger" role="alert" id="alert-danger-form">
							<ul>
								<?php foreach($submitmessage as $val){ ?>
									<li>
										<?php echo $val; ?>
									</li>
								<?php } ?>
				            </ul>
			            </div>
					<?php } ?>

				<?php if (!$success) { ?>
					<form enctype="multipart/form-data" action="casting-update.php<?php if (!empty($_GET['casting_id'])) echo '?casting_id=' . escape_data((int) $_GET['casting_id']); ?>" method="post" name="casting" id="cating-form" class="form-horizontal">
						<div class="row">

							<div class="col-md-6">
								<div class="box box-theme">
									<div class="box-header with-border">
						            	<h3 class="box-title">Casting Information</h3>
						            </div>
						            <div class="box-body">

						            	<div class="form-group">
						                  	<label class="col-sm-3 control-label text-right">Project Name (artist / product / campaign / magazine / etc): *</label>
						                  	<div class="col-sm-9">
						                    	<textarea name="job_title" id="job_title" class="form-control"><?php if (!empty($job_title)) echo $job_title; ?></textarea>
						                  	</div>
						                </div>

						                <div class="form-group">
						                  	<label class="col-sm-3 control-label text-right">Casting Location: *</label>
						                  	<div class="col-sm-9">

											  	<div class="row">
													<?php foreach ($locationarray as $loc) { ?>
														<div class="col-sm-12 col-md-12 col-lg-6">
															<label class="control-label">
																<input type="checkbox" class="" name="location_casting[]" value="<?php echo $loc; ?>"
																<?php if (in_array($loc, $location_casting)) echo ' checked'; ?> /> <?php echo $loc; ?> 
															</label>
														</div>
													<?php } ?>
												</div>

						                    	<!-- <select name="location_casting" id="location_casting" class="form-control" onchange="if(this.value=='Other') { document.getElementById('other_casting').style.display=''; } else { document.getElementById('other_casting').style.display='none'; }">
													<option value="">Select Casting Location</option>
													<?php
														foreach ($locationarray as $loc) {
															echo '<option value="' . $loc . '"';
															if ($loc == $location_casting) echo ' selected';
															echo '>' . $loc . '</option>';
														}
													?>
													<option value="Other" <?php if (!in_array($location_casting, $locationarray) && !empty($location_casting)) echo 'selected'; ?>>Other</option>
												</select> -->

												<!-- <div id="other_casting" style=" <?php if(in_array($location_casting, $locationarray) || empty($location_casting)) echo 'display:none'; ?>" >
										        	Please enter your Casting Location:<br />
										            <input type="text" name="location_casting_other" id="location_casting_other" class="form-control" maxlength="50" value="<?php if(!in_array($location_casting, $locationarray)) echo $location_casting; ?>">
										        </div> -->
						                  	</div>
						                </div>

						                <div class="form-group">
						                  	<label class="col-sm-3 control-label text-right">Shoot Location: *</label>
						                  	<div class="col-sm-9">

											  	<div class="row">
													<?php foreach ($locationarray as $loc) { ?>
														<div class="col-sm-12 col-md-12 col-lg-6">
															<label class="control-label">
																<input type="checkbox" class="" name="location_shoot[]" value="<?php echo $loc; ?>"
																<?php if (in_array($loc, $location_shoot)) echo ' checked'; ?> /> <?php echo $loc; ?> 
															</label>
														</div>
													<?php } ?>
												</div>

						                    	<!-- <select name="location_shoot" id="location_shoot" class="form-control" onchange="if(this.value=='Other') { document.getElementById('other_shoot').style.display=''; } else { document.getElementById('other_shoot').style.display='none'; }">
													<option value="">Select Shoot Location</option>
													<?php
														foreach ($locationarray as $loc) {
															echo '<option value="' . $loc . '"';
															if ($loc == $location_shoot) echo ' selected';
															echo '>' . $loc . '</option>';
														}
													?>
													<option value="Other" <?php if (!in_array($location_shoot, $locationarray) && !empty($location_shoot)) echo 'selected'; ?>>Other</option>
												</select> -->

												<!-- <div id="other_shoot" style="<?php if (in_array($location_shoot, $locationarray) || empty($location_shoot)) echo 'display:none'; ?>">
													Please enter your Shoot Location:<br />
													<input type="text" name="location_shoot_other" id="location_shoot_other" class="form-control" maxlength="50" value="<?php if (!in_array($location_shoot, $locationarray)) echo $location_shoot; ?>">
												</div> -->
						                  	</div>
						                </div>

						                <div class="form-group">
						                  	<label class="col-sm-3 control-label text-right">Casting Date: *</label>
						                  	<div class="col-sm-9">
						                    	<input type="text" name="casting_date" id="casting_date" class="form-control" autocomplete="off" value="<?php if (!empty($casting_date)) echo date('m/d/Y',strtotime($casting_date)); ?>"/>
						                  	</div>
						                </div>

						                <div class="form-group">
						                  	<label class="col-sm-3 control-label text-right">Shoot Date/Range: *</label>
						                  	<div class="col-sm-9">
						                  		Start:
						                    	<input type="text" name="shoot_date_start" id="shoot_date_start" autocomplete="off" value="<?php if (!empty($shoot_date_start)) echo $shoot_date_start; ?>" class="width-auto border-dotted"/>
						                    	End:
						                    	<input type="text" name="shoot_date_end" id="shoot_date_end" autocomplete="off" value="<?php if (!empty($shoot_date_end)) echo $shoot_date_end; ?>" class="width-auto border-dotted"/>
						                  	</div>
						                </div>

						                <div class="form-group">
						                  	<label class="col-sm-3 control-label text-right">Casting Director: *</label>
						                  	<div class="col-sm-9">
						                    	<select name="casting_director" id="casting_director" class="form-control">
						                    		<option value="">select</option>
						                    		<?php foreach($casting_directors_list as $val){ ?>
						                    			<option value="<?php echo $val['user_id']; ?>" <?php if (!empty($casting_director) && $casting_director == $val['user_id']){ echo "selected"; } ?>>
						                    				<?php echo $val['firstname'].' '.$val['lastname']; ?>
						                    			</option>
						                    		<?php } ?>
						                    	</select>
						                  	</div>
						                </div>

						                <div class="form-group">
						                  	<label class="col-sm-3 control-label text-right">Company/Link:</label>
						                  	<div class="col-sm-9">
						                    	<textarea name="company" id="company" class="form-control"><?php if (!empty($company)) echo $company; ?></textarea>
						                  	</div>
						                </div>

						                <div class="form-group">
						                  	<label class="col-sm-3 control-label text-right">Client/Artist:</label>
						                  	<div class="col-sm-9">
						                    	<textarea name="artist" id="artist" class="form-control"><?php if (!empty($artist)) echo $artist; ?></textarea>
						                  	</div>
						                </div>

						                <?php if (is_admin()) { ?>
							                <div class="form-group">
							                  	<label class="col-sm-3 control-label text-right">Posted On: *</label>
							                  	<div class="col-sm-9">
							                    	<input type="text" name="posted_on" id="posted_on" class="form-control" value="<?php if (!empty($posted_on)) echo $posted_on; ?>"/>
							                  	</div>
							                </div>
						                <?php } ?>

						                <div class="form-group">
						                  	<label class="col-sm-3 control-label text-right">Job Type: *</label>
						                  	<div class="col-sm-9">

						                  		<div class="row">
							                    	<?php for ($i = 0; isset($jobtypearray[$i]); $i++) { ?>
							                    		<div class="col-sm-12 col-md-12 col-lg-6">
								                    		<label class="control-label">
																<input type="checkbox" class="job_type" name="job_type[]" value="<?php echo $jobtypearray[$i]; ?>"
																<?php if (in_array($jobtypearray[$i], $job_type)) echo ' checked'; ?> /> <?php echo $jobtypearray[$i]; ?> 
															</label>
														</div>
													<?php } ?>
							                  	</div>

						                  	</div>
						                </div>
						                <div class="form-group">
						                	<div class="col-sm-3"></div> 
						                	<div class="col-sm-9">
						                		<div class="row">
													<label class="col-sm-12 control-label" style="text-align: left;">Other:</label>
													<div class="col-sm-12">
														<input type="text" name="job_type[]" class="form-control" 
															value="<?php foreach ($job_type as $jt) { if (!in_array($jt, $jobtypearray)) { echo $jt; } } ?>" 
														/>
													</div>
												</div>
											</div>
										</div>

						                <div class="form-group">
						                  	<label class="col-sm-3 control-label text-right">Union Status: *</label>
						                  	<div class="col-sm-9">

					                  			<div class="row">
							                    	<?php for($i = 0; isset($jobunionarray[$i]); $i++) { ?>
							                    		<div class="col-sm-12 col-md-12 col-lg-6">
								                    		<label class="control-label">
																<input type="checkbox" name="union_status[]" value="<?php echo $jobunionarray[$i]; ?>"
																<?php if (in_array($jobunionarray[$i], $union_status)) echo ' checked'; ?> />
																<?php echo $jobunionarray[$i]; ?>
															</label>
														</div>
													<?php } ?>
							                  	</div>

						                  	</div>
						                </div>

						                <div class="form-group">
						                  	<label class="col-sm-3 control-label text-right">Day Rate: *</label>
						                  	<div class="col-sm-9">
						                    	<textarea name="rate_day" id="rate_day" class="form-control"><?php if (!empty($rate_day)) echo $rate_day; ?></textarea>
						                  	</div>
						                </div>

						                <div class="form-group">
						                  	<label class="col-sm-3 control-label text-right">Usage Rate: *</label>
						                  	<div class="col-sm-9">
						                    	<textarea name="rate_usage" id="rate_usage" class="form-control"><?php if (!empty($rate_usage)) echo $rate_usage; ?></textarea>
						                  	</div>
						                </div>

						                <div class="form-group">
						                  	<label class="col-sm-3 control-label text-right">Usage - Type(s): *</label>
						                  	<div class="col-sm-9">
						                    	<textarea name="usage_type" id="usage_type" class="form-control"><?php if (!empty($usage_type)) echo $usage_type; ?></textarea>
						                  	</div>
						                </div>

						                <div class="form-group">
						                  	<label class="col-sm-3 control-label text-right">Usage - Term: *</label>
						                  	<div class="col-sm-9">
						                    	<textarea name="usage_time" id="usage_time" class="form-control"><?php if (!empty($usage_time)) echo $usage_time; ?></textarea>
						                  	</div>
						                </div>

						                <div class="form-group">
						                  	<label class="col-sm-3 control-label text-right">Usage - Area: *</label>
						                  	<div class="col-sm-9">
						                    	<textarea name="usage_location" id="usage_location" class="form-control"><?php if (!empty($usage_location)) echo $usage_location; ?></textarea>
						                  	</div>
						                </div>

						                <div class="form-group">
						                  	<label class="col-sm-3 control-label text-right">Notes: *</label>
						                  	<div class="col-sm-9">
						                    	<textarea name="notes" id="notes" class="form-control"><?php if (!empty($notes)) echo $notes; ?></textarea>
						                  	</div>
						                </div>

						                <div class="form-group">
						                  	<label class="col-sm-3 control-label text-right">Tags: *</label>
						                  	<div class="col-sm-9">
						                    	<textarea name="tags" id="tags" class="form-control"><?php if (!empty($tags)) echo $tags; ?></textarea>
						                    	<label class="text-alert"><i class="fa fa-bell"></i> Note: Enter tag with comma separator. Ex.tag1,tag2,tag3 </label>
						                  	</div>
						                </div>

						                <!-- <div class="form-group">
						                  	<label class="col-sm-3 control-label text-right">Add .doc, docx or .pdf attachment:</label>
						                  	<div class="col-sm-9">
						                    	<?php
						       					//              		if(isset($castingid)) {
													// 	if(file_exists('attachments/castings/' . $castingid . '/' . $attachment_name)) {
													// 		echo '<a href="attachments/castings/' . $castingid . '/' . $attachment_name . '" target="_blank">view attachment</a>&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="deleteattachment" style="width:10px"> check to delete<br /><br />';
													// 	}
													// }
												?>
												<input type="file" name="attach_casting" id="attach_casting" class="form-control" />
												<label class="text-alert"><i class="fa fa-bell"></i> must be less than 500K</label>
						                  	</div>
						                </div> -->

						                <?php if (is_admin()) { ?>
						                	<div class="form-group">
							                	<label class="col-sm-3 control-label text-right">Send notification when number of submissions reaches:</label>
							                	<div class="col-sm-9">
													<input type="text" name="clientalert" id="clientalert" maxlength="3" value="<?php if (!empty($clientalert)) echo $clientalert; ?>" class="form-control"/>
												</div>
											</div>
										<?php } ?>

						            </div>

						            <div class="box-footer">
										<input type="hidden" name="MAX_FILE_SIZE" value="1000000" />
										<input type="hidden" name="stopdouble" value="<?php $_SESSION['stopdouble'] = time(); echo $_SESSION['stopdouble']; ?>" />
										<input type="hidden" value="<?php echo time(); ?>" name="creation_time" />
										<input type="hidden" value="<?php echo agency_add_form_key('casting'); ?>" name="form_token" />
										<div class="text-right">
											<a type="button" href="casting-list.php" class="btn btn-default">Cancel</a>
											<!-- <input type="button" value="Submit" name="submit" class="btn btn-theme btn-submit"/> -->
											<button type="button" class="btn btn-theme btn-submit">Submit</button>
										</div>
						            </div>
									
								</div>
							</div>

							<div class="col-md-6">
								<div class="box box-theme role_description_box" id="role_description_box">
									<div class="box-header with-border">
						            	<h3 class="box-title">Role Descriptions</h3>
						            </div>

						            <div class="box-body with-border">
										<label class="col-sm-12 text-center text-alert"><i class="fa fa-bell"></i> Note: optional: attach a .gif, .jpg or .pdf (&lt; 500K) for each role </label>
									</div>

									<?php if($form_status == 'normal'){ ?>
							            <div class="box-body with-border" id="role_box_0">
							            	<div class="col-sm-11">

							            		<div class="form-group">
								                  	<label class="col-sm-3 control-label text-right">Character Name: *</label>
								                  	<div class="col-sm-9">
								                    	<input type="text" name="name[0]" class="form-control"/>
								                  	</div>
								                </div>

								                <div class="form-group">
								                  	<label class="col-sm-3 control-label text-right">Age Range: *</label>
								                  	<div class="col-sm-9">
							                    		From:   <select name="ageLower[0]" class="width-auto border-dotted">
																	<?php
																	$maxage = 100; 
																	for ($j = 0; $j <= $maxage; $j++) {
																		echo '<option value="' . $j . '"';
																		echo '>' . $j . '</option>';
																	}
																	?>
																</select>
															&nbsp;&nbsp;&nbsp;
															To: <select name="ageUpper[0]" class="width-auto border-dotted">
																	<?php
																	for ($j = 0; $j <= $maxage; $j++) {
																		echo '<option value="' . $j . '"';
																		echo '>' . $j . '</option>';
																	}
																	?>
																</select>
								                  	</div>
								                </div>

								                <div class="form-group">
								                  	<label class="col-sm-3 control-label text-right">Gender: *</label>
								                  	<div class="col-sm-9">
							                  			<label>
							                    			<input type="checkbox" name="gender[0][]" value="M" />M &nbsp;&nbsp;&nbsp;&nbsp;
							                    		</label>
							                    		<label>
															<input type="checkbox" name="gender[0][]" value="F" />F &nbsp;&nbsp;&nbsp;&nbsp;
														</label>
														<label>
															<input type="checkbox" name="gender[0][]" value="Transgender" />Transgender &nbsp;&nbsp;&nbsp;&nbsp;
														</label>
														<!-- <label>
															<input type="checkbox" name="gender[0][]" value="Other" />Other
														</label> -->
								                  	</div>
								                </div>

								                <div class="form-group">
								                	<label class="col-sm-3 control-label text-right">Ethnicity: *</label>

													<div class="col-sm-9">
															<div class="row">
																<?php
																	if (identical_values($ethnicityarray, $ethnicity[0])) { // all ethnicities have been set
																		$allflag = true;
																	} else {
																		$allflag = false;
																	}
																	$ethboxes = '';
																	foreach ($ethnicityarray as $key => $e) {
																		$ethboxes .= '<label class="col-sm-6"><input type="checkbox" name="ethnicity[0][]" value="' . $e . '"';
																		$ethboxes .= ' class="ethnicity_0" />' . $e . '</label>';
																	}
																?>
																<label class="col-sm-12">
																	<input type="checkbox" name="ethnicity[0][]" id="all_ethnicity_0" class="all_ethnicity" value="All Ethnicities" />All Ethnicities
																</label>

																<?php echo $ethboxes; ?>
															</div>
													</div>
												</div>

												<div class="form-group">
								                  	<label class="col-sm-3 control-label text-right">Height: (Inch) *</label>
								                  	<div class="col-sm-9">
							                    		From:   <select name="heightLower[0]" class="width-auto border-dotted">
																	<?php
																		for ($j = 10; $j <= 100; $j++) {
																			echo '<option value="' . $j . '"';
																			echo '>' . $j . '</option>';
																		}
																	?>
																</select>
																&nbsp;&nbsp;&nbsp;&nbsp;
															To: <select name="heightUpper[0]" class="width-auto border-dotted">
																	<?php
																		for ($j = 10; $j <= 100; $j++) {
																			echo '<option value="' . $j . '"';
																			echo '>' . $j . '</option>';
																		}
																	?>
																</select>
								                  	</div>
								                </div>

								                <div class="form-group">
													<label class="col-sm-3 text-right">Requirement:</label>
													<div class="col-sm-9">
														<textarea name="requirement[0]" class="form-control"></textarea>
													</div>
												</div>

												<div class="form-group">
													<label class="col-sm-3 text-right">Description: *</label>
													<div class="col-sm-9">
														<textarea name="description[0]" class="form-control"></textarea>
													</div>
												</div>

												<div class="form-group">
													<label class="col-sm-3 text-right">Language:</label>
													<div class="col-sm-9">
														<textarea name="language[0]" class="form-control"></textarea>
													</div>
												</div>

												<div class="form-group">
													<label class="col-sm-3 text-right">Accent:</label>
													<div class="col-sm-9">
														<textarea name="accent[0]" class="form-control"></textarea>
													</div>
												</div>

												<div class="form-group">
													<label class="col-sm-3 text-right">Special Skills:</label>
													<div class="col-sm-9">
														<textarea name="special_skills[0]" class="form-control"></textarea>
													</div>
												</div>

												<div class="form-group">
													<label class="col-sm-3 text-right">Reference Photo: </label>
													<div class="col-sm-9">
														<input type="file" name="reference_photo[0]" class="form-control" />
													</div>
												</div>

												<div class="form-group">
													<label class="col-sm-3 text-right">Sides:</label>
													<div class="col-sm-9">
														<input type="file" name="slides[0]" class="form-control"/>
													</div>
												</div>

												<div class="form-group">
													<label class="col-sm-3 text-right">Required materials: *</label>
													<div class="col-sm-9">
														<?php foreach($required_material_Ary as $rm){ ?>
															<label><input type="checkbox" name="required_material[0][]" value="<?php echo $rm; ?>" class="" /> <?php echo $rm; ?></label>&nbsp;&nbsp;
														<?php } ?>
													</div>
												</div>

											</div>
											<div class="col-sm-1">
											</div>
										</div>
									<?php }elseif($form_status == 'submit' || $form_status == 'update'){ ?>
										
										<?php foreach($names as $key_r=>$val_r){ ?>

											<div class="box-body with-border" id="role_box_<?php echo $key_r; ?>">
								            	<div class="col-sm-11">

								            		<input type="hidden" name="roleid[<?php echo $key_r; ?>]" value="<?php if (!empty($roleids[$key_r])) echo $roleids[$key_r]; ?>">
								            		<div class="form-group">
									                  	<label class="col-sm-3 control-label text-right">Character Name: *</label>
									                  	<div class="col-sm-9">
									                    	<input type="text" name="name[<?php echo $key_r; ?>]" value="<?php if (!empty($names[$key_r])) echo $names[$key_r]; ?>" class="form-control"/>
									                  	</div>
									                </div>

									                <div class="form-group">
									                  	<label class="col-sm-3 control-label text-right">Age Range: *</label>
									                  	<div class="col-sm-9">
								                    		From:   <select name="ageLower[<?php echo $key_r; ?>]" class="width-auto border-dotted">
																		<?php
																		$maxage = 100; // define upper limit here for age dropdowns
																		for ($j = 0; $j <= $maxage; $j++) {
																			echo '<option value="' . $j . '"';
																			if (!empty($ageLower[$key_r])) {
																				if ($ageLower[$key_r] == $j) {
																					echo ' selected="selected"';
																				}
																			}
																			echo '>' . $j . '</option>';
																		}
																		?>
																	</select>
																&nbsp;&nbsp;&nbsp;
																To: <select name="ageUpper[<?php echo $key_r; ?>]" class="width-auto border-dotted">
																		<?php
																		for ($j = 0; $j <= $maxage; $j++) {
																			echo '<option value="' . $j . '"';
																			if (!empty($ageUpper[$key_r])) {
																				if ($ageUpper[$key_r] == $j) {
																					echo ' selected="selected"';
																				}
																			} else if ($j == $maxage) {
																				echo ' selected="selected"';
																			}
																			echo '>' . $j . '</option>';
																		}
																		?>
																	</select>
									                  	</div>
									                </div>

									                <div class="form-group">
									                  	<label class="col-sm-3 control-label text-right">Gender: *</label>
									                  	<div class="col-sm-9">
								                  			<label>
								                    			<input type="checkbox" name="gender[<?php echo $key_r; ?>][]" value="M" <?php if (!empty($gender[$key_r])) if (in_array('M', $gender[$key_r])) echo 'checked'; ?> />M &nbsp;&nbsp;&nbsp;&nbsp;
								                    		</label>
								                    		<label>
																<input type="checkbox" name="gender[<?php echo $key_r; ?>][]" value="F" <?php if (!empty($gender[$key_r])) if (in_array('F', $gender[$key_r])) echo 'checked'; ?> />F &nbsp;&nbsp;&nbsp;&nbsp;
															</label>
															<label>
																<input type="checkbox" name="gender[<?php echo $key_r; ?>][]" value="Transgender" <?php if (!empty($gender[$key_r])) if (in_array('Transgender', $gender[$key_r])) echo 'checked'; ?> />Transgender &nbsp;&nbsp;&nbsp;&nbsp;
															</label>
															<!-- <label>
																<input type="checkbox" name="gender[<?php //echo $key_r; ?>][]" value="Other" <?php //if (!empty($gender[$key_r])) if (in_array('Other', $gender[$key_r])) echo 'checked'; ?> />Other
															</label> -->
									                  	</div>
									                </div>

									                <div class="form-group">
									                	<label class="col-sm-3 control-label text-right">Ethnicity: *</label>
										                <?php
															if (!empty($names[$key_r]) && empty($ethnicity[$key_r])) {
																$ethnicityRed = true;
															} else {
																$ethnicityRed = false;
															}
														?>

														<div class="col-sm-9">
															<div class="row">
																<?php
																	if (identical_values($ethnicityarray, $ethnicity[0])) { // all ethnicities have been set
																		$allflag = true;
																	} else {
																		$allflag = false;
																	}
																	$ethboxes = '';
																	foreach ($ethnicityarray as $key => $e) {
																		$ethboxes .= '<label class="col-sm-6"><input id="0_' . $key . '" type="checkbox" name="ethnicity['.$key_r.'][]" value="' . $e . '"';
																		if (!empty($ethnicity[$key_r])) {
																			if (in_array($e, $ethnicity[$key_r])) {
																				$ethboxes .= 'checked';
																			} else {
																				// $allflag = false;
																			}
																		} 

																		$ethboxes .= ' class="ethnicity_'.$key_r.'" />' . $e . '</label>';
																	}
																?>
																<label class="col-sm-12">
																	<input type="checkbox" name="ethnicity[<?php echo $key_r; ?>][]" id="all_ethnicity_<?php echo $key_r; ?>" class="all_ethnicity" value="All Ethnicities" <?php if (!empty($ethnicity[$key_r])) { if (in_array("All Ethnicities", $ethnicity[$key_r])) { echo 'checked'; } } ?> />All Ethnicities
																</label>

																<?php echo $ethboxes; ?>
															</div>
														</div>
													</div>

													<div class="form-group">
									                  	<label class="col-sm-3 control-label text-right">Height: (Inch) *</label>
									                  	<div class="col-sm-9">
								                    		From:   <select name="heightLower[<?php echo $key_r; ?>]" class="width-auto border-dotted">
																		<?php
																			for ($j = 10; $j <= 100; $j++) {
																				echo '<option value="' . $j . '"';
																				if (!empty($heightLower[$key_r])) {
																					if ($heightLower[$key_r] == $j) {
																						echo ' selected="selected"';
																					}
																				}
																				echo '>' . $j . '</option>';
																			}
																		?>
																	</select>
																	&nbsp;&nbsp;&nbsp;&nbsp;
																To: <select name="heightUpper[<?php echo $key_r; ?>]" class="width-auto border-dotted">
																		<?php
																			for ($j = 10; $j <= 100; $j++) {
																				echo '<option value="' . $j . '"';
																				if (!empty($heightUpper[$key_r])) {
																					if ($heightUpper[$key_r] == $j) {
																						echo ' selected="selected"';
																					}
																				} else if ($j == $maxage) {
																					echo ' selected="selected"';
																				}
																				echo '>' . $j . '</option>';
																			}
																		?>
																	</select>
									                  	</div>
									                </div>

									                <div class="form-group">
														<label class="col-sm-3 text-right">Requirement:</label>
														<div class="col-sm-9">
															<textarea name="requirement[<?php echo $key_r; ?>]" class="form-control"><?php if (!empty($requirement[$key_r])){ echo $requirement[$key_r]; } ?></textarea>
														</div>
													</div>

													<div class="form-group">
														<label class="col-sm-3 text-right">Description: *</label>
														<div class="col-sm-9">
															<textarea name="description[<?php echo $key_r; ?>]" class="form-control"><?php if (!empty($description[$key_r])){ echo $description[$key_r]; } ?></textarea>
														</div>
													</div>

													<div class="form-group">
														<label class="col-sm-3 text-right">Language:</label>
														<div class="col-sm-9">
															<textarea name="language[<?php echo $key_r; ?>]" class="form-control"><?php if (!empty($language[$key_r])){ echo $language[$key_r]; } ?></textarea>
														</div>
													</div>

													<div class="form-group">
														<label class="col-sm-3 text-right">Accent:</label>
														<div class="col-sm-9">
															<textarea name="accent[<?php echo $key_r; ?>]" class="form-control"><?php if (!empty($accent[$key_r])){ echo $accent[$key_r]; } ?></textarea>
														</div>
													</div>

													<div class="form-group">
														<label class="col-sm-3 text-right">Special Skills:</label>
														<div class="col-sm-9">
															<textarea name="special_skills[<?php echo $key_r; ?>]" class="form-control"><?php if (!empty($special_skills[$key_r])){ echo $special_skills[$key_r]; } ?></textarea>
														</div>
													</div>

													<div class="form-group">
														<label class="col-sm-3 text-right">Reference Photo: </label>
														<div class="col-sm-9">
															<input type="file" name="reference_photo[<?php echo $key_r; ?>]" class="form-control" />
															<?php 
																if(!empty($reference_photo[$key_r])){ 
																	echo '<a href="../attachments/roles/' . $roleids[$key_r] . '/' . $reference_photo[$key_r] . '" target="_blank">view attachment</a>&nbsp;&nbsp;&nbsp;&nbsp;
																		<label><input type="checkbox" name="reference_photo_del[' . $roleids[$key_r] . ']"> check to delete</label>';
																}
															?>
														</div>
													</div>

													<div class="form-group">
														<label class="col-sm-3 text-right">Sides:</label>
														<div class="col-sm-9">
															<input type="file" name="slides[<?php echo $key_r; ?>]" class="form-control" />
															<?php 
																if(!empty($sides[$key_r])){ 
																	echo '<a href="../attachments/roles/' . $roleids[$key_r] . '/' . $sides[$key_r] . '" target="_blank">view attachment</a>&nbsp;&nbsp;&nbsp;&nbsp;
																		<label><input type="checkbox" name="sides_del[' . $roleids[$key_r] . ']"> check to delete</label>';
																}
															?>
														</div>
													</div>

													<div class="form-group">
														<label class="col-sm-3 text-right">Required materials: *</label>
														<div class="col-sm-9">
															<?php foreach($required_material_Ary as $rm){ ?>
																<label>
																	<input type="checkbox" name="required_material[<?php echo $key_r; ?>][]" value="<?php echo $rm; ?>" class="" <?php if(in_array($rm,$required_material[$key_r])){ echo "checked"; } ?> /> <?php echo $rm; ?></label>&nbsp;&nbsp;
															<?php } ?>
														</div>
													</div>

												</div>
												<div class="col-sm-1">
													<?php if($key_r > 0){ ?>
														<button class="btn btn-danger remove_role_btn"><i class="fa fa-minus"></i></button>
													<?php } ?>
												</div>
											</div>

										<?php } ?>

									<?php } ?>

									<div class="box-footer">
										<button class="pull-right add_role_btn btn btn-info" id="add_role_btn"><i class="fa fa-plus"></i></button>
									</div>
								</div>
							</div>

						</div>
					</form>
				<?php } ?>
			

		</div>
	<!-- </div> -->
</div>
<?php include('footer_js.php'); ?>

<script type="text/javascript" src="https://code.jquery.com/ui/1.11.0/jquery-ui.min.js"></script>
<script type="text/javascript" src="../dashboard/assets/jQuery-DateTimepicker-Addon/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript" src="../dashboard/assets/jQuery-DateTimepicker-Addon/i18n/jquery-ui-timepicker-addon-i18n.min.js"></script>
<script type="text/javascript" src="../dashboard/assets/jQuery-DateTimepicker-Addon/jquery-ui-sliderAccess.js"></script>

<!-- <script type="text/javascript" src="../dashboard/assets/bootstrap-tagsinput-latest/dist/bootstrap-tagsinput.min.js"></script> -->

<script type="text/javascript">

	// $('#tags').tagsinput({
	// 	tagClass: 'big'
	// });
	
	$('#casting_date').datepicker({
    	changeMonth: true,
    	changeYear: true,
    	minDate: 0,
    });

	 // $('#shoot_date_start').datepicker({
	 //    	changeMonth: true,
	 //    	changeYear: true,
	 //    	minDate: 0,
	 //    });

	$("#shoot_date_start").datepicker({
        // dateFormat: "dd-M-yy",
        changeMonth: true,
	    changeYear: true,
        minDate: 0,
        onSelect: function () {
            var dt2 = $('#shoot_date_end');
            var startDate = $(this).datepicker('getDate');
            // startDate.setDate(startDate.getDate() + 30);
            var minDate = $(this).datepicker('getDate');
            var dt2Date = dt2.datepicker('getDate');
            // var dateDiff = (dt2Date - minDate)/(86400 * 1000);

            // if (dt2Date == null || dateDiff < 0) {
            //         dt2.datepicker('setDate', minDate);
            // }else if (dateDiff > 30){
            //         dt2.datepicker('setDate', startDate);
            // }
            // dt2.datepicker('option', 'maxDate', startDate);
            dt2.datepicker('option', 'minDate', minDate);
        }
    });

    $('#shoot_date_end').datepicker({
        // dateFormat: "dd-M-yy",
        changeMonth: true,
	    changeYear: true,
        minDate: 0
    });

    $('#posted_on').datetimepicker({
        dateFormat: "yy-mm-dd",
        changeMonth: true,
	    changeYear: true,
        // minDate: 0
    });

	$(".add_role_btn").click(function(e){
		e.preventDefault();
		// id_str_last = $("#role_description_box > .box-body:last-child" ).attr('id');
		id_str_last = $('#role_description_box').children('.box-body').last().attr('id');
		id_str_last_ary = id_str_last.split('_');
		last_num = id_str_last_ary[2];
		new_num = parseInt(last_num) + 1;

		html1 = "";
		html1 += '<div class="box-body with-border" id="role_box_'+new_num+'">'+
					'<div class="col-sm-11">'+
						'<div class="form-group">'+
							'<label class="col-sm-3 control-label text-right">Character Name: *</label>'+
							'<div class="col-sm-9">'+
								'<input type="text" name="name['+new_num+']" value="" class="form-control"/>'+
							'</div>'+
						'</div>'+

						'<div class="form-group">'+
                  			'<label class="col-sm-3 control-label text-right">Age Range: *</label>'+
              				'<div class="col-sm-9">'+
                				'From:   <select name="ageLower['+new_num+']" class="width-auto border-dotted">'+
									'<?php
										$maxage = 100;
										for ($j = 0; $j <= $maxage; $j++) {
											echo '<option value="' . $j . '"';
											echo '>' . $j . '</option>';
										}
									?>'+
								'</select>'+
								'&nbsp;&nbsp;&nbsp;'+
								'To: <select name="ageUpper['+new_num+']" class="width-auto border-dotted">'+
									'<?php
										for ($j = 0; $j <= $maxage; $j++) {
											echo '<option value="' . $j . '"';
											echo '>' . $j . '</option>';
										}
									?>'+
								'</select>'+
                  			'</div>'+
                		'</div>'+

                		'<div class="form-group">'+
                  			'<label class="col-sm-3 control-label text-right">Gender: *</label>'+
		                  	'<div class="col-sm-9">'+
		                  			'<label>'+
		                    			'<input type="checkbox" name="gender['+new_num+'][]" value="M"/>M &nbsp;&nbsp;&nbsp;&nbsp;'+
		                    		'</label>'+
		                    		'<label>'+
										'<input type="checkbox" name="gender['+new_num+'][]" value="F"/>F &nbsp;&nbsp;&nbsp;&nbsp;'+
									'</label>'+
									'<label>'+
										'<input type="checkbox" name="gender['+new_num+'][]" value="Transgender" />Transgender &nbsp;&nbsp;&nbsp;&nbsp;'+
									'</label>'+
									// '<label>'+
									// 	'<input type="checkbox" name="gender['+new_num+'][]" value="Other" />Other'+
									// '</label>'+
		                  	'</div>'+
                		'</div>'+

                		'<div class="form-group">'+
		                	'<label class="col-sm-3 control-label text-right">Ethnicity: *</label>'+
							'<div class="col-sm-9">'+
								'<div class="row">'+
									'<label class="col-sm-12">'+
										'<input type="checkbox" name="ethnicity['+new_num+'][]" id="all_ethnicity_'+new_num+'" class="all_ethnicity" />All Ethnicities'+
									'</label>';

									<?php foreach ($ethnicityarray as $key => $e) { ?>
										// val = '<?php //echo $e ?>'.replace(/[!"#$%&'()*+,.\/:;<=>?@[\\\]^`{|}~]/g, "\\\\$&");
										val1 = '<?php echo $e ?>';
										// val1 = val.replace(/\//g, "-");
										// console.log(val1);
										html1 +=	'<label class="col-sm-6"><input type="checkbox" name="ethnicity['+new_num+'][]" value="'+val1+'" class="ethnicity_'+new_num+'" value="All Ethnicities"/>'+val1+'</label>';
									<?php } ?>
								
								html1 += '</div>'+
							'</div>'+
						'</div>'+

						'<div class="form-group">'+
		                  	'<label class="col-sm-3 control-label text-right">Height: (Inch) *</label>'+
		                  	'<div class="col-sm-9">'+
		                		'From:   <select name="heightLower['+new_num+']" class="width-auto border-dotted">'+
											'<?php
												for ($j = 10; $j <= 100; $j++) {
													echo '<option value="' . $j . '"';
													echo '>' . $j . '</option>';
												}
											?>'+
										'</select>'+
										'&nbsp;&nbsp;&nbsp;&nbsp;'+
								'To: <select name="heightUpper[0]" class="width-auto border-dotted">'+
										'<?php
											for ($j = 10; $j <= 100; $j++) {
												echo '<option value="' . $j . '"';
												echo '>' . $j . '</option>';
											}
										?>'+
									'</select>'+
		                  	'</div>'+
		                '</div>'+

		                '<div class="form-group">'+
							'<label class="col-sm-3 text-right">Requirement:</label>'+
							'<div class="col-sm-9">'+
								'<textarea name="requirement['+new_num+']" class="form-control"></textarea>'+
							'</div>'+
						'</div>'+

						'<div class="form-group">'+
							'<label class="col-sm-3 text-right">Description: *</label>'+
							'<div class="col-sm-9">'+
								'<textarea name="description['+new_num+']" class="form-control"></textarea>'+
							'</div>'+
						'</div>'+

						'<div class="form-group">'+
							'<label class="col-sm-3 text-right">Language:</label>'+
							'<div class="col-sm-9">'+
								'<textarea name="language['+new_num+']" class="form-control"></textarea>'+
							'</div>'+
						'</div>'+

						'<div class="form-group">'+
							'<label class="col-sm-3 text-right">Accent:</label>'+
							'<div class="col-sm-9">'+
								'<textarea name="accent['+new_num+']" class="form-control"></textarea>'+
							'</div>'+
						'</div>'+

						'<div class="form-group">'+
							'<label class="col-sm-3 text-right">Special Skills:</label>'+
							'<div class="col-sm-9">'+
								'<textarea name="special_skills['+new_num+']" class="form-control"></textarea>'+
							'</div>'+
						'</div>'+

						'<div class="form-group">'+
							'<label class="col-sm-3 text-right">Reference Photo: </label>'+
							'<div class="col-sm-9">'+
								'<input type="file" name="reference_photo['+new_num+']" class="form-control" />'+
							'</div>'+
						'</div>'+

						'<div class="form-group">'+
							'<label class="col-sm-3 text-right">Sides:</label>'+
							'<div class="col-sm-9">'+
								'<input type="file" name="slides['+new_num+']" class="form-control" />'+
							'</div>'+
						'</div>'+

						'<div class="form-group">'+
							'<label class="col-sm-3 text-right">Required materials: *</label>'+
							'<div class="col-sm-9">'+
								<?php foreach($required_material_Ary as $rm){ ?>
									'<label><input type="checkbox" name="required_material['+new_num+'][]" value="<?php echo $rm; ?>" class="" /> <?php echo $rm; ?></label>&nbsp;&nbsp;'+
								<?php } ?>
							'</div>'+
						'</div>'+

					'</div>'+
					'<div class="col-sm-1">'+
						'<button class="btn btn-danger remove_role_btn"><i class="fa fa-minus"></i></button>'+
					'</div>'+
				'</div>';

		$("#"+id_str_last).after(html1);
		// console.log(last_num);
	});

	$(document).on("click", ".remove_role_btn" , function(e) {
	 	e.preventDefault();
        $(this).parents('.box-body').remove();
    });

	$(".btn-submit").click(function(e){
		// e.preventDefault();
		// console.log('ddd');
		$("#cating-form").submit();
		// document.casting.submit()
		// document.getElementById("AGENCYcastingform").submit();
	});

	$(document).on("change", ".all_ethnicity" , function() {
		ethnicity_all_id_str = $(this).attr('id');
		ethnicity_all_id_ary = ethnicity_all_id_str.split('_');
		num = ethnicity_all_id_ary[2];

        if($(this).is(":checked")) {
            $('.ethnicity_'+num).prop("checked",true);
        }else{
			$('.ethnicity_'+num).prop("checked",false);
        }       
    });


</script>
<?php include('footer.php'); ?>