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
  			  $this->Image('images/logo.png',24,14,20);
		 }
		 function Footer() {
				$this->SetY(-15);
				$this->SetTextColor(0,0,255);
				$this->Cell(0,10,'www.theagencyOnline.com',0,0,'L');
		 }
	}
	$pdf=new PDFX();
	$pdf->AliasNbPages();
	$pdf->AddPage();
}
// $pdf->Output()
ob_start();
session_start();

include('includes/mysql_connect.php');
include('includes/agency_functions.php');

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
		echo '<div align="right" style="font-size:24px; width:550px">' . $displayname . '</div>';
	}

	if(isset($userid) && isset($folder)) { // if folder (reg date) is not found, no images will be found, something is wrong so don't display
	  //  echo '<div style="border:1px solid #999; width:600px; height:600px; position:absolute; top:0px">';
	  // echo '<div style="font-size:medium; font-weight:bold; padding-left:10px; width:300px; color:blue">CompCard</div>';

		if(isset($folder)) {
			 $sql = "SELECT * FROM agency_photos WHERE user_id='$userid' ORDER BY order_id ASC";
			 $result=mysql_query($sql);

			 $total = mysql_num_rows($result);
			 $counter = 1;
			 $flag = true;
			 while($counter <= $total) {
				 $current = 1;
				 echo '<table border="0" cellspacing="10" cellpadding="0" align="center">';
				 while($current <= 4 && $flag) {
					if($row = sql_fetchrow($result)) {
						switch($current) {
							case 1:
								echo '<tr><td valign="top"><img src="' . $folder . $row['filename'] . '" width="220" /></td>';
								break;
							case 2:
								echo '<td valign="top"><img src="' . $folder . $row['filename'] . '" width="220" /></td></tr>';
								break;
							case 3:
								echo '<tr><td valign="top"><img src="' . $folder . $row['filename'] . '" width="220" /></td>';
								break;
							case 4:
								echo '<td valign="top"><img src="' . $folder . $row['filename'] . '" width="220" /></td></tr>';
								break;
						}
						 $current++;
					 } else {
					 	$flag = false;
					 }
					 $counter++;
				}

				 if($current == 4) { // only in this case do we need to add one more cell.
				 	echo '<td> </td></tr>';
					$counter = $total+1;
				} else if($current == 2) {
					echo '</tr>';
				}

				echo '<tr><td colspan="3">';
				echo '<br /><div align="right"><br />';
				if(agency_account_type() == 'client' && is_active()) {
					if(!empty($userinfo['phone'])) {
						echo '<br />p: ' . $varphone . '<br />';
					}
					$sql2 = "SELECT user_email FROM forum_users WHERE user_id='$userid'";
					$result2=mysql_query($sql2);
					if($row2 = sql_fetchrow($result2)) {  // "$userinfo" array will be available through file, so no need to access database again
						echo 'email: ' . $varemail . '<br />';
					}
				}
				echo '</div>';
				// echo '<div align="left" style="color:blue">www.theagencyOnline.com</div>';
				echo '</td></tr></table>';

				if($makepdf && $flag) {
					$content = ob_get_clean();
					$pdf->WriteHTML($content);
					$pdf->AddPage();
					ob_start();
				}
			}
		}
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
