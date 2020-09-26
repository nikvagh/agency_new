<?php
include('header.php');
$success = FALSE; // flag for showing or not showing form

if (isset($_POST['Submit'])) {

	if (stripslashes(trim($_POST['title']))) {
		$title = escape_data($_POST['title']);
	} else {
		$title = FALSE;
		echo '<p align="center" style="color:red; font-weight:bold ">You must enter a Title</p>';
	}

	$subtitle = '';
	$caption = escape_data($_POST['caption']);

	if (stripslashes(trim($_POST['content']))) {
		$content = escape_data($_POST['content']);
	} else {
		$content = FALSE;
		echo '<p align="center" style="color:red; font-weight:bold ">You must enter Content</p>';
	}

	if ($_POST['AMPM_start'] == 'PM') {
		if ($_POST['Hour_start'] != 12) { // Takes care of 12 noon
			$H_S = $_POST['Hour_start'] + 12;
		} else {
			$H_S = $_POST['Hour_start'];
		}
	} else {
		if ($_POST['Hour_start'] == 12) { // if it's 12 AM then set to 00
			$H_S = "00";
		} else {
			$H_S = $_POST['Hour_start'];
		}
	}

	$date = $_POST['Year_start'] . '-' . $_POST['Month_start'] . '-' . $_POST['Day_start'] . ' ' .	$H_S . ':' . $_POST['Minute_start'] . ':00';

	$active = $_POST['active'];

	$featured = 0;
	if (isset($_POST['featured'])) {
		$featured = 1;
	}

	if ($title && $content) { // If everything's OK.
		$query = "INSERT INTO agency_news (Title, Subtitle, Caption, Date, Content, Active, Featured) VALUES ('$title', '$subtitle', '$caption', '$date', '$content', '$active', '$featured' )";
		$result = mysql_query ($query);
		if (mysql_affected_rows() == 1) { // If it ran OK.
			$id = mysql_insert_id();
			
			if($active) {
				// place into feed
				$count = 1;
				while($count) {
					$content = str_replace('\r\n', ' ', $content, $count);
				}
				$content = strip_tags(stripslashes($content));
				$count = 1;
				while($count) {
					$content = str_replace('&nbsp;', ' ', $content, $count);
				}
				if (strlen($content) > 200) {
					$content = substr($content,0,200);
					$content = preg_replace("/\s+[,\.!?\w-]*?$/",'...',$content);
				}
				$store = '<span class="AGENCYindextitle">' . $title . ':</span> <span class="AGENCYindexcontent">' . $content . ' | <a href="news.php?newsid=' . $id . '&amp;title=' . urlencode($title) . '">Read More&gt;</a></span>';
				$store = htmlspecialchars($store, ENT_QUOTES);
				
				$query = "INSERT INTO agency_feed (type, content, post_date, news_id) VALUES ('news', '$store', '$date', '$id')";
				mysql_query ($query);
			}


			// make topic in forum
			// have to add this for castings too; also add last few posts on article pages with post box
			// include('posttopic.php');

			if (!empty($_FILES['newsthumb']['name'])) { // insert image
 				$folder = '../images/news/';
				$allowed = array ('image/gif', 'image/jpeg', 'image/jpg', 'image/pjpeg', 'image/JPG');
				if (in_array($_FILES['newsthumb']['type'], $allowed)) {
					$allowed_jpg = array ('image/jpeg', 'image/jpg', 'image/pjpeg', 'image/JPG');
					$allowed_gif = array ('image/gif');
					if (in_array($_FILES['newsthumb']['type'], $allowed_jpg)) {
						$filetype = ".jpg";
						$current_pic = $folder . $id . ".gif";
					} else if (in_array($_FILES['newsthumb']['type'], $allowed_gif)) {
						$filetype = ".gif";
						$current_pic = $folder . $id . ".jpg";
					}

					// Move the file over.
					$filename = $folder . $id . $filetype;
					if (move_uploaded_file($_FILES['newsthumb']['tmp_name'], "$filename")) {
						if (file_exists($current_pic)) { unlink ($current_pic); }  // delete old file if not same type

						// Set a maximum height and width
						$height = 60;

						// Get new dimensions
						list($width_orig, $height_orig) = getimagesize($filename);
						
						if($height_orig > $height) {

							$ratio_orig = $width_orig/$height_orig;
							$width = $height*$ratio_orig;
	
							// Resample
							$image_p = imagecreatetruecolor($width, $height);
	
							if ($filetype == '.jpg') {
								$image = imagecreatefromjpeg($filename);
							}
							if ($filetype == '.gif') {
								$image = imagecreatefromgif($filename);
							}
							imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
						}
						// Output

						if ($filetype == '.jpg') {
							imagejpeg($image_p, $filename, 100);
						}
						if ($filetype == '.gif') {
							imagegif($image_p, $filename, 100);
						}
					} else { // Couldn't move the file over.

						echo '<p><font color="red">The file could not be uploaded because: </b>';

						// Print a message based upon the error.
						switch ($_FILES['newsthumb']['error']) {
							case 1:
								print 'The file exceeds the upload_max_filesize setting in php.ini.';
								break;
							case 2:
								print 'The file must be less than 1MB.';
								break;
							case 3:
								print 'The file was only partially uploaded.';
								break;
							case 4:
								print 'No file was uploaded.';
								break;
							case 6:
								print 'No temporary folder was available.';
								break;
							default:
								print 'A system error occurred.';
								break;
						} // End of switch.

						print '</b></font></p>';

					} // End of move... IF.

				} else { // Invalid type.
					echo '<p><font color="red">Please upload a JPEG or GIF image smaller than 1MB.</font></p>';
					if (file_exists($_FILES['newsthumb']['tmp_name'])) { unlink ($_FILES['newsthumb']['tmp_name']); }  // delete temp file
				}
			}



			include('makesitemap.php');
			
			$url = 'news.php';
			ob_end_clean(); // Delete the buffer.
			header("Location: $url");
			exit(); // Quit the script.
		}
	}
}

