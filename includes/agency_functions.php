<?php
function sql_fetchrow($result) {		
	// return mysql_fetch_array($result, MYSQL_ASSOC);
	return mysql_fetch_assoc($result);
}

function time_elapsed_string($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}

function time_remain_string($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' left' : 'few seconds left !';
}

function days_remain_string($datetime, $full = false) {
	$date1 = new DateTime();  //current date or any date
	$date2 = new DateTime($datetime);   //Future date
	$diff = $date2->diff($date1)->format("%a");  //find difference
	$days = intval($diff);   //rounding days
	return $days;
}

function is_super_admin() {
	if(!empty($_SESSION['superadmin'])) {
		return true;
	} else {
		return false;
	}
}

function is_admin() {
	if(!empty($_SESSION['admin'])) {
		return true;
	} else {
		return false;
	}
}

function is_active() {
	if(!empty($_SESSION['user_id'])) {
		$userid = (int) $_SESSION['user_id'];
		$sql = "SELECT user_type FROM forum_users WHERE user_id='$userid'";
		$result=mysql_query($sql);
		if($row = sql_fetchrow($result)) {
			if($row['user_type'] != 1) {
				return true;
			}
		}
	}
	return false;
}


function profile_ready() {
	if(!empty($_SESSION['user_id'])) {
		$userid = (int) $_SESSION['user_id'];
		$sql = "SELECT forum_users.user_id, forum_users.user_email, forum_users.username, agency_profiles.firstname, agency_profiles.lastname, agency_profiles.location, agency_profiles.register_browser, agency_cc.number, agency_cc.promocode FROM forum_users, agency_profiles, agency_cc WHERE agency_profiles.payProcessed='0' AND  agency_profiles.user_id=forum_users.user_id AND agency_cc.user_id=forum_users.user_id AND forum_users.user_type='1' AND agency_profiles.account_type='talent' AND (forum_users.user_id IN (SELECT user_id FROM agency_photos) OR agency_profiles.headshot IS NOT NULL) AND forum_users.user_id='$userid'";
		$result=mysql_query($sql);
		if($row = sql_fetchrow($result)) {
			return true;
		}
	}
	return false;
}


function is_banned() {
	$IP = getRealIpAddr();
	if(mysql_result(mysql_query("SELECT COUNT(*) as 'Num' FROM forum_banlist WHERE ban_ip='$IP' AND ban_ip<>''"),0)) {
		return true;
	} else {
		return false;
	}
}

function agency_add_form_key($form_name)
{
	$now = time();
	$token_sid = $_SESSION['user_id'];
	$token = sha1($now . $form_name . $token_sid);

	return $token;
}

function agency_account_type($userid = false) {
	if(!$userid) {
		if(!empty($_SESSION['user_id'])) { // check if user is logged in
			$userid = (int) $_SESSION['user_id'];
		}
	}
	$sql = "SELECT account_type FROM agency_profiles WHERE user_id='$userid'";
	$result=mysql_query($sql);
	if($row = sql_fetchrow($result)) {
		return $row['account_type'];
	} else {
		return false;
	}
}

function agency_privacy($userid, $what) {
	if (is_admin()) {
		return true; // admin can see everything
	} else {
		if(!empty($userid) && !empty($what)){ // make sure these are set
			if($userid == $_SESSION['user_id']) {  // the owner of the information can always view it
				return true;
			} else if (agency_account_type() == 'talent') {
				 // check if a friend is viewing
				 if($what == 'phone') {
					 return false; // no talent see phone 4/8/14
				 }
				 if(agency_friends($_SESSION['user_id'], $userid) && (@mysql_result(@mysql_query("SELECT COUNT(*) as 'Num' FROM agency_privacy WHERE user_id='$userid' AND what='$what' AND (who='everyone' OR who LIKE '%friends%')"),0) > 0)) {
			 		return true;
				 } else {
			 		 return false;
				 }
			} else if(agency_account_type() == 'client' && is_active()) {
				if($what == 'phone') {
					 return true; // all clients see phone  4/8/14
				 }
				if(@mysql_result(@mysql_query("SELECT COUNT(*) as 'Num' FROM agency_privacy WHERE user_id='$userid' AND what='$what' AND (who='everyone' OR who LIKE '%clients%')"),0) > 0) {
					return true;
				} else {
					return false;
				}
			} else { // default: account type is not set, so only show things meant for everyone
				if($what == 'phone') {
					 return false; // no talent see phone 4/8/14
				 }
				if(@mysql_result(@mysql_query("SELECT COUNT(*) as 'Num' FROM agency_privacy WHERE user_id='$userid' AND what='$what' AND who='everyone'"),0) > 0) {
					return true;
				} else {
					return false;
				}
			}
		} else {
			return false;
		}
	}
}

// function to check if two people are friends
function agency_friends($userid, $friendid) {
	if(@mysql_result(@mysql_query("SELECT friend_id FROM agency_friends WHERE user_id='$userid' AND friend_id='$friendid' AND confirmed='1'"),0) > 0) {
		return true;
	} else {
		return false;
	}
}


function avatar_link($userid) {
	if(agency_account_type($userid) == 'client') {
		return '<a href="javascript:void(0)">';
	} else {
		return '<a href="profile.php?u=' . $userid . '">';
	}
}

// function to remove http from URL
function remove_http($url = '')
{
	if ($url == 'http://' OR $url == 'https://')
	{
		return $url;
	}
	$matches = substr($url, 0, 7);
	if ($matches=='http://')
	{
		$url = substr($url, 7);
	}
	else
	{
		$matches = substr($url, 0, 8);
		if ($matches=='https://')
		$url = substr($url, 8);
	}
	return $url;
}

