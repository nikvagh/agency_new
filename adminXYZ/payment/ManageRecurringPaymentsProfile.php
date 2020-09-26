<?php
if(!empty($paypal_profile_id) && !empty($payment_action)) {
		
	include('payment_functions.php');
	
	$paypal_profile_id = urlencode($paypal_profile_id);
	
	if($payment_action == 'status') {   // =============  CANCEL PAYMENT
		// Add request-specific fields to the request string.
		
		$nvpStr="&PROFILEID=$paypal_profile_id";
		
		$httpParsedResponseAr = PPHttpPost('GetRecurringPaymentsProfileDetails', $nvpStr);
		
		// var_dump($httpParsedResponseAr);
		if("Success" == $httpParsedResponseAr["ACK"]) {
			if($httpParsedResponseAr["STATUS"] == 'Active') {
				$paypal_active = true;
				
				// collect some info for Update
				$billingPeriod = $httpParsedResponseAr["REGULARBILLINGPERIOD"];
				$billingFreq = $httpParsedResponseAr["REGULARBILLINGFREQUENCY"];
				$amount = $httpParsedResponseAr["AMT"];			
				$nextBillingDate = urldecode($httpParsedResponseAr["NEXTBILLINGDATE"]);
			}
			
			
			/*
			if(!empty($_SESSION['admin'])) {
				echo '<br /><b>(only admin sees this) STATUS FOUND.</b><br /><br />PayPal output:<br />';
				echo urldecode(print_r($httpParsedResponseAr, true));
				echo '<br /><br /><hr><br /><br />' . $nvpStr;
			}
			*/
			
		} else  {
			/*
			if(!empty($_SESSION['admin'])) {
				echo '<br /><b>(only admin sees this) STATUS WAS <i>NOT</i> FOUND.</b><br /><br />PayPal output:<br />';
				echo urldecode(print_r($httpParsedResponseAr, true));
				echo '<br /><br />Use to find source of failure:<br />' . $nvpStr . '<br /><br /><hr><br /><br />'; 
			}
			*/
		}
	
	
	
	
	
	
	
	
	} else if($payment_action == 'cancel') {   // =============  CANCEL PAYMENT
		$note = urlencode($userid . ': Cancellation');
		
		
		// Add request-specific fields to the request string.
		$nvpStr="&PROFILEID=$paypal_profile_id&ACTION=Cancel&NOTE=$note";
		 
		$httpParsedResponseAr = PPHttpPost('ManageRecurringPaymentsProfileStatus', $nvpStr);
		
		// if("Success" == $httpParsedResponseAr["ACK"]) {
			// MARK USER AS PAID
			if($payment_action == 'update') { // if this is an update then payProcessed is 1
				mysql_query("UPDATE agency_profiles SET payProcessed='1', payFailed='0' WHERE user_id='$userid' LIMIT 1");
			} else {
				mysql_query("UPDATE agency_profiles SET payProcessed='0', payFailed='0' WHERE user_id='$userid' LIMIT 1");
			}
			mysql_query("DELETE FROM agency_cc WHERE user_id='$userid'");
			
			// MAKE NOT ACTIVE
			$query = "UPDATE forum_users SET user_type='1' WHERE user_id='$userid' LIMIT 1";
			mysql_query($query);
			
			$paypal_cancel = true;
			/* echo '<br /><b>TRANSACTION WAS SUCCESSFULLY PROCESSED.</b><br /><br />PayPal output:<br />';
			echo 'CreateRecurringPaymentsProfile Completed Successfully: ' . urldecode(print_r($httpParsedResponseAr, true));
			echo '<br /><br /><hr><br /><br />' . $nvpStr; */
		// } else  {
			/* echo '<br /><b>TRANSACTION WAS <i>NOT</i> SUCCESSFULLY PROCESSED.</b><br /><br />PayPal output:<br />';
			echo 'CreateRecurringPaymentsProfile failed: ' . urldecode(print_r($httpParsedResponseAr, true));
			echo '<br /><br />Use to find source of failure:<br />' . $nvpStr . '<br /><br /><hr><br /><br />';
			*/
		// }
	
	
	
	
	
	
	
	
	} else if($payment_action == 'update') {  // =============  UPDATE PAYMENT INFO

		$firstName = urlencode($customer_first_name);
		$lastName = urlencode($customer_last_name);
		$creditCardType = urlencode($customer_credit_card_type);
		$creditCardNumber = urlencode($customer_credit_card_number);
		$expDateMonth = $cc_expiration_month;
		// Month must be padded with leading zero
		$padDateMonth = urlencode(str_pad($expDateMonth, 2, '0', STR_PAD_LEFT));
		
		$expDateYear = urlencode($cc_expiration_year);
		$cvv2Number = urlencode($cc_cvv2_number);
		$address1 = urlencode($customer_address1);
		$address2 = urlencode($customer_address2);
		$city = urlencode($customer_city);
		$state = urlencode($customer_state);
		$zip = urlencode($customer_zip);
		$country = urlencode($customer_country);				// US or other valid country code
		$currencyID = urlencode('USD');							// or other currency ('GBP', 'EUR', 'JPY', 'CAD', 'AUD')
		
		
		$desc = urlencode($userid . ': Payment Updated');	
	
	
	
		$nvpStr =	"&PROFILEID=$paypal_profile_id&CREDITCARDTYPE=$creditCardType&ACCT=$creditCardNumber".
			"&EXPDATE=$padDateMonth$expDateYear&CVV2=$cvv2Number&FIRSTNAME=$firstName&LASTNAME=$lastName".
			"&STREET=$address1&CITY=$city&STATE=$state&ZIP=$zip&COUNTRYCODE=$country&CURRENCYCODE=$currencyID".
			"&BILLINGPERIOD=$billingPeriod&BILLINGFREQUENCY=$billingFreq&AMT=$amount&DESC=$desc";
	
	
		$httpParsedResponseAr = PPHttpPost('UpdateRecurringPaymentsProfile', $nvpStr);
		
		if("Success" == $httpParsedResponseAr["ACK"]) {
			$paypal_update = true;
			/* echo '<br /><b>TRANSACTION WAS SUCCESSFULLY PROCESSED.</b><br /><br />PayPal output:<br />';
			echo 'CreateRecurringPaymentsProfile Completed Successfully: ' . urldecode(print_r($httpParsedResponseAr, true));
			echo '<br /><br /><hr><br /><br />' . $nvpStr; */
		} else  {
			$paypal_update = false;
			/* echo '<br /><b>TRANSACTION WAS <i>NOT</i> SUCCESSFULLY PROCESSED.</b><br /><br />PayPal output:<br />';
			echo 'CreateRecurringPaymentsProfile failed: ' . urldecode(print_r($httpParsedResponseAr, true));
			echo '<br /><br />Use to find source of failure:<br />' . $nvpStr . '<br /><br /><hr><br /><br />';  */
		}	
	
	
	}
}
?>