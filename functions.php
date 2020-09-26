<!DOCTYPE html>
<html>
<head>
  <title>My Message</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="dashboard1.css">
<link rel="stylesheet" href="css/bootstrap.min.css">
 <link rel="stylesheet" href="css/font-awesome.min.css">
 <script src="js/jquery.min.js"></script>
 <script src="js/bootstrap.min.js"></script>
  <link rel="shortcut icon" href="image/fav2.png" type="image/x-icon">
</head>
<body>
<?php
session_start();
@include('header_dashboard.php');
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

<div class="col-md-12">
  <div class="col-md-2">
           <?php
  @include('sidebar_talent.php')
?>
  </div>



<div class="col-md-10">
<div class="content2">
      <?php
  @include('dash_header.php')
?>
<div class="section">
   <div class="row">
    <div class="col-md-6">
<div class="wecome-text">
    <?php
    $sql = "SELECT firstname, lastname FROM agency_profiles WHERE user_id='$loggedin'";
               $result = mysql_query($sql);
               $row = sql_fetchrow($result);
    ?>
<h2> Welcome <?php print_r($row['firstname']); ?></h2>
</div>
</div>
 <div class="col-md-6">
<div class="wecome-text">
<h3> My profile is active</h3>  
</div>
</div>
</div>
<div class="roaster1">
  <div class="col-md-12">
           <div class="panel with-nav-tabs panel-success">
                <div class="panel-heading">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab1success" data-toggle="tab">Inbox</a></li>
                            <li><a href="#tab2success" data-toggle="tab">Sent</a></li>
                            <li><a href="#tab3success" data-toggle="tab">Compose</a></li>
                            <li><a href="talent-profile.php" data-toggle="">My Profile </a></li>
                            
                            
                        </ul>
                </div>
                <div class="panel-body">
                    <div class="tab-content">
                        <div class="tab-pane fade in active" id="tab1success">
               <div id="AGENCYProfileMiddleContent" style="width:100%; min-height:560px">

<div id="messagelist">
  <table align="center" cellspacing="0" cellpadding="5" width="100%">
    <tbody>
      <tr bgcolor="#EAE6DB">
        <td align="left" style="width:33%; float:left; text-align: center;"><font color="#444444"><b>Sent By</b></font></td>
      <td align="left" style="width:33%; float:left; text-align: center;"><font color="#444444"><b>Subject</b></font></td>
      <td align="left" style="width:33%; float:left; text-align: center;"><font color="#444444"><b>Date Sent</b></font></td><td width="20">&nbsp;</td>
    </tr></tbody></table><br><div align="center">You have no messages.</div></div>
     </div>

                        </div>



 <div class="tab-pane fade" id="tab2success">
       <div id="AGENCYProfileMiddleContent" style="width:100%; min-height:560px">

<div id="messagelist">
  <table align="center" cellspacing="0" cellpadding="5" width="100%">
    <tbody>
      <tr bgcolor="#EAE6DB">
        <td align="left" style="width:33%; float:left; text-align: center;"><font color="#444444"><b>Sent To</b></font></td>
      <td align="left" style="width:33%; float:left; text-align: center;"><font color="#444444"><b>Subject</b></font></td>
      <td align="left" style="width:33%; float:left; text-align: center;"><font color="#444444"><b>Date Sent</b></font></td><td width="20">&nbsp;</td>
    </tr></tbody></table><br><div align="center">You have no messages.</div></div>
     </div>
 </div>

 <div class="tab-pane fade" id="tab3success">
<div id="AGENCYProfileMiddleContent" style="width:100%;">

  <div style="margin:20px; padding:10px" id="processmessage">

  <form name="sendmessage" id="sendmessage" action="javascript:void(0)" method="post">
    <b>Compose Message:</b><br> <br>
        
        
        Recipient:<br><a href="javascript:void(0)" onclick="document.getElementById('to_friendlist').style.display=''" id="to_image" style="color:black; text-decoration:none">(click a Friend to send them a message)</a>
        
        
<br><br><p align="center">You may only send messages to your friends.  To make a friend, go to a member profile and send a friend request.</p> 
        <div id="lb_list"></div>
            <br>
            Subject:<br>
            <input type="text" style="width:100%; font-size:12px" name="subject" id="to_subject">
            <br><br>
            Message:<br>
            <textarea style="width:100%; font-size:12px" rows="10" name="message"></textarea><br>
            <br>
            <input type="hidden" value="" name="to" id="to_id">
            <input type="hidden" value="true" name="sendit">
            <input type="hidden" name="creation_time" value="1349132467">
        <input type="hidden" name="form_token" value="478146734f72e7b9819baff01bf01a4c75e4f38e">
   
  <input type="button" onclick="if(!(document.getElementById('to_id').value)) { alert('Please select a recipient.'); } else if(!(document.getElementById('to_subject').value)) { alert('Please enter a Subject.'); } else { submitform (document.getElementById('sendmessage'),'ajax/message_process.php','processmessage',validatetask); } return false;" value="Send">
   
    
    </form>
  </div>

  </div>


</div>




                      



                       





                    </div>
                </div>
            </div>

          <!--/tab-pane-->
          </div>

  </div>
</div>

</div>
</div>
</div>



















</body>
</html>