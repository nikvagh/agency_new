<?php
$page = "notes_form";
$page_selected = "notes";
include('header.php');
$success = FALSE; // flag for showing or not showing form
?>

<div id="page-wrapper">
	<div class="" id="main">

		<?php
			$err_msg = '';
			if (isset($_POST['Submit'])) {
				if (stripslashes(trim($_POST['title']))) {
					$title = escape_data($_POST['title']);
				} else {
					$title = FALSE;
					$err_msg .= '<li>You must enter a Title</li>';
				}

				if (stripslashes(trim($_POST['content']))) {
					$content = escape_data($_POST['content']);
				} else {
					$content = FALSE;
					$err_msg .= '<li>You must enter Content</li>';
				}
				// echo $err_msg;

				$status = $_POST['status'];

				$featured = 0;
				if (isset($_POST['featured'])) {
					$featured = 1;
				}

				if ($title && $content) { // If everything's OK.
					$query = "INSERT INTO agency_notes (title, content, status) VALUES ('$title', '$content', '$status' )";
					$result = mysql_query($query);
					if (mysql_affected_rows() == 1) { // If it ran OK.
						$id = mysql_insert_id();
						$url = 'notes-list.php';
						ob_end_clean(); // Delete the buffer.
						header("Location: $url");
						exit(); // Quit the script.
					}
				}
			}

			if (!$success) {
			?>

				<form enctype="multipart/form-data" name="postform" method="post" action="notes-create.php">
					<div class="row">
						<div class="col-sm-12">
							<h3>Note </h3>

							<?php //if(isset($notification['success'])){ ?>
						        <div class="alert alert-success" role="alert" id="alert-success-form" style="display: none;">
						            <?php //echo $notification['success']; ?>
						        </div>
					        <?php //} ?>
					        <?php if($err_msg != ""){ ?>
					            <div class="alert alert-danger" role="alert" id="alert-danger-form">
					            	<ul>
						                <?php echo $err_msg; ?>
						            </ul>
					            </div>
					        <?php } ?>
							
							<div class="row">
								<div class="col-sm-6">
									<div class="box box-theme">
										<div class="box-header with-border">
							            	<h3 class="box-title">Create Note</h3>
							            </div>
							            <div class="box-body">
							            	<div class="form-group">
							                  	<label class="control-label text-right">Title *</label>
							                  	<input type="text" class="form-control" name="title" id="title" placeholder="Enter Title" maxlength="250" value="<?php if (isset($_POST['title'])) echo $_POST['title']; ?>">
							                </div>

							                <div class="form-group">
							                  	<label class="control-label text-right">Content </label>
							                  	<textarea name="content" cols="60" rows="20" class="form-control"><?php if (isset($_POST['content'])) echo $_POST['content']; ?></textarea>
							                </div>

							                <div class="form-group">
							                  	<label class="control-label text-right">Status </label>
						                  		<select class="form-control" name="status" id="status">
													<option value="Enable">Enable</option>
													<option value="Disable">Disable</option>
												</select>
							                </div>
							            </div>

							            <div class="box-footer">
											<div class="text-right">
												<a type="button" href="notes-list.php" class="btn btn-default">Cancel</a>
												<!-- <input type="button" value="Submit" name="submit" class="btn btn-theme btn-submit"/> -->
												<!-- <button type="button" class="btn btn-theme btn-submit">Submit</button> -->
												<input type="submit" name="Submit" value="Submit" class="btn btn-theme btn-submit" />
											</div>
							            </div>

									</div>
								</div>
							</div>

						</div>
					</div>
				</form>
				
			<?php } ?>

	</div>
</div>
<?php include('footer_js.php'); ?>
<script type="text/javascript" src="../fckeditor/fckeditor.js"></script>
<script type="text/javascript">
	window.onload = function() {
		var oFCKeditor = new FCKeditor('content');
		oFCKeditor.BasePath = "../fckeditor/";
		oFCKeditor.Config["EditorAreaCSS"] = "custom.css";
		oFCKeditor.ReplaceTextarea();
	}
</script>
<?php include('footer.php'); ?>