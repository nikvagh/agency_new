<?php 
	$page = "talent_create";
	$page_selected = "talent_create";
	include('header.php');
	include('../includes/agency_dash_functions.php');

	$user_id = $_SESSION['user_id'];

	if(isset($_POST['ApplyNew'])){
		// echo "<pre>";
		// print_r($_POST);
		// echo "</pre>";

		$password = _hash($_POST['password']);
		$user_type = 1;
		$user_ip = getRealIpAddr();
		$user_regdate = time();

		$birthdate = date('Y-m-d',strtotime($_POST['birthdate']));
		$height_ft = $_POST['height_ft'];
		$height_inch = $_POST['height_inch'];
		$height = ($height_ft*12) + $height_inch;

		if($_POST['ethnicity'] != ""){
			$ethnicity = $_POST['ethnicity'];
		}else{
			$ethnicity = $_POST['ethnicity_other'];
		}

		$sql_talent_ins = "INSERT into forum_users 
							SET
							username = '".$_POST['username']."',
							username_clean = '".$_POST['username']."',
							user_email = '".$_POST['email']."',
							user_password = '".$password."',
							user_ip = '".$user_ip."',
							user_regdate = '".$user_regdate."'
						";
		if(mysql_query($sql_talent_ins)){

			$user_id_ins = mysql_insert_id();
			$sql_t_profile_ins = "INSERT into agency_profiles 
							SET
							user_id = '".$user_id_ins."',
							account_type = 'talent',
							firstname = '".$_POST['firstname']."',
							lastname = '".$_POST['lastname']."',
							phone = '".$_POST['phone']."',
							pay_term = '".$_POST['payment_term']."',
							roster_id = '".$user_id."',
							gender = '".$_POST['gender']."',
							birthdate = '".$birthdate."',
							weight = '".$_POST['weight']."',
							height_ft = '".$height_ft."',
							height_inch = '".$height_inch."',
							height = '".$height."',
							ethnicity = '".$ethnicity."',
							nationality = '".$_POST['nationality']."',
							hair_color = '".$_POST['hair_color']."',
							hair_length = '".$_POST['hair_length']."',
							eye_color = '".$_POST['eye_color']."',
							eye_shape = '".$_POST['eye_shape']."'
						";
			if(mysql_query($sql_t_profile_ins)){

				$payment_term = agency_payment_term_byId($_POST['payment_term']);
				// $amount = $payment_term['total_amount'] - ($payment_term['total_amount']*10)/100;
				$amount = $payment_term['total_amount'];
				$total_month = $payment_term['total_month'];

				$sql_payment_ins = "INSERT into agency_payment 
							SET
							user_id = '".$user_id_ins."',
							amount = '".$amount."',
							description = 'New Account (Talent Manager Roster)',
							status = 'success'
						";

				if(mysql_query($sql_payment_ins)){

					$next_payment_date = date('Y-m-d h:i:s', strtotime("+".$total_month." months"));
					$sql_profile_upadte = "UPDATE agency_profiles 
							SET
							next_payment_date = '".$next_payment_date."'
							WHERE
							user_id = '".$user_id_ins."'
						";

					if(mysql_query($sql_profile_upadte)){
						$notification['success'] = "User Created Successfully";
					}
					
				}
			}
		}
	}
?>

