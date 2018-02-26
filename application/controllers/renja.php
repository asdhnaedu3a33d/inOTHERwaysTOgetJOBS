<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Renja extends CI_Controller
{
	var $CI = NULL;
	public function __construct(){
		$this->CI =& get_instance();
        parent::__construct();
        $this->load->model(array('m_renja_trx', 'm_skpd', 'm_template_cetak', 'm_lov', 'm_urusan', 'm_bidang', 'm_jenis_belanja', 'm_kategori_belanja', 'm_subkategori_belanja', 'm_kode_belanja',
		                         'm_program', 'm_kegiatan','m_settings', 'm_rpjmd_trx', 'm_prioritas_pembangunan_rkpd'));
		//$this->load->helper('date');
        if (!empty($this->session->userdata("db_aktif"))) {
            $this->load->database($this->session->userdata("db_aktif"), FALSE, TRUE);
        }
	}

	function index(){
		$this->renja_skpd();
	}

	function get_jendela_kontrol(){
		//$this->output->enable_profiler(true);
		$id_skpd = $this->session->userdata("id_skpd");
		$nama_skpd = $this->session->userdata("nama_skpd");
		$ta = $this->m_settings->get_tahun_anggaran();
		$data['jendela_kontrol'] = $this->m_renja_trx->count_jendela_kontrol($id_skpd,$ta);

		if($id_skpd > 100){
			//$id_skpd = $this->m_skpd->get_kode_unit_dari_asisten($id_skpd);
		}else {
			$kode_unit = $this->m_skpd->get_kode_unit($id_skpd);
			if ($kode_unit != $id_skpd) {
				$id_skpd = $kode_unit;
			}
		}

		$data['id_skpd'] = $id_skpd;
		$data['nama_skpd'] = $nama_skpd;
		$data['ada_renja'] = $this->m_renja_trx->get_renja($id_skpd,$ta);
		$data['ada_program'] = $this->m_renja_trx->get_all_program($this->session->userdata("id_skpd"),$ta);
		
		$data['renja'] = $this->m_renja_trx->get_one_renja_skpd($id_skpd, TRUE);
		$this->load->view('renja/jendela_kontrol', $data);
	}

	function renja_skpd(){
		$this->auth->restrict();
		//$this->output->enable_profiler(TRUE);
		$id_skpd 	= $this->session->userdata("id_skpd");

		$nama_skpd 	= $this->session->userdata("nama_skpd");
		$ta 		= $this->m_settings->get_tahun_anggaran();
		$id_tahun	= $this->m_settings->get_id_tahun();

		if (empty($id_skpd)) {
			$this->session->set_userdata('msg_typ','err');
			$this->session->set_userdata('msg', 'User tidak memiliki akses untuk pembuatan Renja, mohon menghubungi administrator.');
			redirect('home');
		}

		$data['nama_skpd']=$nama_skpd;
		$data['jendela_kontrol'] = $this->m_renja_trx->count_jendela_kontrol($id_skpd,$ta);
// print_r($data['jendela_kontrol']);
// exit;
		$id_renstra = $this->input->post('id_renstra');
		$id 		= $this->input->post('id');

		$data['id_renstra'] = $id_renstra;
		$data['id']			= $id;
		$data['program'] = $this->m_renja_trx->get_all_program($id_skpd,$ta);
		$data['id_skpd'] = $id_skpd;
		$data['ta']	= $ta;

		if($id_skpd > 100){
			$id_skpd = $this->m_skpd->get_kode_unit_dari_asisten($id_skpd);
		}
		$kode_unit = $this->m_skpd->get_kode_unit($id_skpd);

		if (empty($data['program'])) {
			if ($kode_unit != $id_skpd) {
				redirect('home/kosong/Renja');
			}
		}
		$this->template->load('template','renja/view', $data);
	}

	function get_renstra(){
		$id_skpd 	= $this->session->userdata("id_skpd");
		$ta 		= $this->m_settings->get_tahun_anggaran();
		$id_tahun	= $this->m_settings->get_id_tahun();

		$kode_unit = $this->m_skpd->get_kode_unit($id_skpd);
		
		if ($kode_unit == $id_skpd) {
			$renja = $this->m_renja_trx->insert_renja($id_skpd,$ta);
			$result 	= $this->m_renja_trx->import_from_renstra($id_skpd,$ta,$id_tahun,$renja);
		}else{
			$renja = '';
			$result 	= $this->m_renja_trx->import_from_renstra_sub_unit($id_skpd,$ta,$id_tahun,$renja);
		}

		
		if ($result) {
			$msg = array('success' => '1', 'msg' => 'Renstra berhasil diambil.');
			echo json_encode($msg);
		}else{
			$msg = array('success' => '0', 'msg' => 'ERROR! Renstra gagal diambil, mohon menghubungi administrator.');
			echo json_encode($msg);
		}

	}

	function cru_program_skpd(){
		$this->auth->restrict();
		$id = $this->input->post('id');
		$id_skpd = $this->session->userdata("id_skpd");
		$ta = $this->m_settings->get_tahun_anggaran();
		$data['skpd'] = $this->m_skpd->get_one_skpd(array('id_skpd' => $id_skpd));

		$kd_urusan_edit = NULL;
		$kd_bidang_edit = NULL;
		$kd_program_edit = NULL;
		$id_prog_rpjmd_edit = NULL;
		$id_prog_prioritas_edit = NULL;
		$data['status'] = NULL;
		if (!empty($id)) {
			$result = $this->m_renja_trx->get_one_program($id);
			if (empty($result)) {
				echo '<div style="width: 400px;">ERROR! Data tidak ditemukan.</div>';
				return FALSE;
			}
			$data['program'] = $result;
			$data['status'] = $result->id_status;
			$data['indikator_program'] = $this->m_renja_trx->get_indikator_prog_keg($id, FALSE);
			$data['revisi'] = $this->m_renja_trx->get_revisi_awal($id);
			$kd_urusan_edit = $result->kd_urusan;
			$kd_bidang_edit = $result->kd_bidang;
			$kd_program_edit = $result->kd_program;
			$id_prog_rpjmd_edit = $result->id_prog_rpjmd;
			$id_prog_prioritas_edit = $result->id_prioritas_daerah;
			// print_r($result);
			// exit;
			//$data_indikator_rpjmd = $this->m_rpjmd_trx->get_indikator_program_rpjmd_for_me($result->id_prog_rpjmd);
			$c_rpjmd = 0;
			if (!empty($result->id_prog_rpjmd)) {
				$c_rpjmd = $result->id_prog_rpjmd;
			}
			$data['indik_prog_rpjmd'] = $this->m_rpjmd_trx->get_indikator_program_rpjmd_for_me($c_rpjmd);

		}


		$satuan = array("" => "~~ Pilih Satuan ~~");
		foreach ($this->m_lov->get_list_lov(1) as $row) {
			$satuan[$row->kode_value]=$row->nama_value;
		}

		$id_prog_rpjmd = array("" => "");
		foreach ($this->m_rpjmd_trx->get_program_rpjmd_for_me($id_skpd) as $row) {
			$id_prog_rpjmd[$row->id_nya] = $row->nama_prog;
		}

		$id_prog_prioritas = array("" => "");
		foreach ($this->m_prioritas_pembangunan_rkpd->get_prog_prioritas_by_skpd($id_skpd, $ta)->result() as $row) {
			$id_prog_prioritas[$row->id] = $row->id_prog_or_keg;
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
		$data['id_prog_prioritas'] = form_dropdown('id_prioritas_daerah', $id_prog_prioritas, $id_prog_prioritas_edit, 'data-placeholder="Pilih Program Prioritas" class="common chosen-select" id="id_prioritas_daerah"');
		$data['kd_urusan'] = form_dropdown('kd_urusan', $kd_urusan, $kd_urusan_edit, 'data-placeholder="Pilih Urusan" class="common chosen-select" id="kd_urusan"');
		$data['kd_bidang'] = form_dropdown('kd_bidang', $kd_bidang, $kd_bidang_edit, 'data-placeholder="Pilih Bidang Urusan" class="common chosen-select" id="kd_bidang"');
		$data['kd_program'] = form_dropdown('kd_program', $kd_program, $kd_program_edit, 'data-placeholder="Pilih Program" class="common chosen-select" id="kd_program"');
		$this->load->view("renja/cru_program", $data);
	}

	function save_program_renja(){
		$this->auth->restrict();
		$id = $this->input->post('id_program');

		$data = $this->input->post();
		$id_skpd = $this->input->post("id_skpd");
		$tahun = $this->input->post("tahun");
		$id_indikator_program = $this->input->post("id_indikator_program");
		$indikator = $this->input->post("indikator_kinerja");
		$satuan_target = $this->input->post("satuan_target");
		$status_indikator = $this->input->post('status_target');
		$kategori_indikator = $this->input->post('kategori_target');
		$target = $this->input->post("target");
		$target_thndpn = $this->input->post("target_thndpn");

		$clean = array('id_program', 'indikator_kinerja', 'id_indikator_program', 'status_target', 'kategori_target', 'satuan_target', 'target', 'target_thndpn');
		$data = $this->global_function->clean_array($data, $clean);

		if (!empty($id)) {
			$result = $this->m_renja_trx->edit_program_skpd($data, $id, $indikator, $id_indikator_program, $satuan_target, $status_indikator, $kategori_indikator, $target, $target_thndpn);
		}else{
			$result = $this->m_renja_trx->add_program_skpd($data, $indikator, $satuan_target, $status_indikator, $kategori_indikator, $target, $target_thndpn);
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
		$result = $this->m_renja_trx->delete_program($id);
		if ($result) {
			$msg = array('success' => '1', 'msg' => 'Program berhasil dihapus.');
			echo json_encode($msg);
		}else{
			$msg = array('success' => '0', 'msg' => 'ERROR! Program gagal dihapus, mohon menghubungi administrator.');
			echo json_encode($msg);
		}
	}

	function get_kegiatan_skpd(){
		$id_skpd = $this->session->userdata("id_skpd");
		// print_r($this->session->userdata("id_skpd"));
		$ta 		= $this->m_settings->get_tahun_anggaran();
		$data['jendela_kontrol'] = $this->m_renja_trx->count_jendela_kontrol($id_skpd,$ta);

		$id			= $this->input->post('id');
		//echo $id_renstra;

		$data['id']	= $id;
		$data['kegiatan'] = $this->m_renja_trx->get_all_kegiatan($id, $id_skpd, $ta);
		// print_r($this->db->last_query().' '.$id_skpd);

		$this->load->view("renja/view_kegiatan", $data);
	}

	function cru_kegiatan_skpd(){
		$this->auth->restrict();
		//$this->output->enable_profiler(true);
		$id_program = $this->input->post('id_program');
		$id 		= $this->input->post('id');

		$id_skpd = $this->session->userdata("id_skpd");
		$data['skpd'] = $this->m_skpd->get_one_skpd(array('id_skpd' => $id_skpd));

		$kd_kegiatan_edit = NULL;
		$data['status'] = NULL;

		$cb_jenis_belanja_edit = NULL;
		$cb_kategori_belanja_edit = NULL;
		$cb_subkategori_belanja_edit = NULL;
		$cb_belanja_edit = NULL;

		if (!empty($id)) {
			$result = $this->m_renja_trx->get_one_kegiatan($id_program,$id);
			if (empty($result)) {
				echo '<div style="width: 400px;">ERROR! Data tidak ditemukan.</div>';
				return FALSE;
			}
			$data['kegiatan'] = $result;
			$data['status'] = $result->id_status;
			$data['indikator_kegiatan'] = $this->m_renja_trx->get_indikator_prog_keg($id, FALSE);
			$data['revisi'] = $this->m_renja_trx->get_revisi_awal($id);
			$data['akhir'] = $this->m_renja_trx->get_revisi_akhir($id);
			$kd_kegiatan_edit = $result->kd_kegiatan;


		}
		$data['id_program'] = $id_program;
		$kodefikasi = $this->m_renja_trx->get_info_kodefikasi_program($id_program);
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

		$status_indikator = array("" => "~~ Pilih Positif / Negatif ~~");
		foreach ($this->m_lov->get_status_indikator() as $row) {
			$status_indikator[$row->kode_status_indikator]=$row->nama_status_indikator;
		}

		$kategori_indikator = array("" => "~~ Pilih Kategori Indikator ~~");
		foreach ($this->m_lov->get_kategori_indikator() as $row) {
			$kategori_indikator[$row->kode_kategori_indikator]=$row->nama_kategori_indikator;
		}

		$kd_kegiatan = array("" => "");
		foreach ($this->m_kegiatan->get_keg($kodefikasi->kd_urusan, $kodefikasi->kd_bidang, $kodefikasi->kd_program) as $row) {
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

		$data['detil_kegiatan_1'] = $this->m_renja_trx->get_renja_belanja_per_tahun($id, '1');
		$data['detil_kegiatan_2'] = $this->m_renja_trx->get_renja_belanja_per_tahun($id, '0');
		//print_r($data['detil_kegiatan_2']);
		//exit;
		$this->load->view("renja/cru_kegiatan", $data);
	}

	function save_kegiatan(){

		$this->auth->restrict();
		$id = $this->input->post("id_kegiatan");

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
			'id_renstra' => $this->input->post('id_renstra'),
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

		// $dataKegiatan1 = array(
		// 	'tahun'=>$tahun,
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
		// 	'is_tahun_sekarang'=>1,
		// 	'id_status_renja'=>1,
		// 	'id_keg'=>$this->input->post("id_kegiatan")
		// );

		// $dataKegiatan2 = array(
		// 'tahun'=>$tahun + 1,
		// 'kode_urusan'=>$this->input->post('kd_urusan',true),
		// 'kode_bidang'=>$this->input->post('kd_bidang',true),
		// 'kode_program'=>$this->input->post('kd_program',true),
		// 'kode_kegiatan'=>$this->input->post('kd_kegiatan',true),
		// 'kode_sumber_dana'=>$this->input->post('kd_sumber_dana_2',true),
		// 'kode_jenis_belanja'=>$this->input->post('r_kd_jenis_belanja_2',true),
		// 'kode_kategori_belanja'=>$this->input->post('r_kd_kategori_belanja_2',true),
		// 'kode_sub_kategori_belanja'=>$this->input->post('r_kd_subkategori_belanja_2',true),
		// 'kode_belanja'=>$this->input->post('r_kd_belanja_2',true),
		// 'uraian_belanja'=>$this->input->post('r_uraian_2',true),
		// 'detil_uraian_belanja'=>$this->input->post('r_det_uraian_2',true),
		// 'volume'=>$this->input->post('r_volume_2',true),
		// 'satuan'=>$this->input->post('r_satuan_2',true),
		// 'nominal_satuan'=>$this->input->post('r_nominal_satuan_2',true),
		// 'subtotal'=>$this->input->post('r_subtotal_2',true),
		// 'is_tahun_sekarang'=>0,
		// 'id_status_renja'=>1,
		// 'id_keg'=>$this->input->post("id_kegiatan")
		// );


		// if ($this->input->post('nominal') > 0) {
		// 	$dataKegiatan1 = $this->global_function->re_index($dataKegiatan1);
		// }
		// if ($this->input->post('nominal_thndpn') > 0) {
		// 	$dataKegiatan2 = $this->global_function->re_index($dataKegiatan2);
		// }

		$clean = array('id_kegiatan', 'id_indikator_kegiatan', 'indikator_kinerja', 'satuan_target','target','target_thndpn');
		$data = $this->global_function->clean_array($data, $clean);
		$change = array('id_program'=>'parent');
		$data = $this->global_function->change_array($data, $change);

		if (!empty($id)) {
			$result = $this->m_renja_trx->edit_kegiatan_skpd($data, $id, $indikator, $id_indikator_kegiatan, $satuan_target, $status_indikator, $kategori_indikator, $target, $target_thndpn);
		}else{
			$result = $this->m_renja_trx->add_kegiatan_skpd($data, $indikator, $satuan_target, $status_indikator, $kategori_indikator, $target, $target_thndpn);
		}

		if ($result) {
			$msg = array('success' => '1', 'msg' => 'Kegiatan berhasil dibuat.');
			echo json_encode($msg);
		}else{
			$msg = array('success' => '0', 'msg' => 'ERROR! Kegiatan gagal dibuat, mohon menghubungi administrator.');
			echo json_encode($msg);
		}
	}

	function belanja_kegiatan_lihat($return, $id_kegiatan, $ta, $tahun, $is_tahun, $not=NULL){
		$result = $this->m_renja_trx->get_kegiatan($id_kegiatan, $ta, $is_tahun, $not);

		$i = 1;
		$total = 0;
		if ($return) {
			return $result;
		}else{
			foreach ($result as $row) {
				$vol = Formatting::currency($row->volume);
				$nom = Formatting::currency($row->nominal_satuan);
				$sub = Formatting::currency($row->subtotal);

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
					<span id='ubahrowng' class='icon-pencil' onclick='ubahrowng(".$row->id.",".$tahun.")' style='cursor:pointer' title='Ubah Belanja'></span>
				</td>
				<td>
					<span id='hapusrowng' class='icon-remove' onclick='hapusrowng(".$row->id.",".$tahun.")' style='cursor:pointer' title='Hapus Belanja'></span>
				</td>
				</tr>";
				$i++;
				$total += $row->subtotal;
			}
			if ($is_tahun == 1) {
				echo "<script type='text/javascript'>$('input[name=nominal]').autoNumeric('set', ".$total.");</script>";
			}else{
				echo "<script type='text/javascript'>$('input[name=nominal_thndpn]').autoNumeric('set', ".$total.");</script>";
			}
			
		}		
	}

	function belanja_kegiatan_save(){

		
		$is_tahun = $this->input->post('is_tahun_sekarang');
		$ta = $this->input->post('tahun');
		if ($is_tahun == 1) {
			$tahun = 1;
		}else{
			$tahun = 2;
		}

		$id_kegiatan = $this->input->post('id_keg');
		$id_belanja = $this->input->post('id_belanja');
		$data = $this->input->post();

		// $th = $this->m_settings->get_tahun_anggaran();

		$clean = array('id_belanja');
		$data = $this->global_function->clean_array($data, $clean);

		$add = array('id_status_renja'=>1, 'created_date' => date("Y-m-d H-i-s"));
		$data = $this->global_function->add_array($data, $add);
// print_r($data);
		$this->m_renja_trx->add_belanja_kegiatan($data, $id_belanja);
		
		$this->belanja_kegiatan_lihat(FALSE, $id_kegiatan, $ta, $tahun, $is_tahun);
	}

	function belanja_kegiatan_edit(){
		$tahun = $this->input->post('tahun');
		$id_kegiatan = $this->input->post('id_kegiatan');
		$id_belanja = $this->input->post('id_belanja');

		$ta = $this->input->post('ta');
		$is_tahun = $this->input->post('is_tahun');

		$data['edit'] = $this->m_renja_trx->get_one_belanja($id_belanja);
		$data['list'] = $this->belanja_kegiatan_lihat(TRUE, $id_kegiatan, $ta, $tahun, $is_tahun, $id_belanja);

		echo json_encode($data);
	}

	function belanja_kegiatan_hapus(){
		$id = $this->input->post('id_belanja');
		$id_kegiatan = $this->input->post('id_kegiatan');
		$tahun = $this->input->post('tahun');
		
		$ta = $this->input->post('ta');
		$is_tahun = $this->input->post('is_tahun');

		// $th = $this->m_settings->get_tahun_anggaran_db();

		$this->m_renja_trx->delete_one_kegiatan($id);

		$data['list'] = $this->belanja_kegiatan_lihat(TRUE, $id_kegiatan, $ta, $tahun, $is_tahun);

		echo json_encode($data);
	}

	function delete_kegiatan(){
		$this->auth->restrict();
		$id = $this->input->post('id');
		$result = $this->m_renja_trx->delete_kegiatan($id);
		if ($result) {
			$msg = array('success' => '1', 'msg' => 'Kegiatan berhasil dihapus.');
			echo json_encode($msg);
		}else{
			$msg = array('success' => '0', 'msg' => 'ERROR! Kegiatan gagal dihapus, mohon menghubungi administrator.');
			echo json_encode($msg);
		}
	}

	function preview_kegiatan_renja(){
		$id = $this->input->post("id");
		$result = $this->m_renja_trx->get_one_kegiatan(NULL, $id, TRUE);
		if (!empty($result)) {
			$data['renja'] = $result;
			$data['indikator_kegiatan'] = $this->m_renja_trx->get_indikator_prog_keg($result->id, TRUE, FALSE);
			$data['tahun1'] = $this->m_renja_trx->get_renja_belanja_per_tahun($id, '1');
			$data['tahun2'] = $this->m_renja_trx->get_renja_belanja_per_tahun($id, '0');

			$this->load->view('renja/preview', $data);
		}else{
			echo "Data tidak ditemukan . . .";
		}
	}

	function kirim_renja(){
		$this->auth->restrict();
		$data['skpd'] = $this->session->userdata("id_skpd");
		$this->load->view('renja/kirim_renja', $data);
	}

	function do_kirim_renja(){
		$this->auth->restrict();
		$id = $this->input->post('skpd');
		$ta = $this->m_settings->get_tahun_anggaran();
		$result = $this->m_renja_trx->kirim_renja($id,$ta);
		//echo $this->db->last_query();
		if ($result) {
			$msg = array('success' => '1', 'msg' => 'renja berhasil dikirim.');
			echo json_encode($msg);
		}else{
			$msg = array('success' => '0', 'msg' => 'ERROR! Renja gagal dikirim, mohon menghubungi administrator.');
			echo json_encode($msg);
		}
	}

	## --------------------------------------- ##
	## Verifikasi renja yang telah di kirim  ##
	## --------------------------------------- ##
	function preview_revisi(){
		$id = $this->input->post("id_renja");
		$id_skpd = $this->session->userdata('id_skpd');
		$ta = $this->m_settings->get_tahun_anggaran();
		$revisi = $this->input->post("id_revisi");
		//echo $id.$revisi.$id_skpd;

		if ($revisi == "revisi_ranwal") {
			$data['revisi'] = $this->m_renja_trx->get_revisi_renja_ranwal($id_skpd,$ta);
			$this->load->view('renja/revisi_ranwal', $data);
		}elseif ($revisi == "revisi_akhir") {
			$data['revisi'] = $this->m_renja_trx->get_revisi_renja_akhir($id_skpd,$ta);
			$this->load->view('renja/revisi_renja_akhir', $data);
		}elseif ($revisi == "revisi_rpjm") {
			$data['revisi'] = $this->m_renja_trx->get_revisi_rpjm($id);
			$this->load->view('renja/revisi_rpjm', $data);
		}
		//echo $this->db->last_query();
	}

	## --------------------------------------------- ##
	## Verifikasi renja Akhir yang telah di kirim  ##
	## --------------------------------------------- ##



	private function cetak_skpd_func($id_skpd,$ta){
		//$proses = $this->m_renja_trx->count_jendela_kontrol($id_skpd);
		$data['renja_type'] = "RENJA";
		$skpd_detail = $this->m_skpd->get_one_skpd(array('id_skpd' => $id_skpd));
		$data['skpd'] = $skpd_detail;
		$data['ta'] = $ta;

		$protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://';
		//$header = $this->m_template_cetak->get_value("GAMBAR");
		//$data['logo'] = str_replace("src=\"","height=\"90px\" src=\"".$protocol.$_SERVER['HTTP_HOST'],$header);
		//$data['header'] = $this->m_template_cetak->get_value("HEADER");
		//$skpd_detail = $this->m_skpd->get_one_skpd(array('id_skpd' => $id_skpd));
		//$data['header'] = "<p>". strtoupper($skpd_detail->nama_skpd) ."<BR>KABUPATEN KLUNGKUNG, PROVINSI BALI - INDONESIA<BR>".$skpd_detail->alamat."<BR>Telp.".$skpd_detail->telp_skpd."<p>";
		$data['logo'] = "";
		$data['header'] = "";

		$data2['program'] = $this->m_renja_trx->get_program_skpd_4_cetak($id_skpd,$ta);
		$data['renja'] = $this->load->view('renja/cetak/program_kegiatan', $data2, TRUE);
		return $data;
	}

	function do_cetak_renja($id_skpd=NULL){
		// ini_set('memory_limit', '-1');

		$this->auth->restrict();
		if (empty($id_skpd)) {
			$id_skpd = $this->session->userdata('id_skpd');
			$ta = $this->session->userdata('t_anggaran_aktif');
		}

		if ($id_skpd == "all") {
			$all_skpd = $this->m_renja_trx->get_all_skpd();
			$html="";
			foreach ($all_skpd as $row) {
				$data = $this->cetak_skpd_func_new($row->id_skpd,$ta);
				$html .= '<div style="page-break-after: always;">'.$this->load->view('renja/cetak/cetak', $data, true).'</div>';
			}
			$data['contents'] = $html;
			$data['qr'] = $this->ciqrcode->generateQRcode("sirenbangda", 'Renja Semua '. date("d-m-Y_H-i-s"), 1);
			$html = $this->load->view('template_cetak', $data, true);

			$filename='renja '. $this->session->userdata('nama_skpd') ." ". date("d-m-Y_H-i-s") .'.pdf';
		    pdf_create($html, $filename, "A4", "Landscape", FALSE);
		}else{
			$data = $this->cetak_skpd_func_new($id_skpd,$ta);
			$data['qr'] = $this->ciqrcode->generateQRcode("sirenbangda", 'Renja '. $this->session->userdata('nama_skpd') ." ". date("d-m-Y_H-i-s"), 1);
			$data['tanpa_footer'] = true;
			$html = $this->template->load('template_cetak', 'renja/cetak/cetak', $data, true);
	        $filename='renja '. $this->session->userdata('nama_skpd') ." ". date("d-m-Y_H-i-s") .'.pdf';
			$this->create_pdf->load($html, $filename, 'A4-L','');

		    // pdf_create($html, $filename, "A4", "Landscape", FALSE);
		}


	}

	function cetak_renja_skpd_all($id_skpd="all"){
		$this->auth->restrict();

		$all_skpd = $this->m_skpd->get_data_dropdown_skpd(NULL, TRUE);
		$data['dd_skpd'] = form_dropdown('ss_skpd', $all_skpd, $id_skpd, 'id="ss_skpd"');
		$data['id'] = $id_skpd;

		$data['total_nominal_renja'] = $this->m_renja_trx->get_total_nominal_renja($id_skpd);
		$this->template->load('template','renja/cetak/view', $data);
	}

	## ------------------------ ##
	## Revisi RPJM dan Renja  ##
	## ------------------------ ##
	function revisi_rpjm(){
		$this->auth->restrict();

		$data['bidang_urusan'] = $this->m_renja_trx->get_all_renja_revisi_rpjm();
		$this->template->load('template','renja/revisi_rpjm/view_all', $data);
	}

	function revisi_rpjm_akhir($urusan, $bidang){
		$this->auth->restrict();
		$data['bidang_urusan'] = $this->m_renja_trx->get_bidang_urusan_revisi_rpjm($urusan, $bidang);
		$this->template->load('template','renja/revisi_rpjm/view_revisi_all', $data);
	}

	function revisi_rpjm_view(){
		$id = $this->input->post("id");
		$result = $this->m_renja_trx->get_one_bidang_urusan_skpd_program_revisi_rpjm($id);
		if (!empty($result)) {
			$data['renja'] = $result;
			$this->load->view('renja/revisi_rpjm/revisi', $data);
		}else{
			echo '<div style="color: red; padding: 20px; text-align: center; width: 500px;">Program ini sedang di revisi.<div>';
		}
	}

	function save_revisi_rpjm(){
		$this->auth->restrict();
		$id = $this->input->post("id");

		$data = $this->input->post();
		$add = array('is_revisi_rpjm' => '1');
		$data = $this->global_function->add_array($data, $add);
		$change = array('id'=>'id_prog_keg');
		$data = $this->global_function->change_array($data, $change);

		$result = $this->m_renja_trx->revisi_rpjm($id, $data);

		if ($result) {
			$msg = array('success' => '1', 'msg' => 'Renja berhasil diverifikasi.');
			echo json_encode($msg);
		}else{
			$msg = array('success' => '0', 'msg' => 'ERROR! Renja gagal diverifikasi, mohon menghubungi administrator.');
			echo json_encode($msg);
		}
	}

	function revisi_rpjm_kegiatan(){
		$id_renja = $this->input->post('id_renja');
		$id_sasaran = $this->input->post('id_sasaran');
		$id_program = $this->input->post('id_program');
		$id_kegiatan = $this->input->post('id_kegiatan');

		$id_skpd = $this->session->userdata("id_skpd");
		$data['skpd'] = $this->m_skpd->get_one_skpd(array('id_skpd' => $id_skpd));

		$result = $this->m_renja_trx->get_one_kegiatan($id_renja, $id_sasaran, $id_program, $id_kegiatan, TRUE);
		if (empty($result)) {
			echo '<div style="width: 400px;">ERROR! Data Kegiatan tidak ditemukan.</div>';
			return FALSE;
		}
		$data['kegiatan'] = $result;
		$revisi_rpjmd = $this->m_renja_trx->revisi_rpjmd($result->parent);

		$data['revisi_rpjmd'] = $revisi_rpjmd;
		$data['nominal_banding'] = $this->m_renja_trx->cek_nominal_banding_dengan_rpjmd($id_kegiatan, $revisi_rpjmd->kd_urusan, $revisi_rpjmd->kd_bidang, $revisi_rpjmd->kd_program);
		$data['id_renja'] = $id_renja;
		$data['id_sasaran'] = $id_sasaran;
		$data['id_program'] = $id_program;

		$this->load->view("renja/revisi_rpjm/revisi_kegiatan", $data);
	}
	#########################################################################################################################################

	## --------------------------------------------- ##
	## Tambah, Edit, Delete View Renja 				 ##
	## --------------------------------------------- ##
	function view(){
		$this->auth->restrict();
    	$this->template->load('template','renja/view');
	}

	function get_data(){
		$search = $this->input->post("search");
		$start = $this->input->post("start");
		$length = $this->input->post("length");
		$order = $this->input->post("order");
		$id_skpd = $this->session->userdata("id_skpd");

		$order_arr = array('id','', 'tujuan','sasaran','indikator_sasaran', '','nm_urusan','nm_bidang','ket_program','ket_kegiatan', 'status_renja');
		$renja = $this->m_renja_trx->get_all_renja($search, $start, $length, $order["0"], $id_skpd, $order_arr);
		$alldata = $this->m_renja_trx->count_all_renja($search, $id_skpd);

		$data = array();
		$no=0;

		foreach ($renja as $row) {
			$no++;
			$preview_action = '<a href="javascript:void(0)" onclick="preview_modal('. $row->id .')" class="icon-search" title="Lihat Renja"/>';
			$edit_action = '<a href="javascript:void(0)" onclick="edit_renja('. $row->id .')" class="icon2-page_white_edit" title="Edit Renja"/>';
			$delete_action = '<a href="javascript:void(0)" onclick="delete_renja('. $row->id .')" class="icon2-delete" title="Hapus Renja"/>';
			$history_action = '<a href="javascript:void(0)" onclick="preview_history('. $row->id .')" title="Preview History">'. $row->status_renja .'</a>';

			if ($row->id_status == 1 || $row->id_status == 3) {
				//Baru dan Revisi
				$action = 	$preview_action.
							$edit_action.
							$delete_action;
			}else{
				$action = $preview_action;
			}

			$data[] = array(
							$no,
							$action,
							$row->tujuan,
							$row->sasaran,
							$row->indikator_sasaran,
							$row->kd_urusan.". ".$row->kd_bidang.". ".$row->kd_program.". ".$row->kd_kegiatan,
							$row->nm_urusan,
							$row->nm_bidang,
							$row->ket_program,
							$row->ket_kegiatan,
							$history_action
							);
		}
		$json = array("recordsTotal"=> $alldata, "recordsFiltered"=> $alldata, 'data' => $data);
		echo json_encode($json);
	}

	function cru($id_renja=NULL){
		$this->auth->restrict();

		$id_skpd = array('id_skpd' => $this->session->userdata('id_skpd'));
		$skpd = $this->m_skpd->get_skpd_detail($id_skpd);
		$data['skpd'] = $skpd->row();

		if (!empty($id_renja)) {
			$id_skpd = $this->session->userdata('id_skpd');
			$result = $this->m_renja_trx->get_one_renja($id_renja, $id_skpd);
			if (empty($result)) {
				$this->session->set_userdata('msg_typ','err');
				$this->session->set_userdata('msg', 'Data Renja tidak ditemukan.');
				redirect('renja/view');
			}
			$data['renja'] = $result;
		}

    	$this->template->load('template','renja/create', $data);
	}

	function delete(){
		$this->auth->restrict_ajax_login();

		$id = $this->input->post("id");
		$id_skpd = $this->session->userdata('id_skpd');
		$result = $this->m_renja_trx->delete($id, $id_skpd);
		if ($result) {
			$msg = array('success' => '1', 'msg' => 'Data Renja berhasil dihapus.');
			echo json_encode($msg);
		}else{
			$msg = array('success' => '0', 'msg' => 'FAILED! Data Renja gagal dihapus, terjadi kesalahan pada sistem.');
			echo json_encode($msg);
		}
	}

	function save(){
		$this->auth->restrict();
		$id_skpd = array('id_skpd' => $this->session->userdata('id_skpd'));
		$skpd = $this->m_skpd->get_skpd_detail($id_skpd);
		$skpd = $skpd->row();

		$id = $this->input->post('id_renja');

		$data = $this->input->post();
		$change = array('Kd_Urusan_autocomplete'=>'nm_urusan', 'Kd_Bidang_autocomplete'=>'nm_bidang', 'Kd_Prog_autocomplete'=>'ket_program', 'Kd_Keg_autocomplete'=>'ket_kegiatan');
		$add = array('id_skpd'=> $skpd->id_skpd, 'id_bidkoor' => $skpd->id_bidkoor);
		$clean = array('id_renja');
		$data = $this->global_function->change_array($data, $change);
		$data = $this->global_function->add_array($data, $add);
		$data = $this->global_function->clean_array($data, $clean);

		if (!empty($id)) {
			$result = $this->m_renja_trx->edit($data, $id);
		}else{
			$result = $this->m_renja_trx->add($data);
		}

		if ($result) {
			$this->session->set_userdata('msg_typ','ok');
			$this->session->set_userdata('msg', 'renja berhasil disimpan.');
			redirect('renja/view');
		}else{
			$this->session->set_userdata('msg_typ','err');
			$this->session->set_userdata('msg', 'ERROR! renja gagal disimpan, mohon menghubungi administrator.');
			redirect('renja/view');
		}
	}

	function preview_modal(){
		$id_renja = $this->input->post("id");

		$result = $this->m_renja_trx->get_one_renja_detail($id_renja);
		if (!empty($result)) {
			$data['renja'] = $result;
			$this->load->view('renja/preview', $data);
		}
	}

	function preview_history(){
		$id_renja = $this->input->post("id");
		$result = $this->m_renja_trx->get_all_histories_renja($id_renja);
		if (!empty($result)) {
			$data['renja'] = $result;
			$this->load->view('renja/preview_history', $data);
		}
	}

	## -------------------------------------- ##
	## Pengiriman renja untuk Di Verifikasi ##
	## -------------------------------------- ##
	function send(){
		$this->auth->restrict();
		$id_skpd = $this->session->userdata('id_skpd');
		$data['json_id'] = $this->m_renja_trx->get_all_id_renja_veri_or_approved_to_json($id_skpd);
		$this->template->load('template','renja/send/send', $data);
	}

	function get_data_send(){
		$id = $this->input->post("id");

		$id_skpd = array('id_skpd' => $this->session->userdata('id_skpd'));
		$skpd = $this->m_skpd->get_skpd_detail($id_skpd);
		$data['skpd'] = $skpd->row();

		$renja = $this->m_renja_trx->get_all_renja_by_in($id, TRUE);
		$data['jml_data'] = $renja->num_rows();
		$data['renja'] = $renja->result();
		$data['total_nominal_renja'] = $this->m_renja_trx->get_total_nominal_renja_by_in($id);
		$this->load->view('renja/send/view', $data);
	}

	function pilih_renja(){
		$this->load->view('renja/send/pilih');
	}

	function get_data_pilih_renja(){
		$search = $this->input->post("search");
		$start = $this->input->post("start");
		$length = $this->input->post("length");
		$order = $this->input->post("order");
		$renjas = $this->input->post("renjas");

		$id_skpd = $this->session->userdata("id_skpd");

		$order_arr = array('id','tujuan','sasaran','indikator_sasaran', '','nm_urusan','nm_bidang','ket_program','ket_kegiatan', 'status_renja');
		$renja = $this->m_renja_trx->get_all_renja($search, $start, $length, $order["0"], $id_skpd, $order_arr, "BARU");
		$alldata = $this->m_renja_trx->count_all_renja($search, $id_skpd, "BARU");

		$data = array();
		$no=0;
		foreach ($renja as $row) {
			$no++;
			$checked = (!empty($renjas) && in_array($row->id, $renjas))?"checked":"";
			$data[] = array(
							$no,
							$row->tujuan,
							$row->sasaran,
							$row->indikator_sasaran,
							$row->kd_urusan.". ".$row->kd_bidang.". ".$row->kd_program.". ".$row->kd_kegiatan,
							$row->nm_urusan,
							$row->nm_bidang,
							$row->ket_program,
							$row->ket_kegiatan,
							$row->status_renja,
							'<input type="checkbox" class="pilih_renja" title="Pilih Renja" value="'. $row->id .'" '. $checked .'/>'
							);
		}
		$json = array("recordsTotal"=> $alldata, "recordsFiltered"=> $alldata, 'data' => $data);
		echo json_encode($json);
	}

	function save_sended_renja(){
		$this->auth->restrict();
		$id = $this->input->post("id");
		$id_skpd = $this->session->userdata("id_skpd");
		$result = $this->m_renja_trx->send_renja($id, $id_skpd);

		if ($result) {
			$msg = array('success' => '1', 'msg' => 'Data renja berhasil dikirim.');
			echo json_encode($msg);
		}else{
			$msg = array('success' => '0', 'msg' => 'FAILED! Data renja gagal dikirm, terjadi kesalahan pada sistem.');
			echo json_encode($msg);
		}
	}

	function delete_sended_renja(){
		$this->auth->restrict();
		$id = $this->input->post("id");
		$id_skpd = $this->session->userdata("id_skpd");
		$result = $this->m_renja_trx->delete_sended_renja($id, $id_skpd);

		if ($result) {
			$msg = array('success' => '1', 'msg' => 'Data renja berhasil dihapus.');
			echo json_encode($msg);
		}else{
			$msg = array('success' => '0', 'msg' => 'FAILED! Data renja gagal dihapus, terjadi kesalahan pada sistem.');
			echo json_encode($msg);
		}
	}

	private function cetak_func($data=NULL, $id_skpd=NULL, $page=1, $site_url=NULL, $search_skpd=FALSE){
		$data_per_page = 25;
		$total = $this->m_renja_trx->count_all_renja(NULL, $id_skpd, "APPROVED");
		$total_page = round(($total/$data_per_page), 0, PHP_ROUND_HALF_UP);

		if ($page > $total_page || $page < 1) {
			$page = 1;
		}

		$start = $page-1;

		$data['first_page'] = 1;
		$data['page'] = $page;
		$data['last_page'] = $total_page;
		$data['site_url'] = $site_url;
		$data['search_skpd'] = $search_skpd;
		$data['renja'] = $this->m_renja_trx->get_all_renja(NULL, $start, $data_per_page, NULL, $id_skpd, NULL, "APPROVED", TRUE);
		$data['total_nominal_renja'] = $this->m_renja_trx->get_total_nominal_renja($id_skpd, "APPROVED");
		$this->template->load('template','renja/cetak/view', $data);
	}

	function cetak_renja($page=1){
		$this->auth->restrict();

		$id_skpd = array('id_skpd' => $this->session->userdata('id_skpd'));
		$skpd = $this->m_skpd->get_skpd_detail($id_skpd);
		$data['skpd'] = $skpd->row();

		$id_skpd = $this->session->userdata('id_skpd');
		$this->cetak_func($data, $id_skpd, $page, "renja/cetak_renja");
	}

	function cetak_renja_all($page=1, $id_skpd="all"){
		$this->auth->restrict();

		$all_skpd = $this->m_skpd->get_data_dropdown_skpd(NULL, TRUE);
		$data['dd_skpd'] = form_dropdown('ss_skpd', $all_skpd, $id_skpd, 'id="ss_skpd"');
		$data['id'] = $id_skpd;

		$this->cetak_func($data, $id_skpd, $page, "renja/cetak_renja_all", TRUE);
	}

	function do_cetak($id_skpd=NULL){
		$this->auth->restrict();
		if (empty($id_skpd)) {
			$id_skpd = $this->session->userdata('id_skpd');
		}

		$protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://';
		$header = $this->m_template_cetak->get_value("GAMBAR");
		$data['logo'] = str_replace("src=\"","height=\"90px\" src=\"".$protocol.$_SERVER['HTTP_HOST'],$header);
		$data['header'] = $this->m_template_cetak->get_value("HEADER");

		$data['renja'] = $this->m_renja_trx->get_all_renja(NULL, NULL, NULL, NULL, $id_skpd, NULL, "APPROVED", TRUE);
		$html = $this->template->load('template_cetak', 'renja/cetak/cetak', $data, true);
        $filename='renja '. $this->session->userdata('nama_skpd') ." ". date("d-m-Y_H-i-s") .'.pdf';
	    pdf_create($html, $filename, "A4", "Landscape");
	}

	########## Verifikasi Awal ##########
	function veri_view(){
		$this->auth->restrict();
		//$this->output->enable_profiler(true);
		$data['renjas'] = $this->m_renja_trx->get_all_renja_veri();
		$this->template->load('template','renja/verifikasi/view_all', $data);
	}

	function veri($id_skpd){
		$this->auth->restrict();

		$data['renjas'] = $this->m_renja_trx->get_data_renja($id_skpd);
		$data['id_skpd'] = $id_skpd;
		$this->template->load('template','renja/verifikasi/view', $data);
	}

	function do_veri(){
		$this->auth->restrict();
		$id = $this->input->post('id');
		$action = $this->input->post('action');

		$data['renja'] = $this->m_renja_trx->get_one_renja_veri($id);
		$renja = $data['renja'];

		$data['indikator'] = $this->m_renja_trx->get_indikator_prog_keg($renja->id, TRUE, TRUE);
		$result = $this->m_renja_trx->get_one_kegiatan(NULL, $id, TRUE);
		if (!empty($result)) {
			$data['renja'] = $result;
			$data['indikator_kegiatan'] = $this->m_renja_trx->get_indikator_prog_keg($result->id, TRUE, TRUE);

			$data['tahun1'] = $this->m_renja_trx->get_renja_belanja_per_tahun($id, '1');
			$data['tahun2'] = $this->m_renja_trx->get_renja_belanja_per_tahun($id, '0');
		}else{
			echo "Data tidak ditemukan . . .";
		}

	if ($action=="pro") {
			$data['program'] = TRUE;
		}else{
			$data['program'] = FALSE;

		}

		$this->load->view('renja/verifikasi/veri', $data);
	}

	function save_veri(){
		$this->auth->restrict();
		$id = $this->input->post("id");
		$veri = $this->input->post("veri");
		$ket = $this->input->post("ket");

		if ($veri == "setuju") {
			$result = $this->m_renja_trx->approved_renja($id);
		}elseif ($veri == "tdk_setuju") {
			$result = $this->m_renja_trx->not_approved_renja($id, $ket);
		}

		if ($result) {
			$msg = array('success' => '1', 'msg' => 'Kegiatan berhasil diverifikasi.');
			echo json_encode($msg);
		}else{
			$msg = array('success' => '0', 'msg' => 'ERROR! Kegiatan gagal diverifikasi, mohon menghubungi administrator.');
			echo json_encode($msg);
		}
	}

	function disapprove_renja(){
		$this->auth->restrict();
		$data['id'] = $this->input->post('id');
		$this->load->view('renja/verifikasi/disapprove_renja', $data);
	}

	function do_disapprove_renja(){
		$this->auth->restrict();
		$this->auth->restrict_ajax_login();

		$id = $this->input->post('id');
		$ket = $this->input->post('ket');
		$result = $this->m_renja_trx->disapprove_renja($id, $ket);
		echo json_encode(array('success' => '1', 'msg' => 'Renja telah ditolak.', 'href' => site_url('renja/veri_view')));
	}

	########## Verifikasi Akhir ##########
	function veri_view_akhir_all(){
		$this->auth->restrict();
		$data['renjas'] = $this->m_renja_trx->get_all_renja_veri_akhir();
		$this->template->load('template','renja/verifikasi_akhir/view_all', $data);
	}

	function veri_view_akhir($id_skpd){
		$this->auth->restrict();

		//$data['bidang_urusan'] = $this->m_renja_trx->get_bidang_urusan_veri_akhir($urusan, $bidang);
		$data['renjas'] = $this->m_renja_trx->get_data_renja_akhir($id_skpd);
		$this->template->load('template','renja/verifikasi_akhir/view_veri_all', $data);
	}

	function veri_view_tdk_setuju(){
		$this->auth->restrict();
		$id = $this->input->post("id");
		$result = $this->m_renja_trx->get_one_kegiatan(NULL, $id);
		$data['renja'] = $result;
		$data['indikator_program'] = $this->m_renja_trx->get_indikator_prog_keg($result->id, TRUE, TRUE);
		$this->load->view('renja/verifikasi_akhir/veri', $data);
	}

	function save_veri_akhir(){
		$this->auth->restrict();
		$id = $this->input->post("id");
		$veri = $this->input->post("veri");

		$data = $this->input->post();
		$clean = array('veri');
		$data = $this->global_function->clean_array($data, $clean);
		$change = array('id'=>'id_prog_keg');
		$data = $this->global_function->change_array($data, $change);

		if ($veri == "setuju") {
			$result = $this->m_renja_trx->approved_veri_akhir_renja($id);
		}elseif ($veri == "tdk_setuju") {
			$result = $this->m_renja_trx->not_approved_veri_akhir_renja($id, $data);
		}

		if ($result) {
			$msg = array('success' => '1', 'msg' => 'Renja berhasil diverifikasi.');
			echo json_encode($msg);
		}else{
			$msg = array('success' => '0', 'msg' => 'ERROR! Renja gagal diverifikasi, mohon menghubungi administrator.');
			echo json_encode($msg);
		}
	}

	## --------------- ##
	## Preview Renja ##
	## --------------- ##
	function preview_renja(){
		$this->auth->restrict();
		$id_skpd = $this->session->userdata('id_skpd');
		$id_skpd_sub = $this->session->userdata('id_skpd');
		$ta = $this->session->userdata('t_anggaran_aktif');
		if($id_skpd > 100){
			//$id_skpd = $this->m_skpd->get_kode_unit_dari_asisten($id_skpd);
		}else {
			$kode_unit = $this->m_skpd->get_kode_unit($id_skpd);
			if ($kode_unit != $id_skpd) {
				$id_skpd = $kode_unit;
			}
		}
		$skpd = $this->m_renja_trx->get_one_renja_skpd($id_skpd, TRUE);
		if (!empty($skpd)) {

			$data = $this->cetak_skpd_func_new($id_skpd_sub,$ta);
			$this->template->load('template', 'renja/preview_renja', $data);
		}else{
			$this->session->set_userdata('msg_typ','err');
			$this->session->set_userdata('msg', 'Data renstra tidak tersedia.');
			redirect('home');
		}
	}

	private function cetak_skpd_func_new($id_skpd,$ta){
		//$proses = $this->m_renja_trx->count_jendela_kontrol($id_skpd);
		$data['renja_type'] = "RENJA";

		//$protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://';
//		$header = $this->m_template_cetak->get_value("GAMBAR");
//		$data['logo'] = str_replace("src=\"","height=\"90px\" src=\"".$protocol.$_SERVER['HTTP_HOST'],$header);
		$skpd_detail = $this->m_skpd->get_one_skpd(array('id_skpd' => $id_skpd));
//		$data['header'] = "<p>". strtoupper($skpd_detail->nama_skpd) ."<BR>KABUPATEN KLUNGKUNG, PROVINSI BALI - INDONESIA<BR>".$skpd_detail->alamat."<BR>Telp.".$skpd_detail->telp_skpd."<p>";

		$data2['program'] = $this->m_renja_trx->get_program_skpd_4_cetak($id_skpd,$ta);

		// $data2['urusan'] = $this->db->query("
		// select t.kd_urusan,u.Nm_Urusan as nama_urusan from (
		// 	select kd_urusan
		// 	from t_renja_prog_keg
		// 	where is_prog_or_keg = 1 and id_skpd = ".$id_skpd." and tahun = ".$ta."
		// 	group by kd_urusan) t
		// 	left join m_urusan u
		// 	on t.kd_urusan = u.Kd_Urusan
		// ")->result();

		$data2['urusan'] = $this->db->query("
			select t.*,u.Nm_Urusan as nama_urusan from (
			SELECT
				pro.kd_urusan,pro.kd_bidang,pro.kd_program,pro.kd_kegiatan,
				SUM(keg.nominal) AS sum_nominal,
				SUM(keg.nominal_thndpn) AS sum_nominal_thndpn
			FROM
				(SELECT * FROM t_renja_prog_keg WHERE is_prog_or_keg=1 AND id_skpd > 0) AS pro
			INNER JOIN
				(SELECT * FROM t_renja_prog_keg WHERE is_prog_or_keg=2 AND id_skpd > 0) AS keg ON keg.parent=pro.id
			WHERE
				keg.id_skpd=".$id_skpd."
				AND keg.tahun= ".$ta."
				AND pro.id IN (SELECT id_prog_keg FROM t_renja_indikator_prog_keg WHERE id_prog_keg = pro.id AND (target > 0))
			GROUP BY pro.kd_urusan
			ORDER BY kd_urusan ASC, kd_bidang ASC, kd_program ASC
			) t
			left join m_urusan u
			on t.kd_urusan = u.Kd_Urusan
		")->result();

		$data2['id_skpd'] = $id_skpd;
		$data2['ta'] = $ta;
		$data['skpd'] = $this->m_skpd->get_one_skpd(array('id_skpd' => $id_skpd));
		$data['renja'] = $this->load->view('renja/cetak/program_kegiatan_new', $data2, TRUE);
		return $data;
	}

	#################################FUNGSI READONLY###############################################
	function veri_view_readonly(){
		$this->auth->restrict();
		//$this->output->enable_profiler(true);
		$data['renjas'] = $this->m_renja_trx->get_all_renja_veri_readonly();
		$this->template->load('template','renja/verifikasi/view_all_readonly', $data);
	}

	function veri_readonly($id_skpd){
		$this->auth->restrict();

		$data['renjas'] = $this->m_renja_trx->get_data_renja_readonly($id_skpd);
		$data['id_skpd'] = $id_skpd;
		$this->template->load('template','renja/verifikasi/view_readonly', $data);
	}

	function veri_view_akhir_all_readonly(){
		$this->auth->restrict();
		$data['renjas'] = $this->m_renja_trx->get_all_renja_veri_akhir_readonly();
		$this->template->load('template','renja/verifikasi_akhir/view_all_readonly', $data);
	}

	function veri_view_akhir_readonly($id_skpd){
		$this->auth->restrict();

		//$data['bidang_urusan'] = $this->m_renja_trx->get_bidang_urusan_veri_akhir($urusan, $bidang);
		$data['renjas'] = $this->m_renja_trx->get_data_renja_akhir_readonly($id_skpd);
		$this->template->load('template','renja/verifikasi_akhir/view_veri_all_readonly', $data);
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
		$data['tahun1'] =$this->m_renja_trx->get_renja_belanja_per_tahun221($ta, $is_tahun, $idK);

		$data['keluaran'] =$this->m_renja_trx->get_indikator_keluaran($ta, $idK);

		$data['capaian'] =$this->m_renja_trx->get_indikator_capaian($idK);
		$data['nominal'] =$this->m_renja_trx->get_nominal_renja($idK,$is_tahun);

		$data['ta_ng'] = $ta;
		$data['idk_ng'] = $idK;

		$result = $this->load->view("renja/cetak/cetak_form_221",$data, TRUE);


		return $result;
	}

	function cetak_kegiatan($ta, $is_tahun, $idK){
		$protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://';
		$header = $this->m_template_cetak->get_value("GAMBAR");
		$data['logo'] = str_replace("src=\"","height=\"70px\" src=\"".$protocol.$_SERVER['HTTP_HOST'],$header);
		$data['header'] = $this->m_template_cetak->get_value("HEADER");
		$data['qr'] = $this->ciqrcode->generateQRcode("sirenbangda", 'usulanbansos/persetujuanusulanbansos'. date("d-m-Y_H-i-s"), 1);

		$data['cetak'] = $this->cetak_func221(TRUE, $ta, $is_tahun, $idK);

		$html = $this->template->load('template_cetak_rka', 'renstra/cetak/cetak_view', $data, true);

		$filename='renja '. $this->session->userdata('nama_skpd') ." ". date("d-m-Y_H-i-s") .'.pdf';

		// print_r($html);
		// exit();

		pdf_create($html, $filename, "A4", "Landscape", FALSE);

	}

	function preview_periode_221(){
		$data['id_keg'] = $this->input->post('id');
		$this->load->view('renja/periode_221', $data);
	}


}
