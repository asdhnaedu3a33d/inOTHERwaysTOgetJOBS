<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_rpjmd_trx extends CI_Model
{
	var $table = 't_rpjmd';
	var $table_misi = 't_rpjmd_misi';
	var $table_tujuan = 't_rpjmd_tujuan';
	var $table_sasaran = 't_rpjmd_sasaran';
	var $table_indikator_tujuan = 't_rpjmd_indikator_tujuan';
	var $table_indikator_sasaran = 't_rpjmd_indikator_sasaran';
	var $table_indikator_program = 't_rpjmd_indikator_program';
	var $table_strategi = 't_rpjmd_strategi';
	var $table_kebijakan = 't_rpjmd_kebijakan';
	var $table_indikator = 't_rpjmd_indikator';
	var $table_program = 't_rpjmd_program';
	var $table_program_ng = 't_rpjmd_program_ng';
	var $table_kebijakan_sasaran = 't_rpjmd_kebijakan_sasaran';
	var $table_urusan = 't_rpjmd_urusan';
	var $table_program_skpd = 't_rpjmd_program_skpd';

    public function __construct()
    {
        parent::__construct();
    }

	private function create_history($data){
		$data['created_date'] = date("Y-m-d H:i:s");
		$data['created_by'] = $this->session->userdata('username');
		return $data;
	}

	private function change_history($data){
		$data['changed_date'] = date("Y-m-d H:i:s");
		$data['changed_by'] = $this->session->userdata('username');
		return $data;
	}

	private function add_misi($misi){
		$this->db->insert($this->table_misi, $misi);
		return $this->db->insert_id();
	}

	private function update_misi($misi, $id){
		$this->db->where("id", $id);
		$this->db->update($this->table_misi, $misi);
	}

	private function add_tujuan($tujuan){
		$this->db->insert_batch($this->table_tujuan, $tujuan);
	}

	private function update_tujuan($tujuan, $id){
		$this->db->where("id", $id);
		$this->db->update($this->table_tujuan, $tujuan);
	}

	function get_all_rpjmd(){
		$query = "SELECT * FROM ".$this->table." ORDER BY id ASC";
		$result = $this->db->query($query);
		return $result->result();
	}

	function get_one_rpjmd($id){
		$this->db->select($this->table.".*");
		$this->db->from($this->table);
		$this->db->where($this->table.".id", $id);

		$result = $this->db->get();
		return $result->row();
	}

	function get_all_rpjmd_misi($id, $no_result=FALSE){
		$this->db->from($this->table_misi);
		$this->db->where("id_rpjmd", $id);
		$this->db->order_by('id_rpjmd', 'asc');
		$result = $this->db->get();
		if ($no_result) {
			return $result;
		}else{
			return $result->result();
		}
	}

	function get_all_rpjmd_tujuan($id_rpjmd, $no_result=FALSE){
		$this->db->from($this->table_tujuan);
		$this->db->where("id_rpjmd", $id_rpjmd);
		$this->db->order_by('id_misi', 'asc');
		$result = $this->db->get();
		if ($no_result) {
			return $result;
		}else{
			return $result->result();
		}
	}

	function get_each_rpjmd_tujuan($id, $id_misi){
		$this->db->from($this->table_tujuan);
		$this->db->where("id_rpjmd", $id);
		$this->db->where("id_misi", $id_misi);
		$result = $this->db->get();
		return $result;
	}

	function get_each_rpjmd_indikator_tujuan($id, $inner=FALSE, $no_result=FALSE){
		$this->db->select($this->table_indikator_tujuan.'.*');
		$this->db->from($this->table_indikator_tujuan);
		$this->db->where("id_tujuan", $id);
		if ($inner) {
			$this->db->select("m_status_indikator.nama_status_indikator");
			$this->db->select("m_kategori_indikator.nama_kategori_indikator");
			$this->db->join('m_status_indikator', $this->table_indikator_tujuan.'.status_indikator = m_status_indikator.kode_status_indikator', 'inner');
			$this->db->join('m_kategori_indikator', $this->table_indikator_tujuan.'.kategori_indikator = m_kategori_indikator.kode_kategori_indikator', 'inner');
		}
		$result = $this->db->get();
		if ($no_result) {
			return $result;
		}else{
			return $result->result();
		}
	}

	function get_one_rpjmd_tujuan($id_rpjmd, $id_tujuan){
		$this->db->from($this->table_tujuan);
		$this->db->where("id_rpjmd", $id_rpjmd);
		$this->db->where("id", $id_tujuan);
		$result = $this->db->get();
		return $result->row();
	}

	function add_rpjmd($data, $misi, $tujuan, $data_indikator){
		$this->db->trans_strict(FALSE);
		$this->db->trans_start();

		$data = $this->create_history($data);

		$this->db->insert($this->table, $data);
		$id_rpjmd = $this->db->insert_id();

		$id_misi = array();
		foreach ($misi as $key => $value) {
			$id_misi[$key] = $this->add_misi(array('id_rpjmd' => $id_rpjmd, 'misi' => $value));
		}

		$tujuan_batch = array();
		foreach ($misi as $key => $value) {
			foreach ($tujuan[$key] as $key1 => $value1) {
				$this->db->insert($this->table_tujuan, array('id_rpjmd' => $id_rpjmd, 'id_misi' => $id_misi[$key], 'tujuan' => $value1));
				$id_tujuan = $this->db->insert_id();
				foreach ($data_indikator['indikator'][$key][$key1] as $key2 => $value2) {
					$this->db->insert($this->table_indikator_tujuan, array('id_tujuan' => $id_tujuan, 'indikator' => $data_indikator['indikator'][$key][$key1][$key2], 'cara_pengukuran' => $data_indikator['cara_pengukuran'][$key][$key1][$key2], 'satuan_target' => $data_indikator['satuan_target'][$key][$key1][$key2], 'status_indikator' => $data_indikator['status_indikator'][$key][$key1][$key2], 'kategori_indikator' => $data_indikator['kategori_indikator'][$key][$key1][$key2], 'kondisi_awal' => $data_indikator['kondisi_awal'][$key][$key1][$key2], 'target_1' => $data_indikator['target_1'][$key][$key1][$key2], 'target_2' => $data_indikator['target_2'][$key][$key1][$key2], 'target_3' => $data_indikator['target_3'][$key][$key1][$key2], 'target_4' => $data_indikator['target_4'][$key][$key1][$key2], 'target_5' => $data_indikator['target_5'][$key][$key1][$key2], 'kondisi_akhir' => $data_indikator['kondisi_akhir'][$key][$key1][$key2]
					));
				}

				// $tujuan_batch[] = array('id_rpjmd' => $id_rpjmd, 'id_misi' => $id_misi[$key], 'tujuan' => $value1);
			}
		}

		// $this->add_tujuan($tujuan_batch);

		$this->db->trans_complete();
		return $this->db->trans_status();
	}

	function edit_rpjmd($data, $misi, $tujuan, $id_misi_old, $id_tujuan_old, $id_rpjmd, $id_indikator_old, $indikator){
		$this->db->trans_strict(FALSE);
		$this->db->trans_start();

		$data = $this->change_history($data);

		$this->db->where('id', $id_rpjmd);
		$this->db->update($this->table, $data);

		$id_misi = array();
		foreach ($misi as $key => $value) {
			if (!empty($id_misi_old[$key])) {
				$this->update_misi(array('id_rpjmd' => $id_rpjmd, 'misi' => $value), $id_misi_old[$key]);
				$id_misi[$key] = $id_misi_old[$key];
				unset($id_misi_old[$key]);
			}else{
				$id_misi[$key] = $this->add_misi(array('id_rpjmd' => $id_rpjmd, 'misi' => $value));
			}
		}

		// //tujuan batch untuk yg baru
		// $tujuan_batch = array();
		// foreach ($misi as $key => $value) {
		// 	foreach ($tujuan[$key] as $key1 => $value1) {
		// 		if (!empty($id_tujuan[$key][$key1])) {
		// 			//update tujuannya
		// 			$this->update_tujuan(array('tujuan' => $value1, 'id_misi' => $id_misi[$key]), $id_tujuan[$key][$key1]);
		// 			unset($id_tujuan[$key][$key1]);
		// 		}else{
		// 			$tujuan_batch[] = array('id_rpjmd' => $id_rpjmd, 'id_misi' => $id_misi[$key], 'tujuan' => $value1);
		// 		}
		// 	}
		// }

		// if (!empty($tujuan_batch)) {
		// 	$this->add_tujuan($tujuan_batch);
		// }


		// $id_tujuan_batch = array();
		// foreach ($misi as $key => $value) {
		// 	foreach ($id_tujuan[$key] as $key1 => $value1) {
		// 		$id_tujuan_batch[] = $value1;
		// 	}
		// }
		// print_r($misi);
		// exit();

		$id_tujuan = array();
		foreach ($misi as $key => $value) {
			foreach ($tujuan[$key] as $key1 => $value1) {
				if (!empty($id_tujuan_old[$key][$key1])) {
					$this->update_tujuan(array('tujuan' => $value1, 'id_misi' => $id_misi[$key]), $id_tujuan_old[$key][$key1]);
					$id_tujuan[$key][$key1] = $id_tujuan_old[$key][$key1];
					unset($id_tujuan_old[$key][$key1]);
				}else{
					// $id_tujuan[$key][$key1] = $this->add_tujuan(array('id_rpjmd' => $id_rpjmd, 'id_misi' => $id_misi[$key], 'tujuan' => $value1));
					$this->db->insert($this->table_tujuan, array('id_rpjmd' => $id_rpjmd, 'id_misi' => $id_misi[$key], 'tujuan' => $value1));
					$id_tujuan[$key][$key1] = $this->db->insert_id();
					// print_r($this->db->last_query());
				}
			}
		}

// exit();
		$id_indikator = array();
		foreach ($misi as $key => $value) {
			foreach ($tujuan[$key] as $key1 => $value1) {
				foreach ($indikator['indikator'][$key][$key1] as $key2 => $value2) {
					if (!empty($id_indikator_old[$key][$key1][$key2])) {
						$isinyah = array('indikator' => $indikator['indikator'][$key][$key1][$key2], 'cara_pengukuran' => $indikator['cara_pengukuran'][$key][$key1][$key2], 'satuan_target' => $indikator['satuan_target'][$key][$key1][$key2], 'status_indikator' => $indikator['status_indikator'][$key][$key1][$key2], 'kategori_indikator' => $indikator['kategori_indikator'][$key][$key1][$key2], 'kondisi_awal' => $indikator['kondisi_awal'][$key][$key1][$key2], 'target_1' => $indikator['target_1'][$key][$key1][$key2], 'target_2' => $indikator['target_2'][$key][$key1][$key2], 'target_3' => $indikator['target_3'][$key][$key1][$key2], 'target_4' => $indikator['target_4'][$key][$key1][$key2], 'target_5' => $indikator['target_5'][$key][$key1][$key2], 'kondisi_akhir' => $indikator['kondisi_akhir'][$key][$key1][$key2]);
						$this->db->where("id", $id_indikator_old[$key][$key1][$key2]);
						$this->db->update($this->table_indikator_tujuan, $isinyah);
						$id_indikator[$key][$key1][$key2] = $id_indikator_old[$key][$key1][$key2];
						unset($id_indikator_old[$key][$key1][$key2]);
					}else{
						$isinyahnyah = array('id_tujuan' => $id_tujuan[$key][$key1], 'indikator' => $indikator['indikator'][$key][$key1][$key2], 'cara_pengukuran' => $indikator['cara_pengukuran'][$key][$key1][$key2], 'satuan_target' => $indikator['satuan_target'][$key][$key1][$key2], 'status_indikator' => $indikator['status_indikator'][$key][$key1][$key2], 'kategori_indikator' => $indikator['kategori_indikator'][$key][$key1][$key2], 'kondisi_awal' => $indikator['kondisi_awal'][$key][$key1][$key2], 'target_1' => $indikator['target_1'][$key][$key1][$key2], 'target_2' => $indikator['target_2'][$key][$key1][$key2], 'target_3' => $indikator['target_3'][$key][$key1][$key2], 'target_4' => $indikator['target_4'][$key][$key1][$key2], 'target_5' => $indikator['target_5'][$key][$key1][$key2], 'kondisi_akhir' => $indikator['kondisi_akhir'][$key][$key1][$key2]);
						$this->db->insert($this->table_indikator_tujuan, $isinyahnyah);
					}

				}

			}
		}

		// $id_tujuan_batchai = array();
		// $id_indikator_batchai = array();
	 // 	foreach ($misi as $key => $value) {
	 // 		foreach ($id_tujuan_old[$key] as $key1 => $value1) {
	 // 			$id_tujuan_batchai[] = $value1;
	 // 		}
	 // 	}

		// $id_indikator_batchai = array();
		// foreach ($misi as $key => $value) {
	 // 		foreach ($tujuan[$key] as $key1 => $value1) {
		// 		foreach ($id_indikator_old[$key][$key1] as $key2 => $value2) {
		// 			$id_indikator_batchai[] = $value2;
		// 		}
	 // 		}
	 // 	}

		// if (!empty($id_indikator_batchai)) {
		// 	$this->db->where_in('id', $id_indikator_batchai);
		// 	$this->db->delete($this->table_indikator_tujuan);
		// }

		// if (!empty($id_tujuan_batchai)) {
		// 	$this->db->where_in('id', $id_tujuan_batchai);
		// 	$this->db->delete($this->table_tujuan);
		// }


		// if (!empty($id_tujuan_batch)) {
		// 	$this->db->where_in('id', $id_tujuan_batch);
		// 	$this->db->delete($this->table_tujuan);
		// }

		// if (!empty($id_misi_old)) {
		// 	$this->db->where_in('id', $id_misi_old);
		// 	$this->db->delete($this->table_misi);
		// }

		$this->db->trans_complete();
		return $this->db->trans_status();
	}

	function delete_rpjmd($id){
		$this->db->where('id', $id);
		$result = $this->db->delete($this->table);
		return $result;
	}

	function get_all_sasaran($id_rpjmd, $id_tujuan=NULL){
		$this->db->select($this->table_sasaran.".*");
		$this->db->where('id_rpjmd', $id_rpjmd);
		// $this->db->where('id_tujuan', '31');
		if (!empty($id_tujuan)) {
			$this->db->where('id_tujuan', $id_tujuan);
		}
		$this->db->from($this->table_sasaran);
		$this->db->order_by('id_tujuan', 'asc');
		$result = $this->db->get();
		return $result->result();
	}

	function get_kebijakan_sasaran($id_sasaran, $return_result=TRUE){
		$this->db->where('id_sasaran', $id_sasaran);
		$this->db->from($this->table_kebijakan_sasaran);
		$result = $this->db->get();
		if ($return_result) {
			return $result->result();
		}else{
			return $result;
		}
	}

	function get_one_sasaran($id_rpjmd=NULL, $id_tujuan=NULL, $id_sasaran){
		if (!empty($id_rpjmd)) {
			$this->db->where('id_rpjmd', $id_rpjmd);
		}

		if (!empty($id_tujuan)) {
			$this->db->where('id_tujuan', $id_tujuan);
		}

		$this->db->where('id', $id_sasaran);
		$this->db->from($this->table_sasaran);
		$result = $this->db->get();
		return $result->row();
	}

	function get_indikator_sasaran($id, $return_result=TRUE, $satuan=FALSE){
		$this->db->select($this->table_indikator_sasaran.".*");
		$this->db->where('id_sasaran', $id);
		$this->db->from($this->table_indikator_sasaran);

		if ($satuan) {
			$this->db->select("m_lov.nama_value");
			$this->db->select("m_status_indikator.nama_status_indikator");
			$this->db->select("m_kategori_indikator.nama_kategori_indikator");
			$this->db->join("m_lov",$this->table_indikator_sasaran.".satuan_target = m_lov.kode_value AND kode_app='1'","inner");
			$this->db->join("m_status_indikator",$this->table_indikator_sasaran.".status_indikator = m_status_indikator.kode_status_indikator","inner");
			$this->db->join("m_kategori_indikator",$this->table_indikator_sasaran.".kategori_indikator = m_kategori_indikator.kode_kategori_indikator","inner");
		}

		$result = $this->db->get();
		if ($return_result) {
			return $result->result();
		}else{
			return $result;
		}
	}

	function get_indikator_program_per_sasaran($id){
		$query = "SELECT t_rpjmd_indikator_program.* FROM t_rpjmd_indikator_program
							INNER JOIN t_rpjmd_program_ng
							ON t_rpjmd_indikator_program.`id_prog` = t_rpjmd_program_ng.`id`
							WHERE t_rpjmd_program_ng.`id_sasaran` = $id";

		$result = $this->db->query($query);
		return $result;
	}

	function add_sasaran($data){
		$this->db->trans_strict(FALSE);
		$this->db->trans_start();

		$result = $this->db->insert($this->table_sasaran, $data);

		$this->db->trans_complete();
		return $this->db->trans_status();
	}

	function edit_sasaran($data, $id_sasaran){
		$this->db->trans_strict(FALSE);
		$this->db->trans_start();

		$this->db->where('id', $id_sasaran);
		$result = $this->db->update($this->table_sasaran, $data);
		//
		// $this->db->where('id', $id_prog);
		// $result = $this->db->update($this->table_program_ng, array('id_rpjmd' => $data['id_rpjmd'], 'id_tujuan' => $data['id_tujuan'], 'id_sasaran' => $id_sasaran, 'nama_prog' => $nama_prog, 'pagu_rpjmd' => $pagu_rpjmd ));
		//
		// foreach ($indikator as $key => $value) {
		// 	if (!empty($id_indikator[$key])) {
		// 		$this->db->where('id', $id_indikator[$key]);
		// 		$this->db->where('id_sasaran', $id_sasaran);
		// 		$this->db->update($this->table_indikator_sasaran, array('indikator' => $value,
		// 				'satuan_target' => $satuan_target[$key], 'status_indikator' => $status_target[$key], 'kategori_indikator' => $kategori_target[$key],
		// 				'kondisi_awal' => $kondisi_awal[$key], 'target_1' => $target_1[$key], 'target_2' => $target_2[$key], 'target_3' => $target_3[$key] ,
		// 				'target_4' => $target_4[$key], 'target_5' => $target_5[$key], 'kondisi_akhir' => $kondisi_akhir[$key]));
		// 		unset($id_indikator[$key]);
		// 	}else{
		// 		$this->db->insert($this->table_indikator_sasaran, array('id_sasaran' => $id_sasaran, 'indikator' => $value,
		// 				'satuan_target' => $satuan_target[$key], 'status_indikator' => $status_target[$key], 'kategori_indikator' => $kategori_target[$key],
		// 				'kondisi_awal' => $kondisi_awal[$key], 'target_1' => $target_1[$key], 'target_2' => $target_2[$key], 'target_3' => $target_3[$key] ,
		// 				'target_4' => $target_4[$key], 'target_5' => $target_5[$key], 'kondisi_akhir' => $kondisi_akhir[$key]));
		// 	}
		// }
		//
		// if (!empty($id_indikator)) {
		// 	$this->db->where_in('id', $id_indikator);
		// 	$this->db->delete($this->table_indikator_sasaran);
		// }

		$this->db->trans_complete();
		return $this->db->trans_status();
	}

	function delete_sasaran($id){
		$this->db->where('id', $id);
		$result = $this->db->delete($this->table_sasaran);
		return $result;
	}

	function get_all_strategi($id_sasaran){
		$this->db->select($this->table_strategi.".*");
		$this->db->where('id_sasaran', $id_sasaran);
		$this->db->from($this->table_strategi);

		$result = $this->db->get();
		return $result->result();
	}

	function get_one_strategi($id_strategi){
		$this->db->where('id', $id_strategi);
		$this->db->from($this->table_strategi);
		$result = $this->db->get();
		return $result->row();
	}

	function add_strategi($data){
		$this->db->trans_strict(FALSE);
		$this->db->trans_start();

		$result = $this->db->insert($this->table_strategi, $data);

		$this->db->trans_complete();
		return $this->db->trans_status();
	}

	function edit_strategi($data, $id){
		$this->db->where('id', $id);
		$result = $this->db->update($this->table_strategi, $data);

		return $result;
	}

	function delete_strategi($id){
		$this->db->where('id', $id);
		$result = $this->db->delete($this->table_strategi);
		return $result;
	}


	function get_all_kebijakan($id_strategi){
		$this->db->select($this->table_kebijakan.".*");
		$this->db->where('id_strategi', $id_strategi);
		$this->db->from($this->table_kebijakan);

		$result = $this->db->get();
		return $result->result();
	}

	function get_one_kebijakan($id_kebijakan){
		$this->db->where('id', $id_kebijakan);
		$this->db->from($this->table_kebijakan);
		$result = $this->db->get();
		return $result->row();
	}

	function add_kebijakan($data){
		$this->db->trans_strict(FALSE);
		$this->db->trans_start();

		$result = $this->db->insert($this->table_kebijakan, $data);

		$this->db->trans_complete();
		return $this->db->trans_status();
	}

	function edit_kebijakan($data, $id){
		$this->db->where('id', $id);
		$result = $this->db->update($this->table_kebijakan, $data);

		return $result;
	}

	function delete_kebijakan($id){
		$this->db->where('id', $id);
		$result = $this->db->delete($this->table_kebijakan);
		return $result;
	}

	function add_urusan($data, $id_program, $skpd_bidang){
		$this->db->trans_strict(FALSE);
		$this->db->trans_start();

		$result = $this->db->insert($this->table_urusan, $data);
		$id_urusan = $this->db->insert_id();

		foreach ($skpd_bidang as $key => $value) {
			$this->db->insert($this->table_program_skpd, array('id_prog' => $id_program, 'id_skpd' => $value, 'id_urusan' => $id_urusan));
		}

		$this->db->trans_complete();
		return $this->db->trans_status();
	}

	function edit_urusan($data, $id, $id_program, $skpd_bidang){
		$this->db->trans_strict(FALSE);
		$this->db->trans_start();

		$this->db->where('id', $id);
		$result = $this->db->update($this->table_urusan, $data);

		$query = "delete from ".$this->table_program_skpd." where id_urusan = $id";
		$this->db->query($query);

		foreach ($skpd_bidang as $key => $value) {
			$this->db->insert($this->table_program_skpd, array('id_prog' => $id_program, 'id_skpd' => $value, 'id_urusan' => $id));
		}

		$this->db->trans_complete();
		return $this->db->trans_status();
	}

	function delete_urusan($id){
		$this->db->where('id', $id);
		$result = $this->db->delete($this->table_urusan);
		return $result;
	}

	function get_all_program_ng($id_sasaran){
		$query = "SELECT * FROM `t_rpjmd_program_ng` WHERE id_sasaran = $id_sasaran";

		$result = $this->db->query($query);
		return $result->result();
	}

	function get_all_indikator_program_ng($id_program){
		$this->db->select($this->table_indikator_program.".*");
		$this->db->where('id_prog', $id_program);
		$this->db->from($this->table_indikator_program);

		$result = $this->db->get();
		return $result->result();
	}

	function get_all_skpd_bidang($id_urusan){
		// $query = "SELECT `t_rpjmd_program_skpd`.`id_skpd`, id_urusan, id, id_prog, nama_skpd FROM `t_rpjmd_program_skpd`
		// 		INNER JOIN `m_skpd`
		// 		ON `t_rpjmd_program_skpd`.`id_skpd` = `m_skpd`.`id_skpd`
		// 		WHERE id_urusan = $id_urusan";
		$query = "SELECT `t_rpjmd_program_skpd`.`id_skpd`, id_urusan, id, id_prog, nama_skpd FROM `t_rpjmd_program_skpd`
				INNER JOIN `m_skpd`
				ON `t_rpjmd_program_skpd`.`id_skpd` = `m_skpd`.`id_skpd`
				WHERE id_urusan = $id_urusan";

		$result = $this->db->query($query);
		return $result->result();
	}

	function get_all_indikator($id_rpjmd, $id_sasaran, $with_satuan){
		$this->db->select($this->table_indikator.".*");
		$this->db->where('id_rpjmd', $id_rpjmd);
		$this->db->where('id_sasaran', $id_sasaran);
		$this->db->from($this->table_indikator);

		if ($with_satuan) {
			$this->db->select("m_lov.nama_value");
			$this->db->join("m_lov",$this->table_indikator.".satuan_target = m_lov.kode_value AND kode_app='1'","inner");
		}

		$result = $this->db->get();
		return $result->result();
	}

	function get_one_indikator($id_rpjmd=NULL, $id_sasaran=NULL, $id_indikator){
		if (!empty($id_rpjmd)) {
			$this->db->where('id_rpjmd', $id_rpjmd);
		}

		if (!empty($id_sasaran)) {
			$this->db->where('id_sasaran', $id_sasaran);
		}

		$this->db->where('id', $id_indikator);
		$this->db->from($this->table_indikator);
		$result = $this->db->get();
		return $result->row();
	}

	function get_info_tujuan_n_sasaran_n_indikator($id_tujuan=NULL, $id_sasaran=NULL){
		$this->db->select($this->table_tujuan.".tujuan");
		$this->db->from($this->table_tujuan);
		if (!empty($id_tujuan)) {
			$this->db->where('id_tujuan', $id_tujuan);
		}
		if (!empty($id_sasaran)) {
			$this->db->select($this->table_sasaran.".sasaran");
			$this->db->join($this->table_sasaran,$this->table_sasaran.".id_tujuan = ". $this->table_tujuan .".id","inner");
			$this->db->where($this->table_sasaran.'.id', $id_sasaran);
		}

		$result = $this->db->get();
		return $result->row();
	}

	function add_indikator($data){
		$result = $this->db->insert($this->table_indikator, $data);
		return $result;
	}

	function edit_indikator($data, $id_indikator){
		$this->db->where('id', $id_indikator);
		$result = $this->db->update($this->table_indikator, $data);

		return $result;
	}

	function delete_indikator($id){
		$this->db->where('id', $id);
		$result = $this->db->delete($this->table_indikator);
		return $result;
	}

	function get_all_program($id_kebijakan){
		$this->db->select($this->table_program.".id AS id_program_rpjmd");
		$this->db->select("t_renstra_prog_keg.*");
		$this->db->select("Nm_Bidang");
		$this->db->select("nama_skpd");
		$this->db->where($this->table_program.'.id_rpjmd', $id_rpjmd);
		$this->db->where($this->table_program.'.id_sasaran', $id_sasaran);
		$this->db->where($this->table_program.'.id_indikator', $id_indikator);
		$this->db->from($this->table_program);
		$this->db->join('t_renstra_prog_keg',"t_renstra_prog_keg.id = ". $this->table_program .".id_program_renstra AND t_renstra_prog_keg.is_prog_or_keg=1","inner");
		$this->db->join('t_renstra', "t_renstra_prog_keg.id_renstra = t_renstra.id","inner");
		$this->db->join("m_skpd", "t_renstra.id_skpd = m_skpd.id_skpd","inner");
		$this->db->join("m_bidang", "t_renstra_prog_keg.kd_urusan = m_bidang.Kd_Urusan AND t_renstra_prog_keg.kd_bidang = m_bidang.Kd_Bidang","inner");
		$result = $this->db->get();
		return $result->result();
	}

	function get_all_pilih_program($search, $start, $length, $order, $order_arr, $id_rpjmd, $id_sasaran, $id_indikator){
		$query = "SELECT *
							FROM (
								SELECT
									t_renstra_prog_keg.id,
									t_renstra_prog_keg.kd_urusan,
									t_renstra_prog_keg.kd_bidang,
									t_renstra_prog_keg.kd_program,
									nama_prog_or_keg,
									m_skpd.nama_skpd,
									Nm_Bidang,
									t_rpjmd_program.id_rpjmd,
									t_rpjmd_program.id_indikator,
									t_rpjmd_program.id_sasaran
								FROM
									(SELECT * FROM t_renstra_prog_keg WHERE is_prog_or_keg=1 AND (id_status=4 OR id_status=7)) AS t_renstra_prog_keg
								INNER JOIN
									t_renstra ON t_renstra_prog_keg.id_renstra=t_renstra.id
								INNER JOIN
									m_skpd ON m_skpd.id_skpd=t_renstra.id_skpd
								INNER JOIN
									m_bidang ON m_bidang.Kd_Urusan=t_renstra_prog_keg.kd_urusan AND m_bidang.Kd_Bidang=t_renstra_prog_keg.kd_bidang
								LEFT JOIN
									(SELECT * FROM t_rpjmd_program GROUP BY id_rpjmd, id_sasaran, id_program_renstra) AS t_rpjmd_program ON t_renstra_prog_keg.id=t_rpjmd_program.id_program_renstra
							) AS vw1
							WHERE
								(nama_prog_or_keg LIKE ? OR Nm_Bidang LIKE ? OR nama_skpd LIKE ?) AND
								((id_rpjmd IS NULL AND id_sasaran IS NULL AND id_indikator IS NULL))
							ORDER BY ? ?
							LIMIT ".$start.", ".$length.";";
		$data = array("%". $search['value'] ."%", "%". $search['value'] ."%", "%". $search['value'] ."%", $id_rpjmd, $id_sasaran, $id_indikator, $order_arr[$order["column"]], $order["dir"]);
		$result = $this->db->query($query, $data);
		return $result->result();
	}

	function count_all_pilih_program($search, $start, $length, $order, $order_arr, $id_rpjmd, $id_sasaran, $id_indikator){
		$query = "SELECT COUNT(*) AS count
							FROM (
								SELECT
									t_renstra_prog_keg.id,
									t_renstra_prog_keg.kd_urusan,
									t_renstra_prog_keg.kd_bidang,
									t_renstra_prog_keg.kd_program,
									nama_prog_or_keg,
									m_skpd.nama_skpd,
									Nm_Bidang,
									t_rpjmd_program.id_rpjmd,
									t_rpjmd_program.id_indikator,
									t_rpjmd_program.id_sasaran
								FROM
									(SELECT * FROM t_renstra_prog_keg WHERE is_prog_or_keg=1 AND (id_status=4 OR id_status=7)) AS t_renstra_prog_keg
								INNER JOIN
									t_renstra ON t_renstra_prog_keg.id_renstra=t_renstra.id
								INNER JOIN
									m_skpd ON m_skpd.id_skpd=t_renstra.id_skpd
								INNER JOIN
									m_bidang ON m_bidang.Kd_Urusan=t_renstra_prog_keg.kd_urusan AND m_bidang.Kd_Bidang=t_renstra_prog_keg.kd_bidang
								LEFT JOIN
									(SELECT * FROM t_rpjmd_program GROUP BY id_rpjmd, id_sasaran, id_program_renstra) AS t_rpjmd_program ON t_renstra_prog_keg.id=t_rpjmd_program.id_program_renstra
							) AS vw1
							WHERE
								(nama_prog_or_keg LIKE ? OR Nm_Bidang LIKE ? OR nama_skpd LIKE ?) AND
								((id_rpjmd IS NULL AND id_sasaran IS NULL AND id_indikator IS NULL) OR (id_rpjmd=? AND id_sasaran=? AND id_indikator!=?))
							";
		$data = array("%". $search['value'] ."%", "%". $search['value'] ."%", "%". $search['value'] ."%", $id_rpjmd, $id_sasaran, $id_indikator);
		$result = $this->db->query($query, $data);
		$result = $result->row();
		return $result->count;
	}

	function add_program($data, $indikator, $pengukuran, $satuan_target, $status_target, $kategori_target, $kondisi_awal, $target_1, $target_2,	$target_3, $target_4, $target_5, $kondisi_akhir){
		$this->db->trans_strict(FALSE);
		$this->db->trans_start();

		$this->db->insert($this->table_program_ng, $data);
		$id = $this->db->insert_id();

		foreach ($indikator as $key => $value) {
			$this->db->insert($this->table_indikator_program, array('id_prog' => $id, 'indikator' => $value, 'cara_pengukuran' => $pengukuran[$key], 'satuan_target' => $satuan_target[$key], 'status_indikator' => $status_target[$key], 'kategori_indikator' => $kategori_target[$key],
			'kondisi_awal' => $kondisi_awal[$key], 'target_1' => $target_1[$key], 'target_2' => $target_2[$key], 'target_3' => $target_3[$key], 'target_4' => $target_4[$key], 'target_5' => $target_5[$key], 'kondisi_akhir' => $kondisi_akhir[$key]));
		}

		$this->db->trans_complete();
		return $this->db->trans_status();
	}

	function edit_program($data, $id, $id_indikator_program, $indikator, $pengukuran, $satuan_target, $status_target, $kategori_target, $kondisi_awal, $target_1, $target_2, $target_3, $target_4,	$target_5, $kondisi_akhir){

		$this->db->trans_strict(FALSE);
		$this->db->trans_start();

		$this->db->where('id', $id);
		$result = $this->db->update($this->table_program_ng, $data);

		foreach ($indikator as $key => $value) {
			if (!empty($id_indikator_program[$key])) {
				$this->db->where('id', $id_indikator_program[$key]);
				$this->db->where('id_prog', $id);
				$this->db->update($this->table_indikator_program, array('indikator' => $value, 'cara_pengukuran' => $pengukuran[$key], 'satuan_target' => $satuan_target[$key], 'status_indikator' => $status_target[$key], 'kategori_indikator' => $kategori_target[$key],
				'kondisi_awal' => $kondisi_awal[$key], 'target_1' => $target_1[$key], 'target_2' => $target_2[$key], 'target_3' => $target_3[$key], 'target_4' => $target_4[$key], 'target_5' => $target_5[$key], 'kondisi_akhir' => $kondisi_akhir[$key]));
				unset($id_indikator_program[$key]);
			}else{
				$this->db->insert($this->table_indikator_program, array('id_prog' => $id, 'indikator' => $value, 'cara_pengukuran' => $pengukuran[$key], 'satuan_target' => $satuan_target[$key], 'status_indikator' => $status_target[$key], 'kategori_indikator' => $kategori_target[$key], 'kondisi_awal' => $kondisi_awal[$key], 'target_1' => $target_1[$key], 'target_2' => $target_2[$key], 'target_3' => $target_3[$key], 'target_4' => $target_4[$key], 'target_5' => $target_5[$key], 'kondisi_akhir' => $kondisi_akhir[$key]));
			}
		}

		if (!empty($id_indikator_program)) {
			$this->db->where_in('id', $id_indikator_program);
			$this->db->delete($this->table_indikator_program);
		}

		$this->db->trans_complete();
		return $this->db->trans_status();
	}

	function delete_program($id){
		$this->db->where('id', $id);
		$result = $this->db->delete($this->table_program_ng);
		return $result;
	}

	function preview_program_rpjmd($id){
		$query = "SELECT t_renstra_prog_keg.*
					FROM
						t_rpjmd_program
					INNER JOIN
						(SELECT
							t_prog.*,
							m_skpd.nama_skpd,
							m_bidang.Nm_Bidang AS nm_bidang,
							m_lov.nama_value,
							SUM(t_keg.nominal_1) AS nominal_1_pro,
							SUM(t_keg.nominal_2) AS nominal_2_pro,
							SUM(t_keg.nominal_3) AS nominal_3_pro,
							SUM(t_keg.nominal_4) AS nominal_4_pro,
							SUM(t_keg.nominal_5) AS nominal_5_pro
						FROM
							t_renstra_prog_keg AS t_prog
						INNER JOIN
							t_renstra_prog_keg AS t_keg ON t_prog.id=t_keg.parent AND t_keg.is_prog_or_keg=2
						INNER JOIN
							t_renstra ON t_prog.id_renstra=t_renstra.id
						INNER JOIN
							m_skpd ON m_skpd.id_skpd=t_renstra.id_skpd
						INNER JOIN
							m_bidang ON m_bidang.Kd_Urusan=t_prog.kd_urusan AND m_bidang.Kd_Bidang=t_prog.kd_bidang
						INNER JOIN
							m_lov ON t_prog.satuan_target=m_lov.kode_value AND kode_app='1'
						WHERE
							t_prog.is_prog_or_keg=1
						GROUP BY
							t_prog.id
						) AS t_renstra_prog_keg ON t_rpjmd_program.id_program_renstra=t_renstra_prog_keg.id
					WHERE
						t_rpjmd_program.id=?";
		$data = array($id);
		$result = $this->db->query($query, $data);
		return $result->row();
	}

	function get_all_bidang_urusan_4_cetak_final(){
		$query = "SELECT m_urusan.*, m_bidang.* FROM t_rpjmd_program INNER JOIN t_renstra_prog_keg ON t_rpjmd_program.id_program_renstra=t_renstra_prog_keg.id INNER JOIN m_bidang ON (t_renstra_prog_keg.kd_urusan=m_bidang.Kd_Urusan AND t_renstra_prog_keg.kd_bidang=m_bidang.Kd_Bidang) INNER JOIN m_urusan ON t_renstra_prog_keg.kd_urusan=m_urusan.Kd_Urusan GROUP BY t_renstra_prog_keg.kd_urusan, t_renstra_prog_keg.kd_bidang";
		$result = $this->db->query($query);
		return $result->result();
	}

	function get_bidang_urusan_4_cetak_final($urusan, $bidang){
		$query = "SELECT t_renstra_prog_keg.*, m_bidang.*,SUM(t_renstra_prog_keg.nominal_1) AS nominal_1_pro, SUM(t_renstra_prog_keg.nominal_2) AS nominal_2_pro, SUM(t_renstra_prog_keg.nominal_3) AS nominal_3_pro, SUM(t_renstra_prog_keg.nominal_4) AS nominal_4_pro, SUM(t_renstra_prog_keg.nominal_5) AS nominal_5_pro FROM t_rpjmd_program INNER JOIN t_renstra_prog_keg ON t_rpjmd_program.id_program_renstra=t_renstra_prog_keg.parent INNER JOIN m_bidang ON (t_renstra_prog_keg.kd_urusan=m_bidang.Kd_Urusan AND t_renstra_prog_keg.kd_bidang=m_bidang.Kd_Bidang) WHERE t_renstra_prog_keg.is_prog_or_keg=? AND t_renstra_prog_keg.kd_urusan=? AND t_renstra_prog_keg.kd_bidang=? GROUP BY t_renstra_prog_keg.kd_urusan, t_renstra_prog_keg.kd_bidang";
		$data = array(2, $urusan, $bidang);
		$result = $this->db->query($query, $data);
		return $result->result();
	}

	function get_bidang_urusan_skpd_4_cetak_final($urusan, $bidang){
		$query = "SELECT t_renstra_prog_keg.*,m_skpd.*,SUM(t_renstra_prog_keg.nominal_1) AS nominal_1_pro, SUM(t_renstra_prog_keg.nominal_2) AS nominal_2_pro, SUM(t_renstra_prog_keg.nominal_3) AS nominal_3_pro, SUM(t_renstra_prog_keg.nominal_4) AS nominal_4_pro, SUM(t_renstra_prog_keg.nominal_5) AS nominal_5_pro FROM t_rpjmd_program INNER JOIN t_renstra_prog_keg ON t_rpjmd_program.id_program_renstra=t_renstra_prog_keg.parent INNER JOIN t_renstra ON (t_renstra.id=t_renstra_prog_keg.id_renstra AND t_renstra_prog_keg.is_prog_or_keg=?) INNER JOIN m_skpd ON t_renstra.id_skpd=m_skpd.id_skpd WHERE t_renstra_prog_keg.is_prog_or_keg=? AND t_renstra_prog_keg.kd_urusan=? AND t_renstra_prog_keg.kd_bidang=? GROUP BY t_renstra_prog_keg.kd_urusan, t_renstra_prog_keg.kd_bidang, m_skpd.id_skpd";
		$data = array(2, 2, $urusan, $bidang);
		$result = $this->db->query($query, $data);
		return $result->result();
	}

	function get_bidang_urusan_skpd_program_4_cetak_final($urusan, $bidang, $skpd){
		$query = "SELECT vw1.*,m_skpd.*,SUM(t_renstra_prog_keg.nominal_1) AS nominal_1_pro, SUM(t_renstra_prog_keg.nominal_2) AS nominal_2_pro, SUM(t_renstra_prog_keg.nominal_3) AS nominal_3_pro, SUM(t_renstra_prog_keg.nominal_4) AS nominal_4_pro, SUM(t_renstra_prog_keg.nominal_5) AS nominal_5_pro FROM t_rpjmd_program INNER JOIN t_renstra_prog_keg ON t_rpjmd_program.id_program_renstra=t_renstra_prog_keg.parent INNER JOIN t_renstra ON (t_renstra.id=t_renstra_prog_keg.id_renstra AND t_renstra_prog_keg.is_prog_or_keg=?) INNER JOIN m_skpd ON t_renstra.id_skpd=m_skpd.id_skpd INNER JOIN t_renstra_prog_keg AS vw1 ON (vw1.id=t_renstra_prog_keg.parent AND vw1.is_prog_or_keg=?) WHERE t_renstra_prog_keg.is_prog_or_keg=? AND t_renstra_prog_keg.kd_urusan=? AND t_renstra_prog_keg.kd_bidang=? AND t_renstra.id_skpd=? GROUP BY t_renstra_prog_keg.kd_urusan, t_renstra_prog_keg.kd_bidang, m_skpd.id_skpd, t_renstra_prog_keg.kd_program";
		$data = array(2, 1, 2, $urusan, $bidang, $skpd);
		$result = $this->db->query($query, $data);
		return $result->result();
	}

	function get_misi_rpjmd_4_cetak_final(){
		$query = "SELECT * FROM t_rpjmd_misi";
		$result = $this->db->query($query);
		return $result->result();
	}

	function get_tujuan_rpjmd_4_cetak_final($id){
		$query = "SELECT t_rpjmd_tujuan.*, m_skpd.nama_skpd, COUNT(t_rpjmd_tujuan.id) AS span FROM t_rpjmd_tujuan INNER JOIN t_rpjmd_sasaran ON t_rpjmd_tujuan.id=t_rpjmd_sasaran.id_tujuan INNER JOIN t_rpjmd_indikator ON t_rpjmd_sasaran.id=t_rpjmd_indikator.id_sasaran INNER JOIN t_rpjmd_program ON t_rpjmd_indikator.id=t_rpjmd_program.id_indikator AND t_rpjmd_sasaran.id=t_rpjmd_program.id_sasaran INNER JOIN t_renstra_prog_keg ON t_renstra_prog_keg.id=t_rpjmd_program.id_program_renstra AND t_renstra_prog_keg.is_prog_or_keg=1 INNER JOIN t_renstra ON t_renstra_prog_keg.id_renstra=t_renstra.id INNER JOIN m_skpd ON m_skpd.id_skpd=t_renstra.id_skpd WHERE id_misi=? GROUP BY t_rpjmd_tujuan.id";
		$result = $this->db->query($query, $id);
		return $result->result();
	}

	function get_sasaran_rpjmd_4_cetak_final($id){
		$query = "SELECT t_rpjmd_sasaran.*, COUNT(t_rpjmd_sasaran.id) AS span FROM t_rpjmd_sasaran INNER JOIN t_rpjmd_indikator ON t_rpjmd_sasaran.id=t_rpjmd_indikator.id_sasaran INNER JOIN t_rpjmd_program ON t_rpjmd_indikator.id=t_rpjmd_program.id_indikator AND t_rpjmd_sasaran.id=t_rpjmd_program.id_sasaran WHERE id_tujuan=? GROUP BY t_rpjmd_sasaran.id";
		$result = $this->db->query($query, $id);
		return $result->result();
	}

	function get_indikator_rpjmd_4_cetak_final($id){
		$query = "SELECT t_rpjmd_indikator.*, COUNT(t_rpjmd_indikator.id) AS span, m_lov.nama_value FROM t_rpjmd_indikator INNER JOIN t_rpjmd_sasaran ON t_rpjmd_indikator.id_sasaran=t_rpjmd_sasaran.id INNER JOIN t_rpjmd_program ON t_rpjmd_indikator.id=t_rpjmd_program.id_indikator AND t_rpjmd_sasaran.id=t_rpjmd_program.id_sasaran INNER JOIN m_lov ON t_rpjmd_indikator.satuan_target=m_lov.kode_value AND kode_app='1'WHERE t_rpjmd_indikator.id_sasaran=? GROUP BY t_rpjmd_indikator.id";
		$result = $this->db->query($query, $id);
		return $result->result();
	}

	function get_program_rpjmd_4_cetak_final($id_sasaran, $id_indikator){
		$query = "SELECT t_rpjmd_program.id_sasaran, t_rpjmd_program.id_indikator, t_rpjmd_program.id_program_renstra, t_renstra_prog_keg.nama_prog_or_keg, m_bidang.Nm_Bidang AS nm_bidang, m_skpd.nama_skpd FROM t_rpjmd_program INNER JOIN t_renstra_prog_keg ON t_rpjmd_program.id_program_renstra=t_renstra_prog_keg.id INNER JOIN t_renstra ON t_renstra_prog_keg.id_renstra=t_renstra.id INNER JOIN m_skpd ON t_renstra.id_skpd=m_skpd.id_skpd INNER JOIN m_bidang ON (t_renstra_prog_keg.kd_urusan=m_bidang.Kd_Urusan AND t_renstra_prog_keg.kd_bidang=m_bidang.Kd_Bidang) WHERE t_rpjmd_program.id_sasaran=? AND t_rpjmd_program.id_indikator=?";
		$data = array($id_sasaran, $id_indikator);
		$result = $this->db->query($query, $data);
		return $result->result();
	}

	function get_revisi_pengajuan(){
		$query = "SELECT * FROM t_pengajuan_revisi INNER JOIN m_skpd ON t_pengajuan_revisi.id_skpd=m_skpd.id_skpd WHERE status=1";
		$result = $this->db->query($query);
		return $result->result();
	}

	function get_revisi_skpd(){
		$query = "SELECT m_skpd.id_skpd AS id, m_skpd.nama_skpd, visi AS keterangan, SUM(IF(t_renstra_prog_keg.id_status=7, 1, 0)) AS total, COUNT(*) AS total1 FROM t_renstra INNER JOIN t_renstra_prog_keg ON t_renstra_prog_keg.id_renstra=t_renstra.id INNER JOIN m_skpd ON t_renstra.id_skpd=m_skpd.id_skpd GROUP BY t_renstra.id HAVING total=total1";
		$result = $this->db->query($query);
		return $result->result();
	}

	function proses_revisi($skpd_setuju, $skpd_tdk_setuju, $with_pengajuan=TRUE){
		$this->db->trans_strict(FALSE);
		$this->db->trans_start();

		if (!empty($skpd_setuju)) {
			$table_plus="";
			if ($with_pengajuan) {
				$this->db->where_in('id', $skpd_setuju);
				$this->db->update("t_pengajuan_revisi", array('status' => '3'));

				$table_plus=" INNER JOIN t_pengajuan_revisi ON t_renstra.id_skpd=t_pengajuan_revisi.id_skpd";
				$this->db->where_in('t_pengajuan_revisi.id', $skpd_setuju);
			}else{
				$this->db->where_in('t_renstra.id_skpd', $skpd_setuju);
			}

			$this->db->update("t_renstra_prog_keg INNER JOIN t_renstra ON t_renstra_prog_keg.id_renstra=t_renstra.id".$table_plus, array('id_status' => '1'));
		}

		if (!empty($skpd_tdk_setuju) && $with_pengajuan) {
			$this->db->where_in('id', $skpd_tdk_setuju);
			$this->db->update("t_pengajuan_revisi", array('status' => '2'));
		}

		$this->db->trans_complete();
		return $this->db->trans_status();
	}

	function simpan_revisi($id_skpd, $ket){
		$query = "INSERT INTO t_pengajuan_revisi(id_skpd, keterangan, status) VALUES (?, ?, 4)";
		$data = array($id_skpd, $ket);
		$result = $this->db->query($query, $data);

		$result = $this->proses_revisi($id_skpd, NULL, FALSE);
		return $result;
	}

	function get_program_rpjmd_for_me($id_skpd){
		$query = "SELECT *,`t_rpjmd_program_ng`.`id` AS id_nya FROM `t_rpjmd_program_ng`
							INNER JOIN `t_rpjmd_program_skpd`
							ON `t_rpjmd_program_ng`.`id` = `t_rpjmd_program_skpd`.`id_prog`
							WHERE `t_rpjmd_program_skpd`.`id_skpd` = $id_skpd";
		$result = $this->db->query($query);
		return $result->result();
	}

	function get_one_program_rpjmd_for_me($id_program){
		$query = "SELECT * FROM `t_rpjmd_program_ng` WHERE id = $id_program";
		$result = $this->db->query($query);
		return $result->row();
	}

	function get_all_program_rpjmd_for_me($id_sasaran){
		$query = "SELECT * FROM `t_rpjmd_program_ng` WHERE id_sasaran = $id_sasaran";
		$result = $this->db->query($query);
		return $result;
	}

	function get_indikator_program_rpjmd_for_me($id_program){
		$query = "SELECT `id`,`id_prog`,`indikator`,`cara_pengukuran`,`satuan_target` AS satuan_target,`status_indikator` AS status_indikator,
						`kategori_indikator` AS kategori_indikator, `kondisi_awal`,`target_1`,`target_2`,`target_3`,`target_4`,`target_5`,`kondisi_akhir`,
						(SELECT nama_status_indikator FROM m_status_indikator WHERE kode_status_indikator = status_indikator) AS status_nya,
						(SELECT nama_kategori_indikator FROM m_kategori_indikator WHERE kode_kategori_indikator = kategori_indikator) AS kategori_nya
						FROM `t_rpjmd_indikator_program`
						WHERE id_prog = $id_program";
		$result = $this->db->query($query);
		return $result;
	}

	function get_all_sasaran_for_me($id_rpjmd, $id_tujuan){
		$query = "SELECT *
						FROM `t_rpjmd_sasaran`
						WHERE `t_rpjmd_sasaran`.`id_rpjmd` = $id_rpjmd AND `t_rpjmd_sasaran`.`id_tujuan` = $id_tujuan";
		$result = $this->db->query($query);
		return $result->result();
	}

	function get_all_urusan($id_program){
		$query = "SELECT t_rpjmd_urusan.id, id_rpjmd, id_tujuan, id_sasaran, id_prog, t_rpjmd_urusan.kd_urusan,
				t_rpjmd_urusan.kd_bidang, nama_bidang_urusan , Nm_Urusan, Nm_Bidang
				FROM `t_rpjmd_urusan`
				INNER JOIN m_urusan ON t_rpjmd_urusan.`kd_urusan` = m_urusan.`Kd_Urusan`
				INNER JOIN m_bidang ON t_rpjmd_urusan.`kd_bidang` = m_bidang.`Kd_Bidang` AND t_rpjmd_urusan.`kd_urusan` = m_bidang.`Kd_Urusan`
				WHERE id_prog = $id_program";

		$result = $this->db->query($query);
		return $result->result();
	}

	function get_one_urusan($id_urusan){
		$this->db->where('id', $id_urusan);
		$this->db->from($this->table_urusan);
		$result = $this->db->get();
		return $result->row();
	}

	function skpd_bidang($id_urusan, $id_bidang){
		$query = "SELECT m_skpd.* FROM m_skpd_urusan
				INNER JOIN m_skpd 
				ON m_skpd_urusan.id_skpd = m_skpd.id_skpd
				WHERE m_skpd_urusan.kd_urusan = $id_urusan AND m_skpd_urusan.kd_bidang = $id_bidang
				ORDER BY nama_skpd";
		$result = $this->db->query($query);
		return $result->result();
	}

	function get_sisa_pagu_rpjmd($id_program){
		$query = "SELECT SUM(a.nominal_1+a.nominal_2+a.nominal_3+a.nominal_4+a.nominal_5) AS sisa
		FROM t_renstra_prog_keg a
		INNER JOIN t_renstra_prog_keg b
		ON a.parent = b.id
		WHERE b.id_prog_rpjmd = $id_program";
		$result = $this->db->query($query);
		return $result->row();
	}
	function get_visi_rpjmd_cetak(){
		$query = "SELECT * FROM t_rpjmd";
		$result = $this->db->query($query);
		return $result->result();
	}
	function get_misi_rpjmd_cetak($id){
		$query = "SELECT * FROM t_rpjmd_misi where id_rpjmd = '$id'";
		$result = $this->db->query($query);
		return $result->result();
	}

	function get_tujuan_rpjmd_cetak($id){
		$query = "SELECT * FROM t_rpjmd_tujuan where id_misi = '$id'";
		$result = $this->db->query($query);
		return $result->result();
	}
	function get_indikator_tujuan_rpjmd_cetak1($id){
		$query = "SELECT * FROM t_rpjmd_indikator_tujuan where id_tujuan = '$id'";
		$result = $this->db->query($query);
		return $result->result();
	}
	function get_count_indikator_tujuan_bymisi($id){
		$query = "SELECT indikator FROM `t_rpjmd_indikator_tujuan` a
						INNER JOIN `t_rpjmd_tujuan`  b
						ON a.`id_tujuan`=b.`id`
						INNER JOIN `t_rpjmd_misi` c
						ON b.`id_misi`=c.`id`
						WHERE c.id='$id'";
		$result = $this->db->query($query);
		return $result->result();
	}

	function get_count_indikator_sasaran_bytujuan($id){
		$query = "SELECT indikator FROM `t_rpjmd_indikator_sasaran` INNER JOIN `t_rpjmd_sasaran`
					ON `t_rpjmd_indikator_sasaran`.`id_sasaran` = t_rpjmd_sasaran.`id` INNER JOIN `t_rpjmd_tujuan`
					ON t_rpjmd_sasaran.`id_tujuan` = t_rpjmd_tujuan.id
					where id_tujuan = '$id' ";
		$result = $this->db->query($query);
		return $result->result();
	}

	function get_count_indikator_program_bytujuan($id){
		$query = "SELECT indikator FROM `t_rpjmd_indikator_program` INNER JOIN `t_rpjmd_program_ng`
					ON `t_rpjmd_indikator_program`.`id_prog` = t_rpjmd_program_ng.`id` INNER JOIN `t_rpjmd_tujuan`
					ON t_rpjmd_program_ng.`id_tujuan` = t_rpjmd_tujuan.id
					where id_tujuan = '$id' ";
		$result = $this->db->query($query);
		return $result->result();
	}



	function get_tujuan_rpjmd_allcetak(){
		$query = "SELECT * FROM t_rpjmd_tujuan order by id asc  ";
		$result = $this->db->query($query);
		return $result->result();
	}

	function get_sasaran_tujuan_cetak_($id){
		$query = "SELECT * FROM t_rpjmd_sasaran where id_tujuan = '$id'";
		$result = $this->db->query($query);
		return $result->result();
	}
	function get_program_sasaran_cetak($id){
		$query = "SELECT * FROM t_rpjmd_program_ng where id_tujuan = '$id'";
		$result = $this->db->query($query);
		return $result->result();
	}
	function get_indikator_sasaran_cetak($id){
		$query = "SELECT * FROM t_rpjmd_indikator_sasaran where id_sasaran = '$id'";
		$result = $this->db->query($query);
		return $result->result();
	}

	function get_program_sasaran_cetak_bysasaran($id){
		$query = "SELECT * FROM t_rpjmd_program_ng where id_sasaran = '$id'";
		$result = $this->db->query($query);
		return $result->result();
	}

	function get_indikator_program_cetak_by_sasaran($id){
		$query = "SELECT * FROM t_rpjmd_indikator_program
					INNER join t_rpjmd_program_ng
					ON t_rpjmd_indikator_program.id_prog = t_rpjmd_program_ng.id
		 where t_rpjmd_program_ng.id_sasaran = '$id'";
		$result = $this->db->query($query);
		return $result->result();
	}

	function get_indikator_program_cetak($id){
		$query = "SELECT * FROM t_rpjmd_indikator_program where id_prog = '$id'";
		$result = $this->db->query($query);
		return $result->result();
	}

	function get_program_sasaran_allcetak(){
		$query = "SELECT * FROM t_rpjmd_program_ng order by id asc";
		$result = $this->db->query($query);
		return $result->result();
	}


	function get_strategi_sasaran_cetak($id){
		$query = "SELECT * FROM t_rpjmd_strategi where id_sasaran = '$id'";
		$result = $this->db->query($query);
		return $result->result();
	}

	function get_kebijakan_strategi_cetak($id){
		$query = "SELECT * FROM t_rpjmd_kebijakan where id_strategi = '$id'";
		$result = $this->db->query($query);
		return $result->result();
	}

	function get_total_kebijakan_strategi_cetak($id){
		$query = "SELECT count(id) as jumlah FROM t_rpjmd_kebijakan where id_sasaran = '$id'";
		$result = $this->db->query($query);
		return $result->row();
	}
	function get_program_sasaran_allcetak_renstra(){
		$query = "SELECT * FROM t_renstra_prog_keg where is_prog_or_keg='1' and id<125 order by id asc";
		$result = $this->db->query($query);
		return $result->result();
	}
	function get_indikator_program_cetak_renstra($id){
		$query = "SELECT * FROM t_renstra_indikator_prog_keg where id_prog_keg = '$id'";
		$result = $this->db->query($query);
		return $result->result();
	}
















	function get_start_program_daerah_renstra(){
		$query = "SELECT t_rpjmd_program_ng.`nama_prog`, t_renstra_prog_keg.`kd_urusan`,t_renstra_prog_keg.`kd_bidang`,t_renstra_prog_keg.`kd_program`,`nama_prog_or_keg`,`indikator` FROM t_renstra_prog_keg INNER JOIN `t_renstra_indikator_prog_keg`
					ON t_renstra_prog_keg.id = t_renstra_indikator_prog_keg.`id_prog_keg` INNER JOIN `t_rpjmd_program_ng`
					ON t_renstra_prog_keg.`id_prog_rpjmd` = `t_rpjmd_program_ng`.`id`
					";
		$result = $this->db->query($query);
		return $result->result();
	}
	function get_prog_for_program_daerah_renstra(){
		$query = "SELECT id,kd_program, nama_prog_or_keg,id_prog_rpjmd FROM `t_renstra_prog_keg` WHERE is_prog_or_keg = '1'";
		$result = $this->db->query($query);
		return $result->result();
	}
	function get_indikator_for_program_daerah_renstra($id_prog_keg){
		$query = "SELECT  `id_prog_keg`,`indikator` FROM `t_renstra_indikator_prog_keg` WHERE id_prog_keg='$id_prog_keg' ";
		$result = $this->db->query($query);
		return $result->result();
	}
	function get_programdaerah_for_program_daerah_renstra($id_rpjmd){
		$query = "SELECT id,nama_prog FROM `t_rpjmd_program_ng` WHERE id='$id_rpjmd' ";
		$result = $this->db->query($query);
		return $result->result();
	}


	function get_program_daerah_all(){
		$query = "SELECT id,nama_prog FROM `t_rpjmd_program_ng` ";
		$result = $this->db->query($query);
		return $result->result();
	}
	function get_indikator_program_skpd_by_program_daerah($id_prog_rpjmd){
		$query = "SELECT `id_prog_keg`,indikator FROM t_renstra_indikator_prog_keg a
					INNER JOIN t_renstra_prog_keg b
					ON a.id_prog_keg = b.id
					 WHERE b.`id_prog_rpjmd` = '$id_prog_rpjmd'";
		$result = $this->db->query($query);
		return $result->result();
	}
	function get_program_skpd_by_program_daerah($id_prog_rpjmd){
		$query = "SELECT * FROM `t_renstra_prog_keg` where id_prog_rpjmd ='$id_prog_rpjmd'  ";
		$result = $this->db->query($query);
		return $result->result();
	}
	function get_indikator_program_skpd_by_program_skpd($id_prog){
		$query = "SELECT * FROM `t_renstra_indikator_prog_keg` where id_prog_keg ='$id_prog'  ";
		$result = $this->db->query($query);

		return $result->result();
	}
	function get_skpd_penanggung_by_program_daerah($id_prog_rpjmd){
		$query = "select a.*,b.nama_skpd from t_rpjmd_program_skpd a inner join m_skpd b on a.id_skpd = b.id_skpd
							where a.id_prog ='$id_prog_rpjmd'  ";
		$result = $this->db->query($query);

		return $result->result();
	}


		function get_kebijakan_by_program_daerah($id_prog_rpjmd){
			$query = "select a.*,b.id_program from t_rpjmd_kebijakan a
			inner join t_rpjmd_strategi b
			on a.id_strategi = b.id
			where b.id_program= '$id_prog_rpjmd'";
			$result = $this->db->query($query);
			return $result->result();
		}
		function get_strategi_by_program_daerah($id_prog_rpjmd){
			$query = "select * from t_rpjmd_strategi
			where id_program= '$id_prog_rpjmd'";
			$result = $this->db->query($query);
			return $result->result();
		}
		function get_kebijakan_by_strategi($id_strategi){
			$query = "select * from t_rpjmd_kebijakan
			where id_strategi =  '$id_strategi'";
			$result = $this->db->query($query);
			return $result->result();
		}
		function get_kebijakan_by_tujuan($id){
			$query = "SELECT * FROM t_rpjmd_kebijakan where id_tujuan = '$id'";
			$result = $this->db->query($query);
			return $result->result();
		}
		function get_kebijakan_by_sasaran($id){
			$query = "SELECT * FROM t_rpjmd_kebijakan where id_sasaran = '$id'";
			$result = $this->db->query($query);
			return $result->result();
		}

		function get_indikator_bytujuan($id){
				$query = "SELECT * FROM t_rpjmd_indikator_tujuan where id_tujuan='$id' ORDER BY id ASC";
				$result = $this->db->query($query);
				return $result->result();
			}
	function get_urusan_all(){
		$query = "SELECT kd_urusan AS kd, (SELECT nm_urusan FROM m_urusan WHERE m_urusan.kd_urusan = t_renstra_prog_keg.kd_urusan) AS nama,
		SUM(nominal_1) AS nom1, SUM(nominal_2) AS nom2, SUM(nominal_3) AS nom3, SUM(nominal_4) AS nom4, SUM(nominal_5) AS nom5
		FROM t_renstra_prog_keg
		WHERE id_skpd > 0 
		GROUP BY kd_urusan, nama";

		return $this->db->query($query)->result();
	}

	function get_all_skpd(){
		$query = "SELECT * FROM m_skpd  ORDER BY kode_skpd ASC";

		return $this->db->query($query)->result();
	}

	function get_bidang_urusan($kd_urusan){
		$query = "SELECT kd_bidang AS kd,
		(SELECT nm_bidang FROM m_bidang WHERE m_bidang.kd_urusan = t_renstra_prog_keg.kd_urusan AND m_bidang.kd_bidang = t_renstra_prog_keg.kd_bidang) AS nama,
		SUM(nominal_1) AS nom1, SUM(nominal_2) AS nom2, SUM(nominal_3) AS nom3, SUM(nominal_4) AS nom4, SUM(nominal_5) AS nom5
		FROM t_renstra_prog_keg
		WHERE kd_urusan = '$kd_urusan' AND id_skpd > 0
		GROUP BY kd_urusan, kd_bidang";

		return $this->db->query($query)->result();
	}

	function get_skpd_bidang_urusan($kd_urusan, $kd_bidang){
		$query = "SELECT id_skpd AS kd,
		(SELECT nama_skpd FROM m_skpd WHERE m_skpd.id_skpd = t_renstra_prog_keg.id_skpd) AS nama, (SELECT kode_skpd FROM m_skpd WHERE m_skpd.id_skpd = t_renstra_prog_keg.id_skpd) AS kode_skpd,
		SUM(nominal_1) AS nom1, SUM(nominal_2) AS nom2, SUM(nominal_3) AS nom3, SUM(nominal_4) AS nom4, SUM(nominal_5) AS nom5
		FROM t_renstra_prog_keg
		WHERE kd_urusan = '$kd_urusan' AND kd_bidang = '$kd_bidang' AND id_skpd > 0
		GROUP BY id_skpd, nama
		ORDER BY kode_skpd";

		return $this->db->query($query)->result();
	}

	function get_program_skpd($kd_urusan, $kd_bidang, $id_skpd){
		$query = "SELECT t_renstra_prog_keg.*, crot.nom1, crot.nom2, crot.nom3, crot.nom4, crot.nom5 FROM t_renstra_prog_keg INNER JOIN
		(SELECT id, parent, SUM(nominal_1) AS nom1, SUM(nominal_2) AS nom2, SUM(nominal_3) AS nom3, 
		SUM(nominal_4) AS nom4, SUM(nominal_5) AS nom5 FROM t_renstra_prog_keg 
		WHERE id_skpd = '$id_skpd' AND kd_urusan = '$kd_urusan' AND 
		kd_bidang = '$kd_bidang' AND is_prog_or_keg = 2 GROUP BY kd_urusan, kd_bidang, kd_program) AS crot ON
		t_renstra_prog_keg.id = crot.parent
		WHERE id_skpd = '$id_skpd' AND kd_urusan = '$kd_urusan' AND 
		kd_bidang = '$kd_bidang' AND is_prog_or_keg = 1 GROUP BY kd_urusan, kd_bidang, kd_program";

		return $this->db->query($query)->result();
	}

	function get_indikator_program_skpd($id_program){
		$query = "SELECT * FROM t_renstra_indikator_prog_keg
		WHERE id_prog_keg = '$id_program'";

		return $this->db->query($query)->result();
	}

	function get_sum_urusan_per_skpd($id_skpd, $group=FALSE){
		$where = "";
		if ($group) {
			$where = " GROUP BY kd_urusan, kd_bidang";
		}

		// $query = "SELECT t_renstra_prog_keg.kd_urusan, t_renstra_prog_keg.kd_bidang, 
		// SUM(crot.nom1) AS crots1, SUM(crot.nom2) AS crots2, SUM(crot.nom3) AS crots3, SUM(crot.nom4) AS crots4, SUM(crot.nom5) AS crots5,  
		// (SELECT nm_urusan FROM m_urusan WHERE kd_urusan = t_renstra_prog_keg.`kd_urusan`) AS urusan,
		// (SELECT nm_bidang FROM m_bidang WHERE kd_urusan = t_renstra_prog_keg.`kd_urusan` AND kd_bidang = t_renstra_prog_keg.`kd_bidang`) 
		// AS bidang
		// FROM t_renstra_prog_keg INNER JOIN
		// (SELECT id, parent, SUM(nominal_1) AS nom1, SUM(nominal_2) AS nom2, SUM(nominal_3) AS nom3, 
		// SUM(nominal_4) AS nom4, SUM(nominal_5) AS nom5 FROM t_renstra_prog_keg 
		// WHERE id_skpd = '$id_skpd'  AND is_prog_or_keg = 2 GROUP BY kd_urusan, kd_bidang, kd_program) AS crot ON
		// t_renstra_prog_keg.id = crot.parent
		// WHERE id_skpd = '$id_skpd' AND is_prog_or_keg = 1 ".$where;

		$query = "SELECT crot.kd_urusan, crot.kd_bidang, 
		SUM(crot.nominal_1) AS crots1, SUM(crot.nominal_2) AS crots2, SUM(crot.nominal_3) AS crots3, SUM(crot.nominal_4) AS crots4, SUM(crot.nominal_5) AS crots5,  
		(SELECT nm_urusan FROM m_urusan WHERE kd_urusan = crot.`kd_urusan`) AS urusan,
		(SELECT nm_bidang FROM m_bidang WHERE kd_urusan = crot.`kd_urusan` AND kd_bidang = crot.`kd_bidang`) 
		AS bidang
		FROM t_renstra_prog_keg crot
		WHERE id_skpd = '$id_skpd' ".$where;

		return $this->db->query($query)->result();
	}

	function get_all_program_ng_row($id_sasaran){
		$query = "SELECT * FROM `t_rpjmd_program_ng` WHERE id_sasaran = $id_sasaran";

		$result = $this->db->query($query);
		return $result->row();
	}


	function get_program_skpd_from_renstra($id_prog_rpjmd){
		$query = "SELECT distinct t_renstra_prog_keg.nama_prog_or_keg FROM `t_renstra_prog_keg` 
		where id_prog_rpjmd ='$id_prog_rpjmd' ";
		$result = $this->db->query($query);
		return $result->result();
	}


}
?>
