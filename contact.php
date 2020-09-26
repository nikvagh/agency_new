<?php
@include('./includes/header.php');
require_once('./includes/recaptchalib.php');
$publickey = "6LcFTN0SAAAAAFitjQtOP2bVrB1YGlFr2WS1R07-";  
$privatekey = "6LcFTN0SAAAAAJIhKf8ZyomJ_5LPZShjBk1GRYvY";
?>
<br />
<div align="center">
<div class="AGENCYLtBlue AGENCYGeneralTitle">Send Us Your Casting Call</div>
You can expect a quick response! To instantly post a casting, you can also log in to your client account 24/7!</div>
<?php
$sent = false;

if(!empty($_POST['submitcontact'])) {
	$resp = recaptcha_check_answer ($privatekey,
                                $_SERVER["REMOTE_ADDR"],
                                $_POST["recaptcha_challenge_field"],
                                $_POST["recaptcha_response_field"]);

	if (!$resp->is_valid) {
		// What happens when the CAPTCHA was entered incorrectly
		echo '<div style="padding:50px; font-weight:bold; color:red; text-align:center; font-size:20px">The reCAPTCHA wasn\'t entered correctly. Please try again (any attachements must be reattached).</div>';
	} else {
		// Your code here to handle a successful verification
		$name = $_POST['name'];
		$company = $_POST['company'];   
		$email = $_POST['email'];
		$phone = $_POST['phone'];
		$details = $_POST['details'];
		$roles = $_POST['roles'];
		$notes = $_POST['notes'];
		
		$msg = '';
		if(empty($name)) {
			$msg .= '<p>Please Enter Your Name</p>';
		}
		if(empty($company)) {
			$msg .= '<p>Please Enter Your Company</p>';
		}
		if (!eregi('^[[:alnum:]][a-z0-9_\.\-]*@[a-z0-9\.\-]+\.[a-z]{2,4}$', stripslashes(trim($_POST['email'])))) {
			$msg .= '<p>Please Enter a valid Email address</p>';
		}
	
		if(empty($msg)) {
			$mailmessage = '<body><b>Name</b>: ' . $name  . '<br />
							<b>Company</b>: ' . $company  . '<br />
							<b>Email</b>: ' . $email  . '<br />
							<b>Phone</b>: ' . $phone  . '<br /><br />
							<b>Details</b>: ' . nl2br($details)  . '<br /><br />
							<b>Roles</b>: ' . nl2br($roles)  . '<br /><br />
							<b>Notes</b>: ' . nl2br($notes) . '</body>';
	
	
			$subject = 'AgencyOnline: Client Casting';
			$toemail = 'clients@theagencyOnline.com';
			
			// $toemail = 'ungabo@yahoo.com';
	
			require_once('PHPMailer/class.phpmailer.php');
			
			$mail             = new PHPMailer(); // defaults to using php "mail()"
			
			$body             = $mailmessage; // file_get_contents('contents.html');
			// $body             = preg_replace('/[\]/','',$body);
			// echo $body;
			
			$mail->SetFrom($email, $name);
			
			$mail->AddReplyTo($email, $name);
			
			$address = $toemail;
			$mail->AddAddress($address, "AGENCY: Client Casting Form");
			
			$mail->Subject    = $subject;
			
			$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
			
			$mail->MsgHTML($body);
			
			if($_FILES["fileAttach"]["name"] != "")  
			{  
				// echo $_FILES["fileAttach"]["tmp_name"];
				// $strFilesName = $_FILES["fileAttach"]["name"];  
				$mail->AddAttachment($_FILES["fileAttach"]["tmp_name"], $_FILES["fileAttach"]["name"]);      // attachment
				// AddAttachment($path,$name,$encoding,$type);
				// $mail->AddAttachment("images/phpmailer_mini.gif"); // attachment
			}
			
			if(!$mail->Send()) {
			  $msg .= "Mailer Error: " . $mail->ErrorInfo;
			  $sent = false;
			} else {
			  // echo "Message sent!";
			  $sent = true;
			}		
			
			unlink($_FILES["fileAttach"]["tmp_name"]);
		}
	}
}


