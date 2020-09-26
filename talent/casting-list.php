<?php
	$page = "casting_call_list";
	$page_selected = "casting_calls";
	include('header.php');
	include('../forms/definitions.php');
  	include('../includes/agency_dash_functions.php');

	$notification = array();

	if(isset($_POST['filter']) && $_POST['filter'] = "filter"){
		// echo "<pre>";
		// print_r($_POST);
		// echo "</pre>";
		$_SESSION['filter']['casting']['filter_gender'] = $_POST['filter_gender'];
		$_SESSION['filter']['casting']['filter_height'] = $_POST['filter_height'];
		$_SESSION['filter']['casting']['filter_age'] = $_POST['filter_age'];
		$_SESSION['filter']['casting']['filter_location'] = $_POST['filter_location'];
		$_SESSION['filter']['casting']['filter_ethnicity'] = $_POST['filter_ethnicity'];
		$_SESSION['filter']['casting']['filter_job_type'] = $_POST['filter_job_type'];
		$_SESSION['filter']['casting']['filter_union_status'] = $_POST['filter_union_status'];
	}

	if(isset($_POST['clear']) && $_POST['clear'] = "clear"){
		// echo "<pre>";
		// print_r($_POST);
		// echo "</pre>";
		unset($_SESSION['filter']['casting']);
	}


	if(isset($_POST['requestSend']) && $_POST['requestSend'] != ""){

		// while ($row = mysql_fetch_array($reminder_res, mysql_ASSOC)) {
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
				<div class="col-md-6">

					<div class="box box-theme">
						<form enctype="multipart/form-data" action="" method="post" name="article">
							<div class="box-header with-border">
				            	<h3 class="box-title">Filter</h3>
				            </div>
				            <div class="box-body">
			            		<div class="row">

				            		<div class="col-sm-4">
										<div class="form-group">
											<label class="control-label">Gender</label>
						                  	<select class="form-control" name="filter_gender" id="filter_gender">
						                  		<option value="">Select</option>
						                  		<option value="M" <?php if(isset($_SESSION['filter']['casting']['filter_gender']) && $_SESSION['filter']['casting']['filter_gender'] == "M"){ echo "selected"; } ?>>Male</option>
						                  		<option value="F" <?php if(isset($_SESSION['filter']['casting']['filter_gender']) && $_SESSION['filter']['casting']['filter_gender'] == "F"){ echo "selected"; } ?>>Female</option>
						                  		<option value="Transgender" <?php if(isset($_SESSION['filter']['casting']['filter_gender']) && $_SESSION['filter']['casting']['filter_gender'] == "Transgender"){ echo "selected"; } ?>>Transgender</option>
						                  	</select>
						                </div>
					                </div>

					                <div class="col-sm-4">
										<div class="form-group">
						                  	<label class="control-label">Height</label>
						                  	<select class="form-control" name="filter_height" id="filter_height">
						                  		<option value="">Select</option>
						                  		<?php for($height=0; $height <= 100; $height++){ ?>
						                  			<option value="<?php echo $height; ?>" <?php if(isset($_SESSION['filter']['casting']['filter_height']) && $_SESSION['filter']['casting']['filter_height'] == $height){ echo "selected"; } ?>><?php echo $height; ?></option>
						                  		<?php } ?>
						                  	</select>
						                </div>
					                </div>

					                <div class="col-sm-4">
										<div class="form-group">
						                  	<label class="control-label">Age</label>
						                  	<select class="form-control" name="filter_age" id="filter_age">
						                  		<option value="">Select</option>
						                  		<?php for($age=0; $age <= 100; $age++){ ?>
						                  			<option value="<?php echo $age; ?>" <?php if(isset($_SESSION['filter']['casting']['filter_age']) && $_SESSION['filter']['casting']['filter_age'] == $age){ echo "selected"; } ?>><?php echo $age; ?></option>
						                  		<?php } ?>
						                  	</select>
						                </div>
					                </div>

					                <div class="col-sm-4">
					                	<div class="form-group">
											<label class="control-label">Ethnicity</label>
						                  	<select class="form-control" name="filter_ethnicity" id="filter_ethnicity">
						                  		<option value="">Select</option>
						                  		<?php foreach ($ethnicityarray as $key => $val) { ?>
						                  			<option value="<?php echo $val; ?>" <?php if(isset($_SESSION['filter']['casting']['filter_ethnicity']) && $_SESSION['filter']['casting']['filter_ethnicity'] == $val){ echo "selected"; } ?>><?php echo $val; ?></option>
						                  		<?php } ?>
						                  	</select>
						                </div>
					                </div>

					                <div class="col-sm-4">
					                	<div class="form-group">
											<label class="control-label">Job Type</label>
						                  	<select class="form-control" name="filter_job_type" id="filter_job_type">
						                  		<option value="">Select</option>
						                  		<?php foreach ($jobtypearray as $key => $val) { ?>
						                  			<option value="<?php echo $val; ?>" <?php if(isset($_SESSION['filter']['casting']['filter_job_type']) && $_SESSION['filter']['casting']['filter_job_type'] == $val){ echo "selected"; } ?>><?php echo $val; ?></option>
						                  		<?php } ?>
						                  	</select>
					                    </div>
					                </div>

					                <div class="col-sm-4">
					                	<div class="form-group">
											<label class="control-label">Union Status</label>
						                  	<select class="form-control" name="filter_union_status" id="filter_union_status">
						                  		<option value="">Select</option>
						                  		<?php foreach ($jobunionarray as $key => $val) { ?>
						                  			<option value="<?php echo $val; ?>" <?php if(isset($_SESSION['filter']['casting']['filter_union_status']) && $_SESSION['filter']['casting']['filter_union_status'] == $val){ echo "selected"; } ?>><?php echo $val; ?></option>
						                  		<?php } ?>
						                  	</select>
					                    </div>
					                </div>

					                <div class="col-sm-4">
					                	<div class="form-group">
											<label class="control-label">Location</label>
						                  	<select class="form-control" name="filter_location" id="filter_location">
						                  		<option value="">Select</option>
						                  		<?php foreach ($locationarray as $key => $val) { ?>
						                  			<option value="<?php echo $val; ?>" <?php if(isset($_SESSION['filter']['casting']['filter_location']) && $_SESSION['filter']['casting']['filter_location'] == $val){ echo "selected"; } ?>><?php echo $val; ?></option>
						                  		<?php } ?>
						                  	</select>
						               	</div>
					                </div>

				                </div>
							</div>
							<div class="box-footer text-right">
								<input type="submit" class="btn btn-flat btn-default" name="clear" value="clear" />
							    <input type="submit" class="btn btn-flat btn-theme" name="filter" value="filter"/>
							</div>
						</form>
					</div>

					<div class="box box-theme">
						<div class="box-body">
							<table class="datatable table table-responsive table-striped">
								<thead>
									<tr>
										<th>Id</th>
										<th>Project</th>
										<th>Director</th>
										<th></th>
									</tr>
								</thead>
								<tbody>
									<?php
										$cond = "";

										if(isset($_SESSION['filter']['casting']['filter_gender']) && $_SESSION['filter']['casting']['filter_gender'] != ""){
											$cond .= " AND acrv.var_type = 'gender' AND acrv.var_value = '".$_SESSION['filter']['casting']['filter_gender']."' ";
										} 
										if(isset($_SESSION['filter']['casting']['filter_height']) && $_SESSION['filter']['casting']['filter_height'] != ""){
											$cond .= " AND acr.height_lower <= '".$_SESSION['filter']['casting']['filter_height']."' AND acr.height_upper >= '".$_SESSION['filter']['casting']['filter_height']."' ";
										}
										if(isset($_SESSION['filter']['casting']['filter_age']) && $_SESSION['filter']['casting']['filter_age'] != ""){
											$cond .= " AND acr.age_lower <= '".$_SESSION['filter']['casting']['filter_age']."' AND acr.age_upper >= '".$_SESSION['filter']['casting']['filter_age']."' ";
										}
										if(isset($_SESSION['filter']['casting']['filter_location']) && $_SESSION['filter']['casting']['filter_location'] != ""){
											$cond .= " AND ac.location_casting = '".$_SESSION['filter']['casting']['filter_location']."' ";
										}
										if(isset($_SESSION['filter']['casting']['filter_ethnicity']) && $_SESSION['filter']['casting']['filter_ethnicity'] != ""){
											$cond .= " AND acrv.var_type = 'ethnicity' AND acrv.var_value = '".$_SESSION['filter']['casting']['filter_ethnicity']."' ";
										}
										if(isset($_SESSION['filter']['casting']['filter_job_type']) && $_SESSION['filter']['casting']['filter_job_type'] != ""){
											$cond .= " AND acj.jobtype = '".$_SESSION['filter']['casting']['filter_job_type']."' ";
										}
										if(isset($_SESSION['filter']['casting']['filter_union_status']) && $_SESSION['filter']['casting']['filter_union_status'] != ""){
											$cond .= " AND acu.union_name = '".$_SESSION['filter']['casting']['filter_union_status']."' ";
										}

										$sql_list = "select ac.*,ap.firstname,ap.lastname from agency_castings ac 
												LEFT JOIN agency_profiles ap ON ac.casting_director = ap.user_id
												LEFT JOIN agency_castings_unions acu ON ac.casting_id = acu.casting_id
												LEFT JOIN agency_castings_jobtype acj ON ac.casting_id = acj.casting_id
												LEFT JOIN agency_castings_roles acr ON ac.casting_id = acr.casting_id
												LEFT JOIN agency_castings_roles_vars acrv ON acr.role_id = acrv.role_id
												WHERE live = 1 AND deleted = 0 AND casting_date >= CURDATE() ".$cond." 
												GROUP BY ac.casting_id";

										$result = mysql_query($sql_list);
										if (mysql_num_rows($result) > 0) {
											while ($row = mysql_fetch_assoc($result)) {
												echo '<tr>';
												echo '<td>'.$row['casting_id'].'</td>';
												echo '<td>'.$row['job_title'].'</td>';
												echo '<td>'.$row['firstname'].' '.$row['lastname'].'</td>';
												echo '<td>';
												// echo '<a href="casting-update.php?casting_id='.$row['casting_id'].'" class="btn btn-primary">Edit</a> ';
												// echo '<a href="" class="btn btn-info btn-request" data-id="'.$row['casting_id'].'"><i class="fa fa-paper-plane"></i> Send Booking Request </a>&nbsp;';
												echo '<a href="casting-view.php?casting_id='.$row['casting_id'].'" class="btn btn-theme btn-flat" data-id="'.$row['casting_id'].'"> View </a>';
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
</script>
<?php include('footer.php'); ?>