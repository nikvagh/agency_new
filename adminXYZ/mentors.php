<?php
include('header.php');

if(isset($_SESSION['admin'])) {

echo '<div align="center"><b>MENTORS:</b></div><br />';

if (isset($_POST['delete'])) {
	$delete = $_POST['delete'];
	$query = "DELETE FROM agency_mentors WHERE mentor_id='$delete'";
	$result = mysql_query ($query);
}

if (isset($_GET['new'])) {
	$success = FALSE; // flag for showing or not showing form
} else {
	$success = TRUE;
}

function getcode($iter) {
	if ($iter > 100) { // iter is there to count the number of recursive calls to this function.  If after 100 tries it still can't find a unique code, the function will quit to avoid getting stuck in a loop
		return 'error';
	} else {
		$c = rand(100000, 999999);
		
		$query = "SELECT * FROM agency_discounts WHERE discount_code='$c'";
		$result = mysql_query ($query);
	
		$query2 = "SELECT mentor_code FROM agency_mentors WHERE mentor_code='$c'";
		$result2 = mysql_query ($query2);
		
		if (mysql_num_rows($result) > 0 || mysql_num_rows($result2) > 0) { // If this code is already being used.	
			$iter++;
			return getcode($iter);
		} else {
			return $c;
		}
	}
}

if (isset($_POST['code'])) {
	$code = $_POST['code'];
} else {
	$code = getcode(0);
}
if (isset($_POST['Submit'])) {
	
	if (stripslashes(trim($_POST['firstname'])) && stripslashes(trim($_POST['lastname']))) {
		$firstname = escape_data($_POST['firstname']);
		$lastname = escape_data($_POST['lastname']);
		$email = escape_data($_POST['email']);
		$code = escape_data($_POST['code']);

		$query = "SELECT * FROM agency_mentors WHERE firstname='$firstname' AND lastname='$lastname'";
		$result = mysql_query ($query);
	
		if (mysql_affected_rows() == 0) { // If it ran OK.			
			$query = "SELECT * FROM agency_discounts WHERE discount_code='$code'";
			$result = mysql_query ($query);
		
			$query2 = "SELECT mentor_code FROM agency_mentors WHERE mentor_code='$code'";
			$result2 = mysql_query ($query2);
			
			if (mysql_num_rows($result) == 0 && mysql_num_rows($result2) == 0) { // If it ran OK.			
			
				$query = "INSERT INTO agency_mentors (firstname, lastname, paypal_email, mentor_code) VALUES ( '$firstname', '$lastname', '$email', '$code' )";		
				$result = mysql_query ($query);

				if (mysql_affected_rows() == 1) { // If it ran OK.	
					$success = TRUE;
					echo '<br /><br /><div align="center"><font size="+1" color="#990000"><b>Your new Mentor has been added.  Thank you.</b></font><br><br><br></div>';
				} else {
					echo '<br /><br /><div align="center"><font size="+1" color="#990000"><b>Database error.  Please contact system administrator.</b></font><br><br><br></div>';
					$success = FALSE;
				}
			} else {
				echo '<br /><br /><div align="center"><font size="+1" color="#990000"><b>The Code you entered is already in the database (either as a Discount code or a Mentor code).  Please select another code.</b></font><br><br><br></div>';
				$success = FALSE;
			}
		} else {
				echo '<br /><br /><div align="center"><font size="+1" color="#990000"><b>The name you entered is already in the database.</b></font><br><br><br></div>';
				$success = FALSE;
		}

	} else {
		echo '<p align="center" style="color:red; font-weight:bold ">You must enter a First and Last Name</p>';
		$success = FALSE;
	}
}

if (!$success) {
?>
<style type="text/css">
<!--
.style3 {font-size: x-small}
-->
</style>

<br>

<table width="400"  border="1" align="center" cellpadding="10" cellspacing="0" bgcolor="#F0F0F0">
  <tr>
    <td valign="top">
		<p>Please enter information for a new Mentor</p>
        <form name="form1" method="post" action="mentors.php">
          <p>First Name:
              <input name="firstname" type="text" size="50" maxlength="250" value="<?php if(isset($_POST['firstname'])) echo $_POST['firstname']; ?>">
          </p>
          <p>Last Name:
              <input name="lastname" type="text" size="50" maxlength="250" value="<?php if(isset($_POST['lastname'])) echo $_POST['lastname']; ?>">
          </p>
          <p>PayPal Email (commisions are sent to this email address; please confirm it is their PayPal email or they will not receive commissions):
              <input name="email" type="text" size="50" maxlength="250" value="<?php if(isset($_POST['email'])) echo $_POST['email']; ?>">
          </p>
          <br />
		  <p>Promo Code <span class="style3">-- you may leave the default or choose your own (up to 10 characters)</span>:<br>
            <input name="code" type="text" size="10" maxlength="10" value="<?php echo $code; ?>">
          </p>
			<p align="center"><br>
                      <input type="submit" name="Submit" value="Submit">
          <div align="center">
                          <p></p>
          </div>
        </form></td>
  </tr>
</table>
<?php
} else {
	echo '<div align="center"><a class="viewbutton" style="text-decoration:none" href="mentors.php?new=true">Add New Mentor</a></div>';
}
?>
<div align="center">
<br><div align="center"><a class="viewbutton" style="text-decoration:none" href="mentors_pay.php">Process Payments</a></div><br>
<?php

if(isset($_GET['delete'])) {
	$id = $_GET['delete'];
	$query = "DELETE FROM agency_mentors WHERE mentor_code='$id'";
	$result = mysql_query ($query);
}

if (isset($_GET['sort'])) {
	$sort = $_GET['sort'];
	switch ($sort) {
	case "lastname":
		$query = "SELECT * FROM agency_mentors ORDER BY lastname";  
		break;
	case "firstname":
		$query = "SELECT * FROM agency_mentors ORDER BY firstname";  
		break;
	case "email":
		$query = "SELECT * FROM agency_mentors ORDER BY paypal_email";  
		break;
	case "code":
		$query = "SELECT * FROM agency_mentors ORDER BY mentor_code";  
		break;
	default:
		$query = "SELECT * FROM agency_mentors ORDER BY lastname";  
	}
} else {
	$query = "SELECT * FROM agency_mentors ORDER BY lastname";  
}

$result = mysql_query ($query);
if (mysql_affected_rows() > 0) { // If there are projects.
	echo '<table  bgcolor="#EEEEEE" border="1" cellspacing="0" cellpadding="4" align="center"><tr><td width="120"><a href="mentors.php?sort=firstname">First Name</a></td><td width="120"><a href="mentors.php?sort=lastname">Last Name</a></td><td width="120"><a href="mentors.php?sort=email">Email</a></td><td width="50"><a href="mentors.php?sort=code">Code</a></td><td></td></tr>';
	while ($row = mysql_fetch_array ($result, MYSQL_ASSOC)) {
		$MentorID = $row['mentor_id'];
		$Firstname = $row['firstname'];
		$Lastname = $row['lastname'];
		$Email = $row['paypal_email'];
		$Code = $row['mentor_code'];
		echo '<tr><td>' . $Firstname . '</td><td>' . $Lastname . '</td><td>' . $Email .
			'</td><td>' . $Code . '</td><td><form action="mentor_view.php?id=' . $MentorID . '" method="post"><input name="submit" type="submit" value="View Details"></form></td></tr>'; 
	}
	echo '</table>';
} else {
	echo '<b>There are no Mentors yet.</b>';
}
echo '</div><br /><br />Click "View Details" for each mentor to see the members who have been signed up with that mentors\'s code.<br />Please note that even though a member has signed up using the code, they haven\'t necessarily paid for their membership.<br />If you would like to view all members who have signed up using a code, click the link below:<br /><br /><a href="userlist.php?filter=referred">view all referal signups</a><br />';
?>
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
