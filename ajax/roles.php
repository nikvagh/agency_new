<?php
session_start();

include('../includes/mysql_connect.php');
include('../includes/agency_functions.php');

if(!empty($_GET['lightboxid'])) {
	$lightboxid = escape_data($_GET['lightboxid']);
	if(!empty($_GET['castingid'])) {
		// if this is from a casting, they do not select roles, but a notification is to be given if it is going to GENERAL section
		$castingid = escape_data($_GET['castingid']);
		// check if this lightbox is associated with this casting
		if(!mysql_result(mysql_query("SELECT COUNT(*) as `Num` FROM agency_lightbox WHERE casting_id='$castingid' AND lightbox_id='$lightboxid'"),0)) {
			echo '<b>Note:</b> These talent will be saved in a "General" section of this lightbox, because the lightbox already has roles/categories from another casting. To save your new selections by role/category, please create a new lightbox';
			
		}
		
	} else {
		$query = "SELECT agency_castings_roles.* FROM agency_lightbox, agency_castings, agency_castings_roles WHERE agency_lightbox.lightbox_id='$lightboxid' AND agency_lightbox.casting_id=agency_castings.casting_id AND agency_castings_roles.casting_id=agency_castings.casting_id ORDER BY agency_castings_roles.name";
		$result = mysql_query ($query);
		if(mysql_num_rows($result) > 0) {
			echo '<br />Role:<br /><select name="roleid"><option value=""> - GENERAL LIGHTBOX - </option>';
			while ($row = mysql_fetch_array ($result, MYSQL_ASSOC)) {
				echo '<option value="' . $row['role_id'] . '">' . $row['name'] . '</option>';
			}
			echo '</select>';
		}
	}
}
mysql_close(); // Close the database connection.
?>
