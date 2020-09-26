<?php
include('header.php');
if(isset($_SESSION['admin'])) {


if (isset($_POST['submit']) && isset($_POST['id'])) {  // If the form has been submitted, then process the data
	$id = (int) escape_data($_POST['id']);

	// Check for a code.
	if (stripslashes(trim($_POST['code']))) {
		$code = escape_data($_POST['code']);
	} else {
		$code = FALSE;
		echo '<p align="center" style="color:red; font-weight:bold ">You must enter a Discount Code</p>';
	}
	
	$type = escape_data($_POST['type']);
	
	$usage = (int) escape_data($_POST['usage']);
	if(empty($usage)) {
		$usage = 'NULL';
	}
	
	// Check if there is already an entry for this code.
	if ($code && $type) {
		$query = "SELECT * FROM agency_discounts WHERE discount_code='$code' AND discount_id != '$id'";
		$result = mysql_query ($query);
	
		$query2 = "SELECT mentor_code FROM agency_mentors WHERE mentor_code='$code'";
		$result2 = mysql_query ($query2);
		
		if (mysql_num_rows($result) == 0 && mysql_num_rows($result2) == 0) { // If code not used elsewhere.		
			$ok = TRUE;
		} else {
			$ok = FALSE;  // if username is already used, it's not okay unless it's by this user
			echo '<br /><br /><div align="center"><font size="+1" color="#990000"><b>The Code you entered is already in the database (either as a Discount code or a Mentor code).  Please select another code.</b></font><br><br><br></div>';
		}
	}
	
	if ($ok) {
		$query = "UPDATE agency_discounts SET discount_code='$code', discount_type='$type', discount_usage=$usage WHERE discount_id='$id'";		
		$result = mysql_query ($query);
		$url = 'discount_view.php?id=' . $id;
		ob_end_clean(); // Delete the buffer.
		header("Location: $url");
		exit(); // Quit the script.
	}
} else {
	if (isset($_GET['id'])) {
		$id = $_GET['id'];
		$query = "SELECT * FROM agency_discounts WHERE discount_id='$id'";  // check to see if name already used.
		$result = mysql_query ($query);
		if ($row = mysql_fetch_array ($result, MYSQL_ASSOC)) { // If there are projects.	
			$type = $row['discount_type'];
			$usage = $row['discount_usage'];
			$code = $row['discount_code'];		
		}
	}
}
?>

<table width="400"  border="1" align="center" cellpadding="10" cellspacing="0" bgcolor="#F0F0F0">
  <tr>
    <td valign="top">
		<p>Enter Discount Code Information:</p>
        <form name="form1" method="post" action="discount_edit.php">
		<input name="id" type="hidden" value="<?php if (isset($id)) echo $id; ?>">
		  <p>Discount Code <span style="font-size:small">-- you may leave the default or choose your own (up to 10 characters)</span>:<br>
            <input name="code" type="text" size="10" maxlength="10" <?php if(!empty($code)) echo ' value="' . $code . '"'; ?>>
          </p>
		  <p>Number of times code can be used <span style="font-size:small">-- leave blank for unlimited</span>:<br>
            <input name="usage" type="text" size="10" maxlength="10" <?php if(!empty($usage)) echo ' value="' . $usage . '"'; ?>>
          </p>
		  <p>Discount Type:<br>
            <input name="type" type="radio" value="freemonthwithcc" <?php if($type == 'freemonthwithcc') echo 'checked'; ?>> 30 days free<br />
			<input name="type" type="radio" value="14daysfree" <?php if($type == '14daysfree') echo 'checked'; ?>> 14 days free<br />
          </p>
		  
		  			<p align="center"><br>
                      <input type="submit" name="submit" value="Submit">
          <div align="center">
                          <p></p>
          </div>
        </form></td>
  </tr>
</table>

</div>
<?php
} else {
	$url = "index.php";
	ob_end_clean(); // Delete the buffer.
	header("Location: $url");
	exit(); // Quit the script.
}
include('footer.php');
?>
