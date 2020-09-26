<!DOCTYPE html>
<?php
session_start(); // for temporary login, a session is needed
@include('header_dashboard.php');
unset($loggedin); // avoid XSS
if (!empty($_SESSION['user_id'])) { // check if user is logged in
   $loggedin = $_SESSION['user_id'];
} else { // if not logged in, redirect to login page
  $url = 'login.php';
  ob_end_clean(); // Delete the buffer.
  header("Location: $url");
  exit(); // Quit the script.
}
$userid = $loggedin;
$profileid = $loggedin;
?>
<html>
<head>
  <title>Change Passwordaa</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="dashboard1.css">
<link rel="stylesheet" href="css/bootstrap.min.css">
 <link rel="stylesheet" href="css/font-awesome.min.css">

 <script src="js/bootstrap.min.js"></script>
</head>
<body>


<div class="col-md-12">
  <div class="col-md-2">
           <?php
  @include('sidebar_talent.php')
?>
  </div>



<div class="col-md-10">
  
        <?php

  @include('dash_header.php')

?>
<div class="content2">


<div class="section">
<div class="roaster1">
  <div class="col-md-12">
           
<div align="center">
      <?php
	$success = false;
	$error = '';
	if(!empty($_POST['submit'])) {
		if(!empty($_POST['original']) && !empty($_POST['new1']) && !empty($_POST['new2'])) {
			$agency_pw = escape_data($_POST['original']);
			$sql = "SELECT user_password FROM forum_users WHERE user_id='$userid'";
			$result = mysql_query($sql);
			if($row = @mysql_fetch_array ($result, mysql_ASSOC)) {
				 $password = $row['user_password'];

				 if(_check_hash($agency_pw, $password)) { // original password checks out
					
					// Check for a password and match against the confirmed password.
					if (eregi ('^[[:alnum:]]{6,20}$', stripslashes(trim($_POST['new1']))) && eregi ('^[[:alnum:]]{6,20}$', stripslashes(trim($_POST['new2'])))) {
						$p = escape_data($_POST['new1']);
						$p2 = escape_data($_POST['new2']);
						if($p == $p2) {
							
							$p = _hash($p);
							$query = "UPDATE forum_users SET user_password='$p' WHERE user_id='$userid'";
							mysql_query($query);
							$success = true;
							
						} else {
							$error =  'YOUR PASSWORD ENTRIES DID NOT MATCH.  PLEASE BE SURE BOTH THE PASSWORD AND CONFIRM PASSWORD FIELDS ARE IDENTICAL.';
						}
					} else {
						$error =  'PLEASE ENTER A VALID PASSWORD (BETWEEN 6 AND 20 ALPHANUMERIC CHARACTERS)';
					}
				 } else {
					 $error = 'THE ORIGINAL PASSWORD WAS NOT ENTERED CORRECTLY.  YOU MAY NOT CHANGE YOUR PASSWORD UNLESS YOU KNOW YOUR CURRENT PASSWORD.  REMEMBER PASSWORDS ARE CASE SENSITIVE AND MUST BE ENTERED EXACTLY AS THEY WERE CREATED.  PLEASE TRY AGAIN.';
				 }
			}
		} else {
			$error = 'NOT ALL FIELDS WERE FILLED.  PLEASE FILL ALL FIELDS.';
		}
	}
	
	if($success) {
		echo '<div style="font-weight:bold; padding:12px; border:1px solid gray;">YOUR PASSWORD HAS BEEN UPDATED.  PLEASE BE SURE TO WRITE IT DOWN IN A SAFE PLACE SO YOU DON\'T FORGET IT.</div>';
		
		
	} else {
		if(!empty($error)) {
			echo '<div style="font-weight:bold; color:red; padding:12px; border:1px solid gray;">' . $error . '</div>';
		}
	}
?>	
      
        
     <br>
<form action="change-password.php" method="post" name="changepw">
     <table cellpadding="8">
     <tbody><tr>
     <td align="right">Current Password:</td><td align="left"><input type="password" name="original"></td>
     </tr>
     <tr>
     <td align="right">New Password:</td><td align="left"><input type="password" name="new1"></td>
     </tr>
     <tr>
     <td align="right">Confirm New Password:</td><td align="left"><input type="password" name="new2"></td>
     </tr>
     </tbody></table>
   <br>
    <input type="submit" value="Submit" name="submit">
  </form>
