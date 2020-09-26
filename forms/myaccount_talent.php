<script type="text/javascript">


function changetab(thetab) {
	var tabs = ['General', 'Measurements', 'Experience', 'Bio', 'Links', 'Privacy Settings'];
	for(thistab=0; thistab<7; thistab++) {
		if(tabs[thistab] == thetab) {
			document.getElementById(thetab).innerHTML='<span class="AGENCYProfileTab AGENCYProfileTabActive">'+thetab+'</span>';
		 	document.getElementById('Form'+thetab).style.display='block';
		} else {
			if(document.getElementById(tabs[thistab])) {
				document.getElementById(tabs[thistab]).innerHTML = '<a href="javascript:void(0)" onclick="changetab(\''+tabs[thistab]+'\')" class="AGENCYProfileTab AGENCYProfileTabInActive">'+tabs[thistab]+'</span>';
			}
			if(document.getElementById('Form'+tabs[thistab])) {
				document.getElementById('Form'+tabs[thistab]).style.display = 'none';
			}
		}
	}
}
</script>
<div id="AGENCYProfileMiddleSurround" style="width:805px">
  <form action="myaccount.php" method="post" name="myaccount" id="myaccount">

  <div id="AGENCYProfileName" class="AGENCYUserName">
<?php
            $sql = "SELECT * FROM agency_profiles WHERE user_id='$userid'";
            $result = mysql_query($sql);
            if ($row = sql_fetchrow($result))
            {
                echo '<font color="' . $experiencecolors[$row['experience']] . '">' . $row['firstname'] . ' ' . $row['lastname'] . '</font>';
            }
?>
<span style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:16px; color: #000000; font-weight:normal"> MySettings</span>


&nbsp;&nbsp;&nbsp;<input type="submit" value="Save Changes" name="submit" <?php if(!is_admin()) echo 'onclick="return checkform();"'; ?> />&nbsp;&nbsp;<input type="button" value="Cancel" onclick="window.location='profile.php'" />

  </div>

<div id="AGENCYProfileTabContainer" style="width:805px">

<span id="General"><span class="AGENCYProfileTab AGENCYProfileTabActive">General</span></span>
<span id="Measurements"><a href="javascript:void(0)" onclick="changetab('Measurements')" class="AGENCYProfileTab AGENCYProfileTabInActive">Measurements</a></span>
<span id="Experience"><a href="javascript:void(0)" onclick="changetab('Experience')" class="AGENCYProfileTab AGENCYProfileTabInActive">Experience</a></span>
<span id="Bio"><a href="javascript:void(0)" onclick="changetab('Bio')" class="AGENCYProfileTab AGENCYProfileTabInActive">Bio</a></span>
<span id="Links"><a href="javascript:void(0)" onclick="changetab('Links')" class="AGENCYProfileTab AGENCYProfileTabInActive">Links</a></span>
<span id="Privacy Settings"><a href="javascript:void(0)" onclick="changetab('Privacy Settings')" class="AGENCYProfileTab AGENCYProfileTabInActive">Privacy Settings</a></span>
</div>

<div id="AGENCYProfileMiddleContent" style="width:791px">

<div align="center" id="AGENCYmyaccount" style="width:430px; float:left">
  <span id="FormGeneral">
    <table width="430" border="0" cellpadding="3" cellspacing="2" bgcolor="white">

      <tr>
        <td  class="AGENCYregtableleft"<?php if (empty($location)) {echo ' style="color:#FF0000"';} ?>>Region:</td>
        <td class="AGENCYregtableright"><select name="location" id="location" onchange="if(this.value=='Other') { document.getElementById('otherlocation').style.display=''; } else { document.getElementById('otherlocation').style.display='none'; }">
        <option value="">Choose your region</option>
		<?php
		for($i=0; isset($locationarray[$i]); $i++) {
			echo '<option value="' . $locationarray[$i] . '"';
			if (!empty($location)) { if($locationarray[$i] == $location) echo ' selected'; }
			echo '>' . $locationarray[$i] . '</option>';
		}
		echo '<option value="Other"';
		if (!empty($location)) { if(!in_array($location, $locationarray)) echo ' selected'; }
		echo '>Other</option>';
		?>
		</select>

