<?php 
$page = "article_form";
$page_selected = "article_update";
include('header.php');
include('../includes/agency_dash_functions.php');

function deleteold_image($id){
	$old_file = '../uploads/featured_image/'. mysql_result(mysql_query("SELECT featured_image FROM agency_article WHERE article_id=".$id.""), 0, 'featured_image');
	unlink($old_file);
	mysql_query("UPDATE agency_article SET featured_image='' WHERE article_id=".$id."");
}


$allowedExtensions = array("jpg","jpeg","png");
$authors = getAllAuthor();

$article = array();
if(isset($_GET['article_id'])){
	$sql_select = "SELECT * FROM agency_article WHERE article_id=".$_GET['article_id']."";
	$result = mysql_query($sql_select);
	if($row = sql_fetchrow($result)) {
		$article = $row;
	}
}

// echo "<pre>";
// print_r($article);
// echo "</pre>";

if(isset($_POST) && !empty($_POST)){
	// echo "<pre>";
	// print_r($_POST);
	// echo "</pre>";


	$featured_image = "";
	if(isset($_POST['featured_image_del'])){
		deleteold_image($_POST['article_id']);
	}else{
		$featured_image = $_POST['featured_image_old'];
	}

	if(isset($_FILES) && $_FILES['featured_image']['name'] != ""){
		deleteold_image($_POST['article_id']);
		$file_ext = end(explode(".", strtolower($_FILES['featured_image']['name'])));
		if (in_array($file_ext, $allowedExtensions)) {

			$filename = time().'_'.preg_replace("/[^a-zA-Z0-9.]/", "", $_FILES['featured_image']['name']);
			$folder = '../uploads/featured_image';

			$newfile = $folder . '/' . $filename;
			if (move_uploaded_file($_FILES['featured_image']['tmp_name'], "$newfile")) {
				$featured_image = $filename;
			}
		}
	}

	if($_POST['author_new'] != ""){
		$author = $_POST['author_new'];
		$sql_ins_auth = "INSERT INTO agency_author 
						SET 
						author_name = '".$_POST['author_new']."'
						";
		mysql_query($sql_ins_auth);
	}else{
		$author = $_POST['author'];
	}

	$sql_ins = "UPDATE agency_article 
				SET 
				title = '".$_POST['title']."',
				author = '".$author."',
				content = '".$_POST['content']."',
				publish_date = '".date('Y-m-d',strtotime($_POST['publish_date']))."',
				tags = '".$_POST['tags']."',
				featured_image = '".$featured_image."',
				updated_at = '".date('Y-m-d h:i:s')."'
				WHERE  
				article_id = ".$_POST['article_id']."
			";
	if(mysql_query($sql_ins)){
		header("Location: article-list.php");
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


			<form enctype="multipart/form-data" action="" method="post" name="article" id="article-form" class="">
				<div class="row">
					<div class="col-sm-11 col-sm-offset-1">
						<h3>Article </h3>
						
						<div class="row">
							<div class="col-sm-7">
								<div class="box box-theme">
									<div class="box-header with-border">
						            	<h3 class="box-title">Edit Article</h3>
						            </div>
						            <div class="box-body">
						            	<div class="form-group">
						                  	<label class="control-label text-right">Title *</label>
						                  	<input type="text" class="form-control" name="title" id="title" placeholder="Enter Title" value="<?php echo $article['title']; ?>">
						                </div>

						                <div class="form-group">
						                  	<label class="control-label text-right">Author *</label>
						                  	<div class="row">
							                  	<div class="col-sm-6">
								                  	<select class="form-control author_select" name="author" id="author">
								                  		<option value="">Select</option>
								                  		<?php foreach($authors as $val){ ?>
								                  			<option value="<?php echo $val['author_name']; ?>" <?php if($val['author_name'] == $article['author']){ echo "selected"; } ?>><?php echo $val['author_name']; ?></option>
								                  		<?php } ?>
								                  	</select>
							                  	</div>
							                  	<div class="col-sm-6">
								                  	<input type="text" class="form-control author_select" name="author_new" id="author_new" placeholder="Add New Author">
								                </div>
							                </div>
						                </div>

						                <div class="form-group">
						                  	<label class="control-label text-right">Content *</label>
						                  	<textarea id="content" name="content"><?php echo $article['content']; ?></textarea>
						                </div>
						            </div>
								</div>
							</div>

							<div class="col-sm-3">
								
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

								<div class="box box-theme">
									<div class="box-header with-border">
						            	<h3 class="box-title">Tags</h3>
						            </div>

						            <div class="box-body with-border">
						            	<div class="form-group">
						                  	<input type="text" class="form-control" name="tags" id="tags" placeholder="Enter Tags" value="<?php echo $article['tags']; ?>">
						                </div>
									</div>
								</div>

								<div class="box box-theme">
									<div class="box-header with-border">
						            	<h3 class="box-title">Featured Image</h3>
						            </div>

						            <div class="box-body with-border">
							            <div class="form-group">
						                  	<input type="file" class="form-control" name="featured_image" id="featured_image" placeholder="Upload Image">
						                  	<input type="hidden" class="form-control" name="featured_image_old" id="featured_image_old" value="<?php echo $article['featured_image']; ?>">

						                  	<?php if(!empty($article['featured_image'])){ ?>
												<a href="<?php echo '../uploads/featured_image/' . $article['featured_image']; ?>" target="_blank">view featured Image</a>&nbsp;&nbsp;&nbsp;&nbsp;
												<label><input type="checkbox" name="featured_image_del"> check to delete</label>
											<?php } ?>
						                  	
						                </div>
					                </div>
								</div>

								<div class="box box-theme">
									<div class="box-header with-border">
						            	<h3 class="box-title">Publish</h3>
						            </div>

						            <div class="box-body">
						                <div class="form-group">
						                  	<label class="control-label text-right">Publish Date *</label>
						                  	<input type="text" class="form-control" name="publish_date" id="publish_date" placeholder="Select Date" autocomplete="off" value="<?php echo date('m/d/Y',strtotime($article['publish_date'])); ?>">
						                </div>
						                <!-- <div class="form-group">
						                  	<label class="control-label text-right">Status *</label>
					                  		<select class="form-control" name="status" id="status">
					                  			<option value="">Select</option>
					                  			<option value="approved" <?php if($article['status'] == "approved"){ echo "selected"; } ?> >Approved</option>
					                  			<option value="pending" <?php if($article['status'] == "pending"){ echo "selected"; } ?> >Pending</option>
					                  		</select>
						                </div> -->
									</div>

									<div class="box-footer">
										<input type="hidden" name="MAX_FILE_SIZE" value="1000000" />
										<div class="text-right">
											<a type="button" href="article-list.php" class="btn btn-default btn-flat">Cancel</a>
											<!-- <input type="button" value="Submit" name="submit" class="btn btn-theme btn-submit"/> -->
											<input type="hidden" name="article_id" value="<?php echo $article['article_id']; ?>" />
											<button type="button" class="btn btn-theme btn-submit btn-flat">Submit</button>
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
<script type="text/javascript" src="../dashboard/assets/ckeditor/ckeditor.js"></script>

<script type="text/javascript" src="../dashboard/assets/bootstrap-tagsinput-latest/dist/bootstrap-tagsinput.min.js"></script>
<script type="text/javascript" src="../dashboard/assets/js/jquery.validate.js"></script>

<script type="text/javascript">

	// $(document).ready( function () {

		$('#tags').tagsinput({
			tagClass: 'big'
		});

		CKEDITOR.replace('content');
		$('#publish_date').datepicker({
	    	changeMonth: true,
	    	changeYear: true,
	    	minDate: 0,
	    });

	    var validator = $("#article-form").validate({
			rules: {
				// title: "required",
				author: {required: true},
				// article_content: { ckrequired1: true },
				publish_date: "required",
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
			if($("#author").val() == "" && $("#author_new").val() == ""){
				alert("Please Select Author or add new one!");
				return false;
			}

			var editor = CKEDITOR.instances['content'];  
			var ckValue = editor.getData().replace(/<[^>]*>/gi, '').trim();
			if(ckValue == 0){
				alert("Please Enter Content..!");
				return false;
			}

			// console.log($('#article_content').attr('id'));
			$("#article-form").submit();
		});

	// });
</script>
<?php include('footer.php'); ?>