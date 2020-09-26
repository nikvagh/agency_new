<?php
	$page = "casting_call_list";
	$page_selected = "casting_calls";
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

	if(isset($_POST['action']) && $_POST['action'] == "change_publish"){

		$sql_status = "UPDATE agency_castings SET live = ".$_POST['publish']." WHERE casting_id = ".$_POST['id']." ";
		if(mysql_query($sql_status)){
			$notification['success'] = "Casting Approved successfully.";
		}
	}

	// =====================
	if(isset($_SESSION['flashdata'])){
		$notification['success'] = $_SESSION['flashdata'];
		unset($_SESSION['flashdata']);
	}
?>

<div id="page-wrapper">
	<!-- <div class="container-fluid"> -->
    	<div class="" id="main">
    		<h3>Casting Calls </h3>
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
						<div class="box-header text-right">
							<a href="casting-update.php" class="btn btn-theme btn-flat">Create Casting </a>
						</div>
						<div class="box-body">

							<form action="" method="post" id="datatableForm">
								<table class="datatable table table-responsive table-striped">
									<thead>
										<tr>
											<th>Id</th>
											<th>Project</th>
											<th>Director</th>
											<th>Status</th>
											<th></th>
										</tr>
									</thead>
									<tbody>
										<?php
											// $result = mysql_query("select * from agency_castings where posted_by = " . $_SESSION['user_id'] . "");
											$result = mysql_query("select ac.*,ap.firstname,ap.lastname from agency_castings ac 
													LEFT JOIN agency_profiles ap ON ac.casting_director = ap.user_id
												");
											if (mysql_num_rows($result) > 0) {
												while ($row = mysql_fetch_assoc($result)) {
													echo '<tr>';
													echo '<td>'.$row['casting_id'].'</td>';
													echo '<td>'.$row['job_title'].'</td>';
													echo '<td>'.$row['firstname'].' '.$row['lastname'].'</td>';
													if($row['live'] == 1){
														echo '<td><label class="label label-primary">Approved</label></td>';
													}else{
													?>

														<td><label class="label label-warning">Pending</label> &nbsp;
															<button class="btn btn-sm btn-default btn-flat" onclick="javascript: changePublishStatus('datatableForm','<?php echo $row['casting_id']; ?>','1');return false;"> Click To Approve</button>
														</td>

													<?php
													}
													echo '<td>';
													echo '<a href="casting-update.php?casting_id='.$row['casting_id'].'" class="btn btn-theme btn-flat"> Edit</a> &nbsp;';
													// echo '<a href="" class="btn btn-info btn-request" data-id="'.$row['casting_id'].'"><i class="fa fa-paper-plane"></i> Send Booking Request </a>&nbsp;';
													echo '<a href="casting-view.php?casting_id='.$row['casting_id'].'" class="btn btn-theme btn-flat" data-id="'.$row['casting_id'].'"> View </a>';
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
	<!-- </div> -->
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
<script type="text/javascript" src="https://code.jquery.com/ui/1.11.0/jquery-ui.min.js"></script>
<script type="text/javascript" src="../dashboard/assets/jQuery-DateTimepicker-Addon/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript" src="../dashboard/assets/jQuery-DateTimepicker-Addon/i18n/jquery-ui-timepicker-addon-i18n.min.js"></script>
<script type="text/javascript" src="../dashboard/assets/jQuery-DateTimepicker-Addon/jquery-ui-sliderAccess.js"></script>

<script type="text/javascript" src="../dashboard/assets/js/jquery.validate.js"></script>

<script>
	$(document).ready( function () {
	    $('.datatable').DataTable({
	        "order": [[ 0, "desc" ]],
	        'columnDefs': [{
			    'targets': [4], /* column index */
			    'orderable': false, /* true or false */
			}]
	    });

	    // $('.btn-request').click(function(e){
	    $(".datatable").on("click", ".btn-request", function(e){
	    	e.preventDefault();

		   var casting_id = $(this).attr('data-id');
		   // AJAX request
		   $.ajax({
			    url: '../ajax/dashboard_request.php',
			    type: 'post',
			    data: {name:'get_talent',casting_id: casting_id},
			    dataType: 'json',
			    success: function(response){ 

			    	// console.log(response);
			    	// return false;

			    	html = '<option value=""></option>';
			    	$.each(response, function(index, value){
				    	html += '<option value="'+value.user_id+'">'+value.firstname+' '+value.lastname+'</option>';
				    });

			    	$('#req_talent').html(html);
			    	$('#casting_id').val(casting_id);
				    // Add response in Modal body
				    // $('.modal-body').html(response);

				    // Display Modal
				    $('#requestModal').modal('show'); 
			    }
		  	});
		});

	    $('#req_date').datepicker({
	    	changeMonth: true,
	    	changeYear: true,
	    	minDate: 0,
	    });
	    $('#req_time').timepicker();
	    // $('#req_location').datetimepicker();

	    $("#requestForm").validate({
			rules: {
				req_talent: "required",
				req_date: "required",
				req_time: "required",
				req_for: "required",
				req_location: "required"
			},
			messages: {
				// lastname: "Please +enter your lastname",
			},
			errorElement: "em",
			errorPlacement: function ( error, element ) {
				// Add the `help-block` class to the error element
				error.addClass( "help-block" );

				if ( element.prop( "type" ) === "checkbox") {
					error.insertAfter(element.parent("label"));
				} else {
					error.insertAfter(element);
				}
			},
			highlight: function ( element, errorClass, validClass ) {
				$( element ).parents( ".col-sm-5" ).addClass( "has-error" ).removeClass( "has-success" );
			},
			unhighlight: function (element, errorClass, validClass) {
				$( element ).parents( ".col-sm-5" ).addClass( "has-success" ).removeClass( "has-error" );
			}

			// submitHandler: function (){
			// 	alert( "submitted!" );
			// }
		});

	});

	function confirmDelete(frm, id)
	{
		var agree=confirm("Are you sure to delete this product?");
		if (agree)
		{
			$("#id").val(id);
			$("#action").val("delete");
			$("#"+frm).submit();
		}

	}

	function changePublishStatus(frm, id, status)
	{
		var agree = confirm("Are you sure to change status?");
		if (agree)
		{
			$("#id").val(id);
			$("#action").val("change_publish");
			$("#publish").val(status);
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