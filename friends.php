<?php
session_start(); // for temporary login, a session is needed
@include('includes/header.php');

if(!empty($_GET['u'])) {
	$profileid= (int) $_GET['u'];
	if(!is_active() && !isset($_SESSION['user_id']) && !is_admin() && mysql_result(mysql_query("SELECT COUNT(*) as 'Num' FROM forum_users WHERE user_id='$profileid' AND user_type='0'"),0) == 0) {
		unset($profileid);
	}
} else if(!empty($_SESSION['user_id'])) { // check if user is logged in
	$profileid= $_SESSION['user_id'];
}


// first get the folder name
$sql = "SELECT * FROM agency_profiles WHERE user_id='$profileid'";
$result=mysql_query($sql);
if($userinfo = sql_fetchrow($result)) {  // "$userinfo" array will be available through file, so no need to access database again
	$folder = 'talentphotos/' . $profileid. '_' . $userinfo['registration_date'] . '/';
	$profilecode = $userinfo['registration_date'];
}

if(!empty($profileid) && isset($folder)) { // if folder (reg date) is not found, no images will be found, something is wrong so don't display


	/* =========================   Start: PROCESS OF PROFILE CHANGES ==================== */

	// ACCEPT FRIEND REQUEST
	if(!empty($_GET['accept'])) {
		$friend = $_GET['accept'];
		$loggedin = $_SESSION['user_id'];
		$sql = "SELECT * FROM agency_friends WHERE user_id='$friend' AND friend_id='$loggedin'"; // check if request was made
		$result = mysql_query($sql);
		if($row = sql_fetchrow($result)) { // request was made, make friends
				$sql = "UPDATE agency_friends SET confirmed='1', denied='0' WHERE user_id='$friend' AND friend_id='$loggedin'";
				mysql_query($sql);
				$sql = "INSERT INTO agency_friends (user_id, friend_id, confirmed) VALUES ('$loggedin', '$friend', '1')";
				mysql_query($sql);
		} else { // request was not made
			echo 'You are not authorized to take this action.';
		}
	}

	// DENY FRIEND REQUEST
	if(!empty($_GET['deny'])) {
		$friend = $_GET['deny'];
		$loggedin = $_SESSION['user_id'];
		$sql = "SELECT * FROM agency_friends WHERE user_id='$friend' AND friend_id='$loggedin'"; // check if request was made
		$result = mysql_query($sql);
		if($row = sql_fetchrow($result)) {
				$sql = "UPDATE agency_friends SET confirmed='0', denied='1' WHERE user_id='$friend' AND friend_id='$loggedin'";
				mysql_query($sql);
		} else {
			echo 'You are not authorized to take this action.';
		}
	}

	// SEND FRIEND REQUEST
	if(!empty($_GET['request']) && is_active()) {
		$request = $_POST['request'];
		$loggedin = $_SESSION['user_id'];
		// first see if user is already a friend, has been denied, or a request has already been sent
		$sql = "SELECT * FROM agency_friends WHERE user_id='$loggedin' AND friend_id='$profileid'";
		$result = mysql_query($sql);
		if($row = sql_fetchrow($result)) {
			// if there's a result, this person has already made contact, do not send request
			if($row['denied'] == '1') { // this request has already been denied
				echo 'Your friend request has been denied.';
			} else if($row['confirmed'] == '0') {
				echo 'You have already requested this friend.  They have not yet accepted your request';
			} else {
				echo 'This person is already your friend';
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
				echo 'You are now friends with this person';
			} else {
				// send friend request
		   		$sql = "INSERT INTO agency_friends (user_id, friend_id) VALUES ('$loggedin', '$profileid')";
		   		mysql_query($sql);
		   		echo 'Your friend request has been sent';
			}
	   //	}
	   //	if(((int) $db->sql_fetchfield('num')) == 0) { // if request does not already exist
	   // 	unset($result);



		}
		unset($result);

		// check to see if proper request code was sent.  The request code is here to make it slightly more difficult
		// to run a script to automatically send friend requests to everyone.  It won't stop it, but might slow it down.


	}


	/* =========================   End: PROCESS OF PROFILE CHANGES ==================== */

@include('includes/profile_left.php');
?>





<div id="AGENCYProfileMiddleSurround">
  <div id="AGENCYProfileName" class="AGENCYUserName">
<?php
	$sql = "SELECT * FROM agency_profiles WHERE user_id='$profileid'";
	$result=mysql_query($sql);
	if($row = sql_fetchrow($result)) {
		echo $row['firstname'] . ' ' . $row['lastname'];
	}
?>
<span style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:16px; color: #000000; font-weight:normal"> MyFriends</span>
  </div>

<div id="AGENCYProfileTabContainer">
<?php
	if($_SESSION['user_id'] == $profileid) {
		$tabs = array('Friends', 'Friend Requests');
	} else {
		$tabs = array('Friends');
	}
	if(empty($_GET['tab'])) {
		$_GET['tab'] = 'Friends';
	}
	foreach($tabs as $t) {
		if($t == $_GET['tab']) {
			echo '<span class="AGENCYProfileTab AGENCYProfileTabActive">' . $t . '</span>';
		} else {
			echo '<a href="friends.php?tab=' . $t . '&u=' . $profileid . '" class="AGENCYProfileTab AGENCYProfileTabInActive">' . $t . '</a>';
		}
	}

   echo '<a href="profile.php?u=' . $profileid . '" class="AGENCYProfileTab AGENCYProfileTabInActive">Profile</a>';
?>
</div>

<div id="AGENCYProfileMiddleContent">
<?php
	if(isset($_GET['tab'])) {
		$tab = $_GET['tab'];
	} else {
		$tab = 'Friends';
	}

	if($tab == 'Friend Requests' && $_SESSION['user_id'] == $profileid) {
			/* ===================================   Start: FRIEND REQUESTS    =============================== */
		 $sql = "SELECT DISTINCT agency_friends.user_id FROM agency_friends, forum_users WHERE agency_friends.user_id=forum_users.user_id AND forum_users.user_type='0' AND agency_friends.friend_id='$profileid' AND agency_friends.confirmed='0' AND agency_friends.denied='0'";
		 $result=mysql_query($sql);
		 if(mysql_num_rows($result) == 0) { // no requests
		 	echo '<br /><br /><p align="center">You have no friend requests at this time.</p>';
		 } else {
			 while($row = sql_fetchrow($result)) {
		 		 $friendid = $row['user_id'];
		 		echo '<div class="AGENCYWallPrimary"><div class="AGENCYWallThumbnail">';

				// get avatar
				$sql2 = "SELECT firstname, lastname, registration_date FROM agency_profiles WHERE user_id='$friendid'";
				$result2=mysql_query($sql2);
		 		if($row2 = sql_fetchrow($result2)) {
					$posterfolder = 'talentphotos/' . $friendid . '_' . $row2['registration_date'] . '/';
					echo '<a href="profile.php?u=' . $friendid . '"><img src="';
						if(file_exists($posterfolder . 'avatar.jpg')) {
							echo   $posterfolder . 'avatar.jpg';
						} else if(file_exists($posterfolder . 'avatar.gif')) {
							echo   $posterfolder . 'avatar.gif';
						} else {
							echo 'images/avatar_temp.gif';
						}
					echo '" /></a>';
				}


				echo '</div>'; // close div for thumbnail
			 	$displayname = $row2['firstname'];
		 		if(agency_privacy($friendid, 'lastname')) {
		 			$displayname .= ' ' . $row2['lastname'];
				}
				echo '<div class="AGENCYWallPost"><a href="profile.php?u=' . $friendid . '" style="text-decoration:none; font-weight:bold">' . $displayname . '</a> ';
				echo ' has invited you to be their friend.';
				if($profileid == $_SESSION['user_id']) {
		   			echo ' <a href="friends.php?tab=Friend%20Requests&amp;accept=' . $friendid . '" style="padding:3px; font-weight:bold; text-decoration:none"">ACCEPT</a>';
					echo ' / <a href="friends.php?tab=Friend%20Requests&amp;deny=' . $friendid . '" style="padding:3px; font-weight:bold; text-decoration:none"">DENY</a>';
				}
				echo '</div>';
				echo '</div>';
		 		echo '<br clear="all" />';
			 }
		 }

			/* ===================================   End: FRIEND REQUESTS   =============================== */

	} else {
			/* ===================================   Start: FRIENDS    =============================== */
		 $sql = "SELECT DISTINCT friend_id FROM agency_friends, forum_users WHERE agency_friends.friend_id=forum_users.user_id AND forum_users.user_type='0' AND agency_friends.user_id='$profileid' AND agency_friends.confirmed='1'";
		 $result=mysql_query($sql);
		 if(mysql_num_rows($result) == 0) { // no requests
		 	echo '<br /><br /><p align="center">No friends at this time.</p>';
		 } else {
		 	$count = 8;
			 while($row = sql_fetchrow($result)) {
		 		 $friendid = $row['friend_id'];
		 		echo '<div class="AGENCYWallThumbnail">';

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

				echo '</div>'; // close div for thumbnail
				if($count == 1) {
					$count = 8;
					echo '<br clear="all" />';
				} else {
					$count--;
				}
			 }
			 echo '<br clear="all" />';
		 }
			/* ===================================   End: FRIENDS    =============================== */
	}

?>
</div>
</div>

<?php
	@include('includes/profile_right.php');
?>
<div style="clear:both">
  <?php

	unset($result);
?>
</div>









<!-- below are content divs which are not displayed.  Content is inserted into ThickBox popup -->

<?php
	include('includes/profile_popups.php');
} else {
 	$url = 'home.php';
	ob_end_clean(); // Delete the buffer.
	header("Location: $url");
	exit(); // Quit the script.
}
@include('includes/footer.php');
?>
