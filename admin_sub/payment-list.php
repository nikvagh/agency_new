<?php
	$page = "payment-list";
	$page_selected = "payments";
	include('header.php');
	include('../forms/definitions.php');
  	include('../includes/agency_dash_functions.php');
	$notification = array();
?>

<div id="page-wrapper">
	<!-- <div class="container-fluid"> -->
    	<div class="" id="main">

    		<h3>Payments </h3>

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
						<!-- <div class="box-header">
							<a href="casting-update.php" class="btn btn-primary pull-right">Create Casting </a>
						</div> -->
						<div class="box-body">
							<table align="center" class="datatable table table-responsive table-striped">
								<thead>
									<tr>
										<th>Id</th>
										<th>User Id</th>
										<th>Email</th>
										<th>Name</th>
										<th>Name On CC</th>
										<th>User Name</th>
									</tr>
								</thead>
								<tbody>
									<?php
										// $result = mysql_query("select * from agency_castings where posted_by = " . $_SESSION['user_id'] . "");
										// $result = mysql_query("select ac.*,ap.firstname,ap.lastname from agency_castings ac 
										// 		LEFT JOIN agency_profiles ap ON ac.casting_director = ap.user_id
										// 	");
										// if (mysql_num_rows($result) > 0) {
										// 	while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
										// 		echo '<tr>';
										// 		echo '<td>'.$row['casting_id'].'</td>';
										// 		echo '<td>'.$row['job_title'].'</td>';
										// 		echo '<td>'.$row['firstname'].' '.$row['lastname'].'</td>';
										// 		echo '<td>';
										// 		echo '<a href="casting-update.php?casting_id='.$row['casting_id'].'" class="btn btn-primary">Edit</a> ';
										// 		// echo '<a href="" class="btn btn-info btn-request" data-id="'.$row['casting_id'].'"><i class="fa fa-paper-plane"></i> Send Booking Request </a>&nbsp;';
										// 		echo '<a href="casting-view.php?casting_id='.$row['casting_id'].'" class="btn btn-info" data-id="'.$row['casting_id'].'"><i class="fa fa-eye"></i> View </a>';
										// 		echo '</td>';
										// 		echo '</tr>';
										// 	}
										// }
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

<!-- <script type="text/javascript" src="http://code.jquery.com/jquery-1.11.1.min.js"></script> -->
<script type="text/javascript" src="http://code.jquery.com/ui/1.11.0/jquery-ui.min.js"></script>
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