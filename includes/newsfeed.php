<div class="AGENCYLtBlue AGENCYGeneralTitle" style="cursor: pointer" onclick="window.location='news.php'">News Feed</div>
<?php
if (is_super_admin()) { // if logged in as ADMIN process feed changes
	echo '<form action="home.php" method="post"><input type="text" name="feedpost" style="width:510px">&nbsp;<input type="submit" value="Post"></form>';
	if(!empty($_POST['feedpost'])) {
		$store = htmlspecialchars($_POST['feedpost'], ENT_QUOTES);
		mysql_query("INSERT INTO agency_feed (type, content, post_date, user_id) VALUES ('admin', '$store', NOW(), '0')");
		$url = 'home.php';
		ob_end_clean(); // Delete the buffer.
		header("Location: $url");
		exit(); // Quit the script.
	}
}

if (!empty($_GET['feeddel'])) { // if logged in as admin or poster, delete
	$feedid = escape_data($_GET['feeddel']);
	if(is_admin()) {
		mysql_query("UPDATE agency_feed SET removed='1' WHERE feed_id='$feedid' LIMIT 1");
	} else if($loggedin) {
		mysql_query("UPDATE agency_feed SET removed='1' WHERE feed_id='$feedid' AND user_id='$loggedin' LIMIT 1");
	}
}

?>
<div class="AGENCYhomescroll">

<?php
$maxchars_title = 30;
$maxchars_content = 135;
$perpage = 25; // how many news items to show per page
$now = date("Y-m-d H:i:s");
$total = mysql_result(mysql_query("SELECT COUNT(*) as `Num` FROM `agency_feed` WHERE removed='0'"),0);
$sql = "SELECT * FROM agency_feed WHERE removed='0' AND post_date <= '$now' ORDER BY post_date DESC, feed_id DESC LIMIT $perpage";
$result=mysql_query($sql);
while($row = sql_fetchrow($result)) {
	$feedid = $row['feed_id'];
	$content = $row['content'];
	$userid = $row['user_id'];
	$newsid = $row['news_id'];
	$type = $row['type'];
	$date = $row['post_date'];
?>
	<div class="AGENCYhomescrollThumbnail">
<?php
	// figure out which image to use
	if($type == 'news' && !empty($newsid)) {
		if(file_exists('images/news/' . $newsid . '.jpg')) {
			echo '<a href="news.php?newsid=' . $newsid . '&amp;title=' .  urlencode($title) . '"><img src="images/news/' . $newsid . '.jpg" align="left" style="margin-right:3px" border="0" /></a>';
		} else if(file_exists('images/news/' . $newsid . '.gif')) {
			echo '<a href="news.php?newsid=' .  $newsid . '&amp;title=' .  urlencode($title) . '"><img src="images/news/' . $newsid . '.gif" align="left" style="margin-right:3px" border="0" /></a>';
		}
	} else {
		if($userid == 0) {
			$userid = 2; // default to the user id of the admin which happens to be "2"
		}
		
		// convert links
		$content = strip_tags($content);
		$content = eregi_replace('(www.[-a-zA-Z0-9@:%_\+.~#?&//=]+)', '<a href="http://\\1" target="_blank">\\1</a>', $content);

		
		// get avatar
		$sql2 = "SELECT registration_date, firstname FROM agency_profiles WHERE user_id='$userid'";
		$result2=mysql_query($sql2);
		if($row2 = sql_fetchrow($result2)) {
			$posterfolder = 'talentphotos/' . $userid . '_' . $row2['registration_date'] . '/';
			echo '<a href="profile.php?u=' . $userid . '" style="text-decoration:none; font-size:x-small"><img src="';
				if(file_exists($posterfolder . 'avatar.jpg')) {
					echo   $posterfolder . 'avatar.jpg';
				} else if(file_exists($posterfolder . 'avatar.gif')) {
					echo   $posterfolder . 'avatar.gif';
				} else {
					echo 'images/friend.gif';
				}
			echo '" /><br />' . $row2['firstname'] . '</a>';
		}
	}
	echo '</div>';
	echo ' <div style="float:left; width:470px; overflow:hidden">' . htmlspecialchars_decode($content);
	if (is_admin() || (($loggedin == $userid) && (!empty($loggedin) && !empty($userid)))) { // if logged in as ADMIN
		echo ' <a href="home.php?feeddel=' . $feedid . '" style="padding:3px; font-weight:bold; text-decoration:none" onclick="return confirm(\'Are you sure you wish to delete this feed item?\')">X</a>';
	}

	echo '<div style="color:#aaa; font-size:9px; padding:5px 0 15px 0">' . date('g:ia M j', strtotime($date)) . '</div>';
	echo '</div><br clear="all" />';

}
if($total > $perpage) {
?>
	<div id="morenews1">
		<div align="right" style="margin-top:20px">
			<a href="javascript:void(0)" style="font-size:14px; font-weight:bold" onClick="loaddiv('morenews1', false, 'ajax/morenews.php?page=2&perpage=<?php echo $perpage; ?>&total=<?php echo $total; ?>&')">VIEW MORE NEWS</a>
		</div>
	</div>
<?php
}
?>

</div>