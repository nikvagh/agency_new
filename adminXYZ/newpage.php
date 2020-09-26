<?php
include('header.php');
?>
<?php
if(isset($_SESSION['admin'])) {

	$success = FALSE; // flag for showing or not showing form

	if (isset($_POST['Submit'])) {

		if (stripslashes(trim($_POST['menuname']))) {
			$menuname = escape_data($_POST['menuname']);
		} else {
			$menuname = FALSE;
			echo '<p align="center" style="color:red; font-weight:bold ">You must enter a Menu Name</p>';
		}

		if (stripslashes(trim($_POST['heading']))) {
			$heading = escape_data($_POST['heading']);
		} else {
			$heading = NULL;
		}

		$content = escape_data($_POST['content']);
		$link = trim($_POST['link']);
		if(!empty($link)) {
			$link = escape_data(trim($_POST['link']));
		} else {
			$link = NULL;
		}

		$active = 0;
		if (isset($_POST['active'])) {
			$active = 1;
		}

		$whosees = $_POST['whosees'];

		$level = '1'; // for now just work with level 1 of menu

		if ($menuname) { // The main thing is there must be a title
			// first place content in content table
			$query = "INSERT INTO agency_pages (MenuName, Link, Level, WhoSees, Active) VALUES ('$menuname', '$link', '$level', '$whosees', '$active' )";

			$result = mysql_query ($query);
			if (mysql_affected_rows() == 1) { // If it ran OK.
				$pageid = mysql_insert_id(); // retrieve the content ID to place in 'pages' table
				$query = "INSERT INTO agency_content (Heading, Content, PageID) VALUES ('$heading', '$content', '$pageid' )";		// Section will automaticaly be set to '1'
				$result = mysql_query ($query);

				if (mysql_affected_rows() == 1) { // If it ran OK.
					$success = TRUE;
					echo '<br /><br /><div align="center"><font size="+1" color="#990000"><b>Your Page has been added.  Thank you.</b></font><br /><br /><br /></div>';
					include('makesitemap.php');
				}
			}
		}
	}

	if (!$success) {
?>
<div class="adminheading">New Page</div>
        <form name="form1" method="post" action="newpage.php">
          <p>Page name as it appears in the menu:<br />
              <input name="menuname" type="text" size="20" maxlength="20" value="<?php if(isset($_POST['menuname'])) echo $_POST['menuname']; ?>">
          </p>
		 <p>Link (if you place a link here, the menu item will follow the link and ignore <br />
		all other content below--must put "www" or "http://" at beginning of external link):<br />
              <input name="link" type="text" size="50" maxlength="250" value="<?php if(isset($_POST['link'])) echo $_POST['link']; ?>">
          </p>
           <p>Page Heading (this will appear at the top of the page):<br />
              <input name="heading" type="text" size="20" maxlength="250" value="<?php if(isset($_POST['heading'])) echo $_POST['heading']; ?>">
          </p>
                 <p align="left">Content (you will be able to edit this later as well):
              <textarea id="content" name="content" cols="60" rows="20"><?php if(isset($_POST['content'])) echo stripslashes($_POST['content']); ?></textarea>
          </p>
<div class="radiobox">
<input name="whosees" type="radio" value="all" checked> This page is available to EVERYONE, including the public<br />
<input name="whosees" type="radio" value="client"> Only logged in CLIENTS can view this page<br />
<input name="whosees" type="radio" value="members"> Only logged in MEMBERS can view this page<br />
<input name="whosees" type="radio" value="both"> Both logged in CLIENTS AND MEMBERS can view, but not the public<br />
</div>
			<br /><br />
		  <input name="active" type="checkbox" value="" <?php if(isset($_POST['active'])) echo 'checked'; ?> > Place page as item on main menu
  		<br /><br />

                      <input type="submit" name="Submit" value="Save">
        </form>
<br />
<form action="pages.php">
<input type="submit" value="Cancel">
</form>
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
<?php
	}
} else {
	$url = "index.php";
	ob_end_clean(); // Delete the buffer.
	header("Location: $url");
	exit(); // Quit the script.
}
?>
<?php
include('footer.php');
?>