<br>
  <form action="profile.php" style="padding-bottom:20px">
    <input type="submit" value="Cancel">
  </form>

</div>
          <!--/tab-pane-->
          </div>

  </div>
</div>

</div>
</div>
</div>





<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        
        <div class="modal-body">
          <div id="popupcontent">
  <br><b>GENERAL INFO:</b><br>
  <div style="border:1px solid black; min-height:300px; margin:20px; padding:10px">
Experience Level:<br><br>Gender: <b>Male</b><br><br>Ethnicity: <b>Asian</b><br><br>Location: <b>dvcd, Alaska United States</b><br><br>Categories: <b>fashion</b><br><br>Unions: <b>SAG-Eligible</b><br><br>Height: <b>3' 0"</b><br><br>
  Waist: <b>20"</b><br><br>Suit: <b>30 XS</b><br><br>
  Neck: <b>8.0"</b><br><br>
  Shirt: <b>S"</b><br><br>
  Inseam: <b>8"</b><br><br>Shoe: <b>1.0</b><br><br>
  Hair: <b>Black</b><br><br>
  Eyes: <b>Black</b><br><br><a class="AGENCY_graybutton" href="myaccount.php">edit</a>  </div>
</div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>


<div class="modal fade" id="myModal2" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        
        <div class="modal-body">
         <div id="popupcontent">
  <br><b>RESUME:</b><br>
  <div id="resume_main" style="border:1px solid black;  min-height:300px; margin:20px; padding:10px">
<div align="center">Click <a class="AGENCY_graybutton" href="myaccount.php?tab=bio#resumeanchor">EDIT</a> to type in your resume for easy viewing<br><br>OR use the form below to upload a document</div><hr>
<div align="center">
no resume on file<div style="padding:30px 0">
    <form enctype="multipart/form-data" action="profile.php?u=15798" method="post">
      <input type="hidden" name="MAX_FILE_SIZE" value="5000000">
      <input type="file" name="resumefile"><br><br>
      <input type="submit" name="submit" value="Upload New Resume (5MB)">
    </form>
    <br><font color="gray">*for better security, do not use "resume"<br>or your name for the resume filename</font>
    </div>  </div>
  </div>
</div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>


<div class="modal fade" id="myModal3" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        
        <div class="modal-body">
        <div id="popupcontent">
  <br><b>HEADSHOT:</b><br>
  <div style="border:1px solid black;  min-height:300px; margin:20px; padding:10px" align="center">
no headshot on file<div style="padding:50px 0">
      <form enctype="multipart/form-data" action="profile.php?u=15798" method="post">
        <input type="hidden" name="MAX_FILE_SIZE" value="5000000">
        <input type="file" name="headshotfile"><br><br>
        <input type="submit" name="submit" value="Upload New Headshot (<5MB)">
      </form>
      </div>  </div>
</div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>


<div class="modal fade" id="myModal4" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        
        <div class="modal-body">
        <div id="popupcontent">
  <br><b>BIOGRAPHY:</b><br>
  <div style="border:1px solid black;  min-height:300px; margin:20px; padding:10px">
This user has has not entered a biography or has set their biography to private.<br><br><a class="AGENCY_graybutton" href="myaccount.php?tab=bio">edit</a>  </div>
</div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>


<div class="modal fade" id="myModal5" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        
        <div class="modal-body">
        <div id="popupcontent">
  <br><b>LINKS:</b><br>
  <div style="border:1px solid black; width:400px; margin:20px; padding:10px">
<br><br><a class="AGENCY_graybutton" href="myaccount.php?tab=links">edit</a>  </div>
</div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>

<div class="modal fade" id="myModal6" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        
        <div class="modal-body">
      <div id="popupcontent">
  <br><b>SKILLS:</b><br>
  <div style="border:1px solid black; width:400px; min-height:300px; margin:20px; padding:10px">
<br><br><a class="AGENCY_graybutton" href="myaccount.php?tab=experience">edit</a> </div>
</div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>



</body>
</html>