<?php
// This is the right column of the main section of the talent profile page.  I don't think there are any XSS security issues
// as at the moment there isn't any private information here
?>

<div id="AGENCYProfileRight">
<?php
if(agency_account_type() == 'client' && is_active()) {
?>
<div style="position:relative;">
<?php
			$varemail="bookings@theagencyonline.com";
			$varphone="212-944-0801";
			// get email address:
			$query = "SELECT user_email FROM forum_users WHERE user_id='$profileid'";
			$result = @mysql_query($query);
			if ($row = @mysql_fetch_array($result, MYSQL_ASSOC)) {
				if(!empty($row['user_email'])) { echo '<a style="position:absolute; top:26px; font-weight:bold; text-decoration:none;" href="mailto:' . $varemail . '"><span class="AGENCYRed">' . $varemail . '</span></a>'; }
			}
			if(agency_privacy($profileid, 'phone')) {
				echo '<div class="AGENCYRed" style="position:absolute; top:42px; font-weight:bold;">' . $varphone . '</div>';
			}
?>
<img src="images/ClientTalentButtons.png" border="0" usemap="#Map2">
<map name="Map2">
  <area shape="rect" coords="1,94,118,109" href="ajax/lightbox_add.php?height=350&amp;width=300&amp;inlineId=hiddenModalContent" class="thickbox" onClick="ProcessArray=<?php echo $profileid; ?>" title="Add To Lightbox">
  <area shape="rect" coords="0,109,138,126" href="pdf_compcard.php?u=<?php echo $profileid; ?>" title="print/email/save">
</map>


</div>
<?php
} else if(agency_account_type() == 'talent') { // TALENT should nav buttons
?>
<div align="center" style="position:relative">
<div style="color:white; font-size:12px; font-weight:bold; left:145px; position: absolute; text-align:center; top:34px; width:24px;"><?php echo mysql_result(mysql_query("SELECT COUNT(*) as 'num' FROM agency_messages WHERE to_id='$loggedin' AND viewed='0'"),0); ?></div>
<img src="images/talentbuttons.png" width="164" height="101" border="0" usemap="#Map" />
<map name="Map">
  <area shape="rect" coords="1,1,168,28" href="home.php" title="Check Castings">
  <area shape="rect" coords="-1,32,162,66" href="messages.php" title="Messages">
  <area shape="rect" coords="1,73,164,102" href="myaccount.php" title="Edit Profile">
</map>
</div>


<?php
} else if(!$loggedin) {
?>
	<img src="images/apply_now_small.gif" border="0" usemap="#Map2">
	<map name="Map2">
	  <area shape="rect" coords="1,1,168,33" href="#TB_inline?height=240&amp;width=450&amp;inlineId=hiddenModalContent" onclick="loaddiv('popupcontent', 'join_options')" class="thickbox">
	  <area shape="rect" coords="2,38,164,52" href="index2.php?pageid=59&title=What+We+Do">
	</map>
<?php
}
?>

<!--  START: HIGHLIGHTS on left -->
	<div style="font-weight:bold; font-size:10px; background-color:#f3f2f2; padding:4px; margin-top:50px">
	<img src="images/highlights.png" style="margin-top:-36px">
<?php
	$sql = "SELECT * FROM agency_profile_highlights WHERE user_id='$profileid' AND highlight !='' AND highlight IS NOT NULL ORDER BY highlight_ID DESC LIMIT 3";
	$result = mysql_query($sql);
	$num_highlights = mysql_num_rows($result);
    while ($row = sql_fetchrow($result)) {
		$highlightid = $row['highlight_ID'];
		$highlight = $row['highlight'];
		echo '<div class="AGENCYhighlight">' . $highlight;
		if($profileid == $_SESSION['user_id']) {
?>	
			<a style="font-size:x-small" href="#TB_inline?height=400&amp;width=450&amp;inlineId=hiddenModalContent" onclick="loaddiv('popupcontent', false, 'ajax/edit_highlight.php?mode=edit&id=<?php echo $highlightid; ?>')" class="thickbox">edit</a>		
<?php
		}	
		echo '</div>';
	}
	if(($num_highlights < 3) && $profileid == $_SESSION['user_id']) {
?>
<a href="#TB_inline?height=400&amp;width=450&amp;inlineId=hiddenModalContent" onclick="loaddiv('popupcontent', false, 'ajax/edit_highlight.php?mode=addnew&')" class="thickbox">add new</a>		
<?php
	}
?>
	</div>
	
	

<!--  START: SKILLS on left -->
<?php
if(agency_privacy($profileid, 'skills') || (agency_account_type() == 'client' && is_active())) {
	$skills_max_chars = 55;
	$skillsonright = '';
	$more_link = ' <a href="#TB_inline?height=400&amp;width=450&amp;inlineId=hiddenModalContent" onclick="loaddiv(\'popupcontent\', \'content_skills\')" class="thickbox" style="font-weight:normal">view all</a>';
	if(!empty($userinfo['skills_language'])) {
		$checkchars = strip_tags($userinfo['skills_language']);
		if (strlen($checkchars) > $skills_max_chars) {
			$checkchars = substr($checkchars,0,$skills_max_chars) . '...' . $more_link;
			$skillsonright .= '<font color="black">Languages:</font> ' . nl2br($checkchars) . '<br /><br />';
		} else {
			$skillsonright .= '<font color="black">Languages:</font> ' . nl2br($userinfo['skills_language']) . '<br /><br />';
		}
	}
	if(!$max_reached && !empty($userinfo['skills_sports_music'])) {
		$checkchars = strip_tags($userinfo['skills_sports_music']);
		if (strlen($checkchars) > $skills_max_chars) {
			$checkchars = substr($checkchars,0,$skills_max_chars) . '...' . $more_link;
			$skillsonright .=  '<font color="black">Sports & Music:</font> ' . nl2br($checkchars) . '<br /><br />';
		} else {
			$skillsonright .=  '<font color="black">Sports & Music:</font> ' . nl2br($userinfo['skills_sports_music']) . '<br /><br />';
		}
	}
	if(!$max_reached && !empty($userinfo['skills_other'])) {
		$checkchars = strip_tags($userinfo['skills_other']);
		if (strlen($checkchars) > $skills_max_chars) {
			$checkchars = substr($checkchars,0,$skills_max_chars) . '...' . $more_link;
			$skillsonright .=  '<font color="black">Other:</font> ' . nl2br($checkchars);
		} else {
			$skillsonright .=  '<font color="black">Other:</font> ' . nl2br($userinfo['skills_other']);
		}	
	}
	
	// 
	
	// echo nl2br($userinfoskills']);
	if($_SESSION['user_id'] == $profileid) { // if this is the profile of the logged in user
		$skillsonright .=  '<br><br><a class="AGENCY_graybutton" href="myaccount.php?tab=experience">edit</a>';
	}
	
	if(!empty($skillsonright)) {
?>
		<div style="font-weight:bold; font-size:12px; background-color:#f3f2f2; padding:4px; margin-top:50px">
		<img src="images/skills.png" style="margin-top:-36px">
<?php
		echo $skillsonright;
?>
		</div>
<?php
	}
}
?>


</div>