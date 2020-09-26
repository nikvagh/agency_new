<?php
session_start();

include('../includes/mysql_connect.php');
include('../includes/vars.php');
include('../includes/agency_functions.php');

if(is_active()) { // check if user is logged in
	if(!empty($_POST['message_id'])) { // process REPLY TO MESSAGE
		$profileid= $_SESSION['user_id'];
		$message_id = escape_data((int) $_POST['message_id']);
		$query = "SELECT * FROM agency_messages WHERE to_id='$profileid' AND message_id='$message_id'";  // check to see if user can access message_id.
		$result_messages = mysql_query ($query);
		if ($row = @mysql_fetch_array ($result_messages, MYSQL_ASSOC)) {
			$subject = 'Re: ' . escape_data($row['subject']);
			$sender_id = $row['from_id'];
			if(!empty($_POST['messagereply'])) {
				$reply = escape_data($_POST['messagereply']);
				// get From name:
				$sql = "SELECT firstname, client_company FROM agency_profiles WHERE user_id='$profileid'";
				$result=mysql_query($sql);
				if($row = sql_fetchrow($result)) {
					if(agency_account_type($profileid) == 'client') {
						$from_name = escape_data($row['client_company']);
					} else {
						$from_name = escape_data($row['firstname']);
					}
				} else {
					$from_name = 'name not found';
				}

				// get To name:
				$sql = "SELECT firstname, client_company FROM agency_profiles WHERE user_id='$sender_id'";
				$result=mysql_query($sql);
				if($row = sql_fetchrow($result)) {
					if(agency_account_type($sender_id) == 'client') {
						$to_name = escape_data($row['client_company']);
					} else {
						$to_name = escape_data($row['firstname']);
					}					
				} else {
					$to_name = 'name not found';
				}


				$query = "INSERT INTO agency_messages (from_id, to_id, from_name, subject, message, reply_to, date_entered) VALUES ('$profileid', '$sender_id', '$from_name', '$subject', '$reply', '$message_id', NOW() )";
				if(mysql_query($query)) {
					$message_id = mysql_insert_id();
					$query = "INSERT INTO agency_messages_out (message_id, from_id, to_id, from_name, to_name, subject, message, date_entered) VALUES ('$message_id', '$profileid', '$sender_id', '$from_name', '$to_name', '$subject', '$reply', NOW() )";
					mysql_query($query);					
					
					echo '<b>Your reply has been sent</b>';
				} else {
					echo '<b>There was an problem sending your message.  Please contact the administrator if this problem persists.</b>';
				}

			} else {
				echo "<b>Your reply appears to have been empty.  Your message was not sent.</b>";
			}
		} else {
			echo "<b>You don't have access to this message. #2</b>";
		}

	}

	/* =========================   Start: DELETE MESSAGE   ============================== */
	if(!empty($_GET['deletemessage'])) {
		$profileid= $_SESSION['user_id'];
		$message_id = escape_data((int) $_GET['deletemessage']);
		$query = "SELECT * FROM agency_messages WHERE to_id='$profileid' AND message_id='$message_id'";  // check to see if user can access message_id.
		$result_messages = mysql_query ($query);
		if ($row = @mysql_fetch_array ($result_messages, MYSQL_ASSOC)) {
			$query = "UPDATE agency_messages SET deleted='1' WHERE message_id='$message_id'";
			mysql_query($query);
			echo '<td colspan="4">message deleted</td>';
		}
	}
	
	if(!empty($_GET['deletesent'])) {
		$profileid= $_SESSION['user_id'];
		$sent_id = escape_data((int) $_GET['deletesent']);
		$query = "SELECT * FROM agency_messages_out WHERE from_id='$profileid' AND sent_id='$sent_id'";  // check to see if user can access message_id.
		$result_messages = mysql_query ($query);
		if ($row = @mysql_fetch_array ($result_messages, MYSQL_ASSOC)) {
			$query = "UPDATE agency_messages_out SET deleted='1' WHERE sent_id='$sent_id'";
			mysql_query($query);
			echo '<td colspan="4">message removed</td>';
		}
	}	
	

	// if sending to selected from a lightbox, but only one person, change the vars a bit
	if(!empty($_REQUEST['send_lb']) && !empty($_REQUEST['to_list'])) {
		$to_array = array();
		$to_array = explode(',', $_REQUEST['to_list']);
		if(sizeof($to_array) == 1) {
			unset($_REQUEST['send_lb']);
			unset($_REQUEST['to_list']);
			$_POST['to'] = $to_array[0];
		}
	}
	
	
	
	if(isset($_POST['sendit']) && !empty($_POST['to'])) {
		$sender_id= $_SESSION['user_id'];
		$message = escape_data($_POST['message']);
		$subject = escape_data($_POST['subject']);
		$to_id = escape_data((int)$_POST['to']);
		if(!empty($message)) {
			// get From name:
			$sql = "SELECT firstname, client_company FROM agency_profiles WHERE user_id='$sender_id'";
			$result=mysql_query($sql);
			if($row = sql_fetchrow($result)) {
				if(agency_account_type($sender_id) == 'client') {
					$from_name = escape_data($row['client_company']);
				} else {
					$from_name = escape_data($row['firstname']);
				}				
			} else {
				$from_name = 'name not found';
			}
			
			// get To name:
			$sql = "SELECT firstname, client_company FROM agency_profiles WHERE user_id='$to_id'";
			$result=mysql_query($sql);
			if($row = sql_fetchrow($result)) {
				if(agency_account_type($to_id) == 'client') {
					$to_name = escape_data($row['client_company']);
				} else {
					$to_name = escape_data($row['firstname']);
				}					
			} else {
				$to_name = 'name not found';
			}
			
			

			$query = "INSERT INTO agency_messages (from_id, to_id, from_name, subject, message, date_entered) VALUES ('$sender_id', '$to_id', '$from_name', '$subject', '$message', NOW() )";
			if(mysql_query($query)) {
				$message_id = mysql_insert_id();
				$query = "INSERT INTO agency_messages_out (message_id, from_id, to_id, from_name, to_name, subject, message, date_entered) VALUES ('$message_id', '$sender_id', '$to_id', '$from_name', '$to_name', '$subject', '$message', NOW() )";
				mysql_query($query);
				echo '<b>Your message has been sent</b>';
			} else {
				echo '<b>There was an problem sending your message.  Please contact the administrator if this problem persists.</b>';
			}

		} else {
			echo "<b>Your message content appears to have been empty.  Your message was not sent.</b>";
		}
	}

	
	// SEND TO LIGHTBOX
	if(!empty($_REQUEST['send_lb'])) {
		$sender_id= $_SESSION['user_id'];
		$message = escape_data($_POST['message']);
		$subject = escape_data($_POST['subject']);
		$lightbox_id = escape_data((int)$_POST['send_lb']);
		if(!empty($_REQUEST['to_list'])) {
			$to_array = array();
			$to_array = explode(',', $_REQUEST['to_list']);
		}
		if(!empty($message)) {
			// get From name:
			$sql = "SELECT firstname, client_company FROM agency_profiles WHERE user_id='$sender_id'";
			$result=mysql_query($sql);
			if($row = sql_fetchrow($result)) {
				if(agency_account_type($sender_id) == 'client') {
					$from_name = escape_data($row['client_company']);
				} else {
					$from_name = escape_data($row['firstname']);
				}				
			} else {
				$from_name = 'name not found';
			}
			
			
			// Lightbox Name for Outbox:
			$sql = "SELECT lightbox_name FROM agency_lightbox WHERE lightbox_id='$lightbox_id'";
			$result=mysql_query($sql);
			if($row = sql_fetchrow($result)) {
				$outbox_name = escape_data($row['lightbox_name']);					
			}
			
			$query = "INSERT INTO agency_messages_out (from_id, lightbox_id, from_name, to_name, subject, message, date_entered) VALUES ('$sender_id', '$lightbox_id', '$from_name', '$outbox_name', '$subject', '$message', NOW() )";
			mysql_query($query);
			$sent_id = mysql_insert_id();
			
			// go through lightbox
			$query = "SELECT DISTINCT agency_profiles.user_id, agency_profiles.firstname, agency_profiles.lastname FROM agency_lightbox_users, agency_lightbox, agency_profiles WHERE agency_lightbox_users.lightbox_id=agency_lightbox.lightbox_id AND agency_lightbox_users.user_id=agency_profiles.user_id AND agency_lightbox_users.lightbox_id='$lightbox_id' ORDER BY agency_profiles.firstname";
			$result = @mysql_query ($query);
			while ($row = @mysql_fetch_array ($result, MYSQL_ASSOC)) {
				$to_id = (int) $row['user_id'];
				$to_name = $row['firstname'];
				$sendthis = true; // if we're just sending to people checked in lightbox, then not all will get messages
				if(isset($to_array)) {
					if(!in_array($to_id, $to_array)) {
						$sendthis = false;
					}
				}
				if($sendthis) {
					$query = "INSERT INTO agency_messages (from_id, to_id, from_name, subject, message, date_entered) VALUES ('$sender_id', '$to_id', '$from_name', '$subject', '$message', NOW() )";
					if(mysql_query($query)) {
						// $message_id = mysql_insert_id();
						$query2 = "INSERT INTO agency_messages_out_lb (sent_id, user_id) VALUES ('$sent_id', '$to_id')";
						mysql_query($query2);
						echo '<b>Your message has been sent to: ' . $row['firstname'] . ' ' . $row['lastname'] . '</b><br />';
					} else {
						echo '<b>There was an problem sending your message to ' . $row['firstname'] . ' ' . $row['lastname'] . '.  Please contact the administrator if this problem persists.</b>';
					}
				}
			}

		} else {
			echo "<b>Your message content appears to have been empty.  Your message was not sent.</b>";
		}
	}



	// LOAD SEND MESSAGE FORM
	if(!empty($_GET['sendto'])) {
?>
	<div id="processmessage" style="border:1px solid black; width:400px; margin:20px; padding:10px">

	<form method="post" action="javascript:void(0)" id="sendmessage" name="sendmessage">
		<b>Send Message:</b><br /> <br />
		Subject:<br />
		<input type="text" name="subject" style="width:100%; font-size:12px" />
		<br /><br />
		Message:<br />
		<textarea name="message" rows="10" style="width:100%; font-size:12px"></textarea><br />
		<br />
		<input type="hidden" name="to" value="<?php echo escape_data((int) $_GET['sendto']); ?>" />
		<input type="hidden" name="sendit" value="true" />
		<input type="hidden" value="<?php echo time(); ?>" name="creation_time"/>
	<input type="hidden" value="<?php echo agency_add_form_key('sendmessage'); ?>" name="form_token"/>
	<input type="button" value="Send" onclick="submitform (document.getElementById('sendmessage'),'ajax/message_process.php','processmessage',validatetask); return false;" />
	</form>
	</div>

<?php
	}
} else {
	echo "You must be logged in and approved to take this action.";
}

mysql_close(); // Close the database connection.
?>
