<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	class Data extends CI_Controller
	{
		var $CI = NULL;
		public function __construct()
		{
			$this->CI =& get_instance(); 
	        parent::__construct();    
	        $this->load->database();
	        $this->load->model(array('m_data','m_urusan', 'm_bidang', 'm_program', 'm_kegiatan','m_skpd','m_settings'));
	        if (!empty($this->session->userdata("db_aktif"))) {
	            $this->load->database($this->session->userdata("db_aktif"), FALSE, TRUE);
	        }
	    }
//------------------------------------------------------FUNGSI UNTUK MASTER BIDANG-------------------------------------------//
	    function view_bidang()
	    {
			$this->auth->restrict();
	    	$this->template->load('template','data/bidang_view');
	    }

	    function cru_bidang()
		{
			$kd_urusan_edit = NULL;

			$kd_urusan = array("" => "");
			foreach ($this->m_urusan->get_urusan() as $row) {
				$kd_urusan[$row->id] = $row->id .". ". $row->nama;
			}

			$data['kd_urusan'] = form_dropdown('kd_urusan', $kd_urusan, $kd_urusan_edit, 'data-placeholder="Pilih Urusan" class="common chosen-select" id="kd_urusan"');

			$this->template->load('template','data/cru_bidang', $data);
		}

		function save_bidang()
		{
			$id 		 	= $this->input->post('id');
			$call_from		= $this->input->post('call_from');
			$kd_urusan		= $this->input->post('kd_urusan');
			$kd_bidang	 	= $this->input->post('kd_bidang');
			$nm_bidang	 	= $this->input->post('nm_bidang');
			$kd_fungsi 		= $this->input->post('kd_fungsi');

			if(strpos($call_from, 'data/cru_bidang') != FALSE) {
				$call_from = '';
			}

			$data_bidang = $this->m_data->get_bidang_by_id($id);
			if(empty($data_bidang)) {
				//cek bank baru
				$data_bidang = new stdClass();
				$id = '';
			}

			$data_bidang->id				= $id;
			$data_bidang->Kd_Urusan			= $kd_urusan;
			$data_bidang->Kd_Bidang	 		= $kd_bidang;
			$data_bidang->Nm_Bidang	 		= $nm_bidang;
			$data_bidang->Kd_Fungsi 		= $kd_fungsi;

			$ret = TRUE;
			if(empty($id)) {
				//insert
				$ret = $this->m_data->simpan_bidang($data_bidang);
				//echo $this->db->last_query();
			} else {
				//update
				$ret = $this->m_data->update_bidang($data_bidang, $id, 'table_bidang', 'primary_bidang');
				//echo $this->db->last_query();
			}
			if ($ret === FALSE){
	            $this->session->set_userdata('msg_typ','err');
	            $this->session->set_userdata('msg', 'Data Bidang gagal disimpan');						  
			} else {
	            $this->session->set_userdata('msg_typ','ok');
	            $this->session->set_userdata('msg', 'Data Bidang Berhasil disimpan');
			}

			if(!empty($call_from))
				redirect($call_from);

	        redirect('data/view_bidang');
		}

	    function load_bidang()
	    {
	    	$search = $this->input->post("search");
			$start = $this->input->post("start");
			$length = $this->input->post("length");
			$order = $this->input->post("order");
			
			$bidang = $this->m_data->get_data_table_bidang($search, $start, $length, $order["0"]);		
			$alldata = $this->m_data->count_data_table_bidang($search, $start, $length, $order["0"]);

			$data = array();
			$no=0;


			foreach ($bidang as $row) {
				$no++;
				$data[] = array(
								$no, 
								$row->Kd_Urusan,
								$row->Nm_Urusan,
								$row->Kd_Bidang,
								$row->Nm_Bidang,
								$row->Kd_Fungsi,
								$row->Nm_Fungsi,
								'<a href="javascript:void(0)" onclick="edit_bidang('. $row->id .')" class="icon2-page_white_edit" title="Edit Bidang"/>
								<a href="javascript:void(0)" onclick="delete_bidang('. $row->id .')" class="icon2-delete" title="Hapus Bidang"/>'
								);
			}
			$json = array("recordsTotal"=> $alldata, "recordsFiltered"=> $alldata, 'data' => $data);
			
	        echo json_encode($json);
	    }

	    function edit_bidang($id)
		{
			//$this->output->enable_profiler(TRUE);
	        $this->auth->restrict();
	        //$data['url_save_data'] = site_url('cik/save_cik');

	        $data['isEdit'] = FALSE;
	        if (!empty($id)) {
	            $data_ = array('id'=>$id);
	            $result = $this->m_data->get_data_with_rincian($id,'table_bidang');
				if (empty($result)) {
					$this->session->set_userdata('msg_typ','err');
					$this->session->set_userdata('msg', 'Data bidang tidak ditemukan.');
					redirect('data/view_bidang');
				}
				
	            $data['id']					= $result->id;
	    		$data['kd_bidang'] 			= $result->Kd_Bidang;
	    		$data['nm_bidang'] 			= $result->Nm_Bidang;
	    		$data['kd_fungsi']			= $result->Kd_Fungsi;

	            $data['isEdit']				= TRUE;         
	            
	            $kd_urusan_edit = $result->Kd_Urusan;
	    
	            //prepare combobox
	    		$kd_urusan = array("" => "");
	    		foreach ($this->m_urusan->get_urusan() as $row) {
	    			$kd_urusan[$row->id] = $row->id .". ". $row->nama;
	    		}
	    
	    
	    		$data['kd_urusan'] = form_dropdown('kd_urusan', $kd_urusan, $kd_urusan_edit, 'data-placeholder="Pilih Urusan" class="common chosen-select" id="kd_urusan"');
	    
			}
	        $this->template->load('template','data/cru_bidang',$data);
	        
		}

		function delete_bidang() 
		{  
	        $id = $this->input->post('id');
	        
	        $result = $this->m_data->delete_bidang($id);
	        if ($result) {
				$msg = array('success' => '1', 'msg' => 'Data bidang berhasil dihapus.');
				echo json_encode($msg);			
			}else{
				$msg = array('success' => '0', 'msg' => 'ERROR! Data bidang gagal dihapus, mohon menghubungi administrator.');
				echo json_encode($msg);			
			}
		}
//------------------------------------------------AKHIR FUNGSI UNTUK MASTER BIDANG-------------------------------------------//

//---------------------------------------------------FUNGSI UNTUK MASTER PROGRAM---------------------------------------------//
		function view_program()
	    {
			$this->auth->restrict();
	    	$this->template->load('template','data/program_view');
	    }

	    function cru_program()
		{
			$kd_urusan_edit = NULL;
			$kd_bidang_edit = NULL;

			$kd_urusan = array("" => "");
			foreach ($this->m_urusan->get_urusan() as $row) {
				$kd_urusan[$row->id] = $row->id .". ". $row->nama;
			}
			$kd_bidang = array("" => "");
			foreach ($this->m_bidang->get_bidang() as $row) {
				$kd_bidang[$row->id] = $row->id .". ". $row->nama;
			}

			$data['kd_urusan'] = form_dropdown('kd_urusan', $kd_urusan, $kd_urusan_edit, 'data-placeholder="Pilih Urusan" class="common chosen-select" id="kd_urusan"');
			$data['kd_bidang'] = form_dropdown('kd_bidang', $kd_bidang, $kd_bidang_edit, 'data-placeholder="Pilih Bidang Urusan" class="common chosen-select" id="kd_bidang"');
		

			$this->template->load('template','data/cru_program', $data);
		}

		function save_program()
		{
			$id 		 	= $this->input->post('id');
			$call_from		= $this->input->post('call_from');
			$kd_urusan		= $this->input->post('kd_urusan');
			$kd_bidang	 	= $this->input->post('kd_bidang');
			$kd_prog	 	= $this->input->post('kd_prog');
			$ket_program 	= $this->input->post('ket_program');

			if(strpos($call_from, 'data/cru_program') != FALSE) {
				$call_from = '';
			}

			$data_program = $this->m_data->get_program_by_id($id);
			if(empty($data_program)) {
				//cek bank baru
				$data_program = new stdClass();
				$id = '';
			}

			$data_program->id				= $id;
			$data_program->Kd_Urusan		= $kd_urusan;
			$data_program->Kd_Bidang		= $kd_bidang;
			$data_program->Kd_Prog  		= $kd_prog;
			$data_program->Ket_Program 		= $ket_program;

			$ret = TRUE;
			if(empty($id)) {
				//insert
				$ret = $this->m_data->simpan_program($data_program);
				//echo $this->db->last_query();
			} else {
				//update
				$ret = $this->m_data->update_program($data_program, $id, 'table_program', 'primary_program');
				//echo $this->db->last_query();
			}
			if ($ret === FALSE){
	            $this->session->set_userdata('msg_typ','err');
	            $this->session->set_userdata('msg', 'Data Program gagal disimpan');						  
			} else {
	            $this->session->set_userdata('msg_typ','ok');
	            $this->session->set_userdata('msg', 'Data Program Berhasil disimpan');
			}

			if(!empty($call_from))
				redirect($call_from);

	        redirect('data/view_program');
		}

	    function load_program()
	    {
	    	$search = $this->input->post("search");
			$start = $this->input->post("start");
			$length = $this->input->post("length");
			$order = $this->input->post("order");
			
			$program = $this->m_data->get_data_table_program($search, $start, $length, $order["0"]);		
			$alldata = $this->m_data->count_data_table_program($search, $start, $length, $order["0"]);

			$data = array();
			$no=0;


			foreach ($program as $row) {
				$no++;
				$data[] = array(
								$no,
								$row->Kd_Urusan, 
								$row->Nm_Urusan,
								$row->Kd_Bidang,
								$row->Nm_Bidang,
								$row->Kd_Prog,
								$row->Ket_Program,
								'<a href="javascript:void(0)" onclick="edit_program('. $row->id .')" class="icon2-page_white_edit" title="Edit Bidang"/>
								<a href="javascript:void(0)" onclick="delete_program('. $row->id .')" class="icon2-delete" title="Hapus Bidang"/>'
								);
			}
			$json = array("recordsTotal"=> $alldata, "recordsFiltered"=> $alldata, 'data' => $data);
			
	        echo json_encode($json);
	    }

	    function edit_program($id)
		{
			$this->output->enable_profiler(TRUE);
	        $this->auth->restrict();
	        //$data['url_save_data'] = site_url('cik/save_cik');

	        $data['isEdit'] = FALSE;
	        if (!empty($id)) {
	            $data_ = array('id'=>$id);
	            $result = $this->m_data->get_data_with_rincian($id,'table_program');
				if (empty($result)) {
					$this->session->set_userdata('msg_typ','err');
					$this->session->set_userdata('msg', 'Data program tidak ditemukan.');
					redirect('data/view_program');
				}
				
	            $data['id']					= $result->id;
	    		$data['kd_prog'] 			= $result->Kd_Prog;
	    		$data['ket_program'] 		= $result->Ket_Program;

	            $data['isEdit']				= TRUE;         
	            
	            $kd_urusan_edit = $result->Kd_Urusan;
	            $kd_bidang_edit = $result->Kd_Bidang;
	    
	            //prepare combobox
	    		$kd_urusan = array("" => "");
	    		foreach ($this->m_urusan->get_urusan() as $row) {
	    			$kd_urusan[$row->id] = $row->id .". ". $row->nama;
	    		}
	    		$kd_bidang = array("" => "");
	    		foreach ($this->m_bidang->get_bidang($result->Kd_Urusan) as $row) {
	    			$kd_bidang[$row->id] = $row->id .". ". $row->nama;
	    		}
	    
	    
	    		$data['kd_urusan'] = form_dropdown('kd_urusan', $kd_urusan, $kd_urusan_edit, 'data-placeholder="Pilih Urusan" class="common chosen-select" id="kd_urusan"');
	    		$data['kd_bidang'] = form_dropdown('kd_bidang', $kd_bidang, $kd_bidang_edit, 'data-placeholder="Pilih Bidang Urusan" class="common chosen-select" id="kd_bidang"');
	    
			}
	        $this->template->load('template','data/cru_program',$data);
	        
		}

		function delete_program() 
		{  
	        $id = $this->input->post('id');
	        
	        $result = $this->m_data->delete_program($id);
	        if ($result) {
				$msg = array('success' => '1', 'msg' => 'Data program berhasil dihapus.');
				echo json_encode($msg);			
			}else{
				$msg = array('success' => '0', 'msg' => 'ERROR! Data program gagal dihapus, mohon menghubungi administrator.');
				echo json_encode($msg);			
			}
		}
//------------------------------------------------AKHIR FUNGSI UNTUK MASTER PROGRAM-------------------------------------------//

//------------------------------------------------- FUNGSI UNTUK MASTER KEGIATAN----------------------------------------------//
		function view_kegiatan()
	    {
			//$this->output->enable_profiler(TRUE);
			$this->auth->restrict();
	    	$this->template->load('template','data/kegiatan_view');
	    }

	    function cru_kegiatan()
		{
			$kd_urusan_edit = NULL;
			$kd_bidang_edit = NULL;
			$kd_program_edit = NULL;

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

			$data['kd_urusan'] = form_dropdown('kd_urusan', $kd_urusan, $kd_urusan_edit, 'data-placeholder="Pilih Urusan" class="common chosen-select" id="kd_urusan"');
			$data['kd_bidang'] = form_dropdown('kd_bidang', $kd_bidang, $kd_bidang_edit, 'data-placeholder="Pilih Bidang Urusan" class="common chosen-select" id="kd_bidang"');
			$data['kd_program'] = form_dropdown('kd_program', $kd_program, $kd_program_edit, 'data-placeholder="Pilih Program" class="common chosen-select" id="kd_program"');
			

			$this->template->load('template','data/cru_kegiatan', $data);
		}

		function save_kegiatan()
		{
			$id 		 	= $this->input->post('id');
			$call_from		= $this->input->post('call_from');
			$kd_urusan		= $this->input->post('kd_urusan');
			$kd_bidang	 	= $this->input->post('kd_bidang');
			$kd_program	 	= $this->input->post('kd_program');
			$kd_keg		 	= $this->input->post('kd_keg');
			$ket_kegiatan 	= $this->input->post('ket_kegiatan');

			if(strpos($call_from, 'data/cru_kegiatan') != FALSE) {
				$call_from = '';
			}

			$data_kegiatan = $this->m_data->get_kegiatan_by_id($id);
			if(empty($data_kegiatan)) {
				//cek bank baru
				$data_kegiatan = new stdClass();
				$id = '';
			}

			$data_kegiatan->id				= $id;
			$data_kegiatan->Kd_Urusan		= $kd_urusan;
			$data_kegiatan->Kd_Bidang		= $kd_bidang;
			$data_kegiatan->Kd_Prog  		= $kd_program;
			$data_kegiatan->Kd_Keg  		= $kd_keg;
			$data_kegiatan->Ket_Kegiatan 	= $ket_kegiatan;

			$ret = TRUE;
			if(empty($id)) {
				//insert
				$ret = $this->m_data->simpan_kegiatan($data_kegiatan);
				//echo $this->db->last_query();
			} else {
				//update
				$ret = $this->m_data->update_kegiatan($data_kegiatan, $id, 'table_kegiatan', 'primary_kegiatan');
				//echo $this->db->last_query();
			}
			if ($ret === FALSE){
	            $this->session->set_userdata('msg_typ','err');
	            $this->session->set_userdata('msg', 'Data Kegiatan gagal disimpan');						  
			} else {
	            $this->session->set_userdata('msg_typ','ok');
	            $this->session->set_userdata('msg', 'Data Kegiatan Berhasil disimpan');
			}

			if(!empty($call_from))
				redirect($call_from);

	        redirect('data/view_kegiatan');
		}

	    function load_kegiatan()
	    {
	    	
	    	$search = $this->input->post("search");
			$start = $this->input->post("start");
			$length = $this->input->post("length");
			$order = $this->input->post("order");
			
			$kegiatan = $this->m_data->get_data_table_kegiatan($search, $start, $length, $order["0"]);		
			$alldata = $this->m_data->count_data_table_kegiatan($search, $start, $length, $order["0"]);

			$data = array();
			$no=0;


			foreach ($kegiatan as $row) {
				
				$no++;
				$data[] = array(
								$no, 
								$row->Kd_Urusan,
								$row->Nm_Urusan,
								$row->Kd_Bidang,
								$row->Nm_Bidang,
								$row->Kd_Prog,
								$row->Ket_Program,
								$row->Kd_Keg,
								$row->Ket_Kegiatan,
								'<a href="javascript:void(0)" onclick="edit_kegiatan('. $row->id .')" class="icon2-page_white_edit" title="Edit Bidang"/>
								<a href="javascript:void(0)" onclick="delete_kegiatan('. $row->id .')" class="icon2-delete" title="Hapus Bidang"/>'
								);
			}
			$json = array("recordsTotal"=> $alldata, "recordsFiltered"=> $alldata, 'data' => $data);
			
	        echo json_encode($json);
	    }

	    function edit_kegiatan($id)
		{
			$this->output->enable_profiler(TRUE);
	        $this->auth->restrict();
	        //$data['url_save_data'] = site_url('cik/save_cik');

	        $data['isEdit'] = FALSE;
	        if (!empty($id)) {
	            $data_ = array('id'=>$id);
	            $result = $this->m_data->get_data_with_rincian($id,'table_kegiatan');
				if (empty($result)) {
					$this->session->set_userdata('msg_typ','err');
					$this->session->set_userdata('msg', 'Data Kegiatan tidak ditemukan.');
					redirect('data/view_kegiatan');
				}
				
	            $data['id']					= $result->id;
	    		$data['kd_keg'] 			= $result->Kd_Keg;
	    		$data['ket_kegiatan'] 		= $result->Ket_Kegiatan;

	            $data['isEdit']				= TRUE;         
	            
	            $kd_urusan_edit = $result->Kd_Urusan;
	            $kd_bidang_edit = $result->Kd_Bidang;
	    		$kd_program_edit = $result->Kd_Prog;

	            //prepare combobox
	    		$kd_urusan = array("" => "");
	    		foreach ($this->m_urusan->get_urusan() as $row) {
	    			$kd_urusan[$row->id] = $row->id .". ". $row->nama;
	    		}
	    		$kd_bidang = array("" => "");
	    		foreach ($this->m_bidang->get_bidang($result->Kd_Urusan) as $row) {
	    			$kd_bidang[$row->id] = $row->id .". ". $row->nama;
	    		}
	    		$kd_program = array("" => "");
	    		foreach ($this->m_program->get_prog($result->Kd_Urusan,$result->Kd_Bidang) as $row) {
	    			$kd_program[$row->id] = $row->id .". ". $row->nama;
	    		}
	    
	    		$data['kd_urusan'] = form_dropdown('kd_urusan', $kd_urusan, $kd_urusan_edit, 'data-placeholder="Pilih Urusan" class="common chosen-select" id="kd_urusan"');
	    		$data['kd_bidang'] = form_dropdown('kd_bidang', $kd_bidang, $kd_bidang_edit, 'data-placeholder="Pilih Bidang Urusan" class="common chosen-select" id="kd_bidang"');
	    		$data['kd_program'] = form_dropdown('kd_program', $kd_program, $kd_program_edit, 'data-placeholder="Pilih Program" class="common chosen-select" id="kd_program"');
    		
	    
			}
	        $this->template->load('template','data/cru_kegiatan',$data);
	        
		}

		function delete_kegiatan() 
		{  
	        $id = $this->input->post('id');
	        
	        $result = $this->m_data->delete_kegiatan($id);
	        if ($result) {
				$msg = array('success' => '1', 'msg' => 'Data Kegiatan berhasil dihapus.');
				echo json_encode($msg);			
			}else{
				$msg = array('success' => '0', 'msg' => 'ERROR! Data Kegiatan gagal dihapus, mohon menghubungi administrator.');
				echo json_encode($msg);			
			}
		}
//------------------------------------------------AKHIR FUNGSI UNTUK MASTER KEGIATAN------------------------------------------//

		function form_hapus($cont){
			$jenis = "";
			$query = "";

			if ($cont == 'renstra') {
				$jenis = 'Renstra';
				$query = 'INNER JOIN t_renstra_prog_keg ON m_skpd.id_skpd = t_renstra_prog_keg.id_skpd';
			}elseif ($cont == 'renja') {
				$jenis = 'Renja';
				$query = 'INNER JOIN t_renja_prog_keg ON m_skpd.id_skpd = t_renja_prog_keg.id_skpd WHERE tahun = '.$this->m_settings->get_tahun_anggaran();
				$query2 = 'INNER JOIN t_renja ON m_skpd.id_skpd = t_renja.id_skpd WHERE tahun = '.$this->m_settings->get_tahun_anggaran();
			}elseif ($cont == 'ppas') {
				$jenis = 'PPAS';
				$query = 'INNER JOIN t_ppas_prog_keg ON m_skpd.id_skpd = t_ppas_prog_keg.id_skpd WHERE tahun = '.$this->m_settings->get_tahun_anggaran();
				$query2 = 'INNER JOIN t_ppas ON m_skpd.id_skpd = t_ppas.id_skpd WHERE tahun = '.$this->m_settings->get_tahun_anggaran();
			}elseif ($cont == 'rka') {
				$jenis = 'RKA';
				$query = 'INNER JOIN tx_rka_prog_keg ON m_skpd.id_skpd = tx_rka_prog_keg.id_skpd WHERE tahun = '.$this->m_settings->get_tahun_anggaran();
				$query2 = 'INNER JOIN tx_rka ON m_skpd.id_skpd = tx_rka.id_skpd WHERE tahun = '.$this->m_settings->get_tahun_anggaran();
			}elseif ($cont == 'dpa') {
				$jenis = 'DPA';
				$query = 'INNER JOIN tx_dpa_prog_keg ON m_skpd.id_skpd = tx_dpa_prog_keg.id_skpd WHERE tahun = '.$this->m_settings->get_tahun_anggaran();
				$query2 = 'INNER JOIN tx_dpa ON m_skpd.id_skpd = tx_dpa.id_skpd WHERE tahun = '.$this->m_settings->get_tahun_anggaran();
			}elseif ($cont == 'cik') {
				$jenis = 'CIK';
				$query = 'INNER JOIN tx_cik_prog_keg ON m_skpd.id_skpd = tx_cik_prog_keg.id_skpd WHERE tahun = '.$this->m_settings->get_tahun_anggaran();
				$query2 = 'INNER JOIN tx_cik ON m_skpd.id_skpd = tx_cik.id_skpd WHERE tahun = '.$this->m_settings->get_tahun_anggaran();
			}elseif ($cont == 'renja_perubahan') {
				$jenis = 'Remja Perubahan';
				$query = 'INNER JOIN t_renja_prog_keg_perubahan ON m_skpd.id_skpd = t_renja_prog_keg_perubahan.id_skpd WHERE tahun = '.$this->m_settings->get_tahun_anggaran();
				$query2 = 'INNER JOIN t_renja_perubahan ON m_skpd.id_skpd = t_renja_perubahan.id_skpd WHERE tahun = '.$this->m_settings->get_tahun_anggaran();
			}elseif ($cont == 'ppas_perubahan') {
				$jenis = 'PPAS Perubahan';
				$query = 'INNER JOIN t_ppas_prog_keg_perubahan ON m_skpd.id_skpd = t_ppas_prog_keg_perubahan.id_skpd WHERE tahun = '.$this->m_settings->get_tahun_anggaran();
				$query2 = 'INNER JOIN t_ppas_perubahan ON m_skpd.id_skpd = t_ppas_perubahan.id_skpd WHERE tahun = '.$this->m_settings->get_tahun_anggaran();
			}elseif ($cont == 'rka_perubahan') {
				$jenis = 'RKA Perubahan';
				$query = 'INNER JOIN tx_rka_prog_keg_perubahan ON m_skpd.id_skpd = tx_rka_prog_keg_perubahan.id_skpd WHERE tahun = '.$this->m_settings->get_tahun_anggaran();
				$query2 = 'INNER JOIN tx_rka_perubahan ON m_skpd.id_skpd = tx_rka_perubahan.id_skpd WHERE tahun = '.$this->m_settings->get_tahun_anggaran();
			}elseif ($cont == 'dpa_perubahan') {
				$jenis = 'DPA Perubahan';
				$query = 'INNER JOIN tx_dpa_prog_keg_perubahan ON m_skpd.id_skpd = tx_dpa_prog_keg_perubahan.id_skpd WHERE tahun = '.$this->m_settings->get_tahun_anggaran();
				$query2 = 'INNER JOIN tx_dpa_perubahan ON m_skpd.id_skpd = tx_dpa_perubahan.id_skpd WHERE tahun = '.$this->m_settings->get_tahun_anggaran();
			}elseif ($cont == 'cik_perubahan') {
				$jenis = 'CIK Perubahan';
				$query = 'INNER JOIN tx_cik_prog_keg_perubahan ON m_skpd.id_skpd = tx_cik_prog_keg_perubahan.id_skpd WHERE tahun = '.$this->m_settings->get_tahun_anggaran();
				$query2 = 'INNER JOIN tx_cik_perubahan ON m_skpd.id_skpd = tx_cik_perubahan.id_skpd WHERE tahun = '.$this->m_settings->get_tahun_anggaran();
			}

			if (!empty($query)) {
				$result = $this->db->query('SELECT m_skpd.id_skpd, nama_skpd FROM m_skpd '.$query.' 
				UNION 
				SELECT m_skpd.id_skpd, nama_skpd FROM m_skpd '.$query2.' GROUP BY m_skpd.id_skpd')->result();
			}else{
				$result = array();
			}

			$data['jenis'] = $jenis;
			$data['cont'] = $cont;
			$data['result'] = $result;
			// print_r($this->db->last_query());
			$this->template->load('template', 'data/form_hapus', $data);
		}

		function do_hapus(){
			$id_skpd = $this->input->post('id_skpd');
			$jenis = $this->input->post('jenis');
			$cont = $this->input->post('cont');
			$tahun = $this->m_settings->get_tahun_anggaran();
			$kode_unit = $this->m_skpd->get_kode_unit($id_skpd);
			if ($kode_unit == $id_skpd) {
				$induk = TRUE;
			}else{
				$induk = FALSE;
			}

			if ($cont == 'renstra') {
				$result = $this->m_data->ubah_status_renstra($id_skpd, $tahun);
			}elseif ($cont == 'renja') {
				$result = $this->m_data->hapus_data_renja($id_skpd, $tahun, $induk);
			}elseif ($cont == 'ppas') {
				$result = $this->m_data->hapus_data_ppas($id_skpd, $tahun, $induk);
			}elseif ($cont == 'rka') {
				$result = $this->m_data->hapus_data_rka($id_skpd, $tahun, $induk);
			}elseif ($cont == 'dpa') {
				$result = $this->m_data->hapus_data_dpa($id_skpd, $tahun, $induk);
			}elseif ($cont == 'cik') {
				$result = $this->m_data->hapus_data_cik($id_skpd, $tahun, $induk);
			}elseif ($cont == 'renja_perubahan') {
				$result = $this->m_data->hapus_data_renja_perubahan($id_skpd, $tahun, $induk);
			}elseif ($cont == 'ppas_perubahan') {
				$result = $this->m_data->hapus_data_ppas_perubahan($id_skpd, $tahun, $induk);
			}elseif ($cont == 'rka_perubahan') {
				$result = $this->m_data->hapus_data_rka_perubahan($id_skpd, $tahun, $induk);
			}elseif ($cont == 'dpa_perubahan') {
				$result = $this->m_data->hapus_data_dpa_perubahan($id_skpd, $tahun, $induk);
			}elseif ($cont == 'cik_perubahan') {
				$result = $this->m_data->hapus_data_cik_perubahan($id_skpd, $tahun, $induk);
			}

			if ($result) {
				if ($cont == 'resntra') {
					$msg = array('success' => '1', 'msg' => 'Status Rensta berhasil diubah.');
				}else{
					$msg = array('success' => '1', 'msg' => 'Data '.$jenis.' berhasil dihapus.');
				}
				
				echo json_encode($msg);
			}else{
				if ($cont == 'resntra') {
					$msg = array('success' => '0', 'msg' => 'ERROR! Status Renstra gagal diubah, mohon menghubungi administrator.');
				}else{
					$msg = array('success' => '0', 'msg' => 'ERROR! Data '.$jenis.' gagal dihapus, mohon menghubungi administrator.');
				}
				echo json_encode($msg);
			}

		}

	}
?>