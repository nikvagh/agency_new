<?php
	$page = "incomplate_accounts_list";
	$page_selected = "incomplate_accounts";
	include('header.php');
	include('../forms/definitions.php');
  	include('../includes/agency_dash_functions.php');

	$notification = array();
	if(isset($_POST['requestSend']) && $_POST['requestSend'] != ""){

		// echo "<pre>";
		// print_r($_POST);
		// echo "</pre>";
		// exit;

		// $reminder_res = mysql_query("select * from agency_talent_casting atc
		//                   LEFT JOIN forum_users u ON u.user_id = atc.user_id 
		//                   LEFT JOIN agency_castings_roles acr ON atc.casting_role_id = acr.role_id 
		//                   LEFT JOIN agency_castings ac ON acr.casting_id = ac.casting_id
		//                   WHERE atc.talent_casting_id = ".$_POST['talent_casting_id']."
		//                 ");

		// while ($row = mysql_fetch_array($reminder_res, MYSQL_ASSOC)) {
		  $talent = get_talent_byId($_POST['req_talent']);

		  // echo "<pre>";print_r($talent);exit;

		  $req_email = $talent['user_email'];
		  $subject = 'The Agency: Booking available now';

		  $msg = '<p>we are informing that booking is avilable for you now for following project.</p>';
		  $msg .= 'Date : '.$_POST['req_date'].'<br/>';
		  $msg .= 'Time : '.$_POST['req_time'].'<br/>';
		  $msg .= 'Location : '.$_POST['req_location'].'<br/>';
		  $msg .= 'Description : '.$_POST['req_description'].'<br/>';
		  $msg .= 'Instructions : '.$_POST['req_instructions'].'<br/>';

		  if(send_mail($req_email,$subject,$msg)){

		    $sqlIns = "INSERT INTO agency_talent_request
		    		SET casting_id = ".$_POST['casting_id'].",
		    			user_id = ".$_POST['req_talent'].",
		    			request_by = ".$_SESSION['user_id'].",
		    			request_date = '".date('Y-m-d', strtotime($_POST['req_date']))."',
		    			request_time = '".$_POST['req_time']."',
		    			request_location = '".$_POST['req_location']."',
		    			request_description = '".$_POST['req_description']."',
		    			request_instruction = '".$_POST['req_instructions']."',
		    			request_status = 'approve'
		    		";

			if(mysql_query($sqlIns)){
				$notification['success'] = "Request sent successfully.";
			}

		  }else{
		    $notification['error'] = "Request sending failed!";
		  }

		// }
		// exit;
	}
?>

<div id="page-wrapper">
	<!-- <div class="container-fluid"> -->
    	<div class="" id="main">

    		<h3>Incomplete Accounts </h3>

    		<?php if(isset($notification['success'])){ ?>
		        <div class="alert alert-success" role="alert">
		            <?php echo $notification['success']; ?>
		        </div>
	        <?php } ?>
	        <?php if(isset($notification['error'])){ ?>
	            <div class="alert alert-danger" role="alert">
	                <?php echo $notification['error']; ?>
	            </div>
	        <?php } ?>

			<?php $counter = 0; ?>

			<div class="row">
				<div class="col-md-12">
					<div class="box box-theme">
						<div class="box-body">

							<table class="datatable table table-responsive table-striped">
								<thead>
									<tr>
										<th>USER ID</th>
										<th>USER TYPE</th>
										<th>FIRST NAME</th>
										<th>LAST NAME</th>
										<th>USER NAME</th>
										<th>EMAIL</th>
										<th></th>
									</tr>
								</thead>
								<tbody>
									<?php
										// $result = mysql_query("select * from agency_castings where posted_by = " . $_SESSION['user_id'] . "");
										$result = mysql_query("SELECT fu.user_id, fu.user_email, fu.username, ap.account_type, ap.firstname, ap.lastname, ap.register_browser FROM forum_users fu, agency_profiles ap WHERE ap.user_id=fu.user_id AND ap.account_status='pending' AND (ap.account_type='talent' OR ap.account_type='client' OR ap.account_type='talent_manager') ORDER BY fu.user_id DESC
											");
										if (mysql_num_rows($result) > 0) {
											while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
												echo '<tr>';
												echo '<td>'.$row['user_id'].'</td>';
												echo '<td>'.$row['account_type'].'</td>';
												echo '<td>'.$row['firstname'].'</td>';
												echo '<td>'.$row['lastname'].'</td>';
												echo '<td>'.$row['username'].'</td>';
												echo '<td>'.$row['user_email'].'</td>';
												echo '<td>';
												// echo '<a href="casting-update.php?casting_id='.$row['casting_id'].'" class="btn btn-primary">Edit</a> ';=
												echo '<a href="../profile-view.php?user_id='.$row['user_id'].'" class="btn btn-info"><b> View </b></a>';
												echo '</td>';
												echo '</tr>';
											}
										}
									?>
								</tbody>
							</table>

						</div>
					</div>
				</div>
			</div>
		

		</div>
	<!-- </div> -->
</div>

<?php include('footer_js.php'); ?>
<script src="../dashboard/assets/DataTables/datatables.min.js"></script> 
<script src="../dashboard/assets/DataTables/dataTables.bootstrap.min.js"></script> 

<!-- <script type="text/javascript" src="http://code.jquery.com/ui/1.11.0/jquery-ui.min.js"></script>
<script type="text/javascript" src="../dashboard/assets/jQuery-DateTimepicker-Addon/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript" src="../dashboard/assets/jQuery-DateTimepicker-Addon/i18n/jquery-ui-timepicker-addon-i18n.min.js"></script>
<script type="text/javascript" src="../dashboard/assets/jQuery-DateTimepicker-Addon/jquery-ui-sliderAccess.js"></script>
<script type="text/javascript" src="../dashboard/assets/js/jquery.validate.js"></script> -->

<script>
	$(document).ready( function () {
	    $('.datatable').DataTable({
	        "order": [[ 0, "desc" ]],
	        'columnDefs': [{
			    'targets': [6], /* column index */
			    'orderable': false, /* true or false */
			}]
	    });
	});
</script>
<?php include('footer.php'); ?>