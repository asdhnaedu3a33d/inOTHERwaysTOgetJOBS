<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rpjmd extends CI_Controller
{
	var $CI = NULL;
	public function __construct(){
		$this->CI =& get_instance();
        parent::__construct();
        $this->load->model(array('m_rpjmd_trx', 'm_renstra_trx', 'm_skpd', 'm_template_cetak', 'm_lov', 'm_urusan', 'm_bidang', 'm_program', 'm_kegiatan'));
        if (!empty($this->session->userdata("db_aktif"))) {
            $this->load->database($this->session->userdata("db_aktif"), FALSE, TRUE);
        }
	}

	function index(){
		$this->rpjmd();
	}

	function rpjmd(){
		$this->auth->restrict();

		$data['rpjmd'] = $this->m_rpjmd_trx->get_all_rpjmd();
		$this->template->load('template','rpjmd/view_all', $data);
	}

	function cru_rpjmd($id=NULL){
		$this->auth->restrict();
		$data['rpjmd'] = NULL;
		$rpjmd = $this->m_rpjmd_trx->get_one_rpjmd($id, TRUE);
		if (!empty($rpjmd)) {
			$data['rpjmd'] = $rpjmd;
			$data['rpjmd_misi'] = $this->m_rpjmd_trx->get_all_rpjmd_misi($rpjmd->id, TRUE);
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
		$this->template->load('template','rpjmd/cru_rpjmd', $data);
	}

	function save(){
		$id = $this->input->post('id_rpjmd');
		$misi = $this->input->post('misi');
		$id_misi = $this->input->post('id_misi');
		$tujuan = $this->input->post('tujuan');
		$id_tujuan = $this->input->post('id_tujuan');
		$id_indikator = $this->input->post('id_indikator');

		$data['visi'] = $this->input->post('visi');
		$data_indikator = $this->input->post();

		// $clean = array('id_rpjmd','misi', 'tujuan', 'id_tujuan', 'id_misi');
		// $data = $this->global_function->clean_array($data, $clean);

		$clean = array('id_rpjmd','misi', 'tujuan', 'id_tujuan', 'id_misi', 'id_indikator', 'tujuan', 'visi', 'id_indikator');
		$data_indikator = $this->global_function->clean_array($data_indikator, $clean);

		// print_r($data_indikator['indikator']);
		// exit;

		if (!empty($id)) {
			$result = $this->m_rpjmd_trx->edit_rpjmd($data, $misi, $tujuan, $id_misi, $id_tujuan, $id, $id_indikator, $data_indikator);
		}else{
			$result = $this->m_rpjmd_trx->add_rpjmd($data, $misi, $tujuan, $data_indikator);
		}

		if ($result) {
			$this->session->set_userdata('msg_typ','ok');
			$this->session->set_userdata('msg', 'RPJMD berhasil dibuat.');
			redirect('rpjmd');
		}else{
			$this->session->set_userdata('msg_typ','err');
			$this->session->set_userdata('msg', 'ERROR! RPJMD gagal dibuat, mohon menghubungi administrator.');
			redirect('rpjmd');
		}
	}

	function delete_rpjmd(){
		$id = $this->input->post('id');
		$result = $this->m_rpjmd_trx->delete_rpjmd($id);
		if ($result) {
			$msg = array('success' => '1', 'msg' => 'RPJMD berhasil dihapus.');
			echo json_encode($msg);
		}else{
			$msg = array('success' => '0', 'msg' => 'ERROR! RPJMD gagal dihapus, mohon menghubungi administrator.');
			echo json_encode($msg);
		}
	}

	function det_rpjmd($id=NULL){
		if (empty($id)) {
			redirect('rpjmd');
		}
		$this->auth->restrict();
		$rpjmd = $this->m_rpjmd_trx->get_one_rpjmd($id, TRUE);
		$data['rpjmd'] = $rpjmd;
		$data['rpjmd_misi'] = $this->m_rpjmd_trx->get_all_rpjmd_misi($rpjmd->id, TRUE);
		$data['rpjmd_tujuan'] = $this->m_rpjmd_trx->get_all_rpjmd_tujuan($rpjmd->id);
		$this->template->load('template','rpjmd/view', $data);
	}

	function get_sasaran(){
		$id_rpjmd = $this->input->post('id_rpjmd');
		$id_tujuan = $this->input->post('id_tujuan');

		// $data['sasaran'] = $this->m_rpjmd_trx->get_all_sasaran($id_rpjmd, $id_tujuan);
		$data['sasaran'] = $this->m_rpjmd_trx->get_all_sasaran_for_me($id_rpjmd, $id_tujuan);
		$this->load->view("rpjmd/view_sasaran", $data);
	}

	function cru_sasaran(){
		$id_rpjmd = $this->input->post('id_rpjmd');
		$id_tujuan = $this->input->post('id_tujuan');
		$id_sasaran = $this->input->post('id_sasaran');

		if (!empty($id_sasaran)) {
			$result = $this->m_rpjmd_trx->get_one_sasaran($id_rpjmd, $id_tujuan, $id_sasaran);
			// print_r($this->db->last_query());
			if (empty($result)) {
				echo '<div style="width: 400px;">ERROR! Data sasaran tidak ditemukan.</div>';
				return FALSE;
			}
			$data['sasaran'] = $result;
		}

		$data['id_rpjmd'] = $id_rpjmd;
		$data['tujuan'] = $this->m_rpjmd_trx->get_one_rpjmd_tujuan($id_rpjmd, $id_tujuan);

		$this->load->view("rpjmd/cru_sasaran", $data);
	}

	function save_sasaran(){
		$id = $this->input->post('id_sasaran');

		$data = $this->input->post();

		$clean = array('id_sasaran');
		$data = $this->global_function->clean_array($data, $clean);

		if (!empty($id)) {
			$result = $this->m_rpjmd_trx->edit_sasaran($data, $id);
		}else{
			$result = $this->m_rpjmd_trx->add_sasaran($data);
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
		$result = $this->m_rpjmd_trx->delete_sasaran($id);
		if ($result) {
			$msg = array('success' => '1', 'msg' => 'Sasaran berhasil dihapus.');
			echo json_encode($msg);
		}else{
			$msg = array('success' => '0', 'msg' => 'ERROR! Sasaran gagal dihapus, mohon menghubungi administrator.');
			echo json_encode($msg);
		}
	}

	function get_strategi(){
		$id_rpjmd = $this->input->post('id_rpjmd');
		$id_tujuan = $this->input->post('id_tujuan');
		$id_sasaran = $this->input->post('id_sasaran');
		$id_program = $this->input->post('id_program');

		$data['strategi'] = $this->m_rpjmd_trx->get_all_strategi($id_sasaran);
		$data['program'] = $this->m_rpjmd_trx->get_all_program_ng($id_sasaran);
		$data['id_rpjmd'] = $id_rpjmd;
		$data['id_tujuan'] = $id_tujuan;
		$data['id_sasaran'] = $id_sasaran;
		$data['id_prog'] = $id_program;
		// $strategi = $this->load->view("rpjmd/view_strategi", $data);
		$this->load->view("rpjmd/view_strategi_program", $data);
	}

	function cru_strategi(){
		$id_rpjmd = $this->input->post('id_rpjmd');
		$id_tujuan = $this->input->post('id_tujuan');
		$id_sasaran = $this->input->post('id_sasaran');
		$id_strategi = $this->input->post('id_strategi');

		$program_edit = NULL;
		if (!empty($id_strategi)) {
			$result = $this->m_rpjmd_trx->get_one_strategi($id_strategi);
			if (empty($result)) {
				echo '<div style="width: 400px;">ERROR! Data strategi tidak ditemukan.</div>';
				return FALSE;
			}
			$data['strategi'] = $result;
			$program_edit = $result->id_program;
		}

		$cmb_program = array('' => '');
		foreach ($this->m_rpjmd_trx->get_all_program_ng($id_sasaran) as $row) {
			$cmb_program[$row->id] = $row->nama_prog;
		}

		$data['id_rpjmd'] = $id_rpjmd;
		$data['tujuan'] = $this->m_rpjmd_trx->get_one_rpjmd_tujuan($id_rpjmd, $id_tujuan);
		$data['cmb_program'] = form_dropdown('id_program', $cmb_program, $program_edit, 'data-placeholder="Pilih Program RPJMD" class="common chosen-select" id="id_program"');
		$data['sasaran'] = $this->m_rpjmd_trx->get_one_sasaran($id_rpjmd, $id_tujuan, $id_sasaran);

		$this->load->view("rpjmd/cru_strategi", $data);
	}

	function save_strategi(){
		$id = $this->input->post('id_strategi');

		$data = $this->input->post();

		$clean = array('id_strategi');
		$data = $this->global_function->clean_array($data, $clean);

		if (!empty($id)) {
			$result = $this->m_rpjmd_trx->edit_strategi($data, $id);
		}else{
			$result = $this->m_rpjmd_trx->add_strategi($data);
		}

		if ($result) {
			$msg = array('success' => '1', 'msg' => 'Strategi berhasil dibuat.');
			echo json_encode($msg);
		}else{
			$msg = array('success' => '0', 'msg' => 'ERROR! Strategi gagal dibuat, mohon menghubungi administrator.');
			echo json_encode($msg);
		}
	}

	function delete_strategi(){
		$id = $this->input->post('id_strategi');
		$result = $this->m_rpjmd_trx->delete_strategi($id);
		if ($result) {
			$msg = array('success' => '1', 'msg' => 'Strategi berhasil dihapus.');
			echo json_encode($msg);
		}else{
			$msg = array('success' => '0', 'msg' => 'ERROR! Strategi gagal dihapus, mohon menghubungi administrator.');
			echo json_encode($msg);
		}
	}

	function get_kebijakan(){
		$id_rpjmd = $this->input->post('id_rpjmd');
		$id_tujuan = $this->input->post('id_tujuan');
		$id_sasaran = $this->input->post('id_sasaran');
		$id_strategi = $this->input->post('id_strategi');

		$data['kebijakan'] = $this->m_rpjmd_trx->get_all_kebijakan($id_strategi);
		$data['id_rpjmd'] = $id_rpjmd;
		$data['id_tujuan'] = $id_tujuan;
		$data['id_sasaran'] = $id_sasaran;
		$data['id_strategi'] = $id_strategi;
		$this->load->view("rpjmd/view_kebijakan", $data);
	}

	function cru_kebijakan(){
		$id_rpjmd = $this->input->post('id_rpjmd');
		$id_tujuan = $this->input->post('id_tujuan');
		$id_sasaran = $this->input->post('id_sasaran');
		$id_strategi = $this->input->post('id_strategi');
		$id_kebijakan = $this->input->post('id_kebijakan');

		if (!empty($id_kebijakan)) {
			$result = $this->m_rpjmd_trx->get_one_kebijakan($id_kebijakan);
			if (empty($result)) {
				echo '<div style="width: 400px;">ERROR! Data kebijakan tidak ditemukan.</div>';
				return FALSE;
			}
			$data['kebijakan'] = $result;
		}

		$data['id_rpjmd'] = $id_rpjmd;
		$data['tujuan'] = $this->m_rpjmd_trx->get_one_rpjmd_tujuan($id_rpjmd, $id_tujuan);
		$data['sasaran'] = $this->m_rpjmd_trx->get_one_sasaran($id_rpjmd, $id_tujuan, $id_sasaran);
		$data['strategi'] = $this->m_rpjmd_trx->get_one_strategi($id_strategi);

		$this->load->view("rpjmd/cru_kebijakan", $data);
	}

	function save_kebijakan(){
		$id = $this->input->post('id_kebijakan');

		$data = $this->input->post();

		$clean = array('id_kebijakan');
		$data = $this->global_function->clean_array($data, $clean);

		if (!empty($id)) {
			$result = $this->m_rpjmd_trx->edit_kebijakan($data, $id);
		}else{
			$result = $this->m_rpjmd_trx->add_kebijakan($data);
		}

		if ($result) {
			$msg = array('success' => '1', 'msg' => 'Strategi berhasil dibuat.');
			echo json_encode($msg);
		}else{
			$msg = array('success' => '0', 'msg' => 'ERROR! Strategi gagal dibuat, mohon menghubungi administrator.');
			echo json_encode($msg);
		}
	}

	function delete_kebijakan(){
		$id = $this->input->post('id_kebijakan');
		$result = $this->m_rpjmd_trx->delete_kebijakan($id);
		if ($result) {
			$msg = array('success' => '1', 'msg' => 'Kebijakan berhasil dihapus.');
			echo json_encode($msg);
		}else{
			$msg = array('success' => '0', 'msg' => 'ERROR! Kebijakan gagal dihapus, mohon menghubungi administrator.');
			echo json_encode($msg);
		}
	}

	function cru_program(){
		$this->auth->restrict();
		$id = $this->input->post('id_program');
		$id_rpjmd = $this->input->post('id_rpjmd');
		$id_tujuan = $this->input->post('id_tujuan');
		$id_sasaran = $this->input->post('id_sasaran');

		if (!empty($id)) {
			$result = $this->m_rpjmd_trx->get_one_program_rpjmd_for_me($id);
			$indikator = $this->m_rpjmd_trx->get_indikator_program_rpjmd_for_me($id);
			if (empty($result)) {
				echo '<div style="width: 400px;">ERROR! Data tidak ditemukan.</div>';
				return FALSE;
			}
			$data['program'] = $result;
			$data['indikator_program'] = $indikator;
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
		$data['id_rpjmd'] = $id_rpjmd;
		$data['tujuan'] = $this->m_rpjmd_trx->get_one_rpjmd_tujuan($id_rpjmd, $id_tujuan);
		$data['sasaran'] = $this->m_rpjmd_trx->get_one_sasaran($id_rpjmd, $id_tujuan, $id_sasaran);
		$this->load->view("rpjmd/cru_program", $data);
	}

	function save_program(){
		$id = $this->input->post('id_program');

		$data = $this->input->post();
		$id_indikator_program = $this->input->post('id_indikator_program');
		$indikator = $this->input->post('indikator_kinerja');
		$pengukuran = $this->input->post('cara_pengukuran');
		$satuan_target = $this->input->post('satuan_target');
		$status_target = $this->input->post('status_target');
		$kategori_target = $this->input->post('kategori_target');
		$kondisi_awal = $this->input->post('kondisi_awal');
		$target_1 = $this->input->post("target_1");
		$target_2 = $this->input->post("target_2");
		$target_3 = $this->input->post("target_3");
		$target_4 = $this->input->post("target_4");
		$target_5 = $this->input->post("target_5");
		$kondisi_akhir = $this->input->post('kondisi_akhir');


		$clean = array('id_program', 'pagu_renstra', 'id_indikator_program', 'indikator_kinerja', 'cara_pengukuran', 'satuan_target', 'status_target', 'kategori_target', 'kondisi_awal', 'target_1', 'target_2',
			'target_3',	'target_4',	'target_5',	'kondisi_akhir');
		$data = $this->global_function->clean_array($data, $clean);

		// print_r($data);
		// exit;
		if (!empty($id)) {
			$result = $this->m_rpjmd_trx->edit_program($data, $id, $id_indikator_program, $indikator, $pengukuran, $satuan_target, $status_target, $kategori_target, $kondisi_awal, $target_1, $target_2, $target_3, $target_4,	$target_5, $kondisi_akhir);
		}else{
			$result = $this->m_rpjmd_trx->add_program($data, $indikator, $pengukuran, $satuan_target, $status_target, $kategori_target, $kondisi_awal, $target_1, $target_2, $target_3, $target_4,	$target_5, $kondisi_akhir);
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
		$result = $this->m_rpjmd_trx->delete_program($id);
		if ($result) {
			$msg = array('success' => '1', 'msg' => 'Program berhasil dihapus.');
			echo json_encode($msg);
		}else{
			$msg = array('success' => '0', 'msg' => 'ERROR! Program gagal dihapus, mohon menghubungi administrator.');
			echo json_encode($msg);
		}
	}

	function get_urusan(){
		$id_rpjmd = $this->input->post('id_rpjmd');
		$id_tujuan = $this->input->post('id_tujuan');
		$id_sasaran = $this->input->post('id_sasaran');
		$id_program = $this->input->post('id_program');

		$data['urusan'] = $this->m_rpjmd_trx->get_all_urusan($id_program);
		$data['id_rpjmd'] = $id_rpjmd;
		$data['id_tujuan'] = $id_tujuan;
		$data['id_sasaran'] = $id_sasaran;
		$data['id_prog'] = $id_program;
		// $strategi = $this->load->view("rpjmd/view_strategi", $data);
		$this->load->view("rpjmd/view_urusan", $data);
	}

	function cru_urusan(){
		$id_rpjmd = $this->input->post('id_rpjmd');
		$id_tujuan = $this->input->post('id_tujuan');
		$id_sasaran = $this->input->post('id_sasaran');
		$id_program = $this->input->post('id_program');
		$id_urusan = $this->input->post('id_urusan');

		$kd_urusan_edit = NULL;
		$kd_bidang_edit = NULL;
		if (!empty($id_urusan)) {
			$result = $this->m_rpjmd_trx->get_one_urusan($id_urusan);
			$skpd_bidang_edit = $this->m_rpjmd_trx->get_all_skpd_bidang($result->id);
			if (empty($result)) {
				echo '<div style="width: 400px;">ERROR! Data strategi tidak ditemukan.</div>';
				return FALSE;
			}


			$skpd_bidang = "";
			foreach ($this->m_rpjmd_trx->skpd_bidang($result->kd_urusan, $result->kd_bidang) as $row) {
				$is_ketemu = 0;
				foreach ($skpd_bidang_edit as $row2) {
					if ($row->id_skpd == $row2->id_skpd) {
						$is_ketemu = 1;
						break;
					}
				}
				if ($is_ketemu == 1) {
					$skpd_bidang .= "<input type='checkbox' name='skpd_bidang[]' value='".$row->id_skpd."' checked>".$row->nama_skpd."<br>";
				}else {
					$skpd_bidang .= "<input type='checkbox' name='skpd_bidang[]' value='".$row->id_skpd."'>".$row->nama_skpd."<br>";
				}
			}

			$kd_urusan_edit = $result->kd_urusan;
			$kd_bidang_edit = $result->kd_bidang;
			$data['urusan'] = $result;
			$data['skpd_bidang'] = $skpd_bidang;

		}

		// $skpd_bidang = array("" => "");
		// foreach ($this->m_rpjmd_trx->skpd_bidang('4', '01') as $row) {
		// 	$skpd_bidang[$row->id_skpd] = $row->nama_skpd;
		// }

		$kd_urusan = array("" => "");
		foreach ($this->m_urusan->get_urusan() as $row) {
			$kd_urusan[$row->id] = $row->id .". ". $row->nama;
		}

		$kd_bidang = array("" => "");
		foreach ($this->m_bidang->get_bidang($kd_urusan_edit) as $row) {
			$kd_bidang[$row->id] = $row->id .". ". $row->nama;
		}

		$data['id_rpjmd'] = $id_rpjmd;
		$data['tujuan'] = $this->m_rpjmd_trx->get_one_rpjmd_tujuan($id_rpjmd, $id_tujuan);
		$data['sasaran'] = $this->m_rpjmd_trx->get_one_sasaran($id_rpjmd, $id_tujuan, $id_sasaran);
		$data['program'] = $this->m_rpjmd_trx->get_one_program_rpjmd_for_me($id_program);

		$data['kd_urusan'] = form_dropdown('kd_urusan', $kd_urusan, $kd_urusan_edit, 'data-placeholder="Pilih Urusan" class="common chosen-select" id="kd_urusan"');
		$data['kd_bidang'] = form_dropdown('kd_bidang', $kd_bidang, $kd_bidang_edit, 'data-placeholder="Pilih Bidang Urusan" class="common chosen-select" id="kd_bidang"');
		// $data['skpd_bidang'] = form_multiselect('skpd_bidang', $skpd_bidang, NULL, 'data-placeholder="Pilih Bidang Urusan" class="common select2" id="skpd_bidang"');
		// $data['skpd_bidang'] = $skpd_bidang;
		$this->load->view("rpjmd/cru_urusan", $data);
	}

	function save_urusan(){
		$id = $this->input->post('id_urusan');

		$data = $this->input->post();
		$id_program = $this->input->post('id_prog');
		$skpd_bidang = $this->input->post('skpd_bidang');

		$clean = array('id_urusan', 'skpd_bidang');
		$data = $this->global_function->clean_array($data, $clean);

		// print_r($skpd_bidang);
		// exit;
		if (!empty($id)) {
			$result = $this->m_rpjmd_trx->edit_urusan($data, $id, $id_program, $skpd_bidang);
		}else{
			$result = $this->m_rpjmd_trx->add_urusan($data, $id_program, $skpd_bidang);
		}

		if ($result) {
			$msg = array('success' => '1', 'msg' => 'Urusan berhasil dibuat.');
			echo json_encode($msg);
		}else{
			$msg = array('success' => '0', 'msg' => 'ERROR! Urusan gagal dibuat, mohon menghubungi administrator.');
			echo json_encode($msg);
		}
	}

	function delete_urusan(){
		$id = $this->input->post('id_urusan');
		$result = $this->m_rpjmd_trx->delete_urusan($id);
		if ($result) {
			$msg = array('success' => '1', 'msg' => 'Urusan berhasil dihapus.');
			echo json_encode($msg);
		}else{
			$msg = array('success' => '0', 'msg' => 'ERROR! Urusan gagal dihapus, mohon menghubungi administrator.');
			echo json_encode($msg);
		}
	}

	function get_program(){
		$id_rpjmd = $this->input->post('id_rpjmd');
		$id_tujuan = $this->input->post('id_tujuan');
		$id_sasaran = $this->input->post('id_sasaran');
		$id_strategi = $this->input->post('id_strategi');
		$id_kebijakan = $this->input->post('id_kebijakan');

		$data['program'] = $this->m_rpjmd_trx->get_all_program($id_kebijakan);
		$data['id_rpjmd'] = $id_rpjmd;
		$data['id_tujuan'] = $id_tujuan;
		$data['id_sasaran'] = $id_sasaran;
		$data['id_strategi'] = $id_strategi;
		$data['id_kebijakan'] = $id_kebijakan;
		$this->load->view("rpjmd/view_program", $data);
	}

	function pilih_program(){
		$id_rpjmd = $this->input->post('id_rpjmd');
		$id_sasaran = $this->input->post('id_sasaran');
		$id_indikator = $this->input->post('id_indikator');

		$data['id_rpjmd'] = $id_rpjmd;
		$data['id_sasaran'] = $id_sasaran;
		$data['id_indikator'] = $id_indikator;

		$this->load->view("rpjmd/pilih_program", $data);
	}

	function get_pilih_program(){
		$search = $this->input->post("search");
		$start = $this->input->post("start");
		$length = $this->input->post("length");
		$order = $this->input->post("order");
		$id_rpjmd = $this->input->post("id_rpjmd");
		$id_sasaran = $this->input->post("id_sasaran");
		$id_indikator = $this->input->post("id_indikator");

		$order_arr = array('Nm_Bidang','','','','nama_prog_or_keg','nama_skpd', 'Nm_Bidang');
		$program = $this->m_rpjmd_trx->get_all_pilih_program($search, $start, $length, $order["0"], $order_arr, $id_rpjmd, $id_sasaran, $id_indikator);
		$alldata = $this->m_rpjmd_trx->count_all_pilih_program($search, $start, $length, $order["0"], $order_arr, $id_rpjmd, $id_sasaran, $id_indikator);

		$data = array();
		$no=0;
		foreach ($program as $row) {
			$no++;

			$data[] = array(
							$no,
							$row->kd_urusan.". ".$row->kd_bidang.". ".$row->kd_program,
							$row->nama_prog_or_keg,
							$row->Nm_Bidang,
							$row->nama_skpd,
							'<a href="javascript:void(0)" onclick="pilih_program(\''. $id_rpjmd .'\',\''. $id_sasaran .'\',\''. $id_indikator .'\',\''. $row->id .'\')" class="icon-ok" title="Pilih Program"/>'
							);
		}
		$json = array("recordsTotal"=> $alldata, "recordsFiltered"=> $alldata, 'data' => $data);
		echo json_encode($json);
	}

	function do_pilih_program(){
		$data = $this->input->post();
		$result = $this->m_rpjmd_trx->add_program($data);

		if ($result) {
			$msg = array('success' => '1', 'msg' => 'Program berhasil ditambahkan.');
			echo json_encode($msg);
		}else{
			$msg = array('success' => '0', 'msg' => 'ERROR! Program gagal ditambahkan, mohon menghubungi administrator.');
			echo json_encode($msg);
		}
	}

	function preview_program_rpjmd(){
		$id = $this->input->post("id");

		$result = $this->m_rpjmd_trx->preview_program_rpjmd($id);
		if (!empty($result)) {
			$data['rpjmd'] = $result;
			$data['indikator_program'] = $this->m_renstra_trx->get_indikator_prog_keg($result->id);
			$this->load->view('rpjmd/preview', $data);
		}else{
			echo "Data tidak ditemukan . . .";
		}
	}

	function get_indikator(){
		$id_rpjmd = $this->input->post('id_rpjmd');
		$id_sasaran = $this->input->post('id_sasaran');

		$data['id_rpjmd'] = $id_rpjmd;
		$data['id_sasaran'] = $id_sasaran;
		$data['indikator'] = $this->m_rpjmd_trx->get_all_indikator($id_rpjmd, $id_sasaran, TRUE);
		$this->load->view("rpjmd/view_indikator", $data);
	}

	function cru_indikator(){
		$id_rpjmd = $this->input->post('id_rpjmd');
		$id_sasaran = $this->input->post('id_sasaran');
		$id_indikator = $this->input->post('id_indikator');

		$satuan_edit = NULL;
		if (!empty($id_indikator)) {
			$result = $this->m_rpjmd_trx->get_one_indikator($id_rpjmd, $id_sasaran, $id_indikator);
			if (empty($result)) {
				echo '<div style="width: 400px;">ERROR! Data sasaran tidak ditemukan.</div>';
				return FALSE;
			}
			$data['indikator'] = $result;
			$satuan_edit = $result->satuan_target;
		}

		$data['id_rpjmd'] = $id_rpjmd;
		$data['id_sasaran'] = $id_sasaran;
		$data['tujuan_n_sasaran'] = $this->m_rpjmd_trx->get_info_tujuan_n_sasaran_n_indikator(NULL, $id_sasaran);

		$satuan = array("" => "~~ Pilih Satuan ~~");
		foreach ($this->m_lov->get_list_lov(1) as $row) {
			$satuan[$row->kode_value]=$row->nama_value;
		}

		$data['satuan'] = form_dropdown('satuan_target', $satuan, $satuan_edit, 'class="common" id="satuan_target"');
		$this->load->view("rpjmd/cru_indikator", $data);
	}

	function save_indikator(){
		$id = $this->input->post('id_indikator');

		$data = $this->input->post();
		$clean = array('id_indikator');
		$data = $this->global_function->clean_array($data, $clean);

		if (!empty($id)) {
			$result = $this->m_rpjmd_trx->edit_indikator($data, $id);
		}else{
			$result = $this->m_rpjmd_trx->add_indikator($data);
		}

		if ($result) {
			$msg = array('success' => '1', 'msg' => 'Indikator berhasil dibuat.');
			echo json_encode($msg);
		}else{
			$msg = array('success' => '0', 'msg' => 'ERROR! Indikator gagal dibuat, mohon menghubungi administrator.');
			echo json_encode($msg);
		}
	}

	function delete_indikator(){
		$id = $this->input->post('id_indikator');
		$result = $this->m_rpjmd_trx->delete_indikator($id);
		if ($result) {
			$msg = array('success' => '1', 'msg' => 'Indikator berhasil dihapus.');
			echo json_encode($msg);
		}else{
			$msg = array('success' => '0', 'msg' => 'ERROR! Indikator gagal dihapus, mohon menghubungi administrator.');
			echo json_encode($msg);
		}
	}



		private function cetak_func($cetak=FALSE){
			if (!$cetak) {
				$temp['class_table']='class="table-common"';
			}else{
				$temp['class_table']='class="border"';
			}
			// $temp['misi'] = $this->m_rpjmd_trx->get_misi_rpjmd_4_cetak_final();
			// $temp['bidang_urusans'] = $this->m_rpjmd_trx->get_all_bidang_urusan_4_cetak_final();
			// $result = $this->load->view("rpjmd/cetak/cetak_bidang_urusan", $temp, TRUE);

			$temp['visi'] = $this->m_rpjmd_trx->get_visi_rpjmd_cetak();
			$temp['misi'] = $this->m_rpjmd_trx->get_misi_rpjmd_cetak( $temp['visi'][0]->id);
			$result = $this->load->view("rpjmd/cetak/cetak_visi", $temp, TRUE);
					//print_r($result );exit();

			return $result;
		}

	function cetak(){
		$this->auth->restrict();

		$data['cetak'] = $this->cetak_func();

		$this->template->load('template','rpjmd/cetak/view', $data);
	}

	function do_cetak(){
		ini_set('memory_limit', '-1');

		$protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://';
		$header = $this->m_template_cetak->get_value("GAMBAR");
		$data['logo'] = str_replace("src=\"","height=\"90px\" src=\"".$protocol.$_SERVER['HTTP_HOST'],$header);
		$data['header'] = $this->m_template_cetak->get_value("HEADER");

		$data['cetak'] = $this->cetak_func(TRUE);
		$html = $this->template->load('template_cetak', 'rpjmd/cetak/cetak_view', $data, true);

        $filename='RPJMD '. date("d-m-Y_H-i-s") .'.pdf';
	    pdf_create($html, $filename, "A4", "Landscape", FALSE);
	}

	 function do_cetak_misi_tujuan(){
		$temp['visi'] = $this->m_rpjmd_trx->get_visi_rpjmd_cetak();
		$temp['misi'] = $this->m_rpjmd_trx->get_misi_rpjmd_cetak( $temp['visi'][0]->id);
		 $JenisLaporan = 'Strategi-Arah-Kebijakan';
		 $Text = date("d-m-Y_H-i-s");
		$data['qr'] = $this->ciqrcode->generateQRcode($JenisLaporan, $Text,1.9);
		$temp['header']= $this->load->view('Cetak_head',$data, TRUE);
		$result = $this->load->view("rpjmd/cetak/cetak_misi", $temp, TRUE);
		$this->create_pdf->load($result,'RPJMD'.'-'.'Tujuan-Sasaran'.date("d-m-Y H:i:s"), 'A4-L','');
	}




	function cetak_func_indikator_program($cetak=FALSE){
		 if (!$cetak) {
			 $temp['class_table']='class="table-common"';
		 }else{
			 $temp['class_table']='class="border"';
		 }

		 $temp['dataprogram'] = $this->m_rpjmd_trx->get_program_sasaran_allcetak();

		 $result = $this->load->view("rpjmd/cetak/cetak_indikator_program", $temp, TRUE);

		 return $result;
	 }

	 function do_cetak_indikator_program(){
		 ini_set('memory_limit', '-1');

		 $protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://';
		 $header = $this->m_template_cetak->get_value("GAMBAR");
		 $data['logo'] = str_replace("src=\"","height=\"90px\" src=\"".$protocol.$_SERVER['HTTP_HOST'],$header);
		 $data['header'] = $this->m_template_cetak->get_value("HEADER");

		 $data['cetak'] = $this->cetak_func_indikator_program(TRUE);
		 $html = $this->template->load('template_cetak', 'rpjmd/cetak/cetak_view', $data, true);

				 $filename='RPJMD '. date("d-m-Y_H-i-s") .'.pdf';
			 pdf_create($html, $filename, "A4", "Landscape", FALSE);
	 }
	 function cetak_func_program_daerah($cetak=FALSE){
		if (!$cetak) {
			$temp['class_table']='class="table-common"';
		}else{
			$temp['class_table']='class="border"';
		}

		//$temp['dataprogram'] = $this->m_rpjmd_trx->get_program_sasaran_allcetak();
		$temp['dataprogram'] = $this->m_rpjmd_trx->get_program_daerah_all();

		$result = $this->load->view("rpjmd/cetak/cetak_program_daerah", $temp, TRUE);
				//print_r($result );exit();

		return $result;
	}

	function do_cetak_program_daerah(){
		ini_set('memory_limit', '-1');

		$protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://';
		$header = $this->m_template_cetak->get_value("GAMBAR");
		$data['logo'] = str_replace("src=\"","height=\"90px\" src=\"".$protocol.$_SERVER['HTTP_HOST'],$header);
		$data['header'] = $this->m_template_cetak->get_value("HEADER");

		$data['cetak'] = $this->cetak_func_program_daerah(TRUE);
		$html = $this->template->load('template_cetak', 'rpjmd/cetak/cetak_view', $data, true);

				$filename='RPJMD '. date("d-m-Y_H-i-s") .'.pdf';
			pdf_create($html, $filename, "A4", "Landscape", FALSE);
	}


	 function cetak_func_indikator_program_renstra($cetak=FALSE){
		if (!$cetak) {
			$temp['class_table']='class="table-common"';
		}else{
			$temp['class_table']='class="border"';
		}

		$temp['dataprogram'] = $this->m_rpjmd_trx->get_program_sasaran_allcetak_renstra();

		$result = $this->load->view("rpjmd/cetak/cetak_indikator_program_renstra", $temp, TRUE);
		//print_r($result);exit();

		return $result;
	}

	function do_cetak_indikator_program_renstra(){
		ini_set('memory_limit', '-1');

		$protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://';
		$header = $this->m_template_cetak->get_value("GAMBAR");
		$data['logo'] = str_replace("src=\"","height=\"90px\" src=\"".$protocol.$_SERVER['HTTP_HOST'],$header);
		$data['header'] = $this->m_template_cetak->get_value("HEADER");

		$data['cetak'] = $this->cetak_func_indikator_program_renstra(TRUE);
		$html = $this->template->load('template_cetak', 'rpjmd/cetak/cetak_view', $data, true);

				$filename='RPJMD '. date("d-m-Y_H-i-s") .'.pdf';
			pdf_create($html, $filename, "A4", "Landscape", FALSE);
	}


		private function cetak_func_progdaerah_renstra($cetak=FALSE){
			if (!$cetak) {
				$temp['class_table']='class="table-common"';
			}else{
				$temp['class_table']='class="border"';
			}

	//		$temp['program'] = $this->m_rpjmd_trx->get_prog_for_program_daerah_renstra();
			$temp['program'] = $this->m_rpjmd_trx->get_program_daerah_all();
			//print_r($temp['program'] );
			$result = $this->load->view("rpjmd/cetak/cetak_program_daerah_renstra", $temp, TRUE);
				//print_r($result);exit();

			return $result;
		}

		function do_cetak_progdaerah_renstra(){
			ini_set('memory_limit', '-1');

			$protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://';
			$header = $this->m_template_cetak->get_value("GAMBAR");
			$data['logo'] = str_replace("src=\"","height=\"90px\" src=\"".$protocol.$_SERVER['HTTP_HOST'],$header);
			$data['header'] = $this->m_template_cetak->get_value("HEADER");

			$data['cetak'] = $this->cetak_func_progdaerah_renstra(TRUE);
			$html = $this->template->load('template_cetak', 'rpjmd/cetak/cetak_view', $data, true);

	        $filename='RPJMD '. date("d-m-Y_H-i-s") .'.pdf';
		    pdf_create($html, $filename, "A4", "Landscape", FALSE);
		}
	##########
	# Revisi #
	##########
	function view_revisi(){
		$data['action'] = 'pengajuan';
		$data['revisi'] = $this->m_rpjmd_trx->get_revisi_pengajuan();
		$this->template->load('template','rpjmd/revisi/view', $data);
	}

	function do_p_revisi(){
		$skpd_setuju = $this->input->post("skpd_setuju");
		$skpd_tdk_setuju = $this->input->post("skpd_id");
		$action = $this->input->post("action");

		/*if ($action =="skpd") {
			$result = $this->m_rpjmd_trx->proses_revisi($skpd_setuju, $skpd_tdk_setuju, FALSE);
		}elseif ($action =="pengajuan") {
			$result = $this->m_rpjmd_trx->proses_revisi($skpd_setuju, $skpd_tdk_setuju);
		}*/
		$result = $this->m_rpjmd_trx->proses_revisi($skpd_setuju, $skpd_tdk_setuju);

		if ($result) {
			$msg = array('success' => '1', 'msg' => 'Pengajuan Revisi berhasil diproses.');
			echo json_encode($msg);
		}
	}

	function view_skpd_revisi(){
		$data['action'] = 'skpd';
		$data['revisi'] = $this->m_rpjmd_trx->get_revisi_skpd();
		$this->template->load('template','rpjmd/revisi/view_skpd', $data);
	}

	function view_p_revisi(){
		$this->auth->restrict_ajax_login();
		$data['id_skpd'] = $this->input->post('id');

		$msg = $this->load->view('rpjmd/pengajuan_revisi', $data, TRUE);
		echo json_encode(array('msg' => $msg));
	}

	function p_revisi(){
		$this->auth->restrict_ajax_login();

		$id_skpd = $this->input->post('id_skpd');
		$ket = $this->input->post('ket');
		$result = $this->m_rpjmd_trx->simpan_revisi($id_skpd, $ket);
		echo json_encode(array('success' => '1', 'msg' => 'Pengajuan Revisi Berhasil di kirim.'));
	}



	function do_cetak_arah_kebijakan($cetak=FALSE){
		 if (!$cetak) {
			 $temp['class_table']='class="table-common"';
		 }else{
			 $temp['class_table']='class="border"';
		 }
		 $temp['visi'] = $this->m_rpjmd_trx->get_visi_rpjmd_cetak();
		 $temp['misi'] = $this->m_rpjmd_trx->get_misi_rpjmd_cetak( $temp['visi'][0]->id);
		 $JenisLaporan = 'Strategi-Arah-Kebijakan';
		 $Text = date("d-m-Y_H-i-s");
		 $result = $this->load->view("rpjmd/cetak/cetak_arah_kebijakan", $temp, TRUE);
		 $data['qr'] = $this->ciqrcode->generateQRcode($JenisLaporan, $Text,1.9);
		 $temp['header']= $this->load->view('Cetak_head',$data, TRUE);
		 $result = $this->load->view("rpjmd/cetak/cetak_arah_kebijakan", $temp, TRUE);
		$this->create_pdf->load($result,'RPJMD-Strategi-Arah-Kebijakan'.date("d-m-Y H:i:s"), 'A4-L','');
		// return $result;
	 }
	 function do_cetak_indikator_tujuan(){
	 		$temp['visi'] = $this->m_rpjmd_trx->get_visi_rpjmd_cetak();
	 		$temp['tujuan'] = $this->m_rpjmd_trx->get_all_rpjmd_tujuan('1');
	 		$JenisLaporan = 'Indikator-Tujuan-Kebijakan';
	 		$Text = date("d-m-Y_H-i-s");
	 		$data['qr'] = $this->ciqrcode->generateQRcode($JenisLaporan, $Text,1.9);
	 		$temp['header']= $this->load->view('Cetak_head',$data, TRUE);
	 		$result = $this->load->view("rpjmd/cetak/cetak_indikator_tujuan", $temp, TRUE);
	 		//print_r($result);exit();
	 		$this->create_pdf->load($result,'RPJMD'.'-'.'Tujuan-Sasaran'.date("d-m-Y H:i:s"), 'A4-L','');
	 	}
		function do_cetak_indikasi_program_prioritas(){
		$temp['th_anggaran'] = $this->m_settings->get_tahun_anggaran_db();
		$temp['rpjmd'] = $this->m_rpjmd_trx->get_urusan_all();
		$JenisLaporan = 'indikasi-program-prioritas';
		$Text = date("d-m-Y_H-i-s");
		$data['qr'] = $this->ciqrcode->generateQRcode($JenisLaporan, $Text,1.9);
		$temp['header']= $this->load->view('Cetak_head',$data, TRUE);
		$result = $this->load->view("rpjmd/cetak/cetak_program_prioritas", $temp, TRUE);
		 // print_r($result);exit();
		$this->create_pdf->load($result,'RPJMD'.'-'.'Tujuan-Sasaran'.date("d-m-Y H:i:s"), 'A4-L','');
	}

	function do_cetak_pemerintahan_organisasi(){
		$temp['th_anggaran'] = $this->m_settings->get_tahun_anggaran_db();
		$temp['rpjmd'] = $this->m_rpjmd_trx->get_urusan_all();
		$JenisLaporan = 'urusan-pemerintahan-dan-organisasi';
		$Text = date("d-m-Y_H-i-s");
		$data['qr'] = $this->ciqrcode->generateQRcode($JenisLaporan, $Text,1.9);
		$temp['header']= $this->load->view('Cetak_head',$data, TRUE);
		$result = $this->load->view("rpjmd/cetak/cetak_pemerintahan_organisasi", $temp, TRUE);
		 // print_r($result);exit();
		$this->create_pdf->load($result,'RPJMD'.'-'.'urusan-pemerintahan-dan-organisasi'.date("d-m-Y H:i:s"), 'A4-L','');
	}

	function do_cetak_organisasi_pemerintahan(){
		$temp['th_anggaran'] = $this->m_settings->get_tahun_anggaran_db();
		// $temp['rpjmd'] = $this->m_rpjmd_trx->get_urusan_all();
		$temp['rpjmd'] = $this->m_rpjmd_trx->get_all_skpd();
		$JenisLaporan = 'urusan-organisasi-dan-pemerintahan';
		$Text = date("d-m-Y_H-i-s");
		$data['qr'] = $this->ciqrcode->generateQRcode($JenisLaporan, $Text,1.9);
		$temp['header']= $this->load->view('Cetak_head',$data, TRUE);
		$result = $this->load->view("rpjmd/cetak/cetak_organisasi_pemerintahan", $temp, TRUE);
		  // print_r($result);exit();
		$this->create_pdf->load($result,'RPJMD'.'-'.'urusan-organisasi-dan-pemerintahan'.date("d-m-Y H:i:s"), 'A4-L','');
	}

	function do_cetak_program_prioritas(){
		// $temp['th_anggaran'] = $this->m_settings->get_tahun_anggaran_db();
		// // $temp['rpjmd'] = $this->m_rpjmd_trx->get_urusan_all();
		// $temp['rpjmd'] = $this->m_rpjmd_trx->get_all_rpjmd();
		// $JenisLaporan = 'program-prioritas';
		// $Text = date("d-m-Y_H-i-s");
		// $data['qr'] = $this->ciqrcode->generateQRcode($JenisLaporan, $Text,1.9);
		// $temp['header']= $this->load->view('Cetak_head',$data, TRUE);
		// $result = $this->load->view("rpjmd/cetak/cetak_prog_prio", $temp, TRUE);
		//   // print_r($result);exit();
		// $this->create_pdf->load($result,'RPJMD'.'-'.'program-prioritas'.date("d-m-Y H:i:s"), 'A4-L','');

		$temp['th_anggaran'] = $this->m_settings->get_tahun_anggaran_db();
		$temp['rpjmd'] = $this->m_rpjmd_trx->get_all_rpjmd();
		$JenisLaporan = 'program-prioritas';
		$Text = date("d-m-Y_H-i-s");
		// $data['qr'] = $this->ciqrcode->generateQRcode($JenisLaporan, $Text,1.9);
		// $temp['header']= $this->load->view('Cetak_head',$data, TRUE);
		$temp['namafile'] = 'RPJMD-program-prioritas'.date("d-m-Y H:i:s");
		$this->load->view("rpjmd/cetak/cetak_prog_prio", $temp);
	}
}