<div id="otherlocation" class="signupfield" <?php if(in_array($location, $locationarray)) echo 'style="display:none"'; ?>>Location:<br /><input type="text" name="otherlocation" <?php if(!in_array($location, $locationarray)) echo 'value="' . $location . '"'; ?>></div>        
        
        
        </td>
      </tr>

      <tr>
        <td  class="AGENCYregtableleft"<?php if (empty($firstname)) {echo ' style="color:#FF0000"';} ?>>First Name:</td>
        <td class="AGENCYregtableright"><input type="text" id="firstname" name="firstname" class="fixwidth" maxlength="50" value="<?php if (!empty($firstname)) echo $firstname; ?>" />
        </td>
      </tr>
      <tr>
        <td class="AGENCYregtableleft">Last Name:</td>
        <td class="AGENCYregtableright"><input type="text" name="lastname" class="fixwidth" maxlength="50" value="<?php if (!empty($lastname)) echo $lastname; ?>" />
        </td>
      </tr>
      <tr>
        <td class="AGENCYregtableleft"<?php if (empty($email)) {echo ' style="color:#FF0000"';} ?>>Email Address:</td>
        <td class="AGENCYregtableright"><input type="text" id="email" name="email" class="fixwidth" maxlength="100" value="<?php if (!empty($email)) echo $email; ?>" />
        </td>
      </tr>
      <tr>
        <td class="AGENCYregtableleft">Phone Number:</td>
        <td class="AGENCYregtableright"><input type="text" name="phone" class="fixwidth" maxlength="40" value="<?php if (!empty($phone)) echo $phone; ?>" /><br />
<br />
Please include a phone number so casting directors can reach you at short notice.
        </td>
      </tr>

      <tr>
        <td class="AGENCYregtableleft">Country:</td>
        <td class="AGENCYregtableright"><select id="country" name="country" onChange="changecountry(this.value)" class="fixwidth">
        <option value=""> -- Select Country -- </option>
<?php
foreach($countryarray as $abr=>$c) {
	echo '<option value="' . $c . '"';
	if(isset($country)) {
		if($country == $c) {
			echo ' selected';
		}
	}
	echo '>' . $c . '</option>';
}
?>
		</select>
        </td>
      </tr>

      <tr>
        <td class="AGENCYregtableleft">State/Province:</td>
        <td class="AGENCYregtableright" id="statediv">
<?php
$showstates = false; // if true, the states of the US will display in a dropdown
if(isset($country)) {
	if($country == 'United States') {
		$showstates = true;
	}
}
if($showstates) {
	echo '<select name="state">';
	foreach($stateList['US'] as $abr=>$st) {
		echo '<option value="' . $st . '"';
		if(isset($state)) {
			if($st == $state) {
				echo ' selected';
			}
		}
		echo '>' . $st . '</option>';
	}
	echo '</select>';
} else {
?>
        <input type="text" name="state" class="fixwidth" maxlength="50" value="<?php if (!empty($state)) echo $state; ?>" />
<?php
}
?>
        </td>
      </tr>

      <tr>
        <td class="AGENCYregtableleft">City:</td>
        <td class="AGENCYregtableright"><input type="text" id="city" name="city" class="fixwidth" maxlength="40" value="<?php if (!empty($city)) echo $city; ?>" />
        </td>
      </tr>

      <tr>
        <td class="AGENCYregtableleft">Zip/Postal Code:</td>
        <td class="AGENCYregtableright"><input type="text" name="zip" class="fixwidth" maxlength="20" value="<?php if (!empty($zip)) echo $zip; ?>" />
        </td>
      </tr>
	    
      <tr>
        <td class="AGENCYregtableleft"<?php if (empty($gender)) {echo ' style="color:#FF0000"';} ?>>Gender:</td>
        <td class="AGENCYregtableright">
        	<input onclick="alert('In the instance of a change in Gender, please submit this form first, then go to the Measurements tab and re-enter your measurements')" type="radio" name="gender" value="M" <?php if (!empty($gender)) { if($gender=='M') echo 'checked'; } else { echo 'checked'; } ?> /> Male&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        	<input onclick="alert('In the instance of a change in Gender, please submit this form first, then go to the Measurements tab and re-enter your measurements')" type="radio" name="gender" value="F" <?php if (!empty($gender)) {if($gender=='F') echo 'checked';} ?> /> Female&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        	<input onclick="alert('In the instance of a change in Gender, please submit this form first, then go to the Measurements tab and re-enter your measurements')" type="radio" name="gender" value="O" <?php if (!empty($gender)) {if($gender=='O') echo 'checked';} ?> /> Other
        </td>
      </tr>

      <tr>
        <td class="AGENCYregtableleft">Ethnicity:</td>
        <td class="AGENCYregtableright">
		<?php
		
		for($i=0; isset($ethnicityarray[$i]); $i++) {
			echo '<input type="checkbox" name="ethnicities[]" id="ethnicities[' . $i . ']" value="' . $ethnicityarray[$i] . '"';
			if(in_array($ethnicityarray[$i], $ethnicities)) echo ' checked';
			echo ' /> ' . $ethnicityarray[$i] . '<br />';
		}
		?>
        </td>
      </tr>

      <tr>
        <td class="AGENCYregtableleft">Date of Birth:</td>
        <td class="AGENCYregtableright">
