<?php

error_reporting(E_ALL);
// ini_set('error_reporting', E_ALL);
ini_set('display_errors', '1');

include('header.php');

// GET A LIST OF RECENT PAYMENTS FOR ALL ACTIVE PAID ACCOUNTS

$red = 0;
$blue = 0;
$yellow = 0;
$green =0;
$okcount = 0;
$badcount = 0;
define('LIMIT', 5); // how many log entries to show in table


echo '<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
			  integrity="sha256-k2WSCIexGzOj3Euiig+TlR8gA0EmPjuc79OEeY5L45g="
			  crossorigin="anonymous"></script>';


echo '<a href="javascript:void()" onclick="$(\'#hidebox\').toggle()">show/hide details</a><br /><br />';
echo '<div id="hidebox" style="display:none">';
$query = "SELECT forum_users.user_id, forum_users.user_email, forum_users.username, agency_profiles.firstname, agency_profiles.lastname, agency_profiles.pay_term FROM forum_users, agency_profiles WHERE agency_profiles.user_id=forum_users.user_id AND agency_profiles.payProcessed='1' AND forum_users.user_type='0' AND agency_profiles.account_type='talent' ORDER BY forum_users.user_id ASC";
// echo '<p>' . $query . '</p>';

$build = '<table border="1" bgcolor="white"><tr>
			<th>User ID</th>
			<th>Email</th>
			<th>Username</th>
			<th>First Name</th>
			<th>Last Name</th>
			<th>Plan</th>
			<th>Name on CC</th>
			<th>Subscription ID</th>
			<th>Last Payment</th>
			<th>Payment Current</th>
			<th>' . LIMIT . ' most recent logs</th>
		</tr>';
