
<?php 
	$page = "affiliate";
	$page_selected = "affiliate";
	include('header.php'); 
?>

	<div id="page-wrapper">
	    <div class="" id="main">
	        <div class="row">
	        	<div class="col-md-12">
	        		<h3>Mentor</h3>

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

							<div class="col-md-6">
								<div class="box box-theme">
									<div class="box-header with-border">
										<h3 class="box-title">Mentor Information</h3>
				                	</div>

				                	<div class="box-footer no-padding no-border">
						                <ul class="nav nav-stacked">

					                		<?php if (isset($_GET['id'])) { ?>
												<?php
												        $id = $_GET['id'];
												        $query = "SELECT * FROM agency_mentors WHERE mentor_id='$id'";  // check to see if name already used.
												        $result = mysql_query($query);
												        if ($row = mysql_fetch_assoc($result)) {
												            $firstname = $row['firstname'];
												            $lastname = $row['lastname'];
												            $email = $row['paypal_email'];
												            $code = $row['mentor_code'];
												?>	
												<li><a>First Name: <?php echo $firstname; ?> </a></li>
												<li><a>Last Name: <?php echo $lastname; ?> </a></li>
												<li><a>Email: <?php echo $email; ?> </a></li>
												<li><a>Code: <?php echo $code; ?> </a></li>
												<li>
													<?php
														$query2 = "SELECT * FROM agency_profiles WHERE mentor_id='$id'";
														$result2 = mysql_query($query2);
													?>
													<div class="col-sm-12">REFERRED MEMBERS:<br />
														<?php while ($row2 = mysql_fetch_assoc($result2)) { ?>
												                <br />
												                <a href="../profile-view.php?user_id=<?php echo $row2['user_id']; ?>">
												                	<?php echo $row2['firstname'] . ' ' . $row2['lastname']; ?>
												                </a> : <?php echo $row2['pay_term']; ?><br />
												        <?php } ?>
												    </div>
												</li>
												<li>
													<form action="mentor_edit.php?id=<?php echo $id; ?>" method="post">
														<input name="submit" type="submit" value="Edit Mentor Information" class="btn btn-primary btn-flat btn-block">
													</form>
												</li>
												<li>
													<form action="mentors.php" method="post">
														<input name="delete" type="hidden" value="<?php echo $id; ?>">
									    				<input name="submit" type="submit" value="Delete Mentor" onclick="return confirm('This Mentor is about to be PERMANENTLY DELETED from the database.  Are you sure you want to delete this Mentor?')" class="btn btn-danger btn-flat btn-block">
									    			</form>
												</li>
												<?php } else { ?>
											           <li><a>There is no Mentor with this ID.  Mentor may have been deleted.</a></li>
											    <?php } ?>
											<?php } else { ?>
												<li><a>Page accessed in error.</a></li>
											<?php } ?>

						                </ul>
						            </div>

						    	</div>
							</div>

					</div>
				</div>
			</div>
		</div>
	</div>

<?php include('footer_js.php'); ?>
<script>
	if (window.history.replaceState) {
		window.history.replaceState( null, null, window.location.href );
	}
</script>
<?php include('footer.php'); ?>