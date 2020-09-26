<?php
include('header.php');
?>
<div align="left">
  <p><br />
  Profile editing mode allows you to view/edit member profiles as if you are logged in as that member.  This can be
  used for removing photos, editing profile information, reviewing messages, deleting wall posts, etc...</p>
  <p><a href="userlist.php" class="viewbutton" style="font-weight: bold; text-decoration:none; float:left">Filtered Member Lists </a>
  <form action="useredit.php" method="post" style="float:right">Enter UserID or Username: <input type="text" name="member"><input type="submit" value="Manage User"></form></p>
  <p><br />
        <br />
        <b>Profile editing mode is:
      <?php
if(!empty($_GET['editmode'])) {
	if($_GET['editmode'] == 'on') {
		$_SESSION['editmode'] = true;
	} else {
		unset($_SESSION['editmode']);
	}
}
echo ($_SESSION['editmode'] ? 'ON (<a href="members.php?editmode=off" class="viewbutton" style="font-weight: bold; text-decoration:none">turn off</a>)' : 'OFF (<a href="members.php?editmode=on">turn on</a>)');
?>
            </b>
        <br />
        <br />
        <br />
        <br />
        In order to find particular members use the <a href="../clienthome.php" class="viewbutton" style="font-weight: bold; text-decoration:none">advanced search</a> function
      
        <br />
        <br />
        <?php
if($exp_highlight) {
	echo '<hr><b><font color="red">The following members have made experience level change requests:</font><br /><span style="font-size:10px; font-weight:normal">(to approve (or deny) change requests, turn <b>on</b> "profile editing mode" above, click on link below to review profile, and submit experience level (even if it is not being changed) in the account settings for the user)</span><br /><br />';
	while($row = mysql_fetch_array($exp_result, MYSQL_ASSOC)) {
		echo '<a href="../profile.php?u=' . $row['user_id'] . '" target="_blank">' . $row['firstname'] . ' ' . $row['lastname'] . '</a><br />';
	}
	echo '</b><br /><br /><hr>';
}
?>
        <br />
        <br />
        <a href="emails.php">export email list</a>
        <br />
        <br />
      </p>
</div>
<?php
include('footer.php');
?>