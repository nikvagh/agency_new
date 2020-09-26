<?php
include('header.php');
?>
This is a temporary page to list who has submitted credit card info (we'll fine tune this over time, but just so you have something to start with...):<br /><br /><br />
<table cellpadding="3" border="1"><tr><td>User ID</td><td>email</td><td>Name</td><td>Name on CC</td><td>username</td><tr>
<?php
$query = "SELECT cc.firstname AS ccfn, cc.lastname AS ccln, u.user_id AS uid, u.username AS un, p.firstname AS fn, p.lastname AS ln, u.user_email AS email FROM agency_cc cc, agency_profiles p, forum_users u WHERE u.user_id=p.user_id AND u.user_id=cc.user_id ORDER BY u.user_id ASC";
$result = mysql_query($query);
while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
	echo '<tr><td><a href="../profile.php?u=' . $row['uid'] . '">' . $row['uid'] . '</a></td><td>' . $row['email'] . '</td><td>' . $row['fn'] . ' ' . $row['ln'] . 
	'</td><td>' . $row['ccfn'] . ' ' . $row['ccln'] . '</td><td>' . $row['un'] . '</td><tr>';
}

?>
</table>

<?php
include('footer.php');
?>