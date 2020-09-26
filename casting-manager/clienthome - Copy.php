<style>
    table {
    clear: both;
    width: 32% !important; 
    border-radius: 3px;
    border-collapse: collapse;
    color: @calendar-color;
    margin: 50px 0px 0px 25% !important;
}
</style>
<?php

// TESTING
/* 
if(is_admin()) {
	echo '<div id="debug" style="position:fixed; left:0; top:20px; overflow:auto; width:1200px; height:50px; color: white; background-color:black; padding:10px;">' . $_COOKIE['lightbox'] . '</div>';
} */
@include('sidebar.php');


define("PERPAGE", 30);// how many results to post per page

require '../includes/PageNavigator.php';
//max per page

define("OFFSET", "offset");
//get query string
$offset=@escape_data((int)$_GET[OFFSET]);

//check variable
if (!isset($offset)){
	$recordoffset=0;
} else {
	//calc record offset
	$recordoffset=$offset*PERPAGE;
}

	if (isset($_COOKIE['agencysearch']) && (isset($_GET['reset']) || isset($_POST['submitsearch']))) {
		unset($_SESSION['currentsearch']);
		unset($_SESSION['countsearch']);
		foreach ($_COOKIE['agencysearch'] as $name => $value) {
	        setcookie("agencysearch[$name]", "");
			if(is_array($_COOKIE['agencysearch'][$name])) {
				foreach ($_COOKIE['agencysearch'][$name] as $n => $v) {
					setcookie("agencysearch[$name][$n]", "");
				}
			}
	    }
		if(isset($_GET['reset'])) {
			$url = $_SERVER['REQUEST_URI'];
			ob_end_clean(); // Delete the buffer.
			header("Location: $url");
			exit(); // Quit the script.
		}
	}

   	if (isset($_POST['submitsearch'])) { // Handle the form.
   	//	if(agency_account_type() == 'client' && is_active()) { // check if user is logged in and approved
   			$sql_start = "SELECT DISTINCT p.user_id, p.registration_date, p.firstname, p.lastname, p.experience, p.phone, p.resume, p.resume_text";

			$sql = " FROM agency_profiles AS p, forum_users AS u";
			if(!empty($_POST['category'])) {
				$sql .= ", agency_profile_categories AS c";
			}
			if(!empty($_POST['unions'][0])) {
				$sql .= ", agency_profile_unions AS un";
			}
			if(!empty($_POST['ethnicity'][0])) {
				$sql .= ", agency_profile_ethnicities AS eth";
			}			

			$sql .= " WHERE p.user_id=u.user_id AND ";

			if(!empty($_POST['category'])) {
				$sql .= "c.user_id=p.user_id AND ";
			}
			if(!empty($_POST['unions'][0])) {
				$sql .= "un.user_id=p.user_id AND ";
			}
			if(!empty($_POST['ethnicity'][0])) {
				$sql .= "eth.user_id=p.user_id AND ";
			}			

   			function addsql($var1, $var2=false) {
				global $malearray;
				global $femalearray;
				$sql = '';
				$run = true;

				if(in_array($var1, $femalearray) || in_array($var1, $malearray)) {
					if(isset($_POST['gender'])) {
						if($_POST['gender'] == 'M') {
							if(!in_array($var1, $malearray)) {
								$run = false;
							}
						}
						if($_POST['gender'] == 'F') {
							if(!in_array($var1, $femalearray)) {
								$run = false;
							}
						}
					}
				}

				if($run) {
					$value1 = escape_data($_POST[$var1]);
					$value2 = escape_data($_POST[$var2]);
					if(!empty($value1) && !empty($value2)) {
						if($value2 >= $value1) {
							$sql = "p.$var1 >= '$value1' AND p.$var1 <= '$value2' AND ";
							setcookie("agencysearch[$var1]", $value1);
							setcookie("agencysearch[$var2]", $value2);
						}
					} else 	if(!empty($value1) && empty($value2)) {
						$sql = "p.$var1 = '$value1' AND ";
						setcookie("agencysearch[$var1]", $value1);
					}
				}
				return $sql;
			}

			if(!empty($_POST['firstname'])) {
   				$firstname = $_POST['firstname'];
   				setcookie("agencysearch[firstname]", $_POST['firstname']);
   				$var = mysql_real_escape_string($firstname);
   				$sql .= "p.firstname LIKE '$var%' AND ";
			}
			
			if(!empty($_POST['lastname'])) {
   				$lastname = $_POST['lastname'];
   				setcookie("agencysearch[lastname]", $_POST['lastname']);
   				$var = mysql_real_escape_string($lastname);
   				$sql .= "p.lastname LIKE '$var%' AND ";
			}
			
			if(!empty($_POST['location'])) {
   				$location = $_POST['location'];
   				setcookie("agencysearch[location]", $_POST['location']);
   				$var = mysql_real_escape_string($location);
   				$sql .= "p.location='$var' AND ";
			}
			
			// $sql .= addsql('firstname');
			// $sql .= addsql('lastname');
			$sql .= addsql('gender');
			$sql .= addsql('weight', 'weight2');
			$sql .= addsql('waist', 'waist2');
			// $sql .= addsql('hair');
			// $sql .= addsql('eyes');
			$sql .= addsql('shoe', 'shoe2');

			 $sql .= addsql('shirt', 'shirt2');
			 $sql .= addsql('neck', 'neck2');
			 $sql .= addsql('sleeve', 'sleeve2');
			 $sql .= addsql('inseam', 'inseam2');
			 $sql .= addsql('bust', 'bust2');
			 $sql .= addsql('cup', 'cup2');
			 $sql .= addsql('hips', 'hips2');
			 $sql .= addsql('dress', 'dress2');

			 $sql .= addsql('experience');
			 // $sql .= addsql('ethnicity');
			 $sql .= addsql('country');
			
			/* if(!empty($_POST['state'])) {
				 $sql .= addsql('state');
			 }
			if(!empty($_POST['city'])) {
				$sql .= addsql('city');
			} */


			if(!empty($_POST['zipcode']) && !empty($_POST['miles'])) {
				include('../includes/zipsearch/radius-search.php');
				
				if(empty($ziparray)) {
					// zip code is not found, then search for only zip code which will show no results
					$sql .= "p.zip = '$zipcode' AND ";
				} else {
					$sql .= "p.zip IN ('" . implode("','", $ziparray) . "') AND ";
					setcookie("agencysearch[zipcode]", $_POST['zipcode']);
					setcookie("agencysearch[miles]", $_POST['miles']);
				}
			} else {
				if(!empty($_POST['state'])) {
					 $sql .= addsql('state');
				 }
				if(!empty($_POST['city'])) {
					$sql .= addsql('city');
				}
			}




			if(!empty($_POST['language'])) {
   				$language = $_POST['language'];
   				setcookie("agencysearch[language]", $_POST['language']);
   				$var = mysql_real_escape_string($language);
   				$sql .= "p.skills_language LIKE '%$var%' AND ";
			}
			if(!empty($_POST['sports_music'])) {
   				$sports_music = $_POST['sports_music'];
   				setcookie("agencysearch[sports_music]", $_POST['sports_music']);
   				$var = mysql_real_escape_string($sports_music);
   				$sql .= "p.skills_sports_music LIKE '%$var%' AND ";
			}
			if(!empty($_POST['skills_other'])) {
   				$skills_other = $_POST['skills_other'];
   				setcookie("agencysearch[skills_other]", $_POST['skills_other']);
   				$var = mysql_real_escape_string($skills_other);
   				$sql .= "p.skills_other LIKE '%$var%' AND ";
			}

   			if(!empty($_POST['height_feet'])) {
   				$height = $_POST['height_feet'] * 12;
   				setcookie("agencysearch[height_feet]", $_POST['height_feet']);
   				if(!empty($_POST['height_inches'])) {
   					$height += $_POST['height_inches'];
   					setcookie("agencysearch[height_inches2]", $_POST['height_inches2']);
				}
   				$var = (int) mysql_real_escape_string($height);
   				$sql .= "p.height >='$var' AND ";
			}
   			if(!empty($_POST['height_feet2'])) {
   				$height = $_POST['height_feet2'] * 12;
   				setcookie("agencysearch[height_feet2]", $_POST['height_feet2']);
   				if(!empty($_POST['height_inches2'])) {
   					$height += $_POST['height_inches2'];
   					setcookie("agencysearch[height_inches]", $_POST['height_inches']);
				}
   				$var = (int) mysql_real_escape_string($height);
   				$sql .= "p.height <='$var' AND ";
			}

			if($_POST['gender'] != 'F') {
				if(!empty($_POST['suit']) && !empty($_POST['suitvariation'])) {
					$suit = $_POST['suit'] + $_POST['suitvariation'];
					// echo $suit;
					setcookie("agencysearch[suit]", $_POST['suit']);
					setcookie("agencysearch[suitvariation]", $_POST['suitvariation']);
					$var = mysql_real_escape_string($suit);
					$sql .= "p.suit >='$var' AND ";
				}
				if(!empty($_POST['suit2']) && !empty($_POST['suitvariation2'])) {
					$suit = $_POST['suit2'] + $_POST['suitvariation2'];
					setcookie("agencysearch[suit2]", $_POST['suit2']);
					setcookie("agencysearch[suitvariation2]", $_POST['suitvariation2']);
					$var = mysql_real_escape_string($suit);
					$sql .= "p.suit <='$var' AND ";
				}
			}

			$today = date('m-d');
			$thisyear = date('Y');
   			if(!empty($_POST['age'])) {
   				$age = $_POST['age'];
   				setcookie("agencysearch[age]", $_POST['age']);
   				$var = (int) mysql_real_escape_string($age);
				$var = ($thisyear - $var) . '-' . $today;
   				$sql .= "p.birthdate <='$var' AND ";
			}
   			if(!empty($_POST['age2'])) {
   				$age2 = $_POST['age2'];
   				setcookie("agencysearch[age2]", $_POST['age2']);
   				$var = (int) mysql_real_escape_string($age2);
				$var = ($thisyear - $var) . '-' . $today;
   				$sql .= "p.birthdate >='$var' AND ";
			}

   			if(!empty($_POST['shirt']) && $_POST['gender'] != 'F') {
				$shirt = array();
   				$shirt = $_POST['shirt'];
				$num_shirts = sizeof($shirt);
				$sql .= "(";
				foreach($shirt as $value) {
					$num_shirts--;
					setcookie("agencysearch[shirt][$num_shirts]", $value);
					$value = mysql_real_escape_string($value);
					$sql .= "p.shirt ='$value'";
					if($num_shirts > 0) {
						$sql .= " OR ";
					} else {
						$sql .= ") AND ";
					}
				}
			}
			
   			if(!empty($_POST['hair'])) {
				$hair = array();
   				$hair = $_POST['hair'];
				$num_hair = sizeof($hair);
				$sql .= "(";
				foreach($hair as $value) {
					$num_hair--;
					setcookie("agencysearch[hair][$num_hair]", $value);
					$value = mysql_real_escape_string($value);
					$sql .= "p.hair ='$value'";
					if($num_hair > 0) {
						$sql .= " OR ";
					} else {
						$sql .= ") AND ";
					}
				}
			}

   			if(!empty($_POST['eyes'])) {
				$eyes = array();
   				$eyes = $_POST['eyes'];
				$num_eyes = sizeof($eyes);
				$sql .= "(";
				foreach($eyes as $value) {
					$num_eyes--;
					setcookie("agencysearch[eyes][$num_eyes]", $value);
					$value = mysql_real_escape_string($value);
					$sql .= "p.eyes ='$value'";
					if($num_eyes > 0) {
						$sql .= " OR ";
					} else {
						$sql .= ") AND ";
					}
				}
			}

   			if(!empty($_POST['category'])) {
				$category = array();
   				$category = $_POST['category'];
				$num_category = sizeof($category);
				$sql .= "(";
				foreach($category as $value) {
					$num_category--;
					setcookie("agencysearch[category][$num_category]", $value);
					$value = mysql_real_escape_string($value);
					$sql .= "c.category ='$value'";
					if($num_category > 0) {
						$sql .= " OR ";
					} else {
						$sql .= ") AND ";
					}
				}
			}

   			if(!empty($_POST['unions'][0])) {
				$unions = array();
   				$unions = $_POST['unions'];
				$unions = array_filter($unions);
				$num_unions = sizeof($unions);
				if($num_unions > 0) {
					$sql .= "(";
					foreach($unions as $value) {
						$num_unions--;
						setcookie("agencysearch[unions][$num_unions]", $value);
						$value = mysql_real_escape_string($value);
						$sql .= "un.union_name ='$value'";
						if($num_unions > 0) {
							$sql .= " OR ";
						} else {
							$sql .= ") AND ";
						}
					}
				}
			}
			
   			if(!empty($_POST['ethnicity'][0])) {
				$ethnicity = array();
   				$ethnicity = $_POST['ethnicity'];
				$ethnicity = array_filter($ethnicity);
				$num_eth = sizeof($ethnicity);
				if($num_eth > 0) {
					$sql .= "(";
					foreach($ethnicity as $value) {
						$num_eth--;
						setcookie("agencysearch[ethnicity][$num_eth]", $value);
						$value = mysql_real_escape_string($value);
						$sql .= "eth.ethnicity ='$value'";
						if($num_eth > 0) {
							$sql .= " OR ";
						} else {
							$sql .= ") AND ";
						}
					}
				}
			}				
		
	}
	echo ' <div id="page-wrapper">
        <div class="container-fluid">
            <!-- Page Heading -->
   <div class="row well" id="main" style="padding: 19px 0 !important;">
   <div class="col-sm-12 col-md-12 " id="content"><div style="margin-top:-40px; margin-left:170px; width:500px; position:absolute">';
	if(isset($sql) && isset($sql_start)) {

		$sql .= "p.account_type='talent' AND u.user_type='0'";

		$searchquery = $sql_start . $sql . " ORDER BY p.payProcessedDate DESC, p.user_id DESC LIMIT 20"; // for Newest Talent Matches
		
		//echo $searchquery;
		
		$sql_count = "SELECT COUNT(*)" . $sql; // this is to find the total number of results

		$sql = $sql_start . $sql; // add first part for normal query
		$_SESSION['currentsearch'] = $sql; // save this part of query for pagination
		$_SESSION['countsearch'] = $sql_count;
		
		$sqlsort = 'p.firstname ASC, p.user_id ASC'; // sets default sorting order
		$sortlinks = '<a href="clienthome.php?mode=search&offset=0&reverse=true" class="AGENCY_graybutton" style="font-size:12px; font-weight:bold" onclick="remind=false">A-Z:Z-A</a>&nbsp;&nbsp;&nbsp;<a href="clienthome.php?mode=search&sort=date&offset=0" class="AGENCY_graybutton" style="font-size:12px;" onclick="remind=false">Newest-Oldest:Oldest-Newest</a>&nbsp;&nbsp;&nbsp;<a href="clienthome.php?mode=search&sort=exp&offset=0" class="AGENCY_graybutton" style="font-size:12px" onclick="remind=false">Pro-NewFaces:NewFaces-Pro</a>';

		if(!empty($_GET['sort'])) {
			if($_GET['sort'] == 'date') {
				$sqlsort = 'p.payProcessedDate DESC, p.user_id DESC';
				$sortlinks = '<a href="clienthome.php?mode=search&offset=0" class="AGENCY_graybutton" style="font-size:12px" onclick="remind=false">A-Z:Z-A</a>&nbsp;&nbsp;&nbsp;<a href="clienthome.php?mode=search&sort=date&offset=0' . (!isset($_GET['reverse']) ? '&reverse=true' : '') . '" class="AGENCY_graybutton" style="font-size:12px; font-weight:bold" onclick="remind=false">Newest-Oldest:Oldest-Newest</a>&nbsp;&nbsp;&nbsp;<a href="clienthome.php?mode=search&sort=exp&offset=0" class="AGENCY_graybutton" style="font-size:12px" onclick="remind=false">Pro-NewFaces:NewFaces-Pro</a>';
			}
		}
		$sql .= " ORDER BY $sqlsort LIMIT $recordoffset,". PERPAGE;
		echo $sortlinks;
	} else if(isset($_SESSION['currentsearch']) && isset($_SESSION['countsearch']) && isset($_GET['offset'])) {
		if(!empty($_GET['reverse'])) {
			$orderby = "p.firstname DESC, p.user_id DESC";
		} else {
			$orderby = "p.firstname ASC, p.user_id ASC";
		}		
		// echo 'Sort by: ';
		if(!empty($_GET['sort'])) {
			if($_GET['sort'] == 'date') {
				if(!empty($_GET['reverse'])) {
					$orderby = "p.payProcessedDate ASC";
				} else {
					$orderby = "p.payProcessedDate DESC";
				}
				echo '<a href="clienthome.php?mode=search&offset=0" class="AGENCY_graybutton" style="font-size:12px" onclick="remind=false">A-Z:Z-A</a>&nbsp;&nbsp;&nbsp;<a href="clienthome.php?mode=search&sort=date&offset=0' . (!isset($_GET['reverse']) ? '&reverse=true' : '') . '" class="AGENCY_graybutton" style="font-size:12px; font-weight:bold" onclick="remind=false">Newest-Oldest:Oldest-Newest</a>&nbsp;&nbsp;&nbsp;<a href="clienthome.php?mode=search&sort=exp&offset=0" class="AGENCY_graybutton" style="font-size:12px" onclick="remind=false">Pro-NewFaces:NewFaces-Pro</a>';
			}
			if($_GET['sort'] == 'exp') {
				if(!empty($_GET['reverse'])) {
					$orderby = "p.experience ASC";
				} else {
					$orderby = "p.experience DESC";
				}
				echo '<a href="clienthome.php?mode=search&offset=0" class="AGENCY_graybutton" style="font-size:12px" onclick="remind=false">A-Z:Z-A</a>&nbsp;&nbsp;&nbsp;<a href="clienthome.php?mode=search&sort=date&offset=0" class="AGENCY_graybutton" style="font-size:12px" onclick="remind=false">Newest-Oldest:Oldest-Newest</a>&nbsp;&nbsp;&nbsp;<a href="clienthome.php?mode=search&sort=exp&offset=0' . (!isset($_GET['reverse']) ? '&reverse=true' : '') . '" class="AGENCY_graybutton" style="font-size:12px; font-weight:bold" onclick="remind=false">Pro-NewFaces:NewFaces-Pro</a>';
			}
		} else {
			echo '<a href="clienthome.php?mode=search&offset=0' . (!isset($_GET['reverse']) ? '&reverse=true' : '') . '" class="AGENCY_graybutton" style="font-size:12px; font-weight:bold" onclick="remind=false">A-Z:Z-A</a>&nbsp;&nbsp;&nbsp;<a href="clienthome.php?mode=search&sort=date&offset=0" class="AGENCY_graybutton" style="font-size:12px" onclick="remind=false">Newest-Oldest:Oldest-Newest</a>&nbsp;&nbsp;&nbsp;<a href="clienthome.php?mode=search&sort=exp&offset=0" class="AGENCY_graybutton" style="font-size:12px" onclick="remind=false">Pro-NewFaces:NewFaces-Pro</a>';
		}
		
		$sql = $_SESSION['currentsearch'] . " ORDER BY $orderby LIMIT $recordoffset,". PERPAGE;;
		$sql_count = $_SESSION['countsearch'];
	}
	echo '</div>';


	if(isset($sql) && isset($sql_count)) {
		// echo 'Testing: ' . $sql;
		/*echo '<a href="ajax/lightbox_add.php?height=350&amp;width=300&amp;inlineId=hiddenModalContent" class="thickbox AGENCY_graybutton" style="float:right">add checked to lightbox</a>';
		echo '<a href="javascript:void(0)" onclick="uncheckAll(); uncheckAll(\'addme\'); remind=true;" class="AGENCY_graybutton" style="float:right; margin-right:10px">uncheck all</a>';
		echo '<a href="javascript:void(0)" onclick="checkAll(); checkAll(\'addme\'); remind=true;" class="AGENCY_graybutton" style="float:right; margin-right:10px">check all</a>';
		echo '<br clear="all" /><br clear="all" />';
		echo '<div class="AGENCYresultlist">';*/
		echo '<br>';
		echo '<br>';
		echo '<br>';
		echo '<br>';

		// get lightbox cookie and turn it into an array
		if(!empty($_COOKIE['lightbox'])) {
			$lightboxcookie = array();
			$lightboxcookie = explode(',', $_COOKIE['lightbox']);
		}
			
		
		$result=mysql_query($sql);
		if(mysql_num_rows($result) == 0) {
			echo 'no results with the given search parameters<br /><br />';
		} else if(isset($_GET['configure'])) {
			// SAVE QUERY FOR "NEWEST TALENT MATCHES
			mysql_query("DELETE FROM agency_search_matches WHERE user_id='$profileid'");
			$searchquery = addslashes($searchquery);
			mysql_query("INSERT INTO agency_search_matches (user_id, searchquery) VALUES ('$profileid', '$searchquery')");
			if(mysql_affected_rows() == 1) {
				// saved successfully
				echo '<div class="AGENCYsubmitmessage" style="border:0;">Congratulations!  Your Newest Talent Matches have been set!<br /><br />As new members fit your search parameters, they will appear in you Newest Talent Matches box on the right.</div>';
			}
		}
		echo '<form action="javascript:void(0)" method="post" name="resultform">';
		echo '<table width="100%" cellpadding="10">';
		$current = 1;
		$number_across = 3;
		$varemail="bookings@theagencyonline.com";
		$varphone="212-944-0801";
				
		while($row = sql_fetchrow($result)) {
			if($current == 1) {
				echo '<tr>';
			}			
			echo '<td align="center" valign="top">';
			$uid = $row['user_id'];
			$displayname = $row['firstname'];
			if(agency_privacy($uid, 'lastname')) {
				$displayname .= ' ' . $row['lastname'];
			}
			$displayname = '<span style="color:' . $experiencecolors[$row['experience']] . '">' . $displayname . '</span>';
			$posterfolder = 'images/' . $uid . '_' . $row['registration_date'] . '.jpg';//print_r($posterfolder);die();
			$email = mysql_result(mysql_query("SELECT user_email FROM forum_users WHERE user_id='$uid'"), 0, 'user_email');
			echo '<img src="';
				if(file_exists($posterfolder . 'avatar.jpg')) {
					echo   $posterfolder;
				} else if(file_exists($posterfolder . 'avatar.gif')) {
					echo   $posterfolder . 'avatar.gif';
				} else {
					echo $posterfolder;
				}
			
			// check to see if this user should already be checked
			$checkme = '';
			if(isset($lightboxcookie)) {
				if(in_array($uid, $lightboxcookie)) {
					$checkme = 'checked ';
				}
			}
			echo '" /><br />' .
				'<input type="checkbox" id="addme' . $uid . '" name="addme' . $uid . '" onclick="remind=true; lightbox_check(\'lightbox\', this, \'' . $uid . '\');" ' . $checkme . '/><b>' .
				$displayname . '</b><br /><img src="images/' . $experienceimages[$row['experience']] . '.gif" onmouseout="document.getElementById(\'experience_popup\').style.display=\'none\'" onmouseover="document.getElementById(\'experience_popup\').style.display=\'\'"><br /><br />' .
				'<a href="./ajax/compcard_mini.php?u=' . $uid . '&height=400&amp;width=450" class="thickbox"></a><br />' .
				'<a href="mailto:' . $varemail . '">' . $varemail . '</a>';
			if(!empty($row['phone']) && agency_privacy($uid, 'phone')) {
				echo '<br />' . $varphone;
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
					if($resumeicon) {
						echo '&nbsp;&nbsp;';
					} else {
						echo '<br />';
					}
					echo '<a target="_blank" href="profile.php?tab=Reel/VO&u=' . $uid . '"><img src="images/reelVO.gif" border="0" style="padding-top:5px;" ></a>';
			}			
				
			
				
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
			 
			
			echo '</td>';	
			if($current == $number_across) {
				echo '</tr>';
				$current = 1;
			} else {
				$current++;
			}
					
							 
			// echo '</div><br clear="all" /><hr style="clear:both">';
		}
		echo '</table></form><br clear="all" />';
		
		$totalrecords = @mysql_result(mysql_query($sql_count), 0);

		$numpages = ceil($totalrecords/PERPAGE);
		$otherparameter = '&mode=search';
		//create category parameter
		if(!empty($_GET['reverse'])) {
			$otherparameter .= '&reverse=true';
		}
		if(!empty($_GET['sort'])) {
			$otherparameter .= '&sort=' . escape_data($_GET['sort']);
		}
		$otherparameter .= '" onclick="remind=false;';
		//create if needed
		if($numpages>1){
		  //create navigator
		  $nav = new PageNavigator($pagename, $totalrecords, PERPAGE,
			$recordoffset, 20, $otherparameter);
		  echo $nav->getNavigator();
		}

		 echo '<br /><a href="clienthome.php?mode=search" class="AGENCY_graybutton" style="margin: 0 0 0 20%;">new search</a>';
		 if(isset($_GET['configure'])) {
			echo ' or <a href="clienthome.php?mode=search&configure=true" class="AGENCY_graybutton">reconfigure</a>';
		}

		echo '<script type="text/javascript">
				var ProcessArray = new Array();
				if(checkCookie(\'lightbox\')) { // if cookie already set, reminder is active
					var remind = true;
				} else {
					var remind = false;
				}
				window.onbeforeunload = exitCheck;
				
				// this is causing problems in some browsers where when you close the ThickBox window it thinks the page is being exited and kills the cookie.  Bad.
				// window.onunload = exitCheck2;
				function exitCheck(evt){
					if(remind) {
						return "If you have checked off talent which have not yet been added to a lightbox, you may wish to do so at this point.";
						// deleteCookie(\'lightbox\');
					}
				}
				
				/* function exitCheck2(evt){
					if(remind) {
						deleteCookie(\'lightbox\');
					}
				} */
				
				</script>';
		/*echo '<a href="ajax/lightbox_add.php?height=350&amp;width=300&amp;inlineId=hiddenModalContent" class="thickbox AGENCY_graybutton" style="float:right">add checked to lightbox</a>';
		echo '<a href="javascript:void(0)" onclick="uncheckAll(); uncheckAll(\'addme\'); remind=true;" class="AGENCY_graybutton" style="float:right; margin-right:10px">uncheck all</a>';
		echo '<a href="javascript:void(0)" onclick="checkAll(); checkAll(\'addme\'); remind=true;" class="AGENCY_graybutton" style="float:right; margin-right:10px">check all</a>';
		echo '<br clear="all" /></div>';*/

	} else {
		unset($_SESSION['currentsearch']);
		unset($_SESSION['countsearch']);

		$firstname = $_COOKIE['agencysearch']['firstname'];
		$lastname = $_COOKIE['agencysearch']['lastname'];
		$gender = $_COOKIE['agencysearch']['gender'];
		$agegroup = $_COOKIE['agencysearch']['agegroup'];
		$height_feet = $_COOKIE['agencysearch']['height_feet'];
		$height_inches = $_COOKIE['agencysearch']['height_inches'];
		$weight = $_COOKIE['agencysearch']['weight'];
		$waist = $_COOKIE['agencysearch']['waist'];
		$hair = $_COOKIE['agencysearch']['hair'];
		$eyes = $_COOKIE['agencysearch']['eyes'];
		$shoe = $_COOKIE['agencysearch']['shoe'];
		$age = $_COOKIE['agencysearch']['age'];

		$suit = $_COOKIE['agencysearch']['suit'];
		$suitvariation = $_COOKIE['agencysearch']['suitvariation'];
		$shirt = $_COOKIE['agencysearch']['shirt'];
		$neck = $_COOKIE['agencysearch']['neck'];
		$sleeve = $_COOKIE['agencysearch']['sleeve'];
		$inseam = $_COOKIE['agencysearch']['inseam'];
		$bust = $_COOKIE['agencysearch']['bust'];
		$cup = $_COOKIE['agencysearch']['cup'];
		$hips = $_COOKIE['agencysearch']['hips'];
		$dress = $_COOKIE['agencysearch']['dress'];

		$height_feet2 = $_COOKIE['agencysearch']['height_feet2'];
		$height_inches2 = $_COOKIE['agencysearch']['height_inches2'];
		$weight2 = $_COOKIE['agencysearch']['weight2'];
		$waist2 = $_COOKIE['agencysearch']['waist2'];
		// $hair2 = $_COOKIE['agencysearch']['hair2'];
		// $eyes2 = $_COOKIE['agencysearch']['eyes2'];
		$shoe2 = $_COOKIE['agencysearch']['shoe2'];
		$age2 = $_COOKIE['agencysearch']['age2'];

		$suit2 = $_COOKIE['agencysearch']['suit2'];
		$suitvariation2 = $_COOKIE['agencysearch']['suitvariation2'];
		$shirt2 = $_COOKIE['agencysearch']['shirt2'];
		$neck2 = $_COOKIE['agencysearch']['neck2'];
		$sleeve2 = $_COOKIE['agencysearch']['sleeve2'];
		$inseam2 = $_COOKIE['agencysearch']['inseam2'];
		$bust2 = $_COOKIE['agencysearch']['bust2'];
		$cup2 = $_COOKIE['agencysearch']['cup2'];
		$hips2 = $_COOKIE['agencysearch']['hips2'];
		$dress2 = $_COOKIE['agencysearch']['dress2'];

		$experience = $_COOKIE['agencysearch']['experience'];
		$ethnicity = $_COOKIE['agencysearch']['ethnicity'];
		$location = $_COOKIE['agencysearch']['location'];
		$country = $_COOKIE['agencysearch']['country'];
		$zipcode = $_COOKIE['agencysearch']['zipcode'];
		$miles = $_COOKIE['agencysearch']['miles'];

		$category = $_COOKIE['agencysearch']['category'];
		$unions = $_COOKIE['agencysearch']['unions'];

		$language = $_COOKIE['agencysearch']['language'];
		$sports_music = $_COOKIE['agencysearch']['sports_music'];
		$skills_other = $_COOKIE['agencysearch']['skills_other'];


		/* if(empty($gender)) {
			$gender = 'F'; // default
		} */
		
if(isset($_GET['configure'])) {
	echo '<div class="AGENCYsubmitmessage" style="border:0;">To configure your Newest Talent Matches, fill in the search parameters below and click the "Configure" button at the bottom</div>';
}

// if this is a new search, delete the cookie from the previous search
echo '<script> deleteCookie(\'lightbox\'); </script>';
?>
<form name="searchit" action="clienthome.php?mode=search" method="post" style="display:none" >
<input type="hidden" name="submitsearch">
</form>

<!--  *******************************   START SEARCH FORM   *******************************   -->
<form action="clienthome.php?mode=search<?php if(isset($_GET['configure'])) echo '&configure=true'; ?>" method="post" name="searchform">

    <div style="position:relative;top:20px;margin: 0px 0px 0px 17% !important;">
        <span class="AGENCYRed" style="font-size:12px">Enter Search Parameters (all fields optional):&nbsp;&nbsp;&nbsp;
        </span>
<?php
if(is_active()) { // check if user is logged in
?>
	<input type="button" value="BROWSE" onclick="document.searchit.submit()" />
	&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" value="<?php if(isset($_GET['configure'])) echo 'CONFIGURE'; else echo 'SEARCH'; ?>" name="submitsearch" />
<?php
if(isset($_COOKIE['agencysearch'])) {
?>
    &nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="CLEAR ALL FIELDS<?php if(isset($_GET['configure'])) echo '/CANCEL'; ?>" onclick="document.location='clienthome.php?mode=search&reset=true'" />
<?php
} else if(isset($_GET['configure'])) {
?>
    &nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="CANCEL" onclick="document.location='clienthome.php?mode=search&reset=true'" />
<?php
}
?>
	
<?php
} else {
?>
	
    <input type="button" value="Search">

<?php
}
?>
		</div>


 <table width="50%" border="0" cellpadding="3" cellspacing="2" bgcolor="white" align="left">
       <tr>
        <td class="AGENCYregtableleft">First Name:</td>
        <td class="AGENCYregtableright">
        	<input type="text" name="firstname"<?php if (!empty($firstname)) echo ' value="' . $firstname . '"'; ?> />
        </td>
 	   </tr>
      <tr>
         <td class="AGENCYregtableleft">Last Name:</td>
        <td class="AGENCYregtableright">
        	<input type="text" name="lastname"<?php if (!empty($lastname)) echo ' value="' . $lastname . '"'; ?> />
        </td>
      </tr>
      <tr>
        <td class="AGENCYregtableleft">Gender:</td>
        <td class="AGENCYregtableright">
        	<select name="gender" onchange="setgendercss(this.options[this.selectedIndex].value)">
        	<option value="" <?php if (!empty($gender)) {if($gender=='') echo 'selected="selected"';} else { echo 'selected="selected"'; } ?> > Any</option>
        	<option value="M" <?php if (!empty($gender)) { if($gender=='M') echo 'selected="selected"'; } ?> > Male</option>
        	<option value="F" <?php if (!empty($gender)) {if($gender=='F') echo 'selected="selected"';} ?> > Female</option>
        	<!-- <option value="O" <?php if (!empty($gender)) {if($gender=='O') echo 'selected="selected"';} ?> > Other</option> -->
        	</select>
        </td>
      </tr>
      
      
 	<tr>
        <td class="AGENCYregtableleft">Location:</td>
        <td class="AGENCYregtableright">
        	<select name="location">
        	<option value="">Any</option>
<?php
for($i=0; isset($locationarray[$i]); $i++) {
	echo '<option value="' . $locationarray[$i] . '"';
	if (!empty($location)) { if($locationarray[$i] == ($location)) echo ' selected="selected"'; }
	echo '>' . $locationarray[$i] . '</option>';
}
?>
        	</select>

       </td>
      </tr>
      
      
      
      
      
      
<!--
      <tr>
        <td class="AGENCYregtableleft">Location:</td>
        <td class="AGENCYregtableright">
<?php
$sql = "SELECT DISTINCT country FROM agency_cities WHERE country IS NOT NULL AND country<>'' AND country<>'United States' ORDER BY country";
$result = @mysql_query($sql);
if(mysql_num_rows($result) >= 0) {
?>
<select style="width:186px" name="country" onchange="if(this.value == 'United States') { document.getElementById('AGENCY_search_city').innerHTML=''; loaddiv('AGENCY_search_state', false, 'ajax/statelist.php?country='+this.value+'&'); } else { document.getElementById('AGENCY_search_state').innerHTML=''; loaddiv('AGENCY_search_city', false, 'ajax/citylist.php?country='+this.value+'&'); }">
		<option value=""> - Any Country - </option>
        <option value="United States"<?php if($country == 'United States') echo ' selected'; ?>>United States</option>
<?php
	while ($row = @mysql_fetch_array($result, MYSQL_ASSOC)) { // If there are projects
		echo '<option value="' . $row['country'] .'"';
		if($country == $row['country']) echo ' selected="selected"';
		echo '>' . $row['country'] . '</option>';
	}
?>
        </select><br />
<?php
} else {
?>
<input type="hidden" name="country" value="United States" />
<?php
}
?>
<span id="AGENCY_search_state"></span>
<span id="AGENCY_search_city"></span>
<?php
$endscript .= '<script type="text/javascript">
if(document.searchform.country.value == \'United States\') {
	loaddiv(\'AGENCY_search_state\', false, \'ajax/statelist.php?country=\'+document.searchform.country.value+\'&\');
	loaddiv(\'AGENCY_search_city\', false, \'ajax/citylist.php?state=' . $_COOKIE['agencysearch']['state'] . '&\', true);
} else {
	loaddiv(\'AGENCY_search_city\', false, \'ajax/citylist.php?country=\'+document.searchform.country.value+\'&\');
}
</script>';
?>
        </td>
      </tr>
-->	  
	   <tr>
        <td class="AGENCYregtableleft">Zip Search</td>
        <td class="AGENCYregtableright">
    Zip Code:
    <input type="text" name="zipcode" id="zipcode"<?php if (!empty($zipcode)) echo ' value="' . $zipcode . '"'; ?> />
</p>
  <p>

    Distance:
	<select name="miles" id="miles">
	<option value="1"<?php if (!empty($miles)) { if($miles == '1') echo ' selected'; } ?> >1 mile</option>
	<option value="5"<?php if (!empty($miles)) { if($miles == '5') echo ' selected'; } ?> >5 miles</option>
	<option value="10"<?php if (!empty($miles)) { if($miles == '10') echo ' selected'; } ?> >10 miles</option>
	<option value="25"<?php if (!empty($miles)) { if($miles == '25') echo ' selected'; } ?> >25 miles</option>
	<option value="50"<?php if (!empty($miles)) { if($miles == '50') echo ' selected'; } ?> >50 miles</option>
	<option value="100"<?php if (!empty($miles)) { if($miles == '100') echo ' selected'; } ?> >100 miles</option>
	<option value="500"<?php if (!empty($miles)) { if($miles == '500') echo ' selected'; } ?> >500 miles</option>
	<option value="1000"<?php if (!empty($miles)) { if($miles == '1000') echo ' selected'; } ?> >1000 miles</option>
	<option value="">Worldwide</option>
	</select>	  </td>
	  </tr>
	  
      <tr>
        <td class="AGENCYregtableleft">Age</td>
        <td class="AGENCYregtableright">
        <select class="thin" name="age">
        <option value="">Any</option>
		<?php
		for($i=1; $i<=120; $i++) {
			$insert = "";
			if($age == $i) {
				$insert = ' selected="selected"';
			}
			echo "<option value=\"$i\"$insert>$i</option>";
		}
		?>
		</select>
		&nbsp;&nbsp;to&nbsp;&nbsp;
        <select class="thin" name="age2">
        <option value="">Any</option>
		<?php
		for($i=1; $i<=120; $i++) {
			$insert = "";
			if($age2 == $i) {
				$insert = ' selected="selected"';
			}
			echo "<option value=\"$i\"$insert>$i</option>";
		}
		?>
		</select> years
        </td>
      </tr>

      <tr>
        <td class="AGENCYregtableleft">Height:</td>
        <td class="AGENCYregtableright">
        <select class="thin" name="height_feet">
        <option value="">Any</option>
		<?php
		for($i=1; $i<=7; $i++) {
			$insert = "";
			if($height_feet == $i) {
				$insert = ' selected="selected"';
			}
			echo "<option value=\"$i\"$insert>$i</option>";
		}
		?>
		</select> feet

		<select class="thin" name="height_inches">
		<option value="">Any</option>
		<?php
		for($i=0; $i<=11; $i++) {
			$insert = "";
			if($height_inches == $i) {
				$insert = ' selected="selected"';
			}
			echo "<option value=\"$i\"$insert>$i</option>";
		}
		?>
		</select> inches
		<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;to<br />
		 <select class="thin" name="height_feet2">
		 <option value="">Any</option>
		<?php
		for($i=1; $i<=7; $i++) {
			$insert = "";
			if($height_feet2 == $i) {
				$insert = ' selected="selected"';
			}
			echo "<option value=\"$i\"$insert>$i</option>";
		}
		?>
		</select> feet

		<select class="thin" name="height_inches2">
		<option value="">Any</option>
		<?php
		for($i=0; $i<=11; $i++) {
			$insert = "";
			if($height_inches2 == $i) {
				$insert = ' selected="selected"';
			}
			echo "<option value=\"$i\"$insert>$i</option>";
		}
		?>
		</select> inches
        </td>
      </tr>

      <tr>
        <td class="AGENCYregtableleft">Weight:</td>
        <td class="AGENCYregtableright">
        <select class="thin" name="weight">
        <option value="">Any</option>
		<?php
		for($i=10; $i<=800; $i++) {
			$insert = "";
			if($weight == $i) {
				$insert = ' selected="selected"';
			}
			echo "<option value=\"$i\"$insert>$i</option>";
		}
		?>
		</select>
		&nbsp;&nbsp;to&nbsp;&nbsp;
        <select class="thin" name="weight2">
        <option value="">Any</option>
		<?php
		for($i=10; $i<=800; $i++) {
			$insert = "";
			if($weight2 == $i) {
				$insert = ' selected="selected"';
			}
			echo "<option value=\"$i\"$insert>$i</option>";
		}
		?>
		</select> pounds

        </td>
      </tr>

      <tr>
        <td class="AGENCYregtableleft">Waist:</td>
        <td class="AGENCYregtableright">
		<select class="thin" name="waist">
		<option value="">Any</option>
		<?php
		for($i=20; $i<=60; $i++) {
			$insert = "";
			if($waist == $i) {
				$insert = ' selected="selected"';
			}
			echo "<option value=\"$i\"$insert>$i</option>";
		}
		?>
		</select>
		&nbsp;&nbsp;to&nbsp;&nbsp;
		<select class="thin" name="waist2">
		<option value="">Any</option>
		<?php
		for($i=20; $i<=60; $i++) {
			$insert = "";
			if($waist2 == $i) {
				$insert = ' selected="selected"';
			}
			echo "<option value=\"$i\"$insert>$i</option>";
		}
		?>
		</select> inches
        </td>
      </tr>

      <tr>
        <td class="AGENCYregtableleft">Shoe Size:</td>
        <td class="AGENCYregtableright">
		<select class="thin" name="shoe">
		<option value="">Any</option>
		<?php
		for($i=1; $i<=21; $i++) {
			$insert = "";
			if($shoe == $i) {
				$insert = ' selected="selected"';
			}
			echo "<option value=\"$i\"$insert>$i</option>";
		}
		?>
		</select>

		&nbsp;&nbsp;to&nbsp;&nbsp;
		<select class="thin" name="shoe2">
		<option value="">Any</option>
		<?php
		for($i=1; $i<=21; $i++) {
			$insert = "";
			if($shoe2 == $i) {
				$insert = ' selected="selected"';
			}
			echo "<option value=\"$i\"$insert>$i</option>";
		}
		?>
		</select> US sizes
        </td>
      </tr>
      <tr class="maleclass">
        <td class="AGENCYregtableleft">Suit:</td>
        <td class="AGENCYregtableright">
		<select class="thin" name="suit">
		<option value="">Any</option>
		<?php
		for($i=30; $i<=60; $i++) {
			$insert = "";
			if($suit== $i) {
				$insert = ' selected="selected"';
			}
			echo "<option value=\"$i\"$insert>$i</option>";
		}
		?>
		</select>
		&nbsp;<select class="thin" name="suitvariation">
		<option value="0.1" <?php if(!empty($suitvariation)) { if($suitvariation == '0.1') echo 'selected="selected"'; } ?>>XS</option>
		<option value="0.2" <?php if(!empty($suitvariation)) { if($suitvariation == '0.2') echo 'selected="selected"'; } ?>>S</option>
		<option value="0.3" <?php if(!empty($suitvariation)) { if($suitvariation == '0.3') echo 'selected="selected"'; } ?>>R</option>
		<option value="0.4" <?php if(!empty($suitvariation)) { if($suitvariation == '0.4') echo 'selected="selected"'; } ?>>L</option>
		<option value="0.5" <?php if(!empty($suitvariation)) { if($suitvariation == '0.5') echo 'selected="selected"'; } ?>>XL</option>
		<option value="0.6" <?php if(!empty($suitvariation)) { if($suitvariation == '0.6') echo 'selected="selected"'; } ?>>XXL</option>
		</select>
		<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;to<br />
		<select class="thin" name="suit2">
		<option value="">Any</option>
		<?php
		for($i=30; $i<=60; $i++) {
			$insert = "";
			if($suit2== $i) {
				$insert = ' selected="selected"';
			}
			echo "<option value=\"$i\"$insert>$i</option>";
		}
		?>
		</select>
		&nbsp;<select class="thin" name="suitvariation2">
		<option value="0.1" <?php if(!empty($suitvariation2)) { if($suitvariation2 == '0.1') echo 'selected="selected"'; } ?>>XS</option>
		<option value="0.2" <?php if(!empty($suitvariation2)) { if($suitvariation2 == '0.2') echo 'selected="selected"'; } ?>>S</option>
		<option value="0.3" <?php if(!empty($suitvariation2)) { if($suitvariation2 == '0.3') echo 'selected="selected"'; } ?>>R</option>
		<option value="0.4" <?php if(!empty($suitvariation2)) { if($suitvariation2 == '0.4') echo 'selected="selected"'; } ?>>L</option>
		<option value="0.5" <?php if(!empty($suitvariation2)) { if($suitvariation2 == '0.5') echo 'selected="selected"'; } ?>>XL</option>
		<option value="0.6" <?php if(!empty($suitvariation2)) { if($suitvariation2 == '0.6') echo 'selected="selected"'; } ?>>XXL</option>
		</select> US sizes
        </td>
      </tr>

      <tr class="maleclass">
        <td class="AGENCYregtableleft">Shirt:</td>
        <td class="AGENCYregtableright">
		<input type="checkbox" name="shirt[]" value="S" <?php if(is_array($shirt)) { if(in_array('S', $shirt)) echo 'checked'; } ?> />Small<br />
		<input type="checkbox" name="shirt[]" value="M" <?php if(is_array($shirt)) { if(in_array('M', $shirt)) echo 'checked'; } ?> />Medium<br />
		<input type="checkbox" name="shirt[]" value="L" <?php if(is_array($shirt)) { if(in_array('L', $shirt)) echo 'checked'; } ?> />Large<br />
		<input type="checkbox" name="shirt[]" value="XL" <?php if(is_array($shirt)) { if(in_array('XL', $shirt)) echo 'checked'; } ?> />X-Large<br />
		<input type="checkbox" name="shirt[]" value="XXL" <?php if(is_array($shirt)) { if(in_array('XXL', $shirt)) echo 'checked'; } ?> />XX-Large<br />
		<input type="checkbox" name="shirt[]" value="Other" <?php if(is_array($shirt)) { if(in_array('Other', $shirt)) echo 'checked'; } ?> />Other
        </td>
      </tr>

      <tr class="maleclass">
        <td class="AGENCYregtableleft">Neck:</td>
        <td class="AGENCYregtableright">
		<select class="thin" name="neck">
		<option value="">Any</option>
		<?php
		for($i=8; $i<=30; $i=$i+.5) {
			$insert = "";
			if($neck == $i) {
				$insert = ' selected="selected"';
			}
			echo "<option value=\"$i\"$insert>$i</option>";
		}
		?>
		</select>
		&nbsp;&nbsp;to&nbsp;&nbsp;
		<select class="thin" name="neck2">
		<option value="">Any</option>
		<?php
		for($i=8; $i<=30; $i=$i+.5) {
			$insert = "";
			if($neck2 == $i) {
				$insert = ' selected="selected"';
			}
			echo "<option value=\"$i\"$insert>$i</option>";
		}
		?>
		</select> Inches
        </td>
      </tr>

      <tr class="maleclass">
        <td class="AGENCYregtableleft">Sleeve:</td>
        <td class="AGENCYregtableright">
		<select class="thin" name="sleeve">
		<option value="">Any</option>
		<?php
		for($i=20; $i<=50; $i++) {
			$insert = "";
			if($sleeve == $i) {
				$insert = ' selected="selected"';
			}
			echo "<option value=\"$i\"$insert>$i</option>";
		}
		?>
		</select>
		&nbsp;&nbsp;to&nbsp;&nbsp;
		<select class="thin" name="sleeve2">
		<option value="">Any</option>
		<?php
		for($i=20; $i<=50; $i++) {
			$insert = "";
			if($sleeve2 == $i) {
				$insert = ' selected="selected"';
			}
			echo "<option value=\"$i\"$insert>$i</option>";
		}
		?>
		</select> Inches
        </td>
      </tr>
	  
      <tr>
        <td class="AGENCYregtableleft">Inseam:</td>
        <td class="AGENCYregtableright">
		<select class="thin" name="inseam">
		<option value="">Any</option>
		<?php
		for($i=8; $i<=50; $i++) {
			$insert = "";
			if($inseam == $i) {
				$insert = ' selected="selected"';
			}
			echo "<option value=\"$i\"$insert>$i</option>";
		}
		?>
		</select>
		&nbsp;&nbsp;to&nbsp;&nbsp;
		<select class="thin" name="inseam2">
 		<option value="">Any</option>
		<?php
		for($i=8; $i<=50; $i++) {
			$insert = "";
			if($inseam2 == $i) {
				$insert = ' selected="selected"';
			}
			echo "<option value=\"$i\"$insert>$i</option>";
		}
		?>
		</select> Inches
        </td>
      </tr>
      
      <tr class="femaleclass">
        <td class="AGENCYregtableleft">Bust:</td>
        <td class="AGENCYregtableright">
		<select class="thin" name="bust">
		<option value="">Any</option>
		<?php
		for($i=20; $i<=60; $i++) {
			$insert = "";
			if($bust == $i) {
				$insert = ' selected="selected"';
			}
			echo "<option value=\"$i\"$insert>$i</option>";
		}
		?>
		</select>
		&nbsp;&nbsp;to&nbsp;&nbsp;
		<select class="thin" name="bust2">
		<option value="">Any</option>
		<?php
		for($i=20; $i<=60; $i++) {
			$insert = "";
			if($bust2 == $i) {
				$insert = ' selected="selected"';
			}
			echo "<option value=\"$i\"$insert>$i</option>";
		}
		?>
		</select> Inches
        </td>
      </tr>      
      
      <tr class="femaleclass">
        <td class="AGENCYregtableleft">Cup Size:</td>
        <td class="AGENCYregtableright">
		<select name="cup">
		<option value="">Any</option>
		<?php
		foreach($bracups as $value=>$size) {
			$insert = "";
			if($cup == $value) {
				$insert = ' selected';
			}
			echo "<option value=\"$value\"$insert>$size</option>";
		}
		?>
		</select>
		&nbsp;&nbsp;to&nbsp;&nbsp;
		<select name="cup2">
		<option value="">Any</option>
		<?php
		foreach($bracups as $value=>$size) {
			$insert = "";
			if($cup2 == $value) {
				$insert = ' selected';
			}
			echo "<option value=\"$value\"$insert>$size</option>";
		}
		?>
		</select>
        </td>
      </tr>

      <tr class="femaleclass">
        <td class="AGENCYregtableleft">Hips:</td>
        <td class="AGENCYregtableright">
		<select class="thin" name="hips">
		<option value="">Any</option>
		<?php
		for($i=20; $i<=60; $i++) {
			$insert = "";
			if($hips == $i) {
				$insert = ' selected="selected"';
			}
			echo "<option value=\"$i\"$insert>$i</option>";
		}
		?>
		</select>
		&nbsp;&nbsp;to&nbsp;&nbsp;
 		<select class="thin" name="hips2">
		<option value="">Any</option>
		<?php
		for($i=20; $i<=60; $i++) {
			$insert = "";
			if($hips2 == $i) {
				$insert = ' selected="selected"';
			}
			echo "<option value=\"$i\"$insert>$i</option>";
		}
		?>
		</select> Inches
        </td>
      </tr>

      <tr class="femaleclass">
        <td class="AGENCYregtableleft">Dress:</td>
        <td class="AGENCYregtableright">
		<select class="thin" name="dress">
		<option value="">Any</option>
		<?php
		for($i=0; $i<=40; $i++) {
			$insert = "";
			if(!empty($dress)) {
				if($dress == $i) {
					$insert = ' selected="selected"';
				}
			}
			echo "<option value=\"$i\"$insert>$i</option>";
		}
		?>
		</select>
		&nbsp;&nbsp;to&nbsp;&nbsp;
 		<select class="thin" name="dress2">
		<option value="">Any</option>
		<?php
		for($i=0; $i<=40; $i++) {
			$insert = "";
			if(!empty($dress2)) {
				if($dress2 == $i) {
					$insert = ' selected="selected"';
				}
			}
			echo "<option value=\"$i\"$insert>$i</option>";
		}
		?>
		</select> US sizes
        </td>
		</tr>
		</table>

 <table width="50%" border="0" cellpadding="3" cellspacing="2" bgcolor="white" align="left">
      <tr>
        <td class="AGENCYregtableleft">Experience Level:</td>
        <td class="AGENCYregtableright">
        	<select name="experience" style="width:100px">
        	<option value="">Any</option>
<?php
for($i=1; isset($experiencearray[$i]); $i++) {
	echo '<option value="' . ($i) . '"';
	if (!empty($experience)) { if($i == ($experience)) echo ' selected="selected"'; }
	echo '>' . $experiencearray[$i] . '</option>';
}
?>
        	</select>
	<div onmouseover="document.getElementById('expcolors').style.display='block'" onmouseout="document.getElementById('expcolors').style.display='none'" style="font-weight:bold; color:black; padding:2px; font-size:14px; background-color:white; float:right">?</div>

          <div id="expcolors" style="margin-top:8px; margin-left:-20px; display:none; position:absolute;">
<?php
echo mysql_result(mysql_query("SELECT varvalue FROM agency_vars WHERE varname='levelsExp'"), 0, 'varvalue');
?>
        </div>

       </td>
      </tr>


      <tr>
        <td class="AGENCYregtableleft">Hair Color:</td>
        <td class="AGENCYregtableright">
		<div id="hairexpand" style="height:40px; overflow:hidden;">

<?php
		for($i=0; isset($haircolorarray[$i]); $i++) {
			echo '<input type="checkbox" name="hair[]" value="' . $haircolorarray[$i] . '"';
			if(!empty($hair)) {
   				if(in_array($haircolorarray[$i], $hair)) echo ' checked';
			}
			echo ' /> ' . $haircolorarray[$i] . '<br />';
		}
?>
</div>
		<a id="expandlink2" href="javascript:void(0)" onclick="getElementById('hairexpand').style.height=''; getElementById('expandlink2').style.display='none'">click to show full list</a>

        </td>
      </tr>

      <tr>
        <td class="AGENCYregtableleft">Eye Color:</td>
        <td class="AGENCYregtableright">
<?php
		for($i=0; isset($eyecolorarray[$i]); $i++) {
			echo '<input type="checkbox" name="eyes[]" value="' . $eyecolorarray[$i] . '"';
			if(!empty($eyes)) {
   				if(in_array($eyecolorarray[$i], $eyes)) echo ' checked';
			}
			echo ' /> ' . $eyecolorarray[$i] . '<br />';
		}
?>
        </td>
      </tr>  
	  	  
      <tr>
        <td class="AGENCYregtableleft">Ethnicity:</td>
        <td class="AGENCYregtableright">
<?php
		for($i=0; isset($ethnicityarray[$i]); $i++) {
			echo '<input type="checkbox" name="ethnicity[]" value="' . $ethnicityarray[$i] . '"';
			if(!empty($ethnicity)) {
   				if(in_array($ethnicityarray[$i], $ethnicity)) echo ' checked';
			}
			echo ' /> ' . $ethnicityarray[$i] . '<br />';
		}
?>
        </td>
      </tr>
      <tr>
        <td class="AGENCYregtableleft">Categories:</td>
        <td class="AGENCYregtableright">
        <div id="catexpand" style="height:40px; overflow:hidden;">
<?php
		for($i=0; isset($categoryarray_1[$i]); $i++) {
			echo '<input type="checkbox" name="category[]" value="' . $categoryarray_1[$i] . '"';
			if(!empty($category)) {
   				if(in_array($categoryarray_1[$i], $category)) echo ' checked';
			}
			echo ' /> ' . $categoryarray_1[$i] . '<br />';
		}
		echo '-----------------------<br />';
		for($i=0; isset($categoryarray_2[$i]); $i++) {
			echo '<input type="checkbox" name="category[]" value="' . $categoryarray_2[$i] . '"';
			if(!empty($category)) {
   				if(in_array($categoryarray_2[$i], $category)) echo ' checked';
			}
			echo ' /> ' . $categoryarray_2[$i] . '<br />';
		}
?>		</div>
		<a id="expandlink" href="javascript:void(0)" onclick="getElementById('catexpand').style.height=''; getElementById('expandlink').style.display='none'">click to show full list</a>
        </td>
      </tr>

      <tr>
        <td class="AGENCYregtableleft">Union Status:</td>
        <td class="AGENCYregtableright">
<?php
for($i=0; isset($unionarray[$i]); $i++) {
	echo '<input type="checkbox" name="unions[]" value="' . $unionarray[$i] . '"';
	if(!empty($unions)) {
   		if(in_array($unionarray[$i], $unions)) echo ' checked';
	}
	echo ' /> ' . $unionarray[$i] . '<br />';
}

echo 'Other:<br /><input type="text" name="unions[]" value="';
if(!empty($unions)) {
	foreach($unions as $un) {
		if(!in_array($un, $unionarray)) {
			echo $un;
			$showblank = false;
		}
	}
}
echo '" />';
?>
        </td>
      </tr>
       <tr>
        <td class="AGENCYregtableleft">Language:*</td>
        <td class="AGENCYregtableright">
        	<input type="text" name="language"<?php if (!empty($language)) echo ' value="' . $language . '"'; ?> />
        </td>
 	   </tr>
      <tr>
         <td class="AGENCYregtableleft">Sports & Music:*</td>
        <td class="AGENCYregtableright">
        	<input type="text" name="sports_music"<?php if (!empty($sports_music)) echo ' value="' . $sports_music . '"'; ?> />
        </td>
      </tr>
      <tr>
         <td class="AGENCYregtableleft">Other Skills/Physical Traits:*</td>
        <td class="AGENCYregtableright">
        	<input type="text" name="skills_other"<?php if (!empty($skills_other)) echo ' value="' . $skills_other . '"'; ?> />
        </td>
      </tr>
      <tr>
      <td colspan="2" class="AGENCYregtableright">
      *For skills, enter one word or phrase per search.  For example, &quot;french horn&quot; is okay, but &quot;violin, guitar, piano&quot; is not recommended.
      </td>
      </tr>
      </table>

    <br clear="all" /><br clear="all" />
<?php
if(is_active()) { // check if user is logged in
?>
	<div align="center">
	<input type="submit" value="<?php if(isset($_GET['configure'])) echo 'CONFIGURE'; else echo 'SEARCH'; ?>" name="submitsearch" />
<?php
if(isset($_COOKIE['agencysearch'])) {
?>
    &nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="CLEAR ALL FIELDS<?php if(isset($_GET['configure'])) echo '/CANCEL'; ?>" onclick="document.location='clienthome.php?mode=search&reset=true'" />
<?php
} else if(isset($_GET['configure'])) {
?>
    &nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="CANCEL" onclick="document.location='clienthome.php?mode=search&reset=true'" />
<?php
}
?>
	</div>
<?php
} else {
?>
	<div align="center">
    <input type="submit" value="Search" name="submitsearch">
	</div>
<?php
}
?>
  </form>

<!-- *******************************   END SEARCH FORM ******************************* -->

<?php
	}
	
if (!empty($gender)) {
	if($gender=='M') echo '<script>changecss(\'.femaleclass\', \'display\', \'none\'); </script>';
	if($gender=='F') echo '<script>changecss(\'.maleclass\', \'display\', \'none\'); </script>';
}
?>
