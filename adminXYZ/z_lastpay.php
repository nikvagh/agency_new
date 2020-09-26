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

$query = "SELECT forum_users.user_id, forum_users.user_email, forum_users.username, agency_profiles.firstname, agency_profiles.lastname, agency_profiles.payProcessedDate, agency_profiles.pay_term FROM forum_users, agency_profiles WHERE agency_profiles.user_id=forum_users.user_id AND agency_profiles.payProcessed='1' AND forum_users.user_type='0' AND agency_profiles.account_type='talent' ORDER BY forum_users.user_id ASC";
echo '<p>' . $query . '</p>';
$result = mysql_query ($query);
while ($row = mysql_fetch_array ($result, MYSQL_ASSOC)) {
	
	$ok = false;
	foreach($row as $k=>$v) {
		$style = '';
		$tag = '';
		$border = '';
		
		if($k == 'payProcessedDate') {
			if(strpos($row['pay_term'], ':1:M') !== false) echo '--1M--';
			else if(strpos($row['pay_term'], ':3:M') !== false) echo '--3M--';
			else if(strpos($row['pay_term'], ':1:Y') !== false) echo '--1Y--';
			else echo '*****';
			
			if(strpos($row['pay_term'], ':1:M') !== false && strtotime($v) < strtotime("1 month ago")) {
				$style = "background-color:red";
				$tag = '1m1m:: ';
				$red++;
				$border = 'red';
			} else if(strpos($row['pay_term'], ':3:M') !== false && strtotime($v) < strtotime("3 months ago")) {
				$style = "background-color:yellow";
				$tag = '3m3m:: ';
				$yellow++;
				$border = 'yellow';
			} else if(strpos($row['pay_term'], ':1:Y') !== false && strtotime($v) < strtotime("1 year ago")) {
				$style = "background-color:lightblue";
				$tag = '1y1y:: ';
				$blue++;
				$border = 'lightblue';
			} else {
				$style = "background-color:lightgreen";
				$tag = 'okok:: ';
				$green++;
				$border = 'lightgreen';
			}
		}
		echo '<div style="' . $style . '">' . $tag;
		echo $k . ': ' . $v;
		echo '</div>';
	}
	$uid = $row['user_id'];
	$query2 = "SELECT * FROM agency_payment_log WHERE user_id='$uid' ORDER BY log_id DESC LIMIT 5";
	// echo '<p>' . $query . '</p>';
	$result2 = mysql_query ($query2);
	while ($row2 = mysql_fetch_array ($result2, MYSQL_ASSOC)) {
		echo '<blockquote style="border:1px solid ' . $border . '; margin-top:10px; padding:10px">';
		foreach($row2 as $k=>$v) {
			$style = '';
			$tag = '';
			if($k == 'logtime') {					
				if(strpos($row['pay_term'], ':1:M') !== false && strtotime($v) < strtotime("1 month ago")) {
					$style = "background-color:red";
					$tag = '1m1m:: ';
				} else if(strpos($row['pay_term'], ':3:M') !== false && strtotime($v) < strtotime("3 months ago")) {
					$style = "background-color:yellow";
					$tag = 'LOG-3m3m:: ';
				} else if(strpos($row['pay_term'], ':1:Y') !== false && strtotime($v) < strtotime("1 year ago")) {
					$style = "background-color:lightblue";
					$tag = 'LOG-1y1y:: ';;
				} else {
					$style = "background-color:lightgreen";
					$tag = 'LOG-okok:: ';
					$ok = true;
				}
			}
			echo '<div style="' . $style . '">' . $tag;
			echo $k . ': ' . $v;
			echo '</div>';
				
				
			// echo $k . ': ' . $v . '<br>';
		}
		echo '</blockquote>';

	}

	echo '<br><br><hr /><br><br>';
	
	if($ok) {
		$okcount++;
	} else {
		$badcount++;
	}
}

echo '<p>Red: ' . $red . '</p>';
echo '<p>Blue: ' . $blue . '</p>';
echo '<p>Yellow: ' . $yellow . '</p>';
echo '<p>ALL FAILED: ' . ($yellow+$red+$blue) . '</p>';
echo '<p>Green (GOOD): ' . $green . '</p>';
echo '<p></p>';
echo '<p>OK: ' . $okcount . '</p>';
echo '<p>NOT OK: ' . $badcount . '</p>';


// FIGURE OUT WHICH ACCOUNTS HAVE NOT HAD A RECENT PAYMENT






include('footer.php');
?>
