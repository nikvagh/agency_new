<?php
// include('includes/UrlLinker.php');
?>
  <div style="width:200px; font-size:11px">
	<div class="AGENCYRed AGENCYGeneralTitle" style="height:33px;">ShoutOuts</div>
<?php
		// PROCESS NEW SHOUT
		if (!empty($_POST['myshout'])) { // Handle the form.
			if(is_active()) { // check if user is logged in
				$shouterid = $_SESSION['user_id'];
		 		$shout = escape_data(utf8_encode($_POST['myshout']));
		 		$shouterip = getRealIpAddr();
		 		// see if shouter posted in last 8 hours
		 		$timespan = date("Y-m-d H:i:s", (strtotime("NOW")-(8*60*60)));
		 		$sql = "SELECT * FROM agency_shoutouts WHERE user_id='$shouterid' AND date > '$timespan'";
		 		$result=mysql_query($sql);
		 		if((mysql_num_rows($result) == 0) || is_super_admin()) { // no posts in last 8 hours, thus allowed to post again
					$sql = "INSERT INTO agency_shoutouts (user_id, shout, shouter_ip, date) VALUES ('$shouterid', '$shout', '$shouterip', NOW() )";
					mysql_query($sql);
				} else {
				 	echo '<script>alert(\'You must wait 8 hours between shouts.  It appears you have already made a recent post\'); </script>';
				}
			}
		}

		// DELETE A SHOUT
		if (is_admin() && isset($_GET['shoutdel'])) { // if logged in as ADMIN
			$shoutid = escape_data((int) $_GET['shoutdel']);
			$sql = "DELETE FROM agency_shoutouts WHERE shout_id='$shoutid' LIMIT 1";
			mysql_query($sql);
		}


		// DISPLAY SHOUTS
		$shoutcount = 0;
		$sql = "SELECT * FROM agency_shoutouts ORDER BY date DESC LIMIT 24";
		 $result=mysql_query($sql);
		 if(mysql_num_rows($result) == 0) { // no requests
		 	echo 'There are not Shout Outs at this time.';
		 } else {
			 while($row = sql_fetchrow($result)) {
			 	if($shoutcount == 12 && !isset($show_all_shouts)) {
					echo '<div id="morelink"><a href="javascript:void(0)" onclick="document.getElementById(\'moreshouts\').style.display=\'block\'; document.getElementById(\'morelink\').style.display=\'none\'; castings_resize()">view more</a></div>';
					echo '<div id="moreshouts" style="display:none">';
				}
				$shoutcount++;
		 	 	$shouter_id = $row['user_id'];
		 		echo '<div>';

				// get avatar
				$sql2 = "SELECT registration_date, firstname FROM agency_profiles WHERE user_id='$shouter_id'";
				$result2=mysql_query($sql2);
		 		if($row2 = sql_fetchrow($result2)) {
					$posterfolder = 'talentphotos/' . $shouter_id . '_' . $row2['registration_date'] . '/';
					echo '<div class="AGENCYShoutThumbnail"><div style="max-height:60px; overflow:hidden"><a href="profile.php?u=' . $shouter_id . '"><img src="';
						if(file_exists($posterfolder . 'avatar.jpg')) {
							echo   $posterfolder . 'avatar.jpg';
						} else if(file_exists($posterfolder . 'avatar.gif')) {
							echo   $posterfolder . 'avatar.gif';
						} else {
							echo 'images/friend.gif';
						}
					echo '" /></a></div><div><a style="text-decoration:none; font-size:x-small;" href="profile.php?u=' . $shouter_id . '">' . $row2['firstname'] . '</a></div></div>';					
				}
				$width = '150';
				if($_SERVER["PHP_SELF"] == '/news.php') {
					$width = '140';
				}
				
				$shout = utf8_decode($row['shout']);
				// $shout = htmlEscapeAndLinkUrls($shout);
				
				echo '</div> <div style="float:left; width:' . $width . 'px; overflow:hidden">' . $shout;

				if (is_admin()) { // if logged in as ADMIN
		   			echo ' <a href="home.php?shoutdel=' . $row['shout_id'] . '" style="padding:3px; font-weight:bold; text-decoration:none" onclick="return confirm(\'Are you sure you wish to delete this post?\')">X</a>';
				}

				echo '<div style="color:#aaa; font-size:9px; padding:5px 0 15px 0">' . date('g:ia M j', strtotime($row['date'])) . '</div>';

				echo '</div><br clear="all" />';
			 }
			 if($shoutcount > 10 && !isset($show_all_shouts)) {
			 	echo '</div>';
			}
		 }
		 // SHOUT SUBMIT FORM
		 if(is_active()) { // check if user is logged in
   		 	echo '<form class="AGENCYWallPrimary" style="padding:10px 0" method="post" action="' . htmlspecialchars($_SERVER['REQUEST_URI']) . '" name="shoutout">' .
			'<input type="text" name="myshout" style="width:190px" maxlength="150" /><br /><br /><input type="submit" value="Shout" />' .
			'<input type="hidden" value="' . time() . '" name="creation_time"/>' .
			'<input type="hidden" value="' . agency_add_form_key('shoutout') . '" name="form_token"/>' .
			'</form>';
		 }
?>
  </div>