<?php
if(!empty($birthdate)) {
	$YR = date("Y", strtotime($birthdate));
	$MO = date("m", strtotime($birthdate));
	$DY = date("d", strtotime($birthdate));
} else {
	$YR = '';
	$MO = '';
	$DY = '';
}


 //Create the month pull-down menu

 echo '<SELECT id="Month_dob" NAME=Month_dob style="border:thin dotted #BBB">';
 echo '<OPTION VALUE="">-Month-</OPTION>';
 echo "<OPTION VALUE=01"; if (isset($_POST['Month_dob'])) { if ($_POST['Month_dob'] == "01") { echo " selected"; } } else if ($MO == "01") echo " selected"; echo ">January</OPTION>\n";
 echo "<OPTION VALUE=02"; if (isset($_POST['Month_dob'])) { if ($_POST['Month_dob'] == "02") { echo " selected"; } } else if ($MO == "02") echo " selected"; echo ">February</OPTION>\n";
 echo "<OPTION VALUE=03"; if (isset($_POST['Month_dob'])) { if ($_POST['Month_dob'] == "03") { echo " selected"; } } else if ($MO == "03") echo " selected"; echo ">March</OPTION>\n";
 echo "<OPTION VALUE=04"; if (isset($_POST['Month_dob'])) { if ($_POST['Month_dob'] == "04") { echo " selected"; } } else if ($MO == "04") echo " selected"; echo ">April</OPTION>\n";
 echo "<OPTION VALUE=05"; if (isset($_POST['Month_dob'])) { if ($_POST['Month_dob'] == "05") { echo " selected"; } } else if ($MO == "05") echo " selected"; echo ">May</OPTION>\n";
 echo "<OPTION VALUE=06"; if (isset($_POST['Month_dob'])) { if ($_POST['Month_dob'] == "06") { echo " selected"; } } else if ($MO == "06") echo " selected"; echo ">June</OPTION>\n";
 echo "<OPTION VALUE=07"; if (isset($_POST['Month_dob'])) { if ($_POST['Month_dob'] == "07") { echo " selected"; } } else if ($MO == "07") echo " selected"; echo ">July</OPTION>\n";
 echo "<OPTION VALUE=08"; if (isset($_POST['Month_dob'])) { if ($_POST['Month_dob'] == "08") { echo " selected"; } } else if ($MO == "08") echo " selected"; echo ">August</OPTION>\n";
 echo "<OPTION VALUE=09"; if (isset($_POST['Month_dob'])) { if ($_POST['Month_dob'] == "09") { echo " selected"; } } else if ($MO == "09") echo " selected"; echo ">September</OPTION>\n";
 echo "<OPTION VALUE=10"; if (isset($_POST['Month_dob'])) { if ($_POST['Month_dob'] == "10") { echo " selected"; } } else if ($MO == "10") echo " selected"; echo ">October</OPTION>\n";
 echo "<OPTION VALUE=11"; if (isset($_POST['Month_dob'])) { if ($_POST['Month_dob'] == "11") { echo " selected"; } } else if ($MO == "11") echo " selected"; echo ">November</OPTION>\n";
 echo "<OPTION VALUE=12"; if (isset($_POST['Month_dob'])) { if ($_POST['Month_dob'] == "12") { echo " selected"; } } else if ($MO == "12") echo " selected"; echo ">December</OPTION>\n";
 echo "</SELECT>&nbsp;&nbsp;";

 //Create the day pull-down menu.

 echo "<SELECT id=\"Day_dob\" NAME=Day_dob style=\"border:thin dotted #BBB\">";
 echo '<OPTION VALUE="">-Day-</OPTION>';
 $Day = 1;
 while ($Day <= 31) {
   if (strlen($Day) < 2) {
		$Day = '0' .$Day;
	}
   echo "<OPTION VALUE=$Day"; if (isset($_POST['Day_dob'])) { if ($_POST['Day_dob'] == $Day) { echo " selected"; } } else if ($DY == $Day) echo " selected"; echo ">$Day</OPTION>\n";
   $Day++;
 }
 echo "</SELECT>&nbsp;&nbsp;";

 //Create the year pull-down menu.
 echo "<SELECT id=\"Year_dob\" NAME=Year_dob style=\"border:thin dotted #BBB\">";
 echo '<OPTION VALUE="">-Year-</OPTION>';
 $Year = 1900;
 $Current_Year = date("Y");
 while ($Year <= $Current_Year) {
   echo "<OPTION VALUE=$Year"; if (isset($_POST['Year_dob'])) { if ($_POST['Year_dob'] == $Year) { echo " selected"; } } else if ($Year == $YR) echo " selected"; echo ">$Year</OPTION>\n";
   $Year++;
 }
 echo "</SELECT>";
