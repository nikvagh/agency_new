<?php
session_start();

include('../includes/mysql_connect.php');
include('../includes/vars.php');
include('../forms/definitions.php');
include('../includes/agency_functions.php');

if(is_active() && !empty($_GET['u'])) { // check if user is logged in
	$profileid= escape_data($_GET['u']);
	$loggedin = $_SESSION['user_id'];
	// first get the folder name
	$sql = "SELECT * FROM agency_profiles WHERE user_id='$profileid'";
	$result=mysql_query($sql);
	if($userinfo = sql_fetchrow($result)) {  // "$userinfo" array will be available through file, so no need to access database again
		$folder = 'talentphotos/' . $profileid. '_' . $userinfo['registration_date'] . '/';
		$profilecode = $userinfo['registration_date'];
	}
	
	if(isset($profileid) && isset($folder)) { // if folder (reg date) is not found, no images will be found, something is wrong so don't display
	
		echo '<div class="AGENCYBlue" style="font-size:medium; font-weight:bold; width:90px; float:left; padding-left:10px">CompCard';
		echo '</div>';


		echo '<div class="AGENCYBlue" style="font-size:small; font-weight:bold; width:170px; float:left"><a href="profile.php?u=' . $profileid . '';
		if(isset($_GET['lightbox'])) {
			echo '&lightbox=' . (int)$_GET['lightbox'];
		}
		echo '" style="text-decoration:none">view profile page</a></div>';
		
		
		if($loggedin) {
			echo '<div class="AGENCYBlue" style="font-size:small; font-weight:bold; width:84px; float:left"><a href="mailto:?subject=Check out this talent at The Agency&body=I\'ve found someone that I would like you to check out.  Here\'s the link:%0A%0Ahttp://www.theagencyonline.com/profile.php%3Ftab=Photos%26u=' . $profileid. '" style="text-decoration:none">Email</a></div>';
			echo '<div class="AGENCYBlue" style="font-size:small; font-weight:bold; width:70px; float:left"><a href="javascript:void(0)" onclick="document.getElementById(\'compcard_options\').style.display=\'\'" style="text-decoration:none">Print/Save</a>
					<div id="compcard_options" style="display:none; background-color:white; padding:0px; position:absolute; border:1px solid gray">
						<div align="right" style="padding-right:4px"><a href="javascript:void(0)" onclick="document.getElementById(\'compcard_options\').style.display=\'none\'" style="text-decoration:none">x</a></div>
						<div style="padding: 0 10px 10px 10px">
							<a href="pdf_compcard.php?u=' . $profileid. '" style="text-decoration:none" target="_blank">PDF</a><br />
							<a href="compcard_image.php?u=' . $profileid. '" style="text-decoration:none" target="_blank">JPEG</a>
						</div>
					</div>
				  </div>';
		} else {
			echo '<div class="AGENCYBlue" style="font-size:small; font-weight:bold; width:84px; float:left"><a href="javascript:void(0)" onClick="alert(\'Please log in to enjoy this feature.\')"  style="text-decoration:none">Email</a></div>';
			echo '<div class="AGENCYBlue" style="font-size:small; font-weight:bold; width:70px; float:left"><a href="javascript:void(0)" onClick="alert(\'Please log in to enjoy this feature.\')"  style="text-decoration:none">Print/Save</a></div>';
		}

		echo '<br clear="all" />';
		echo '<table border="0" cellspacing="10" cellpadding="0" style="clear:both"><tr>';
	    if(isset($folder)) {
			 $sql = "SELECT * FROM agency_photos WHERE user_id='$profileid' AND card_position IS NOT NULL ORDER BY card_position ASC";
			 $result=mysql_query($sql);
			 $current = 1;

			 while(($row = sql_fetchrow($result)) && $current <= 4) {
			 	 $pos = $row['card_position'];
			 	 while($current < $pos) {
		 			switch($current) {
						case 1:
							echo '<td rowspan="2" style="width:200px" valign="top"><div id="primaryspot" style="display:none; width:200px"></div></td>';
							break;
						case 2:
							echo '<td>&nbsp;</td>';
							break;
						case 3:
							echo '<td>&nbsp;</td></tr>';
							break;
						case 4:
							echo '<tr><td>&nbsp;</td>';
							break;
					}
					$current++;
				 }

		 		 switch($current) {
					case 1:
						echo '<td rowspan="2" valign="top"><div id="primaryspot" style="display:none; width:200px"></div><img id="primarypic" src="' . $folder . $row['filename'] . '" width="200" /></td>';
						break;
					case 2:
						echo '<td valign="top"><img src="' . $folder . $row['filename'] . '" width="100" onmouseover="cardimageswap(\'' . $folder . $row['filename'] . '\')" onmouseout="cardimageswapout()" /></td>';
						break;
					case 3:
						echo '<td valign="top"><img src="' . $folder . $row['filename'] . '" width="100" onmouseover="cardimageswap(\'' . $folder . $row['filename'] . '\')" onmouseout="cardimageswapout()" /></td></tr>';
						break;
					case 4:
						echo '<tr><td valign="top"><img src="' . $folder . $row['filename'] . '" width="100" onmouseover="cardimageswap(\'' . $folder . $row['filename'] . '\')" onmouseout="cardimageswapout()" /></td>';
						break;
				}
				$current++;
			 }
		}
		echo '<td valign="top"><table>
			<tr><td class="AGENCYCompCardLabel">Height: </td><td class="AGENCYCompCardStat">' . floor($userinfo['height']/12) . '\' ' . $userinfo['height'] % 12 . '"</td></tr>
			<tr><td class="AGENCYCompCardLabel">Waist: </td><td class="AGENCYCompCardStat">' . escape_data($userinfo['waist']) . '"</td></tr>';

		if($userinfo['gender'] != 'M') { // if female or "other"
		echo '<tr><td class="AGENCYCompCardLabel">Bust: </td><td class="AGENCYCompCardStat">' . escape_data($userinfo['bust']) . '"</td></tr>
			<tr><td class="AGENCYCompCardLabel">Cup Size: </td><td class="AGENCYCompCardStat">' . escape_data($bracups[$userinfo['cup']]) . '</td></tr>
			<tr><td class="AGENCYCompCardLabel">Hips: </td><td class="AGENCYCompCardStat">' . escape_data($userinfo['hips']) . '"</td></tr>';
		}

		if($userinfo['gender'] != 'F') { // if male or "other"
		echo '<tr><td class="AGENCYCompCardLabel">Suit: </td><td class="AGENCYCompCardStat">' . agency_print_suit(escape_data($userinfo['suit'])) . '</td></tr>
			<tr><td class="AGENCYCompCardLabel">Neck: </td><td class="AGENCYCompCardStat">' . escape_data($userinfo['neck']) . '"</td></tr>
			<tr><td class="AGENCYCompCardLabel">Inseam: </td><td class="AGENCYCompCardStat">' . escape_data($userinfo['inseam']) . '"</td></tr>';
		}


		echo '<tr><td class="AGENCYCompCardLabel">Shoe: </td><td class="AGENCYCompCardStat">' . escape_data($userinfo['shoe']) . '</td></tr>
			<tr><td class="AGENCYCompCardLabel">Hair: </td><td class="AGENCYCompCardStat">' . $userinfo['hair'] . '</td></tr>
			<tr><td class="AGENCYCompCardLabel">Eyes: </td><td class="AGENCYCompCardStat">' . escape_data($userinfo['eyes']) . '</td></tr>';

		 $sql = "SELECT * FROM agency_profile_unions WHERE user_id='$profileid'";
		 $result=mysql_query($sql);
		 $num_results = mysql_num_rows($result);
		 $current = 1;
		 if($num_results) {
		 	echo '<tr><td class="AGENCYCompCardLabel">Union(s): </td><td class="AGENCYCompCardStat">';
			while($row = sql_fetchrow($result)) {
		   		echo escape_data($row['union_name']);
		   		if($current < $num_results) echo ', ';
		   		$current++;
			}
			echo '</td></tr>';
		 }

		echo '</table></td></tr></table>';
	} else {
		echo "Could not retrieve information on this member.";
	}

} else {
	echo "You don't have access to this page.";
}


mysql_close(); // Close the database connection.
?>
