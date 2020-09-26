<?php
session_start();

$NUM_TO_DISPLAY = 10; // HOW MANY MESSAGES TO SHOW PER PAGE
	
include('../includes/mysql_connect.php');
include('../includes/vars.php');
include('../includes/agency_functions.php');

if(is_active()) { // check if user is logged in
	$profileid= $_SESSION['user_id'];

	$mod = '';
	if (isset($_GET['page'])) { // for pagination
		$page = $_GET['page'];
		$offset = ($page - 1) * $NUM_TO_DISPLAY;
		$mod = ' OFFSET ' . $offset;
	}
	$limit = $NUM_TO_DISPLAY + 1;
	
	
	
	echo '<table align="center" cellspacing="0" cellpadding="5" width="100%">
		<tr bgcolor="#EAE6DB">';
		
		
	if(isset($_REQUEST['sent'])) {
		$sentlink = '&sent=true';
		echo '<td align="left" width="100"><font color="#444444"><b>Sent To</b></font></td>
			<td align="left"><font color="#444444"><b>Subject</b></font></font></td>
			<td align="left" width="80"><font color="#444444"><b>Date Sent</b></font></td>';

		$query = "SELECT * FROM agency_messages_out WHERE from_id='$profileid' AND deleted='0' ORDER BY date_entered DESC LIMIT " . $limit . $mod;	

	} else {
		echo '<td align="left" width="100"><font color="#444444"><b>Sent By</b></font></td>
			<td align="left"><font color="#444444"><b>Subject</b></font></font></td>
			<td align="left" width="80"><font color="#444444"><b>Date Sent</b></font></td>';
			
			$query = "SELECT * FROM agency_messages WHERE to_id='$profileid' AND deleted='0' ORDER BY date_entered DESC LIMIT " . $limit . $mod;			
	}
	
	
	echo '<td width="20">&nbsp;</td>
		</tr>';
	$bg = '#333333'; // Set the background color.


	$result_messages = mysql_query ($query);
	if (mysql_affected_rows() > 0) { // If it ran OK.
		$box_names = 0;
		$more_flag = FALSE;
		
		// for show message back link
		if (!empty($_GET['page'])) {
			$page = (int) $_GET['page'];
		} else {
			$page = 1;
		}
		
		if(isset($_GET['sent'])) {
			
												// SENT BOX
			
			while (($row = mysql_fetch_array ($result_messages, MYSQL_ASSOC)) && ($more_flag == FALSE)) {
				$box_names++;
				if ($box_names > $NUM_TO_DISPLAY) { // Stop at 15 and set next page button
					$more_flag = TRUE;
				} else {
					$bg = ($bg=='#FFFFFF' ? '#F8F7EC' : '#FFFFFF'); // Switch the background color.
					$sent_id = $row['sent_id'];
					$subject = stripslashes($row['subject']);
					$to_id = $row['to_id'];
					$lightbox_id = $row['lightbox_id'];
					$to_name = stripslashes($row['to_name']);
					$message = stripslashes(nl2br($row['message']));
					$date = date('m/d/Y',strtotime($row['date_entered']));
					echo '<tr bgcolor="' . $bg . '" id="sent' . $sent_id . '">';
					echo '<td align="center">';
					// get the sender's folder name
					$sql = "SELECT registration_date FROM agency_profiles WHERE user_id='$to_id'";
					$result=mysql_query($sql);
					if($userinfo = sql_fetchrow($result)) {  // "$userinfo" array will be available through file, so no need to access database again
						$folder = 'talentphotos/' . $to_id. '_' . $userinfo['registration_date'] . '/';
					}
							
							
					if(!empty($lightbox_id)) {
						echo '<img src="images/group.gif" border="0" width="40" /></a>';
					} else {		
						echo avatar_link($to_id) . '<img src="';
						if(file_exists('../' . $folder . 'avatar.jpg')) {
							echo   $folder . 'avatar.jpg';
						} else if(file_exists('../' . $folder . 'avatar.gif')) {
							echo   $folder . 'avatar.gif';
						} else {
							echo 'images/friend.gif';
						}
						echo '" border="0" width="40" /></a>';
					}
			
			
					echo '<br /><font color="#0000DD" size="1">' . $to_name . '</font></a></td>';
			
					echo '<td align="left"><a href="javascript:loaddiv(\'messagelist\', false, \'ajax/message_view.php?sent_id=' . $sent_id . '&page=' . $page . $sentlink . '&\')" style="text-decoration: none">';
			
					echo $subject;
			
					echo '</a></td><td align="center"><font color="#AAAAAA" size="1">' . $date . '</font></td>';
					echo '<td><a href="javascript:void(0)" onClick="if (confirm(\'Are you sure you wish to remove this message from your Sent folder?\')) loaddiv(\'sent' . $sent_id . '\', false, \'ajax/message_process.php?deletesent=' . $sent_id . '\')" style="font-weight:bold; text-decoration:none">x</a></td>';
					echo '</tr>';
				}
			}
		} else {
			
													// INBOX
			$sentlink = '';
			while (($row = mysql_fetch_array ($result_messages, MYSQL_ASSOC)) && ($more_flag == FALSE)) {
				$box_names++;
				if ($box_names > $NUM_TO_DISPLAY) { // Stop at LIMIT and set next page button
					$more_flag = TRUE;
				} else {
					$bg = ($bg=='#FFFFFF' ? '#F8F7EC' : '#FFFFFF'); // Switch the background color.
					$message_id = $row['message_id'];
					$subject = stripslashes($row['subject']);
					$sender_id = $row['from_id'];
					$sender_name = stripslashes($row['from_name']);
					$message = stripslashes(nl2br($row['message']));
					$date = date('m/d/Y',strtotime($row['date_entered']));
					echo '<tr bgcolor="' . $bg . '" id="message' . $message_id . '">';
					echo '<td align="center">';
					// get the sender's folder name
					$sql = "SELECT registration_date FROM agency_profiles WHERE user_id='$sender_id'";
					$result=mysql_query($sql);
					if($userinfo = sql_fetchrow($result)) {  // "$userinfo" array will be available through file, so no need to access database again
						$folder = 'talentphotos/' . $sender_id. '_' . $userinfo['registration_date'] . '/';
					}
					
					if(agency_account_type($sender_id) == 'client') {
						echo '<a href="javascript:loaddiv(\'messagelist\', false, \'ajax/message_view.php?message_id=' . $message_id . '&page=' . $page . '&\')">';
					} else {
						echo '<a href="profile.php?u=' . $sender_id . '">';
					}
					
					echo '<img src="';
					if(file_exists('../' . $folder . 'avatar.jpg')) {
						echo   $folder . 'avatar.jpg';
					} else if(file_exists('../' . $folder . 'avatar.gif')) {
						echo   $folder . 'avatar.gif';
					} else {
						echo 'images/friend.gif';
					}
					echo '" border="0" width="40" /></a>';
			
			
			
					echo '<br /><font color="#0000DD" size="1">' . $sender_name . '</font></a></td>';
			
					if ($row['viewed'] != 1) {
						$makebold = '; font-weight:bold';
					} else {
						$makebold = '';
					}
					echo '<td align="left"><a href="javascript:loaddiv(\'messagelist\', false, \'ajax/message_view.php?message_id=' . $message_id . '&page=' . $page . '&\')" onClick="this.style.fontWeight=\'normal\'" style="text-decoration: none' . $makebold . '">';
			
					echo $subject;
			
					echo '</a></td><td align="center"><font color="#AAAAAA" size="1">' . $date . '</font></td>';
					echo '<td><a href="javascript:void(0)" onClick="if (confirm(\'Are you sure you wish to delete this message?\')) loaddiv(\'message' . $message_id . '\', false, \'ajax/message_process.php?deletemessage=' . $message_id . '\')" style="font-weight:bold; text-decoration:none">x</a></td>';
					echo '</tr>';
				}
			}

			
			
			
		}
		echo '</table>';
		echo '<table width="100%" cellpadding="5" border="0" cellspacing="0"><tr><td align="left">';
		if (isset($_GET['page'])) {
			if ($_GET['page'] > 1) {
				$page = (int) $_GET['page'] - 1;
				echo '<div style="font-size:xx-small"> <a href="javascript:loaddiv(\'messagelist\', false, \'ajax/message_list.php?page=' . $page . $sentlink . '&\');" style="color:#333333; font-weight:bold; text-decoration:none"><--previous page</a></div>';
			}
		}
		echo '</td><td align="right">';
		if ($more_flag) {
			if (isset($_GET['page'])) {
				$page = (int) $_GET['page'] + 1;
			} else {
				$page = 2;
			}
			echo '<div style="font-size:xx-small"><a href="javascript:loaddiv(\'messagelist\', false, \'ajax/message_list.php?page=' . $page . $sentlink . '&\');" style="color:#333333; font-weight:bold; text-decoration:none">next page--> </a></div> ';
		}
		echo '</td></tr></table>';

		echo '<br />';
	} else {
		echo "</table><br /><div align=\"center\">You have no messages.</div>";
	}

} else {
	echo "You don't have access to this message. #1";
}


mysql_close(); // Close the database connection.
?>