?>
        <br /><br />
NOTE: Your actual birthdate or age will never be visible to anyone on the site, but you have to enter a birthday to make you searchable for castings.</td>
      </tr>
      <tr>
	<td class="AGENCYregtableleft">Tattoos:</td>
        <td class="AGENCYregtableright"><input type="checkbox" id="c_t" <?php if(!empty($tattoos)) echo 'checked'; ?>  onclick="if(this.checked) { document.getElementById('b_t').style.display=''; } else { document.getElementById('b_t').style.display='none'; document.getElementById('tattoos').value=''; }" />
      <span id="b_t" <?php if(empty($tattoos)) echo 'style="display:none"'; ?>>
      	&nbsp;Description: <input type="text" id="tattoos" name="tattoos" value="<?php if(!empty($tattoos)) echo $tattoos; ?>" maxlength="255" />
      	</span>
  		</td>
     </tr>
     <tr>
	<td class="AGENCYregtableleft">Piercings:</td>
        <td class="AGENCYregtableright"><input type="checkbox" id="c_p" <?php if(!empty($piercings)) echo 'checked'; ?>  onclick="if(this.checked) { document.getElementById('b_p').style.display=''; } else { document.getElementById('b_p').style.display='none'; document.getElementById('piercings').value=''; }" />
      <span id="b_p" <?php if(empty($piercings)) echo 'style="display:none"'; ?>>
      	&nbsp;Description: <input type="text" id="piercings" name="piercings" value="<?php if(!empty($piercings)) echo $piercings; ?>" maxlength="255" />
      	</span>
  		</td>
     </tr>
      
      
      
      
      
      
      </table>
