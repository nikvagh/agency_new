<?php 
$page = "notes_form";
$page_selected = "notes";
include('header.php'); 
?>

<div id="page-wrapper">
	<div class="" id="main">

		<?php
		$err_msg = '';
		if (isset($_SESSION['admin'])) {
			$success = FALSE; // flag for showing or not showing form

			if (isset($_POST['Submit']) && isset($_GET['note_id'])) {

				$id = $_GET['note_id'];
				if (stripslashes(trim($_POST['title']))) {
					$title = escape_data($_POST['title']);
				} else {
					$title = FALSE;
					$err_msg .= '<li>You must enter a Title</li>';
				}

				$subtitle = '';

				if (stripslashes(trim($_POST['content']))) {
					$content = escape_data($_POST['content']);
				} else {
					$content = FALSE;
					$err_msg .= '<li>You must enter Content</li>';
				}
				// echo $err_msg;

				$status = escape_data($_POST['status']);

				if ($title && $content) { // If everything's OK.
					$query = "UPDATE agency_notes SET title='$title', Content='$content', status='$status' WHERE note_id='$id' LIMIT 1";
					$result = mysql_query($query);

					$success = TRUE;
					echo '<br /><br /><div align="center"><font size="+1" color="#990000"><b>Your Note has been updated.  Thank you.</b></font><br /><br />';
					// include('makesitemap.php');
				}
			} else if (isset($_GET['note_id']) && !isset($_POST['Submit'])) {
				$id = $_GET['note_id'];
				$query = "SELECT * FROM agency_notes WHERE note_id='$id'";
				$result = mysql_query($query);
				if ($row = mysql_fetch_array($result, MYSQL_ASSOC)) { // If there are projects.
					$title = $row['title'];
					$content = $row['content'];
					$status = $row['status'];
				} else {
					$success = TRUE; // tag to not show form if can't extract info from database
					echo '<br /><br /><div align="center"><font size="+1" color="#990000"><b>Database error.  Please contact administrator.</b></font><br /><br /><br /></div>';
				}
			}else if (isset($_POST['delete'])){ 
				$query = "DELETE from agency_notes WHERE note_id=".$_POST['note_id']."";
				if(mysql_query($query)){
					$url = 'notes-list.php';
					header("Location: $url");
				}
			} else {
				$success = TRUE; // tag to not show form if can't extract info from database
				echo '<br /><br /><div align="center"><font size="+1" color="#990000"><b>Database error.  Please contact administrator.</b></font><br /><br /><br /></div>';
			}

		?>

		<?php if (!$success) { ?>

			
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
								<form enctype="multipart/form-data" name="form1" method="post" action="notes-update.php?note_id=<?php if (isset($id)) echo $id; ?>">
									<div class="box box-theme">
										<div class="box-header with-border">
							            	<h3 class="box-title">Edit Note</h3>
							            </div>
							            <div class="box-body">
							            	<div class="form-group">
							                  	<label class="control-label text-right">Title *</label>
							                  	<input type="text" name="title" placeholder="Enter Title" class="form-control" size="50" maxlength="250" value="<?php if (isset($_POST['title'])) echo $_POST['title'];
																								else if (isset($title)) echo $title; ?>">
							                </div>

							                <div class="form-group">
							                  	<label class="control-label text-right">Content </label>
							                  	<textarea name="content" id="content" cols="60" rows="20" class="form-control"><?php if (isset($_POST['content'])){ echo $_POST['content']; }else if (isset($content)){ echo $content; } ?></textarea>
							                </div>

							                <div class="form-group">
												<select class="form-control" name="status" id="status">
													<option value="Enable" <?php if (isset($_POST['status']) && $_POST['status'] == "Enable") echo "selected"; else if (isset($status) && $status == "Enable") echo "selected"; ?>>Enable</option>
													<option value="Disable" <?php if (isset($_POST['status']) && $_POST['status'] == "Disable") echo "selected"; else if (isset($status) && $status == "Disable") echo "selected"; ?>>Disable</option>
												</select>
							                </div>
							            </div>

							            <div class="box-footer">
											<div class="text-right">
												<input type="hidden" name="note_id" value="<?php echo $id; ?>">
												<a type="button" href="notes-list.php" class="btn btn-default">Cancel</a>
												<!-- <input type="button" value="Submit" name="submit" class="btn btn-theme btn-submit"/> -->
												<!-- <button type="button" class="btn btn-theme btn-submit">Submit</button> -->
												<input type="submit" name="Submit" value="Submit" class="btn btn-theme btn-submit" />
											</div>
							            </div>
									</div>
								</form>

								<!-- <div class="box">
									<div class="box-footer">
										<form action="notes-update.php" method="post">
											<input type="submit" name="delete" value="Delete Note" class="btn btn-danger" onClick="return confirm('This Note Item is about to be PERMANENTLY DELETED from the database.  Are you sure you want to delete this Note Item forever?')">
											
										</form>
									</div>
								</div> -->

							</div>
						</div>

					</div>
				</div>

				<script type="text/javascript" src="../fckeditor/fckeditor.js"></script>
				<script type="text/javascript">
					var oFCKeditor = new FCKeditor('content');
					oFCKeditor.BasePath = "../fckeditor/";
					oFCKeditor.Config["EditorAreaCSS"] = "custom.css";
					oFCKeditor.ReplaceTextarea();
					// $(".btn-submit").click(function(e){
					// 	// console.log($('#article_content').attr('id'));
					// 	$("#service-provider-form").submit();
					// });
				</script>
			
		<?php } ?>

		<?php 
			} else {
				$url = "index.php";
				ob_end_clean(); // Delete the buffer.
				header("Location: $url");
				exit(); // Quit the script.
			}
		?>
	</div>
</div>

<?php include('footer_js.php'); ?>

<?php include('footer.php'); ?>