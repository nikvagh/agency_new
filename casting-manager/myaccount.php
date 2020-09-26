<?php
@include('sidebar.php');
$loggedin = 2;
$userid = $loggedin;
$profileid = $loggedin;
if (isset($_POST['submit']) && isset($_POST['clientform'])) { // Handle the form.
            // Check for a first name.
			if (!empty($_POST['firstname'])) {
				$firstname = escape_data($_POST['firstname']);
			} else {
				$firstname = FALSE;
				$message .=  '<p><font color="red">Please fill the First Name field.</font></p>';
			}

			// Check for a last name.
			$lastname = escape_data($_POST['lastname']);


			 // Check for an email address.
			 if (eregi('^[[:alnum:]][a-z0-9_\.\-]*@[a-z0-9\.\-]+\.[a-z]{2,4}$', stripslashes(trim($_POST['email'])))) {
				$email = $_POST['email']; // for form
				$e = escape_data($_POST['email']);
				$sql = "SELECT user_id FROM forum_users WHERE user_email='$e'";
				$result = mysql_query($sql);
				if(mysql_num_rows($result) != 0) {
					if ($row = sql_fetchrow($result)) {
						if($loggedin != $row['user_id']) {
							$submitmessage .= '<p><font color="red">The Email you entered is already being used by another account.  Each account must have a unique email.</font></p>';
						}
					}
				} else {
					$sql = "UPDATE forum_users SET user_email='$e' WHERE user_id='$loggedin'";
					mysql_query($sql);
				}
			 } else {
				$submitmessage .= '<p><font color="red">Please enter a valid email address.</font></p>';
			 }

            // Check for a phone number
			$phone = escape_data($_POST['phone']);

            // Check for a company
            if (trim($_POST['company'])) {
               $company = $_POST['company'];
               $company_confirm = true;
            } else {
               $company_confirm = false;
               $submitmessage .= '<p><font color="red">Please enter your company name.</font></p>';
            }

            // Check for a profession
            if (trim($_POST['profession'])) {
               $profession = $_POST['profession'];
               $profession_confirm = true;
            } else {
               $profession_confirm = false;
               $submitmessage .= '<p><font color="red">Please enter your profession.</font></p>';
            }

            $city = $_POST['city'];
            $state = $_POST['state'];
            $country = $_POST['country'];
            $link = $_POST['link'];
            $link = remove_http(escape_data($link));
            $note = $_POST['note'];

            $sql_ary = array('firstname' => request_var('firstname', ''), 'lastname' =>
               request_var('lastname', ''), 'phone' => request_var('phone', ''), 'city' =>
               request_var('city', ''), 'state' => request_var('state', ''), 'country' =>
               request_var('country', ''), 'client_company' => request_var('company', ''), 'client_profession' =>
               request_var('profession', ''), 'client_link' => request_var('link', ''), 'client_note' =>
               request_var('note', ''),);


            $sql = 'UPDATE agency_profiles SET ' . sql_build_array('UPDATE', $sql_ary) .
               " WHERE user_id = '$loggedin'";

            if (mysql_query($sql)) {
               $success = true;
            }

            // ======================  place into castings ===============
            $sql = "DELETE FROM agency_profile_castings WHERE user_id='$loggedin'";
            mysql_query($sql); // delete castings settings, and then reset with new ones
            $castings = array();
            $castings = $_POST['castings'];
            foreach ($castings as $ca) {
               if (!empty($ca)) {
                  $sql = "INSERT INTO agency_profile_castings (user_id, casting_type) VALUES ('$loggedin', '$ca')";
                  mysql_query($sql); // insert casting type
               }
            }
            // ======================  end: place into castings ===============


            if ($success && empty($submitmessage)) { // If required fields.
               $submitmessage .= 'Your Settings Have Been Updated.';
            } else { // If one of the data tests failed.
               $submitmessage .= '<p><font color="red">Please try again.</font></p>';
            }


         } else { // End of the main Submit conditional.
            $query = "SELECT * FROM forum_users, agency_profiles WHERE agency_profiles.user_id=forum_users.user_id AND forum_users.user_id='$userid'"; // check to see if name already used.
            $result = @mysql_query($query);
            if ($row = @mysql_fetch_array($result, MYSQL_ASSOC)) { // If there are projects.
               $email = $row['user_email'];
               $firstname = $row['firstname'];
               $lastname = $row['lastname'];
               $phone = $row['phone'];
               $city = $row['city'];
               $state = $row['state'];
               $country = $row['country'];
               $company = $row['client_company'];
               $profession = $row['client_profession'];
               $link = $row['client_link'];
               $note = $row['client_note'];

               //============== get castings ====================
               $sql = "SELECT casting_type FROM agency_profile_castings WHERE user_id='$loggedin'";
               $result = mysql_query($sql);
               $castings = array();
               while ($row = sql_fetchrow($result)) {
                  $castings[] = $row['casting_type'];
               }
               unset($result);
               //============== end: get castings ====================
            }
         }

		  if(!empty($submitmessage)) {
			echo '<div class="AGENCYsubmitmessage">' . $submitmessage . '</div>';
		  }

		if (!is_active()) {
			echo '<div class="AGENCYsubmitmessage" style="text-align:left">';
			if(mysql_result(mysql_query("SELECT COUNT(*) as 'Num' FROM agency_profile_castings WHERE user_id='$loggedin'"),0) == 0) { // if myaccount form has never been submitted
				echo mysql_result(mysql_query("SELECT varvalue FROM agency_vars WHERE varname='waitingClient'"), 0, 'varvalue');
			} else {
				echo mysql_result(mysql_query("SELECT varvalue FROM agency_vars WHERE varname='waitingClient2'"), 0, 'varvalue');
			}
			echo '</div>';
		}
