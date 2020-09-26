
<?php 
	$page = "affiliate";
	$page_selected = "affiliate";
	include('header.php'); 
?>

<div id="page-wrapper">
    <div class="" id="main">
        <div class="row">
        	<div class="col-md-12">
        		<h3>Affiliate</h3>

        		<div class="box box-theme">
					<!-- <div class="box-header with-border">
						<h3 class="box-title">Enter Mentor Information</h3>
                	</div> -->
					<div class="box-body">
						<?php
						    $lastmonth = date("Y-m-d H:i:s", mktime(0, 0, 0, date("m") - 1, date("d"), date("Y")));
						    $commission_sum = 0;

						    if (!empty($_GET['process'])) {
						        $query = "UPDATE agency_mentor_sales SET paid='1', date_of_payment=NOW() WHERE paid='0' AND date_of_payment IS NULL AND date_of_txn < '$lastmonth'";  // check to see if name already used.
						        mysql_query($query);
						        $url = 'mentors_pay.php';
						        ob_end_clean(); // Delete the buffer.
						        header("Location: $url");
						        exit(); // Quit the script.
						    }

						    echo '<br />Commissions for payments made at least one month ago (<' . $lastmonth . ')<br />
							';
						    $list = '<br /><u>MENTOR PAYMENT INFORMATION:</u><br /><br />';

						    echo '<textarea cols="80" rows="20" readonly="readonly" onclick="this.select()">';
						    $query = "SELECT * FROM agency_mentor_sales WHERE paid='0' AND date_of_txn < '$lastmonth' ORDER BY mentor_id";  // check to see if name already used.
						    $result = mysql_query($query);
						    while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) { // If there are projects.	
						        $saleid = $row['sale_id'];
						        $mentorid = $row['mentor_id'];
						        $userid = $row['user_id'];
						        $commission = $row['commission_amt'] - .02;
						        $date = $row['date_of_txn'];
						        $txn = $row['txn_id'];


						        $list .= 'Mentor ID: <b>' . $mentorid . '</b><br />';

						        // get mentor email
						        $query2 = "SELECT * FROM agency_mentors WHERE mentor_id='$mentorid'";
						        $result2 = mysql_query($query2);
						        if ($row2 = mysql_fetch_array($result2, MYSQL_ASSOC)) { // If there are projects.	
						            $list .= 'Mentor Name: <b>' . $row2['firstname'] . ' ' . $row2['lastname'] . '</b><br />';
						            $list .= 'PayPal Email: <b>' . $row2['paypal_email'] . '</b><br />';
						            echo $row2['paypal_email'] . '	' . $commission . '	USD	' . $saleid . "\r\n";
						            $commission_sum += $commission;
						        } else {
						            $list .= '<b><font color="red">Mentor account has been removed.  No commission will be paid.</font></b><br />';
						        }


						        $list .= 'User ID: <b>' . $userid . '</b><br />';
						        $list .= 'Commission: <b>' . $commission . '</b><br />';
						        $list .= 'PayPal TXN ID: <b>' . $txn . '</b><br />';
						        $list .= 'Date: <b>' . $date . '</b><br /><br />';
						    }
						    echo '</textarea>';
						    echo '<br />Total commissions: $' . number_format($commission_sum, 2);
						    echo '<br /><br /><div align="center"><a class="viewbutton" style="text-decoration:none" href="mentors_pay.php?process=true" onclick="return confirm(\'By continuing all of the visible commissions will be marked as paid and removed from the list.  Continue?\')"><b>CLEAR PAYMENTS</b></a><span style="font-size:x-small; color:gray"> (click after payments have been processed)</span></div><br />';
						    echo '<br>
								<br>
								<b>DIRECTIONS ON MAKING COMMISSION PAYMENTS WITH PAYPAL:*<br /><br />
								1) Copy the information from the box above (exactly as is!) into a plain text file and save it on your system<span style="font-size:xx-small; color:gray; font-weight:normal"> (you should probably make sure you have money in your PayPal account to make these payments first)</span><br />
								2) Log into PayPal<br />
								3) Click "Send Money"<br />
								4) Click "Make a Mass Payment"<br />
								5) Click "Upload"<br />
								6) Select the text file you created and saved in step 1<br />
								7) Leave "My payment recipients are identified by:" as "Email address"<br />
								8) <i>Optionally</i> fill in a personalized email subject and message for all recipients<br />
								9) Review and Submit<br />
								10) <font color="red"><u>DON\'T FORGET!</u></font> On this screen, click <a href="mentors_pay.php?process=true" onclick="return confirm(\'By continuing all of the visible commissions will be marked as paid and removed from the list.  Continue?\')">CLEAR PAYMENTS</a> (here or above) to be sure not to send the same commissions again<br />
								</b><br>
								*<span style="font-size:x-small; color:gray">this process can be automated but this is the safest way to do this process because there are no files on the server to be hacked to create payments that should not be made and you can visually monitor the process for any problems (eg, if someone gave a bad PayPal email address and the payment does not go through).  Also, this gives you the opportunity to make sure you have enough money in your account to make the Mentor payments!<br />
								Note: Do not do this process around midnight (server time) or commission list could roll over to next day in the middle of the process.<br />
								Note: 2 cents is subtracted from each payment as we discussed since PayPal charges 2 cents per Mass Pay transaction.<br />
								</span>
								<br>
								<br>';
						    echo $list;

						    $list = ''; // reset
						    echo '<hr><br /><br /><u>UPCOMING (month safety zone not past yet):</u><br><br>';
						    $query = "SELECT * FROM agency_mentor_sales WHERE paid='0' AND date_of_txn >= '$lastmonth' ORDER BY date_of_txn ASC";  // check to see if name already used.
						    $result = mysql_query($query);
						    while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) { // If there are projects.	
						        $saleid = $row['sale_id'];
						        $mentorid = $row['mentor_id'];
						        $userid = $row['user_id'];
						        $commission = $row['commission_amt'] - .02;
						        $date = $row['date_of_txn'];
						        $txn = $row['txn_id'];


						        $list .= 'Mentor ID: <b>' . $mentorid . '</b><br />';

						        // get mentor email
						        $query2 = "SELECT * FROM agency_mentors WHERE mentor_id='$mentorid'";
						        $result2 = mysql_query($query2);
						        if ($row2 = mysql_fetch_array($result2, MYSQL_ASSOC)) { // If there are projects.	
						            $list .= 'Mentor Name: <b>' . $row2['firstname'] . ' ' . $row2['lastname'] . '</b><br />';
						            $list .= 'PayPal Email: <b>' . $row2['paypal_email'] . '</b><br />';
						        } else {
						            $list .= '<b><font color="red">Mentor account has been removed.  No commission will be paid.</font></b><br />';
						        }


						        $list .= 'User ID: <b>' . $userid . '</b><br />';
						        $list .= 'Commission: <b>' . $commission . '</b><br />';
						        $list .= 'PayPal TXN ID: <b>' . $txn . '</b><br />';
						        $list .= 'Date: <b>' . $date . '</b><br /><br />';
						    }

						    echo $list;
						?>
					</div>
				</div>

			</div>
		</div>
	</div>
</div>

<?php include('footer_js.php'); ?>
<script>
	if (window.history.replaceState) {
		window.history.replaceState( null, null, window.location.href );
	}
</script>
<?php include('footer.php'); ?>