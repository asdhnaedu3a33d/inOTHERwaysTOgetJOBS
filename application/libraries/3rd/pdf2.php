<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    class Pdf2 {
        function pdf_create($html2, $nama, $orintasi='P',$ukuran ='A4'){
            require_once('asset/tcpdf/config/lang/ind.php');
			require_once('asset/tcpdf/tcpdf.php');
			
           //$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
           $pdf = new TCPDF($orintasi, PDF_UNIT, $ukuran, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Siaku Udayana');
$pdf->SetTitle('Laporan Keuangan');
$pdf->SetSubject('Laporan Keuangan');
$pdf->SetKeywords('siaku, laporan');

// set default header data
//$pdf->SetHeaderData(PDF_HEADER_LOGO, 20, PDF_HEADER_TITLE, PDF_HEADER_STRING);

// remove default header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

//set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

//set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

//set some language-dependent strings
$pdf->setLanguageArray($l);

// ---------------------------------------------------------

// set default font subsetting mode
$pdf->setFontSubsetting(true);

// Set font
// dejavusans is a UTF-8 Unicode font, if you only need to
// print standard ASCII chars, you can use core fonts like
// helvetica or times to reduce file size.
$pdf->SetFont('times', '', '12', '', true);

// Add a page
// This method has several options, check the source code documentation for more information.
$pdf->AddPage();

// Set some content to print



$html = <<<EOF
$html2
EOF;
//echo $html;
// Print text using writeHTMLCell()
//$pdf->writeHTMLCell($html);	
//$pdf->writeHTMLCell($w=0, $h=0, $x='', $y='', $html, $border=0, $ln=1, $fill=0, $reseth=true, $align='', $autopadding=true);
    $pdf->writeHTML($html, true, false, false, false, '');
// ---------------------------------------------------------

// Close and output PDF document
// This method has several options, check the source code documentation for more information.
    $pdf->Output($nama.'.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
		   
		   
		   
		   
        }
    }
?>