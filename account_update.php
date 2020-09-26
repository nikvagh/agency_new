<?php
$pagetitle = 'Edit Account';
@include('includes/header.php');
include('includes/regionarrays.php');

if(isset($_SESSION['user_id'])) {
	
	$userid = (int) $_SESSION['user_id'];
			
	if($_GET['mode'] == 'updated') {
		
?>
<div align="center" style="font-size:16px; font-weight:bold">Your Payment Information Was Successfully Updated</div>
<br />
<br />
<br />
<?php
	$payment_action = 'status';
	include('adminXYZ/payment/ManageRecurringPaymentsProfile.php'); // this processes the payment
	if($paypal_active && !empty($billingPeriod) && !empty($billingFreq) && !empty($amount)) {
		$pay_term = urldecode($amount) . ':' . $billingFreq . ':' . $billingPeriod[0]; // '9.95:1:M'
		echo '<b>Your current payment plan is $' . urldecode($amount) . ' billed once every ' . $billingFreq . ' ' . $billingPeriod . '</b>';
		

		}
	} else if($_GET['mode'] == 'payment') {
?>
<div align="center" style="font-size:16px; font-weight:bold">Update Payment Information</div>
<br />
<br />
<br />
<?php
		// if there's an active payment profile, give option to cancel it
		$paypal_profile_id = get_rec_payment_id($userid);
		
		if(!empty($_SESSION['admin'])) {
			// echo 'only admin sees this: ' . $paypal_profile_id;
		}
		
		if(!empty($paypal_profile_id)) {
			// $paypal_profile_id = 'I-8K6FK3EGHR9H';
			$payment_action = 'status';
			include('adminXYZ/payment/ManageRecurringPaymentsProfile.php'); // this processes the payment
			if($paypal_active && !empty($billingPeriod) && !empty($billingFreq) && !empty($amount)) {
				$pay_term = urldecode($amount) . ':' . $billingFreq . ':' . $billingPeriod[0]; // '9.95:1:M'
				$paydesc = 'Your current payment plan is $' . urldecode($amount) . ' billed once every ' . $billingFreq . ' ' . $billingPeriod;
				if($billingFreq > 1) $paydesc .= 's';
				
				$old_pay_term = $pay_term;
				
				$days_left = ceil((strtotime($nextBillingDate) - strtotime("now"))/(60*60*24));
				if($days_left < 0) {
					$days_left = 0;
				}
				$startDate = date('Y-m-d\TH:i:s.00\Z', strtotime("+" . $days_left . " DAYS"));
				// echo '<br /><br />CYCLE 1 INFO: ' . $nextBillingDate . ' | ' . $days_left . ' | ' . $startDate . '<br /><br />';

?>
<a style="color:black; background-color:#ddd; padding:4px; border:1px solid #999; text-decoration:none" href="account_update.php?mode=cancel_membership" onclick="return confirm('Are you sure you want to cancel your membership and deactivate your profile? You will not be able to submit on castings, and your profile will no longer be visible on the site!')">Cancel Membership</a><br /><br />
<?php
			}

			if (isset($_POST['submit'])) { // Handle the form.
				// if ($_SESSION['form_token'] == $_POST['form_token']) {
				if(true) {
					unset($_SESSION['form_token']);

					$BillFname = trim($_POST['BillFname']);
					$BillLname = trim($_POST['BillLname']);
					$BillStreet = trim($_POST['BillStreet']);
					$BillStreet2 = trim($_POST['BillStreet2']);
					$BillCity = trim($_POST['BillCity']);
					$BillState = trim($_POST['BillState']);
					$BillZip = trim($_POST['BillZip']);
					$BillCountry = trim($_POST['BillCountry']);
					$CardType = trim($_POST['CardType']);
					$CardNumber = trim($_POST['CardNumber']);
					// remove spaces from credit card
					$CardNumber = ereg_replace( '[^0-9]+', '', $CardNumber );
					$CVV = trim($_POST['CVV']);
					$ExpMonth = trim($_POST['ExpMonth']);
					$ExpYear = trim($_POST['ExpYear']);
					
					if(!empty($_POST['pay_term'])) {
						$pay_term = escape_data($_POST['pay_term']);
					} else {
						$pay_term = $old_pay_term;
					}
		
					$paypal_profile_id = get_rec_payment_id($userid);
					// $paypal_profile_id = 'I-8K6FK3EGHR9H';
					
					if(!empty($paypal_profile_id) && !empty($BillFname) && !empty($BillLname) && !empty($BillStreet) && !empty($BillCity) && !empty($BillState) && !empty($BillZip) && !empty($CardType) && !empty($CardNumber) && !empty($CVV) && !empty($ExpMonth) && !empty($ExpYear)) {
		
						// process order
						$customer_first_name = escape_data($BillFname);
						$customer_last_name = escape_data($BillLname);
						$customer_credit_card_type = escape_data($CardType);
						// remove spaces from credit card
						$CardNumber = ereg_replace( '[^0-9]+', '', $CardNumber );
						$CardNumber = escape_data($CardNumber);
						$customer_credit_card_number = escape_data($CardNumber);
						$cc_expiration_month = escape_data($ExpMonth);
						$cc_expiration_year = escape_data($ExpYear);
						$cc_cvv2_number = escape_data($CVV);
						$customer_address1 = escape_data($BillStreet);
						$customer_address2 = escape_data($BillStreet2);
						$customer_city = escape_data($BillCity);
						$customer_state = escape_data($BillState);
						$customer_zip = escape_data($BillZip);
						$customer_country = escape_data($BillCountry);
						
						// echo 'Pay Terms: ' . $pay_term . ' | ' . $old_pay_term;
						
						if($pay_term == $old_pay_term) { // if payment period has not changed, just update
							$payment_action = 'update';
							include('adminXYZ/payment/ManageRecurringPaymentsProfile.php'); // this processes the payment						
					
							if($paypal_update) {
								echo 'Your Payment Information has been updated.  Thank you.';
							} else {
								echo 'There was a problem updating your Payment Information.  Please contact us for assistance.';
							}							
							
						} else {
						
							switch($pay_term) {
								case '9.95:1:M':	// $9.95 per Month
									$periodcode = 1;
									break;
								case '24.95:3:M': // $24.95 for 3 months
									$periodcode = 2;
									break;
								case '89.95:1:Y': // $89.95 per Year
									$periodcode = 3;
									break;
								default:
									$periodcode = 1;
									break;
							}						
							
						
							// $days_into_cycle = floor((strtotime("now") - strtotime($lastPaymentDate))/(60*60*24));
							
							// echo 'CYCLE INFO: ' . $lastPaymentDate . ' | ' . $days_into_cycle;
							
							// $startDate = urlencode("2010-9-6T0:0:0");
							// $startDate = urlencode("2009-11-13T18:00:00Z");
							// $startDate = date('Y-m-d\TH:i:s.00\Z', strtotime("+$days_into_cycle DAYS", "now"));
							// $startDate = date('Y-m-21\TH:i:s');
							// $startDate = '2009-11-21T04:00:00';
							
							// echo '<br><br>' . $startDate . '<br><br>';
							
							
							// $startDate is defined when Status is retrieved
							include('adminXYZ/payment/RedefineRecurringPaymentsProfile.php'); // this processes the payment
							
							if($paypal_update) {
								// $paypal_profile_id =  get_rec_payment_id($userid);  // NO, this will cancel the new one, use var from pageload
								// cancel previous payment profile
								if(!empty($paypal_profile_id)) {
									$payment_action = 'cancel';
									include('adminXYZ/payment/ManageRecurringPaymentsProfile.php'); // this processes the payment
								}
								
								if($paypal_cancel) {
									echo 'Your subscription has successfully been updated';
									$url = 'account_update.php?mode=updated';
									ob_end_clean(); // Delete the buffer.
									header("Location: $url");
									exit(); // Quit the script.		
									
								} else {
									echo 'There was a problem cancelling your previous payment plan.  Please contact us for assistance.';
								}	
							} else {
								echo 'There was a problem changing your payment plan.  Please be sure you have entered your payment information correctly or contact us for assistance.';
							}
						}

	
	
	
		
						/* $url = 'http://' . URL_SITENAME . '/profile.php?u=' . $userid . '&payment=true';
						ob_end_clean(); // Delete the buffer.
						header("Location: $url");
						exit(); // Quit the script. */
		
					} else {
						$stage = 'creditincomplete';
					}
				}
			} else { // form not submitted, so get information
				// get firstname, lastname, make sure it's the right type
				$sql = "SELECT firstname, lastname, account_type FROM agency_profiles WHERE user_id='$userid'";
				$result = mysql_query($sql);
				$row = sql_fetchrow($result);
				unset($result);
		
				if (!$row)
				{
					// break; // this will change, if there is no row, one will have to be created with at least a type
				}
				$type = $row['account_type'];
				$BillFname = $row['firstname'];
				$BillLname = $row['lastname'];
		
				if($type == 'client') { // they don't have to pay, redirect to login
					$url = 'http://' . URL_SITENAME . '/home.php?trace=3';
					ob_end_clean(); // Delete the buffer.
					header("Location: $url");
					exit(); // Quit the script.
				}
		
			} // End of the main Submit conditional.


?>
<br />
<br />
<b><?php echo $paydesc; ?></b>
<br />
<br />
Use the form below to update your billing information:
  <br />
<?php
			if($stage == 'creditincomplete') { // problem with billing information
				echo '<br /><div align="left"><font color=red><b>There was a problem processing your credit information.<br />';

				echo 'Please review your entries below.</b><br /><br /></font></div>';
			}
?>
              <br />
  <form action="<?php if(URL_SITENAME == 'www.theagencyonline.com') { echo 'https://'; } else { echo 'http://'; } echo URL_SITENAME; ?>/account_update.php?mode=payment<?php if(isset($_GET['action'])) echo '&action=check'; if(isset($_GET['token'])) echo '?token=' . $_GET['token']; ?>" method="post" id="payment" name="payment">
    <table width="430"  border="0" cellpadding="3" cellspacing="3">
      <tr>
        <td  class="AGENCYregtableleft">First Name<span class="paren"> (as it appears on card)</span>:</td>
        <td class="AGENCYregtableright"><input type="text" id="BillFname" name="BillFname" value="<?php if(isset($BillFname)) echo $BillFname; ?>" />
        </td>
      </tr>
      <tr>
        <td class="AGENCYregtableleft">Last Name<span class="paren"> (as it appears on card)</span>:</td>
        <td class="AGENCYregtableright"><input type="text" id="BillLname" name="BillLname" value="<?php if(isset($BillLname)) echo $BillLname; ?>" />
        </td>
      </tr>
      <tr>
        <td class="AGENCYregtableleft" valign="top">Street Address:</td>
        <td class="AGENCYregtableright"><input type="text" id="BillStreet" name="BillStreet" value="<?php if(isset($BillStreet)) echo $BillStreet; ?>" /><br /><br />
        								<input type="text" name="BillStreet2" value="<?php if(isset($BillStreet2)) echo $BillStreet2; ?>" />
        </td>
      </tr>
      <tr>
        <td class="AGENCYregtableleft">City:</td>
        <td class="AGENCYregtableright"><input type="text" id="BillCity" name="BillCity" value="<?php if(isset($BillCity)) echo $BillCity; ?>" />
        </td>
      </tr>

      <tr>
        <td class="AGENCYregtableleft">Country:</td>
        <td class="AGENCYregtableright"><select id="BillCountry" name="BillCountry" onChange="changecountry(this.value)">
        <option value=""> -- Select Country -- </option>
<?php
			foreach($countryarray as $abr=>$c) {
				echo '<option value="' . $c . '"';
				if(isset($BillCountry)) {
					if($BillCountry == $c) {
						echo ' selected';
					}
				}
				echo '>' . $c . '</option>';
			}
?>
		</select>
		</td>
		</tr>

      <tr>
      <td class="AGENCYregtableleft">State:</td>
        <td class="AGENCYregtableright" id="statediv">
<?php
			$showstates = false; // if true, the states of the US will display in a dropdown
			if(isset($country)) {
				if($country == 'United States') {
					$showstates = true;
				}
			}
			if($showstates) {
				echo '<select id="BillState" name="BillState">';
				foreach($stateList['US'] as $abr=>$state) {
					echo '<option value="' . $state . '"';
					if(isset($BillState)) {
						if($BillState == $state) {
							echo ' selected';
						}
					}
					echo '>' . $state . '</option>';
				}
				echo '</select>';
			} else {
?>
        <input type="text" id="BillState" name="BillState" maxlength="50" value="<?php if (!empty($state)) echo $state; ?>" />
<?php
			}
?>
      </tr>

      <tr>
        <td class="AGENCYregtableleft">Zip/Postal Code:</td>
        <td class="AGENCYregtableright"><input type="text" id="BillZip" name="BillZip" value="<?php if(isset($BillZip)) echo $BillZip; ?>" />
        </td>
      </tr>
      <tr>
        <td class="AGENCYregtableleft">Card Type:</td>
        <td class="AGENCYregtableright">
        	<select id="CardType" name="CardType">
                <option value="Visa" <?php if(isset($CardType)) {if($CardType == 'Visa') echo 'selected'; } ?>>Visa</option>
                <option value="MasterCard" <?php if(isset($CardType)) {if($CardType == 'MasterCard') echo 'selected'; } ?>>MasterCard</option>
                <option value="Discover" <?php if(isset($CardType)) {if($CardType == 'Discover') echo 'selected'; } ?>>Discover</option>
                <option value="Amex" <?php if(isset($CardType)) {if($CardType == 'Amex') echo 'selected'; } ?>>Amex</option>
              </select>
        </td>
      </tr>
      <tr>
        <td class="AGENCYregtableleft">Card Number:</td>
        <td class="AGENCYregtableright"><input type="text" id="CardNumber" name="CardNumber" value="<?php if(isset($CardNumber)) echo $CardNumber; // SSL is advised ?>" />
        </td>
      </tr>
      <tr>
        <td class="AGENCYregtableleft">CVV <span class="paren">(3 or 4 digit number located on back of card)</span>:</td>
        <td class="AGENCYregtableright"><input type="text" id="CVV" name="CVV" size="4" value="<?php if(isset($CVV)) echo $CVV; // SSL is advised ?>" />
        </td>
      </tr>
      <tr>
        <td class="AGENCYregtableleft">Expires:</td>
        <td class="AGENCYregtableright">
 Month
              <select name="ExpMonth">
                <option value="1" <?php if(isset($ExpMonth)) {if($ExpMonth == '1') echo 'selected'; } ?>>1</option>
                <option value="2" <?php if(isset($ExpMonth)) {if($ExpMonth == '2') echo 'selected'; } ?>>2</option>
                <option value="3" <?php if(isset($ExpMonth)) {if($ExpMonth == '3') echo 'selected'; } ?>>3</option>
                <option value="4" <?php if(isset($ExpMonth)) {if($ExpMonth == '4') echo 'selected'; } ?>>4</option>
                <option value="5" <?php if(isset($ExpMonth)) {if($ExpMonth == '5') echo 'selected'; } ?>>5</option>
                <option value="6" <?php if(isset($ExpMonth)) {if($ExpMonth == '6') echo 'selected'; } ?>>6</option>
                <option value="7" <?php if(isset($ExpMonth)) {if($ExpMonth == '7') echo 'selected'; } ?>>7</option>
                <option value="8" <?php if(isset($ExpMonth)) {if($ExpMonth == '8') echo 'selected'; } ?>>8</option>
                <option value="9" <?php if(isset($ExpMonth)) {if($ExpMonth == '9') echo 'selected'; } ?>>9</option>
                <option value="10" <?php if(isset($ExpMonth)) {if($ExpMonth == '10') echo 'selected'; } ?>>10</option>
                <option value="11" <?php if(isset($ExpMonth)) {if($ExpMonth == '11') echo 'selected'; } ?>>11</option>
                <option value="12" <?php if(isset($ExpMonth)) {if($ExpMonth == '12') echo 'selected'; } ?>>12</option>
              </select>
&nbsp;&nbsp; Year
              <select name="ExpYear">
                <?php
		$thisyear = date('Y');
		for($year = $thisyear; $year<$thisyear+20; $year++) {
			echo '<option ';
			if(isset($ExpYear)) {
				if($ExpYear == $year) echo ' selected';
			}
			echo '>' . $year;
		}
?>
              </select>
        </td>
      </tr>
     
		<tr>
        <td class="AGENCYregtableleft">Payment Option:</td>
        <td class="AGENCYregtableright">   
        <?php $nochecked = true; ?>    
		<input type="radio" name="pay_term" value="9.95:1:M" onClick="payterm='9.95:1:M'" <?php if(isset($pay_term)) { if($pay_term == '9.95:1:M') { echo "checked"; $nochecked = false; }} ?> /> $9.95 per month<br />
		<input type="radio" name="pay_term" value="24.95:3:M" onClick="payterm='24.95:3:M'" <?php if(isset($pay_term)) { if($pay_term == '24.95:3:M') { echo "checked"; $nochecked = false; }} ?> /> $8.30 per month ($24.95 once every <i>three</i> months)<br />
		<input type="radio" name="pay_term" value="89.95:1:Y" onClick="payterm='89.95:1:Y'" <?php if(isset($pay_term)) { if($pay_term == '89.95:1:Y') { echo "checked"; $nochecked = false; }} ?> /> $7.50 per month ($89.95 once every <i>year</i>)<br />
        
        <?php
		if($nochecked) {
		?>
        <br /><input type="radio" name="pay_term" value="" onClick="payterm=''" checked="checked" /> KEEP CURRENT PLAN<br />
        <?php
		}
		?>
        
		</td>
      </tr>     
     
     
    </table>
    <br /><br />
	<input type="hidden" value="<?php $_SESSION['form_token'] = rand('100000', '999999'); echo $_SESSION['form_token']; ?>" name="form_token"/>
	
    <input type="submit" name="submit" value="Update Payment Information" onClick="if(!checkform()) { alert('please fill in all fields'); return false; }" />


<div id="payconfirm" style="font-family:&quot;Times New Roman&quot, Times, serif; display:none; font-weight:bold; position:relative; padding:20px; top:-30px; left:0px; width:300px; background-color:#FFFFFF; border:1px solid gray">

</div>


  </form>
  <br />  <br />
</div></div>

    <div id="AGENCYProfileLeftListBottomLeft"></div>
    <div style="width: 432px;" id="AGENCYProfileLeftListBottomCenter"></div>
    <div id="AGENCYProfileLeftListBottomRight"></div>
  </div>
</div>

<?php
			}
	} else if ($_GET['mode'] == 'cancel_membership') {
		$paypal_profile_id =  get_rec_payment_id($userid);
		
		if(!empty($paypal_profile_id)) {
			$payment_action = 'cancel';
			include('adminXYZ/payment/ManageRecurringPaymentsProfile.php'); // this processes the payment
		}
		
		if($paypal_cancel) {
			echo 'Your subscription has successfully been canceled and your profile is no longer active';
			
			$message = '<html>
			  <body>
			  ' .
			  mysql_result(mysql_query("SELECT varvalue FROM agency_vars WHERE varname='email_membership_canceled'"), 0, 'varvalue') .
			  '
			  </body>
			  </html>';
		
			$to = mysql_result(mysql_query("SELECT user_email FROM forum_users WHERE user_id='$userid'"), 0, 'user_email');
			$from = "info@theagencyonline.com";
			$subject = "Membership Canceled for The Agency Online";
		
			$headers  = "From: $from\r\n";
			$headers .= "Content-type: text/html\r\n";
		
			//options to send to cc+bcc
			//$headers .= "Cc: [email]email@email.com[/email]";
			//$headers .= "Bcc: [email]email@email.com[/email]";
			
			// now lets send the email.
			mail($to, $subject, $message, $headers);
			// mail("ungabo@yahoo.com", $subject, $message, $headers);
			
			$subject = "Membership Canceled";
			$message = 'Userid: ' . $userid . ' has canceled their membership';
			// mail("ungabo@yahoo.com", $subject, $message, $headers);
			mail($from, $subject, $message, $headers);			
			
			
			
			
		} else {
			echo 'There was a problem cancelling your membership.  Please contact us for assistance.';
		}
		
		
	} else if ($_GET['mode'] == 'password') { // update password
	
	
	} else {
		echo 'This page was not accessed correctly.';
	}
		
} else if($_SERVER['HTTPS'] == 'on' && isset($_GET['action'])) {
	// if userid has not been set in session var, redirect to register page
	echo 'We apologize.  Some people are having trouble with this page in Safari.  If you have another browser, please use that one for payment.  If you are unable to use this page with another browser please contact us and we will take care of it for you.  We are working to resolve this issue.';
} else if($_SERVER['HTTPS'] == 'on') {
	$url = 'http://' . URL_SITENAME . '/account_update.php?action=check&mode=' . $_GET['mode'];
	ob_end_clean(); // Delete the buffer.
	header("Location: $url");
 	exit(); // Quit the script. */
} else {
	$url = 'https://' . URL_SITENAME . '/account_update.php?action=check&token=' . session_id() . '&mode=' . $_GET['mode'];
	ob_end_clean(); // Delete the buffer.
	header("Location: $url");
	exit(); // Quit the script. */
}

