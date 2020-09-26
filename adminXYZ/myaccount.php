<?php
$page = "edit_profile";
$page_selected = "edit_profile";

include('header.php');
include('../includes/agency_dash_functions.php');

use \Gumlet\ImageResize;
use \Gumlet\ImageResizeException;
include('../ImageResize/ImageResize.php');

unset($loggedin); // avoid XSS
if (!empty($_SESSION['user_id'])) { // check if user is logged in
   $loggedin = $_SESSION['user_id'];
} else { // if not logged in, redirect to login page
  $url = 'login.php';
  ob_end_clean(); // Delete the buffer.
  header("Location: $url");
  exit(); // Quit the script.
}
$userid = $loggedin;
$profileid = $loggedin;

// echo "<pre>";
// print_r($_POST);
// echo "</pre>";

$folder = '../uploads/users/' . $userid . '/profile_pic/';
$folder_thumb = $folder . 'thumb/';
$allowed = array ('image/gif', 'image/jpeg', 'image/jpg', 'image/pjpeg', 'image/JPG');

function dlt_profile_pic($id,$folder,$profile_pic_thumb){
	$old_file = mysql_result(mysql_query("SELECT user_avatar FROM forum_users WHERE user_id =".$id.""), 0, 'user_avatar');
	$old_file_link = $folder. $old_file;
	if(unlink($old_file_link)){
		foreach($profile_pic_thumb as $height=>$width){
			unlink($folder.'thumb/'.$height.'x'.$width.'_'. $old_file);
		}
	}
	mysql_query("UPDATE forum_users SET user_avatar='' WHERE user_id=".$id."");
}


if(isset($_POST['profile_pic_del'])){
	dlt_profile_pic($userid,$folder,$profile_pic_thumb);
}

if (isset($_POST['submit']) && $_POST['submit'] == "Save Changes") {
	// echo "<pre>";
	// print_r($_POST);
	// echo "</pre>";

	$filename_db = $_POST['avatarfile_old'];
	if (isset($_FILES['avatarfile']) && $_FILES['avatarfile']['name'] != "") { // Handle the form.
		// process for sizing

		// echo "<pre>";
		// print_r($_FILES);
		// echo "</pre>";
		
		if (in_array($_FILES['avatarfile']['type'], $allowed)) {

			if(!is_dir($folder)) {
				mkdir($folder, 0777, true);
			}

			if(!is_dir($folder_thumb)) {
				mkdir($folder_thumb, 0777, true);
			}

			dlt_profile_pic($userid,$folder,$profile_pic_thumb);

			// Move the file over.
			$filename = filename_new($_FILES['avatarfile']['name']);
			$destination = $folder.$filename;
			if (move_uploaded_file($_FILES['avatarfile']['tmp_name'], "$destination")) {
				foreach($profile_pic_thumb as $height=>$width){
					$image = new ImageResize($destination);
	                $image->resizeToHeight($height);
	                $image->save($folder_thumb.$height.'x'.$width.'_'. $filename);
	            }
	            $filename_db = $filename;
			}

		} else { // Invalid type.
			$notification['error'] = "Something Wrong With Profile Picture.";
		}
	}

	// =============


	if($_POST['country'] == 'United States'){
		$state = $_POST['state_hidden'];
	}else{
		$state = $_POST['state_hidden'];
	}

	$sql_profile = "UPDATE agency_profiles 
			SET 
			firstname = '".$_POST['firstname']."',
			lastname = '".$_POST['lastname']."',
			phone = '".$_POST['phone']."',
			country = '".$_POST['country']."',
			state = '".$state."',
			city = '".$_POST['city']."'
			WHERE  
			user_id = ".$_POST['user_id']."
		";
	if(mysql_query($sql_profile)){
		$sql_forum = "UPDATE forum_users 
			SET 
			user_email = '".$_POST['email']."',
			user_avatar = '".$filename_db."'
			WHERE  
			user_id = ".$_POST['user_id']."
		";
		if(mysql_query($sql_forum)){
			$notification['success'] = "Profile Details Updated Successfully.";
		}
	}
}

