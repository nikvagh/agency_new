<?php
include('../includes/mysql_connect.php');

$makepdf = true; // for testing

if($makepdf) {
	header("Content-type: application/pdf");
	define("RELATIVE_PATH", "../html2fpdf/");
	define("FPDF_FONTPATH","../html2fpdf/font/");

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
			$this->Cell(0,10,'www.theagency.com',0,0,'L');
		}
	}
	$pdf = new PDFX('P', 'mm', 'compcard');
	$pdf->AliasNbPages();
	$pdf->AddPage();
}
// $pdf->Output()
ob_start();

session_start();


include('../includes/agency_functions.php');
include('../forms/definitions.php');

?>
<div style="font-family:Arial, Helvetica, sans-serif">
<?php

if(!empty($_GET['u']) && (!empty($_GET['card_type'])) ) {
	$user_id = $userid = $_GET['u'];
	$type = $_GET['card_type'];

	$folder_profile_pic = $base_url.'uploads/users/' . $user_id . '/profile_pic/';
	$folder_profile_pic_thumb = $folder_profile_pic . 'thumb/';
	$folder_headshot = $base_url.'uploads/users/' . $user_id . '/headshot/';
	$folder_headshot_thumb = $folder_headshot . 'thumb/';
	$folder_card = $base_url.'uploads/users/' . $user_id . '/portfolio/';
	$folder_card_thumb = $folder_card . 'thumb/';
	$folder_audio = $base_url.'uploads/users/' . $user_id . '/audio/';
	$folder_portfolio = $base_url.'uploads/users/' . $user_id . '/portfolio/';
	$folder_portfolio_thumb = $folder_portfolio . 'thumb/';

	// first get the folder name
	$sql = "SELECT ap.*,fu.user_avatar FROM agency_profiles ap
			LEFT JOIN forum_users fu ON ap.user_id = fu.user_id
			WHERE ap.user_id='$userid'";
	$result=mysql_query($sql);

	?>
	<?php if($userInfo = $userinfo = sql_fetchrow($result)) { ?>

		<?php if($type == "large"){ ?>
			<table class="table" border="0" cellspacing="10" cellpadding="0" align="center">
				<tr>
					<td>
						<?php 
							if(file_exists($folder_profile_pic.$userInfo['user_avatar'])){
								$profile_pic = $folder_profile_pic.$userInfo['user_avatar'];
							}else{
								$profile_pic = $base_url."images/friend.gif";
							}
							echo $profile_pic;
						?>
						<img src="<?php echo $profile_pic; ?>" style="width:500px" />
						<h3 style="text-align:center;text-transform:uppercase"><?php echo $userInfo['firstname'].' '.$userInfo['lastname']; ?></h3>
						<p style="text-align:center;">
							Height: <?php echo $userInfo['height']; ?> &nbsp;&nbsp;&nbsp;&nbsp;
							Weight: <?php echo $userInfo['weight']; ?> &nbsp;&nbsp;&nbsp;&nbsp;
							Hair: <?php echo $userInfo['hair_color']; ?> &nbsp;&nbsp;&nbsp;&nbsp;
							Eye: <?php echo $userInfo['eye_color'].' '.$userInfo['eye_shape']; ?> &nbsp;&nbsp;&nbsp;&nbsp;
						</p>

						<!-- <img src="http://tamba30.us/theagency/uploads/users/104/profile_pic/thumb/128x128_1589030642_5945MCGRW426.jpg" /> -->
					</td>
					<td>
						<?php 
							$sql_photos = "SELECT ap.* FROM agency_photos ap
									WHERE ap.user_id='$userid' limit 4";
							$sql_photos=mysql_query($sql_photos);
						?>
						<table>
							<?php $cnt_card = 1; ?>
							<?php while ($row = sql_fetchrow($sql_photos)) { ?>
								<?php if(file_exists($folder_portfolio.$row['filename'])){ ?>
									<?php if($cnt_card % 2 != 0){ ?>
										<tr>
									<?php } ?>
										<td>
											<img src="<?php echo $folder_portfolio.$row['filename']; ?>" style="width:200px" />
										</td>
									<?php if($cnt_card % 2 == 0){ ?>
										</tr>
									<?php } ?>
								<?php } ?>
								<?php $cnt_card++; ?>
							<?php } ?>
						</table>
					</td>
				</tr>
			</table>

		<?php } ?>


	<?php }else{ ?>
		This page was not accessed correctly.
	<?php } ?>





	<?php 
	// $varemail="bookings@theagencyonline.com";
	// $varphone="212-944-0801";

	// if(isset($userid) && isset($folder)) {

	// 	echo '<table border="0" cellspacing="10" cellpadding="0" align="center"><tr>';
	// 	if(isset($folder)) {
	// 		 $sql = "SELECT * FROM agency_photos WHERE user_id='$userid' AND card_position IS NOT NULL ORDER BY card_position ASC";
	// 		 $result=mysql_query($sql);
	// 		 $current = 1;
	// 		 while(($row = sql_fetchrow($result)) && ($current <= 4)) {
	// 	 		switch($current) {
	// 				case 1:
	// 					echo '<td rowspan="2" valign="top"><img src="' . $folder . $row['filename'] . '" width="350px" /></td>';
	// 					break;
	// 				case 2:
	// 					echo '<td valign="top"><img src="' . $folder . $row['filename'] . '" width="175" /></td>';
	// 					break;
	// 				case 3:
	// 					echo '<td valign="top"><img src="' . $folder . $row['filename'] . '" width="175" /></td></tr>';
	// 					break;
	// 				case 4:
	// 					echo '<tr><td valign="bottom"><img src="' . $folder . $row['filename'] . '" width="175" /></td>';
	// 					break;
	// 			}
	// 			$current++;
	// 		 }
	// 	}
	// 	switch($current) {
	// 		case 1:
	// 			echo '<td rowspan="2" width="350">no images</td><td width="175"> </td><td width="175"> </td></tr><tr><td width="175"> </td>';
	// 			break;
	// 		case 2:
	// 			echo '<td width="175"> </td><td width="175"> </td></tr><tr><td width="175"> </td>';
	// 			break;
	// 		case 3:
	// 			echo '<td width="175"> </td></tr><tr><td width="175"> </td>';
	// 			break;
	// 		case 4:
	// 			echo '<tr><td width="175"> </td>';
	// 			break;
	// 	}

	// 	echo '<td valign="bottom">
	// 		<div align="center" style="font-size:x-small">
	// 		Height: ' . floor($userinfo['height']/12) . '\' ' . $userinfo['height'] % 12 . '"<br />
	// 		Weight: ' . $userinfo['weight'] . ' lbs<br />
	// 		Waist: ' . $userinfo['waist'] . '"<br />
	// 		Hair Color: ' . $userinfo['hair'] . '<br />
	// 		Eye Color: ' . $userinfo['eyes'] . '<br />
	// 		Shoe Size: ' . $userinfo['shoe'] . '<br />';


	// 	if($userinfo['gender'] != 'M') { // if female or "other"
	// 		echo ' Bust: ' . escape_data($userinfo['bust']) . '"<br />
	// 			Cup Size: ' . escape_data($bracups[$userinfo['cup']]) . '<br />
	// 			Hips: ' . escape_data($userinfo['hips']) . '"<br />';
	// 	}

	// 	if($userinfo['gender'] != 'F') { // if male or "other"
	// 		echo ' Suit: ' . agency_print_suit(escape_data($userinfo['suit'])) . '<br />
	// 			Neck: ' . escape_data($userinfo['neck']) . '"<br />
	// 			Inseam: ' . escape_data($userinfo['inseam']) . '"<br />';
	// 	}

	// 	 $sql = "SELECT * FROM agency_profile_unions WHERE user_id='$userid'";
	// 	 $result=mysql_query($sql);
	// 	 $num_results = mysql_num_rows($result);
	// 	 $current = 1;
	// 	 if($num_results) {
	// 	 	echo 'Union(s): ';
	// 		while($row = sql_fetchrow($result)) {
	// 	   		echo escape_data($row['union_name']);
	// 	   		if($current < $num_results) echo ', ';
	// 	   		$current++;
	// 		}
	// 	 }



	// 	echo '</div></td></tr>';


	// 	echo '<tr><td colspan="3"><div align="right"><br />';
	// 	if(agency_account_type() == 'client' && is_active()) {
	// 		if(!empty($userinfo['phone'])) echo '<br />p: ' . $varphone . '<br />';
	// 		$sql2 = "SELECT user_email FROM forum_users WHERE user_id='$userid'";
	// 		$result2=mysql_query($sql2);
	// 		if($row2 = sql_fetchrow($result2)) {  // "$userinfo" array will be available through file, so no need to access database again
	// 			echo 'email: ' . $varemail . '<br />';
	// 		}
	// 	}
	// 	echo '</div><br />';
	// 	echo '</td></tr></table>';
	// } else {
	//  	echo 'Error: folder not found';
	// }

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
