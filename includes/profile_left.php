<?php
// This is the right column of the main section of the talent profile page.  I don't think there are any XSS security issues
// as there are no queries to the database done in this section
?>
<div id="AGENCYProfileLeft">
  <div id="AGENCYProfileAvatar">

  <img src="<?php
  	$folder = 'talentphotos/' . $profileid. '_' . $userinfo['registration_date'] . '/';
	if(file_exists($folder . 'avatar.jpg')) {
		echo   $folder . 'avatar.jpg';
	} else if(file_exists($folder . 'avatar.gif')) {
		echo   $folder . 'avatar.gif';
	} else {
		echo 'images/friend.gif';
	}
?>" />
  </div>

  <!--  START: menu list on left -->
  <div id="AGENCYProfileLeftList">
      <img src="images/camera.gif" width="54" height="47" align="left" style="margin-right:4px;" />
	  <div style="height:10px"></div>
        <span style="color:#FF0000; font-size:16px;">Talent<br />
        Info: </span>

<?php
if($_SESSION['user_id'] == $profileid) { // if this is the profile of the logged in user
?>
<br /><br />
<div class="casting_drop settings">
<ul class="sf-menu">
	<li><a href="javascript:void(0)" style="padding-left:0; font-size:14px;">Settings</a>
		<ul style="width:150px">
			<li><a href="myaccount.php">edit profile</a></li>
            <li><a href="myimages.php">manage photos</a></li>
            <li><a href="myaccount.php?tab=privacy">privacy settings</a></li>
<?php
	if($_SESSION['user_id'] == $profileid) {
?>
                 <li><a href="changepassword.php">change password</a></li>
                  
<?php
		if(get_rec_payment_id($profileid) && mysql_result(mysql_query("SELECT payProcessed FROM agency_profiles WHERE user_id='$profileid'"), 0, 'payProcessed')) {
?>
                  <li><a href="account_update.php?mode=payment">edit payment</a></li>
<?php
		} else {
			echo '<li><a href="payment.php">edit payment</a></li>';
		}
	}
?>            
		</ul>
    </li>
</ul>
</div>

      <!--
	  <p align="center"><a class="thickbox AGENCY_graybutton" onclick="loaddiv('popupcontent', 'profile_mylink')" href="#TB_inline?height=400&amp;width=450&amp;inlineId=hiddenModalContent"><b>send my profile</b></a></p>
	-->
  <?php
}

if(is_admin() && !empty($profileid)) {
	$sql = "SELECT username FROM forum_users WHERE user_id='$profileid'";
	$result=mysql_query($sql);
	if($getusername= sql_fetchrow($result)) {  // "$userinfo" array will be available through file, so no need to access database again
		echo '<p>username: ' . $getusername['username'] . '</p>';
	}
}
?>
      <br clear="all" /><p><?php echo $userinfo['location']; ?></p>

      <p><a class="thickbox" onclick="loaddiv('popupcontent', 'content_general')" href="#TB_inline?height=400&amp;width=450&amp;inlineId=hiddenModalContent">general info</a></p>
      <p><a class="thickbox" onclick="loaddiv('popupcontent', 'content_resume')" href="#TB_inline?height=400&amp;width=450&amp;inlineId=hiddenModalContent">resume</a></p>
<?php
if(agency_account_type() == 'client' && is_active()) {
	if(!empty($userinfo['headshot'])) {
		if(file_exists($folder . '/' . $userinfo['headshot'])) {
			echo '<p><a href="' . $folder . $userinfo['headshot'] . '" target="_blank">headshot</a></p>';
		}
	}
} else {
?>
      <p><a class="thickbox" onclick="loaddiv('popupcontent', 'content_headshot')" href="#TB_inline?height=400&amp;width=450&amp;inlineId=hiddenModalContent">headshot</a></p>
<?php
}
?>
	  <p><a class="thickbox" onclick="loaddiv('popupcontent', 'content_bio')" href="#TB_inline?height=400&amp;width=450&amp;inlineId=hiddenModalContent">bio</a></p>
      <p><a class="thickbox" onclick="loaddiv('popupcontent', 'content_links')" href="#TB_inline?height=200&amp;width=450&amp;inlineId=hiddenModalContent">links</a></p>
      <p><a class="thickbox" onclick="loaddiv('popupcontent', 'content_skills')" href="#TB_inline?height=400&amp;width=450&amp;inlineId=hiddenModalContent">skills</a></p>


  </div>
  <!--  END: menu list on left -->
</div>
