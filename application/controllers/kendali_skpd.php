<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Kendali_skpd extends CI_Controller
{
	var $CI = NULL;
	public function __construct()
	{
		$this->CI =& get_instance(); 
        parent::__construct();
        //$this->load->helper(array('form','url', 'text_helper','date'));        
        $this->load->database();
        //$this->load->model('m_musrenbang','',TRUE);
        $this->load->model(array('m_kendali_skpd','m_urusan', 'm_bidang', 'm_program', 'm_kegiatan'));
        if (!empty($this->session->userdata("db_aktif"))) {
            $this->load->database($this->session->userdata("db_aktif"), FALSE, TRUE);
        }
	}

	function index()
	{
		$this->output->enable_profiler(TRUE);
		$this->auth->restrict();
		//$data['url_delete_data'] = site_url('cik/delete_cik');
		$this->template->load('template','kendali_renja/view_kendali_skpd');
	}

	function load_data()
	{
		$search = $this->input->post("search");
		$start = $this->input->post("start");
		$length = $this->input->post("length");
		$order = $this->input->post("order");
		
		$kendali_skpd = $this->m_kendali_skpd->get_data_table($search, $start, $length, $order["0"]);		
		$alldata = $this->m_kendali_skpd->count_data_table($search, $start, $length, $order["0"]);

		$data = array();
		$no=0;


		foreach ($kendali_skpd as $row) {
			$no++;
			$data[] = array(
							$no, 
							$row->kd_urusan.".".
							$row->kd_bidang.".".
                            $row->kd_program.".".
                            $row->kd_kegiatan,
                            $row->ind_kinerja_renja,
                            $row->ind_kinerja_rka,
                            $row->lokasi_rencana_renja,
                            $row->lokasi_rencana_rka,
                            $row->target_rencana_renja,
                            $row->target_rencana_rka,
                            $row->dana_rencana_renja,
                            $row->dana_rencana_rka,
                            $row->target_prakiraan_renja,
                            $row->target_prakiraan_rka,
                            $row->dana_prakiraan_renja,
                            $row->dana_prakiraan_rka,
                            $row->kesesuaian_ya,
                            $row->kesesuaian_tidak,
                            $row->hasil_pengendalian,
                            $row->tindak_lanjut,
                            $row->hasil_tindak_lanjut,
							'<a href="javascript:void(0)" onclick="edit('. $row->id_kendali_skpd .')" class="icon2-page_white_edit" title="Edit"/>
							<a href="javascript:void(0)" onclick="delete('. $row->id_kendali_skpd .')" class="icon2-delete" title="Hapus"/>'
							);
		}
		$json = array("recordsTotal"=> $alldata, "recordsFiltered"=> $alldata, 'data' => $data);
		
        echo json_encode($json);
	}
}