<?php
session_start();
include('../includes/agency_functions.php');
?>

<div id="join_options">
	<div style="font-size:18px; padding-top:20px" class="AGENCYRed">
		<b>Are you a:</b><br><br><br>
		<a href="signup.php"><b>Talent</b> - Are you an actor, model or performer?</a> 
		<br><br><br>
		<a href="javascript:void(0)" onclick="jQuery('#join_options').hide(); jQuery('#join_client').show();"><b>Client</b> - Do you have a casting or want to book talent?</a>
	
		<div align="center" style="font-size:12px; padding-top:150px; color:black">
			- OR Click below if you have already applied for an account and entered an email address.-
			<br><br>
			<a style="font-weight: normal; text-decoration: none;" href="forgotpassword.php">I forgot my password</a>
		</div>
	</div>
</div>

<div id="join_client" style="display:none">
	<div style="font-size:10px; line-height:2.2em;background-color:#FFEEDD;padding: 10px;">
		<div class="AGENCYblueheading" style="padding-bottom:10px">Join! It's Free. (Really!)</div>
		<?php if(isset($message_client)) echo $message_client; ?>
		<form action="home.php" method="post" id="join" name="join">
		<input class="AGENCYformtext" type="text" name="company" maxlength="50" value="<?php if (isset($_POST['company'])) echo $_POST['company']; ?>" /><span style="float:right">Company: </span><br />
		<input class="AGENCYformtext" type="text" name="profession" maxlength="50" value="<?php if (isset($_POST['profession'])) echo $_POST['profession']; ?>" /><span style="float:right">Profession: </span><br />
		<input class="AGENCYformtext" type="text" name="firstname" maxlength="50" value="<?php if (isset($_POST['firstname'])) echo $_POST['firstname']; ?>" /><span style="float:right">First Name: </span><br />
		<input class="AGENCYformtext" type="text" name="lastname" maxlength="50" value="<?php if (isset($_POST['lastname'])) echo $_POST['lastname']; ?>" /><span style="float:right">Last Name: </span><br />
		<input class="AGENCYformtext" type="text" name="email" maxlength="100" value="<?php if (isset($_POST['email'])) echo $_POST['email']; ?>" /><span style="float:right">Email: </span><br />
		<input class="AGENCYformtext" type="text" name="confirmemail" maxlength="100" value="<?php if (isset($_POST['confirmemail'])) echo $_POST['confirmemail']; ?>" /><span style="float:right">Confirm Email: </span><br />
		<input class="AGENCYformtext" type="text" name="username" maxlength="30" value="<?php if (isset($_POST['username'])) echo $_POST['username']; ?>" /><span style="float:right">Username: </span><br />
		<input class="AGENCYformtext" type="password" name="joinpassword" maxlength="30" value="<?php if (isset($_POST['joinpassword'])) echo $_POST['joinpassword']; ?>" /><span style="float:right">Password: </span><br />
		<input class="AGENCYformtext" type="password" name="confirmpassword" maxlength="30" value="<?php if (isset($_POST['confirmpassword'])) echo $_POST['confirmpassword']; ?>" /><span style="float:right">Confirm Password: </span><br /> 
        
        

<select style="float:right; width:229px; margin-bottom:5px" name="location">
<?php
			foreach($locationarray as $location) {
				echo '<option value="' . $location . '">' . $location . '</option>';
			}
?>
<option value="Other">Other</option>
</select><span style="float:right">Please Select your Region: </span><br />
<input class="AGENCYformtext" type="text" name="otherlocation">
<span style="float:right">Other Location (optional): </span><br />      
   <br clear="all" />     
       <input name="agree_terms" type="checkbox" /> I have read and agree to the <a href="index2.php?pageid=68" target="_blank">terms and conditions</a> 
               <br />
            <input id="recaptcha_response" type="hidden" value="" name="g-recaptcha-response"/>
          
            <div id="myCaptcha" data-callback="verifyCallback"></div>
            
		<div align="right"><input type="submit" value="Join" name="submitclient" style="background-color:#DDD" /></div>
		</form>
	</div>
</div>

<script type="text/javascript">

jQuery(document).ready(function(){
	var captchaWidgetId = grecaptcha.render( 'myCaptcha', {
	  'sitekey' : '6LeZ-EkUAAAAAK371tlFWsg24d2Q7X5a0cb-Wjk7'
	});
	
	
	// grecaptcha.reset();
});
var verifyCallback = function( response ) {
	
	console.log( 'g-recaptcha-response: ' + response );
	jQuery("#recaptcha_response").val(response);
};
	
</script>
<?php
if(!empty($message_talent) && isset($_POST['submittalent'])) {
?>
<script type="text/javascript">

jQuery(document).ready(function(){
tb_show("","#TB_inline?height=460&amp;width=450&amp;inlineId=hiddenModalContent", "");
loaddiv('popupcontent', 'join_talent');
});
</script>
<?php
}


if(!empty($message_client) && isset($_POST['submitclient'])) {
?>
<script type="text/javascript">

jQuery(document).ready(function(){
tb_show("","#TB_inline?height=460&amp;width=450&amp;inlineId=hiddenModalContent", "");
loaddiv('popupcontent', 'join_client');
});
</script>
<?php
}
?>