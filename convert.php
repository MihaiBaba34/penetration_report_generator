<?php
ini_set('display_errors', 1);
$arr = $_POST['result'];
// print_r($_POST);
require('WriteHTML.php');
$pdf=new PDF_HTML();
$pdf->Open();
$pdf->AddPage();
$pdf->SetTitle('Penetration Report');
$pdf->SetAutoPageBreak(true, 15);
	foreach ($arr as $k => $val) {
		$pdf->WriteHTML('Record: '.($k+1));
		$pdf->Ln();
		foreach ($val as $key => $value) {
			//echo $key." : ".$value;
			$pdf->WriteHTML2('<table><tr><td>'.$key.'</td><td bgcolor="#D0D0FF" width="100">'.$value.'</td></tr></table>');
				$pdf->SetFont('courier','B',7);
		}
		$pdf->Ln();
	}
ob_end_clean();
$pdf->Output();
$pdf->Output('','I');  // display on the browse
exit;