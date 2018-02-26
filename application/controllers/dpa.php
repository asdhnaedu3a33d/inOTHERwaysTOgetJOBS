<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dpa extends CI_Controller
{
	var $CI = NULL;
	public function __construct()
	{
		$this->CI =& get_instance();
        parent::__construct();
        //$this->load->helper(array('form','url', 'text_helper','date'));
        $this->load->database();
        //$this->load->model('m_musrenbang','',TRUE);
        $this->load->model(array('m_dpa','m_skpd','m_lov','m_urusan', 'm_bidang', 'm_program', 'm_kegiatan', 'm_jenis_belanja', 'm_kategori_belanja', 'm_subkategori_belanja', 'm_kode_belanja',
															'm_template_cetak', 'm_kendali', 'm_rpjmd_trx'));
        if (!empty($this->session->userdata("db_aktif"))) {
            $this->load->database($this->session->userdata("db_aktif"), FALSE, TRUE);
        }
	}

	/*function index()
	{
        $this->output->enable_profiler(TRUE);
		$this->auth->restrict();
		$data['url_delete_data'] = site_url('rka/delete_rka');
		$this->template->load('template','rka/rka_view',$data);
	}*/
	function index(){
		$this->dpa_skpd();
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
		$data['dpa'] = $this->m_dpa->get_dpa($id_skpd,$ta);
		//$data['renja'] = $this->m_renja_trx->get_one_renja_skpd($id_skpd, TRUE);
		//$data['jendela_kontrol'] = $this->m_renja_trx->count_jendela_kontrol($id_skpd);
		$data['jendela_kontrol'] = $this->m_dpa->count_jendela_kontrol($id_skpd,$ta);
		$this->load->view('dpa/jendela_kontrol', $data);
	}

	function dpa_skpd(){
		$this->auth->restrict();
		//$this->output->enable_profiler(TRUE);
		$id_skpd 	= $this->session->userdata("id_skpd");
		$nama_skpd 	= $this->session->userdata("nama_skpd");
		$ta 		= $this->m_settings->get_tahun_anggaran();
		$id_tahun	= $this->m_settings->get_id_tahun();

		if (empty($id_skpd)) {
			$this->session->set_userdata('msg_typ','err');
			$this->session->set_userdata('msg', 'User tidak memiliki akses untuk pembuatan RKA, mohon menghubungi administrator.');
			redirect('home');
		}

		$data['nama_skpd']=$nama_skpd;
		//$data['jendela_kontrol'] = $this->m_renstra_trx->count_jendela_kontrol($id_skpd);

		$id_renstra = $this->input->post('id_renstra');
		$id 		= $this->input->post('id');

		$data['id_renstra'] = $id_renstra;
		$data['id']			= $id;
		$data['program'] = $this->m_dpa->get_all_program($id_skpd,$ta);
		$data['id_skpd'] = $id_skpd;
		$data['ta']	= $ta;

		if($id_skpd > 100){
			$id_skpd = $this->m_skpd->get_kode_unit_dari_asisten($id_skpd);
		}
		$kode_unit = $this->m_skpd->get_kode_unit($id_skpd);
		if (empty($data['program'])) {
			if ($kode_unit != $id_skpd) {
				redirect('home/kosong/DPA');
			}
		}
		$this->template->load('template','dpa/view', $data);
	}

	function get_rka(){
		$id_skpd 	= $this->session->userdata("id_skpd");
		$ta 		= $this->m_settings->get_tahun_anggaran();
		$dpa		= $this->m_dpa->insert_dpa($id_skpd,$ta);
		$result 	= $this->m_dpa->import_from_rka($id_skpd,$ta);
		if ($result) {
			$msg = array('success' => '1', 'msg' => 'RKA berhasil diambil.');
			echo json_encode($msg);
		}else{
			$msg = array('success' => '0', 'msg' => 'ERROR! RKA gagal diambil, mohon menghubungi administrator.');
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
			$result = $this->m_dpa->get_one_program($id);
			if (empty($result)) {
				echo '<div style="width: 400px;">ERROR! Data tidak ditemukan.</div>';
				return FALSE;
			}
			$data['program'] = $result;
			$data['indikator_program'] = $this->m_dpa->get_indikator_prog_keg($id, FALSE);
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
			$data['rencana_aksi'] = $this->m_dpa->get_rencana_aksi($id);
			$data['anggaran_rencana_aksi'] = $this->m_dpa->get_sum_anggaran_rencana_aksi($id);
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
		$this->load->view("dpa/cru_program", $data);
	}

	function save_program_dpa(){
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
		$id_for_rencana = $this->input->post('rcn_unicid');

		$clean = array('id_program', 'indikator_kinerja', 'id_indikator_program', 'satuan_target', 'status_target', 'kategori_target', 'target', 'rcn_bulan', 'rcn_aksi', 'rcn_bobot', 'rcn_satuan', 'rcn_target', 'rcn_id', 'rcn_unicid');
		$data = $this->global_function->clean_array($data, $clean);

		if (!empty($id)) {
			$result = $this->m_dpa->edit_program_skpd($data, $id, $indikator, $id_indikator_program, $satuan_target, $status_indikator, $kategori_indikator, $target, $id_for_rencana);
		}else{
			$result = $this->m_dpa->add_program_skpd($data, $indikator, $satuan_target, $status_indikator, $kategori_indikator, $target, $id_for_rencana);
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
		$result = $this->m_dpa->delete_program($id);
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
		$ta 		= $this->m_settings->get_tahun_anggaran();
		//$data['jendela_kontrol'] = $this->m_renja_trx->count_jendela_kontrol($id_skpd);

		$id			= $this->input->post('id');
		//echo $id_renstra;

		$data['id']	= $id;
		$data['kegiatan'] = $this->m_dpa->get_all_kegiatan($id, $id_skpd, $ta);

		$this->load->view("dpa/view_kegiatan", $data);
	}

	function cru_kegiatan_skpd(){
		$this->auth->restrict();
		//$this->output->enable_profiler(true);
		$id_program = $this->input->post('id_program');
		$id 		= $this->input->post('id');

		$id_skpd = $this->session->userdata("id_skpd");
		$data['skpd'] = $this->m_skpd->get_one_skpd(array('id_skpd' => $id_skpd));

		$kd_kegiatan_edit = NULL;

		$cb_jenis_belanja_edit = NULL;
		$cb_kategori_belanja_edit = NULL;
		$cb_subkategori_belanja_edit = NULL;
		$cb_belanja_edit = NULL;

		if (!empty($id)) {
			$result = $this->m_dpa->get_one_kegiatan($id_program,$id);
			if (empty($result)) {
				echo '<div style="width: 400px;">ERROR! Data tidak ditemukan.</div>';
				return FALSE;
			}
			$data['kegiatan'] = $result;
			$data['indikator_kegiatan'] = $this->m_dpa->get_indikator_prog_keg($id, FALSE);
			$kd_kegiatan_edit = $result->kd_kegiatan;
			$data['rencana_aksi'] = $this->m_dpa->get_rencana_aksi($id);
			$data['anggaran_rencana_aksi'] = $this->m_dpa->get_sum_anggaran_rencana_aksi($id);
		}
		$data['id_program'] = $id_program;
		$kodefikasi = $this->m_dpa->get_info_kodefikasi_program($id_program);
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

		//lov kegiatan
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

		//TODO : Decompose table, new query
		//get data triwulan dari table tx_dpa_prog_keg bedasarkan id
		$sql = "select * from tx_dpa_prog_keg_triwulan where id_dpa_prog_keg = '".$id."' order by id_triwulan asc";
		//$data_triwulan = array();
		$data['data_triwulan'] =$this->db->query($sql)->result();




		$data['satuan'] = $satuan;
		$data['sumber_dana'] = $sumber_dana;
		$data['status_indikator'] = $status_indikator;
		$data['kategori_indikator'] = $kategori_indikator;
		$data['kd_kegiatan'] = form_dropdown('kd_kegiatan', $kd_kegiatan, $kd_kegiatan_edit, 'data-placeholder="Pilih Kegiatan" class="common chosen-select" id="kd_kegiatan"');
		$data['cb_jenis_belanja_1'] = form_dropdown('cb_jenis_belanja_1', $cb_jenis_belanja, $cb_jenis_belanja_edit, 'data-placeholder="Pilih Kelompok Belanja" class="common chosen-select" id="cb_jenis_belanja_1"');
		$data['cb_kategori_belanja_1'] = form_dropdown('cb_kategori_belanja_1', $cb_kategori_belanja, $cb_kategori_belanja_edit, 'data-placeholder="Pilih Jenis Belanja" class="common chosen-select" id="cb_kategori_belanja_1"');
		$data['cb_subkategori_belanja_1'] = form_dropdown('cb_subkategori_belanja_1', $cb_subkategori_belanja, $cb_subkategori_belanja_edit, 'data-placeholder="Pilih Obyek Belanja" class="common chosen-select" id="cb_subkategori_belanja_1"');
		$data['cb_belanja_1'] = form_dropdown('cb_belanja_1', $cb_belanja, $cb_belanja_edit, 'data-placeholder="Pilih Rincian Obyek" class="common chosen-select" id="cb_belanja_1"');
		$data['detil_kegiatan_1'] = $this->m_dpa->get_dpa_belanja_per_tahun($id, $this->session->userdata('t_anggaran_aktif'));
		$this->load->view("dpa/cru_kegiatan", $data);
	}

	function save_kegiatan(){
		$this->auth->restrict();
		$id = $this->input->post('id_kegiatan');

		$data = $this->input->post();
		$id_skpd = $this->input->post("id_skpd");
		$tahun = $this->input->post("tahun");
		$parent = $this->input->post("id_program");
		$id_indikator_kegiatan = $this->input->post("id_indikator_kegiatan");
		$indikator = $this->input->post("indikator_kinerja");
		$satuan_target = $this->input->post("satuan_target");
		$status_indikator = $this->input->post("status_target");
		$kategori_indikator = $this->input->post("kategori_target");
		$target = $this->input->post("target");
		$id_for_rencana = $this->input->post('rcn_unicid');

		$data = array(
			'tahun' => $this->input->post("tahun"),
			'id_skpd' => $this->input->post('id_skpd'),
			'id_rka' => $this->input->post('id_rka'),
		  'id_program'  => $this->input->post('id_program'),
		  'kd_urusan' =>  $this->input->post('kd_urusan'),
		  'kd_bidang' => $this->input->post('kd_bidang'),
		  'kd_program' =>  $this->input->post('kd_program'),
		  'nama_prog_or_keg' => $this->input->post('nama_prog_or_keg'),
		  'kd_kegiatan' =>  $this->input->post('kd_kegiatan'),
		  'penanggung_jawab' =>  $this->input->post('penanggung_jawab'),
		  'parent'=> $this->input->post('parent'),
		  'is_prog_or_keg' => $this->input->post('is_prog_or_keg'),
			'nominal_1' => $this->input->post("nominal_1"),
			'nominal_2' => $this->input->post("nominal_2"),
			'nominal_3' => $this->input->post("nominal_3"),
			'nominal_4' => $this->input->post("nominal_4"),
			'nominal_5' => $this->input->post("nominal_5"),
			'nominal_6' => $this->input->post("nominal_6"),
			'nominal_7' => $this->input->post("nominal_7"),
			'nominal_8' => $this->input->post("nominal_8"),
			'nominal_9' => $this->input->post("nominal_9"),
			'nominal_10' => $this->input->post("nominal_10"),
			'nominal_11' => $this->input->post("nominal_11"),
			'nominal_12' => $this->input->post("nominal_12")
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
		// 	'id_keg'=>$this->input->post("id_kegiatan")
		// );

		// if ($this->input->post('total_belanja') > 0) {
		// 	$dataKegiatan1 = $this->global_function->re_index($dataKegiatan1);
		// }

		$clean = array('id_kegiatan', 'id_indikator_kegiatan', 'indikator_kinerja', 'satuan_target', 'target', 'rcn_bulan', 'rcn_aksi', 'rcn_bobot', 'rcn_satuan', 'rcn_target', 'rcn_anggaran', 'rcn_id', 'rcn_unicid');
		$data = $this->global_function->clean_array($data, $clean);
		$change = array('id_program'=>'parent');
		$data = $this->global_function->change_array($data, $change);

		if (!empty($id)) {
			$result = $this->m_dpa->edit_kegiatan_skpd($data, $id, $indikator, $id_indikator_kegiatan, $satuan_target, $status_indikator, $kategori_indikator, $target, $id_for_rencana);
		}else{
			$result = $this->m_dpa->add_kegiatan_skpd($data, $indikator, $satuan_target, $status_indikator, $kategori_indikator, $target, $id_for_rencana);
		}

		//process nomunal
		// $check_1 = "select * from tx_dpa_prog_keg_triwulan where id_dpa_prog_keg='".$id."' and id_triwulan='1'";
		// $check_2 = "select * from tx_dpa_prog_keg_triwulan where id_dpa_prog_keg='".$id."' and id_triwulan='2'";
		// $check_3 = "select * from tx_dpa_prog_keg_triwulan where id_dpa_prog_keg='".$id."' and id_triwulan='3'";
		// $check_4 = "select * from tx_dpa_prog_keg_triwulan where id_dpa_prog_keg='".$id."' and id_triwulan='4'";
		// $check_1 = "select * from tx_dpa_prog_keg_triwulan where id_dpa_prog_keg='".$id."' and id_triwulan='1'";
		// $check_2 = "select * from tx_dpa_prog_keg_triwulan where id_dpa_prog_keg='".$id."' and id_triwulan='2'";
		// $check_3 = "select * from tx_dpa_prog_keg_triwulan where id_dpa_prog_keg='".$id."' and id_triwulan='3'";
		// $check_4 = "select * from tx_dpa_prog_keg_triwulan where id_dpa_prog_keg='".$id."' and id_triwulan='4'";
		// $check_1 = "select * from tx_dpa_prog_keg_triwulan where id_dpa_prog_keg='".$id."' and id_triwulan='1'";
		// $check_2 = "select * from tx_dpa_prog_keg_triwulan where id_dpa_prog_keg='".$id."' and id_triwulan='2'";
		// $check_3 = "select * from tx_dpa_prog_keg_triwulan where id_dpa_prog_keg='".$id."' and id_triwulan='3'";
		// $check_4 = "select * from tx_dpa_prog_keg_triwulan where id_dpa_prog_keg='".$id."' and id_triwulan='4'";
		//
		// $sql_1 = "update tx_dpa_prog_keg_triwulan set anggaran='".$this->input->post('nominal_1')."' where id_dpa_prog_keg='".$id."' and id_triwulan='1'";
		// $sql_2 = "update tx_dpa_prog_keg_triwulan set anggaran='".$this->input->post('nominal_2')."' where id_dpa_prog_keg='".$id."' and id_triwulan='2'";
		// $sql_3 = "update tx_dpa_prog_keg_triwulan set anggaran='".$this->input->post('nominal_3')."' where id_dpa_prog_keg='".$id."' and id_triwulan='3'";
		// $sql_4 = "update tx_dpa_prog_keg_triwulan set anggaran='".$this->input->post('nominal_4')."' where id_dpa_prog_keg='".$id."' and id_triwulan='4'";
		// $sql_1 = "update tx_dpa_prog_keg_triwulan set anggaran='".$this->input->post('nominal_1')."' where id_dpa_prog_keg='".$id."' and id_triwulan='1'";
		// $sql_2 = "update tx_dpa_prog_keg_triwulan set anggaran='".$this->input->post('nominal_2')."' where id_dpa_prog_keg='".$id."' and id_triwulan='2'";
		// $sql_3 = "update tx_dpa_prog_keg_triwulan set anggaran='".$this->input->post('nominal_3')."' where id_dpa_prog_keg='".$id."' and id_triwulan='3'";
		// $sql_4 = "update tx_dpa_prog_keg_triwulan set anggaran='".$this->input->post('nominal_4')."' where id_dpa_prog_keg='".$id."' and id_triwulan='4'";
		// $sql_1 = "update tx_dpa_prog_keg_triwulan set anggaran='".$this->input->post('nominal_1')."' where id_dpa_prog_keg='".$id."' and id_triwulan='1'";
		// $sql_2 = "update tx_dpa_prog_keg_triwulan set anggaran='".$this->input->post('nominal_2')."' where id_dpa_prog_keg='".$id."' and id_triwulan='2'";
		// $sql_3 = "update tx_dpa_prog_keg_triwulan set anggaran='".$this->input->post('nominal_3')."' where id_dpa_prog_keg='".$id."' and id_triwulan='3'";
		// $sql_4 = "update tx_dpa_prog_keg_triwulan set anggaran='".$this->input->post('nominal_4')."' where id_dpa_prog_keg='".$id."' and id_triwulan='4'";
		//
		// $insert_1 = "insert tx_dpa_prog_keg_triwulan(id_dpa_prog_keg,id_triwulan,anggaran) value ('".$id."','1',".$this->input->post('nominal_1').")";
		// $insert_2 = "insert tx_dpa_prog_keg_triwulan(id_dpa_prog_keg,id_triwulan,anggaran) value ('".$id."','2',".$this->input->post('nominal_2').")";
		// $insert_3 = "insert tx_dpa_prog_keg_triwulan(id_dpa_prog_keg,id_triwulan,anggaran) value ('".$id."','3',".$this->input->post('nominal_3').")";
		// $insert_4 = "insert tx_dpa_prog_keg_triwulan(id_dpa_prog_keg,id_triwulan,anggaran) value ('".$id."','4',".$this->input->post('nominal_4').")";
		// $insert_1 = "insert tx_dpa_prog_keg_triwulan(id_dpa_prog_keg,id_triwulan,anggaran) value ('".$id."','1',".$this->input->post('nominal_1').")";
		// $insert_2 = "insert tx_dpa_prog_keg_triwulan(id_dpa_prog_keg,id_triwulan,anggaran) value ('".$id."','2',".$this->input->post('nominal_2').")";
		// $insert_3 = "insert tx_dpa_prog_keg_triwulan(id_dpa_prog_keg,id_triwulan,anggaran) value ('".$id."','3',".$this->input->post('nominal_3').")";
		// $insert_4 = "insert tx_dpa_prog_keg_triwulan(id_dpa_prog_keg,id_triwulan,anggaran) value ('".$id."','4',".$this->input->post('nominal_4').")";
		// $insert_1 = "insert tx_dpa_prog_keg_triwulan(id_dpa_prog_keg,id_triwulan,anggaran) value ('".$id."','1',".$this->input->post('nominal_1').")";
		// $insert_2 = "insert tx_dpa_prog_keg_triwulan(id_dpa_prog_keg,id_triwulan,anggaran) value ('".$id."','2',".$this->input->post('nominal_2').")";
		// $insert_3 = "insert tx_dpa_prog_keg_triwulan(id_dpa_prog_keg,id_triwulan,anggaran) value ('".$id."','3',".$this->input->post('nominal_3').")";
		// $insert_4 = "insert tx_dpa_prog_keg_triwulan(id_dpa_prog_keg,id_triwulan,anggaran) value ('".$id."','4',".$this->input->post('nominal_4').")";
		//
		//
		// if($this->db->query($check_1)->num_rows()>0){
		// 	$this->db->query($sql_1);
		// }else{
		// 	$this->db->query($insert_1);
		// }
		//
		// if($this->db->query($check_2)->num_rows()>0){
		// 	$this->db->query($sql_2);
		// }else{
		// 	$this->db->query($insert_2);
		// }
		//
		// if($this->db->query($check_3)->num_rows()>0){
		// 	$this->db->query($sql_3);
		// }else{
		// 	$this->db->query($insert_3);
		// }
		//
		// if($this->db->query($check_4)->num_rows()>0){
		// 	$this->db->query($sql_4);
		// }else{
		// 	$this->db->query($insert_4);
		// }

		for ($i=1; $i < 13; $i++) {
			$nominal = $this->input->post('nominal_'.$i);
			if ($this->db->query("select * from tx_dpa_prog_keg_triwulan where id_dpa_prog_keg='".$id."' and id_triwulan='".$i."'")->num_rows()>0) {
				$this->db->query("update tx_dpa_prog_keg_triwulan set anggaran='".$nominal."' where id_dpa_prog_keg='".$id."' and id_triwulan='".$i."'");
			}else {
				$this->db->query("insert tx_dpa_prog_keg_triwulan(id_dpa_prog_keg,id_triwulan,anggaran) value ('".$id."','".$i."',".$nominal.")");
			}
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
		$result = $this->m_dpa->delete_kegiatan($id);
		if ($result) {
			$msg = array('success' => '1', 'msg' => 'Kegiatan berhasil dihapus.');
			echo json_encode($msg);
		}else{
			$msg = array('success' => '0', 'msg' => 'ERROR! Kegiatan gagal dihapus, mohon menghubungi administrator.');
			echo json_encode($msg);
		}
	}

	function preview_kegiatan_dpa(){
		$id = $this->input->post("id");
		$result = $this->m_dpa->get_one_kegiatan(NULL, $id, TRUE);
		if (!empty($result)) {
			$data['dpa'] = $result;
			$data['indikator_kegiatan'] = $this->m_dpa->get_indikator_prog_keg($result->id, TRUE, TRUE);
			$data['tahun1'] = $this->m_dpa->get_dpa_belanja_per_tahun($id, $this->m_settings->get_tahun_anggaran_aktif_db());
			$data['rencana_aksi'] = $this->m_dpa->get_rencana_aksi($id);
			$this->load->view('dpa/preview', $data);
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

		$this->template->load('template','rka/cru_rka', $data);
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

		if(strpos($call_from, 'rka/cru_rka') != FALSE) {
			$call_from = '';
		}
		//cek apakah cekbank tsb ada
		$data_rka = $this->m_dpa->get_rka_by_id($id_rka);
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
			$ret = $this->m_dpa->simpan_rka($data_rka);
			//echo $this->db->last_query();
		} else {
			//update
			$$ret = $this->m_dpa->update_rka($data_rka, $id_rka,'table_rka', 'primary_rka');
			//echo $this->db->last_query();
		}
		if ($ret === FALSE){
            $this->session->set_userdata('msg_typ','err');
            $this->session->set_userdata('msg', 'Data RKA gagal disimpan');
		} else {
            $this->session->set_userdata('msg_typ','ok');
            $this->session->set_userdata('msg', 'Data RKA Berhasil disimpan');
		}

		if(!empty($call_from))
			redirect($call_from);

        redirect('rka');
		//var_dump($cekbank);
		//print_r ($id_cek);
    }
	/*function save_rka()
	{
		$id_rka 	= $this->input->post('id_rka');
        $call_from	= $this->input->post('call_from');
        $id_rka 	= $this->input->post('id_rka');
        $data_post 	= array(
            'kd_urusan'				=> $this->input->post('kd_urusan'),
    		'kd_bidang'	 			=> $this->input->post('kd_bidang'),
    		'kd_program'	 		=> $this->input->post('kd_programm'),
    		'kd_kegiatan'			=> $this->input->post('kd_kegiatan'),
    		'capaian_sekarang'		=> $this->input->post('capaian_sekarang'),
    		'jumlah_dana_sekarang'	=> $this->input->post('jumlah_dana_sekarang'),
    		'capaian_mendatang'		=> $this->input->post('capaian_mendatang'),
    		'jumlah_dana_mendatang'	=> $this->input->post('jumlah_dana_mendatang'),
    		'kesesuaian_ya'			=> $this->input->post('kesesuaian_ya'),
            'kesesuaian_tidak'		=> $this->input->post('kesesuaian_tidak'),
            'hasil_pengendalian'   	=> $this->input->post('hasil_pengendalian'),
            'tindak_lanjut'	   		=> $this->input->post('tindak_lanjut'),
            'hasil_tindak_lanjut'  	=> $this->input->post('hasil_tindak_lanjut')
        );

        if(strpos($call_from, 'rka/edit_rka') != FALSE) {
			$call_from = '';
		}
		$cek_rka = $this->m_dpa->get_data(array('id_rka'=>$id_rka),'table_rka');
		if($cek_rka === empty($cek_rka)) {
			$cek_rka = new stdClass();
			$id_rka = '';
		}
	}*/

	function load_rka()
	{
		$search = $this->input->post("search");
		$start = $this->input->post("start");
		$length = $this->input->post("length");
		$order = $this->input->post("order");

		$rka = $this->m_dpa->get_data_table($search, $start, $length, $order["0"]);
		$alldata = $this->m_dpa->count_data_table($search, $start, $length, $order["0"]);

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
       //                      $row->kriteria_keberhasilan,
							// $row->ukuran_keberhasilan,
							// $row->triwulan,
							// $row->pagu,
							// $row->realisasi,
							// $row->capaian_triwulan,
							// $row->ukuran_kinerja_triwulan,
							// $row->capaian_output_triwulan,
							// $row->keterangan,
							'<a href="javascript:void(0)" onclick="edit_rka('. $row->id_rka .')" class="icon2-page_white_edit" title="Edit RKA"/>
							<a href="javascript:void(0)" onclick="delete_rka('. $row->id_rka .')" class="icon2-delete" title="Hapus RRA"/>'
							);
		}
		$json = array("recordsTotal"=> $alldata, "recordsFiltered"=> $alldata, 'data' => $data);

        echo json_encode($json);
	}

	function edit_rka($id_rka = NULL)
	{
		//$this->output->enable_profiler(TRUE);
        $this->auth->restrict();
        $data['url_save_data'] = site_url('rka/save_rka');

        $data['isEdit'] = FALSE;
        if (!empty($id_rka)) {
            $data_ = array('id_rka'=>$id_rka);
            $result = $this->m_dpa->get_data_with_rincian($id_rka,'table_rka');
			if (empty($result)) {
				$this->session->set_userdata('msg_typ','err');
				$this->session->set_userdata('msg', 'Data musrenbang tidak ditemukan.');
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

            //$mp_filefiles				= $this->m_musrenbang->get_file(explode( ',', $result->file), TRUE);
			//$data['mp_jmlfile']			= $mp_filefiles->num_rows();
			//$data['mp_filefiles']		= $mp_filefiles->result();


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
        $this->template->load('template','rka/cru_rka', $data);



	}

	function delete_rka()
	{
        $id = $this->input->post('id');

        $result = $this->m_dpa->delete_rka($id);
        if ($result) {
			$msg = array('success' => '1', 'msg' => 'RKA berhasil dihapus.');
			echo json_encode($msg);
		}else{
			$msg = array('success' => '0', 'msg' => 'ERROR! RKA gagal dihapus, mohon menghubungi administrator.');
			echo json_encode($msg);
		}
	}

	## --------------- ##
	## 	  Preview DPA  ##
	## --------------- ##
	private function cetak_skpd_func($id_skpd,$ta){
		$data['dpa_type'] = "DPA";

		$protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://';
		$header = $this->m_template_cetak->get_value("GAMBAR");
		$data['logo'] = str_replace("src=\"","height=\"90px\" src=\"".$protocol.$_SERVER['HTTP_HOST'],$header);
		$data['header'] = $this->m_template_cetak->get_value("HEADER");
		$data2['urusan'] = $this->db->query("
			SELECT t.*,u.Nm_Urusan as nama_urusan from (
			SELECT
				pro.kd_urusan,pro.kd_bidang,pro.kd_program,pro.kd_kegiatan,
				SUM(keg.nominal_1) AS sum_nominal_1,
				SUM(keg.nominal_2) AS sum_nominal_2,
				SUM(keg.nominal_3) AS sum_nominal_3,
				SUM(keg.nominal_4) AS sum_nominal_4,
				SUM(keg.nominal_5) AS sum_nominal_5,
				SUM(keg.nominal_6) AS sum_nominal_6,
				SUM(keg.nominal_7) AS sum_nominal_7,
				SUM(keg.nominal_8) AS sum_nominal_8,
				SUM(keg.nominal_9) AS sum_nominal_9,
				SUM(keg.nominal_10) AS sum_nominal_10,
				SUM(keg.nominal_11) AS sum_nominal_11,
				SUM(keg.nominal_12) AS sum_nominal_12
			FROM
				(SELECT * FROM tx_dpa_prog_keg WHERE is_prog_or_keg=1) AS pro
			INNER JOIN
				(SELECT * FROM tx_dpa_prog_keg WHERE is_prog_or_keg=2) AS keg ON keg.parent=pro.id
			WHERE
				keg.id_skpd=".$id_skpd."
				AND keg.tahun= ".$ta."
			GROUP BY pro.kd_urusan
			ORDER BY kd_urusan ASC, kd_bidang ASC, kd_program ASC
			) t
			left join m_urusan u
			on t.kd_urusan = u.Kd_Urusan
		")->result();

		$data2['id_skpd'] = $id_skpd;
		$data2['ta'] = $ta;

		//$data2['program'] = $this->m_dpa->get_program_skpd_4_cetak($id_skpd,$ta);
		$data['dpa'] = $this->load->view('dpa/cetak/program_kegiatan_preview', $data2, TRUE);
		return $data;
	}

	function preview_dpa(){
		$this->auth->restrict();
		$id_skpd = $this->session->userdata('id_skpd');
		$ta = $this->session->userdata('t_anggaran_aktif');

		$skpd = $this->m_dpa->get_one_dpa_skpd($id_skpd, TRUE);
		if (!empty($skpd)) {
			$data = $this->cetak_skpd_func($id_skpd,$ta);
			$this->template->load('template', 'dpa/preview_dpa_1', $data);
		}else{
			$this->session->set_userdata('msg_typ','err');
			$this->session->set_userdata('msg', 'Data DPA tidak tersedia.');
			redirect('home');
		}
	}



	private function cetak_func221($cetak=FALSE, $ta, $idK){


		if (!$cetak) {
			$temp['class_table']='class="table-common"';
		}else{
			$temp['class_table']='class="border"';
		}

		$protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://';
		$header = $this->m_template_cetak->get_value("GAMBAR");
		$data['logo'] = str_replace("src=\"","height=\"45px\" src=\"".$protocol.$_SERVER['HTTP_HOST'],$header);
		$data['tahun1'] =$this->m_dpa->get_dpa_belanja_per_tahun221($ta, $idK);

		$data['keluaran'] =$this->m_dpa->get_indikator_keluaran($ta, $idK);
		$data['capaian'] =$this->m_dpa->get_indikator_capaian($idK);

		$data['nominal'] =$this->m_dpa->get_nominal_dpa($idK,$ta);


		$data['ta_ng'] = $ta;
		$data['idk_ng'] = $idK;

		$result = $this->load->view("dpa/cetak/cetak_form_221",$data, TRUE);



		return $result;
	}

	function cetak_kegiatan($idK){

		$ta = $this->session->userdata('t_anggaran_aktif');
		$protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://';
		$header = $this->m_template_cetak->get_value("GAMBAR");
		$data['logo'] = str_replace("src=\"","height=\"70px\" src=\"".$protocol.$_SERVER['HTTP_HOST'],$header);
		$data['header'] = $this->m_template_cetak->get_value("HEADER");
		$data['qr'] = $this->ciqrcode->generateQRcode("sirenbangda", 'usulanbansos/persetujuanusulanbansos'. date("d-m-Y_H-i-s"), 1);

		$data['cetak'] = $this->cetak_func221(TRUE, $ta, $idK);

		$html = $this->template->load('template_cetak_rka', 'renstra/cetak/cetak_view', $data, true);

		$filename='renja '. $this->session->userdata('nama_skpd') ." ". date("d-m-Y_H-i-s") .'.pdf';

		pdf_create($html, $filename, "A4", "Landscape", FALSE);

	}

	function view_rencana_aksi($view, $uniq_id, $id_dpa, $id, $kd_status){
		$rcn_bln = 0;
		$tot_rcn_bobot_k = 0;
		$tot_rcn_anggaran_k = 0;
		foreach ($view as $row_rcn) {
			if ($rcn_bln != $row_rcn->bulan) {
				$rcn_bln = $row_rcn->bulan;
				$rcn_per_bulan = $this->db->query("SELECT COUNT(bulan) AS tot_row, SUM(bobot) AS sum_bot, SUM(anggaran) AS sum_ang FROM tx_dpa_rencana_aksi WHERE bulan = '".$rcn_bln."' AND id <> '".$id."' AND (id_dpa_prog_keg = '".$uniq_id."' OR id_dpa_prog_keg = '".$id_dpa."')")->row();
				// print_r($this->db->last_query());
				$tot_row_rcn = $rcn_per_bulan->tot_row;
				$rcn_akumulasi = $rcn_per_bulan->sum_bot;
				$rcn_anggaran = $rcn_per_bulan->sum_ang;

				$tot_rcn_bobot_k += $rcn_akumulasi;
				$tot_rcn_anggaran_k += $rcn_anggaran;
if ($kd_status == 1) {
				echo "
					<tr>
						<td style='vertical-align: middle !important;' rowspan='".$tot_row_rcn."'>".$row_rcn->bulan."</td>
						<td>".$row_rcn->aksi."</td>
						<td>".$row_rcn->target."</td>
						<td>".$row_rcn->satuan."</td>
						<td>".$row_rcn->bobot."</td>
						
						<td style='vertical-align: middle !important;' rowspan='".$tot_row_rcn."'>".$rcn_akumulasi."</td>
						
						<td>".$row_rcn->kumul."</td>
						<td style='vertical-align: middle !important;' rowspan='".$tot_row_rcn."'>".$tot_rcn_bobot_k."</td>
						
						<td style='width: 40px;'>
							<button type='button' onclick='edit_rcn(".$row_rcn->id.")'><i class='fa fa-pencil'></i></button>
							<button type='button' onclick='hapus_rcn_aksi(1, ".$row_rcn->id.")'><i class='fa fa-remove'></i></button>
						</td>
					</tr>";
}else{
				echo "
					<tr>
						<td style='vertical-align: middle !important;' rowspan='".$tot_row_rcn."'>".$row_rcn->bulan."</td>
						<td>".$row_rcn->aksi."</td>
						<td>".$row_rcn->target."</td>
						<td>".$row_rcn->satuan."</td>
						<td>".$row_rcn->bobot."</td>
						<td>".number_format($row_rcn->anggaran,2,",",".")."</td>
						<td style='vertical-align: middle !important;' rowspan='".$tot_row_rcn."'>".$rcn_akumulasi."</td>
						<td style='vertical-align: middle !important;' rowspan='".$tot_row_rcn."'>".number_format($rcn_anggaran,2,",",".")."</td>
						<td>".$row_rcn->kumul."</td>
						<td style='vertical-align: middle !important;' rowspan='".$tot_row_rcn."'>".$tot_rcn_bobot_k."</td>
						<td style='vertical-align: middle !important;' rowspan='".$tot_row_rcn."'>".number_format($tot_rcn_anggaran_k,2,",",".")."</td>
						<td style='width: 40px;'>
							<button type='button' onclick='edit_rcn(".$row_rcn->id.")'><i class='fa fa-pencil'></i></button>
							<button type='button' onclick='hapus_rcn_aksi(2, ".$row_rcn->id.")'><i class='fa fa-remove'></i></button>
						</td>
					</tr>";
}
			}else{
if ($kd_status == 1) {
				echo "
					<tr>
						<td>".$row_rcn->aksi."</td>
						<td>".$row_rcn->target."</td>
						<td>".$row_rcn->satuan."</td>
						<td>".$row_rcn->bobot."</td>
						
						<td>".$row_rcn->kumul."</td>
						<td style='width: 40px;'>
							<button type='button' onclick='edit_rcn(".$row_rcn->id.")'><i class='fa fa-pencil'></i></button>
							<button type='button' onclick='hapus_rcn_aksi(1, ".$row_rcn->id.")'><i class='fa fa-remove'></i></button>
						</td>
					</tr>";
}else{
				echo "
					<tr>
						<td>".$row_rcn->aksi."</td>
						<td>".$row_rcn->target."</td>
						<td>".$row_rcn->satuan."</td>
						<td>".$row_rcn->bobot."</td>
						<td>".number_format($row_rcn->anggaran,2,",",".")."</td>
						<td>".$row_rcn->kumul."</td>
						<td style='width: 40px;'>
							<button type='button' onclick='edit_rcn(".$row_rcn->id.")'><i class='fa fa-pencil'></i></button>
							<button type='button' onclick='hapus_rcn_aksi(2, ".$row_rcn->id.")'><i class='fa fa-remove'></i></button>
						</td>
					</tr>";
}
			}
		}
	}

	function save_rencana_aksi(){
		$data = $this->input->post();
		$uniq_id = $this->input->post('id_dpa_prog_keg');
		$id_dpa = $this->input->post('id_dpa');
		$id = $this->input->post('id');

		$kd_status = $this->input->post('kd_status');

		// print_r($data);
		// exit;

		$clean = array('id_dpa', 'id', 'kd_status');
		$data = $this->global_function->clean_array($data, $clean);

		$add = array('get_date' => date('Y-m-d H:i:s'));
		$data = $this->global_function->add_array($data, $add);
		
		if (!empty($id)) {
			$this->m_dpa->edit_rencana_aksi($id, $data);	
		}else{
			$this->m_dpa->add_rencana_aksi($data);
		}

		$view = $this->m_dpa->get_rencana_aksi($data['id_dpa_prog_keg'], $id_dpa);
		
		$this->view_rencana_aksi($view, $uniq_id, $id_dpa, NULL, $kd_status);
	}

	function delete_rencana_aksi(){
		$id = $this->input->post('id');
		$uniq_id = $this->input->post('id_dpa_prog_keg');
		$id_dpa = $this->input->post('id_dpa');

		$kd_status = $this->input->post('kd_status');

		$this->m_dpa->delete_rencana_aksi($id);

		$view = $this->m_dpa->get_rencana_aksi($uniq_id, $id_dpa);
		
		$this->view_rencana_aksi($view, $uniq_id, $id_dpa, $id, $kd_status);
	}

	function edit_rencana_aksi(){
		$id = $this->input->post('id');
		$uniq_id = $this->input->post('id_dpa_prog_keg');
		$id_dpa = $this->input->post('id_dpa');

		$kd_status = $this->input->post('kd_status');

		$view = $this->m_dpa->get_rencana_aksi($uniq_id, $id_dpa, $id, TRUE);
		
		$this->view_rencana_aksi($view, $uniq_id, $id_dpa, $id, $kd_status);
	}

	function one_rencana_aksi(){
		$id = $this->input->post('id');
		$uniq_id = $this->input->post('id_dpa_prog_keg');
		$id_dpa = $this->input->post('id_dpa');


		$view = $this->m_dpa->get_rencana_aksi(NULL, NULL, $id);
		echo json_encode($view);
	}

	function get_sum_anggaran_per_bulan(){
		$arrayName = array();
		for ($i=0; $i < 12; $i++) { 
			$arrayName[$i] = (object)array('bulan' => $i+1, 'anggaran' => '0');
		}

		$uniq_id = $this->input->post('id_dpa_prog_keg');
		$id_dpa = $this->input->post('id_dpa');
		$total = $this->m_dpa->get_sum_anggaran_rencana_aksi($uniq_id, $id_dpa);
		foreach ($total as $key => $value) {
			$arrayName[$value->bulan-1] = array('bulan' => $value->bulan, 'anggaran' => $value->anggaran);
		}
		// print_r($arrayName);
		// exit();
		echo json_encode($arrayName);
	}

	function cetak_form_aksi($is_prog_or_keg, $id=null){
		$data['id'] = $id;
		$data['id_skpd'] = $this->session->userdata('id_skpd');
		$data['t_anggaran'] = $this->session->userdata('t_anggaran_aktif');

		if ($is_prog_or_keg == 1) {
			$data['aksi'] = array('rencana_prog' => 'Cetak Rencana Aksi Program', 'capaian_prog' => 'Cetak Capaian Aksi Program');
		}else{
			$data['aksi'] = array('rencana_keg' => 'Cetak Rencana Aksi Kegiatan', 'capaian_keg' => 'Cetak Capaian Aksi Kegiatan');
		}
		$this->load->view('dpa/cetak_form', $data);
	}

	function cetak_aksi($id_skpd,$t_anggaran,$aksi,$id=null){
		// $id_skpd = $this->input->post('id_skpd');
		// $t_anggaran = $this->input->post('t_anggaran');
		// $id = $this->input->post('id');
		// $aksi = $this->input->post('aksi');
		$t_anggaran = urldecode($t_anggaran);

		if ($aksi == "rencana_prog") {
			$JenisLaporan = 'rencana-aksi-program';
			$dir = "rencana_aksi_program";
			$data['aksi'] = $this->m_dpa->get_all_program($id_skpd, $t_anggaran);
			$data['filenameEX'] = 'Rencana_Aksi_Program_'.date("Y_m_d_H_i_s");
		}elseif ($aksi == "capaian_prog") {
			$JenisLaporan = 'capaian-aksi-program';
			$dir = "capaian_aksi_program";
			$data['aksi'] = $this->m_dpa->get_all_program($id_skpd, $t_anggaran);
			$data['filenameEX'] = 'Capaian_Aksi_Program_'.date("Y_m_d_H_i_s");
		}elseif ($aksi == "rencana_keg") {
			$JenisLaporan = 'rencana-aksi-kegiatan';
			$dir = "rencana_aksi_kegiatan";
			$data['aksi'] = $this->m_dpa->get_one_program($id);
			$data['filenameEX'] = 'Rencana_Aksi_Kegiatan_'.date("Y_m_d_H_i_s");
		}elseif ($aksi == "capaian_keg") {
			$JenisLaporan = 'capaian-aksi-kegiatan';
			$dir = "capaian_aksi_kegiatan";
			$data['aksi'] = $this->m_dpa->get_one_program($id);
			$data['filenameEX'] = 'Capaian_Aksi_Kegiatan_'.date("Y_m_d_H_i_s");
		}
		
		$Text = date("d-m-Y_H-i-s");
		// $data['qr'] = $this->ciqrcode->generateQRcode($JenisLaporan, $Text,1.9);
		$temp['header']= $this->load->view('Cetak_head',$data, TRUE);
		$result = $this->load->view("dpa/cetak/".$dir, $temp, TRUE);

		//coba dengan PHPExcel
		// $tmpfile = tempnam(sys_get_temp_dir(), 'html');
		// file_put_contents($tmpfile, $result);
		// $objPHPExcel = new PHPExcel();
		// $excelHTMLReader = PHPExcel_IOFactory::createReader('HTML');
		// $excelHTMLReader->loadIntoExisting($tmpfile, $objPHPExcel);
		// $objPHPExcel->getActiveSheet()->setTitle('Rencana Aksi');
		// $objPHPExcel->getActiveSheet()->getProtection()->setSheet(true);
		// $objPHPExcel->getActiveSheet()->getProtection()->setSort(true);
		// $objPHPExcel->getActiveSheet()->getProtection()->setInsertRows(true);
		// $objPHPExcel->getActiveSheet()->getProtection()->setFormatCells(true);
		// $objPHPExcel->getActiveSheet()->getProtection()->setPassword('password');
		// unlink($tmpfile);
		// header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); // header for .xlxs file
		// header('Content-Disposition: attachment;filename=test.xlxs'); // specify the download file name
		// header('Cache-Control: max-age=0');
		// $writer = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		// $writer->save('php://output');
		// exit();
		 
		print_r($result);exit();
		// $this->create_pdf->load($result,'DPA-'.$JenisLaporan.date("d-m-Y H:i:s"), 'A4-L','');
	}





	function belanja_kegiatan_lihat($return, $id_kegiatan, $ta, $tahun, $is_tahun, $not=NULL){
		$result = $this->m_dpa->get_kegiatan($id_kegiatan, $ta, $is_tahun, $not);

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
			echo "<script type='text/javascript'>$('#nominal').autoNumeric('set', ".$total.");</script>";
			
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

		$clean = array('id_belanja', 'is_tahun_sekarang');
		$data = $this->global_function->clean_array($data, $clean);

		$add = array('created_date' => date("Y-m-d H-i-s"));
		$data = $this->global_function->add_array($data, $add);
// print_r($data);
		$this->m_dpa->add_belanja_kegiatan($data, $id_belanja);
		
		$this->belanja_kegiatan_lihat(FALSE, $id_kegiatan, $ta, $tahun, $is_tahun);
	}

	function belanja_kegiatan_edit(){
		$tahun = $this->input->post('tahun');
		$id_kegiatan = $this->input->post('id_kegiatan');
		$id_belanja = $this->input->post('id_belanja');

		$ta = $this->input->post('ta');
		$is_tahun = $this->input->post('is_tahun');

		$data['edit'] = $this->m_dpa->get_one_belanja($id_belanja);
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

		$this->m_dpa->delete_one_kegiatan($id);

		$data['list'] = $this->belanja_kegiatan_lihat(TRUE, $id_kegiatan, $ta, $tahun, $is_tahun);

		echo json_encode($data);
	}
}
