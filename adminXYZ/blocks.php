<?php
include('header.php');
?>
<div align="center">
  <div class="adminheading">Ads/Blocks</div>

  <?php
if(isset($_SESSION['admin'])) {
	$success = FALSE; // flag for showing or not showing form

	if (isset($_POST['submit']) && !empty($_POST['zone']) && isset($_POST['code'])) {
		$code = escape_data($_POST['code']);
		$zone = escape_data($_POST['zone']);
		$query = "SELECT * FROM agency_vars WHERE varname='$zone'";
		$result = mysql_query($query);
		if(mysql_num_rows($result) == 0) {
			mysql_query("INSERT INTO agency_vars (varname, varvalue) VALUES ('$zone', '$code')");
		} else {
			mysql_query("UPDATE agency_vars SET varvalue='$code' WHERE varname='$zone'");
		}
		if(mysql_affected_rows()) {
			$success = TRUE;
			echo '<br /><br /><div align="center"><font size="+1" color="#990000"><b>Your settings have been updated.  Thank you.</b></font><br /><br />';
			
			if(!empty($_POST['email'])) { // send test email
				$message = '<html>
					  <body>
					  ' .
					  $_POST['code'] .
					  '
					  </body>
					  </html>';
				
				$to = $_POST['email'];
				$from = "info@theagencyonline.com";
				$subject = "The Agency: TEST EMAIL";
			
				$headers  = "From: $from\r\n";
				$headers .= "Content-type: text/html\r\n";
		
				mail($to, $subject, $message, $headers);
						
				echo '<br /><br /><div align="center"><font size="+1" color="#990000"><b>Test email sent to "' . $to . '"</b></font><br /><br />';		
				
				
				
				
				
				
			}
			
		}
	}
?>
  <br />
  <div id="AGENCY_ul_spaced" style="text-align:left; font-weight:bold">
  Select an area to edit:<br /><br />


  <ul>
  <li><a href="blocks.php?zone=codetop#form">Top Banner Area</a></li>
  <li><a href="blocks.php?zone=coderight#form">Right Vertical Sidebar Area</a></li>
  <li><a href="blocks.php?zone=codeleft#form">Left Vertical Sidebar Area</a></li>
  <li><a href="blocks.php?zone=codebottom#form">Bottom of Page(links, etc)</a></li>
  <br />
  <li><a href="blocks.php?zone=homeundermenu_out#form">Home: Logged Out Under main menu</a></li>
  <li><a href="blocks.php?zone=homeundermenu_in#form">Home: Logged In Under main menu</a></li>
  <li><a href="blocks.php?zone=homeright#form">Home: Logged In Page Right Block</a></li>
  <li><a href="blocks.php?zone=homemiddle#form">Home: Logged In Page Middle Block</a></li>
  <li><a href="blocks.php?zone=homemiddle-right#form">Home: Logged In Page Middle and Right combined Block</a></li>
  <li><a href="blocks.php?zone=homeleft#form">Home: Logged In Page Left Block</a></li>
  <br />
  <li><a href="blocks.php?zone=alltalent#form">Profile: Talent sees on all profile pages</a></li>
  <li><a href="blocks.php?zone=clientontalent#form">Profile: Client sees on all profile pages</a></li>
  <li><a href="blocks.php?zone=profileguest#form">Profile: Public sees on all profile pages</a></li>
  <li><a href="blocks.php?zone=waiting#form">Profile: Top of Talent account before approval (incomplete and upaid)</a></li>
  <li><a href="blocks.php?zone=unpaid#form">Profile: Unpaid Talent</a></li>
  <li><a href="blocks.php?zone=ready#form">Profile: Top of Talent account ready for approval (including being paid)</a></li>
  <br />
  <li><a href="blocks.php?zone=payTalent#form">Talent Payment Page</a></li>
  <li><a href="blocks.php?zone=TalentSettings#form">Talent Settings Info Box</a></li>
  <li><a href="blocks.php?zone=highlights#form">Talent Settings Highlights Box</a></li>
  <li><a href="blocks.php?zone=regTalent#form">Next to Talent Registration on Home Page</a></li>
  <li><a href="blocks.php?zone=codeMyCast#form">Announcements on Upper part of Casting Area in personal profiles</a></li>
 <br />
  <li><a href="blocks.php?zone=waitingClient#form">Client Page: Top of account before approval</a></li>
  <li><a href="blocks.php?zone=waitingClient2#form">Client Page: Top of Client account before approval (after entering info)</a></li>
  <li><a href="blocks.php?zone=client_always#form">Client Page: Always visible on main Client page</a></li>
  <li><a href="blocks.php?zone=regClient#form">Client Registration Page</a></li>

  <li><a href="blocks.php?zone=ClientSettings#form">Client Settings Info Box</a></li>
  <li><a href="blocks.php?zone=clientsearch#form">Client Search Form Instructions</a></li>
 <br />
  <li><a href="blocks.php?zone=castingsbox#form">Top of Castings pages</a></li>
  <li><a href="blocks.php?zone=castingsbox2#form">Bottom of Castings pages</a></li>
  <li><a href="blocks.php?zone=contentpagebox#form">Top of Content pages</a></li>
  <li><a href="blocks.php?zone=levelsExp#form">Experience Levels Description (popup on rollover)</a></li>
  <li><a href="blocks.php?zone=email_payment_failed#form">Failed Payments Email Text (Active)</a></li>
  <li><a href="blocks.php?zone=email_payment_failed2#form">Failed Payments Email Text (Inactive)</a></li>
  <li><a href="blocks.php?zone=email_membership_canceled#form">Email sent to Member after they Cancel Membership</a></li>
  <br />
    <li><a href="blocks.php?zone=theangle#form">Under News (link to The Angle)</a></li>
  </ul>

  </div>

  <br /><br /><br />

 <a name="form">
  <br />
  <?php
	if (!$success && !empty($_GET['zone'])) {
		$zone = escape_data($_GET['zone']);
		$code = '';
		$query = "SELECT varvalue FROM agency_vars WHERE varname='$zone'";
		$result = mysql_query($query);
		if ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
			$code = $row['varvalue'];
		}
?>


  <form method="post" action="blocks.php">
  <b>Edit Block</b> (wait to fully load):<br />
  <textarea name="code" cols="80" rows="10"><?php if(isset($code)) echo $code;?></textarea>
  <input type="hidden" name="zone" value="<?php echo $zone; ?>"
    <br /><br />
    Send Test Email To: <input type="text" name="email" />
    <br /><br />
    <input type="submit" value="Update" name="submit">
  </form>
  <br />
  <br />
  <form action="index.php">
    <input type="submit" value="Cancel">
  </form>
  <br />
  <br />
  <br />
<script type="text/javascript" src="../fckeditor/fckeditor.js"></script>
<script type="text/javascript">
window.onload = function()
{
var oFCKeditor = new FCKeditor( 'code' ) ;
oFCKeditor.BasePath = "../fckeditor/" ;
oFCKeditor.Config["EditorAreaCSS"] = "custom.css"  ;
oFCKeditor.ReplaceTextarea() ;
}
</script>
<?php
	}
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
