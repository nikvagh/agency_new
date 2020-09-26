
<div class="AGENCYShowcaseLongMain">
<div class="AGENCYRed AGENCYGeneralTitle" style="height:33px;">Featured Talent
<?php
if(is_active()) {
	if(agency_account_type() == 'client') {
?>
<form name="searchit" action="clienthome.php?mode=search" method="post" style="display:none" >
<input type="hidden" name="submitsearch">
</form>
<a style="color:gray; text-decoration:none; font-size:12px; font-weight:normal; float:none; height:auto; margin:0; width:auto;" href="javascript:document.searchit.submit();">view all</a>
<?php
	} else if(agency_account_type() == 'talent') {
?>
<form name="searchit" action="search.php" method="post" style="display:none" >
<input type="hidden" name="submitsearch">
</form>
<a style="color:gray; text-decoration:none; font-size:12px; font-weight:normal; float:none; height:auto; margin:0; width:auto;" href="javascript:document.searchit.submit();">view all</a>
<?php
	}
}
?>
</div>
<?php
$time = date('Y-m-d H:i:s', strtotime("NOW"));
if(file_exists("./userfiles/showcase_cache.php") && mysql_result(mysql_query("SELECT COUNT(*) as 'Num' FROM agency_vars WHERE varname='featured_talent_expire' AND varvalue>'$time'"),0) > 0) {
	// if expire time has not yet passed, show from cache
	include('./userfiles/showcase_cache.php');
} else {
	$delay_in_minutes = 30; // how long the featured talent are displayed before refreshing

	$SC_F = array();
	$SC_M = array();
	$SC_O = array();
	$SC_F_rd = array();
	$SC_M_rd = array();
	$SC_O_rd = array();
	$SC_list = array();
	$SC_list_rd = array();
	
	//  AND u.user_avatar_type<>'0'
	// p.gender='F' AND
	$sql = "SELECT p.user_id, p.registration_date, p.gender FROM agency_profiles p, forum_users u WHERE p.account_type='talent' AND p.user_id=u.user_id AND u.user_type='0' ORDER BY p.registration_date DESC";
	$result=mysql_query($sql);
	while($row = sql_fetchrow($result)) {
		switch($row['gender']) {
			case 'F':
				$SC_F[] = $row['user_id'];
				$SC_F_rd[] = $row['registration_date'];
				break;
			case 'M':
				$SC_M[] = $row['user_id'];
				$SC_M_rd[] = $row['registration_date'];
				break;
			default:
				$SC_O[] = $row['user_id'];
				$SC_O_rd[] = $row['registration_date'];
				break;
		}
	}
	
	$dc_done = false;
	for($total=12; $total > 0; $total--) {  // 24 results
		if(sizeof($SC_F) > 0) { // female
			$sizenum = sizeof($SC_F) - 1;
			$pick = rand(0, $sizenum);
			$SC_list[] = $SC_F[$pick];
			$SC_list_rd[] = $SC_F_rd[$pick];
			array_splice($SC_F, $pick, 1);
			array_splice($SC_F_rd, $pick, 1);
		}
		if(sizeof($SC_M) > 0) {
			$sizenum = sizeof($SC_M) - 1;
			$pick = rand(0, $sizenum);
			
			$SC_list[] = $SC_M[$pick];
			$SC_list_rd[] = $SC_M_rd[$pick];
			array_splice($SC_M, $pick, 1);
			array_splice($SC_M_rd, $pick, 1);
		}
	}
	
	$content = '';
	foreach($SC_list as $key => $u_id) {
		//echo $SC_list[$key];
		$posterfolder = 'talentphotos/' . $u_id . '_' . $SC_list_rd[$key] . '/';
		$content .= '<a href="profile.php?u=' . $u_id . '"><img src="';
			if(file_exists($posterfolder . 'avatar.jpg')) {
				$content .= $posterfolder . 'avatar.jpg';
			} else if(file_exists($posterfolder . 'avatar.gif')) {
				$content .= $posterfolder . 'avatar.gif';
			} else {
				$content .= 'images/friend.gif';
			}
		$content .= '" /></a>';
	}
	
	
	echo $content;
	
	$myFile = "./userfiles/showcase_cache.php";
	$fh = fopen($myFile, 'w');
	fwrite($fh, $content);
	fclose($fh);

	$newtime = mktime(date("H"), date("i") + $delay_in_minutes, date("s"), date("n"), date("j"), date("Y"));
	$newtime = date('Y-m-d H:i:s', $newtime);

	if(mysql_result(mysql_query("SELECT COUNT(*) as 'Num' FROM agency_vars WHERE varname='featured_talent_expire'"),0) > 0) { // make sure var exists
		$query = "UPDATE agency_vars SET varvalue='$newtime' WHERE varname='featured_talent_expire'";
	} else {
		$query = "INSERT INTO agency_vars (varname, varvalue) VALUES ('featured_talent_expire', '$newtime')";
	}
	mysql_query($query);
	
	/*
	$sql = "SELECT p.user_id, p.registration_date FROM agency_profiles p, forum_users u WHERE account_type='talent' AND p.user_id=u.user_id AND u.user_type='0' ORDER BY p.registration_date DESC LIMIT 12";
	 $result=mysql_query($sql);
	 while($row = sql_fetchrow($result)) {
		$posterfolder = 'talentphotos/' . $row['user_id'] . '_' . $row['registration_date'] . '/';
		echo '<a href="profile.php?u=' . $row['user_id'] . '"><img class="AGENCYshowcaseimage" src="';
			if(file_exists($posterfolder . 'avatar.jpg')) {
				echo   $posterfolder . 'avatar.jpg';
			} else if(file_exists($posterfolder . 'avatar.gif')) {
				echo   $posterfolder . 'avatar.gif';
			} else {
				echo 'images/friend.gif';
			}
		echo '"></a>';
	 }
	 */
	 
	unset($SC_F);
	unset($SC_M);
	unset($SC_O);
	unset($SC_F_rd);
	unset($SC_M_rd);
	unset($SC_O_rd);
	unset($SC_list);
	unset($SC_list_rd);
}
?>
</div>