<?php
$page = "notes_list";
$page_selected = "notes";
include('header.php');
include('../forms/definitions.php');
?>

<div id="page-wrapper">
	<div id="main">

		<div class="row">
			<div class="col-md-7">
				<h3>Notes</h3>
				<div class="box box-theme">
					<div class="box-header">
						<a href="notes-create.php" class="btn btn-primary pull-right">Create Notes </a>
					</div>
					<div class="box-body">
						<table class="datatable table table-responsive table-striped" align="center">
							<thead>
								<tr>
									<th>Id</th>
									<th>Date</th>
									<th>Title</th>
									<th>Status</th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								<?php
									$result = mysql_query("select * from agency_notes");
									if (mysql_num_rows($result) > 0) {
										while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
											echo '<tr>';
											echo '<td>'.$row['note_id'].'</td>';
											echo '<td>'.date('Y-m-d',strtotime($row['created_at'])).'</td>';
											echo '<td>'.$row['title'].'</td>';
											echo '<td>'.$row['status'].'</td>';
											// <a href="notes-view.php?casting_id='.$row['casting_id'].'">Delete</a></td>
											echo '<td><a href="notes-update.php?note_id='.$row['note_id'].'" class="btn btn-primary">Edit</a>';
											// echo '<a href="notes-view.php?note_id='.$row['note_id'].'" class="btn btn-primary">View</a>';
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
</div>
<?php include('footer_js.php'); ?>
<script src="../dashboard/assets/DataTables/datatables.min.js"></script> 
<script src="../dashboard/assets/DataTables/dataTables.bootstrap.min.js"></script> 
<script>
  $(document).ready( function () {
      $('.datatable').DataTable({
      	"order": [[ 0, "desc" ]],
	        'columnDefs': [{
		    'targets': [4], /* column index */
		    'orderable': false, /* true or false */
		}]
      });
  });
</script>
<?php include('footer.php'); ?>