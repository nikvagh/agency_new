<?php
include('header.php');
?>
        <?php
if(isset($_SESSION['admin'])) {

	if (isset($_POST['Submit'])) {
		$index = $_POST['index'];
		$me = $_POST['active'];
		$o = $_POST['order'];
		$c = $_POST['childof'];
		foreach ($index as $i) {  // Count the number of locations selection
			// echo $i . ' ' . $a[$i] . ' ';
			// echo $m[$i] . ' ' . $o[$i] . '<br />';
			if($me[$i] > 0) $active = '1'; else $active = '0';
			$query = "UPDATE agency_pages SET Active='$active', ChildOf='$c[$i]', OrderID='$o[$i]' WHERE PageID='$i'";
			// echo $query;
			$result = mysql_query ($query);
		}
		if(isset($_POST['delete'])) {
			$delete = $_POST['delete'];
			foreach ($delete as $d) {
				$query = "DELETE FROM agency_pages WHERE PageID='$d'";
				$result = mysql_query ($query);
				$query = "DELETE FROM agency_content WHERE PageID='$d'";
				$result = mysql_query ($query);
			}
		}

		// check to make sure there are no sub-sub-sub menu items.  If there are, put them back to the root.
		$query = "SELECT PageID FROM agency_pages WHERE PageID NOT IN (SELECT PageID FROM agency_pages WHERE (ChildOf = '0' OR ChildOf IN (SELECT PageID FROM agency_pages WHERE (ChildOf = '0') OR ChildOf IN (SELECT PageID FROM agency_pages WHERE ChildOf = '0'))))";
		$result = mysql_query ($query);
		while($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$PageID = $row['PageID'];
			mysql_query("UPDATE agency_pages SET ChildOf='0' WHERE PageID='$PageID'");
		}
	}


?>
        <div class="adminheading">Manage Pages</div>
        <p style="font-size: small; color: #333333">Be sure to Submit Changes when finished.</p>
        <div style="position:relative">
          <div id="helpbox" style="position:absolute; top:22px; border:3px solid #663333; width:100%; padding:4px 2px 4px 2px; font-weight:bold"></div>
        </div>
        <form name="form1" method="post" action="pages.php">
          <table border="1" width="100%">
            <tr style="font-size:x-small ">
              <td>Menu [<a href="javascript:void(0)" onMouseOver="helpover('When checked, the page will appear in the menu.  If you would like to have this page on the site but not in the  menu, un-check the box below and use the \'view\' link to determine the URL of the page.')" onMouseOut="helpoff()">?</a>]</td>
              <td>Menu Name [<a href="javascript:void(0)" onMouseOver="helpover('This is the name of the page as it appears in the site menu.')" onMouseOut="helpoff()">?</a>]</td>
              <td>Order [<a href="javascript:void(0)" onMouseOver="helpover('The lower the number, the higher the menu item will appear in the list of pages on the site.  If you place a &quot;1&quot; here, it will be the first menu item.  Avoid using the same number twice.')" onMouseOut="helpoff()">?</a>]</td>
              <td>Parent Page [<a href="javascript:void(0)" onMouseOver="helpover('If this is a page that is to be listed in a sub-menu, please select the Parent page here.  Pages that do not have parent pages and are not in the Main menu will only be accessible through direct links placed in content on other pages or emails.')" onMouseOut="helpoff()">?</a>]</td>
              <td>Edit [<a href="javascript:void(0)" onMouseOver="helpover('This is what will appear at the top of the page as a heading.<br />Click the link to edit the title or content for this page.')" onMouseOut="helpoff()">?</a>]</td>
              <td>Delete [<a href="javascript:void(0)" onMouseOver="helpover('When you check one of these pages you will permanently delete the page from the database.  If you would like to remove the page from the site but think you may use the page again at some time you may not want to delete it completely.  You may use the checkboxes on the left to simply hide the page from public users.')" onMouseOut="helpoff()">?</a>]</td>
              <td>View [<a href="javascript:void(0)" onMouseOver="helpover('Click the link to view the page as it appears on the site.  The page will open in a new window.<br />If the page is set as a link, the work &quot;link&quot; will be shown')" onMouseOut="helpoff()">?</a>]</td>
            </tr>
            <?php

	$query = "SELECT * FROM agency_pages WHERE ChildOf='0' ORDER BY OrderID";
	$result = mysql_query ($query);
	while ($row = mysql_fetch_array ($result, MYSQL_ASSOC)) { // If there are projects.
		$pageID = $row['PageID'];
		$menuName = $row['MenuName'];
		$orderID = $row['OrderID'];
		$active = $row['Active'];
		$childOf = $row['ChildOf'];
		$link = $row['Link'];
?>
            <tr bgcolor="#FFFF99">
              <td align="center"><input type="hidden" name="index[]" value="<?php echo $pageID; ?>">
                <input name="active[<?php echo $pageID; ?>]" type="checkbox" value="<?php echo $pageID; ?>"<?php if($active=='1') echo ' checked'; ?>>
              </td>
              <td><b><?php echo $menuName; ?><b></td>
              <td align="left">
                <input name="order[<?php echo $pageID; ?>]" type="text" size="2" maxlength="2" value="<?php echo $orderID; ?>"></td>
              <td align="center"><SELECT NAME="childof[<?php echo $pageID; ?>]" style="border:thin dotted #BBB; font-size:x-small">
                  <OPTION VALUE="0"> -- Main Menu -- </OPTION>
                  <?php
			$query3 = "SELECT * FROM agency_pages WHERE ChildOf='0' OR ChildOf IN (SELECT PageID FROM agency_pages WHERE ChildOf='0') ORDER BY MenuName";
			$result3 = mysql_query($query3);
			while ($row3 = mysql_fetch_array ($result3, MYSQL_ASSOC)) { // If there are projects.
				$pID = $row3['PageID'];
				$parent = $row3['ChildOf'];
				$mn = $row3['MenuName'];
				if($pID != $pageID) {
					echo "<OPTION VALUE='$pID'"; if ($pID == $childOf) echo " selected"; echo ">$mn</OPTION>\n";
				}
			}
 ?>
                </SELECT>
              </td>
              <td><?php
		echo '<a href="editcontent.php?id=' . mysql_result(mysql_query("SELECT ContentID FROM agency_content WHERE PageID='$pageID' AND Section='1'"), 0, 'ContentID') . '">EDIT</a>';
?>
              </td>
              <td align="center"><input id="check<?php echo $pageID; ?>" name="delete[<?php echo $pageID; ?>]" type="checkbox" value="<?php echo $pageID; ?>" onClick="if(this.checked==true) {var conf=confirm('By checking this box and submitting this page, you are deleting this page permanently from the web site.  Alternatively you can remove the page from being visible on the site without deleting it completely by unchecking the box on the left under &quot;Live&quot;.  Are you sure you wish to permanently remove this page from your web site?'); if (conf==false) this.checked=false; }" >
              </td>
              <td>
<?php
			if($link != NULL) {
?>
<a href="<?php if((substr($link, 0, 3) == 'www') && (substr($link, 0, 7) != 'http://') ) { echo 'http://'; } if((substr($link, 0, 3) != 'www') && (substr($link, 0, 7) != 'http://') ) { echo '../'; } echo $link; ?>"  target="_blank">link</a>
<?php
			} else {
?>
			  <a href="../index2.php?pageid=<?php echo $pageID ?>" target="_blank">view</a></td>
<?php
			}
?>
			  </td>
            </tr>
            <?php


		// SUB MENU ITEMS
		$query2 = "SELECT * FROM agency_pages WHERE ChildOf='$pageID' ORDER BY OrderID";
		$result2 = mysql_query ($query2);
		while ($row2 = mysql_fetch_array ($result2, MYSQL_ASSOC)) { // If there are projects.
			$pageID2 = $row2['PageID'];
			$menuName2 = $row2['MenuName'];
			$orderID2 = $row2['OrderID'];
			$active2 = $row2['Active'];
			$childOf2 = $row2['ChildOf'];
			$link2 = $row2['Link'];
?>
            <tr bgcolor="#FFFFCC">
              <td align="center"><input name="active[<?php echo $pageID2; ?>]" type="checkbox" value="<?php echo $pageID2; ?>"<?php if($active2=='1') echo ' checked'; ?>>
              </td>
              <td><img src="../images/bentarrow.gif">&nbsp;<?php echo $menuName2; ?></td>
              <td align="left">&nbsp;&nbsp;
                <input name="order[<?php echo $pageID2; ?>]" type="text" size="2" maxlength="2" value="<?php echo $orderID2; ?>"></td>
              <td align="center"><SELECT NAME="childof[<?php echo $pageID2; ?>]" style="border:thin dotted #BBB; font-size:x-small">
                  <OPTION VALUE="0"> -- Main Menu -- </OPTION>
                  <?php
			$query3 = "SELECT * FROM agency_pages WHERE ChildOf='0' OR ChildOf IN (SELECT PageID FROM agency_pages WHERE ChildOf='0') ORDER BY MenuName";
			$result3 = mysql_query($query3);
			while ($row3 = mysql_fetch_array ($result3, MYSQL_ASSOC)) { // If there are projects.
				$pID = $row3['PageID'];
				$parent = $row3['ChildOf'];
				$mn = $row3['MenuName'];
				if($pID != $pageID2) {
					echo "<OPTION VALUE='$pID'"; if ($pID == $childOf2) echo " selected"; echo ">$mn</OPTION>\n";
				}
			}
 ?>
                </SELECT>
              </td>
              <td><?php
			echo '<a href="editcontent.php?id=' . mysql_result(mysql_query("SELECT ContentID FROM agency_content WHERE PageID='$pageID2' AND Section='1'"), 0, 'ContentID') . '">EDIT</a>';
?>
              </td>
              <td align="center"><input id="check<?php echo $pageID2; ?>" name="delete[<?php echo $pageID2; ?>]" type="checkbox" value="<?php echo $pageID2; ?>" onClick="if(this.checked==true) {var conf=confirm('By checking this box and submitting this page, you are deleting this page permanently from the web site.  Alternatively you can remove the page from being visible on the site without deleting it completely by unchecking the box on the left under &quot;Live&quot;.  Are you sure you wish to permanently remove this page from your web site?'); if (conf==false) this.checked=false; }" >
                <input type="hidden" name="index[]" value="<?php echo $pageID2; ?>">
              </td>
              <td>
<?php
			if($link2 != NULL) {
?>
<a href="<?php if((substr($link2, 0, 3) == 'www') && (substr($link2, 0, 7) != 'http://') ) { echo 'http://'; } if((substr($link2, 0, 3) != 'www') && (substr($link2, 0, 7) != 'http://') ) { echo '../'; } echo $link2; ?>"  target="_blank">link</a>
<?php
			} else {
?>
			  <a href="../index2.php?pageid=<?php echo $pageID2 ?>" target="_blank">view</a></td>
<?php
			}
?>
			  </td>
            </tr>
            <?php

			// SUB MENU ITEMS
			$query5 = "SELECT * FROM agency_pages WHERE ChildOf='$pageID2' ORDER BY OrderID";
			$result5 = mysql_query ($query5);
			while ($row5 = mysql_fetch_array ($result5, MYSQL_ASSOC)) { // If there are projects.
				$pageID5 = $row5['PageID'];
				$menuName5 = $row5['MenuName'];
				$orderID5 = $row5['OrderID'];
				$contentID5 = $row5['ContentID'];
				$active5 = $row5['Active'];
				$childOf5 = $row5['ChildOf'];
				$link5 = $row5['Link'];
?>
            <tr bgcolor="#FFFFE1">
              <td align="center"><input name="active[<?php echo $pageID5; ?>]" type="checkbox" value="<?php echo $pageID5; ?>"<?php if($active5=='1') echo ' checked'; ?>>
              </td>
              <td>&nbsp;&nbsp;&nbsp;&nbsp;<img src="../images/bentarrow.gif">&nbsp;<?php echo $menuName5; ?></td>
              <td align="left">&nbsp;&nbsp;&nbsp;&nbsp;
                <input name="order[<?php echo $pageID5; ?>]" type="text" size="2" maxlength="2" value="<?php echo $orderID5; ?>"></td>
              <td align="center"><SELECT NAME="childof[<?php echo $pageID5; ?>]" style="border:thin dotted #BBB; font-size:x-small">
                  <OPTION VALUE="0"> -- Main Menu -- </OPTION>
                  <?php
				$query6 = "SELECT * FROM agency_pages WHERE ChildOf='0' OR ChildOf IN (SELECT PageID FROM agency_pages WHERE ChildOf='0') ORDER BY MenuName";
				$result6 = mysql_query($query6);
				while ($row6 = mysql_fetch_array ($result6, MYSQL_ASSOC)) { // If there are projects.
					$pID = $row6['PageID'];
					$parent = $row6['ChildOf'];
					$mn = $row6['MenuName'];
					if($pID != $pageID5) {
						echo "<OPTION VALUE='$pID'"; if ($pID == $childOf5) echo " selected"; echo ">$mn</OPTION>\n";
					}
				}
 ?>
                </SELECT>
              </td>
              <td><?php
			echo '<a href="editcontent.php?id=' . mysql_result(mysql_query("SELECT ContentID FROM agency_content WHERE PageID='$pageID5' AND Section='1'"), 0, 'ContentID') . '">EDIT</a>';
?>
              </td>
              <td align="center"><input id="check<?php echo $pageID5; ?>" name="delete[<?php echo $pageID5; ?>]" type="checkbox" value="<?php echo $pageID5; ?>" onClick="if(this.checked==true) {var conf=confirm('By checking this box and submitting this page, you are deleting this page permanently from the web site.  Alternatively you can remove the page from being visible on the site without deleting it completely by unchecking the box on the left under &quot;Live&quot;.  Are you sure you wish to permanently remove this page from your web site?'); if (conf==false) this.checked=false; }" >
                <input type="hidden" name="index[]" value="<?php echo $pageID5; ?>">
              </td>
              <td>
<?php
			if($link5 != NULL) {
?>
<a href="<?php if((substr($link5, 0, 3) == 'www') && (substr($link5, 0, 7) != 'http://') ) { echo 'http://'; } if((substr($link5, 0, 3) != 'www') && (substr($link5, 0, 7) != 'http://') ) { echo '../'; } echo $link5; ?>"  target="_blank">link</a>
<?php
			} else {
?>
			  <a href="../index.php2?pageid=<?php echo $pageID5 ?>" target="_blank">view</a></td>
<?php
			}
?>
            </tr>
            <?php
			} // End Sub Sub Menu
		} // End Sub Menu
	} // End main Menu

?>
          </table>
          <p align="center">
            <input type="submit" name="Submit" value="Submit">
          </p>
        </form>
        <script type="text/javascript" language="javascript">
function helpover(content) {
	var obj = document.getElementById('helpbox');
	obj.style.color = '#000000';
	obj.style.background = '#FFFFFF';
	obj.innerHTML = content;
	obj.style.visibility = "visible";
	/* var obj2 = document.getElementById('menuitem');
	obj.style.left = ob2.style.left+20;
	obj.style.top = ob2.style.top+20; */
}
function helpoff() {
	var obj = document.getElementById('helpbox');
	obj.style.visibility = "hidden";
	/* obj.style.color = '#666666';
	obj.style.background = '#DDDDDD';
	obj.innerHTML = 'Roll over a question marks for more information'; */
}

helpoff();
</script>
        <?php
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
