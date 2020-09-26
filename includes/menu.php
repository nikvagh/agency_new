			<div id="AGENCYmenu">
			<ul class="sf-menu">
<?php
$query = "SELECT * FROM pages WHERE Active='1' AND ChildOf='0' ORDER BY OrderID ASC";
$result = @mysql_query($query);
while ($row = @mysql_fetch_array ($result, MYSQL_ASSOC)) {
	$menuname = $row['MenuName'];
	$pageid = $row['PageID'];
	$link = $row['Link'];
	if($link == NULL) {
?>
		<li><a href="index.php?pageid=<?php echo $pageid; ?>"><?php echo $menuname; ?></a>
<?php
	} else {
?>
		<li><a href="<?php if((substr($link, 0, 3) == 'www') && (substr($link, 0, 7) != 'http://') ) { echo 'http://'; } echo $link; ?>"><?php echo $menuname; ?></a>
<?php 
	}
	$query2 = "SELECT * FROM pages WHERE Active='1' AND ChildOf='$pageid' ORDER BY OrderID ASC";
	$result2 = @mysql_query($query2);
	if(mysql_num_rows($result2) > 0) {
		echo '<ul>';
		while ($row2 = @mysql_fetch_array ($result2, MYSQL_ASSOC)) {
			$menuname2 = $row2['MenuName'];
			$pageid2 = $row2['PageID'];
			$link2 = $row2['Link'];
			if($link2 == NULL) {
?>
				<li><a href="index.php?pageid=<?php echo $pageid2; ?>"><?php echo $menuname2; ?></a>
<?php
			} else {
?>
				<li><a href="<?php if((substr($link2, 0, 3) == 'www') && (substr($link2, 0, 7) != 'http://') ) { echo 'http://'; } echo $link2; ?>"><?php echo $menuname2; ?></a>
<?php 
			}
			$query3 = "SELECT * FROM pages WHERE Active='1' AND ChildOf='$pageid2' ORDER BY OrderID ASC";
			$result3 = @mysql_query($query3);
			if(mysql_num_rows($result3) > 0) {
				echo '<ul>';
				while ($row3 = @mysql_fetch_array ($result3, MYSQL_ASSOC)) {
					$menuname3 = $row3['MenuName'];
					$pageid3 = $row3['PageID'];
					$link3 = $row3['Link'];
					if($link3 == NULL) {
?>
						<li><a href="index.php?pageid=<?php echo $pageid3; ?>"><?php echo $menuname3; ?></a></li>
<?php
					} else {
?>
						<li><a href="<?php if((substr($link3, 0, 3) == 'www') && (substr($link3, 0, 7) != 'http://') ) { echo 'http://'; } echo $link3; ?>"><?php echo $menuname3; ?></a></li>
<?php 
					}
				}
				echo '</ul>';			
			}
			echo '</li>';
		}
		echo '</ul>';
	}
	echo '</li>';
}
?>
		</ul>
			</div>