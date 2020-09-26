<?php
/* THIS FILE IS NOT CURRENTLY BEING USED.  IT IS A START FOR IF WE WANT TO HAVE A PAYMENT FORM INSIDE THE SIGNUP PROCESS, BUT BECAUSE OF SSL ISSUES IT MAY BE SIMPLER NOT TO DO THIS */

	$user_id = (int) $loggedin;
	// echo 'userid:' . $user_id;
	if (isset($_POST['submit'])) { // Handle the form.
		if(true) {
		// if ($_SESSION['form_token'] == $_POST['form_token']) {
			// unset($_SESSION['form_token']);
			// collect information from form
			// $BillFname = request_var('BillFname', '', true);
			// $BillLname = request_var('lastname', '', true);

			/* foreach($_POST as $key=>$value) {
				echo '<br />' . $key . '=' . $value . '<br />';
			} */

			// if($stage == 'creditdone') {
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
			if(!is_numeric($CardNumber)) {
				unset($CardNumber);
			}
			$CVV = trim($_POST['CVV']);
			$ExpMonth = trim($_POST['ExpMonth']);
			$ExpYear = trim($_POST['ExpYear']);
			$pay_term = trim($_POST['pay_term']);
			$promotion = trim($_POST['promotion']);
			
			
			if(empty($promotion)) {
				$promo_ok = true;
			} else {
				$promo_ok = false;
			}
			// check if promocode is in mentor code list
			$query = "SELECT * FROM agency_mentors WHERE mentor_code='$promotion'";
			$result = mysql_query ($query);
			if (mysql_num_rows($result) > 0) { // If code exists.	
				$promo_ok = true;
			}
			// check if promocode is in discount code list
			$query = "SELECT * FROM agency_discounts WHERE discount_code='$promotion'";
			$result = mysql_query ($query);
			if ($row = mysql_fetch_array ($result, MYSQL_ASSOC)) { // If code exists.
				$promo_ok = true;
				// check if code number of uses have been used up:
				if(is_numeric($row['discount_usage'])) {
					if($row['discount_usage'] > 0) {
						// is fine, remove one usage
						$usage = $row['discount_usage'] - 1;
						mysql_query("UPDATE agency_discounts SET discount_usage='$usage' WHERE discount_code='$promotion'");
					} else {
						// uses remaining are zero, cannot be used
						$promo_ok = false;
					}
				}
			}	


			if(!empty($BillFname) && !empty($BillLname) && !empty($BillStreet) && !empty($BillCity) && !empty($BillState) && !empty($BillZip) && !empty($CardType) && !empty($CardNumber) && !empty($CVV) && !empty($ExpMonth) && !empty($ExpYear) && !empty($pay_term) && $promo_ok) {

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
				$pay_term = escape_data($pay_term);
				$promocode = escape_data($promotion);

				// create credit card record

				$sql = "INSERT INTO agency_cc (user_id, firstname, lastname, street1, street2, city, state, zip, country, type, number, cvv, exp_month, exp_year, pay_term, promocode) VALUES ('$user_id', '$customer_first_name', '$customer_last_name', '$customer_address1', '$customer_address2', '$customer_city', '$customer_state', '$customer_zip', '$customer_country', '$customer_credit_card_type', '$customer_credit_card_number', '$cc_cvv2_number', '$cc_expiration_month', '$cc_expiration_year', '$pay_term', '$promocode')";
				mysql_query($sql);

				/* $url = 'http://' . URL_SITENAME . '/profile.php?u=' . $user_id . '&payment=true';
				ob_end_clean(); // Delete the buffer.
				header("Location: $url");
				exit(); // Quit the script. */
				$stage = 'creditdone';

			} else {
				$stage = 'creditincomplete';
			}
		}
	} else { // form not submitted, so get information
		// get firstname, lastname, make sure it's the right type
		$sql = "SELECT firstname, lastname, account_type FROM agency_profiles WHERE user_id='$user_id'";
		$result = mysql_query($sql);
		$row = sql_fetchrow($result);
		unset($result);

		if (!$row)
		{
			// break; // this will change, if there is no row, one will have to be created with at least a type
		}
		$type = $row['user_type'];
		$BillFname = $row['firstname'];
		$BillLname = $row['lastname'];

		if($type == 'client') { // they don't have to pay, redirect to login
			echo 'Hi!  Not sure how you got here, but as a client you do not have to pay. :)';
		}

	} // End of the main Submit conditional.


