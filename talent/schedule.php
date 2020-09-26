<?php 
	$page = "schedule";
	$page_selected = "schedule";

	include('header.php');
	include('../includes/agency_dash_functions.php');

	$notification = array();
	if(isset($_POST['submit_fitting_date_time'])){
		
		// echo "<pre>";
		// print_r($_POST);
		// echo "</pre>";

		$sql = "UPDATE agency_mycastings
				SET 
				fitting_date = '".$_POST['fitting_date']."',
				fitting_time = '".$_POST['fitting_time']."'
				WHERE 
				submission_id = ".$_POST['submission_id']."
				";

		if(mysql_query($sql)){
			$notification['success'] = 'Date and Time saved successfully';
		}
	}
?>

<div id="page-wrapper">
    <div class="" id="main">

		<div class="row">
			<div class="col-sm-12">
				<h3>Schedule </h3>

				<?php if(isset($notification['success'])){ ?>
					<div class="alert alert-success" role="alert" id="alert-success-form">
						<?php echo $notification['success']; ?>
					</div>
				<?php } ?>
				<?php if(isset($notification['error'])){ ?>
					<div class="alert alert-danger" role="alert" id="alert-danger-form">
						<?php echo $notification['error']; ?>
					</div>
				<?php } ?>
				
				<div class="row">
					<div class="col-sm-8">

						<div class="box box-theme">
							<div class="box-header with-border">
				            	<h3 class="box-title">Schedule </h3>
							</div>

							<div class="box-body">
								<table class="datatable table table-responsive table-striped table-bordered" align="center">
									<thead>
										<tr>
											<th>Casting</th>
											<th>Role</th>
											<th></th>
										</tr>
									</thead>
									<tbody>
										<?php
											$sc_sql = "SELECT am.*,acr.*,ac.* FROM agency_mycastings am
																	LEFT JOIN agency_castings_roles acr ON acr.role_id = am.role_id
																	LEFT JOIN agency_castings ac ON acr.casting_id = ac.casting_id
																	LEFT JOIN agency_profiles ap ON ap.user_id = am.user_id
																	WHERE am.audition_list = 'Y' AND am.audition_book = 'Y' 
																	AND am.user_id = ".$_SESSION['user_id']."
																	GROUP BY am.submission_id
																";
											$sc_result = mysql_query($sc_sql);
										?>

										<?php
											if (mysql_num_rows($sc_result) > 0) {
												while ($row = mysql_fetch_assoc($sc_result)) {
													echo '<tr>';
													echo '<td>'.$row['job_title'].'</td>';
													echo '<td>'.$row['name'].'</td>';
													?>

													<td>
														<a class="btn btn-theme btn-sm btn-flat btn-fitting-request-modal" data-id="<?php echo $row['submission_id']; ?>">
															Fitting Date Time
														</a>
													</td>

												<?php 
													// echo '<form action="" method="post" class="form-inline">';
													// echo '<input type="hidden" name="reminder_email" value="'.$row['user_email'].'"/>';
													// echo '<button type="submit" class="btn btn-info" value="reminder">Send Reminder</button>';
													// echo '</form>';
													// echo '</td>';
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
			
	</div>
</div>

<div class="modal fade" id="fittingModal" role="dialog">
    <div class="modal-dialog">
    	<form role="form" id="fittingModalForm" method="post" action="">
	        <div class="modal-content">
	            <!-- Modal Header -->
	            <div class="modal-header">
	                <button type="button" class="close" data-dismiss="modal">
	                    <span aria-hidden="true">&times;</span>
	                    <span class="sr-only">Close</span>
	                </button>
	                <h4 class="modal-title" id="myModalLabel">Fitting Date Time</h4>
	            </div>
	            
	            <!-- Modal Body -->
	            <div class="modal-body">
	                
	            </div>
	            
	            <!-- Modal Footer -->
	            <div class="modal-footer">
	            	<!-- <input type="submit" name="" id="casting_id" value="" /> -->
	                <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Close</button>
	                <input type="submit" class="btn btn-success btn-flat" name="submit_fitting_date_time" value="Accept & Confirm"/>
	            </div>
	        </div>
        </form>
    </div>
</div>

<?php include('footer_js.php'); ?>

<script type="text/javascript" src="http://code.jquery.com/ui/1.11.0/jquery-ui.min.js"></script>
<script type="text/javascript" src="../dashboard/assets/jQuery-DateTimepicker-Addon/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript" src="../dashboard/assets/jQuery-DateTimepicker-Addon/i18n/jquery-ui-timepicker-addon-i18n.min.js"></script>
<script type="text/javascript" src="../dashboard/assets/jQuery-DateTimepicker-Addon/jquery-ui-sliderAccess.js"></script>
<!-- <script type="text/javascript" src="../dashboard/assets/ckeditor/ckeditor.js"></script> -->

<!-- <script type="text/javascript" src="../dashboard/assets/bootstrap-tagsinput-latest/dist/bootstrap-tagsinput.min.js"></script> -->
<!-- <script type="text/javascript" src="../dashboard/assets/js/jquery.validate.js"></script> -->
<script src="../dashboard/assets/DataTables/datatables.min.js"></script> 
<script src="../dashboard/assets/DataTables/dataTables.bootstrap.min.js"></script> 
<script>
	$(document).ready( function () {
	    $('.datatable').DataTable();
	});

	$("table").on("click", ".btn-fitting-request-modal", function(e){	
    	e.preventDefault();

	   var submission_id = $(this).attr('data-id');
	   // AJAX request
	   $.ajax({
		    url: '../ajax/dashboard_request.php',
		    type: 'post',
		    data: {name:'get_submission_byId',submission_id: submission_id},
		    dataType: 'json',
		    success: function(res){ 
		    	// console.log(res);
		    	// return false;
				fitting_date ="";
				if(res.fitting_date != "0000-00-00"){
					fitting_date = res.fitting_date;
				}

				fitting_time = "";
				if(res.fitting_time != "00:00:00"){
					fitting_time = res.fitting_time;
				}

				html = '';
				html += '<div class="form-group">'+
							'<label> Date</label>'+
							'<input type="text" name="fitting_date" id="fitting_date" class="form-control" value="'+fitting_date+'" autocomplete="off"/>'+
						'</div>'+
						'<div class="form-group">'+
							'<label> Time</label>'+
							'<input type="text" name="fitting_time" id="fitting_time" class="form-control" value="'+fitting_time+'" autocomplete="off"/>'+
						'</div>';
				html += '<input type="hidden" name="submission_id" value="'+submission_id+'" />';

				$('#fittingModal .modal-body').html(html);

				// Display Modal
				$('#fittingModal').modal('show');

		    }
	  	});
	});

	$(function() {
		$("body").delegate("#fitting_date", "focusin", function(){
			$(this).datepicker({
				changeMonth: true,
				changeYear: true,
				minDate: 0,
				dateFormat: 'yy-mm-dd'
			});
		});
	});

	$(function() {
		$("body").delegate("#fitting_time", "focusin", function(){
			$(this).timepicker();
		});
	});

</script>
<script>
	if (window.history.replaceState) {
		window.history.replaceState( null, null, window.location.href );
	}
</script>
<?php include('footer.php'); ?>