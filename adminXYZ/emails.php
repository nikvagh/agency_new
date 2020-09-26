<?php
include('header.php');
?>
<div align="center">
<br />
<div class="adminheading">Email List</div><br />
copy and paste to your prefered email program<br />
<textarea id="emails" cols="70" rows="15" onFocus="this.select()">
<?php
$query = "SELECT user_email FROM forum_users WHERE user_email !='' ORDER BY user_id";
$result = @mysql_query ($query);
while ($row = mysql_fetch_array ($result, MYSQL_ASSOC)) {
	echo $row['user_email'] . ", ";
}
?>
</textarea>
<br />
<br />
<br />
<br />
<br />
With Names:<br />
<textarea cols="70" rows="15" onFocus="this.select()">
<?php
$query = "SELECT forum_users.user_email, agency_profiles.firstname, agency_profiles.lastname FROM forum_users, agency_profiles WHERE agency_profiles.user_id=forum_users.user_id AND forum_users.user_email !='' ORDER BY forum_users.user_id";
$result = @mysql_query ($query);
while ($row = mysql_fetch_array ($result, MYSQL_ASSOC)) {
	echo '"' . $row['firstname'] . ' ' . $row['lastname'] . '" <' . $row['user_email'] . ">, ";
}
?>
</textarea>
<br />
<br />
<br />
<br />
<br />
<?php
include('footer.php');
?>