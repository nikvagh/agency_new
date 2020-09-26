<?php
include('header.php');
?>
<div align="center">
  <?php
if(isset($_SESSION['admin'])) {
	$success = FALSE; // flag for showing or not showing form
	
	if (isset($_POST['submit'])) {
		if (stripslashes(trim($_POST['sitename']))) {
			$sitename = escape_data($_POST['sitename']);
		} else {
			$sitename = FALSE;
			echo '<p align="center" style="color:red; font-weight:bold ">You must enter a name for your web site</p>';
		}
		if (stripslashes(trim($_POST['welcomenote']))) {
			$welcomenote = escape_data($_POST['welcomenote']);
		} else {
			$welcomenote = FALSE;
			echo '<p align="center" style="color:red; font-weight:bold ">You must enter a welcome note</p>';
		}
		
		$codeleft = escape_data($_POST['codeleft']);
		$codetop = escape_data($_POST['codetop']);
		$codebottom = escape_data($_POST['codebottom']);
		$email = escape_data($_POST['email']);

		$addthis = 0;
		if (isset($_POST['addthis'])) {
			$addthis = 1;
		}
		$rss = 0;
		if (isset($_POST['rss'])) {
			$rss = 1;
		}
		
		if ($sitename && welcomenote) { // If everything's OK.			
			mysql_query("UPDATE vars SET val='$sitename' WHERE name='sitename'");
			mysql_query("UPDATE vars SET val='$welcomenote' WHERE name='welcomenote'");
			mysql_query("UPDATE vars SET val='$codeleft' WHERE name='codeleft'");
			mysql_query("UPDATE vars SET val='$codetop' WHERE name='codetop'");
			mysql_query("UPDATE vars SET val='$codebottom' WHERE name='codebottom'");
			mysql_query("UPDATE vars SET val='$addthis' WHERE name='addthis'");
			mysql_query("UPDATE vars SET val='$rss' WHERE name='rss'");
			mysql_query("UPDATE vars SET val='$email' WHERE name='email'");
		
			$success = TRUE;
			echo '<br /><br /><div align="center"><font size="+1" color="#990000"><b>Your settings have been updated.  Thank you.</b></font><br /><br />';
		} else { 
			echo '<br /><br /><div align="center"><font size="+1" color="#990000"><b>Settings could not be updated.  Please confirm all values are valid.</b></font><br /><br /><br /></div>';
		}
	} else {
		$welcomenote = mysql_result(mysql_query("SELECT val FROM vars WHERE name='welcomenote'"), 0, 'val');
		$sitename = mysql_result(mysql_query("SELECT val FROM vars WHERE name='sitename'"), 0, 'val');
		$addthis = mysql_result(mysql_query("SELECT val FROM vars WHERE name='addthis'"), 0, 'val');
		$rss = mysql_result(mysql_query("SELECT val FROM vars WHERE name='rss'"), 0, 'val');
		$email = mysql_result(mysql_query("SELECT val FROM vars WHERE name='email'"), 0, 'val');
		$codeleft = mysql_result(mysql_query("SELECT val FROM vars WHERE name='codeleft'"), 0, 'val');
		$codetop = mysql_result(mysql_query("SELECT val FROM vars WHERE name='codetop'"), 0, 'val');
		$codebottom = mysql_result(mysql_query("SELECT val FROM vars WHERE name='codebottom'"), 0, 'val');
	}
?>
  <br />
  <?php
	if (!$success) {
?>
  <div class="adminheading">Site Settings</div>
  <br />
  <form name="form1" method="post" action="options.php">
    <table border="0" cellpadding="3" cellspacing="3">
      <tr>
        <td class="regtableleft">Add "+" button (if checked, each news article will have an option for users to submit the article to sites such as Digg.com, Stumbleupon.com, Facebook.com and many more):</td>
        <td class="regtableright"><input name="addthis" type="checkbox" <?php if(isset($_POST['addthis'])) echo ' checked'; else if($addthis == '1') echo ' checked';?>>
        </td>
      </tr>
      <tr>
        <td class="regtableleft">News RSS feed (if checked, the site will create an RSS feed of the news articles):</td>
        <td class="regtableright"><input name="rss" type="checkbox" <?php if(isset($_POST['rss'])) echo ' checked'; else if($rss == '1') echo ' checked';?>>
        </td>
      </tr>
    </table>
    <br />
    <input type="submit" value="Update" name="submit">
  </form>
  <br />
  <br />
  <form action="options.php">
    <input type="submit" value="Cancel">
  </form>
  <br />
  <br />
  <br />
  <?php
	}
?>
</div>
<?php
} else {
	$url = "index.php";
	ob_end_clean(); // Delete the buffer.
	header("Location: $url");
	exit(); // Quit the script.
}
include('footer.php');
?>
