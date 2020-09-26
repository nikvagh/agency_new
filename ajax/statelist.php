<?php
include('../includes/mysql_connect.php');
include('../includes/agency_functions.php');

if(isset($_GET['country'])) {
	$country = escape_data($_GET['country']);
	$query = "SELECT DISTINCT state FROM agency_cities WHERE country='$country' ORDER BY state";
	$result = mysql_query ($query);
	if(mysql_num_rows($result) > 0) {
		echo '<br /><select name="state" onchange="loaddiv(\'AGENCY_search_city\', false, \'ajax/citylist.php?state=\'+this.value+\'&\')"><option value=""> - Any State - </option>';
		while ($row = mysql_fetch_array ($result, MYSQL_ASSOC)) {
			echo '<option value="' . $row['state'] . '"';
			if(!empty($_COOKIE['agencysearch']['state'])) {
				if($_COOKIE['agencysearch']['state'] == $row['state']) {
					echo ' selected';
				}
			}
			echo '>' . $row['state'] . '</option>';
		}
		echo '</select>';
	}
}
mysql_close(); // Close the database connection.
?>
