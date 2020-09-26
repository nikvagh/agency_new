<?php
// Start output buffering.
ob_start();
// Initialize session.
session_start();

include('../includes/vars.php');
include('../includes/mysql_connect.php');
include('../includes/agency_functions.php');
include('../forms/definitions.php');


 function getExcelData($data){
	$retval = "";
	if (is_array($data)  && !empty($data))
	{
	 $row = 0;
	 foreach(array_values($data) as $_data){
	  if (is_array($_data) && !empty($_data))
	  {
		  if ($row == 0)
		  {
			  // write the column headers
			  // $retval = implode("\t",array_keys($_data));
			  // $retval .= "\n";
		  }
		   //create a line of values for this row...
			  $retval .= implode("\t",array_values($_data));
			  $retval .= "\n";
			  //increment the row so we don't create headers all over again
			  $row++;
	   }
	 }
	}
  return $retval;
 }
	 
	 
	 
//your code here to create your sql statement...we'll call it $finalSQL
 
if(isset($_GET['filter']) && is_super_admin()) {
	switch($_GET['filter']) {
		case 'unapprovedtalent':
			$query = "SELECT forum_users.user_id, forum_users.user_email, forum_users.username, agency_profiles.firstname, agency_profiles.lastname, agency_profiles.phone FROM forum_users, agency_profiles WHERE agency_profiles.user_id=forum_users.user_id AND forum_users.user_type='1' AND agency_profiles.account_type='talent' ORDER BY forum_users.user_id DESC";
			break;
		case 'unapprovedclients':
			$query = "SELECT forum_users.user_id, forum_users.user_email, forum_users.username, agency_profiles.firstname, agency_profiles.lastname, agency_profiles.phone FROM forum_users, agency_profiles WHERE agency_profiles.user_id=forum_users.user_id AND forum_users.user_type='1' AND agency_profiles.account_type='client' ORDER BY forum_users.user_id DESC";
			break;
		case 'approvedclients':
			$query = "SELECT forum_users.user_id, forum_users.user_email, forum_users.username, agency_profiles.firstname, agency_profiles.lastname, agency_profiles.phone FROM forum_users, agency_profiles WHERE agency_profiles.user_id=forum_users.user_id AND forum_users.user_type='0' AND agency_profiles.account_type='client' ORDER BY forum_users.user_id DESC";
			break;
		case 'approvedunpaidtalent':
			$query = "SELECT forum_users.user_id, forum_users.user_email, forum_users.username, agency_profiles.firstname, agency_profiles.lastname, agency_profiles.phone FROM forum_users, agency_profiles WHERE agency_profiles.user_id=forum_users.user_id AND agency_profiles.payProcessed='0' AND forum_users.user_type='0' AND agency_profiles.account_type='talent' ORDER BY forum_users.user_id DESC";
			break;
		case 'cclist':
			$query = "SELECT forum_users.user_email FROM forum_users, agency_profiles, agency_cc WHERE agency_profiles.payProcessed='0' AND agency_profiles.user_id=forum_users.user_id AND agency_cc.user_id=forum_users.user_id ORDER BY forum_users.user_id DESC";
			break;
		case 'unapprovedtalentwithcc':
			$query = "SELECT forum_users.user_email FROM forum_users, agency_profiles, agency_cc WHERE agency_profiles.payProcessed='0' AND agency_profiles.user_id=forum_users.user_id AND agency_cc.user_id=forum_users.user_id AND forum_users.user_type='1' AND agency_profiles.account_type='talent' ORDER BY forum_users.user_id DESC";
			break;
		case 'unapprovedtalentwithoutcc':
			$query = "SELECT forum_users.user_email FROM forum_users, agency_profiles WHERE agency_profiles.user_id=forum_users.user_id AND forum_users.user_type='1' AND forum_users.user_id NOT IN (SELECT user_id FROM agency_cc) AND agency_profiles.account_type='talent' ORDER BY forum_users.user_id DESC";
			break;
		case 'unapprovedtalentwithccandpics':
			$query = "SELECT DISTINCT forum_users.user_email FROM forum_users, agency_profiles, agency_cc WHERE agency_profiles.payProcessed='0' AND  agency_profiles.user_id=forum_users.user_id AND agency_cc.user_id=forum_users.user_id AND forum_users.user_type='1' AND agency_profiles.account_type='talent' AND (forum_users.user_id IN (SELECT user_id FROM agency_photos) OR agency_profiles.headshot IS NOT NULL) ORDER BY forum_users.user_id DESC";
			break;
		case 'unapprovedtalentwithpaymentprocessed':
			$query = "SELECT forum_users.user_email FROM forum_users, agency_profiles WHERE agency_profiles.payProcessed='1' AND agency_profiles.user_id=forum_users.user_id AND forum_users.user_type='1' AND agency_profiles.account_type='talent' ORDER BY forum_users.user_id DESC";
			break;
		case 'approvedpaidtalent':
			$query = "SELECT forum_users.user_id, forum_users.user_email, forum_users.username, agency_profiles.firstname, agency_profiles.lastname, agency_profiles.phone FROM forum_users, agency_profiles WHERE agency_profiles.user_id=forum_users.user_id AND agency_profiles.payProcessed='1' AND forum_users.user_type='0' AND agency_profiles.account_type='talent' ORDER BY forum_users.user_id ASC";
			break;
		case 'referred':
			$query = "SELECT forum_users.user_email FROM forum_users, agency_profiles WHERE agency_profiles.user_id=forum_users.user_id AND agency_profiles.account_type='talent' AND agency_profiles.mentor_id IS NOT NULL ORDER BY forum_users.user_id ASC";
			break;
		case 'discounts':
			$query = "SELECT forum_users.user_email FROM forum_users, agency_profiles WHERE agency_profiles.user_id=forum_users.user_id AND agency_profiles.account_type='talent' AND agency_profiles.discount_code IS NOT NULL ORDER BY forum_users.user_id ASC";
			break;
		case 'failedpayments':
			$query = "SELECT DISTINCT forum_users.user_email FROM forum_users, agency_profiles WHERE agency_profiles.payFailed='1' AND agency_profiles.user_id=forum_users.user_id AND agency_profiles.account_type='talent' AND forum_users.user_type='0' ORDER BY forum_users.user_id DESC";
			break;
		case 'failedpaymentsunapproved':
			$query = "SELECT DISTINCT forum_users.user_email FROM forum_users, agency_profiles WHERE agency_profiles.payFailed='1' AND agency_profiles.user_id=forum_users.user_id AND agency_profiles.account_type='talent' AND forum_users.user_type='1' ORDER BY forum_users.user_id DESC";
			break;
	}
	
	
	//go get the data we need...
	$result = mysql_query($query);
	//fetching each row as an array and placing it into a holder array ($aData)
	while($row = mysql_fetch_assoc($result)){
	 $aData[] = $row;
	}
	//feed the final array to our formatting function...
	$contents = getExcelData($aData);
	
	$filename = $_GET['filter'] . ".xls";
	
	//prepare to give the user a Save/Open dialog...
	header ("Content-type: application/octet-stream");
	header ("Content-Disposition: attachment; filename=".$filename);
	
	//setting the cache expiration to 30 seconds ahead of current time. an IE 8 issue when opening the data directly in the browser without first saving it to a file
	$expiredate = time() + 30;
	$expireheader = "Expires: ".gmdate("D, d M Y G:i:s",$expiredate)." GMT";
	header ($expireheader);
	
	//output the contents
	echo $contents;
	exit;

} else {
	echo 'Page Not Accessed Correctly.';
}
?>
