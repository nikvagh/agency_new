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
		Project Name : <?php echo $casting['job_title']; ?><br/>
		Casting Location: <?php echo $casting['location_casting']; ?><br/>
		Shoot Location: <?php echo $casting['location_shoot']; ?><br/>
		Shoot Date/Range: <?php echo $casting['shoot_date']; ?><br/>
		Casting Date: <?php echo $casting['casting_date']; ?><br/>
		Casting Director: <?php echo $casting['casting_director']; ?><br/>
		Company/Link:	 <?php echo $casting['company']; ?><br/>
		Client/Artist:	 <?php echo $casting['artist']; ?><br/>
		Job Type: 
			<?php
				$query_job = mysql_query("SELECT group_concat(jobtype) as jobtype FROM agency_castings_jobtype WHERE casting_id='".$casting['casting_id']."'");
				$casting_job = sql_fetchrow($query_job);
				echo $casting_job['jobtype'];
			?>
		<br/>
		Union Status: 
			<?php echo $casting['union_status']; ?>
			<?php
				$query_union = mysql_query("SELECT group_concat(union_name) as union_name FROM agency_castings_unions WHERE casting_id='".$casting['casting_id']."'");
				$casting_union = sql_fetchrow($query_union);
				echo $casting_union['union_name'];
			?>
		<br/>
		Day Rate: <?php echo $casting['rate_day']; ?><br/>
		Usage Rate: <?php echo $casting['rate_usage']; ?><br/>
		Usage - Type(s): <?php echo $casting['usage_type']; ?><br/>
		Usage - Term: <?php echo $casting['usage_time']; ?><br/>
		Usage - Area: <?php echo $casting['usage_location']; ?><br/>
		Notes: <?php echo $casting['notes']; ?><br/>

		<br/><br/>
		<h3><b>Role Descriptions:</b></h3>

		<?php
			$query_role = "SELECT * FROM agency_castings_roles WHERE casting_id='".$casting['casting_id']."'";
			$casting_role = mysql_query($query_role);
			while($row = mysql_fetch_array($casting_role, MYSQL_ASSOC)) {
		?>
				Character Name:	 <?php echo $row['name']; ?></br/>
				Age Range:	 <?php echo $row['age_lower']; ?> to <?php echo $row['age_upper']; ?><br/>
				Gender:
					<?php
						$query_role_gender = mysql_query("SELECT group_concat(var_value) as gender FROM agency_castings_roles_vars WHERE casting_id=".$casting['casting_id']." AND role_id=".$row['role_id']." AND var_type = 'gender' ");
						$casting_role_gender = sql_fetchrow($query_role_gender);
						// echo "<pre>111";print_r($casting_role_gender);exit;
							echo $casting_role_gender['gender'];
					?>
				<br/>
				Ethnicity:	 
					<?php
						$query_role_ethnicity = mysql_query("SELECT group_concat(var_value) as ethnicity FROM agency_castings_roles_vars WHERE casting_id=".$casting['casting_id']." AND role_id=".$row['role_id']." AND var_type = 'ethnicity' ");
						$casting_role_ethnicity = sql_fetchrow($query_role_ethnicity);
						// echo "<pre>111";print_r($casting_role_ethnicity);exit;
						echo $casting_role_ethnicity['ethnicity'];
					?>
				<br/>
				Description:	 <?php echo $row['description']; ?><br/>
				<!-- <p>Attachment:	 <?php //echo $row['rolefile']; ?></p> -->

				<hr/>
		<?php } ?>
	</div>

<?php } ?>
<?php
@include('includes/footer.php');
?>