?>

Please enter your billing information:
  <br />
<?php
		if($stage == 'creditincomplete') { // problem with billing information
			echo '<br /><div align="left"><font color=red><b>There was a problem processing your credit information.<br />';
			if(!$promo_ok) {
				echo '<br /><br />The Promotion Code you entered is incorrect or no longer valid.  Please delete or enter a new code.<br /><br />';
			}
			echo 'Please review your entries below.</b><br /><br /></font></div>';
		}
?>
              <br />
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
        <td class="AGENCYregtableleft">Promotion Code (optional):</td>
        <td class="AGENCYregtableright"><input type="text" name="promotion" onBlur="promocode=this.value" value="<?php if(isset($promotion)) echo $promotion; // SSL is advised ?>" />
        </td>
      </tr>
      <tr>
        <td class="AGENCYregtableleft">Payment Option:</td>
        <td class="AGENCYregtableright">
		<input type="radio" name="pay_term" value="9.95:1:M" onClick="payterm='9.95:1:M'" <?php if(isset($pay_term)) { if($pay_term == '9.95:1:M') echo "checked"; } else { echo "checked"; } ?> /> $9.95 per month<br />
		<input type="radio" name="pay_term" value="24.95:3:M" onClick="payterm='24.95:3:M'" <?php if(isset($pay_term)) { if($pay_term == '24.95:3:M') echo "checked"; } ?> /> $8.30 per month ($24.95 once every <i>three</i> months)<br />
		<input type="radio" name="pay_term" value="89.95:1:Y" onClick="payterm='89.95:1:Y'" <?php if(isset($pay_term)) { if($pay_term == '89.95:1:Y') echo "checked"; } ?> /> $7.50 per month ($89.95 once every <i>year</i>)<br />
		</td>
      </tr>
    </table>
	<br />
	<input type="checkbox" name="termscheck"> I have read the <a href="index2.php?pageid=68" target="_blank">terms and conditions</a>
    <br /><br />
	<input type="hidden" value="<?php $_SESSION['form_token'] = rand('100000', '999999'); echo $_SESSION['form_token']; ?>" name="form_token"/>
	
    <input type="button" value="Submit" onClick="if(checkform()) { if(!termscheck.checked) { alert('You must read the Terms and Conditions before submitting this form'); return false; } else { loaddiv('payconfirm', false, 'ajax/payconfirm.php?payterm='+payterm+'&promocode='+promocode+'&name='+document.payment.BillFname.value+' '+document.payment.BillLname.value+'&'); document.getElementById('payconfirm').style.display='block'; } }" /> *


<div id="payconfirm" style="font-family:&quot;Times New Roman&quot, Times, serif; display:none; font-weight:bold; position:relative; padding:20px; top:-30px; left:0px; width:300px; background-color:#FFFFFF; border:1px solid gray">

</div>


  </form>
  <br />

  * Payment will not be processed until your account has been approved.
  <br />  <br />
</div></div>

    <div id="AGENCYProfileLeftListBottomLeft"></div>
    <div style="width: 432px;" id="AGENCYProfileLeftListBottomCenter"></div>
    <div id="AGENCYProfileLeftListBottomRight"></div>
  </div>
</div>
<div style="float:right; width:330px">
<?php
echo mysql_result(mysql_query("SELECT varvalue FROM agency_vars WHERE varname='payTalent'"), 0, 'varvalue');
?>
</div>