<?php 
$page = "article_form";
$page_selected = "article_create";
include('header.php');
include('../includes/agency_dash_functions.php');

$allowedExtensions = array("jpg","jpeg","png");

$project_id = "";
if(isset($_GET['project_id'])){
	$project_id = $_GET['project_id'];
}

$notification = array();
if(isset($_POST['submit']) && !empty($_POST['submit'] == 'Save')){
	// echo "<pre>";
	// print_r($_POST);
	// echo "</pre>";

	// $featured_image = "";
	// if(isset($_FILES) && $_FILES['featured_image']['name'] != ""){
	// 	$file_ext = end(explode(".", strtolower($_FILES['featured_image']['name'])));
	// 	if (in_array($file_ext, $allowedExtensions)) {

	// 		$filename = time().'_'.preg_replace("/[^a-zA-Z0-9.]/", "", $_FILES['featured_image']['name']);
	// 		$folder = '../uploads/featured_image';

	// 		$newfile = $folder . '/' . $filename;
	// 		if (move_uploaded_file($_FILES['featured_image']['tmp_name'], "$newfile")) {
	// 			$featured_image = $filename;
	// 		}
	// 	}
	// }

	if(isset($_GET['project_id'])){
		$sql_update = "UPDATE agency_project 
					SET 
					project_name = '".$_POST['project_name']."',
					start_date = '".$_POST['start_date']."',
					due_date = '".$_POST['due_date']."',
					details = '".$_POST['details']."'
					WHERE 
					project_id = ".$_GET['project_id']."
				";
		if(mysql_query($sql_update)){
			$project_id_update = $_GET['project_id'];
			// $url = 'project-create.php?project_id='.$project_id_update;
			// header("Location: ".$url);
			$notification['success'] = "Projects Details Upadted Successfully";
		}
	}else{
		$sql_ins = "INSERT INTO agency_project 
					SET 
					project_name = '".$_POST['project_name']."',
					start_date = '".$_POST['start_date']."',
					due_date = '".$_POST['due_date']."',
					details = '".$_POST['details']."'
				";
		if(mysql_query($sql_ins)){
			$project_id_new = mysql_insert_id();
			$url = 'project-create.php?project_id='.$project_id_new;
			header("Location: ".$url);
		}
	}

}

// ============= casting add

if(isset($_POST['project_casting_add']) && !empty($_POST['project_casting_add'] == 'Add')){

	if(isset($_POST['casting'])){
		$casting_add = 0;
		foreach($_POST['casting'] as $casting_id){
			$sql_casting_ins = "INSERT INTO agency_project_casting 
				SET 
				project_id = '".$project_id."',
				casting_id = '".$casting_id."'
			";
			if(mysql_query($sql_casting_ins)){
				$casting_add++;
			}
		}
		if($casting_add > 0){
			$notification['success'] = "Casting Imported Successfully";
		}
	}

}
// ============= remove casting

if(isset($_POST['project_casting_id'])){
	// echo "<pre>";
	// print_r($_POST);
	// echo "</pre>";

	$sql_casting_dlt = "DELETE FROM agency_project_casting 
		WHERE 
		project_casting_id = '".$_POST['project_casting_id']."'
	";

	if(mysql_query($sql_casting_dlt)){
		$notification['success'] = "Casting Removed From Project Successfully";
	}
}

// ============= talent add

if(isset($_POST['project_talent_add']) && !empty($_POST['project_talent_add'] == 'Add')){

	if(isset($_POST['talent'])){
		$talent_add = 0;
		foreach($_POST['talent'] as $talent_id){
			$sql_talent_ins = "INSERT INTO agency_project_talent 
				SET 
				project_id = '".$project_id."',
				talent_id = '".$talent_id."'
			";
			if(mysql_query($sql_talent_ins)){
				$talent_add++;
			}
		}
		if($talent_add > 0){
			$notification['success'] = "Talent Imported For Project Successfully";
		}
	}

}

// ============= remove talent

if(isset($_POST['project_talent_id'])){
	// echo "<pre>";
	// print_r($_POST);
	// echo "</pre>";

	$sql_talent_dlt = "DELETE FROM agency_project_talent 
		WHERE 
		project_talent_id = '".$_POST['project_talent_id']."'
	";

	if(mysql_query($sql_talent_dlt)){
		$notification['success'] = "Talent Removed From Project Successfully";
	}
}