?>
<style>
    .AGENCYsubmitmessage {
    margin: 5% 0px 0px 0px;
}
</style>
 

<div style="width:100%; float:left">
 <div style="color: blue;font-size: 18px;font-weight: bold;margin: 0 0px 0px 20px;">Profile Options<br /><br /></div>
 <div id="AGENCYProfileLeftList" style="width:70%;">
    <div id="AGENCYProfileLeftListTopLeft"></div>
    <div id="AGENCYProfileLeftListTopCenter" style="width:612px"></div>
    <div id="AGENCYProfileLeftListTopRight"></div>
    <div id="AGENCYProfileLeftListInner">&nbsp;

 <div style="width:35%; text-align:center; float:left; padding:3px">
<div id="AGENCYProfileAvatar">

  <img src="<?php
	if(file_exists($folder . 'avatar.jpg')) {
		echo   $folder . 'avatar.jpg';
	} else if(file_exists($folder . 'avatar.gif')) {
		echo   $folder . 'avatar.gif';
	} else {
		echo 'img/avatar.jpg';
	}
?>" class="img-circle" align="middle" style="margin:0px" />

<br /><br />
  <a href="#TB_inline?height=200&amp;width=450&amp;inlineId=hiddenModalContent" onclick="loaddiv('popupcontent', 'ppicform')" class="thickbox AGENCY_graybutton" style="font-size:xx-small; text-decoration:none; color:#000; background-color:#FFF;padding:2px"></a>
<br /><br />
  <a href="changepassword.php" class="AGENCY_graybutton" style="font-size:xx-small; text-decoration:none; color:#000; background-color:#FFF;padding:2px">change password</a>

<br clear="all" />
  </div>

<?php echo mysql_result(mysql_query("SELECT varvalue FROM agency_vars WHERE varname='ClientSettings'"), 0, 'varvalue'); ?>
</div>