$userInfo = get_user_byId($userid);
?>

				<div id="page-wrapper">
			    	<div class="" id="main">
			    		<h3>Profile</h3>

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
			    			<div class="col-md-6">

								<form action="" method="post" name="profile-editForm" id="profile-editForm" enctype="multipart/form-data">
									<div class="box box-theme">
				    					<div class="box-header with-border">
							                <h3 class="box-title">Profile Options</h3>
							            </div>

					                	<div class="box-body">
					                		<div class="form-group">
						                        <label>First Name *</label>
						                        <input type="text" class="form-control" id="firstname" name="firstname" placeholder="Enter First Name" value="<?php echo $userInfo['firstname']; ?>"/>
						                    </div>

						                    <div class="form-group">
						                        <label>Last Name *</label>
						                        <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Enter Last Name" value="<?php echo $userInfo['lastname']; ?>"/>
						                    </div>

						                    <div class="form-group">
						                        <label>Email *</label>
						                        <input type="text" class="form-control" id="email" name="email" placeholder="Enter Email" value="<?php echo $userInfo['user_email']; ?>"/>
						                    </div>

						                    <div class="form-group">
						                        <label>Phone Number *</label>
						                        <input type="text" class="form-control" id="phone" name="phone" placeholder="Enter Phone" value="<?php echo $userInfo['phone']; ?>"/>
						                    </div>

						                    <div class="form-group">
						                    	<label>Country *</label>
							                    <select name="country" id="country" class="form-control">
							                    	<option value=""> -- Select -- </option>
													<?php foreach($countryarray as $key=>$val) { ?>
														<option value="<?php echo $val; ?>" <?php if(isset($userInfo['country'])) { if($userInfo['country'] == $val) { echo 'selected'; } } ?>>
															<?php echo $val; ?>
														</option>
													<?php } ?>
												</select>
											</div>

											<?php 
												// $showstates = 'N';
												// if(isset($country)) {
												// 	if($country == 'United States') {
												// 		$showstates = 'N';
												// 	}
												// }
											?>

											<?php //if($showstates == "Y") { ?> 
												<div class="form-group state_select_box">
													<label>State *</label>
								                    <select name="state" id="state_select" class="form-control">
								                    	<option value=""> -- Select -- </option>
														<?php foreach($stateList['US'] as $key=>$val) { ?>
															<option value="<?php echo $val; ?>" <?php if(isset($userInfo['state'])) { if($userInfo['state'] == $val) { echo 'selected'; } } ?>>
																<?php echo $val; ?>
															</option>
														<?php } ?>
													</select>
												</div>
											<?php //}else{ ?>
												<div class="form-group state_text_box">
													<label>State *</label>
													<input type="text" class="form-control" name="state" id="state_text" value="<?php echo $userInfo['state']; ?>" />
												</div>
											<?php //} ?>

						                    <div class="form-group">
						                        <label>City *</label>
						                        <input type="text" class="form-control" id="city" name="city" placeholder="Enter City" value="<?php echo $userInfo['city']; ?>"/>
						                    </div>

						                    <div class="form-group">
						                    	<label>Profile Image</label>
						                    	<input type="hidden" name="avatarfile_old" id="avatarfile_old" value="<?php echo $userInfo['user_avatar']; ?>"/>
							                    <input type="file" name="avatarfile" id="avatarfile" class="form-control"/>
							                    <?php if($userInfo['user_avatar'] != ""){ ?>
							                    	<a href="<?php echo '../uploads/users/' . $userInfo['user_id'] . '/profile_pic/' . $userInfo['user_avatar']; ?>" target="_blank">View Profile Image</a>&nbsp;&nbsp;&nbsp;&nbsp;
													<label><input type="checkbox" name="profile_pic_del"> check to delete</label>
							                    <?php } ?>
							                </div>

					                	</div>

					                	<div class="box-footer">
					                		<input type="hidden" name="state_hidden" id="state_hidden" value="" />
					                		<input type="hidden" name="user_id" id="user_id" value="<?php echo $userInfo['user_id']; ?>" />
										    <input type="submit" name="submit" value="Save Changes" class="btn btn-theme pull-right"/>
					                	</div>
					                </div>
								</form>

							</div>
						</div>

					</div>
				</div>



<?php include('footer_js.php'); ?>
<script src="../dashboard/assets/DataTables/datatables.min.js"></script> 
<script src="../dashboard/assets/DataTables/dataTables.bootstrap.min.js"></script> 

<script type="text/javascript" src="https://code.jquery.com/ui/1.11.0/jquery-ui.min.js"></script>
<!-- <script type="text/javascript" src="../dashboard/assets/jQuery-DateTimepicker-Addon/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript" src="../dashboard/assets/jQuery-DateTimepicker-Addon/i18n/jquery-ui-timepicker-addon-i18n.min.js"></script>
<script type="text/javascript" src="../dashboard/assets/jQuery-DateTimepicker-Addon/jquery-ui-sliderAccess.js"></script> -->

<script type="text/javascript" src="../dashboard/assets/js/jquery.validate.js"></script>

<script>

	country_state();
	$("#country").on('change',function () {
		country_state();
	});

	function country_state(){
		val = $("#country").val();
		// console.log(val);
		$(".state_select_box").hide();
		$(".state_text_box").hide();
		if(val == "United States"){
			$(".state_select_box").show();
		}else{
			$(".state_text_box").show();
		}

		// $("#state_select").val();
		// $("#state_text").val();
	}

	const dependsState = function(element){
		
	};

	jQuery.validator.addMethod("dependsState", function(value, element) {
	  	// return value.indexOf(" ") < 0 && value != ""; 
	  	// return value.indexOf(" ") < 0; 
	  	if($("#country").val() == "United States"){
			state = $("#state_select").val();
		}else{
			state = $("#state_text").val();
		}
		$("#state_hidden").val(state);
		return state != "";
	}, "State is required field.");


	$("#profile-editForm").validate({
		rules: {
			firstname: "required",
			lastname: "required",
			email: {
					required: true,
					email: true,
					remote: {
				        url: "../ajax/dashboard_request.php",
				        type: "post",
				        data: {
					        name:'user_email_unique_upadte',
					        user_id: $("#user_id").val()
					    }
				    }
			},
			state: {dependsState: true},
			phone: "required",
			country: "required",
			city: "required"
		},
		messages: {
			email: {
					remote: "Email already exist for other account."
			},
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
		// submitHandler: function (form){
		// 	if($('#state_text_select').is(':visible')){
		// 		val_new = $(this).val();
		// 		$("#state").val(val_new);
		// 		console.log('ggg');
		// 	}
		// 	form.submit();
		// }
	});


</script>

<script>
if (window.history.replaceState) {
  window.history.replaceState( null, null, window.location.href );
}
</script>
<?php include('footer.php'); ?>