// ============= upload documents

if(isset($_FILES['project_doc']) && $_FILES['project_doc'] != ''){
	// echo "<pre>";
	// print_r($_POST);
	// echo "</pre>";

	// echo "<pre>";
	// print_r($_FILES);
	// echo "</pre>";
	// exit;

	$folder_project_doc = '../uploads/project_doc/' . $project_id . '/';
	if(!is_dir($folder_project_doc)) {
	    mkdir($folder_project_doc, 0777, true);
	}

	$upload_success = "N";
    $filename_project_doc = filename_new($_FILES['project_doc']['name']);
    $destination_project_doc = $folder_project_doc.$filename_project_doc;
 	if (move_uploaded_file($_FILES['project_doc']['tmp_name'], "$destination_project_doc")) {
 		$sql_ins = "INSERT INTO agency_project_document
 					SET 
 					project_id = ".$project_id.",
 					title = '".$_POST['title']."',
 					document = '".$filename_project_doc."'
 				";
 		if(mysql_query($sql_ins)){
 			$upload_success = 'Y';
 		}
 	}

 	if($upload_success == "Y"){
		$notification['success'] = "Document Upload for Project Successfully.";
 	}else{
 		$notification['error'] = "Document Not Uploaded. Please Try Again.";
 	}
}

// =============

$project = array();
if(isset($_GET['project_id'])){
	$get_project_sql = "select * from agency_project ap WHERE project_id = ".$project_id."";
	$get_project_res = mysql_query($get_project_sql);

	while($row = sql_fetchrow($get_project_res)) {
		$project = $row;
	}
}
?>

<style type="text/css">
	.wrapper-inactive{
		pointer-events: none;
	}
