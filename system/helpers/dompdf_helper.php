<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
function pdf_create($html, $filename='', $paper, $type_paper, $stream=TRUE, $file_to_save = 'file_upload/file.pdf') 
{
    require_once("dompdf/dompdf_config.inc.php");

    $dompdf = new DOMPDF();
    $dompdf->load_html($html);
    $dompdf->set_paper($paper, $type_paper);
    $dompdf->render();
    if ($stream) {
        $dompdf->stream($filename.".pdf");
    } else {
        $file_to_save = dirname($_SERVER["SCRIPT_FILENAME"]).'/'.$file_to_save;
        //return $dompdf->output();        
        //save the pdf file on the server
        file_put_contents($file_to_save, $dompdf->output()); 
        //print the pdf file to the screen for saving
        header('Content-type: application/pdf');
        header('Content-Disposition: inline; filename="file.pdf"');
        header('Content-Transfer-Encoding: binary');        
        header('Accept-Ranges: bytes');
        readfile($file_to_save);
    }
}
?>