<?php
include('header.php');
?>

<div align="center">
<br />
<div class="adminheading">News Items</div><br />
[<a href="createnews.php">create new News Item </a>] <br />
<table cellpadding="5" border="1"><tr><td><a href="managenews.php?sort=Date">Date</a></td><td width="300"><a href="managenews.php?sort=Title">Title</a></td><td></td></tr>
<?php
if(isset($_POST['delete'])) {
	$deleteid = $_POST['newsid'];
	$query = "DELETE FROM agency_news WHERE NewsID='$deleteid' LIMIT 1";
	mysql_query ($query);
	$query = "DELETE FROM agency_feed WHERE news_id='$deleteid' LIMIT 1";
	mysql_query ($query);
}

$sort = "Date DESC";
if(isset($_GET['sort'])) {
	if($_GET['sort'] == 'Title') {
		$sort = 'Title ASC';
	} else if($_GET['sort'] == 'Date') {
		$sort = 'Date DESC';
	}
}

$query = "SELECT * FROM agency_news ORDER BY $sort";
$result = mysql_query ($query);
while ($row = mysql_fetch_array ($result, MYSQL_ASSOC)) { // If there are projects.
	$NewsID = $row['NewsID'];
	$Title = $row['Title'];
	$Date = date('m/d/Y',strtotime($row['Date']));
	$Active = $row['Active'];
?>
<tr><td><?php echo $Date; ?></td><td><a href="../news.php?newsid=<?php echo $NewsID; ?>" target="_blank"><?php echo $Title; ?></a><?php if($Active == '0') echo '*'; ?></td><td><form action="editnews.php?id=<?php echo $NewsID; ?>" method="post"><input name="edit" type="submit" value="edit this news"></form></td></tr>
<?php
}
?>
</table>
* = news item not active<br /><br />
<?php
include('footer.php');
?>