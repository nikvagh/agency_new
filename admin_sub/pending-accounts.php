<?php
	$page = "pending_accounts_list";
	$page_selected = "pending_accounts";
	include('header.php');
	include('../forms/definitions.php');
  	include('../includes/agency_dash_functions.php');

  	$notification = array();
  	if(isset($_POST['action']) && $_POST['action'] == "pending_to_approve"){

  		// echo "<pre>";
  		// print_r($_POST['id']);

  		$sql = "UPDATE agency_cc 
  				SET verified = 1
  				WHERE
  				user_id = ".$_POST['id']."
  				";
  		if(mysql_query($sql)){
  			$notification['success'] = "Approve Account Successfully";
  		}

  	}
?>

<div id="page-wrapper">
	<!-- <div class="container-fluid"> -->
    	<div class="" id="main">

    		<h3>Pending Accounts </h3>

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

							<form action="" method="post" id="pending_frm">
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

										// UNION
										// 		(SELECT fu.user_id, fu.user_email, fu.username, ap.account_type, ap.firstname, ap.lastname, ap.register_browser FROM forum_users fu, agency_profiles ap
										// 		WHERE ap.user_id = fu.user_id AND (ap.account_type='client' OR ap.account_type='talent_manager') ORDER BY fu.user_id DESC)
											$result = mysql_query("
												(SELECT fu.user_id, fu.user_email, fu.username, ap.account_type, ap.firstname, ap.lastname, ap.register_browser FROM forum_users fu, agency_profiles ap, agency_cc cc 
												WHERE ap.user_id = fu.user_id AND cc.user_id = fu.user_id AND (ap.account_type = 'talent' OR ap.account_type='client' OR ap.account_type='talent_manager')
													AND cc.verified = 0 
													GROUP BY fu.user_id
													ORDER BY fu.user_id DESC)
												");
											if (mysql_num_rows($result) > 0) {
												while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
													echo '<tr>';
													echo '<td>'.$row['user_id'].'</td>';
	                                          	?>
													<td>
														<?php 
															if($row['account_type'] == "client"){
																echo "Client";
															}else if($row['account_type'] == "talent_manager"){
																echo "Talent Manager";
															}else if($row['account_type'] == "talent"){
																echo "Talent";
															}
														?>
													</td>

												<?php
													echo '<td>'.$row['firstname'].'</td>';
													echo '<td>'.$row['lastname'].'</td>';
													echo '<td>'.$row['username'].'</td>';
													echo '<td>'.$row['user_email'].'</td>';
													echo '<td>';
													echo '<a href="../profile-view.php?user_id='.$row['user_id'].'" class="btn btn-info btn-sm btn-flat">View </a>&nbsp;';

													if(array_key_exists('approve_talent', $user_privilege)){
														echo '<a onClick="approve_pending('.$row['user_id'].')" class="btn btn-primary btn-sm btn-flat">Approve</a> ';
													}

													echo '</td>';
													echo '</tr>';
												}
											}
										?>
									</tbody>
								</table>

								<input type="hidden" name="id" id="formId" value="" />
								<input type="hidden" name="action" id="formAction" value="" />
							</form>

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

	function approve_pending(id){
		// alert(id);

		if(confirm('Are you sure want to arrove account?')){
			$('#pending_frm #formId').val(id);
			$('#pending_frm #formAction').val('pending_to_approve');
			$('#pending_frm').submit();
		}

	}
</script>
<?php include('footer.php'); ?>