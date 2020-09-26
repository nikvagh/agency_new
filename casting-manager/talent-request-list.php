
<?php
$page_selected = "request";
include('header.php');
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
	    		SET casting_id = '".$_POST['casting_id']."',
	    			user_id = ".$_POST['req_talent'].",
	    			request_by = ".$_SESSION['user_id'].",
	    			request_date = '".date('Y-m-d', strtotime($_POST['req_date']))."',
	    			request_time = '".$_POST['req_time']."',
	    			request_for = '".$_POST['req_for']."',
	    			request_location = '".$_POST['req_location']."',
	    			request_description = '".$_POST['req_description']."',
	    			request_instruction = '".$_POST['req_instructions']."',
	    			request_status = 'pending'
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

if(isset($_POST['pending_submit'])){

	$request = get_talent_request($_POST['talent_request_id']);
	$talent = get_talent_byId($request['user_id']);

	$req_email = $talent['user_email'];
  	$subject = 'The Agency: Booking available now';

	$msg = '<p>we are informing that booking is avilable for you now for following project.</p>';
	$msg .= 'Date : '.$request['request_date'].'<br/>';
	$msg .= 'Time : '.$request['request_time'].'<br/>';
	$msg .= 'Location : '.$request['request_location'].'<br/>';
	$msg .= 'Description : '.$request['request_description'].'<br/>';
	$msg .= 'Instructions : '.$request['request_instruction'].'<br/>';

  	if(send_mail($req_email,$subject,$msg)){

    	$sqlIns = "UPDATE agency_talent_request
	    		SET request_status = 'approve'
	    			WHERE 
	    			talent_request_id = '".$_POST['talent_request_id']."'
	    		";

	if(mysql_query($sqlIns)){
		$notification['success'] = "Request approve successfully.";
	}

  	}else{
	    $notification['error'] = "Request approving failed.";
	}
	

	// echo "<pre>";
	// print_r($talent_req);
	// echo "</pre>";
}

?>