if (!$success) {
?>

<div id="page-wrapper">
	<div class="container-fluid">
    	<!-- Page Heading -->
		<div class="well" id="main">

			<div class="adminheading">Create News Items</div><br />
			<table width="50%" cellpadding="10" cellspacing="0" bgcolor="#F0F0F0">
				<tr>
				    <td valign="top">
				        <p>Create your news item using the form below</p>
				        <form enctype="multipart/form-data" name="postform" method="post" action="createnews.php">
					        <p>Title:<br />
					            <input name="title" type="text" size="50" maxlength="250" value="<?php if(isset($_POST['title'])) echo $_POST['title']; ?>">
					        </p>
							<br />
							<div id="link">
								<a href="#" style="font-size:x-small; text-decoration:none" onclick="var obj = document.getElementById('setdate');obj.style.visibility = 'visible';obj.style.height = '100%';var obj = document.getElementById('link');obj.style.visibility = 'hidden';obj.style.height = '0px';">Set Date</a>
							</div>
							<div id="setdate" style="visibility:hidden; height:0px">
								News Release Date: <br />

								<?php
								date_default_timezone_set("America/New_York");

								$YR = date("Y");
								$MO = date("m");
								$DY = date("d");
								$HR = date("h");
								$MN = date("i");
								$AP = date("A");


								 //Create the month pull-down menu

								 echo '<SELECT NAME=Month_start style="border:thin dotted #BBB">';
								 echo "<OPTION VALUE=01"; if ($MO == "01") echo " selected"; echo ">January</OPTION>\n";
								 echo "<OPTION VALUE=02"; if ($MO == "02") echo " selected"; echo ">February</OPTION>\n";
								 echo "<OPTION VALUE=03"; if ($MO == "03") echo " selected"; echo ">March</OPTION>\n";
								 echo "<OPTION VALUE=04"; if ($MO == "04") echo " selected"; echo ">April</OPTION>\n";
								 echo "<OPTION VALUE=05"; if ($MO == "05") echo " selected"; echo ">May</OPTION>\n";
								 echo "<OPTION VALUE=06"; if ($MO == "06") echo " selected"; echo ">June</OPTION>\n";
								 echo "<OPTION VALUE=07"; if ($MO == "07") echo " selected"; echo ">July</OPTION>\n";
								 echo "<OPTION VALUE=08"; if ($MO == "08") echo " selected"; echo ">August</OPTION>\n";
								 echo "<OPTION VALUE=09"; if ($MO == "09") echo " selected"; echo ">September</OPTION>\n";
								 echo "<OPTION VALUE=10"; if ($MO == "10") echo " selected"; echo ">October</OPTION>\n";
								 echo "<OPTION VALUE=11"; if ($MO == "11") echo " selected"; echo ">November</OPTION>\n";
								 echo "<OPTION VALUE=12"; if ($MO == "12") echo " selected"; echo ">December</OPTION>\n";
								 echo "</SELECT>&nbsp;&nbsp;";

								 //Create the day pull-down menu.

								 echo "<SELECT NAME=Day_start style=\"border:thin dotted #BBB\">";
								 $Day = 1;
								 while ($Day <= 31) {
								   if (strlen($Day) < 2) {
										$Day = '0' .$Day;
									}
								   echo "<OPTION VALUE=$Day"; if ($DY == $Day) echo " selected"; echo ">$Day</OPTION>\n";
								   $Day++;
								 }
								 echo "</SELECT>&nbsp;&nbsp;";

								 //Create the year pull-down menu.
								 echo "<SELECT NAME=Year_start style=\"border:thin dotted #BBB\">";
								 $Year = 2007;
								 while ($Year <= $YR + 1) {
								   echo "<OPTION VALUE=$Year"; if ($Year == $YR) echo " selected"; echo ">$Year</OPTION>\n";
								   $Year++;
								 }
								 echo "</SELECT>&nbsp;&nbsp;at&nbsp;&nbsp;";

								 echo "<SELECT NAME=Hour_start style=\"border:thin dotted #BBB\">";
								 $Hour = 1;
								 while ($Hour <= 12) {
								   echo "<OPTION VALUE=";
								   if (strlen($Hour) < 2) {
										$Hour = '0' .$Hour;
									}
								   echo "$Hour";
								   if (isset($_POST['Hour_start'])) { if ($_POST['Hour_start'] == $Hour) { echo " selected"; } } else if ($Hour == $HR) echo " selected";
								   echo ">$Hour</OPTION>\n";
								   $Hour++;
								 }
								 echo "</SELECT>&nbsp;&nbsp;";


								 //Create the minute pull-down menu.
								 echo "<SELECT NAME=Minute_start style=\"border:thin dotted #BBB\">";
								 $Minute = 0;
								 while ($Minute <= 59) {
								   echo "<OPTION VALUE=";
								   if (strlen($Minute) < 2) {
										$Minute = '0' .$Minute;
									}
								   echo "$Minute";
								   if (isset($_POST['Minute_start'])) { if ($_POST['Minute_start'] == $Minute) { echo " selected"; } } else if ($Minute == $MN) echo " selected";
								   echo " >$Minute</OPTION>\n";
								   $Minute += 15;
								 }
								 echo "</SELECT>&nbsp;&nbsp;";

								 //Create the month pull-down menu
								 echo "<SELECT NAME=AMPM_start style=\"border:thin dotted #BBB\">";
								 echo "<OPTION VALUE=AM";
								 if (isset($_POST['AMPM_start'])) { if ($_POST['AMPM_start'] == "AM") { echo " selected"; } } else if ($AP == "AM") echo ' selected';
								 echo ">AM</OPTION>\n";
								 echo "<OPTION VALUE=PM";
								 if (isset($_POST['AMPM_start'])) { if ($_POST['AMPM_start'] == "PM") { echo " selected"; } } else if ($AP == "PM") echo ' selected';
								 echo ">PM</OPTION>\n";
								 echo "</SELECT>&nbsp;&nbsp;";

								?>
							</div>

					        <p align="left">Content:<br />
					            <textarea name="content" cols="60" rows="20"><?php if(isset($_POST['content'])) echo $_POST['content']; ?></textarea>
					        </p>
				          	<p align="left">
						      	<input name="active" type="radio" value="1" <?php if (isset($active)) { if ($active == "1") echo " checked"; } else echo " checked"; ?>> Make News Item Active<br />
				              	<input name="active" type="radio" value="0" <?php if (isset($active)) { if ($active == "0") echo " checked"; } ?>> Do not place on site yet, save for later<br />
							 	<p align="left"><input name="featured" type="checkbox" value="" <?php if(isset($_POST['featured'])) echo 'checked'; ?> > Check this box if you would like this to be a featured article (article must be active to be featured)</p>
					            <p align="center">
					            	Thumbnail image:<br />
					            	<input type="file" name="newsthumb">
					            	<input type="hidden" name="MAX_FILE_SIZE" value="1000000" />
								</p>
					 		  	<p align="center"><br />
					                <input type="submit" name="Submit" value="Submit">
								</p>
				        </form>
				        <div align="center">
				          <p><br /></p>
				      	</div>
				    </td>
				</tr>
			</table>
			<script type="text/javascript" src="../fckeditor/fckeditor.js"></script>
			<script type="text/javascript">
				window.onload = function()
				{
					var oFCKeditor = new FCKeditor( 'content' ) ;
					oFCKeditor.BasePath = "../fckeditor/" ;
					oFCKeditor.Config["EditorAreaCSS"] = "custom.css"  ;
					oFCKeditor.ReplaceTextarea() ;
				}
			</script>
<?php } ?>

<?php include('footer_js.php'); ?>
<?php include('footer.php'); ?>