@include('includes/footer.php');
?>
<script type="text/javascript">
var payterm = '9.95:1:M';
var promocode = '';

var USregions = '<select name="BillState" id="state">';
  <?php
foreach($stateList['US'] as $abr=>$st) { ?>
	USregions += '<option value="<?php echo $st; ?>"';
	<?php
	if(isset($BillState)) {
		if($BillState == $st) { ?>
			USregions += ' selected';
			<?php
		}
	}
	?>
	USregions +=  '><?php echo $st; ?></option>';
<?php } ?>
USregions += '</select>';

function changecountry(country) {
	var obj = document.getElementById('statediv');
	if(country == 'United States') {
		obj.innerHTML = USregions;
	} else {
		obj.innerHTML = '<input type="text" id="BillState" name="BillState" maxlength="50" value="<?php if (!empty($state)) echo $state; ?>" />';
	}
}
<?php
if(isset($BillCountry)) {
	echo 'changecountry(\'' . $BillCountry . '\');';
}
?>

function checkform() {
	if(!document.getElementById('BillFname').value) {
		alert('Please enter your First Name');
		return false;
	} else if(!document.getElementById('BillLname').value) {
		alert('Please enter your Last Name');
		return false;
	} else if(!document.getElementById('BillStreet').value) {
		alert('Please enter your Street Address');
		return false;
	} else if(!document.getElementById('BillCity').value) {
		alert('Please enter your City');
		return false;
	} else if(!document.getElementById('BillCountry').value) {
		alert('Please enter your Country');
		return false;
	} else if(!document.getElementById('BillZip').value) {
		alert('Please enter your Zip Code');
		return false;
	} else if(!document.getElementById('CardType').value) {
		alert('Please enter your Credit Card Type');
		return false;
	} else if(!document.getElementById('CardNumber').value) {
		alert('Please enter your Credit Card Number');
		return false;
	} else if(!document.getElementById('CVV').value) {
		alert('Please enter your Credit Card CVV code');
		return false;
	} else {
		if(document.getElementById('BillCountry').value == 'United States') {
			if(document.getElementById('state').value == '--- Select State ---') {
				alert('Please enter your State');
				return false;
			}
		} else if(typeof(document.getElementById('BillState').value != 'undefined')) {
			if(!document.getElementById('BillState').value) {
				alert('Please enter your State');
				return false;
			}
		}		
		
		
		return true;
	}
}
</script>
