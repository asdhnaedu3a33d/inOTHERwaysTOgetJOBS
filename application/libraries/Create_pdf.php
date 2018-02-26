<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
require_once dirname(__FILE__) . '/mpdf/mpdf.php';

if (!defined('_MPDF_PATH')) define('_MPDF_PATH', APPPATH . 'libraries/mpdf/');

class Create_pdf {


	function Create_pdf(){
		$this->ci =& get_instance();
		//$this->ci->load->library('session');
		
	}


	function load($html,$namafile,$ukuran,$metode){

		$mpdf =  new mPDF('utf-8', $ukuran);
	

		$mpdf->	SetTitle($namafile);
		 $mpdf->SetHTMLFooter('
					<table width="100%" style="vertical-align: bottom; font-family: serif; font-size: 8pt; color: #000000; font-weight: bold; font-style: italic;"><tr>
					<td width="33%"> <img style="margin-top:-2px; margin-left: -2px;" height="18" width="18" src="' . base_url() . 'asset/images/S_4_sirenbangda_black.png"><font size="3">I</font>RENBANGDA </td>
					<td width="33%" align="right" style="font-weight: bold; ">{PAGENO}/{nbpg}</td>
					</tr></table>');

		$mpdf->WriteHTML($html);
		if ($metode=='Print') {
			$mpdf->Output($namafile.'.pdf','D');
		}else{
			$mpdf->Output(); 
		}

	}

}