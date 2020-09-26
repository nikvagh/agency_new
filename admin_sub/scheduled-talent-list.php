
<?php
$page_selected = "scheduled";
include('header.php');
?>

<div id="page-wrapper">
	<!-- <div class="container-fluid"> -->
    	<div class="" id="main">

    		<div class="row">
	    		<div class="col-md-8">
	    			<h3>Scheduled Talent</h3>
	              	<div class="box box-theme">
	                	<div class="box-body">

								<!-- <br/>
								<table class="datatable table table-responsive table-striped">
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

											// $result = mysql_query("select * from agency_talent_casting atc
											// 						LEFT JOIN forum_users u ON atc.user_id = u.user_id
											// 						LEFT JOIN agency_profiles ap ON ap.user_id = u.user_id
											// 						WHERE atc.scheduled = 'Y'
											// 						GROUP BY u.user_id
											// 					");

											// if (mysql_num_rows($result) > 0) {
											// 	while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
											// 		echo '<tr>';
											// 		echo '<td>'.$row['firstname'].' '.$row['lastname'].'</td>';
											// 		echo '<td>'.$row['user_email'].'</td>';
											// 		echo '<td>'.$row['phone'].'</td>';
											// 		echo '<td>'.$row['country'].'</td>';
											// 		echo '<td>'.$row['city'].'</td>';
											// 		// <a href="notes-view.php?casting_id='.$row['casting_id'].'">Delete</a></td>
											// 		echo '<td><a href="scheduled-talent-view.php?user_id='.$row['user_id'].'" class="btn btn-theme btn-sm btn-flat">View</a></td>';
											// 		echo '</tr>';
											// 	}
											// }
										?>
									</tbody>
								</table> -->

								<table class="datatable table table-responsive table-striped">
									<thead>
										<tr>
											<td>Talent</td>
											<td>Email</td>
											<td>Phone</td>
											<td>city</td>
											<th></th>
										</tr>
									</thead>
									<tbody>
										<?php
											$sc_talent_sql = "SELECT am.*,ap.*,fu.* FROM agency_mycastings am
																LEFT JOIN agency_profiles ap ON ap.user_id = am.user_id
																LEFT JOIN forum_users fu ON fu.user_id = ap.user_id
																WHERE am.audition_list = 'Y' AND am.audition_book = 'Y'
																GROUP BY am.user_id";

											$sc_talent_res = mysql_query($sc_talent_sql);

											if (mysql_num_rows($sc_talent_res) > 0) {
												while ($row = mysql_fetch_assoc($sc_talent_res)) {
													?>
													<tr>
														<td><?php echo $row['firstname'].' '.$row['lastname']; ?></td>
														<td><?php echo $row['user_email']; ?></td>
														<td><?php echo $row['phone']; ?></td>
														<!-- <td><?php //echo $row['country']; ?></td> -->
														<td><?php echo $row['city']; ?></td>
														<td>
															<a href="<?php echo 'scheduled-talent-view.php?user_id='.$row['user_id']; ?>" class="btn btn-theme btn-sm btn-flat">View</a>
														</td>
													</tr>
													<?php
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
</script>
<?php include('footer.php'); ?>