<div id="page-wrapper">
    <div class="" id="main">

		<?php if(isset($notification['success'])){ ?>
	        <div class="alert alert-success" role="alert" id="alert-success-form" style="">
	            <?php echo $notification['success']; ?>
	        </div>
        <?php } ?>
        <?php if(isset($notification['error'])){ ?>
            <div class="alert alert-danger" role="alert" id="alert-danger-form" style="">
                <?php echo $notification['error']; ?>
            </div>
        <?php } ?>

		<form enctype="multipart/form-data" action="" method="post" name="apply_Form" id="apply_Form" class="">
			<div class="row">
				<div class="col-sm-12">
					<h3>Talent </h3>
					
					<div class="row">
						<div class="col-sm-10">
							<div class="box box-theme">
								<div class="box-header with-border">
					            	<h3 class="box-title">Add New Talent To Roster</h3>
					            </div>
					            <div class="box-body">

					            	<div class="col-sm-6">
						            	<a class="text-theme"><h4>General Info</h4></a>
						            	<div class="form-group">
					                        <label>First Name</label>
					                        <input type="text" name="firstname" id="firstname" class="form-control">
					                    </div>
					                    <div class="form-group">
					                        <label>Last Name</label>
					                        <input type="text" name="lastname" id="lastname" class="form-control">
					                    </div>
					                    <div class="form-group">
					                        <label>Email</label>
					                        <input type="text" name="email" id="email" class="form-control">
					                    </div>
					                    <div class="form-group">
					                        <label>Phone</label>
					                        <input type="text" name="phone" id="phone" class="form-control">
					                    </div>

					                    <br/>
					                    <a class="text-theme"><h4>Account Info</h4></a>
					                	<div class="form-group">
					                        <label>User Name</label>
					                        <input type="text" name="username" id="username" class="form-control">
					                    </div>
					                    <div class="form-group">
					                        <label>Password</label>
					                        <input type="password" name="password" id="password" class="form-control">
					                    </div>
					                    <div class="form-group">
					                        <label>Password Confirm</label>
					                        <input type="password" name="confirm_password" id="confirm_password" class="form-control">
					                    </div>

					                    <br/>
					                    <a class="text-theme"><h4>Payment Info</h4></a>
					                	<div class="form-group">
					                        <label>Select Payment Term</label>
					                        <?php
					                        	$sql_terms = "SELECT * FROM agency_payment_term";
												$result_terms = mysql_query($sql_terms);
					                        ?>
					                        <?php while ($row = sql_fetchrow($result_terms)) { ?>
					                        	<br/>
					                        	<label>
					                        		<input type="radio" name="payment_term" id="" value="<?php echo $row['payment_term_id']; ?>"> <?php echo '$'.$row['per_month'].' per month'; ?>
					                        		<?php if($row['total_amount'] != $row['per_month']){ echo '($'.$row['total_amount'].' '.$row['term_title'].')'; } ?>
					                        	</label>
					                        <?php } ?>
					                        <span class="radio_err"></span>
					                    </div>
					                </div>



					                <div class="col-sm-6">
					                	<a class="text-theme"><h4>Physical Info</h4></a>

					                    <div class="form-group">
					              			<label>Gender <span class="text-danger">*</span></label>
					              			<br/>
					              			<label><input type="radio" name="gender" value="M" /> Male</label>
					              			<label><input type="radio" name="gender" value="F" /> Female</label>
					              			<label><input type="radio" name="gender" value="Transgender" /> Transgender</label>
					              			<span class="radio_err"></span>
										</div>

						            	<div class="form-group">
					              			<label>Birth Date</label>
					              			<input type="text" class="form-control" name="birthdate" id="birthdate" value="" autocomplete="off" />
										</div>

										<div class="form-group">
					              			<label>Weight (lbs)</label>
					              			<select name="weight" id="weight" class="form-control">
					              				<option value=""></option>
					              				<?php for($i=0;$i<=300;$i++){ ?>
					              					<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
					              				<?php } ?>
					              			</select>
										</div>

										<label>height</label>
										<div class="row">
											<div class="col-md-6">
								             	<div class="form-group">
							              			<label>Feet</label>
							              			<select name="height_ft" id="height_ft" class="form-control">
							              				<?php for($i=1;$i<=10;$i++){ ?>
							              					<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
							              				<?php } ?>
							              			</select>
												</div>
											</div>
											<div class="col-md-6">
								             	<div class="form-group">
							              			<label>Inch</label>
							              			<select name="height_inch" id="height_inch" class="form-control">
							              				<?php for($i=0;$i<=11;$i++){ ?>
							              					<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
							              				<?php } ?>
							              			</select>
												</div>
											</div>
										</div>

										<label>Portrayable Ethnicity <span class="text-danger">*</span></label>
										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
							              			<select name="ethnicity" id="ethnicity" class="form-control">
							              				<option value="">Select</option>
							              				<?php $et_other = "Y"; ?>
							              				<?php foreach($ethnicityarray as $val){ ?>
							              					<option value="<?php echo $val; ?>" ><?php echo $val; ?></option>
							              				<?php } ?>
							              			</select>
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<input type="text" name="ethnicity_other" id="ethnicity_other" class="form-control" value="" placeholder="other"/>
												</div>
											</div>
										</div>

										<div class="form-group">
					              			<label>Nationality</label>
					              			<select name="nationality" id="nationality" class="form-control">
					              				<option value="">Select</option>
					              				<?php foreach($countryarray as $key=>$val){ ?>
					              					<option value="<?php echo $val; ?>" ><?php echo $val; ?></option>
					              				<?php } ?>
					              			</select>
										</div>

										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label>Hair Color</label>
							              			<select name="hair_color" id="hair_color" class="form-control">
							              				<option value="">Select</option>
							              				<?php foreach($haircolorarray as $val){ ?>
							              					<option value="<?php echo $val; ?>" ><?php echo $val; ?></option>
							              				<?php } ?>
							              			</select>
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
							              			<label>Hair Length</label>
							              			<input type="text" name="hair_length" id="hair_length" value="" class="form-control"/>
												</div>
											</div>
										</div>

										<div class="row">
											<div class="col-md-6">
												<div class="form-group">
							              			<label>Eye Color</label>
							              			<select name="eye_color" id="eye_color" class="form-control">
							              				<option value="">Select</option>
							              				<?php foreach($eyecolorarray as $val){ ?>
							              					<option value="<?php echo $val; ?>" ><?php echo $val; ?></option>
							              				<?php } ?>
							              			</select>
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
							              			<label>Eye Shape</label>
							              			<select name="eye_shape" id="eye_shape" class="form-control">
							              				<option value="">Select</option>
							              				<?php foreach($eyeShapeArray as $val){ ?>
							              					<option value="<?php echo $val; ?>" ><?php echo $val; ?></option>
							              				<?php } ?>
							              			</select>
												</div>
											</div>
										</div>
									</div>

					            </div>

					            <div class="box-footer">
									<div class="text-right">
										<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                						<input type="submit" class="btn btn-theme submitBtn" name="ApplyNew" value="Save" />
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

