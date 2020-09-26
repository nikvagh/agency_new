<?php
	$page = "cancelled_accounts_list";
	$page_selected = "cancelled_accounts";
	include('header.php');
	include('../forms/definitions.php');
  	include('../includes/agency_dash_functions.php');
?>

<div id="page-wrapper">
	<!-- <div class="container-fluid"> -->
    	<div class="" id="main">

    		<h3>Cancelled Accounts </h3>

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
										$result = mysql_query("SELECT fu.user_id, fu.user_email, fu.username, ap.account_type, ap.firstname, ap.lastname, ap.register_browser FROM forum_users fu, agency_profiles ap WHERE ap.user_id=fu.user_id AND ap.account_status='cancel' AND (ap.account_type='talent' OR ap.account_type='client' OR ap.account_type='talent_manager') ORDER BY fu.user_id DESC
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