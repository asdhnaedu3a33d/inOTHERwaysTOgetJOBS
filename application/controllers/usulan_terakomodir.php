<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	class Usulan_terakomodir extends CI_Controller {
		
		var $CI = NULL;
		public function __construct(){
			$this->CI =& get_instance();
			parent::__construct();
			$this->load->model(array('m_usulan_terakomodir','m_skpd', 'm_template_cetak', 'm_lov', 'm_urusan', 'm_bidang',
									 'm_program', 'm_kegiatan','m_settings'));
	        if (!empty($this->session->userdata("db_aktif"))) {
	            $this->load->database($this->session->userdata("db_aktif"), FALSE, TRUE);
	        }
		}
		
		function index(){
			$this->auth->restrict();
			$id_group = $this->session->userdata('id_group');
			$ta = $this->session->userdata('t_anggaran_aktif');
			if (empty($id_group)) {
				$this->session->set_userdata('msg_typ','err');
				$this->session->set_userdata('msg', 'User tidak memiliki akses untuk melihat Usulan Terakomodir, mohon menghubungi administrator.');
				redirect('home');
			}
			$data = $this->get_urusan($ta);
	
			$this->template->load('template', 'usulan_terakomodir/preview_usulan', $data);
	
		}
		
		private function get_urusan($ta){
			//$proses = $this->m_renja_trx->count_jendela_kontrol($id_skpd);
			$data['usulan_type'] = "Usulan Terakomodir";
	
			//$protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://';
			//$header = $this->m_template_cetak->get_value("GAMBAR");
			//$data['logo'] = str_replace("src=\"","height=\"90px\" src=\"".$protocol.$_SERVER['HTTP_HOST'],$header);
			//$data['header'] = $this->m_template_cetak->get_value("HEADER");
	
			$data2['urusan'] = $this->m_usulan_terakomodir->get_urusan_usulan($ta);
	
			$data2['ta'] = $ta;
			$data['usulan'] = $this->load->view('usulan_terakomodir/cetak/usulan_terakomodir_all', $data2, TRUE);
			return $data;
		}
		
		function do_cetak_usulan(){
		ini_set('memory_limit','-1');
		$ta = $this->session->userdata('t_anggaran_aktif');
			$data = $this->get_urusan($ta);
			$data['qr'] = $this->ciqrcode->generateQRcode("sirenbangda", 'Usulan Terakomodir '. date("d-m-Y_H-i-s"), 1);
			$html = $this->template->load('template_cetak', 'usulan_terakomodir/cetak/cetak_usulan', $data, TRUE);

			$filename = 'Usulan-Terakomodir_'. date("d-m-Y_H-i_s") .'.pdf';
			pdf_create($html,$filename,"A4","Landscape");
		}
	}
?>