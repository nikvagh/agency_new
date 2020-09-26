<?php 
$page = "privilege_form";
$page_selected = "privilege";
include('header.php');
include('../includes/agency_dash_functions.php');

echo "<br/>";
$user_id = 0;
$form_data = array();
if(isset($_GET['user_id'])){
	$user_id = $_GET['user_id'];

	$prev_user_check_sql = "select * from agency_user_privileges 
					where user_id = ".$user_id."";
	$prev_user_check_res = mysql_query($prev_user_check_sql);
	if (mysql_num_rows($prev_user_check_res) > 0) {
		$rec_avail = true;
		while ($row = mysql_fetch_array($prev_user_check_res, MYSQL_ASSOC)) {
			$form_data = json_decode($row['privilege_json']);
		}
	}

}

// echo "<pre>";
// print_r($form_data);
// exit;

if(isset($_POST['submit'])){
	// echo "<pre>";
	// print_r($_POST);
	// echo "</pre>";

	$privilege_json = json_encode($_POST['privilege']);

	// $prev_user_check_sql = "select * from agency_user_privileges 
	// 				where user_id = ".$user_id."";
	// $prev_user_check_res = mysql_query($prev_user_check_sql);
	// if (mysql_num_rows($prev_user_check_res) > 0) {
	if(isset($rec_avail)){
		// update
		$sql_update = "UPDATE agency_user_privileges 
				SET 
				privilege_json = '".$privilege_json."'
				WHERE
				user_id = '".$user_id."'
			";

		if(mysql_query($sql_update)){
			header("Location: privilege-list.php");
		}

	}else{
		// insert
		echo $sql_ins = "INSERT INTO agency_user_privileges 
				SET 
				user_id = '".$user_id."',
				privilege_json = '".$privilege_json."'
			";

		if(mysql_query($sql_ins)){
			header("Location: privilege-list.php");
		}
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


				<div class="row">
					<div class="col-sm-12">
						<h3>Privileges </h3>

						<form name="" method="post" action="">
							<div class="row">
								<div class="col-sm-8">
									<form name="" method="post" action="">
									<div class="box box-theme">
							            <div class="box-body">
							            	
							            	<table class="table table-striped">
												<thead>
													<tr>
														<th>#</th>
														<th>Name</th>
														<th>
															<label>
																<input type="checkbox" name="checkbox_main" id="checkbox_main"/> Check / Uncheck All
															</label>
														</th>
													</tr>
												</thead>
												<tbody>
													<?php
														$privilege_list_sql = "select * from agency_privileges 
																				ORDER BY sort_order
																			   ";
														$privilege_list_query = mysql_query($privilege_list_sql);
														$cnt1 = 1;
														while ($row = mysql_fetch_array($privilege_list_query, MYSQL_ASSOC)) {
															?>
																	<tr>
																		<td><?php echo $cnt1; ?></td>
																		<td><?php echo $row['privileges_title']; ?></td>
																		<td><input type="checkbox" name="privilege[<?php echo $row['privileges_name']; ?>]" class="ch_box" <?php if(array_key_exists($row['privileges_name'], $form_data)){ echo "checked"; } ?> ></td>
																	</tr>
													<?php $cnt1++; } ?>
												</tbody>
											</table>

							            </div>

							            <div class="box-footer">
											<div class="text-right">
												<a type="button" href="privilege-list.php" class="btn btn-default">Cancel</a>
												<button type="submit" name="submit" class="btn btn-theme btn-submit">Submit</button>
											</div>
							            </div>
									</div>
								</div>
							</div>
						</form>

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
<script type="text/javascript" src="../dashboard/assets/js/jquery.validate.js"></script>
<!-- <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script> -->

<script type="text/javascript">

	$('#checkbox_main').change(function(){
		if($(this).prop("checked") == true){
			$(".ch_box").prop("checked",true);
		}else{
			$(".ch_box").prop("checked",false);
		}
	});

</script>
<?php include('footer.php'); ?>