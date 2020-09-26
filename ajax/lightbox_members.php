<?php
include('../includes/mysql_connect.php');
// include('../includes/agency_functions.php');
echo '<br />';
$lightbox_id = (int) $_REQUEST['id'];
$query = "SELECT DISTINCT agency_profiles.user_id, agency_profiles.firstname, agency_profiles.lastname FROM agency_lightbox_users, agency_lightbox, agency_profiles WHERE agency_lightbox_users.lightbox_id=agency_lightbox.lightbox_id AND agency_lightbox_users.user_id=agency_profiles.user_id AND agency_lightbox_users.lightbox_id='$lightbox_id' ORDER BY agency_profiles.firstname";
$result = @mysql_query ($query);
if(mysql_num_rows($result) == 0) {
	echo 'The Lightbox you selected is empty.  Please select a different lightbox.';
} else {
	$lblist = array();
	while ($row = @mysql_fetch_array ($result, MYSQL_ASSOC)) {
		$lblist[] = '<a href="profile.php?u=' . $row['user_id'] . '" target="_blank" style="text-decoration:none">' . $row['firstname'] . ' ' . $row['lastname'] . '</a>';
	}
	echo 'Recipients: ' . implode(', ', $lblist);
}
mysql_close(); // Close the database connection.
?>
