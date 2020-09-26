
<div style="width:340px; background-color:#FFEEDD; float:right; padding: 5px; margin-bottom:20px">
<div style="font-size:10px; line-height:2.2em;">
<div class="AGENCYblueheading" style="padding-left:46px">Apply Today! Start Tomorrow!</div>
<form action="home.php<?php if(!empty($_GET['type'])) echo '?type=' . $_GET['type']; ?>" method="post" id="join" name="join">
<input class="AGENCYformtext" type="text" name="firstname" maxlength="50" value="<?php if (isset($_POST['firstname'])) echo $_POST['firstname']; ?>" /><span style="float:right">First Name: </span><br />
<input class="AGENCYformtext" type="text" name="lastname" maxlength="50" value="<?php if (isset($_POST['lastname'])) echo $_POST['lastname']; ?>" /><span style="float:right">Last Name: </span><br />
<input class="AGENCYformtext" type="text" name="email" maxlength="100" value="<?php if (isset($_POST['email'])) echo $_POST['email']; ?>" /><span style="float:right">Email: </span><br />
<input class="AGENCYformtext" type="text" name="confirmemail" maxlength="100" value="<?php if (isset($_POST['confirmemail'])) echo $_POST['confirmemail']; ?>" /><span style="float:right">Confirm Email: </span><br />
<input class="AGENCYformtext" type="text" name="username" maxlength="30" value="<?php if (isset($_POST['username'])) echo $_POST['username']; ?>" /><span style="float:right">Username: </span><br />
<input class="AGENCYformtext" type="password" name="joinpassword" maxlength="30" value="<?php if (isset($_POST['joinpassword'])) echo $_POST['joinpassword']; ?>" /><span style="float:right">Password: </span><br />
<select class="AGENCYformtext" style="height:18px; width:229px" name="gender">
<option value="F" <?php if(isset($_POST['gender'])) { if($_POST['gender'] == 'F') echo 'selected'; } ?>>Female&nbsp;&nbsp;</option>
<option value="M" <?php if(isset($_POST['gender'])) { if($_POST['gender'] == 'M') echo 'selected'; } ?>>Male</option>
<option value="O" <?php if(isset($_POST['gender'])) { if($_POST['gender'] == 'O') echo 'selected'; } ?>>Other</option>
</select><span style="float:right">Gender: </span><br />
	<input type="hidden" value="<?php echo time(); ?>" name="creation_time"/>



<div align="right"><input type="submit" value="Join" name="submit" style="background-color:#DDD" /></div>
</form>
</div>
</div>