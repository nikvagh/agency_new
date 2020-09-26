
<?php
include('header.php');

if(isset($_GET['talent_request_id'])){

	if(mysql_query("UPDATE agency_talent_request 
	  				SET request_status = 'approve'
                  	WHERE talent_request_id = ".$_GET['talent_request_id']."
                ")){
					$notification['success'] = "Status updated successfully.";
				}

}
?>

<div id="page-wrapper">
	<div class="container-fluid">
    	<div class="well" id="main">

    		<?php if(isset($notification['success'])){ ?>
		        <div class="alert alert-success" role="alert">
		            <?php echo $notification['success']; ?>
		        </div>
	        <?php } ?>

			<?php $counter = 0; ?>
				<!-- <a href="notes-create.php" class="btn btn-primary">Create Notes </a> -->
				<!-- <br/> -->
				<br/>
				<table class="datatable table table-responsive table-striped" align="center">
					<thead>
						<tr>
							<th>Project</th>
							<th>Talent</th>
							<th>Request From</th>
							<th>Date Time</th>
							<th>Location</th>
							<th>Status</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						<?php
							$result = mysql_query("select atr.*,ap.firstname,ap.lastname,ac.job_title,ap1.firstname as firstname_by,ap1.lastname as lastname_by 
													from agency_talent_request atr
													LEFT JOIN agency_profiles ap ON ap.user_id = atr.user_id 
													LEFT JOIN agency_profiles ap1 ON ap1.user_id = atr.request_by 
													LEFT JOIN agency_castings ac ON ac.casting_id = atr.casting_id
													where atr.request_status = 'pending'
												");
							if (mysql_num_rows($result) > 0) {
								while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
									echo '<tr>';
									echo '<td>'.$row['job_title'].'</td>';
									echo '<td>'.$row['firstname'].' '.$row['lastname'].'</td>';
									echo '<td>'.$row['firstname_by'].' '.$row['lastname_by'].'</td>';

									echo '<td>'.$row['request_date'].' '.$row['request_time'].'</td>';
									echo '<td>'.$row['request_location'].'</td>';
									echo '<td>'.$row['request_status'].'</td>';
									// <a href="notes-view.php?casting_id='.$row['casting_id'].'">Delete</a></td>
									echo '<td><a href="talent-request-pending-list.php?talent_request_id='.$row['talent_request_id'].'" class="btn btn-success">Approve Status</a></td>';
									echo '</tr>';
								}
							}
						?>
					</tbody>
				</table>

		</div>
	</div>
</div>
<?php include('footer_js.php'); ?>
<script src="../dashboard/assets/DataTables/datatables.min.js"></script> 
<script src="../dashboard/assets/DataTables/dataTables.bootstrap.min.js"></script> 
<script>
  $(document).ready( function () {
      $('.datatable').DataTable();
  });
</script>
<?php include('footer.php'); ?>