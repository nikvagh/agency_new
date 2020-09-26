<?php
session_start();
if(isset($_GET['location']) && empty($_GET['location'])) {
	unset($_SESSION['casting_location']); // set to show all if empty
}
include('../includes/mysql_connect.php');
include('../includes/agency_functions.php');
include('../forms/definitions.php');

if(isset($_GET['page']) && isset($_GET['perpage'])) {
	$maxchars_title = 30;
	$maxchars_content = 140;
	$page = (int) $_GET['page'];
	$perpage = (int) $_GET['perpage']; // how many news items to show per page
	$start = ($page-1) * $perpage;
	$link = '';
	
	if(!empty($_GET['matches']) && !empty($_SESSION['user_id'])) {
		$link .= '&matches=true';
		$location_query = ''; // put in some stuff for locations (and Job Type!) here to expand on this
		
		// get user info
		$user_id = (int) $_SESSION['user_id'];
		$query = "SELECT birthdate, gender, location FROM agency_profiles WHERE user_id='$user_id'";
		$result = mysql_query ($query);
		if ($row = mysql_fetch_array ($result, MYSQL_ASSOC)) {
			$age = age_from_dob($row['birthdate']);
			$gender = $row['gender'];
			$location = $row['location'];
		}
		
		if(in_array($location, $locationarray)) {
			$location_query = "AND location_casting='$location'";
		}
		
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
		
		$ethnicities = array();
		$query = "SELECT ethnicity FROM agency_profile_ethnicities WHERE user_id='$user_id'";
		$result = mysql_query ($query);
		while ($row = mysql_fetch_array ($result, MYSQL_ASSOC)) {
			$ethnicities[] = $row['ethnicity'];
		}
		
		$unions = array();
		$query = "SELECT union_name FROM agency_profile_unions WHERE user_id='$user_id'";
		$result = mysql_query ($query);
		while ($row = mysql_fetch_array ($result, MYSQL_ASSOC)) {
			$unions[] = $row['union_name'];
		}				
		
		
		// age/ ethnicity/ gender/ union status
		
		// CREATE AN ARRAY OF JOBS THAT FIT THESE CRITERIA
		// $query = "SELECT DISTINCT agency_castings.casting_id FROM agency_castings, agency_castings_unions, agency_castings_roles, agency_castings_roles_vars WHERE agency_castings.casting_id=agency_castings_unions.casting_id AND agency_castings.casting_id=agency_castings_roles.casting_id AND agency_castings.casting_id=agency_castings_roles_vars.casting_id AND agency_castings_unions.union_name IN ('".implode("', '", $unions)."') AND (agency_castings_roles_vars.var_type='ethnicity' AND agency_castings_roles_vars.var_value IN ('".implode("', '", $ethnicities)."')) AND (agency_castings_roles_vars.var_type='gender' AND agency_castings_roles_vars.var_value='$gender') AND agency_castings_roles.age_lower<='$age' AND agency_castings_roles.age_upper>='$age' AND agency_castings.deleted='0' AND agency_castings.live='1' $location_query ORDER BY agency_castings.post_date DESC LIMIT $perpage OFFSET $start";
		
		// because age and ethnicities are both in agency_castings_roles_vars I'm not seeing how to do this in one query and to save time/costs I'm going to break it into two queries.  The second query should be very fast.  Also, the ethnicity and age has to match for the same role!
		$age_lower = $age + 5;
		$age_upper = $age - 5;
		
		$query = "SELECT DISTINCT agency_castings_roles.role_id FROM agency_castings, agency_castings_unions, agency_castings_roles WHERE agency_castings.casting_id=agency_castings_unions.casting_id AND agency_castings.casting_id=agency_castings_roles.casting_id AND (agency_castings_unions.union_name IN ('".implode("', '", $unions)."') OR agency_castings_unions.union_name='Non-Specified/Open to All') AND agency_castings_roles.age_lower<='$age_lower' AND agency_castings_roles.age_upper>='$age_upper' AND agency_castings.deleted='0' AND agency_castings.live='1' $location_query";
		
		// echo $query . '<br /><br />';
		$result = mysql_query ($query);
		$role_ids = array();
		while ($row = mysql_fetch_array ($result, MYSQL_ASSOC)) {
			$role_ids[] = $row['role_id'];
		}
		$query = "SELECT DISTINCT agency_castings_roles.role_id FROM agency_castings_roles, agency_castings_roles_vars WHERE agency_castings_roles.role_id=agency_castings_roles_vars.role_id AND (agency_castings_roles_vars.var_type='gender' AND agency_castings_roles_vars.var_value='$gender') AND agency_castings_roles.role_id IN ('".implode("', '", $role_ids)."')";
		// echo $query . '<br /><br />';
		$result = mysql_query ($query);
		$role_ids2 = array();
		while ($row = mysql_fetch_array ($result, MYSQL_ASSOC)) {
			$role_ids2[] = $row['role_id'];
		}		
		
		$query = "SELECT DISTINCT agency_castings_roles.role_id FROM  agency_castings_roles, agency_castings_roles_vars WHERE agency_castings_roles.role_id=agency_castings_roles_vars.role_id AND (agency_castings_roles_vars.var_type='ethnicity' AND agency_castings_roles_vars.var_value IN ('".implode("', '", $ethnicities)."')) AND agency_castings_roles.role_id IN ('".implode("', '", $role_ids2)."')";
		// echo $query . '<br /><br />';
		$result = mysql_query ($query);
		$role_ids3 = array();
		while ($row = mysql_fetch_array ($result, MYSQL_ASSOC)) {
			$role_ids3[] = $row['role_id'];
		}			
		
		
		$query = "SELECT DISTINCT agency_castings.casting_id FROM agency_castings, agency_castings_roles WHERE agency_castings.casting_id=agency_castings_roles.casting_id AND agency_castings_roles.role_id IN ('".implode("', '", $role_ids3)."')";
		// echo $query . '<br /><br />';
		$result = mysql_query ($query);
		$casting_ids = array();
		while ($row = mysql_fetch_array ($result, MYSQL_ASSOC)) {
			$casting_ids[] = $row['casting_id'];
		}			
		 
		
		
		$total = mysql_result(mysql_query("SELECT COUNT(*) as `Num` FROM agency_castings WHERE casting_id IN ('".implode("', '", $casting_ids)."')"),0);
		$sql = "SELECT * FROM agency_castings WHERE casting_id IN ('".implode("', '", $casting_ids)."') ORDER BY post_date DESC LIMIT $perpage OFFSET $start";
		// echo $sql;
		// echo 'Total' . $total;
		
			// $total = mysql_result(mysql_query("SELECT COUNT(*) as `Num` FROM agency_castings, agency_castings_jobtype WHERE agency_castings.casting_id=agency_castings_jobtype.casting_id AND agency_castings_jobtype.jobtype NOT IN ('".implode("', '", $jobtypearray)."') AND agency_castings.deleted='0' AND agency_castings.live='1' $location_query"),0);
			// $sql = "SELECT agency_castings.* FROM agency_castings, agency_castings_jobtype WHERE agency_castings.casting_id=agency_castings_jobtype.casting_id AND agency_castings_jobtype.jobtype NOT IN ('".implode("', '", $jobtypearray)."') AND agency_castings.deleted='0' AND agency_castings.live='1' $location_query ORDER BY agency_castings.post_date DESC LIMIT $perpage OFFSET $start";
	
	
	
	
	} else if(!empty($_GET['jobtype'])) {
		$jobtype = escape_data($_GET['jobtype']);
		
		// place location into the jobtype query
		$location_query = '';
		if(!empty($_GET['location']) || !empty($_SESSION['casting_location'])) {
			if(!empty($_GET['location'])) {
				$location = escape_data($_GET['location']);
				$_SESSION['casting_location'] = $_GET['location'];
			} else if(!empty($_SESSION['casting_location'])) {
				$location = escape_data($_SESSION['casting_location']);
			}
			$link .= '&location=' . $location;
			$location_query = "AND agency_castings.location_casting='$location'";
		}		
		
		
		if(in_array($jobtype, $jobtypearray)) {
		// filter by Job Type
			$total = mysql_result(mysql_query("SELECT COUNT(*) as `Num` FROM agency_castings, agency_castings_jobtype WHERE agency_castings.casting_id=agency_castings_jobtype.casting_id AND agency_castings_jobtype.jobtype='$jobtype' AND agency_castings.deleted='0' AND agency_castings.live='1' $location_query"),0);
			$sql = "SELECT agency_castings.* FROM agency_castings, agency_castings_jobtype WHERE agency_castings.casting_id=agency_castings_jobtype.casting_id AND agency_castings_jobtype.jobtype='$jobtype' AND agency_castings.deleted='0' AND agency_castings.live='1' $location_query ORDER BY agency_castings.post_date DESC LIMIT $perpage OFFSET $start";
		} else {
			$total = mysql_result(mysql_query("SELECT COUNT(*) as `Num` FROM agency_castings, agency_castings_jobtype WHERE agency_castings.casting_id=agency_castings_jobtype.casting_id AND agency_castings_jobtype.jobtype NOT IN ('".implode("', '", $jobtypearray)."') AND agency_castings.deleted='0' AND agency_castings.live='1' $location_query"),0);
			$sql = "SELECT agency_castings.* FROM agency_castings, agency_castings_jobtype WHERE agency_castings.casting_id=agency_castings_jobtype.casting_id AND agency_castings_jobtype.jobtype NOT IN ('".implode("', '", $jobtypearray)."') AND agency_castings.deleted='0' AND agency_castings.live='1' $location_query ORDER BY agency_castings.post_date DESC LIMIT $perpage OFFSET $start";
			
			
		}
		$link .= '&jobtype=' . $jobtype;

	
	
	
	
	} else if(!empty($_GET['union'])) {
		$union = escape_data($_GET['union']);
		
		// place location into the jobtype query
		$location_query = '';
		if(!empty($_GET['location']) || !empty($_SESSION['casting_location'])) {
			if(!empty($_GET['location'])) {
				$location = escape_data($_GET['location']);
				$_SESSION['casting_location'] = $_GET['location'];
			} else if(!empty($_SESSION['casting_location'])) {
				$location = escape_data($_SESSION['casting_location']);
			}
			$link .= '&location=' . $location;
			$location_query = "AND agency_castings.location_casting='$location'";
		}		
		
		
		if(in_array($union, $unionarray)) {
		// filter by Union Type
			$total = mysql_result(mysql_query("SELECT COUNT(*) as `Num` FROM agency_castings,  agency_castings_unions WHERE agency_castings.casting_id= agency_castings_unions.casting_id AND  agency_castings_unions.union_name='$union' AND agency_castings.deleted='0' AND agency_castings.live='1' $location_query"),0);
			$sql = "SELECT agency_castings.* FROM agency_castings,  agency_castings_unions WHERE agency_castings.casting_id= agency_castings_unions.casting_id AND  agency_castings_unions.union_name='$union' AND agency_castings.deleted='0' AND agency_castings.live='1' $location_query ORDER BY agency_castings.post_date DESC LIMIT $perpage OFFSET $start";
		} else {
			$total = mysql_result(mysql_query("SELECT COUNT(*) as `Num` FROM agency_castings,  agency_castings_unions WHERE agency_castings.casting_id= agency_castings_unions.casting_id AND  agency_castings_unions.union_name NOT IN ('".implode("', '", $unionarray)."') AND agency_castings.deleted='0' AND agency_castings.live='1' $location_query"),0);
			$sql = "SELECT agency_castings.* FROM agency_castings,  agency_castings_unions WHERE agency_castings.casting_id= agency_castings_unions.casting_id AND  agency_castings_unions.union_name NOT IN ('".implode("', '", $unionarray)."') AND agency_castings.deleted='0' AND agency_castings.live='1' $location_query ORDER BY agency_castings.post_date DESC LIMIT $perpage OFFSET $start";
			
			
		}
		$link .= '&union=' . $union;

		
	
	
	
	} else if(!empty($_GET['location']) || !empty($_SESSION['casting_location'])) {
		if(!empty($_GET['location'])) {
			$location = escape_data($_GET['location']);
			$_SESSION['casting_location'] = $_GET['location'];
		} else if(!empty($_SESSION['casting_location'])) {
			$location = escape_data($_SESSION['casting_location']);
		}
		
		if($location == 'New York City Area' || $location == 'Los Angeles/Southern Cal.') {
			$location_query = "location_casting='$location'";
		} else {
			$location_query = "location_casting<>'New York City Area' AND location_casting<>'Los Angeles/Southern Cal.'";
		}
		// $_SESSION['test']
		// filter by Location
		// $location_query = "location_casting='$location'"; //this is what it should be if using all locations
		$total = mysql_result(mysql_query("SELECT COUNT(*) as `Num` FROM agency_castings WHERE $location_query AND deleted='0' AND live='1'"),0);
		$link = '&location=' . $location;
		$sql = "SELECT * FROM agency_castings WHERE $location_query AND deleted='0' AND live='1' ORDER BY post_date DESC LIMIT $perpage OFFSET $start";
		if($_SESSION['test']) {
			// echo $sql;
		}
	} else {
		$sql = "SELECT * FROM agency_castings WHERE deleted='0' AND live='1' ORDER BY post_date DESC LIMIT $perpage OFFSET $start";
		$total = mysql_result(mysql_query("SELECT COUNT(*) as `Num` FROM `agency_castings` WHERE deleted='0' AND live='1'"),0);
	}
	$result=mysql_query($sql);
	if(!mysql_num_rows($result)) {
		echo '<div align="center" style="padding:20px; font-weight:bold">There are currently no castings for that filtering option you selected.</div>';
		$total = mysql_result(mysql_query("SELECT COUNT(*) as `Num` FROM `agency_castings` WHERE deleted='0' AND live='1'"),0);		

		$sql = "SELECT * FROM agency_castings WHERE deleted='0' AND live='1' ORDER BY post_date DESC LIMIT $perpage OFFSET $start";
		$result=mysql_query($sql);
	}
	while($row = sql_fetchrow($result)) {
		$castingid = $row['casting_id'];
		$jobtitle = $row['job_title'];
		$location = $row['location_casting'];
		$postdate = date('m/d/y', strtotime($row['post_date']));
		$notes = strip_tags(stripslashes($row['notes']));
		if (strlen($notes) > $maxchars_content) {
			$notes = substr($notes,0,$maxchars_content) . '...';
			// $notes = preg_replace("/\s+[,\.!?\w-]*?$/",'....',$notes);
		}
	
		$jobtype_html = ''; // this is done this way to figure out the icon to be used before outputting the job type
		$jobicon = false; // flag for if the icon has been displayed yet
		$sql2 = "SELECT jobtype FROM agency_castings_jobtype WHERE casting_id='$castingid'";
		$result2 = mysql_query($sql2);
		$num_results = mysql_num_rows($result2);
		if($num_results > 0) {
			$jobtype_html .= '<span class="AGENCYcastinglabel">Job Type:</span> <span class="AGENCYcastinginfo">';
			while($row2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
				$jobtype = $row2['jobtype'];
				$jobtype_html .= $jobtype;
				if($num_results-- > 1) $jobtype_html .= ', ';
				// place icon
				if(!$jobicon) {
					if(isset($castingicons[$jobtype])) {
						//echo '<a href="news.php?castingid=' . $castingid . '&amp;title=' .  urlencode($jobtitle) . '"><img src="images/icons/' . $castingicons[$jobtype] . '" align="left" style="margin-right:3px; margin-bottom:10px" border="0" /></a>';
						$jobicon = true;
					}
				}		
			}
			$jobtype_html .= '</span>&nbsp;&nbsp;&nbsp;';
		}
		if(!$jobicon) {
			echo '<a href="news.php?castingid=' . $castingid . '&amp;title=' .  urlencode($jobtitle) . '"><img src="images/icons/FilmOTHER.jpg" align="left" style="margin-right:3px; margin-bottom:10px" border="0" /></a>';
		}

/*
	if(file_exists('images/castings/' . $castingid . '.jpg')) {
		echo '<a href="news.php?castingid=' . $castingid . '&amp;title=' .  urlencode($jobtitle) . '"><img src="images/castings/' . $castingid . '.jpg" align="left" style="margin-right:3px" border="0" /></a>';
	} else if(file_exists('images/castings/' . $castingid . '.gif')) {
		echo '<a href="news.php?castingid=' .  $castingid . '&amp;title=' .  urlencode($jobtitle) . '"><img src="images/castings/' . $castingid . '.gif" align="left" style="margin-right:3px" border="0" /></a>';
	}
*/
?>
<div class="row">

  <div class="col-sm-1"></div>

  <div class="col-sm-11">

  <div class="row casting-post">
      
	<span class="AGENCYindextitle"><?php echo $jobtitle; ?></span> <h4 class="date">[<?php echo $postdate; ?>]</h4> <br />
	<span class="AGENCYindexcontent" style="font-size:10px;">
<?php
		echo $jobtype_html;
		
		$sql2 = "SELECT union_name FROM agency_castings_unions WHERE casting_id='$castingid'";
		$result2 = mysql_query($sql2);
		$num_results = mysql_num_rows($result2);
		if($num_results > 0) {
			echo '<span class="AGENCYcastinglabel">Union:</span> <span class="AGENCYcastinginfo">';
			while($row2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
				echo $row2['union_name'];
				if($num_results-- > 1) echo ', ';
			}
			echo '</span>';
		}
		
		echo '&nbsp;&nbsp;<span class="AGENCYcastinglabel">Location:</span> <span class="AGENCYcastinginfo">' . $location . '</span>';
?>

	<br /><?php echo $notes; ?> <a href="news.php?castingid=<?php echo $castingid; ?>&amp;title=<?php echo urlencode($jobtitle); ?>">More Info&gt;</a></span><br /><br clear="all" />
	
</div>

</div>

</div>
<?php
	}
	// echo 'total: ' . $total . '; page: ' . $page . '; perpage: ' . $perpage;
	if($total > $page * $perpage) {
?>
		<div id="morecastings<?php echo $page; ?>">
			<div align="right" style="margin:20px">
				<a href="javascript:void(0)" style="font-size:14px; font-weight:bold" onClick="loaddiv('morecastings<?php echo $page; ?>', false, 'ajax/morecastings.php?page=<?php echo $page+1; ?>&perpage=<?php echo $perpage; echo $link; ?>&')">VIEW MORE CASTINGS</a>
			</div>
		</div>
<?php
	}
}
mysql_close(); // Close the database connection.
?>