<script type="text/javascript" src="https://code.jquery.com/ui/1.11.0/jquery-ui.min.js"></script>
<script type="text/javascript" src="../dashboard/assets/jQuery-DateTimepicker-Addon/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript" src="../dashboard/assets/jQuery-DateTimepicker-Addon/i18n/jquery-ui-timepicker-addon-i18n.min.js"></script>
<script type="text/javascript" src="../dashboard/assets/jQuery-DateTimepicker-Addon/jquery-ui-sliderAccess.js"></script>

<!-- <script type="text/javascript" src="../dashboard/assets/bootstrap-tagsinput-latest/dist/bootstrap-tagsinput.min.js"></script> -->
<script type="text/javascript" src="../dashboard/assets/js/jquery.validate.js"></script>
<!-- <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script> -->

<script type="text/javascript">
	$('#birthdate').datepicker({
    	changeMonth: true,
    	changeYear: true,
    	// minDate: 0,
    });
    
	$.validator.addMethod("ethnicity_check", function(value, element) {
	  	eth = $("#ethnicity").val();
	  	eth_other = $("#ethnicity_other").val();
	  	// console.log(eth);
	  	// console.log(eth_other);
	  	if (eth == "" && eth_other == "") {
	        return false;
	    } else {
	        return true;
	    };
	}, "Please select ethnicity or enter other.");

 	$("#apply_Form").validate({
		rules: {
			firstname: "required",
			lastname: "required",
			email: {
                required : true,
                email : true,
                remote: {
			        url: "../ajax/dashboard_request.php",
			        type: "post",
			        data: {
				        name:'user_email_unique_insert'
				    }
			    }
            },
            phone: {
                required : true,
                digits : true,
            },
			username : {
                required : true,
                remote: {
			        url: "../ajax/dashboard_request.php",
			        type: "post",
			        data: {
				        name:'user_username_unique_insert'
				    }
			    }
            },
            password : {
                required : true,
                minlength:6,
                maxlength:20,
            },
			confirm_password : {
                required : true,
                equalTo : "#password"
            },
			payment_term: "required",
			gender: "required",
			ethnicity: {ethnicity_check: true,}
		},
		messages: {
			email: { 
				remote: "Email already exist",
			},
			username: { 
				remote: "Username already exist",
			}
		},
		errorElement: "em",
		errorPlacement: function ( error, element ) {
			// Add the `help-block` class to the error element
			error.addClass( "help-block" );

			if (element.prop("type") === "radio") {
				error.insertAfter(element.parents('label').siblings('.radio_err'));
				// error.html("#radio_err");
			} else {
				error.insertAfter(element);
			}
		},
		highlight: function ( element, errorClass, validClass ) {
			$( element ).parents( ".col-sm-5" ).addClass( "has-error" ).removeClass( "has-success" );
		},
		unhighlight: function (element, errorClass, validClass) {
			$( element ).parents( ".col-sm-5" ).addClass( "has-success" ).removeClass( "has-error" );
		},
		// submitHandler: function (){

			// return false;
			// form.submit();
			// if(error){
			// 	console.log('111');
			// 	return false;
			// }
			// alert("222!");
			// return false;
		// }
	});

</script>
<?php include('footer.php'); ?>