
<?php
@include('sidebar.php');


echo '
 <div id="page-wrapper">
        <div class="container-fluid">
            <!-- Page Heading -->
   <div class="row well" id="main" style="padding: 19px 0 !important;">
   <div class="col-sm-12 col-md-12 " id="content">
<div class="AGENCY_ClientPageTitle">Manage Castings</div>';
					if(!empty($_GET['deletecastingid'])) { // DELETE CASTING
						$deletecastingid = $_GET['deletecastingid'];
						if(!is_admin()) { // if not admin, make sure user has permission to delete this casting
							$query = "SELECT * FROM agency_castings WHERE casting_id='$deletecastingid' AND posted_by='$profileid'";
							$result = @mysql_query($query);
							if (@mysql_num_rows($result) == 0) { // If user does not access to project
								$deletecastingid = false;
							}
						}
						if($deletecastingid) {
							$sql = "SELECT * FROM agency_castings_roles WHERE casting_id='$deletecastingid'";
							$result=mysql_query($sql);
							while($row = sql_fetchrow($result)) {
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
							$result=mysql_query($sql);
							while($row = sql_fetchrow($result)) {
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
		
					if(!empty($_GET['castingid'])) { // LIST CASTING ROLE SUBMISSIONS
						$castingid = $_GET['castingid'];
						setcookie ("lightbox", "", time() - 3600000);
						
						// CULL
						if(!empty($_GET['cull'])) {
							auto_cull($castingid);
						}
						
						
						// REMOVE FROM ROLE
						if(!empty($_POST['removefromrole']) && !empty($_POST['submissions'])) {
							$rid = (int) escape_data($_POST['removefromrole']);
							$uids = array();
							$uids = $_POST['submissions'];
							foreach($uids as $value) {
								$query = "UPDATE agency_mycastings SET removed='1' WHERE role_id='$rid' AND user_id='$value'";
								mysql_query($query);
							}
						}
						
						// RE-SUBMIT TO ROLE
						if(!empty($_GET['addtorole']) && !empty($_GET['user'])) {
							$rid = (int) escape_data($_GET['addtorole']);
							$uid = (int) escape_data($_GET['user']);
							$query = "UPDATE agency_mycastings SET removed='0' WHERE role_id='$rid' AND user_id='$uid'";
							mysql_query($query);
						}
		?>
		<div style="position:absolute;" onmouseover="document.getElementById('experience_popup').style.display=''" onmouseout="document.getElementById('experience_popup').style.display='none'"><b>WHAT IS: &nbsp;</b><font color="#0000ff"><b>NEW FACES</b></font><b> </b><font color="#ff0000"><b>EXPERIENCED</b></font><b> PROFESSIONAL ?</b></div>
		<?php
						$sql = "SELECT job_title FROM agency_castings WHERE casting_id='$castingid'";
						$result=mysql_query($sql);
						if($row = sql_fetchrow($result)) {
							echo '<div align="center"><b>' . $row['job_title'] . '</b></div>';
						}
						$sql = "SELECT * FROM agency_castings_roles WHERE casting_id='$castingid'";
						$result=mysql_query($sql);
						if(mysql_num_rows($result) == 0) {
							echo '<br /><br />There are no roles for this casting.<br /><br />';
							$placebottom = false; // flag for if the button need to be added to the bottom of the list as well.
						} else {
							echo '<script>var ProcessArray = new Array(); </script>';
							echo '<div align="center"><br />';
							
							echo '<span id="check_uncheck_all"><a href="javascript:void(0)" onclick="checkAllRoles(); checkAllToggle2(\'switch_to_uncheck\')" class="AGENCY_graybutton">check all</a></span> ';
							
							echo '<a href="ajax/lightbox_add.php?castingid=' . $castingid . '&height=350&amp;width=300&amp;inlineId=hiddenModalContent" class="thickbox AGENCY_graybutton" onclick="var ProcessArray=getCookie(\'lightbox\')">add checked to lightbox</a> <a class="AGENCY_graybutton" href="clienthome.php?mode=castings&cull=true&amp;castingid=' . $_GET['castingid'] . '" onclick="return confirm(\'This will PERMANENTLY remove all submissions that do not fit the specs of the casting?\')">auto edit</a></div>';
							$placebottom = true;
						}
						while($row = sql_fetchrow($result)) {
							$roleid = $row['role_id'];
							$name = $row['name'];
		
							echo '<div class="AGENCYcastingrole AGENCYresultlist" style="line-height:1.5em; position:relative"><b>' . $name . '</b><br /><br />';
							// get profiles
							$sql2 = "SELECT agency_profiles.user_id, agency_profiles.firstname, agency_profiles.phone, agency_profiles.experience, agency_profiles.lastname, agency_profiles.registration_date, agency_profiles.resume, agency_profiles.resume_text, agency_mycastings.submission_id, agency_mycastings.message, agency_mycastings.date, agency_mycastings.new_submission FROM agency_profiles, agency_mycastings WHERE agency_mycastings.user_id=agency_profiles.user_id AND agency_profiles.account_type='talent' AND agency_mycastings.role_id='$roleid' AND agency_mycastings.removed='0' ORDER BY agency_mycastings.date DESC";
							$result2=mysql_query($sql2);
							if(mysql_num_rows($result2) == 0) {
								echo 'Noboby has submitted themselves for this role yet.<br /><br />';
							}
					//	echo '<
					//		action="clienthome.php?mode=castings&castingid=' . $castingid . '" method="post" name="form' . $roleid . '">';
							while($row2 = sql_fetchrow($result2)) {
								$friendid = $row2['user_id'];
								$displayname = $row2['firstname'];
								$phone = $row2['phone'];
								$new = $row2['new_submission'];
								$id = $row2['submission_id'];
								
								if($new == '1') {
									echo '<div style="position:absolute; left:0px; margin-left:-40px; width:100px"><img src="images/new.jpg" width="60"></div>';
									if(!is_admin()) {
										mysql_query("UPDATE agency_mycastings SET new_submission='0' WHERE submission_id='$id' LIMIT 1");
									}
								}								
								if(agency_privacy($friendid, 'lastname')) {
									$displayname .= ' ' . $row2['lastname'];
								}
								/* if(!rolematch($friendid, $roleid, $castingid)) {
									$displayname .= '*';
								} */
								
								$displayname = '<span style="color:' . $experiencecolors[$row2['experience']] . '">' . $displayname . '</span><br /><img src="images/' . $experienceimages[$row2['experience']] . '.gif" onmouseout="document.getElementById(\'experience_popup\').style.display=\'none\'" onmouseover="document.getElementById(\'experience_popup\').style.display=\'\'">';
								
								
								
								
								$message = $row2['message'];
								if($row2['date'] > 0) {
									$submitdate = '<br /><span style="font-size:xx-small; color:gray">Submitted: ' . date('M j, Y g:iA', strtotime($row2['date'])) . '</span>';
								}
								$posterfolder = 'talentphotos/' . $friendid . '_' . $row2['registration_date'] . '/';
								$email = mysql_result(mysql_query("SELECT user_email FROM forum_users WHERE user_id='$friendid'"), 0, 'user_email');
								echo '<img src="';
									if(file_exists($posterfolder . 'avatar.jpg')) {
										echo   $posterfolder . 'avatar.jpg';
									} else if(file_exists($posterfolder . 'avatar.gif')) {
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
								if(!empty($phone) && agency_privacy($friendid, 'phone')) {
									echo '<br />' . $varphone;
								}						
								
								$resumeicon = false;

								if(!empty($row2['resume'])) {
									if(file_exists($posterfolder . '/' . $row2['resume'])) {
										echo '<br /><a href="' . $posterfolder . $row2['resume'] . '" target="_blank"><img src="images/resume1.gif" border="0" style="padding-top:5px;" ></a>';
										$resumeicon = true;
									}
								}
								
									// check for reel/vo
								if( mysql_result(mysql_query("SELECT COUNT(*) as 'Num' FROM agency_vo WHERE user_id='$friendid'"),0) || mysql_result(mysql_query("SELECT COUNT(*) as 'Num' FROM agency_reel WHERE user_id='$friendid'"),0 )) {
										if($resumeicon) {
											echo '&nbsp;&nbsp;&nbsp;&nbsp;';
										} else {
											echo '<br />';
										}
										
			
										if( mysql_result(mysql_query("SELECT COUNT(*) as 'Num' FROM agency_vo WHERE user_id='$friendid'"),0)) {
										echo '<a target="_blank" href="profile.php?tab=Reel/VO&u=' . $friendid . '"><img src="images/vo.gif" border="0" style="padding-top:5px;" ></a>';
										}
										if( mysql_result(mysql_query("SELECT COUNT(*) as 'Num' FROM agency_reel WHERE user_id='$friendid'"),0)) {
										echo '&nbsp;&nbsp;&nbsp;&nbsp;<a target="_blank" href="profile.php?tab=Reel/VO&u=' . $friendid . '"><img src="images/reel.gif" border="0" style="padding-top:5px;" ></a>';
										}

								}
								
								
								
								// UNION STATUS
								 $sql4 = "SELECT * FROM agency_profile_unions WHERE user_id='$friendid'";
								 $result4=mysql_query($sql4);
								 $num_results4 = mysql_num_rows($result4);
								 $current4 = 1;
								 if($num_results4) {
									echo '<br /><span class="AGENCYCompCardLabel">Union: </span><span class="AGENCYCompCardStat">';
									while($row4 = sql_fetchrow($result4)) {
										echo escape_data($row4['union_name']);
										if($current4 < $num_results4) echo ', ';
										$current4++;
									}
									echo '</span>';
								 }											
								
								echo '</div><br clear="all" /><hr>';
							}
							echo '<div align="center">';
							
							if(mysql_num_rows($result2) > 0) {
								// echo '<span id="check_uncheck_' . $roleid . '"><a href="javascript:void(0)" onclick="checkAllRoles(' . $roleid . '); checkAllToggle3(\'switch_to_uncheck\', \'' . $roleid . '\')" class="AGENCY_graybutton">check all in role</a></span>';
								
								echo '<input type="button" value="select role" onclick="checkGroup(\'' . $roleid . '_\', true)" class="AGENCY_graybutton">&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="unselect role" onclick="checkGroup(\'' . $roleid . '_\', false)" class="AGENCY_graybutton">';
								
								echo '&nbsp;&nbsp;&nbsp;&nbsp;';
								echo '<input type="button" value="Remove checked from this role" onclick="document.form' . $roleid .'.submit()"">';								
							}
							
							// if any have been removed, give option to display removed people
							$query3 = "SELECT agency_profiles.user_id, agency_profiles.firstname, agency_profiles.experience, agency_profiles.lastname, agency_profiles.registration_date, agency_mycastings.message FROM agency_profiles, agency_mycastings WHERE agency_mycastings.user_id=agency_profiles.user_id AND agency_profiles.account_type='talent' AND agency_mycastings.role_id='$roleid' AND agency_mycastings.removed='1'";
							$result3 = mysql_query($query3);
							if(mysql_num_rows($result3) > 0) {
								echo '&nbsp;&nbsp;<a href="#TB_inline?height=400&amp;width=450&amp;inlineId=hiddenModalContent" onclick="loaddiv(\'popupcontent\', \'content_removed' . $roleid . '\')" class="thickbox AGENCY_graybutton">view removed submissions</a>';
								echo '<div id="content_removed' . $roleid . '" style="display:none">';
								while($row3 = sql_fetchrow($result3)) {
									$friendid = $row3['user_id'];
									$displayname = $row3['firstname'];
									if(agency_privacy($friendid, 'lastname')) {
										$displayname .= ' ' . $row3['lastname'];
									}
									$displayname = '<span style="color:' . $experiencecolors[$row2['experience']] . '">' . $displayname . '</span>';
									$message = $row3['message'];
									$posterfolder = 'talentphotos/' . $friendid . '_' . $row3['registration_date'] . '/';
									$email = mysql_result(mysql_query("SELECT user_email FROM forum_users WHERE user_id='$friendid'"), 0, 'user_email');
									echo '<img src="';
										if(file_exists($posterfolder . 'avatar.jpg')) {
											echo   $posterfolder . 'avatar.jpg';
										} else if(file_exists($posterfolder . 'avatar.gif')) {
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
							
							
							
							echo'</div><br /><br />';
						}
						if($placebottom) {
							echo '<div align="center"><br /><span id="check_uncheck_all2"><a href="javascript:void(0)" onclick="checkAll(\'_\', true); checkAllToggle2(\'switch_to_uncheck\')" class="AGENCY_graybutton">check all</a></span> <a href="ajax/lightbox_add.php?castingid=' . $castingid . '&height=350&amp;width=300&amp;inlineId=hiddenModalContent" class="thickbox AGENCY_graybutton" onclick="var ProcessArray=getCookie(\'lightbox\')">add checked to lightbox</a> <a class="AGENCY_graybutton" href="clienthome.php?mode=castings&cull=true&amp;castingid=' . $_GET['castingid'] . '" onclick="return confirm(\'This will PERMANENTLY remove all submissions that do not fit the specs of the casting?\')">auto edit</a></div>';
						}
		
					}
					?>
					
				
					
					