$result = mysql_query ($query);
while ($row = mysql_fetch_array ($result, MYSQL_ASSOC)) {
	
	$ok = false;
	$build .= '<tr>';
	
	foreach($row as $k=>$v) {
		$style = '';
		$tag = '';
		$border = '';
		
		echo '<div style="' . $style . '">' . $tag;
		
		
		if($k == 'pay_term') {
			$arr = explode(':', $v);
			
			if(strpos($v, ':1:M') !== false) {
				$z = 'Payment Plan: $' . $arr['0'] . ' Monthly';
			} else if(strpos($v, ':3:M') !== false) {
				$z = 'Payment Plan: $' . $arr['0'] . ' every 3 Months';
			} else if(strpos($v, ':1:Y') !== false) {
				$z = 'Payment Plan: $' . $arr['0'] . ' Annually';
			} else {
				$z = 'Undefined';
			}
			echo $z;
			$build .= '<td>' . $z . '</td>';
		} else {
			echo $k . ': ' . $v;
			$build .= '<td>' . $v . '</td>';
		}
		echo '</div>';
	}
	
	$currentfound=false;
	$recent_arr = array();
	
	$uid = $row['user_id'];
	$query2 = "SELECT * FROM agency_payment_log WHERE user_id='$uid' ORDER BY logtime DESC";
	// echo '<p>' . $query . '</p>';
	$fullname = false;
	$lastpayment = false;
	$lp = false;
	$paypal_PROFILEID = false;
	$result2 = mysql_query ($query2);
	if(mysql_num_rows($result2) == 0) {
		echo '<td>&nbsp;.</td><td>&nbsp;</td>';
	}
	$limit = LIMIT;
	while ($row2 = mysql_fetch_array ($result2, MYSQL_ASSOC)) {
		if(!$lp && $row2['transaction_type'] == 'recurring_payment') {
			$lp = date('m/d/Y', strtotime($row2['logtime']));
		}
		
		
		if($limit-- > 0) {
			$recent_arr[] = date('m/d/y', strtotime($row2['logtime'])) . ': ' . $row2['transaction_type'];
			
			if(!$fullname) {
				$fullname = stripslashes($row2['firstname'] . ' ' . $row2['lastname']);
				$build .= '<td>' . $fullname . '</td>';
			}
			if(!$paypal_PROFILEID) {
				$build .= '<td>' . $row2['paypal_PROFILEID'] . '</td>';
				$paypal_PROFILEID = true;
			}
			
			
			
			// ROW CONTAINS FULL LOG DETAILS, THERE WILL BE 3 (limit in query)
			
			echo '<blockquote style="border:1px solid ' . $border . '; margin-top:10px; padding:10px">';
			
			// WE'VE STARTED THE INDENT FOR ALL LOG ENTRIES FOR THIS USER
			
			
			
			foreach($row2 as $k=>$v) {
				
				// GOING THROUGH EACH FIELD IN ROW (should be hit 3 times (limit in query)
				
				$style = '';
				$tag = '';
				
				if($k == 'logtime') {				
					if(strpos($row['pay_term'], ':1:M') !== false && strtotime($v) < strtotime("1 month ago")) {
						$style = "background-color:red";
						$tag = '1m1m:: ';
					} else if(strpos($row['pay_term'], ':3:M') !== false && strtotime($v) < strtotime("3 months ago")) {
						$style = "background-color:red";
						$tag = 'LOG-3m3m:: ';
					} else if(strpos($row['pay_term'], ':1:Y') !== false && strtotime($v) < strtotime("1 year ago")) {
						$style = "background-color:red";
						$tag = 'LOG-1y1y:: ';
					} else if(empty($row['pay_term'])) {
						$style = "background-color:red";
					} else {
						$style = "background-color:lightgreen";
						$tag = 'LOG-okok:: ';
						$ok = true;
					}
				}
				 // IF THIS ROW IS LOG OF A RECURRING PAYMENT
				if($row2['transaction_type'] != 'recurring_payment') {
					$style = "background-color:gray";
				} else if(!$lastpayment) {
					$lastpayment = $lp;
					$build .= '<td>' . $lastpayment . '</td>';
				}
					
				echo '<div style="' . $style . '">' . $tag;
				echo $k . ': ' . $v;
				echo '</div>';
				$currentfound=true;
				/* } else {
					echo '<p style="background-color:red; padding:30px">' . $k . ': ' . $v . '</p>';
				} */
					
					
					
				// echo $k . ': ' . $v . '<br>';
			}
			
			echo '</blockquote>';
		}
	}
	
	if(!$lastpayment) {
		if($lp) {
			$build .= '<td>' . $lp . '</td>';
		} else {
			$build .= '<td>--</td>';
		}
	}
		
	if(!$currentfound) {
		echo '***ATTN***';
	}

	echo '<br><br><hr /><br><br>';
	
	if(!$currentfound) {
		$build .= '<td bgcolor="yellow">No Record Found*</td>
					<td bgcolor="yellow">missing</td>
					<td bgcolor="yellow">?</td>';
	} else if($ok) {
		$okcount++;
		$build .= '<td bgcolor="lightgreen">yes</td>';
	} else {
		$badcount++;
		$build .= '<td bgcolor="red"><b>NO</b></td>';
	}
	
	$build .= '<td style="font-size:10px; color:gray">' . implode('<br>', array_reverse($recent_arr)) . '</td>';
	
	$build .= '</tr>';
}

echo '</div>';

$build .= '</table>';

echo $build;

/*
echo '<p>Red: ' . $red . '</p>';
echo '<p>Blue: ' . $blue . '</p>';
echo '<p>Yellow: ' . $yellow . '</p>';
echo '<p>ALL FAILED: ' . ($yellow+$red+$blue) . '</p>';
echo '<p>Green (GOOD): ' . $green . '</p>';
echo '<p></p>'; */
echo '<p>OK: ' . $okcount . '</p>';
echo '<p>NOT OK: ' . $badcount . '</p>';


// FIGURE OUT WHICH ACCOUNTS HAVE NOT HAD A RECENT PAYMENT






include('footer.php');
?>
