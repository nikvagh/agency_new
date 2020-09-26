<?php
include('header.php');
include('../forms/definitions.php');
?>


<?php $counter = 0; ?>
<div align="center">
	<a href="notes-create.php">Create Notes </a>
	<br/>
	<br/>
	<table bgcolor="#EEEEEE" cellpadding="5" cellspacing="0" border="1" align="center">
		<thead>
			<tr>
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
						echo '<td>'.date('Y-m-d',strtotime($row['created_at'])).'</td>';
						echo '<td>'.$row['title'].'</td>';
						echo '<td>'.$row['status'].'</td>';
						// <a href="notes-view.php?casting_id='.$row['casting_id'].'">Delete</a></td>
						echo '<td><a href="notes-update.php?note_id='.$row['note_id'].'">Edit</a> <a href="notes-view.php?note_id='.$row['note_id'].'">View</a></td>';
						echo '</tr>';
					}
				}
			?>
		</tbody>
	</table>
</div>
<?php include('footer.php'); ?>