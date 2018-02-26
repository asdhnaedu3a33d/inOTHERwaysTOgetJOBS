<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Kendali_belanja extends CI_Controller
{
	var $CI = NULL;
	public function __construct()
	{
		$this->CI =& get_instance(); 
        parent::__construct();
        //$this->load->helper(array('form','url', 'text_helper','date'));        
        $this->load->database();
        $this->load->model(array('m_kendali_belanja','m_urusan', 'm_bidang', 'm_program', 'm_kegiatan'));
        if (!empty($this->session->userdata("db_aktif"))) {
            $this->load->database($this->session->userdata("db_aktif"), FALSE, TRUE);
        }
	}

	function index()
	{
		$this->output->enable_profiler(TRUE);
		$this->auth->restrict();
		$data['url_delete_data'] = site_url('kendali_belanja/delete_kendali_belanja');
		$this->template->load('template','kendali_belanja/kendali_view',$data);
	}

	function load_kendali()
	{
		$search = $this->input->post("search");
		$start = $this->input->post("start");
		$length = $this->input->post("length");
		$order = $this->input->post("order");
		
		$kb = $this->m_kendali_belanja->get_data_table($search, $start, $length, $order["0"]);		
		$alldata = $this->m_kendali_belanja->count_data_table($search, $start, $length, $order["0"]);

		$data = array();
		$no=0;


		foreach ($kb as $row) {
			$no++;
			$data[] = array(
							$no, 
							$row->kd_urusan.".".
							$row->kd_bidang.".".
                            $row->kd_program.".".
                            $row->kd_kegiatan,
                            'BELUM ISI',
                            $row->kriteria_keberhasilan,
                            $row->ukuran_keberhasilan,
                            $row->triwulan,
                            $row->pagu_triwulan,
                            $row->realisasi,
                            $row->capaian_input,
                            $row->kinerja_output,
                            $row->capaian_output,
                            $row->keterangan,
							'<a href="javascript:void(0)" onclick="edit_kendali_belanja('. $row->id_kendali_belanja .')" class="icon2-page_white_edit" title="Edit Pengendalian Belanja"/>
							<a href="javascript:void(0)" onclick="delete_kendali_belanja('. $row->id_kendali_belanja .')" class="icon2-delete" title="Hapus Pengendalian Belanja"/>'
							);
		}
		$json = array("recordsTotal"=> $alldata, "recordsFiltered"=> $alldata, 'data' => $data);
		
        echo json_encode($json);
	}

	function cru_kendali_belanja()
	{
		$kd_urusan_edit = NULL;
		$kd_bidang_edit = NULL;
		$kd_program_edit = NULL;
		$kd_kegiatan_edit = NULL;

		$kd_urusan = array("" => "");
		foreach ($this->m_urusan->get_urusan() as $row) {
			$kd_urusan[$row->id] = $row->id .". ". $row->nama;
		}

		$kd_bidang = array("" => "");
		foreach ($this->m_bidang->get_bidang() as $row) {
			$kd_bidang[$row->id] = $row->id .". ". $row->nama;
		}

		$kd_program = array("" => "");
		foreach ($this->m_program->get_prog() as $row) {
			$kd_program[$row->id] = $row->id .". ". $row->nama;
		}

		$kd_kegiatan = array("" => "");
		foreach ($this->m_kegiatan->get_keg() as $row) {
			$kd_kegiatan[$row->id] = $row->id .". ". $row->nama;
		}

		$data['kd_urusan'] = form_dropdown('kd_urusan', $kd_urusan, $kd_urusan_edit, 'data-placeholder="Pilih Urusan" class="common chosen-select" id="kd_urusan"');
		$data['kd_bidang'] = form_dropdown('kd_bidang', $kd_bidang, $kd_bidang_edit, 'data-placeholder="Pilih Bidang Urusan" class="common chosen-select" id="kd_bidang"');
		$data['kd_program'] = form_dropdown('kd_program', $kd_program, $kd_program_edit, 'data-placeholder="Pilih Program" class="common chosen-select" id="kd_program"');
		$data['kd_kegiatan'] = form_dropdown('kd_kegiatan', $kd_kegiatan, $kd_kegiatan_edit, 'data-placeholder="Pilih Kegiatan" class="common chosen-select" id="kd_kegiatan"');

		$this->template->load('template','kendali_belanja/cru_kendali_belanja', $data);
	}

	function save_kendali_belanja()
	{
		$id_kendali_belanja	= $this->input->post('id_kendali_belanja');
		$call_from			= $this->input->post('call_from');
		$kd_urusan			= $this->input->post('kd_urusan');
		$kd_bidang	 		= $this->input->post('kd_bidang');
		$kd_program	 		= $this->input->post('kd_program');
		$kd_kegiatan		= $this->input->post('kd_kegiatan');
		$kriteria_keberhasilan	= $this->input->post('kriteria_keberhasilan');
		$ukuran_keberhasilan	= $this->input->post('ukuran_keberhasilan');
		$triwulan			= $this->input->post('triwulan');
		$pagu_triwulan	 		= $this->input->post('pagu_triwulan');
		$realisasi	= $this->input->post('realisasi');
		$capaian_input	= $this->input->post('capaian_input');
		$kinerja_output		= $this->input->post('kinerja_output');
		$capaian_output		= $this->input->post('capaian_output');
		$keterangan			= $this->input->post('keterangan');

		if(strpos($call_from, 'kendali_belanja/cru_kendali_belanja') != FALSE) {
			$call_from = '';
		}

		$data_kendali_belanja = $this->m_kendali_belanja->get_kendali_belanja_by_id($id_kendali_belanja);
		if(empty($data_kendali_belanja)) {
			//cek bank baru
			$data_kendali_belanja = new stdClass();
			$id_kendali_belanja = '';
		}

		$data_kendali_belanja->id_kendali_belanja	= $id_kendali_belanja;
		$data_kendali_belanja->kd_urusan			= $kd_urusan;
		$data_kendali_belanja->kd_bidang	 		= $kd_bidang;
		$data_kendali_belanja->kd_program	 		= $kd_program;
		$data_kendali_belanja->kd_kegiatan			= $kd_kegiatan;
		$data_kendali_belanja->kriteria_keberhasilan	= $kriteria_keberhasilan;
		$data_kendali_belanja->ukuran_keberhasilan	= $ukuran_keberhasilan;
		$data_kendali_belanja->triwulan				= $triwulan;
		$data_kendali_belanja->pagu_triwulan		= $pagu_triwulan;
		$data_kendali_belanja->realisasi			= $realisasi;
		$data_kendali_belanja->capaian_input		= $capaian_input;
		$data_kendali_belanja->kinerja_output		= $kinerja_output;
		$data_kendali_belanja->capaian_output		= $capaian_output;
		$data_kendali_belanja->keterangan			= $keterangan;

		$ret = TRUE;
		if(empty($id_kendali_belanja)) {
			//insert
			$ret = $this->m_kendali_belanja->simpan_kendali_belanja($data_kendali_belanja);
			//echo $this->db->last_query();
		} else {
			//update
			$ret = $this->m_kendali_belanja->update_kendali_belanja($data_kendali_belanja, $id_kendali_belanja, 'table_kendali_belanja', 'primary_kendali_belanja');
			//echo $this->db->last_query();
		}
		if ($ret === FALSE){
            $this->session->set_userdata('msg_typ','err');
            $this->session->set_userdata('msg', 'Data Pengendalian Belanja gagal disimpan');						  
		} else {
            $this->session->set_userdata('msg_typ','ok');
            $this->session->set_userdata('msg', 'Data Pengendalian Belanja Berhasil disimpan');
		}

		if(!empty($call_from))
			redirect($call_from);

        redirect('kendali_belanja');
	}

	function edit_kendali_belanja($id_kendali_belanja)
	{
		//$this->output->enable_profiler(TRUE);
        $this->auth->restrict();
        //$data['url_save_data'] = site_url('cik/save_cik');

        $data['isEdit'] = FALSE;
        if (!empty($id_kendali_belanja)) {
            $data_ = array('id_kendali_belanja'=>$id_kendali_belanja);
            $result = $this->m_kendali_belanja->get_data_with_rincian($id_kendali_belanja,'table_kendali_belanja');
			if (empty($result)) {
				$this->session->set_userdata('msg_typ','err');
				$this->session->set_userdata('msg', 'Data musrenbang tidak ditemukan.');
				redirect('kendali_belanja');
			}
			
            $data['id_kendali_belanja']	= $result->id_kendali_belanja;
    		$data['kriteria_keberhasilan'] 	= $result->kriteria_keberhasilan;
    		$data['anggaran_realisasi'] = $result->anggaran_realisasi;
    		$data['ukuran_keberhasilan'] 		= $result->ukuran_keberhasilan;
    		$data['triwulan'] 			= $result->triwulan;
    		$data['pagu_triwulan'] 	= $result->pagu_triwulan;
    		$data['realisasi'] = $result->realisasi;
    		$data['capaian_input'] 	= $result->capaian_input;
    		$data['kinerja_output'] 	= $result->kinerja_output;
    		$data['capaian_output'] 	= $result->capaian_output;
    		$data['keterangan'] 		= $result->keterangan;

            $data['isEdit']				= TRUE;         
            
            $kd_urusan_edit = $result->kd_urusan;
    		$kd_bidang_edit = $result->kd_bidang;
    		$kd_program_edit = $result->kd_program;
    		$kd_kegiatan_edit = $result->kd_kegiatan;
    
            //prepare combobox
    		$kd_urusan = array("" => "");
    		foreach ($this->m_urusan->get_urusan() as $row) {
    			$kd_urusan[$row->id] = $row->id .". ". $row->nama;
    		}
    
    		$kd_bidang = array("" => "");
    		foreach ($this->m_bidang->get_bidang() as $row) {
    			$kd_bidang[$row->id] = $row->id .". ". $row->nama;
    		}
    
    		$kd_program = array("" => "");
    		foreach ($this->m_program->get_prog() as $row) {
    			$kd_program[$row->id] = $row->id .". ". $row->nama;
    		}
    
    		$kd_kegiatan = array("" => "");
    		foreach ($this->m_kegiatan->get_keg() as $row) {
    			$kd_kegiatan[$row->id] = $row->id .". ". $row->nama;
    		}
    
    		$data['kd_urusan'] = form_dropdown('kd_urusan', $kd_urusan, $kd_urusan_edit, 'data-placeholder="Pilih Urusan" class="common chosen-select" id="kd_urusan"');
    		$data['kd_bidang'] = form_dropdown('kd_bidang', $kd_bidang, $kd_bidang_edit, 'data-placeholder="Pilih Bidang Urusan" class="common chosen-select" id="kd_bidang"');
    		$data['kd_program'] = form_dropdown('kd_program', $kd_program, $kd_program_edit, 'data-placeholder="Pilih Program" class="common chosen-select" id="kd_program"');
    		$data['kd_kegiatan'] = form_dropdown('kd_kegiatan', $kd_kegiatan, $kd_kegiatan_edit, 'data-placeholder="Pilih Kegiatan" class="common chosen-select" id="kd_kegiatan"');
    
		}
        $this->template->load('template','kendali_belanja/cru_kendali_belanja',$data);
        
	}

	function delete_kendali_belanja() 
	{  
        $id = $this->input->post('id');
        
        $result = $this->m_kendali_belanja->delete_kendali_belanja($id);
        if ($result) {
			$msg = array('success' => '1', 'msg' => 'Pengendalian Belanja berhasil dihapus.');
			echo json_encode($msg);			
		}else{
			$msg = array('success' => '0', 'msg' => 'ERROR! Pengendalian Belanja gagal dihapus, mohon menghubungi administrator.');
			echo json_encode($msg);			
		}
	}
}