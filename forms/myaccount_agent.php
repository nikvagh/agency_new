

<div style="width:630px; float:left">
 <div style="color: blue; font-size: 18px; font-weight: bold;">Profile Options<br /><br /></div>
 <div id="AGENCYProfileLeftList" style="width:630px;">
    <div id="AGENCYProfileLeftListTopLeft"></div>
    <div id="AGENCYProfileLeftListTopCenter" style="width:612px"></div>
    <div id="AGENCYProfileLeftListTopRight"></div>
    <div id="AGENCYProfileLeftListInner">&nbsp;

 <div style="width:140px; text-align:center; float:left; padding:3px">
<div id="AGENCYProfileAvatar">

  <img src="<?php
	if(file_exists($folder . 'avatar.jpg')) {
		echo   $folder . 'avatar.jpg';
	} else if(file_exists($folder . 'avatar.gif')) {
		echo   $folder . 'avatar.gif';
	} else {
		echo 'images/friend.gif';
	}
?>" align="middle" style="margin:0px" />

<br /><br />
  <a href="#TB_inline?height=200&amp;width=450&amp;inlineId=hiddenModalContent" onclick="loaddiv('popupcontent', 'ppicform')" class="thickbox AGENCY_graybutton" style="font-size:xx-small; text-decoration:none; color:#000; background-color:#FFF;padding:2px">change avatar image</a>
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
<div style="width:165px; margin-top:44px; margin-right:-20px; float:right">
<?php echo clientbuttons(true); ?>
</div>


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