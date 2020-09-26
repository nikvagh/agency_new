<?php 
$page = "talent_form";
$page_selected = "talent_member";
include('header.php');
include('../includes/agency_dash_functions.php');

if(isset($_POST) && $_POST['submit'] == "Submit"){
	echo "<pre>";
	print_r($_POST);
	echo "</pre>";
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

			<form enctype="multipart/form-data" action="talent-create.php" method="post" name="talent" id="talent-form" class="">
				<div class="row">
					<div class="col-sm-12 col-sm-offset-1">
						<h3>Talent </h3>
						
						<div class="row">
							<div class="col-sm-7">
								<!-- <div class="box box-theme">
									<div class="box-header with-border">
						            	<h3 class="box-title">Add New Talent</h3>
						            </div>
						            <div class="box-body">
						            	<div class="form-group">
						                  	<label class="control-label text-right">First Name *</label>
						                  	<input type="text" class="form-control" name="first_name" id="first_name" placeholder="Enter First Name">
						                </div>

						                <div class="form-group">
						                  	<label class="control-label text-right">Last Name *</label>
						                  	<input type="text" class="form-control" name="last_name" id="last_name" placeholder="Enter Last Name">
						                </div>

						                <div class="form-group">
						                  	<label class="control-label text-right">Email *</label>
						                  	<input type="text" class="form-control" name="email" id="email" placeholder="Enter Email">
						                </div>

						                <div class="form-group">
						                  	<label class="control-label text-right">Username *</label>
						                  	<input type="text" class="form-control" name="username" id="username" placeholder="Enter Username">
						                </div>

						                <div class="form-group">
						                  	<label class="control-label text-right">Password *</label>
						                  	<input type="password" class="form-control" name="password" id="password" placeholder="Enter Author">
						                </div>

						                <div class="form-group">
						                  	<label class="control-label text-right">Confirm Password *</label>
						                  	<input type="password" class="form-control" name="confirm_password" id="confirm_password" placeholder="Enter Author">
						                </div>

						                <div class="form-group">
						                  	<label class="control-label text-right">Phone Number *</label>
						                  	<input type="text" class="form-control" name="phone" id="phone" placeholder="Enter Phone Number">
						                </div>

						                <div class="form-group">
						                  	<label class="control-label text-right">Cell Phone Number</label>
						                  	<input type="text" class="form-control" name="phone" id="phone" placeholder="Enter Cell Phone Number">
						                </div>

						            </div>
								</div> -->
							</div>

							<div class="col-sm-3">
								<div class="box box-theme">
									<div class="box-header with-border">
						            	<h3 class="box-title">Publish</h3>
						            </div>

						            <div class="box-body">
						                <div class="form-group">
						                  	<label class="control-label text-right">Publish Date *</label>
						                  	<input type="text" class="form-control" name="article_publish_date" id="article_publish_date" placeholder="Select Date" autocomplete="off">
						                </div>
						                <div class="form-group">
						                  	<label class="control-label text-right">Status *</label>
					                  		<select class="form-control" name="article_status" id="article_status">
					                  			<option value="">Select</option>
					                  			<option value="enable">Enable</option>
					                  			<option value="disable">Disable</option>
					                  		</select>
						                </div>
									</div>

									<div class="box-footer">
										<input type="hidden" name="MAX_FILE_SIZE" value="1000000" />
										<div class="text-right">
											<a type="button" href="article-list.php" class="btn btn-default">Cancel</a>
											<input type="button" value="Submit" name="submit" class="btn btn-theme btn-submit"/>
											<!-- <button type="button" class="btn btn-theme btn-submit">Submit</button> -->
										</div>
						            </div>
								</div>

								<div class="box box-theme">
									<div class="box-header with-border">
						            	<h3 class="box-title">Categories</h3>
						            </div>

						            <div class="box-body with-border">
						                <div class="form-group">
						                	<input type="text" class="form-control" name="article_category" id="article_category" placeholder="Enter Category">
						                </div>
									</div>
								</div>

								<div class="box box-theme">
									<div class="box-header with-border">
						            	<h3 class="box-title">Tags</h3>
						            </div>

						            <div class="box-body with-border">
						            	<div class="form-group">
						                  	<input type="text" class="form-control" name="article_tags" id="article_tags" placeholder="Enter Tags">
						                </div>
									</div>
								</div>

								<div class="box box-theme">
									<div class="box-header with-border">
						            	<h3 class="box-title">Featured Image</h3>
						            </div>

						            <div class="box-body with-border">
							            <div class="form-group">
						                  	<input type="file" class="form-control" name="article_featured_image" id="article_featured_image" placeholder="Upload Image">
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

<script type="text/javascript" src="http://code.jquery.com/ui/1.11.0/jquery-ui.min.js"></script>
<script type="text/javascript" src="../dashboard/assets/jQuery-DateTimepicker-Addon/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript" src="../dashboard/assets/jQuery-DateTimepicker-Addon/i18n/jquery-ui-timepicker-addon-i18n.min.js"></script>
<script type="text/javascript" src="../dashboard/assets/jQuery-DateTimepicker-Addon/jquery-ui-sliderAccess.js"></script>
<script type="text/javascript" src="../dashboard/assets/ckeditor/ckeditor.js"></script>

<!-- <script type="text/javascript" src="../dashboard/assets/bootstrap-tagsinput-latest/dist/bootstrap-tagsinput.min.js"></script> -->

<script type="text/javascript">
	CKEDITOR.replace('article_content');
	$('#article_publish_date').datepicker({
    	changeMonth: true,
    	changeYear: true,
    	minDate: 0,
    });
</script>
<?php include('footer.php'); ?>