<div style="width:470px; float:right; text-align:center">
  <form action="myaccount.php" method="post" name="myaccount">
  <input type="hidden" name="clientform" value="1" />
    <table width="100%" border="0" cellpadding="3" cellspacing="3">
      <tr>
        <td class="AGENCYregtableleft"<?php if (empty($firstname)) {echo ' style="color:#FF0000"';} ?>>First Name:</td>
        <td class="AGENCYregtableright"><input type="text" name="firstname" size="30" maxlength="50" value="<?php if (!empty($firstname)) echo $firstname; ?>" />
        </td>
      </tr>
      <tr>
        <td class="AGENCYregtableleft"<?php if (empty($lastname)) {echo ' style="color:#FF0000"';} ?>>Last Name:</td>
        <td class="AGENCYregtableright"><input type="text" name="lastname" size="30" maxlength="50" value="<?php if (!empty($lastname)) echo $lastname; ?>" />
        </td>
      </tr>
      <tr>
        <td class="AGENCYregtableleft"<?php if (empty($company)) {echo ' style="color:#FF0000"';} ?>>Company:</td>
        <td class="AGENCYregtableright"><input type="text" name="company" size="30" maxlength="255" value="<?php if (!empty($company)) echo $company; ?>" />
        </td>
      </tr>
      <tr>
        <td class="AGENCYregtableleft"<?php if (empty($profession)) {echo ' style="color:#FF0000"';} ?>>Profession:</td>
        <td class="AGENCYregtableright"><input type="text" name="profession" size="30" maxlength="255" value="<?php if (!empty($profession)) echo $profession; ?>" />
        </td>
      </tr>
      <tr>
        <td class="AGENCYregtableleft">Link:</td>
        <td class="AGENCYregtableright"><input type="text" name="link" size="30" maxlength="30" value="<?php if (!empty($link)) echo $link; ?>" />
        </td>
      </tr>
      <tr>
        <td class="AGENCYregtableleft"<?php if (empty($email)) {echo ' style="color:#FF0000"';} ?>>Email Address:</td>
        <td class="AGENCYregtableright"><input type="text" name="email" size="40" maxlength="40" value="<?php if (!empty($email)) echo $email; ?>" />
        </td>
      </tr>
      <tr>
        <td class="AGENCYregtableleft">Phone Number:</td>
        <td class="AGENCYregtableright"><input type="text" name="phone" size="40" maxlength="40" value="<?php if (!empty($phone)) echo $phone; ?>" />
        </td>
      </tr>
      <tr>
        <td class="AGENCYregtableleft">City:</td>
        <td class="AGENCYregtableright"><input type="text" name="city" size="40" maxlength="40" value="<?php if (!empty($city)) echo $city; ?>" />
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
        <input type="text" name="state" size="40" maxlength="40" value="<?php if (!empty($state)) echo $state; ?>" />
<?php
}
?>
        </td>
      </tr>

      <tr>
        <td class="AGENCYregtableleft">Country:</td>
        <td class="AGENCYregtableright"><select name="country" onChange="changecountry(this.value)">
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
        <td class="AGENCYregtableleft">Types of Castings:</td>
        <td class="AGENCYregtableright">
<?php
for($i=0; isset($castingarray[$i]); $i++) {
	echo '<input type="checkbox" name="castings[]" value="' . $castingarray[$i] . '"';
	if(in_array($castingarray[$i], $castings)) echo ' checked';
	echo ' /> ' . $castingarray[$i] . '<br />';
}
/*
// "Other" types of castings
echo 'Other:<input type="text" name="castings[]" value="';
foreach($castings as $ca) {
	if(!in_array($ca, $castingarray)) {
		echo $ca;
		$showblank = false;
	}
}
echo '">';
*/
?>
        </td>
      </tr>
      <tr>
        <td class="AGENCYregtableleft">Notes:</td>
        <td class="AGENCYregtableright"><textarea name="note" cols="37"><?php if (!empty($note)) echo $note; ?></textarea>
        </td>
      </tr>
    </table>
    <br />
	<input type="hidden" value="<?php echo time(); ?>" name="creation_time"/>
	<input type="hidden" value="<?php echo agency_add_form_key('myaccount'); ?>" name="form_token"/>
    <input type="submit" value="Save Changes" name="submit" onclick="if(!checkcastingsboxes()) { alert('You must check at least one Casting Type');  return false; }" />
    <br />
    <br />
  </form>

  <form action="clienthome.php">
    <input type="submit" value="Cancel" />
  </form>
</div>

	
<br clear="all" /><br clear="all" />
    </div>
    <div id="AGENCYProfileLeftListBottomLeft"></div>
    <div id="AGENCYProfileLeftListBottomCenter"  style="width:612px"></div>
    <div id="AGENCYProfileLeftListBottomRight"></div>
  </div>
</div>



<!--  START: client Buttons -->



<script type="text/javascript">
function checkcastingsboxes() {
	var frmCheckform        = document.myaccount;
	// assigh the name of the checkbox;
	var chks = document.getElementsByName('castings[]');

	var hasChecked = false;
	// Get the checkbox array length and iterate it to see if any of them is selected
	for (var i = 0; i < chks.length; i++)
	{
	       if (chks[i].checked)
	        {
	                hasChecked = true;
	                break;
	        }
	}
	return hasChecked;
}
</script>