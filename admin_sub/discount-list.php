<?php
	$page = "discount_list";
	$page_selected = "discount";

	include('header.php');
	include('../forms/definitions.php');
  	include('../includes/agency_dash_functions.php');
	$notification = array();

	// echo "<pre>";
	// print_r($_POST);
	// echo "</pre>";

	if(isset($_POST['action']) && $_POST['action'] == "delete"){
		$sql_status = "DELETE FROM agency_discounts WHERE discount_id = ".$_POST['id']." ";
		if(mysql_query($sql_status)){
			$notification['success'] = "Coupon delete successfully.";
		}
	}

?>

<div id="page-wrapper">
	<div class="" id="main">

		<h3>Discounts </h3>
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
					<div class="box-header text-right">
						<a href="discount-create.php" class="btn btn-theme btn-flat">Create Discount Code</a>
					</div>

					<div class="box-body">
						<form action="" method="post" id="datatableForm">
							<table class="datatable table table-responsive table-striped">
								<thead>
									<tr>
										<th>Id</th>
										<th>Code</th>
										<th>Maximum Number Of Usage</th>
										<th>% Of Memebership</th>
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

										$result = mysql_query("select ad.* from agency_discounts ad
																WHERE 1 ".$cond."
															");
										if (mysql_num_rows($result) > 0) {
											while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
												echo '<tr>';
												echo '<td>'.$row['discount_id'].'</td>';
												echo '<td>'.$row['code'].'</td>';
												echo '<td>'.$row['max_use'].'</td>';
												echo '<td>'.$row['percentage'].'</td>';

												echo '<td>';
												if($row['status'] == "Enable"){
													echo '<label class="label label-success">'.$row['status'].'</label>';
												}elseif($row['status'] == "Disable"){
													echo '<label class="label label-danger">'.$row['status'].'</label>';
												}
												echo '</td>';

												echo '<td>';
												echo '<a href="discount-edit.php?discount_id='.$row['discount_id'].'" class="btn btn-theme btn-flat">Edit</a> &nbsp;';
												?>
													<button onClick="javascript: confirmDelete('datatableForm','<?php echo $row['discount_id']; ?>');return false;" class="btn btn-theme btn-flat"> <i class="fa fa-trash-o"></i> Delete</button>
												<?php
												// echo '<a href="" class="btn btn-info btn-request" data-id="'.$row['casting_id'].'"><i class="fa fa-paper-plane"></i> Send Booking Request </a>&nbsp;';
												echo '</td>';
												echo '</tr>';
											}
										}
									?>
								</tbody>
							</table>

							<input type="hidden" name="action" id="action" />
							<input type="hidden" name="id" id="id"/>
							<input type="hidden" name="publish" id="publish"/>

						</form>
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

	function confirmDelete(frm, id)
	{
		var agree=confirm("Are you sure to delete ?");
		if (agree)
		{
			$("#id").val(id);
			$("#action").val("delete");
			$("#"+frm).submit();
		}
	}
</script>
<script>
	if (window.history.replaceState) {
	  window.history.replaceState( null, null, window.location.href );
	}
</script>
<?php include('footer.php'); ?>