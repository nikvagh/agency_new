<?php
include('includes/mysql_connect.php');
include('includes/agency_functions.php');
include('forms/definitions.php');
?>
<body>
<div style="font-family:Arial, Helvetica, sans-serif">
	<?php
	
		if (!empty($_GET['u']) && (!empty($_GET['card_type']))) {
			$user_id = $userid = $_GET['u'];
			$type = $_GET['card_type'];

			$folder_profile_pic = 'uploads/users/' . $user_id . '/profile_pic/';
			$folder_profile_pic_thumb = $folder_profile_pic . 'thumb/';
			$folder_headshot = 'uploads/users/' . $user_id . '/headshot/';
			$folder_headshot_thumb = $folder_headshot . 'thumb/';
			$folder_card = 'uploads/users/' . $user_id . '/portfolio/';
			$folder_card_thumb = $folder_card . 'thumb/';
			$folder_audio = 'uploads/users/' . $user_id . '/audio/';
			$folder_portfolio = 'uploads/users/' . $user_id . '/portfolio/';
			$folder_portfolio_thumb = $folder_portfolio . 'thumb/';

			// first get the folder name
			$sql = "SELECT ap.*,fu.user_avatar,fu.user_email FROM agency_profiles ap
				LEFT JOIN forum_users fu ON ap.user_id = fu.user_id
				WHERE ap.user_id='$userid'";
			$result = mysql_query($sql);

	?>
		<?php if ($userInfo = $userinfo = sql_fetchrow($result)) { ?>

			<?php if ($type == "compcard") { ?>

				<table class="table" cellspacing="10" cellpadding="0">
					<tr>
						<td>
							<?php
							if (file_exists($folder_profile_pic . $userInfo['user_avatar'])) {
								$profile_pic = $folder_profile_pic . $userInfo['user_avatar'];
							} else {
								$profile_pic = $base_url . "images/friend.jpg";
							}
							// echo "<h1>" . $profile_pic . "</h1>";
							?>
							<a style="">
								<img src="<?php echo $profile_pic; ?>" style="width:500px;height:500px;" />
							</a>
							<!-- <img src="http://tamba30.us/theagency/uploads/users/104/profile_pic/thumb/128x128_1589030642_5945MCGRW426.jpg" /> -->
						</td>
						<td>
							<?php
							$sql_photos = "SELECT ap.* FROM agency_photos ap
									WHERE ap.user_id='$userid' limit 4";
							$sql_photos = mysql_query($sql_photos);
							?>
							<table>
								<?php $cnt_card = 1; ?>
								<?php while ($row = sql_fetchrow($sql_photos)) { ?>
									<?php if (file_exists($folder_portfolio . $row['filename'])) { ?>
										<?php if ($cnt_card % 2 != 0) { ?>
											<tr>
											<?php } ?>
											<td>
												<img src="<?php echo $folder_portfolio . $row['filename']; ?>" style="width:250px;height:250px" />
											</td>
											<?php if ($cnt_card % 2 == 0) { ?>
											</tr>
										<?php } ?>
									<?php } ?>
									<?php $cnt_card++; ?>
								<?php } ?>
							</table>
						</td>
					</tr>

					<tr>
						<td align="center" style="padding:0px 20px;">
							<h2 style="text-transform:uppercase"><?php echo $userInfo['firstname'] . ' ' . $userInfo['lastname']; ?></h2>
							<br/>
							<h3 style="text-align:center;font-weight:normal;">
												
								Height: <?php echo $userInfo['height_ft']."'".$userInfo['height_inch'].'"'; ?> &nbsp;&nbsp;&nbsp;&nbsp;
								Weight: <?php echo $userInfo['weight']; ?> &nbsp;&nbsp;&nbsp;&nbsp;
								
								<?php if($userInfo['gender'] == 'F'){ ?>
									bust: <?php echo $userInfo['bust'].'" C'; ?> &nbsp;&nbsp;&nbsp;&nbsp;
									Waist: <?php echo $userInfo['waist'].'"'; ?> &nbsp;&nbsp;&nbsp;&nbsp;
									Hip: <?php echo $userInfo['hips'].'"'; ?> &nbsp;&nbsp;&nbsp;&nbsp;
									Dress: <?php echo $userInfo['dress'].' US'; ?> &nbsp;&nbsp;&nbsp;&nbsp;
									Shoe: <?php echo $userInfo['shoe'].' US'; ?> &nbsp;&nbsp;&nbsp;&nbsp;
								<?php }elseif($userInfo['gender'] == 'M'){ ?>
									Shirt: <?php echo $userInfo['shirt'].'" '; ?> &nbsp;&nbsp;&nbsp;&nbsp;
									Kids: <?php echo $userInfo['kids'].'"'; ?> &nbsp;&nbsp;&nbsp;&nbsp;
									Glove: <?php echo $userInfo['glove'].'"'; ?> &nbsp;&nbsp;&nbsp;&nbsp;
									Cup: <?php echo $userInfo['cup'].' '; ?> &nbsp;&nbsp;&nbsp;&nbsp;
									Jacket: <?php echo $userInfo['jacket'].' '; ?> &nbsp;&nbsp;&nbsp;&nbsp;
									Pants: <?php echo $userInfo['pants'].' '; ?> &nbsp;&nbsp;&nbsp;&nbsp;
									Inseam: <?php echo $userInfo['inseam'].' '; ?> &nbsp;&nbsp;&nbsp;&nbsp;
									Hat: <?php echo $userInfo['hat'].''; ?> &nbsp;&nbsp;&nbsp;&nbsp;
								<?php } ?>
								
								Hair: <?php echo $userInfo['hair_color']; ?> &nbsp;&nbsp;&nbsp;&nbsp;
								Eye: <?php echo $userInfo['eye_color'] . ' ' . $userInfo['eye_shape']; ?> &nbsp;&nbsp;&nbsp;&nbsp;
							</h3>
						</td>
						<td align="center" style="padding:0px 20px;">
							<h3 style="text-align:center;font-weight:normal;">
								<?php echo $userInfo['address']; ?><br/>
								T: <?php echo $userInfo['phone']; ?> &nbsp;&nbsp;&nbsp;&nbsp;
								Email: <?php echo $userInfo['user_email']; ?> &nbsp;&nbsp;&nbsp;&nbsp;
							</h3>
						</td>
					</tr>
				</table>

			<?php } ?>

			<?php if ($type == "thumbnails") { ?>

				<h3 style="text-transform:uppercase;text-align:center"><?php echo $userInfo['firstname'] . ' ' . $userInfo['lastname']; ?></h3>
				<h3 style="text-align:center;font-weight:normal;">
									
					Height: <?php echo $userInfo['height_ft']."'".$userInfo['height_inch'].'"'; ?> &nbsp;&nbsp;&nbsp;&nbsp;
					Weight: <?php echo $userInfo['weight']; ?> &nbsp;&nbsp;&nbsp;&nbsp;
					
					<?php if($userInfo['gender'] == 'F'){ ?>
						bust: <?php echo $userInfo['bust'].'" C'; ?> &nbsp;&nbsp;&nbsp;&nbsp;
						Waist: <?php echo $userInfo['waist'].'"'; ?> &nbsp;&nbsp;&nbsp;&nbsp;
						Hip: <?php echo $userInfo['hips'].'"'; ?> 	&nbsp;&nbsp;&nbsp;&nbsp;
						Dress: <?php echo $userInfo['dress'].' US'; ?> &nbsp;&nbsp;&nbsp;&nbsp;
						Shoe: <?php echo $userInfo['shoe'].' US'; ?> &nbsp;&nbsp;&nbsp;&nbsp;
					<?php }elseif($userInfo['gender'] == 'M'){ ?>
						Shirt: <?php echo $userInfo['shirt'].'" '; ?> &nbsp;&nbsp;&nbsp;&nbsp;
						Kids: <?php echo $userInfo['kids'].'"'; ?> &nbsp;&nbsp;&nbsp;&nbsp;
						Glove: <?php echo $userInfo['glove'].'"'; ?> &nbsp;&nbsp;&nbsp;&nbsp;
						Cup: <?php echo $userInfo['cup'].' '; ?> &nbsp;&nbsp;&nbsp;&nbsp;
						Jacket: <?php echo $userInfo['jacket'].' '; ?> &nbsp;&nbsp;&nbsp;&nbsp;
						Pants: <?php echo $userInfo['pants'].' '; ?> &nbsp;&nbsp;&nbsp;&nbsp;
						Inseam: <?php echo $userInfo['inseam'].' '; ?> &nbsp;&nbsp;&nbsp;&nbsp;
						Hat: <?php echo $userInfo['hat'].''; ?> &nbsp;&nbsp;&nbsp;&nbsp;
					<?php } ?>
					
					Hair: <?php echo $userInfo['hair_color']; ?> &nbsp;&nbsp;&nbsp;&nbsp;
					Eye: <?php echo $userInfo['eye_color'] . ' ' . $userInfo['eye_shape']; ?> &nbsp;&nbsp;&nbsp;&nbsp;

					<?php //echo $userInfo['address']; ?><br/>
					<!-- T: <?php //echo $userInfo['phone']; ?> &nbsp;&nbsp;&nbsp;&nbsp; -->
					<!-- Email: <?php //echo $userInfo['user_email']; ?> &nbsp;&nbsp;&nbsp;&nbsp; -->
				</h3>

				<div style="text-align: center;">
					<?php
						if (file_exists($folder_profile_pic . $userInfo['user_avatar'])) {
							$profile_pic = $folder_profile_pic . $userInfo['user_avatar'];
						} else {
							$profile_pic = $base_url . "images/friend.jpg";
						}
					?>
					<img src="<?php echo $profile_pic; ?>" style="height:150px;" />
					<?php
						$sql_photos = "SELECT ap.* FROM agency_photos ap
								WHERE ap.user_id='$userid' limit 13";
						$sql_photos = mysql_query($sql_photos);
					?>
					<?php $cnt_card = 1; ?>
					<?php while ($row = sql_fetchrow($sql_photos)) { ?>
						<?php if (file_exists($folder_portfolio . $row['filename'])) { ?>
							<img src="<?php echo $folder_portfolio . $row['filename']; ?>" style="height:150px" />
						<?php } ?>
						<?php $cnt_card++; ?>
					<?php } ?>
				</div>

			<?php } ?>


			<?php if ($type == "large") { ?>

				<div style="text-align: center;width:650px;margin:auto">
					<?php
						if (file_exists($folder_profile_pic . $userInfo['user_avatar'])) {
							$profile_pic = $folder_profile_pic . $userInfo['user_avatar'];
						} else {
							$profile_pic = $base_url . "images/friend.jpg";
						}
					?>
					<img src="<?php echo $profile_pic; ?>" style="height:550px" />
						
					<h3 style="text-transform:uppercase;text-align:center"><?php echo $userInfo['firstname'] . ' ' . $userInfo['lastname']; ?></h3>
					<h3 style="text-align:center;font-weight:normal;">
										
							Height: <?php echo $userInfo['height_ft']."'".$userInfo['height_inch'].'"'; ?> &nbsp;&nbsp;&nbsp;&nbsp;
							Weight: <?php echo $userInfo['weight']; ?> &nbsp;&nbsp;&nbsp;&nbsp;
						
							<?php if($userInfo['gender'] == 'F'){ ?>
								bust: <?php echo $userInfo['bust'].'" C'; ?> &nbsp;&nbsp;&nbsp;&nbsp;
								Waist: <?php echo $userInfo['waist'].'"'; ?> &nbsp;&nbsp;&nbsp;&nbsp;
								Hip: <?php echo $userInfo['hips'].'"'; ?> 	&nbsp;&nbsp;&nbsp;&nbsp;
								Dress: <?php echo $userInfo['dress'].' US'; ?> &nbsp;&nbsp;&nbsp;&nbsp;
								Shoe: <?php echo $userInfo['shoe'].' US'; ?> &nbsp;&nbsp;&nbsp;&nbsp;
							<?php }elseif($userInfo['gender'] == 'M'){ ?>
								Shirt: <?php echo $userInfo['shirt'].'" '; ?> &nbsp;&nbsp;&nbsp;&nbsp;
								Kids: <?php echo $userInfo['kids'].'"'; ?> &nbsp;&nbsp;&nbsp;&nbsp;
								Glove: <?php echo $userInfo['glove'].'"'; ?> &nbsp;&nbsp;&nbsp;&nbsp;
								Cup: <?php echo $userInfo['cup'].' '; ?> &nbsp;&nbsp;&nbsp;&nbsp;
								Jacket: <?php echo $userInfo['jacket'].' '; ?> &nbsp;&nbsp;&nbsp;&nbsp;
								Pants: <?php echo $userInfo['pants'].' '; ?> &nbsp;&nbsp;&nbsp;&nbsp;
								Inseam: <?php echo $userInfo['inseam'].' '; ?> &nbsp;&nbsp;&nbsp;&nbsp;
								Hat: <?php echo $userInfo['hat'].''; ?> &nbsp;&nbsp;&nbsp;&nbsp;
							<?php } ?>
						
							Hair: <?php echo $userInfo['hair_color']; ?> &nbsp;&nbsp;&nbsp;&nbsp;
							Eye: <?php echo $userInfo['eye_color'] . ' ' . $userInfo['eye_shape']; ?> &nbsp;&nbsp;&nbsp;&nbsp;

						<?php //echo $userInfo['address']; ?><br/>
						<!-- T: <?php //echo $userInfo['phone']; ?> &nbsp;&nbsp;&nbsp;&nbsp; -->
						<!-- Email: <?php //echo $userInfo['user_email']; ?> &nbsp;&nbsp;&nbsp;&nbsp; -->
					</h3>
				</div>

				<pagebreak page-break-type="clonebycss" />

				<div style="margin:auto;text-align:center;width:100%;">
					<?php
						$sql_photos = "SELECT ap.* FROM agency_photos ap
								WHERE ap.user_id='$userid' limit 13";
						$sql_photos = mysql_query($sql_photos);
					?>
					<?php $cnt_card = 1; ?>
					<?php while ($row = sql_fetchrow($sql_photos)) { ?>
						<?php if (file_exists($folder_portfolio . $row['filename'])) { ?>
							<div style="text-align: center;float:left;width:47%;padding:15px;">
								<img src="<?php echo $folder_portfolio . $row['filename']; ?>" style="" />
							</div>
						<?php } ?>
						<?php $cnt_card++; ?>
					<?php } ?>
				</div>

			<?php } ?>

		<?php } else { ?>
			This page was not accessed correctly.
		<?php } ?>
	<?php
		} else {
			echo 'This page was not accessed correctly.';
		}
	?>
</div>

</body>