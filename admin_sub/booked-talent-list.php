
<?php
	$page_selected = "booked";
	include('header.php');
?>

<div id="page-wrapper">
	<!-- <div class="container-fluid"> -->
    	<div class="" id="main">

    		<div class="row">
	    		<div class="col-md-6">
	    			<?php if($_GET['booking'] && $_GET['booking'] == "casting"){ ?>
			    		<h3>Casting Bookings</h3>
			    	<?php }elseif($_GET['booking'] && $_GET['booking'] == "confirmed"){ ?>
						<h3>Confirmed Bookings</h3>
			    	<?php } ?>
	    			
	              	<div class="box box-theme">
	                	<div class="box-body">

				    		<?php if(!empty($notification)){ ?>
				    			<?php if($notification['success']){ ?>
									<div class="alert alert-success" role="alert">
									  	<?php echo $notification['success']; ?>
									</div>
								<?php } ?>
								<?php if($notification['error']){ ?>
									<div class="alert alert-danger" role="alert">
									  	<?php echo $notification['error']; ?>
									</div>
								<?php } ?>
				    		<?php } ?>

				    		<?php if($_GET['booking'] && $_GET['booking'] == "casting"){ ?>
					    		<a class="toggle-btn btn btn-theme btn-flat" href="booked-talent-list.php?booking=confirmed"> View Confirmed Bookings</a>
					    	<?php }elseif($_GET['booking'] && $_GET['booking'] == "confirmed"){ ?>
								<a class="toggle-btn btn btn-theme btn-flat" href="booked-talent-list.php?booking=casting"> View Casting Bookings</a>
					    	<?php } ?>
					    	<br/><br/>

							<?php $counter = 0; ?>
							<table class="datatable table table-responsive table-striped table-bordered">
								<thead>
									<tr>
										<th>Name</th>
										<th>Email</th>
										<th>Phone</th>
										<th>Country</th>
										<th>City</th>
										<th></th>
									</tr>
								</thead>
								<tbody>
									<?php
										$cond = "";
										if($_GET['booking'] == "casting"){
											$cond = " AND atc.casting_booking = 'Y'";
										}elseif($_GET['booking'] == "confirmed"){
											$cond = " AND atc.confirm_booking = 'Y'";
										}
										// $result = mysql_query("select * from forum_users u
										// 						LEFT JOIN agency_profiles ap ON ap.user_id = u.user_id 
										// 						LEFT JOIN agency_talent_casting atc ON ap.user_id = atc.user_id 
										// 						WHERE ap.account_type = 'talent' ".$cond."
										// 					");

										$result = mysql_query("select * from agency_talent_casting atc
																	LEFT JOIN forum_users u ON atc.user_id = u.user_id
																	LEFT JOIN agency_profiles ap ON ap.user_id = u.user_id
																	WHERE 1 ".$cond."
																	GROUP BY u.user_id
																");

										// print_r($result);
									?>

									<?php
										if (mysql_num_rows($result) > 0) {
											while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
												echo '<tr>';
												echo '<td>'.$row['firstname'].' '.$row['lastname'].'</td>';
												echo '<td>'.$row['user_email'].'</td>';
												echo '<td>'.$row['phone'].'</td>';
												echo '<td>'.$row['country'].'</td>';
												echo '<td>'.$row['city'].'</td>';
												// <a href="notes-view.php?casting_id='.$row['casting_id'].'">Delete</a></td>
												echo '<td><a href="booked-talent-view.php?user_id='.$row['user_id'].'&booking='.$_GET['booking'].'" class="btn btn-theme btn-flat">View</a>';
												// echo '<form action="" method="post" class="form-inline">';
												// echo '<input type="hidden" name="reminder_email" value="'.$row['user_email'].'"/>';
												// echo '<button type="submit" class="btn btn-info" value="reminder">Send Reminder</button>';
												// echo '</form>';
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
	<!-- </div> -->
</div>

<?php include('footer_js.php'); ?>
<script src="../dashboard/assets/DataTables/datatables.min.js"></script> 
<script src="../dashboard/assets/DataTables/dataTables.bootstrap.min.js"></script> 
<script>
	$(document).ready( function () {
	    $('.datatable').DataTable();
	});

	$('.cb-value').click(function() {
	  	var mainParent = $(this).parent('.toggle-btn');
	  	if($(mainParent).find('input.cb-value').is(':checked')) {
	    	$(mainParent).addClass('active');
	  	} else {
	    	$(mainParent).removeClass('active');
	  	}
	})
</script>
<?php include('footer.php'); ?>