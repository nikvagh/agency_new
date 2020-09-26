<?php
	function send_mail($to_email,$subject,$msg,$cc=""){
      	  // echo "send_mail";
	    $timecode = strtotime("NOW");
	    $timecode = md5($timecode);

	      // $from = "no-reply@theagencyonline.com";
	      // $headers = "From: ".$from;
	      // $headers .= "Content-type: text/html\r\n";

	    $headers = 'To: '.$to_email. "\r\n";
	    $headers .= 'From: $from' . "\r\n";
	    if($cc != ""){
		    $headers .= 'Cc: '.$cc. "\r\n";
		}
	    $headers .= "Content-type: text/html;\r\n";
	    
	    $message = '<html><body>'.$msg.'</body></html>';

	      // echo $to_email;
	      // echo "<br/>";
	      // echo $subject;
	      // echo "<br/>";
	      // echo $message;
	      // echo "<br/>";
	      // exit;
      
      	if(mail($to_email, $subject, $message, $headers)){
      		// echo "send";
        	return true;
      	}else{
			// echo "err";
			return true;
        	return false;
      	}
	}

	function notification_add($not = array()){
		$res = "N";
		if(!empty($not)){
				$query = "INSERT INTO agency_notification 
	                            SET
	                            from_id = '".$not['from_id']."',
	                            to_id = '".$not['to_id']."',
	                            title = '".$not['title']."',
	                            link = '".$not['link']."',
	                            message = '".$not['message']."',
	                            status = 'active'
	                        ";
	        if(mysql_query($query)) {
	        	$res = "Y";
			}else{
				echo("Error description: " . mysql_error());
			}
		}

		if($res == "Y"){
			return true;
		}else{
			return false;
		}
	}

	function in_arrayi($needle, $haystack)
	{
		return in_array(strtolower($needle), array_map('strtolower', $haystack));
	}

	function get_talent(){
		$sql = "select * from forum_users u
				LEFT JOIN agency_profiles ap ON ap.user_id = u.user_id 
				WHERE account_type = 'talent'";
		$query = mysql_query($sql);
		$result = array();
		if (mysql_num_rows($query) > 0) {
			while ($row = mysql_fetch_assoc($query)) {
				$result[] = $row;
			}
		}
		return $result;
	}

	function get_talent_request($id){
		$sql = "select atr.*,ap.firstname,ap.lastname,ap1.firstname as sender_fname,ap1.lastname sender_lname from agency_talent_request atr 
				LEFT JOIN agency_profiles ap ON atr.user_id=ap.user_id
				LEFT JOIN agency_profiles ap1 ON atr.request_by=ap1.user_id
				WHERE talent_request_id = ".$id."";
		$query = mysql_query($sql);
		$result = array();
		if (mysql_num_rows($query) > 0) {
			while ($row = mysql_fetch_assoc($query)) {
				$result = $row;
			}
		}
		return $result;
	}

	function get_all_casting_directors(){
		$sql = "select * from forum_users u
				LEFT JOIN agency_profiles ap ON ap.user_id = u.user_id 
				WHERE account_type = 'client'";
		$query = mysql_query($sql);
		$result = array();
		if (mysql_num_rows($query) > 0) {
			while ($row = mysql_fetch_assoc($query)) {
				$result[] = $row;
			}
		}
		return $result;
	}

	function get_talent_byId($user_id){
		$sql = "select ap.*,fu.*,apt.* from forum_users fu
				LEFT JOIN agency_profiles ap ON ap.user_id = fu.user_id 
				LEFT JOIN agency_payment_term apt ON ap.pay_term = apt.payment_term_id
				WHERE fu.user_id = ".$user_id."";

		$query = mysql_query($sql);
		$result = array();
		if (mysql_num_rows($query) > 0) {
			while ($row = mysql_fetch_assoc($query)) {
				$result = $row;
			}
		}
		return $result;
	}

	function get_talent_byTmId_serach($user_id,$search = array()){
		// echo "<pre>";
		// print_r($search);
		// echo "</pre>";

		$cond="";
		if($search['firstname'] != ""){
			$cond .= ' AND ap.firstname LIKE "%'.$search['firstname'].'%"';
		}
		if($search['lastname'] != ""){
			$cond .= ' AND ap.lastname LIKE "%'.$search['lastname'].'%"';
		}
		if($search['age_start'] != ""){
			$cond .= " AND (YEAR(CURDATE()) - YEAR(ap.birthdate)) >= '".$search['age_start']."'";
		}
		if($search['age_end'] != ""){
			$cond .= " AND (YEAR(CURDATE()) - YEAR(ap.birthdate)) <= '".$search['age_end']."'";
		}
		if($search['gender'] != ""){
			$cond .= ' AND ap.gender = "'.$search['gender'].'"';
		}

		$cond_eth = array();
		if(isset($_POST['ethnicity'])){
			foreach($_POST['ethnicity'] as $val){
				$cond_eth[] = "ape.ethnicity = '".$val."'";
			}
		}
		if(!empty($cond_eth)){
          $eth_str = implode(' OR ',$cond_eth);
          $cond .= ' AND ('.$eth_str.')';
        }

        $cond_union = array();
		if(isset($_POST['union_name'])){
			foreach($_POST['union_name'] as $val){
				$cond_union[] = "apu.union_name = '".$val."'";
			}
		}
		if(!empty($cond_union)){
          $union_str = implode(' OR ',$cond_union);
          $cond .= ' AND ('.$union_str.')';
        }

		$sql = "select ap.*,fu.*,apt.* from forum_users fu
				LEFT JOIN agency_profiles ap ON ap.user_id = fu.user_id 
				LEFT JOIN agency_payment_term apt ON ap.pay_term = apt.payment_term_id
				LEFT JOIN agency_profile_ethnicities ape ON ape.user_id = ap.user_id
				LEFT JOIN agency_profile_unions apu ON apu.user_id = ap.user_id
				WHERE ap.roster_id = ".$user_id.$cond."
				GROUP BY fu.user_id";

		$query = mysql_query($sql);
		$result = array();
		if (mysql_num_rows($query) > 0) {
			while ($row = mysql_fetch_assoc($query)) {
				$result[] = $row;
			}
		}
		return $result;
	}

	function autofind_by_role($role_id,$tm_id=""){

		$age_lower_matched = array();
		$age_upper_matched = array();
		$height_lower_matched = array();
		$height_upper_matched = array();
		$gender_matched = array();

		$casting_role_q = mysql_query("select * from agency_castings_roles
										WHERE role_id =".$role_id."
									");
		if (mysql_num_rows($casting_role_q) > 0) {
			while ($role_row = mysql_fetch_assoc($casting_role_q)) {

				$age_lower_matched[] = $role_row['age_lower'];
				$age_upper_matched[] = $role_row['age_upper'];
				$height_lower_matched[] = $role_row['height_lower'];
				$height_upper_matched[] = $role_row['height_upper'];


				$gender_q = mysql_query("select * from agency_castings_roles_vars
											WHERE role_id = ".$role_row['role_id']." AND var_type = 'gender'
										");
				if (mysql_num_rows($gender_q) > 0) {
					while ($gender_row = mysql_fetch_assoc($gender_q)) {
						$gender_matched[] = $gender_row['var_value'];
					}
				}

			}
		}

		$match['min_age'] = min($age_lower_matched);
		$match['max_age'] = max($age_upper_matched);
		$match['min_height'] = min($height_lower_matched);
		$match['max_height'] = max($height_upper_matched);
		$match['gender'] = array_unique($gender_matched);

		$cond = "";

		$gender_cond = array();
		if(in_array("M",$match['gender'])){
			$gender_cond[] = "gender = 'M'";
		}
		if(in_array("F",$match['gender'])){
			$gender_cond[] = "gender = 'F'";
		}
		if(in_array("Transgender",$match['gender'])){
			$gender_cond[] = "gender = 'Transgender'";
		}
		if(in_array("Other",$match['gender'])){
			$gender_cond[] = "gender = 'Other'";
		}

		if(!empty($gender_cond)){
			$gender_str = implode(' OR ',$gender_cond);
			$cond .= ' AND ('.$gender_str.')';
		}

		if($tm_id != ""){
			$cond .= ' AND ap.roster_id = "'.$tm_id.'"'; 
		}

		// $matched_q = mysql_query("select *,YEAR(CURDATE()) - YEAR(birthdate) as age from agency_profiles  AS age
		// 							WHERE 1 AND height >= '".$match['min_height']."' AND height <= '".$match['max_height']."' ". 
		// 									$cond. " 
		// 									AND (YEAR(CURDATE()) - YEAR(birthdate)) >= '".$match['min_age']."'
		// 									AND (YEAR(CURDATE()) - YEAR(birthdate)) <= '".$match['max_age']."'
		// 						");

		$matched_sql = "select ap.*,fu.*,apt.* from forum_users fu
								LEFT JOIN agency_profiles ap ON ap.user_id = fu.user_id 
								LEFT JOIN agency_payment_term apt ON ap.pay_term = apt.payment_term_id
								LEFT JOIN agency_profile_ethnicities ape ON ape.user_id = ap.user_id
								LEFT JOIN agency_profile_unions apu ON apu.user_id = ap.user_id
								WHERE 
								height >= '".$match['min_height']."' AND height <= '".$match['max_height']."' 
								AND (YEAR(CURDATE()) - YEAR(birthdate)) >= '".$match['min_age']."'
								AND (YEAR(CURDATE()) - YEAR(birthdate)) <= '".$match['max_age']."'
								".$cond."
								GROUP BY fu.user_id";

		$matched_q = mysql_query($matched_sql);
		$result = array();
		if (mysql_num_rows($matched_q) > 0) {
			while ($matched_row = mysql_fetch_assoc($matched_q)) {
				$result[] = $matched_row;
			}
		}

		// echo "<pre>";
		// print_r($result);
		// echo "</pre>";
		return $result;
	}

	function autofind_by_casting($casting_id){

	}

	function get_talent_submission_tm_byRoleId($user_id,$role_id){
		$sql = "select ap.*,fu.*,amc.* from forum_users fu
				LEFT JOIN agency_profiles ap ON ap.user_id = fu.user_id 
				LEFT JOIN agency_mycastings amc ON amc.user_id = fu.user_id
				WHERE 
				ap.roster_id = ".$user_id."
				AND amc.removed = 0
				AND amc.role_id = ".$role_id."";

		// $sql = "select ap.*,fu.*,amc.* from forum_users fu
		// 		LEFT JOIN agency_profiles ap ON ap.user_id = fu.user_id 
		// 		LEFT JOIN agency_mycastings amc ON amc.user_id = fu.user_id
		// 		WHERE 
		// 		ap.roster_id = ".$user_id."";

		$query = mysql_query($sql);
		$result = array();
		if (mysql_num_rows($query) > 0) {
			while ($row = mysql_fetch_assoc($query)) {
				$result[] = $row;
			}
		}
		return $result;
	}

	function get_tm_byId($user_id){
		$sql = "select ap.*,fu.*,a_tm.* from forum_users fu
				LEFT JOIN agency_profiles ap ON ap.user_id = fu.user_id 
				LEFT JOIN agency_tm a_tm ON ap.user_id = a_tm.user_id 
				WHERE fu.user_id = ".$user_id."";

		$query = mysql_query($sql);
		$result = array();
		if (mysql_num_rows($query) > 0) {
			while ($row = mysql_fetch_assoc($query)) {
				$result = $row;
			}
		}
		return $result;
	}

	function agency_payment_term_byId($id){
		$sql = "select apt.* from agency_payment_term apt
				WHERE apt.payment_term_id = ".$id."";
		$query = mysql_query($sql);

		$row = array();
		$row = sql_fetchrow($query);
		return $row;
	}

	function get_user_byId($user_id){
		$sql = "select * from forum_users u
				LEFT JOIN agency_profiles ap ON ap.user_id = u.user_id 
				WHERE u.user_id = ".$user_id."";

		$query = mysql_query($sql);
		$result = array();
		if (mysql_num_rows($query) > 0) {
			while ($row = mysql_fetch_assoc($query)) {
				$result = $row;
			}
		}
		return $result;
	}

	function get_message_inbox_list($req){
		$profileid= $_SESSION['user_id'];

		$cond = "";
		if($req['msg_type'] == "inbox"){
			$cond .= "to_id='".$profileid."'";
		}

		$sql = "SELECT * FROM agency_messages WHERE ".$cond." AND deleted='0' ORDER BY date_entered DESC";

		$query = mysql_query($sql);
		$result = array();
		if (mysql_num_rows($query) > 0) {
			while ($row = mysql_fetch_assoc($query)) {
				$result[] = $row;
			}
		}
		return $result;
	}

	function get_message_sent_list($req){
		$profileid= $_SESSION['user_id'];

		$cond = "";
		if($req['msg_type'] == "sent"){
			$cond .= "from_id='".$profileid."'";
		}

		$sql = "SELECT * FROM agency_messages_out WHERE ".$cond." AND deleted='0' ORDER BY date_entered DESC ";	

		$query = mysql_query($sql);
		$result = array();
		if (mysql_num_rows($query) > 0) {
			while ($row = mysql_fetch_assoc($query)) {
				$result[] = $row;
			}
		}
		return $result;
	}

	function get_users_by_role($role){
		$sql = "select * from forum_users u
				LEFT JOIN agency_profiles ap ON ap.user_id = u.user_id 
				WHERE account_type = '".$role."'";
		$query = mysql_query($sql);
		$result = array();
		if (mysql_num_rows($query) > 0) {
			while ($row = mysql_fetch_assoc($query)) {
				$result[] = $row;
			}
		}
		return $result;
	}

	function send_message_admin($post){
		$sender_id= $_SESSION['user_id'];
		$message = escape_data($_POST['message']);
		$subject = escape_data($_POST['subject']);
		$to_id = escape_data((int)$_POST['user']);

		// return false;
		// exit;
		$res = false;
		if(!empty($message)) {
			// get From name:
			$sql = "SELECT firstname, company FROM agency_profiles WHERE user_id='$sender_id'";
			$result=mysql_query($sql);
			if($row = sql_fetchrow($result)) {
				// if(agency_account_type($sender_id) == 'client') {
				// 	$from_name = escape_data($row['client_company']);
				// } else {
					$from_name = escape_data($row['firstname']);
				// }				
			} else {
				$from_name = 'name not found';
			}
			
			// get To name:
			$sql = "SELECT firstname, company FROM agency_profiles WHERE user_id='$to_id'";
			$result=mysql_query($sql);
			if($row = sql_fetchrow($result)) {
				// if(agency_account_type($to_id) == 'client') {
				// 	$to_name = escape_data($row['client_company']);
				// } else {
					$to_name = escape_data($row['firstname']);
				// }					
			} else {
				$to_name = 'name not found';
			}
			
			

			$query = "INSERT INTO agency_messages (from_id, to_id, from_name, subject, message, date_entered) VALUES ('$sender_id', '$to_id', '$from_name', '$subject', '$message', NOW() )";
			if(mysql_query($query)) {
				$message_id = mysql_insert_id();
				$query = "INSERT INTO agency_messages_out (message_id, from_id, to_id, from_name, to_name, subject, message, date_entered) VALUES ('$message_id', '$sender_id', '$to_id', '$from_name', '$to_name', '$subject', '$message', NOW() )";
				if(mysql_query($query)){
					$res = true;
				}
				// echo '<b>Your message has been sent</b>';
			} 
			// else {
			// 	echo '<b>There was an problem sending your message.  Please contact the administrator if this problem persists.</b>';
			// }
		}
		return $res;
	}

	function send_message_dash($post){
		$sender_id= $_SESSION['user_id'];
		$message = escape_data($post['message']);
		$subject = escape_data($post['subject']);
		$to_id = escape_data((int)$post['user']);

		$lightbox_id = "";
		if(isset($post['lightbox_id'])){
			$lightbox_id = escape_data((int)$post['lightbox_id']);
		}
		
		// return false;
		// exit;
		$res = false;
		if(!empty($message)) {
			// get From name:
			$sql = "SELECT firstname, company FROM agency_profiles WHERE user_id='$sender_id'";
			$result=mysql_query($sql);
			if($row = sql_fetchrow($result)) {
				// if(agency_account_type($sender_id) == 'client') {
				// 	$from_name = escape_data($row['client_company']);
				// } else {
					$from_name = escape_data($row['firstname']);
				// }				
			} else {
				$from_name = 'name not found';
			}
			
			// get To name:
			$sql = "SELECT firstname, company FROM agency_profiles WHERE user_id='$to_id'";
			$result=mysql_query($sql);
			if($row = sql_fetchrow($result)) {
				// if(agency_account_type($to_id) == 'client') {
				// 	$to_name = escape_data($row['client_company']);
				// } else {
					$to_name = escape_data($row['firstname']);
				// }					
			} else {
				$to_name = 'name not found';
			}

			// $query = "INSERT INTO agency_messages (from_id, to_id, from_name, subject, message, date_entered) VALUES ('$sender_id', '$to_id', '$from_name', '$subject', '$message', NOW() )";

			$query = "INSERT INTO agency_messages 
						SET 
						from_id = '$sender_id',
						to_id = '$to_id',
						from_name = '$from_name',
						subject = '$subject',
						message = '$message',
						date_entered = NOW()
					";
			if(mysql_query($query)) {
				$message_id = mysql_insert_id();
				// $query = "INSERT INTO agency_messages_out (message_id, from_id, to_id, from_name, to_name, subject, message, date_entered) VALUES ('$message_id', '$sender_id', '$to_id', '$from_name', '$to_name', '$subject', '$message', NOW() )";

				$cond_q = "";
				if($lightbox_id != ""){
					$cond_q = "lightbox_id = ".$lightbox_id.",";
				}

				$query = "INSERT INTO agency_messages_out 
						SET 
						message_id = '$message_id',
						from_id = '$sender_id',
						to_id = '$to_id',
						".$cond_q."
						from_name = '$from_name',
						to_name = '$to_name',
						subject = '$subject',
						message = '$message',
						date_entered = NOW()
					";

				if(mysql_query($query)){
					$res = true;
				}
				// echo '<b>Your message has been sent</b>';
			} 
			// else {
			// 	echo '<b>There was an problem sending your message.  Please contact the administrator if this problem persists.</b>';
			// }
		}
		return $res;
	}

	function send_message_reply_admin($post){
		if(!empty($post['message_id'])) { // process REPLY TO MESSAGE
			$profileid= $_SESSION['user_id'];
			$message_id = escape_data((int) $post['message_id']);
			$query = "SELECT * FROM agency_messages WHERE to_id='$profileid' AND message_id='$message_id'";  // check to see if user can access message_id.
			$result_messages = mysql_query ($query);
			if ($row = @mysql_fetch_assoc ($result_messages)) {
				$subject = 'Re: ' . escape_data($row['subject']);
				$sender_id = $row['from_id'];
				if(!empty($post['messagereply'])) {
					$reply = escape_data($post['messagereply']);
					// get From name:
					$sql = "SELECT firstname, company FROM agency_profiles WHERE user_id='$profileid'";
					$result=mysql_query($sql);
					if($row = sql_fetchrow($result)) {
						if(agency_account_type($profileid) == 'client') {
							$from_name = escape_data($row['company']);
						} else {
							$from_name = escape_data($row['firstname']);
						}
					} else {
						$from_name = 'name not found';
					}

					// get To name:
					$sql = "SELECT firstname, company FROM agency_profiles WHERE user_id='$sender_id'";
					$result=mysql_query($sql);
					if($row = sql_fetchrow($result)) {
						if(agency_account_type($sender_id) == 'client') {
							$to_name = escape_data($row['company']);
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
						return array('success'=>'Your reply has been sent');
					} else {
						return array('error'=>'There was an problem sending your message.  Please contact the administrator if this problem persists.');
					}

				} else {
					return array('error'=>'Your reply appears to have been empty.  Your message was not sent.');
				}
			} else {
				return array('error'=>'You do not have access to this message. #2');
			}

		}
	}

	function view_message($get){
		// echo "<pre>";
		// print_r($get);
		// exit;
		?>
		<div class="box box-theme">

			<div class="box-header with-border">
				<a href="javascript:void(0)" class="close_view_box"><b>CLOSE</b></a>
			</div>
		
			<?php 
			if(is_active()) {
				if(!empty($get['message_id'])) { // check if user is logged in
					$profileid= $_SESSION['user_id'];
					$message_id = escape_data((int) $get['message_id']);
					$query = "SELECT * FROM agency_messages WHERE to_id=$profileid AND message_id=$message_id";  // check to see if user exists.
					$result_messages = mysql_query ($query);
					if ($row = mysql_fetch_assoc ($result_messages)) {
				
						// see if this is a response:
						if(!empty($row['reply_to'])) {
							$reply_to = $row['reply_to'];
							$query2 = "SELECT * FROM agency_messages WHERE message_id=$reply_to";  // check to see if user exists.
							$result_messages2 = mysql_query ($query2);
							if ($row2 = mysql_fetch_assoc ($result_messages2)) {
								$subject = stripslashes($row2['subject']);
								$sender_id = $row2['from_id'];
								$sender_name = stripslashes($row2['from_name']);
								$message = stripslashes(nl2br($row2['message']));
								$date_entered = $row2['date_entered'];
								$sent_time=date('F d, Y g:i A',strtotime($date_entered));
							?>

							<div class="box-body no-padding">
								<div class="mailbox-read-info">
									<div class="row">
										<div class="col-md-2">
											<?php
												// get the folder name
												$sql3 = "SELECT registration_date FROM agency_profiles WHERE user_id='$sender_id'";
												$result3=mysql_query($sql3);
												if($row3 = sql_fetchrow($result3)) {
													$folder = '../talentphotos/' . $sender_id. '_' . $row3['registration_date'] . '/';
												}

												echo avatar_link($sender_id) . '<img src="';
												if(file_exists('../' . $folder . 'avatar.jpg')) {
													echo   $folder . 'avatar.jpg';
												} else if(file_exists('../' . $folder . 'avatar.gif')) {
													echo   $folder . 'avatar.gif';
												} else {
													echo '../images/friend.gif';
												}
												echo '" border="0" width="30" /></a>';
											?>
										</div>
										<div class="col-md-10">
						                	<h3><?php echo $subject; ?></h3>
						                	<?php //echo avatar_link($sender_id) . '<b>' . $sender_name . '</b></a> <span style="color:#AAA; font-size:10px">' . $sent_time . '</span><br />'; ?>
						                	<h5><?php echo $sender_name; ?><span class="mailbox-read-time pull-right"><?php echo $sent_time; ?></span></h5>
				              			</div>
			              			</div>
				              	</div>

				              	<div class="mailbox-read-message">
					                <?php echo $message; ?>
				            	</div>
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

					<div class="box-body no-padding">
						<div class="mailbox-read-info">
							<div class="row">
								<div class="col-md-2">
									<?php
										// get the folder name
										$sql = "SELECT registration_date FROM agency_profiles WHERE user_id='$sender_id'";
										$result=mysql_query($sql);
										if($row = sql_fetchrow($result)) {
											$folder = '../talentphotos/' . $sender_id. '_' . $row['registration_date'] . '/';
										}

										echo avatar_link($sender_id) . '<img src="';
										if(file_exists('../' . $folder . 'avatar.jpg')) {
											echo   $folder . 'avatar.jpg';
										} else if(file_exists('../' . $folder . 'avatar.gif')) {
											echo   $folder . 'avatar.gif';
										} else {
											echo '../images/friend.gif';
										}
										echo '" border="0" width="30" /></a>';
									?>
								</div>
								<div class="col-md-10">
						        	<h3><?php echo $subject; ?></h3>
						        	<h5>From: <?php echo $sender_name; ?><span class="mailbox-read-time pull-right"><?php echo $sent_time; ?></span></h5>
						        	<?php //echo avatar_link($sender_id) . '<b>' . $sender_name . '</b></a> <span style="color:#AAA; font-size:10px">' . $sent_time . '</span><br />'; ?>
						        </div>
				      		</div>
				      	</div>

				      	<div class="mailbox-read-message">
				            <?php echo $message; ?>
				        </div>
				    </div>
				
					<div class="box-body">
						<div id="replybox">
							<form action="" name="reply" id="reply" method="post">
								<textarea name="messagereply" rows="4" class="form-control"></textarea>
								<input type="hidden" name="message_id" value="<?php echo $message_id; ?>" />
								<br />
								<input type="hidden" value="<?php echo time(); ?>" name="creation_time"/>
								<!-- <input type="button" value="Reply" onclick="submitform (document.getElementById('reply'),'ajax/message_process.php','replybox',validatetask); return false;" class="btn btn-theme pull-right"/> -->

								<button type="submit" id="send_message_reply_btn" class="btn btn-theme pull-right"><i class="fa fa-paper-plane"></i> Send</button>
							</form>
						</div>
					</div>
				
				<?php } else { ?>
					<div class="box-body no-padding">
						<?php echo "You don't have access to this message. #2"; ?>
					</div>
				<?php } ?>
				
				<?php } else if(!empty($get['sent_id'])) { ?>

						<div class="box-body no-padding">
							<?php 
								$profileid= $_SESSION['user_id'];
								$sent_id = escape_data((int) $get['sent_id']);
								$query = "SELECT * FROM agency_messages_out WHERE from_id=$profileid AND sent_id=$sent_id";  // check to see if user exists.
								$result_messages = mysql_query ($query);

								if ($row = mysql_fetch_assoc ($result_messages)) {
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
										while($row2 = mysql_fetch_assoc ($result2)) {
											$lblist[] = '<a href="profile-view.php?user_id=' . $row2['user_id'] . '" target="_blank">' . $row2['firstname'] . ' ' . $row2['lastname'] . '</a>';
										}
										$to_name = 'LIGHTBOX: ' . $to_name . ' (' . implode(', ', $lblist) . ')<br />';
									}
							?>

								<div class="mailbox-read-info">
									<div class="row">
										<div class="col-md-2">
											<?php
												// get the folder name
												$sql = "SELECT registration_date FROM agency_profiles WHERE user_id='$to_id'";
												$result=mysql_query($sql);
												if($row = sql_fetchrow($result)) {
													$folder = '../talentphotos/' . $to_id. '_' . $row['registration_date'] . '/';
												}
										
												if(!empty($lightbox_id)) {
													echo '<img src="../images/group.gif" border="0" width="30" /></a>';
												} else {
													echo avatar_link($to_id) . '<img src="';
													if(file_exists('../' . $folder . 'avatar.jpg')) {
														echo   $folder . 'avatar.jpg';
													} else if(file_exists('../' . $folder . 'avatar.gif')) {
														echo   $folder . 'avatar.gif';
													} else {
														echo '../images/friend.gif';
													}
													echo '" border="0" width="30" /></a>';
												}
											?>
										</div>
										<div class="col-md-10">
								        	<h3><?php echo $subject; ?></h3>
								        	<?php //if(!empty($to_id)) { echo avatar_link($to_id); } echo '<b>' . $to_name . '</b></a> <span style="color:#AAA; font-size:10px">' . $sent_time . '</span><br />'; ?>
								        	<h5>To: <?php echo $to_name; ?><span class="mailbox-read-time pull-right"><?php echo $sent_time; ?></span></h5>
								        </div>
								    </div>
						      	</div>

						      	<div class="mailbox-read-message">
						            <?php echo $message; ?>
						        </div>

							<?php } else { ?>
								<?php echo "You don't have access to this message. #3"; ?>
							<?php } ?>
						</div>
					
				<?php } else { ?>
					<div class="box-body">
						<?php echo "You don't have access to this message. #1"; ?>
					</div>
				<?php } ?>
			<?php } ?>

		</div>
	<?php	
	}


	function delete_message($get){
		$res = false;
		if(!empty($_GET['deletemessage'])) {
			$profileid= $_SESSION['user_id'];
			$message_id = escape_data((int) $_GET['deletemessage']);
			$query = "SELECT * FROM agency_messages WHERE to_id='$profileid' AND message_id='$message_id'";  // check to see if user can access message_id.
			$result_messages = mysql_query ($query);
			if ($row = @mysql_fetch_assoc ($result_messages)) {
				$query = "UPDATE agency_messages SET deleted='1' WHERE message_id='$message_id'";
				if(mysql_query($query)){
					$res = true;
				}
				// echo '<td colspan="4">message deleted</td>';
			}
		}
		
		if(!empty($_GET['deletesent'])) {
			$profileid= $_SESSION['user_id'];
			$sent_id = escape_data((int) $_GET['deletesent']);
			$query = "SELECT * FROM agency_messages_out WHERE from_id='$profileid' AND sent_id='$sent_id'";  // check to see if user can access message_id.
			$result_messages = mysql_query ($query);
			if ($row = @mysql_fetch_assoc ($result_messages)) {
				$query = "UPDATE agency_messages_out SET deleted='1' WHERE sent_id='$sent_id'";
				if(mysql_query($query)){
					$res = true;
				}
				// echo '<td colspan="4">message removed</td>';
			}
		}

		return $res;
	}

	function get_sales_year_analytics(){
		// SELECT YEAR(payment_dt),COUNT(*) FROM `plus2_bills` GROUP BY YEAR(payment_dt)
		$sql = "SELECT YEAR(date_of_payment) as year,SUM(txn_amt) as total FROM agency_mentor_sales GROUP BY YEAR(date_of_payment)";
		$query = mysql_query ($sql);
		$result = array();
		while ($row = @mysql_fetch_assoc ($query)) {
			$result[] = $row;
			// echo '<td colspan="4">message removed</td>';
		}
		return $result;
	}

	function get_sales_month_analytics(){
		// $sql = "SELECT MONTH(date_of_payment) as month,SUM(txn_amt) as total FROM agency_mentor_sales WHERE YEAR(date_of_payment) = YEAR(CURDATE()) GROUP BY MONTH(date_of_payment)";
		$sql = "SELECT MONTH(date_of_payment) as month,SUM(txn_amt) as total FROM agency_mentor_sales WHERE YEAR(date_of_payment) = YEAR(CURDATE() GROUP BY MONTH(date_of_payment)";
		$query = mysql_query ($sql);
		$result = array();
		while ($row = @mysql_fetch_assoc ($query)) {
			$result[] = $row;
			// echo '<td colspan="4">message removed</td>';
		}
		return $result;
	}

	function get_sales_week_analytics(){
		$sql = "SELECT MONTH(date_of_payment) as month,SUM(txn_amt) as total FROM agency_mentor_sales WHERE yearweek(DATE(date_of_payment), 1) = yearweek(curdate(), 1) GROUP BY MONTH(date_of_payment)";
		$query = mysql_query ($sql);
		$result = array();
		while ($row = @mysql_fetch_assoc ($query)) {
			$result[] = $row;
			// echo '<td colspan="4">message removed</td>';
		}
		return $result;
	}

	function get_sales_day_analytics(){
		$sql = "SELECT MONTH(date_of_payment) as month,SUM(txn_amt) as total FROM agency_mentor_sales WHERE yearweek(DATE(date_of_payment), 1) = yearweek(curdate(), 1) GROUP BY MONTH(date_of_payment)";
		$query = mysql_query ($sql);
		$result = array();
		while ($row = @mysql_fetch_assoc ($query)) {
			$result[] = $row;
			// echo '<td colspan="4">message removed</td>';
		}
		return $result;
	}

	function filename_new($filename){
		$filename_ary = explode('.', $filename);
		// get the first item in the array (definitely)
		$first = reset($filename_ary); // prints 'one'
		 
		// get the last item in the array
		$last = end($filename_ary); // prints 'three'
		$filename_new = $first.'.'.$last;
		return time().'_'.preg_replace("/[^a-zA-Z0-9.]/", "", $filename_new);
	}

	function get_role_byId($role_id){
		$sql = "SELECT * FROM agency_castings_roles acr 
				WHERE role_id = ".$role_id."";
		$query = mysql_query ($sql);
		$result = array();
		while ($row = @mysql_fetch_assoc ($query)) {
			$result['role'] = $row;
		}

		$sql_var = "SELECT * FROM agency_castings_roles_vars acrv 
				WHERE role_id = ".$role_id."";
		$query_var = mysql_query ($sql_var);
		while ($row = @mysql_fetch_assoc ($query_var)) {
			$result['vars'][] = $row;
		}

		// echo "<pre>";
		// print_r($result);
		return $result;
	}

	function get_submission_byId($submission_id){
		$sql = "SELECT * FROM agency_mycastings am 
				WHERE submission_id = ".$submission_id."";
		$query = mysql_query($sql);
		$result = array();
		while ($row = mysql_fetch_assoc($query)) {
			$result = $row;
		}
		return $result;
	}

	function get_submission_role_user($role_id,$user_id){
		$sql = "SELECT * FROM agency_mycastings am 
				WHERE role_id = ".$role_id." AND user_id = ".$user_id."";
		$query = mysql_query ($sql);
		$result = array();
		if (mysql_num_rows($query) > 0) {
			while ($row = @mysql_fetch_assoc ($query)) {
				$result = $row;
			}
		}
		return $result;
	}

	// function to check if two people are friends
	function get_agency_friends($user_id) {
		$sql = "SELECT user_id,friend_id FROM agency_friends where (user_id = ".$user_id." OR friend_id = ".$user_id.") AND confirmed = 1";
		$query = mysql_query ($sql);
		$ids = array();
		if (mysql_num_rows($query) > 0) {
			while ($row = @mysql_fetch_assoc ($query)) {

				if($row['user_id'] != $user_id){
					$ids[] = $row['user_id'];
				}
				if($row['friend_id'] != $user_id){
					$ids[] = $row['friend_id'];
				}

			}
		}

		$final_res = array();

		if(!empty($ids)){
			$ids_str = implode(",",$ids);

			$sql_profile = "select ap.*,fu.* from agency_profiles ap
					LEFT JOIN forum_users fu ON fu.user_id = ap.user_id
					WHERE ap.user_id IN(".$ids_str.")";
					// exit;
			$query_profile = mysql_query ($sql_profile);
			if (mysql_num_rows($query_profile) > 0) {
				while ($res = @mysql_fetch_assoc ($query_profile)) {
					$final_res[] = $res;
				}
			}

			// echo "<pre>";
			// print_r($final_res);
			// echo "</pre>";
		}

		return $final_res;
	}

	function get_agency_friends_normal($user_id) {
		$sql = "SELECT user_id,friend_id FROM agency_friends where (user_id = ".$user_id." OR friend_id = ".$user_id.") AND confirmed = 1";
		$query = mysql_query ($sql);
		$ids = array();
		if (mysql_num_rows($query) > 0) {
			while ($row = @mysql_fetch_assoc ($query)) {

				if($row['user_id'] != $user_id){
					$ids[] = $row['user_id'];
				}
				if($row['friend_id'] != $user_id){
					$ids[] = $row['friend_id'];
				}

			}
		}

		$final_res = array();

		if(!empty($ids)){
			$ids_str = implode(",",$ids);

			$sql_profile = "select ap.*,fu.* from agency_profiles ap
					LEFT JOIN forum_users fu ON fu.user_id = ap.user_id
					WHERE ap.account_type != 'super_admin' AND ap.user_id IN(".$ids_str.")";
					// exit;
			$query_profile = mysql_query ($sql_profile);
			if (mysql_num_rows($query_profile) > 0) {
				while ($res = @mysql_fetch_assoc ($query_profile)) {
					$final_res[] = $res;
				}
			}

			// echo "<pre>";
			// print_r($final_res);
			// echo "</pre>";
		}

		return $final_res;
	}

	function check_user_role_submit($user_id,$role_id){
		$check_sub_sql = "SELECT * FROM agency_mycastings
                          WHERE user_id = ".$user_id." AND role_id = ".$role_id."";

		$check_sub_res = mysql_query($check_sub_sql);
		if (mysql_num_rows($check_sub_res) > 0) {
			return true;
		}else{
			return false;
		}
	}

?>