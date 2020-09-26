<?php
if(isset($_GET['newsid'])) {
	$id = (int) escape_data($_GET['newsid']);
	$type = 'N';

} else if(isset($_GET['castingid'])) {
	$id = (int) escape_data($_GET['castingid']);
	$type = 'C';
}

$thisurl = 'news.php';
$connector = '?';
if(!empty($_SERVER['QUERY_STRING'])) {
	$thisurl .= '?' . htmlspecialchars($_SERVER['QUERY_STRING']);
	$connector = '&';
}

// PROCESS NEW POST
if(!empty($_POST['Content']) && isset($_POST['Submit']) && is_active()) {
	// CHECK FORM RESUBMISSION!!!!
	$posterid = $_SESSION['user_id'];
	$content = escape_data($_POST['Content']);

	$sql = "SELECT firstname, lastname FROM agency_profiles WHERE user_id='$posterid'";
	$result = mysql_query($sql);
	if ($row = @mysql_fetch_array ($result, MYSQL_ASSOC)) { // If there are results.
		$firstname = escape_data($row['firstname']);
		$lastname = escape_data($row['lastname']);
		$query = "INSERT INTO agency_news_posts (user_id, postpage_id, news_or_casting, poster_fname, poster_lname, content, postdate) VALUES ('$posterid', '$id', '$type', '$firstname', '$lastname', '$content', NOW() )";
		$result = @mysql_query ($query);
	}
}


// DELETE POST
if(isset($_GET['delete']) && is_admin()) {
	$delete = (int) escape_data($_GET['delete']);
	$query = "DELETE FROM agency_news_posts WHERE post_id='$delete' LIMIT 1";
	$result = mysql_query ($query);
}


function censored($message) {
	$pathToFile="badwords.txt";//set this to where you have the profanity filter file.
	if(file_exists($pathToFile)) {
		$badwords=file($pathToFile);

		for($i=0;$i<count($badwords);$i++){
			$thisword = preg_replace("/(\015\012)|(\015)|(\012)/","",$badwords[$i]);
			$message = preg_replace("/\b($thisword)\b/", str_repeat("*", strlen($thisword)), $message);
			$thisword = strtolower($thisword);
			$message = preg_replace("/\b($thisword)\b/", str_repeat("*", strlen($thisword)), $message);
			$thisword = strtoupper($thisword);
			$message = preg_replace("/\b($thisword)\b/", str_repeat("*", strlen($thisword)), $message);
			$thisword = ucfirst($thisword);
			$message = preg_replace("/\b($thisword)\b/", str_repeat("*", strlen($thisword)), $message);
		}
	}
	return $message;
}
?>

<div class="AGENCYblueheading" style="padding-top:30px">COMMENTS</div>
<?php
// Display Comments:
$query = "SELECT * FROM agency_news_posts WHERE postpage_id='$id' AND news_or_casting='$type' ORDER BY postdate ASC";
$result = mysql_query ($query);
if(mysql_num_rows($result) == 0) {
	echo '<div class="AGENCYWallPrimary" style="padding:15px; clear:left; text-align:center">Be the first to post a comment!</div>';
}
while ($row = mysql_fetch_array ($result, MYSQL_ASSOC)) { // If there are projects.
	$postid = $row['post_id'];
	$userid = $row['user_id'];
	$postdate = date('m/d/Y g:i a',(strtotime($row['postdate'])+(3*60*60)));
	$content = nl2br($row['content']);

	echo '<div class="AGENCYWallPrimary" style="margin:15px; clear:left">';

	// get avatar
	$sql2 = "SELECT registration_date, firstname, lastname FROM agency_profiles WHERE user_id='$userid'";
	$result2=mysql_query($sql2);
	if($row2 = sql_fetchrow($result2)) {
		$displayname = $row2['firstname'];
		if(agency_privacy($userid, 'lastname')) {
			$displayname .= ' ' . $row2['lastname'];
		}
		$posterfolder = 'talentphotos/' . $userid . '_' . $row2['registration_date'] . '/';
		echo '<a href="profile.php?u=' . $userid . '" style="text-decoration:none; font-weight:bold"><img src="';
			if(file_exists($posterfolder . 'avatar.jpg')) {
				echo   $posterfolder . 'avatar.jpg';
			} else if(file_exists($posterfolder . 'avatar.gif')) {
				echo   $posterfolder . 'avatar.gif';
			} else {
				echo 'images/friend.gif';
			}
		echo '" height="90" align="left" style="margin:5px 10px 0 0" />' . $displayname . '</a> <b>writes:</b><br />';
	}
	echo censored($content);

	if (is_admin()) { // if logged in as ADMIN
		echo ' <a href="' . $thisurl . $connector . 'delete=' . $postid . '" style="padding:3px; font-weight:bold; text-decoration:none" onclick="return confirm(\'Are you sure you wish to delete this post?\')">X</a>';
	}

	echo '<br /><span style="color:#aaa; font-size:9px; padding-top:10px">' . $postdate . '</span><br clear="left" /></div>';
}

?>
			<div align="center" class="AGENCYWallPrimary">
						<br /><br />
						Post A Comment: <br />
							<form action="<?php echo $thisurl; ?>" method="post" name="postcomment">
                            <textarea name="Content" style="width:300px" rows="5" <?php if(!is_active()) echo 'onFocus="alert(\'You must be logged in with an approved account to post a comment.  Come join our community!\')"'; ?>></textarea>
                            <br /><br />
                            <input type="submit" name="Submit" value="Post It" />
							</form>
                          </div>
						  <br /><br />