</style>
<div id="page-wrapper">
    <div class="" id="main">

			<div class="row">
				<div class="col-sm-12">
					<h3>My Projects </h3>
					
					<?php if(isset($notification['success'])){ ?>
				        <div class="alert alert-success" role="alert" id="alert-success-form">
				            <?php echo $notification['success']; ?>
				        </div>
			        <?php } ?>
			        <?php if(isset($notification['error'])){ ?>
			            <div class="alert alert-danger" role="alert" id="alert-danger-form">
			                <?php echo $notification['error']; ?>
			            </div>
			        <?php } ?>

					<div class="row">
						<div class="col-sm-8">

							<form enctype="multipart/form-data" action="" method="post" name="article" id="project-form" class="">
								<div class="box box-theme">
									<!-- <div class="box-header with-border">
						            	<h3 class="box-title">Add new Article</h3>
						            </div> -->
						            <div class="box-body">
						            	<div class="form-group">
						                  	<label class="control-label text-right">PROJECT NAME *</label>
						                  	<input type="text" class="form-control" name="project_name" id="project_name" placeholder="Enter Project Name" value="<?php if(isset($project['project_name'])){ echo $project['project_name']; } ?>">
						                </div>

						                <div class="form-group">
						                  	<label class="control-label text-right">START DATE *</label>
						                  	<input type="text" class="form-control" name="start_date" id="start_date" placeholder="Enter Start Date" value="<?php if(isset($project['start_date'])){ echo $project['start_date']; } ?>">
						                </div>

						                <div class="form-group">
						                  	<label class="control-label text-right">DUE DATE *</label>
						                  	<input type="text" class="form-control" name="due_date" id="due_date" placeholder="Enter Due Date" value="<?php if(isset($project['due_date'])){ echo $project['due_date']; } ?>">
						                </div>

						                <div class="form-group">
						                  	<label class="control-label text-right">PROJECT DETAILS </label>
						                  	<textarea id="details" name="details" placeholder="Enter Project Details..." class="form-control"><?php if(isset($project['details'])){ echo $project['details']; } ?></textarea>
						                </div>
						            </div>
						            <div class="box-footer">
										<div class="text-right">
											<a type="button" href="project-list.php" class="btn btn-default btn-flat">Cancel</a>
											<!-- <input type="button" value="Submit" name="submit" class="btn btn-theme btn-submit"/> -->
											<input type="submit" name="submit" class="btn btn-theme btn-submit btn-flat" value="Save">
										</div>
						            </div>
								</div>
							</form>

							<?php if(isset($_GET['project_id'])){ ?>

								<div class="box box-theme">
									<div class="box-header with-border">
										<div class="row">
											<div class="col-sm-6"> 
												<h3 class="box-title">Casting For This Projects</h3>
											</div>
											<div class="col-sm-6">
												<a data-toggle="modal" data-target="#project_casting_Modal" class="btn btn-theme btn-sm pull-right btn-flat"><i class="fa fa-plus"></i> Import Casting </a>
											</div>
										</div>
						            </div>

						            <div class="box-body">
						            	<form action="" method="POST" id="casting_tbl_frm">
											<table align="center" class="datatable table table-responsive table-striped">
												<thead>
													<tr>
														<th></th>
														<th>Roles</th>
														<th>Submission</th>
														<th></th>
													</tr>
												</thead>
												<tbody>
													<?php
														$get_imported_casting_res = mysql_query("select apc.*,ac.job_title from agency_project_casting apc 
																LEFT JOIN agency_castings ac ON ac.casting_id = apc.casting_id
																WHERE apc.project_id = ".$project_id."
															");
														if (mysql_num_rows($get_imported_casting_res) > 0) {
															while ($row = mysql_fetch_array($get_imported_casting_res, MYSQL_ASSOC)) {
																?>
																<tr>
																	<td><?php echo $row['job_title']; ?></td>
																	<td>
																		<?php 
																		// echo "SELECT COUNT(role_id) as total_role FROM agency_castings_roles WHERE casting_id =".$row['casting_id']."<br/>";

																		echo mysql_result(mysql_query("SELECT COUNT(role_id) as total_role FROM agency_castings_roles WHERE casting_id =".$row['casting_id'].""), 0, 'total_role'); ?>
																	</td>
																	<td>
																		<?php echo mysql_result(mysql_query("SELECT COUNT(submission_id) as total_submission FROM agency_mycastings am
																			LEFT JOIN agency_castings_roles acr ON acr.role_id = am.role_id
																			WHERE acr.casting_id =".$row['casting_id'].""), 0, 'total_submission'); ?>
																	</td>
																	<td>
																		<a href="casting-view.php?casting_id=<?php echo $row['casting_id']; ?>" class="btn btn-sm btn-flat btn-default" target="_blank">View</a>
																		<a class="btn btn-sm btn-flat btn-danger delete_casting_btn" project_casting_id="<?php echo $row['project_casting_id']; ?>"><i class="fa fa-trash-o"></i></a>
																	</td>
																<tr>
																<?php 
															}
														}
													?>
												</tbody>
											</table>
											<input type="hidden" name="project_casting_id" id="project_casting_id" value="" />
										</form>
									</div>
								</div>

								<div class="box box-theme">
									<div class="box-header with-border">
										<div class="row">
											<div class="col-sm-6"> 
												<h3 class="box-title">Talent Selcted For This Projects</h3>
											</div>
											<div class="col-sm-6">
												<a data-toggle="modal" data-target="#project_talent_Modal" class="btn btn-theme btn-sm pull-right btn-flat"><i class="fa fa-plus"></i> Import Talent </a>
											</div>
										</div>
						            </div>

						            <div class="box-body">
										<form action="" method="POST" id="casting_tbl_frm">
											<table align="center" class="datatable table table-responsive table-striped">
												<thead>
													<tr>
														<th></th>
														<th>Name</th>
														<th></th>
													</tr>
												</thead>
												<tbody>
													<?php
														$get_imported_talent_res = mysql_query("select apt.*,ap.*,fu.user_avatar from agency_project_talent apt 
																LEFT JOIN agency_profiles ap ON ap.user_id = apt.talent_id
																LEFT JOIN forum_users fu ON fu.user_id = ap.user_id
																WHERE apt.project_id = ".$project_id."
															");
														if (mysql_num_rows($get_imported_talent_res) > 0) {
															while ($row = mysql_fetch_array($get_imported_talent_res, MYSQL_ASSOC)) {
																?>
																<tr>
																	<td>
																		<?php
										                                    if(file_exists('../uploads/users/' . $row['user_id'] . '/profile_pic/thumb/50x50_'.$row['user_avatar']) ){
										                                      $img_profile = '../uploads/users/' . $row['user_id'] . '/profile_pic/thumb/50x50_'.$row['user_avatar'];
										                                    }else{
										                                      $img_profile = '../images/friend.gif';
										                                    }
										                                ?>
																		<a><img src="<?php echo $img_profile; ?>" style="height: 50px;"/></a>
																	</td>
																	<td>
																		<?php echo $row['firstname'].' '.$row['lastname']; ?>
																	</td>
																	<td>
																		<a href="profile-view.php?user_id=<?php echo $row['user_id']; ?>" class="btn btn-sm btn-flat btn-default" target="_blank">View</a>
																		<a class="btn btn-sm btn-flat btn-danger delete_talent_btn" project_talent_id="<?php echo $row['project_talent_id']; ?>"><i class="fa fa-trash-o"></i></a>
																	</td>
																<tr>
																<?php 
															}
														}
													?>
												</tbody>
											</table>
											<input type="hidden" name="project_talent_id" id="project_talent_id" value="" />
										</form>
									</div>
								</div>

							<?php } ?>

						</div>

						<div class="col-sm-4">
							<?php include('quick_search.php'); ?>

							<!-- <div class="box box-theme">
								<div class="box-header with-border">
					            	<h3 class="box-title">Categories</h3>
					            </div>

					            <div class="box-body with-border">
					                <div class="form-group">
					                	<input type="text" class="form-control" name="article_category" id="article_category" placeholder="Enter Category">
					                </div>
								</div>
							</div> -->

							<?php if(isset($_GET['project_id'])){ ?>
								<div class="box box-theme">
									<div class="box-header with-border">
						            	<h3 class="box-title">Project Documents</h3>
						            </div>

						            <div class="box-body with-border">
							            <!-- <div class="form-group">
						                  	<input type="file" class="form-control" name="featured_image" id="featured_image" placeholder="Upload Image">
						                </div> -->
						                <div class="row text-center">
							                <?php
							                	$project_doc_sql = "select * from agency_project_document apc 
							                						WHERE project_id = ".$project_id."
							                						";
							                	$project_doc_res = mysql_query($project_doc_sql);
							                	while ($row = mysql_fetch_array($project_doc_res, MYSQL_ASSOC)) {
							                		if(file_exists('../uploads/project_doc/' . $project_id . '/'.$row['document'])) {

							                			?>
							                				<div class="col-sm-3" style="margin-bottom: 15px;">
							                					<a href="<?php echo '../uploads/project_doc/' . $project_id . '/'.$row['document']; ?>" target="_blank"><i class="fa fa-file-text fa-4x"></i></a>
							                					<br/>
							                					<?php echo $row['title']; ?>
							                				</div>
							                			<?php

							                		}
							                	}
							                ?>
							            </div>
					                </div>
					                <div class="box-footer text-right">
					                	<button data-toggle="modal" data-target="#project_doc_Modal" class="btn btn-sm btn-flat btn-warning"><i class="fa fa-plus"></i> Add New Documents</button>
					                </div>
								</div>
							<?php } ?>

						</div>

					</div>

				</div>
			</div>
			
	</div>
</div>

<div class="modal fade" id="project_casting_Modal" role="dialog">
	<div class="modal-dialog">
		<form role="form" id="" class="project_casting_form" method="post" action="" enctype="multipart/form-data">
		  	<div class="modal-content">
		        <!-- Modal Header -->
		        <div class="modal-header">
		            <button type="button" class="close" data-dismiss="modal">
		                <span aria-hidden="true">&times;</span>
		                <span class="sr-only">Close</span>
		            </button>
		            <h4 class="modal-title" id=""> Import Castings</h4>
		        </div>
		        <!-- Modal Body -->
		        <div class="modal-body">

		        	<table class="table table-striped table-responsive">
		        		<thead>
		        			<tr>
		        				<th>Castings</th>
		        			</tr>
		        		</thead>
		        		<tbody>
							<?php
								$get_casting_sql = "select * from agency_castings ac
													WHERE casting_director = ".$_SESSION['user_id']." ";
								$get_casting_res = mysql_query($get_casting_sql);
							?>
							<?php if(mysql_num_rows($get_casting_res) > 0){ ?>
								<?php while($row = sql_fetchrow($get_casting_res)) { ?>
									<tr>
										<td>
											<label class="font-normal">
												<?php
													$check_casting_add_or_not_sql = "select * from agency_project_casting apc
																		WHERE project_id = ".$project_id." 
																		AND casting_id = ".$row['casting_id']."
																		";
													$check_casting_add_or_not_res = mysql_query($check_casting_add_or_not_sql);
												?>
												<?php if(mysql_num_rows($check_casting_add_or_not_res) > 0){ ?>
													<label class="label label-warning">Already Imported!</label>
												<?php }else{ ?>
													<input type="checkbox" name="casting[]" value="<?php echo $row['casting_id']; ?>">
												<?php } ?>
												<?php echo $row['job_title']; ?>
											</label>
										</td>
				        			</tr>
								<?Php } ?>
							<?Php }else{ ?>
								<tr>
									<td>No Casting Avialble</td>
								</tr>
							<?php } ?>
		        		</tbody>
		        	</table>
		        </div>
		        <!-- Modal Footer -->
		        <div class="modal-footer">
		            <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Close</button>
		            <input type="submit" class="btn btn-success btn-flat" name="project_casting_add" value="Add"/>
		        </div>
		  	</div>
		</form>
	</div>
</div>

<div class="modal fade" id="project_talent_Modal" role="dialog">
	<div class="modal-dialog">
		<form role="form" id="" class="project_talent_form" method="post" action="" enctype="multipart/form-data">
		  	<div class="modal-content">
		        <!-- Modal Header -->
		        <div class="modal-header">
		            <button type="button" class="close" data-dismiss="modal">
		                <span aria-hidden="true">&times;</span>
		                <span class="sr-only">Close</span>
		            </button>
		            <h4 class="modal-title" id="">Import Talent</h4>
		        </div>
		        <!-- Modal Body -->
		        <div class="modal-body">

		        	<table class="table table-striped table-responsive">
		        		<thead>
		        			<tr>
		        				<th>Castings</th>
		        			</tr>
		        		</thead>
		        		<tbody>
				        	<?php
								$get_talent_sql = "select * from agency_profiles ap
													LEFT JOIN forum_users fu ON fu.user_id = ap.user_id
													WHERE account_type = 'talent' 
													AND  account_status = 'open'
													";
								$get_talent_res = mysql_query($get_talent_sql);
							?>

							<?php if(mysql_num_rows($get_talent_res) > 0){ ?>
								<?php while($row = sql_fetchrow($get_talent_res)) { ?>
									<tr>
										<td>
											<label class="font-normal">
												<?php
													$check_talent_add_or_not_sql = "select * from agency_project_talent apt
																		WHERE project_id = ".$project_id." 
																		AND talent_id = ".$row['user_id']."
																		";
													$check_talent_add_or_not_res = mysql_query($check_talent_add_or_not_sql);
												?>
												<?php if(mysql_num_rows($check_talent_add_or_not_res) > 0){ ?>
													<label class="label label-warning">Already Imported!</label>
												<?php }else{ ?>
													<input type="checkbox" name="talent[]" value="<?php echo $row['user_id']; ?>">
												<?php } ?>
												<?php echo $row['firstname'].' '.$row['lastname']; ?>
											</label>
										</td>
				        			</tr>
								<?Php } ?>
							<?Php }else{ ?>
								<tr>
									<td>No Talent Avialble</td>
								</tr>
							<?php } ?>
						</tbody>
					</table>

		        </div>
		        <!-- Modal Footer -->
		        <div class="modal-footer">
		            <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Close</button>
		            <input type="submit" class="btn btn-success btn-flat" name="project_talent_add" value="Add"/>
		        </div>
		  	</div>
		</form>
	</div>
</div>

<div class="modal fade" id="project_doc_Modal" role="dialog">
	<div class="modal-dialog">
		<form role="form" id="" class="project_doc_form" method="post" action="" enctype="multipart/form-data">
		  	<div class="modal-content">
		        <!-- Modal Header -->
		        <div class="modal-header">
		            <button type="button" class="close" data-dismiss="modal">
		                <span aria-hidden="true">&times;</span>
		                <span class="sr-only">Close</span>
		            </button>
		            <h4 class="modal-title" id=""> Project Documents</h4>
		        </div>
		        <!-- Modal Body -->
		        <div class="modal-body">
		        	<div class="form-group">
		        		<label>Title</label>
		        		<input type="text" name="title" id="title" class="form-control"/>
		        	</div>
		        	<div class="form-group">
			        	<label>Upload Document</label>
			        	<label class="file-box">
	                        <span class="name-box">Drag and Drop Files</span>
	                        <input type="file" name="project_doc" id="project_doc" class="form-control"/>
	                    </label>
		        	</div>
		        </div>
		        <!-- Modal Footer -->
		        <div class="modal-footer">
		            <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Close</button>
		            <input type="submit" class="btn btn-success btn-flat project_doc_add_btn" name="project_doc_add" value="Add"/>
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
<!-- <script type="text/javascript" src="../dashboard/assets/ckeditor/ckeditor.js"></script> -->

<!-- <script type="text/javascript" src="../dashboard/assets/bootstrap-tagsinput-latest/dist/bootstrap-tagsinput.min.js"></script> -->
<script src="../dashboard/assets/fileStyle/fileStyle.js"></script>
<script type="text/javascript" src="../dashboard/assets/js/jquery.validate.js"></script>

<script type="text/javascript">

	    $("#start_date").datepicker({
	        dateFormat: "yy-mm-dd",
	        minDate: 0,
	        changeMonth: true,
		    changeYear: true,
	        onSelect: function () {
	            var dt2 = $('#due_date');
	            var startDate = $(this).datepicker('getDate');
	            //add 30 days to selected date
	            startDate.setDate(startDate.getDate() + 365);
	            var minDate = $(this).datepicker('getDate');
	            var dt2Date = dt2.datepicker('getDate');
	            //difference in days. 86400 seconds in day, 1000 ms in second
	            var dateDiff = (dt2Date - minDate)/(86400 * 1000);

	            //dt2 not set or dt1 date is greater than dt2 date
	            // if (dt2Date == null || dateDiff < 0) {
	            //         dt2.datepicker('setDate', minDate);
	            // }
	            //dt1 date is 30 days under dt2 date
	            // else if (dateDiff > 365){
	            //         dt2.datepicker('setDate', startDate);
	            // }
	            //sets dt2 maxDate to the last day of 30 days window
	            // dt2.datepicker('option', 'maxDate', startDate);
	            //first day which can be selected in dt2 is selected date in dt1
	            dt2.datepicker('option', 'minDate', minDate);
	        }
	    });
	    $('#due_date').datepicker({
	        dateFormat: "yy-mm-dd",
	        minDate: 0,
	        changeMonth: true,
		    changeYear: true,
	    });


	    $("#project-form").validate({
			rules: {
				project_name: "required",
				start_date: "required",
				due_date: "required",
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

		$('.delete_casting_btn').click(function(){
			project_casting_id = $(this).attr('project_casting_id');
			$('#project_casting_id').val(project_casting_id);
			$(this).parents('form').submit();
		});

		$('.delete_talent_btn').click(function(){
			project_talent_id = $(this).attr('project_talent_id');
			$('#project_talent_id').val(project_talent_id);
			$(this).parents('form').submit();
		});

		$('.project_doc_add_btn').click(function(){
			// file1 = $('#project_doc');
			if($("#project_doc")[0].files.length == 0 || $("#title").val() == ""){ 
                alert('All Fields are Required For Documents');
                return false;
            } else { 
            	$(this).parents('form').submit();
                $(this).attr('disabled','true');
            }
		});

		// $(".btn-submit").click(function(e){
		// 	if($("#author").val() == "" && $("#author_new").val() == ""){
		// 		alert("Please Select Author or add new one!");
		// 		return false;
		// 	}
		// 	// console.log($('#article_content').attr('id'));
		// 	$("#project-form").submit();
		// });

	// });

	<?php if(isset($_GET['project_id'])){ ?>
		<?php if($project['due_date'] < date('Y-m-d')){ ?>
	        $('#page-wrapper').addClass('wrapper-inactive');
	    <?php } ?>
    <?php } ?>

</script>
<script>
    if (window.history.replaceState) {
      window.history.replaceState( null, null, window.location.href );
    }
</script>
<?php include('footer.php'); ?>