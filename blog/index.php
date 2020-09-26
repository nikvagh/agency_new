<?php
$url = '../theangle/';
ob_end_clean(); // Delete the buffer.
header("Location: $url");
exit(); // Quit the script.		
?>