<?php
include('header.php');
?>
<?php
$success = FALSE;  // Flag to show form or not
if(isset($_SESSION['admin'])) {
	if(isset($_GET['id'])) {
		$id = $_GET['id'];
		if (isset($_POST['Submit'])) {
			if (stripslashes(trim($_POST['menuname']))) {
				$link = trim($_POST['link']);
				if(!empty($link)) {
					$link = escape_data(trim($_POST['link']));
				} else {
					$link = NULL;
				}
				$menuname = escape_data($_POST['menuname']);
				$heading = escape_data($_POST['heading']);
				$content = escape_data($_POST['content']);
				$whosees = escape_data($_POST['whosees']);
				$pageid = mysql_result(mysql_query("SELECT PageID FROM agency_content WHERE ContentID='$id'"), 0, 'PageID');
				$query = "UPDATE agency_content SET Heading='$heading', Content='$content' WHERE ContentID='$id'";
				$result = mysql_query ($query);
				if (mysql_affected_rows() == 1) { // If it ran OK.
					$success = TRUE;
				}
				$query = "UPDATE agency_pages SET MenuName='$menuname', Link='$link', WhoSees='$whosees' WHERE PageID='$pageid'";
				$result = mysql_query ($query);
				if (mysql_affected_rows() == 1) { // If it ran OK.
					$success = TRUE;
				}
			} else {
				echo '<p align="center" style="color:red; font-weight:bold ">You must enter a Menu Name</p>';
			}
		} else {
			$query = "SELECT * FROM agency_content WHERE contentID='$id'";
			$result = mysql_query ($query);
			if ($row = mysql_fetch_array ($result, MYSQL_ASSOC)) { // If there are projects.
				$heading = $row['Heading'];
				$content = $row['Content'];
				$section = $row['Section'];
				$pageid = $row['PageID'];
				$menuname = mysql_result(mysql_query("SELECT MenuName FROM agency_pages WHERE PageID='$pageid'"), 0, 'MenuName');
				$link = mysql_result(mysql_query("SELECT Link FROM agency_pages WHERE PageID='$pageid'"), 0, 'Link');
				$whosees = mysql_result(mysql_query("SELECT WhoSees FROM agency_pages WHERE PageID='$pageid'"), 0, 'WhoSees');
			}
		}
	} else if (isset($_POST['Submit']) && isset($_POST['pageid'])) { // if no id, then this is new content
		$pageid = $_POST['pageid'];
		$heading = escape_data($_POST['heading']);
		$content = escape_data($_POST['content']);
		$query = "INSERT INTO agency_content (Heading, Content, Section, PageID) VALUES ('$heading', '$content', '1', '$pageid')";
		$result = mysql_query ($query);
		if (mysql_affected_rows() == 1) { // If it ran OK.
			$success = TRUE;
		}
	}
	if($success) {
		echo 'Thank you.  Your content has been stored.';
		include('makesitemap.php');
	} else {
?>
            <div class="adminheading">Edit Content</div>
            <p style="font-size: small; color: #333333">Be sure to Submit Changes when finished.</p>

        <form name="form1" method="post" action="editcontent.php<?php if(isset($pageid)) { echo '?pageid=' . $pageid; if(isset($id)) echo '&id=' . $id; } else { if(isset($id)) echo '?id=' . $id; } ?>">
          <p>Page name as it appears in the menu:<br />
              <input name="menuname" type="text" size="16" maxlength="20" value="<?php if(isset($_POST['menuname'])) echo $_POST['menuname']; else if(isset($menuname)) echo $menuname;?>">
          </p>
		 <p>Link (if you place a link here, the menu item will follow the link and ignore <br />
		all other content below--must put "www" or "http://" at beginning of external link):<br />
              <input name="link" type="text" size="50" maxlength="250" value="<?php if(isset($_POST['link'])) echo $_POST['link']; else if(isset($link)) echo $link;?>">
          </p>
		 <p>Heading:<br />
              <input name="heading" type="text" size="50" maxlength="250" value="<?php if(isset($_POST['heading'])) echo $_POST['heading']; else if(isset($heading)) echo $heading;?>">
          </p>
          <p align="left">Content:<br />
            <textarea name="content" cols="60" rows="80"><?php if(isset($_POST['content'])) echo stripslashes($_POST['content']); else if(isset($content)) echo $content;?></textarea>
          </p>
<div class="radiobox">
<input name="whosees" type="radio" value="all"<?php if(isset($_POST['whosees'])) { if($_POST['whosees'] == 'all') echo ' checked'; } else if(isset($whosees)) { if($whosees == 'all') echo ' checked'; } else { echo ' checked'; }?>> This page is available to EVERYONE, including the public<br />
<input name="whosees" type="radio" value="client"<?php if(isset($_POST['whosees'])) { if($_POST['whosees'] == 'client') echo ' checked'; } else if(isset($whosees)) { if($whosees == 'client') echo ' checked'; } ?>> Only logged in CLIENTS can view this page<br />
<input name="whosees" type="radio" value="talent"<?php if(isset($_POST['whosees'])) { if($_POST['whosees'] == 'talent') echo ' checked'; } else if(isset($whosees)) { if($whosees == 'talent') echo ' checked'; } ?>> Only logged in TALENT can view this page<br />
<input name="whosees" type="radio" value="both"<?php if(isset($_POST['whosees'])) { if($_POST['whosees'] == 'both') echo ' checked'; } else if(isset($whosees)) { if($whosees == 'both') echo ' checked'; } ?>> Both logged in CLIENTS AND TALENT can view, but not the public<br />
</div>
<?php
	if(isset($_GET['pageid'])) {
?>
		<input name="pageid" type="hidden" value="<?php echo $_GET['pageid']; ?>">
<?php
	}
?>
<p align="center"><input type="submit" name="Submit" value="Submit"></p>
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