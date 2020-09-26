<?php
session_start();

include('../includes/mysql_connect.php');
include('../includes/vars.php');
include('../includes/agency_functions.php');

if(!empty($_GET['page'])) {
	$pagelink = '&page=' . (int) $_GET['page'];
} else {
	$pagelink = '';
}
if(!empty($_GET['sent'])) {
	$sentlink = '&sent=true';
} else {
	$sentlink = '';
}

echo '<a href="javascript:loaddiv(\'messagelist\', false, \'ajax/message_list.php?x=1' . $pagelink . $sentlink . '&\')" style="font-weight:bold; color:black">CLOSE</a>';



if(is_active()) {
	if(!empty($_GET['message_id'])) { // check if user is logged in
		$profileid= $_SESSION['user_id'];
		$message_id = escape_data((int) $_GET['message_id']);
		$query = "SELECT * FROM agency_messages WHERE to_id=$profileid AND message_id=$message_id";  // check to see if user exists.
		$result_messages = mysql_query ($query);
		if ($row = mysql_fetch_array ($result_messages, MYSQL_ASSOC)) {
	
			// see if this is a response:
			if(!empty($row['reply_to'])) {
				$reply_to = $row['reply_to'];
				$query2 = "SELECT * FROM agency_messages WHERE message_id=$reply_to";  // check to see if user exists.
				$result_messages2 = mysql_query ($query2);
				if ($row2 = mysql_fetch_array ($result_messages2, MYSQL_ASSOC)) {
					$subject = stripslashes($row2['subject']);
					$sender_id = $row2['from_id'];
					$sender_name = stripslashes($row2['from_name']);
					$message = stripslashes(nl2br($row2['message']));
					$date_entered = $row2['date_entered'];
					$sent_time=date('F d, Y g:i A',strtotime($date_entered));
	?>
	<div style="clear:both; border-bottom:3px solid #CCC; padding-left:70px">
	<?php echo $subject; ?>
	</div>
	<div style="width:60px; float:left">
	
	<?php
					// get the folder name
					$sql3 = "SELECT registration_date FROM agency_profiles WHERE user_id='$sender_id'";
					$result3=mysql_query($sql3);
					if($row3 = sql_fetchrow($result3)) {
						$folder = 'talentphotos/' . $sender_id. '_' . $row3['registration_date'] . '/';
					}

					echo avatar_link($sender_id) . '<img src="';
					if(file_exists('../' . $folder . 'avatar.jpg')) {
						echo   $folder . 'avatar.jpg';
					} else if(file_exists('../' . $folder . 'avatar.gif')) {
						echo   $folder . 'avatar.gif';
					} else {
						echo 'images/friend.gif';
					}
					echo '" border="0" width="60" /></a>';
	?>
	</div>
	
	<div style="width:360px; float:left; padding:10px">
	<?php echo avatar_link($sender_id) . '<b>' . $sender_name . '</b></a> <span style="color:#AAA; font-size:10px">' . $sent_time . '</span><br />'; ?>
	<?php echo $message; ?>
	</div>
	<?php
				}
			}
	
	
	
	
			$subject = stripslashes($row['subject']);
			$sender_id = $row['from_id'];
			$sender_name = stripslashes($row['from_name']);
			$message = stripslashes(nl2br($row['message']));
			$date_entered = $row['date_entered'];
			$sent_time=date('F d, Y g:i A',strtotime($date_entered));
			$query = "UPDATE agency_messages SET viewed='1' WHERE to_id=$profileid AND message_id=$message_id";
			$result = mysql_query ($query);
			$query = "UPDATE agency_messages_out SET email_sent='1' WHERE to_id=$profileid AND message_id=$message_id";
			$result = mysql_query ($query);
	
	?>
	<div style="clear:both; border-bottom:3px solid #CCC; padding-left:130px">
	<b><?php echo $subject; ?></b>
	</div>
	<div style="width:120px; float:left">
	
	<?php
			// get the folder name
			$sql = "SELECT registration_date FROM agency_profiles WHERE user_id='$sender_id'";
			$result=mysql_query($sql);
			if($row = sql_fetchrow($result)) {
				$folder = 'talentphotos/' . $sender_id. '_' . $row['registration_date'] . '/';
			}

	
			echo avatar_link($sender_id) . '<img src="';
			if(file_exists('../' . $folder . 'avatar.jpg')) {
				echo   $folder . 'avatar.jpg';
			} else if(file_exists('../' . $folder . 'avatar.gif')) {
				echo   $folder . 'avatar.gif';
			} else {
				echo 'images/friend.gif';
			}
			echo '" border="0" width="120" /></a>';
	
	?>
	</div>
	
	<div style="width:300px; float:left; padding:10px">
<?php echo avatar_link($sender_id) . '<b>' . $sender_name . '</b></a> <span style="color:#AAA; font-size:10px">' . $sent_time . '</span><br />'; ?>
	<?php echo $message; ?>
	</div>
	
	
	
	
	
		<div id="replybox" align="center" style="clear:both; padding-top:20px">
		<form action="javascript:void" name="reply" id="reply" method="post">
		<textarea name="messagereply" style="font-size:10px; width:400px" rows="5"></textarea>
		<input type="hidden" name="message_id" value="<?php echo $message_id; ?>" />
		<br /><br />
		<input type="button" value="Reply" onclick="submitform (document.getElementById('reply'),'ajax/message_process.php','replybox',validatetask); return false;" />
			<input type="hidden" value="<?php echo time(); ?>" name="creation_time"/>
		</form>
	
	</div>
	
	<?php
	
		} else {
			echo "You don't have access to this message. #2";
		}
	
	
	
	
	
	
	
	
	} else if(!empty($_GET['sent_id'])) { // check if user is logged in
		$profileid= $_SESSION['user_id'];
		$sent_id = escape_data((int) $_GET['sent_id']);
		$query = "SELECT * FROM agency_messages_out WHERE from_id=$profileid AND sent_id=$sent_id";  // check to see if user exists.
		$result_messages = mysql_query ($query);
		if ($row = mysql_fetch_array ($result_messages, MYSQL_ASSOC)) {
			$subject = stripslashes($row['subject']);
			$to_id = $row['to_id'];
			$lightbox_id = $row['lightbox_id'];
			$to_name = stripslashes($row['to_name']);
			$message = stripslashes(nl2br($row['message']));
			$date_entered = $row['date_entered'];
			$sent_time=date('F d, Y g:i A',strtotime($date_entered));
			
			$lblist = array();
			if(!empty($lightbox_id)) { // add lightbox members the message was sent to to $to_name
				$query2 = "SELECT agency_profiles.firstname, agency_profiles.lastname, agency_messages_out_lb.user_id FROM agency_profiles, agency_messages_out_lb WHERE agency_profiles.user_id=agency_messages_out_lb.user_id And agency_messages_out_lb.sent_id='$sent_id'";
				$result2 = mysql_query ($query2);
				while($row2 = mysql_fetch_array ($result2, MYSQL_ASSOC)) {
					$lblist[] = '<a href="profile.php?u=' . $row2['user_id'] . '" target="_blank">' . $row2['firstname'] . ' ' . $row2['lastname'] . '</a>';
				}
				$to_name = 'LIGHTBOX: ' . $to_name . ' (' . implode(', ', $lblist) . ')<br />';
			}
	
	?>
	<div style="clear:both; border-bottom:3px solid #CCC; padding-left:130px">
	<b><?php echo $subject; ?></b>
	</div>
	<div style="width:120px; float:left; padding-right: 10px;">
	
	<?php
			// get the folder name
			$sql = "SELECT registration_date FROM agency_profiles WHERE user_id='$to_id'";
			$result=mysql_query($sql);
			if($row = sql_fetchrow($result)) {
				$folder = 'talentphotos/' . $to_id. '_' . $row['registration_date'] . '/';
			}
	
			if(!empty($lightbox_id)) {
				echo '<img src="images/group.gif" border="0" width="120" /></a>';
			} else {
				echo avatar_link($to_id) . '<img src="';
				if(file_exists('../' . $folder . 'avatar.jpg')) {
					echo   $folder . 'avatar.jpg';
				} else if(file_exists('../' . $folder . 'avatar.gif')) {
					echo   $folder . 'avatar.gif';
				} else {
					echo 'images/friend.gif';
				}
				echo '" border="0" width="120" /></a>';
			}
	
	?>
	</div>
	
	<div style="padding:10px">
	<?php if(!empty($to_id)) { echo avatar_link($to_id); } echo '<b>' . $to_name . '</b></a> <span style="color:#AAA; font-size:10px">' . $sent_time . '</span><br />'; ?>
	<?php echo $message; ?>
	</div>	
	<?php
	
		} else {
			echo "You don't have access to this message. #3";
		}
	
	} else {
		echo "You don't have access to this message. #1";
	}
}

mysql_close(); // Close the database connection.
?>
