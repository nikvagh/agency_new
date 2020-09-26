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
		if (!empty($_GET['castingid']) && !is_admin()) { // if there is a casting id, the we're editing this.  retrieve information from database
			$castingid = escape_data((int) $_GET['castingid']);
			$query = "SELECT * FROM agency_castings WHERE casting_id='$castingid'";
			$result = @mysql_query($query);
			if (@mysql_num_rows($result) == 0) { // If user does not access to project
				unset($_GET['castingid']);
			}

			$casting = sql_fetchrow($result);
			// echo "<pre>";print_r($casting);exit;
		}

?>

	<div style="width:660px; float:left">
		<h3><b>Casting Information:</b></h3>
		<p>Project Name : <?php echo $casting['job_title']; ?></p>
		<p>Casting Location: <?php echo $casting['location_casting']; ?></p>
		<p>Shoot Location: <?php echo $casting['location_shoot']; ?></p>
		<p>Shoot Date/Range: <?php echo $casting['shoot_date']; ?></p>
		<p>Casting Date: <?php echo $casting['casting_date']; ?></p>
		<p>Casting Director: <?php echo $casting['casting_director']; ?></p>
		<p>Company/Link:	 <?php echo $casting['company']; ?></p>
		<p>Client/Artist:	 <?php echo $casting['artist']; ?></p>
		<p>Job Type: 
			<?php
				$query_job = "SELECT group_concat(jobtype) as jobtype FROM agency_castings_jobtype WHERE casting_id='".$casting['casting_id']."'";
				$casting_job = sql_fetchrow($query_job);
				echo $casting_job['jobtype'];
			?>
		</p>
		<p>Union Status: 
			<?php echo $casting['union_status']; ?>
			<?php
				$query_union = "SELECT group_concat(union_name) as union_name FROM agency_castings_unions WHERE casting_id='".$casting['casting_id']."'";
				$casting_union = sql_fetchrow($query_union);
				echo $casting_union['union_name'];
			?>
		</p>
		<p>Day Rate: <?php echo $casting['rate_day']; ?></p>
		<p>Usage Rate: <?php echo $casting['rate_usage']; ?></p>
		<p>Usage - Type(s): <?php echo $casting['usage_type']; ?></p>
		<p>Usage - Term: <?php echo $casting['usage_time']; ?></p>
		<p>Usage - Area: <?php echo $casting['usage_location']; ?></p>
		<p>Notes: <?php echo $casting['notes']; ?></p>

		<br/><br/>
		<h3><b>Role Descriptions:</b></h3>

		<?php
			$query_role = "SELECT * FROM agency_castings_roles WHERE casting_id='".$casting['casting_id']."'";
			$casting_role = mysql_query($query_role);
			while($row = mysql_fetch_array($casting_role, MYSQL_ASSOC)) {
		?>
				<p>Character Name:	 <?php echo $row['name']; ?></p>
				<p>Age Range:	 <?php echo $row['age_lower']; ?> to <?php echo $row['age_upper']; ?></p>
				<p>Gender:
					<?php
						$query_role_gender = "SELECT group_concat(var_value) as gender FROM agency_castings_roles_vars WHERE casting_id='".$casting['casting_id']."' AND role_id='".$row['role_id']."' AND var_type = 'gender' ";
						$casting_role_gender = sql_fetchrow($query_role_gender);
						echo $casting_role_gender['gender'];
					?>
				</p>
				<p>Ethnicity:	 <?php echo $row['ethnicity']; ?></p>
				<p>Description:	 <?php echo $row['description']; ?></p>
				<!-- <p>Attachment:	 <?php //echo $row['rolefile']; ?></p> -->

				<hr/>
		<?php } ?>
	</div>

<?php } ?>
<?php
@include('includes/footer.php');
?>