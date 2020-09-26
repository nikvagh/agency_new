<?php
include('header.php');
if(isset($_SESSION['admin'])) {


echo '<br /><div align="center"><div style="width:300px; background-color:#EEEEEE; padding:15px 15px 15px 15px" align="left">';

if (isset($_GET['id'])) {
	$id = $_GET['id'];
	$query = "SELECT * FROM agency_discounts WHERE discount_id='$id'";  // check to see if name already used.
	$result = mysql_query ($query);
	if ($row = mysql_fetch_array ($result, MYSQL_ASSOC)) { // If there are projects.	
		$usage = $row['discount_usage'];
		// $type = $row['discount_type'];
		$code = $row['discount_code'];

		echo '<br />DISCOUNT INFORMATION:<br /><br />';
		echo 'Code: <b>' . $code . '</b><br /><br />';
		// echo 'Type: <b>' . $type . '</b><br /><br />';
		echo 'Remaining uses: <b>' . (is_numeric($usage) ? $usage : 'Unlimited') . '</b><br /><br />';
		echo '<form action="discount_edit.php?id=' . $id . '" method="post"><input name="submit" type="submit" value="Edit Discount Information"></form><br /><br /><br />';
		$query2 = "SELECT * FROM agency_profiles WHERE discount_code='$code'";
		$result2 = mysql_query ($query2);
		echo '<div align="center">MEMBERS:<br />';
		while ($row2 = mysql_fetch_array ($result2, MYSQL_ASSOC)) { // If there are projects.	
			echo '<br /><a href="../profile.php?u=' . $row2['user_id'] . '">' . $row2['firstname'] . ' ' . $row2['lastname'] . '</a> : ' . $row2['pay_term'] . '<br />';
		}
		echo '</div><br /><br /><br /><br /><form action="discounts.php" method="post"><input name="delete" type="hidden" value="' . $id . '"><input name="submit" type="submit" value="Delete Discount Code" onclick="return confirm(\'This Discount Code is about to be PERMANENTLY DELETED from the database.  Any payments using this code that have not yet been processed will no longer have the discount applied to them.  Are you sure you want to delete this Discount Code?\')"></form><br />';
	} else {
		echo '<b>There is no Discount with this ID.  Discount Code may have been deleted.</b>';
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
