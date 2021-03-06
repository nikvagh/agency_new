<?php
	$page = "talent_list";
	$page_selected = "talent_member";
	include('header.php');
	include('../forms/definitions.php');
  	include('../includes/agency_dash_functions.php');
	$notification = array();
?>

<div id="page-wrapper">
	<div class="" id="main">

		<h3>Talents </h3>
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
					<div class="box-header">
						<a href="talent-create.php" class="btn btn-theme pull-right">Create Talent </a>
					</div>

					<div class="box-body">
						<table align="center" class="datatable table table-responsive table-striped">
							<thead>
								<tr>
									<th>USER ID</th>
									<th>FIRST NAME</th>
									<th>LAST NAME</th>
									<th>USER NAME</th>
									<th>EMAIL</th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								<?php
									$result = mysql_query("select ap.*,fu.username,fu.user_email from agency_profiles ap 
													LEFT JOIN forum_users fu ON fu.user_id = ap.user_id 
													WHERE account_type = 'talent'
											");
								?>
								<?php if (mysql_num_rows($result) > 0) { ?>
									<?php while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) { ?>
											<tr>
												<td><?php echo $row['user_id']; ?></td>
												<td><?php echo $row['firstname']; ?></td>
												<td><?php echo $row['lastname']; ?></td>
												<td><?php echo $row['username']; ?></td>
												<td><?php echo $row['user_email']; ?></td>
												<td>
													<a href="talent-update.php?user_id=<?php echo $row['user_id']; ?>" class="btn btn-theme">Edit</a>
													<a href="profile-view.php?user_id=<?php echo $row['user_id']; ?>" class="btn btn-theme">View </a>
												</td>
											</tr>
									<?php } ?>
								<?php } ?>
							</tbody>
						</table>
					</div>

				</div>
			</div>
		</div>
	
	</div>
</div>

<!-- data-toggle="modal" data-target="#requestModal" -->
<!-- Modal -->
<div class="modal fade" id="requestModal" role="dialog">
    <div class="modal-dialog">
    	<form role="form" id="requestForm" method="post" action="">
	        <div class="modal-content">
	            <!-- Modal Header -->
	            <div class="modal-header">
	                <button type="button" class="close" data-dismiss="modal">
	                    <span aria-hidden="true">&times;</span>
	                    <span class="sr-only">Close</span>
	                </button>
	                <h4 class="modal-title" id="myModalLabel">Booking Request To Talent</h4>
	            </div>

	            <!-- Modal Body -->
	            <div class="modal-body">
	                <p class="statusMsg"></p>
                	<div class="form-group">
                        <label>Talent</label>
                        <select class="form-control" id="req_talent" name="req_talent">
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Date</label>
                        <input type="text" class="form-control" id="req_date" name="req_date" placeholder="Enter Date" autocomplete="off"/>
                    </div>
                    <div class="form-group">
                        <label>Time</label>
                        <input type="text" class="form-control" id="req_time" name="req_time" placeholder="Enter Time" autocomplete="off"/>
                    </div>
                    <div class="form-group">
                        <label>Request For</label>
                        <select class="form-control" id="req_for" name="req_for">
                        	<option value=""></option>
                        	<option value="casting">casting</option>
                        	<option value="booking">booking</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Location</label>
                        <input type="text" class="form-control" id="req_location" name="req_location" placeholder="Enter Location"/>
                    </div>
                    <div class="form-group">
                        <label>Description </label>
                        <textarea class="form-control" id="req_description" name="req_description" placeholder="Enter Description"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Instructions </label>
                        <textarea class="form-control" id="req_instructions" name="req_instructions" placeholder="Enter Instructions"></textarea>
                    </div>
	            </div>
	            
	            <!-- Modal Footer -->
	            <div class="modal-footer">
	            	<input type="hidden" name="casting_id" id="casting_id" value="" />
	                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	                <!-- <button type="submit" class="btn btn-primary submitBtn"><i class="fa fa-paper-plane"></i> Send</button> -->
	                <input type="submit" class="btn btn-primary submitBtn" name="requestSend" value="Send" />
	            </div>
	        </div>
        </form>
    </div>
</div>

<?php include('footer_js.php'); ?>
<script src="../dashboard/assets/DataTables/datatables.min.js"></script> 
<script src="../dashboard/assets/DataTables/dataTables.bootstrap.min.js"></script> 

<!-- <script type="text/javascript" src="../dashboard/assets/jQuery-DateTimepicker-Addon/jquery-ui.min.js"></script> -->

<!-- <script type="text/javascript" src="http://code.jquery.com/jquery-1.11.1.min.js"></script> -->
<script type="text/javascript" src="http://code.jquery.com/ui/1.11.0/jquery-ui.min.js"></script>
<!-- <script type="text/javascript" src="../dashboard/assets/jQuery-DateTimepicker-Addon/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript" src="../dashboard/assets/jQuery-DateTimepicker-Addon/i18n/jquery-ui-timepicker-addon-i18n.min.js"></script>
<script type="text/javascript" src="../dashboard/assets/jQuery-DateTimepicker-Addon/jquery-ui-sliderAccess.js"></script> -->

<script type="text/javascript" src="../dashboard/assets/js/jquery.validate.js"></script>

<script>
	$(document).ready( function () {
	    $('.datatable').DataTable({
	        "order": [[ 0, "desc" ]],
	        'columnDefs': [{
			    'targets': [5], /* column index */
			    'orderable': false, /* true or false */
			}]
	    });

	});
</script>
<?php include('footer.php'); ?>