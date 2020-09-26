<?php
session_start(); // for temporary login, a session is needed
@include('includes/header.php');

if (agency_account_type() == 'client') {

	if (is_active()) { // check if user is logged in
		$profileid = $_SESSION['user_id'];
	}

	if (!is_active() && isset($_SESSION['user_id'])) {
		$waitingClient = showbox('waitingClient');
		if ($waitingClient) {
			echo '<div class="AGENCYsubmitmessage" style="text-align:left">' . $waitingClient . '</div>';
		}

		// collect the info
		$userid = (int) $_SESSION['user_id'];
		$query = "SELECT * FROM forum_users, agency_profiles WHERE agency_profiles.user_id=forum_users.user_id AND forum_users.user_id='$userid'"; // check to see if name already used.
		$result = @mysql_query($query);
		if ($row = @mysql_fetch_array($result, MYSQL_ASSOC)) { // If there are projects.
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

?>
			<div align="center" style="font-size:12px">
				<table border="0" cellpadding="3" cellspacing="3">
					<tr>
						<td class="AGENCYregtableleft">First Name:</td>
						<td class="AGENCYregtableright"><?php if (!empty($firstname)) echo $firstname; ?>
						</td>
					</tr>
					<tr>
						<td class="AGENCYregtableleft">Last Name:</td>
						<td class="AGENCYregtableright"><?php if (!empty($lastname)) echo $lastname; ?>
						</td>
					</tr>
					<tr>
						<td class="AGENCYregtableleft">Company:</td>
						<td class="AGENCYregtableright"><?php if (!empty($company)) echo $company; ?>
						</td>
					</tr>
					<tr>
						<td class="AGENCYregtableleft">Profession:</td>
						<td class="AGENCYregtableright"><?php if (!empty($profession)) echo $profession; ?>
						</td>
					</tr>
					<tr>
						<td class="AGENCYregtableleft">Link:</td>
						<td class="AGENCYregtableright"><?php if (!empty($link)) echo $link; ?>
						</td>
					</tr>
					<tr>
						<td class="AGENCYregtableleft">Email Address:</td>
						<td class="AGENCYregtableright"><?php if (!empty($email)) echo $email; ?>
						</td>
					</tr>
					<tr>
						<td class="AGENCYregtableleft">Phone Number:</td>
						<td class="AGENCYregtableright"><?php if (!empty($phone)) echo $phone; ?>
						</td>
					</tr>
					<tr>
						<td class="AGENCYregtableleft">City:</td>
						<td class="AGENCYregtableright"><?php if (!empty($city)) echo $city; ?>
						</td>
					</tr>
					<tr>
						<td class="AGENCYregtableleft">State/Province:</td>
						<td class="AGENCYregtableright"><?php if (!empty($state)) echo $state; ?>
						</td>
					</tr>

					<tr>
						<td class="AGENCYregtableleft">Country:</td>
						<td class="AGENCYregtableright"><?php if (!empty($country)) echo $country; ?>
						</td>
					</tr>
					<td class="AGENCYregtableleft">Types of Castings:</td>
					<td class="AGENCYregtableright"><?php echo implode(', ', $castings); ?>
					</td>
					</tr>
					<tr>
						<td class="AGENCYregtableleft">Notes:</td>
						<td class="AGENCYregtableright"><?php if (!empty($note)) echo $note; ?>
						</td>
					</tr>
				</table>
				<br />
				<a href="myaccount.php">Please click here to edit or review your account information.</a>
			</div>
			<?php
		}
	}

	if (isset($profileid)) {


		$mode = false;
		$varemail = "bookings@theagencyonline.com";
		$varphone = "212-944-0801";

		if (!empty($_GET['mode'])) {
			echo '<div style="width:650px; float:left">';
			$mode = escape_data($_GET['mode']);
			switch ($mode) {
				case "search": // Search
					$clientsearch = showbox('clientsearch');
					if (!empty($clientsearch)) {
						echo '<div class="AGENCYsubmitmessage">' . $clientsearch . '</div>';
					}
					echo '<div class="AGENCY_ClientPageTitle" style="padding-top:0">Talent Search</div>';

					include('includes/search_advanced.php');

					break;
				case "castings": // CASTINGS
					echo '<div class="AGENCY_ClientPageTitle">Manage Castings</div>';
					if (!empty($_GET['deletecastingid'])) { // DELETE CASTING
						$deletecastingid = $_GET['deletecastingid'];
						if (!is_admin()) { // if not admin, make sure user has permission to delete this casting
							$query = "SELECT * FROM agency_castings WHERE casting_id='$deletecastingid' AND posted_by='$profileid'";
							$result = @mysql_query($query);
							if (@mysql_num_rows($result) == 0) { // If user does not access to project
								$deletecastingid = false;
							}
						}
						if ($deletecastingid) {
							$sql = "SELECT * FROM agency_castings_roles WHERE casting_id='$deletecastingid'";
							$result = mysql_query($sql);
							while ($row = sql_fetchrow($result)) {
								$roleid = $row['role_id'];
								$sql = "DELETE FROM agency_mycastings WHERE role_id='$roleid'";
								mysql_query($sql);
							}
							$sql = "DELETE FROM agency_castings WHERE casting_id='$deletecastingid'";
							mysql_query($sql);
							$sql = "DELETE FROM agency_castings_roles WHERE casting_id='$deletecastingid'";
							mysql_query($sql);
							$sql = "DELETE FROM agency_castings_jobtype WHERE casting_id='$deletecastingid'";
							mysql_query($sql);
							$sql = "DELETE FROM agency_castings_unions WHERE casting_id='$deletecastingid'";
							mysql_query($sql);

							$sql = "SELECT lightbox_id FROM agency_lightbox WHERE casting_id='$deletecastingid'";
							$result = mysql_query($sql);
							while ($row = sql_fetchrow($result)) {
								$lightbox_id = $row['lightbox_id'];
								$sql = "UPDATE agency_lightbox_users SET role_id=NULL WHERE lightbox_id='$lightbox_id'";
								mysql_query($sql);
							}
							$sql = "UPDATE agency_lightbox SET casting_id=NULL WHERE casting_id='$deletecastingid'";
							mysql_query($sql);

							update_dropdowns();
						}
						$url = 'clienthome.php';
						ob_end_clean(); // Delete the buffer.
						header("Location: $url");
						exit(); // Quit the script.
					}

					if (!empty($_GET['castingid'])) { // LIST CASTING ROLE SUBMISSIONS
						$castingid = $_GET['castingid'];
						setcookie("lightbox", "", time() - 3600000);

						// CULL
						if (!empty($_GET['cull'])) {
							auto_cull($castingid);
						}


						// REMOVE FROM ROLE
						if (!empty($_POST['removefromrole']) && !empty($_POST['submissions'])) {
							$rid = (int) escape_data($_POST['removefromrole']);
							$uids = array();
							$uids = $_POST['submissions'];
							foreach ($uids as $value) {
								$query = "UPDATE agency_mycastings SET removed='1' WHERE role_id='$rid' AND user_id='$value'";
								mysql_query($query);
							}
						}

						// RE-SUBMIT TO ROLE
						if (!empty($_GET['addtorole']) && !empty($_GET['user'])) {
							$rid = (int) escape_data($_GET['addtorole']);
							$uid = (int) escape_data($_GET['user']);
							$query = "UPDATE agency_mycastings SET removed='0' WHERE role_id='$rid' AND user_id='$uid'";
							mysql_query($query);
						}
						?>
						<div align="right" style="position:absolute; width:600px; margin-top:-30px" onmouseover="document.getElementById('experience_popup').style.display=''" onmouseout="document.getElementById('experience_popup').style.display='none'"><b>WHAT IS: &nbsp;</b>
							<font color="#0000ff"><b>NEW FACES</b></font><b> </b>
							<font color="#ff0000"><b>EXPERIENCED</b></font><b> PROFESSIONAL ?</b>
						</div>
						<?php
						$sql = "SELECT job_title FROM agency_castings WHERE casting_id='$castingid'";
						$result = mysql_query($sql);
						if ($row = sql_fetchrow($result)) {
							echo '<div align="center"><b>' . $row['job_title'] . '</b></div>';
						}
						$sql = "SELECT * FROM agency_castings_roles WHERE casting_id='$castingid'";
						$result = mysql_query($sql);
						if (mysql_num_rows($result) == 0) {
							echo '<br /><br />There are no roles for this casting.<br /><br />';
							$placebottom = false; // flag for if the button need to be added to the bottom of the list as well.
						} else {
							echo '<script>var ProcessArray = new Array(); </script>';
							echo '<div align="center"><br />';
							echo '<span id="check_uncheck_all"><a href="javascript:void(0)" onclick="checkAllRoles(); checkAllToggle2(\'switch_to_uncheck\')" class="AGENCY_graybutton">check all</a></span> ';
							echo '<a href="ajax/lightbox_add.php?castingid=' . $castingid . '&height=350&amp;width=300&amp;inlineId=hiddenModalContent" class="thickbox AGENCY_graybutton" onclick="var ProcessArray=getCookie(\'lightbox\')">add checked to lightbox</a> <a class="AGENCY_graybutton" href="clienthome.php?mode=castings&cull=true&amp;castingid=' . $_GET['castingid'] . '" onclick="return confirm(\'This will PERMANENTLY remove all submissions that do not fit the specs of the casting?\')">auto edit</a></div>';
							$placebottom = true;
						}

						while ($row = sql_fetchrow($result)) {
							$roleid = $row['role_id'];
							$name = $row['name'];

							echo '<div class="AGENCYcastingrole AGENCYresultlist" style="line-height:1.5em; position:relative"><b>' . $name . '</b><br /><br />';
							// get profiles
							$sql2 = "SELECT agency_profiles.user_id, agency_profiles.firstname, agency_profiles.phone, agency_profiles.experience, agency_profiles.lastname, agency_profiles.registration_date, agency_profiles.resume, agency_profiles.resume_text, agency_mycastings.submission_id, agency_mycastings.message, agency_mycastings.date, agency_mycastings.new_submission FROM agency_profiles, agency_mycastings WHERE agency_mycastings.user_id=agency_profiles.user_id AND agency_profiles.account_type='talent' AND agency_mycastings.role_id='$roleid' AND agency_mycastings.removed='0' ORDER BY agency_mycastings.date DESC";
							$result2 = mysql_query($sql2);
							if (mysql_num_rows($result2) == 0) {
								echo 'Noboby has submitted themselves for this role yet.<br /><br />';
							}
							//	echo '<
							//		action="clienthome.php?mode=castings&castingid=' . $castingid . '" method="post" name="form' . $roleid . '">';
							while ($row2 = sql_fetchrow($result2)) {
								$friendid = $row2['user_id'];
								$displayname = $row2['firstname'];
								$phone = $row2['phone'];
								$new = $row2['new_submission'];
								$id = $row2['submission_id'];

								if ($new == '1') {
									echo '<div style="position:absolute; left:0px; margin-left:-40px; width:100px"><img src="images/new.jpg" width="60"></div>';
									if (!is_admin()) {
										mysql_query("UPDATE agency_mycastings SET new_submission='0' WHERE submission_id='$id' LIMIT 1");
									}
								}
								if (agency_privacy($friendid, 'lastname')) {
									$displayname .= ' ' . $row2['lastname'];
								}
								/* if(!rolematch($friendid, $roleid, $castingid)) {
									$displayname .= '*';
								} */

								$displayname = '<span style="color:' . $experiencecolors[$row2['experience']] . '">' . $displayname . '</span><br /><img src="images/' . $experienceimages[$row2['experience']] . '.gif" onmouseout="document.getElementById(\'experience_popup\').style.display=\'none\'" onmouseover="document.getElementById(\'experience_popup\').style.display=\'\'">';




								$message = $row2['message'];
								if ($row2['date'] > 0) {
									$submitdate = '<br /><span style="font-size:xx-small; color:gray">Submitted: ' . date('M j, Y g:iA', strtotime($row2['date'])) . '</span>';
								}
								$posterfolder = 'talentphotos/' . $friendid . '_' . $row2['registration_date'] . '/';
								$email = mysql_result(mysql_query("SELECT user_email FROM forum_users WHERE user_id='$friendid'"), 0, 'user_email');
								echo '<img src="';
								if (file_exists($posterfolder . 'avatar.jpg')) {
									echo   $posterfolder . 'avatar.jpg';
								} else if (file_exists($posterfolder . 'avatar.gif')) {
									echo   $posterfolder . 'avatar.gif';
								} else {
									echo 'images/friend.gif';
								}

								// addProcessCheck(ProcessArray, \'' . $friendid . '\', \'' . $roleid . '_' . $friendid . '\')
								echo '" align="left" hspace="10" />' .
									'<div style="float:right; width:490px">' .
									'<input type="checkbox" name="submissions[]" value="' . $friendid . '" id="' . $roleid . '_' . $friendid . '" onclick="lightbox_check(\'lightbox\', this, \'' . $roleid . '_' . $friendid . '\');" /><b>' .
									$displayname . '</b>' . $submitdate . '<br />' . $message . '<br />' .
									'<a href="ajax/compcard_mini.php?u=' . $friendid . '&amp;height=400&amp;width=450" class="thickbox">View CompCard</a><br />';

								echo '<a href="mailto:' . $varemail . '">' . $varemail . '</a>';
								if (!empty($phone) && agency_privacy($friendid, 'phone')) {
									echo '<br />' . $varphone;
								}

								$resumeicon = false;

								if (!empty($row2['resume'])) {
									if (file_exists($posterfolder . '/' . $row2['resume'])) {
										echo '<br /><a href="' . $posterfolder . $row2['resume'] . '" target="_blank"><img src="images/resume1.gif" border="0" style="padding-top:5px;" ></a>';
										$resumeicon = true;
									}
								}

								// check for reel/vo
								if (mysql_result(mysql_query("SELECT COUNT(*) as 'Num' FROM agency_vo WHERE user_id='$friendid'"), 0) || mysql_result(mysql_query("SELECT COUNT(*) as 'Num' FROM agency_reel WHERE user_id='$friendid'"), 0)) {
									if ($resumeicon) {
										echo '&nbsp;&nbsp;&nbsp;&nbsp;';
									} else {
										echo '<br />';
									}


									if (mysql_result(mysql_query("SELECT COUNT(*) as 'Num' FROM agency_vo WHERE user_id='$friendid'"), 0)) {
										echo '<a target="_blank" href="profile.php?tab=Reel/VO&u=' . $friendid . '"><img src="images/vo.gif" border="0" style="padding-top:5px;" ></a>';
									}
									if (mysql_result(mysql_query("SELECT COUNT(*) as 'Num' FROM agency_reel WHERE user_id='$friendid'"), 0)) {
										echo '&nbsp;&nbsp;&nbsp;&nbsp;<a target="_blank" href="profile.php?tab=Reel/VO&u=' . $friendid . '"><img src="images/reel.gif" border="0" style="padding-top:5px;" ></a>';
									}
								}



								// UNION STATUS
								$sql4 = "SELECT * FROM agency_profile_unions WHERE user_id='$friendid'";
								$result4 = mysql_query($sql4);
								$num_results4 = mysql_num_rows($result4);
								$current4 = 1;
								if ($num_results4) {
									echo '<br /><span class="AGENCYCompCardLabel">Union: </span><span class="AGENCYCompCardStat">';
									while ($row4 = sql_fetchrow($result4)) {
										echo escape_data($row4['union_name']);
										if ($current4 < $num_results4) echo ', ';
										$current4++;
									}
									echo '</span>';
								}

								echo '</div><br clear="all" /><hr>';
							}
							echo '<div align="center">';

							if (mysql_num_rows($result2) > 0) {
								// echo '<span id="check_uncheck_' . $roleid . '"><a href="javascript:void(0)" onclick="checkAllRoles(' . $roleid . '); checkAllToggle3(\'switch_to_uncheck\', \'' . $roleid . '\')" class="AGENCY_graybutton">check all in role</a></span>';

								echo '<input type="button" value="select role" onclick="checkGroup(\'' . $roleid . '_\', true)" class="AGENCY_graybutton">&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="unselect role" onclick="checkGroup(\'' . $roleid . '_\', false)" class="AGENCY_graybutton">';

								echo '&nbsp;&nbsp;&nbsp;&nbsp;';
								echo '<input type="button" value="Remove checked from this role" onclick="document.form' . $roleid . '.submit()"">';
							}

							// if any have been removed, give option to display removed people
							$query3 = "SELECT agency_profiles.user_id, agency_profiles.firstname, agency_profiles.experience, agency_profiles.lastname, agency_profiles.registration_date, agency_mycastings.message FROM agency_profiles, agency_mycastings WHERE agency_mycastings.user_id=agency_profiles.user_id AND agency_profiles.account_type='talent' AND agency_mycastings.role_id='$roleid' AND agency_mycastings.removed='1'";
							$result3 = mysql_query($query3);
							if (mysql_num_rows($result3) > 0) {
								echo '&nbsp;&nbsp;<a href="#TB_inline?height=400&amp;width=450&amp;inlineId=hiddenModalContent" onclick="loaddiv(\'popupcontent\', \'content_removed' . $roleid . '\')" class="thickbox AGENCY_graybutton">view removed submissions</a>';
								echo '<div id="content_removed' . $roleid . '" style="display:none">';
								while ($row3 = sql_fetchrow($result3)) {
									$friendid = $row3['user_id'];
									$displayname = $row3['firstname'];
									if (agency_privacy($friendid, 'lastname')) {
										$displayname .= ' ' . $row3['lastname'];
									}
									$displayname = '<span style="color:' . $experiencecolors[$row2['experience']] . '">' . $displayname . '</span>';
									$message = $row3['message'];
									$posterfolder = 'talentphotos/' . $friendid . '_' . $row3['registration_date'] . '/';
									$email = mysql_result(mysql_query("SELECT user_email FROM forum_users WHERE user_id='$friendid'"), 0, 'user_email');
									echo '<img src="';
									if (file_exists($posterfolder . 'avatar.jpg')) {
										echo   $posterfolder . 'avatar.jpg';
									} else if (file_exists($posterfolder . 'avatar.gif')) {
										echo   $posterfolder . 'avatar.gif';
									} else {
										echo 'images/friend.gif';
									}
									echo '" align="left" height="90px" hspace="10" />' .
										'<b>' . $displayname . '</b><br />' . $message . '<br />' .
										'<a href="profile.php?u=' . $friendid . '" target="_blank">View Profile</a><br />' .
										'<a href="clienthome.php?mode=castings&castingid=' . $castingid . '&addtorole=' . $roleid . '&user=' . $friendid . '" onclick="return confirm(\'Page will refresh.  Any actions in progress will be cancelled.\')">Re-Submit</a><br />' .
										'<a href="mailto:' . $email . '">Send Email</a><br clear="all" /><hr>';
								}

								echo '</div>';
							}
							echo '<br /></div>';
							echo '<input type="hidden" name="removefromrole" value=' . $roleid . '"></form>';



							echo '</div><br /><br />';
						}
						if ($placebottom) {
							echo '<div align="center"><br /><span id="check_uncheck_all2"><a href="javascript:void(0)" onclick="checkAll(\'_\', true); checkAllToggle2(\'switch_to_uncheck\')" class="AGENCY_graybutton">check all</a></span> <a href="ajax/lightbox_add.php?castingid=' . $castingid . '&height=350&amp;width=300&amp;inlineId=hiddenModalContent" class="thickbox AGENCY_graybutton" onclick="var ProcessArray=getCookie(\'lightbox\')">add checked to lightbox</a> <a class="AGENCY_graybutton" href="clienthome.php?mode=castings&cull=true&amp;castingid=' . $_GET['castingid'] . '" onclick="return confirm(\'This will PERMANENTLY remove all submissions that do not fit the specs of the casting?\')">auto edit</a></div>';
						}
					}

					break;






				case "lightbox": // LIGHTBOX
					echo '<div class="AGENCY_ClientPageTitle">Lightboxes&nbsp;&nbsp;<a href="clienthome.php?mode=lightbox" class="AGENCY_graybutton">Manage</a></div>';
					if (!empty($_GET['deletelightbox'])) { // DELETE LIGHTBOX
						$lightbox_id = (int) $_GET['deletelightbox'];
						$query = "DELETE FROM agency_lightbox WHERE lightbox_id='$lightbox_id' AND client_id='$profileid' LIMIT 1";
						$result = mysql_query($query);
						if (mysql_affected_rows() == 0) {
							$query = "DELETE FROM agency_lightbox_users WHERE lightbox_id='$lightbox_id'";
							$result = mysql_query($query);
						}
						$url = 'clienthome.php?mode=lightbox';
						ob_end_clean(); // Delete the buffer.
						header("Location: $url");
						exit(); // Quit the script.
					}

					if (!empty($_GET['lightbox'])) { // LIST CASTING ROLE SUBMISSIONS





						echo '<script> var ProcessArray = new Array(); </script>';
						$lightbox_id = escape_data((int) $_GET['lightbox']);

						// get name of lightbox and MAKE SURE THIS IS THE LOGGED IN USERS LIGHTBOX
						$query = "SELECT * FROM agency_lightbox WHERE lightbox_id='$lightbox_id' AND client_id='$profileid'";
						$result = mysql_query($query);
						if ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {

							if (isset($_POST['delete'])) { // REMOVE USER FROM LIGHTBOX
								$entryid = array();
								$entryid = $_POST['entryid'];
								if (!empty($entryid[0])) {
									foreach ($entryid as $remove) {
										$query2 = "DELETE FROM agency_lightbox_users WHERE entry_id='$remove' AND lightbox_id='$lightbox_id' LIMIT 1";
										$result2 = mysql_query($query2);
										if (mysql_affected_rows() == 0) {
											echo 'There was an error removing user from the lightbox.<br /><br />';
										}
									}
								}
							}



							if (isset($_POST['copylightbox']) && !empty($_POST['entryid'])) {  // COPY LIGHTBOX
								$copyname = escape_data($_POST['copyname']);
								$entryid = array();
								$entryid = $_POST['entryid'];
								// echo $copyname;
								if (!empty($copyname)) {
									$copydescription = escape_data($_POST['copydescription']);
									$timecode = strtotime("NOW");
									$query2 = "INSERT INTO agency_lightbox (client_id, lightbox_name, lightbox_description, timecode) VALUES ('$profileid', '$copyname', '$copydescription', '$timecode')";
									$result2 = mysql_query($query2);
									if (mysql_affected_rows() > 0) { // new lightbox created
										$new_lightbox_id = mysql_insert_id();

										if (!empty($row['casting_id']) && isset($_POST['keeproles'])) {
											$castingid = (int) $row['casting_id'];
											$query2 = "UPDATE agency_lightbox SET casting_id='$castingid' WHERE lightbox_id='$new_lightbox_id' AND casting_id IS NULL";
											mysql_query($query2);
										}

										foreach ($entryid as $id) {
											$id = (int) $id;
											$query2 = "SELECT * FROM agency_lightbox_users WHERE entry_id='$id'";
											$result2 = mysql_query($query2);
											if ($row2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
												$new_uid = $row2['user_id'];
												if (!empty($row['casting_id']) && isset($_POST['keeproles'])) {
													$new_rid = $row2['role_id'];
													$query3 = "INSERT INTO agency_lightbox_users (lightbox_id, user_id, role_id) VALUES ('$new_lightbox_id', '$new_uid', '$new_rid')";
												} else {
													// avoid duplicates when combining roles
													if (mysql_result(mysql_query("SELECT COUNT(*) as 'Num' FROM agency_lightbox_users WHERE lightbox_id='$new_lightbox_id' AND user_id='$new_uid'"), 0)) {
														$query3 = NULL;
													} else {
														$query3 = "INSERT INTO agency_lightbox_users (lightbox_id, user_id) VALUES ('$new_lightbox_id', '$new_uid')";
													}
												}
												if (!empty($query3)) {
													mysql_query($query3);
												}
											}
										}

										$url = 'clienthome.php?mode=lightbox&lightbox=' . $new_lightbox_id;
										ob_end_clean(); // Delete the buffer.
										header("Location: $url");
										exit(); // Quit the script.		


									}
								} else {
									echo 'Please enter a valid name for the new Lightbox.<br /><br />';
								}
							}





							if (isset($_POST['email_content']) && !empty($_POST['emailto'])) {

								$clientid = $_SESSION['user_id'];
								$sql = "SELECT user_email FROM forum_users WHERE user_id='$clientid'";
								$result = mysql_query($sql);
								if ($row = sql_fetchrow($result)) {
									$email_from = $row['user_email'];

									$email_subject = "Lightbox from The Agency Online"; // The Subject of the email

									$email_to = $_POST['emailto']; // Who the email is too
									$email_message = $_POST['email_content'] . "
		
		
									mailed from http://www.theagencyonline.com";

									$headers = "From: " . $email_from;

									$ok = @mail($email_to, $email_subject, $email_message, $headers);

									if ($ok) {
										echo '<div align="center"><br /><br /><b>Your Lightbox has been sent.  Thank you.</b><br /><br /></div>';
									} else {
										echo "<b>Sorry but the lightbox could not be sent. Please try again!</b>";
									}
								}
							}






							$timecode = $row['timecode'];
							echo '<div align="right" onmouseout="document.getElementById(\'experience_popup\').style.display=\'none\'" onmouseover="document.getElementById(\'experience_popup\').style.display=\'\'"><b>WHAT IS: &nbsp;</b><font color="#0000ff"><b>NEW FACES</b></font><b> </b><font color="#ff0000"><b>EXPERIENCED</b></font><b> PROFESSIONAL ?</b></div>';
							echo '<p align="center"><b>LIGHTBOX NAME: ' . $row['lightbox_name'] . '</b><br />';
							if (!empty($row['lightbox_description'])) {
								echo $row['lightbox_description'] . '<br />';
							}

							// associated with a casting?
							if (!empty($row['casting_id'])) {
								$castingid = (int) $row['casting_id'];
								// get casting name
								$castingname = mysql_result(mysql_query("SELECT job_title FROM agency_castings WHERE casting_id='$castingid'"), 0, 'job_title');

								echo 'This lightbox is linked with the casting: <a target="_blank" href="view_casting.php?castingid=' . $castingid . '"><b>' . $castingname . '</b></a>';



								if (!empty($_REQUEST['autonotify']) && is_admin()) {
									$sent = false;
									// GO THROUGH EACH PERSON IN LIGHTBOX AND SEND A MESSAGE
									$job_title = mysql_result(mysql_query("SELECT job_title FROM agency_castings WHERE casting_id='$castingid'"), 0, 'job_title');

									$count = 0;

									$query = "SELECT DISTINCT user_id FROM agency_lightbox_users WHERE lightbox_id='$lightbox_id'";
									$result = mysql_query($query);
									while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
										$count++;
										$uid = $row['user_id'];
										$query2 = "SELECT user_email FROM forum_users WHERE user_id='$uid'";
										$result2 = mysql_query($query2);
										if ($row2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
											$to = $row2['user_email'];

											$message = '<html><body>Dear Talent, 
												<br /><br />
												A new Casting was just posted that may be a perfect fit for you! 
												<br /><br />
												Please take a look at the link below, and submit As Soon As Possible if you\'re available & interested! 
												<br /><br />
												<a href="http://www.theagencyonline.com/news.php?castingid=' . $castingid . '">' . $job_title . '</a>
												<br /><br />
												As always, be sure to leave a note if any specific information is requested. 
												<br /><br />
												thanks, & good luck! 
												<br /><br />
												The Agency Team<br />
												<a href="http://www.theagencyOnline.com">www.theagencyOnline.com</a><br />
												castings@theagencyOnline.com<br />
												</body></html>';


											$from = "info@theagencyonline.com";
											$subject = "New Casting at The Agency";

											$headers  = "From: $from\r\n";
											$headers .= "Content-type: text/html\r\n";

											mail($to, $subject, $message, $headers);
											// mail("oliver@theagencyonline.com", $subject, $message, $headers);

											$sent = true;
										}
									}
								}
								if ($sent) {
									echo '<br /><br /><b>AUTO-NOTIFICATIONS HAVE BEEN SENT';

									$query2 = "SELECT agency_profiles.firstname, agency_profiles.lastname FROM agency_profiles, agency_castings WHERE agency_castings.posted_by = agency_profiles.user_id AND agency_castings.casting_id='$castingid'";
									$result2 = mysql_query($query2);
									if ($row2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
										$clientname = $row2['firstname'] . ' ' . $row2['lastname'];
									}

									$to = "clients@theagencyonline.com";

									$message = '<html><body>Auto Notify has notified <b>' . $count . '</b> people for the casting: 
									<br /><br />
									<a href="http://www.theagencyonline.com/news.php?castingid=' . $castingid . '">' . $job_title . '</a>
									<br /><br />
									Client: ' . $clientname . '
									</body></html>';


									$from = "clients@theagencyonline.com";
									$subject = "Auto-Notify Report";

									$headers  = "From: $from\r\n";
									$headers .= "Content-type: text/html\r\n";

									mail($to, $subject, $message, $headers);
								}
							}

							echo '</p>';






							$query = "SELECT agency_profiles.user_id, agency_profiles.firstname, agency_profiles.lastname, agency_profiles.phone, agency_profiles.experience, agency_profiles.registration_date, agency_profiles.resume, agency_profiles.resume_text, forum_users.user_email FROM agency_lightbox_users, agency_lightbox, agency_profiles, forum_users WHERE agency_lightbox_users.lightbox_id=agency_lightbox.lightbox_id AND agency_lightbox_users.user_id=agency_profiles.user_id AND forum_users.user_id=agency_profiles.user_id AND agency_lightbox_users.lightbox_id='$lightbox_id' AND agency_lightbox.client_id='$profileid'";
							$result = mysql_query($query);
							if (mysql_num_rows($result) > 0) {
								echo '<div align="center" class="AGENCYresultlist AGENCYGrayBG">';

								echo '<form name="lightbox" method="post" action="clienthome.php?mode=lightbox&amp;lightbox=' . $lightbox_id . '">';

								// show "General" section
								lightbox_show();


								if (is_int($castingid)) { // if this is part of a casting, start showing the roles
									// get roles
									$query2 = "SELECT * FROM agency_castings_roles WHERE casting_id='$castingid'";
									$result2 = mysql_query($query2);
									while ($row2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
										echo '<div style="background-color:white; padding:3px; text-align:left">Role: <b>' . $row2['name'] . '</b></div>';
										lightbox_show($row2['role_id']);
									}
								}



								echo '<div style="background-color:white; padding:10px; text-align:left"> </div>';



								echo '<div id="sendtoform" style="display:none; padding:40px">
										Email address of recipient:<br /><input type="text" name="emailto" style="width:400px"><br /><br />
										Your Message:<br /><textarea name="email_content" style="font-size:10px; width:400px; height:80px">I thought you might be interested in the some talent from TheAgencyOnline.com.  Just follow the link below.
										
										http://www.theagencyonline.com/lightbox.php?lightbox=' . $lightbox_id . '&code=' . md5($timecode) . '</textarea><br /><br />
										<input type="submit" name="portfolio" value="SEND!"/>&nbsp;&nbsp;&nbsp;<input type="button" onclick="document.getElementById(\'sendtoform\').style.display=\'none\'" value="CANCEL"/>
										</div>';




								echo '<div id="sendmessageform" class="popup" style="display:none; padding:40px"></div>';





								echo '<div id="copyform" style="display:none; padding:40px">
									The selected Talent will be copied to a new lightbox.  Please enter a name for your new lightbox.<br /><br />
										<b>New Lightbox Name:</b><br /><input type="text" name="copyname" style="width:400px"><br /><br />
										 Description:
										<br>
										<textarea name="copydescription"></textarea><br /><br />';


								if (!empty($castingid)) {
									echo '<input type="checkbox" name="keeproles"> Keep Roles in new Lightbox<br /><br />';
								}
								echo '<input type="submit" name="copylightbox" value="COPY"/>&nbsp;&nbsp;&nbsp;<input type="button" onclick="document.getElementById(\'copyform\').style.display=\'none\'" value="CANCEL"/>
										</div>';




								echo '<span id="check_uncheck_all"><input type="button" value="select all" onclick="checkGroupBtn(\'entry_\', true, \'switch_to_uncheck\', \'check_uncheck_all\', \'all\')"></span> ';
								echo '<input type="submit" name="print" value="print/save" onclick="this.form.setAttribute(\'action\', \'pdf_lightbox.php\'); this.form.target=\'_blank\';" /> ';



								echo '<input type="submit" name="sendmessage" value="send message" onclick="this.form.setAttribute(\'action\', \'messages.php?tab=Compose&lightbox_id=' . $lightbox_id . '\');" /> ';

								echo '<input type="button" value="send lightbox to friend" onclick="document.getElementById(\'sendtoform\').style.display=\'block\'; document.getElementById(\'copyform\').style.display=\'none\'"/> ';

								echo '<input type="button" value="copy" onclick="document.getElementById(\'copyform\').style.display=\'block\'; document.getElementById(\'sendtoform\').style.display=\'none\'"/> ';

								echo '<input type="submit" name="delete" value="remove" onclick=" this.form.setAttribute(\'action\', \'clienthome.php?mode=lightbox&amp;lightbox=' . $lightbox_id . '\'); this.form.target=\'_self\'; return confirm(\'Are you sure you wish to remove the selected Talent from this Lightbox?\')" />';

								if (is_admin()) {
									echo '<br /><br /><input type="button" name="email" value="Admin: email to" onclick="alert(\'The email application associated with your browser will open with the emails of the selected talent filled in.  To protect the privacy of our Talent, move the email addresses from the To field to the BCC (blind carbon copy) field in your email application.  Thank you!\'); window.location=&quot;mailto:&quot;+generate_email_list()"> ';
									if (!empty($castingid)) {
										echo '<input type="submit" name="autonotify" value="Admin: Auto-Notify" onclick="return confirm(\'ALL people in this lightbox will be sent an automated email letting them know about this casting.  Reminder: This auto feature sends to ALL people, not just checked people.\')"> ';
									}
								}

								echo '</form></div>';
							} else {
								echo '<br />There are no members in this lightbox. You may add to a lightbox from your search results or by going to a Talent Profile page and clicking "Add to Lightbox."<br /><br />';
							}
						} else {
							echo 'Error: Lightbox could not be located or you do not have permission to view this lightbox.';
						}

						echo '<br clear="all" />';
					} else { // LIST LIGHTBOXES
						echo '<b>';
						$sql = "SELECT lightbox_id, lightbox_name, timecode FROM agency_lightbox WHERE client_id='$profileid' ORDER BY lightbox_id DESC";
						$result = mysql_query($sql);
						if (mysql_num_rows($result) == 0) echo '<br /><br />You have not created any lightboxes yet.  You may create a new lightbox from your search results or by going to a Talent Profile page and clicking "Add to Lightbox."<br /><br />';
						while ($row = sql_fetchrow($result)) {
							$lightboxid = $row['lightbox_id'];
							$timecode = $row['timecode'];
							$lightboxname = $row['lightbox_name'];
							echo '<div style="margin:10px; float:left; background-image:url(images/lightbox.gif); background-repeat: no-repeat; height:130px; width:130px; text-align:center; padding:10px; overflow:hidden">';
							echo $row['lightbox_name'];
							echo '</div>';
							echo '<div style="margin:10px; padding:10px; width:200px; float:left; line-height:1.6em"><a name="lb' . $lightboxid . '"></a>' .
								'<a href="clienthome.php?mode=lightbox&amp;lightbox=' . $lightboxid . '">view this lightbox</a><br />' .
								'<a href="clienthome.php?mode=lightbox&amp;deletelightbox=' . $lightboxid . '" onclick="return confirm(\'Are you sure you wish to delete this lightbox?\')">delete this lightbox</a><br />' .
								'<a href="#lb' . $lightboxid . '" onclick="document.getElementById(\'sendtoform' . $lightboxid . '\').style.display=\'block\'">send this lightbox</a><br /><br />';


							echo '</div><br clear="all" />';


							echo '<div id="sendtoform' . $lightboxid . '" style="display:none; padding:40px">';
							echo '<form name="lightbox" method="post" action="clienthome.php?mode=lightbox&amp;lightbox=' . $lightboxid . '">';
							echo 'Email address of recipient:<br /><input type="text" name="emailto" style="width:400px"><br /><br />
									Your Message:<br /><textarea name="email_content" style="font-size:10px; width:400px; height:80px">I thought you might be interested in the some talent from TheAgencyOnline.com.  Just follow the link below.
									
									http://www.theagencyonline.com/lightbox.php?lightbox=' . $lightboxid . '&code=' . md5($timecode) . '</textarea><br /><br />
									<input type="submit" name="portfolio" value="SEND!"/>
									';

							echo '</form></div>';

							echo '<hr>';
						}
						echo '</b>';
					}

					break;



				default:
					$mode = false;
					break;
			}

			echo '</div>';

			// client nav buttons
			echo '<div style="width:144px; float:right; padding-top:10px">' . clientbuttons() . '</div>';
		}



		if (!$mode) {
			?>


			<!--  START: client page content -->
			<div style="position:relative; height:1000px; top:30px">

				<!--  START: client "block" content -->
				<div style="width:196px; height:294px; position:absolute; top:0px; left:0px; overflow:hidden">
					<?php
					echo showbox('client_always');
					?>
				</div>



				<!-- ************************************************************************************************************* -->



				<!--  START: client MyCastings -->
				<div style="width:390px; height:294px; position:absolute; top:0px; left:228px">
					<img src="images/client_MyCastings.png" style="position:absolute; margin-top:-25px">
					<div class="AGENCYhomescroll AGENCY_Client_Castings" style="height:100%">
						<?php
						$sql = "SELECT * FROM agency_castings WHERE posted_by='$profileid' AND deleted='0' ORDER BY post_date DESC";
						$result = mysql_query($sql);
						if (mysql_num_rows($result) == 0) echo '<br /><br />You have not created any castings yet.<br /><br />';
						while ($row = sql_fetchrow($result)) {
							$castingid = $row['casting_id'];
							$jobtitle = $row['job_title'];
							$live = $row['live'];
							$livenote = '';
							if (!$live) {
								$livenote = '<span style="color:red">NOTE: THIS CASTING IS NOT LIVE.</span>';
							}
							// style="text-decoration:none; padding-left:130px"><a href="news.php?castingid=' . $castingid . '&amp;title=' . urlencode($jobtitle) . '"
							echo '- <a href="view_casting.php?castingid=' . $castingid . '" style="text-decoration:none; color:#000000;">' . $jobtitle . ' (view)</a>' .
								' (<a href="castingupdate.php?castingid=' . $castingid . '" style="text-decoration:none; color:#333333;">edit</a>) ' . $livenote . '<br />';

							// find submissions for this casting
							echo '<a href="clienthome.php?mode=castings&castingid=' . $castingid . '" style="text-decoration:none; padding-left:70px;';
							$sql2 = "SELECT * FROM agency_mycastings, agency_castings_roles WHERE agency_mycastings.role_id=agency_castings_roles.role_id AND agency_castings_roles.casting_id='$castingid' AND agency_mycastings.removed='0'";
							$result2 = mysql_query($sql2);
							$num_castings = mysql_num_rows($result2);
							if ($num_castings == 0) {
								echo ' color:#0066FF;">You Have No Submissions';
							} else {
								$sql2 = "SELECT * FROM agency_mycastings, agency_castings_roles WHERE agency_mycastings.role_id=agency_castings_roles.role_id AND agency_castings_roles.casting_id='$castingid' AND agency_mycastings.new_submission='1' AND agency_mycastings.removed='0'";
								$result2 = mysql_query($sql2);
								$num_castings = mysql_num_rows($result2);
								if ($num_castings == 0) {
									echo '">View Submissions (No New Submissions)';
								} else {
									echo '">View Submissions (You have ' . $num_castings . ' New Submissions!)';
								}
							}
							echo '</a><br /><br />';
						}
						?>
					</div>
				</div>




				<!-- ************************************************************************************************************* -->




				<!--  START: client Buttons -->
				<div style="width:144px; height:294px; position:absolute; top:0px; left:644px">
					<?php echo clientbuttons(); ?>
				</div>





				<!-- ************************************************************************************************************* -->




				<!--  START: client Talent Matches -->
				<div style="width:196px; height:400px; position:absolute; top:320px; left:0px">
					<span class="AGENCYRed AGENCYGeneralTitle">Newest Talent </span>
					<form name="searchit2" action="clienthome.php?mode=search&sort=date" method="post" style="display:none">
						<input type="hidden" name="submitsearch">
					</form>
					<a style="color:gray; text-decoration:none; font-size:12px; font-weight:normal; float:right; height:auto; margin:0; width:auto;" href="javascript:document.searchit2.submit();">view all</a>
					<div align="right" style="margin-top:-10px; clear:both">
						<a href="clienthome.php?mode=search&configure=true" style="color:gray; text-decoration:none;">configure</a>
					</div>
					<?php
					$columns = 3;

					$sql = "SELECT searchquery FROM agency_search_matches WHERE user_id='$profileid'";
					$result = mysql_query($sql);
					if ($row = sql_fetchrow($result)) {
						$sql2 = $row['searchquery'];
					} else {
						$sql2 = "SELECT p.user_id, p.registration_date FROM agency_profiles p, forum_users u WHERE p.account_type='talent' AND p.user_id=u.user_id AND u.user_type='0' ORDER BY p.payProcessedDate DESC, p.user_id DESC LIMIT 27";
					}
					$result2 = mysql_query($sql2);
					while ($row2 = sql_fetchrow($result2)) {
						$friendid = $row2['user_id'];
						$posterfolder = 'talentphotos/' . $friendid . '_' . $row2['registration_date'] . '/';
						echo '<div class="AGENCYTalentThumbnail"><a href="profile.php?u=' . $friendid . '"><img src="';
						if (file_exists($posterfolder . 'avatar.jpg')) {
							echo   $posterfolder . 'avatar.jpg';
						} else if (file_exists($posterfolder . 'avatar.gif')) {
							echo   $posterfolder . 'avatar.gif';
						} else {
							echo 'images/friend.gif';
						}
						echo '" /></a></div>';
						$columns--;
						if ($columns == 0) {
							$columns = 3;
							echo '<br clear="all" />';
						}
					}
					?>
				</div>



				<!-- ************************************************************************************************************* -->



				<!--  START: client Lightboxes -->
				<div style="width:562px; height:444px; position:absolute; top:320px; left:228px">
					<img src="images/client_MyLightboxes.png" style="margin-bottom:-6px ">
					<div class="AGENCYhomescroll" style="height:100%">
						<?php
						if (!empty($_GET['deletelightbox'])) { // DELETE LIGHTBOX
							$lightbox_id = (int) $_GET['deletelightbox'];
							$query = "DELETE FROM agency_lightbox WHERE lightbox_id='$lightbox_id' AND client_id='$profileid' LIMIT 1";
							$result = mysql_query($query);
							if (mysql_affected_rows() == 0) {
								$query = "DELETE FROM agency_lightbox_users WHERE lightbox_id='$lightbox_id'";
								$result = mysql_query($query);
							}
						}


						// LIST LIGHTBOXES
						echo '<b>';
						$sql = "SELECT lightbox_id, lightbox_name, timecode FROM agency_lightbox WHERE client_id='$profileid' ORDER BY lightbox_id DESC";
						$result = mysql_query($sql);
						if (mysql_num_rows($result) == 0) echo '<br /><br />You have not created any lightboxes yet.  You may create a new lightbox from your search results or by going to a Talent Profile page and clicking "Add to Lightbox."<br /><br />';
						while ($row = sql_fetchrow($result)) {
							$lightboxid = $row['lightbox_id'];
							$timecode = $row['timecode'];
							$lightboxname = $row['lightbox_name'];
							echo '<div style="margin:10px; float:left; background-image:url(images/lightbox.gif); background-repeat: no-repeat; height:130px; width:130px; text-align:center; padding:10px; overflow:hidden">';
							echo $row['lightbox_name'];
							echo '</div>';
							echo '<div style="margin:10px; padding:10px; width:200px; float:left; line-height:1.6em"><a name="lb' . $lightboxid . '"></a>' .
								'<a href="clienthome.php?mode=lightbox&amp;lightbox=' . $lightboxid . '">view this lightbox</a><br />' .
								'<a href="clienthome.php?mode=lightbox&amp;deletelightbox=' . $lightboxid . '" onclick="return confirm(\'Are you sure you wish to delete this lightbox?\')">delete this lightbox</a><br />' .
								'<a href="#lb' . $lightboxid . '" onclick="document.getElementById(\'sendtoform' . $lightboxid . '\').style.display=\'block\'">send this lightbox</a><br /><br />';


							echo '</div><br clear="all" />';


							echo '<div id="sendtoform' . $lightboxid . '" style="display:none; padding:40px">';
							echo '<form name="lightbox" method="post" action="clienthome.php?mode=lightbox&amp;lightbox=' . $lightboxid . '">';
							echo 'Email address of recipient:<br /><input type="text" name="emailto" style="width:400px"><br /><br />
						Your Message:<br /><textarea name="email_content" style="font-size:10px; width:400px; height:80px">I thought you might be interested in the some talent from TheAgencyOnline.com.  Just follow the link below.
						
						http://www.theagencyonline.com/lightbox.php?lightbox=' . $lightboxid . '&code=' . md5($timecode) . '</textarea><br /><br />
						<input type="submit" name="portfolio" value="SEND!"/>
						';

							echo '</form></div>';

							echo '<hr>';
						}
						echo '</b>';


						?>
					</div>
				</div>





				<!-- ************************************************************************************************************* -->



				<!--  START: client Lightboxes -->
				<div style="width:562px; height:200px; position:absolute; top:818px; left:228px">
					<?php
					include('includes/showcase_long.php');
					?>
				</div>

			</div>
		<?php
		}
		?>
		<div id="experience_popup" style="position:absolute; top:200px; left:500px; display:none; z-index:5">
			<?php
			echo @mysql_result(@mysql_query("SELECT varvalue FROM agency_vars WHERE varname='levelsExp'"), 0, 'varvalue');
			?>
		</div>
<?php
	}
} else {
	$url = 'home.php';
	ob_end_clean(); // Delete the buffer.
	header("Location: $url");
	exit(); // Quit the script.
}
@include('includes/footer.php');
?>
<script type="text/javascript" language="javascript">
	// Simple follow the mouse script

	var divName = 'experience_popup'; // div that is to follow the mouse
	// (must be position:absolute)
	var offX = 20; // X offset from mouse position
	var offY = -90; // Y offset from mouse position

	function mouseX(evt) {
		if (!evt)
			evt = window.event;

		if (evt.pageX)
			return evt.pageX;
		else if (evt.clientX)
			return evt.clientX + (document.documentElement.scrollLeft ? document.documentElement.scrollLeft : document.body.scrollLeft);
		else return 0;
	}

	function mouseY(evt) {
		if (!evt)
			evt = window.event;

		if (evt.pageY)
			return evt.pageY;
		else if (evt.clientY)
			return evt.clientY - (document.documentElement.scrollTop ? document.documentElement.scrollTop : document.body.scrollTop);
		else return 0;
	}

	function follow(evt) {
		if (document.getElementById) {
			var obj = document.getElementById(divName).style;
			obj.visibility = 'visible';
			obj.left = (parseInt(mouseX(evt)) + offX) + 'px';
			obj.top = (parseInt(mouseY(evt)) + offY) + 'px';
			// alert(mouseY(evt));
		}
	}

	document.onmousemove = follow;
</script>