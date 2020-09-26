<?php
session_start();

include('../includes/mysql_connect.php');
include('../includes/vars.php');
include('../includes/agency_functions.php');
include('../includes/regionarrays.php');
include('../forms/definitions.php');

$shownext = true; // default is to show the next button, but there are cases we may not want it.

if(!empty($_SESSION['user_id'])) { // check if user is logged in
	$uid = (int) $_SESSION['user_id'];
}

if(!empty($_REQUEST['step'])) {
	$step = (int) $_REQUEST['step'];
} else {
	$step = 1;
}
if(!empty($_REQUEST['substep'])) {
	$substep = (int) $_REQUEST['substep'];
} else {
	$substep = 1;
}

?>

        
<form action="javascript:void" name="signupform" id="signupform" method="post">
    <div id="signup_ajax" align="center" style="width:600px; position:relative; border: 1px solid gray; padding:10px;">
    <div style="min-height:250px">
<?php

if($step == 1) {
	$num_substeps = 3; // number of substeps set for each Step for number of dots at bottom
	if($substep == 1) {
		if(isset($uid)) {
			if(is_int($uid) && $uid > 0) {
				$step = 2;
				$substep = 1; // if they have a user_id then we will assume they've gone through substep 3 where data is entered; jump to sub 4
				unset($_POST['submit']);
			}
		} else {
			if(!empty($_POST['submit'])) {
				if(!empty($_POST['agree_terms'])) {
					$terms = true;
				} else {
					$terms = false;
					echo '<p style="color:red">Please check your agreement to the terms and conditions.</p>';
				}
				if (!empty($_POST['agree_email'])) {
					$agree_email = TRUE;
				} else {
					$agree_email = FALSE;
					echo '<p><font color="red">Please acknowledge communication checkbox.</font></p>';
				}
				if (eregi ('^[[:alnum:]]{4,30}$', stripslashes(trim($_POST['username'])))) {
					$un = escape_data($_POST['username']);
					$sql = "SELECT user_id FROM forum_users WHERE username='$un'";
					if(mysql_num_rows(mysql_query($sql)) != 0) {
						$un = FALSE;
						echo '<p style="color:red">The Username you selected has already been taken by another member.  Please select a different Username.</p>';
					}
				} else {
					$un = FALSE;
					echo '<p style="color:red">Please enter a valid username (between 4 and 30 alphanumeric characters, no spaces).</p>';
				}					
				
				// Check for a password and match against the confirmed password.
				if (eregi ('^[[:alnum:]]{6,20}$', stripslashes(trim($_POST['joinpassword']))) && eregi ('^[[:alnum:]]{6,20}$', stripslashes(trim($_POST['confirmpassword'])))) {
					$p = escape_data($_POST['joinpassword']);
					$p2 = escape_data($_POST['confirmpassword']);
					if($p != $p2) {
						$p = FALSE;
						echo '<p style="color:red">Your Password entries did not match.  Please be sure both the Password and Confirm Password fields are identical.</p>';
					}
				} else {
					$p = FALSE;
					echo '<p style="color:red">Please enter a valid password (between 6 and 20 alphanumeric characters)</p>';
				}
				
				if($p && $un && $terms && $agree_email) {
					// temporarily store this info in a SESSION as without email the account can't be created
					$_SESSION['un'] = $un;
					$_SESSION['p'] = $p; // not hashed in session because it has to be sent in email
					$substep = 2; // go to next step
					unset($_POST['submit']);					
				}
			}
			if($substep == 1) {	// if substep is still equal "1" then show this form (otherwise it'll just go on to step 2)
?>
<div class="signupinfo">Please choose a username and password</div>
<div class="signupfield"><span class="signuplabel">Username:</span><span class="signupentry"><input type="text" name="username"></span></div>
<div class="signupfield"><span class="signuplabel">Password:</span><span class="signupentry"><input type="password" name="joinpassword"></span></div>
<div class="signupfield"><span class="signuplabel">Confirm Password:</span><span class="signupentry"><input type="password" name="confirmpassword"></span></div>
<br clear="all" /><br clear="all" />
<div align="left" style="display:inline-block">
<input name="agree_terms" type="checkbox" /> I have read and agree to the <a href="index2.php?pageid=68" target="_blank">terms and conditions</a>

    <br clear="all" />                   
               <input id="agree_email" name="agree_email" type="checkbox" /> Check box to acknowledge that you will be receiving communications regarding<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; castings, callbacks, bookings and general announcements from The Agency Online.    
</div>

<?php
			}
		}
	} // end substep 1



	if($substep == 2) {
		
		if(false) { // CHECK HERE IF LOCATION IS IN DATABASE ALREADY
			$substep = 3;
			unset($_POST['submit']);
		} else {
			if(!empty($_POST['submit'])) {
				if(($_POST['location'] == 'Other') && !empty($_POST['otherlocation'])) { 
					$location = escape_data($_POST['otherlocation']);
				} else if(!empty($_POST['location']) && $_POST['location'] != 'Other') {
					$location = escape_data($_POST['location']);
				} else {
					$location = FALSE;
					echo '<p style="color:red">Please enter your primary location.</p>';
				}					
					
	
					
				if($location) {
					// temporarily store this info in a SESSION as without email the account can't be created
					$_SESSION['location'] = $location;
					$substep = 3; // go to next substep	
					unset($_POST['submit']);				
				
				}
			}
			if($substep == 2) {	// if substep is still equal "2" then show this form (otherwise it'll just go on to step 3)
?>
<div class="signupinfo">Please Select your Region</div>
<div class="signupfield"><span class="signuplabel">Location:</span><span class="signupentry">
<select name="location" onchange="if(this.value=='Other') { document.getElementById('otherlocation').style.display=''; } else { document.getElementById('otherlocation').style.display='none'; }">
<?php
			foreach($locationarray as $location) {
				echo '<option value="' . $location . '">' . $location . '</option>';
			}
?>
<option value="Other">Other</option>
</select>
</span></div>
<div id="otherlocation" class="signupfield" style="display:none"><span class="signuplabel">Location:</span><span class="signupentry"><input type="text" name="otherlocation"></span></div>
<?php					
			}
		}			
	}
	
	

	if($substep == 3) {
		
		if(false) { // CHECK HERE IF NAME, EMAIL AND PHONE ARE ALREADY IN DATABASE [not needed??]
			$step = 2;
			$substep = 1;
			unset($_POST['submit']);
		} else {
			if(!empty($_POST['submit'])) {
				// Check for a first name.
				if (!empty($_POST['firstname'])) {
					$firstname = escape_data($_POST['firstname']);
				} else {
					$firstname = FALSE;
					echo '<p style="color:red">Please fill the First Name field.</p>';
				}
			
				// Check for an email address.
				if (eregi ('^[[:alnum:]][a-z0-9_\.\-]*@[a-z0-9\.\-]+\.[a-z]{2,4}$', stripslashes(trim($_POST['email'])))) {
					if($_POST['email'] == $_POST['confirmemail']) {
						$e = escape_data(strtolower($_POST['email']));
						$sql = "SELECT user_id FROM forum_users WHERE user_email='$e'";
						if(mysql_num_rows(mysql_query($sql)) != 0) {
							$e = FALSE;
							echo '<p style="color:red">The Email you entered is already being used by another account.  Each account must have a unique email.  If you have forgotten your password, you may retrieve it <a href="forgotpassword.php">here</a>.</p>';
						}
					} else {
						$e = FALSE;
						echo '<p style="color:red">Your confirmation email did not match.</p>';
					}
				} else {
					$e = FALSE;
					echo '<p style="color:red">Please enter a valid email address.</p>';
				}
	
				// Check for a first name.
				if (!empty($_POST['phone'])) {
					$phone = escape_data($_POST['phone']);
				} else {
					$phone = FALSE;
					echo '<p style="color:red">Please fill the Phone field.</p>';
				}	
	
				// no special checking on these:
				$lastname = escape_data($_POST['lastname']);
	
				if($firstname && $e && $phone && !empty($_SESSION['p']) && !empty($_SESSION['un'])) {
					// get info from stored vars
					$un = $_SESSION['un'];
					$pass_orig = $_SESSION['p'];
					$p = _hash($_SESSION['p']);
					
					$location = $_SESSION['location'];
					
					
					//	ENTER INFO INTO DATABASE AND RETRIEVE USER ID
					$user_type = 1;
					$user_ip = getRealIpAddr();
					$user_regdate = time();
	
					$query = "INSERT INTO forum_users (username, username_clean, user_email, user_password, user_type, user_ip, user_regdate) VALUES ('$un', '$un', '$e', '$p', '$user_type', '$user_ip', '$user_regdate')";
					mysql_query($query);
					if(mysql_affected_rows() == 1) {
						// Register user...
						$user_id = mysql_insert_id();
						
						if(is_int($user_id)) {	
							$_SESSION['user_id'] = $user_id;
							$uid = $user_id; // so it's there when we jump to the next step.
							
							// place firstname and lastname (profile vars) in agency_users
							$type = 'talent';
							$registration_date = time();
				
							mysql_query("INSERT INTO agency_profiles (user_id, firstname, lastname, account_type, location, phone, registration_date, last_visit_ip) VALUES ('$user_id', '$firstname', '$lastname', '$type', '$location', '$phone', '$registration_date', '$user_ip')");
							// automated first Highlight entry
							$highlight = $firstname . ' has joined The Agency!';
							$sql = "INSERT INTO agency_profile_highlights (user_id, highlight) VALUES ('$user_id', '$highlight')";
							mysql_query($sql);
							
							// create Agency as first fan
							$sql = "INSERT INTO agency_fans (user_id, fan_id) VALUES ('$user_id', '2')";
							mysql_query($sql);	
							// recount fans
							$sql = "SELECT * FROM agency_fans WHERE user_id='$user_id'";
							$result = mysql_query($sql);
							$fans = mysql_num_rows($result);
							$sql = "UPDATE agency_profiles SET fans='$fans' WHERE user_id='$user_id'";
							mysql_query($sql);	
				
							
							
							// SEND WELCOME EMAIL!
							// if(isset($_SESSION['test'])) {
								// echo 'testing: ';
								$subject = 'Welcome to The Agency Online';
								$message = file_get_contents('../adminXYZ/email_templates/admin_welcome_inactive.txt');
								$message = str_replace("{USERNAME}", $un, $message);
								$message = str_replace("{PASSWORD}", $pass_orig, $message);
								// echo $message;
								$headers = 'From: info@theagencyonline.com' . "\r\n" .
									'Reply-To: info@theagencyonline.com' . "\r\n";
								
								mail($e, $subject, $message, $headers);					
							// }
							$step = 2;
							$substep = 1; // go to next substep	
							unset($_POST['submit']);	
						}
					}
				} else {
					// echo 'x';
				}
			}
			if($substep == 3) {	// if substep is still the same then show this form (otherwise it'll just go on to next step)
?>
<div class="signupinfo">Please enter the following</div>
<div class="signupfield"><span class="signuplabel">First Name:</span><span class="signupentry"><input type="text" name="firstname" <?php if(!empty($_POST['firstname'])) { echo 'value="' . $_POST['firstname'] . '"'; } ?>></span></div>
<div class="signupfield"><span class="signuplabel">Last Name:</span><span class="signupentry"><input type="text" name="lastname" <?php if(!empty($_POST['lastname'])) { echo 'value="' . $_POST['lastname'] . '"'; } ?>></span></div>
<div class="signupfield"><span class="signuplabel">Email:</span><span class="signupentry"><input type="text" name="email" <?php if(!empty($_POST['email'])) { echo 'value="' . $_POST['email'] . '"'; } ?>></span></div>
<div class="signupfield"><span class="signuplabel">Confirm Email:</span><span class="signupentry"><input type="text" name="confirmemail" <?php if(!empty($_POST['confirmemail'])) { echo 'value="' . $_POST['confirmemail'] . '"'; } ?>></span></div>
<div class="signupfield"><span class="signuplabel">Phone:</span><span class="signupentry"><input type="text" name="phone" <?php if(!empty($_POST['phone'])) { echo 'value="' . $_POST['phone'] . '"'; } ?>></span></div>
<?php					
				
			}
		}			
	}
}


