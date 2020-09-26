<?php 
$page = "service_provider_form";
$page_selected = "service_provider_update";
include('header.php');
include('../includes/agency_dash_functions.php');

function deleteold_image($id){
	$old_file = '../uploads/featured_photo/'. mysql_result(mysql_query("SELECT featured_photo FROM agency_service_provider WHERE service_provider_id=".$id.""), 0, 'featured_photo');
	unlink($old_file);
	mysql_query("UPDATE agency_service_provider SET featured_photo='' WHERE service_provider_id=".$id."");
}


$allowedExtensions = array("jpg","jpeg","png");
$service_categories = get_service_category();

$service_provider = array();
if(isset($_GET['service_provider_id'])){
	$sql_select = "SELECT * FROM agency_service_provider WHERE service_provider_id=".$_GET['service_provider_id']."";
	$result = mysql_query($sql_select);
	if($row = sql_fetchrow($result)) {
		$service_provider = $row;
	}
}

if(isset($_POST) && !empty($_POST)){
	// echo "<pre>";
	// print_r($_POST);
	// echo "</pre>";

	$featured_photo = "";
	if(isset($_POST['featured_photo_del'])){
		deleteold_image($_POST['service_provider_id']);
	}else{
		$featured_photo = $_POST['featured_photo_old'];
	}

	if(isset($_FILES) && $_FILES['featured_photo']['name'] != ""){
		deleteold_image($_POST['service_provider_id']);
		$file_ext = end(explode(".", strtolower($_FILES['featured_photo']['name'])));
		if (in_array($file_ext, $allowedExtensions)) {

			$filename = time().'_'.preg_replace("/[^a-zA-Z0-9.]/", "", $_FILES['featured_photo']['name']);
			$folder = '../uploads/featured_photo';

			$newfile = $folder . '/' . $filename;
			if (move_uploaded_file($_FILES['featured_photo']['tmp_name'], "$newfile")) {
				$featured_photo = $filename;
			}
		}
	}

	$service_category = implode(',',$_POST['service_category']);
	$sql_ins = "UPDATE agency_service_provider 
				SET 
				name = '".$_POST['name']."',
				website = '".$_POST['website']."',
				phone = '".$_POST['phone']."',
				email = '".$_POST['email']."',
				featured_photo = '".$featured_photo."',
				service_category = '".$service_category."',
				description_of_service = '".$_POST['description_of_service']."',
				status = '".$_POST['status']."',
				updated_at = '".date('Y-m-d h:i:s')."'
				WHERE  
				service_provider_id = ".$_POST['service_provider_id']."
			";
	if(mysql_query($sql_ins)){
		header("Location: service-provider-list.php");
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


			<form enctype="multipart/form-data" action="" method="post" name="article" id="service-provider-form" class="">
				<div class="row">
					<div class="col-sm-12">
						<h3>Service Provider </h3>
						
						<div class="row">
							<div class="col-sm-6">
								<div class="box box-theme">
									<div class="box-header with-border">
						            	<h3 class="box-title">Add New Service Provider</h3>
						            </div>
						            <div class="box-body">
						            	<div class="form-group">
						                  	<label class="control-label text-right">Name *</label>
						                  	<input type="text" class="form-control" name="name" id="name" placeholder="Enter Name" value="<?php echo $service_provider['name']; ?>">
						                </div>

						                <div class="form-group">
						                  	<label class="control-label text-right">Website </label>
						                  	<input type="text" class="form-control" name="website" id="website" placeholder="Enter Website URL" value="<?php echo $service_provider['website']; ?>">
						                </div>

						                <div class="form-group">
						                  	<label class="control-label text-right">Phone *</label>
						                  	<input type="text" class="form-control" name="phone" id="phone" placeholder="Enter Phone Number" value="<?php echo $service_provider['phone']; ?>">
						                </div>

						                <div class="form-group">
						                  	<label class="control-label text-right">Email *</label>
						                  	<input type="text" class="form-control" name="email" id="email" placeholder="Enter Email" value="<?php echo $service_provider['email']; ?>">
						                </div>

							            <div class="form-group">
							            	<label class="control-label text-right">Feature Photo </label>
						                  	<input type="file" class="form-control" name="featured_photo" id="featured_photo" placeholder="Upload Image">
						                  	<input type="hidden" class="form-control" name="featured_photo_old" id="featured_photo_old" value="<?php echo $service_provider['featured_photo']; ?>">

						                  	<?php if(!empty($service_provider['featured_photo'])){ ?>
												<a href="<?php echo '../uploads/featured_photo/' . $service_provider['featured_photo']; ?>" target="_blank">view featured Photo</a>&nbsp;&nbsp;&nbsp;&nbsp;
												<label><input type="checkbox" name="featured_photo_del"> check to delete</label>
											<?php } ?>
						                </div>

						                <div class="form-group">
						                  	<label class="control-label text-right">Service Category *</label>
						                  	<?php $service_category_old = explode(',',$service_provider['service_category']); ?>
						                  	<select class="form-control" name="service_category[]" id="service_category" multiple="">
						                  		<option value="">Select</option>
						                  		<?php foreach($service_categories as $val){ ?>
						                  			<option value="<?php echo $val['service_category_name']; ?>" <?php if(in_array($val['service_category_name'],$service_category_old)){ echo "selected"; } ?>><?php echo $val['service_category_name']; ?></option>
						                  		<?php } ?>
						                  	</select>
						                </div>

						                <div class="form-group">
						                  	<label class="control-label text-right">Description Of Service</label>
						                  	<textarea id="description_of_service" name="description_of_service" class="form-control"><?php echo $service_provider['description_of_service']; ?></textarea>
						                </div>

						                <div class="form-group">
						                  	<label class="control-label text-right">Status *</label>
					                  		<select class="form-control" name="status" id="status">
					                  			<option value="">Select</option>
					                  			<option value="pending" <?php if($service_provider['status'] == "pending"){ echo "selected"; } ?>>Pending</option>
					                  			<option value="approved" <?php if($service_provider['status'] == "approved"){ echo "selected"; } ?>>Approved</option>
					                  		</select>
						                </div>
						            </div>

						            <div class="box-footer">
										<input type="hidden" name="MAX_FILE_SIZE" value="1000000" />
										<div class="text-right">
											<input type="hidden" name="service_provider_id" value="<?php echo $service_provider['service_provider_id']; ?>" />
											<a type="button" href="service-provider-list.php" class="btn btn-default">Cancel</a>
											<!-- <input type="button" value="Submit" name="submit" class="btn btn-theme btn-submit"/> -->
											<button type="button" class="btn btn-theme btn-submit">Submit</button>
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
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

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

	 	$('#service_category').select2();
	    var validator = $("#service-provider-form").validate({
			rules: {
				name: "required",
				phone: {required: true},
				email: {required: true,email: true},
				'service_category[]': "required",
				status: "required"
			},
			messages: {
				// lastname: "Please enter your lastname",
			},
			errorElement: "em",
			errorPlacement: function ( error, element ) {
				// Add the `help-block` class to the error element
				error.addClass( "help-block" );

				if ( element.prop( "type" ) === "checkbox" ) {
					error.insertAfter(element.parent("label"));

				} else if(element.prop("id") === "service_category"){
					error.insertAfter(element.siblings(".select2"));
				// } else if(element.prop("id") === "article_content"){
				// 	error.insertAfter(element.siblings(".cke"));
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
			$("#service-provider-form").submit();
		});

	// });
</script>
<?php include('footer.php'); ?>