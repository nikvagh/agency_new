<?php
include('header.php');
include('../forms/definitions.php');
?>
<script language="javascript" type="text/javascript">
function checkAll(current) {
	var c = new Array();
	c = document.getElementsByTagName('input');
	for (var i = 0; i < c.length; i++) 	{
		if (c[i].type == 'checkbox') {
			c[i].checked = current.checked;
		}
	}
}
</script>

<div id="page-wrapper">
  	<div class="container-fluid">
    	<div class="well" id="main">

			<div align="center">
				<a href="userlist.php?filter=unapprovedtalent" class="viewbutton" style="text-decoration:none; ">Unapproved Talent</a><br /><br />

				<a href="userlist.php?filter=unapprovedclients" class="viewbutton" style="text-decoration:none;">Unapproved Clients</a><br /><br />
			    	<a href="userlist.php?filter=approvedclients" class="viewbutton" style="text-decoration:none; ">Approved Clients</a><br /><br />

				<a href="userlist.php?filter=cclist" class="viewbutton" style="text-decoration:none;">Unprocessed Credit Cards</a><br /><br />
				<a href="userlist.php?filter=unapprovedtalentwithcc" class="viewbutton" style="text-decoration:none;">Unapproved Talent who have entered Credit Card info</a><br /><br />
			    <a href="userlist.php?filter=unapprovedtalentwithoutcc" class="viewbutton" style="text-decoration:none;">Unapproved Talent who have NOT entered Credit Card info</a><br /><br />
				<a href="userlist.php?filter=unapprovedtalentwithccandpics" class="viewbutton" style="text-decoration:none; font-weight:bold">Unapproved Talent who have entered Unprocessed Credit Card info and have portfolio OR headshot</a><br /><br />
				<a href="userlist.php?filter=unapprovedtalentwithpaymentprocessed" class="viewbutton" style="text-decoration:none; font-weight:bold">Unapproved Talent who had their payment processed</a>
			<br /><br />
			<a href="userlist.php?filter=approvedpaidtalent" class="viewbutton" style="text-decoration:none;">Paid Approved Talent</a><br /><br />


			<a href="userlist.php?filter=allrequiredwithcc" class="viewbutton" style="text-decoration:none; font-weight:bold">Unapproved Talent who have entered unprocessed Credit Card info and all required info</a>
			<br /><br />
			<br /><br />

			<?php
				if(!empty($_GET['unapproveid'])) {
					$uaid = (int) escape_data($_GET['unapproveid']);
					$query = "UPDATE forum_users SET user_type='1' WHERE user_id='$uaid'";
					mysql_query($query);
					if(mysql_affected_rows() > 0) {
						echo '<b>User ID: ' . $upid . ' has been reset to Unapproved.</b><br /><br />';
					}
				}
				if(!empty($_GET['approveid'])) {
					$uid = (int) escape_data($_GET['approveid']);
					$query = "UPDATE forum_users SET user_type='0' WHERE user_id='$uid'";
					mysql_query($query);
					if(mysql_affected_rows() > 0) {
						echo '<b>User ID: ' . $upid . ' has been Approved.</b><br /><br />';
						
						
						// SEND WELCOME EMAIL!
						$query = "SELECT user_email, username FROM forum_users WHERE user_id='$upid'";
						$result = mysql_query ($query);
						if ($row = mysql_fetch_array ($result, MYSQL_ASSOC)) {
							$e = $row['user_email'];
							$un = $row['username'];
						
							$subject = 'Account activated';
							$message = file_get_contents('../adminXYZ/email_templates/admin_welcome_activated.txt');
							$message = str_replace("{USERNAME}", $un, $message);
							// echo $message;
							$headers = 'From: info@theagencyonline.com' . "\r\n" .
								'Reply-To: info@theagencyonline.com' . "\r\n";
							
							mail($e, $subject, $message, $headers);		
						}		
						
						
					}
				}

				if(!empty($_GET['remind'])) {
					$uid = (int) escape_data($_GET['remind']);
					$query = "SELECT user_email FROM forum_users WHERE user_id='$uid'";
					$result = mysql_query ($query);
					if ($row = mysql_fetch_array ($result, MYSQL_ASSOC)) {
						$to = $row['user_email'];
						$message = '<html>
							  <body>
							  ' .
							  mysql_result(mysql_query("SELECT varvalue FROM agency_vars WHERE varname='email_payment_failed'"), 0, 'varvalue') .
							  '
							  </body>
							  </html>';
						
						// $to = mysql_result(mysql_query("SELECT user_email FROM forum_users WHERE user_id='$user_id'"), 0, 'user_email');
						$from = "info@theagencyonline.com";
						$subject = "Your Pictures and Profile at The AGENCY";
					
						$headers  = "From: $from\r\n";
						$headers .= "Content-type: text/html\r\n";
					
						//options to send to cc+bcc
						//$headers .= "Cc: [email]email@email.com[/email]";
						//$headers .= "Bcc: [email]email@email.com[/email]";
						
						// now lets send the email.
						mail($to, $subject, $message, $headers);
						// mail("ungabo@yahoo.com", $subject, $message, $headers);
						
						echo '<b>User ID: ' . $uid . ' has been sent a reminder email.</b><br /><br />';
					}
				}

				// bulk reminders
				if(!empty($_POST['remind']) && ($_GET['filter'] == 'failedpayments' || $_GET['filter'] == 'failedpaymentsunapproved')) {
					$remind_a = array();
					$remind_a = $_POST['remind'];
					
					foreach($remind_a as $uid) {
						$uid = (int) escape_data($uid);
						$query = "SELECT user_email FROM forum_users WHERE user_id='$uid'";
						$result = mysql_query ($query);
						if ($row = mysql_fetch_array ($result, MYSQL_ASSOC)) {
							$to = $row['user_email'];
							$message = '<html>
								  <body>
								  ' .
								  mysql_result(mysql_query("SELECT varvalue FROM agency_vars WHERE varname='email_payment_failed'"), 0, 'varvalue') .
								  '
								  </body>
								  </html>';
							
							// $to = mysql_result(mysql_query("SELECT user_email FROM forum_users WHERE user_id='$user_id'"), 0, 'user_email');
							$from = "info@theagencyonline.com";
							$subject = "Your Pictures and Profile at The AGENCY";
						
							$headers  = "From: $from\r\n";
							$headers .= "Content-type: text/html\r\n";
						
							//options to send to cc+bcc
							//$headers .= "Cc: [email]email@email.com[/email]";
							//$headers .= "Bcc: [email]email@email.com[/email]";
							
							// now lets send the email.
							mail($to, $subject, $message, $headers);
							// mail("ungabo@yahoo.com", $subject, $message, $headers);
							
							echo '<b>User ID: ' . $uid . ' has been sent a reminder email.</b><br /><br />';
						}
					}
				}

				if(!empty($_GET['process'])) { // process credit card
					$userid = (int) $_GET['process'];
					$query = "SELECT * FROM agency_cc WHERE user_id='$userid' ORDER BY cc_id DESC";
					$result = mysql_query ($query);
					if ($row = mysql_fetch_array ($result, MYSQL_ASSOC)) {
						$cc_id = $row['cc_id'];
						$BillFname = $row['firstname'];
						$BillLname = $row['lastname'];
						$BillStreet = $row['street1'];
						$BillStreet2 = $row['street2'];
						$BillCity = $row['city'];
						$BillState = $row['state'];
						$BillZip = $row['zip'];
						$BillCountry = array_search($row['country'], $countryarray); 
						$CardType = $row['type'];
						$CardNumber = $row['number'];
						$CVV = $row['cvv'];
						$ExpMonth = $row['exp_month'];
						$ExpYear = $row['exp_year'];
						$pay_term = $row['pay_term'];
						$promocode = $row['promocode'];

						
						// if there's a promo code, check it for processing
						if(!empty($promocode)) {
							// FIRST check for "Discount" code
							$query = "SELECT * FROM agency_discounts WHERE discount_code='$promocode'";
							$result = mysql_query ($query);
							if ($row = mysql_fetch_array ($result, MYSQL_ASSOC)) {
								// this is a discount code
								$discount_type = $row['discount_type'];
								$query = "UPDATE agency_profiles SET discount_code='$promocode' WHERE user_id='$userid'";
								mysql_query($query);
							} else {
								// CHECK FOR "MENTOR" PROMO CODE
								$query = "SELECT mentor_id FROM agency_profiles WHERE user_id='$userid'";
								$result = mysql_query ($query);
								if ($row = mysql_fetch_array ($result, MYSQL_ASSOC)) {
									$mentorid = trim($row['mentor_id']);
									if(empty($mentorid)) { // if the mentor has not already been set previously, then assign
										$query = "SELECT * FROM agency_mentors WHERE mentor_code='$promocode'";
										$result = mysql_query ($query);
										if ($row = mysql_fetch_array ($result, MYSQL_ASSOC)) {
											$mentorid = $row['mentor_id'];
											$query = "UPDATE agency_profiles SET mentor_id='$mentorid' WHERE user_id='$userid'";
											mysql_query($query);
										}
									}
								}
								if(!empty($mentorid)) { // if this user has a mentor, apply discount
									$agencydiscount = .1;
								}
							}
						}
						
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
					}
					
					echo 'Firstname: ' . $BillFname . '<br />';
					echo 'Lastname: ' . $BillLname . '<br />';
					echo 'Street: ' . $BillStreet . '<br />';
					echo 'City: ' . $BillCity . '<br />';
					echo 'State: ' . $BillState . '<br />';
					echo 'Zip: ' . $BillZip . '<br />';
					echo 'Card Type: ' . $CardType . '<br />';
					echo 'Card Number: ' . $CardNumber . '<br />';
					echo 'CVV: ' . $CVV . '<br />';
					echo 'Exp Month: ' . $ExpMonth . '<br />';
					echo 'Exp Year: ' . $ExpYear . '<br />';
					echo 'Payment Terms: ' . $pay_term . '<br />';
					echo 'Discount Code: ' . $promocode . '<br />';
					echo 'Discount: ' . $agencydiscount * 100 . '%<br />';

					if(!empty($BillFname) && !empty($BillLname) && !empty($BillStreet) && !empty($BillCity) && !empty($BillState) && !empty($BillZip) && !empty($CardType) && !empty($CardNumber) && !empty($CVV) && !empty($ExpMonth) && !empty($ExpYear) && !empty($periodcode)) {

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
						include('../adminXYZ/payment/CreateRecurringPaymentsProfile.php'); // this processes the payment
					} else {
						echo '<b>Some information was missing</b><br /><br /><br /><br />';
					}
				}

				unset($query);
				if(isset($_GET['filter'])) {
					switch($_GET['filter']) {
						case 'unapprovedtalent':
							echo '<div class="adminheading">Unapproved Talent</div><br />';
							$query = "SELECT forum_users.user_id, forum_users.user_email, forum_users.username, agency_profiles.firstname, agency_profiles.lastname, agency_profiles.register_browser FROM forum_users, agency_profiles WHERE agency_profiles.user_id=forum_users.user_id AND forum_users.user_type='1' AND agency_profiles.account_type='talent' ORDER BY forum_users.user_id DESC";
							break;		
						case 'approvedclients':
							echo '<div class="adminheading">Approved Clients</div><br />';
							$query = "SELECT forum_users.user_id, forum_users.user_email, forum_users.username, agency_profiles.firstname, agency_profiles.lastname, agency_profiles.register_browser FROM forum_users, agency_profiles WHERE agency_profiles.user_id=forum_users.user_id AND forum_users.user_type='0' AND agency_profiles.account_type='client' ORDER BY forum_users.user_id DESC";
							break;		
						case 'unapprovedclients':
							echo '<div class="adminheading">Unapproved Clients</div><br />';
							$query = "SELECT forum_users.user_id, forum_users.user_email, forum_users.username, agency_profiles.firstname, agency_profiles.lastname, agency_profiles.register_browser FROM forum_users, agency_profiles WHERE agency_profiles.user_id=forum_users.user_id AND forum_users.user_type='1' AND agency_profiles.account_type='client' AND agency_profiles.registration_date>'1340057335' ORDER BY forum_users.user_id DESC";
							break;
						case 'unapprovedtalentwithcc':
							echo '<div class="adminheading">Unapproved Talent who have entered Credit Card info (unprocessed)</div><br />';
							$query = "SELECT forum_users.user_id, forum_users.user_email, forum_users.username, agency_profiles.firstname, agency_profiles.lastname, agency_profiles.register_browser, agency_cc.number, agency_cc.promocode FROM forum_users, agency_profiles, agency_cc WHERE agency_profiles.payProcessed='0' AND agency_profiles.user_id=forum_users.user_id AND agency_cc.user_id=forum_users.user_id AND forum_users.user_type='1' AND agency_profiles.account_type='talent' ORDER BY forum_users.user_id DESC";
							break;
						case 'unapprovedtalentwithoutcc':
							echo '<div class="adminheading">Unapproved Talent who have NOT entered Credit Card info</div><br />';
							$query = "SELECT forum_users.user_id, forum_users.user_email, forum_users.username, agency_profiles.firstname, agency_profiles.lastname, agency_profiles.register_browser FROM forum_users, agency_profiles WHERE agency_profiles.user_id=forum_users.user_id AND forum_users.user_type='1' AND forum_users.user_id NOT IN (SELECT user_id FROM agency_cc) AND agency_profiles.account_type='talent' ORDER BY forum_users.user_id DESC";
							break;			
						case 'unapprovedtalentwithccandpics':
							echo '<div class="adminheading">Unapproved Talent who have entered Unprocessed Credit Card info and have at least 1 photo in Gallery OR a Headshot</div><br />';
							$query = "SELECT DISTINCT forum_users.user_id, forum_users.user_email, forum_users.username, agency_profiles.firstname, agency_profiles.lastname, agency_profiles.register_browser, agency_cc.number, agency_cc.promocode FROM forum_users, agency_profiles, agency_cc WHERE agency_profiles.payProcessed='0' AND  agency_profiles.user_id=forum_users.user_id AND agency_cc.user_id=forum_users.user_id AND forum_users.user_type='1' AND agency_profiles.account_type='talent' AND (forum_users.user_id IN (SELECT user_id FROM agency_photos) OR agency_profiles.headshot IS NOT NULL) ORDER BY forum_users.user_id DESC";
							break;
							
							
							
						case 'allrequiredwithcc':
							echo '<div class="adminheading">Unapproved Talent who have entered unprocessed Credit Card info and all required info</div><br />';
							$query = "SELECT DISTINCT forum_users.user_id, forum_users.user_email, forum_users.username, agency_profiles.firstname, agency_profiles.lastname, agency_profiles.register_browser, agency_cc.number, agency_cc.promocode FROM forum_users, agency_profiles, agency_cc WHERE agency_profiles.payProcessed='0' AND agency_profiles.user_id=forum_users.user_id AND agency_cc.user_id=forum_users.user_id AND forum_users.user_type='1' AND agency_profiles.account_type='talent' AND agency_profiles.firstname>'' AND agency_profiles.phone>'' AND agency_profiles.gender>'' AND agency_profiles.birthdate>'' AND agency_profiles.eyes>'' AND agency_profiles.hair>'' AND agency_profiles.height>'' AND agency_profiles.waist>'' AND agency_profiles.weight>'' AND agency_profiles.shoe>'' AND forum_users.user_id IN (SELECT user_id FROM agency_profile_unions) AND forum_users.user_id IN (SELECT user_id FROM agency_profile_ethnicities) AND forum_users.user_id IN (SELECT user_id FROM agency_profile_categories) AND forum_users.user_id IN (SELECT user_id FROM agency_profile_voices) AND forum_users.user_id IN (SELECT user_id FROM agency_photos) ORDER BY forum_users.user_id DESC";
							break;				
							
							
							
							
						case 'unapprovedtalentwithpaymentprocessed':
							echo '<div class="adminheading">Unapproved Talent who had their payment processed -- <font color="red">anyone in this list should have their account approved immediately!</font></div><br />';
							$query = "SELECT forum_users.user_id, forum_users.user_email, forum_users.username, agency_profiles.firstname, agency_profiles.lastname, agency_profiles.register_browser FROM forum_users, agency_profiles WHERE agency_profiles.payProcessed='1' AND agency_profiles.user_id=forum_users.user_id AND forum_users.user_type='1' AND agency_profiles.account_type='talent' ORDER BY forum_users.user_id DESC";
							break;
						case 'approvedpaidtalent':
							echo '<div class="adminheading">Paid Approved Talent</div><br />';
							$query = "SELECT forum_users.user_id, forum_users.user_email, forum_users.username, agency_profiles.firstname, agency_profiles.lastname, agency_profiles.register_browser FROM forum_users, agency_profiles WHERE agency_profiles.user_id=forum_users.user_id AND agency_profiles.payProcessed='1' AND forum_users.user_type='0' AND agency_profiles.account_type='talent' ORDER BY forum_users.user_id ASC";
							break;			
					}

								
					$emaillistnames = '';
					$emaillist = '';
					$result = @mysql_query ($query);
					if (@mysql_affected_rows() > 0) { // If there are projects.
						$counter = 0; // count how many users there are
						if($_GET['filter'] == 'failedpayments' || $_GET['filter'] == 'failedpaymentsunapproved') {
							echo '<form action="userlist.php?filter=' . $_GET['filter'] . '" method="post">';	
						}
						echo '<table cellpadding="4" cellspacing="0" align="center" class="datatable table table-striped">
								<thead>
									<tr>
										<td>User Id</td>'.
										'<td>First Name</td><td width="120">' .
										'Last Name</td><td>Username</td><td>' . 
										'Email</td>' .
										'<td';
											if($_GET['filter'] == 'failedpayments' || $_GET['filter'] == 'failedpaymentsunapproved') {
												echo ' width="80"><input type="checkbox" onClick="checkAll(this)"';
											}
										echo '></td>
									</tr>
								</thead><tbody>';
						while ($row = mysql_fetch_array ($result, MYSQL_ASSOC)) {
							$proceed = true; // this is to check if there are 4 images minimum when allrequiredwithcc
							$userid = $row['user_id'];
							if($_GET['filter'] == 'allrequiredwithcc' && (mysql_result(mysql_query("SELECT COUNT(*) as 'Num' FROM agency_photos WHERE user_id='$userid'"),0) < 4)) {
								// $proceed = false;
							}
								
							if($proceed) {
								$username = $row['username'];
								$firstname = $row['firstname'];
								$lastname = $row['lastname'];
								$email = $row['user_email'];
								$emaillistnames .= '"' . $row['firstname'] . ' ' . $row['lastname'] . '" <' . $row['user_email'] . ">, ";
								$emaillist .= $row['user_email'] . ", ";
					
								$refercode = '&nbsp;';
								$refname = '&nbsp;';
								
								echo '<tr><td><a href="../profile.php?u=' . $userid . '" target="_blank">' . $userid . '</a></td><td>' . $firstname . '</td><td>' . $lastname . '</td><td>' . $username .
									'</td><td>' . $email . '</td><td>';
								if(isset($row['number'])) {
									echo '*' . substr($row['number'], -4, 4);
									if(isset($_GET['filter'])) {
										if($_GET['filter'] == 'unapprovedtalentwithccandpics' || $_GET['filter'] == 'cclist') {
											echo '(<a href="userlist.php?filter=' . $_GET['filter'] . '&process=' . $userid . '" onclick="return confirm(\'You are about to initiate the payment subscription for this member.  Please confirm.\')">process</a>)';
											if(!empty($row['promocode'])) {
												echo ' [' . $row['promocode'] . ']';
											}
										}
									}
								} else if($_GET['filter'] == 'approvedunpaidtalent') {
									echo '<a href="userlist.php?filter=' . $_GET['filter'] . '&unapproveid=' . $userid . '" onclick="return confirm(\'You are about to UNAPPROVE this member.  Please confirm.\')">UNapprove</a>';
								} else if($_GET['filter'] == 'unapprovedtalentwithpaymentprocessed') {
									echo '<a href="userlist.php?filter=' . $_GET['filter'] . '&approveid=' . $userid . '" onclick="return confirm(\'You are about to APPROVE this member.  Please confirm.\')">Approve</a>';			
								} else if($_GET['filter'] == 'referred') {
									echo '<a href="mentor_view.php?id=' . $row['mentor_id'] . '"><b>mentor</b></a>';
								} else if($_GET['filter'] == 'discounts') {
									$dcode = $row['discount_code'];
									$discountid = mysql_result(mysql_query("SELECT discount_id FROM agency_discounts WHERE discount_code='$dcode'"), 0, 'discount_id');
									echo '<a href="discount_view.php?id=' . $discountid . '"><b>' . $row['discount_code'] . '</b></a>';
								} else if($_GET['filter'] == 'failedpayments' || $_GET['filter'] == 'failedpaymentsunapproved') {
									echo '<input type="checkbox" name="remind[]" value="' . $userid  . '"><a href="userlist.php?filter=' . $_GET['filter'] . '&remind=' . $userid . '"><b>remind</b></a>';
									if($_GET['filter'] == 'failedpayments') {
										echo ' / <a href="userlist.php?filter=' . $_GET['filter'] . '&unapproveid=' . $userid . '" onclick="return confirm(\'You are about to UNAPPROVE this member.  Please confirm.\')"><b>UNapprove</b></a>';			
									}
								} else {
									echo '<a href="../profile.php?u=' . $userid . '" target="_blank" class="btn btn-primary"><b>view</b></a>';
								}	
									
								echo '</td></tr>'; 
								$counter++;
							}
						}
						echo '</tbody></table>';
						if($_GET['filter'] == 'failedpayments' || $_GET['filter'] == 'failedpaymentsunapproved') {
							echo '<br /><input type="submit" value="Remind Selected"></form>';
						}
						echo '</b><br />Accounts: ' . $counter;
					} else {
						echo '<b>No accounts in this category.</b>';
					}
				}

				echo '</div>';
			?>
			</div>

		</div>
	</div>
</div>

<?php include('footer_js.php'); ?>
<script src="../dashboard/assets/DataTables/datatables.min.js"></script> 
<script>
  $(document).ready( function () {
      $('.datatable').DataTable();
  });
</script>
<?php include('footer.php'); ?>