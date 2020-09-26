<?php
session_start();

include('../includes/mysql_connect.php');
include('../includes/vars.php');
include('../includes/agency_functions.php');

if(is_active() && agency_account_type() == 'client') { // check if user is logged in
	$showform = true;  // flag to show form
	$clientid= $_SESSION['user_id'];

	// CHECK IF FORM HAS BEEN SUBMITTED
	if(isset($_POST['submit'])) { // process form
		
		if(!empty($_COOKIE['lightbox'])) {
   			$processarray = explode(",", $_COOKIE['lightbox']);
		} else if(!empty($_POST['processarray'])) {
   			$processarray = explode(",", $_POST['processarray']);
		} 
		
		// print_r($processarray);
	
		/* if(!empty($_POST['processarray']) && !empty($_COOKIE['lightbox'])) {
   			$processarray = array_merge(explode(",", $_POST['processarray']), explode(",", $_COOKIE['lightbox']));
		} else if(!empty($_POST['processarray'])) {
   			$processarray = explode(",", $_POST['processarray']);
		} else if(!empty($_COOKIE['lightbox'])) {
   			$processarray = explode(",", $_COOKIE['lightbox']);
		} */
		
		// setcookie ("lightbox", "", time() - 3600, "/");
		
		/* echo '<br /><br />array: ' . $_POST['processarray'] . '<br /><br />';
		print_r($_POST['processarray']);
		echo '<br /><br />set arry: ' . $processarray . '<br /><br />'; 
		print_r($processarray); */
		
		// LIGHTBOX ID MUST BE OBTAINED
		if(!empty($_POST['old_lightbox'])) { // existing lightbox selected
			// INSERT USER INTO EXISTING LIGHTBOX
			$lightbox_id = escape_data((int)$_POST['old_lightbox']);
		} else if(!empty($_POST['new_lightbox'])) { // new lightbox added
			// INSERT INTO NEW LIGHTBOX
			$lightbox_name = escape_data($_POST['new_lightbox']);
			$description = escape_data($_POST['description']);
			// first check if name is already used
			if(mysql_result(mysql_query("SELECT COUNT(*) as 'Num' FROM agency_lightbox WHERE lightbox_name='$lightbox_id' AND client_id='$clientid'"),0) == 0) {
				// name not used, so add it
				$timecode = strtotime("NOW");
				$query = "INSERT INTO agency_lightbox (client_id, lightbox_name, lightbox_description, timecode) VALUES ('$clientid', '$lightbox_name', '$description', '$timecode')";
				$result = mysql_query($query);
				if(mysql_affected_rows() > 0 ) { // new lightbox created
					echo 'New lightbox successfully created.<br />';
					$lightbox_id = mysql_insert_id();
				}
			} else { // name is already used, so get the id
				$query = "SELECT lightbox_id FROM agency_lightbox WHERE lightbox_name='$lightbox_name'";
				$result = mysql_query($query);
				if($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
					$lightbox_id = $row['lightbox_id'];
				} else {
					echo 'Error: Lightbox ID could not be retrieved.<br /><br />';
				}
			}
		}

		// at this point, the lightbox ID should be determined if a value was entered
		if(isset($lightbox_id) && sizeof($processarray) > 0) {
			
			if(!empty($_GET['castingid'])) {
				// associate lightbox with casting (if not already done)
				$castingid = escape_data($_GET['castingid']);
				$query = "UPDATE agency_lightbox SET casting_id='$castingid' WHERE lightbox_id='$lightbox_id' AND casting_id IS NULL";
				mysql_query($query);
			}
			
			
			// first see if user is already in lightbox
	   		$showlink = false; // flag for if anything is added, then show link to lightbox
			foreach($processarray as $user_id) {
				
				// echo $user_id . ' - ';
				
				
				if(!empty($_GET['castingid'])) {
					// adding these from a casting, so track roles
					$castingid = escape_data($_GET['castingid']);
					
					// find the roles this user is in
					// if there's an "_" in the user_id it's because the format is roleid_userid
					if(strrpos($user_id, '_')) {
						$strings = explode('_',$user_id);
						$roleid = $strings[0];
						$user_id = $strings[1];		
												

						// echo 'xx' . $roleid;
						if(mysql_result(mysql_query("SELECT COUNT(*) as 'Num' FROM agency_lightbox_users WHERE lightbox_id='$lightbox_id' AND user_id='$user_id' AND role_id='$roleid'"),0) == 0) {
							$query2 = "INSERT INTO agency_lightbox_users (lightbox_id, user_id, role_id) VALUES ('$lightbox_id', '$user_id', '$roleid')";
							// echo $query2;
							// echo $query2 . '<br />';
							mysql_query($query2);
						}						
					}
				
				} else if(!empty($_POST['roleid'])) {
					// echo 'ROLE';
					// place in roles
					$roleid = escape_data($_POST['roleid']);
					if(mysql_result(mysql_query("SELECT COUNT(*) as 'Num' FROM agency_lightbox_users WHERE lightbox_id='$lightbox_id' AND user_id='$user_id' AND role_id='$roleid'"),0) == 0) {
						$query = "INSERT INTO agency_lightbox_users (lightbox_id, user_id, role_id) VALUES ('$lightbox_id', '$user_id', '$roleid')";
						// echo $query . 'xx<br />';
						
						$result = mysql_query($query);
					}
					
				} else if(mysql_result(mysql_query("SELECT COUNT(*) as 'Num' FROM agency_lightbox_users WHERE lightbox_id='$lightbox_id' AND user_id='$user_id' AND role_id IS NULL"),0) == 0) {
					$query = "INSERT INTO agency_lightbox_users (lightbox_id, user_id) VALUES ('$lightbox_id', '$user_id')";
					
					// echo $query . 'yy<br />';
					
					$result = mysql_query($query);
				}
				
				$showlink = true;
				
				
			}
			if($showlink) {
				echo '<br /><br />Lightbox has been updated with selected Talent.<br /><br /><a href="clienthome.php?mode=lightbox&amp;lightbox=' . $lightbox_id . '" onclick="remind=false;">view lightbox</a> or press <i>esc</i> to go back.';
				$showform = false;
			}
		} else { // no lightbox was entered.
			echo 'It appears a lightbox or talent was not selected.<br /><br />';
		}
	}
	if($showform) {
?>
<br /><b>Add to a lightbox:</b><br /><br />
<form action="javascript:void(0)" method="post" name="lightboxadd" id="lightboxadd">
New Lightbox Name:<br />
<input name="new_lightbox" maxlength="30" /><br />
Description:<br />
<textarea name="description"></textarea><br />
<?php
	// LIST LIGHTBOXES
	/*
	if(!empty($_GET['castingid'])) {
		$castingid = escape_data($_GET['castingid']);
		$query2 = "SELECT lightbox_id, lightbox_name FROM agency_lightbox WHERE casting_id='$castingid' AND client_id='$clientid' ORDER BY lightbox_id DESC";
	} else {
		$query2 = "SELECT lightbox_id, lightbox_name FROM agency_lightbox WHERE client_id='$clientid' ORDER BY lightbox_id DESC";
	} */
	
	$query2 = "SELECT lightbox_id, lightbox_name FROM agency_lightbox WHERE client_id='$clientid' ORDER BY lightbox_id DESC";
	$result2 = @mysql_query($query2);
	if(mysql_num_rows($result2) > 0) {
		echo '<br />or<br /><br /><select name="old_lightbox"';
		if(!empty($_GET['castingid'])) {
			// check if a notification has to be given
			$castingid = (int)$_GET['castingid'];
			echo 'onchange="loaddiv(\'roledropdown\', false, \'ajax/roles.php?castingid=' . $castingid . '&lightboxid=\'+this.value+\'&\');"';
		} else {
			// if not working in a casting, give option to select a role
			echo 'onchange="loaddiv(\'roledropdown\', false, \'ajax/roles.php?lightboxid=\'+this.value+\'&\');"';
		}
		
		
		
		echo '>';
		echo '<option value="">-- Select Lightbox --</option>';
		while ($row2 = @mysql_fetch_array ($result2, MYSQL_ASSOC)) {
			echo '<option value="' . $row2['lightbox_id'] . '">' . $row2['lightbox_name'] . '</option>';
		}
		echo '</select>';
	}
?>
<div id="roledropdown"></div>
<br /><br />
<input type="hidden" id="processarray" name="processarray" value="" />
<input type="hidden" name="submit" value="submit" />
<input type="button" value="Add To Lightbox" onclick="document.getElementById('processarray').value=ProcessArray; submitform (document.getElementById('lightboxadd'),'ajax/lightbox_add.php<?php if(!empty($castingid)) echo '?castingid=' . $castingid; ?>','TB_ajaxContent',validatetask); return false;" />
</form>
<?php
	}
} else {
	echo "You don't have access to this page.";
}


mysql_close(); // Close the database connection.
?>
