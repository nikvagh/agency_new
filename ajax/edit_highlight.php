<?php
session_start();

include('../includes/mysql_connect.php');
include('../includes/vars.php');
include('../includes/agency_functions.php');


unset($loggedin);
if(!empty($_SESSION['user_id'])) { // check if user is logged in
	$loggedin = $_SESSION['user_id'];
}

echo '<div id="processhighlight">';

if($loggedin && !empty($_GET['mode'])) {
	echo showbox('highlights') . '<br /><br />';
	$mode = escape_data($_GET['mode']);
	$success = false; // default (to show form)
	
	
	if(!empty($_POST['highlight'])) { // form submitted
		if(!empty($_POST['posttype'])) {
			$posttype = escape_data($_POST['posttype']);
		} else {
			$posttype = 'silent';
		}
		$highlight = escape_data($_POST['highlight']);
		
		if($mode == 'edit' && isset($_POST['id'])) {  // EDIT HIGHLIGHT
			$id = escape_data((int) $_POST['id']);
			if($posttype == 'delete') {	
				$query = "DELETE FROM agency_profile_highlights WHERE highlight_ID='$id' AND user_id='$loggedin' LIMIT 1"; // IMPORTANT to have "user_id" so people can't delete other people's highlights
				mysql_query($query);
				if(mysql_affected_rows() == 1) {
					$success = true;
				} else {
					echo 'ERROR.';
				}
			} else {
				// replace
				$query = "UPDATE agency_profile_highlights SET highlight='$highlight' WHERE highlight_ID='$id' AND user_id='$loggedin' LIMIT 1"; // IMPORTANT to have "user_id" so people can't edit other people's highlights
				mysql_query($query);
				if(mysql_affected_rows() == 1) {
					$success = true;
				} else {
					echo 'Highlight was not changed<br><br>';
				}
			}
		} else if($mode == 'addnew') {  // ADD HIGHLIGHT
			$query = "INSERT INTO agency_profile_highlights (user_id, highlight) VALUES ('$loggedin', '$highlight')"; 
			mysql_query($query);
			if(mysql_affected_rows() == 1) {
				$id = mysql_insert_id();
				$success = true;
			} else {
				echo 'ERROR.';
			}
		}
			
			
			
			
		if ($success && !empty($id) && $posttype == 'announce' && is_active()) { // post to walls and stuff
			// first check time of last announced highlight
			$timespan = date("Y-m-d H:i:s", (strtotime("NOW")-(8*60*60)));				
			$query = "SELECT feed_id FROM agency_feed WHERE post_date IS NOT NULL AND post_date > '$timespan' AND user_id='$loggedin' ORDER BY post_date DESC LIMIT 1";
			$result = mysql_query ($query);
			if(mysql_num_rows($result) == 0) { // ok to post
				$now = date("Y-m-d H:i:s");
				$query2 = "UPDATE agency_profile_highlights SET announce_time='$now' WHERE highlight_ID='$id' AND user_id='$loggedin'";
				mysql_query($query2);
				if(mysql_affected_rows() == 1) { // only do this if the announce_time was updated to avoid letting them do it multiple times
					// add to home page News Feed
					$query3 = "INSERT INTO agency_feed (user_id, type, content, post_date, highlight_id) VALUES ('$loggedin', 'highlight', '$highlight', '$now', '$id')";
					mysql_query($query3);
					
					// post on wall of all friends
					$sql = "SELECT firstname, lastname FROM agency_profiles WHERE user_id='$loggedin'";
					$result=mysql_query($sql);
					if($row = sql_fetchrow($result)) {
						$fname = $row['firstname'];
						$lname = $row['lastname'];					
						$query3 = "SELECT DISTINCT friend_id FROM agency_friends, forum_users WHERE agency_friends.friend_id=forum_users.user_id AND forum_users.user_type='0' AND agency_friends.user_id='$loggedin' AND agency_friends.confirmed='1'";
						$result3 = mysql_query($query3);
						while ($row3 = @mysql_fetch_array ($result3, MYSQL_ASSOC)) {
							$friendid = $row3['friend_id'];
							$sql = "INSERT INTO agency_wall (user_id, message, poster_id, poster_fname, poster_lname, date) VALUES ('$friendid', '$highlight', '$loggedin', '$fname', '$lname', NOW() )";
							mysql_query($sql);							
						}
					}
				}
			}
		}
	}
		
	if($success) {
		echo 'Your modifications have been made successfully.  <a href="profile.php">Refresh</a> this page to view them.';

	// LOAD HIGHLIGHT FORM
	} else {
	// get the content
		if($mode == 'edit' && isset($_GET['id'])) {  // EDIT HIGHLIGHT
			$id = escape_data($_GET['id']);
			$query = "SELECT highlight FROM agency_profile_highlights WHERE highlight_ID='$id' AND user_id='$loggedin'";
			$result = mysql_query($query);
			if ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
				$highlight = $row['highlight'];
			}
		} else if(isset($_POST['highlight'])) {
			$highlight = $_POST['highlight'];
		}
	
?>
	<form method="post" action="javascript:void(0)" id="edithighlight" name="edithighlight">
		Highlight:<br />
        	<input type="text" style="width:100%" maxlength="80" name="highlight"<?php if(isset($highlight)) echo ' value="' . $highlight . '"'; ?> />
		<br /><br /><div style="font-size:11px">

		
<?php
		if($mode == 'edit' && is_active()) { // must be approved to have the option
?>

		<input type="radio" name="posttype" value="update" checked> I am making an edit to this Highlight<br />
		<input type="radio" name="posttype" value="announce"> I am replacing this Highlight with a New Highlight and would like to announce<sup>*</sup> it<br />
		<input type="radio" name="posttype" value="silent"> I am replacing this Highlight with a New Highlight but would NOT like to announce<sup>*</sup> it<br />
		<input type="radio" name="posttype" value="delete"> Delete this highlight<br />
		
		
<?php
		} else if($mode == 'addnew' && is_active()) { // must be approved to have the option
?>
		<input type="radio" name="posttype" value="announce" checked> I would like to announce<sup>*</sup> this Highlight<br />
		<input type="radio" name="posttype" value="silent"> I would NOT like to announce<sup>*</sup> this Highlight<br />
<?php
		} else if($mode == 'edit') {
?>
		<input type="radio" name="posttype" value="update" checked> I am making an edit to this Highlight<br />
		<input type="radio" name="posttype" value="delete"> Delete this highlight<br />
<?php
		}
		
		if($mode == 'edit' && !empty($_GET['id'])) {
?>
			<input type="hidden" name="id" value="<?php echo escape_data((int) $_GET['id']); ?>" />
<?php
		}
?>

		</div>
		<input type="hidden" name="postit" value="true" />
		<input type="hidden" value="<?php echo time(); ?>" name="creation_time"/>
	<input type="hidden" value="<?php echo agency_add_form_key('highlight'); ?>" name="form_token"/>
	<br /><br />
	<input type="button" value="Submit" onclick="submitform (document.getElementById('edithighlight'),'ajax/edit_highlight.php?mode=<?php echo $mode; if(!empty($_GET['id'])) echo '&id=' . $_GET['id']; ?>','processhighlight',validatetask); return false;" />
	</form>
	<br /><br />
<?php
		if(is_active()) {
?>
	<sup>*</sup>If you "announce" a Highlight, it will appear the News Feed on the site Home Page and added to the wall of all your friends.  You may not announce a highlight more than once every 8 hours.
<?php
		} else {
?>
		As an Approved member your will be able to share your Highlights automatically on the site News Feed and your Friends' Walls.
<?php
		}
	}
} else {
	echo "You must be logged in to take this action.  If you were logged in, your login may have expired.  Refresh this page and log in again.";
}

echo '</div>';

mysql_close(); // Close the database connection.
?>
