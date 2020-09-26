<?php

// $environment = 'sandbox';	// or 'beta-sandbox' or 'live'
$environment = 'live';	// or 'beta-sandbox' or 'live'

// echo '<br /><br /><b>********* ENVIRONMENT: ' . $environment . ' *****************</b><br /><br />';

/**
 * Send HTTP POST Request
 *
 * @param	string	The API method name
 * @param	string	The POST Message fields in &name=value pair format
 * @return	array	Parsed HTTP Response body
 */
 
if(!function_exists('PPHttpPost')) {
	function PPHttpPost($methodName_, $nvpStr_) {
		global $environment;
	
		if($environment == 'live') { // live info
			$API_UserName = urlencode('oliver_api1.theagencyonline.com');
			$API_Password = urlencode('QCVMGV7KYZ9EH4EQ');
			$API_Signature = urlencode('AvM82poEsdRLSrruzqsK-6L1pKYdAh1qt6v6AByWRypW9x.Se6E.3gsz');
			$API_Endpoint = "https://api-3t.paypal.com/nvp";
			if("sandbox" === $environment || "beta-sandbox" === $environment) {
				$API_Endpoint = "https://api-3t.$environment.paypal.com/nvp";
			}
		} else { // sandbox info
			$API_UserName = urlencode('junkdm_1207538419_biz_api1.yahoo.com');
			$API_Password = urlencode('1207538425');
			$API_Signature = urlencode('AFcWxV21C7fd0v3bYYYRCpSSRl31Al3EIDNZ5kze2ojx1SQV-JgsJIsz');
			// $API_Endpoint = "https://api-3t.paypal.com/nvp";
			if("sandbox" === $environment || "beta-sandbox" === $environment) {
				$API_Endpoint = "https://api-3t.$environment.paypal.com/nvp";
			}
		}
		
	
	
	
		
		
		
		
		$version = urlencode('54.0');
	
		// setting the curl parameters.
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $API_Endpoint);
		curl_setopt($ch, CURLOPT_VERBOSE, 1);
	
		// turning off the server and peer verification(TrustManager Concept).
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		// curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
	
		// NVPRequest for submitting to server
		$nvpreq = "METHOD=$methodName_&VERSION=$version&PWD=$API_Password&USER=$API_UserName&SIGNATURE=$API_Signature$nvpStr_";
	
		// setting the nvpreq as POST FIELD to curl
		curl_setopt($ch, CURLOPT_POSTFIELDS, $nvpreq);
	
		// getting response from server
		$httpResponse = curl_exec($ch);		
		
		if(!$httpResponse) {
			exit("$methodName_ failed: ".curl_error($ch).'('.curl_errno($ch).')');
		}
	
		// Extract the RefundTransaction response details
		$httpResponseAr = explode("&", $httpResponse);
	
		$httpParsedResponseAr = array();
		foreach ($httpResponseAr as $i => $value) {
			$tmpAr = explode("=", $value);
			if(sizeof($tmpAr) > 1) {
				$httpParsedResponseAr[$tmpAr[0]] = $tmpAr[1];
			}
		}
	
		if((0 == sizeof($httpParsedResponseAr)) || !array_key_exists('ACK', $httpParsedResponseAr)) {
			exit("Invalid HTTP Response for POST request($nvpreq) to $API_Endpoint.");
		}
	
		return $httpParsedResponseAr;
	}
}
?>