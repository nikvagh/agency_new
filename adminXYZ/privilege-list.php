<?php
	$page = "privilege_list";
	$page_selected = "privilege";

	include('header.php');
	include('../forms/definitions.php');
  	include('../includes/agency_dash_functions.php');
	$notification = array();
?>

<div id="page-wrapper">
	<div class="" id="main">

		<h3>Privileges </h3>
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
			<div class="col-md-7">
				<div class="box box-theme">
					<!-- <div class="box-header">
						<a href="discount-create.php" class="btn btn-primary pull-right">Create Discount Code</a>
					</div> -->

					<div class="box-body">
						<table align="center" class="datatable table table-responsive table-striped">
							<thead>
								<tr>
									<th>Id</th>
									<th>Name</th>
									<th>Email</th>
									<th>Phone</th>
									<th>Status</th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								<?php
									$cond = "";
									// if(isset($_GET['status'])){
									// 	$cond .= " AND asp.status = '".$_GET['status']."'";
									// }

									$result = mysql_query("select ap.* from agency_profiles ap
															LEFT JOIN forum_users fu ON fu.user_id = ap.user_id
															WHERE ap.account_type = 'admin' ".$cond."
														");
									if (mysql_num_rows($result) > 0) {
										while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
											echo '<tr>';
											echo '<td>'.$row['user_id'].'</td>';
											echo '<td>'.$row['firstname'].' '.$row['lastname'].'</td>';
											echo '<td>'.$row['email'].'</td>';
											echo '<td>'.$row['phone'].'</td>';

											echo '<td>';
											if($row['user_type'] == 0){
												echo '<label class="label label-success">Enable</label>';
											}elseif($row['status'] == 1){
												echo '<label class="label label-danger">Disable</label>';
											}
											echo '</td>';

											echo '<td>';
											echo '<a href="profile-view.php?user_id='.$row['user_id'].'" class="btn btn-theme btn-sm btn-flat">View</a> &nbsp;';
											echo '<a href="privilege-edit.php?user_id='.$row['user_id'].'" class="btn btn-theme btn-sm btn-flat">Privilege </a>&nbsp;';
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

<!-- <script type="text/javascript" src="../dashboard/assets/js/jquery.validate.js"></script> -->

<script>
	$(document).ready( function () {
	    $('.datatable').DataTable({
	        "order": [[ 0, "desc" ]],
	        'columnDefs': [{
			    'targets': [4,5], /* column index */
			    'orderable': false, /* true or false */
			}]
	    });

	});
</script>
<?php include('footer.php'); ?>