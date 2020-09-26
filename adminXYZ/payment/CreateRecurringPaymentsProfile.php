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

if(!empty($agencydiscount)) {
	if(is_numeric($agencydiscount)) {
		$amount = urlencode(number_format(($amount - ($amount * $agencydiscount)), 2));
	}
} else {
	urlencode($amount);
}


// $token = urlencode("token_from_setExpressCheckout");

// $paymentAmount = urlencode("payment_amount");
// $currencyID = urlencode("USD");						// or other currency code ('GBP', 'EUR', 'JPY', 'CAD', 'AUD')
// $startDate = urlencode("2010-9-6T0:0:0");
// $startDate = urlencode("2009-11-13T18:00:00Z");
$startDate = date('Y-m-d\TH:i:s.00\Z');
// $startDate = date('Y-m-21\TH:i:s');
// $startDate = '2009-11-21T04:00:00';

echo '<br><br>' . $startDate . '<br><br>';

// Set request-specific fields.
// $paymentType = urlencode('Sale');				// 'Authorization' or 'Sale'
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

if(isset($discount_type)) {
	if($discount_type == 'freemonthwithcc') {
		// APPLY FIRST MONTH FREE DISCOUNT
		$desc = urlencode($userid . ': Registration for The Agency Online [First month free. code: ' . $promocode . ']');
		$nvpStr =	"&MAXFAILEDPAYMENTS=3&AMT=$amount&CREDITCARDTYPE=$creditCardType&ACCT=$creditCardNumber".
			"&EXPDATE=$padDateMonth$expDateYear&CVV2=$cvv2Number&FIRSTNAME=$firstName&LASTNAME=$lastName".
			"&STREET=$address1&CITY=$city&STATE=$state&ZIP=$zip&COUNTRYCODE=$country&CURRENCYCODE=$currencyID".
			"&PROFILESTARTDATE=$startDate&BILLINGPERIOD=$billingPeriod&BILLINGFREQUENCY=$billingFreq&DESC=$desc" . 
			"&TRIALBILLINGPERIOD=Day&TRIALBILLINGFREQUENCY=30&TRIALTOTALBILLINGCYCLES=1&TRIALAMT=0";
	}
}
if(isset($discount_type)) {
	if($discount_type == '14daysfree') {
		// APPLY 14 DAYS FREE DISCOUNT
		$desc = urlencode($userid . ': Registration for The Agency Online [14 days free. code: ' . $promocode . ']');
		$nvpStr =	"&MAXFAILEDPAYMENTS=3&AMT=$amount&CREDITCARDTYPE=$creditCardType&ACCT=$creditCardNumber".
			"&EXPDATE=$padDateMonth$expDateYear&CVV2=$cvv2Number&FIRSTNAME=$firstName&LASTNAME=$lastName".
			"&STREET=$address1&CITY=$city&STATE=$state&ZIP=$zip&COUNTRYCODE=$country&CURRENCYCODE=$currencyID".
			"&PROFILESTARTDATE=$startDate&BILLINGPERIOD=$billingPeriod&BILLINGFREQUENCY=$billingFreq&DESC=$desc" . 
			"&TRIALBILLINGPERIOD=Day&TRIALBILLINGFREQUENCY=14&TRIALTOTALBILLINGCYCLES=1&TRIALAMT=0";
	}
}

if(isset($discount_type)) {
	if($discount_type == '6weeksLA') {
		// APPLY 45 DAYS FREE DISCOUNT
		$desc = urlencode($userid . ': Registration for The Agency Online [14 days free. code: ' . $promocode . ']');
		$nvpStr =	"&MAXFAILEDPAYMENTS=3&AMT=$amount&CREDITCARDTYPE=$creditCardType&ACCT=$creditCardNumber".
			"&EXPDATE=$padDateMonth$expDateYear&CVV2=$cvv2Number&FIRSTNAME=$firstName&LASTNAME=$lastName".
			"&STREET=$address1&CITY=$city&STATE=$state&ZIP=$zip&COUNTRYCODE=$country&CURRENCYCODE=$currencyID".
			"&PROFILESTARTDATE=$startDate&BILLINGPERIOD=$billingPeriod&BILLINGFREQUENCY=$billingFreq&DESC=$desc" . 
			"&TRIALBILLINGPERIOD=Day&TRIALBILLINGFREQUENCY=45&TRIALTOTALBILLINGCYCLES=1&TRIALAMT=0";
	}
}

// $nvpStr="&TOKEN=$token&AMT=$paymentAmount&CURRENCYCODE=$currencyID&PROFILESTARTDATE=$startDate";
// $nvpStr .= "&BILLINGPERIOD=$billingPeriod&BILLINGFREQUENCY=$billingFreq";

$httpParsedResponseAr = PPHttpPost('CreateRecurringPaymentsProfile', $nvpStr);

if("Success" == $httpParsedResponseAr["ACK"]) {
	// MARK USER AS PAID
	mysql_query("UPDATE agency_profiles SET payProcessed='1', payFailed='0', pay_term='$pay_term' WHERE user_id='$userid' LIMIT 1");
	mysql_query("DELETE FROM agency_cc WHERE user_id='$userid'");
	
	// if this is the first time member has paid, mark the date for tagging them as a new member
	$query = "SELECT payProcessedDate FROM agency_profiles WHERE user_id='$userid' AND payProcessedDate='2000-01-01 00:00:01'"; // 2000-01-01 00:00:01 is the default value, so if it's still set as this then it has not been set.
	$result = mysql_query($query);
	if(mysql_num_rows($result) == 1) {
		mysql_query("UPDATE agency_profiles SET payProcessedDate=NOW() WHERE user_id='$userid' LIMIT 1");
	}
	
	echo '<br /><b>TRANSACTION WAS SUCCESSFULLY PROCESSED.</b>';
	if(!empty($superadmin) && false) {
		echo '<br /><br />PayPal output:<br />';
		echo 'CreateRecurringPaymentsProfile Completed Successfully: ' . urldecode(print_r($httpParsedResponseAr, true));
		echo '<br /><br /><hr><br /><br />' . $nvpStr;
	}
} else  {
	echo '<br /><b>TRANSACTION WAS <i>NOT</i> SUCCESSFULLY PROCESSED.</b>';
	if(!empty($superadmin)) {
		echo '<br /><br />PayPal output:<br />';
		echo 'CreateRecurringPaymentsProfile failed: ' . urldecode(print_r($httpParsedResponseAr, true));
		echo '<br /><br />Use to find source of failure:<br />' . $nvpStr . '<br /><br /><hr><br /><br />';
	}
}

?>