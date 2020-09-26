<?php
include('header.php');
?>
<div align="center">
  <div class="adminheading">Testimonials</div>

  <?php
if(isset($_SESSION['admin'])) {
	$success = FALSE; // flag for showing or not showing form

	if (isset($_POST['submit']) && !empty($_POST['zone']) && isset($_POST['code'])) {
		$code = escape_data($_POST['code']);
		$zone = escape_data($_POST['zone']);
		mysql_query("UPDATE agency_vars SET varvalue='$code' WHERE varname='$zone'");
		if(mysql_affected_rows()) {
			$success = TRUE;
			echo '<br /><br /><div align="center"><font size="+1" color="#990000"><b>Your settings have been updated.  Thank you.</b></font><br /><br />';
		}
	}
?>
  <br />
  <div id="AGENCY_ul_spaced" style="text-align:left; font-weight:bold">
  Select testimonial to edit:<br /><br />


  <ul>
  <li><a href="testimonials.php?zone=testimonials_1#form">Testimonial Box #1</a></li>
  <li><a href="testimonials.php?zone=testimonials_2#form">Testimonial Box #2</a></li>
  <li><a href="testimonials.php?zone=testimonials_3#form">Testimonial Box #3</a></li>
  <li><a href="testimonials.php?zone=testimonials_4#form">Testimonial Box #4</a></li>
  <li><a href="testimonials.php?zone=testimonials_5#form">Testimonial Box #5</a></li>
  <li><a href="testimonials.php?zone=testimonials_6#form">Testimonial Box #6</a></li>
  <li><a href="testimonials.php?zone=testimonials_7#form">Testimonial Box #7</a></li>
  <li><a href="testimonials.php?zone=testimonials_8#form">Testimonial Box #8</a></li>
  <li><a href="testimonials.php?zone=testimonials_9#form">Testimonial Box #9</a></li>
  <li><a href="testimonials.php?zone=testimonials_10#form">Testimonial Box #10</a></li>  </ul>

  </div>

  <br /><br /><br />

 <a name="form">
  <br />
  <?php
	if (!$success && !empty($_GET['zone'])) {
		$zone = escape_data($_GET['zone']);
		$code = mysql_result(mysql_query("SELECT varvalue FROM agency_vars WHERE varname='$zone'"), 0, 'varvalue');
?>
<div style="position:relative">
	<div style="position:absolute; right:0px; top:0px; margin-right:-262px;">place mouse over box to update</div>
   <div align="left" style="position:absolute; right:0px; top:16px; margin-right:-912px; width:900px; height:260px; overflow:hidden; border:1px solid gray" onMouseOver="fillcontent()">
   <div  id="viewit"></div>
  </div>
  <form method="post" action="testimonials.php">
  <b>Edit Block</b> (wait to fully load):<br />
  <textarea id="code" name="code" cols="80" rows="20"><?php if(isset($code)) echo $code;?></textarea>
<br />
Note: Dimensions are Width:900px, Height:260px
  
  <input type="hidden" name="zone" value="<?php echo $zone; ?>"
    <br /><br />
    <input type="submit" value="Update" name="submit">
  </form>

  
  

  <br />
  <br />
  <form action="index.php">
    <input type="submit" value="Cancel">
  </form>
  <br />
  <br />
</div>
<script type="text/javascript" src="../fckeditor/fckeditor.js"></script>
<script type="text/javascript">
function fillcontent() {
	var oEditorHTML = FCKeditorAPI.GetInstance('code').GetHTML();
	document.getElementById('viewit').innerHTML = oEditorHTML;
}

function FCKeditor_OnComplete( editorInstance )
{
    if (document.all) // IE
    {      
        editorInstance.EditorDocument.attachEvent('onkeyup', fillcontent);
    } 
    else // other browser
    {
        editorInstance.EditorDocument.addEventListener( 'keyup', fillcontent, true);
    } 
}

window.onload = function()
{
var oFCKeditor = new FCKeditor( 'code', '100%', '400' ) ;
oFCKeditor.BasePath = "../fckeditor/" ;
oFCKeditor.Config["EditorAreaCSS"] = "custom.css"  ;
oFCKeditor.ReplaceTextarea() ;

}
</script>
<?php
	}
?>
</div>
<?php
} else {
	$url = "index.php";
	ob_end_clean(); // Delete the buffer.
	header("Location: $url");
	exit(); // Quit the script.
}
include('footer.php');
?>
