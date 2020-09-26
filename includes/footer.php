<br clear="all" /><br clear="all" />

<div id="AGENCYcodebottom">
	<?php echo showbox('codebottom'); ?>
</div>
</div>
</div>

</div>
</div>
	</td>
	<td width="0" valign="top" align="left">
	<div style="margin-left:20px">
		<?php echo showbox('coderight'); ?>
	</div>
	</td>
	</tr>
	</table>



<div style="display:block; text-align:center; width:100%; clear:both">
<b><span style="font-size:14px">©</span> <?php echo date("Y"); ?> <a href="http://www.theagencyOnline.com">www.theagencyOnline.com</a></b> - constructed by <a href="http://www.motmotstudios.com" target="_blank">Motmot Studios Website Development</a>
</div>
<br /><br />
<span id="siteseal">
<script type="text/javascript" src="https://seal.godaddy.com/getSeal?sealID=3Ry8ofK1XcJBjvZG1xUIr9DDMQt1zyYfoxYZ1zSQItjgC415s9SlguT3"></script>
</span> 

<!-- Begin Official PayPal Seal -->
<a target="_blank" href="https://www.paypal.com/us/verified/pal=oliver%40theagencyonline%
2ecom"><img border="0" alt="Official PayPal Seal" src="https://www.paypal.com/en_US/i/icon/verification_seal.gif" /></a>
<!-- End Official PayPal Seal -->  



<!-- popups below -->
<div id="hiddenModalContent" style="display:none">
<!-- HTML code for popup starts here -->
<div id="popupcontent"> <!-- for some reason, this div tag needs to be here even if it is empty -->
loading content....




</div>
<!-- HTML code for popup ends here -->
</div>



<?php 
if(!isset($_SESSION['user_id'])) {
	// changing to ajax load when join button clicked
	// include('./includes/register_forms.php');  // registration forms and processing; only needed if not logged in
}

if(!empty($tt)) {
	echo '<div style="background-color:white; color:white">' . $tt . '</div>'; 
}
?>
</body></html>

<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script> <script type="text/javascript">
try {
var pageTracker = _gat._getTracker("UA-11323300-1");
pageTracker._trackPageview();
} catch(err) {}</script>  

<?php
// script has to be run after page loads:
if(!empty($endscript)) {
	echo $endscript;
}


mysql_close(); // Close the database connection.
ob_end_flush();
?>
