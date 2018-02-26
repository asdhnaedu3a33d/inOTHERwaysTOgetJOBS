<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
function generate_pdf($object, $filename='', $direct_download=TRUE) 
{
    require_once("dompdf/dompdf_config.inc.php");
    
    $dompdf = new DOMPDF();
      
    $dompdf->load_html($object);
    $dompdf->set_paper("A4","Landscape");
        
    $dompdf->render();
    if ($direct_download == TRUE) {
        $dompdf->stream($filename);
    } else {
        return $dompdf->output();
    }
}

function generate_pdf_portrait($object, $filename='', $direct_download=TRUE) 
{
    require_once("dompdf/dompdf_config.inc.php");
    
    $dompdf = new DOMPDF();
      
    $dompdf->load_html($object);
    $dompdf->set_paper("Legal");
        
    $dompdf->render();
    if ($direct_download == TRUE) {
        $dompdf->stream($filename);
    } else {
        return $dompdf->output();
    }
}

function generate_pdf_A4($object, $filename='', $direct_download=TRUE) 
{
    require_once("dompdf/dompdf_config.inc.php");
    
    $dompdf = new DOMPDF();
      
    $dompdf->load_html($object);
    $dompdf->set_paper("A4");
        
    $dompdf->render();
    if ($direct_download == TRUE) {
        $dompdf->stream($filename);
    } else {
        return $dompdf->output();
    }
}

function generate_pdf_legal_landscape($object, $filename='', $direct_download=TRUE) 
{
    require_once("dompdf/dompdf_config.inc.php");
    
    $dompdf = new DOMPDF();
      
    $dompdf->load_html($object);
    $dompdf->set_paper("Legal","Landscape");
        
    $dompdf->render();
    if ($direct_download == TRUE) {
        $dompdf->stream($filename);
    } else {
        return $dompdf->output();
    }
}