<div id="page-wrapper">
	<!-- <div class="container-fluid"> -->
    	<div class="" id="main">
    		<h3>Talent Request</h3>

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
    			<div class="col-md-4">
    				<form role="form" id="requestForm" method="post" action="">
	    				<div class="box box-theme">
	    					<div class="box-header with-border">
				                <h3 class="box-title">Send Booking Request To Talent</h3>
				            </div>

		                	<div class="box-body">
				                <p class="statusMsg"></p>
			                	<div class="form-group">
			                        <label>Talent</label>
			                        <select class="form-control" id="req_talent" name="req_talent">
			                        	<option value="">Select</option>
			                        	<?php foreach (get_talent() as $key => $value) { ?>
				                        	<option value="<?php echo $value['user_id']; ?>"><?php echo $value['firstname'].' '.$value['lastname']; ?></option>
				                        <?php } ?>
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

							<div class="box-footer">
				            	<input type="hidden" name="casting_id" id="casting_id" value="" />
				                <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->
				                <!-- <button type="submit" class="btn btn-primary submitBtn"><i class="fa fa-paper-plane"></i> Send</button> -->
				                <input type="submit" class="btn btn-theme submitBtn pull-right" name="requestSend" value="Send" />
				            </div>
						        
						</div>
					</form>
    			</div>

	    		<div class="col-md-4">
	              	<div class="box box-theme">
	              		<div class="box-header with-border">
			                <h3 class="box-title">All Request</h3>
			            </div>
	                	<div class="box-body">

							<?php $counter = 0; ?>
							<!-- <a href="notes-create.php" class="btn btn-primary">Create Notes </a> -->
							<!-- <br/> -->
							<br/>
							<table class="datatable table table-responsive table-striped">
								<thead>
									<tr>
										<!-- <th>Project</th> -->
										<th>Talent</th>
										<th>Request From</th>
										<th>Date Time</th>
										<!-- <th>Location</th> -->
										<th></th>
									</tr>
								</thead>
								<tbody>
									<?php
										$result = mysql_query("select atr.*,ap.firstname,ap.lastname,ac.job_title,ap1.firstname as firstname_by,ap1.lastname as lastname_by 
																from agency_talent_request atr
																LEFT JOIN agency_profiles ap ON ap.user_id = atr.user_id 
																LEFT JOIN agency_profiles ap1 ON ap1.user_id = atr.request_by 
																LEFT JOIN agency_castings ac ON ac.casting_id = atr.casting_id
																WHERE request_by = ".$_SESSION['user_id']."
															");
										if (mysql_num_rows($result) > 0) {
											while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
												echo '<tr>';
												// echo '<td>'.$row['job_title'].'</td>';
												echo '<td>'.$row['firstname'].' '.$row['lastname'].'</td>';
												echo '<td>'.$row['firstname_by'].' '.$row['lastname_by'].'</td>';

												echo '<td>'.$row['request_date'].' '.$row['request_time'].'</td>';
												// echo '<td>'.$row['request_location'].'</td>';
												echo '<td><a class="btn btn-theme btn-view-request btn-sm" data-id="'.$row['talent_request_id'].'">View</a></td>';
												// <a href="notes-view.php?casting_id='.$row['casting_id'].'">Delete</a></td>
												// echo '<td><a href="scheduled-talent-view.php?user_id='.$row['user_id'].'" class="btn btn-primary">View</a></td>';
												echo '</tr>';
											}
										}
									?>
								</tbody>
							</table>

						</div>
					</div>
				</div>

				<div class="col-md-4">
					<div class="box box-theme">
	              		<div class="box-header with-border">
			                <h3 class="box-title">Pending Casting</h3>
			            </div>
	                	<div class="box-body">
							<table class="pending_datatable table table-responsive table-striped">
								<thead>
									<tr>
										<!-- <th>Project</th> -->
										<th>Talent</th>
										<th>Request From</th>
										<th>Date Time</th>
										<!-- <th>Location</th> -->
										<th>Status</th>
										<th></th>
									</tr>
								</thead>
								<tbody>
									<?php
										$result = mysql_query("select atr.*,ap.firstname,ap.lastname,ac.job_title,ap1.firstname as firstname_by,ap1.lastname as lastname_by 
																from agency_talent_request atr
																LEFT JOIN agency_profiles ap ON ap.user_id = atr.user_id 
																LEFT JOIN agency_profiles ap1 ON ap1.user_id = atr.request_by 
																LEFT JOIN agency_castings ac ON ac.casting_id = atr.casting_id
																where atr.request_status = 'pending' AND request_by = ".$_SESSION['user_id']."
															");
										if (mysql_num_rows($result) > 0) {
											while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
												echo '<tr>';
												// echo '<td>'.$row['job_title'].'</td>';
												echo '<td>'.$row['firstname'].' '.$row['lastname'].'</td>';
												echo '<td>'.$row['firstname_by'].' '.$row['lastname_by'].'</td>';

												echo '<td>'.$row['request_date'].' '.$row['request_time'].'</td>';
												// echo '<td>'.$row['request_location'].'</td>';
												echo '<td>';
												echo $row['request_status'].'&nbsp;&nbsp;&nbsp;';
												// echo '
												// 	<form method="post" name="pending" action="">
												// 		<input type="hidden" name="talent_request_id" value="'.$row['talent_request_id'].'"/>
												// 		<button type="submit" name="pending_submit" class="btn btn-success btn-xs">Click To Approve</button>
												// 	</form>';

												// echo '<a href="talent-request-list.php?talent_request_id='.$row['talent_request_id'].'" class="btn btn-success btn-xs">Click To Approve</a>';
												echo '</td>';
												echo '<td><a class="btn btn-theme btn-view-request btn-sm" data-id="'.$row['talent_request_id'].'">View</a></td>';
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


<div class="modal fade" id="requestModal" role="dialog">
    <div class="modal-dialog">
    	<form role="form" id="requestModalForm" method="post" action="">
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
	                
	            </div>
	            
	            <!-- Modal Footer -->
	            <div class="modal-footer">
	            	<input type="hidden" name="casting_id" id="casting_id" value="" />
	                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	            </div>
	        </div>
        </form>
    </div>
</div>

<?php include('footer_js.php'); ?>
<script src="../dashboard/assets/DataTables/datatables.min.js"></script> 
<script src="../dashboard/assets/DataTables/dataTables.bootstrap.min.js"></script> 

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
			    'targets': [3], /* column index */
			    'orderable': false, /* true or false */
			}]
	    });

	    $('.pending_datatable').DataTable({
	        "order": [[ 0, "desc" ]],
	        'columnDefs': [{
			    'targets': [3], /* column index */
			    'orderable': false, /* true or false */
			}]
	    });

	    // $('.btn-request').click(function(e){
	    $("table").on("click", ".btn-view-request", function(e){	
	    	e.preventDefault();

		   var talent_request_id = $(this).attr('data-id');
		   // AJAX request
		   $.ajax({
			    url: '../ajax/dashboard_request.php',
			    type: 'post',
			    data: {name:'get_talent_request',talent_request_id: talent_request_id},
			    dataType: 'json',
			    success: function(res){ 
			    	// console.log(res);
			    	// return false;

			    	html = '';
			    	html += 'Request To(Talent) : '+ res.firstname +' '+res.lastname+'<br/>';
			    	html += 'Request From : '+ res.sender_fname +' '+res.sender_lname+'<br/>';
			    	html += 'Date : '+ res.request_date +'<br/>';
			    	html += 'Time : '+ res.request_time +'<br/>';
			    	html += 'Location : '+ res.request_location +'<br/>';
			    	html += 'Description : '+ res.request_description +'<br/>';
			    	html += 'Instruction : '+ res.request_instruction;

			    	$('#requestModal .modal-body').html(html);
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
				// lastname: "Please enter your lastname",
			},
			errorElement: "em",
			errorPlacement: function ( error, element ) {
				// Add the `help-block` class to the error element
				error.addClass( "help-block" );

				if ( element.prop( "type" ) === "checkbox" ) {
					error.insertAfter( element.parent( "label" ) );
				} else {
					error.insertAfter( element );
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
</script>

<?php include('footer.php'); ?>