<?php
@include('sidebar.php')
?>

    <div id="page-wrapper">
        <div class="container-fluid">
            <!-- Page Heading -->
            <div class="row well" id="main" >
                <div class="col-sm-12 col-md-12 " id="content">
               
           <div class="panel with-nav-tabs panel-success">
                <div class="panel-heading">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab1success" data-toggle="tab">Inbox</a></li>
                            <li><a href="#tab2success" data-toggle="tab">Sent</a></li>
                            <li><a href="#tab3success" data-toggle="tab">Compose</a></li>
                            <li><a href="index.php" data-toggle="">My Profile </a></li>
                            
                            
                        </ul>
                </div>
                <div class="panel-body">
                    <div class="tab-content">
                        <div class="tab-pane fade in active" id="tab1success">
               <div id="AGENCYProfileMiddleContent" style="width:100%; min-height:560px">

<div id="messagelist">
   <form name="sendmessage" id="sendmessage" action="javascript:void(0)" method="post">
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


<div id="messagelist"><table align="center" cellspacing="0" cellpadding="5" width="100%">
    <tbody><tr bgcolor="#EAE6DB"><td align="left" width="100"><font color="#444444"><b>Sent To</b></font></td>
      <td align="left"><font color="#444444"><b>Subject</b></font></td>
      <td align="left" width="80"><font color="#444444"><b>Date Sent</b></font></td><td width="20">&nbsp;</td>
    </tr>
    <tr bgcolor="#FFFFFF" id="sent222">
      <td align="center"><img src="img/group.gif" border="0" width="40"><br><font color="#0000DD" size="1">test</font></td>
      <td align="left"><a href="javascript:loaddiv('messagelist', false, 'ajax/message_view.php?sent_id=222&amp;page=1&amp;sent=true&amp;')" style="text-decoration: none">test</a></td>
      <td align="center"><font color="#AAAAAA" size="1">06/12/2013</font></td><td><a href="javascript:void(0)" onclick="if (confirm('Are you sure you wish to remove this message from your Sent folder?')) loaddiv('sent222', false, 'ajax/message_process.php?deletesent=222')" style="font-weight:bold; text-decoration:none">x</a></td></tr>

    <tr bgcolor="#F8F7EC" id="sent221">
      <td align="center"><a href="profile.php?u=5678"><img src="img/group.gif" border="0" width="40"></a><br><font color="#0000DD" size="1">Oliver</font></td>
      <td align="left"><a href="javascript:loaddiv('messagelist', false, 'ajax/message_view.php?sent_id=221&amp;page=1&amp;sent=true&amp;')" style="text-decoration: none">test</a></td>
      <td align="center"><font color="#AAAAAA" size="1">06/12/2013</font></td><td><a href="javascript:void(0)" onclick="if (confirm('Are you sure you wish to remove this message from your Sent folder?')) loaddiv('sent221', false, 'ajax/message_process.php?deletesent=221')" style="font-weight:bold; text-decoration:none">x</a></td></tr>

    <tr bgcolor="#FFFFFF" id="sent220">
      <td align="center"><a href="profile.php?u=5678"><img src="img/group.gif" border="0" width="40"></a><br><font color="#0000DD" size="1">Oliver</font></td>
      <td align="left"><a href="javascript:loaddiv('messagelist', false, 'ajax/message_view.php?sent_id=220&amp;page=1&amp;sent=true&amp;')" style="text-decoration: none">test message</a></td>
      <td align="center"><font color="#AAAAAA" size="1">06/12/2013</font></td><td><a href="javascript:void(0)" onclick="if (confirm('Are you sure you wish to remove this message from your Sent folder?')) loaddiv('sent220', false, 'ajax/message_process.php?deletesent=220')" style="font-weight:bold; text-decoration:none">x</a></td></tr>

    <tr bgcolor="#F8F7EC" id="sent219">
      <td align="center"><a href="profile.php?u=5678"><img src="img/group.gif" border="0" width="40"></a><br><font color="#0000DD" size="1">Oliver</font></td>
      <td align="left"><a href="javascript:loaddiv('messagelist', false, 'ajax/message_view.php?sent_id=219&amp;page=1&amp;sent=true&amp;')" style="text-decoration: none">test</a></td><td align="center"><font color="#AAAAAA" size="1">06/12/2013</font></td>
      <td><a href="javascript:void(0)" onclick="if (confirm('Are you sure you wish to remove this message from your Sent folder?')) loaddiv('sent219', false, 'ajax/message_process.php?deletesent=219')" style="font-weight:bold; text-decoration:none">x</a></td></tr>

  <tr bgcolor="#FFFFFF" id="sent218"><td align="center"><a href="profile.php?u=5678"><img src="img/group.gif" border="0" width="40"></a><br><font color="#0000DD" size="1">Oliver</font></td>

  <td align="left"><a href="javascript:loaddiv('messagelist', false, 'ajax/message_view.php?sent_id=218&amp;page=1&amp;sent=true&amp;')" style="text-decoration: none">test test 2</a></td>
  <td align="center"><font color="#AAAAAA" size="1">06/12/2013</font></td><td><a href="javascript:void(0)" onclick="if (confirm('Are you sure you wish to remove this message from your Sent folder?')) loaddiv('sent218', false, 'ajax/message_process.php?deletesent=218')" style="font-weight:bold; text-decoration:none">x</a></td></tr>