if(!empty($msg)) {			
?>
<div id="failmessage" style="padding:10px; border:1px solid black">
<br />There was a problem with your information.  Please review and try again.  Any files will have to be reattached.
<b><?php echo $msg; ?></b>
<br /><br /><br />
<a href="javascript:void(0)" onclick="document.getElementById('failmessage').style.display='none'">close</a>
<br /><br />
</div>
<?php
}

if($sent) {
	echo '<div align="center" style="padding:100px"><b>THANK YOU FOR SENDING YOUR CASTING.</b></div>';
} else {
?>
<br />
<br />

        <form method="post" action="contact.php" enctype="multipart/form-data">
          <table align="center" border="0" cellspacing="0" cellpadding="4">
            <tr>
              <td colspan="2"><div align="center"><strong>Please include your Casting Call as an attachment, or fill in the appropriate boxes</strong></div><br /></td>
            </tr>
            <tr>
              <td align="right">Name:</td>
              <td><input name="name" type="text" size="25" <?php if(!empty($_POST['name'])) echo 'value="' . $_POST['name'] . '"'; ?> /></td>
            </tr>
            <tr>
              <td align="right">Company:</td>
              <td><input name="company" type="text" size="25" <?php if(!empty($_POST['company'])) echo 'value="' . $_POST['company'] . '"'; ?> /></td>
            </tr>
            <tr>
              <td align="right">Email:</td>
              <td><input name="email" type="text" size="25" <?php if(!empty($_POST['email'])) echo 'value="' . $_POST['email'] . '"'; ?> /></td>
            </tr>                
            <tr>
              <td align="right">Phone:</td>
              <td><input name="phone" type="text" size="25" <?php if(!empty($_POST['phone'])) echo 'value="' . $_POST['phone'] . '"'; ?> /></td>
            </tr>            
            <tr>
              <td align="right">Project Details:</td>
              <td><textarea name="details" style="width:250px; height:100px;"><?php if(!empty($_POST['details'])) echo $_POST['details']; ?></textarea></td>
            </tr>  
            <tr>
              <td align="right">Seeking/Role Description:</td>
              <td><textarea name="roles" style="width:250px; height:100px;"><?php if(!empty($_POST['roles'])) echo $_POST['roles']; ?></textarea></td>
            </tr>                         
            <tr>
              <td align="right">Additional Notes:</td>
              <td><textarea name="notes" style="width:250px; height:100px;"><?php if(!empty($_POST['notes'])) echo $_POST['notes']; ?></textarea></td>
            </tr>  
            <tr>
              <td align="right">Attachment:</td>
              <td><b>Or Attach Your Casting Call</b><br /><input name="fileAttach" type="file" /></td>
            </tr> 
           <tr>
              <td colspan="2" align="center"><br />please type in the words below:<br /><?php echo recaptcha_get_html($publickey); ?></td>
            </tr>  
            
            <tr>
              <td>&nbsp;</td>
              <td><input type="hidden" name="submitcontact" value="1" />
             	 <span id="submitmessage"><input type="submit" value="SUBMIT" onclick="" /></span>
              </td>
            </tr>
          </table>
        </form>
<?php
}

if(!is_active() && !isset($_SESSION['user_id'])) {
?>
<div align="center">
<br /><br /><br />
<b>Don't have an account yet? Client/Casting accounts are free! </b><br /><br />
	<img src="images/apply_now.gif" border="0" usemap="#Map2">
	<map name="Map2">
	  <area shape="rect" coords="89,2,277,45" href="#TB_inline?height=240&amp;width=450&amp;inlineId=hiddenModalContent" onclick="loaddiv('popupcontent', 'join_options')" class="thickbox">
	  <area shape="rect" coords="102,47,273,74" href="index2.php?pageid=59&title=What+We+Do">
	</map>
</div>
<?php
}

@include('./includes/footer.php');
?>
