<?php
session_start(); // for temporary login, a session is needed
@include('includes/header.php');
// include('forms/definitions.php');
if (agency_account_type() == 'client' && is_active()) { // only clients can access this page

	$loggedin = (int) $_SESSION['user_id'];
	$submitmessage = '';
	$location_casting = NULL; // initialize to avoid error/notice
	$location_shoot = NULL;

	// if casting ID is sent for edit, make sure user has permission to edit it.
	// if (!empty($_GET['castingid']) && !is_admin()) { // if there is a casting id, the we're editing this.  retrieve information from database
	// 	$castingid = escape_data((int) $_GET['castingid']);
	// 	$query = "SELECT * FROM agency_castings WHERE posted_by='$loggedin'";
	// 	$result = @mysql_query($query);
	// 	if (@mysql_num_rows($result) == 0) { // If user does not access to project
	// 		unset($_GET['castingid']);
	// 	}
	// }

?>

	<div align="center" style="width:660px; float:left">
		<div class="AGENCY_ClientPageTitle">Casting</div>
		<table bgcolor="#EEEEEE" cellpadding="4" border="1" cellspacing="0" align="center">
			<tr>
				<th>Project Name</th>
				<th>Company</th>
				<th>Location Casting</th>
				<th>Location Shoot</th>
				<th>Casting Director</th>
				<th></th>
			</tr>
		
			<?php 
				if (agency_account_type() == 'client'){
					$query_span = " AND posted_by='$loggedin'";
				}elseif(agency_account_type() == 'superadmin'){
					$query_span = "";
				}
				$query_casting = "SELECT * FROM agency_castings WHERE 1 ".$query_span;
				$result_casting = @mysql_query($query_casting);
				while($row = mysql_fetch_array($result_casting, MYSQL_ASSOC)) {
			?>
				<tr>
					<td><?php echo $row['job_title']; ?></td>
					<td><?php echo $row['company']; ?></td>
					<td><?php echo $row['location_casting']; ?></td>
					<td><?php echo $row['location_shoot']; ?></td>
					<td><?php echo $row['casting_director']; ?></td>
					<td><a href="#">view</a></td>
				</tr>
			<?php } ?>
		</table>
	</div>
	

<?php } ?>
<?php
@include('includes/footer.php');
?>