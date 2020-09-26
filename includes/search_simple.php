<?php
define("PERPAGE", 20);// how many topics to post per page

require 'includes/PageNavigator.php';
//max per page

define("OFFSET", "offset");
//get query string
$offset=@escape_data((int)$_GET[OFFSET]);

//check variable
if (!isset($offset)){
	$recordoffset=0;
}else{
	//calc record offset
	$recordoffset=$offset*PERPAGE;
}

   	if (isset($_POST['submitsearch']) && is_active()) { // Handle the form.
   		if(is_active()) { // check if user is logged in and approved
   			$sql_start = "SELECT DISTINCT p.user_id, p.registration_date, p.firstname, p.lastname";

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
					$value1 = request_var($var1, '');
					$value2 = request_var($var2, '');
					if(!empty($value1) && !empty($value2)) {
						if($value2 >= $value1) {
							$sql = "p.$var1 >= '$value1' AND p.$var1 <= '$value2' AND ";
						}
					} else 	if(!empty($value1) && empty($value2)) {
						$sql = "p.$var1 = '$value1' AND ";
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
			
			// $sql .= addsql('firstname');
			// $sql .= addsql('lastname');
			$sql .= addsql('gender');
			// $sql .= addsql('hair');

			 $sql .= addsql('experience');
			 
			if(!empty($_POST['country'])) {
				 $sql .= addsql('country');
			 }
			 // $sql .= addsql('ethnicity');
			if(!empty($_POST['zipcode']) && !empty($_POST['miles'])) {
				include('includes/zipsearch/radius-search.php');
				
				if(empty($ziparray)) {
					// zip code is not found, then search for only zip code which will show no results
					$sql .= "p.zip = '$zipcode' AND ";
				} else {
					$sql .= "p.zip IN ('" . implode("','", $ziparray) . "') AND ";
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
   				$var = mysql_real_escape_string($language);
   				$sql .= "p.skills_language LIKE '%$var%' AND ";
			}
			if(!empty($_POST['sports_music'])) {
   				$sports_music = $_POST['sports_music'];
   				$var = mysql_real_escape_string($sports_music);
   				$sql .= "p.skills_sports_music LIKE '%$var%' AND ";
			}
			if(!empty($_POST['skills_other'])) {
   				$skills_other = $_POST['skills_other'];
   				$var = mysql_real_escape_string($skills_other);
   				$sql .= "p.skills_other LIKE '%$var%' AND ";
			}


			$today = date('m-d');
			$thisyear = date('Y');
   			if(!empty($_POST['age'])) {
   				$age = $_POST['age'];
   				$var = (int) mysql_real_escape_string($age);
				$var = ($thisyear - $var) . '-' . $today;
   				$sql .= "p.birthdate <='$var' AND ";
			}
   			if(!empty($_POST['age2'])) {
   				$age2 = $_POST['age2'];
   				$var = (int) mysql_real_escape_string($age2);
				$var = ($thisyear - $var) . '-' . $today;
   				$sql .= "p.birthdate >='$var' AND ";
			}

   			if(!empty($_POST['hair'])) {
				$hair = array();
   				$hair = $_POST['hair'];
				$num_hair = sizeof($hair);
				$sql .= "(";
				foreach($hair as $value) {
					$num_hair--;
					$value = mysql_real_escape_string($value);
					$sql .= "p.hair ='$value'";
					if($num_hair > 0) {
						$sql .= " OR ";
					} else {
						$sql .= ") AND ";
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

   			if(!empty($_POST['category'])) {
				$category = array();
   				$category = $_POST['category'];
				$num_category = sizeof($category);
				$sql .= "(";
				foreach($category as $value) {
					$num_category--;
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
		} else {
			echo '<br clear="all" /><br />You must an APPROVED member before you can use the search function</div>';
		}
	}

	echo '<div style="margin-top:-40px; margin-left:230px; position:absolute">';
	if(isset($sql) && isset($sql_start)) {

		$sql .= "p.account_type='talent' AND u.user_type='0'";

		$sql_count = "SELECT COUNT(*)" . $sql; // this is to find the total number of results

		$sql = $sql_start . $sql; // add first part for normal query
		$_SESSION['currentsearch'] = $sql; // save this part of query for pagination
		$_SESSION['countsearch'] = $sql_count;

		$sql .= " ORDER BY p.firstname ASC LIMIT $recordoffset,". PERPAGE;
		echo 'Sort by: <a href="search.php?sort=joindate&offset=0" class="AGENCY_graybutton" style="font-size:12px">first name --> newest talent</a>';
	} else if(isset($_SESSION['currentsearch']) && isset($_SESSION['countsearch']) && isset($_GET['offset'])) {
		$orderby = "p.firstname ASC";
		echo 'Sort by: ';
		if(!empty($_GET['sort'])) {
			if($_GET['sort'] == 'joindate') {
				$orderby = "p.payProcessedDate DESC, p.user_id DESC";
				echo '<a href="search.php?offset=0" class="AGENCY_graybutton" style="font-size:12px">first name <-- newest talent</a>';
			}
		} else {
			echo '<a href="search.php?sort=joindate&offset=0" class="AGENCY_graybutton" style="font-size:12px">first name --> newest talent</a>';
		}
	
		$sql = $_SESSION['currentsearch'] . " ORDER BY $orderby LIMIT $recordoffset,". PERPAGE;;
		$sql_count = $_SESSION['countsearch'];
	}
	echo '</div>';
		
	if(isset($sql) && isset($sql_count)) {
		// echo 'Testing: ' . $sql;

		echo '<div>';

		$result=mysql_query($sql);
		if(mysql_num_rows($result) == 0) {
			echo 'no results with the given search parameters<br /><br />';
		}


		$columns = 5;
		 while($row = sql_fetchrow($result)) {
			$uid = $row['user_id'];
			$displayname = $row['firstname'];
			if(agency_privacy($friendid, 'lastname')) {
				$displayname .= ' ' . $row['lastname'];
			}
			$posterfolder = 'talentphotos/' . $uid . '_' . $row['registration_date'] . '/';
			echo '<div align="center" style="float:left; width:110px; height: 180px; padding:5px"><a style="text-decoration:none; font-weight:bold" href="profile.php?u=' . $uid . '"><img src="';
				if(file_exists($posterfolder . 'avatar.jpg')) {
					echo   $posterfolder . 'avatar.jpg';
				} else if(file_exists($posterfolder . 'avatar.gif')) {
					echo   $posterfolder . 'avatar.gif';
				} else {
					echo 'images/friend.gif';
				}
			echo '" style="max-width:100%; max-height:160px" /><br />' . $displayname . '</a></div>';
			$columns--;
			if($columns == 0) {
				$columns = 5;
				echo '<br clear="all" />';
			}
		 }
		 echo '<br clear="all" />';

		$totalrecords = @mysql_result(mysql_query($sql_count), 0);

		$numpages = ceil($totalrecords/PERPAGE);

		//create category parameter
		$otherparameter = '';

		if(!empty($_GET['sort'])) {
			if($_GET['sort'] == 'name') {
				$otherparameter = '&sort=name';
			}
		}
		//create if needed
		if($numpages>1){
		  //create navigator
		  $nav = new PageNavigator($pagename, $totalrecords, PERPAGE,
			$recordoffset, 20, $otherparameter);
		  echo $nav->getNavigator();
		}

		 echo '<br /><br /><br /><a href="search.php" class="AGENCY_graybutton">new search</a>';

		 echo '</div>';

	} else {
		unset($_SESSION['currentsearch']);
		unset($_SESSION['countsearch']);

?>

    <div style="position:relative;top:-10px">
        <span style="color:#FF0000; font-size:12px">Enter Search Parameters (all fields optional):
        </span></div>

<!--  *******************************   START SEARCH FORM   *******************************   -->

<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" name="searchform">
 <table width="50%" border="0" cellpadding="3" cellspacing="2" bgcolor="white" align="left" style="border-right:1px solid gray">
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
        	<select name="gender">
        	<option value="" <?php if (!empty($gender)) {if($gender=='') echo 'selected="selected"';} else { echo 'selected="selected"'; } ?> /> Any</option>
        	<option value="M" <?php if (!empty($gender)) { if($gender=='M') echo 'selected="selected"'; } ?> /> Male</option>
        	<option value="F" <?php if (!empty($gender)) {if($gender=='F') echo 'selected="selected"';} ?> /> Female</option>
        	<!-- <option value="O" <?php if (!empty($gender)) {if($gender=='O') echo 'selected="selected"';} ?> /> Other</option> -->
        	</select>
        </td>
      </tr>
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
        <option value="United States">United States</option>
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
        </td>
      </tr>

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
	</select>
	  </td>
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

          <div id="expcolors" style="margin-top:8px; margin-left:-14px; display:none; position:absolute;">
<?php
echo mysql_result(mysql_query("SELECT varvalue FROM agency_vars WHERE varname='levelsExp'"), 0, 'varvalue');
?>
        </div>

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
		</table>

 <table width="50%" border="0" cellpadding="3" cellspacing="2" bgcolor="white" align="left">
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
    <input type="submit" value="Search" name="submitsearch" />
	</div>
<?php
} else {
?>
	<div align="center">
    <input type="button" value="Search" onclick="alert('Once you sign up and your account has been approved you will be able to search our Talent database.'); window.scrollTo(0,0)" />
	</div>
<?php
}
?>
  </form>

<!-- *******************************   END SEARCH FORM ******************************* -->

<?php
	}
?>
