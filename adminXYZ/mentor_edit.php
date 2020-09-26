<?php
include('header.php');
if(isset($_SESSION['admin'])) {


if (isset($_POST['submit']) && isset($_POST['id'])) {  // If the form has been submitted, then process the data
	$id = $_POST['id'];
	$firstname = escape_data($_POST['firstname']);
	$lastname = escape_data($_POST['lastname']);
	$email = escape_data($_POST['email']);
	$code = escape_data($_POST['code']);

	// Check for an first name.
	if (stripslashes(trim($_POST['firstname']))) {
		$firstname = escape_data($_POST['firstname']);
	} else {
		$firstname = FALSE;
		echo '<p align="center" style="color:red; font-weight:bold ">You must enter a First Name</p>';
	}
	
	// Check for an last name.
	if (stripslashes(trim($_POST['lastname']))) {
		$lastname = escape_data($_POST['lastname']);
	} else {
		$lastname = FALSE;
		echo '<p align="center" style="color:red; font-weight:bold ">You must enter a Last Name</p>';
	}
	
	// Check if there is already an entry for this mentor.
	if ($firstname && $lastname) {
		$query = "SELECT * FROM agency_mentors WHERE (firstname='$firstname' AND lastname='$lastname' AND mentor_id!='$id')";  // check to see if name exists under a different ID.
		$result = mysql_query ($query);
		if (mysql_affected_rows() > 0) { // If username is already used then check to see if it is used by this user
			$ok = FALSE;  // if username is already used, it's not okay unless it's by this user
			echo '<br /><font color="red">Error: There is already an entry under this First and Last name.  Please find <b>' . $firstname . ' ' . $lastname . '</b> in the list of mentors and edit them from there.</font><br /><br />';
		} else {
			$ok = TRUE;
		}
	}


	$email = escape_data($_POST['email']);
	$code = trim(escape_data($_POST['code']));
	
	if ($ok) {
		$query = "UPDATE agency_mentors SET firstname='$firstname', lastname='$lastname', paypal_email='$email', mentor_code='$code' WHERE mentor_id='$id'";		
		$result = mysql_query ($query);
		$url = 'mentor_view.php?id=' . $id;
		ob_end_clean(); // Delete the buffer.
		header("Location: $url");
		exit(); // Quit the script.
	}
} else {
	if (isset($_GET['id'])) {
		$id = $_GET['id'];
		$query = "SELECT * FROM agency_mentors WHERE mentor_id='$id'";  // check to see if name already used.
		$result = mysql_query ($query);
		if ($row = mysql_fetch_array ($result, MYSQL_ASSOC)) { // If there are projects.	
			$firstname = $row['firstname'];
			$lastname = $row['lastname'];
			$email = $row['paypal_email'];
			$code = $row['mentor_code'];		
		}
	}
}
?>

<table width="400"  border="1" align="center" cellpadding="10" cellspacing="0" bgcolor="#F0F0F0">
  <tr>
    <td valign="top">
		<p>Enter Mentor Information:</p>
        <form name="form1" method="post" action="mentor_edit.php">
		<input name="id" type="hidden" value="<?php if (isset($id)) echo $id; ?>">
          <p>First Name:
              <input name="firstname" type="text" size="50" maxlength="250" <?php if(isset($firstname)) echo ' value="' . $firstname . '"'; ?>">
          </p>
          <p>Last Name:
              <input name="lastname" type="text" size="50" maxlength="250" <?php if(isset($lastname)) echo ' value="' . $lastname . '"'; ?>">
          </p>
          <p>PayPal Email (commisions are sent to this email address; please confirm it is their PayPal email or they will not receive commissions):
              <input name="email" type="text" size="50" maxlength="250" <?php if(isset($email)) echo ' value="' . $email . '"'; ?>">
          </p>
          <br />
		  <p>Promo Code <span class="style3">-- you may leave the default or choose your own (up to 10 characters)</span>:<br>
            <input name="code" type="text" size="10" maxlength="10" <?php if(isset($code)) echo ' value="' . $code . '"'; ?>">
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
