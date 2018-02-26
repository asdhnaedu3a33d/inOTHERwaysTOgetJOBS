<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ppas_perubahan extends CI_Controller
{
	var $CI = NULL;
	public function __construct()
	{
		$this->CI =& get_instance();
        parent::__construct();
        //$this->load->helper(array('form','url', 'text_helper','date'));
        $this->load->database();
        //$this->load->model('m_musrenbang','',TRUE);
        $this->load->model(array('m_jenis_belanja','m_kategori_belanja','m_subkategori_belanja','m_kode_belanja',
				'm_ppas_perubahan','m_skpd','m_lov','m_urusan', 'm_bidang', 'm_program', 'm_kegiatan', 'm_template_cetak', 'm_rpjmd_trx'));
        if (!empty($this->session->userdata("db_aktif"))) {
            $this->load->database($this->session->userdata("db_aktif"), FALSE, TRUE);
        }
	}

	function index(){
		$this->rka_skpd();
	}

	function kirim_ppas(){
		$this->auth->restrict();
		$data['skpd'] = $this->session->userdata("id_skpd");
		$this->load->view('ppas_perubahan/kirim_ppas', $data);
	}

	function do_kirim_ppas(){
		$this->auth->restrict();
		$id = $this->input->post('skpd');
		$ta = $this->m_settings->get_tahun_anggaran();
		$result = $this->m_ppas_perubahan->kirim_kendali_renja($id,$ta);
		//echo $this->db->last_query();
		if ($result) {
			$msg = array('success' => '1', 'msg' => 'PPAS Perubahan berhasil dikirim.');
			echo json_encode($msg);
		}else{
			$msg = array('success' => '0', 'msg' => 'ERROR! PPAS Perubahan gagal dikirim, mohon menghubungi administrator.');
			echo json_encode($msg);
		}
	}

	function get_jendela_kontrol(){
		$id_skpd = $this->session->userdata("id_skpd");
		$nama_skpd = $this->session->userdata("nama_skpd");

		if($id_skpd > 100){
			//$id_skpd = $this->m_skpd->get_kode_unit_dari_asisten($id_skpd);
		}else {
			$kode_unit = $this->m_skpd->get_kode_unit($id_skpd);
			if ($kode_unit != $id_skpd) {
				$id_skpd = $kode_unit;
			}
		}

		$ta = $this->m_settings->get_tahun_anggaran();
		$data['id_skpd'] = $id_skpd;
		$data['nama_skpd'] = $nama_skpd;
		$data['rka'] = $this->m_ppas_perubahan->get_rka($id_skpd,$ta);
		//$data['renja'] = $this->m_renja_trx->get_one_renja_skpd($id_skpd, TRUE);
		$data['jendela_kontrol'] = $this->m_ppas_perubahan->count_jendela_kontrol($id_skpd,$ta);
		//echo $this->db->last_query();
		$this->load->view('ppas_perubahan/jendela_kontrol', $data);
	}

	function rka_skpd(){
		$this->auth->restrict();
		//$this->output->enable_profiler(TRUE);
		$id_skpd 	= $this->session->userdata("id_skpd");
		$nama_skpd 	= $this->session->userdata("nama_skpd");
		$ta 		= $this->m_settings->get_tahun_anggaran();
		$id_tahun	= $this->m_settings->get_id_tahun();

		if (empty($id_skpd)) {
			$this->session->set_userdata('msg_typ','err');
			$this->session->set_userdata('msg', 'User tidak memiliki akses untuk pembuatan RKA Perubahan, mohon menghubungi administrator.');
			redirect('home');
		}

		$data['nama_skpd']=$nama_skpd;
		$data['jendela_kontrol'] = $this->m_ppas_perubahan->count_jendela_kontrol($id_skpd,$ta);

		$id_renja 	= $this->input->post('id_renja');
		$id 		= $this->input->post('id');

		$data['id_renja'] = $id_renja;
		$data['id']			= $id;
		$data['program'] = $this->m_ppas_perubahan->get_all_program($id_skpd,$ta);
		$data['id_skpd'] = $id_skpd;
		$data['ta']	= $ta;

		if($id_skpd > 100){
			$id_skpd = $this->m_skpd->get_kode_unit_dari_asisten($id_skpd);
		}
		$kode_unit = $this->m_skpd->get_kode_unit($id_skpd);
		if (empty($data['program'])) {
			if ($kode_unit != $id_skpd) {
				redirect('home/kosong/PPAS_Perubahan');
			}
		}
		$this->template->load('template','ppas_perubahan/view', $data);
	}

	function get_renja(){
		$id_skpd 	= $this->session->userdata("id_skpd");
		$ta 		= $this->m_settings->get_tahun_anggaran();
		$rka		= $this->m_ppas_perubahan->insert_rka($id_skpd,$ta);
		$result 	= $this->m_ppas_perubahan->import_from_renja($id_skpd,$ta);
		if ($result) {
			$msg = array('success' => '1', 'msg' => 'Renja Perubahan berhasil diambil.');
			echo json_encode($msg);
		}else{
			$msg = array('success' => '0', 'msg' => 'ERROR! Renja Perubahan gagal diambil, mohon menghubungi administrator.');
			echo json_encode($msg);
		}
	}

	function cru_program_skpd(){
		$this->auth->restrict();
		$id = $this->input->post('id');
		$id_skpd = $this->session->userdata("id_skpd");
		$data['skpd'] = $this->m_skpd->get_one_skpd(array('id_skpd' => $id_skpd));

		$kd_urusan_edit = NULL;
		$kd_bidang_edit = NULL;
		$kd_program_edit = NULL;
		$id_prog_rpjmd_edit = NULL;
		if (!empty($id)) {
			$result = $this->m_ppas_perubahan->get_one_program($id);
			if (empty($result)) {
				echo '<div style="width: 400px;">ERROR! Data tidak ditemukan.</div>';
				return FALSE;
			}
			$data['program'] = $result;
			$data['indikator_program'] = $this->m_ppas_perubahan->get_indikator_prog_keg_perubahan($id, FALSE);
			$kd_urusan_edit = $result->kd_urusan;
			$kd_bidang_edit = $result->kd_bidang;
			$kd_program_edit = $result->kd_program;
			$id_prog_rpjmd_edit = $result->id_prog_rpjmd;
			// $data_indikator_rpjmd = $this->m_rpjmd_trx->get_indikator_program_rpjmd_for_me($result->id_prog_rpjmd);
			$c_rpjmd = 0;
			if (!empty($result->id_prog_rpjmd)) {
				$c_rpjmd = $result->id_prog_rpjmd;
			}
			$data['indik_prog_rpjmd'] = $this->m_rpjmd_trx->get_indikator_program_rpjmd_for_me($result->id_prog_rpjmd);
		}


		$satuan = array("" => "~~ Pilih Satuan ~~");
		foreach ($this->m_lov->get_list_lov(1) as $row) {
			$satuan[$row->kode_value]=$row->nama_value;
		}

		$id_prog_rpjmd = array("" => "");
		foreach ($this->m_rpjmd_trx->get_program_rpjmd_for_me($id_skpd, NULL) as $row) {
			$id_prog_rpjmd[$row->id_nya] = $row->nama_prog;
		}

		$status_indikator = array("" => "~~ Pilih Positif / Negatif ~~");
		foreach ($this->m_lov->get_status_indikator() as $row) {
			$status_indikator[$row->kode_status_indikator]=$row->nama_status_indikator;
		}

		$kategori_indikator = array("" => "~~ Pilih Kategori Indikator ~~");
		foreach ($this->m_lov->get_kategori_indikator() as $row) {
			$kategori_indikator[$row->kode_kategori_indikator]=$row->nama_kategori_indikator;
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

		$data['satuan'] = $satuan;
		$data['status_indikator'] = $status_indikator;
		$data['kategori_indikator'] = $kategori_indikator;
		$data['id_prog_rpjmd'] = form_dropdown('id_prog_rpjmd', $id_prog_rpjmd, $id_prog_rpjmd_edit, 'data-placeholder="Pilih Program RPJMD" class="common chosen-select" id="id_prog_rpjmd"');
		$data['kd_urusan'] = form_dropdown('kd_urusan', $kd_urusan, $kd_urusan_edit, 'data-placeholder="Pilih Urusan" class="common chosen-select" id="kd_urusan"');
		$data['kd_bidang'] = form_dropdown('kd_bidang', $kd_bidang, $kd_bidang_edit, 'data-placeholder="Pilih Bidang Urusan" class="common chosen-select" id="kd_bidang"');
		$data['kd_program'] = form_dropdown('kd_program', $kd_program, $kd_program_edit, 'data-placeholder="Pilih Program" class="common chosen-select" id="kd_program"');
		$this->load->view("ppas_perubahan/cru_program", $data);
	}

	function save_program_rka(){
		$this->auth->restrict();
		$id = $this->input->post('id_program');

		$data = $this->input->post();
		$id_skpd = $this->input->post("id_skpd");
		$tahun = $this->input->post("tahun");
		$id_indikator_program = $this->input->post("id_indikator_program");
		$indikator = $this->input->post("indikator_kinerja");
		$satuan_target = $this->input->post("satuan_target");
		$target = $this->input->post("target");
		$target_thndpn = $this->input->post("target_thndpn");
		$status_indikator = $this->input->post('status_target');
		$kategori_indikator = $this->input->post('kategori_target');

		$clean = array('id_program', 'indikator_kinerja', 'id_indikator_program','status_target', 'kategori_target', 'satuan_target','target','target_thndpn');
		$data = $this->global_function->clean_array($data, $clean);

		if (!empty($id)) {
			$result = $this->m_ppas_perubahan->edit_program_skpd($data, $id, $indikator, $id_indikator_program, $satuan_target, $status_indikator, $kategori_indikator, $target, $target_thndpn);
		}else{
			$result = $this->m_ppas_perubahan->add_program_skpd($data, $indikator, $satuan_target,$status_indikator, $kategori_indikator, $target, $target_thndpn);
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
		$this->auth->restrict();
		$id = $this->input->post('id');
		$result = $this->m_ppas_perubahan->delete_program($id);
		if ($result) {
			$msg = array('success' => '1', 'msg' => 'Program berhasil dihapus.');
			echo json_encode($msg);
		}else{
			$msg = array('success' => '0', 'msg' => 'ERROR! Program gagal dihapus, mohon menghubungi administrator.');
			echo json_encode($msg);
		}
	}

	function get_kegiatan_skpd(){
		$this->auth->restrict();
		$id_skpd = $this->session->userdata("id_skpd");
		$ta		 = $this->m_settings->get_tahun_anggaran();
		$data['jendela_kontrol'] = $this->m_ppas_perubahan->count_jendela_kontrol($id_skpd,$ta);

		$id			= $this->input->post('id');
		//echo $id_renstra;

		$data['id']	= $id;
		$data['kegiatan'] = $this->m_ppas_perubahan->get_all_kegiatan($id, $id_skpd, $ta);

		$this->load->view("ppas_perubahan/view_kegiatan", $data);
	}

	function cru_kegiatan_skpd(){
		$this->auth->restrict();
		//$this->output->enable_profiler(true);
		$id_program = $this->input->post('id_program');
		$id 		= $this->input->post('id');

		$id_skpd = $this->session->userdata("id_skpd");
		$data['skpd'] = $this->m_skpd->get_one_skpd(array('id_skpd' => $id_skpd));

		$kd_kegiatan_edit = NULL;
		$cb_kategori_belanja_edit = NULL;
		$cb_subkategori_belanja_edit = NULL;
		$cb_belanja_edit = NULL;
		$cb_jenis_belanja_edit = NULL;

		if (!empty($id)) {
			$result = $this->m_ppas_perubahan->get_one_kegiatan($id_program,$id);
			if (empty($result)) {
				echo '<div style="width: 400px;">ERROR! Data tidak ditemukan.</div>';
				return FALSE;
			}
			$data['kegiatan'] = $result;
			$data['indikator_kegiatan'] = $this->m_ppas_perubahan->get_indikator_prog_keg_perubahan($id, FALSE);
			$kd_kegiatan_edit = $result->kd_kegiatan;

		}
		$data['id_program'] = $id_program;
		$kodefikasi = $this->m_ppas_perubahan->get_info_kodefikasi_program($id_program);
		//echo $this->db->last_query();
		$data['kodefikasi'] = $kodefikasi;

		$sumber_dana = array("" => "");
		foreach ($this->m_lov->get_all_sumber_dana() as $row) {
			$sumber_dana[$row->id]=$row->sumber_dana;
		}

		$satuan = array("" => "~~ Pilih Satuan ~~");
		foreach ($this->m_lov->get_list_lov(1) as $row) {
			$satuan[$row->kode_value]=$row->nama_value;
		}

		$satuan_thndpn = array("" => "~~ Pilih Satuan ~~");
		foreach ($this->m_lov->get_list_lov(1) as $row) {
			$satuan_thndpn[$row->kode_value]=$row->nama_value;
		}

		$kd_kegiatan = array("" => "");
		foreach ($this->m_kegiatan->get_keg($kodefikasi->kd_urusan, $kodefikasi->kd_bidang, $kodefikasi->kd_program) as $row) {
			$kd_kegiatan[$row->id] = $row->id .". ". $row->nama;
		}
		$cb_jenis_belanja = array("" => "");
		foreach ($this->m_jenis_belanja->get_jenis_belanja() as $row) {
			$cb_jenis_belanja[$row->id] = $row->id .". ". $row->nama;
		}
		$status_indikator = array("" => "~~ Pilih Positif / Negatif ~~");
		foreach ($this->m_lov->get_status_indikator() as $row) {
			$status_indikator[$row->kode_status_indikator]=$row->nama_status_indikator;
		}

		$kategori_indikator = array("" => "~~ Pilih Kategori Indikator ~~");
		foreach ($this->m_lov->get_kategori_indikator() as $row) {
			$kategori_indikator[$row->kode_kategori_indikator]=$row->nama_kategori_indikator;
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

		//------------------------TAHUN SEKARANG
		$data['cb_jenis_belanja_1'] = form_dropdown('cb_jenis_belanja_1', $cb_jenis_belanja, $cb_jenis_belanja_edit, 'data-placeholder="Pilih Kelompok Belanja" class="common chosen-select" id="cb_jenis_belanja_1"');
		$data['cb_kategori_belanja_1'] = form_dropdown('cb_kategori_belanja_1', $cb_kategori_belanja, $cb_kategori_belanja_edit, 'data-placeholder="Pilih Jenis Belanja" class="common chosen-select" id="cb_kategori_belanja_1"');
		$data['cb_subkategori_belanja_1'] = form_dropdown('cb_subkategori_belanja_1', $cb_subkategori_belanja, $cb_subkategori_belanja_edit, 'data-placeholder="Pilih Obyek Belanja" class="common chosen-select" id="cb_subkategori_belanja_1"');
		$data['cb_belanja_1'] = form_dropdown('cb_belanja_1', $cb_belanja, $cb_belanja_edit, 'data-placeholder="Pilih Rincian Obyek" class="common chosen-select" id="cb_belanja_1"');
		//------------------------TAHUN DEPAN
		$data['cb_jenis_belanja_2'] = form_dropdown('cb_jenis_belanja_2', $cb_jenis_belanja, $cb_jenis_belanja_edit, 'data-placeholder="Pilih Kelompok Belanja" class="common chosen-select" id="cb_jenis_belanja_2"');
		$data['cb_kategori_belanja_2'] = form_dropdown('cb_kategori_belanja_2', $cb_kategori_belanja, $cb_kategori_belanja_edit, 'data-placeholder="Pilih Jenis Belanja" class="common chosen-select" id="cb_kategori_belanja_2"');
		$data['cb_subkategori_belanja_2'] = form_dropdown('cb_subkategori_belanja_2', $cb_subkategori_belanja, $cb_subkategori_belanja_edit, 'data-placeholder="Pilih Obyek Belanja" class="common chosen-select" id="cb_subkategori_belanja_2"');
		$data['cb_belanja_2'] = form_dropdown('cb_belanja_2', $cb_belanja, $cb_belanja_edit, 'data-placeholder="Pilih Rincian Obyek" class="common chosen-select" id="cb_belanja_2"');

		$data['detil_kegiatan_1'] = $this->m_ppas_perubahan->get_rka_belanja_per_tahun($id, '1');
		$data['detil_kegiatan_2'] = $this->m_ppas_perubahan->get_rka_belanja_per_tahun($id, '0');

		$this->load->view("ppas_perubahan/cru_kegiatan", $data);
	}

	function save_kegiatan(){
		$this->auth->restrict();
		$id = $this->input->post('id_kegiatan');

		//$data = $this->input->post();
		$id_skpd = $this->input->post("id_skpd");
		$tahun = $this->input->post("tahun");
		$parent = $this->input->post("id_program");
		$id_indikator_kegiatan = $this->input->post("id_indikator_kegiatan");
		$indikator = $this->input->post("indikator_kinerja");
		$satuan_target = $this->input->post("satuan_target");
		$status_indikator = $this->input->post("status_target");
		$kategori_indikator = $this->input->post("kategori_target");
		$target = $this->input->post("target");
		$target_thndpn = $this->input->post("target_thndpn");

		$data = array(
			'tahun' => $this->input->post("tahun"),
			'id_skpd' => $this->input->post('id_skpd'),
			'id_renja' => $this->input->post('id_renja'),
		  'id_program'  => $this->input->post('id_program'),
		  'kd_urusan' =>  $this->input->post('kd_urusan'),
		  'kd_bidang' => $this->input->post('kd_bidang'),
		  'kd_program' =>  $this->input->post('kd_program'),
		  'nama_prog_or_keg' => $this->input->post('nama_prog_or_keg'),
		  'kd_kegiatan' =>  $this->input->post('kd_kegiatan'),
		  'penanggung_jawab' =>  $this->input->post('penanggung_jawab'),
		  'parent'=> $this->input->post('parent'),
		  'is_prog_or_keg' => $this->input->post('is_prog_or_keg'),
			'lokasi' => $this->input->post("lokasi"),
			'lokasi_thndpn' => $this->input->post("lokasi_thndpn"),
			'nominal' => $this->input->post("nominal"),
			'nominal_thndpn' => $this->input->post("nominal_thndpn"),
			'catatan' => $this->input->post("catatan"),
			'catatan_thndpn' => $this->input->post("catatan_thndpn")
		);

		$dataKegiatan1 = array(
			'tahun'=>$tahun,
			'kode_urusan'=>$this->input->post('kd_urusan',true),
			'kode_bidang'=>$this->input->post('kd_bidang',true),
			'kode_program'=>$this->input->post('kd_program',true),
			'kode_kegiatan'=>$this->input->post('kd_kegiatan',true),
			'kode_sumber_dana'=>$this->input->post('kd_sumber_dana_1',true),
			'kode_jenis_belanja'=>$this->input->post('r_kd_jenis_belanja_1',true),
			'kode_kategori_belanja'=>$this->input->post('r_kd_kategori_belanja_1',true),
			'kode_sub_kategori_belanja'=>$this->input->post('r_kd_subkategori_belanja_1',true),
			'kode_belanja'=>$this->input->post('r_kd_belanja_1',true),
			'uraian_belanja'=>$this->input->post('r_uraian_1',true),
			'detil_uraian_belanja'=>$this->input->post('r_det_uraian_1',true),
			'volume'=>$this->input->post('r_volume_1',true),
			'satuan'=>$this->input->post('r_satuan_1',true),
			'nominal_satuan'=>$this->input->post('r_nominal_satuan_1',true),
			'subtotal'=>$this->input->post('r_subtotal_1',true),
			'is_tahun_sekarang'=>1,
			'id_status_renja'=>1,
			'id_keg'=>$this->input->post("id_kegiatan")
		);

		$dataKegiatan2 = array(
		'tahun'=>$tahun + 1,
		'kode_urusan'=>$this->input->post('kd_urusan',true),
		'kode_bidang'=>$this->input->post('kd_bidang',true),
		'kode_program'=>$this->input->post('kd_program',true),
		'kode_kegiatan'=>$this->input->post('kd_kegiatan',true),
		'kode_sumber_dana'=>$this->input->post('kd_sumber_dana_2',true),
		'kode_jenis_belanja'=>$this->input->post('r_kd_jenis_belanja_2',true),
		'kode_kategori_belanja'=>$this->input->post('r_kd_kategori_belanja_2',true),
		'kode_sub_kategori_belanja'=>$this->input->post('r_kd_subkategori_belanja_2',true),
		'kode_belanja'=>$this->input->post('r_kd_belanja_2',true),
		'uraian_belanja'=>$this->input->post('r_uraian_2',true),
		'detil_uraian_belanja'=>$this->input->post('r_det_uraian_2',true),
		'volume'=>$this->input->post('r_volume_2',true),
		'satuan'=>$this->input->post('r_satuan_2',true),
		'nominal_satuan'=>$this->input->post('r_nominal_satuan_2',true),
		'subtotal'=>$this->input->post('r_subtotal_2',true),
		'is_tahun_sekarang'=>0,
		'id_status_renja'=>1,
		'id_keg'=>$this->input->post("id_kegiatan")
		);


		if ($this->input->post('nominal') > 0) {
			$dataKegiatan1 = $this->global_function->re_index($dataKegiatan1);
		}
		if ($this->input->post('nominal_thndpn') > 0) {
			$dataKegiatan2 = $this->global_function->re_index($dataKegiatan2);
		}

		$clean = array('id_kegiatan', 'id_indikator_kegiatan', 'indikator_kinerja', 'satuan_target','target','target_thndpn');
		$data = $this->global_function->clean_array($data, $clean);
		$change = array('id_program'=>'parent');
		$data = $this->global_function->change_array($data, $change);

		if (!empty($id)) {
			$result = $this->m_ppas_perubahan->edit_kegiatan_skpd($data, $id, $indikator, $id_indikator_kegiatan, $satuan_target,$status_indikator, $kategori_indikator, $target, $target_thndpn, $dataKegiatan1, $dataKegiatan2);
		}else{
			$result = $this->m_ppas_perubahan->add_kegiatan_skpd($data, $indikator, $satuan_target,$status_indikator, $kategori_indikator, $target, $target_thndpn, $dataKegiatan1, $dataKegiatan2);
		}

		if ($result) {
			$msg = array('success' => '1', 'msg' => 'Kegiatan berhasil dibuat.');
			echo json_encode($msg);
		}else{
			$msg = array('success' => '0', 'msg' => 'ERROR! Kegiatan gagal dibuat, mohon menghubungi administrator.');
			echo json_encode($msg);
		}
	}

	function delete_kegiatan(){
		$this->auth->restrict();
		$id = $this->input->post('id');
		$result = $this->m_ppas_perubahan->delete_kegiatan($id);
		if ($result) {
			$msg = array('success' => '1', 'msg' => 'Kegiatan berhasil dibuat.');
			echo json_encode($msg);
		}else{
			$msg = array('success' => '0', 'msg' => 'ERROR! Kegiatan gagal dibuat, mohon menghubungi administrator.');
			echo json_encode($msg);
		}
	}

	function preview_kegiatan_renja(){
		$id = $this->input->post("id");
		$result = $this->m_ppas_perubahan->get_one_kegiatan(NULL, $id, TRUE);
		if (!empty($result)) {
			$data['renja'] = $result;
			$data['indikator_kegiatan'] = $this->m_ppas_perubahan->get_indikator_prog_keg_perubahan($result->id, TRUE, TRUE);


			$data['tahun1'] = $this->m_ppas_perubahan->get_rka_belanja_per_tahun($id, '1');
			$data['tahun2'] = $this->m_ppas_perubahan->get_rka_belanja_per_tahun($id, '0');
			$this->load->view('ppas_perubahan/preview', $data);
		}else{
			echo "Data tidak ditemukan . . .";
		}
	}

	function cru_rka()
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

		$this->template->load('template','ppas_perubahan/cru_rka', $data);
	}

	function save_rka() {
	$this->auth->restrict();
        //action save cekbank di table t_cekbank
		$id_rka		 			= $this->input->post('id_rka');
		$call_from				= $this->input->post('call_from');
		$kd_urusan				= $this->input->post('kd_urusan');
		$kd_bidang	 			= $this->input->post('kd_bidang');
		$kd_program	 			= $this->input->post('kd_program');
		$kd_kegiatan			= $this->input->post('kd_kegiatan');
		$indikator_capaian		= $this->input->post('indikator_capaian');
		$tahun_sekarang			= $this->input->post('tahun_sekarang');
		$lokasi					= $this->input->post('lokasi');
    	$capaian_sekarang		= $this->input->post('capaian_sekarang');
    	$jumlah_dana_sekarang	= $this->input->post('jumlah_dana_sekarang');
    	$tahun_mendatang		= $this->input->post('tahun_mendatang');
    	$capaian_mendatang		= $this->input->post('capaian_mendatang');
    	$jumlah_dana_mendatang	= $this->input->post('jumlah_dana_mendatang');
        $kriteria_keberhasilan	= $this->input->post('kriteria_keberhasilan');
        $ukuran_keberhasilan	= $this->input->post('ukuran_keberhasilan');
        $triwulan				= $this->input->post('triwulan');
        $pagu					= $this->input->post('pagu');
        $realisasi				= $this->input->post('realisasi');
        $capaian_triwulan 		= $this->input->post('capaian_triwulan');
        $ukuran_kinerja_triwulan= $this->input->post('ukuran_kinerja_triwulan');
        $capaian_output_triwulan= $this->input->post('capaian_output_triwulan');
        $keterangan				= $this->input->post('keterangan');

		if(strpos($call_from, 'ppas_perubahan/cru_rka') != FALSE) {
			$call_from = '';
		}
		//cek apakah cekbank tsb ada
		$data_rka = $this->m_ppas_perubahan->get_rka_by_id($id_rka);
		if(empty($data_rka)) {
			//cek bank baru
			$data_rka = new stdClass();
			$id_rka = '';
		}
		//all
		$data_rka->id_rka				= $id_rka;
		$data_rka->kd_urusan			= $kd_urusan;
		$data_rka->kd_bidang	 		= $kd_bidang;
		$data_rka->kd_program	 		= $kd_program;
		$data_rka->kd_kegiatan			= $kd_kegiatan;
		$data_rka->indikator_capaian	= $indikator_capaian;
		$data_rka->tahun_sekarang 		= $tahun_sekarang;
		$data_rka->lokasi		 		= $lokasi;
    	$data_rka->capaian_sekarang		= $capaian_sekarang;
    	$data_rka->jumlah_dana_sekarang	= $jumlah_dana_sekarang;
    	$data_rka->tahun_mendatang 		= $tahun_mendatang;
    	$data_rka->capaian_mendatang	= $capaian_mendatang;
    	$data_rka->jumlah_dana_mendatang	= $jumlah_dana_mendatang;
		$data_rka->kriteria_keberhasilan= $kriteria_keberhasilan;
		$data_rka->ukuran_keberhasilan	= $ukuran_keberhasilan;
		$data_rka->triwulan 			= $triwulan;
		$data_rka->pagu 				= $pagu;
		$data_rka->realisasi			= $realisasi;
		$data_rka->capaian_triwulan 	= $capaian_triwulan;
		$data_rka->ukuran_kinerja_triwulan = $ukuran_kinerja_triwulan;
		$data_rka->capaian_output_triwulan = $capaian_output_triwulan;
		$data_rka->keterangan			= $keterangan;

		$ret = TRUE;
		if(empty($id_rka)) {
			//insert
			$ret = $this->m_ppas_perubahan->simpan_rka($data_rka);
			//echo $this->db->last_query();
		} else {
			//update
			$$ret = $this->m_ppas_perubahan->update_rka($data_rka, $id_rka,'table_rka', 'primary_rka');
			//echo $this->db->last_query();
		}
		if ($ret === FALSE){
            $this->session->set_userdata('msg_typ','err');
            $this->session->set_userdata('msg', 'Data RKA Perubahan gagal disimpan');
		} else {
            $this->session->set_userdata('msg_typ','ok');
            $this->session->set_userdata('msg', 'Data RKA Perubahan Berhasil disimpan');
		}

		if(!empty($call_from))
			redirect($call_from);

        redirect('ppas_perubahan');
		//var_dump($cekbank);
		//print_r ($id_cek);
    }

	function load_rka()
	{
		$search = $this->input->post("search");
		$start = $this->input->post("start");
		$length = $this->input->post("length");
		$order = $this->input->post("order");

		$rka = $this->m_ppas_perubahan->get_data_table($search, $start, $length, $order["0"]);
		$alldata = $this->m_ppas_perubahan->count_data_table($search, $start, $length, $order["0"]);

		$data = array();
		$no=0;


		foreach ($rka as $row) {
			$no++;
			$data[] = array(
							$no,
							$row->kd_urusan.".".
							$row->kd_bidang.".".
                            $row->kd_program.".".
                            $row->kd_kegiatan,
                            $row->nm_urusan." / ".
                            $row->nm_bidang." / ".
                            $row->ket_program." / ".
                            $row->ket_kegiatan,
                            $row->indikator_capaian,
                            $row->tahun_sekarang,
                            $row->lokasi,
                            $row->capaian_sekarang,
                            $row->jumlah_dana_sekarang,
                            $row->tahun_mendatang,
                            $row->capaian_mendatang,
                            $row->jumlah_dana_mendatang,
							'<a href="javascript:void(0)" onclick="edit_rka('. $row->id_rka .')" class="icon2-page_white_edit" title="Edit RKA Perubahan"/>
							<a href="javascript:void(0)" onclick="delete_rka('. $row->id_rka .')" class="icon2-delete" title="Hapus RKA Perubahan"/>'
							);
		}
		$json = array("recordsTotal"=> $alldata, "recordsFiltered"=> $alldata, 'data' => $data);

        echo json_encode($json);
	}

	function edit_rka($id_rka = NULL)
	{
		//$this->output->enable_profiler(TRUE);
        $this->auth->restrict();
        $data['url_save_data'] = site_url('ppas_perubahan/save_rka');

        $data['isEdit'] = FALSE;
        if (!empty($id_rka)) {
            $data_ = array('id_rka'=>$id_rka);
            $result = $this->m_ppas_perubahan->get_data_with_rincian($id_rka,'table_rka');
			if (empty($result)) {
				$this->session->set_userdata('msg_typ','err');
				$this->session->set_userdata('msg', 'Data RKA Perubahan tidak ditemukan.');
				redirect('rka');
			}

            $data['id_rka']				= $result->id_rka;
            $data['tahun_sekarang']		= $result->tahun_sekarang;
            $data['lokasi']				= $result->lokasi;
    		$data['capaian_sekarang'] 	= $result->capaian_sekarang;
    		$data['jumlah_dana_sekarang'] 	= $result->jumlah_dana_sekarang;
    		$data['tahun_mendatang']		= $result->tahun_mendatang;
    		$data['capaian_mendatang'] 	= $result->capaian_mendatang;
    		$data['jumlah_dana_mendatang'] 	= $result->jumlah_dana_mendatang;
    		$data['kriteria_keberhasilan'] = $result->kriteria_keberhasilan;
    		$data['ukuran_keberhasilan'] = $result->ukuran_keberhasilan;
    		$data['triwulan'] = $result->triwulan;
    		$data['pagu'] = $result->pagu;
    		$data['realisasi'] = $result->realisasi;
    		$data['capaian_triwulan'] = $result->capaian_triwulan;
    		$data['ukuran_kinerja_triwulan'] = $result->ukuran_kinerja_triwulan;
    		$data['capaian_output_triwulan'] = $result->capaian_output_triwulan;
    		$data['keterangan'] = $result->keterangan;
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
    		foreach ($this->m_bidang->get_bidang($result->kd_urusan) as $row) {
    			$kd_bidang[$row->id] = $row->id .". ". $row->nama;
    		}

    		$kd_program = array("" => "");
    		foreach ($this->m_program->get_prog($result->kd_urusan,$result->kd_program) as $row) {
    			$kd_program[$row->id] = $row->id .". ". $row->nama;
    		}

    		$kd_kegiatan = array("" => "");
    		foreach ($this->m_kegiatan->get_keg($result->kd_urusan,$result->kd_program,$result->kd_kegiatan) as $row) {
    			$kd_kegiatan[$row->id] = $row->id .". ". $row->nama;
    		}

    		$data['kd_urusan'] = form_dropdown('kd_urusan', $kd_urusan, $kd_urusan_edit, 'data-placeholder="Pilih Urusan" class="common chosen-select" id="kd_urusan"');
    		$data['kd_bidang'] = form_dropdown('kd_bidang', $kd_bidang, $kd_bidang_edit, 'data-placeholder="Pilih Bidang Urusan" class="common chosen-select" id="kd_bidang"');
    		$data['kd_program'] = form_dropdown('kd_program', $kd_program, $kd_program_edit, 'data-placeholder="Pilih Program" class="common chosen-select" id="kd_program"');
    		$data['kd_kegiatan'] = form_dropdown('kd_kegiatan', $kd_kegiatan, $kd_kegiatan_edit, 'data-placeholder="Pilih Kegiatan" class="common chosen-select" id="kd_kegiatan"');

		}
        $this->template->load('template','ppas_perubahan/cru_rka', $data);
	}

	function delete_rka()
	{
        $id = $this->input->post('id');

        $result = $this->m_ppas_perubahan->delete_rka($id);
        if ($result) {
			$msg = array('success' => '1', 'msg' => 'RKA Perubahan berhasil dihapus.');
			echo json_encode($msg);
		}else{
			$msg = array('success' => '0', 'msg' => 'ERROR! RKA Perubahan gagal dihapus, mohon menghubungi administrator.');
			echo json_encode($msg);
		}
	}

	## --------------- ##
	## 	  Preview RKA  ##
	## --------------- ##
	private function cetak_skpd_func($id_skpd,$ta){
		//$proses = $this->m_renja_trx->count_jendela_kontrol($id_skpd);
		$data['rka_type'] = "PPAS PERUBAHAN";

		$protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://';
		$header = $this->m_template_cetak->get_value("GAMBAR");
		$data['logo'] = str_replace("src=\"","height=\"90px\" src=\"".$protocol.$_SERVER['HTTP_HOST'],$header);
		$data['header'] = $this->m_template_cetak->get_value("HEADER");

		/*$data2['urusan'] = $this->db->query("
			SELECT t.*,u.Nm_Urusan as nama_urusan from (
			SELECT
				pro.kd_urusan,pro.kd_bidang,pro.kd_program,pro.kd_kegiatan,
				SUM(keg.nominal) AS sum_nominal,
				SUM(keg.nominal_thndpn) AS sum_nominal_thndpn
			FROM
				(SELECT * FROM tx_rka_prog_keg_perubahan WHERE is_prog_or_keg=1) AS pro
			INNER JOIN
				(SELECT * FROM tx_rka_prog_keg_perubahan WHERE is_prog_or_keg=2) AS keg ON keg.parent=pro.id
			WHERE
				keg.id_skpd=".$id_skpd."
				AND keg.tahun= ".$ta."
			GROUP BY pro.kd_urusan
			ORDER BY kd_urusan ASC, kd_bidang ASC, kd_program ASC
			) t
			left join m_urusan u
			on t.kd_urusan = u.Kd_Urusan
		")->result();*/
		$data2['urusan'] = $this->db->query("
			SELECT t.*,u.Nm_Urusan AS nama_urusan FROM (
			SELECT pro.*,
			SUM(keg.nomrka) AS sum_nomrka,
			SUM(keg.nomrka_perubahan) AS sum_nomrka_perubahan
			FROM
				(SELECT a.`id`,
					b.`id` AS id_perubahan,
					a.`tahun`,
					a.`kd_urusan`,
					a.`kd_bidang`,
					a.`kd_program`,
					a.`kd_kegiatan`,
					a.`nama_prog_or_keg`,
					a.`nominal` AS nomrka,
					b.`nominal_thndpn` AS nomrka_perubahan,
					a.`id_skpd`,
					a.penanggung_jawab,
					a.lokasi,
					a.catatan,
					a.id_status,
					b.`penanggung_jawab` AS penanggung_jawab_perubahan,
					b.`lokasi` AS lokasi_perubahan ,
					b.`catatan` AS catatan_perubahan
				 FROM t_renja_prog_keg_perubahan a
				 LEFT JOIN t_ppas_prog_keg_perubahan b ON a.`kd_urusan`=b.`kd_urusan`
							  AND a.`kd_bidang`=b.`kd_bidang`
							  AND a.`kd_program`=b.`kd_program`
							  AND a.`kd_kegiatan`=b.`kd_kegiatan`
							  AND a.`is_prog_or_keg`=b.`is_prog_or_keg`
							  AND a.`id_skpd`=b.`id_skpd`
							  AND a.`tahun`=b.`tahun`
				 WHERE a.is_prog_or_keg=1
				 GROUP BY a.`id`) AS pro
			INNER JOIN
				(SELECT a.`id`,
					b.`id` AS id_perubahan,
					a.`id_skpd`,
					a.`tahun`,
					a.`kd_urusan`,
					a.`kd_bidang`,
					a.`kd_program`,
					a.`kd_kegiatan`,
					a.`parent`,
					a.`nominal` AS nomrka,
					b.`nominal_thndpn` AS nomrka_perubahan,
					b.`penanggung_jawab` AS penanggung_jawab_perubahan,
					b.`lokasi` AS lokasi_perubahan ,
					b.`catatan` AS catatan_perubahan
				 FROM t_renja_prog_keg_perubahan a
				 LEFT JOIN t_ppas_prog_keg_perubahan b ON a.`kd_urusan`=b.`kd_urusan`
							  AND a.`kd_bidang`=b.`kd_bidang`
							  AND a.`kd_program`=b.`kd_program`
							  AND a.`kd_kegiatan`=b.`kd_kegiatan`
							  AND a.`is_prog_or_keg`=b.`is_prog_or_keg`
							  AND a.`id_skpd`=b.`id_skpd`
							  AND a.`tahun`=b.`tahun`
				 WHERE a.is_prog_or_keg=2
				 GROUP BY a.`kd_urusan`, a.`kd_bidang`, a.`kd_program`, a.`kd_kegiatan`,a.`id`) AS keg ON keg.parent=pro.id
			WHERE
				keg.id_skpd = ".$id_skpd."
			AND keg.tahun = ".$ta."
			GROUP BY pro.kd_urusan
			ORDER BY kd_urusan ASC, kd_bidang ASC, kd_program ASC
		) t
		LEFT JOIN m_urusan AS u
		ON t.kd_urusan = u.Kd_Urusan;
		")->result();

		$data2['id_skpd'] = $id_skpd;
		$data2['ta'] = $ta;
		//$data2['program'] = $this->m_ppas_perubahan->get_program_skpd_4_cetak($id_skpd,$ta);
		$data['rka'] = $this->load->view('ppas_perubahan/cetak/program_kegiatan_preview', $data2, TRUE);
		return $data;
	}

	function preview_rka(){
		$this->auth->restrict();
		$id_skpd = $this->session->userdata('id_skpd');
		$ta = $this->session->userdata('t_anggaran_aktif');

		$skpd = $this->m_ppas_perubahan->get_one_rka_skpd($id_skpd, TRUE);
		if (!empty($skpd)) {
			$data = $this->cetak_skpd_func($id_skpd,$ta);
			$this->template->load('template', 'ppas_perubahan/preview_rka_1', $data);
		}else{
			$this->session->set_userdata('msg_typ','err');
			$this->session->set_userdata('msg', 'Data PPAS Perubahan tidak tersedia.');
			redirect('ppas_perubahan');
		}
	}



	private function cetak_func221($cetak=FALSE, $ta, $is_tahun, $idK){
		if (!$cetak) {
			$temp['class_table']='class="table-common"';
		}else{
			$temp['class_table']='class="border"';
		}

		$protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://';
		$header = $this->m_template_cetak->get_value("GAMBAR");
		$data['logo'] = str_replace("src=\"","height=\"45px\" src=\"".$protocol.$_SERVER['HTTP_HOST'],$header);
		$data['tahun1'] =$this->m_ppas_perubahan->get_rka_belanja_per_tahun221($ta, $is_tahun, $idK);

		$data['keluaran'] =$this->m_ppas_perubahan->get_indikator_keluaran($ta, $idK);


		$data['capaian'] =$this->m_ppas_perubahan->get_indikator_capaian($idK);
		$data['nominal'] =$this->m_ppas_perubahan->get_nominal_rka($idK,$ta);
	// print_r($ta);
	// 		exit();

		$result = $this->load->view("rka/cetak/cetak_form_221",$data, TRUE);

		return $result;
	}

		function cetak_kegiatan($ta,$ta_tahun,$id_kegiatan){

			// print_r($ta_tahun ." . ". $ta_tahun );
			// exit();

					$protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://';
			$header = $this->m_template_cetak->get_value("GAMBAR");
			$data['logo'] = str_replace("src=\"","height=\"70px\" src=\"".$protocol.$_SERVER['HTTP_HOST'],$header);
			$data['header'] = $this->m_template_cetak->get_value("HEADER");
			$data['qr'] = $this->ciqrcode->generateQRcode("sirenbangda", 'usulanbansos/persetujuanusulanbansos'. date("d-m-Y_H-i-s"), 1);

			$data['cetak'] = $this->cetak_func221(TRUE, $ta,$ta_tahun,$id_kegiatan);



			$html = $this->template->load('template_cetak_rka', 'renstra/cetak/cetak_view', $data, true);

			 $filename='renja '. $this->session->userdata('nama_skpd') ." ". date("d-m-Y_H-i-s") .'.pdf';



			 pdf_create($html, $filename, "A4", "Landscape", FALSE);


		}

		function preview_periode_221(){

			$data['id_kegiatan'] =  $this->input->post('id');

			$this->load->view('ppas_perubahan/periode_221',$data);
	}

	function veri_view(){
		$this->auth->restrict();
		//$this->output->enable_profiler(true);
		$data['ppas'] = $this->m_ppas_perubahan->get_all_ppas_veri();
		$data['th_anggaran'] = $this->m_settings->get_tahun_anggaran();
		$this->template->load('template','ppas_perubahan/verifikasi/view_all', $data);
	}

	function veri($kd_urusan, $kd_bidang, $id_skpd){
		$this->auth->restrict();

		$data['ppas'] = $this->m_ppas_perubahan->get_data_ppas($kd_urusan, $kd_bidang, $id_skpd);
		$data['th_anggaran'] = $this->m_settings->get_tahun_anggaran();
		$data['id_skpd'] = $id_skpd;
		$data['urusan'] = $kd_urusan;
		$data['bidang'] = $kd_bidang;
		$this->template->load('template','ppas_perubahan/verifikasi/view', $data);
	}

	function do_veri(){
		$this->auth->restrict();
		$id = $this->input->post('id');
		$action = $this->input->post('action');

		$data['renja'] = $this->m_ppas_perubahan->get_one_rka_veri($id);
		$renja = $data['renja'];

		$data['indikator'] = $this->m_ppas_perubahan->get_indikator_prog_keg($renja->id, TRUE, TRUE);
		$result = $this->m_ppas_perubahan->get_one_kegiatan(NULL, $id, TRUE);
		if (!empty($result)) {
			$data['renja'] = $result;
			$data['indikator_kegiatan'] = $this->m_ppas_perubahan->get_indikator_prog_keg($result->id, TRUE, TRUE);

			$data['tahun1'] = $this->m_ppas_perubahan->get_belanja_per_tahun($id, '1');
			$data['tahun2'] = $this->m_ppas_perubahan->get_belanja_per_tahun($id, '0');
		}else{
			echo "Data tidak ditemukan . . .";
		}

	if ($action=="pro") {
			$data['program'] = TRUE;
		}else{
			$data['program'] = FALSE;

		}

		$this->load->view('ppas_perubahan/verifikasi/veri', $data);
	}

	function save_veri(){
		$this->auth->restrict();
		$id = $this->input->post("id");
		$veri = $this->input->post("veri");
		$ket = $this->input->post("ket");

		if ($veri == "setuju") {
			$result = $this->m_ppas_perubahan->approved_renja($id);
		}elseif ($veri == "tdk_setuju") {
			$result = $this->m_ppas_perubahan->not_approved_renja($id, $ket);
		}

		if ($result) {
			$msg = array('success' => '1', 'msg' => 'Program/kegiatan berhasil diverifikasi.');
			echo json_encode($msg);
		}else{
			$msg = array('success' => '0', 'msg' => 'ERROR! Program/kegiatan gagal diverifikasi, mohon menghubungi administrator.');
			echo json_encode($msg);
		}
	}

	function disapprove_renja(){
		$this->auth->restrict();
		$data['ids'] = $this->input->post('ids');
		$data['idu'] = $this->input->post('idu');
		$data['idb'] = $this->input->post('idb');
		$this->load->view('ppas_perubahan/verifikasi/disapprove_renja', $data);
	}
}
