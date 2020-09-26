<?php
include('header.php');
if(isset($_SESSION['admin'])) {


echo '<br /><div align="center"><div style="width:300px; background-color:#EEEEEE; padding:15px 15px 15px 15px" align="left">';

if (isset($_GET['id'])) {
	$id = $_GET['id'];
	$query = "SELECT * FROM agency_mentors WHERE mentor_id='$id'";  // check to see if name already used.
	$result = mysql_query ($query);
	if ($row = mysql_fetch_array ($result, MYSQL_ASSOC)) { // If there are projects.	
		$firstname = $row['firstname'];
		$lastname = $row['lastname'];
		$email = $row['paypal_email'];
		$code = $row['mentor_code'];

		echo '<br />MENTOR INFORMATION:<br /><br />';
		echo 'First Name: <b>' . $firstname . '</b><br />';
		echo 'Last Name: <b>' . $lastname . '</b><br />';
		echo 'Email: <b>' . $email . '</b><br />';
		echo 'Code: <b>' . $code . '</b><br /><br />';
		echo '<form action="mentor_edit.php?id=' . $id . '" method="post"><input name="submit" type="submit" value="Edit Mentor Information"></form><br /><br /><br />';
		$query2 = "SELECT * FROM agency_profiles WHERE mentor_id='$id'";
		$result2 = mysql_query ($query2);
		echo '<div align="center">REFERRED MEMBERS:<br />';
		while ($row2 = mysql_fetch_array ($result2, MYSQL_ASSOC)) { // If there are projects.	
			echo '<br /><a href="../profile.php?u=' . $row2['user_id'] . '">' . $row2['firstname'] . ' ' . $row2['lastname'] . '</a> : ' . $row2['pay_term'] . '<br />';
		}
		echo '</div><br /><br /><br /><br /><form action="mentors.php" method="post"><input name="delete" type="hidden" value="' . $id . '"><input name="submit" type="submit" value="Delete Mentor" onclick="return confirm(\'This Mentor is about to be PERMANENTLY DELETED from the database.  Are you sure you want to delete this Mentor?\')"></form><br />';
	} else {
		echo '<b>There is no Mentor with this ID.  Mentor may have been deleted.</b>';
	}
} else {
	echo 'Page accessed in error.';
}

?>
</div></div>
<?php
} else {
	$url = "index.php";
	ob_end_clean(); // Delete the buffer.
	header("Location: $url");
	exit(); // Quit the script.
}
include('footer.php');
?>
