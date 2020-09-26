<div id="content_general" class="AGENCYfillcontent">
	<br /><b>GENERAL INFO:</b><br />
	<div style="border:1px solid black; width:400px; min-height:300px; margin:20px; padding:10px">
<?php
if(!empty($userinfo['experience'])) {
	echo 'Experience Level: <img src="images/' . $experienceimages[$userinfo['experience']] . '.gif" /><br /><br />';
}
if(!empty($userinfo['gender'])) {
	echo 'Gender: <b>';
	switch ($userinfo['gender']) {
		case 'F':
			echo 'Female';
			break;
		case 'M':
			echo 'Male';
			break;
		default:
			echo 'Other';
			break;
	}
	echo '</b><br /><br />';
}
$sql = "SELECT ethnicity FROM agency_profile_ethnicities WHERE user_id='$profileid'";
$result = mysql_query($sql);
if(mysql_num_rows($result) > 0) {
	$num_cats = mysql_num_rows($result);
	echo 'Ethnicity: <b>';
	while ($row = sql_fetchrow($result)) {
		echo $row['ethnicity'];
		if($num_cats > 1) echo ', ';
		$num_cats--;
	}
	echo '</b><br /><br />';
}
echo 'Location: <b>' . $userinfo['city']; if(!empty($userinfo['city'])) { echo ', '; } echo $userinfo['state'] . ' ' . $userinfo['country'] . '</b><br /><br />';

$sql = "SELECT category FROM agency_profile_categories WHERE user_id='$profileid'";
$result = mysql_query($sql);
if(mysql_num_rows($result) > 0) {
	$num_cats = mysql_num_rows($result);
	echo 'Categories: <b>';
	while ($row = sql_fetchrow($result)) {
		echo $row['category'];
		if($num_cats > 1) echo ', ';
		$num_cats--;
	}
	echo '</b><br /><br />';
}

$sql = "SELECT union_name FROM agency_profile_unions WHERE user_id='$profileid'";
$result = mysql_query($sql);
if(mysql_num_rows($result) > 0) {
	$num_cats = mysql_num_rows($result);
	echo 'Unions: <b>';
	while ($row = sql_fetchrow($result)) {
		echo $row['union_name'];
		if($num_cats > 1) echo ', ';
		$num_cats--;
	}
	echo '</b><br /><br />';
}

echo 'Height: <b>' . floor($userinfo['height']/12) . '\' ' . $userinfo['height'] % 12 . '"</b><br /><br />
	Waist: <b>' . escape_data($userinfo['waist']) . '"</b><br /><br />';

if($userinfo['gender'] != 'M') { // if female or "other"
echo 'Bust: <b>' . escape_data($userinfo['bust']) . '"</b><br /><br />
	Cup Size <b>: ' . escape_data($bracups[$userinfo['cup']]) . '</b><br /><br />
	Hips: <b>' . escape_data($userinfo['hips']) . '"</b><br /><br />
	Dress: <b>' . escape_data($userinfo['dress']) . '"</b><br /><br />';
}

if($userinfo['gender'] != 'F') { // if male or "other"
echo 'Suit: <b>' . agency_print_suit(escape_data($userinfo['suit'])) . '</b><br /><br />
	Neck: <b>' . escape_data($userinfo['neck']) . '"</b><br /><br />
	Shirt: <b>' . escape_data($userinfo['shirt']) . '"</b><br /><br />
	Inseam: <b>' . escape_data($userinfo['inseam']) . '"</b><br /><br />';
}

echo 'Shoe: <b>' . escape_data($userinfo['shoe']) . '</b><br /><br />
	Hair: <b>' . $userinfo['hair'] . '</b><br /><br />
	Eyes: <b>' . escape_data($userinfo['eyes']) . '</b><br /><br />';

if($_SESSION['user_id'] == $profileid) { // if this is the profile of the logged in user
	echo '<br><br><a class="AGENCY_graybutton" href="myaccount.php">edit</a>';
}
?>
	</div>
</div>

<div id="content_bio" class="AGENCYfillcontent">
	<br /><b>BIOGRAPHY:</b><br />
	<div style="border:1px solid black; width:400px; min-height:300px; margin:20px; padding:10px">
<?php 
if((agency_privacy($profileid, 'bio') || (agency_account_type() == 'client' && is_active())) && !empty($userinfo['bio'])) {
	echo nl2br($userinfo['bio']) . '<br /><div align="center"><a href="pdf_bio.php?u=' . $profileid . '" target="_blank" style="text-decoration:none"><img src="images/biography1.gif" border="0" style="padding-top:5px;"></a></div>'; 
} else {
	echo 'This user has has not entered a biography or has set their biography to private.';
}
if($_SESSION['user_id'] == $profileid) { // if this is the profile of the logged in user
	echo '<br><br><a class="AGENCY_graybutton" href="myaccount.php?tab=bio">edit</a>';
}
?>
	</div>
</div>


<div id="content_links" class="AGENCYfillcontent">
	<br /><b>LINKS:</b><br />
	<div style="border:1px solid black; width:400px; margin:20px; padding:10px">
<?php
	if(agency_privacy($profileid, 'links') || (agency_account_type() == 'client' && is_active())) {
		$sql = "SELECT * FROM agency_profile_links WHERE user_id='$profileid' AND link !='' AND link IS NOT NULL";
		$result = mysql_query($sql);
		while ($row = sql_fetchrow($result)) {
			$link = $row['link'];
			$link_desc = $row['link_desc'];
			echo '<div class="AGENCYleftlink"><a target="_blank" href="http://' . $link . '">' . $link . '</a>';
			if(!empty($link_desc)) {
				echo ' - ' . $link_desc;
			}
			echo '</div><br />';
		}
		if($_SESSION['user_id'] == $profileid) { // if this is the profile of the logged in user
			echo '<br><br><a class="AGENCY_graybutton" href="myaccount.php?tab=links">edit</a>';
		}
	} else {
		echo 'This user has set their links to private.';
	}
