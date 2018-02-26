<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cik extends CI_Controller
{
	var $CI = NULL;
	public function __construct()
	{
		$this->CI =& get_instance();
        parent::__construct();
        //$this->load->helper(array('form','url', 'text_helper','date'));
        $this->load->database();
        //$this->load->model('m_musrenbang','',TRUE);
        $this->load->model(array('m_cik','m_skpd','m_lov','m_urusan', 'm_bidang', 'm_program', 'm_kegiatan','m_bulan','m_template_cetak', 'm_rpjmd_trx'));
        if (!empty($this->session->userdata("db_aktif"))) {
            $this->load->database($this->session->userdata("db_aktif"), FALSE, TRUE);
        }
	}

	/*function index()
	{
		//$this->output->enable_profiler(TRUE);
		$this->auth->restrict();
	}
*/
	//Proses CIK baru
	function index(){
		$this->cik_skpd();
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
		$data['cik'] = $this->m_cik->get_cik($id_skpd,$ta);
		//$data['renja'] = $this->m_renja_trx->get_one_renja_skpd($id_skpd, TRUE);
		$data['jendela_kontrol'] = $this->m_cik->count_jendela_kontrol($id_skpd,$ta);
		//echo $this->db->last_query();
		$this->load->view('cik/jendela_kontrol', $data);
	}

	function cik_skpd(){
		$this->auth->restrict();
		//$this->output->enable_profiler(TRUE);
		$id_skpd 	= $this->session->userdata("id_skpd");
		$nama_skpd 	= $this->session->userdata("nama_skpd");
		$ta 		= $this->m_settings->get_tahun_anggaran();
		$id_tahun	= $this->m_settings->get_id_tahun();

		if (empty($id_skpd)) {
			$this->session->set_userdata('msg_typ','err');
			$this->session->set_userdata('msg', 'User tidak memiliki akses untuk pembuatan CIK, mohon menghubungi administrator.');
			redirect('home');
		}

		$data['nama_skpd']=$nama_skpd;
		$data['jendela_kontrol'] = $this->m_cik->count_jendela_kontrol($id_skpd,$ta);

		$id_renstra = $this->input->post('id_renstra');
		$id 		= $this->input->post('id');

		$data['id_renstra'] = $id_renstra;
		$data['id']			= $id;
		$data['program'] = $this->m_cik->get_all_program($id_skpd,$ta);
		$data['id_skpd'] = $id_skpd;
		$data['ta']	= $ta;

		if($id_skpd > 100){
			$id_skpd = $this->m_skpd->get_kode_unit_dari_asisten($id_skpd);
		}
		$kode_unit = $this->m_skpd->get_kode_unit($id_skpd);
		if (empty($data['program'])) {
			if ($kode_unit != $id_skpd) {
				redirect('home/kosong/CIK');
			}
		}
		$this->template->load('template','cik/view', $data);
	}

	function get_dpa(){
		$this->auth->restrict();
		$id_skpd 	= $this->session->userdata("id_skpd");
		$ta 		= $this->m_settings->get_tahun_anggaran();
		$dpa		= $this->m_cik->insert_cik($id_skpd,$ta);
		$result 	= $this->m_cik->import_from_dpa($id_skpd,$ta);
		if ($result) {
			$msg = array('success' => '1', 'msg' => 'DPA berhasil diambil.');
			echo json_encode($msg);
		}else{
			$msg = array('success' => '0', 'msg' => 'ERROR! DPA gagal diambil, mohon menghubungi administrator.');
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
			$result = $this->m_cik->get_one_program($id);
			if (empty($result)) {
				echo '<div style="width: 400px;">ERROR! Data tidak ditemukan.</div>';
				return FALSE;
			}
			$data['program'] = $result;
			$data['indikator_program'] = $this->m_cik->get_indikator_prog_keg($id, FALSE);
			$kd_urusan_edit = $result->kd_urusan;
			$kd_bidang_edit = $result->kd_bidang;
			$kd_program_edit = $result->kd_program;
			$id_prog_rpjmd_edit = $result->id_prog_rpjmd;
			$data_indikator_rpjmd = $this->m_rpjmd_trx->get_indikator_program_rpjmd_for_me($result->id_prog_rpjmd);
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
		$data['id_prog_rpjmd'] = form_dropdown('id_prog_rpjmd', $id_prog_rpjmd, $id_prog_rpjmd_edit, 'data-placeholder="Pilih Program RPJMD" class="common chosen-select" id="id_prog_rpjmd"');
		$data['kd_urusan'] = form_dropdown('kd_urusan', $kd_urusan, $kd_urusan_edit, 'data-placeholder="Pilih Urusan" class="common chosen-select" id="kd_urusan"');
		$data['kd_bidang'] = form_dropdown('kd_bidang', $kd_bidang, $kd_bidang_edit, 'data-placeholder="Pilih Bidang Urusan" class="common chosen-select" id="kd_bidang"');
		$data['kd_program'] = form_dropdown('kd_program', $kd_program, $kd_program_edit, 'data-placeholder="Pilih Program" class="common chosen-select" id="kd_program"');
		$this->load->view("cik/cru_program", $data);
	}

	function save_program_cik_(){
		$this->auth->restrict();
		$id = $this->input->post('id_program');

		$data = $this->input->post();
		$id_skpd = $this->input->post("id_skpd");
		$tahun = $this->input->post("tahun");
		$id_indikator_program = $this->input->post("id_indikator_program");
		$indikator = $this->input->post("indikator_kinerja");
		$satuan_target = $this->input->post("satuan_target");
		$target = $this->input->post("target");

		$clean = array('id_program', 'indikator_kinerja', 'id_indikator_program', 'satuan_target','target');
		$data = $this->global_function->clean_array($data, $clean);

		if (!empty($id)) {
			$result = $this->m_cik->edit_program_skpd($data, $id, $indikator, $id_indikator_program, $satuan_target, $target);
		}else{
			$result = $this->m_cik->add_program_skpd($data, $indikator, $satuan_target, $target);
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
		$result = $this->m_cik->delete_program($id);
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
		$data['jendela_kontrol'] = $this->m_cik->count_jendela_kontrol($id_skpd,$ta);

		$id			= $this->input->post('id');
		//echo $id_renstra;

		$data['id']	= $id;
		$data['kegiatan'] = $this->m_cik->get_all_kegiatan($id, $id_skpd, $ta);

		$this->load->view("cik/view_kegiatan", $data);
	}

	function cru_kegiatan_skpd(){
		$this->auth->restrict();
		//$this->output->enable_profiler(true);
		$id_program = $this->input->post('id_program');
		$id 		= $this->input->post('id');

		$id_skpd = $this->session->userdata("id_skpd");
		$data['skpd'] = $this->m_skpd->get_one_skpd(array('id_skpd' => $id_skpd));

		$kd_kegiatan_edit = NULL;

		if (!empty($id)) {
			$result = $this->m_cik->get_one_kegiatan($id_program,$id);
			if (empty($result)) {
				echo '<div style="width: 400px;">ERROR! Data tidak ditemukan.</div>';
				return FALSE;
			}
			$data['kegiatan'] = $result;
			$data['indikator_kegiatan'] = $this->m_cik->get_indikator_prog_keg($id, FALSE);
			$kd_kegiatan_edit = $result->kd_kegiatan;

		}
		$data['id_program'] = $id_program;
		$kodefikasi = $this->m_cik->get_info_kodefikasi_program($id_program);
		//echo $this->db->last_query();
		$data['kodefikasi'] = $kodefikasi;

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

		$data['satuan'] = $satuan;
		$data['kd_kegiatan'] = form_dropdown('kd_kegiatan', $kd_kegiatan, $kd_kegiatan_edit, 'data-placeholder="Pilih Kegiatan" class="common chosen-select" id="kd_kegiatan"');
		$this->load->view("cik/cru_kegiatan", $data);
	}

	function cru_perprogram(){
		$this->auth->restrict();
		//$this->output->enable_profiler(true);
		$bulan		= $this->input->post('bulan');
		$id 		= $this->input->post('id');

		$id_skpd = $this->session->userdata("id_skpd");
		$data['skpd'] = $this->m_skpd->get_one_skpd(array('id_skpd' => $id_skpd));
		$data['bulan'] = $bulan;

		$kd_kegiatan_edit = NULL;


		if (!empty($id)) {
			$result = $this->m_cik->get_cik_kegiatan($id,$bulan);
			if (empty($result)) {
				echo '<div style="width: 400px;">ERROR! Data tidak ditemukan.</div>';
				return FALSE;
			}
			$data['kegiatan'] = $result;
			$data['indikator_kegiatan'] = $this->m_cik->get_indikator_prog_keg_preview($id, $bulan,FALSE);

		}


		$status_indikator = array("" => "~~ Pilih Positif / Negatif ~~");
		foreach ($this->m_lov->get_status_indikator() as $row) {
			$status_indikator[$row->kode_status_indikator]=$row->nama_status_indikator;
		}

		$kategori_indikator = array("" => "~~ Pilih Kategori Indikator ~~");
		foreach ($this->m_lov->get_kategori_indikator() as $row) {
			$kategori_indikator[$row->kode_kategori_indikator]=$row->nama_kategori_indikator;
		}

		$data['status_indikator'] = $status_indikator;
		$data['kategori_indikator'] = $kategori_indikator;
		$data['kodefikasi'] = $result;

		$this->load->view("cik/cru_perprogram", $data);
	}

	function cru_perkegiatan(){
		$this->auth->restrict();
		//$this->output->enable_profiler(true);
		$id_program = $this->input->post('id_program');
		$bulan		= $this->input->post('bulan');
		$id 		= $this->input->post('id');

		$id_skpd = $this->session->userdata("id_skpd");
		$data['skpd'] = $this->m_skpd->get_one_skpd(array('id_skpd' => $id_skpd));
		$data['bulan'] = $bulan;
		$data['id_program'] = $id_program;

		$kd_kegiatan_edit = NULL;

		if (!empty($id)) {
			$result = $this->m_cik->get_cik_kegiatan($id,$bulan);
			if (empty($result)) {
				echo '<div style="width: 400px;">ERROR! Data tidak ditemukan.</div>';
				return FALSE;
			}
			$data['kegiatan'] = $result;
			$data['indikator_kegiatan'] = $this->m_cik->get_indikator_prog_keg_preview($id, $bulan,FALSE);
			$kd_kegiatan_edit = $result->kd_kegiatan;

		}
		$status_indikator = array("" => "~~ Pilih Positif / Negatif ~~");
		foreach ($this->m_lov->get_status_indikator() as $row) {
			$status_indikator[$row->kode_status_indikator]=$row->nama_status_indikator;
		}

		$kategori_indikator = array("" => "~~ Pilih Kategori Indikator ~~");
		foreach ($this->m_lov->get_kategori_indikator() as $row) {
			$kategori_indikator[$row->kode_kategori_indikator]=$row->nama_kategori_indikator;
		}

		$data['status_indikator'] = $status_indikator;
		$data['kategori_indikator'] = $kategori_indikator;
		$data['kodefikasi'] = $result;

		$this->load->view("cik/cru_perkegiatan", $data);
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
		$target = $this->input->post("target");

		$clean = array('id_kegiatan', 'id_indikator_kegiatan', 'indikator_kinerja', 'satuan_target','target');
		$data = $this->global_function->clean_array($data, $clean);
		$change = array('id_program'=>'parent');
		$data = $this->global_function->change_array($data, $change);

		if (!empty($id)) {
			$result = $this->m_cik->edit_kegiatan_skpd($data, $id, $indikator, $id_indikator_kegiatan, $satuan_target, $target);
		}else{
			$result = $this->m_cik->add_kegiatan_skpd($data, $indikator, $satuan_target, $target);
		}

		if ($result) {
			$msg = array('success' => '1', 'msg' => 'Kegiatan berhasil dibuat.');
			echo json_encode($msg);
		}else{
			$msg = array('success' => '0', 'msg' => 'ERROR! Kegiatan gagal dibuat, mohon menghubungi administrator.');
			echo json_encode($msg);
		}
	}

	function save_program_cik(){
		$this->auth->restrict();
		$id = $this->input->post('id_kegiatan');
		$id_bulan = $this->input->post('bulan');

		$data = $this->input->post();
		$id_skpd = $this->input->post("id_skpd");
		//$parent = $this->input->post("id_program");
		$tahun = $this->input->post("tahun");
		$indikator = $this->input->post("indikator_kinerja");
		$id_indikator_kegiatan = $this->input->post("id_indikator_kegiatan");
		$real = $this->input->post("real_".$id_bulan);
		//$realisasi = $this->post()

		$clean = array('id_kegiatan','indikator_kinerja','target','id_indikator_kegiatan','real_'.$id_bulan,'bulan', 'hasil');
		$data = $this->global_function->clean_array($data, $clean);

		$result = $this->m_cik->edit_program_cik($data, $id, $indikator, $id_indikator_kegiatan, $real, $id_bulan);



		if ($result) {
			$msg = array('success' => '1', 'msg' => 'Program CIK berhasil dibuat.');
			echo json_encode($msg);
		}else{
			$msg = array('success' => '0', 'msg' => 'ERROR! Program CIK gagal dibuat, mohon menghubungi administrator.');
			echo json_encode($msg);
		}
	}

	function save_kegiatan_cik(){
		$this->auth->restrict();
		$id = $this->input->post('id_kegiatan');
		$id_bulan = $this->input->post('bulan');

		$data = $this->input->post();
		$id_skpd = $this->input->post("id_skpd");
		$parent = $this->input->post("id_program");
		$tahun = $this->input->post("tahun");
		$indikator = $this->input->post("indikator_kinerja");
		$id_indikator_kegiatan = $this->input->post("id_indikator_kegiatan");
		$real = $this->input->post("real_".$id_bulan);

		$clean = array('id_kegiatan','indikator_kinerja','target','id_indikator_kegiatan','real_'.$id_bulan,'bulan', 'hasil');
		$data = $this->global_function->clean_array($data, $clean);
		$change = array('id_program'=>'parent');
		$data = $this->global_function->change_array($data, $change);

		$result = $this->m_cik->edit_kegiatan_cik($data, $id, $indikator, $id_indikator_kegiatan, $real, $id_bulan);


		if ($result) {
			$msg = array('success' => '1', 'msg' => 'Kegiatan CIK berhasil dibuat.');
			echo json_encode($msg);
		}else{
			$msg = array('success' => '0', 'msg' => 'ERROR! Kegiatan CIK gagal dibuat, mohon menghubungi administrator.');
			echo json_encode($msg);
		}
	}


	function delete_kegiatan(){
		$this->auth->restrict();
		$id = $this->input->post('id');
		$result = $this->m_cik->delete_kegiatan($id);
		if ($result) {
			$msg = array('success' => '1', 'msg' => 'Kegiatan berhasil dibuat.');
			echo json_encode($msg);
		}else{
			$msg = array('success' => '0', 'msg' => 'ERROR! Kegiatan gagal dibuat, mohon menghubungi administrator.');
			echo json_encode($msg);
		}
	}

	function preview_kegiatan_cik(){
		$id = $this->input->post("id");
		$result = $this->m_cik->get_one_kegiatan(NULL, $id, TRUE);
		if (!empty($result)) {
			$data['cik'] = $result;
			$data['indikator_kegiatan'] = $this->m_cik->get_indikator_prog_keg($result->id, TRUE, TRUE);
			$this->load->view('cik/preview', $data);
		}else{
			echo "Data tidak ditemukan . . .";
		}
	}

	//============================================================== lama ========================================================
	function view_cik()
	{
		$this->auth->restrict();
		$data['url_delete_data'] = site_url('cik/delete_cik');
		$this->template->load('template','cik/cik_view',$data);
	}

	function cru_cik()
	{
		$kd_urusan_edit = NULL;
		$kd_bidang_edit = NULL;
		$kd_program_edit = NULL;
		$kd_kegiatan_edit = NULL;
		$id_bulan_edit = NULL;

		$id_bulan = array("" => "");
		foreach ($this->m_bulan->get_bulan() as $row) {
			$id_bulan[$row->id] = $row->id .". ". $row->nama;
		}

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

		$data['id_bulan'] = form_dropdown('id_bulan', $id_bulan, $id_bulan_edit, 'data-placeholder="Pilih Bulan" class="common chosen-select" id="id_bulan"');
		$data['kd_urusan'] = form_dropdown('kd_urusan', $kd_urusan, $kd_urusan_edit, 'data-placeholder="Pilih Urusan" class="common chosen-select" id="kd_urusan"');
		$data['kd_bidang'] = form_dropdown('kd_bidang', $kd_bidang, $kd_bidang_edit, 'data-placeholder="Pilih Bidang Urusan" class="common chosen-select" id="kd_bidang"');
		$data['kd_program'] = form_dropdown('kd_program', $kd_program, $kd_program_edit, 'data-placeholder="Pilih Program" class="common chosen-select" id="kd_program"');
		$data['kd_kegiatan'] = form_dropdown('kd_kegiatan', $kd_kegiatan, $kd_kegiatan_edit, 'data-placeholder="Pilih Kegiatan" class="common chosen-select" id="kd_kegiatan"');

		$this->template->load('template','cik/cru_cik', $data);
	}

	function save_cik()
	{
		$id_cik		 	= $this->input->post('id_cik');
		$call_from		= $this->input->post('call_from');
		$id_bulan 		= $this->input->post('id_bulan');
		$kd_urusan		= $this->input->post('kd_urusan');
		$kd_bidang	 	= $this->input->post('kd_bidang');
		$kd_program	 	= $this->input->post('kd_program');
		$kd_kegiatan	= $this->input->post('kd_kegiatan');
		$anggaran_rencana	= $this->input->post('anggaran_rencana');
		$anggaran_realisasi	= $this->input->post('anggaran_realisasi');
		$capaian_ik		= $this->input->post('capaian_ik');
		$indikator	 	= $this->input->post('indikator');
		$indikator_rencana	= $this->input->post('indikator_rencana');
		$indikator_realisasi	= $this->input->post('indikator_realisasi');
		$ind_capaian_ik	= $this->input->post('ind_capaian_ik');
		$keterangan		= $this->input->post('keterangan');
		$ta 			= $this->m_settings->get_tahun_anggaran();
		$id_skpd 		= $this->session->userdata("id_skpd");

		if(strpos($call_from, 'cik/cru_cik') != FALSE) {
			$call_from = '';
		}

		$data_cik = $this->m_cik->get_cik_by_id($id_cik);
		if(empty($data_cik)) {
			//cek bank baru
			$data_cik = new stdClass();
			$id_cik = '';
		}

		$data_cik->id_cik				= $id_cik;
		$data_cik->id_bulan 			= $id_bulan;
		$data_cik->kd_urusan			= $kd_urusan;
		$data_cik->kd_bidang	 		= $kd_bidang;
		$data_cik->kd_program	 		= $kd_program;
		$data_cik->kd_kegiatan			= $kd_kegiatan;
		$data_cik->anggaran_rencana		= $anggaran_rencana;
		$data_cik->anggaran_realisasi	= $anggaran_realisasi;
		$data_cik->capaian_ik			= $capaian_ik;
		$data_cik->indikator			= $indikator;
		$data_cik->indikator_rencana	= $indikator_rencana;
		$data_cik->indikator_realisasi	= $indikator_realisasi;
		$data_cik->ind_capaian_ik		= $ind_capaian_ik;
		$data_cik->keterangan			= $keterangan;
		$data_cik->tahun 				= $ta;
		$data_cik->id_skpd 				= $id_skpd;

		$ret = TRUE;
		if(empty($id_cik)) {
			//insert
			$ret = $this->m_cik->simpan_cik($data_cik);
			//echo $this->db->last_query();
		} else {
			//update
			$ret = $this->m_cik->update_cik($data_cik, $id_cik, 'table_cik', 'primary_cik');
			//echo $this->db->last_query();
		}
		if ($ret === FALSE){
            $this->session->set_userdata('msg_typ','err');
            $this->session->set_userdata('msg', 'Data CIK gagal disimpan');
		} else {
            $this->session->set_userdata('msg_typ','ok');
            $this->session->set_userdata('msg', 'Data CIK Berhasil disimpan');
		}

		if(!empty($call_from))
			redirect($call_from);

        redirect('cik');
	}

	function load_cik()
	{
		$search = $this->input->post("search");
		$start = $this->input->post("start");
		$length = $this->input->post("length");
		$order = $this->input->post("order");

		$cik = $this->m_cik->get_data_table($search, $start, $length, $order["0"]);
		$alldata = $this->m_cik->count_data_table($search, $start, $length, $order["0"]);

		$data = array();
		$no=0;


		foreach ($cik as $row) {
			$no++;
			$data[] = array(
							$no,
							$row->kd_urusan.".".
							$row->kd_bidang.".".
                            $row->kd_program.".".
                            $row->kd_kegiatan,
                            $row->nm_bulan,
                            $row->anggaran_rencana,
                            $row->anggaran_realisasi,
                            $row->capaian_ik,
                            $row->indikator,
                            $row->indikator_rencana,
                            $row->indikator_realisasi,
                            $row->ind_capaian_ik,
                            $row->keterangan,
							'<a href="javascript:void(0)" onclick="edit_cik('. $row->id_cik .')" class="icon2-page_white_edit" title="Edit CIK"/>
							<a href="javascript:void(0)" onclick="delete_cik('. $row->id_cik .')" class="icon2-delete" title="Hapus CIK"/>'
							);
		}
		$json = array("recordsTotal"=> $alldata, "recordsFiltered"=> $alldata, 'data' => $data);

        echo json_encode($json);
	}

	function edit_cik($id_cik)
	{
		//$this->output->enable_profiler(TRUE);
        $this->auth->restrict();
        //$data['url_save_data'] = site_url('cik/save_cik');

        $data['isEdit'] = FALSE;
        if (!empty($id_cik)) {
            $data_ = array('id_cik'=>$id_cik);
            $result = $this->m_cik->get_data_with_rincian($id_cik,'table_cik');
			if (empty($result)) {
				$this->session->set_userdata('msg_typ','err');
				$this->session->set_userdata('msg', 'Data musrenbang tidak ditemukan.');
				redirect('cik');
			}

            $data['id_cik']				= $result->id_cik;
    		$data['anggaran_rencana'] 	= $result->anggaran_rencana;
    		$data['anggaran_realisasi'] = $result->anggaran_realisasi;
    		$data['capaian_ik'] 		= $result->capaian_ik;
    		$data['indikator'] 			= $result->indikator;
    		$data['indikator_rencana'] 	= $result->indikator_rencana;
    		$data['indikator_realisasi'] = $result->indikator_realisasi;
    		$data['ind_capaian_ik'] 	= $result->ind_capaian_ik;
    		$data['keterangan'] 		= $result->keterangan;

            $data['isEdit']				= TRUE;

            $id_bulan_edit	= $result->id_bulan;
            $kd_urusan_edit = $result->kd_urusan;
    		$kd_bidang_edit = $result->kd_bidang;
    		$kd_program_edit = $result->kd_program;
    		$kd_kegiatan_edit = $result->kd_kegiatan;

            //prepare combobox

            $id_bulan = array("" => "");
    		foreach ($this->m_bulan->get_bulan() as $row) {
    			$id_bulan[$row->id] = $row->id .". ". $row->nama;
    		}
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

    		$data['id_bulan'] = form_dropdown('id_bulan', $id_bulan, $id_bulan_edit, 'data-placeholder="Pilih Bulan" class="common chosen-select" id="id_bulan"');
    		$data['kd_urusan'] = form_dropdown('kd_urusan', $kd_urusan, $kd_urusan_edit, 'data-placeholder="Pilih Urusan" class="common chosen-select" id="kd_urusan"');
    		$data['kd_bidang'] = form_dropdown('kd_bidang', $kd_bidang, $kd_bidang_edit, 'data-placeholder="Pilih Bidang Urusan" class="common chosen-select" id="kd_bidang"');
    		$data['kd_program'] = form_dropdown('kd_program', $kd_program, $kd_program_edit, 'data-placeholder="Pilih Program" class="common chosen-select" id="kd_program"');
    		$data['kd_kegiatan'] = form_dropdown('kd_kegiatan', $kd_kegiatan, $kd_kegiatan_edit, 'data-placeholder="Pilih Kegiatan" class="common chosen-select" id="kd_kegiatan"');

		}
        $this->template->load('template','cik/cru_cik',$data);

	}

	function delete_cik()
	{
        $id = $this->input->post('id');

        $result = $this->m_cik->delete_cik($id);
        if ($result) {
			$msg = array('success' => '1', 'msg' => 'CIK berhasil dihapus.');
			echo json_encode($msg);
		}else{
			$msg = array('success' => '0', 'msg' => 'ERROR! CIK gagal dihapus, mohon menghubungi administrator.');
			echo json_encode($msg);
		}
	}

	private function cetak_cik_func($id_skpd)
	{
		$data['header_type'] = "CIK";
		/*$protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://';
		$header = $this->m_template_cetak->get_value("GAMBAR");
		$data['logo'] = str_replace("src=\"","height=\"90px\" src=\"".$protocol.$_SERVER['HTTP_HOST'],$header);
		$data['header'] = $this->m_template_cetak->get_value("HEADER");*/

		$data['skpd'] = $this->m_skpd->get_one_skpd(array('id_skpd' => $id_skpd));
		$data['cik'] = $this->load->view('cik/cetak/isi_cik',$data1,TRUE);
		//var_dump($data['cik']);
		return $data;
	}

	function do_cetak_cik($id_skpd=NULL)
	{
		ini_set('memory_limit','-1');
		if(empty($id_skpd)){
			$id_skpd = $this->session->userdata('id_skpd');
		}

		$data = $this->cetak_cik_func($id_skpd);
		$data['qr'] = $this->ciqrcode->generateQRcode("sirenbangda", 'CIK '. $this->session->userdata('nama_skpd') ." ". date("d-m-Y_H-i-s"), 1);
		$html = $this->template->load('template_cetak', 'cik/cetak/cetak', $data, TRUE);

		$filename = 'CIK '.$this->session->userdata('nama_skpd')." ".date("d-m-Y_H-i_s").'.pdf';
		pdf_create($html,$filename,"A4","Landscape");
	}

	function preview_cik($bulan=null)
	{
		//$this->output->enable_profiler(true);
		$this->auth->restrict();
		//$id_skpd = $this->session->userdata('id_skpd');

		//$all_bulan = $this->m_bulan->get_data_dropdown_bulan(NULL);
		//$data['dd_bulan'] = form_dropdown('dd_bulan', $all_bulan, NULL, 'class="span6 m-wrap" id="appendedInputButton" style="margin: 0;"');
		$data['bulan'] = $bulan;
		$this->template->load('template', 'cik/preview_cik',$data);
	}

	function get_data_cik(){
		//$this->output->enable_profiler(true);
		$id_bulan = $this->input->post("id_bulan");
		$id_skpd = $this->session->userdata("id_skpd");
		$ta = $this->session->userdata("t_anggaran_aktif");

		$data['ta']	= $ta;
		$data['bulan'] = $id_bulan;
		$data['id_skpd'] = $id_skpd;
		$tot_prog = $this->m_cik->sum_capaian_program($id_skpd,$id_bulan,$ta);
		$count_prog = $this->m_cik->count_program($id_skpd,$id_bulan,$ta);
		$tot_keg = $this->m_cik->sum_capaian_kegiatan($id_skpd,$id_bulan,$ta);
		$count_keg = $this->m_cik->count_kegiatan($id_skpd,$id_bulan,$ta);

		$data['tot_prog'] = 0;
		$data['tot_keg'] = 0;
		if (!empty($tot_prog->capaianp)) {
			$data['tot_prog'] = $tot_prog->capaianp/$count_prog->countp;
		}
		if (!empty($tot_keg->capaiank)) {
			$data['tot_keg'] = $tot_keg->capaiank/$count_keg->countk;
		}
	
		$data['urusan'] = $this->m_cik->get_urusan_cik($id_bulan,$ta,$id_skpd);
		//$data['program'] = $this->m_cik->get_program_cik($id_skpd,$id_bulan,$ta);
		//echo $this->db->last_query();
		$this->load->view('cik/cetak/isi_cik', $data);
	}

	function view_rekapitulasi_cik()
	{
		$this->auth->restrict();
		$id_skpd = $this->session->userdata('id_skpd');
		$tahun = $this->session->userdata('t_anggaran_aktif');
		$data['program'] = $this->m_cik->get_program_rekap_cik_4_cetak($id_skpd,$tahun);
		$this->template->load('template','cik/rekapitulasi_cik_view',$data);
	}

	private function cetak_rekapitulasi_cik($id_skpd)
	{
		$tahun = $this->session->userdata('t_anggaran_aktif');
		//$data['kendali_type'] = "KENDALI BELANJA";
		$protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://';
		$header = $this->m_template_cetak->get_value("GAMBAR");
		$data['logo'] = str_replace("src=\"","height=\"90px\" src=\"".$protocol.$_SERVER['HTTP_HOST'],$header);
		$data['header'] = $this->m_template_cetak->get_value("HEADER");

		$data1['program'] = $this->m_cik->get_program_rekap_cik_4_cetak($id_skpd,$tahun);
		$data['cik'] = $this->load->view('cik/cetak/isi_rekap_cik', $data1, TRUE);
		return $data;
	}

	function do_cetak_rekap_cik()
	{
		ini_set('memory_limit','-1');
			if(empty($id_skpd)) {
				$id_skpd = $this->session->userdata('id_skpd');
			}

			$data = $this->cetak_rekapitulasi_cik($id_skpd);
			$data['qr'] = $this->ciqrcode->generateQRcode("sirenbangda", 'Rekapitulasi CIK '. $this->session->userdata('nama_skpd') ." ". date("d-m-Y_H-i-s"), 1);
			$html = $this->template->load('template_cetak', 'cik/cetak/cetak', $data, TRUE);

			$filename = 'rekapitulasi_cik'. $this->session->userdata('nama_skpd') ." ". date("d-m-Y_H-i_s") .'.pdf';
			pdf_create($html,$filename,"A4","Landscape");
	}

	private function cetak_preview_cik($id_skpd,$id_bulan,$tahun)
	{
		/*$tahun = $this->session->userdata('t_anggaran_aktif');
		$data['kendali_type'] = "KENDALI BELANJA";
		$protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://';
		$header = $this->m_template_cetak->get_value("GAMBAR");
		$data['logo'] = str_replace("src=\"","height=\"90px\" src=\"".$protocol.$_SERVER['HTTP_HOST'],$header);$skpd_detail = $this->m_skpd->get_one_skpd(array('id_skpd' => $id_skpd));
		$data['header'] = "<p>". strtoupper($skpd_detail->nama_skpd) ."<BR>KABUPATEN KLUNGKUNG, PROVINSI BALI - INDONESIA<BR>".$skpd_detail->alamat."<BR>Telp.".$skpd_detail->telp_skpd."<p>";
		$data['logo'] = "";
		$data['header'] = "";*/
		$data1['bulan'] = $id_bulan;
		$data1['urusan'] = $this->db->query("
			SELECT pro.*,
			SUM(keg.realisasi_".$id_bulan.") AS realisasi,
			SUM(keg.rencana) AS rencana,
			pro.capaian_".$id_bulan." AS capaian,
			u.Nm_Urusan AS nama_urusan
			  FROM
				(SELECT * FROM tx_cik_prog_keg WHERE is_prog_or_keg=1) AS pro
			  INNER JOIN
				(SELECT * FROM tx_cik_prog_keg WHERE is_prog_or_keg=2) AS keg ON keg.parent=pro.id
			LEFT JOIN m_urusan AS u
			ON pro.kd_urusan = u.Kd_Urusan
			WHERE
				keg.id_skpd =".$id_skpd."
			  AND keg.tahun = ".$tahun."
			  GROUP BY keg.kd_urusan
			  ORDER BY kd_urusan ASC, kd_bidang ASC, kd_program ASC, kd_kegiatan ASC;
		")->result();
		$data1['id_skpd'] = $id_skpd;
		$data1['tahun'] = $tahun;
		$tot_prog = $this->m_cik->sum_capaian_program($id_skpd,$id_bulan,$tahun);
		$count_prog = $this->m_cik->count_program($id_skpd,$id_bulan,$tahun);
		$tot_keg = $this->m_cik->sum_capaian_kegiatan($id_skpd,$id_bulan,$tahun);
		$count_keg = $this->m_cik->count_kegiatan($id_skpd,$id_bulan,$tahun);
		$data1['tot_prog'] = $tot_prog->capaianp/$count_prog->countp;
		$data1['tot_keg'] = $tot_keg->capaiank/$count_keg->countk;
		//$data1['program'] = $this->m_cik->get_program_cik_4_cetak($id_skpd,$id_bulan,$tahun);
		$data['bulan'] = $id_bulan;
		$data['skpd'] = $this->m_skpd->get_one_skpd(array('id_skpd' => $id_skpd));
		$data['cik'] = $this->load->view('cik/cetak/isi_preview_cik', $data1, TRUE);
		//echo $data['cik'];
		return $data;
	}

	function do_cetak_preview($id_skpd=NULL)
	{
		ini_set('memory_limit','-1');
		if(empty($id_skpd)){
			$id_skpd = $this->session->userdata('id_skpd');
			$id_bulan = $this->input->post("id_bulan");
			$tahun = $this->session->userdata('t_anggaran_aktif');
		}
		$data = $this->cetak_preview_cik($id_skpd,$id_bulan,$tahun);
		$data['qr'] = $this->ciqrcode->generateQRcode("sirenbangda", 'CIK '. $this->session->userdata('nama_skpd') ." ". date("d-m-Y_H-i-s"), 1);
		$html = $this->template->load('template_cetak', 'cik/cetak/cetak', $data, TRUE);

		$filename = 'CIK '.$this->session->userdata('nama_skpd')." ".date("d-m-Y_H-i_s").'.pdf';
		pdf_create($html,$filename,"A4","Landscape");
	}

	//===========================================================================================//
	//  Fungsi Verifikasi CIK																	 //
	//===========================================================================================//

	function kirim_cik(){
		$this->auth->restrict();
		$ta = $this->m_settings->get_tahun_anggaran();
		$id_skpd = $this->session->userdata('id_skpd');
		$bulan = $this->input->post('bulan');

		$data['tahun'] = $ta;
		$data['bulan'] = $bulan;
		$data['id_skpd'] = $id_skpd;

		$this->load->view('cik/kirim_cik', $data);
	}

	function do_kirim_cik(){
		$id_skpd = $this->input->post('id_skpd');
		$bulan = $this->input->post('bulan');
		$ta = $this->m_settings->get_tahun_anggaran();
		$result = $this->m_cik->kirim_cik($id_skpd,$bulan,$ta);
		//echo $this->db->last_query();
		if ($result) {
			$msg = array('success' => '1', 'msg' => 'CIK berhasil dikirim.');
			echo json_encode($msg);
		}else{
			$msg = array('success' => '0', 'msg' => 'ERROR! CIK gagal dikirim, mohon menghubungi administrator.');
			echo json_encode($msg);
		}
	}

	function veri_view_cik($bulan=null)
	{
		$this->auth->restrict();
		//$this->output->enable_profiler(true);
		//$data['ciks'] = $this->m_cik->get_all_cik_veri();
		$data['bulan'] = $bulan;
		$this->template->load('template','cik/verifikasi/view_all',$data);
	}

	function veri_view_cik_readonly($bulan=null)
	{
		$this->auth->restrict();
		//$this->output->enable_profiler(true);
		//$data['ciks'] = $this->m_cik->get_all_cik_veri();
		$data['bulan'] = $bulan;
		$this->template->load('template','cik/verifikasi/view_all_readonly',$data);
	}

	function veri_cik($id_skpd,$bulan)
	{
		$this->auth->restrict();
		$ta = $this->session->userdata("t_anggaran_aktif");

		//$data['program'] = $this->m_cik->get_data_program_cik($id_skpd,$bulan);
		$data['urusan'] = $this->m_cik->get_data_urusan_cik($id_skpd,$bulan);
		$data['bulan'] = $bulan;
		$data['id_skpd'] = $id_skpd;
		$tot_prog = $this->m_cik->sum_capaian_program($id_skpd,$bulan,$ta);
		$count_prog = $this->m_cik->count_program($id_skpd,$bulan,$ta);
		$tot_keg = $this->m_cik->sum_capaian_kegiatan($id_skpd,$bulan,$ta);
		$count_keg = $this->m_cik->count_kegiatan($id_skpd,$bulan,$ta);
		$data['tot_prog'] = $tot_prog->capaianp/$count_prog->countp;
		$data['tot_keg'] = $tot_keg->capaiank/$count_keg->countk;
		$this->template->load('template','cik/verifikasi/view', $data);
	}

	function veri_cik_readonly($id_skpd,$bulan)
	{
		$this->auth->restrict();
		$ta = $this->session->userdata("t_anggaran_aktif");

		//$data['program'] = $this->m_cik->get_data_program_cik($id_skpd,$bulan);
		$data['urusan'] = $this->m_cik->get_data_urusan_cik_readonly($id_skpd,$bulan);
		$data['bulan'] = $bulan;
		$data['id_skpd'] = $id_skpd;
		$tot_prog = $this->m_cik->sum_capaian_program($id_skpd,$bulan,$ta);
		$count_prog = $this->m_cik->count_program($id_skpd,$bulan,$ta);
		$tot_keg = $this->m_cik->sum_capaian_kegiatan($id_skpd,$bulan,$ta);
		$count_keg = $this->m_cik->count_kegiatan($id_skpd,$bulan,$ta);
		$data['tot_prog'] = $tot_prog->capaianp/$count_prog->countp;
		$data['tot_keg'] = $tot_keg->capaiank/$count_keg->countk;
		$this->template->load('template','cik/verifikasi/view_readonly', $data);
	}

	function get_data_cik_veri(){
		$this->auth->restrict();
		//$this->output->enable_profiler(true);
		$id_bulan = $this->input->post("id_bulan");
		$id_skpd = $this->input->post("id_skpd");
		$ta = $this->session->userdata("t_anggaran_aktif");

		$data['ta']	= $ta;
		$data['bulan'] = $id_bulan;
		$data['program'] = $this->m_cik->get_program_cik_4_cetak($id_skpd,$id_bulan,$ta);
		//echo $this->db->last_query();
		$this->load->view('cik/cetak/isi_veri_cik', $data);
	}

	function upload_datacik(){
		$this->auth->restrict();
		$bulan		= $this->input->post('bulan');
		$id 		= $this->input->post('id');

		$data['bulan'] = $bulan;
		$data['id'] = $id;

		$result = $this->m_cik->get_cik_kegiatan($id,$bulan);
		if (empty($result)) {
			echo '<div style="width: 400px;">ERROR! Data tidak ditemukan.</div>';
			return FALSE;
		}
		$data['kodefikasi'] = $result;
		$data['skpd'] = $this->m_skpd->get_one_skpd(array('id_skpd' => $result->id_skpd));
		//$data['indikator_kegiatan'] = $this->m_cik->get_indikator_prog_keg_preview($id, $bulan,FALSE);

		$selector_file = "file_".$bulan;
		$mp_filefiles				= $this->get_file(explode( ',', $this->db->query("select ".$selector_file." from tx_cik_prog_keg where id ='".$data['id'] ."'")->row()->$selector_file), TRUE);
		$data['mp_jmlfile']			= $mp_filefiles->num_rows();
		$data['mp_filefiles']		= $mp_filefiles->result();
		//var_dump($result);
		$this->load->view('cik/upload_datacik',$data);
	}

	/**
	* Function File Upload
	*/

	function save_file_upload_cik(){
		$this->auth->restrict();
		$date=date("Y-m-d");
        $time=date("H:i:s");

		$id = $this->input->post('id');
		$bulan = $this->input->post('bulan');
		$selector_file = 'file_'.$bulan;
		//Persiapan folder berdasarkan unit
		$dir_file_upload='file_upload/cik';
		if (!file_exists($dir_file_upload)) {
			mkdir($dir_file_upload, 0766, true);
		}

		//UPLOAD
		$this->load->library('upload');
		$config = array();
		$directory = dirname($_SERVER["SCRIPT_FILENAME"]).'/'.$dir_file_upload;
		$config['upload_path'] = $directory;
		$config['allowed_types'] = 'jpeg|jpg|png|pdf|xls|doc|docx|xlsx';
		$config['max_size'] = '2048';
		$config['overwrite'] = FALSE;

		$id_userfile 	= $this->input->post("id_userfile");
		$name_file 	= $this->input->post("name_file");
		$ket_file	= $this->input->post("ket_file");
		$files = $_FILES;
		$cpt = $this->input->post("upload_length");
		//var_dump($files);
		$hapus	= $this->input->post("hapus_file");
		$name_file_arr = array();
		$id_file_arr = array();

		for($i=1; $i<=$cpt; $i++)
		{
			if (empty($files['userfile']['name'][$i]) && empty($id_userfile[$i])) {
				continue;
			}elseif (empty($files['userfile']['name'][$i]) && !empty($id_userfile[$i])) {
				$update_var = array('name'=> $name_file[$i],'ket'=>$ket_file[$i]);
				$this->update_file($id_userfile[$i], $update_var);
				continue;
			}

			//$file_name=date("Ymd_His");

			$_FILES['userfile']['name']= $files['userfile']['name'][$i];
			$_FILES['userfile']['type']= $files['userfile']['type'][$i];
			$_FILES['userfile']['tmp_name']= $files['userfile']['tmp_name'][$i];
			$_FILES['userfile']['error']= $files['userfile']['error'][$i];
			$_FILES['userfile']['size']= $files['userfile']['size'][$i];

			$this->upload->initialize($config);
			$file = $this->upload->do_upload();
			//var_dump($this->upload->display_errors('<p>', '</p>'));
			//var_dump($this->upload->data());
			if ($file) {
				$file = $this->upload->data();
				$file = $file['file_name'];
				if (!empty($id_userfile[$i])) {
					$hapus[] = 	$id_userfile[$i];
				}
				$id_file_arr[] = $this->add_file($file, $name_file[$i], $ket_file[$i], $dir_file_upload."/".$file);
				$name_file_arr[] = $file;
			} else {
				// Error Occured in one of the uploads
				if (empty($id) || (!empty($_FILES['userfile']['name']) && !empty($id))) {
					foreach ($id_file_arr as $value) {
						$this->delete_file($value);
					}
					foreach ($name_file_arr as $value) {
						unlink($directory.$value);
					}
					$error_upload	= "Draft Usulan gagal disimpan, terdapat kesalahan pada upload file atau file upload tidak sesuai dengan ketentuan.";
					$this->session->set_userdata('msg_typ','err');
					$this->session->set_userdata('msg', $error_upload);
					//var_dump($file);
					//redirect('home');
				}
			}
		}

		$hasil_cik = $this->db->query("select * from tx_cik_prog_keg where id='$id'")->row();
		if(empty($hasil_cik)) {
			$hasil_cik = new stdClass();
		}

		if (!empty($hasil_cik->$selector_file)) {
			$id_file_arr_old = explode(",", $hasil_cik->$selector_file);
			if (!empty($hapus)) {
				foreach ($hapus as $row) {
					$key = array_search($row, $id_file_arr_old);
					unset($id_file_arr_old[$key]);

					$var_hapus = $this->get_one_file($row);
					//echo $this->db->last_query();
					unlink($directory.'/'.$var_hapus->$selector_file);
					$this->delete_file($row);
				}
			}
			foreach ($id_file_arr_old as $value) {
				$id_file_arr[] = $value;
			}
		}

		if (!empty($id_file_arr)) {
			$data_post['file'] = implode(",", $id_file_arr);
		}


		//$result = $this->m_kendali_belanja->kinerja_triwulan($id,$id_dpa_prog_keg_triwulan,$catatan,$keterangan,$capaian);

		//var_dump($data_post);
		//update
		$sql = "update tx_cik_prog_keg set `".$selector_file."` = '".$data_post['file']."' where id='$id'";
		$result = $this->db->query($sql);


		if ($result) {
			$msg = array('success' => '1', 'msg' => 'Program berhasil dibuat.');
			//echo json_encode($msg);
			$this->session->set_userdata('msg_typ','ok');
			$this->session->set_userdata('msg', 'Program berhasil dibuat');
			//var_dump($file);
			redirect('cik/preview_cik');
		}else{
			$msg = array('success' => '0', 'msg' => 'ERROR! Program gagal dibuat, mohon menghubungi administrator.');
			//echo json_encode($msg);
			$this->session->set_userdata('msg_typ','err');
			$this->session->set_userdata('msg', 'ERROR! Program gagal dibuat, mohon menghubungi administrator.');
			redirect('cik/preview_cik');
		}

	}

	function add_file($file, $name, $ket, $location){
		$this->db->set('file', $file);
		$this->db->set('name', $name);
		$this->db->set('ket', $ket);
		$this->db->set('location', $location);
		$this->db->insert('t_upload_file_cik');
		return $this->db->insert_id();
	}

	function update_file($id, $data){
		$this->db->where('id', $id);
		$result = $this->db->update('t_upload_file_cik', $data);
		return $result;
	}

	function delete_file($id){
		$this->db->where("id", $id);
		$result = $this->db->delete('t_upload_file_cik');
		return $result;
	}

	function get_file($id = array(), $only = FALSE){
		$this->db->where_in("id", $id);
		$this->db->from('t_upload_file_cik');
		$result = $this->db->get();
		if ($only) {
			return $result;
		}else{
			return $result->result();
		}
	}

	function get_one_file($id){
		$this->db->where("id", $id);
		$this->db->from('t_upload_file_cik');
		$result = $this->db->get();
		return $result->row();
	}
	//end

	function cik_pusat(){
		$this->auth->restrict();
		$this->template->load('template', 'cik/preview_cik_pusat');
	}

	function get_data_cik_pusat(){
		$this->auth->restrict();
		//$this->output->enable_profiler(true);
		$id_bulan = $this->input->post("id_bulan");
		$ta = $this->session->userdata("t_anggaran_aktif");

		$data['ta']	= $ta;
		$data['bulan'] = $id_bulan;
		$data['urusan'] = $this->m_cik->get_urusan_cik_pusat($id_bulan,$ta);
		$tot_prog = $this->m_cik->sum_capaian_program_pusat($id_bulan,$ta);
		$count_prog = $this->m_cik->count_program_pusat($id_bulan,$ta);
		$tot_keg = $this->m_cik->sum_capaian_kegiatan_pusat($id_bulan,$ta);
		$count_keg = $this->m_cik->count_kegiatan_pusat($id_bulan,$ta);
		$data['tot_prog'] = $tot_prog->capaianp/$count_prog->countp;
		$data['tot_keg'] = $tot_keg->capaiank/$count_keg->countk;
		//echo $this->db->last_query();
		$this->load->view('cik/cetak/isi_cik_pusat', $data);
	}

	private function cetak_preview_cik_pusat($id_bulan,$tahun)
	{
		//$tahun = $this->session->userdata('t_anggaran_aktif');
		//$data['kendali_type'] = "KENDALI BELANJA";
		//$protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://';
		//$header = $this->m_template_cetak->get_value("GAMBAR");
		//$data['logo'] = str_replace("src=\"","height=\"90px\" src=\"".$protocol.$_SERVER['HTTP_HOST'],$header);
		$skpd_detail = $this->m_skpd->get_one_skpd(array('id_skpd' => $id_skpd));
		//$data['header'] = "<p>". strtoupper($skpd_detail->nama_skpd) ."<BR>KABUPATEN KLUNGKUNG, PROVINSI BALI - INDONESIA<BR>".$skpd_detail->alamat."<BR>Telp.".$skpd_detail->telp_skpd."<p>";

		$data1['bulan'] = $id_bulan;
		$data1['urusan'] = $this->m_cik->get_urusan_cik_pusat($id_bulan,$tahun);
		//$data1['id_skpd'] = $id_skpd;
		$data1['ta'] = $tahun;
		//$data1['program'] = $this->m_cik->get_program_cik_4_cetak($id_skpd,$id_bulan,$tahun);
		$data['bulan'] = $id_bulan;
		$data['cik'] = $this->load->view('cik/cetak/isi_cik_pusat', $data1, TRUE);
		return $data;
	}

	function do_cetak_preview_pusat($id_skpd=NULL)
	{
		ini_set('memory_limit','-1');
		if(empty($id_skpd)){
			$id_bulan = $this->input->post("id_bulan");
			$tahun = $this->session->userdata('t_anggaran_aktif');
		}
		$data = $this->cetak_preview_cik_pusat($id_bulan,$tahun);
		$data['qr'] = $this->ciqrcode->generateQRcode("sirenbangda", 'CIK '. $this->session->userdata('nama_skpd') ." ". date("d-m-Y_H-i-s"), 1);
		$html = $this->template->load('template_cetak', 'cik/cetak/cetak_pusat', $data, TRUE);

		$filename = 'CIK '.$this->session->userdata('nama_skpd')." ".date("d-m-Y_H-i_s").'.pdf';
		pdf_create($html,$filename,"A4","Landscape");
	}

	function do_export_pusat($bulan=NULL){
		if (empty($bulan)) {
			echo "<i>Error...</i>";
		}

		$this->auth->restrict();

		ini_set('memory_limit','-1');

		$this->load->library('Export_excel');

		$ta = $this->session->userdata('t_anggaran_aktif');

		$this->export_excel->create_header(array(
													"Kode",
													NULL,
													NULL,
													NULL,
													"Program dan Kegiatan",
													"Anggaran",
													NULL,
													NULL,
													"Kelompok Indikator Kinerja Program (Outcome) / Indikator Kinerja Kegiatan (Output)",
													NULL,
													NULL,
													NULL,
													"Ket"
												)
											);

		$this->export_excel->create_header(array(
													NULL,
													NULL,
													NULL,
													NULL,
													NULL,
													"Rencana (Rp)",
													"Realisasi (Rp)",
													"Capaian IK (%)",
													"Indikator / Satuan",
													"Rencana",
													"Realisasi",
													"Capaian IK"
												)
											);

		$this->export_excel->merge_cell('A1','D2');
		$this->export_excel->merge_cell('E1','E2');
		$this->export_excel->merge_cell('F1','H1');
		$this->export_excel->merge_cell('I1','L1');
		$this->export_excel->merge_cell('M1','M2');

		$urusan = $this->m_cik->get_urusan_cik_pusat($bulan, $ta);

		$max_col_keg=1;
		$tot_rencana=0; $tot_realisasi=0;
		foreach($urusan as $row_urusan){
			if($row_urusan->id == ""){
				$this->export_excel->set_row(array("Data Belum Terisikan.."));
				$this->export_excel->merge_cell('A'.$this->export_excel->get_last_row(),'M'.$this->export_excel->get_last_row());
			}else{
				$p=0;
				$k=0;
				$tot_rencana_urusan=0;
				$tot_realisasi_urusan=0;
				$bidang = $this->m_cik->get_bidang_cik_pusat_4_cetak($row_urusan->kd_urusan, $bulan, $ta);
				$cik_pro_keg_urusan = (empty($row_urusan->sumrealisasi))?0:round(($row_urusan->sumrealisasi/$row_urusan->sumrencana)*100,2);

				$tot_rencana_urusan+=$row_urusan->sumrencana;
				$tot_realisasi_urusan+=$row_urusan->sumrealisasi;

				$this->export_excel->set_row(array($row_urusan->kd_urusan, NULL, NULL, NULL, $row_urusan->nama_urusan, Formatting::currency(round($row_urusan->sumrencana,2)), Formatting::currency(round($row_urusan->sumrealisasi,2)), round($cik_pro_keg_urusan,2), NULL, NULL, NULL, NULL, "-"));
				$this->export_excel->merge_cell('I'.$this->export_excel->get_last_row(),'L'.$this->export_excel->get_last_row());
				$this->export_excel->getActiveSheet()->getStyle("A".$this->export_excel->get_last_row().":M".$this->export_excel->get_last_row())->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => '78cbfd'))));
		    	$this->export_excel->getActiveSheet()->getStyle("F".$this->export_excel->get_last_row().":H".$this->export_excel->get_last_row())->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				foreach($bidang as $row_bidang){
					$tot_rencana_bidang = 0;
					$tot_realisasi_bidang = 0;
					$p_bidang = 0;
					$k_bidang = 0;
					$skpd = $this->m_cik->get_skpd_cik_pusat_4_cetak($row_urusan->kd_urusan,$row_bidang->kd_bidang,$bulan,$ta);
					$cik_pro_keg_bidang = (empty($row_bidang->sumrealisasi))?0:round(($row_bidang->sumrealisasi/$row_bidang->sumrencana)*100,2);
					$tot_rencana_bidang += $row_bidang->sumrencana;
					$tot_realisasi_bidang += $row_bidang->sumrealisasi;

					$this->export_excel->set_row(array($row_urusan->kd_urusan, $row_bidang->kd_bidang, NULL, NULL, $row_bidang->nama_bidang, Formatting::currency(round($row_bidang->sumrencana,2)), Formatting::currency(round($row_bidang->sumrealisasi,2)), round($cik_pro_keg_bidang,2), NULL, NULL, NULL, NULL, "-"));
					$this->export_excel->merge_cell('I'.$this->export_excel->get_last_row(),'L'.$this->export_excel->get_last_row());
					$this->export_excel->getActiveSheet()->getStyle("A".$this->export_excel->get_last_row().":M".$this->export_excel->get_last_row())->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => '00FF33'))));
					$this->export_excel->getActiveSheet()->getStyle("F".$this->export_excel->get_last_row().":H".$this->export_excel->get_last_row())->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

					foreach($skpd as $row_skpd){
						$program = $this->m_cik->get_program_cik_pusat_4_cetak($row_urusan->kd_urusan,$row_bidang->kd_bidang,$row_skpd->id_skpd,$bulan,$ta);
						$cik_pro_keg_skpd = (empty($row_skpd->sumrealisasi))?0:round(($row_skpd->sumrealisasi/$row_skpd->sumrencana)*100,2);

						$this->export_excel->set_row(array(strtoupper($row_skpd->nama_skpd), NULL, NULL, NULL, NULL, Formatting::currency(round($row_skpd->sumrencana,2)), Formatting::currency(round($row_skpd->sumrealisasi,2)), round($cik_pro_keg_skpd,2), NULL, NULL, NULL, NULL, NULL));
						$this->export_excel->merge_cell('A'.$this->export_excel->get_last_row(),'E'.$this->export_excel->get_last_row());
						$this->export_excel->merge_cell('I'.$this->export_excel->get_last_row(),'M'.$this->export_excel->get_last_row());
						$this->export_excel->getActiveSheet()->getStyle("A".$this->export_excel->get_last_row().":M".$this->export_excel->get_last_row())->getFont()->setBold(true);
						$this->export_excel->getActiveSheet()->getStyle("F".$this->export_excel->get_last_row().":H".$this->export_excel->get_last_row())->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

						foreach($program as $row_program){
							$p++;
							$p_bidang++;
							$tot_rencana += $row_program->sumrencana;
							$tot_realisasi += $row_program->sumrealisasi;
							$kegiatan = $this->m_cik->get_kegiatan_cik_pusat_4_cetak($row_urusan->kd_urusan,$row_bidang->kd_bidang,$row_program->kd_program,$row_skpd->id_skpd,$bulan, $ta);

							$indikator_program = $this->m_cik->get_indikator_prog_keg_preview($row_program->id, $bulan, FALSE, TRUE);
							$temp = $indikator_program->result();
							$total_temp = $indikator_program->num_rows();
							$cik_pro_keg_program = (empty($row_program->sumrealisasi))?0:round(($row_program->sumrealisasi/$row_program->sumrencana)*100,2);
							$total_for_iteration = $total_temp;

							$realisasi_temp = (empty($temp[0]->realisasi)) ? 0 :$temp[0]->realisasi;
							$this->export_excel->set_row(array($row_urusan->kd_urusan, $row_bidang->kd_bidang, $row_program->kd_program, NULL, $row_program->nama_prog_or_keg, Formatting::currency(round($row_program->sumrencana,2)), Formatting::currency(round($row_program->sumrealisasi,2)), round($cik_pro_keg_program,2), $temp[0]->indikator, $temp[0]->target, $realisasi_temp, $row_program->capaian, "-"));

							$this->export_excel->getActiveSheet()->getStyle("A".$this->export_excel->get_last_row().":M".($this->export_excel->get_last_row()+$total_for_iteration-1))->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'FF8000'))));
                            $this->export_excel->getActiveSheet()->getStyle("F".$this->export_excel->get_last_row().":H".$this->export_excel->get_last_row())->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
							$this->export_excel->getActiveSheet()->getStyle("J".$this->export_excel->get_last_row().":L".$this->export_excel->get_last_row())->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
							if ($total_for_iteration > 1) {
                            	$this->export_excel->merge_cell('A'.$this->export_excel->get_last_row(),'A'.($this->export_excel->get_last_row()+$total_for_iteration-1));
								$this->export_excel->merge_cell('B'.$this->export_excel->get_last_row(),'B'.($this->export_excel->get_last_row()+$total_for_iteration-1));
								$this->export_excel->merge_cell('C'.$this->export_excel->get_last_row(),'C'.($this->export_excel->get_last_row()+$total_for_iteration-1));
								$this->export_excel->merge_cell('D'.$this->export_excel->get_last_row(),'D'.($this->export_excel->get_last_row()+$total_for_iteration-1));
								$this->export_excel->merge_cell('E'.$this->export_excel->get_last_row(),'E'.($this->export_excel->get_last_row()+$total_for_iteration-1));
								$this->export_excel->merge_cell('F'.$this->export_excel->get_last_row(),'F'.($this->export_excel->get_last_row()+$total_for_iteration-1));
								$this->export_excel->merge_cell('G'.$this->export_excel->get_last_row(),'G'.($this->export_excel->get_last_row()+$total_for_iteration-1));
								$this->export_excel->merge_cell('H'.$this->export_excel->get_last_row(),'H'.($this->export_excel->get_last_row()+$total_for_iteration-1));
								$this->export_excel->merge_cell('L'.$this->export_excel->get_last_row(),'L'.($this->export_excel->get_last_row()+$total_for_iteration-1));
								$this->export_excel->merge_cell('M'.$this->export_excel->get_last_row(),'M'.($this->export_excel->get_last_row()+$total_for_iteration-1));
                                for ($i=1; $i < $total_for_iteration; $i++) {
                                	$realisasi_temp = (empty($temp[$i]->realisasi)) ? 0 :$temp[$i]->realisasi;
									$this->export_excel->set_row(array(NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, $temp[$i]->indikator, $temp[$i]->target, $realisasi_temp, NULL, NULL));
									$this->export_excel->getActiveSheet()->getStyle("F".$this->export_excel->get_last_row().":H".$this->export_excel->get_last_row())->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
									$this->export_excel->getActiveSheet()->getStyle("J".$this->export_excel->get_last_row().":L".$this->export_excel->get_last_row())->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
								}
							}

							foreach ($kegiatan as $row_kegiatan) {
								$k++;
								$k_bidang++;
								$indikator_kegiatan = $this->m_cik->get_indikator_prog_keg_preview($row_kegiatan->id, $bulan, FALSE, TRUE);
								$temp = $indikator_kegiatan->result();
								$total_temp = $indikator_kegiatan->num_rows();
								$total_for_iteration = $total_temp;

								$cik_pro_keg_kegiatan = (empty($row_kegiatan->realisasi)) ? 0 :round(($row_kegiatan->realisasi/$row_kegiatan->rencana)*100,2);
								$realisasi_keg_temp = (empty($temp[0]->realisasi)) ? 0 :$temp[0]->realisasi;
								$this->export_excel->set_row(array($row_urusan->kd_urusan, $row_bidang->kd_bidang, $row_program->kd_program, $row_kegiatan->kd_kegiatan, $row_kegiatan->nama_prog_or_keg, Formatting::currency(round($row_kegiatan->rencana,2)), Formatting::currency(round($row_kegiatan->realisasi,2)), round($cik_pro_keg_kegiatan,2), $temp[0]->indikator, $temp[0]->target, $realisasi_keg_temp, round($row_kegiatan->capaian,2), "-"));
								$this->export_excel->getActiveSheet()->getStyle("F".$this->export_excel->get_last_row().":H".$this->export_excel->get_last_row())->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
								$this->export_excel->getActiveSheet()->getStyle("J".$this->export_excel->get_last_row().":L".$this->export_excel->get_last_row())->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

	                            if ($total_for_iteration > 1) {
	                            	$this->export_excel->merge_cell('A'.$this->export_excel->get_last_row(),'A'.($this->export_excel->get_last_row()+$total_for_iteration-1));
									$this->export_excel->merge_cell('B'.$this->export_excel->get_last_row(),'B'.($this->export_excel->get_last_row()+$total_for_iteration-1));
									$this->export_excel->merge_cell('C'.$this->export_excel->get_last_row(),'C'.($this->export_excel->get_last_row()+$total_for_iteration-1));
									$this->export_excel->merge_cell('D'.$this->export_excel->get_last_row(),'D'.($this->export_excel->get_last_row()+$total_for_iteration-1));
									$this->export_excel->merge_cell('E'.$this->export_excel->get_last_row(),'E'.($this->export_excel->get_last_row()+$total_for_iteration-1));
									$this->export_excel->merge_cell('F'.$this->export_excel->get_last_row(),'F'.($this->export_excel->get_last_row()+$total_for_iteration-1));
									$this->export_excel->merge_cell('G'.$this->export_excel->get_last_row(),'G'.($this->export_excel->get_last_row()+$total_for_iteration-1));
									$this->export_excel->merge_cell('H'.$this->export_excel->get_last_row(),'H'.($this->export_excel->get_last_row()+$total_for_iteration-1));
									$this->export_excel->merge_cell('L'.$this->export_excel->get_last_row(),'L'.($this->export_excel->get_last_row()+$total_for_iteration-1));
									$this->export_excel->merge_cell('M'.$this->export_excel->get_last_row(),'M'.($this->export_excel->get_last_row()+$total_for_iteration-1));

	                                for ($i=1; $i < $total_for_iteration; $i++) {
	                                	$realisasi_keg_temp = (empty($temp[$i]->realisasi)) ? 0 :$temp[$i]->realisasi;
	                                	$this->export_excel->set_row(array(NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, $temp[$i]->indikator, $temp[$i]->target, round($realisasi_keg_temp,2), NULL, NULL));
										$this->export_excel->getActiveSheet()->getStyle("F".$this->export_excel->get_last_row().":H".$this->export_excel->get_last_row())->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
										$this->export_excel->getActiveSheet()->getStyle("J".$this->export_excel->get_last_row().":L".$this->export_excel->get_last_row())->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
									}
								}
							}
						}
					}
						$tot_prog_bidang = $this->m_cik->sum_capaian_program_bidang($row_urusan->kd_urusan,$row_bidang->kd_bidang,$bulan,$ta);
						$count_prog_bidang = $this->m_cik->count_program_bidang($row_urusan->kd_urusan,$row_bidang->kd_bidang,$bulan,$ta);
						$tot_keg_bidang = $this->m_cik->sum_capaian_kegiatan_bidang($row_urusan->kd_urusan,$row_bidang->kd_bidang,$bulan,$ta);
						$count_keg_bidang = $this->m_cik->count_kegiatan_bidang($row_urusan->kd_urusan,$row_bidang->kd_bidang,$bulan,$ta);
						$tot_prog_count_bidang = $tot_prog_bidang->capaianp/$count_prog_bidang->countp;
						$tot_keg_count_bidang = $tot_keg_bidang->capaiank/$count_keg_bidang->countk;
						$sisa_bidang = $tot_rencana_bidang-$tot_realisasi_bidang;

						$this->export_excel->set_row(array(NULL, NULL, NULL, NULL, "JUMLAH PROGRAM BIDANG ".strtoupper($row_bidang->nama_bidang), $p_bidang." Program", NULL, NULL, NULL, NULL, NULL, NULL, NULL));
						$this->export_excel->merge_cell('F'.$this->export_excel->get_last_row(),'M'.$this->export_excel->get_last_row());
						$this->export_excel->merge_cell('A'.$this->export_excel->get_last_row(),'D'.$this->export_excel->get_last_row());
						$this->export_excel->getActiveSheet()->getStyle("A".$this->export_excel->get_last_row().":M".$this->export_excel->get_last_row())->getFont()->setBold(true);
						$this->export_excel->getActiveSheet()->getStyle("F".$this->export_excel->get_last_row().":M".$this->export_excel->get_last_row())->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
						$this->export_excel->getActiveSheet()->getStyle("A".$this->export_excel->get_last_row().":M".$this->export_excel->get_last_row())->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'CCCCCC'))));

						$this->export_excel->set_row(array(NULL, NULL, NULL, NULL, "JUMLAH KEGIATAN BIDANG ".strtoupper($row_bidang->nama_bidang), $k_bidang." Kegiatan", NULL, NULL, NULL, NULL, NULL, NULL, NULL));
						$this->export_excel->merge_cell('F'.$this->export_excel->get_last_row(),'M'.$this->export_excel->get_last_row());
						$this->export_excel->merge_cell('A'.$this->export_excel->get_last_row(),'D'.$this->export_excel->get_last_row());
						$this->export_excel->getActiveSheet()->getStyle("A".$this->export_excel->get_last_row().":M".$this->export_excel->get_last_row())->getFont()->setBold(true);
						$this->export_excel->getActiveSheet()->getStyle("F".$this->export_excel->get_last_row().":M".$this->export_excel->get_last_row())->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
						$this->export_excel->getActiveSheet()->getStyle("A".$this->export_excel->get_last_row().":M".$this->export_excel->get_last_row())->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'CCCCCC'))));

						$tot_realisasi_bidang_temp = (empty($tot_realisasi_bidang)) ? 0 :round(($tot_realisasi_bidang/$tot_rencana_bidang)*100,2);
						$this->export_excel->set_row(array(NULL, NULL, NULL, NULL, "JUMLAH", Formatting::currency($tot_rencana_bidang,2), Formatting::currency($tot_realisasi_bidang,2), $tot_realisasi_bidang_temp, "Rata-rata Capaian Program", NULL, NULL, round($tot_prog_count_bidang,2), NULL));
						$this->export_excel->merge_cell('I'.$this->export_excel->get_last_row(),'K'.$this->export_excel->get_last_row());
						$this->export_excel->merge_cell('A'.$this->export_excel->get_last_row(),'D'.$this->export_excel->get_last_row());
						$this->export_excel->getActiveSheet()->getStyle("A".$this->export_excel->get_last_row().":M".$this->export_excel->get_last_row())->getFont()->setBold(true);
						$this->export_excel->getActiveSheet()->getStyle("F".$this->export_excel->get_last_row().":H".$this->export_excel->get_last_row())->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
						$this->export_excel->getActiveSheet()->getStyle("L".$this->export_excel->get_last_row())->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
						$this->export_excel->getActiveSheet()->getStyle("A".$this->export_excel->get_last_row().":M".$this->export_excel->get_last_row())->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'CCCCCC'))));

						$sisa_realisasi_bidang_temp = (empty($sisa_bidang)) ? 0 :round(($sisa_bidang/$tot_rencana_bidang)*100,2);
						$this->export_excel->set_row(array(NULL, NULL, NULL, NULL, "SISA", Formatting::currency($sisa_bidang,2), NULL, Formatting::currency($sisa_realisasi_bidang_temp,2), "Rata-rata Capaian Kegiatan", NULL, NULL, round($tot_keg_count_bidang,2), NULL));
						$this->export_excel->merge_cell('F'.$this->export_excel->get_last_row(),'G'.$this->export_excel->get_last_row());
						$this->export_excel->merge_cell('I'.$this->export_excel->get_last_row(),'K'.$this->export_excel->get_last_row());
						$this->export_excel->merge_cell('A'.$this->export_excel->get_last_row(),'D'.$this->export_excel->get_last_row());
						$this->export_excel->getActiveSheet()->getStyle("A".$this->export_excel->get_last_row().":M".$this->export_excel->get_last_row())->getFont()->setBold(true);
						$this->export_excel->getActiveSheet()->getStyle("F".$this->export_excel->get_last_row().":H".$this->export_excel->get_last_row())->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
						$this->export_excel->getActiveSheet()->getStyle("L".$this->export_excel->get_last_row())->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
						$this->export_excel->getActiveSheet()->getStyle("A".$this->export_excel->get_last_row().":M".$this->export_excel->get_last_row())->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'CCCCCC'))));

				}
				$tot_prog_urusan = $this->m_cik->sum_capaian_program_urusan($row_urusan->kd_urusan,$bulan,$ta);
				$count_prog_urusan = $this->m_cik->count_program_urusan($row_urusan->kd_urusan,$bulan,$ta);
				$tot_keg_urusan = $this->m_cik->sum_capaian_kegiatan_urusan($row_urusan->kd_urusan,$bulan,$ta);
				$count_keg_urusan = $this->m_cik->count_kegiatan_urusan($row_urusan->kd_urusan,$bulan,$ta);
				$tot_prog_count = $tot_prog_urusan->capaianp/$count_prog_urusan->countp;
				$tot_keg_count = $tot_keg_urusan->capaiank/$count_keg_urusan->countk;
				$sisa_urusan = $tot_rencana_urusan-$tot_realisasi_urusan;

				$this->export_excel->set_row(array(NULL, NULL, NULL, NULL, "JUMLAH PROGRAM ".strtoupper($row_urusan->nama_urusan), $p." Program", NULL, NULL, NULL, NULL, NULL, NULL, NULL));
				$this->export_excel->merge_cell('F'.$this->export_excel->get_last_row(),'M'.$this->export_excel->get_last_row());
				$this->export_excel->merge_cell('A'.$this->export_excel->get_last_row(),'D'.$this->export_excel->get_last_row());
				$this->export_excel->getActiveSheet()->getStyle("A".$this->export_excel->get_last_row().":M".$this->export_excel->get_last_row())->getFont()->setBold(true);
				$this->export_excel->getActiveSheet()->getStyle("F".$this->export_excel->get_last_row().":M".$this->export_excel->get_last_row())->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				$this->export_excel->getActiveSheet()->getStyle("A".$this->export_excel->get_last_row().":M".$this->export_excel->get_last_row())->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'FFFF00'))));

				$this->export_excel->set_row(array(NULL, NULL, NULL, NULL, "JUMLAH KEGIATAN ".strtoupper($row_urusan->nama_urusan), $k." Kegiatan", NULL, NULL, NULL, NULL, NULL, NULL, NULL));
				$this->export_excel->merge_cell('F'.$this->export_excel->get_last_row(),'M'.$this->export_excel->get_last_row());
				$this->export_excel->merge_cell('A'.$this->export_excel->get_last_row(),'D'.$this->export_excel->get_last_row());
				$this->export_excel->getActiveSheet()->getStyle("A".$this->export_excel->get_last_row().":M".$this->export_excel->get_last_row())->getFont()->setBold(true);
				$this->export_excel->getActiveSheet()->getStyle("F".$this->export_excel->get_last_row().":M".$this->export_excel->get_last_row())->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				$this->export_excel->getActiveSheet()->getStyle("A".$this->export_excel->get_last_row().":M".$this->export_excel->get_last_row())->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'FFFF00'))));

				$tot_realisasi_temp = (empty($tot_realisasi_urusan)) ? 0 :round(($tot_realisasi_urusan/$tot_rencana_urusan)*100,2);
				$this->export_excel->set_row(array(NULL, NULL, NULL, NULL, "JUMLAH", Formatting::currency($tot_rencana_urusan,2), Formatting::currency($tot_realisasi_urusan,2), $tot_realisasi_temp, "Rata-rata Capaian Program", NULL, NULL, round($tot_prog_count,2), NULL));
				$this->export_excel->merge_cell('I'.$this->export_excel->get_last_row(),'K'.$this->export_excel->get_last_row());
				$this->export_excel->merge_cell('A'.$this->export_excel->get_last_row(),'D'.$this->export_excel->get_last_row());
				$this->export_excel->getActiveSheet()->getStyle("A".$this->export_excel->get_last_row().":M".$this->export_excel->get_last_row())->getFont()->setBold(true);
				$this->export_excel->getActiveSheet()->getStyle("F".$this->export_excel->get_last_row().":H".$this->export_excel->get_last_row())->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				$this->export_excel->getActiveSheet()->getStyle("L".$this->export_excel->get_last_row())->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				$this->export_excel->getActiveSheet()->getStyle("A".$this->export_excel->get_last_row().":M".$this->export_excel->get_last_row())->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'FFFF00'))));

				$sisa_realisasi_temp = (empty($sisa_urusan)) ? 0 :round(($sisa_urusan/$tot_rencana_urusan)*100,2);
				$this->export_excel->set_row(array(NULL, NULL, NULL, NULL, "SISA", Formatting::currency($sisa_urusan,2), NULL, Formatting::currency($sisa_realisasi_temp,2), "Rata-rata Capaian Kegiatan", NULL, NULL, round($tot_keg_count,2), NULL));
				$this->export_excel->merge_cell('F'.$this->export_excel->get_last_row(),'G'.$this->export_excel->get_last_row());
				$this->export_excel->merge_cell('I'.$this->export_excel->get_last_row(),'K'.$this->export_excel->get_last_row());
				$this->export_excel->merge_cell('A'.$this->export_excel->get_last_row(),'D'.$this->export_excel->get_last_row());
				$this->export_excel->getActiveSheet()->getStyle("A".$this->export_excel->get_last_row().":M".$this->export_excel->get_last_row())->getFont()->setBold(true);
				$this->export_excel->getActiveSheet()->getStyle("F".$this->export_excel->get_last_row().":H".$this->export_excel->get_last_row())->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				$this->export_excel->getActiveSheet()->getStyle("L".$this->export_excel->get_last_row())->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
				$this->export_excel->getActiveSheet()->getStyle("A".$this->export_excel->get_last_row().":M".$this->export_excel->get_last_row())->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'FFFF00'))));
			}
		}

		$tot_prog = $this->m_cik->sum_capaian_program_pusat($bulan,$ta);
		$count_prog = $this->m_cik->count_program_pusat($bulan,$ta);
		$tot_keg = $this->m_cik->sum_capaian_kegiatan_pusat($bulan,$ta);
		$count_keg = $this->m_cik->count_kegiatan_pusat($bulan,$ta);
		$tot_prog = $tot_prog->capaianp/$count_prog->countp;
		$tot_keg = $tot_keg->capaiank/$count_keg->countk;

		$tot_realisasi_temp = (empty($tot_realisasi)) ? 0 :round(($tot_realisasi/$tot_rencana)*100,2);
		$this->export_excel->set_row(array(NULL, NULL, NULL, NULL, "TOTAL JUMLAH", Formatting::currency($tot_rencana,2), Formatting::currency($tot_realisasi,2), $tot_realisasi_temp, "Rata-rata Capaian Program", NULL, NULL, round($tot_prog,2), NULL));
		$this->export_excel->merge_cell('I'.$this->export_excel->get_last_row(),'K'.$this->export_excel->get_last_row());
		$this->export_excel->merge_cell('A'.$this->export_excel->get_last_row(),'D'.$this->export_excel->get_last_row());
		$this->export_excel->getActiveSheet()->getStyle("A".$this->export_excel->get_last_row().":M".$this->export_excel->get_last_row())->getFont()->setBold(true);
		$this->export_excel->getActiveSheet()->getStyle("F".$this->export_excel->get_last_row().":H".$this->export_excel->get_last_row())->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$this->export_excel->getActiveSheet()->getStyle("L".$this->export_excel->get_last_row())->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$this->export_excel->getActiveSheet()->getStyle("A".$this->export_excel->get_last_row().":M".$this->export_excel->get_last_row())->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => '#78cbfd'))));

		$sisa = $tot_rencana-$tot_realisasi;
		$sisa_realisasi_temp = (empty($sisa)) ? 0 :round(($sisa/$tot_rencana)*100,2);
		$this->export_excel->set_row(array(NULL, NULL, NULL, NULL, "TOTAL SISA", Formatting::currency($sisa,2), NULL, Formatting::currency($sisa_realisasi_temp,2), "Rata-rata Capaian Kegiatan", NULL, NULL, round($tot_keg,2), NULL));
		$this->export_excel->merge_cell('F'.$this->export_excel->get_last_row(),'G'.$this->export_excel->get_last_row());
		$this->export_excel->merge_cell('I'.$this->export_excel->get_last_row(),'K'.$this->export_excel->get_last_row());
		$this->export_excel->merge_cell('A'.$this->export_excel->get_last_row(),'D'.$this->export_excel->get_last_row());
		$this->export_excel->getActiveSheet()->getStyle("A".$this->export_excel->get_last_row().":M".$this->export_excel->get_last_row())->getFont()->setBold(true);
		$this->export_excel->getActiveSheet()->getStyle("F".$this->export_excel->get_last_row().":H".$this->export_excel->get_last_row())->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$this->export_excel->getActiveSheet()->getStyle("L".$this->export_excel->get_last_row())->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$this->export_excel->getActiveSheet()->getStyle("A".$this->export_excel->get_last_row().":M".$this->export_excel->get_last_row())->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => '#78cbfd'))));

		$this->export_excel->filename = "CIK Pusat ". $bulan ."-".$ta." ";

		$this->export_excel->getActiveSheet()->getColumnDimension($this->export_excel->get_cell_name(1))->setAutoSize(true);
		$this->export_excel->getActiveSheet()->getColumnDimension($this->export_excel->get_cell_name(2))->setAutoSize(true);
		$this->export_excel->getActiveSheet()->getColumnDimension($this->export_excel->get_cell_name(3))->setAutoSize(true);
		$this->export_excel->getActiveSheet()->getColumnDimension($this->export_excel->get_cell_name(4))->setAutoSize(true);
		$this->export_excel->getActiveSheet()->getColumnDimension($this->export_excel->get_cell_name(5))->setWidth(75);
		$this->export_excel->getActiveSheet()->getColumnDimension($this->export_excel->get_cell_name(6))->setAutoSize(true);
		$this->export_excel->getActiveSheet()->getColumnDimension($this->export_excel->get_cell_name(7))->setAutoSize(true);
		$this->export_excel->getActiveSheet()->getColumnDimension($this->export_excel->get_cell_name(8))->setAutoSize(true);
		$this->export_excel->getActiveSheet()->getColumnDimension($this->export_excel->get_cell_name(9))->setWidth(45);
		$this->export_excel->getActiveSheet()->getColumnDimension($this->export_excel->get_cell_name(10))->setAutoSize(true);
		$this->export_excel->getActiveSheet()->getColumnDimension($this->export_excel->get_cell_name(11))->setAutoSize(true);
		$this->export_excel->getActiveSheet()->getColumnDimension($this->export_excel->get_cell_name(12))->setAutoSize(true);

        $this->export_excel->getActiveSheet()->getStyle("I1:L1")->getAlignment()->setWrapText(true);
        $this->export_excel->getActiveSheet()->getStyle("E". $this->export_excel->first_col .":E".$this->export_excel->last_col)->getAlignment()->setWrapText(true);
        $this->export_excel->getActiveSheet()->getStyle("I". $this->export_excel->first_col .":I".$this->export_excel->last_col)->getAlignment()->setWrapText(true);

        $this->export_excel->set_border("A".$this->export_excel->first_col.":M".$this->export_excel->last_col);

		$id_skpd = 1;
		$skpd = $this->m_skpd->get_one_skpd(array('id_skpd' => $id_skpd));
		$styleBorder = array(
		  'borders' => array(
			'allborders' => array(
			  'style' => PHPExcel_Style_Border::BORDER_NONE
			)
		  )
		);
		$styleUnderline = array(
		  'font' => array(
			'underline' => PHPExcel_Style_Font::UNDERLINE_SINGLE
		  )
		);
		switch(date('F')){
			case 'January';
			default:
				$bulan="Januari";
				break;
			case 'February';
			default:
				$bulan="Februari";
				break;
			case 'March';
			default:
				$bulan="Maret";
				break;
			case 'April';
			default:
				$bulan="April";
				break;
			case 'May';
			default:
				$bulan="Mei";
				break;
			case 'June';
			default:
				$bulan="Juni";
				break;
			case 'July';
			default:
				$bulan="Juli";
				break;
			case 'August';
			default:
				$bulan="Agustus";
				break;
			case 'September';
			default:
				$bulan="September";
				break;
			case 'October';
			default:
				$bulan="Oktober";
				break;
			case 'November';
			default:
				$bulan="November";
				break;
			case 'December';
			default:
				$bulan="Desember";
				break;
		}
		$this->export_excel->set_row(array(NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL));
		$this->export_excel->merge_cell('A'.$this->export_excel->get_last_row(),'M'.$this->export_excel->get_last_row());
		$this->export_excel->getActiveSheet()->getStyle("A".$this->export_excel->get_last_row().":M".$this->export_excel->get_last_row())->applyFromArray($styleBorder);

		$this->export_excel->set_row(array(NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, "Semarapura, ".date('j')." ".$bulan." ".date('Y'), NULL, NULL, NULL, NULL));
		$this->export_excel->merge_cell('A'.$this->export_excel->get_last_row(),'H'.$this->export_excel->get_last_row());
		$this->export_excel->merge_cell('I'.$this->export_excel->get_last_row(),'L'.$this->export_excel->get_last_row());
		$this->export_excel->getActiveSheet()->getStyle("I".$this->export_excel->get_last_row())->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->export_excel->getActiveSheet()->getStyle("A".$this->export_excel->get_last_row().":M".$this->export_excel->get_last_row())->applyFromArray($styleBorder);

		$this->export_excel->set_row(array(NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, $skpd->nama_jabatan, NULL, NULL, NULL, NULL));
		$this->export_excel->merge_cell('A'.$this->export_excel->get_last_row(),'H'.$this->export_excel->get_last_row());
		$this->export_excel->merge_cell('I'.$this->export_excel->get_last_row(),'L'.$this->export_excel->get_last_row());
		$this->export_excel->getActiveSheet()->getStyle("I".$this->export_excel->get_last_row())->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->export_excel->getActiveSheet()->getStyle("A".$this->export_excel->get_last_row().":M".$this->export_excel->get_last_row())->applyFromArray($styleBorder);

		$this->export_excel->set_row(array(NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL));
		$this->export_excel->merge_cell('A'.$this->export_excel->get_last_row(),'M'.$this->export_excel->get_last_row());
		$this->export_excel->getActiveSheet()->getStyle("A".$this->export_excel->get_last_row().":M".$this->export_excel->get_last_row())->applyFromArray($styleBorder);
		$this->export_excel->set_row(array(NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL));
		$this->export_excel->merge_cell('A'.$this->export_excel->get_last_row(),'M'.$this->export_excel->get_last_row());
		$this->export_excel->getActiveSheet()->getStyle("A".$this->export_excel->get_last_row().":M".$this->export_excel->get_last_row())->applyFromArray($styleBorder);
		$this->export_excel->set_row(array(NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL));
		$this->export_excel->merge_cell('A'.$this->export_excel->get_last_row(),'M'.$this->export_excel->get_last_row());
		$this->export_excel->getActiveSheet()->getStyle("A".$this->export_excel->get_last_row().":M".$this->export_excel->get_last_row())->applyFromArray($styleBorder);
		$this->export_excel->set_row(array(NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL));
		$this->export_excel->merge_cell('A'.$this->export_excel->get_last_row(),'M'.$this->export_excel->get_last_row());
		$this->export_excel->getActiveSheet()->getStyle("A".$this->export_excel->get_last_row().":M".$this->export_excel->get_last_row())->applyFromArray($styleBorder);
		$this->export_excel->set_row(array(NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL));
		$this->export_excel->merge_cell('A'.$this->export_excel->get_last_row(),'M'.$this->export_excel->get_last_row());
		$this->export_excel->getActiveSheet()->getStyle("A".$this->export_excel->get_last_row().":M".$this->export_excel->get_last_row())->applyFromArray($styleBorder);

		$this->export_excel->set_row(array(NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, $skpd->kaskpd_nama, NULL, NULL, NULL, NULL));
		$this->export_excel->merge_cell('A'.$this->export_excel->get_last_row(),'H'.$this->export_excel->get_last_row());
		$this->export_excel->merge_cell('I'.$this->export_excel->get_last_row(),'L'.$this->export_excel->get_last_row());
		$this->export_excel->getActiveSheet()->getStyle("I".$this->export_excel->get_last_row())->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->export_excel->getActiveSheet()->getStyle("I".$this->export_excel->get_last_row())->applyFromArray($styleUnderline);
		$this->export_excel->getActiveSheet()->getStyle("A".$this->export_excel->get_last_row().":M".$this->export_excel->get_last_row())->applyFromArray($styleBorder);

		$this->export_excel->set_row(array(NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, $skpd->pangkat_golongan, NULL, NULL, NULL, NULL));
		$this->export_excel->merge_cell('A'.$this->export_excel->get_last_row(),'H'.$this->export_excel->get_last_row());
		$this->export_excel->merge_cell('I'.$this->export_excel->get_last_row(),'L'.$this->export_excel->get_last_row());
		$this->export_excel->getActiveSheet()->getStyle("I".$this->export_excel->get_last_row())->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->export_excel->getActiveSheet()->getStyle("A".$this->export_excel->get_last_row().":M".$this->export_excel->get_last_row())->applyFromArray($styleBorder);

		$this->export_excel->set_row(array(NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, "NIP. ".$skpd->kaskpd_nip, NULL, NULL, NULL, NULL));
		$this->export_excel->merge_cell('A'.$this->export_excel->get_last_row(),'H'.$this->export_excel->get_last_row());
		$this->export_excel->merge_cell('I'.$this->export_excel->get_last_row(),'L'.$this->export_excel->get_last_row());
		$this->export_excel->getActiveSheet()->getStyle("I".$this->export_excel->get_last_row())->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->export_excel->getActiveSheet()->getStyle("A".$this->export_excel->get_last_row().":M".$this->export_excel->get_last_row())->applyFromArray($styleBorder);

        $this->export_excel->set_readonly();

		$this->export_excel->execute();
	}

	function get_veri_cik(){
		//$this->output->enable_profiler(true);
		$id_bulan = $this->input->post("id_bulan");
		$ta = $this->session->userdata("t_anggaran_aktif");

		$data['ta']	= $ta;
		$data['bulan'] = $id_bulan;
		$data['skpd'] = $this->m_cik->get_all_cik_veri($id_bulan);

		//echo $this->db->last_query();
		$this->load->view('cik/verifikasi/isi_veri_cik', $data);
	}

	function get_veri_cik_readonly(){
		//$this->output->enable_profiler(true);
		$id_bulan = $this->input->post("id_bulan");
		$ta = $this->session->userdata("t_anggaran_aktif");

		$data['ta']	= $ta;
		$data['bulan'] = $id_bulan;
		$data['skpd'] = $this->m_cik->get_all_cik_veri_readonly($id_bulan);
		//echo $this->db->last_query();
		$this->load->view('cik/verifikasi/isi_veri_cik_readonly', $data);
	}

	function do_veri(){
		$this->auth->restrict();
		$id = $this->input->post('id');
		$bulan = $this->input->post('bulan');
		$action = $this->input->post('action');

		$data['cik'] = $this->m_cik->get_one_cik_veri($id);
		$cik = $data['cik'];
		$data['indikator'] = $this->m_cik->get_indikator_prog_keg($cik->id, TRUE, TRUE);
		if ($action=="pro") {
			$data['program'] = TRUE;
		}else{
			$data['program'] = FALSE;
		}
		$data['bulan'] = $bulan;

		//file upload

		$selector_file = "file_".$bulan;
		$mp_filefiles				= $this->get_file(explode( ',', $this->db->query("select ".$selector_file." from tx_cik_prog_keg where id ='".$id."'")->row()->$selector_file), TRUE);
		$data['mp_jmlfile']			= $mp_filefiles->num_rows();
		$data['mp_filefiles']		= $mp_filefiles->result();

		$this->load->view('cik/verifikasi/veri', $data);
	}

	function save_veri(){
		$this->auth->restrict();
		$id = $this->input->post("id");
		$bulan = $this->input->post("bulan");
		$veri = $this->input->post("veri");
		$ket = $this->input->post("ket");

		if ($veri == "setuju") {
			$result = $this->m_cik->approved_cik($id,$bulan);
		}elseif ($veri == "tdk_setuju") {
			$result = $this->m_cik->not_approved_cik($id,$bulan,$ket);
		}

		if ($result) {
			$msg = array('success' => '1', 'msg' => 'Kegiatan berhasil diverifikasi.');
			echo json_encode($msg);
		}else{
			$msg = array('success' => '0', 'msg' => 'ERROR! Kegiatan gagal diverifikasi, mohon menghubungi administrator.');
			echo json_encode($msg);
		}
	}

	function disapprove_cik(){
		$this->auth->restrict();
		$data['id'] = $this->input->post('id');
		$bulan = $this->input->post("bulan");
		$data['bulan'] = $bulan;
		$this->load->view('cik/verifikasi/disapprove_cik', $data);
	}

	function do_disapprove_cik(){
		$this->auth->restrict_ajax_login();

		$id = $this->input->post('id');
		$bulan = $this->input->post('bulan');
		$ket = $this->input->post('ket');
		$result = $this->m_cik->disapprove_cik($id, $bulan, $ket);
		echo json_encode(array('success' => '1', 'msg' => 'CIK telah ditolak.', 'href' => site_url('cik/veri_view_cik')));
	}

	function cik_pusat_per_skpd(){
		$this->auth->restrict();

		$id_skpd = array("all" => "~~ Semua SKPD ~~");
        foreach ($this->m_skpd->get_skpd_chosen() as $row) {
            $id_skpd[$row->id] = $row->label;
        }
        $data['cmb_skpd'] = form_dropdown('id_skpd', $id_skpd, NULL,'data-placeholder="Pilih SKPD" class="common chosen-select" id="id_skpd"');

		$this->template->load('template', 'cik/preview_cik_pusat_skpd', $data);
	}

	function get_data_cik_pusat_skpd(){
		//$this->output->enable_profiler(true);
		$id_bulan = $this->input->post("id_bulan");
		$ta = $this->session->userdata("t_anggaran_aktif");
		$id_skpd = $this->input->post("id_skpd");

		$data['ta']	= $ta;
		$data['bulan'] = $id_bulan;
		$tot_prog = $this->m_cik->sum_capaian_program_pusat($id_bulan,$ta);
		$count_prog = $this->m_cik->count_program_pusat($id_bulan,$ta);
		$tot_keg = $this->m_cik->sum_capaian_kegiatan_pusat($id_bulan,$ta);
		$count_keg = $this->m_cik->count_kegiatan_pusat($id_bulan,$ta);
		$data['tot_prog'] = $tot_prog->capaianp/$count_prog->countp;
		$data['tot_keg'] = $tot_keg->capaiank/$count_keg->countk;
		//$data['urusan'] = $this->m_cik->get_urusan_cik_pusat($id_bulan,$ta);
		$data['skpd'] = $this->m_cik->get_skpd($id_bulan,$ta, $id_skpd);
		$data['id_skpd'] = $id_skpd;
		//echo $this->db->last_query();
		$this->load->view('cik/cetak/isi_cik_pusat_skpd', $data);
	}

	function do_export_pusat_skpd($id_skpd=NULL, $bulan=NULL){
		if (empty($bulan)) {
			echo "<i>Error...</i>";
		}

		$this->auth->restrict();

		ini_set('memory_limit','-1');

		$this->load->library('Export_excel');

		$ta = $this->session->userdata('t_anggaran_aktif');

		$this->export_excel->create_header(array(
													"Kode",
													NULL,
													NULL,
													NULL,
													"Program dan Kegiatan",
													"Anggaran",
													NULL,
													NULL,
													"Kelompok Indikator Kinerja Program (Outcome) / Indikator Kinerja Kegiatan (Output)",
													NULL,
													NULL,
													NULL,
													"Ket"
												)
											);

		$this->export_excel->create_header(array(
													NULL,
													NULL,
													NULL,
													NULL,
													NULL,
													"Rencana (Rp)",
													"Realisasi (Rp)",
													"Capaian IK (%)",
													"Indikator / Satuan",
													"Rencana",
													"Realisasi",
													"Capaian IK"
												)
											);

		$this->export_excel->merge_cell('A1','D2');
		$this->export_excel->merge_cell('E1','E2');
		$this->export_excel->merge_cell('F1','H1');
		$this->export_excel->merge_cell('I1','L1');
		$this->export_excel->merge_cell('M1','M2');

		$skpd = $this->m_cik->get_skpd($bulan, $ta, $id_skpd);

		$max_col_keg=1;
		$tot_rencana=0;
		$tot_realisasi=0;
		$p_total=0;
		$k_total=0;
		foreach ($skpd as $row_skpd){
			$tot_rencana_skpd=0;
			$tot_realisasi_skpd=0;
			$p=0;
			$k=0;
			$urusan = $this->m_cik->get_urusan_cik($bulan,$ta,$row_skpd->id_skpd);
			$cik_pro_keg_skpd = (empty($row_skpd->sumrealisasi))?0:round(($row_skpd->sumrealisasi/$row_skpd->sumrencana)*100,2);

			$this->export_excel->set_row(array($row_skpd->nama_skpd, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL));
			$this->export_excel->merge_cell('A'.$this->export_excel->get_last_row(),'M'.$this->export_excel->get_last_row());
			$this->export_excel->getActiveSheet()->getStyle("A".$this->export_excel->get_last_row().":M".$this->export_excel->get_last_row())->getFont()->setBold(true);
			$this->export_excel->getActiveSheet()->getStyle("A".$this->export_excel->get_last_row().":M".$this->export_excel->get_last_row())->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'FFFF00'))));

			foreach($urusan as $row_urusan){
				$this->export_excel->set_row(array($row_urusan->kd_urusan, NULL, NULL, NULL, $row_urusan->nama_urusan, Formatting::currency($row_urusan->sumrencana,2), Formatting::currency($row_urusan->sumrealisasi,2), NULL, NULL, NULL, NULL, NULL, NULL));
				$this->export_excel->merge_cell('I'.$this->export_excel->get_last_row(),'L'.$this->export_excel->get_last_row());
				$this->export_excel->getActiveSheet()->getStyle("A".$this->export_excel->get_last_row().":M".$this->export_excel->get_last_row())->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => '78cbfd'))));
				$this->export_excel->getActiveSheet()->getStyle("F".$this->export_excel->get_last_row().":G".$this->export_excel->get_last_row())->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

				$bidang = $this->db->query("
					SELECT pro.*,
					SUM(keg.realisasi_".$bulan.") AS realisasi,
					SUM(keg.rencana) AS rencana,
					pro.capaian_".$bulan." AS capaian,
					b.Nm_Bidang AS nama_bidang
					  FROM
						(SELECT * FROM tx_cik_prog_keg WHERE is_prog_or_keg=1) AS pro
					  INNER JOIN
						(SELECT * FROM tx_cik_prog_keg WHERE is_prog_or_keg=2) AS keg ON keg.parent=pro.id
					LEFT JOIN m_bidang AS b
					ON pro.kd_urusan = b.Kd_Urusan AND pro.kd_bidang = b.`Kd_Bidang`
					WHERE
						keg.id_skpd = ".$row_skpd->id_skpd."
					  AND keg.tahun = ".$ta."
					  AND keg.kd_urusan = ".$row_urusan->kd_urusan."
					  GROUP BY keg.kd_bidang
					  ORDER BY kd_urusan ASC, kd_bidang ASC, kd_program ASC, kd_kegiatan ASC;
				")->result();

				foreach ($bidang as $row_bidang) {
					$this->export_excel->set_row(array($row_urusan->kd_urusan, $row_bidang->kd_bidang, NULL, NULL, $row_bidang->nama_bidang, Formatting::currency($row_bidang->rencana,2), Formatting::currency($row_bidang->realisasi,2), NULL, NULL, NULL, NULL, NULL, NULL));
					$this->export_excel->merge_cell('I'.$this->export_excel->get_last_row(),'L'.$this->export_excel->get_last_row());
					$this->export_excel->getActiveSheet()->getStyle("A".$this->export_excel->get_last_row().":M".$this->export_excel->get_last_row())->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => '00FF33'))));
					$this->export_excel->getActiveSheet()->getStyle("F".$this->export_excel->get_last_row().":G".$this->export_excel->get_last_row())->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

					$program = $this->m_cik->get_program_cik_4_cetak($row_skpd->id_skpd,$bulan,$ta,$row_urusan->kd_urusan,$row_bidang->kd_bidang);

					foreach($program as $row){
						if($row->id == ""){
							$this->export_excel->set_row(array("Data Belum Terisikan.."));
							$this->export_excel->merge_cell('A'.$this->export_excel->get_last_row(),'M'.$this->export_excel->get_last_row());
						}else{
							$p++;
							$p_total++;
							$tot_rencana_skpd += $row->rencana;
							$tot_realisasi_skpd += $row->realisasi;
							$tot_rencana += $row->rencana;
							$tot_realisasi += $row->realisasi;
							$result = $this->m_cik->get_kegiatan_cik_4_cetak($row_urusan->kd_urusan,$row_bidang->kd_bidang,$row->kd_program,$row_skpd->id_skpd,$bulan, $ta);
							//echo $this->db->last_query();
							$cik_pro_keg = (empty($row->realisasi))?0:round(($row->realisasi/$row->rencana)*100,2);
							$kegiatan = $result->result();
							$indikator_program = $this->m_cik->get_indikator_prog_keg_preview($row->id, $bulan, FALSE, TRUE);
							$temp = $indikator_program->result();
							$total_temp = $indikator_program->num_rows();
							$total_for_iteration = $total_temp;

							$realisasi_temp = (empty($temp[0]->realisasi)) ? 0 :$temp[0]->realisasi;
							$this->export_excel->set_row(array($row->kd_urusan, $row->kd_bidang, $row->kd_program, $row->kd_kegiatan, $row->nama_prog_or_keg, Formatting::currency($row->rencana,2), Formatting::currency($row->realisasi,2), round($cik_pro_keg,2), $temp[0]->indikator, $temp[0]->target, round($realisasi_temp,2), $row->capaian, "-"));
							$this->export_excel->getActiveSheet()->getStyle("A".$this->export_excel->get_last_row().":M".$this->export_excel->get_last_row())->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'FF9933'))));
							$this->export_excel->getActiveSheet()->getStyle("F".$this->export_excel->get_last_row().":H".$this->export_excel->get_last_row())->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
							$this->export_excel->getActiveSheet()->getStyle("J".$this->export_excel->get_last_row().":L".$this->export_excel->get_last_row())->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

							if ($total_for_iteration > 1) {
                            	$this->export_excel->merge_cell('A'.$this->export_excel->get_last_row(),'A'.($this->export_excel->get_last_row()+$total_for_iteration-1));
								$this->export_excel->merge_cell('B'.$this->export_excel->get_last_row(),'B'.($this->export_excel->get_last_row()+$total_for_iteration-1));
								$this->export_excel->merge_cell('C'.$this->export_excel->get_last_row(),'C'.($this->export_excel->get_last_row()+$total_for_iteration-1));
								$this->export_excel->merge_cell('D'.$this->export_excel->get_last_row(),'D'.($this->export_excel->get_last_row()+$total_for_iteration-1));
								$this->export_excel->merge_cell('E'.$this->export_excel->get_last_row(),'E'.($this->export_excel->get_last_row()+$total_for_iteration-1));
								$this->export_excel->merge_cell('F'.$this->export_excel->get_last_row(),'F'.($this->export_excel->get_last_row()+$total_for_iteration-1));
								$this->export_excel->merge_cell('G'.$this->export_excel->get_last_row(),'G'.($this->export_excel->get_last_row()+$total_for_iteration-1));
								$this->export_excel->merge_cell('H'.$this->export_excel->get_last_row(),'H'.($this->export_excel->get_last_row()+$total_for_iteration-1));
								$this->export_excel->merge_cell('L'.$this->export_excel->get_last_row(),'L'.($this->export_excel->get_last_row()+$total_for_iteration-1));
								$this->export_excel->merge_cell('M'.$this->export_excel->get_last_row(),'M'.($this->export_excel->get_last_row()+$total_for_iteration-1));
                                for ($i=1; $i < $total_for_iteration; $i++) {
                                	$realisasi_temp = (empty($temp[$i]->realisasi)) ? 0 :$temp[$i]->realisasi;
									$this->export_excel->set_row(array(NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, $temp[$i]->indikator, $temp[$i]->target, round($realisasi_temp,2), NULL, NULL));
									$this->export_excel->getActiveSheet()->getStyle("F".$this->export_excel->get_last_row().":H".$this->export_excel->get_last_row())->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
									$this->export_excel->getActiveSheet()->getStyle("J".$this->export_excel->get_last_row().":L".$this->export_excel->get_last_row())->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
								}
							}

							foreach ($kegiatan as $row_kegiatan) {
								$k++;
								$k_total++;
								$indikator_kegiatan = $this->m_cik->get_indikator_prog_keg_preview($row_kegiatan->id, $bulan, FALSE, TRUE);
								$temp = $indikator_kegiatan->result();
								$total_temp = $indikator_kegiatan->num_rows();
								$total_for_iteration = $total_temp;

								$cik_pro_keg_kegiatan = (empty($row_kegiatan->realisasi)) ? 0 :round(($row_kegiatan->realisasi/$row_kegiatan->rencana)*100,2);
								$realisasi_keg_temp = (empty($temp[0]->realisasi)) ? 0 :$temp[0]->realisasi;
								$this->export_excel->set_row(array($row_kegiatan->kd_urusan, $row_kegiatan->kd_bidang, $row_kegiatan->kd_program, $row_kegiatan->kd_kegiatan, $row_kegiatan->nama_prog_or_keg, Formatting::currency($row_kegiatan->rencana,2), Formatting::currency($row_kegiatan->realisasi,2), round($cik_pro_keg_kegiatan,2), $temp[0]->indikator, $temp[0]->target, round($realisasi_keg_temp,2), $row_kegiatan->capaian, "-"));
								$this->export_excel->getActiveSheet()->getStyle("F".$this->export_excel->get_last_row().":H".$this->export_excel->get_last_row())->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
								$this->export_excel->getActiveSheet()->getStyle("J".$this->export_excel->get_last_row().":L".$this->export_excel->get_last_row())->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

	                            if ($total_for_iteration > 1) {
	                            	$this->export_excel->merge_cell('A'.$this->export_excel->get_last_row(),'A'.($this->export_excel->get_last_row()+$total_for_iteration-1));
									$this->export_excel->merge_cell('B'.$this->export_excel->get_last_row(),'B'.($this->export_excel->get_last_row()+$total_for_iteration-1));
									$this->export_excel->merge_cell('C'.$this->export_excel->get_last_row(),'C'.($this->export_excel->get_last_row()+$total_for_iteration-1));
									$this->export_excel->merge_cell('D'.$this->export_excel->get_last_row(),'D'.($this->export_excel->get_last_row()+$total_for_iteration-1));
									$this->export_excel->merge_cell('E'.$this->export_excel->get_last_row(),'E'.($this->export_excel->get_last_row()+$total_for_iteration-1));
									$this->export_excel->merge_cell('F'.$this->export_excel->get_last_row(),'F'.($this->export_excel->get_last_row()+$total_for_iteration-1));
									$this->export_excel->merge_cell('G'.$this->export_excel->get_last_row(),'G'.($this->export_excel->get_last_row()+$total_for_iteration-1));
									$this->export_excel->merge_cell('H'.$this->export_excel->get_last_row(),'H'.($this->export_excel->get_last_row()+$total_for_iteration-1));
									$this->export_excel->merge_cell('L'.$this->export_excel->get_last_row(),'L'.($this->export_excel->get_last_row()+$total_for_iteration-1));
									$this->export_excel->merge_cell('M'.$this->export_excel->get_last_row(),'M'.($this->export_excel->get_last_row()+$total_for_iteration-1));

	                                for ($i=1; $i < $total_for_iteration; $i++) {
	                                	$realisasi_keg_temp = (empty($temp[$i]->realisasi)) ? 0 :$temp[$i]->realisasi;
	                                	$this->export_excel->set_row(array(NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, $temp[$i]->indikator, $temp[$i]->target, round($realisasi_keg_temp,2), NULL, NULL));
										$this->export_excel->getActiveSheet()->getStyle("F".$this->export_excel->get_last_row().":H".$this->export_excel->get_last_row())->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
										$this->export_excel->getActiveSheet()->getStyle("J".$this->export_excel->get_last_row().":L".$this->export_excel->get_last_row())->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
									}
								}

							}
						}
					}
				}
			}

			$tot_prog_skpd = $this->m_cik->sum_capaian_program($row_skpd->id_skpd,$bulan,$ta);
			$count_prog_skpd = $this->m_cik->count_program($row_skpd->id_skpd,$bulan,$ta);
			$tot_keg_skpd = $this->m_cik->sum_capaian_kegiatan($row_skpd->id_skpd,$bulan,$ta);
			$count_keg_skpd = $this->m_cik->count_kegiatan($row_skpd->id_skpd,$bulan,$ta);
			$tot_prog_count = $tot_prog_skpd->capaianp/$count_prog_skpd->countp;
			$tot_keg_count = $tot_keg_skpd->capaiank/$count_keg_skpd->countk;
			$sisa_skpd = $tot_rencana_skpd-$tot_realisasi_skpd;

			$this->export_excel->set_row(array(NULL, NULL, NULL, NULL, "JUMLAH PROGRAM SKPD", $p." Program", NULL, NULL, NULL, NULL, NULL, NULL, NULL));
			$this->export_excel->merge_cell('F'.$this->export_excel->get_last_row(),'M'.$this->export_excel->get_last_row());
			$this->export_excel->merge_cell('A'.$this->export_excel->get_last_row(),'D'.$this->export_excel->get_last_row());
			$this->export_excel->getActiveSheet()->getStyle("A".$this->export_excel->get_last_row().":M".$this->export_excel->get_last_row())->getFont()->setBold(true);
			$this->export_excel->getActiveSheet()->getStyle("F".$this->export_excel->get_last_row().":M".$this->export_excel->get_last_row())->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			$this->export_excel->getActiveSheet()->getStyle("A".$this->export_excel->get_last_row().":M".$this->export_excel->get_last_row())->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'CCCCCC'))));

			$this->export_excel->set_row(array(NULL, NULL, NULL, NULL, "JUMLAH KEGIATAN SKPD", $k." Kegiatan", NULL, NULL, NULL, NULL, NULL, NULL, NULL));
			$this->export_excel->merge_cell('F'.$this->export_excel->get_last_row(),'M'.$this->export_excel->get_last_row());
			$this->export_excel->merge_cell('A'.$this->export_excel->get_last_row(),'D'.$this->export_excel->get_last_row());
			$this->export_excel->getActiveSheet()->getStyle("A".$this->export_excel->get_last_row().":M".$this->export_excel->get_last_row())->getFont()->setBold(true);
			$this->export_excel->getActiveSheet()->getStyle("F".$this->export_excel->get_last_row().":M".$this->export_excel->get_last_row())->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			$this->export_excel->getActiveSheet()->getStyle("A".$this->export_excel->get_last_row().":M".$this->export_excel->get_last_row())->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'CCCCCC'))));

			$tot_realisasi_temp = (empty($tot_realisasi_skpd)) ? 0 :round(($tot_realisasi_skpd/$tot_rencana_skpd)*100,2);
			$this->export_excel->set_row(array(NULL, NULL, NULL, NULL, "Jumlah", Formatting::currency($tot_rencana_skpd,2), Formatting::currency($tot_realisasi_skpd), $tot_realisasi_temp, "Rata-rata Capaian Program", NULL, NULL, round($tot_prog_count,2), NULL));
			$this->export_excel->merge_cell('I'.$this->export_excel->get_last_row(),'K'.$this->export_excel->get_last_row());
			$this->export_excel->merge_cell('A'.$this->export_excel->get_last_row(),'D'.$this->export_excel->get_last_row());
			$this->export_excel->getActiveSheet()->getStyle("A".$this->export_excel->get_last_row().":M".$this->export_excel->get_last_row())->getFont()->setBold(true);
			$this->export_excel->getActiveSheet()->getStyle("F".$this->export_excel->get_last_row().":H".$this->export_excel->get_last_row())->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			$this->export_excel->getActiveSheet()->getStyle("L".$this->export_excel->get_last_row())->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			$this->export_excel->getActiveSheet()->getStyle("A".$this->export_excel->get_last_row().":M".$this->export_excel->get_last_row())->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'CCCCCC'))));

			$sisa_realisasi_temp = (empty($sisa_skpd)) ? 0 :round(($sisa_skpd/$tot_rencana_skpd)*100,2);
			$this->export_excel->set_row(array(NULL, NULL, NULL, NULL, "Sisa", Formatting::currency($sisa_skpd,2), NULL, Formatting::currency($sisa_realisasi_temp,2), "Rata-rata Capaian Kegiatan", NULL, NULL, round($tot_keg_count,2), NULL));
			$this->export_excel->merge_cell('F'.$this->export_excel->get_last_row(),'G'.$this->export_excel->get_last_row());
			$this->export_excel->merge_cell('I'.$this->export_excel->get_last_row(),'K'.$this->export_excel->get_last_row());
			$this->export_excel->merge_cell('A'.$this->export_excel->get_last_row(),'D'.$this->export_excel->get_last_row());
			$this->export_excel->getActiveSheet()->getStyle("A".$this->export_excel->get_last_row().":M".$this->export_excel->get_last_row())->getFont()->setBold(true);
			$this->export_excel->getActiveSheet()->getStyle("F".$this->export_excel->get_last_row().":H".$this->export_excel->get_last_row())->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			$this->export_excel->getActiveSheet()->getStyle("L".$this->export_excel->get_last_row())->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
			$this->export_excel->getActiveSheet()->getStyle("A".$this->export_excel->get_last_row().":M".$this->export_excel->get_last_row())->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'CCCCCC'))));
		}

		if (!empty($id_skpd) && $id_skpd!="all") {
			$skpd_detail_cetak = $this->m_skpd->get_one_skpd(array('id_skpd' => $id_skpd));
			$nama_skpd_cetak = "SKPD ".$skpd_detail_cetak->nama_skpd;
		}elseif ($id_skpd == "all") {
			$nama_skpd_cetak = "Semua SKPD";
		$tot_prog = $this->m_cik->sum_capaian_program_pusat($bulan,$ta);
		$count_prog = $this->m_cik->count_program_pusat($bulan,$ta);
		$tot_keg = $this->m_cik->sum_capaian_kegiatan_pusat($bulan,$ta);
		$count_keg = $this->m_cik->count_kegiatan_pusat($bulan,$ta);
		$tot_prog = $tot_prog->capaianp/$count_prog->countp;
		$tot_keg = $tot_keg->capaiank/$count_keg->countk;

		$this->export_excel->set_row(array(NULL, NULL, NULL, NULL, "TOTAL JUMLAH PROGRAM", $p_total." Program", NULL, NULL, NULL, NULL, NULL, NULL, NULL));
		$this->export_excel->merge_cell('F'.$this->export_excel->get_last_row(),'M'.$this->export_excel->get_last_row());
		$this->export_excel->merge_cell('A'.$this->export_excel->get_last_row(),'D'.$this->export_excel->get_last_row());
		$this->export_excel->getActiveSheet()->getStyle("A".$this->export_excel->get_last_row().":M".$this->export_excel->get_last_row())->getFont()->setBold(true);
		$this->export_excel->getActiveSheet()->getStyle("F".$this->export_excel->get_last_row().":M".$this->export_excel->get_last_row())->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$this->export_excel->getActiveSheet()->getStyle("A".$this->export_excel->get_last_row().":M".$this->export_excel->get_last_row())->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => '78cbfd'))));

		$this->export_excel->set_row(array(NULL, NULL, NULL, NULL, "TOTAL JUMLAH KEGIATAN", $k_total." Kegiatan", NULL, NULL, NULL, NULL, NULL, NULL, NULL));
		$this->export_excel->merge_cell('F'.$this->export_excel->get_last_row(),'M'.$this->export_excel->get_last_row());
		$this->export_excel->merge_cell('A'.$this->export_excel->get_last_row(),'D'.$this->export_excel->get_last_row());
		$this->export_excel->getActiveSheet()->getStyle("A".$this->export_excel->get_last_row().":M".$this->export_excel->get_last_row())->getFont()->setBold(true);
		$this->export_excel->getActiveSheet()->getStyle("F".$this->export_excel->get_last_row().":M".$this->export_excel->get_last_row())->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$this->export_excel->getActiveSheet()->getStyle("A".$this->export_excel->get_last_row().":M".$this->export_excel->get_last_row())->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => '78cbfd'))));

		$tot_realisasi_temp = (empty($tot_realisasi)) ? 0 :round(($tot_realisasi/$tot_rencana)*100,2);
		$this->export_excel->set_row(array(NULL, NULL, NULL, NULL, "Jumlah Total", Formatting::currency($tot_rencana,2), Formatting::currency($tot_realisasi,2), round($tot_realisasi_temp,2), "Rata-rata Capaian Program", NULL, NULL, round($tot_prog,2), NULL));
		$this->export_excel->merge_cell('I'.$this->export_excel->get_last_row(),'K'.$this->export_excel->get_last_row());
		$this->export_excel->merge_cell('A'.$this->export_excel->get_last_row(),'D'.$this->export_excel->get_last_row());
		$this->export_excel->getActiveSheet()->getStyle("A".$this->export_excel->get_last_row().":M".$this->export_excel->get_last_row())->getFont()->setBold(true);
		$this->export_excel->getActiveSheet()->getStyle("F".$this->export_excel->get_last_row().":H".$this->export_excel->get_last_row())->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$this->export_excel->getActiveSheet()->getStyle("L".$this->export_excel->get_last_row())->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$this->export_excel->getActiveSheet()->getStyle("A".$this->export_excel->get_last_row().":M".$this->export_excel->get_last_row())->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => '78cbfd'))));

		$sisa = $tot_rencana-$tot_realisasi;
		$sisa_realisasi_temp = (empty($sisa)) ? 0 :round(($sisa/$tot_rencana)*100,2);
		$this->export_excel->set_row(array(NULL, NULL, NULL, NULL, "Total Sisa", Formatting::currency($sisa,2), NULL, Formatting::currency($sisa_realisasi_temp,2), "Rata-rata Capaian Kegiatan", NULL, NULL, round($tot_keg,2), NULL));
		$this->export_excel->merge_cell('F'.$this->export_excel->get_last_row(),'G'.$this->export_excel->get_last_row());
		$this->export_excel->merge_cell('I'.$this->export_excel->get_last_row(),'K'.$this->export_excel->get_last_row());
		$this->export_excel->merge_cell('A'.$this->export_excel->get_last_row(),'D'.$this->export_excel->get_last_row());
		$this->export_excel->getActiveSheet()->getStyle("A".$this->export_excel->get_last_row().":M".$this->export_excel->get_last_row())->getFont()->setBold(true);
		$this->export_excel->getActiveSheet()->getStyle("F".$this->export_excel->get_last_row().":H".$this->export_excel->get_last_row())->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$this->export_excel->getActiveSheet()->getStyle("L".$this->export_excel->get_last_row())->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$this->export_excel->getActiveSheet()->getStyle("A".$this->export_excel->get_last_row().":M".$this->export_excel->get_last_row())->applyFromArray(array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => '78cbfd'))));

		}
		$this->export_excel->filename = "CIK Pusat ". $nama_skpd_cetak ." ". $bulan ."-".$ta." ";

		$this->export_excel->getActiveSheet()->getColumnDimension($this->export_excel->get_cell_name(1))->setAutoSize(true);
		$this->export_excel->getActiveSheet()->getColumnDimension($this->export_excel->get_cell_name(2))->setAutoSize(true);
		$this->export_excel->getActiveSheet()->getColumnDimension($this->export_excel->get_cell_name(3))->setAutoSize(true);
		$this->export_excel->getActiveSheet()->getColumnDimension($this->export_excel->get_cell_name(4))->setAutoSize(true);
		$this->export_excel->getActiveSheet()->getColumnDimension($this->export_excel->get_cell_name(5))->setWidth(75);
		$this->export_excel->getActiveSheet()->getColumnDimension($this->export_excel->get_cell_name(6))->setAutoSize(true);
		$this->export_excel->getActiveSheet()->getColumnDimension($this->export_excel->get_cell_name(7))->setAutoSize(true);
		$this->export_excel->getActiveSheet()->getColumnDimension($this->export_excel->get_cell_name(8))->setAutoSize(true);
		$this->export_excel->getActiveSheet()->getColumnDimension($this->export_excel->get_cell_name(9))->setWidth(45);
		$this->export_excel->getActiveSheet()->getColumnDimension($this->export_excel->get_cell_name(10))->setAutoSize(true);
		$this->export_excel->getActiveSheet()->getColumnDimension($this->export_excel->get_cell_name(11))->setAutoSize(true);
		$this->export_excel->getActiveSheet()->getColumnDimension($this->export_excel->get_cell_name(12))->setAutoSize(true);

        $this->export_excel->getActiveSheet()->getStyle("I1:L1")->getAlignment()->setWrapText(true);
        $this->export_excel->getActiveSheet()->getStyle("E". $this->export_excel->first_col .":E".$this->export_excel->last_col)->getAlignment()->setWrapText(true);
        $this->export_excel->getActiveSheet()->getStyle("I". $this->export_excel->first_col .":I".$this->export_excel->last_col)->getAlignment()->setWrapText(true);

        $this->export_excel->set_border("A".$this->export_excel->first_col.":M".$this->export_excel->last_col);
		if (!empty($id_skpd) && $id_skpd!="all") {
			$skpd = $this->m_skpd->get_one_skpd(array('id_skpd' => $id_skpd));
		}elseif ($id_skpd == "all") {
			$id_skpd = 1;
			$skpd = $this->m_skpd->get_one_skpd(array('id_skpd' => $id_skpd));
		}
		$styleBorder = array(
		  'borders' => array(
			'allborders' => array(
			  'style' => PHPExcel_Style_Border::BORDER_NONE
			)
		  )
		);
		$styleUnderline = array(
		  'font' => array(
			'underline' => PHPExcel_Style_Font::UNDERLINE_SINGLE
		  )
		);
		switch(date('F')){
			case 'January';
			default:
				$bulan="Januari";
				break;
			case 'February';
			default:
				$bulan="Februari";
				break;
			case 'March';
			default:
				$bulan="Maret";
				break;
			case 'April';
			default:
				$bulan="April";
				break;
			case 'May';
			default:
				$bulan="Mei";
				break;
			case 'June';
			default:
				$bulan="Juni";
				break;
			case 'July';
			default:
				$bulan="Juli";
				break;
			case 'August';
			default:
				$bulan="Agustus";
				break;
			case 'September';
			default:
				$bulan="September";
				break;
			case 'October';
			default:
				$bulan="Oktober";
				break;
			case 'November';
			default:
				$bulan="November";
				break;
			case 'December';
			default:
				$bulan="Desember";
				break;
		}
		$this->export_excel->set_row(array(NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL));
		$this->export_excel->merge_cell('A'.$this->export_excel->get_last_row(),'M'.$this->export_excel->get_last_row());
		$this->export_excel->getActiveSheet()->getStyle("A".$this->export_excel->get_last_row().":M".$this->export_excel->get_last_row())->applyFromArray($styleBorder);

		$this->export_excel->set_row(array(NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, "Semarapura, ".date('j')." ".$bulan." ".date('Y'), NULL, NULL, NULL, NULL));
		$this->export_excel->merge_cell('A'.$this->export_excel->get_last_row(),'H'.$this->export_excel->get_last_row());
		$this->export_excel->merge_cell('I'.$this->export_excel->get_last_row(),'L'.$this->export_excel->get_last_row());
		$this->export_excel->getActiveSheet()->getStyle("I".$this->export_excel->get_last_row())->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->export_excel->getActiveSheet()->getStyle("A".$this->export_excel->get_last_row().":M".$this->export_excel->get_last_row())->applyFromArray($styleBorder);

		$this->export_excel->set_row(array(NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, $skpd->nama_jabatan, NULL, NULL, NULL, NULL));
		$this->export_excel->merge_cell('A'.$this->export_excel->get_last_row(),'H'.$this->export_excel->get_last_row());
		$this->export_excel->merge_cell('I'.$this->export_excel->get_last_row(),'L'.$this->export_excel->get_last_row());
		$this->export_excel->getActiveSheet()->getStyle("I".$this->export_excel->get_last_row())->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->export_excel->getActiveSheet()->getStyle("A".$this->export_excel->get_last_row().":M".$this->export_excel->get_last_row())->applyFromArray($styleBorder);

		$this->export_excel->set_row(array(NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL));
		$this->export_excel->merge_cell('A'.$this->export_excel->get_last_row(),'M'.$this->export_excel->get_last_row());
		$this->export_excel->getActiveSheet()->getStyle("A".$this->export_excel->get_last_row().":M".$this->export_excel->get_last_row())->applyFromArray($styleBorder);
		$this->export_excel->set_row(array(NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL));
		$this->export_excel->merge_cell('A'.$this->export_excel->get_last_row(),'M'.$this->export_excel->get_last_row());
		$this->export_excel->getActiveSheet()->getStyle("A".$this->export_excel->get_last_row().":M".$this->export_excel->get_last_row())->applyFromArray($styleBorder);
		$this->export_excel->set_row(array(NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL));
		$this->export_excel->merge_cell('A'.$this->export_excel->get_last_row(),'M'.$this->export_excel->get_last_row());
		$this->export_excel->getActiveSheet()->getStyle("A".$this->export_excel->get_last_row().":M".$this->export_excel->get_last_row())->applyFromArray($styleBorder);
		$this->export_excel->set_row(array(NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL));
		$this->export_excel->merge_cell('A'.$this->export_excel->get_last_row(),'M'.$this->export_excel->get_last_row());
		$this->export_excel->getActiveSheet()->getStyle("A".$this->export_excel->get_last_row().":M".$this->export_excel->get_last_row())->applyFromArray($styleBorder);
		$this->export_excel->set_row(array(NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL));
		$this->export_excel->merge_cell('A'.$this->export_excel->get_last_row(),'M'.$this->export_excel->get_last_row());
		$this->export_excel->getActiveSheet()->getStyle("A".$this->export_excel->get_last_row().":M".$this->export_excel->get_last_row())->applyFromArray($styleBorder);

		$this->export_excel->set_row(array(NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, $skpd->kaskpd_nama, NULL, NULL, NULL, NULL));
		$this->export_excel->merge_cell('A'.$this->export_excel->get_last_row(),'H'.$this->export_excel->get_last_row());
		$this->export_excel->merge_cell('I'.$this->export_excel->get_last_row(),'L'.$this->export_excel->get_last_row());
		$this->export_excel->getActiveSheet()->getStyle("I".$this->export_excel->get_last_row())->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->export_excel->getActiveSheet()->getStyle("I".$this->export_excel->get_last_row())->applyFromArray($styleUnderline);
		$this->export_excel->getActiveSheet()->getStyle("A".$this->export_excel->get_last_row().":M".$this->export_excel->get_last_row())->applyFromArray($styleBorder);

		$this->export_excel->set_row(array(NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, $skpd->pangkat_golongan, NULL, NULL, NULL, NULL));
		$this->export_excel->merge_cell('A'.$this->export_excel->get_last_row(),'H'.$this->export_excel->get_last_row());
		$this->export_excel->merge_cell('I'.$this->export_excel->get_last_row(),'L'.$this->export_excel->get_last_row());
		$this->export_excel->getActiveSheet()->getStyle("I".$this->export_excel->get_last_row())->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->export_excel->getActiveSheet()->getStyle("A".$this->export_excel->get_last_row().":M".$this->export_excel->get_last_row())->applyFromArray($styleBorder);

		$this->export_excel->set_row(array(NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, "NIP. ".$skpd->kaskpd_nip, NULL, NULL, NULL, NULL));
		$this->export_excel->merge_cell('A'.$this->export_excel->get_last_row(),'H'.$this->export_excel->get_last_row());
		$this->export_excel->merge_cell('I'.$this->export_excel->get_last_row(),'L'.$this->export_excel->get_last_row());
		$this->export_excel->getActiveSheet()->getStyle("I".$this->export_excel->get_last_row())->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->export_excel->getActiveSheet()->getStyle("A".$this->export_excel->get_last_row().":M".$this->export_excel->get_last_row())->applyFromArray($styleBorder);

        $this->export_excel->set_readonly();

		$this->export_excel->execute();
	}
}
