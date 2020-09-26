<?php
include('payment_functions.php');

// IF PRICING CHANGES, THIS IS WHERE TO MAKE ALTERATIONS
switch($periodcode) {
	case 1:	// $9.95 per Month
		$billingPeriod = urlencode("Month");				// or "Day", "Week", "SemiMonth", "Year"
		$billingFreq = urlencode("1");						// combination of this and billingPeriod must be at most a year
		$amount = 9.95;
		break;
	case 2: // $24.95 for 3 months
		$billingPeriod = urlencode("Month");				// or "Day", "Week", "SemiMonth", "Year"
		$billingFreq = urlencode("3");						// combination of this and billingPeriod must be at most a year
		$amount = 24.95;
		break;
	case 3: // $89.95 per Year
		$billingPeriod = urlencode("Year");				// or "Day", "Week", "SemiMonth", "Year"
		$billingFreq = urlencode("1");						// combination of this and billingPeriod must be at most a year
		$amount = 89.95;
		break;
}



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


$desc = urlencode($userid . ': Registration for The Agency Online');


// Add request-specific fields to the request string.
// DEFAULT
$nvpStr =	"&MAXFAILEDPAYMENTS=3&AMT=$amount&CREDITCARDTYPE=$creditCardType&ACCT=$creditCardNumber".
			"&EXPDATE=$padDateMonth$expDateYear&CVV2=$cvv2Number&FIRSTNAME=$firstName&LASTNAME=$lastName".
			"&STREET=$address1&CITY=$city&STATE=$state&ZIP=$zip&COUNTRYCODE=$country&CURRENCYCODE=$currencyID".
			"&PROFILESTARTDATE=$startDate&BILLINGPERIOD=$billingPeriod&BILLINGFREQUENCY=$billingFreq&DESC=$desc";


$httpParsedResponseAr = PPHttpPost('CreateRecurringPaymentsProfile', $nvpStr);

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

?>