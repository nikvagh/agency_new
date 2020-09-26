<?php
	$page = "project_list";
	$page_selected = "project";
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
	<div class="" id="main">

		<h3>My Projects </h3>

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


		<div class="row">
			<div class="col-md-8 col-sm-12">

				<div class="box box-theme">
					<div class="box-header with-border">
						<div class="row">
							<div class="col-sm-6"> 
								<h3 class="box-title">Current Projects</h3>
							</div>
							<div class="col-sm-6">
								<a href="project-create.php" class="btn btn-theme btn-sm pull-right btn-flat">New Projects </a>
							</div>
						</div>
					</div>
					<div class="box-body">
						<table align="center" class="datatable table table-responsive table-striped">
							<thead>
								<tr>
									<th>Id</th>
									<th>Project</th>
									<th>Due Date</th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								<?php
									$result_project = mysql_query("select ap.* from agency_project ap 
															WHERE due_date >= CURDATE();
										");
									if (mysql_num_rows($result_project) > 0) {
										while ($row = mysql_fetch_array($result_project, MYSQL_ASSOC)) {
											?>
												<tr>
													<td><?php echo $row['project_id']; ?></td>
													<td><?php echo $row['project_name']; ?></td>
													<td><?php echo $row['due_date']; ?></td>
													<td>
														<a href="project-create.php?project_id=<?php echo $row['project_id']; ?>" class="btn btn-info btn-flat btn-sm" target="_blank"> View </a>
													</td>
												</tr>
											<?php
										}
									}
								?>
							</tbody>
						</table>
					</div>
				</div>

				<div class="box box-theme">
					<div class="box-header with-border">
						<div class="row">
							<div class="col-sm-6">
								<h3 class="box-title">Projects Archive</h3>
							</div>
						</div>
					</div>
					<div class="box-body">
						<table align="center" class="datatable table table-responsive table-striped">
							<thead>
								<tr>
									<th>Id</th>
									<th>Project</th>
									<th>Due Date</th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								<?php
									$result_project = mysql_query("select ap.* from agency_project ap 
															WHERE due_date < CURDATE();
										");
									if (mysql_num_rows($result_project) > 0) {
										while ($row = mysql_fetch_array($result_project, MYSQL_ASSOC)) {
											?>
												<tr>
													<td><?php echo $row['project_id']; ?></td>
													<td><?php echo $row['project_name']; ?></td>
													<td><?php echo $row['due_date']; ?></td>
													<td>
														<a href="project-create.php?project_id=<?php echo $row['project_id']; ?>" class="btn btn-info btn-flat btn-sm" target="_blank"> View </a>
													</td>
												</tr>
											<?php
										}
									}
								?>
							</tbody>
						</table>
					</div>
				</div>

			</div>

			<div class="col-md-4 col-sm-12">
				<?php include('quick_search.php'); ?>
			</div>
		</div>
	

	</div>
</div>

<?php include('footer_js.php'); ?>
<script src="../dashboard/assets/DataTables/datatables.min.js"></script> 
<script src="../dashboard/assets/DataTables/dataTables.bootstrap.min.js"></script> 

<!-- <script type="text/javascript" src="../dashboard/assets/jQuery-DateTimepicker-Addon/jquery-ui.min.js"></script> -->

<!-- <script type="text/javascript" src="http://code.jquery.com/jquery-1.11.1.min.js"></script> -->
<!-- <script type="text/javascript" src="http://code.jquery.com/ui/1.11.0/jquery-ui.min.js"></script>
<script type="text/javascript" src="../dashboard/assets/jQuery-DateTimepicker-Addon/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript" src="../dashboard/assets/jQuery-DateTimepicker-Addon/i18n/jquery-ui-timepicker-addon-i18n.min.js"></script>
<script type="text/javascript" src="../dashboard/assets/jQuery-DateTimepicker-Addon/jquery-ui-sliderAccess.js"></script> -->

<script type="text/javascript" src="../dashboard/assets/js/jquery.validate.js"></script>

<script>
	$(document).ready( function () {
	    $('.datatable').DataTable({
	        "order": [[ 0, "desc" ]],
	        'columnDefs': [{
			    'targets': [3], /* column index */
			    'orderable': false, /* true or false */
			}]
	    });
	});
</script>

<?php include('footer.php'); ?>