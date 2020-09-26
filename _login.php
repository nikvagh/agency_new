<?php
@include('./includes/header.php');
?>
<br />
<br />
<?php
   		if(isset($_GET['welcome'])) {
?>
<div id="welcomemessage" style="padding:10px; border:1px solid black">
<br />(we can change the following text...)<br /><br />
Thank you for applying to The Agency.  Our team of reviewers will look at your profile in the next 24 hours and if you are
approved you will have full access to the site.  Your credit card will not be charged until you are approved.
<br /><br />
In the meantime, we suggest you fill out your profile and upload photos as your chances of being approved are much greater if you do so.
<br /><br />
You may also navigate around the site but most features will not be active until you are approved (such as sending friend
requests, posting on walls, participating in the forum, etc).
<br /><br />
Start building your profile!
<br /><br /><br />
<a href="javascript:void(0)" onclick="document.getElementById('welcomemessage').style.display='none'">close</a>
<br /><br />
</div>
<?php
		}
?>
        <form method="post" action="user/ucp.php?mode=login" name="phpbblogin">
          <table align="center" border="0" cellspacing="0" cellpadding="4">
            <tr>
              <td colspan="2"><div align="center"><strong>Already a Member? Sign in.</strong></div><br /></td>
            </tr>
            <tr>
              <td align="right">Username:</td>
              <td><input name="username" type="text" size="25" /><br /><br /></td>
            </tr>
            <tr>
              <td align="right">Password:</td>
              <td><input name="password" type="password" size="25" /><br /><br /></td>
            </tr>
            <tr>
              <td class="p3">&nbsp;</td>
              <td>

				<input name="redirect" value="../profile.php" type="hidden" />
				<input type="hidden" name="login" value="login" />
				<input name="sub" value="Log in" type="hidden" />
				<input name="sid" type="hidden" value="<?php echo $user->session_id; ?>" />
                <input type="submit" name="submit" value="LOGIN" /><span style="font-weight:normal; padding-left:20px;"><input type="checkbox" name="rememberme" /> remember me</span>
                <div align="right"></div></td>
            </tr>
            <tr>
              <td class="p3">&nbsp;</td>
              <td><a href="user/ucp.php?mode=sendpassword" class="p3">Forgot Your Password?</a></td>

          </table>
        </form>
<?php
@include('./includes/footer.php');
?>
