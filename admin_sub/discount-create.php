<?php 
$page = "discount_form";
$page_selected = "discount";
include('header.php');
include('../includes/agency_dash_functions.php');


if(isset($_POST) && !empty($_POST)){
	// echo "<pre>";
	// print_r($_POST);
	// echo "</pre>";
	$weeks = trim($_POST['weeks']);
	$days = trim($_POST['days']);
	$total_days = (($weeks)*7)+$days;

	$sql_ins = "INSERT INTO agency_discounts 
				SET 
				code = '".trim($_POST['code'])."',
				weeks = '".$weeks."',
				days = '".$days."',
				total_days = '".$total_days."',
				max_use = '".$_POST['max_use']."',
				percentage = '".$_POST['percentage']."',
				status = '".$_POST['status']."'
			";
	if(mysql_query($sql_ins)){
		header("Location: discount-list.php");
	}
}

?>

<div id="page-wrapper">
    <div class="" id="main">

    		<?php //if(isset($notification['success'])){ ?>
		        <div class="alert alert-success" role="alert" id="alert-success-form" style="display: none;">
		            <?php //echo $notification['success']; ?>
		        </div>
	        <?php //} ?>
	        <?php //if(isset($notification['error'])){ ?>
	            <div class="alert alert-danger" role="alert" id="alert-danger-form" style="display: none;">
	                <?php //echo $notification['error']; ?>
	            </div>
	        <?php //} ?>


			<form enctype="multipart/form-data" action="" method="post" name="article" id="discount-form" class="">
				<div class="row">
					<div class="col-sm-12">
						<h3>Discount </h3>
						
						<div class="row">
							<div class="col-sm-6">
								<div class="box box-theme">
						            <div class="box-body">
						            	<div class="form-group">
						                  	<label class="control-label text-right">Code *</label>
						                  	<input type="text" class="form-control" name="code" id="code" placeholder="Enter Code">
						                </div>

						                <div class="form-group">
						                  	<label class="control-label text-right">Weeks </label>
						                  	<input type="text" class="form-control" name="weeks" id="weeks" placeholder="Enter Weeks">
						                </div>

						                <div class="form-group">
						                  	<label class="control-label text-right">Days </label>
						                  	<input type="text" class="form-control" name="days" id="days" placeholder="Enter Days">
						                </div>

						                <div class="form-group">
						                  	<label class="control-label text-right">Maximum Number Of Usage *</label>
						                  	<input type="text" class="form-control" name="max_use" id="max_use" placeholder="Enter Maximum Number Of Usage">
						                </div>

						                <div class="form-group">
						                  	<label class="control-label text-right">% off Membership </label>
						                  	<input type="number" class="form-control" name="percentage" id="percentage" placeholder="Enter % off Membership" min="0" max="100">
						                </div>

						                <div class="form-group">
						                  	<label class="control-label text-right">Status *</label>
					                  		<select class="form-control" name="status" id="status">
					                  			<option value="">Select</option>
					                  			<option value="Enable">Enable</option>
					                  			<option value="Disable">Disable</option>
					                  		</select>
						                </div>
						            </div>

						            <div class="box-footer">
										<input type="hidden" name="MAX_FILE_SIZE" value="1000000" />
										<div class="text-right">
											<a type="button" href="discount-list.php" class="btn btn-default btn-flat">Cancel</a> &nbsp;
											<!-- <input type="button" value="Submit" name="submit" class="btn btn-theme btn-submit"/> -->
											<button type="button" class="btn btn-theme btn-flat btn-submit">Submit</button>
										</div>
						            </div>
								</div>
							</div>
						</div>

					</div>
				</div>
			</form>
			
	</div>
</div>
<?php include('footer_js.php'); ?>

<!-- <script type="text/javascript" src="http://code.jquery.com/ui/1.11.0/jquery-ui.min.js"></script>
<script type="text/javascript" src="../dashboard/assets/jQuery-DateTimepicker-Addon/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript" src="../dashboard/assets/jQuery-DateTimepicker-Addon/i18n/jquery-ui-timepicker-addon-i18n.min.js"></script>
<script type="text/javascript" src="../dashboard/assets/jQuery-DateTimepicker-Addon/jquery-ui-sliderAccess.js"></script> -->
<!-- <script type="text/javascript" src="../dashboard/assets/ckeditor/ckeditor.js"></script> -->

<!-- <script type="text/javascript" src="../dashboard/assets/bootstrap-tagsinput-latest/dist/bootstrap-tagsinput.min.js"></script> -->
<script type="text/javascript" src="../dashboard/assets/js/jquery.validate.js"></script>
<!-- <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script> -->

<script type="text/javascript">

	// $(document).ready( function () {

		// $('#tags').tagsinput({
		// 	tagClass: 'big'
		// });

		// CKEDITOR.replace('content');

		// $('#publish_date').datepicker({
	 //    	changeMonth: true,
	 //    	changeYear: true,
	 //    	minDate: 0,
	 //    });

	 	// $('#service_category').select2();

	 	jQuery.validator.addMethod("noSpace", function(value, element) {
		  	// return value.indexOf(" ") < 0 && value != ""; 
		  	return value.indexOf(" ") < 0; 
		}, "No space allowed");

	    validator = $("#discount-form").validate({
			rules: {
				code: {
					required: true,
					noSpace: true,
					remote: {
				        url: "../ajax/dashboard_request.php",
				        type: "post",
				        data: {
					        name:'dicount_code_unique_insert'
					    }
				    }
				},
				// weeks: {required: true,digits: true},
				// days: {required: true,digits: true},
				max_use: {required: true,digits: true},
				// percentage: {required: true,digits: true},
				status: "required"
			},
			messages: {
				// lastname: "Please enter your lastname",
				code: {
					remote: "Discount code already exist"
				},
			},
			errorElement: "em",
			errorPlacement: function ( error, element ) {
				// Add the `help-block` class to the error element
				error.addClass( "help-block" );

				if ( element.prop( "type" ) === "checkbox" ) {
					error.insertAfter(element.parent("label"));

				} else {
					error.insertAfter( element );
				}
			},
			highlight: function ( element, errorClass, validClass ) {
				$( element ).parents(".col-sm-5").addClass( "has-error" ).removeClass( "has-success" );
			},
			unhighlight: function (element, errorClass, validClass) {
				$( element ).parents(".col-sm-5").addClass( "has-success" ).removeClass( "has-error" );
			}

			// submitHandler: function (){
			// 	alert( "submitted!" );
			// }
		});



		$(".btn-submit").click(function(e){

			// var editor = CKEDITOR.instances['content'];  
			// var ckValue = editor.getData().replace(/<[^>]*>/gi, '').trim();
			// if(ckValue == 0){
			// 	alert("Please Enter Content..!");
			// 	return false;
			// }

			// console.log($('#article_content').attr('id'));
			$("#discount-form").submit();
		});

	// });
</script>
<?php include('footer.php'); ?>