if($step == 2) {
	$num_substeps = 4;
	if($substep == 1) {
		// echo 'IN STEP 2.';
		
        $sql = "SELECT ethnicity FROM agency_profile_ethnicities WHERE user_id='$uid'";
        $result = mysql_query($sql);
        $ethnicities = array();
        while ($row = sql_fetchrow($result)) {
           $ethnicities[] = $row['ethnicity'];
        }
        unset($result);		
		
        $sql = "SELECT gender, birthdate FROM agency_profiles WHERE user_id='$uid'";
        $result = mysql_query($sql);
        if ($row = sql_fetchrow($result)) {
           $gender = $row['gender'];
		   $birthdate = $row['birthdate'];
        }
        unset($result);		
		
		
		if(!empty($ethnicities) && !empty($gender) && !empty($birthdate)) { // CHECK HERE IF LOCATION IS IN DATABASE ALREADY
			$substep = 2;
			unset($_POST['submit']);
		} else {			
			if(!empty($_POST['submit'])) {
				
			
				$gender = $_POST['gender'];
				$ethnicities = array();
				$ethnicities = $_POST['ethnicities'];

				if(!empty($_POST['Year_dob']) && !empty($_POST['Month_dob']) && !empty($_POST['Day_dob'])) {
				$birthdate = escape_data($_POST['Year_dob']) . '-' . escape_data($_POST['Month_dob']) . '-' . escape_data($_POST['Day_dob']);
				} else {
				 $birthdate = NULL;
				}						
	
					
				if(!empty($gender) && !empty($birthdate) && !empty($ethnicities)) {
					
					 // ======================  place into ethnicities ===============
					 $sql = "DELETE FROM agency_profile_ethnicities WHERE user_id='$uid'";
					 mysql_query($sql); // delete category settings, and then reset with new ones
					 foreach ($ethnicities as $eth) {
						if (!empty($eth)) {
						   $eth = escape_data($eth);
						   $sql = "INSERT INTO agency_profile_ethnicities (user_id, ethnicity) VALUES ('$uid', '$eth')";
						   mysql_query($sql); // insert union
						}
					 }					
					
					$sql_ary = array('gender' => request_var('gender', ''), 'birthdate' => $birthdate);					
					
					$sql = 'UPDATE agency_profiles SET ' . sql_build_array('UPDATE', $sql_ary) .
					" WHERE user_id = '$uid'";
					
					if (mysql_query($sql)) {
						$substep = 2; // go to next substep	
						unset($_POST['submit']);
					}					
			
				
				}
			}
			if($substep == 1) {	// if substep is still equal "1" then show this form (otherwise it'll just go on to step 2)
?>
        	




<div class="signupinfo">Please enter the following</div>
<div class="signupfield"><span class="signuplabel">Gender:</span><span class="signupentry"><input type="radio" name="gender" value="M" <?php if (!empty($gender)) { if($gender=='M') echo 'checked'; } ?> /> Male&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        	<input type="radio" name="gender" value="F" <?php if (!empty($gender)) {if($gender=='F') echo 'checked';} ?> /> Female&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        	<input type="radio" name="gender" value="O" <?php if (!empty($gender)) {if($gender=='O') echo 'checked';} ?> /> Other</span></div>
<div class="signupfield"><span class="signuplabel">Ethnicity:</span><span class="signupentry">		<?php
		
		for($i=0; isset($ethnicityarray[$i]); $i++) {
			echo '<input type="checkbox" name="ethnicities[]" id="ethnicities[' . $i . ']" value="' . $ethnicityarray[$i] . '"';
			if(in_array($ethnicityarray[$i], $ethnicities)) echo ' checked';
			echo ' /> ' . $ethnicityarray[$i] . '<br />';
		}
		?></span></div>
<div class="signupfield"><span class="signuplabel">Date of Birth:</span><span class="signupentry"><?php
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
NOTE: Your actual birthdate or age will never be visible to anyone on the site, but you have to enter a birthday to make you searchable for castings.</span></div>
</div>
<?php					
			}
		}			
	}
	
	
	
	
	
	
	
	
	
	
	if($substep == 2) {	
	
		$query = "SELECT * FROM agency_profiles WHERE user_id='$uid'";
		$result = @mysql_query($query);
		if ($row = @mysql_fetch_array($result, MYSQL_ASSOC)) {
			
			$gender = $row['gender'];
			
			// get stats:
			$height_feet = floor($row['height'] / 12);
			$height_inches = $row['height'] % 12;
			$weight = $row['weight'];
			$waist = $row['waist'];
			$shoe = $row['shoe'];
			
			
			$suit = $row['suit'];
			$suit = floor($row['suit']);
			$suitvariation = (string) ($row['suit'] - $suit);
			$shirt = $row['shirt'];
			$neck = $row['neck'];
			$sleeve = $row['sleeve'];
			$inseam = $row['inseam'];
			$bust = $row['bust'];
			$cup = $row['cup'];
			$hips = $row['hips'];
			$dress = $row['dress'];
		}	
	
	
	
		
		if(!empty($height_feet) && !empty($weight) && !empty($waist) && !empty($shoe)) { // move on
			$step = 2;
			$substep = 3; 
			unset($_POST['submit']);
		} else {		
			if(!empty($_POST['submit'])) {
				$height = (int)($_POST['height_feet'] * 12) + $_POST['height_inches'];
				$height_feet = $_POST['height_feet'];
				$height_inches = $_POST['height_inches'];
				$weight = $_POST['weight'];
				$waist = $_POST['waist'];
				$shoe = $_POST['shoe'];
				
				
				
				$sql_ary = array('height' => $height, 'weight' => request_var('weight', ''), 'waist' =>
				request_var('waist', ''), 'shoe' => request_var('shoe', ''));
				
				if (isset($_POST['suit']) && isset($_POST['suitvariation'])) {
					$suit = $_POST['suit'];
					$suitvariation = $_POST['suitvariation'];
					$sql_ary['suit'] = request_var('suit', '') + request_var('suitvariation', '');
				}
				if (isset($_POST['shirt'])) {
					$shirt = $_POST['shirt'];
					$sql_ary['shirt'] = request_var('shirt', '');
				}
				if (isset($_POST['neck'])) {
					$neck = $_POST['neck'];
					$sql_ary['neck'] = request_var('neck', '');
				}
				if (isset($_POST['sleeve'])) {
					$sleeve = $_POST['sleeve'];
					$sql_ary['sleeve'] = request_var('sleeve', '');
				}
				if (isset($_POST['inseam'])) {
					$inseam = $_POST['inseam'];
					$sql_ary['inseam'] = request_var('inseam', '');
				}
				if (isset($_POST['bust'])) {
					$bust = $_POST['bust'];
					$sql_ary['bust'] = request_var('bust', '');
				}
				if (isset($_POST['cup'])) {
					$cup = $_POST['cup'];
					$sql_ary['cup'] = request_var('cup', '');
				}		 
				if (isset($_POST['hips'])) {
					$hips = $_POST['hips'];
					$sql_ary['hips'] = request_var('hips', '');
				}
				if (isset($_POST['dress'])) {
					$dress = $_POST['dress'];
					$sql_ary['dress'] = request_var('dress', '');
				}
				
				$sql = 'UPDATE agency_profiles SET ' . sql_build_array('UPDATE', $sql_ary) .
				" WHERE user_id = '$uid'";


				if(!empty($height_feet) && !empty($weight) && !empty($waist) && !empty($shoe)) { 					
					if (mysql_query($sql)) {
						$substep = 3; // go to next substep	
						unset($_POST['submit']);
					}
				} else {
					echo '<p style="color:red">Please enter all values.</p>';
				}
			}
			if($substep == 2) {	// if substep is still equal "2" then show this form (otherwise it'll just go on to step 3)
	
?>

<div class="signupinfo">Please enter the following</div>

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

<?php					
			}
		}			
	}
	
	
	
	
	
	
	
	if($substep == 3) {	
		$query = "SELECT * FROM agency_profiles WHERE user_id='$uid'";
		$result = @mysql_query($query);
		if ($row = @mysql_fetch_array($result, MYSQL_ASSOC)) {
			$hair = $row['hair'];
			$eyes = $row['eyes'];
			$tattoos = $row['tattoos'];
			$piercings = $row['piercings'];
		}

		if(!empty($hair) && !empty($eyes)) { // move on
			$step = 2;
			$substep = 4; 
			unset($_POST['submit']);
		} else {			
			if(!empty($_POST['submit'])) {
				$hair = $_POST['hair'];
				$eyes = $_POST['eyes'];
				$tattoos = $_POST['tattoos'];
				$piercings = $_POST['piercings'];

				
				$sql_ary = array('hair' => request_var('hair', ''), 'eyes' =>
				request_var('eyes', ''), 'tattoos' =>
				request_var('tattoos', ''), 'piercings' =>
				request_var('piercings', ''));

				
				$sql = 'UPDATE agency_profiles SET ' . sql_build_array('UPDATE', $sql_ary) .
				" WHERE user_id = '$uid'";
				
				if (mysql_query($sql)) {
					$step = 2;
					$substep = 4; // go to next substep	
					unset($_POST['submit']);
				}					
			}
			if($substep == 3) {	// if substep is still equal "2" then show this form (otherwise it'll just go on to step 3)
	
?>


<div class="signupinfo">Please enter the following</div>

<table width="430" border="0" cellpadding="3" cellspacing="2" bgcolor="white">
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

<?php					
			}
		}			
	}	
	
	
	
	
	if($substep == 4) {	
		$query = "SELECT * FROM agency_profiles WHERE user_id='$uid'";
		$result = @mysql_query($query);
		if ($row = @mysql_fetch_array($result, MYSQL_ASSOC)) {
			$country = $row['country'];
			$state = $row['state'];
			$zip = $row['zip'];
			$city = $row['city'];
			$location = $row['location'];
		}

		if(!empty($country) && !empty($state) && !empty($city) && !empty($zip)) { // move on
			$step = 3;
			$substep = 1; 
			unset($_POST['submit']);
		} else {			
			if(!empty($_POST['submit'])) {
				$country = $_POST['country'];
				$state = $_POST['state'];
				$city = $_POST['city'];
				$zip = $_POST['zip'];

				
				$sql_ary = array('country' => request_var('country', ''), 'state' =>
				request_var('state', ''), 'city' =>
				request_var('city', ''), 'zip' =>
				request_var('zip', ''));

				
				$sql = 'UPDATE agency_profiles SET ' . sql_build_array('UPDATE', $sql_ary) .
				" WHERE user_id = '$uid'";
				
				if (mysql_query($sql)) {
					$step = 3;
					$substep = 1; // go to next substep	
					unset($_POST['submit']);
				}					
			}
			if($substep == 4) {	// if substep is still equal "2" then show this form (otherwise it'll just go on to step 3)
	
?>


<div class="signupinfo"><small>You have selected <?php echo $location; ?> as your region. Castings in <?php echo $location; ?> will display on our homepage by default, though you can choose to see other regions.
<br /><br />
In order for you to be searchable by our clients, please enter your address below. (For Zip code radius searchers, etc)</small></div>

<table width="430" border="0" cellpadding="3" cellspacing="2" bgcolor="white">
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
      
      </table>

<?php					
			}
		}			
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
} // end of Step 2


if($step == 3) {
	$num_substeps = 4;
	if($substep == 1) {	
		$success = false; // flag for showing form
        //============== get unions ====================
        $sql = "SELECT union_name FROM agency_profile_unions WHERE user_id='$uid'";
        $result = mysql_query($sql);
        $unions = array();
        while ($row = sql_fetchrow($result)) {
           $unions[] = $row['union_name'];
        }
        unset($result);
        //============== end: get unions ====================	
	
        //============== get categories ====================
        $sql = "SELECT category FROM agency_profile_categories WHERE user_id='$uid'";
        $result = mysql_query($sql);
        $category = array();
        while ($row = sql_fetchrow($result)) {
           $category[] = $row['category'];
        }
        unset($result);
        //============== end: get categories ====================	
		
		if(!empty($unions) && !empty($category)) {
			$substep = 2;
			$step = 3; // this is probably redundant here as it's already defined as this.
			unset($_POST['submit']);
		} else if(isset($_POST['submit'])) {
			if(!empty($_POST['category']) && !empty($_POST['unions'])) {
	
				 // ======================  place into unions ===============
				 $unionset = false; // if no unions are selected, set as non-union
				 $sql = "DELETE FROM agency_profile_unions WHERE user_id='$uid'";
				 mysql_query($sql); // delete category settings, and then reset with new ones
				 $unions = array();
				 $unions = $_POST['unions'];
				 foreach ($unions as $un) {
					if (!empty($un)) {
					   $un = escape_data($un);
					   $sql = "INSERT INTO agency_profile_unions (user_id, union_name) VALUES ('$uid', '$un')";
					   mysql_query($sql); // insert union
					   $unionset = true;
					}
				 }
				 if(!$unionset) {
				   $sql = "INSERT INTO agency_profile_unions (user_id, union_name) VALUES ('$uid', 'Non-Union')";
				   mysql_query($sql); // insert union
				 }
				 // ======================  end: place into unions ===============	
				
				 // ======================  place into categories ===============
				 $sql = "DELETE FROM agency_profile_categories WHERE user_id='$uid'";
				 mysql_query($sql); // delete category settings, and then reset with new ones
				 $category = array();
				 $category = $_POST['category'];
				 if(!empty($category)) {
					 foreach ($category as $cat) {
						if (!empty($cat)) {
						   $cat = escape_data($cat);
						   $sql = "INSERT INTO agency_profile_categories (user_id, category) VALUES ('$uid', '$cat')";
						   mysql_query($sql); // insert category
						}
					 }
				 }
				 $success = true;
			} else {
				echo 'You must select at least one item from each list.';
			}
			 // ======================  end: place into categories ===============	
		}
		if($success) {
			$step = 3;
			$substep = 2;
			unset($_POST['submit']);
		}
		if($substep == 1) {
?>
<b>Please select the areas you are active in</b>
<table width="430" border="0" cellpadding="3" cellspacing="2" bgcolor="white">
  <tr>
        <td class="AGENCYregtableleft">Categories:</td>
        <td class="AGENCYregtableright">
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
</table>

<?php

		}
	} // end step 3 substep 1
	



	
	if($substep == 2) {	
		$query = "SELECT * FROM agency_profiles WHERE user_id='$uid'";
		$result = @mysql_query($query);
		if ($row = @mysql_fetch_array($result, MYSQL_ASSOC)) {
			$teleprompter = $row['teleprompter'];
			$hosting = $row['hosting'];
			$comedy = $row['comedy'];
		}
		
        //============== get voice ====================
        $sql = "SELECT voice FROM agency_profile_voices WHERE user_id='$uid'";
        $result = mysql_query($sql);
        $voice = array();
        while ($row = sql_fetchrow($result)) {
           $voice[] = $row['voice'];
        }
        unset($result);
        //============== end: get voice ====================			
		

		if(!empty($voice)) { // move on
			$step = 3;
			$substep = 3; 
			unset($_POST['submit']);
		} else {			
			if(!empty($_POST['submit'])) {
				$teleprompter = $_POST['teleprompter'];
				$hosting = $_POST['hosting'];
				$comedy = $_POST['comedy'];
				
				if(!empty($_POST['voice'])) {
				
					 // ======================  place into voices ===============
					 $sql = "DELETE FROM agency_profile_voices WHERE user_id='$uid'";
					 mysql_query($sql); // delete voices settings, and then reset with new ones
					 $voice = array();
					 $voice = $_POST['voice'];
					 if(!empty($voice)) {
						 foreach ($voice as $v) {
							if (!empty($v)) {
							   $v = escape_data($v);
							   $sql = "INSERT INTO agency_profile_voices (user_id, voice) VALUES ('$uid', '$v')";
							   mysql_query($sql); // insert voice
							}
						 }
					 }
					 // ======================  end: place into voices ===============				
					
					$sql_ary = array('teleprompter' => request_var('teleprompter', ''), 'hosting' =>
					request_var('eyes', ''), 'comedy' =>
					request_var('comedy', ''));
	
					
					$sql = 'UPDATE agency_profiles SET ' . sql_build_array('UPDATE', $sql_ary) .
					" WHERE user_id = '$uid'";
					
					if (mysql_query($sql)) {
						$substep = 3; // go to next substep	
						unset($_POST['submit']);
					}					
				} else {
					echo 'All fields are required.';
				}				
			}
			if($substep == 2) {	// if substep is still equal "2" then show this form (otherwise it'll just go on to step 3)
	
?>


<div class="signupinfo">Please enter the following</div>

<table width="430" border="0" cellpadding="3" cellspacing="2" bgcolor="white">
          
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
      </table>

<?php					
			}
		}			
	}	








	
	
	if($substep == 3) {	
		$query = "SELECT * FROM agency_profiles WHERE user_id='$uid'";
		$result = @mysql_query($query);
		if ($row = @mysql_fetch_array($result, MYSQL_ASSOC)) {
			$skills_language = $row['skills_language'];
			$skills_sports_music = $row['skills_sports_music'];
			$skills_other = $row['skills_other'];	 	
		}
				
		

		if(!is_null($skills_language) && !is_null($skills_sports_music) && !is_null($skills_other)) { // move on
			$step = 3;
			$substep = 4; 
			unset($_POST['submit']);
		} else {			
			if(!empty($_POST['submit'])) {
				$skills_language = $_POST['skills_language'];
				$skills_sports_music = $_POST['skills_sports_music'];
				$skills_other = $_POST['skills_other'];
				
					
				
				$sql_ary = array('skills_language' => request_var('skills_language', ''), 'skills_sports_music' =>
				request_var('skills_sports_music', ''), 'skills_other' =>
				request_var('skills_other', ''));

				
				$sql = 'UPDATE agency_profiles SET ' . sql_build_array('UPDATE', $sql_ary) .
				" WHERE user_id = '$uid'";
				
				if (mysql_query($sql)) {
					$step = 3;
					$substep = 4; // go to next substep	
					unset($_POST['submit']);
				}					
			}
			if($substep == 3) {	// if substep is still equal "2" then show this form (otherwise it'll just go on to step 3)
	
?>


<div class="signupinfo">Please enter the following</div>

<table width="430" border="0" cellpadding="3" cellspacing="2" bgcolor="white">
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

<?php					
			}
		}		
	}
	
	
	
	if($substep == 4) {	
		$query = "SELECT * FROM agency_profiles WHERE user_id='$uid'";
		$result = @mysql_query($query);
		if ($row = @mysql_fetch_array($result, MYSQL_ASSOC)) {
			$bio = $row['bio'];
			$resume = $row['resume'];
		}
				
		

		if(!is_null($bio)) { // move on
			$step = 4;
			$substep = 1; 
			unset($_POST['submit']);
		} else {			
			if(!empty($_POST['submit'])) {
				$bio = $_POST['bio'];
				
				$sql_ary = array('bio' => request_var('bio', ''));
				
				$sql = 'UPDATE agency_profiles SET ' . sql_build_array('UPDATE', $sql_ary) .
				" WHERE user_id = '$uid'";
				
				if (mysql_query($sql)) {
					$step = 4;
					$substep = 1; // go to next substep	
					unset($_POST['submit']);
				}					
			}
			if($substep == 4) {	// if substep is still equal "2" then show this form (otherwise it'll just go on to step 3)
				$shownext = false;
?>


<div class="signupinfo">Please enter the following (not required)</div>

<table width="430" border="0" cellpadding="3" cellspacing="2" bgcolor="white">
      <tr>
        <td class="AGENCYregtableleft" valign="top">Bio:</td>
        <td class="AGENCYregtableright">
        	<textarea name="bio" rows="15" style="width:350px"><?php if(isset($bio)) echo $bio; ?></textarea>
        </td>
      </tr>
      <tr>
        <td class="AGENCYregtableleft" valign="top"><a name="resumeanchor"></a>Resume:</td>
        <td class="AGENCYregtableright">
			<input type="file" name="resumefile" />
		<br /><br />
		<p align="center"><input type="submit" name="submit" value="Submit" onClick="document.getElementById('load2').style.display = 'block'; this.style.display='none'; this.form.encoding='multipart/form-data'; this.form.action='signup.php';">
		<span id="load2" style="display:none">loading...</span>
		</p>
        </td>
      </tr>  
</table>

<?php					
			}
		}	
	}
	
		
	
	
	
	
	
	
	
	
	

} // end of Step 3






if($step == 4) {
	$num_substeps = 1;
	
	
	if($substep == 1) {
		$num_photos = mysql_result(mysql_query("SELECT COUNT(*) as 'Num' FROM agency_photos WHERE user_id='$uid'"),0);
		if ($num_photos >= 1) {  
			$substep = 1;
			$step = 5;
		} else if($uid) {
			$_SESSION['signup'] = true;
			echo 'You\'re doing great so far!  In order to have your profile approved, you must upload some photos.<br /><br />';
			if($num_photos > 0) {
				echo '<b>You\'ve uploaded ' . $num_photos . ' image</b>.  You must upload a minimum of 4 total images.  You can upload more than than, of course, but to get started, please upload at least ' . (4 - $num_photos) . ' more images.<br /><br />';
			}
?>
We suggest that you only upload photos that are under 500 KB each in size, and have a .jpg or .gif file name<br />
		<div style="border:1px solid black; width:400px; margin:20px; padding:10px">
		<b>Upload New Image:</b><br /> <br />
		<input type="hidden" name="MAX_FILE_SIZE" value="10000000" />
		<!-- TITLE: <input type="text" name="newtitle[]" size="40" /><br /><br /> -->
		<!-- POSITION: <input type="text" name="neworder[]" size="2" />&nbsp;&nbsp;&nbsp; -->
		<input type="file" name="filename[]" />
		<br /><br />
		<input type="file" name="filename[]" />
		<br /><br />
		<input type="file" name="filename[]" />
		<br /><br />
		<input type="file" name="filename[]" />
		<br /><br />
		<p align="center"><input type="submit" name="submit" value="Submit" onClick="document.getElementById('load2').style.display = 'block'; this.style.display='none'; this.form.encoding='multipart/form-data'; this.form.action='myimages.php?tab=Upload&signup=1';">
		<span id="load2" style="display:none">loading...</span>
		</p>
		</div>
<?php
			// DON'T SHOW NEXT BUTTON ON THIS PAGE
			$shownext = false;
		}		
	}	
}

if($step == 5) {
	if (mysql_result(mysql_query("SELECT COUNT(*) as 'Num' FROM agency_cc WHERE user_id='$uid'"),0) || mysql_result(mysql_query("SELECT COUNT(*) as 'Num' FROM agency_profiles WHERE user_id='$uid' AND payProcessed='1'"),0)) {  
		$step = 6;
		$substep =1;
	} else if($uid) {
		$_SESSION['signup'] = true;
		echo 'Welcome back!  Thank you for filling in your information.  To complete your application please go to the Payment page using the link below.<br /><br />';
		
		// echo '<a href="https://' . $_SERVER['HTTP_HOST'] . '/agency/payment.php?token=' . session_id() .'">';
		echo '<a href="https://www.theagencyonline.com/payment.php?token=' . session_id() .'&step=1">';
		
		echo 'Please click here to go to the Secure Payment Page</a>';
		
		// EVERYTHING IS IN PAYMENT.PHP, JUST HAVE TO TRANSFER IT	
		/* $loggedin = $uid;
		include('../includes/payment.php');
		if($stage == 'creditdone') {
			$step = 2;
			$substep =1;
		} */
		
		// DON'T SHOW NEXT BUTTON ON THIS PAGE
		$shownext = false;
	}		
}

if($step == 6) {
	if($uid) {
		echo 'Nice Job!  You are ready for approval.<br /><br />';
		echo 'While you are waiting we strongly suggest you place some links on your profile, upload a Voice Over reel if you have one, or place some videos on your profile.  Why don\'t you take a look around your settings to become familiar with them by <a href="myaccount.php">Clicking Here to go to your Account</a>.<br /><br />You can always get to your account settings by clicking the MyProfile link in the main menu and then click on the Edit My Profile button in the right column.<br /><br />Good luck!';
		
		// DON'T SHOW NEXT BUTTON ON THIS PAGE
		$shownext = false;
	}		
}


?>
    <br clear="all" /></div>
    <div align="center"> <!--  style="position:absolute; bottom:0px; width:600px; margin-bottom:-3px" -->
<?php
for($counter=1; $counter<=$num_substeps; $counter++) {
	if($counter == $substep) {
		echo '<img src="images/dot.gif" />&nbsp;';
	} else {
		echo '<img src="images/dot_open.gif" />&nbsp;';
	}
}
?>	
	</div>
    
</div>
<?php 
if($shownext) {
?> 
<br />   
    <input type="hidden" id="step" name="step" value="<?php echo $step; ?>" />
    <input type="hidden" id="substep" name="substep" value="<?php echo $substep; ?>" />
    <input type="hidden" name="submit" value="submit" />
    <input type="button" value="Next Step" onclick="if(checkform()) { submitform (document.getElementById('signupform'),'ajax/signup_steps.php','signup_wrapper',validatetask); window.scrollTo(0,150); return false; } else { return false; }" />
<?php
}
?>
</form>
<div id="stepbar" style="height:14px; overflow:hidden; position:absolute; top:10px">
    <img src="images/signup_main.gif" width="750" style="margin-top:-<?php echo ($step-1)*14; ?>px" />
</div>
<?php
// echo 'Step: ' . $step;
// echo '<br>Substep: ' . $substep;
mysql_close(); // Close the database connection.
?>
