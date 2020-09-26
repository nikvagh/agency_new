<?php
$makepdf = true; // for testing

if($makepdf) {
	header("Content-type: application/pdf");
	define("RELATIVE_PATH", "html2fpdf/");
	define("FPDF_FONTPATH","html2fpdf/font/");

	require_once(RELATIVE_PATH."html2fpdf.php");

	class PDFX extends HTML2FPDF
	{
		//Page header
		function Header() {
			//Logo
  			// $this->Image('images/logo.png',24,14,20);
		 }
		 function Footer() {
				$this->SetY(-15);
				$this->SetTextColor(0,0,255);
				$this->Cell(0,10,'www.theagencyOnline.com',0,0,'L');
		 }
	}
	$pdf=new PDFX('P', 'mm', 'compcard');
	$pdf->AliasNbPages();
	$pdf->AddPage();
}
// $pdf->Output()
ob_start();

session_start();

include('includes/mysql_connect.php');
include('includes/agency_functions.php');
include('forms/definitions.php');

?>
<div align="center" style="font-family:Arial, Helvetica, sans-serif">
<?php

if(!empty($_GET['u'])) {
	$userid = $_GET['u'];
	$varemail="bookings@theagencyonline.com";
	$varphone="212-944-0801";

	// first get the folder name
	$sql = "SELECT * FROM agency_profiles WHERE user_id='$userid'";
	$result=mysql_query($sql);
	if($userinfo = sql_fetchrow($result)) {  // "$userinfo" array will be available through file, so no need to access database again
		$folder = 'talentphotos/' . $userid . '_' . $userinfo['registration_date'] . '/';

		$displayname = $userinfo['firstname'];
		if(agency_privacy($userid, 'lastname')) {
		 	$displayname .= ' ' . $userinfo['lastname'];
		}
		echo '<div align="right" style="font-size:24px">' . $displayname . '</div>';
	}

	if(isset($userid) && isset($folder)) { // if folder (reg date) is not found, no images will be found, something is wrong so don't display
	  //  echo '<div style="border:1px solid #999; width:600px; height:600px; position:absolute; top:0px">';
	  // echo '<div style="font-size:medium; font-weight:bold; padding-left:10px; width:300px; color:blue">CompCard</div>';

		echo '<table border="0" cellspacing="10" cellpadding="0" align="center"><tr>';
		if(isset($folder)) {
			 $sql = "SELECT * FROM agency_photos WHERE user_id='$userid' AND card_position IS NOT NULL ORDER BY card_position ASC";
			 $result=mysql_query($sql);
			 $current = 1;
			 while(($row = sql_fetchrow($result)) && ($current <= 4)) {
		 		switch($current) {
					case 1:
						echo '<td rowspan="2" valign="top"><img src="' . $folder . $row['filename'] . '" width="350px" /></td>';
						break;
					case 2:
						echo '<td valign="top"><img src="' . $folder . $row['filename'] . '" width="175" /></td>';
						break;
					case 3:
						echo '<td valign="top"><img src="' . $folder . $row['filename'] . '" width="175" /></td></tr>';
						break;
					case 4:
						echo '<tr><td valign="bottom"><img src="' . $folder . $row['filename'] . '" width="175" /></td>';
						break;
				}
				$current++;
			 }
		}
		switch($current) {
			case 1:
				echo '<td rowspan="2" width="350">no images</td><td width="175"> </td><td width="175"> </td></tr><tr><td width="175"> </td>';
				break;
			case 2:
				echo '<td width="175"> </td><td width="175"> </td></tr><tr><td width="175"> </td>';
				break;
			case 3:
				echo '<td width="175"> </td></tr><tr><td width="175"> </td>';
				break;
			case 4:
				echo '<tr><td width="175"> </td>';
				break;
		}

		echo '<td valign="bottom">
			<div align="center" style="font-size:x-small">
			Height: ' . floor($userinfo['height']/12) . '\' ' . $userinfo['height'] % 12 . '"<br />
			Weight: ' . $userinfo['weight'] . ' lbs<br />
			Waist: ' . $userinfo['waist'] . '"<br />
			Hair Color: ' . $userinfo['hair'] . '<br />
			Eye Color: ' . $userinfo['eyes'] . '<br />
			Shoe Size: ' . $userinfo['shoe'] . '<br />';


		if($userinfo['gender'] != 'M') { // if female or "other"
			echo ' Bust: ' . escape_data($userinfo['bust']) . '"<br />
				Cup Size: ' . escape_data($bracups[$userinfo['cup']]) . '<br />
				Hips: ' . escape_data($userinfo['hips']) . '"<br />';
		}

		if($userinfo['gender'] != 'F') { // if male or "other"
			echo ' Suit: ' . agency_print_suit(escape_data($userinfo['suit'])) . '<br />
				Neck: ' . escape_data($userinfo['neck']) . '"<br />
				Inseam: ' . escape_data($userinfo['inseam']) . '"<br />';
		}

		 $sql = "SELECT * FROM agency_profile_unions WHERE user_id='$userid'";
		 $result=mysql_query($sql);
		 $num_results = mysql_num_rows($result);
		 $current = 1;
		 if($num_results) {
		 	echo 'Union(s): ';
			while($row = sql_fetchrow($result)) {
		   		echo escape_data($row['union_name']);
		   		if($current < $num_results) echo ', ';
		   		$current++;
			}
		 }



		echo '</div></td></tr>';


		echo '<tr><td colspan="3"><div align="right"><br />';
		if(agency_account_type() == 'client' && is_active()) {
			if(!empty($userinfo['phone'])) echo '<br />p: ' . $varphone . '<br />';
			$sql2 = "SELECT user_email FROM forum_users WHERE user_id='$userid'";
			$result2=mysql_query($sql2);
			if($row2 = sql_fetchrow($result2)) {  // "$userinfo" array will be available through file, so no need to access database again
				echo 'email: ' . $varemail . '<br />';
			}
		}
		echo '</div><br />';
		// echo '<div align="left" style="color:blue">www.theagencyOnline.com</div>';
		echo '</td></tr></table>';
	} else {
	 	echo 'Error: folder not found';
	}
} else {
	echo 'This page was not accessed correctly.';
}
mysql_close(); // Close the database connection.
?>
</div>

<?php
$content = ob_get_clean();

if($makepdf) {
	$pdf->WriteHTML($content);
	$pdf->Output();
} else {
	echo $content;
}
?>