?>
	</div>
</div>

<div id="content_skills" class="AGENCYfillcontent">
	<br /><b>SKILLS:</b><br />
	<div style="border:1px solid black; width:400px; min-height:300px; margin:20px; padding:10px">
<?php
if(agency_privacy($profileid, 'skills') || (agency_account_type() == 'client' && is_active())) {
	if(!empty($userinfo['skills_language'])) {
		echo 'Languages: <b>' . nl2br($userinfo['skills_language']) . '</b><br /><br />';
	}
	if(!empty($userinfo['skills_sports_music'])) {
		echo 'Sports & Music: <b>' . nl2br($userinfo['skills_sports_music']) . '</b><br /><br />';
	}
	if(!empty($userinfo['skills_other'])) {
		echo 'Other: <b>' . nl2br($userinfo['skills_other']) . '</b>';
	}
	// echo nl2br($userinfoskills']);
	if($_SESSION['user_id'] == $profileid) { // if this is the profile of the logged in user
		echo '<br><br><a class="AGENCY_graybutton" href="myaccount.php?tab=experience">edit</a>';
	}
} else {
	echo 'This user has set their skills to private.';
}
?>
	</div>
</div>

<div id="content_resume" class="AGENCYfillcontent">
	<br /><b>RESUME:</b><br />
	<div id="resume_main" style="border:1px solid black; width:400px; min-height:300px; margin:20px; padding:10px">
<?php
if($_SESSION['user_id'] == $profileid) { // if this is the profile of the logged in user
	echo '<div align="center">Click <a class="AGENCY_graybutton" href="myaccount.php?tab=bio#resumeanchor">EDIT</a> to type in your resume for easy viewing';
	echo '<br /><br />OR use the form below to upload a document</div>';
}
?>
<hr>
<div align="center">
<?php
if((agency_account_type() == 'client' && is_active()) || $loggedin == $profileid) {
	if(!empty($userinfo['resume'])) {
		if(file_exists($folder . '/' . $userinfo['resume'])) {
			if($loggedin == $profileid) {
				echo '<b>YOU CURRENTLY HAVE A RESUME UPLOADED</b><br /><br />';
			}		
			echo '<a href="' . $folder . $userinfo['resume'] . '" target="_blank" style="color:#333333"><b>CLICK HERE TO DOWNLOAD/PRINT UPLOADED RESUME FILE</b></a><br /><a href="' . $folder . $userinfo['resume'] . '" target="_blank" style="text-decoration:none"><img src="images/resume1.gif" border="0" style="padding-top:5px;"></a>';
			
			if($loggedin == $profileid) {
				echo '<br /><br /><a href="profile.php?u=' . $loggedin . '&delfile=resume">[delete resume file]</a>';
			}				
			
		}
	} else {
		echo 'no resume on file';
	}
} else {
	echo 'As a member of our Talent pool, you have the option to show your resume to thousands of Clients.  For privacy reasons your resume will only be available to our screened Clients and not to other members or the general public.';
}

if($_SESSION['user_id'] == $profileid) { // if this is the profile of the logged in user
	echo '<div style="padding:30px 0">
		<form enctype="multipart/form-data" action="profile.php?u=' . $profileid . '" method="post">
			<input type="hidden" name="MAX_FILE_SIZE" value="5000000" />
			<input type="file" name="resumefile" /><br /><br />
			<input type="submit" name="submit" value="Upload New Resume (<5MB)" />
		</form>
		<br /><font color="gray">*for better security, do not use "resume"<br />or your name for the resume filename</font>
		</div>';
}
?>	</div>
	</div>
</div>

<div id="content_headshot" class="AGENCYfillcontent">
	<br /><b>HEADSHOT:</b><br />
	<div style="border:1px solid black; width:400px; min-height:300px; margin:20px; padding:10px" align="center">
<?php
if($_SESSION['user_id'] == $profileid || (agency_account_type() == 'client' && is_active())) {
	if(!empty($userinfo['headshot'])) {
		if(file_exists($folder . '/' . $userinfo['headshot'])) {
			echo '<a href="' . $folder . $userinfo['headshot'] . '" target="_blank"><b>click here to view my headshot</b></a>';
		} else {
			echo 'headshot not found';
		}
	} else {
		echo 'no headshot on file';
	}
	
	if($_SESSION['user_id'] == $profileid) { // if this is the profile of the logged in user
		echo '<div style="padding:50px 0">
			<form enctype="multipart/form-data" action="profile.php?u=' . $profileid . '" method="post">
				<input type="hidden" name="MAX_FILE_SIZE" value="5000000" />
				<input type="file" name="headshotfile" /><br /><br />
				<input type="submit" name="submit" value="Upload New Headshot (<5MB)" />
			</form>
			</div>';
	}
} else {
	echo 'As a member of our Talent pool, you have the option to show your headshot to thousands of Clients.  For privacy reasons, because often headshots contain personal information some people do not wish to make public, your headshot will only be available to our screened Clients and not to other members or the general public.';
}
?>
	</div>
</div>

<div id="profile_mylink" class="AGENCYfillcontent">
<br /><br /><b>Copy and paste the link in the box below into your email:</b><br /><br /><br /><br />
<textarea cols="54" rows="2" onClick="this.select()">http://www.theagencyonline.com/profile.php?=<?php echo $profileid; ?></textarea>
</div>