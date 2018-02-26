<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Renstra extends CI_Controller
{
	var $CI = NULL;
	public function __construct(){
		$this->CI =& get_instance();
        parent::__construct();
        $this->load->database();
        $this->load->model(array('m_renstra_trx', 'm_skpd', 'm_template_cetak', 'm_lov', 'm_urusan', 'm_bidang', 'm_program', 'm_kegiatan',
				'm_jenis_belanja', 'm_kategori_belanja', 'm_subkategori_belanja', 'm_kode_belanja', 'm_rpjmd_trx'));
        if (!empty($this->session->userdata("db_aktif"))) {
        	$this->load->database($this->session->userdata("db_aktif"), FALSE, TRUE);
        }
	}

	function index(){
		$this->renstra_skpd();
	}

	## --------------------------------------------- ##
	## Tambah, Edit, Delete View Renstra setiap SKPD ##
	## --------------------------------------------- ##
	private function create_renstra_skpd($id_skpd, $edit=FALSE){
		$data['skpd'] = $this->m_skpd->get_one_skpd(array('id_skpd' => $id_skpd));

		if ($edit) {
			$renstra = $this->m_renstra_trx->get_one_renstra_skpd($id_skpd);
			if (empty($renstra)) {
				$this->session->set_userdata('msg_typ','err');
				$this->session->set_userdata('msg', 'Data renstra tidak ditemukan.');
				redirect('home');
			}

			$satuan = array("" => "~~ Pilih Satuan ~~");
			foreach ($this->m_lov->get_list_lov(1) as $row) {
				$satuan[$row->kode_value]=$row->nama_value;
			}

			$status_indikator = array("" => "~~ Pilih Positif / Negatif ~~");
			foreach ($this->m_lov->get_status_indikator() as $row) {
				$status_indikator[$row->kode_status_indikator]=$row->nama_status_indikator;
			}

			$kategori_indikator = array("" => "~~ Pilih Kategori Indikator ~~");
			foreach ($this->m_lov->get_kategori_indikator() as $row) {
				$kategori_indikator[$row->kode_kategori_indikator]=$row->nama_kategori_indikator;
			}

			$data['satuan'] = $satuan;
			$data['status_indikator'] = $status_indikator;
			$data['kategori_indikator'] = $kategori_indikator;

			$data['renstra'] = $renstra;
			$data['renstra_misi'] = $this->m_renstra_trx->get_all_renstra_misi($renstra->id, TRUE);
		}

		$satuan = array("" => "~~ Pilih Satuan ~~");
		foreach ($this->m_lov->get_list_lov(1) as $row) {
			$satuan[$row->kode_value]=$row->nama_value;
		}

		$status_indikator = array("" => "~~ Pilih Positif / Negatif ~~");
		foreach ($this->m_lov->get_status_indikator() as $row) {
			$status_indikator[$row->kode_status_indikator]=$row->nama_status_indikator;
		}

		$kategori_indikator = array("" => "~~ Pilih Kategori Indikator ~~");
		foreach ($this->m_lov->get_kategori_indikator() as $row) {
			$kategori_indikator[$row->kode_kategori_indikator]=$row->nama_kategori_indikator;
		}

		$data['satuan'] = $satuan;
		$data['status_indikator'] = $status_indikator;
		$data['kategori_indikator'] = $kategori_indikator;

		$this->template->load('template','renstra/create', $data);
	}

	function get_jendela_kontrol(){
		$id_skpd = $this->session->userdata("id_skpd");
		if($id_skpd > 100){
			$id_skpd = $this->m_skpd->get_kode_unit_dari_asisten($id_skpd);
		}
		$kode_unit = $this->m_skpd->get_kode_unit($id_skpd);
		if ($kode_unit != $id_skpd) {
			$id_skpd = $kode_unit;
		}
		$data['kode_unit'] = $kode_unit;
		$data['renstra'] = $this->m_renstra_trx->get_one_renstra_skpd($id_skpd, TRUE);
		$data['jendela_kontrol'] = $this->m_renstra_trx->count_jendela_kontrol($id_skpd);
		$data['log_revisi'] = $this->m_renstra_trx->get_log_revisi($id_skpd);
		$data['is_verifikasi'] = $this->m_renstra_trx->get_verifikasi_kepala_skpd($data['renstra']->id);
		$this->load->view('renstra/jendela_kontrol', $data);
	}

	function preview_cetak_jenisrenstra(){
			$this->load->view('renstra/jenis_renstra_cetak');
		}
		function do_cetak_renstra_byjenis($jenis){
				ini_set('memory_limit', '-1');

				//print_r($jenis); exit();

				$this->auth->restrict();

				$id_skpd=NULL;
				if (empty($id_skpd)) {
					$id_skpd = $this->session->userdata('id_skpd');
				}

				if ($id_skpd == "all") {
					$all_skpd = $this->m_renstra_trx->get_all_skpd();
					$html="";
					foreach ($all_skpd as $row) {
						$data = $this->cetak_skpd_func($row->id_skpd);
						$html .= '<div class="page-break">'.$this->load->view('renstra/cetak/cetak', $data, true).'</div>';
					}

					$data['contents'] = $html;
					$data['qr'] = $this->ciqrcode->generateQRcode("sirenbangda", 'Renstra Semua '. date("d-m-Y_H-i-s"), 1);
					$html = $this->load->view('template_cetak', $data, true);

					$filename='Renstra Semua '. date("d-m-Y_H-i-s") .'.pdf';

				    pdf_create($html, $filename, "A4", "Landscape", FALSE);
				}else{
					$skpd = $this->m_renstra_trx->get_one_renstra_skpd($id_skpd, TRUE);
					if (!empty($skpd)) {
						$data = $this->cetak_skpd_func($id_skpd,$jenis);
						$data['qr'] = $this->ciqrcode->generateQRcode("sirenbangda", 'Renstra '. $skpd->nama_skpd ." ". date("d-m-Y_H-i-s"), 1);
						if($jenis=='TujuanJangkaMenengah'){

							$JenisLaporan = 'Tujuan-Jangka-Menengah';
							$Text = date("d-m-Y_H-i-s");
							$data['qr'] = $this->ciqrcode->generateQRcode($JenisLaporan, $Text,1.9);
							$data['header']= $this->load->view('Cetak_head',$data, TRUE);
							$result1 = $this->load->view("renstra/cetak/cetak_tujuan_jangmeng", $data, TRUE);
							
							$this->create_pdf->load($result1,'RENSTRA'.'-'.'Tujuan-Jangka-Menengah'.date("d-m-Y H:i:s"), 'A4-L','');

						}elseif ($jenis== 'SasaranJangkaMenengah'){

							$JenisLaporan = 'Sasaran-Jangka-Menengah';
							$Text = date("d-m-Y_H-i-s");
							$data['qr'] = $this->ciqrcode->generateQRcode($JenisLaporan, $Text,1.9);
							$data['header']= $this->load->view('Cetak_head',$data, TRUE);
							$result1 = $this->load->view('renstra/cetak/cetak_sasaran_jangmeng', $data, true);
							
							$this->create_pdf->load($result1,'RENSTRA'.'-'.'Sasaran-Jangka-Menengah'.date("d-m-Y H:i:s"), 'A4-L','');

						}elseif ($jenis== 'KebijakanUmumRenstra'){

							$JenisLaporan = 'Kebijakan-Umum-Renstra';
							$Text = date("d-m-Y_H-i-s");
							$data['qr'] = $this->ciqrcode->generateQRcode($JenisLaporan, $Text,1.9);
							$data['header']= $this->load->view('Cetak_head',$data, TRUE);
							$result1 = $this->load->view('renstra/cetak/cetak_kebijakan_umum1', $data, true);
							// print_r($result1);
							// exit;
							$this->create_pdf->load($result1,'RENSTRA'.'-'.'Kebijakan-Umum-Renstra'.date("d-m-Y H:i:s"), 'A4-L','');

						}elseif ($jenis== 'TargepaguIndikatifProgramKegiatan'){
							//$html = $this->template->load('template_cetak', 'renstra/cetak/cetak_target_pagu_indikatif1', $data, true);
						  	$JenisLaporan = 'Target-Pagu-Indikatif-Program-Kegiatan';
							$Text = date("d-m-Y_H-i-s");
							$data['qr'] = $this->ciqrcode->generateQRcode($JenisLaporan, $Text,1.9);
							$data['header']= $this->load->view('Cetak_head',$data, TRUE);
							$result1 = $this->load->view("renstra/cetak/cetak_target_pagu_indikatif1", $data, TRUE);
							// print_r($data);
							// exit;
							$this->create_pdf->load($result1,'RENSTRA'.'-'.'Target-Pagu-Indikatif-Program-Kegiatan'.date("d-m-Y H:i:s"), 'A4-L','');
							//print_r($result1);exit();
						}

						$filename='Renstra '. $skpd->nama_skpd ." ". date("d-m-Y_H-i-s") .'.pdf';
					}else{
						$html = "<center>Data Tidak Tersedia . . .</center>";
						$filename='Renstra '. date("d-m-Y_H-i-s") .'.pdf';
					}
					// echo $html;
					
					// print_r($result1);exit();
					

				   // pdf_create($html, $filename, "A4", "Landscape", FALSE);
				}
			}



	function renstra_skpd(){
		$this->auth->restrict();

		$id_skpd = $this->session->userdata("id_skpd");

		if($id_skpd > 100){
			$id_skpd = $this->m_skpd->get_kode_unit_dari_asisten($id_skpd);
		}

// print_r($id_skpd);
// exit;
		if (empty($id_skpd)) {
			$this->session->set_userdata('msg_typ','err');
			$this->session->set_userdata('msg', 'User tidak memiliki akses untuk pembuatan Renstra, mohon menghubungi administrator.');
			redirect('home');
		}


		$kode_unit = $this->m_skpd->get_kode_unit($id_skpd);
// print_r($kode_unit);
// exit;
		$renstra = $this->m_renstra_trx->get_one_renstra_skpd($kode_unit, TRUE);
		// $renstra = $this->m_renstra_trx->get_one_renstra_skpd($id_skpd, TRUE);




		if (!empty($renstra)) {
			$data['renstra'] = $renstra;
			$data['renstra_misi'] = $this->m_renstra_trx->get_all_renstra_misi($renstra->id, TRUE);
			//echo $this->db->last_query();

			$data['renstra_tujuan'] = $this->m_renstra_trx->get_all_renstra_tujuan($renstra->id);
			//echo $this->db->last_query();

			$data['jendela_kontrol'] = $this->m_renstra_trx->count_jendela_kontrol($kode_unit);
			//echo $this->db->last_query();

			$this->template->load('template','renstra/view', $data);
		}else{
			if ($kode_unit != $id_skpd) {
				redirect('home/kosong/Renstra');
			}else {
				$this->create_renstra_skpd($id_skpd);
			}
		}
	}

	function edit_renstra_skpd(){
		$this->auth->restrict();

		$id_skpd = $this->session->userdata("id_skpd");
		$this->create_renstra_skpd($id_skpd, TRUE);
	}

	function save_skpd(){
		$id = $this->input->post('id_renstra');
		$id_skpd = $this->session->userdata("id_skpd");
		$visi = $this->input->post('visi');

		$misi = $this->input->post('misi');
		$id_misi = $this->input->post('id_misi');
		$tujuan = $this->input->post('tujuan');
		$id_tujuan = $this->input->post('id_tujuan');
		$indikator = $this->input->post("indikator");
		$id_indikator = $this->input->post("id_indikator_tujuan");

		$data = $this->input->post();
		$data_tujuan = $this->input->post();
		$data_indikator = $this->input->post();
		$data_renstra = array();


		//dee
		$add = array('visi' => $visi, 'id_skpd' => $id_skpd);
		$data_renstra = $this->global_function->add_array($data_renstra, $add);

		$clean = array('id_renstra', 'visi', 'misi', 'id_misi');
		$data_tujuan = $this->global_function->clean_array($data_tujuan, $clean);

		$clean = array('id_renstra', 'visi', 'misi', 'id_misi', 'tujuan', 'id_tujuan');
		$data_indikator = $this->global_function->clean_array($data_indikator, $clean);

		//asli
		$clean = array('id_renstra','misi', 'tujuan', 'id_tujuan', 'id_misi');
		$data = $this->global_function->clean_array($data, $clean);

		$add = array('id_skpd'=> $id_skpd);
		$data = $this->global_function->add_array($data, $add);

		// print_r($data_tujuan);
		// exit;

		if (!empty($id)) {
			//$result = $this->m_renstra_trx->edit_renstra_skpd($data, $misi, $tujuan, $id_misi, $id_tujuan, $id);
			$result = $this->m_renstra_trx->edit_renstra_skpd($data_renstra,  $misi, $tujuan, $data_indikator, $id_misi, $id_tujuan, $id_indikator, $id);
		}else{
			$result = $this->m_renstra_trx->add_renstra_skpd($data_renstra, $misi, $data_tujuan, $indikator);
		}

		if ($result) {
			$this->session->set_userdata('msg_typ','ok');
			$this->session->set_userdata('msg', 'Renstra SKPD berhasil dibuat.');
			redirect('renstra/renstra_skpd');
		}else{
			$this->session->set_userdata('msg_typ','err');
			$this->session->set_userdata('msg', 'ERROR! Renstra SKPD gagal dibuat, mohon menghubungi administrator.');
			redirect('renstra/renstra_skpd');
		}
	}

	function get_sasaran_skpd(){
		$id_skpd = $this->session->userdata("id_skpd");
		$data['jendela_kontrol'] = $this->m_renstra_trx->count_jendela_kontrol($id_skpd);

		$id_renstra = $this->input->post('id_renstra');
		$id_tujuan = $this->input->post('id_tujuan');

		$data['sasaran'] = $this->m_renstra_trx->get_all_sasaran($id_renstra, $id_tujuan, TRUE);
		$this->load->view("renstra/view_sasaran", $data);
	}

	function cru_sasaran_skpd(){
		$id_renstra = $this->input->post('id_renstra');
		$id_tujuan = $this->input->post('id_tujuan');
		$id_sasaran = $this->input->post('id_sasaran');

		$id_skpd = $this->session->userdata("id_skpd");
		$data['skpd'] = $this->m_skpd->get_one_skpd(array('id_skpd' => $id_skpd));

		$satuan_edit = NULL;
		$status_edit = NULL;
		$kategori_edit = NULL;
		if (!empty($id_sasaran)) {
			$result = $this->m_renstra_trx->get_one_sasaran($id_renstra, $id_tujuan, $id_sasaran);
			if (empty($result)) {
				echo '<div style="width: 400px;">ERROR! Data sasaran tidak ditemukan.</div>';
				return FALSE;
			}
			$data['sasaran'] = $result;
			$data['indikator_sasaran'] = $this->m_renstra_trx->get_indikator_sasaran($id_sasaran, FALSE);
		}

		$data['id_renstra'] = $id_renstra;
		$data['tujuan'] = $this->m_renstra_trx->get_one_renstra_tujuan($id_renstra, $id_tujuan);

		$satuan = array("" => "~~ Pilih Satuan ~~");
		foreach ($this->m_lov->get_list_lov(1) as $row) {
			$satuan[$row->kode_value]=$row->nama_value;
		}

		$status_indikator = array("" => "~~ Pilih Positif / Negatif ~~");
		foreach ($this->m_lov->get_status_indikator() as $row) {
			$status_indikator[$row->kode_status_indikator]=$row->nama_status_indikator;
		}

		$kategori_indikator = array("" => "~~ Pilih Kategori Indikator ~~");
		foreach ($this->m_lov->get_kategori_indikator() as $row) {
			$kategori_indikator[$row->kode_kategori_indikator]=$row->nama_kategori_indikator;
		}

		//$data['satuan'] = $satuan;
		$data['satuan'] = $satuan;
		$data['status_indikator'] = $status_indikator;
		$data['kategori_indikator'] = $kategori_indikator;

		$this->load->view("renstra/cru_sasaran", $data);
	}

	function save_sasaran(){
		$id = $this->input->post('id_sasaran');

		$data = $this->input->post();
		$id_indikator_sasaran = $this->input->post("id_indikator_sasaran");
		$indikator = $this->input->post("indikator");
		$satuan_target = $this->input->post("satuan");
		$status_target = $this->input->post("status_indikator");
		$kategori_target = $this->input->post("kategori_indikator");
		$kondisi_awal = $this->input->post("kondisi_awal");
		$target_1 = $this->input->post("target_1");
		$target_2 = $this->input->post("target_2");
		$target_3 = $this->input->post("target_3");
		$target_4 = $this->input->post("target_4");
		$target_5 = $this->input->post("target_5");
		$target_kondisi_akhir = $this->input->post("target_kondisi_akhir");

		$clean = array('id_sasaran', 'indikator', 'id_indikator_sasaran',  'satuan', 'status_indikator', 'kategori_indikator' ,'kondisi_awal','target_1','target_2','target_3','target_4','target_5','target_kondisi_akhir');
		$data = $this->global_function->clean_array($data, $clean);



		if (!empty($id)) {
			$result = $this->m_renstra_trx->edit_sasaran_skpd($data, $id, $indikator, $id_indikator_sasaran, $satuan_target, $status_target, $kategori_target, $kondisi_awal, $target_1, $target_2, $target_3, $target_4, $target_5, $target_kondisi_akhir);
		}else{
			$result = $this->m_renstra_trx->add_sasaran_skpd($data, $indikator, $satuan_target, $status_target, $kategori_target, $kondisi_awal, $target_1, $target_2, $target_3, $target_4, $target_5, $target_kondisi_akhir);
		}

		if ($result) {
			$msg = array('success' => '1', 'msg' => 'Sasaran berhasil dibuat.');
			echo json_encode($msg);
		}else{
			$msg = array('success' => '0', 'msg' => 'ERROR! Sasaran gagal dibuat, mohon menghubungi administrator.');
			echo json_encode($msg);
		}
	}

	function delete_sasaran(){
		$id = $this->input->post('id_sasaran');
		$result = $this->m_renstra_trx->delete_sasaran($id);
		if ($result) {
			$msg = array('success' => '1', 'msg' => 'Sasaran berhasil dibuat.');
			echo json_encode($msg);
		}else{
			$msg = array('success' => '0', 'msg' => 'ERROR! Sasaran gagal dibuat, mohon menghubungi administrator.');
			echo json_encode($msg);
		}
	}

	function get_program_skpd(){
		$id_skpd = $this->session->userdata("id_skpd");
		if($id_skpd > 100){
			$id_skpd = $this->m_skpd->get_kode_unit_dari_asisten($id_skpd);
		}
		$data['jendela_kontrol'] = $this->m_renstra_trx->count_jendela_kontrol($id_skpd);

		$id_renstra = $this->input->post('id_renstra');
		$id_sasaran = $this->input->post('id_sasaran');

		$data['id_renstra'] = $id_renstra;
		$data['id_sasaran'] = $id_sasaran;
		$kode_unit = $this->m_skpd->get_kode_unit($id_skpd);
		if ($kode_unit != $id_skpd) {
			$data['program'] = $this->m_renstra_trx->get_all_program_sub_unit($id_renstra, $id_sasaran, $id_skpd, TRUE);
		}else {
			$data['program'] = $this->m_renstra_trx->get_all_program($id_renstra, $id_sasaran, TRUE);
		}
		$this->load->view("renstra/view_program", $data);
	}

	function cru_program_skpd(){
		$id_renstra = $this->input->post('id_renstra');
		$id_sasaran = $this->input->post('id_sasaran');
		$id_program = $this->input->post('id_program');

		$id_skpd = $this->session->userdata("id_skpd");
		$data['skpd'] = $this->m_skpd->get_one_skpd(array('id_skpd' => $id_skpd));

		$kd_urusan_edit = NULL;
		$kd_bidang_edit = NULL;
		$kd_program_edit = NULL;
		$id_prog_rpjmd_edit = NULL;
		if (!empty($id_program)) {
			$result = $this->m_renstra_trx->get_one_program($id_renstra, $id_sasaran, $id_program);
			if (empty($result)) {
				echo '<div style="width: 400px;">ERROR! Data sasaran tidak ditemukan.</div>';
				return FALSE;
			}
			$data['program'] = $result;
			$data['indikator_program'] = $this->m_renstra_trx->get_indikator_prog_keg($id_program, FALSE);
			$kd_urusan_edit = $result->kd_urusan;
			$kd_bidang_edit = $result->kd_bidang;
			$kd_program_edit = $result->kd_program;
			$id_prog_rpjmd_edit = $result->id_prog_rpjmd;
			//$data_indikator_rpjmd = $this->m_rpjmd_trx->get_indikator_program_rpjmd_for_me($result->id_prog_rpjmd);
			$c_rpjmd = 0;
			if (!empty($result->id_prog_rpjmd)) {
				$c_rpjmd = $result->id_prog_rpjmd;
			}
			$data['indik_prog_rpjmd'] = $this->m_rpjmd_trx->get_indikator_program_rpjmd_for_me($c_rpjmd);
		}
// print_r($data['indik_prog_rpjmd']->result());
// exit;
		$data['id_renstra'] = $id_renstra;
		$data['id_sasaran'] = $id_sasaran;
		$data['tujuan_n_sasaran'] = $this->m_renstra_trx->get_info_tujuan_n_sasaran_n_program(NULL, $id_sasaran);
		//$data['indikator_program_rpjmd'] = $this->m_rpjmd_trx->get_program_rpjmd_for_me($id_skpd, NULL);

		$satuan = array("" => "~~ Pilih Satuan ~~");
		foreach ($this->m_lov->get_list_lov(1) as $row) {
			$satuan[$row->kode_value]=$row->nama_value;
		}

		$id_prog_rpjmd = array("" => "");
		foreach ($this->m_rpjmd_trx->get_program_rpjmd_for_me($id_skpd, NULL) as $row) {
			$id_prog_rpjmd[$row->id_nya] = $row->nama_prog;
		}

		$kd_urusan = array("" => "");
		foreach ($this->m_urusan->get_urusan() as $row) {
			$kd_urusan[$row->id] = $row->id .". ". $row->nama;
		}

		$kd_bidang = array("" => "");
		foreach ($this->m_bidang->get_bidang($kd_urusan_edit) as $row) {
			$kd_bidang[$row->id] = $row->id .". ". $row->nama;
		}

		$kd_program = array("" => "");
		foreach ($this->m_program->get_prog($kd_urusan_edit,$kd_bidang_edit) as $row) {
			$kd_program[$row->id] = $row->id .". ". $row->nama;
		}

		$status_indikator = array("" => "~~ Pilih Positif / Negatif ~~");
		foreach ($this->m_lov->get_status_indikator() as $row) {
			$status_indikator[$row->kode_status_indikator]=$row->nama_status_indikator;
		}

		$kategori_indikator = array("" => "~~ Pilih Kategori Indikator ~~");
		foreach ($this->m_lov->get_kategori_indikator() as $row) {
			$kategori_indikator[$row->kode_kategori_indikator]=$row->nama_kategori_indikator;
		}

		$data['satuan'] = $satuan;
		$data['status_indikator'] = $status_indikator;
		$data['kategori_indikator'] = $kategori_indikator;
		$data['id_prog_rpjmd'] = form_dropdown('id_prog_rpjmd', $id_prog_rpjmd, $id_prog_rpjmd_edit, 'data-placeholder="Pilih Program RPJMD" class="common chosen-select" id="id_prog_rpjmd"');
		$data['kd_urusan'] = form_dropdown('kd_urusan', $kd_urusan, $kd_urusan_edit, 'data-placeholder="Pilih Urusan" class="common chosen-select" id="kd_urusan"');
		$data['kd_bidang'] = form_dropdown('kd_bidang', $kd_bidang, $kd_bidang_edit, 'data-placeholder="Pilih Bidang Urusan" class="common chosen-select" id="kd_bidang"');
		$data['kd_program'] = form_dropdown('kd_program', $kd_program, $kd_program_edit, 'data-placeholder="Pilih Program" class="common chosen-select" id="kd_program"');
		$this->load->view("renstra/cru_program", $data);
	}

	function save_program(){
		$id = $this->input->post('id_program');

		$data = $this->input->post();
		$id_indikator_program = $this->input->post("id_indikator_program");
		$indikator = $this->input->post("indikator_kinerja");
		$satuan_target = $this->input->post("satuan_target");
		$status_target = $this->input->post("kode_positif_negatif");
		$kategori_target = $this->input->post("kode_kategori_indikator");
		$kondisi_awal = $this->input->post("kondisi_awal");
		$target_1 = $this->input->post("target_1");
		$target_2 = $this->input->post("target_2");
		$target_3 = $this->input->post("target_3");
		$target_4 = $this->input->post("target_4");
		$target_5 = $this->input->post("target_5");
		$target_kondisi_akhir = $this->input->post("target_kondisi_akhir");

		$clean = array('id_program', 'indikator_kinerja', 'id_indikator_program', 'satuan_target','kode_positif_negatif','kode_kategori_indikator','kondisi_awal','target_1','target_2','target_3','target_4','target_5','target_kondisi_akhir');
		$data = $this->global_function->clean_array($data, $clean);

		if (!empty($id)) {
			$result = $this->m_renstra_trx->edit_program_skpd($data, $id, $indikator, $id_indikator_program, $satuan_target, $status_target, $kategori_target, $kondisi_awal, $target_1, $target_2, $target_3, $target_4, $target_5, $target_kondisi_akhir);
		}else{
			$result = $this->m_renstra_trx->add_program_skpd($data, $indikator, $satuan_target, $status_target, $kategori_target, $kondisi_awal, $target_1, $target_2, $target_3, $target_4, $target_5, $target_kondisi_akhir);
		}

		if ($result) {
			$msg = array('success' => '1', 'msg' => 'Program berhasil dibuat.');
			echo json_encode($msg);
		}else{
			$msg = array('success' => '0', 'msg' => 'ERROR! Program gagal dibuat, mohon menghubungi administrator.');
			echo json_encode($msg);
		}
	}

	function delete_program(){
		$id = $this->input->post('id_program');
		$result = $this->m_renstra_trx->delete_program($id);
		if ($result) {
			$msg = array('success' => '1', 'msg' => 'Program berhasil dibuat.');
			echo json_encode($msg);
		}else{
			$msg = array('success' => '0', 'msg' => 'ERROR! Program gagal dibuat, mohon menghubungi administrator.');
			echo json_encode($msg);
		}
	}

	function get_kegiatan_skpd(){
		$id_skpd = $this->session->userdata("id_skpd");
		if($id_skpd > 100){
			$id_skpd = $this->m_skpd->get_kode_unit_dari_asisten($id_skpd);
		}
		$data['jendela_kontrol'] = $this->m_renstra_trx->count_jendela_kontrol($id_skpd);

		$id_renstra = $this->input->post('id_renstra');
		$id_sasaran = $this->input->post('id_sasaran');
		$id_program = $this->input->post('id_program');

		$data['id_renstra'] = $id_renstra;
		$data['id_sasaran'] = $id_sasaran;
		$data['id_program'] = $id_program;
		$kode_unit = $this->m_skpd->get_kode_unit($id_skpd);
		if ($kode_unit != $id_skpd) {
			$data['kegiatan'] = $this->m_renstra_trx->get_all_kegiatan_sub_unit($id_renstra, $id_sasaran, $id_program, $id_skpd);
		}else {
			$data['kegiatan'] = $this->m_renstra_trx->get_all_kegiatan($id_renstra, $id_sasaran, $id_program, FALSE);
		}
		$this->load->view("renstra/view_kegiatan", $data);
	}

	function cru_kegiatan_skpd(){
		$id_renstra = $this->input->post('id_renstra');
		$id_sasaran = $this->input->post('id_sasaran');
		$id_program = $this->input->post('id_program');
		$id_kegiatan = $this->input->post('id_kegiatan');

		$id_skpd = $this->session->userdata("id_skpd");
		$data['skpd'] = $this->m_skpd->get_one_skpd(array('id_skpd' => $id_skpd));

		$kd_kegiatan_edit = NULL;
		$cb_jenis_belanja_edit = NULL;
		$cb_kategori_belanja_edit = NULL;
		$cb_subkategori_belanja_edit = NULL;
		$cb_belanja_edit = NULL;
		$data['revisi_rpjmd'] = NULL;
		if (!empty($id_kegiatan)) {
			$result = $this->m_renstra_trx->get_one_kegiatan($id_renstra, $id_sasaran, $id_program, $id_kegiatan);
			if (empty($result)) {
				echo '<div style="width: 400px;">ERROR! Data sasaran tidak ditemukan.</div>';
				return FALSE;
			}
			$data['kegiatan'] = $result;
			$data['indikator_kegiatan'] = $this->m_renstra_trx->get_indikator_prog_keg($id_kegiatan, FALSE);
			$kd_kegiatan_edit = $result->kd_kegiatan;


			//cek jika RPJMD
			$proses = $this->m_renstra_trx->cek_proses($id_renstra, $id_skpd);
			if (!empty($proses->proses2) && empty($proses->proses1)) {
				//RPJMD
				$revisi_rpjmd = $this->m_renstra_trx->revisi_rpjmd($result->parent);
				$data['revisi_rpjmd'] = $revisi_rpjmd;
				$data['nominal_banding'] = $this->m_renstra_trx->cek_nominal_banding_dengan_rpjmd($id_kegiatan, $revisi_rpjmd->kd_urusan, $revisi_rpjmd->kd_bidang, $revisi_rpjmd->kd_program, $revisi_rpjmd->id_renstra);
			}
		}

		$data['id_renstra'] = $id_renstra;
		$data['id_sasaran'] = $id_sasaran;
		$data['id_program'] = $id_program;
		$tujuan_sasaran_n_program = $this->m_renstra_trx->get_info_tujuan_n_sasaran_n_program(NULL, $id_sasaran, $id_program);
		$data['tujuan_sasaran_n_program'] = $tujuan_sasaran_n_program;

		$satuan = array("" => "~~ Pilih Satuan ~~");
		foreach ($this->m_lov->get_list_lov(1) as $row) {
			$satuan[$row->kode_value]=$row->nama_value;
		}

		$sumber_dana = array("" => "");
		foreach ($this->m_lov->get_all_sumber_dana() as $row) {
			$sumber_dana[$row->id]=$row->sumber_dana;
		}

		$status_indikator = array("" => "~~ Pilih Positif / Negatif ~~");
		foreach ($this->m_lov->get_status_indikator() as $row) {
			$status_indikator[$row->kode_status_indikator]=$row->nama_status_indikator;
		}

		$kategori_indikator = array("" => "~~ Pilih Kategori Indikator ~~");
		foreach ($this->m_lov->get_kategori_indikator() as $row) {
			$kategori_indikator[$row->kode_kategori_indikator]=$row->nama_kategori_indikator;
		}


		$id_modul_temp = "id_renstra = '".$id_renstra."'";
		$kd_kegiatan = array("" => "");
		foreach ($this->m_kegiatan->get_keg($tujuan_sasaran_n_program->kd_urusan, $tujuan_sasaran_n_program->kd_bidang, $tujuan_sasaran_n_program->kd_program, 't_renstra_prog_keg', $id_modul_temp) as $row) {
			$kd_kegiatan[$row->id] = $row->id .". ". $row->nama;
		}

		$cb_jenis_belanja = array("" => "");
		foreach ($this->m_jenis_belanja->get_jenis_belanja() as $row) {
			$cb_jenis_belanja[$row->id] = $row->id .". ". $row->nama;
		}
		$cb_kategori_belanja = array("" => "");
		foreach ($this->m_kategori_belanja->get_kategori_belanja($cb_jenis_belanja_edit) as $row) {
			$cb_kategori_belanja[$row->id] = $row->id .". ". $row->nama;
		}
		$cb_subkategori_belanja = array("" => "");
		foreach ($this->m_subkategori_belanja->get_subkategori_belanja($cb_jenis_belanja_edit, $cb_kategori_belanja_edit) as $row) {
			$cb_subkategori_belanja[$row->id] = $row->id .". ". $row->nama;
		}
		$cb_belanja = array("" => "");
		foreach ($this->m_kode_belanja->get_belanja($cb_jenis_belanja_edit, $cb_kategori_belanja_edit, $cb_subkategori_belanja_edit) as $row) {
			$cb_belanja[$row->id] = $row->id .". ". $row->nama;
		}

		$data['satuan'] = $satuan;
		$data['sumber_dana'] = $sumber_dana;
		$data['status_indikator'] = $status_indikator;
		$data['kategori_indikator'] = $kategori_indikator;

		$data['kd_kegiatan'] = form_dropdown('kd_kegiatan', $kd_kegiatan, $kd_kegiatan_edit, 'data-placeholder="Pilih Kegiatan" class="common chosen-select" id="kd_kegiatan"');
		//------------------------TAHUN 1
		$data['cb_jenis_belanja_1'] = form_dropdown('cb_jenis_belanja_1', $cb_jenis_belanja, $cb_jenis_belanja_edit, 'data-placeholder="Pilih Kelompok Belanja" class="common chosen-select" id="cb_jenis_belanja_1"');
		$data['cb_kategori_belanja_1'] = form_dropdown('cb_kategori_belanja_1', $cb_kategori_belanja, $cb_kategori_belanja_edit, 'data-placeholder="Pilih Jenis Belanja" class="common chosen-select" id="cb_kategori_belanja_1"');
		$data['cb_subkategori_belanja_1'] = form_dropdown('cb_subkategori_belanja_1', $cb_subkategori_belanja, $cb_subkategori_belanja_edit, 'data-placeholder="Pilih Obyek Belanja" class="common chosen-select" id="cb_subkategori_belanja_1"');
		$data['cb_belanja_1'] = form_dropdown('cb_belanja_1', $cb_belanja, $cb_belanja_edit, 'data-placeholder="Pilih Rincian Obyek" class="common chosen-select" id="cb_belanja_1"');
		//------------------------TAHUN 2
		$data['cb_jenis_belanja_2'] = form_dropdown('cb_jenis_belanja_2', $cb_jenis_belanja, $cb_jenis_belanja_edit, 'data-placeholder="Pilih Kelompok Belanja" class="common chosen-select" id="cb_jenis_belanja_2"');
		$data['cb_kategori_belanja_2'] = form_dropdown('cb_kategori_belanja_2', $cb_kategori_belanja, $cb_kategori_belanja_edit, 'data-placeholder="Pilih Jenis Belanja" class="common chosen-select" id="cb_kategori_belanja_2"');
		$data['cb_subkategori_belanja_2'] = form_dropdown('cb_subkategori_belanja_2', $cb_subkategori_belanja, $cb_subkategori_belanja_edit, 'data-placeholder="Pilih Obyek Belanja" class="common chosen-select" id="cb_subkategori_belanja_2"');
		$data['cb_belanja_2'] = form_dropdown('cb_belanja_2', $cb_belanja, $cb_belanja_edit, 'data-placeholder="Pilih Rincian Obyek" class="common chosen-select" id="cb_belanja_2"');
		//------------------------TAHUN 3
		$data['cb_jenis_belanja_3'] = form_dropdown('cb_jenis_belanja_3', $cb_jenis_belanja, $cb_jenis_belanja_edit, 'data-placeholder="Pilih Kelompok Belanja" class="common chosen-select" id="cb_jenis_belanja_3"');
		$data['cb_kategori_belanja_3'] = form_dropdown('cb_kategori_belanja_3', $cb_kategori_belanja, $cb_kategori_belanja_edit, 'data-placeholder="Pilih Jenis Belanja" class="common chosen-select" id="cb_kategori_belanja_3"');
		$data['cb_subkategori_belanja_3'] = form_dropdown('cb_subkategori_belanja_3', $cb_subkategori_belanja, $cb_subkategori_belanja_edit, 'data-placeholder="Pilih Obyek Belanja" class="common chosen-select" id="cb_subkategori_belanja_3"');
		$data['cb_belanja_3'] = form_dropdown('cb_belanja_3', $cb_belanja, $cb_belanja_edit, 'data-placeholder="Pilih Rincian Obyek" class="common chosen-select" id="cb_belanja_3"');
		//------------------------TAHUN 4
		$data['cb_jenis_belanja_4'] = form_dropdown('cb_jenis_belanja_4', $cb_jenis_belanja, $cb_jenis_belanja_edit, 'data-placeholder="Pilih Kelompok Belanja" class="common chosen-select" id="cb_jenis_belanja_4"');
		$data['cb_kategori_belanja_4'] = form_dropdown('cb_kategori_belanja_4', $cb_kategori_belanja, $cb_kategori_belanja_edit, 'data-placeholder="Pilih Jenis Belanja" class="common chosen-select" id="cb_kategori_belanja_4"');
		$data['cb_subkategori_belanja_4'] = form_dropdown('cb_subkategori_belanja_4', $cb_subkategori_belanja, $cb_subkategori_belanja_edit, 'data-placeholder="Pilih Obyek Belanja" class="common chosen-select" id="cb_subkategori_belanja_4"');
		$data['cb_belanja_4'] = form_dropdown('cb_belanja_4', $cb_belanja, $cb_belanja_edit, 'data-placeholder="Pilih Rincian Obyek" class="common chosen-select" id="cb_belanja_4"');
		//------------------------TAHUN 5
		$data['cb_jenis_belanja_5'] = form_dropdown('cb_jenis_belanja_5', $cb_jenis_belanja, $cb_jenis_belanja_edit, 'data-placeholder="Pilih Kelompok Belanja" class="common chosen-select" id="cb_jenis_belanja_5"');
		$data['cb_kategori_belanja_5'] = form_dropdown('cb_kategori_belanja_5', $cb_kategori_belanja, $cb_kategori_belanja_edit, 'data-placeholder="Pilih Jenis Belanja" class="common chosen-select" id="cb_kategori_belanja_5"');
		$data['cb_subkategori_belanja_5'] = form_dropdown('cb_subkategori_belanja_5', $cb_subkategori_belanja, $cb_subkategori_belanja_edit, 'data-placeholder="Pilih Obyek Belanja" class="common chosen-select" id="cb_subkategori_belanja_5"');
		$data['cb_belanja_5'] = form_dropdown('cb_belanja_5', $cb_belanja, $cb_belanja_edit, 'data-placeholder="Pilih Rincian Obyek" class="common chosen-select" id="cb_belanja_5"');

		$data['detil_kegiatan'] = $this->m_renstra_trx->get_kegiatan($id_kegiatan);

		// print_r($id_kegiatan);
		// exit;

		$this->load->view("renstra/cru_kegiatan", $data);
	}

	function save_kegiatan(){
		$id = $this->input->post('id_kegiatan');

		//$data = $this->input->post();

		$Id_Kegiatan = $this->input->post('id_kegiatan');
		$KodeProgram = $this->input->post("kd_program");
		$id_indikator_kegiatan = $this->input->post("id_indikator_kegiatan");
		$indikator = $this->input->post("indikator_kinerja");
		$satuan_target = $this->input->post("satuan_target");
		$status_target = $this->input->post("status_target");
		$kategori_target = $this->input->post("kategori_target");
		$kondisi_awal = $this->input->post("kondisi_awal");
		$target_1 = $this->input->post("target_1");
		$target_2 = $this->input->post("target_2");
		$target_3 = $this->input->post("target_3");
		$target_4 = $this->input->post("target_4");
		$target_5 = $this->input->post("target_5");
		$target_kondisi_akhir = $this->input->post("target_kondisi_akhir");


	$data = array(

		  'id_renstra' => $this->input->post('id_renstra'),
		  'id_sasaran' => $this->input->post('id_sasaran'),
		 'id_program'  => $this->input->post('id_program'),
		  'kd_urusan' =>  $this->input->post('kd_urusan'),
		  'kd_bidang' => $this->input->post('kd_bidang'),
		  'kd_program' =>  $this->input->post('kd_program'),
		  'nama_prog_or_keg' => $this->input->post('nama_prog_or_keg'),
		  'kd_kegiatan' =>  $this->input->post('kd_kegiatan'),
		  'penanggung_jawab' =>  $this->input->post('penanggung_jawab'),
		  'lokasi' => $this->input->post('lokasin'),
		  'parent'=> $this->input->post('parent'),
		  'is_prog_or_keg' => $this->input->post('is_prog_or_keg'),
		  'id_status' =>  $this->input->post('id_status'),
			'lokasi_1' => $this->input->post('lokasi_1'),
			'lokasi_2' => $this->input->post('lokasi_2'),
			'lokasi_3' => $this->input->post('lokasi_3'),
			'lokasi_4' => $this->input->post('lokasi_4'),
			'lokasi_5' => $this->input->post('lokasi_5'),
			'uraian_kegiatan_1' => $this->input->post('uraian_kegiatan_1'),
			'uraian_kegiatan_2' => $this->input->post('uraian_kegiatan_2'),
			'uraian_kegiatan_3' => $this->input->post('uraian_kegiatan_3'),
			'uraian_kegiatan_4' => $this->input->post('uraian_kegiatan_4'),
			'uraian_kegiatan_5' => $this->input->post('uraian_kegiatan_5'),
			'nominal_1' => $this->input->post('nominal_1'),
			'nominal_2' => $this->input->post('nominal_2'),
			'nominal_3' => $this->input->post('nominal_3'),
			'nominal_4' => $this->input->post('nominal_4'),
			'nominal_5' => $this->input->post('nominal_5')

		);

	// $dataKegiatan1 = array(
	// 	'tahun'=>1,
	// 	'kode_urusan'=>$this->input->post('kd_urusan',true),
	// 	'kode_bidang'=>$this->input->post('kd_bidang',true),
	// 	'kode_program'=>$this->input->post('kd_program',true),
	// 	'kode_kegiatan'=>$this->input->post('kd_kegiatan',true),
	// 	'kode_sumber_dana'=>$this->input->post('kd_sumber_dana_1',true),
	// 	'kode_jenis_belanja'=>$this->input->post('r_kd_jenis_belanja_1',true),
	// 	'kode_kategori_belanja'=>$this->input->post('r_kd_kategori_belanja_1',true),
	// 	'kode_sub_kategori_belanja'=>$this->input->post('r_kd_subkategori_belanja_1',true),
	// 	'kode_belanja'=>$this->input->post('r_kd_belanja_1',true),
	// 	'uraian_belanja'=>$this->input->post('r_uraian_1',true),
	// 	'detil_uraian_belanja'=>$this->input->post('r_det_uraian_1',true),
	// 	'volume'=>$this->input->post('r_volume_1',true),
	// 	'satuan'=>$this->input->post('r_satuan_1',true),
	// 	'nominal_satuan'=>$this->input->post('r_nominal_satuan_1',true),
	// 	'subtotal'=>$this->input->post('r_subtotal_1',true),
	// 	);

	// 	$dataKegiatan2 = array(
	// 	'tahun'=>2,
	// 	'kode_urusan'=>$this->input->post('kd_urusan',true),
	// 	'kode_bidang'=>$this->input->post('kd_bidang',true),
	// 	'kode_program'=>$this->input->post('kd_program',true),
	// 	'kode_kegiatan'=>$this->input->post('kd_kegiatan',true),
	// 	'kode_sumber_dana'=>$this->input->post('kd_sumber_dana_2',true),
	// 	'kode_jenis_belanja'=>$this->input->post('r_kd_jenis_belanja_2',true),
	// 	'kode_kategori_belanja'=>$this->input->post('r_kd_kategori_belanja_2',true),
	// 	'kode_sub_kategori_belanja'=>$this->input->post('r_kd_subkategori_belanja_2',true),
	// 	'kode_belanja'=>$this->input->post('r_kd_belanja_2',true),
	// 	'uraian_belanja'=>$this->input->post('r_uraian_2',true),
	// 	'detil_uraian_belanja'=>$this->input->post('r_det_uraian_2',true),
	// 	'volume'=>$this->input->post('r_volume_2',true),
	// 	'satuan'=>$this->input->post('r_satuan_2',true),
	// 	'nominal_satuan'=>$this->input->post('r_nominal_satuan_2',true),
	// 	'subtotal'=>$this->input->post('r_subtotal_2',true),
	// 	);

	// 	$dataKegiatan3 = array(
	// 	'tahun'=>3,
	// 	'kode_urusan'=>$this->input->post('kd_urusan',true),
	// 	'kode_bidang'=>$this->input->post('kd_bidang',true),
	// 	'kode_program'=>$this->input->post('kd_program',true),
	// 	'kode_kegiatan'=>$this->input->post('kd_kegiatan',true),
	// 	'kode_sumber_dana'=>$this->input->post('kd_sumber_dana_3',true),
	// 	'kode_jenis_belanja'=>$this->input->post('r_kd_jenis_belanja_3',true),
	// 	'kode_kategori_belanja'=>$this->input->post('r_kd_kategori_belanja_3',true),
	// 	'kode_sub_kategori_belanja'=>$this->input->post('r_kd_subkategori_belanja_3',true),
	// 	'kode_belanja'=>$this->input->post('r_kd_belanja_3',true),
	// 	'uraian_belanja'=>$this->input->post('r_uraian_3',true),
	// 	'detil_uraian_belanja'=>$this->input->post('r_det_uraian_3',true),
	// 	'volume'=>$this->input->post('r_volume_3',true),
	// 	'satuan'=>$this->input->post('r_satuan_3',true),
	// 	'nominal_satuan'=>$this->input->post('r_nominal_satuan_3',true),
	// 	'subtotal'=>$this->input->post('r_subtotal_3',true),
	// 	);

	// $dataKegiatan4 = array(
	// 	'tahun'=>4,
	// 	'kode_urusan'=>$this->input->post('kd_urusan',true),
	// 	'kode_bidang'=>$this->input->post('kd_bidang',true),
	// 	'kode_program'=>$this->input->post('kd_program',true),
	// 	'kode_kegiatan'=>$this->input->post('kd_kegiatan',true),
	// 	'kode_sumber_dana'=>$this->input->post('kd_sumber_dana_4',true),
	// 	'kode_jenis_belanja'=>$this->input->post('r_kd_jenis_belanja_4',true),
	// 	'kode_kategori_belanja'=>$this->input->post('r_kd_kategori_belanja_4',true),
	// 	'kode_sub_kategori_belanja'=>$this->input->post('r_kd_subkategori_belanja_4',true),
	// 	'kode_belanja'=>$this->input->post('r_kd_belanja_4',true),
	// 	'uraian_belanja'=>$this->input->post('r_uraian_4',true),
	// 	'detil_uraian_belanja'=>$this->input->post('r_det_uraian_4',true),
	// 	'volume'=>$this->input->post('r_volume_4',true),
	// 	'satuan'=>$this->input->post('r_satuan_4',true),
	// 	'nominal_satuan'=>$this->input->post('r_nominal_satuan_4',true),
	// 	'subtotal'=>$this->input->post('r_subtotal_4',true),
	// 	);
	// 	// print_r($dataKegiatan4);
	// 	// exit();

	// 	$dataKegiatan5 = array(
	// 	'tahun'=>5,
	// 	'kode_urusan'=>$this->input->post('kd_urusan',true),
	// 	'kode_bidang'=>$this->input->post('kd_bidang',true),
	// 	'kode_program'=>$this->input->post('kd_program',true),
	// 	'kode_kegiatan'=>$this->input->post('kd_kegiatan',true),
	// 	'kode_sumber_dana'=>$this->input->post('kd_sumber_dana_5',true),
	// 	'kode_jenis_belanja'=>$this->input->post('r_kd_jenis_belanja_5',true),
	// 	'kode_kategori_belanja'=>$this->input->post('r_kd_kategori_belanja_5',true),
	// 	'kode_sub_kategori_belanja'=>$this->input->post('r_kd_subkategori_belanja_5',true),
	// 	'kode_belanja'=>$this->input->post('r_kd_belanja_5',true),
	// 	'uraian_belanja'=>$this->input->post('r_uraian_5',true),
	// 	'detil_uraian_belanja'=>$this->input->post('r_det_uraian_5',true),
	// 	'volume'=>$this->input->post('r_volume_5',true),
	// 	'satuan'=>$this->input->post('r_satuan_5',true),
	// 	'nominal_satuan'=>$this->input->post('r_nominal_satuan_5',true),
	// 	'subtotal'=>$this->input->post('r_subtotal_5',true),
	// 	);

	// 	// print_r(count($dataKegiatan1['kode_sumber_dana'])." ////.".count($dataKegiatan2['kode_sumber_dana']));
	// 	// exit;

	// 	if ($this->input->post('nominal_1') > 0) {
	// 		$dataKegiatan1 = $this->global_function->re_index($dataKegiatan1);
	// 	}
	// 	if ($this->input->post('nominal_2') > 0) {
	// 		$dataKegiatan2 = $this->global_function->re_index($dataKegiatan2);
	// 	}
	// 	if ($this->input->post('nominal_3') > 0) {
	// 		$dataKegiatan3 = $this->global_function->re_index($dataKegiatan3);
	// 	}
	// 	if ($this->input->post('nominal_4') > 0) {
	// 		$dataKegiatan4 = $this->global_function->re_index($dataKegiatan4);
	// 	}
	// 	if ($this->input->post('nominal_5') > 0) {
	// 		$dataKegiatan5 = $this->global_function->re_index($dataKegiatan5);
	// 	}

		$clean = array('id_kegiatan', 'id_indikator_kegiatan', 'indikator_kinerja', 'satuan_target','kode_positif_negatif','kode_kategori_indikator','kondisi_awal','target_1','target_2','target_3','target_4','target_5','target_kondisi_akhir');
		$data = $this->global_function->clean_array($data, $clean);

		$change = array('id_program'=>'parent');
		$data = $this->global_function->change_array($data, $change);



		if (!empty($id)) {
			$result = $this->m_renstra_trx->edit_kegiatan_skpd($data, $id, $indikator, $id_indikator_kegiatan, $satuan_target, $status_target, $kategori_target, $kondisi_awal, $target_1, $target_2, $target_3, $target_4, $target_5, $target_kondisi_akhir);
		}else{

			$result = $this->m_renstra_trx->add_kegiatan_skpd($data, $indikator, $satuan_target, $status_target, $kategori_target, $kondisi_awal, $target_1, $target_2, $target_3, $target_4, $target_5, $target_kondisi_akhir);
		}

		if ($result) {
			$msg = array('success' => '1', 'msg' => 'Kegiatan berhasil dibuat.');
			echo json_encode($msg);
		}else{
			$msg = array('success' => '0', 'msg' => 'ERROR! Kegiatan gagal dibuat, mohon menghubungi administrator.');
			echo json_encode($msg);
		}
	}

	function belanja_kegiatan_lihat($return, $id_kegiatan, $ta, $tahun, $not=NULL){
		$result = $this->m_renstra_trx->get_kegiatan($id_kegiatan, $ta, $not);

		$i = 1;
		$total = 0;
		if ($return) {
			return $result;
		}else{
			foreach ($result as $row) {
				$vol = Formatting::currency($row->volume, 2);
				$nom = Formatting::currency($row->nominal_satuan, 2);
				$sub = Formatting::currency($row->subtotal, 2);

				echo "<tr id='".$row->id."'>
				<td>".$i.".</td>
				<td>".$row->kode_jenis_belanja.". ".$row->jenis_belanja."</td>
				<td>".$row->kode_kategori_belanja.". ".$row->kategori_belanja."</td>
				<td>".$row->kode_sub_kategori_belanja.". ".$row->sub_kategori_belanja."</td>
				<td>".$row->kode_belanja.". ".$row->belanja."</td>
				<td>".$row->uraian_belanja."</td>
				<td>".$row->Sumber_dana."</td>
				<td>".$row->detil_uraian_belanja."</td>
				<td>".$vol."</td>
				<td>".$row->satuan."</td>
				<td>".$nom."</td>
				<td>".$sub."</td>
				<td>
					<span id='ubahrowng' class='icon-pencil' onclick='ubahrowng_".$tahun."(".$row->id.")' style='cursor:pointer' title='Ubah Belanja'></span>
				</td>
				<td>
					<span id='hapusrowng' class='icon-remove' onclick='hapusrowng_".$tahun."(".$row->id.")' style='cursor:pointer' title='Hapus Belanja'></span>
				</td>
				</tr>";
				$i++;
				$total += $row->subtotal;
			}
			echo "<script type='text/javascript'>$('#nominal_".$tahun."').autoNumeric('set', ".$total.");</script>";
		}
		
	}

	function belanja_kegiatan_save(){
		$tahun = $this->input->post('tahun');
		$id_kegiatan = $this->input->post('id_kegiatan');
		$id_belanja = $this->input->post('id_belanja');
		$data = $this->input->post();

		$th = $this->m_settings->get_tahun_anggaran_db();

		$clean = array('tahun','id_belanja');
		$data = $this->global_function->clean_array($data, $clean);

		$add = array('tahun' => $th[$tahun-1]->tahun_anggaran, 'created_date' => date("Y-m-d H-i-s"));
		$data = $this->global_function->add_array($data, $add);

		$this->m_renstra_trx->add_belanja_kegiatan($data, $id_belanja);
		
		$this->belanja_kegiatan_lihat(FALSE, $id_kegiatan, $th[$tahun-1]->tahun_anggaran, $tahun);
	}

	function belanja_kegiatan_edit(){
		$tahun = $this->input->post('tahun');
		$id_kegiatan = $this->input->post('id_kegiatan');
		$id_belanja = $this->input->post('id_belanja');
		$th = $this->m_settings->get_tahun_anggaran_db();

		$data['edit'] = $this->m_renstra_trx->get_one_belanja($id_belanja);
		$data['list'] = $this->belanja_kegiatan_lihat(TRUE, $id_kegiatan, $th[$tahun-1]->tahun_anggaran, $tahun, $id_belanja);

		echo json_encode($data);
	}

	function belanja_kegiatan_hapus(){
		$id = $this->input->post('id_belanja');
		$id_kegiatan = $this->input->post('id_kegiatan');
		$tahun = $this->input->post('tahun');
		$th = $this->m_settings->get_tahun_anggaran_db();

		$this->m_renstra_trx->delete_one_kegiatan($id);

		$data['list'] = $this->belanja_kegiatan_lihat(TRUE, $id_kegiatan, $th[$tahun-1]->tahun_anggaran, $tahun);

		echo json_encode($data);
	}

	function cek_belanja(){
		$id_kegiatan = $this->input->post('id_kegiatan');
		$tahun = $this->input->post('tahun');
		$th = $this->m_settings->get_tahun_anggaran_db();

		$cek = $this->m_renstra_trx->cek_belanja_pertahun($id_kegiatan, $th[$tahun-1]->tahun_anggaran);
		echo json_encode($cek);
	}

	function belanja_copy(){
		$id_kegiatan = $this->input->post('id_kegiatan');
		$tahun = $this->input->post('tahun');
		$th = $this->m_settings->get_tahun_anggaran_db();

		$this->m_renstra_trx->copy_belanja($id_kegiatan, $th[$tahun-1]->tahun_anggaran);

		$this->belanja_kegiatan_lihat(FALSE, $id_kegiatan, $th[$tahun-1]->tahun_anggaran, $tahun);
	}


	function delete_kegiatan(){
		$id = $this->input->post('id_kegiatan');
		$result = $this->m_renstra_trx->delete_kegiatan($id);
		if ($result) {
			$msg = array('success' => '1', 'msg' => 'Kegiatan berhasil dibuat.');
			echo json_encode($msg);
		}else{
			$msg = array('success' => '0', 'msg' => 'ERROR! Kegiatan gagal dibuat, mohon menghubungi administrator.');
			echo json_encode($msg);
		}
	}

	function preview_kegiatan_renstra(){
		$id = $this->input->post("id");



		$result = $this->m_renstra_trx->get_one_kegiatan(NULL, NULL, NULL, $id, TRUE);
		if (!empty($result)) {
			$data['renstra'] = $result;
			$data['indikator_sasaran'] = $this->m_renstra_trx->get_indikator_sasaran($result->id_sasaran);
			$data['indikator_kegiatan'] = $this->m_renstra_trx->get_indikator_prog_keg_status_kat($result->id, TRUE, TRUE);
			$data['detil_kegiatan'] = $this->m_renstra_trx->get_kegiatan($id);
			//print_r($data['indikator_kegiatan']);
			//exit;
			$this->load->view('renstra/preview', $data);

		}else{
			echo "Data tidak ditemukan . . .";
		}
	}

	function tampilkan($id){

		$result = $this->m_renstra_trx->get_one_kegiatan(NULL, NULL, NULL, $id, TRUE);
		if (!empty($result)) {
			$data['renstra'] = $result;
			$data['indikator_sasaran'] = $this->m_renstra_trx->get_indikator_sasaran($result->id_sasaran);
			$data['indikator_kegiatan'] = $this->m_renstra_trx->get_indikator_prog_keg_status_kat($result->id, TRUE, TRUE);
			$data['detil_kegiatan'] = $this->m_renstra_trx->get_kegiatan_semuanya($id);
			//print_r($data['indikator_kegiatan']);
			//exit;
			$this->load->view('renstra/belanja_renstra_view_detil', $data);

		}else{
			echo "Data tidak ditemukan . . .";
		}
	}

	function kirim_renstra(){
		$data['skpd'] = $this->session->userdata("id_skpd");
		$this->load->view('renstra/kirim_renstra', $data);
	}

	function do_kirim_renstra(){
		$id = $this->input->post('skpd');
		$result = $this->m_renstra_trx->kirim_renstra($id);
		if ($result) {
			$msg = array('success' => '1', 'msg' => 'Renstra berhasil dikirim.');
			echo json_encode($msg);
		}else{
			$msg = array('success' => '0', 'msg' => 'ERROR! Renstra gagal dikirim, mohon menghubungi administrator.');
			echo json_encode($msg);
		}
	}

	## --------------------------------------- ##
	## Verifikasi Renstra yang telah di kirim  ##
	## --------------------------------------- ##
	function veri_view(){
		$this->auth->restrict();

		$data['renstras'] = $this->m_renstra_trx->get_all_renstra_veri();
		$this->template->load('template','renstra/verifikasi/view_all', $data);
	}

function view_detil_renstra_skpd($id_skpd){
		$this->auth->restrict();

		$skpd_visi = $this->m_renstra_trx->get_one_renstra_skpd($id_skpd, TRUE);
		$data1['skpd_visi'] = $skpd_visi;
		$data1['misi'] = $this->m_renstra_trx->get_all_renstra_misi($skpd_visi->id, FALSE);
		$data1['tujuan'] = $this->m_renstra_trx->get_all_renstra_tujuan($skpd_visi->id, FALSE);

		$data3['sasaran'] = $this->m_renstra_trx->get_all_sasaran($skpd_visi->id, NULL, TRUE);
		$data1['sasaran'] = "<table class=\"table-common\">".$this->load->view('renstra/cetak/header_sasaran', $data3, TRUE)."</table>";

		$data['header'] = $this->load->view('renstra/cetak/header', $data1, TRUE);

		$data['program'] = $this->m_renstra_trx->get_program_skpd_4_cetak($id_skpd);
		$data['skpd_visi'] = $skpd_visi;

		$this->template->load('template','renstra/view_renstra_skpd/view', $data);
	}
	
	function veri($id_skpd){
		$this->auth->restrict();

		$skpd_visi = $this->m_renstra_trx->get_one_renstra_skpd($id_skpd, TRUE);
		$data1['skpd_visi'] = $skpd_visi;
		$data1['misi'] = $this->m_renstra_trx->get_all_renstra_misi($skpd_visi->id, FALSE);
		$data1['tujuan'] = $this->m_renstra_trx->get_all_renstra_tujuan($skpd_visi->id, FALSE);

		$data3['sasaran'] = $this->m_renstra_trx->get_all_sasaran($skpd_visi->id, NULL, TRUE);
		$data1['sasaran'] = "<table class=\"table-common\">".$this->load->view('renstra/cetak/header_sasaran', $data3, TRUE)."</table>";

		$data['header'] = $this->load->view('renstra/cetak/header', $data1, TRUE);

		$data['program'] = $this->m_renstra_trx->get_program_skpd_4_cetak($id_skpd);
		$data['skpd_visi'] = $skpd_visi;

		$this->template->load('template','renstra/verifikasi/view', $data);
	}

	function do_veri(){
		$id_renstra = $this->input->post('id_renstra');
		$id_keg = $this->input->post('id_kegiatan');

		$data['renstra'] = $this->m_renstra_trx->get_one_kegiatan($id_renstra, NULL, NULL, $id_keg, TRUE);
		$renstra = $data['renstra'];
		$data['indikator_sasaran'] = $this->m_renstra_trx->get_indikator_sasaran($renstra->id_sasaran);
		$data['indikator'] = $this->m_renstra_trx->get_indikator_prog_keg_status_kat($renstra->id, TRUE, TRUE);
		$data['program'] = FALSE;
		$data['detil_kegiatan'] = $this->m_renstra_trx->get_kegiatan($id_keg);
		$this->load->view('renstra/verifikasi/veri', $data);
	}

	function do_veri_prog(){
		$id_renstra = $this->input->post('id_renstra');
		$id = $this->input->post('id_program');

		$data['renstra'] = $this->m_renstra_trx->get_one_program($id_renstra, NULL, $id, TRUE);
		$renstra = $data['renstra'];
		$data['indikator_sasaran'] = $this->m_renstra_trx->get_indikator_sasaran($renstra->id_sasaran);
		$data['indikator'] = $this->m_renstra_trx->get_indikator_prog_keg_status_kat($renstra->id, TRUE, TRUE);
		$data['program'] = TRUE;
		$this->load->view('renstra/verifikasi/veri', $data);
	}

	function save_veri(){
		$id = $this->input->post("id");
		$id_renstra = $this->input->post("id_renstra");
		$veri = $this->input->post("veri");
		$ket = $this->input->post("ket");

		if ($veri == "setuju") {
			$result = $this->m_renstra_trx->approved_renstra($id_renstra, $id);
		}elseif ($veri == "tdk_setuju") {
			$result = $this->m_renstra_trx->not_approved_renstra($id_renstra, $id, $ket);
		}

		if ($result) {
			$msg = array('success' => '1', 'msg' => 'Kegiatan berhasil diverifikasi.');
			echo json_encode($msg);
		}else{
			$msg = array('success' => '0', 'msg' => 'ERROR! Kegiatan gagal diverifikasi, mohon menghubungi administrator.');
			echo json_encode($msg);
		}
	}

	function preview_revisi(){
		$id = $this->input->post("id_renstra");
		$revisi = $this->input->post("id_revisi");

		if ($revisi == "revisi_ranwal") {
			$data['revisi'] = $this->m_renstra_trx->get_revisi_renstra_ranwal($id);
			$this->load->view('renstra/revisi_ranwal', $data);
		}elseif ($revisi == "revisi_akhir") {
			$data['revisi'] = $this->m_renstra_trx->get_revisi_renstra_akhir($id);
			$this->load->view('renstra/revisi_renstra_akhir', $data);
		}elseif ($revisi == "revisi_rpjm") {
			$data['revisi'] = $this->m_renstra_trx->get_revisi_rpjm($id);
			$this->load->view('renstra/revisi_rpjm', $data);
		}
	}

	function disapprove_renstra(){
		$data['id_renstra'] = $this->input->post('id_renstra');
		$this->load->view('renstra/verifikasi/disapprove_renstra', $data);
	}

	function do_disapprove_renstra(){
		$this->auth->restrict_ajax_login();

		$id_renstra = $this->input->post('id_renstra');
		$ket = $this->input->post('ket');
		$result = $this->m_renstra_trx->disapprove_renstra($id_renstra, $ket);
		echo json_encode(array('success' => '1', 'msg' => 'Revisi telah ditolak.', 'href' => site_url('renstra/veri_view')));
	}

	## --------------------------------------------- ##
	## Verifikasi Renstra Akhir yang telah di kirim  ##
	## --------------------------------------------- ##
	function veri_view_akhir_all(){
		$this->auth->restrict();

		$data['bidang_urusan'] = $this->m_renstra_trx->get_all_renstra_veri_akhir();
		$this->template->load('template','renstra/verifikasi_akhir/view_all', $data);
	}

	function veri_view_akhir($urusan, $bidang){
		$this->auth->restrict();

		$data['bidang_urusan'] = $this->m_renstra_trx->get_bidang_urusan_4_cetak_final($urusan, $bidang);
		$this->template->load('template','renstra/verifikasi_akhir/view_veri_all', $data);
	}

	function veri_view_tdk_setuju(){
		$id = $this->input->post("id");
		$result = $this->m_renstra_trx->get_one_bidang_urusan_skpd_program_final($id);
		$data['renstra'] = $result;
		$data['indikator_program'] = $this->m_renstra_trx->get_indikator_prog_keg($result->id, TRUE, TRUE);
		$this->load->view('renstra/verifikasi_akhir/veri', $data);
	}

	function save_veri_akhir(){
		$id = $this->input->post("id");
		$veri = $this->input->post("veri");

		$data = $this->input->post();
		$clean = array('veri');
		$data = $this->global_function->clean_array($data, $clean);
		$change = array('id'=>'id_prog_keg');
		$data = $this->global_function->change_array($data, $change);

		if ($veri == "setuju") {
			$result = $this->m_renstra_trx->approved_veri_akhir_renstra($id);
		}elseif ($veri == "tdk_setuju") {
			$result = $this->m_renstra_trx->not_approved_veri_akhir_renstra($id, $data);
		}

		if ($result) {
			$msg = array('success' => '1', 'msg' => 'Renstra berhasil diverifikasi.');
			echo json_encode($msg);
		}else{
			$msg = array('success' => '0', 'msg' => 'ERROR! Renstra gagal diverifikasi, mohon menghubungi administrator.');
			echo json_encode($msg);
		}
	}

	## -------------------------------------- ##
	## Cetak Renstra Per SKPD dan Semua SKPD  ##
	## -------------------------------------- ##



	private function cetak_skpd_func1($id_skpd){
		$proses = $this->m_renstra_trx->count_jendela_kontrol($id_skpd);
		if (!empty($proses->veri2)) {
			$data['renstra_type'] = "Renstra Akhir";
		}else{
			$data['renstra_type'] = "Renstra Rancangan Awal";
		}

		/*$protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://';
		$header = $this->m_template_cetak->get_value("GAMBAR");
		$data['logo'] = str_replace("src=\"","height=\"90px\" src=\"".$protocol.$_SERVER['HTTP_HOST'],$header);
		//$data['header'] = $this->m_template_cetak->get_value("HEADER");
		$skpd_detail = $this->m_skpd->get_one_skpd(array('id_skpd' => $id_skpd));
		$data['header'] = "<p>". strtoupper($skpd_detail->nama_skpd) ."<BR>KABUPATEN KLUNGKUNG, PROVINSI BALI - INDONESIA<BR>".$skpd_detail->alamat."<BR>Telp.".$skpd_detail->telp_skpd."<p>";
		*/
		$skpd_visi = $this->m_renstra_trx->get_one_renstra_skpd($id_skpd, TRUE);
		$data1['skpd_visi'] = $skpd_visi;
		$data1['misi'] = $this->m_renstra_trx->get_all_renstra_misi($skpd_visi->id, FALSE);
		$data1['tujuan'] = $this->m_renstra_trx->get_all_renstra_tujuan($skpd_visi->id, FALSE);

		$data3['sasaran'] = $this->m_renstra_trx->get_all_sasaran($skpd_visi->id, NULL, TRUE);
		$data1['sasaran'] = "<table class=\"full_width border\" style=\"font-size: 12px;\">".$this->load->view('renstra/cetak/header_sasaran', $data3, TRUE)."</table>";

		$data['header_renstra'] = $this->load->view('renstra/cetak/header', $data1, TRUE);


		$data2['program'] = $this->m_renstra_trx->get_program_skpd_4_cetak($id_skpd);
		$data['renstra'] = $this->load->view('renstra/cetak/program_kegiatan', $data2, TRUE);
		return $data;
	}

	function do_cetak_renstra($id_skpd=NULL){
		ini_set('memory_limit', '-1');

		$this->auth->restrict();

		if (empty($id_skpd)) {
			$id_skpd = $this->session->userdata('id_skpd');
		}

		if ($id_skpd == "all") {
			$all_skpd = $this->m_renstra_trx->get_all_skpd();
			$html="";
			foreach ($all_skpd as $row) {
				$data = $this->cetak_skpd_func($row->id_skpd);
				$html .= '<div class="page-break">'.$this->load->view('renstra/cetak/cetak', $data, true).'</div>';
			}
			$data['contents'] = $html;
			$data['qr'] = $this->ciqrcode->generateQRcode("sirenbangda", 'Renstra Semua '. date("d-m-Y_H-i-s"), 1);
			$html = $this->load->view('template_cetak', $data, true);

			$filename='Renstra Semua '. date("d-m-Y_H-i-s") .'.pdf';

		    pdf_create($html, $filename, "A4", "Landscape", FALSE);
		}else{
			$skpd = $this->m_renstra_trx->get_one_renstra_skpd($id_skpd, TRUE);
			if (!empty($skpd)) {
				$data = $this->cetak_skpd_func($id_skpd);
				$data['qr'] = $this->ciqrcode->generateQRcode("sirenbangda", 'Renstra '. $skpd->nama_skpd ." ". date("d-m-Y_H-i-s"), 1);
				$html = $this->template->load('template_cetak', 'renstra/cetak/cetak', $data, true);
				$filename='Renstra '. $skpd->nama_skpd ." ". date("d-m-Y_H-i-s") .'.pdf';
			}else{
				$html = "<center>Data Tidak Tersedia . . .</center>";
				$filename='Renstra '. date("d-m-Y_H-i-s") .'.pdf';
			}
			//echo $html;
		    pdf_create($html, $filename, "A4", "Landscape", FALSE);
		}


	}

	function cetak_renstra_skpd_all($id_skpd="all"){
		$this->auth->restrict();

		$all_skpd = $this->m_skpd->get_data_dropdown_skpd(NULL, TRUE);
		$data['dd_skpd'] = form_dropdown('ss_skpd', $all_skpd, $id_skpd, 'id="ss_skpd"');
		$data['id'] = $id_skpd;

		$data['total_nominal_renstra'] = $this->m_renstra_trx->get_total_nominal_renstra($id_skpd);
		$this->template->load('template','renstra/cetak/view', $data);
	}

	## ------------------------ ##
	## Revisi RPJM dan Renstra  ##
	## ------------------------ ##

	function view_p_revisi(){
		$this->auth->restrict_ajax_login();
		$id_skpd = $this->session->userdata('id_skpd');
		$result = $this->m_renstra_trx->check_revisi($id_skpd);
		if (empty($result)) {
			$msg = $this->load->view('renstra/pengajuan_revisi', NULL, TRUE);
			echo json_encode(array('success' => '1', 'msg' => $msg));
		}else{
			echo json_encode(array('success' => '0', 'msg' => 'SKPD bersangkutan telah mengajukan Revisi Renstra, Mohon menunggu persetujuan.'));
		}
	}

	function p_revisi(){
		$this->auth->restrict_ajax_login();

		$id_skpd = $this->session->userdata('id_skpd');
		$ket = $this->input->post('ket');
		$result = $this->m_renstra_trx->simpan_revisi($id_skpd, $ket);
		echo json_encode(array('success' => '1', 'msg' => 'Pengajuan Revisi Berhasil di kirim.'));
	}

	## --------------- ##
	## Preview Renstra ##
	## --------------- ##

	function preview_renstra(){
		$this->auth->restrict();
		$id_skpd = $this->session->userdata('id_skpd');

		$skpd = $this->m_renstra_trx->get_one_renstra_skpd($id_skpd, TRUE);
		// print_r($this->db->last_query());
		// exit();
		if (!empty($skpd)) {
			$data = $this->cetak_skpd_func($id_skpd, 'lihatrenstra');
			$this->template->load('template', 'renstra/preview_renstra', $data);
		}else{
			$this->session->set_userdata('msg_typ','err');
			$this->session->set_userdata('msg', 'Data renstra tidak tersedia.');
			redirect('home');
		}
	}

	private function cetak_skpd_func($id_skpd,$jenis){
		$id_skpd_sub=$id_skpd;
		$kode_unit_skpd = $this->m_skpd->get_kode_unit($id_skpd);
		if ($kode_unit_skpd!=$id_skpd) {
			$id_skpd= $kode_unit_skpd;
		};

		$proses = $this->m_renstra_trx->count_jendela_kontrol($id_skpd);
		if (!empty($proses->veri2)) {
			$data['renstra_type'] = "Renstra Akhir";
		}else{
			$data['renstra_type'] = "Renstra Rancangan Awal";
		}

		$skpd_visi = $this->m_renstra_trx->get_one_renstra_skpd($id_skpd, TRUE);
		$data1['skpd_visi'] = $skpd_visi;
		$data1['misi'] = $this->m_renstra_trx->get_all_renstra_misi($skpd_visi->id, FALSE);
		$data1['tujuan'] = $this->m_renstra_trx->get_all_renstra_tujuan($skpd_visi->id, FALSE);
// print_r($data1);
// exit()
		$data3['tujuan'] = $this->m_renstra_trx->get_all_tujuan($skpd_visi->id, NULL, FALSE);
		$data3['sasaran'] = $this->m_renstra_trx->get_all_sasaran($skpd_visi->id, NULL, FALSE);


		if ($jenis == 'TujuanJangkaMenengah'){
			$data1['tujuan_gabung'] = $this->load->view('renstra/cetak/header_tujuan', $data3, TRUE);
			$data['header_tujuan_gabung'] = $this->load->view('renstra/cetak/header_tujuan_gabung', $data1, TRUE);
		}elseif ($jenis== 'SasaranJangkaMenengah'){
			$data1['sasaran_gabung'] = $this->load->view('renstra/cetak/header_sasaran', $data3, TRUE);
			$data['header_sasaran_gabung'] = $this->load->view('renstra/cetak/header_sasaran_gabung', $data1, TRUE);
		}elseif ($jenis== 'KebijakanUmumRenstra'){
	//$data['header_renstra'] = $this->load->view('renstra/cetak/header', $data1, TRUE);
			$data2['program'] = $this->m_renstra_trx->get_program_skpd_4_cetak($id_skpd);
			$data['renstra'] = $this->load->view('renstra/cetak/cetak_kebijakan_umum', $data2, TRUE);
		}elseif ($jenis== 'TargepaguIndikatifProgramKegiatan'){
			$data2['program'] = $this->m_renstra_trx->get_program_skpd_4_cetak($id_skpd_sub);

			$data['renstra'] = $this->load->view('renstra/cetak/cetak_target_pagu_indikatif', $data2, TRUE);
			
		}elseif ($jenis== 'lihatrenstra') {
			$data1['sasaran'] = "<table class=\"full_width border\" style=\"font-size: 12px;\">".$this->load->view('renstra/cetak/header_sasaran', $data3, TRUE)."</table>";
			// print($data1['sasaran']);
			// exit();
			$data['header_renstra'] = $this->load->view('renstra/cetak/header', $data1, TRUE);
			//$id_skpd=$id_skpd_sub;
			$id_skpd=$id_skpd_sub;
			$data2['program'] = $this->m_renstra_trx->get_program_skpd_4_cetak($id_skpd);
// print_r ($this->db->last_query());
// exit();
			$data['renstra'] = $this->load->view('renstra/cetak/cetak_lihat_renstra', $data2, TRUE);
		}
		return $data;
	}

	private function cetak_func($cetak=FALSE, $ta, $idK){
		if (!$cetak) {
			$temp['class_table']='class="table-common"';
		}else{
			$temp['class_table']='class="border"';
		}

		$protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://';
		$header = $this->m_template_cetak->get_value("GAMBAR");
		$data['logo'] = str_replace("src=\"","height=\"45px\" src=\"".$protocol.$_SERVER['HTTP_HOST'],$header);
		$data['tahun1'] =$this->m_renstra_trx->get_renstra_belanja_per_tahun211($ta, $idK);

		$data['keluaran'] =$this->m_renstra_trx->get_indikator_keluaran($ta, $idK);
		$data['capaian'] =$this->m_renstra_trx->get_indikator_capaian($idK);

		$data['ta_ng'] = $ta;
		$data['idk_ng'] = $idK;

		$result = $this->load->view("renstra/cetak/cetak_form_221",$data, TRUE);

		return $result;
	}

		function cetak_kegiatan($ta, $idK){
			$protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://';
			$header = $this->m_template_cetak->get_value("GAMBAR");
			$data['logo'] = str_replace("src=\"","height=\"70px\" src=\"".$protocol.$_SERVER['HTTP_HOST'],$header);
			$data['header'] = $this->m_template_cetak->get_value("HEADER");
			$data['qr'] = $this->ciqrcode->generateQRcode("sirenbangda", 'usulanbansos/persetujuanusulanbansos'. date("d-m-Y_H-i-s"), 1);

			$data['cetak'] = $this->cetak_func(TRUE, $ta, $idK);

			$html = $this->template->load('template_cetak_rka', 'renstra/cetak/cetak_view', $data, true);

		 	$filename='renja '. $this->session->userdata('nama_skpd') ." ". date("d-m-Y_H-i-s") .'.pdf';

		 	
		 	// print_r($html);
		 	// exit();

			pdf_create($html, $filename, "A4", "Landscape", FALSE);

		}

		function preview_periode_221(){
			$data['id_keg'] = $this->input->post('id');
			$this->load->view('renstra/periode_221', $data);
		}

		function veri_kepala_skpd(){
			$this->auth->restrict();
			$id_skpd = $this->session->userdata('id_skpd');

			$skpd_visi = $this->m_renstra_trx->get_one_renstra_skpd($id_skpd, TRUE);
			$data1['skpd_visi'] = $skpd_visi;
			$data1['misi'] = $this->m_renstra_trx->get_all_renstra_misi($skpd_visi->id, FALSE);
			$data1['tujuan'] = $this->m_renstra_trx->get_all_renstra_tujuan($skpd_visi->id, FALSE);

			$data3['sasaran'] = $this->m_renstra_trx->get_all_sasaran($skpd_visi->id, NULL, TRUE);
			$data1['sasaran'] = "<table class=\"table-common\">".$this->load->view('renstra/cetak/header_sasaran', $data3, TRUE)."</table>";

			$data['header'] = $this->load->view('renstra/cetak/header', $data1, TRUE);

			$data['program'] = $this->m_renstra_trx->get_program_skpd_4_cetak($id_skpd);
			$data['skpd_visi'] = $skpd_visi;

			$this->template->load('template','kepala_skpd/view', $data);
		}

		function do_veri_kepala_skpd(){
			$id_renstra = $this->input->post('id_renstra');
			$id_keg = $this->input->post('id_kegiatan');

			$data['renstra'] = $this->m_renstra_trx->get_one_kegiatan($id_renstra, NULL, NULL, $id_keg, TRUE);
			$renstra = $data['renstra'];
			$data['indikator_sasaran'] = $this->m_renstra_trx->get_indikator_sasaran($renstra->id_sasaran);
			$data['indikator'] = $this->m_renstra_trx->get_indikator_prog_keg_status_kat($renstra->id, TRUE, TRUE);
			$data['program'] = FALSE;
			$data['detil_kegiatan'] = $this->m_renstra_trx->get_kegiatan($id_keg);
			$this->load->view('kepala_skpd/veri', $data);
		}

		function do_veri_prog_kepala_skpd(){
			$id_renstra = $this->input->post('id_renstra');
			$id = $this->input->post('id_program');

			$data['renstra'] = $this->m_renstra_trx->get_one_program($id_renstra, NULL, $id, TRUE);
			$renstra = $data['renstra'];
			$data['indikator_sasaran'] = $this->m_renstra_trx->get_indikator_sasaran($renstra->id_sasaran);
			$data['indikator'] = $this->m_renstra_trx->get_indikator_prog_keg_status_kat($renstra->id, TRUE, TRUE);
			$data['program'] = TRUE;
			$this->load->view('kepala_skpd/veri', $data);
		}

		function save_veri_kepala_skpd(){
			$id = $this->input->post("id");
			$id_renstra = $this->input->post("id_renstra");
			$veri = $this->input->post("veri");
			$ket = $this->input->post("ket");

			if ($veri == "setuju") {
				$result = $this->m_renstra_trx->approve_one_renstra_kepala_skpd($id);
			}elseif ($veri == "tdk_setuju") {
				$result = $this->m_renstra_trx->not_approve_renstra_kepala_skpd($id);
			}

			if ($result) {
				$msg = array('success' => '1', 'msg' => 'Kegiatan berhasil diverifikasi.');
				echo json_encode($msg);
			}else{
				$msg = array('success' => '0', 'msg' => 'ERROR! Kegiatan gagal diverifikasi, mohon menghubungi administrator.');
				echo json_encode($msg);
			}
		}

		function approve_renstra_kepala_skpd(){
			$data['id_renstra'] = $this->input->post('id_renstra');
			$this->load->view('kepala_skpd/approve_renstra', $data);
		}

		function do_approve_renstra_kepala_skpd(){
			$this->auth->restrict_ajax_login();

			$id_renstra = $this->input->post('id_renstra');
			//$ket = $this->input->post('ket');
			$result = $this->m_renstra_trx->approve_renstra_kepala_skpd($id_renstra);
			echo json_encode(array('success' => '1', 'msg' => 'Verifikasi berhasil.', 'href' => site_url('renstra/veri_kepala_skpd')));
		}

function view_renstra_skpd(){
		$this->auth->restrict();

		$data['renstras'] = $this->m_renstra_trx->get_all_renstra_skpd();
		$this->template->load('template','renstra/view_renstra_skpd/view_all', $data);
	}
}
