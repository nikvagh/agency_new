<?php
include('includes/mysql_connect.php');
// $makepdf = true; // for testing

// if ($makepdf) {
// 	header("Content-type: application/pdf");
// 	define("RELATIVE_PATH", "html2fpdf/");
// 	define("FPDF_FONTPATH", "html2fpdf/font/");

// 	require_once(RELATIVE_PATH . "html2fpdf.php");

// 	class PDFX extends HTML2FPDF
// 	{
// 		//Page header
// 		function Header()
// 		{
// 			//Logo
// 			// $this->Image('images/logo.png',24,14,20);
// 		}
// 		function Footer()
// 		{
// 			$this->SetY(-15);
// 			$this->SetTextColor(0, 0, 255);
// 			$this->Cell(0, 10, 'www.theagency.com', 0, 0, 'L');
// 		}
// 	}
// 	$pdf = new PDFX('P', 'mm', 'compcard');
// 	$pdf->AliasNbPages();
// 	$pdf->AddPage();
// }

// $dom = new DOMDocument();
$url = BASE_URL.'compcard_html.php?u=' . $_GET['u'] . '&card_type=' . $_GET['card_type'];
$content = file_get_contents($url);

// if ($makepdf) {
// 	$pdf->WriteHTML($content);
// 	$pdf->Output();
// } else {
// 	echo $content;
// }

include("mpdf/mpdf.php");
$mpdfConfig = array(
	'mode' => 'utf-8', 
	'format' => 'A4',    // format - A4, for example, default ''
	'default_font_size' => 10,     // font size - default 0
	// 'default_font' => '',    // default font family
	// 'margin_left' => 8,    	// 15 margin_left
	// 'margin_right' => 8,    	// 15 margin right
	// 'margin_top' => 8,     // 16 margin top
	// 'margin_bottom' => 8,    	// margin bottom
	// 'margin_header' => 8,     // 9 margin header
	// 'margin_footer' => 8,     // 9 margin footer
	// 'orientation' => 'L'  	// L - landscape, P - portrait
); 
// $mpdf = new mPDF($mpdfConfig); 
$mpdf = new mPDF('utf-8', 'A4-L', '', '', 8, 8, 8, 8, 0, 0, 'P'); 
$mpdf->WriteHTML($content);
$mpdf->Output();
exit;

?>