<tr bgcolor="#F8F7EC" id="sent217"><td align="center"><a href="profile.php?u=5678"><img src="img/group.gif" border="0" width="40"></a><br><font color="#0000DD" size="1">Oliver</font></td>

<td align="left"><a href="javascript:loaddiv('messagelist', false, 'ajax/message_view.php?sent_id=217&amp;page=1&amp;sent=true&amp;')" style="text-decoration: none">test</a></td>
<td align="center"><font color="#AAAAAA" size="1">06/12/2013</font></td><td><a href="javascript:void(0)" onclick="if (confirm('Are you sure you wish to remove this message from your Sent folder?')) loaddiv('sent217', false, 'ajax/message_process.php?deletesent=217')" style="font-weight:bold; text-decoration:none">x</a></td></tr>

  </tbody>
</table>
<table width="100%" cellpadding="5" border="0" cellspacing="0"><tbody><tr><td align="left"></td><td align="right"></td></tr></tbody></table><br></div>



</div>
</div>





 <div class="tab-pane fade" id="tab3success">
<div id="AGENCYProfileMiddleContent" style="width:100%;">

  <div style="margin:20px; padding:10px" id="processmessage">

 
    <b>Compose Message:</b><br> <br>
Send To Lightbox: <select id="send_lb" name="send_lb" onchange="loaddiv('lb_list', false, 'ajax/lightbox_members.php?id='+this.value)">
            <option>Select A Lightbox</option><option value="9728">'Getting Ready': Dir</option><option value="10921">1953 (NEW ROLES</option><option value="15249">30 FRONT STREET</option><option value="13356">7 MISTAKES YOU’RE PROBABLY M</option><option value="13516">ADDITIONAL ROLE! The Wedding S</option><option value="15212">AMERICAN</option><option value="12503">American Cancer Society</option><option value="12504">American Cancer Society</option><option value="13127">Ashland Hair Care: SUBMIT IMME</option><option value="9081">Asian 25-30</option><option value="10614">Atlantic Magazine</option><option value="14173">ATV/UTV RIDERS FOR YAMAHA COMM</option><option value="14898">AUDIBLE AUDIO BOOKS</option><option value="8952">Avon</option><option value="13265">BLIND PSYCHOSIS</option><option value="13139">Brawl Stars (German)</option><option value="13185">BRIGHT IDEA</option><option value="11816">Calvary and Allied Cemeteries </option><option value="9319">Cape Cod Chips</option><option value="14515">CAPTURE (WORKING TITLE)EPISODE</option><option value="13179">Cat and The Moon EXTRAS URGENT</option><option value="11296">Celebrity Photographers Art Pr</option><option value="10122">CNN</option><option value="12005">Coca Cola</option><option value="12006">Coca Cola (New Spec): PLEASE S</option><option value="11924">Cycle - URGENT (script attache</option><option value="9742">Dockers/ Sports Illustrated</option><option value="10327">Facebook (New Roles)</option><option value="12941">Gardasil: Submit IMMEDIATELY (</option><option value="12942">Gardasil: Submit IMMEDIATELY (</option><option value="10583">Google (UPDATED ROLES)</option><option value="11471">Google TV: NEW ROLES</option><option value="10807">HEP C</option><option value="12690">HOMICIDE 103</option><option value="12688">Homicide City (103)</option><option value="14814">INFLUENCERS WANTED</option><option value="14815">INFLUENCERS WANTED</option><option value="12884">Inside Edition: Date Change SU</option><option value="10958">Insulin Pharma. (Revised Age)</option><option value="13732">INTIMISSIMI (Stand-In)</option><option value="11181">Jewelry V/O</option><option value="15075">KIDZ BOP BWL MUSIC VIDEO</option><option value="10575">Kmart</option><option value="10619">Kmart casting</option><option value="9048">Leather Goods Company:</option><option value="12526">Lifetime: LGBT Singles / Coupl</option><option value="12144">Lookbook</option><option value="12183">Lucky Charms: New Role</option><option value="14662">LUCKY JACK</option><option value="11946">Major Womens Magazine: URGENT</option><option value="11947">Major Womens Magazine: URGENT</option><option value="11948">Major Womens Magazine: URGENT</option><option value="11949">Major Womens Magazine: URGENT</option><option value="15010">MODEL NEEDED TOMORROW! (06/04)</option><option value="15156">MODELS NEEDED ASAP!</option><option value="12216">MUSIC VIDEO</option><option value="12984">Music Video: Friends (ADDITION</option><option value="14655">MYSTERY HOUSE- Updated!</option><option value="11656">Nasacort V/O: Still seeking</option><option value="12227">National Highway Traffic Safet</option><option value="13615">NEW ROLES ADDED: Pearson Educa</option><option value="10832">Nike (UPDATED)</option><option value="10148">Oncology Print</option><option value="13640">Online Documentary Project</option><option value="10188">Oral Surgery PSA</option><option value="11246">Organic Food Company: STILL SE</option><option value="11103">Outback: New Roles</option><option value="13587">PEARSON EDUCATION (LEVEL 1/2)</option><option value="15290">PHARMA-LOOKING FOR DWARFS</option><option value="11131">Pop-Up Theatrical Experience (</option><option value="13531">Pottery</option><option value="15165">PROJECT X</option><option value="13831">R &amp; FIELDS</option><option value="9174">Radio Shack</option><option value="12186">Robert Woods Johnson Barnabus </option><option value="12187">Robert Woods Johnson Barnabus </option><option value="9903">Sanofi (Hands)</option><option value="12930">Sansan (British)</option><option value="11610">Sears: URGENT</option><option value="13370">Shift Creative Fund Filmmaker </option><option value="12751">SLIMFAST</option><option value="10395">Social Ad</option><option value="15166">SOCIAL OCCASION DRESS COMPANY</option><option value="15168">SOCIAL OCCASION DRESS COMPANY</option><option value="11967">Spinbrush : Submit IMMEDIATELY</option><option value="12794">STEPHEN KNOLL NEW YORK</option><option value="15160">STEPHEN KNOLL NEW YORK: NEW RO</option><option value="15252">SUBMISSIONS- 30 FRONT STREET</option><option value="13325">Suicide by Sunlight</option><option value="13403">Tall Asian Men</option><option value="10390">Tech Office Commercial (New Ro</option><option value="13163">The Cat &amp; The Moon (SAG Ex</option><option value="13164">The Cat &amp; The Moon (SAG Ex</option><option value="13165">The Cat &amp; The Moon (SAG Ex</option><option value="13098">The Cat And The Moon</option><option value="14453">THE PLAN</option><option value="14225">THE VIEWPOINT COLLECTIVE</option><option value="14226">THE VIEWPOINT COLLECTIVE</option><option value="14227">THE VIEWPOINT COLLECTIVE</option><option value="14228">THE VIEWPOINT COLLECTIVE</option><option value="14224">THE VIEWPOINT COLLECTIVE AUDIT</option><option value="11998">Timberland: Urgent. NEW ROLES</option><option value="11135">Tommie Copper Wearable Wellnes</option><option value="10354">Tsingtao Beer</option><option value="11938">Tushy Toilet Fragrance</option><option value="15112">UNTITLED KIDS PROJECT</option><option value="13384">URGENT! Crime of Fashion</option><option value="14835">URGENT!! HAIR MODELS</option><option value="14650">URGENT!!MIU MIU CAMPAIGN</option><option value="13254">URGENT- Miranda</option><option value="11210">V/O for James Patterson (Scrip</option><option value="11209">V/O for James Patterson Book S</option><option value="9944">Vant Fashion shoot</option><option value="10438">Verizon</option><option value="10439">Verizon</option><option value="13141">VIM.com</option><option value="10715">WDL ' Cashmere'</option><option value="12217">Wet Shapes</option><option value="12218">Wet Shapes</option><option value="12219">Wet Shapes</option><option value="12220">Wet Shapes</option><option value="12223">Wet Shapes</option><option value="12109">Wom: URGENT</option><option value="9598">World Hunger PSA: Men  (Urgent</option></select> 
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
   
  <input type="button" onclick="if(!(document.getElementById('send_lb').value)) { alert('Please select a lightbox.'); } else if(!(document.getElementById('to_subject').value)) { alert('Please enter a Subject.'); } else { alert('lightbox expired. Please select another lightbox.'); } return false;" value="Send">
   
    
   

   
    </div>
  </div>
</div>
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

            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </div>
    <!-- /#page-wrapper -->


</div>
</div><!-- /#wrapper -->




  </body>
 
  </html>