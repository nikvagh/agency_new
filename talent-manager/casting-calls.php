<?php 
  	$page = "casting_call";
  	$page_selected = "casting_call";
  	include('header.php');
  	include('../includes/agency_dash_functions.php');

	if(isset($_POST['filter'])){
		// echo "<pre>";print_r($_POST);
		// echo "</pre>";
		$_SESSION['filter']['casting']['filter_gender'] = $_POST['filter_gender'];
		$_SESSION['filter']['casting']['filter_height'] = $_POST['filter_height'];
		$_SESSION['filter']['casting']['filter_age'] = $_POST['filter_age'];
		$_SESSION['filter']['casting']['filter_ethnicity'] = $_POST['filter_ethnicity'];
		$_SESSION['filter']['casting']['filter_job_type'] = $_POST['filter_job_type'];
		$_SESSION['filter']['casting']['filter_union_status'] = $_POST['filter_union_status'];
		$_SESSION['filter']['casting']['filter_location'] = $_POST['filter_location'];
	}

	if(isset($_POST['clear'])){
		unset($_SESSION['filter']['casting']);
	}

	// echo "<pre>";
	// print_r($_SESSION);
	// echo "</pre>";

  	$user_id = $_SESSION['user_id'];
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
			<div class="col-md-10">
				<h3>Casting Calls</h3>
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
					                  		<?php for($height = 1; $height <= 100; $height++){ ?>
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
					                  		<?php for($age = 1; $age <= 100; $age++){ ?>
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
							<input type="submit" class="btn" name="clear" value="clear" />
						    <input type="submit" class="btn btn-theme" name="filter" value="filter"/>
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
									<th>Job Type</th>
									<th>Union Status</th>
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

									// LEFT JOIN agency_castings_roles acr ON ac.casting_id = acr.casting_id
									// LEFT JOIN agency_castings_roles_vars acrv ON acr.role_id = acrv.role_id
									$sql_list = "select ac.*,ap.firstname,ap.lastname,GROUP_CONCAT(DISTINCT acj.jobtype) jobtype1, GROUP_CONCAT(DISTINCT acu.union_name) union_name1 from agency_castings ac
											LEFT JOIN agency_profiles ap ON ac.casting_director = ap.user_id
											LEFT JOIN agency_castings_unions acu ON ac.casting_id = acu.casting_id
											LEFT JOIN agency_castings_jobtype acj ON ac.casting_id = acj.casting_id
											WHERE live = 1 AND deleted = 0 AND casting_date >= CURDATE() ".$cond." 
											GROUP BY ac.casting_id"; 

									$result = mysql_query($sql_list);
									if (mysql_num_rows($result) > 0) {
										while ($row = mysql_fetch_assoc($result)) {
											echo '<tr>';
											echo '<td>'.$row['casting_id'].'</td>';
											echo '<td>'.$row['job_title'].'</td>';
											echo '<td>'.$row['firstname'].' '.$row['lastname'].'</td>';
											echo '<td>'.$row['jobtype1'].'</td>';
											echo '<td>'.$row['union_name1'].'</td>';
											echo '<td>';
											// echo '<a href="casting-update.php?casting_id='.$row['casting_id'].'" class="btn btn-primary">Edit</a> ';
											// echo '<a href="" class="btn btn-info btn-request" data-id="'.$row['casting_id'].'"><i class="fa fa-paper-plane"></i> Send Booking Request </a>&nbsp;';
											echo '<a href="casting-role.php?casting_id='.$row['casting_id'].'" class="btn btn-theme btn-flat" data-id="'.$row['casting_id'].'"> Roles </a>';
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
</div>
<?php include('footer_js.php'); ?>

<!-- <script type="text/javascript" src="http://code.jquery.com/ui/1.11.0/jquery-ui.min.js"></script>
<script type="text/javascript" src="../dashboard/assets/jQuery-DateTimepicker-Addon/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript" src="../dashboard/assets/jQuery-DateTimepicker-Addon/i18n/jquery-ui-timepicker-addon-i18n.min.js"></script>
<script type="text/javascript" src="../dashboard/assets/jQuery-DateTimepicker-Addon/jquery-ui-sliderAccess.js"></script> -->
<!-- <script type="text/javascript" src="../dashboard/assets/ckeditor/ckeditor.js"></script> -->

<!-- <script type="text/javascript" src="../dashboard/assets/bootstrap-tagsinput-latest/dist/bootstrap-tagsinput.min.js"></script> -->
<!-- <script type="text/javascript" src="../dashboard/assets/js/jquery.validate.js"></script> -->
<script src="../dashboard/assets/DataTables/datatables.min.js"></script> 
<script src="../dashboard/assets/DataTables/dataTables.bootstrap.min.js"></script> 

<script type="text/javascript">
	$('.datatable').DataTable({
        "order": [[ 0, "desc" ]],
        'columnDefs': [{
		    'targets': [3], /* column index */
		    'orderable': false, /* true or false */
		}]
    });
</script>
<?php include('footer.php'); ?>