</span>
<span id="FormMeasurements" style="display:none">
<table width="430" border="0" cellpadding="3" cellspacing="2" bgcolor="white">
      <tr>
        <td class="AGENCYregtableleft">Height:</td>
        <td class="AGENCYregtableright">
        <select class="thin" name="height_feet">
		<?php
		for($i=1; $i<=7; $i++) {
			$insert = "";
			if($height_feet == $i) {
				$insert = ' selected';
			}
			echo "<option value=\"$i\"$insert>$i</option>";
		}
		?>
		</select> feet

		<select class="thin" name="height_inches">
		<?php
		for($i=0; $i<=11; $i++) {
			$insert = "";
			if($height_inches == $i) {
				$insert = ' selected';
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
		<?php
		for($i=10; $i<=800; $i++) {
			$insert = "";
			if($weight == $i) {
				$insert = ' selected';
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
		<?php
		for($i=20; $i<=60; $i++) {
			$insert = "";
			if($waist == $i) {
				$insert = ' selected';
			}
			echo "<option value=\"$i\"$insert>$i</option>";
		}
		?>
		</select> inches
        </td>
      </tr>


      <tr>
        <td class="AGENCYregtableleft">Hair Color:</td>
        <td class="AGENCYregtableright">
        <select name="hair">
		<?php
		for($i=0; isset($haircolorarray[$i]); $i++) {
			echo '<option value="' . $haircolorarray[$i] . '"';
			if (!empty($hair)) { if($haircolorarray[$i] == $hair) echo ' selected'; }
			echo '>' . $haircolorarray[$i] . '</option>';
		}
		?>
		</select>
        </td>
      </tr>

      <tr>
        <td class="AGENCYregtableleft">Eye Color:</td>
        <td class="AGENCYregtableright">
        <select name="eyes">
		<?php
		for($i=0; isset($eyecolorarray[$i]); $i++) {
			echo '<option value="' . $eyecolorarray[$i] . '"';
			if (!empty($eyes)) { if($eyecolorarray[$i] == $eyes) echo ' selected'; }
			echo '>' . $eyecolorarray[$i] . '</option>';
		}
		?>
		</select>
        </td>
      </tr>

      <tr>
        <td class="AGENCYregtableleft">Shoe Size:</td>
        <td class="AGENCYregtableright">
		<select class="thin" name="shoe">
		<?php
		for($i=1; $i<=21; $i += .5) {
			$insert = "";
			if($shoe == $i) {
				$insert = ' selected';
			}
			echo "<option value=\"$i\"$insert>$i</option>";
		}
		?>
		</select> US sizes
        </td>
      </tr>

<?php
if($gender == 'M'|| $gender == 'O') {
?>
      <tr>
        <td class="AGENCYregtableleft">Suit:</td>
        <td class="AGENCYregtableright">
		<select class="thin" name="suit">
		<?php
		for($i=30; $i<=60; $i++) {
			$insert = "";
			if($suit== $i) {
				$insert = ' selected';
			}
			echo "<option value=\"$i\"$insert>$i</option>";
		}
		?>
		</select>
		&nbsp;<select class="thin" name="suitvariation">
		<option value="0.1" <?php if(!empty($suitvariation)) { if($suitvariation == '0.1') echo 'selected'; } ?>>XS</option>
		<option value="0.2" <?php if(!empty($suitvariation)) { if($suitvariation == '0.2') echo 'selected'; } ?>>S</option>
		<option value="0.3" <?php if(!empty($suitvariation)) { if($suitvariation == '0.3') echo 'selected'; } ?>>R</option>
		<option value="0.4" <?php if(!empty($suitvariation)) { if($suitvariation == '0.4') echo 'selected'; } ?>>L</option>
		<option value="0.5" <?php if(!empty($suitvariation)) { if($suitvariation == '0.5') echo 'selected'; } ?>>XL</option>
		<option value="0.6" <?php if(!empty($suitvariation)) { if($suitvariation == '0.6') echo 'selected'; } ?>>XXL</option>
		</select> US sizes
        </td>
      </tr>

      <tr>
        <td class="AGENCYregtableleft">Shirt:</td>
        <td class="AGENCYregtableright">
		<select name="shirt">
		<option value=""> -- Select Size -- </option>
		<option value="S" <?php if(!empty($shirt)) { if($shirt == 'S') echo 'selected'; } ?>>Small</option>
		<option value="M" <?php if(!empty($shirt)) { if($shirt == 'M') echo 'selected'; } ?>>Medium</option>
		<option value="L" <?php if(!empty($shirt)) { if($shirt == 'L') echo 'selected'; } ?>>Large</option>
		<option value="XL" <?php if(!empty($shirt)) { if($shirt == 'XL') echo 'selected'; } ?>>X-Large</option>
		<option value="XXL" <?php if(!empty($shirt)) { if($shirt == 'XXL') echo 'selected'; } ?>>XX-Large</option>
		<option value="Other" <?php if(!empty($shirt)) { if($shirt == 'Other') echo 'selected'; } ?>>Other</option>
		</select>
        </td>
      </tr>

      <tr>
        <td class="AGENCYregtableleft">Neck:</td>
        <td class="AGENCYregtableright">
		<select class="thin" name="neck">
		<?php
		for($i=8; $i<=30; $i=$i+.5) {
			$insert = "";
			if($neck == $i) {
				$insert = ' selected';
			}
			echo "<option value=\"$i\"$insert>$i</option>";
		}
		?>
		</select> Inches
        </td>
      </tr>

      <tr>
        <td class="AGENCYregtableleft">Sleeve:</td>
        <td class="AGENCYregtableright">
		<select class="thin" name="sleeve">
		<?php
		for($i=20; $i<=50; $i++) {
			$insert = "";
			if($sleeve == $i) {
				$insert = ' selected';
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
		<?php
		for($i=8; $i<=50; $i++) {
			$insert = "";
			if($inseam == $i) {
				$insert = ' selected';
			}
			echo "<option value=\"$i\"$insert>$i</option>";
		}
		?>
		</select> Inches
        </td>
      </tr>

<?php
} else if($gender == 'F' || $gender == 'O') {
?>
      <tr>
        <td class="AGENCYregtableleft">Bust:</td>
        <td class="AGENCYregtableright">
		<select class="thin" name="bust">
		<?php
		for($i=20; $i<=60; $i++) {
			$insert = "";
			if($bust == $i) {
				$insert = ' selected';
			}
			echo "<option value=\"$i\"$insert>$i</option>";
		}
		?>
		</select> Inches
        </td>
      </tr>
      
      <tr>
        <td class="AGENCYregtableleft">Cup Size:</td>
        <td class="AGENCYregtableright">
		<select name="cup">
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
        </td>
      </tr>
      <tr>
        <td class="AGENCYregtableleft">Hips:</td>
        <td class="AGENCYregtableright">
		<select class="thin" name="hips">
		<?php
		for($i=20; $i<=60; $i++) {
			$insert = "";
			if($hips == $i) {
				$insert = ' selected';
			}
			echo "<option value=\"$i\"$insert>$i</option>";
		}
		?>
		</select> Inches
        </td>
      </tr>

      <tr>
        <td class="AGENCYregtableleft">Dress:</td>
        <td class="AGENCYregtableright">
		<select class="thin" name="dress">
		<?php
		for($i=0; $i<=40; $i++) {
			$insert = "";
			if($dress == $i) {
				$insert = ' selected';
			}
			echo "<option value=\"$i\"$insert>$i</option>";
		}
		?>
		</select> US sizes
        </td>
      </tr>
<?php
}
?>

      </table>
</span>


<span id="FormExperience" style="display:none">
<table width="430" border="0" cellpadding="3" cellspacing="2" bgcolor="white">
      <tr>
        <td class="AGENCYregtableleft">Experience Level:</td>
        <td class="AGENCYregtableright" style="color:#FFFFFF">
        <div id="expcolors" style="display:none; position:absolute; margin-top:30px">
<?php
echo mysql_result(mysql_query("SELECT varvalue FROM agency_vars WHERE varname='levelsExp'"), 0, 'varvalue');
?>
        </div>
<?php
$checkfirst = true; // make sure one radio button is selected by default

foreach($experiencearray as $key=>$exp) {
	echo '<input type="radio" name="experience" value="' . ($key) . '"';
	if (isset($experience)) {
		if($experience == ($key)) echo ' checked';
	}
	if (!is_admin()) {
		echo ' disabled';
	}
	echo ' /> <span style="background-color:' .
		$experiencecolors[$key] . '; padding:2px">' . $exp . '</span>';
}

	if (!is_admin()) {
		echo '&nbsp;&nbsp;&nbsp;<a class="thickbox AGENCY_graybutton" onclick="loaddiv(\'popupcontent\', \'expchange\')" href="#TB_inline?height=400&width=450&inlineId=hiddenModalContent">change</a>';
	}
	echo '<div onmouseover="document.getElementById(\'expcolors\').style.display=\'block\'"' .
		' onmouseout="document.getElementById(\'expcolors\').style.display=\'none\'" style="' .
		'font-weight:bold; color:black; padding:2px; font-size:14px; background-color:white; float:right">?</div>';

		if(!empty($exp_request)) {
			echo '<div align="center" style="padding:15px; color:black">pending request to change to: <b><font color="' . $experiencecolors[$exp_request] . '">' . $experiencearray[$exp_request] . '</font></div>';
		}
?>
        </td>
      </tr>
      
      <tr>
        <td class="AGENCYregtableleft">Teleprompter Experience:</td>
        <td class="AGENCYregtableright">
        	<input type="radio" name="teleprompter" value="1" <?php if (isset($teleprompter)) { if($teleprompter=='1') echo 'checked'; } ?> /> Yes&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        	<input type="radio" name="teleprompter" value="0" <?php if (empty($teleprompter)) { echo 'checked';} ?> /> No
        </td>
      </tr> 
      
      <tr>
        <td class="AGENCYregtableleft">Hosting Experience:</td>
        <td class="AGENCYregtableright">
        	<input type="radio" name="hosting" value="1" <?php if (isset($hosting)) { if($hosting=='1') echo 'checked'; } ?> /> Yes&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        	<input type="radio" name="hosting" value="0" <?php if (empty($hosting)) { echo 'checked';} ?> /> No
        </td>
      </tr>  
      
      <tr>
        <td class="AGENCYregtableleft">Improv/Stand Up Comedy Experience:</td>
        <td class="AGENCYregtableright">
        	<input type="radio" name="comedy" value="1" <?php if (isset($comedy)) { if($comedy=='1') echo 'checked'; } ?> /> Yes&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        	<input type="radio" name="comedy" value="0" <?php if (empty($comedy)) { echo 'checked';} ?> /> No
        </td>
      </tr>      
      

      <tr>
        <td class="AGENCYregtableleft">Categories:</td>
        <td class="AGENCYregtableright">

        <div id="catexpand" style="height:80px; overflow:hidden;">
<?php
for($i=0; isset($categoryarray_1[$i]); $i++) {
	echo '<input type="checkbox" name="category[]" value="' . $categoryarray_1[$i] . '"';
   	if(in_array($categoryarray_1[$i], $category)) echo ' checked';
	echo ' /> ' . $categoryarray_1[$i] . '<br />';
}
echo '-----------------------<br />';
for($i=0; isset($categoryarray_2[$i]); $i++) {
	echo '<input type="checkbox" name="category[]" value="' . $categoryarray_2[$i] . '"';
   	if(in_array($categoryarray_2[$i], $category)) echo ' checked';
	echo ' /> ' . $categoryarray_2[$i] . '<br />';
}
?>		</div>
		<a id="expandlink" href="javascript:void(0)" onclick="getElementById('catexpand').style.height=''; getElementById('expandlink').style.display='none'">click to show full list</a>

        </td>
      </tr>
      
      <tr>
        <td class="AGENCYregtableleft">Vocal Range:</td>
        <td class="AGENCYregtableright">
<?php
for($i=0; isset($voicearray[$i]); $i++) {
	echo '<input type="checkbox" name="voice[]" value="' . $voicearray[$i] . '"';
   	if(in_array($voicearray[$i], $voice)) echo ' checked';
	echo ' /> ' . $voicearray[$i] . '<br />';
}
?>

        </td>
      </tr>      
      

      <tr>
        <td class="AGENCYregtableleft">Union Status:</td>
        <td class="AGENCYregtableright">
<?php
for($i=0; isset($unionarray[$i]); $i++) {
	echo '<input type="checkbox" name="unions[]" id="unions[' . $i . ']" value="' . $unionarray[$i] . '"';
	if(in_array($unionarray[$i], $unions)) echo ' checked';
	echo ' /> ' . $unionarray[$i] . '<br />';
}

echo 'Other:<input type="text" name="unions[]" id="unions[' . ++$i . ']" value="';
foreach($unions as $un) {
	if(!in_array($un, $unionarray)) {
		echo $un;
		$showblank = false;
	}
}
echo '" />';
?>
        </td>
      </tr>
      <tr>
        <td class="AGENCYregtableleft" valign="top">Languages:</td>
        <td class="AGENCYregtableright">
        	<textarea name="skills_language" class="fixwidth" rows="2"><?php if(isset($skills_language)) echo $skills_language; ?></textarea>
        </td>
      </tr>
      <tr>
        <td class="AGENCYregtableleft" valign="top">Sports & Music:</td>
        <td class="AGENCYregtableright">
        	<textarea name="skills_sports_music" class="fixwidth" rows="2"><?php if(isset($skills_sports_music)) echo $skills_sports_music; ?></textarea>
        </td>
      </tr>
      <tr>
        <td class="AGENCYregtableleft" valign="top">Other Skills/Physical Traits:</td>
        <td class="AGENCYregtableright">
        	<textarea name="skills_other" class="fixwidth" rows="2"><?php if(isset($skills_other)) echo $skills_other; ?></textarea>
        </td>
      </tr>
      </table>
</span>

<span id="FormBio" style="display:none">
<table width="430" border="0" cellpadding="3" cellspacing="2" bgcolor="white">
      <tr>
        <td class="AGENCYregtableleft" valign="top">Bio:</td>
        <td class="AGENCYregtableright">
        	<textarea name="bio" rows="15" class="fixwidth"><?php if(isset($bio)) echo $bio; ?></textarea>
        </td>
      </tr>  
      </table>
</span>

<span id="FormLinks" style="display:none">
<table width="430" border="0" cellpadding="3" cellspacing="2" bgcolor="white">
      <tr>
        <td class="AGENCYregtableleft" valign="top">Links:</td>
        <td class="AGENCYregtableright">
<?php
for($i=1; $i <= 10; $i++) {
?>

			Description:
			<br />
			<input type="text" class="fixwidth" maxlength="250" name="link_desc[]"<?php if(isset($link_desc[$i])) echo ' value="' . $link_desc[$i] . '"'; ?> />
        	<br />
			Link: 
			<br />
			<input type="text" class="fixwidth" maxlength="250" name="link[]"<?php if(isset($link[$i])) echo ' value="' . $link[$i] . '"'; ?> />
        
			<br /><br />
<?php
}
?>
        </td>
      </tr>
      </table>
</span>

<span id="FormPrivacy Settings" style="display:none">
<table width="430" border="0" cellpadding="3" cellspacing="2" bgcolor="white">
<?php
$sql = "SELECT * FROM agency_privacy WHERE user_id='$userid'";
$result = mysql_query($sql);
while ($row = sql_fetchrow($result))
{
	$privacysettings[$row['what']] = $row['who'];
}
foreach($privacylabels as $thissetting => $label) {
?>
      <tr>
        <td class="AGENCYregtableleft"><?php echo $label; ?>:</td>
        <td class="AGENCYregtableright">
        	<select name="privacy[<?php echo $thissetting; ?>]">
<?php
	foreach ($privacylevels[$thissetting] as $key=>$level) {
		echo '<option value="' . $key . '"';
		if (!empty($privacysettings[$thissetting])) {  if ($privacysettings[$thissetting] == $key)  echo ' selected'; }
		echo '>' . $privacylevels[$thissetting][$key] . '</option>';
	}
?>
        	</select>
        </td>
      </tr>
<?php
}
?>
<tr>
        <td class="AGENCYregtableleft">Phone Number:</td>
        <td class="AGENCYregtableright">NOTE: Your Phone number is only visible to clients & casting directors, and the agency's staff. It is never visible directly in your profile to the public. 
        </td>
      </tr>

      </table>
</span>
    <br />
	<input type="hidden" value="<?php echo time(); ?>" name="creation_time"/>
	<input type="hidden" value="<?php echo agency_add_form_key('myaccount'); ?>" name="form_token"/>


<br /><br />


</div>
<div style="width:330px; float:right; border:1px solid #999; background-image:url(images/rightmenu_bg.gif); padding:10px">
<?php echo mysql_result(mysql_query("SELECT varvalue FROM agency_vars WHERE varname='TalentSettings'"), 0, 'varvalue'); ?>
</div>
<br clear="all" />

</div>
</form>
</div>
