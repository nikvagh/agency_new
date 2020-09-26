<?php
include('header.php');

if(isset($_SESSION['admin'])) {


if (isset($_POST['delete'])) {
	$delete = $_POST['delete'];
	$query = "DELETE FROM agency_discounts WHERE discount_id='$delete'";
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
	
	if (stripslashes(trim($_POST['code']))) {
		$usage = (int) escape_data($_POST['usage']);
		if(empty($usage)) {
			$usage = 'NULL';
		}
		$code = escape_data($_POST['code']);
		$type = escape_data($_POST['type']);

		$query = "SELECT * FROM agency_discounts WHERE discount_code='$code'";
		$result = mysql_query ($query);
	
		$query2 = "SELECT mentor_code FROM agency_mentors WHERE mentor_code='$code'";
		$result2 = mysql_query ($query2);
		
		if (mysql_num_rows($result) == 0 && mysql_num_rows($result2) == 0) { // If it ran OK.			
			$query = "INSERT INTO agency_discounts (discount_code, discount_type, discount_usage) VALUES ( '$code', '$type', $usage)";		
			$result = mysql_query ($query);

			if (mysql_affected_rows() == 1) { // If it ran OK.	
				$success = TRUE;
				echo '<br /><br /><div align="center"><font size="+1" color="#990000"><b>Your new Discount Code has been added.  Thank you.</b></font><br><br><br></div>';
			} else {
				echo '<br /><br /><div align="center"><font size="+1" color="#990000"><b>Database error.  Please contact system administrator.</b></font><br><br><br></div>';
				$success = FALSE;
			}
		} else {
				echo '<br /><br /><div align="center"><font size="+1" color="#990000"><b>The Code you entered is already in the database (either as a Discount code or a Mentor code).  Please select another code.</b></font><br><br><br></div>';
				$success = FALSE;
		}

	} else {
		echo '<p align="center" style="color:red; font-weight:bold ">You must enter a Code and select a Type</p>';
		$success = FALSE;
	}
}

if (!$success) {
?>
<br>

<table width="400"  border="1" align="center" cellpadding="10" cellspacing="0" bgcolor="#F0F0F0">
  <tr>
    <td valign="top">
		<p>Please enter information for a new Discount</p>
        <form name="form1" method="post" action="discounts.php">
		  <p>Discount Code <span style="font-size:small">-- you may leave the default or choose your own (up to 10 characters)</span>:<br>
            <input name="code" type="text" size="10" maxlength="10" value="<?php echo $code; ?>">
          </p>
		  <p>Number of times code can be used <span style="font-size:small">-- leave blank for unlimited</span>:<br>
            <input name="usage" type="text" size="10" maxlength="10">
          </p>
		  
		  <p>Discount Type:<br>
            <input name="type" type="radio" value="freemonthwithcc" checked> 30 days free<br />
			<input name="type" type="radio" value="14daysfree"> 14 days free<br />
            <input name="type" type="radio" value="6weeksLA"> 45 days free LA only<br />
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
	echo '<div align="center"><a href="discounts.php?new=true">Add New Discount</a></div>';
}
?>
<div align="center">
<br><br>
<?php

echo '<div align="center"><p><b>DISCOUNTS:</b></p>';
if (isset($_GET['sort'])) {
	$sort = $_GET['sort'];
	switch ($sort) {
	case "code":
		$query = "SELECT * FROM agency_discounts ORDER BY discount_code";  
		break;
	case "type":
		$query = "SELECT * FROM agency_discounts ORDER BY discount_type";  
		break;
	case "usage":
		$query = "SELECT * FROM agency_discounts ORDER BY discount_usage";  
		break;
	default:
		$query = "SELECT * FROM agency_discounts ORDER BY discount_id";  
	}
} else {
	$query = "SELECT * FROM agency_discounts ORDER BY discount_id";  
}

$result = mysql_query ($query);
if (mysql_affected_rows() > 0) { // If there are projects.
	echo '<table  bgcolor="#EEEEEE" border="1" cellspacing="0" cellpadding="4" align="center"><tr><td width="120"><a href="discounts.php?sort=code">Discount Code</a></td><td width="120"><a href="discounts.php?sort=usage">Remaining</a></td><td></td></tr>';
	while ($row = mysql_fetch_array ($result, MYSQL_ASSOC)) {
		$DiscountID = $row['discount_id'];
		// $Type = $row['discount_type'];
		$Usage = $row['discount_usage'];
		$Code = $row['discount_code'];
		echo '<tr><td>' . $Code . '</td><td>' . $Usage .
			'</td><td><form action="discount_view.php?id=' . $DiscountID . '" method="post"><input name="submit" type="submit" value="View Details"></form></td></tr>'; 
	}
	echo '</table>';
} else {
	echo '<b>There are no Discounts yet.</b>';
}
echo '</div><br /><br />Click "View Details" for each discount code to see the members who have been signed up with that discount code.
<br /><br />Please note that even though a member has signed up using the code, they haven\'t necessarily paid for their membership.
<br /><br />If you would like to view all members who have signed up using a discount code, click the link below:
<br /><br /><a href="userlist.php?filter=discounts">view all discount code signups</a><br />';
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
