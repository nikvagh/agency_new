<?php
session_start();

include('../includes/mysql_connect.php');
include('../includes/agency_functions.php');

if(isset($_GET['page']) && isset($_GET['total']) && isset($_GET['perpage'])) {
	$maxchars_title = 30;
	$maxchars_content = 135;
	$page = (int) $_GET['page'];
	$perpage = (int) $_GET['perpage']; // how many news items to show per page
	$start = ($page-1) * $perpage;
	$total =(int) $_GET['total'];
	$sql = "SELECT * FROM agency_news WHERE Active='1' AND Date < NOW() ORDER BY Date DESC LIMIT $perpage OFFSET $start";
	$result=mysql_query($sql);
	while($row = sql_fetchrow($result)) {
		$newsid = $row['NewsID'];
		$title = $row['Title'];
		$content = strip_tags(stripslashes($row['Content']));
		$count = 1;
		while($count) {
			$content = str_replace('&nbsp;', ' ', $content, $count);
		}
		// $content = str_replace('$nbsp;', ' ', $content);
		if (strlen($content) > $maxchars_content) {
			$content = substr($content,0,$maxchars_content);
			$content = preg_replace("/\s+[,\.!?\w-]*?$/",'...',$content);
		}
?>
	<br />
<?php
		if(file_exists('../images/news/' . $newsid . '.jpg')) {
			//echo '<a href="news.php?newsid=' . $newsid . '&amp;title=' .  urlencode($title) . '"><img src="../images/news/' . $newsid . '.jpg" align="left" style="margin-right:3px" border="0" /></a>';
		} else if(file_exists('images/news/' . $newsid . '.gif')) {
			//echo '<a href="news.php?newsid=' .  $newsid . '&amp;title=' .  urlencode($title) . '"><img src="../images/news/' . $newsid . '.gif" align="left" style="margin-right:3px" border="0" /></a>';
		}
?>
<span class="AGENCYindextitle"><?php echo $title; ?>:</span> <span class="AGENCYindexcontent"><?php echo $content; ?> |
    <a href="news.php?newsid=<?php echo $newsid; ?>&amp;title=<?php echo urlencode($title); ?>">Read More&gt;</a></span><br clear="all" />
<?php
	}
	// echo 'total: ' . $total . '; page: ' . $page . '; perpage: ' . $perpage;
	if($total > $page * $perpage) {
?>
		<div id="morenews<?php echo $page; ?>">
			<div align="right" style="margin-top:20px">
				<a href="javascript:void(0)" style="font-size:14px; font-weight:bold" onClick="loaddiv('morenews<?php echo $page; ?>', false, 'ajax/morenews.php?page=<?php echo $page+1; ?>&perpage=<?php echo $perpage; ?>&total=<?php echo $total; ?>&')">VIEW MORE NEWS</a>
			</div>
		</div>
<?php
	}
}
mysql_close(); // Close the database connection.
?>
