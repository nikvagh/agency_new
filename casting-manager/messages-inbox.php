<?php
@include('sidebar.php');
$profileid = 2;
$sql = "SELECT * FROM agency_profiles WHERE user_id='$profileid'";
		$result=mysql_query($sql);
		if($userinfo = sql_fetchrow($result)) {
			// ok
		} else {
			echo 'ERROR 482';
			exit();
		}


	


if(isset($profileid)) {
	if(agency_account_type() == 'talent') {
		@include('includes/profile_left.php');
?>


<div id="AGENCYProfileWideLeftSurround">

  <div id="AGENCYProfileName" class="AGENCYUserName" style="z-index:5; position:relative">


<div style="float:left; margin-right:10px; color:<?php echo $experiencecolors[$userinfo['experience']]; ?>">
<?php
		echo $userinfo['firstname'];
		if(agency_privacy($profileid, 'lastname')) {
			echo ' ' . $userinfo['lastname'];
		}
?>
</div>

<?php
		if($loggedin) {
			if($loggedin == $profileid) { // this is "my" page, show my status
				echo '<div style="font-weight: bold; position:absolute; right:0px; top:27px; font-size: 14px;" class="' . (is_active() ? 'AGENCYGreen">MyProfile Is ACTIVE' : 'AGENCYRed">MyProfile Is NOT ACTIVE') . '</div>';
			}
		}
?>
  </div>
  <br clear="all" />
<?php
		$linkname = 'MyProfile';
	} else if(agency_account_type() == 'client') {
		echo '<div style="position:relative">';
		$linkname = 'MyAccount';
	}
?>
<div id="AGENCYProfileTabContainer" style="width:650px;margin: 12% 0px 0px 30px;">
<?php
	$tabs = array('Inbox', 'Sent', 'Compose');

	if(isset($_GET['tab'])) {
		$tab = $_GET['tab'];
	} else {
		$tab = 'Inbox';
	}
	
	foreach($tabs as $t) {
		if($t == $tab) {
			echo '<a href="messages.php?tab=' . $t . '" class="AGENCYProfileTab AGENCYProfileTabActive">' . $t . '</a><br>';
		} else {
			echo '<a href="messages.php?tab=' . $t . '" class="AGENCYProfileTab AGENCYProfileTabInActive">' . $t . '</a><br>';
		}
	}
	
	echo '<a href="profile.php?u=' . $profileid . '" class="AGENCYProfileTab AGENCYProfileTabInActive" style="background-color:#E89F8B">' . $linkname . '</a>';
?>
</div>

<div id="AGENCYProfileMiddleContent" style="width:636px; min-height:560px">
<?php
	if(isset($_GET['tab'])) {
		$tab = $_GET['tab'];
	} else {
		$tab = 'Inbox';
	}


	
	IF(!IS_ACTIVE()) {
		ECHO '';
		
		
		
	} ELSE if ($tab == 'Sent') {
			/* ===================================   Start: SENT    =============================== */
		echo '<div id="messagelist">loading...</div>';
?>

	<script language="javascript" type="text/javascript">
	loaddiv('messagelist', false, 'ajax/message_list.php?sent=true&');
	
	</script>		

<?php

			
			/* ===================================   End: SENT    =============================== */
} else if ($tab == 'Compose') {
			/* ===================================   Start: COMPOSE    =============================== */
?>

	<div style="margin:20px; padding:10px" id="processmessage">

	<form name="sendmessage" id="sendmessage" action="javascript:void(0)" method="post">
		<b>Compose Message:</b><br> <br>
<?php
		if(agency_account_type() == 'talent') {
?>        
        
        Recipient:<br /><a href="javascript:void(0)" onclick="document.getElementById('to_friendlist').style.display=''" id="to_image" style="color:black; text-decoration:none">(click a Friend to send them a message)</a>
        
        
<?php
			 $sql = "SELECT DISTINCT friend_id FROM agency_friends, forum_users WHERE agency_friends.friend_id=forum_users.user_id AND forum_users.user_type='0' AND agency_friends.user_id='$profileid' AND agency_friends.confirmed='1'";
			 $result=mysql_query($sql);
			 if(mysql_num_rows($result) == 0) { // no requests
				echo '<br /><br /><p align="center">You may only send messages to your friends.  To make a friend, go to a member profile and send a friend request.</p>';
			 } else {
				 echo '<div id="to_friendlist">';
				 while($row = sql_fetchrow($result)) {
					 $friendid = $row['friend_id'];
					echo '<div class="AGENCYWallThumbnail" style="height: 100px;">';
			
					// get avatar
					$sql2 = "SELECT firstname, lastname, registration_date FROM agency_profiles WHERE user_id='$friendid'";
					$result2=mysql_query($sql2);
					if($row2 = sql_fetchrow($result2)) {
						$posterfolder = 'talentphotos/' . $friendid . '_' . $row2['registration_date'] . '/';
							if(file_exists($posterfolder . 'avatar.jpg')) {
								$image =  $posterfolder . 'avatar.jpg';
							} else if(file_exists($posterfolder . 'avatar.gif')) {
								$image = $posterfolder . 'avatar.gif';
							} else {
								$image = 'images/friend.gif';
							}
						
						echo '<a href="javascript:void(0)" onclick="document.getElementById(\'to_id\').value=' . $friendid . '; document.getElementById(\'to_friendlist\').style.display=\'none\';  document.getElementById(\'to_image\').innerHTML=\'<img src=&quot;' . $image . '&quot; />\';"><img src="' . $image . '">' . $row2['firstname'] . '</a>';
					}
			
					echo '</div>'; // close div for thumbnail
				 }
				 echo '</div><br clear="all" /><br /><br />';
			 }
		} else if(agency_account_type() == 'client' && !empty($_POST['entryid']) && !empty($_REQUEST['lightbox_id'])) {
			
			$userlist = array();
	
			$entryid = array();
			$entryid = $_POST['entryid'];
			
			if(!empty($entryid[0])) {
				foreach($entryid as $id) {
					$userlist[] = mysql_result(mysql_query("SELECT user_id FROM agency_lightbox_users WHERE entry_id='$id'"), 0, 'user_id');
				}
			}
			
			$userlist = array_unique($userlist);
			$usernames = array();
			foreach($userlist as $userid) {
				$sql = "SELECT firstname, lastname FROM agency_profiles WHERE user_id='$userid'";
				$result=mysql_query($sql);
				if($row = sql_fetchrow($result)) {  // "$userinfo" array will be available through file, so no need to access database again
					$usernames[] = $row['firstname'] . ' ' . $row['lastname'];
				}
			}
			
			$emails = array();
			foreach($userlist as $userid) {
				$sql = "SELECT user_email FROM forum_users WHERE user_id='$userid'";
				$result=mysql_query($sql);
				if($row = sql_fetchrow($result)) {
					$emails[] = $row['user_email'];
				}
			}
			
			
			$noform = 1;
			
			echo '<p>Please copy and past the emails from the box below into the BCC field of your personal email program.</p>';
			
			echo '<textarea id="emailclientlist" style="width:100%; height:350px">' . implode(', ', $emails) . '</textarea>';
			
			
			echo '<script>jQuery("#emailclientlist").select();</script>';
			
			
			// echo 'Send To: ' . implode(', ', $usernames) . '<br />';
		
			// echo '<input type="hidden" id="to_list" value="' . implode(',', $userlist) . '" name="to_list">';
			// echo '<input type="hidden" id="send_lb" name="send_lb" value="' . $_REQUEST['lightbox_id'] . '">';

			
		} else if(agency_account_type() == 'client') {
			echo 'Send To Lightbox: ';
			// list lightboxes
			$sql = "SELECT lightbox_id, lightbox_name, timecode FROM agency_lightbox WHERE client_id='$profileid' ORDER BY lightbox_name ASC";
			$result=mysql_query($sql);
			if(mysql_num_rows($result) == 0) {
				echo '<br /><br />You have not created any lightboxes yet.  You may create a new lightbox from your search results or by going to a Talent Profile page and clicking "Add to Lightbox."<br /><br />';
			} else {
				echo '<select id="send_lb" name="send_lb" onchange="loaddiv(\'lb_list\', false, \'ajax/lightbox_members.php?id=\'+this.value)">
						<option>Select A Lightbox</option>';
				while($row = sql_fetchrow($result)) {
					$lightboxid = $row['lightbox_id'];
					$timecode = $row['timecode'];
					$lightboxname = $row['lightbox_name'];
					echo '<option value="' . $lightboxid . '"';
					if(isset($_GET['lightbox_id'])) {
						if($_GET['lightbox_id'] == $row['lightbox_id']) {
							echo ' selected';
							$autochoose = $lightboxid;
						}
					}
					
					echo '>' . $lightboxname . '</option>';
				}
				echo '</select>';
			}
		}
		
		if(empty($noform)) {
?> 
        <div id="lb_list"></div>
<?php
			if(!empty($autochoose)) {
				echo '<script>
				loaddiv(\'lb_list\', false, \'ajax/lightbox_members.php?id=' . $autochoose . '\');
				</script>';
			}
?>
            <br />
            Subject:<br>
            <input type="text" style="width:100%; font-size:12px" name="subject" id="to_subject"<?php if(!empty($_GET['castingid'])) echo 'value="Check Out This Casting"'; ?>>
            <br><br>
            Message:<br>
            <textarea style="width:100%; font-size:12px" rows="10" name="message"><?php
            if(!empty($_GET['castingid'])) {
                echo 'You may be interested in a casting at The Agency Online:
                
    http://www.theAgencyOnline.com/news.php?castingid=' . $_GET['castingid'];
            }
            ?></textarea><br>
            <br>
            <input type="hidden" value="" name="to" id="to_id">
            <input type="hidden" value="true" name="sendit">
            <input type="hidden" name="creation_time" value="1349132467">
        <input type="hidden" name="form_token" value="478146734f72e7b9819baff01bf01a4c75e4f38e">
<?php
			if(agency_account_type() == 'talent') {
?>   
	<input type="button" onclick="if(!(document.getElementById('to_id').value)) { alert('Please select a recipient.'); } else if(!(document.getElementById('to_subject').value)) { alert('Please enter a Subject.'); } else { submitform (document.getElementById('sendmessage'),'ajax/message_process.php','processmessage',validatetask); } return false;" value="Send">
<?php
			} else if(agency_account_type() == 'client' && !empty($_POST['entryid'])) {
?>   
	<input type="button" onclick="if(!(document.getElementById('to_list').value)) { alert('It appears no Talent was selected.  Please go back to the lightbox and be sure to select at least one Talent.'); } else if(!(document.getElementById('to_subject').value)) { alert('Please enter a Subject.'); } else { submitform (document.getElementById('sendmessage'),'ajax/message_process.php','processmessage',validatetask); } return false;" value="Send">
<?php	
			} else if(agency_account_type() == 'client') {
?>   
	<input type="button" onclick="if(!(document.getElementById('send_lb').value)) { alert('Please select a lightbox.'); } else if(!(document.getElementById('to_subject').value)) { alert('Please enter a Subject.'); } else { submitform (document.getElementById('sendmessage'),'ajax/message_process.php','processmessage',validatetask); } return false;" value="Send">
<?php	
			}
		
		}
?>   
    
    </form>
	</div>

<?php

			
			/* ===================================   End: COMPOSE    =============================== */
	} else {

			/* ===================================   Start: INBOX    =============================== */
		echo '<div id="messagelist">loading...</div>';
?>

	<script language="javascript" type="text/javascript">
	loaddiv('messagelist', false, 'ajax/message_list.php?');
	
	</script>		

<?php
			/* ===================================   End: INBOX    =============================== */
	}


	if(agency_account_type() == 'talent') {
?>
  </div>
</div>

<div style="clear:both">

</div>









<!-- below are content divs which are not displayed.  Content is inserted into ThickBox popup -->

<?php
		include('includes/profile_popups.php');

	} else if(agency_account_type() == 'client') {  // CLIENT
?>	
	<!--  START: client Buttons -->
	<div style="width:144px; height:242px; position:absolute; top:0px; left:664px">
	<?php echo clientbuttons(true); ?>
	</div>
    
</div>

<?php
	}
}
if($_GET['refresh'] && !$submitmessage) {
	echo '<script type="text/javascript">document.location=\'profile.php?u=' . $profileid . '\';</script>';
}
?>
