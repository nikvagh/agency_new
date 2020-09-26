<?php 
  $page = "casting_call";
  $page_selected = "casting_call";
  include('header.php');
  include('../includes/agency_dash_functions.php');

  $user_id = $_SESSION['user_id'];
  $casting_id = $_GET['casting_id'];
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

		<div class="row">
			<div class="col-md-12">

				<h3>Project Details</h3>

				<button class="auto_find_all btn btn-theme btn-flat">Auto Find</button>
				<br/><br/>

				<?php
					$casting_role_q = mysql_query("select * from agency_castings_roles
                                                WHERE casting_id =".$casting_id."
                                              ");
	  			?>
	  			<?php if (mysql_num_rows($casting_role_q) > 0) { ?>
  					<?php while ($role_row = mysql_fetch_assoc($casting_role_q)) { ?>
						<div class="box">
							<div class="box-header with-border">
								<h3 class="box-title"><?php echo $role_row['name']; ?></h3>
							</div>
	                      	<div class="box-body">

		                        <div class="col-sm-6">
		                          <strong>Age Range</strong>
		                          <p class="text-muted"><?php echo $role_row['age_lower'].' - '.$role_row['age_upper']; ?></p>

		                          <strong>Gender</strong>
		                          <p>
		                            <?php 
		                               $gender_q = mysql_query("select * from agency_castings_roles_vars
		                                                          WHERE casting_id =".$casting_id." AND role_id = ".$role_row['role_id']." AND var_type = 'gender'
		                                                        ");
		                                if (mysql_num_rows($gender_q) > 0) {
		                                  while ($gender_row = mysql_fetch_assoc($gender_q)) {
		                                    $gender_matched[] = $gender_row['var_value'];
		                                    echo '<span class="label label-primary">'.$gender_row['var_value'].'</span> ';
		                                  }
		                                }
		                            ?>
		                          </p>

		                          <strong>Ethnicity </strong>
		                          <p>
		                            <?php 
		                               $ethnicity_q = mysql_query("select * from agency_castings_roles_vars
		                                                          WHERE casting_id =".$casting_id." AND role_id = ".$role_row['role_id']." AND var_type = 'ethnicity'
		                                                        ");
		                                if (mysql_num_rows($ethnicity_q) > 0) {
		                                  while ($ethnicity_row = mysql_fetch_assoc($ethnicity_q)) {
		                                    echo '<span class="label label-primary">'.$ethnicity_row['var_value'].'</span> ';
		                                  }
		                                }
		                            ?>
		                          </p>

		                          <strong>Hight(Inch) </strong>
		                          <p class="text-muted"><?php echo $role_row['height_lower'].' - '.$role_row['height_upper']; ?></p>
		                        
		                          <strong>Requirement </strong>
		                          <p class="text-muted"><?php echo $role_row['requirement']; ?></p>

		                          <strong>Description</strong>
		                          <p class="text-muted"><?php echo $role_row['description']; ?></p>

		                          <strong>Language</strong>
		                          <p class="text-muted"><?php echo $role_row['language']; ?></p>

		                          <strong>Accent</strong>
		                          <p class="text-muted"><?php echo $role_row['accent']; ?></p>

		                          <strong>Special Skills</strong>
		                          <p class="text-muted"><?php echo $role_row['special_skills']; ?></p>

		                          <strong>Reference Photo</strong>
		                            <div class="row">
		                              <div class="col-sm-4">
		                                <?php if($role_row['reference_photo'] != ""){ ?>
		                                  <img src="<?php echo '../attachments/roles/' . $role_row['role_id'] . '/'.$role_row['reference_photo']; ?>" class="img-responsive"/>
		                                <?php } ?>
		                              </div>
		                            </div>

		                          <strong>Sides</strong>
		                          <div class="row">
		                            <div class="col-sm-4">
		                              <?php if($role_row['sides'] != ""){ ?>
		                                <img src="<?php echo '../attachments/roles/' . $role_row['role_id'] . '/'.$role_row['sides']; ?>" class="img-responsive"/>
		                              <?php } ?>
		                            </div>
		                          </div>

		                          <strong>Required materials</strong>
		                          	<p class="text-muted">
		                              	<?php 
			                                if($role_row['required_materials'] != ""){ 
			                                $required_materials = explode(',', $role_row['required_materials']);
			                                foreach($required_materials as $val){
			                            ?>
			                             		<span class="label label-primary"><?php echo $val; ?></span>
			                                <?php } ?>
			                            <?php } ?>
		                          	</p>
		                        </div>

		                        <div class="col-sm-6">
		                        	<form name="" id="form_role_<?php echo $role_row['role_id']; ?>" class="search_form" action="" method="post">
			                        	<!-- <h4 class="box-heading">Search Criteria</h4>
			                        	<div class="form-group">
			                        		<label>First Name</label>
			                        		<input type="text" name="firstname" class="form-control" />
			                        	</div>
			                        	<div class="form-group">
			                        		<label>Last Name</label>
			                        		<input type="text" name="lastname" class="form-control" />
			                        	</div>
			                        	<div class="form-group">
			                        		<label>Age Range</label>
			                        		<div class="row">
			                        			<div class="col-sm-6">
			                        				<select class="form-control" name="age_start">
					                        			<option></option>
					                        			<?php for ($j = 0; $j <= 100; $j++) { ?>
															<option value="<?php echo $j; ?>"><?php echo $j; ?></option>
														<?php } ?>
					                        		</select>
			                        			</div>
			                        			<div class="col-sm-6">
			                        				<select class="form-control" name="age_end">
					                        			<option></option>
					                        			<?php for ($j = 0; $j <= 100; $j++) { ?>
															<option value="<?php echo $j; ?>"><?php echo $j; ?></option>
														<?php } ?>
					                        		</select>
			                        			</div>
			                        		</div>
			                        	</div>

			                        	<div class="form-group">
			                        		<label>Gender</label>
			                        		<select class="form-control" name="gender">
			                        			<option></option>
			                        			<option value="M">Male</option>
			                        			<option value="F">Female</option>
			                        			<option value="Transgender">Transgender </option>
			                        		</select>
			                        	</div>

			                        	<div class="form-group">
			                        		<label>Ethnicities</label>
			                        		<br/>
			                        		<label><input type="checkbox" name="all_ethnicity" class="all_ethnicity" id="all_ethnicity_<?php echo $role_row['role_id']; ?>" value="<?php echo $e; ?>"/>Select All </label>
			                        		<br/>
				                        	<?php foreach ($ethnicityarray as $key => $e) { ?>
												<label><input type="checkbox" name="ethnicity[]" value="<?php echo $e; ?>" class="ethnicity_<?php echo $role_row['role_id']; ?>"/><?php echo $e ; ?></label>
											<?php } ?>
										</div>

										<div class="form-group">
			                        		<label>Union Affiliation</label>
			                        		<br/>
				                        	<?php foreach ($jobunionarray as $key => $e) { ?>
												<label><input type="checkbox" name="union_name[]" value="<?php echo $e; ?>"/><?php echo $e ; ?></label>
											<?php } ?>
										</div> -->

										<div class="row">
											<div class="col-sm-6">
												<input type="hidden" name="role_id" value="<?php echo $role_row['role_id']; ?>">
												<!-- <input type="submit" name="autofind" class="btn btn-theme btn-flat btnSubmit" value="Auto Find"> -->
												<!-- <input type="submit" name="search" class="btn btn-theme btn-flat btnSubmit" value="Search"> -->
												<!-- <button type="reset" name="reset" class="btn btn-default btn-flat"> Clear</button> -->
											</div>
											<div class="col-sm-6 text-right">
												<button class="btn summary-btn btn-success btn-flat" role-id="<?php echo $role_row['role_id']; ?>" user-id="<?php echo $role_row['role_id']; ?>">Summary</button>
											</div>
										</div>

									</form>
		                        </div>

	                      	</div>
	                    </div>
					<?php } ?>
				<?php } ?>

			</div>
		</div>

	</div>
</div>

<div class="modal fade" id="submission_Modal" role="dialog">
</div>

<div class="modal fade" id="note_Modal" role="dialog">
	
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
	$(".all_ethnicity").change(function(){
		ethnicity_all_id_str = $(this).attr('id');
		ethnicity_all_id_ary = ethnicity_all_id_str.split('_');
		num = ethnicity_all_id_ary[2];

        if($(this).is(":checked")) {
            $('.ethnicity_'+num).prop("checked",true);
        }else{
			$('.ethnicity_'+num).prop("checked",false);
        }       
	})

	var buttonpressed;
	$('.btnSubmit').click(function() {
		buttonpressed = $(this).attr('name');
	});

	$(".search_form").submit(function(e){
		e.preventDefault();
		form = $(this).serialize();
		// var role_id = $(this).attr('data-id');
		// console.log(buttonpressed);
		// return false;

	    var user_id = "<?php echo $_SESSION['user_id'] ?>";
	    // AJAX request
	    $.ajax({
	        url: '../ajax/dashboard_request.php',
	        type: 'post',
	        data: form+'&user_id='+user_id+'&name=user_serach_tm&buttonpressed='+buttonpressed,
	        // dataType: 'json',
	        success: function(response){
	          // console.log(response);
	          $('#submission_Modal').html(response);

	          // Display Modal
	          $('#submission_Modal').modal('show'); 
	        }
	    });
	});

	$(".auto_find_all").click(function(e){
		e.preventDefault();
		form = $(this).serialize();
		// var role_id = $(this).attr('data-id');
		// console.log(buttonpressed);
		// return false;
		var casting_id = '<?php echo $casting_id; ?>';
	    var user_id = "<?php echo $_SESSION['user_id'] ?>";
	    // AJAX request
	    $.ajax({
	        url: '../ajax/dashboard_request.php',
	        type: 'post',
	        data: form+'&user_id='+user_id+'&casting_id='+casting_id+'&name=user_serach_tm_autofind_all&buttonpressed=autofind',
	        // dataType: 'json',
	        success: function(response){
	          // console.log(response);
	          $('#submission_Modal').html(response);

	          // Display Modal
	          $('#submission_Modal').modal('show'); 
	        }
	    });
	});

	$(document).on('click', '.summary-btn', function(e) {
		e.preventDefault();
		var user_id = "<?php echo $_SESSION['user_id'] ?>";
		var role_id = $(this).attr('role-id');

		$.ajax({
	        url: '../ajax/dashboard_request.php',
	        type: 'post',
	        data: 'user_id='+user_id+'&role_id='+role_id+'&name=submission_box_tm',
	        // dataType: 'json',
	        success: function(response){
	          // console.log(response);
	          $('#submission_Modal').html(response);

	          // Display Modal
	          $('#submission_Modal').modal('show');
	        }
	    });
	});

	$(document).on('submit', '.talent_add_form', function(e) {
		e.preventDefault();
		form = $(this).serialize();

		$.ajax({
	        url: '../ajax/dashboard_request.php',
	        type: 'post',
	        data: form+'&name=submission_add_talent_tm',
	        // dataType: 'json',
	        success: function(response){
	          // console.log(response);
	          // $('#submission_Modal').html(response);

	          // Display Modal
	          $('#submission_Modal').modal('hide'); 
	        }
	    });
	});


	$(document).on('submit', '.talent_submit_form', function(e) {
		e.preventDefault();
		form = $(this).serialize();

		$.ajax({
	        url: '../ajax/dashboard_request.php',
	        type: 'post',
	        data: form+'&name=submission_talent_tm',
	        // dataType: 'json',
	        success: function(response){
	          // console.log(response);
	          // $('#submission_Modal').html(response);

	          // Display Modal
	          $('#submission_Modal').modal('hide'); 
	        }
	    });
	});

	$(document).on('click', '.note-btn', function(e) {
		submission_id = $(this).attr('submission');
		// talent_id = $(this).attr('data-talent');
		// $('#note_Modal #role_id').val(role_id); 
		// $('#note_Modal #talent_id').val(talent_id); 
		// $('#note_Modal #note').val();

		$.ajax({
	        url: '../ajax/dashboard_request.php',
	        type: 'post',
	        data: 'submission_id='+submission_id+'&name=submission_note_box_tm',
	        // dataType: 'json',
	        success: function(response){
	          // console.log(response);
	          $('#note_Modal').html(response);

	          // Display Modal
	          $('#note_Modal').modal('show');
	        }
	    }); 
	});

	$(document).on('submit', '.lightbox_form', function(e) {
		e.preventDefault();

		// form = $(this).serialize();
		// $.ajax({
	    //     url: '../ajax/dashboard_request.php',
	    //     type: 'post',
	    //     data: form+'&name=submission_note_tm',
	    //     // dataType: 'json',
	    //     success: function(response){
	    //       if(response == "true"){
	    //       	$('#note_Modal').modal('hide'); 
	    //       }else{
	    //       	// console.log('2222');
	    //       }
	    //       // Display Modal
	    //       // 
	    //     }
	    // });
	});

</script>
<?php include('footer.php'); ?>