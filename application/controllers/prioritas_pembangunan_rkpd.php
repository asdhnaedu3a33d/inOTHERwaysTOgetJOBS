<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Prioritas_pembangunan_rkpd extends CI_Controller
{
	var $CI = NULL;
	public function __construct()
	{
		$this->CI =& get_instance();
        parent::__construct();
        $this->load->database();
        $this->load->model(array('m_skpd','m_lov','m_prioritas_pembangunan_rkpd'));
        if (!empty($this->session->userdata("db_aktif"))) {
            $this->load->database($this->session->userdata("db_aktif"), FALSE, TRUE);
        }
	}

	function index(){
		$this->view_prioritas();
	}

	function view_prioritas(){
		$tahun = $this->m_settings->get_tahun_anggaran();
		$data['prioritas'] = $this->m_prioritas_pembangunan_rkpd->get_data_prioritas($tahun)->result();
		$this->template->load('template', 'prioritas_pembangunan_rkpd/view_prioritas', $data);
	}

	function cru_prioritas(){
		$id = $this->input->post('idpi');

		$prioritas_edit = "";
		if (!empty($id)) {
			$data['prioritas'] = $this->m_prioritas_pembangunan_rkpd->get_data_prioritas(NULL, $id)->row();
			$prioritas_edit = $data['prioritas']->id_prioritas;
		}

		// $prioritas = array("" => "");
		// foreach ($this->m_prioritas_pembangunan_rkpd->get_all_prioritas_combo()->result() as $row) {
		// 	$prioritas[$row->id] = $row->prioritas_pembangunan_daerah;
		// }

		// $data['prioritas_combo'] = form_dropdown('id_prioritas', $prioritas, $prioritas_edit, 'data-placeholder="Pilih Prioritas Pembangunan" class="common chosen-select" id="id_prioritas"');

		$this->load->view('prioritas_pembangunan_rkpd/cru_prioritas', $data);
	}

	function save_prioritas(){
		$id = $this->input->post('id');
		$id_prioritas = $this->input->post('id_prioritas');
		$tahun = $this->m_settings->get_tahun_anggaran();

		$data = array(
			'id_prioritas' => $id_prioritas, 
			'created_date' => date('Y-m-d H:i:s'),
			'tahun' => $tahun
			);
		if (!empty($id)) {
			$result = $this->m_prioritas_pembangunan_rkpd->edit_prioritas($data, $id);
		}else{
			$result = $this->m_prioritas_pembangunan_rkpd->add_prioritas($data);
		}

		if ($result) {
			$msg = array('success' => '1', 'msg' => 'Prioritas Pembangunan berhasil dibuat.');
			echo json_encode($msg);
		}else{
			$msg = array('success' => '0', 'msg' => 'ERROR! Prioritas Pembangunan gagal dibuat, mohon menghubungi administrator.');
			echo json_encode($msg);
		}
	}

	function delete_prioritas(){
		$id = $this->input->post('idpi');

		$result = $this->m_prioritas_pembangunan_rkpd->delete_prioritas($id);

		if ($result) {
			$msg = array('success' => '1', 'msg' => 'Prioritas Pembangunan berhasil dihapus.');
			echo json_encode($msg);
		}else{
			$msg = array('success' => '0', 'msg' => 'ERROR! Prioritas Pembangunan gagal dihapus, mohon menghubungi administrator.');
			echo json_encode($msg);
		}
	}

	function view_sasaran(){
		$idpi = $this->input->post('idpi');
		$data['id_prioritas'] = $idpi;
		$data['sasaran'] = $this->m_prioritas_pembangunan_rkpd->get_all_sasaran($idpi)->result();
		$this->load->view('prioritas_pembangunan_rkpd/view_sasaran', $data);
	}

	function cru_sasaran(){
		$id = $this->input->post('ids');
		$id_prioritas = $this->input->post('idpi');

		$sasaran_edit = "";
		if (!empty($id)) {
			$data['sasaran'] = $this->m_prioritas_pembangunan_rkpd->get_one_sasaran($id)->row();
			$sasaran_edit = $data['sasaran']->id_sasaran;
		}

		$data['prioritas'] = $this->m_prioritas_pembangunan_rkpd->get_data_prioritas(NULL, $id_prioritas)->row();

		$sasaran = array("" => "");
		foreach ($this->m_prioritas_pembangunan_rkpd->get_all_sasaran_combo()->result() as $row) {
			$sasaran[$row->id] = $row->sasaran;
		}

		$data['sasaran_combo'] = form_dropdown('id_sasaran', $sasaran, $sasaran_edit, 'data-placeholder="Pilih Sasaran" class="common chosen-select" id="id_sasaran"');

		$this->load->view('prioritas_pembangunan_rkpd/cru_sasaran', $data);
	}

	function save_sasaran(){
		$id = $this->input->post('id');
		$data = $this->input->post();

		$clean = array('id');
		$data = $this->global_function->clean_array($data, $clean);

		$add = array('created_date' => date('Y-m-d H:i:s'));
		$data = $this->global_function->add_array($data, $add);

		if (!empty($id)) {
			$result = $this->m_prioritas_pembangunan_rkpd->edit_sasaran($data, $id);
		}else{
			$result = $this->m_prioritas_pembangunan_rkpd->add_sasaran($data);
		}

		if ($result) {
			$msg = array('success' => '1', 'msg' => 'Sasaran Pembangunan berhasil dibuat.');
			echo json_encode($msg);
		}else{
			$msg = array('success' => '0', 'msg' => 'ERROR! Sasaran Pembangunan gagal dibuat, mohon menghubungi administrator.');
			echo json_encode($msg);
		}
	}

	function delete_sasaran(){
		$id = $this->input->post('ids');

		$result = $this->m_prioritas_pembangunan_rkpd->delete_sasaran($id);

		if ($result) {
			$msg = array('success' => '1', 'msg' => 'Sasaran Pembangunan berhasil dihapus.');
			echo json_encode($msg);
		}else{
			$msg = array('success' => '0', 'msg' => 'ERROR! Sasaran Pembangunan gagal dihapus, mohon menghubungi administrator.');
			echo json_encode($msg);
		}
	}

	function view_program(){
		$idpi = $this->input->post('idpi');
		$ids = $this->input->post('ids');
		$data['id_prioritas'] = $idpi;
		$data['id_sasaran'] = $ids;
		$data['program'] = $this->m_prioritas_pembangunan_rkpd->get_all_program($idpi, $ids)->result();
		$this->load->view('prioritas_pembangunan_rkpd/view_program', $data);
	}

	function cru_program(){
		$id = $this->input->post('idpr');
		$id_sasaran = $this->input->post('ids');
		$id_prioritas = $this->input->post('idpi');

		$program_edit = "";
		if (!empty($id)) {
			$data['program'] = $this->m_prioritas_pembangunan_rkpd->get_one_program($id)->row();
			$data['indikator'] = $this->m_prioritas_pembangunan_rkpd->get_indikator_prog_keg($id);
			$program_edit = $data['program']->id_prog_or_keg;
		}

		$data['prioritas'] = $this->m_prioritas_pembangunan_rkpd->get_data_prioritas(NULL, $id_prioritas)->row();
		$data['sasaran'] = $this->m_prioritas_pembangunan_rkpd->get_one_sasaran($id_sasaran)->row();

		$program = array("" => "");
		foreach ($this->m_prioritas_pembangunan_rkpd->get_all_program_combo()->result() as $row) {
			$program[$row->id] = $row->prog_keg;
		}

		$status_indikator = array("" => "~~ Pilih Positif / Negatif ~~");
		foreach ($this->m_lov->get_status_indikator() as $row) {
			$status_indikator[$row->kode_status_indikator]=$row->nama_status_indikator;
		}

		$kategori_indikator = array("" => "~~ Pilih Kategori Indikator ~~");
		foreach ($this->m_lov->get_kategori_indikator() as $row) {
			$kategori_indikator[$row->kode_kategori_indikator]=$row->nama_kategori_indikator;
		}

		$data['program_combo'] = form_dropdown('id_prog_or_keg', $program, $program_edit, 'data-placeholder="Pilih Program" class="common chosen-select" id="id_prog_or_keg"');
		$data['status_indikator'] = $status_indikator;
		$data['kategori_indikator'] = $kategori_indikator;

		$this->load->view('prioritas_pembangunan_rkpd/cru_program', $data);
	}

	function save_program(){
		$tahun = $this->m_settings->get_tahun_anggaran();
		$id = $this->input->post('id');
		$data = $this->input->post();
		$id_indikator = $this->input->post('id_indikator', TRUE);
		$indikator_kinerja = $this->input->post('indikator_kinerja', TRUE);
		$satuan_target = $this->input->post('satuan_target', TRUE);
		$status_target = $this->input->post('status_target', TRUE);
		$kategori_target = $this->input->post('kategori_target', TRUE);
		$target = $this->input->post('target', TRUE);

		$clean = array('id', 'id_indikator', 'indikator_kinerja', 'satuan_target', 'status_target', 'kategori_target', 'target');
		$data = $this->global_function->clean_array($data, $clean);

		$add = array('created_date' => date('Y-m-d H:i:s'), 'tahun' => $tahun, 'is_prog_or_keg' => 1);
		$data = $this->global_function->add_array($data, $add);

		if (!empty($id)) {
			$result = $this->m_prioritas_pembangunan_rkpd->edit_program($id_indikator, $id, $indikator_kinerja, $satuan_target, $status_target, $kategori_target, $target, $data);
		}else{
			$result = $this->m_prioritas_pembangunan_rkpd->add_program($indikator_kinerja, $satuan_target, $status_target, $kategori_target, $target, $data);
		}

		if ($result) {
			$msg = array('success' => '1', 'msg' => 'Program Pembangunan berhasil dibuat.');
			echo json_encode($msg);
		}else{
			$msg = array('success' => '0', 'msg' => 'ERROR! Program Pembangunan gagal dibuat, mohon menghubungi administrator.');
			echo json_encode($msg);
		}
	}

	function delete_program(){
		$id = $this->input->post('idpr');

		$result = $this->m_prioritas_pembangunan_rkpd->delete_program($id);

		if ($result) {
			$msg = array('success' => '1', 'msg' => 'Program Pembangunan berhasil dihapus.');
			echo json_encode($msg);
		}else{
			$msg = array('success' => '0', 'msg' => 'ERROR! Program Pembangunan gagal dihapus, mohon menghubungi administrator.');
			echo json_encode($msg);
		}
	}

	function view_kegiatan(){
		$idpi = $this->input->post('idpi');
		$ids = $this->input->post('ids');
		$idpr = $this->input->post('idpr');
		
		$data['id_prioritas'] = $idpi;
		$data['id_sasaran'] = $ids;
		$data['id_program'] = $idpr;
		$data['kegiatan'] = $this->m_prioritas_pembangunan_rkpd->get_all_kegiatan($idpi, $ids, $idpr)->result();
		$this->load->view('prioritas_pembangunan_rkpd/view_kegiatan', $data);
	}

	function cru_kegiatan(){
		$id = $this->input->post('idk');
		$id_program = $this->input->post('idpr');
		$id_sasaran = $this->input->post('ids');
		$id_prioritas = $this->input->post('idpi');

		$kegiatan_edit = "";
		$skpd_edit = "";
		if (!empty($id)) {
			$data['kegiatan'] = $this->m_prioritas_pembangunan_rkpd->get_one_kegiatan($id)->row();
			$data['indikator'] = $this->m_prioritas_pembangunan_rkpd->get_indikator_prog_keg($id);
			// $skpd_edit = $this->m_prioritas_pembangunan_rkpd->get_all_perangkat_daerah($id)->result();
			$kegiatan_edit = $data['kegiatan']->id_prog_or_keg;
			foreach ($this->m_prioritas_pembangunan_rkpd->get_all_perangkat_daerah($id)->result() as $row) {
				$skpd_edit[] = $row->id_skpd;
			}
		}

		$data['prioritas'] = $this->m_prioritas_pembangunan_rkpd->get_data_prioritas(NULL, $id_prioritas)->row();
		$data['sasaran'] = $this->m_prioritas_pembangunan_rkpd->get_one_sasaran($id_sasaran)->row();
		$data['program'] = $this->m_prioritas_pembangunan_rkpd->get_one_program($id_program)->row();

		$kegiatan = array("" => "");
		foreach ($this->m_prioritas_pembangunan_rkpd->get_all_kegiatan_combo()->result() as $row) {
			$kegiatan[$row->id] = $row->kegiatan_rkpd;
		}

		$status_indikator = array("" => "~~ Pilih Positif / Negatif ~~");
		foreach ($this->m_lov->get_status_indikator() as $row) {
			$status_indikator[$row->kode_status_indikator] = $row->nama_status_indikator;
		}

		$kategori_indikator = array("" => "~~ Pilih Kategori Indikator ~~");
		foreach ($this->m_lov->get_kategori_indikator() as $row) {
			$kategori_indikator[$row->kode_kategori_indikator] = $row->nama_kategori_indikator;
		}

		$skpd = "";
		foreach ($this->m_skpd->get_skpd_all() as $row) {
			$skpd[$row->id_skpd] = $row->nama_skpd;
		}

		$data['kegiatan_combo'] = form_dropdown('id_prog_or_keg', $kegiatan, $kegiatan_edit, 'data-placeholder="Pilih Kegiatan" class="common chosen-select" id="id_prog_or_keg"');
		$data['skpd_combo'] = form_multiselect('id_skpd[]', $skpd, $skpd_edit, 'data-placeholder="Pilih SKPD" class="common select2" id="id_skpd"');
		$data['status_indikator'] = $status_indikator;
		$data['kategori_indikator'] = $kategori_indikator;

		$data['idsk'] = $skpd;
		$this->load->view('prioritas_pembangunan_rkpd/cru_kegiatan', $data);
	}

	function save_kegiatan(){
		$tahun = $this->m_settings->get_tahun_anggaran();
		$id = $this->input->post('id');
		$data = $this->input->post();
		$id_indikator = $this->input->post('id_indikator', TRUE);
		$indikator_kinerja = $this->input->post('indikator_kinerja', TRUE);
		$satuan_target = $this->input->post('satuan_target', TRUE);
		$status_target = $this->input->post('status_target', TRUE);
		$kategori_target = $this->input->post('kategori_target', TRUE);
		$target = $this->input->post('target', TRUE);
		$skpd = $this->input->post('id_skpd', TRUE);

		$clean = array('id', 'id_indikator', 'indikator_kinerja', 'satuan_target', 'status_target', 'kategori_target', 'target', 'id_skpd');
		$data = $this->global_function->clean_array($data, $clean);

		$add = array('created_date' => date('Y-m-d H:i:s'), 'tahun' => $tahun, 'is_prog_or_keg' => 2);
		$data = $this->global_function->add_array($data, $add);

		if (!empty($id)) {
			$result = $this->m_prioritas_pembangunan_rkpd->edit_kegiatan($id_indikator, $id_skpd, $id, $indikator_kinerja, $satuan_target, $status_target, $kategori_target, $target, $skpd, $data);
		}else{
			$result = $this->m_prioritas_pembangunan_rkpd->add_kegiatan($indikator_kinerja, $satuan_target, $status_target, $kategori_target, $target, $skpd, $data);
		}

		if ($result) {
			$msg = array('success' => '1', 'msg' => 'Program Kegiatan berhasil dibuat.');
			echo json_encode($msg);
		}else{
			$msg = array('success' => '0', 'msg' => 'ERROR! Program Kegiatan gagal dibuat, mohon menghubungi administrator.');
			echo json_encode($msg);
		}
	}

	function delete_kegiatan(){
		$id = $this->input->post('idk');

		$result = $this->m_prioritas_pembangunan_rkpd->delete_kegiatan($id);

		if ($result) {
			$msg = array('success' => '1', 'msg' => 'Kegiatan Pembangunan berhasil dihapus.');
			echo json_encode($msg);
		}else{
			$msg = array('success' => '0', 'msg' => 'ERROR! Kegiatan Pembangunan gagal dihapus, mohon menghubungi administrator.');
			echo json_encode($msg);
		}
	}


}