// get IP address
function getRealIpAddr()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
    {
      $ip=$_SERVER['HTTP_CLIENT_IP'];
    }
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
    {
      $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    else
    {
      $ip=$_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

function required_fields($castingid) {
	$query = "SELECT * FROM agency_castings WHERE casting_id='$castingid'";
	$result = @mysql_query ($query);
	if ($row = @mysql_fetch_array ($result, MYSQL_ASSOC)) {
		if($row['casting_director'] && $row['job_title'] && $row['rate_day'] && $row['rate_usage'] && $row['casting_date'] && $row['shoot_date_start'] && $row['shoot_date_end'] && $row['location_shoot'] && $row['location_casting']) {
			if(mysql_result(mysql_query("SELECT COUNT(*) as 'Num' FROM agency_castings_roles WHERE casting_id='$castingid'"),0) && mysql_result(mysql_query("SELECT COUNT(*) as 'Num' FROM agency_castings_unions WHERE casting_id='$castingid'"),0) && mysql_result(mysql_query("SELECT COUNT(*) as 'Num' FROM agency_castings_jobtype WHERE casting_id='$castingid'"),0)) {
				return true;
			}
		}
	}

	return false;
	
}

function updateCities() {
	$sql = "DELETE FROM agency_cities";
	mysql_query($sql);
	$sql = "SELECT DISTINCT city, state, country FROM agency_profiles, forum_users WHERE agency_profiles.user_id=forum_users.user_id AND agency_profiles.city IS NOT NULL AND agency_profiles.city <> '' AND agency_profiles.country IS NOT NULL AND agency_profiles.country <> '' AND agency_profiles.account_type='talent' AND forum_users.user_type='0'";
	$result = mysql_query($sql);
	while($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$country = escape_data($row['country']);
		$state = escape_data($row['state']);
		$city = escape_data($row['city']);
		$sql2 = "INSERT INTO agency_cities (country, state, city) VALUES ('$country', '$state', '$city')";
		mysql_query($sql2);
	}
}

function agency_print_suit($suitraw) {
	$value = floor($suitraw);
	$var = (string) ($suitraw - $value);
	switch($var) {
		case '0.1':
			$value .= ' XS';
			break;
		case '0.2':
			$value .= ' S';
			break;
		case '0.3':
			$value .= ' R';
			break;
		case '0.4':
			$value .= ' L';
			break;
		case '0.5':
			$value .= ' XL';
			break;
		case '0.6':
			$value .= ' XXL';
			break;
	}

	return $value;
}

function showbox($boxname) {
	$sql = "SELECT varvalue FROM agency_vars WHERE varname='$boxname'";
	$result = mysql_query($sql);
	if($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$content = $row['varvalue'];
		if(strpos($content, 'img')) {
			$emptytest = true;
		} else {
			$emptytest = strip_tags($content);
		}
		if(!empty($emptytest)) {
			return $content;
		}
	}
	return false;
}

function get_agency_var($name) {
	$sql = "SELECT varvalue FROM agency_vars WHERE varname='$name'";
	$result = mysql_query($sql);
	if($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
		$content = $row['varvalue'];
		if(strpos($content, 'img')) {
			$emptytest = true;
		} else {
			$emptytest = strip_tags($content);
		}
		if(!empty($emptytest)) {
			return $content;
		}
	}
	return false;
}

function clientbuttons($backto = false) {
	$buttons = '<a href="castingupdate.php" style="clear:both; padding-bottom:9px"><img src="images/client_post_casting.gif" border="0"></a>
		<a href="clienthome.php?mode=search" style="clear:both; padding-bottom:9px"><img src="images/client_search.gif" border="0"></a>
		<a href="messages.php" style="clear:both; padding-bottom:9px"><img src="images/client_messages.gif" border="0"></a>
		<a href="myaccount.php" style="clear:both; padding-bottom:9px"><img src="images/client_settings.gif" border="0"></a>';
	if(count($_GET) || $backto) {
		$buttons .= '<a href="clienthome.php"><img src="images/client_backto.gif" border="0"></a>';
	}
	return $buttons;
}


function update_dropdowns() { // update dropdowns on casting filtering
	global $db;
	global $jobtypearray;
	global $unionarray;

	// Locations
	$sql = "DELETE FROM agency_castings_drop_loc"; // remove current cached locations before updating
	mysql_query($sql);
	$sql = "SELECT DISTINCT location_casting FROM agency_castings WHERE deleted='0' AND live='1' AND location_casting IS NOT NULL ORDER BY location_casting";
	$result=mysql_query($sql);
	while($row = sql_fetchrow($result)) {
		$location = escape_data($row['location_casting']);
		$sql2 = "INSERT INTO agency_castings_drop_loc (casting_location) VALUES ('$location')";
		mysql_query($sql2);
	}

	// Jobs
	$addother = false; // if it's not in the job array, it should be placed under "other"
	$sql = "DELETE FROM agency_castings_drop_job"; // remove current cached locations before updating
	mysql_query($sql);
	$sql = "SELECT DISTINCT agency_castings_jobtype.jobtype FROM agency_castings, agency_castings_jobtype WHERE agency_castings.casting_id=agency_castings_jobtype.casting_id AND agency_castings.deleted='0' AND agency_castings.live='1' ORDER BY agency_castings_jobtype.jobtype";
	$result=mysql_query($sql);
	while($row = sql_fetchrow($result)) {
		$jobtype = escape_data($row['jobtype']);
		if(in_array($jobtype, $jobtypearray)) {
			$sql2 = "INSERT INTO agency_castings_drop_job (job_type) VALUES ('$jobtype')";
			mysql_query($sql2);
		} else {
			$addother = true;
		}
	}
	
	
	// Unions
	$addother = false; // if it's not in the job array, it should be placed under "other"
	$sql = "DELETE FROM agency_castings_drop_unions"; // remove current cached locations before updating
	mysql_query($sql);
	$sql = "SELECT DISTINCT agency_castings_unions.union_name FROM agency_castings, agency_castings_unions WHERE agency_castings.casting_id=agency_castings_unions.casting_id AND agency_castings.deleted='0' AND agency_castings.live='1' ORDER BY agency_castings_unions.union_name";
	$result=mysql_query($sql);
	while($row = sql_fetchrow($result)) {
		$union = escape_data($row['union_name']);
		if(in_array($union, $unionarray)) {
			$sql2 = "INSERT INTO agency_castings_drop_unions (union_name) VALUES ('$union')";
			mysql_query($sql2);
		} else {
			$addother = true;
		}
	}	
	
	
	if($addother) {
		$sql2 = "INSERT INTO agency_castings_drop_job (job_type) VALUES ('Other')";
		mysql_query($sql2);
	}
}






function lightbox_show($roleid=false, $public=false) {
	global $profileid;
	global $lightbox_id;
	global $experiencecolors;
	global $experienceimages;
	
	$number_across = 3; // how many images to show across the page
	$current = 1; // counter for table	
	
	if($public) {
		$sqlprivate = "";
	} else {
		$sqlprivate = "AND agency_lightbox.client_id='$profileid'";
	}
	
	if($roleid) {
		$query = "SELECT agency_profiles.user_id, agency_profiles.firstname, agency_profiles.lastname, agency_profiles.phone, agency_profiles.experience, agency_profiles.registration_date, agency_profiles.resume, agency_profiles.resume_text, forum_users.user_email, agency_lightbox_users.entry_id FROM agency_lightbox_users, agency_lightbox, agency_profiles, forum_users WHERE agency_lightbox_users.lightbox_id=agency_lightbox.lightbox_id AND agency_lightbox_users.user_id=agency_profiles.user_id AND forum_users.user_id=agency_profiles.user_id AND agency_lightbox_users.lightbox_id='$lightbox_id' $sqlprivate AND agency_lightbox_users.role_id='$roleid'";
	} else {
		$query = "SELECT agency_profiles.user_id, agency_profiles.firstname, agency_profiles.lastname, agency_profiles.phone, agency_profiles.experience, agency_profiles.registration_date, agency_profiles.resume, agency_profiles.resume_text, forum_users.user_email, agency_lightbox_users.entry_id FROM agency_lightbox_users, agency_lightbox, agency_profiles, forum_users WHERE agency_lightbox_users.lightbox_id=agency_lightbox.lightbox_id AND agency_lightbox_users.user_id=agency_profiles.user_id AND forum_users.user_id=agency_profiles.user_id AND agency_lightbox_users.lightbox_id='$lightbox_id' $sqlprivate AND agency_lightbox_users.role_id IS NULL";
	}
	$result = mysql_query($query);
	if(mysql_num_rows($result) > 0) {
		// echo '<table width="100%" cellpadding="10">';
		echo '<div style="clear:both">';
		
		while($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
			if($current == 1) {
				// echo '<tr>';
				echo '<div style="clear:both">';
			}
			$uid = $row['user_id'];
			$entryid = $row['entry_id'];
			
			if($public) { // lightbox for sent link (lightbox.php) is different from clienthome
				$width = '170';
			} else {
				$width = '197';
			}
			echo '<div style="float:left; width:' . $width . 'px; padding:8px">';
			
			if(!is_active()) {
				echo '<a href="profile.php?u=' . $uid . '" target="_blank"><img src="';
			} else {
				echo '<a href="ajax/compcard_mini.php?u=' . $uid . '&amp;lightbox=' . $lightbox_id . '&amp;height=400&amp;width=450" class="thickbox"><img src="';
			}

			$posterfolder = 'talentphotos/' . $uid . '_' . $row['registration_date'] . '/';

			if(file_exists($posterfolder . 'avatar.jpg')) {
				echo   $posterfolder . 'avatar.jpg';
			} else if(file_exists($posterfolder . 'avatar.gif')) {
				echo   $posterfolder . 'avatar.gif';
			} else {
				echo 'images/friend.gif';
			}


			echo '" /></a>';

			echo '<div style="text-align:center; padding:4px; font-weight:bold; color:' . $experiencecolors[$row['experience']] . '">' . $row['firstname'];


			if(agency_privacy($uid, 'lastname')) {
				echo ' ' . $row['lastname'];
			}				
			
			$idplus = '';
			if(!empty($roleid)) {
				$idplus = '_' . $roleid . '_';
			} else {
				$idplus = 'x';
			}
			
			if(!$public) {
				echo ' <input type="checkbox" id="entry_' . $idplus . $entryid . '" name="entryid[]" value="' . $entryid . '" /><input type="hidden" id="emails_' . $entryid . '" value="' . $row['user_email'] . '">';
			}
			echo '<br />' . '<img src="images/' . $experienceimages[$row['experience']] . '.gif" onmouseout="document.getElementById(\'experience_popup\').style.display=\'none\'" onmouseover="document.getElementById(\'experience_popup\').style.display=\'\'">';
			
			if(agency_account_type() == 'client' && is_active()) {	
				// UNION STATUS
				 $sql4 = "SELECT * FROM agency_profile_unions WHERE user_id='$uid'";
				 $result4=mysql_query($sql4);
				 $num_results4 = mysql_num_rows($result4);
				 $current4 = 1;
				 if($num_results4) {
					echo '<br /><span class="AGENCYCompCardLabel">Union: </span><span class="AGENCYCompCardStat">';
					while($row4 = sql_fetchrow($result4)) {
						echo escape_data($row4['union_name']);
						if($current4 < $num_results4) echo ', ';
						$current4++;
					}
					echo '</span>';
				 }												
					
				$resumeicon = false;
				if(!empty($row['resume'])) {
					if(file_exists($posterfolder . '/' . $row['resume'])) {
						echo '<br /><a href="' . $posterfolder . $row['resume'] . '" target="_blank"><img src="images/resume1.gif" border="0" style="padding-top:5px;" ></a>';
						$resumeicon = true;
					}
				}
				
					// check for reel/vo
				if( mysql_result(mysql_query("SELECT COUNT(*) as 'Num' FROM agency_vo WHERE user_id='$uid'"),0) || mysql_result(mysql_query("SELECT COUNT(*) as 'Num' FROM agency_reel WHERE user_id='$uid'"),0 )) {
						/* if($resumeicon) {
							echo '&nbsp;&nbsp;';
						} else { */
							echo '<br />';
						// }
						if( mysql_result(mysql_query("SELECT COUNT(*) as 'Num' FROM agency_vo WHERE user_id='$uid'"),0)) {
						echo '<a target="_blank" href="profile.php?tab=Reel/VO&u=' . $uid . '"><img src="images/vo.gif" border="0" style="padding-top:5px;" ></a>';
						}
						if( mysql_result(mysql_query("SELECT COUNT(*) as 'Num' FROM agency_reel WHERE user_id='$uid'"),0)) {
						echo '&nbsp;&nbsp;&nbsp;<a target="_blank" href="profile.php?tab=Reel/VO&u=' . $uid . '"><img src="images/reel.gif" border="0" style="padding-top:5px;" ></a>';
						}
				}
				
				echo '<br />' .
					'<span style="color:black; font-weight:normal"><a href="mailto:' . $row['user_email'] . '">' . wordwrap($row['user_email'], 33, "<br />", true) . '</a>';
				
				if(!empty($row['phone']) && agency_privacy($uid, 'phone')) {
					echo '<br />' . wordwrap($row['phone'], 33, "<br />", true);
				}
				echo '</span>';
			
			}
			echo '</div></div>';

			if($current == $number_across) {
				echo '<br clear="all" /></div><br clear="all" />';
				$current = 1;
			} else {
				$current++;
			}

		}
		
		if($current != 1) {
			echo '<br clear="all" /></div>';
		}
		
		echo '<br clear="all" /></div>';
		
		/* if(!empty($roleid)) {
			echo '<span id="check_uncheck_all_' . $roleid . '"><input type="button" value="select group" onclick="checkGroup(\'entry__' . $roleid . '_\', true, \'switch_to_uncheck\', \'check_uncheck_all_' . $roleid . '\')"></span> ';
		} */
		
		
		
		if(empty($roleid)) {
			echo '<input type="button" value="select group" onclick="checkGroup(\'entry_x\', true)">&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="unselect group" onclick="checkGroup(\'entry_x\', false)">';			
		} else {
			echo '<input type="button" value="select role" onclick="checkGroup(\'entry__' . $roleid . '_\', true)">&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="unselect role" onclick="checkGroup(\'entry__' . $roleid . '_\', false)">';
		}		
		
		echo '<br /><br />';
					
	} else {
		if(!empty($roleid)) {
			echo 'No lightbox entries yet for this role';
		}
			
	}
}

/*
* Hash the password
*/
function _hash($password)
{
	$itoa64 = './0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';

	$random_state = unique_id();
	$random = '';
	$count = 6;

	if (strlen($random) < $count)
	{
		$random = '';

		for ($i = 0; $i < $count; $i += 16)
		{
			$random_state = md5(unique_id() . $random_state);
			$random .= pack('H*', md5($random_state));
		}
		$random = substr($random, 0, $count);
	}

	$hash = _hash_crypt_private($password, _hash_gensalt_private($random, $itoa64), $itoa64);

	if (strlen($hash) == 34)
	{
		return $hash;
	}

	return md5($password);
}	

function randomStr($n) { 
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'; 
    $randomString = ''; 
  
    for ($i = 0; $i < $n; $i++) { 
        $index = rand(0, strlen($characters) - 1); 
        $randomString .= $characters[$index]; 
    } 
    return $randomString; 
} 

/**
* Return unique id
* @param string $extra additional entropy
*/
function unique_id($extra = 'c')
{
	$seed = 'x8O84j2Gie';
	$val = $seed . microtime();
	$val = md5($val);
	$seed = md5($seed . $val . $extra);

	return substr($val, 4, 16);
}					
/**
* Check for correct password
*
* @param string $password The password in plain text
* @param string $hash The stored password hash
*
* @return bool Returns true if the password is correct, false if not.
*/
function _check_hash($password, $hash)
{
	$itoa64 = './0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
	if (strlen($hash) == 34)
	{
		return (_hash_crypt_private($password, $hash, $itoa64) === $hash) ? true : false;
	}

	return (md5($password) === $hash) ? true : false;
}
/**
* Generate salt for hash generation
*/
function _hash_gensalt_private($input, &$itoa64, $iteration_count_log2 = 6)
{
	if ($iteration_count_log2 < 4 || $iteration_count_log2 > 31)
	{
		$iteration_count_log2 = 8;
	}

	$output = '$H$';
	$output .= $itoa64[min($iteration_count_log2 + ((PHP_VERSION >= 5) ? 5 : 3), 30)];
	$output .= _hash_encode64($input, 6, $itoa64);

	return $output;
}

/**
* Encode hash
*/						
function _hash_encode64($input, $count, &$itoa64)
{
	$output = '';
	$i = 0;

	do
	{
		$value = ord($input[$i++]);
		$output .= $itoa64[$value & 0x3f];

		if ($i < $count)
		{
			$value |= ord($input[$i]) << 8;
		}

		$output .= $itoa64[($value >> 6) & 0x3f];

		if ($i++ >= $count)
		{
			break;
		}

		if ($i < $count)
		{
			$value |= ord($input[$i]) << 16;
		}

		$output .= $itoa64[($value >> 12) & 0x3f];

		if ($i++ >= $count)
		{
			break;
		}

		$output .= $itoa64[($value >> 18) & 0x3f];
	}
	while ($i < $count);

	return $output;
}		

/**
* The crypt function/replacement
*/
function _hash_crypt_private($password, $setting, &$itoa64)
{
	$output = '*';

	// Check for correct hash
	if (substr($setting, 0, 3) != '$H$')
	{
		return $output;
	}

	$count_log2 = strpos($itoa64, $setting[3]);

	if ($count_log2 < 7 || $count_log2 > 30)
	{
		return $output;
	}

	$count = 1 << $count_log2;
	$salt = substr($setting, 4, 8);

	if (strlen($salt) != 8)
	{
		return $output;
	}

	$hash = md5($salt . $password, true);
	do
	{
		$hash = md5($hash . $password, true);
	}
	while (--$count);


	$output = substr($setting, 0, 12);
	$output .= _hash_encode64($hash, 16, $itoa64);

	return $output;
}



// Common global functions

/**
* set_var
*
* Set variable, used by {@link request_var the request_var function}
*
* @access private
*/
function set_var(&$result, $var, $type, $multibyte = false)
{
	settype($var, $type);
	$result = $var;

	if ($type == 'string')
	{
		$multibyte = true; // these two lines were added so in ALL cases strings are converted to UTF8 characters
		// $result = utf8_normalize_nfc($result); // these two lines were added so in ALL cases strings are converted to UTF8 characters
		
		$result = trim(htmlspecialchars(str_replace(array("\r\n", "\r", "\0"), array("\n", "\n", ''), $result), ENT_QUOTES, 'UTF-8'));

		if (!empty($result))
		{
			// Make sure multibyte characters are wellformed
			if ($multibyte)
			{
				if (!preg_match('/^./u', $result))
				{
					$result = '';
				}
			}
			else
			{
				// no multibyte, allow only ASCII (0-127)
				$result = preg_replace('/[\x80-\xFF]/', '?', $result);
			}
		}

		$result = stripslashes($result);
	}
}

/**
* request_var
*
* Used to get passed variable
*/
function request_var($var_name, $default, $multibyte = false, $cookie = false)
{
	if (!$cookie && isset($_COOKIE[$var_name]))
	{
		if (!isset($_GET[$var_name]) && !isset($_POST[$var_name]))
		{
			return (is_array($default)) ? array() : $default;
		}
		$_REQUEST[$var_name] = isset($_POST[$var_name]) ? $_POST[$var_name] : $_GET[$var_name];
	}

	if (!isset($_REQUEST[$var_name]) || (is_array($_REQUEST[$var_name]) && !is_array($default)) || (is_array($default) && !is_array($_REQUEST[$var_name])))
	{
		return (is_array($default)) ? array() : $default;
	}

	$var = $_REQUEST[$var_name];
	if (!is_array($default))
	{
		$type = gettype($default);
	}
	else
	{
		list($key_type, $type) = each($default);
		$type = gettype($type);
		$key_type = gettype($key_type);
		if ($type == 'array')
		{
			reset($default);
			$default = current($default);
			list($sub_key_type, $sub_type) = each($default);
			$sub_type = gettype($sub_type);
			$sub_type = ($sub_type == 'array') ? 'NULL' : $sub_type;
			$sub_key_type = gettype($sub_key_type);
		}
	}

	if (is_array($var))
	{
		$_var = $var;
		$var = array();

		foreach ($_var as $k => $v)
		{
			set_var($k, $k, $key_type);
			if ($type == 'array' && is_array($v))
			{
				foreach ($v as $_k => $_v)
				{
					if (is_array($_v))
					{
						$_v = null;
					}
					set_var($_k, $_k, $sub_key_type);
					set_var($var[$k][$_k], $_v, $sub_type, $multibyte);
				}
			}
			else
			{
				if ($type == 'array' || is_array($v))
				{
					$v = null;
				}
				set_var($var[$k], $v, $type, $multibyte);
			}
		}
	}
	else
	{
		set_var($var, $var, $type, $multibyte);
	}

	return $var;
}


/**
* Build sql statement from array for insert/update/select statements
*
* Idea for this from Ikonboard
* Possible query values: INSERT, INSERT_SELECT, UPDATE, SELECT
*
*/
function sql_build_array($query, $assoc_ary = false)
{
	if (!is_array($assoc_ary))
	{
		return false;
	}

	$fields = $values = array();

	if ($query == 'INSERT' || $query == 'INSERT_SELECT')
	{
		foreach ($assoc_ary as $key => $var)
		{
			$fields[] = $key;

			if (is_array($var) && is_string($var[0]))
			{
				// This is used for INSERT_SELECT(s)
				$values[] = $var[0];
			}
			else
			{
				$values[] = _sql_validate_value($var);
			}
		}

		$query = ($query == 'INSERT') ? ' (' . implode(', ', $fields) . ') VALUES (' . implode(', ', $values) . ')' : ' (' . implode(', ', $fields) . ') SELECT ' . implode(', ', $values) . ' ';
	}
	else if ($query == 'MULTI_INSERT')
	{
		echo 'There was an error.  Please contact the administrator.  Thank you.';
	}
	else if ($query == 'UPDATE' || $query == 'SELECT')
	{
		$values = array();
		foreach ($assoc_ary as $key => $var)
		{
			$values[] = "$key = " . _sql_validate_value($var);
		}
		$query = implode(($query == 'UPDATE') ? ', ' : ' AND ', $values);
	}

	return $query;
}

/**
* Function for validating values
* @access private
*/
function _sql_validate_value($var)
{
	if (is_null($var))
	{
		return 'NULL';
	}
	else if (is_string($var))
	{
		return "'" . escape_data($var) . "'";
	}
	else
	{
		return (is_bool($var)) ? intval($var) : $var;
	}
}


// PAYMENT FUNCTIONS

function getBetween($string, $start, $end) {
	$ini = strpos($string,$start);
	if ($ini === false) return "";
	$ini += strlen($start);
	$len = strpos($string,$end,$ini) - $ini;
	return substr($string,$ini,$len);
}

function get_rec_payment_id($userid) {
	// The "Profile ID" that PayPal requests is "recurring_payment_id"
	$payment_id = NULL;
	$latest = true; // flag to see if the last transaction was a failure
	$failure = false; // flag to see if latest is recurring_payment_failed
	$query = "SELECT * FROM agency_payment_log WHERE user_id='$userid' ORDER BY log_id DESC LIMIT 5"; // it'll check 5 records until it finds the payment id
	$result = @mysql_query ($query);
	while ($row = @mysql_fetch_array ($result, MYSQL_ASSOC)) {
		if(empty($payment_id) && !$failure) {
			if($latest && $row['transaction_type'] == 'recurring_payment_failed') {
				$failure = true;
			} 
			$latest = false;
			$ipn_summary = $row['ipn_summary'];
			$payment_id = trim(getBetween($ipn_summary, "recurring_payment_id = ", "\n"));
			if(empty($payment_id)) {
				$payment_id = $row['paypal_PROFILEID'];
			}
		}
	}
	return $payment_id;
}

function check_info($userid, $parameter=false) {
	if(is_int($userid)) {
		// get ALL info for this user
		$required = array('firstname', 'phone', 'gender', 'ethnicity', 'birthdate', 'hair', 'eyes', 'experience', 'union_name', 'image_id');
		$query = "SELECT profiles.* WHERE user_id='$user_id'";
		
		$query = "SELECT category FROM agency_profile_categories WHERE user_id='$user_id'";
		
		$query = "SELECT ethnicity FROM agency_profile_ethnicities WHERE user_id='$user_id'";
		
		$query = "SELECT union_name FROM agency_profile_unions WHERE user_id='$user_id'";
		
		$query = "SELECT image_id FROM agency_photos WHERE user_id='$user_id'";
		
		$query = "SELECT image_id FROM agency_photos WHERE user_id='$user_id' AND card_position IS NOT NULL";
		
		
		
		
	
		unset($query);
		unset($result);
		unset($row);
	}
}

function user_location() {
	$location = '';
	if(!empty($_SESSION['user_id'])) {
		$userid = (int) $_SESSION['user_id'];
		$sql = "SELECT location FROM agency_profiles WHERE user_id='$userid'";
		$result=mysql_query($sql);
		if($row = sql_fetchrow($result)) {
			$location = $row['location'];
		}
	}
	return $location;
}


function age_from_dob($dob) {

    list($y,$m,$d) = explode('-', $dob);
   
    if (($m = (date('m') - $m)) < 0) {
        $y++;
    } elseif ($m == 0 && date('d') - $d < 0) {
        $y++;
    }
   
    return date('Y') - $y;
   
}


function auto_submit($castingid) { // "saves existing submissions to a lightbox, submits this lightbox to client."

	$query = "SELECT * FROM agency_castings WHERE casting_id='$castingid'";
	$result = mysql_query ($query);
	if ($row = mysql_fetch_array ($result, MYSQL_ASSOC)) {
		$posted_by = $row['posted_by'];
		$job_title = $row['job_title'];

		$query2 = "SELECT agency_mycastings.* FROM agency_castings, agency_castings_roles, agency_mycastings WHERE agency_castings.casting_id='$castingid' AND agency_castings.casting_id=agency_castings_roles.casting_id AND agency_castings_roles.role_id=agency_mycastings.role_id AND agency_mycastings.removed='0'";
		$result2 = mysql_query ($query2);
		if(mysql_num_rows($result2)) {
			// make lightbox
			$timecode = strtotime("NOW");
			$querylb = "INSERT INTO agency_lightbox (client_id, lightbox_name, casting_id, timecode) VALUES ('$posted_by', '$job_title', '$castingid', '$timecode')";
			mysql_query($querylb);
			$lightbox_id = mysql_insert_id();
			$count = 0;
			while ($row2 = mysql_fetch_array ($result2, MYSQL_ASSOC)) {
				$user_id = $row2['user_id'];
				$role_id = $row2['role_id'];
				$query3 = "INSERT INTO agency_lightbox_users (lightbox_id, user_id, role_id) VALUES ('$lightbox_id', '$user_id', '$role_id')";
				mysql_query($query3);
				$count++;
			}
			
			// send lightbox to client
			if(is_admin()) { // if this is the client, they don't need the email
				$query4 = "SELECT user_email FROM forum_users WHERE user_id='$posted_by'";
				$result4 = mysql_query ($query4);
				if ($row4 = mysql_fetch_array ($result4, MYSQL_ASSOC)) {
					$email = $row4['user_email'];
					$timecode = md5($timecode);
					$subject = 'The Agency Online: Your Casting Submissions';
					$from = "no-reply@theagencyonline.com";
					$headers  = "From: $from\r\n";
					$headers .= "Content-type: text/html\r\n";
					$message = '<html><body><p>A Lightbox has been created from your submissions to your Casting: ' . $job_title . '</p>
					
					<p>To manage this lightbox, please log into your account at <a href="http://www.TheAgencyOnline.com">http://www.TheAgencyOnline.com</a></p>
					
					<p>Here is a direct link to your new lightbox: <a href="http://www.theagencyonline.com/lightbox.php?lightbox=' . $lightbox_id . '&code=' . $timecode .'</p>
					</body></html>';
				
				
					mail($email, $subject, $message, $headers);
				}
			}
			
			
			// send summary
			$query5 = "SELECT agency_profiles.firstname, agency_profiles.lastname FROM agency_profiles, agency_castings WHERE agency_castings.posted_by = agency_profiles.user_id AND agency_castings.casting_id='$castingid'";
			$result5 = mysql_query ($query5);
			if ($row5 = mysql_fetch_array ($result5, MYSQL_ASSOC)) {
				$clientname = $row5['firstname'] . ' ' . $row5['lastname'];
			}
			
			$to = "clients@theagencyonline.com";
					
			$message = '<html><body>Auto Submit has created a lightbox for <b>' . $count . '</b> people for the casting: 
<br /><br />
<a href="http://www.theagencyonline.com/news.php?castingid=' . $castingid . '">' . $job_title . '</a>
<br /><br />
Client: ' . $clientname . '
<br /><br />
Lightbox: <a href="http://www.theagencyonline.com/lightbox.php?lightbox=' . $lightbox_id . '&code=' . $timecode .'
</body></html>';
	

			$from = "clients@theagencyonline.com";
			$subject = "Auto-Notify Report";
		
			$headers  = "From: $from\r\n";
			$headers .= "Content-type: text/html\r\n";

			mail($to, $subject, $message, $headers);			
			
			
			
			
			
	
			$url = 'clienthome.php?mode=lightbox&lightbox=' . $lightbox_id;
			ob_end_clean(); // Delete the buffer.
			header("Location: $url");
			exit(); // Quit the script.			
		}
	}
}




function auto_cull($castingid) {  // "one click ability to auto-edit a casting. Deletes people that aren't within specs."
	// $query = "SELECT agency_mycastings.* FROM agency_castings, agency_mycastings WHERE agency_castings.casting_id='$castingid' AND agency_castings.casting_id=agency_mycastings.casting_id AND agency_mycastings.removed='0'";
	$query = "SELECT agency_mycastings.* FROM agency_castings, agency_castings_roles, agency_mycastings WHERE agency_castings.casting_id='$castingid' AND agency_castings.casting_id=agency_castings_roles.casting_id AND agency_castings_roles.role_id=agency_mycastings.role_id AND agency_mycastings.removed='0'";
	$result = mysql_query ($query);
	while ($row = mysql_fetch_array ($result, MYSQL_ASSOC)) {
		$user_id = $row['user_id'];
		$role_id = $row['role_id'];
		
		// check if role matches casting parameters
		if(!rolematch($user_id, $role_id, $castingid)) {
			// remove from casting
			$query = "UPDATE agency_mycastings SET removed='1' WHERE user_id='$user_id' AND role_id='$role_id'";
			mysql_query($query);
		}		
	}
}


function rolematch($user_id, $role_id, $casting_id, $ignore_age = false) {
	$match = false; // initialize
	
	// get ethnicities for role
	$ethnicities = array();
	$query_v = "SELECT var_value FROM agency_castings_roles_vars WHERE role_id='$role_id' AND var_type='ethnicity'";
	$result_v = mysql_query ($query_v);
	while ($row_v = mysql_fetch_array ($result_v, MYSQL_ASSOC)) {
		$ethnicities[] = $row_v['var_value'];
	}
	
	// get genders for role
	$genders = array();
	$query_v = "SELECT var_value FROM agency_castings_roles_vars WHERE role_id='$role_id' AND var_type='gender'";
	$result_v = mysql_query ($query_v);
	while ($row_v = mysql_fetch_array ($result_v, MYSQL_ASSOC)) {
		$genders[] = $row_v['var_value'];
	}		
	
	// get user age and gender
	$query_u = "SELECT birthdate, gender FROM agency_profiles WHERE user_id='$user_id'";
	$result_u = mysql_query ($query_u);
	if ($row_u = mysql_fetch_array ($result_u, MYSQL_ASSOC)) {
		$age = age_from_dob($row_u['birthdate']);
		$gender = $row_u['gender'];
		switch ($gender) {
			case 'M':
				$gender = 'Male';
				break;
			case 'F':
				$gender = 'Female';
				break;
			case 'O':
				$gender = 'Other';
				break;	
		}
	
		// check unions
		$query2 = "SELECT COUNT(*) as 'Num' FROM agency_castings_unions WHERE 
			(	
				(
					agency_castings_unions.union_name IN (SELECT union_name FROM agency_profile_unions WHERE user_id='$user_id')
					OR 
					union_name='Non-Specified/Open to All'
				)
				OR
				(
					(
						union_name='SAG-AFTRA'
						 OR 
						 union_name='Non-Union'
					) AND (
						(SELECT COUNT(*) FROM agency_profile_unions WHERE union_name='SAG-Eligible' AND user_id='$user_id')
					)
				)
				OR
				(
					(
						 union_name='Non-Union'
					) AND (
						(SELECT COUNT(*) FROM agency_profile_unions WHERE union_name='AEA' AND user_id='$user_id')
					)
				)
			) AND casting_id='$casting_id'";
			// echo $query2;
		
		// check ethnicities
		$query3 = "SELECT COUNT(*) as 'Num' FROM agency_profile_ethnicities WHERE ethnicity IN ('" . implode("','", $ethnicities) . "') AND user_id='$user_id'";
		
		// check age
		$age_lower = $age + 5;
		$age_upper = $age - 5;
		$query4 = "SELECT COUNT(*) as 'Num' FROM agency_castings_roles WHERE age_lower<='$age_lower' AND age_upper>='$age_upper' AND role_id='$role_id'";
		
		
		
		if(is_admin()) {
			// echo $query2 . ' | ' . $query3 . ' | ' . $query4;
		}
		
		
		/* 
		// debugging code
		mysql_query($query2);
		mysql_query($query3);
		mysql_query($query4);
		echo mysql_result(mysql_query($query2),0);
		echo mysql_result(mysql_query($query3),0);
		echo mysql_result(mysql_query($query4),0);
		
		if(in_array($gender, $genders)) {
			echo 'yes';
		}
		*/
		// echo mysql_result(mysql_query($query2),0) . ' | ' . mysql_result(mysql_query($query3),0) . ' | ' . mysql_result(mysql_query($query4),0) . ' | ' . in_array($gender, $genders);
		
		if(mysql_result(mysql_query($query2),0) && mysql_result(mysql_query($query3),0) && (($ignore_age) ? true : mysql_result(mysql_query($query4),0)) && in_array($gender, $genders)) {
			$match = true;
		}
	}
	
	return $match;
}

function process_captcha() {
	$response = $_POST["g-recaptcha-response"];
	$url = 'https://www.google.com/recaptcha/api/siteverify';
	$data = array(
		'secret' => '6LeWa0YUAAAAACdsxBq744Lumt8G9qbUaAQcTZKY',
		'response' => $_POST["g-recaptcha-response"]
	);
	$options = array(
		'http' => array (
			'method' => 'POST',
			'content' => http_build_query($data)
		)
	);
	$context  = stream_context_create($options);
	$verify = file_get_contents($url, false, $context);
	$captcha_success=json_decode($verify);
	if ($captcha_success->success==false) {
		$success = false;
	} else if ($captcha_success->success==true) {
		$success = true;
	}	
	return $success;
}

function showcaptcha($userid) {
	$userid = (int) $userid;
	$return = false;
	if($userid) {
		$return = true;
		$date = date("Y-m-d H:i:s", strtotime("-1 day")); 
		
		$total_results = mysql_result(mysql_query("SELECT COUNT(*) as 'Num' FROM agency_wall WHERE agency_wall.date > '$date'"),0);
		
		if($total_results < 4) {
			$return = false;
		}
	}
	return $return;
	
}


function identical_values( $arrayA , $arrayB ) {

    sort( $arrayA );
    sort( $arrayB );

    return $arrayA == $arrayB;
} 

function getAllAuthor(){
	$result = array();
	$query_author = "SELECT * FROM agency_author";
	$result_author = @mysql_query ($query_author);
	while ($row = @mysql_fetch_array ($result_author, MYSQL_ASSOC)) {
		$result[] = $row;
	}
	return $result;
}

function get_service_category(){
	$sql = "SELECT * FROM agency_service_category";
	$query = @mysql_query ($sql);
	$result = array();
	while ($row = @mysql_fetch_array ($query, MYSQL_ASSOC)) {
		$result[] = $row;
	}

	// echo "<pre>";
	// print_r($result);
	// exit;
	return $result;
}


function filename_new_front($filename){
	$filename_ary = explode('.', $filename);
	// get the first item in the array (definitely)
	$first = reset($filename_ary); // prints 'one'
	 
	// get the last item in the array
	$last = end($filename_ary); // prints 'three'
	$filename_new = $first.'.'.$last;
	return time().'_'.preg_replace("/[^a-zA-Z0-9.]/", "", $filename_new);
}

function get_user_privileges($user_id){
	$sql = "SELECT * FROM agency_user_privileges 
			where user_id = ".$user_id." ";
	$query = mysql_query ($sql);
	$result = array();
	if (mysql_num_rows($query) > 0) {
		while ($res = @mysql_fetch_array ($query, MYSQL_ASSOC)) {
			if($res['privilege_json'] != "null"){
				$result = json_decode($res['privilege_json']);
			}
		}
	}
	// echo "<pre>";
	// print_r($result);
	// echo "</pre>";
	// exit;

	return $result;
}

function limit_word($str, $len) {
    if (strlen($str) < $len)
        return $str;
 
    $str = substr($str,0,$len);
    if ($spc_pos = strrpos($str," ")){
		$str = substr($str,0,$spc_pos);
	}
 
    // return $str . "Read more...";
    return $str;
} 

function get_all_sub_admin(){
	$sql = "SELECT ap.*,fu.* FROM agency_profiles ap
			LEFT JOIN forum_users fu ON fu.user_id = ap.user_id 
			where ap.account_type = 'admin' ";
	$query = mysql_query ($sql);
	$result = array();
	if (mysql_num_rows($query) > 0) {
		while ($res = mysql_fetch_assoc ($query)) {
			$result[] = $res;
		}
	}
	return $result;
}

?>