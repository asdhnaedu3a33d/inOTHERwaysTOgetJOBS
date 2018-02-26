<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_cik extends CI_Model
{
	var $table_cik = 't_cik';
	var $primary_cik = 'id_cik';
	//var $table_indikator_program ='tx_dpa_indikator_prog_keg';

	var $table = 'tx_cik';
	var $table_urusan = 'm_urusan';
	var $table_bidang = 'm_bidang';
	var $table_program = 'm_program';
	var $table_kegiatan = 'm_kegiatan';
	var $primary_rka = 'id';

	var $table_program_kegiatan = 'tx_cik_prog_keg';
	var $table_indikator_program = 'tx_cik_indikator_prog_keg';
	var $table_cik_upload = 'tx_cik_upload';
	var $is_program = 1;
	var $is_kegiatan = 2;

	var $id_status_baru = "1";
	var $id_status_send = "2";
	var $id_status_revisi = "3";
	var $id_status_approved = "4";


	function get_cik($id_skpd,$ta)
    {
    	$sql="
			SELECT * FROM ".$this->table."
			WHERE id_skpd = ?
			AND tahun = ?
		";

		$query = $this->db->query($sql, array($id_skpd,$ta));

		if($query) {
				if($query->num_rows() > 0) {
					return $query->row();
				}
			}

			return NULL;
    }

	function count_jendela_kontrol($id_skpd,$ta){
		if($this->session->userdata("id_skpd") > 100){
			$id_skpd = $this->session->userdata("id_skpd");
			$search = "AND tx_cik_prog_keg.id_skpd in (SELECT id_skpd FROM m_asisten_sekda WHERE id_asisten = '$id_skpd')";
		}else {
			$kode_unit = $this->session->userdata("id_skpd");
			if ($id_skpd == $kode_unit) {
				$search = "AND tx_cik_prog_keg.id_skpd in (SELECT id_skpd FROM m_skpd WHERE kode_unit = '$id_skpd')";
			}else {
				$search = "AND (tx_cik_prog_keg.id_skpd = '$id_skpd' OR tx_cik_prog_keg.id_skpd = '$kode_unit')";
			}
		}
		$query = "SELECT
						SUM(IF(tx_cik_prog_keg.id_status=?, 1, 0)) as baru,
						SUM(IF(tx_cik_prog_keg.id_status>=?, 1, 0)) as kirim,
						SUM(IF(tx_cik_prog_keg.id_status>?, 1, 0)) as proses,
						SUM(IF(tx_cik_prog_keg.id_status=?, 1, 0)) as revisi,
						SUM(IF(tx_cik_prog_keg.id_status>=?, 1, 0)) as veri
					FROM
						tx_cik_prog_keg
					WHERE
						tahun = ? ".$search." AND 
						((tx_cik_prog_keg.is_prog_or_keg =2 and rencana >0) or
							id in (select parent from tx_cik_prog_keg where id_skpd = '$id_skpd' and tahun ='$ta' and rencana>0))";
		$data = array(
					$this->id_status_baru,
					$this->id_status_send,
					$this->id_status_send,
					$this->id_status_revisi,
					$this->id_status_approved,
					$ta, $this->is_kegiatan);
		$result = $this->db->query($query, $data);
		return $result->row();
	}

	function get_all_program($id_skpd,$ta){
		if ($this->session->userdata("id_skpd") > 100) {
			$id_skpd = $this->session->userdata("id_skpd");
			$query = "SELECT * FROM (`$this->table_program_kegiatan`)
			WHERE `id_skpd` in (SELECT id_skpd FROM m_asisten_sekda WHERE id_asisten = '$id_skpd')
			AND id in (select parent from tx_cik_prog_keg where id_skpd='$id_skpd' and rencana>0 and tahun = '$ta')
			AND `tahun` = '$ta' AND `is_prog_or_keg` = $this->is_program  
			ORDER BY `kd_urusan` asc, `kd_bidang` asc, `kd_program` asc";

			$result = $this->db->query($query);
		}else {
			// $id_skpd = $this->m_skpd->get_kode_unit($id_skpd);
			$query = "SELECT * FROM (`$this->table_program_kegiatan`)
			WHERE `id_skpd` = '$id_skpd'
			AND `tahun` = '$ta' AND `is_prog_or_keg` = $this->is_program 
			AND id in (select parent from tx_cik_prog_keg where id_skpd='$id_skpd' and rencana>0 and tahun = '$ta')
			ORDER BY `kd_urusan` asc, `kd_bidang` asc, `kd_program` asc";

			$result = $this->db->query($query);
			// $cek = $this->m_skpd->get_kode_unit($id_skpd);
			// if ($cek == $id_skpd) {
			// 	$query = "SELECT * FROM (`$this->table_program_kegiatan`)
			// 	WHERE `id_skpd` in (SELECT id_skpd FROM m_skpd WHERE kode_unit = '$id_skpd')
			// 	AND `tahun` = '$ta' AND `is_prog_or_keg` = $this->is_program
			// 	ORDER BY `kd_urusan` asc, `kd_bidang` asc, `kd_program` asc";
			//
			// 	$result = $this->db->query($query);
			// }else {
			// 	$this->db->select($this->table_program_kegiatan.".*");
			// 	$this->db->where('id_skpd', $id_skpd);
			// 	$this->db->where('tahun', $ta);
			// 	$this->db->where('is_prog_or_keg', $this->is_program);
			// 	$this->db->from($this->table_program_kegiatan);
			// 	$this->db->order_by('kd_urusan', 'asc');
			// 	$this->db->order_by('kd_bidang', 'asc');
			// 	$this->db->order_by('kd_program', 'asc');
			//
			// 	$result = $this->db->get();
			// }
		}
		return $result->result();
	}

	function insert_cik($id_skpd, $ta){
		$created_date = date("Y-m-d H:i:s");
		$created_by = $this->session->userdata('username');
		$this->db->set('id_skpd', $id_skpd);
		$this->db->set('tahun', $ta);
		$this->db->set('created_date', $created_date);
		$this->db->set('created_by', $created_by);
		$this->db->insert('tx_cik');
		return $this->db->insert_id();
	}

	function import_from_dpa($id_skpd, $ta){
		$this->db->trans_strict(FALSE);
		$this->db->trans_start();

		# For program #
		$query="SELECT
					$ta AS tahun,
					tx_dpa_prog_keg.id AS id_dpa,
					is_prog_or_keg,
					kd_urusan,
					kd_bidang,
					kd_program,
					kd_kegiatan,
					nama_prog_or_keg,
					tx_dpa_prog_keg.id_skpd,
					nominal_1+nominal_2+nominal_3+nominal_4+nominal_5+nominal_6+nominal_7+nominal_8+nominal_9+nominal_10+nominal_11+nominal_12 as rencana
				FROM tx_dpa_prog_keg WHERE tx_dpa_prog_keg.is_prog_or_keg=1 AND tahun=$ta AND tx_dpa_prog_keg.id_skpd in (SELECT id_skpd FROM m_skpd WHERE kode_unit = ?)";
		$result = $this->db->query($query, $id_skpd);
		$cik_baru = $result->result_array();

		foreach ($cik_baru as $row) {
			$this->db->insert("tx_cik_prog_keg", $row);
			$new_id = $this->db->insert_id();

			$query = "INSERT INTO tx_cik_indikator_prog_keg(id_prog_keg, indikator, satuan_target, status_indikator, kategori_indikator, target, id_indikator_dpa) SELECT ?, indikator,
			          satuan_target, status_indikator, kategori_indikator, target, tx_dpa_indikator_prog_keg.id FROM tx_dpa_indikator_prog_keg WHERE id_prog_keg=?";
			$result = $this->db->query($query, array($new_id, $row['id_dpa']));

			# For kegiatan #
			$query="SELECT
					$ta AS tahun,
					tx_dpa_prog_keg.id AS id_dpa,
					is_prog_or_keg,
					kd_urusan,
					kd_bidang,
					kd_program,
					kd_kegiatan,
					nama_prog_or_keg,
					tx_dpa_prog_keg.id_skpd,
					nominal_1+nominal_2+nominal_3+nominal_4+nominal_5+nominal_6+nominal_7+nominal_8+nominal_9+nominal_10+nominal_11+nominal_12 as rencana,
					? AS parent
				FROM tx_dpa_prog_keg WHERE tx_dpa_prog_keg.is_prog_or_keg=2 AND tahun=$ta AND tx_dpa_prog_keg.parent=?
				AND tx_dpa_prog_keg.id_skpd in (SELECT id_skpd FROM m_skpd WHERE kode_unit = ?)";
			$result = $this->db->query($query, array($new_id, $row['id_dpa'], $id_skpd));
			$kegiatan_dpa_baru = $result->result_array();
			
			foreach ($kegiatan_dpa_baru as $row1) {
				$this->db->insert("tx_cik_prog_keg", $row1);
				$new_id = $this->db->insert_id();

				$query = "INSERT INTO tx_cik_indikator_prog_keg(id_prog_keg, indikator, satuan_target, status_indikator, kategori_indikator, target, id_indikator_dpa) SELECT ?, indikator,
				          satuan_target, status_indikator, kategori_indikator, target, tx_dpa_indikator_prog_keg.id FROM tx_dpa_indikator_prog_keg WHERE id_prog_keg=?";
				$result = $this->db->query($query, array($new_id, $row1['id_dpa']));
			}
		}

		$this->db->trans_complete();
		return $this->db->trans_status();
	}

	function get_indikator_prog_keg($id, $return_result=TRUE, $satuan=FALSE){
		$this->db->select($this->table_indikator_program.".*");
		$this->db->where('id_prog_keg', $id);
		$this->db->from($this->table_indikator_program);

		if ($satuan) {
			$this->db->select("m_lov.nama_value");
			$this->db->join("m_lov",$this->table_indikator_program.".satuan_target = m_lov.kode_value AND kode_app='1'","inner");
		}

		$result = $this->db->get();
		if ($return_result) {
			return $result->result();
		}else{
			return $result;
		}
	}

	function get_all_kegiatan($id, $id_skpd, $ta){
		if ($this->session->userdata("id_skpd") > 100) {
			$id_skpd = $this->session->userdata("id_skpd");
			$query = "SELECT * FROM (`$this->table_program_kegiatan`)
			WHERE `id_skpd` in (SELECT id_skpd FROM m_asisten_sekda WHERE id_asisten = '$id_skpd')
			AND `tahun` = '$ta' AND parent = $id
			AND `is_prog_or_keg` = $this->is_kegiatan AND rencana > 0
			ORDER BY `kd_urusan` asc, `kd_bidang` asc, `kd_program` asc, `kd_kegiatan` asc";

			$result = $this->db->query($query);
		}else {
			$cek = $this->m_skpd->get_kode_unit($id_skpd);
			if ($cek == $id_skpd) {
				$query = "SELECT * FROM (`$this->table_program_kegiatan`)
				WHERE `id_skpd` in (SELECT id_skpd FROM m_skpd WHERE kode_unit = '$id_skpd')
				AND `tahun` = '$ta' AND parent = $id
				AND `is_prog_or_keg` = $this->is_kegiatan AND rencana > 0
				ORDER BY `kd_urusan` asc, `kd_bidang` asc, `kd_program` asc, `kd_kegiatan` asc";

				$result = $this->db->query($query);
			}else {
				$this->db->select($this->table_program_kegiatan.".*");
				$this->db->where('id_skpd', $id_skpd);
				$this->db->where('tahun', $ta);
				$this->db->where('parent', $id);
				$this->db->where('is_prog_or_keg', $this->is_kegiatan);
				$this->db->from($this->table_program_kegiatan);
				$this->db->order_by('kd_urusan','asc');
				$this->db->order_by('kd_bidang','asc');
				$this->db->order_by('kd_program','asc');
				$this->db->order_by('kd_kegiatan','asc');

				$result = $this->db->get();
			}
		}
		return $result->result();
	}

	function get_one_kegiatan($id_program=NULL, $id, $detail=FALSE){
		if (!empty($id_program)) {
			$this->db->where('parent', $id_program);
		}

		if ($detail) {
			$this->db->select($this->table_program_kegiatan.".*");
			$this->db->select("nama_skpd");

			$this->db->join("m_skpd", $this->table_program_kegiatan.".id_skpd = m_skpd.id_skpd","inner");

			$this->db->select("m_urusan.Nm_Urusan");
			$this->db->select("m_bidang.Nm_Bidang");
			$this->db->select("m_program.Ket_Program");
			$this->db->join("m_urusan",$this->table_program_kegiatan.".kd_urusan = m_urusan.Kd_Urusan","inner");
			$this->db->join("m_bidang",$this->table_program_kegiatan.".kd_urusan = m_bidang.Kd_Urusan AND ".$this->table_program_kegiatan.".kd_bidang = m_bidang.Kd_Bidang","inner");
			$this->db->join("m_program",$this->table_program_kegiatan.".kd_urusan = m_program.Kd_Urusan AND ".$this->table_program_kegiatan.".kd_bidang = m_program.Kd_Bidang AND ".$this->table_program_kegiatan.".kd_program = m_program.Kd_Prog","inner");
		}

		$this->db->where($this->table_program_kegiatan.'.id', $id);
		$this->db->from($this->table_program_kegiatan);
		$result = $this->db->get();
		return $result->row();
	}

	function get_one_program($id=NULL, $detail=FALSE){
		if (!empty($id)) {
			$this->db->where($this->table_program_kegiatan.'.id', $id);
		}

		if ($detail) {
			$this->db->select($this->table_program_kegiatan.".*");
			$this->db->select("nama_skpd");

			$this->db->join($this->table, $this->table_program_kegiatan.".id = ".$this->table.".id","inner");
			$this->db->join("m_skpd", $this->table.".id_skpd = m_skpd.id_skpd","inner");

			$this->db->select("m_urusan.Nm_Urusan");
			$this->db->select("m_bidang.Nm_Bidang");
			$this->db->select("m_program.Ket_Program");
			$this->db->join("m_urusan",$this->table_program_kegiatan.".kd_urusan = m_urusan.Kd_Urusan","inner");
			$this->db->join("m_bidang",$this->table_program_kegiatan.".kd_urusan = m_bidang.Kd_Urusan AND ".$this->table_program_kegiatan.".kd_bidang = m_bidang.Kd_Bidang","inner");
			$this->db->join("m_program",$this->table_program_kegiatan.".kd_urusan = m_program.Kd_Urusan AND ".$this->table_program_kegiatan.".kd_bidang = m_program.Kd_Bidang AND ".$this->table_program_kegiatan.".kd_program = m_program.Kd_Prog","inner");
		}

		$this->db->where($this->table_program_kegiatan.'.id', $id);
		$this->db->from($this->table_program_kegiatan);
		$result = $this->db->get();
		return $result->row();
	}

	function add_program_skpd($data, $indikator, $satuan_target, $target){
		$this->db->trans_strict(FALSE);
		$this->db->trans_start();

		$add = array('is_prog_or_keg'=> $this->is_program);
		$data = $this->global_function->add_array($data, $add);

		$this->db->insert($this->table_program_kegiatan, $data);

		$id = $this->db->insert_id();
		foreach ($indikator as $key => $value) {
			$this->db->insert($this->table_indikator_program, array('id_prog_keg' => $id, 'indikator' => $value,
			'satuan_target' => $satuan_target[$key], 'target' => $target[$key]));
		}

		$this->db->trans_complete();
		return $this->db->trans_status();
	}

	function edit_program_skpd($data, $id_program, $indikator, $id_indikator_program, $satuan_target,  $target){
		$this->db->trans_strict(FALSE);
		$this->db->trans_start();

		$add = array('is_prog_or_keg'=> $this->is_program);
		$data = $this->global_function->add_array($data, $add);

		$this->db->where('id', $id_program);
		$result = $this->db->update($this->table_program_kegiatan, $data);

		foreach ($indikator as $key => $value) {
			if (!empty($id_indikator_program[$key])) {
				$this->db->where('id', $id_indikator_program[$key]);
				$this->db->where('id_prog_keg', $id_program);
				$this->db->update($this->table_indikator_program, array('indikator' => $value, 'satuan_target' => $satuan_target[$key],
					'target' => $target[$key]));
				unset($id_indikator_program[$key]);
			}else{
				$this->db->insert($this->table_indikator_program, array('id_prog_keg' => $id_program, 'indikator' => $value,
				'satuan_target' => $satuan_target[$key],'target' => $target[$key]));
			}
		}

		if (!empty($id_indikator_program)) {
			$this->db->where_in('id', $id_indikator_program);
			$this->db->delete($this->table_indikator_program);
		}

		$renja = $this->get_one_program(NULL, NULL, $id_program);
		//$this->update_status_after_edit($renja->id, NULL, $id_program);

		$this->db->trans_complete();
		return $this->db->trans_status();
	}

	function delete_program($id){
		$this->db->trans_strict(FALSE);
		$this->db->trans_start();

		$this->db->where('id', $id);
		$this->db->where('is_prog_or_keg', $this->is_program);
		$this->db->delete($this->table_program_kegiatan);

		$this->db->where('parent', $id);
		$this->db->where('is_prog_or_keg', $this->is_kegiatan);
		$this->db->delete($this->table_program_kegiatan);

		$this->db->trans_complete();
		return $this->db->trans_status();
	}

	function get_info_kodefikasi_program($id_program=NULL){
		if (!empty($id_program)) {
			$this->db->select($this->table_program_kegiatan.".kd_urusan");
			$this->db->select($this->table_program_kegiatan.".kd_bidang");
			$this->db->select($this->table_program_kegiatan.".kd_program");
			$this->db->select($this->table_program_kegiatan.".nama_prog_or_keg");
			$this->db->from($this->table_program_kegiatan);
			$this->db->where($this->table_program_kegiatan.'.id', $id_program);
		}

		$result = $this->db->get();
		return $result->row();
	}

	function add_kegiatan_skpd($data, $indikator, $satuan_target, $target){
		$this->db->trans_strict(FALSE);
		$this->db->trans_start();

		$add = array('is_prog_or_keg'=> $this->is_kegiatan, 'id_status'=> $this->id_status_baru);
		$data = $this->global_function->add_array($data, $add);

		$this->db->insert($this->table_program_kegiatan, $data);

		$id = $this->db->insert_id();
		foreach ($indikator as $key => $value) {
			$this->db->insert($this->table_indikator_program, array('id_prog_keg' => $id, 'indikator' => $value, 'satuan_target' => $satuan_target[$key], 'target' => $target[$key]));
		}

		$this->db->trans_complete();
		return $this->db->trans_status();
	}

	function edit_kegiatan_skpd($data, $id_kegiatan, $indikator, $id_indikator_kegiatan, $satuan_target, $target){
		$this->db->trans_strict(FALSE);
		$this->db->trans_start();

		$add = array('is_prog_or_keg'=> $this->is_kegiatan);
		$data = $this->global_function->add_array($data, $add);

		$this->db->where('id', $id_kegiatan);
		$result = $this->db->update($this->table_program_kegiatan, $data);

		foreach ($indikator as $key => $value) {
			if (!empty($id_indikator_kegiatan[$key])) {
				$this->db->where('id', $id_indikator_kegiatan[$key]);
				$this->db->where('id_prog_keg', $id_kegiatan);
				$this->db->update($this->table_indikator_program, array('indikator' => $value, 'satuan_target' => $satuan_target[$key],
				'target' => $target[$key]));
				unset($id_indikator_kegiatan[$key]);
			}else{
				$this->db->insert($this->table_indikator_program, array('id_prog_keg' => $id_kegiatan, 'indikator' => $value, 'satuan_target' => $satuan_target[$key], 'target' => $target[$key]));
			}
		}


		if (!empty($id_indikator_kegiatan)) {
			$this->db->where_in('id', $id_indikator_kegiatan);
			$this->db->delete($this->table_indikator_program);
		}

		//$renstra = $this->get_one_kegiatan(NULL, NULL, NULL, $id_kegiatan);
		//$this->update_status_after_edit($renstra->id_renstra, NULL, NULL, $id_kegiatan);

		$this->db->trans_complete();
		return $this->db->trans_status();
	}

	function edit_program_cik($data, $id_kegiatan, $indikator, $id_indikator_kegiatan, $real, $id_bulan){
		$this->db->trans_strict(FALSE);
		$this->db->trans_start();

		$add = array('is_prog_or_keg'=> $this->is_program);
		$data = $this->global_function->add_array($data, $add);

		$this->db->where('id', $id_kegiatan);
		$result = $this->db->update($this->table_program_kegiatan, $data);

		foreach ($indikator as $key => $value) {
			if (!empty($id_indikator_kegiatan[$key])) {
				$this->db->where('id', $id_indikator_kegiatan[$key]);
				$this->db->where('id_prog_keg', $id_kegiatan);
				$this->db->update($this->table_indikator_program, array('indikator' => $value,'real_'.$id_bulan => $real[$key]));
				unset($id_indikator_kegiatan[$key]);
			}
		}

		/*if (!empty($id_indikator_kegiatan)) {
			$this->db->where_in('id', $id_indikator_kegiatan);
			$this->db->delete($this->table_indikator_program);
		}

		$renstra = $this->get_one_kegiatan(NULL, NULL, NULL, $id_kegiatan);
		$this->update_status_after_edit($renstra->id_renstra, NULL, NULL, $id_kegiatan);*/

		$this->db->trans_complete();
		return $this->db->trans_status();
	}

	function edit_kegiatan_cik($data, $id_kegiatan, $indikator, $id_indikator_kegiatan, $real, $id_bulan){
		$this->db->trans_strict(FALSE);
		$this->db->trans_start();

		$add = array('is_prog_or_keg'=> $this->is_kegiatan);
		$data = $this->global_function->add_array($data, $add);

		$this->db->where('id', $id_kegiatan);
		$result = $this->db->update($this->table_program_kegiatan, $data);

		foreach ($indikator as $key => $value) {
			if (!empty($id_indikator_kegiatan[$key])) {
				$this->db->where('id', $id_indikator_kegiatan[$key]);
				$this->db->where('id_prog_keg', $id_kegiatan);
				$this->db->update($this->table_indikator_program, array('indikator' => $value,'real_'.$id_bulan => $real[$key]));
				unset($id_indikator_kegiatan[$key]);
			}
		}

		/*if (!empty($id_indikator_kegiatan)) {
			$this->db->where_in('id', $id_indikator_kegiatan);
			$this->db->delete($this->table_indikator_program);
		}

		$renstra = $this->get_one_kegiatan(NULL, NULL, NULL, $id_kegiatan);
		$this->update_status_after_edit($renstra->id_renstra, NULL, NULL, $id_kegiatan);*/


		//update ke kendali belanja untuk akhir triwulan
		//if($id_bulan%3==0){
			//ambil triwulan
			$id_triwulan = $id_bulan;
			$sql ="select id_dpa from tx_cik_prog_keg where id = ".$id_kegiatan;
			$id_dpa = $this->db->query($sql)->row()->id_dpa;
			$realisasi_name = 'realisasi_'.$id_bulan;
			$capaian_name = 'capaian_'.$id_bulan;
			$sql_update = "update tx_dpa_prog_keg_triwulan set realisasi = '".$data[$realisasi_name]."',capaian='".$data[$capaian_name]."' where id_dpa_prog_keg ='".$id_dpa."' and id_triwulan='".$id_triwulan."'";
			$this->db->query($sql_update);
		//}
		$this->db->trans_complete();
		return $this->db->trans_status();
	}

	function delete_kegiatan($id){
		$this->db->where('id', $id);
		$this->db->where('is_prog_or_keg', $this->is_kegiatan);
		$result = $this->db->delete($this->table_program_kegiatan);
		return $result;
	}

	//======================================================================
	function add_cik()
	{
		$data = $this->global_function->add_array($data, $add);

		$result = $this->db->insert($this->table_cik, $data);
		return $result;
	}

	function get_data($data,$table){
        $this->db->where($data);
        $query = $this->db->get($this->$table);
        return $query->row();
    }

    function get_cik_by_id($id_cik)
	{
		$sql = "
				SELECT *
				FROM t_cik
				WHERE id_cik = ?
			";

		$query = $this->db->query($sql, array($id_cik));
		var_dump($this->query);
		if($query) {
			if($query->num_rows() > 0) {
				return $query->row();
			}
		}

		return NULL;
	}

	function simpan_cik($data_cik)
	{
		$this->db->trans_strict(FALSE);
		$this->db->trans_start();


		$data_cik->created_date		= Formatting::get_datetime();
		$data_cik->created_by		= $this->session->userdata('username');

		$this->db->set($data_cik);
    	$this->db->insert('t_cik');

		$this->db->trans_complete();
		return $this->db->trans_status();
	}

	function get_data_table($search, $start, $length, $order)
	{
		$order_arr = array('id_bulan','id_cik','kd_urusan','kd_bidang','kd_program','kd_kegiatan');
		$sql="
			SELECT *,bulan.nm_bulan AS nm_bulan FROM ".$this->table_cik." AS cik
			INNER JOIN m_bulan AS bulan ON bulan.id_bulan = cik.id_bulan
			WHERE kd_urusan LIKE '%".$search['value']."%'
            OR kd_bidang LIKE '%".$search['value']."%'
            OR kd_program LIKE '%".$search['value']."%'
            OR kd_kegiatan LIKE '%".$search['value']."%'
		";

		$result = $this->db->query($sql);
		return $result->result();
	}

	function count_data_table($search, $start, $length, $order)
	{
		$this->db->from($this->table_cik);

		$this->db->like("kd_urusan", $search['value']);
		$this->db->or_like("kd_bidang", $search['value']);
		$this->db->or_like("kd_program", $search['value']);
		$this->db->or_like("kd_kegiatan", $search['value']);

		$result = $this->db->count_all_results();
		return $result;
	}

	function get_data_with_rincian($id_cik,$table)
	{
		$sql="
			SELECT * FROM ".$this->$table."
			WHERE id_cik = ?
		";

		$query = $this->db->query($sql, array($id_cik));

		if($query) {
				if($query->num_rows() > 0) {
					return $query->row();
				}
			}

			return NULL;
	}

	function delete_cik($id){
   	    $this->db->trans_strict(FALSE);
		$this->db->trans_start();

		$this->db->where('id_cik',$id);
        $this->db->delete('t_cik');

		$this->db->trans_complete();

		return $this->db->trans_status();
    }

    function update_cik($data,$id,$table,$primary) {
        $this->db->where($this->$primary,$id);
        return $this->db->update($this->$table,$data);
    }

    function get_one_cik($id_skpd, $detail=FALSE){
    	$this->db->select($this->table_cik.".*");
    	$this->db->from($this->table_cik);
    	$this->db->where($this->table_cik.".id_skpd",$id_skpd);

    	if($detail){
    		$this->db->select("nama_skpd");
    		$this->db->join("m_skpd","t_cik.id_skpd = m_skpd.id_skpd","inner");
    	}

    	$result = $this->db->get();
			return $result->row();
    }

    function get_program_rekap_cik_4_cetak($id_skpd,$tahun)
    {
    	$query = "SELECT pro.*
				FROM
					(SELECT * FROM tx_dpa_prog_keg WHERE is_prog_or_keg=1) AS pro
				INNER JOIN
					(SELECT * FROM tx_dpa_prog_keg WHERE is_prog_or_keg=2) AS keg ON keg.parent=pro.id
				WHERE
					keg.id_skpd=?
				AND keg.tahun = ?
				GROUP BY pro.id
				ORDER BY kd_urusan ASC, kd_bidang ASC, kd_program ASC, kd_kegiatan ASC";
			$data = array($id_skpd,$tahun);
			$result = $this->db->query($query,$data);
			return $result->result();
    }

    function get_kegiatan_rekap_cik_4_cetak($id_program,$tahun)
    {
    	$query = "SELECT
							tx_dpa_prog_keg.*
						FROM tx_dpa_prog_keg
						WHERE parent=?
						AND tahun = ?";
			$data = array($id_program,$tahun);
			$result = $this->db->query($query, $data);
			return $result;
    }

    function get_indikator_prog_keg_rekap($id,$return_result=TRUE, $satuan=FALSE)
    {
    	$this->db->select($this->table_indikator_program.".*");
			$this->db->where('id_prog_keg', $id);
			$this->db->from($this->table_indikator_program);

			if ($satuan) {
				$this->db->select("m_lov.nama_value");
				$this->db->join("m_lov",$this->table_indikator_program.".satuan_target = m_lov.kode_value AND kode_app='1'","inner");
			}

			$result = $this->db->get();
			if ($return_result) {
				return $result->result();
			}else{
				return $result;
			}
    }

	function get_urusan_cik($id_bulan,$ta,$id_skpd){
		$query = "SELECT t.*,u.Nm_Urusan AS nama_urusan FROM (
					SELECT pro.*, SUM(keg.realisasi_".$id_bulan.") AS sumrealisasi, SUM(keg.rencana) AS sumrencana,
							 pro.capaian_".$id_bulan." AS capaian
					  FROM
						(SELECT * FROM tx_cik_prog_keg WHERE is_prog_or_keg=1) AS pro
					  INNER JOIN
						(SELECT * FROM tx_cik_prog_keg WHERE is_prog_or_keg=2) AS keg ON keg.parent=pro.id
					  WHERE
					  	keg.tahun = ?
						AND keg.id_skpd = ?
					  GROUP BY pro.kd_urusan
					  ORDER BY kd_urusan ASC, kd_bidang ASC, kd_program ASC, kd_kegiatan ASC
					) t
					LEFT JOIN m_urusan u
					ON t.kd_urusan = u.Kd_Urusan
					";
		$data = array($ta,$id_skpd);
		$result = $this->db->query($query,$data);
		return $result->result();
	}

	function get_bidang_cik($kd_urusan, $id_bulan, $ta, $id_skpd){
		$query = "SELECT t.*,b.Nm_Bidang AS nama_bidang FROM (
					SELECT pro.*, SUM(keg.realisasi_".$id_bulan.") AS sumrealisasi, SUM(keg.rencana) AS sumrencana,
							 pro.capaian_".$id_bulan." AS capaian
					  FROM
						(SELECT * FROM tx_cik_prog_keg WHERE is_prog_or_keg=1) AS pro
					  INNER JOIN
						(SELECT * FROM tx_cik_prog_keg WHERE is_prog_or_keg=2) AS keg ON keg.parent=pro.id
					  WHERE
						keg.kd_urusan = ?
						AND keg.id_skpd = ?
						AND keg.tahun = ?
					  GROUP BY pro.kd_bidang
					  ORDER BY kd_urusan ASC, kd_bidang ASC, kd_program ASC, kd_kegiatan ASC
					) t
					LEFT JOIN m_bidang b
					ON t.kd_urusan = b.Kd_Urusan AND t.kd_bidang = b.Kd_Bidang
					WHERE t.sumrencana > 0";
		$data = array($kd_urusan,$id_skpd,$ta);
		$result = $this->db->query($query,$data);
		return $result->result();
	}

	function get_program_cik($id_skpd,$id_bulan,$ta,$kd_urusan,$kd_bidang)
	{
		$query = "SELECT pro.*, SUM(keg.realisasi_".$id_bulan.") AS realisasi, SUM(keg.rencana) AS rencana,
					     pro.capaian_".$id_bulan." AS capaian, pro.status_".$id_bulan.", m_status_tx.status as status
				  FROM
					(SELECT * FROM tx_cik_prog_keg WHERE is_prog_or_keg=1) AS pro
				  INNER JOIN
					(SELECT * FROM tx_cik_prog_keg WHERE is_prog_or_keg=2 AND rencana > 0) AS keg ON keg.parent=pro.id
				  INNER JOIN m_status_tx ON pro.status_".$id_bulan." = m_status_tx.id
				  WHERE keg.kd_urusan = ?
				  AND keg.kd_bidang = ?
				  AND keg.id_skpd =?
				  AND keg.tahun = ?
				  GROUP BY pro.kd_program
				  ORDER BY kd_urusan ASC, kd_bidang ASC, kd_program ASC, kd_kegiatan ASC";
		$data = array($kd_urusan,$kd_bidang,$id_skpd,$ta);
		$result = $this->db->query($query,$data);
		return $result->result();
	}

	function get_program_cik_4_cetak($id_skpd,$id_bulan,$ta,$kd_urusan,$kd_bidang)
	{
		$query = "SELECT pro.*, SUM(keg.realisasi_".$id_bulan.") AS realisasi, SUM(keg.rencana) AS rencana,
					     pro.capaian_".$id_bulan." AS capaian
				  FROM
					(SELECT * FROM tx_cik_prog_keg WHERE is_prog_or_keg=1) AS pro
				  INNER JOIN
					(SELECT * FROM tx_cik_prog_keg WHERE is_prog_or_keg=2 AND rencana > 0) AS keg ON keg.parent=pro.id
				  WHERE
					keg.id_skpd =?
				  AND keg.tahun = ?
				  AND keg.kd_urusan = ?
				  AND keg.kd_bidang = ?
				  GROUP BY pro.kd_program
				  ORDER BY kd_urusan ASC, kd_bidang ASC, kd_program ASC, kd_kegiatan ASC";
		$data = array($id_skpd,$ta,$kd_urusan,$kd_bidang);
		$result = $this->db->query($query,$data);
		return $result->result();
	}

	function get_kegiatan_cik_4_cetak($kd_urusan,$kd_bidang,$kd_program,$id_skpd,$bulan,$tahun)
    {
		$tahun = $this->session->userdata("t_anggaran_aktif");
    	$query = "SELECT
					tx_cik_prog_keg.*,tx_cik_prog_keg.realisasi_".$bulan." AS realisasi,
					tx_cik_prog_keg.capaian_".$bulan." AS capaian, tx_cik_prog_keg.status_".$bulan.", m_status_tx.status as status
				FROM tx_cik_prog_keg
				INNER JOIN m_status_tx ON tx_cik_prog_keg.status_".$bulan." = m_status_tx.id
				WHERE kd_urusan = ?
				AND kd_bidang = ?
				AND kd_program = ?
				AND id_skpd = ?
				AND tahun = ?
				AND is_prog_or_keg = 2
				AND rencana > 0
				ORDER BY kd_urusan, kd_bidang, kd_program, kd_kegiatan ASC";
			$data = array($kd_urusan,$kd_bidang,$kd_program,$id_skpd,$tahun);
			$result = $this->db->query($query, $data);
			return $result;
    }

	function get_kegiatan_cik_4_preview($kd_urusan,$kd_bidang,$kd_program,$id_skpd,$bulan,$tahun)
    {
		//$tahun = $this->session->userdata("t_anggaran_aktif");
    	$query = "SELECT
					tx_cik_prog_keg.*,tx_cik_prog_keg.realisasi_".$bulan." AS realisasi,
					tx_cik_prog_keg.capaian_".$bulan." AS capaian, tx_cik_prog_keg.status_".$bulan.", m_status_tx.status as status
				FROM tx_cik_prog_keg
				INNER JOIN m_status_tx ON tx_cik_prog_keg.status_".$bulan." = m_status_tx.id
				WHERE kd_urusan = ?
				AND kd_bidang = ?
				AND kd_program = ?
				AND id_skpd = ?
				AND tahun = ?
				AND is_prog_or_keg = 2
				AND rencana > 0
				ORDER BY kd_urusan, kd_bidang, kd_program, kd_kegiatan ASC";
			$data = array($kd_urusan,$kd_bidang,$kd_program,$id_skpd,$tahun);
			$result = $this->db->query($query, $data);
			return $result;
    }

	function get_indikator_prog_keg_preview($id, $bulan, $return_result=TRUE, $satuan=FALSE)
    {
    	$this->db->select($this->table_indikator_program.".*, satuan_target as nama_value");
		$this->db->select($this->table_indikator_program.".real_".$bulan." AS realisasi");
			$this->db->where('id_prog_keg', $id);
			$this->db->where('target >', 0);
			$this->db->from($this->table_indikator_program);

			if ($satuan) {
				// $this->db->select("m_lov.nama_value");
				// $this->db->join("m_lov",$this->table_indikator_program.".satuan_target = m_lov.kode_value AND kode_app='1'","inner");
			}

			$result = $this->db->get();
			if ($return_result) {
				return $result->result();
			}else{
				return $result;
			}
    }


	function get_cik_kegiatan($id,$bulan) {
		$sql = "
			SELECT a.*, a.realisasi_".$bulan." as realisasi
			FROM tx_cik_prog_keg as a
			INNER JOIN m_skpd b ON a.id_skpd=b.id_skpd
			WHERE a.id = ?
		";

		$query = $this->db->query($sql, array($id));

		if($query) {
			if($query->num_rows() > 0) {
				return $query->row();
			}
		}
		return FALSE;
	}

	function get_cik_indikator($id,$bulan){
		$sql = "
			SELECT *, IFNULL(realisasi_".$bulan.",0) AS realisasi
			FROM tx_cik_indikator_prog_keg
			WHERE id_prog_keg = ?
		";
		$data = array($id);
		$result = $this->db->query($sql, $data);
		return $result->result();
	}

	function get_data_upload($id, $id_skpd, $bulan, $tahun, $return_result=TRUE, $satuan=FALSE){
		$sql = "
			SELECT * FROM tx_cik_upload
			WHERE 	id_kegiatan = ".$id." AND
					id_skpd = ".$id_skpd." AND
					bulan = ".$bulan
		;

		$query = $this->db->query($sql, array($id));

		if($query) {
			if($query->num_rows() > 0) {
				return $query->row();
			}
		}
		return NULL;
	}

	function get_file($id = array(), $only = FALSE){
		$this->db->where_in("id", $id);
		$this->db->from('tx_upload_file');
		$result = $this->db->get();
		if ($only) {
			return $result;
		}else{
			return $result->result();
		}
	}

	function get_urusan_cik_pusat($id_bulan,$ta){
		$query = "SELECT t.*,u.Nm_Urusan AS nama_urusan FROM (
					SELECT pro.*, SUM(keg.realisasi_".$id_bulan.") AS sumrealisasi, SUM(keg.rencana) AS sumrencana,
							 pro.capaian_".$id_bulan." AS capaian
					  FROM
						(SELECT * FROM tx_cik_prog_keg WHERE is_prog_or_keg=1) AS pro
					  INNER JOIN
						(SELECT * FROM tx_cik_prog_keg WHERE is_prog_or_keg=2) AS keg ON keg.parent=pro.id
					  WHERE
					  	keg.tahun = ?
					  GROUP BY pro.kd_urusan
					  ORDER BY kd_urusan ASC, kd_bidang ASC, kd_program ASC, kd_kegiatan ASC
					) t
					LEFT JOIN m_urusan u
					ON t.kd_urusan = u.Kd_Urusan";
		$data = array($ta);
		$result = $this->db->query($query,$data);
		return $result->result();
	}

	function get_bidang_cik_pusat_4_cetak($kd_urusan, $id_bulan, $ta){
		$query = "SELECT t.*,b.Nm_Bidang AS nama_bidang FROM (
					SELECT pro.*, SUM(keg.realisasi_".$id_bulan.") AS sumrealisasi, SUM(keg.rencana) AS sumrencana,
							 pro.capaian_".$id_bulan." AS capaian
					  FROM
						(SELECT * FROM tx_cik_prog_keg WHERE is_prog_or_keg=1) AS pro
					  INNER JOIN
						(SELECT * FROM tx_cik_prog_keg WHERE is_prog_or_keg=2) AS keg ON keg.parent=pro.id
					  WHERE
						keg.kd_urusan = ?
						AND keg.tahun = ?
					  GROUP BY pro.kd_bidang
					  ORDER BY kd_urusan ASC, kd_bidang ASC, kd_program ASC, kd_kegiatan ASC
					) t
					LEFT JOIN m_bidang b
					ON t.kd_urusan = b.Kd_Urusan AND t.kd_bidang = b.Kd_Bidang";
		$data = array($kd_urusan,$ta);
		$result = $this->db->query($query,$data);
		return $result->result();
	}

	function get_skpd_cik_pusat_4_cetak($kd_urusan, $kd_bidang, $id_bulan, $ta){
		$query = "SELECT t.*,s.nama_skpd FROM (
					SELECT pro.*, SUM(keg.realisasi_".$id_bulan.") AS sumrealisasi, SUM(keg.rencana) AS sumrencana,
							 pro.capaian_".$id_bulan." AS capaian
					  FROM
						(SELECT * FROM tx_cik_prog_keg WHERE is_prog_or_keg=1 AND tahun = ".$ta.") AS pro
					  INNER JOIN
						(SELECT * FROM tx_cik_prog_keg WHERE is_prog_or_keg=2 AND tahun = ".$ta.") AS keg ON keg.parent=pro.id
					  WHERE
						keg.kd_urusan = ?
						AND keg.kd_bidang = ?
						AND keg.tahun = ?
					  GROUP BY pro.id_skpd
					  ORDER BY CONVERT(pro.id_skpd, DECIMAL) ASC
					) t
					LEFT JOIN m_skpd s
					on t.id_skpd = s.id_skpd";
		$data = array($kd_urusan,$kd_bidang, $ta);
		$result = $this->db->query($query,$data);
		return $result->result();
	}

	function get_program_cik_pusat_4_cetak($kd_urusan, $kd_bidang, $id_skpd, $id_bulan, $ta){
		$query = "SELECT t.*,p.Ket_Program AS nama_program FROM (
					SELECT pro.*, SUM(keg.realisasi_".$id_bulan.") AS sumrealisasi, SUM(keg.rencana) AS sumrencana,
							 pro.capaian_".$id_bulan." AS capaian
					  FROM
						(SELECT * FROM tx_cik_prog_keg WHERE is_prog_or_keg=1) AS pro
					  INNER JOIN
						(SELECT * FROM tx_cik_prog_keg WHERE is_prog_or_keg=2) AS keg ON keg.parent=pro.id
					  WHERE
						keg.kd_urusan = ?
						AND keg.kd_bidang = ?
						AND keg.id_skpd = ?
						AND keg.tahun = ?
					  GROUP BY pro.kd_program
					  ORDER BY kd_urusan ASC, kd_bidang ASC, kd_program ASC, kd_kegiatan ASC
					) t
					LEFT JOIN m_program p
					ON t.kd_urusan = p.`Kd_Urusan` AND t.kd_bidang = p.`Kd_Bidang` AND t.kd_program = p.`Kd_Prog`";
		$data = array($kd_urusan,$kd_bidang, $id_skpd, $ta);
		$result = $this->db->query($query,$data);
		return $result->result();
	}

	function get_kegiatan_cik_pusat_4_cetak($kd_urusan,$kd_bidang,$kd_program,$id_skpd,$bulan,$ta){
		$query = "SELECT
					tx_cik_prog_keg.*,tx_cik_prog_keg.realisasi_".$bulan." AS realisasi, tx_cik_prog_keg.capaian_".$bulan." AS capaian
						FROM tx_cik_prog_keg
						WHERE kd_urusan = ?
						AND kd_bidang = ?
						AND kd_program = ?
						AND id_skpd = ?
						AND tahun = ?
						AND is_prog_or_keg = 2";
		$data = array($kd_urusan,$kd_bidang,$kd_program,$id_skpd,$ta);
		$result = $this->db->query($query,$data);
		return $result->result();
	}

	function get_cik_kirim($id_skpd,$bulan,$ta)
    {
    	$sql="
			SELECT * FROM ".$this->table_program_kegiatan."
			WHERE id_skpd = ?
			AND tahun = ?
		";

		$query = $this->db->query($sql, array($id_skpd,$ta));

		if($query) {
				if($query->num_rows() > 0) {
					return $query->result();
				}
			}
			return NULL;
    }

	function kirim_cik($id_skpd,$bulan,$ta) {
		$this->db->trans_strict(FALSE);
		$this->db->trans_start();
		$data_cik = $this->get_cik_kirim($id_skpd,$bulan,$ta);
		$status = "status_".$bulan;
		//echo $this->db->last_query();
		foreach ($data_cik as $cik){
			if($cik->$status =='1'){
				$this->update_status($cik->id,'2',$bulan);
			}else if ($cik->$status =='3'){
				$this->update_status($cik->id,'2', $bulan);
			}
		}
		$this->db->trans_complete();
		return $this->db->trans_status();
	}

	function update_status($id, $id_status, $bulan)
     {
		$this->db->set('status_'.$bulan,$id_status);
		$this->db->where('id', $id);
		$result=$this->db->update('tx_cik_prog_keg');
		return $result;
	 }

	 function get_all_cik_veri($bulan){
		$ta = $this->m_settings->get_tahun_anggaran();

		$query = "
		SELECT tx_cik_prog_keg.*, m_skpd.*, COUNT(tx_cik_prog_keg.id) AS jum_semua,
		       SUM(IF(tx_cik_prog_keg.status_".$bulan."=?,1,0)) AS jum_dikirim
	    FROM tx_cik_prog_keg
		INNER JOIN m_skpd ON tx_cik_prog_keg.id_skpd=m_skpd.id_skpd
		WHERE tx_cik_prog_keg.tahun=?
		AND tx_cik_prog_keg.status_".$bulan."='2'
		GROUP BY m_skpd.id_skpd";
		//tx_cik_prog_keg.is_prog_or_keg=? AND
		$data = array($this->id_status_send, $ta);
		$result = $this->db->query($query, $data);
		return $result->result();
	}

	function get_all_cik_veri_readonly($bulan){
		$ta = $this->m_settings->get_tahun_anggaran();

		$query = "
		SELECT tx_cik_prog_keg.*, m_skpd.*, COUNT(tx_cik_prog_keg.id) AS jum_semua,
		       SUM(IF(tx_cik_prog_keg.status_".$bulan.">=?,1,0)) AS jum_dikirim
	    FROM tx_cik_prog_keg
		INNER JOIN m_skpd ON tx_cik_prog_keg.id_skpd=m_skpd.id_skpd
		WHERE tx_cik_prog_keg.tahun=?
		AND tx_cik_prog_keg.status_".$bulan.">='2'
		GROUP BY m_skpd.id_skpd";
		//tx_cik_prog_keg.is_prog_or_keg=? AND
		$data = array($this->id_status_send, $ta);
		$result = $this->db->query($query, $data);
		return $result->result();
	}

	function get_data_cik($id_skpd,$bulan){
		$ta = $this->m_settings->get_tahun_anggaran();

		$query = "
			SELECT tx_cik_prog_keg.* FROM tx_cik_prog_keg
			WHERE tx_cik_prog_keg.id_skpd=?
			AND tx_cik_prog_keg.tahun=?
			AND tx_cik_prog_keg.status_".$bulan." =?
			ORDER BY tx_cik_prog_keg.kd_urusan, tx_cik_prog_keg.kd_bidang, tx_cik_prog_keg.kd_program, tx_cik_prog_keg.kd_kegiatan";
		$result = $this->db->query($query, array($id_skpd, $ta, $this->id_status_send));
		return $result->result();
	}

	function get_data_urusan_cik($id_skpd,$bulan){
		$ta = $this->m_settings->get_tahun_anggaran();
		//AND tx_cik_prog_keg.status_".$bulan." >=?
		$query = "
			SELECT tx_cik_prog_keg.*
			FROM tx_cik_prog_keg
			WHERE tx_cik_prog_keg.id_skpd=?
			AND tx_cik_prog_keg.tahun=?

			GROUP BY tx_cik_prog_keg.kd_urusan
			ORDER BY tx_cik_prog_keg.kd_urusan, tx_cik_prog_keg.kd_bidang, tx_cik_prog_keg.kd_program, tx_cik_prog_keg.kd_kegiatan";
		$result = $this->db->query($query, array($id_skpd, $ta, $this->id_status_send));
		return $result->result();
	}

	function get_data_urusan_cik_readonly($id_skpd,$bulan){
		$ta = $this->m_settings->get_tahun_anggaran();

		$query = "
			SELECT tx_cik_prog_keg.*
			FROM tx_cik_prog_keg
			WHERE tx_cik_prog_keg.id_skpd=?
			AND tx_cik_prog_keg.tahun=?
			AND tx_cik_prog_keg.status_".$bulan." >=?
			GROUP BY tx_cik_prog_keg.kd_urusan
			ORDER BY tx_cik_prog_keg.kd_urusan, tx_cik_prog_keg.kd_bidang, tx_cik_prog_keg.kd_program, tx_cik_prog_keg.kd_kegiatan";
		$result = $this->db->query($query, array($id_skpd, $ta, $this->id_status_send));
		return $result->result();
	}

	function get_data_bidang_cik($kd_urusan,$id_skpd,$bulan){
		$ta = $this->m_settings->get_tahun_anggaran();
		//AND tx_cik_prog_keg.status_".$bulan." >=?
		$query = "
			SELECT tx_cik_prog_keg.*
			FROM tx_cik_prog_keg
			WHERE tx_cik_prog_keg.kd_urusan = ?
			AND tx_cik_prog_keg.id_skpd=?
			AND tx_cik_prog_keg.tahun=?

			GROUP BY tx_cik_prog_keg.kd_bidang
			ORDER BY tx_cik_prog_keg.kd_urusan, tx_cik_prog_keg.kd_bidang, tx_cik_prog_keg.kd_program, tx_cik_prog_keg.kd_kegiatan";
		$result = $this->db->query($query, array($kd_urusan,$id_skpd, $ta, $this->id_status_send));
		return $result->result();
	}

	function get_data_bidang_cik_readonly($kd_urusan,$id_skpd,$bulan){
		$ta = $this->m_settings->get_tahun_anggaran();

		$query = "
			SELECT tx_cik_prog_keg.*
			FROM tx_cik_prog_keg
			WHERE tx_cik_prog_keg.kd_urusan = ?
			AND tx_cik_prog_keg.id_skpd=?
			AND tx_cik_prog_keg.tahun=?
			AND tx_cik_prog_keg.status_".$bulan." >=?
			GROUP BY tx_cik_prog_keg.kd_bidang
			ORDER BY tx_cik_prog_keg.kd_urusan, tx_cik_prog_keg.kd_bidang, tx_cik_prog_keg.kd_program, tx_cik_prog_keg.kd_kegiatan";
		$result = $this->db->query($query, array($kd_urusan,$id_skpd, $ta, $this->id_status_send));
		return $result->result();
	}

	function get_data_program_cik($kd_urusan,$kd_bidang,$id_skpd,$bulan){
		$ta = $this->m_settings->get_tahun_anggaran();
		//AND tx_cik_prog_keg.status_".$bulan." >=?
		$query = "
			SELECT tx_cik_prog_keg.*,
				SUM(tx_cik_prog_keg.`rencana`) AS sum_rencana,
				SUM(tx_cik_prog_keg.realisasi_".$bulan.") AS sum_realisasi
			FROM tx_cik_prog_keg
			WHERE tx_cik_prog_keg.kd_urusan = ?
			AND tx_cik_prog_keg.kd_bidang = ?
			AND tx_cik_prog_keg.id_skpd=?
			AND tx_cik_prog_keg.tahun=?

			GROUP BY tx_cik_prog_keg.`kd_program`
			ORDER BY tx_cik_prog_keg.kd_urusan, tx_cik_prog_keg.kd_bidang, tx_cik_prog_keg.kd_program, tx_cik_prog_keg.kd_kegiatan";
		$result = $this->db->query($query, array($kd_urusan, $kd_bidang, $id_skpd, $ta, $this->id_status_send));
		return $result->result();
	}

	function get_data_program_cik_readonly($kd_urusan,$kd_bidang,$id_skpd,$bulan){
		$ta = $this->m_settings->get_tahun_anggaran();
		//AND tx_cik_prog_keg.status_".$bulan." >=?
		$query = "
			SELECT tx_cik_prog_keg.*,
				SUM(tx_cik_prog_keg.`rencana`) AS sum_rencana,
				SUM(tx_cik_prog_keg.realisasi_".$bulan.") AS sum_realisasi
			FROM tx_cik_prog_keg
			WHERE tx_cik_prog_keg.kd_urusan = ?
			AND tx_cik_prog_keg.kd_bidang = ?
			AND tx_cik_prog_keg.id_skpd=?
			AND tx_cik_prog_keg.tahun=?

			GROUP BY tx_cik_prog_keg.`kd_program`
			ORDER BY tx_cik_prog_keg.kd_urusan, tx_cik_prog_keg.kd_bidang, tx_cik_prog_keg.kd_program, tx_cik_prog_keg.kd_kegiatan";
		$result = $this->db->query($query, array($kd_urusan, $kd_bidang, $id_skpd, $ta, $this->id_status_send));
		return $result->result();
	}

	function get_data_kegiatan_cik($id_skpd,$bulan,$kd_urusan,$kd_bidang,$kd_program){
		$ta = $this->m_settings->get_tahun_anggaran();

		$query = "
			SELECT tx_cik_prog_keg.* FROM tx_cik_prog_keg
			WHERE tx_cik_prog_keg.id_skpd=?
			AND tx_cik_prog_keg.tahun=?
			AND tx_cik_prog_keg.status_".$bulan." >=?
			AND tx_cik_prog_keg.kd_urusan = ?
			AND tx_cik_prog_keg.kd_bidang = ?
			AND tx_cik_prog_keg.kd_program = ?
			AND tx_cik_prog_keg.is_prog_or_keg = 2
			ORDER BY tx_cik_prog_keg.kd_urusan, tx_cik_prog_keg.kd_bidang, tx_cik_prog_keg.kd_program, tx_cik_prog_keg.kd_kegiatan";
		$result = $this->db->query($query, array($id_skpd, $ta, $this->id_status_send,$kd_urusan,$kd_bidang,$kd_program));
		return $result->result();
	}

	function get_data_kegiatan_cik_readonly($id_skpd,$bulan,$kd_urusan,$kd_bidang,$kd_program){
		$ta = $this->m_settings->get_tahun_anggaran();

		$query = "
			SELECT tx_cik_prog_keg.* FROM tx_cik_prog_keg
			WHERE tx_cik_prog_keg.id_skpd=?
			AND tx_cik_prog_keg.tahun=?
			AND tx_cik_prog_keg.status_".$bulan." >=?
			AND tx_cik_prog_keg.kd_urusan = ?
			AND tx_cik_prog_keg.kd_bidang = ?
			AND tx_cik_prog_keg.kd_program = ?
			AND tx_cik_prog_keg.is_prog_or_keg = 2
			ORDER BY tx_cik_prog_keg.kd_urusan, tx_cik_prog_keg.kd_bidang, tx_cik_prog_keg.kd_program, tx_cik_prog_keg.kd_kegiatan";
		$result = $this->db->query($query, array($id_skpd, $ta, $this->id_status_send,$kd_urusan,$kd_bidang,$kd_program));
		return $result->result();
	}

	function get_one_cik_veri($id){
		$query = "SELECT tx_cik_prog_keg.* FROM tx_cik_prog_keg WHERE id=?";
		$result = $this->db->query($query, array($id));
		return $result->row();
	}

	function approved_cik($id,$bulan){
		$this->db->where($this->table_program_kegiatan.".id", $id);
		$return = $this->db->update($this->table_program_kegiatan, array('status_'.$bulan=>$this->id_status_approved));
		return $return;
	}

	function not_approved_cik($id,$bulan, $ket){
		$this->db->trans_strict(FALSE);
		$this->db->trans_start();

		$this->db->where($this->table_program_kegiatan.".id", $id);
		$return = $this->db->update($this->table_program_kegiatan, array('status_'.$bulan=>$this->id_status_revisi));

		$this->db->where($this->table_program_kegiatan.".id", $id);
		$return = $this->db->update($this->table_program_kegiatan, array('ket_'.$bulan=>$ket));

		//$result = $this->db->insert("t_cik_revisi", array('id_renja' => $id, 'ket_'.$bulan => $ket));

		$this->db->trans_complete();
		return $this->db->trans_status();
	}

	function disapprove_cik($id, $bulan, $ket){
		$this->db->trans_strict(FALSE);
		$this->db->trans_start();

		//$query = "INSERT t_renja_revisi SELECT NULL, t_renja_prog_keg.id, ? FROM t_renja_prog_keg INNER JOIN t_renstra_prog_keg ON t_renstra_prog_keg.id=t_renja_prog_keg.id_renstra INNER JOIN t_renstra ON t_renstra_prog_keg.id_renstra=t_renstra.id WHERE t_renstra.id_skpd=?";
		//$data = array($ket, $id);
		//$result = $this->db->query($query, $data);

		$query = "
		UPDATE tx_cik_prog_keg SET tx_cik_prog_keg.status_".$bulan."=3
			WHERE tx_cik_prog_keg.id_skpd=? AND
			tx_cik_prog_keg.status_".$bulan."=?
		";
		$data = array($id, $this->id_status_send);
		$result = $this->db->query($query, $data);

		$this->db->trans_complete();
		return $this->db->trans_status();
	}

	function sum_capaian_program($id_skpd,$bulan,$ta)
    {
    	$sql="
			SELECT IFNULL(SUM(capaian_".$bulan."),0)  AS capaianp
			FROM ".$this->table_program_kegiatan."
			WHERE id_skpd = ?
			AND tahun = ?
			AND is_prog_or_keg = ".$this->is_program."
		";

		$query = $this->db->query($sql, array($id_skpd,$ta));

		if($query) {
				if($query->num_rows() > 0) {
					return $query->row();
				}
			}
			return NULL;
    }

	function sum_capaian_kegiatan($id_skpd,$bulan,$ta)
    {
    	$sql="
			SELECT IFNULL(SUM(capaian_".$bulan."),0)  AS capaiank
			FROM ".$this->table_program_kegiatan."
			WHERE id_skpd = ?
			AND tahun = ?
			AND is_prog_or_keg = ".$this->is_kegiatan."
		";

		$query = $this->db->query($sql, array($id_skpd,$ta));

		if($query) {
				if($query->num_rows() > 0) {
					return $query->row();
				}
			}
			return NULL;
    }

	function count_program($id_skpd,$bulan,$ta)
    {
    	$sql="
			SELECT COUNT(capaian_".$bulan.")  AS countp
			FROM ".$this->table_program_kegiatan."
			WHERE id_skpd = ?
			AND tahun = ?
			AND is_prog_or_keg = ".$this->is_program."
		";

		$query = $this->db->query($sql, array($id_skpd,$ta));

		if($query) {
				if($query->num_rows() > 0) {
					return $query->row();
				}
			}
			return NULL;
    }

	function count_kegiatan($id_skpd,$bulan,$ta)
    {
    	$sql="
			SELECT COUNT(capaian_".$bulan.")  AS countk
			FROM ".$this->table_program_kegiatan."
			WHERE id_skpd = ?
			AND tahun = ?
			AND is_prog_or_keg = ".$this->is_kegiatan."
		";

		$query = $this->db->query($sql, array($id_skpd,$ta));

		if($query) {
				if($query->num_rows() > 0) {
					return $query->row();
				}
			}
			return NULL;
    }

	function sum_capaian_program_pusat($bulan,$ta)
    {
    	$sql="
			SELECT IFNULL(SUM(capaian_".$bulan."),0)  AS capaianp
			FROM ".$this->table_program_kegiatan."
			WHERE tahun = ?
			AND is_prog_or_keg = ".$this->is_program."
		";

		$query = $this->db->query($sql, array($ta));

		if($query) {
				if($query->num_rows() > 0) {
					return $query->row();
				}
			}
			return NULL;
    }

	function sum_capaian_kegiatan_pusat($bulan,$ta)
    {
    	$sql="
			SELECT IFNULL(SUM(capaian_".$bulan."),0)  AS capaiank
			FROM ".$this->table_program_kegiatan."
			WHERE tahun = ?
			AND is_prog_or_keg = ".$this->is_kegiatan."
		";

		$query = $this->db->query($sql, array($ta));

		if($query) {
				if($query->num_rows() > 0) {
					return $query->row();
				}
			}
			return NULL;
    }

	function count_program_pusat($bulan,$ta)
    {
    	$sql="
			SELECT COUNT(capaian_".$bulan.")  AS countp
			FROM ".$this->table_program_kegiatan."
			WHERE tahun = ?
			AND is_prog_or_keg = ".$this->is_program."
		";

		$query = $this->db->query($sql, array($ta));

		if($query) {
				if($query->num_rows() > 0) {
					return $query->row();
				}
			}
			return NULL;
    }

	function count_kegiatan_pusat($bulan,$ta)
    {
    	$sql="
			SELECT COUNT(capaian_".$bulan.")  AS countk
			FROM ".$this->table_program_kegiatan."
			WHERE tahun = ?
			AND is_prog_or_keg = ".$this->is_kegiatan."
		";

		$query = $this->db->query($sql, array($ta));

		if($query) {
				if($query->num_rows() > 0) {
					return $query->row();
				}
			}
			return NULL;
    }

	function sum_capaian_program_urusan($kd_urusan,$bulan,$ta)
    {
    	$sql="
			SELECT IFNULL(SUM(capaian_".$bulan."),0)  AS capaianp
			FROM ".$this->table_program_kegiatan."
			WHERE kd_urusan = ?
			AND tahun = ?
			AND is_prog_or_keg = ".$this->is_program."
		";

		$query = $this->db->query($sql, array($kd_urusan,$ta));

		if($query) {
				if($query->num_rows() > 0) {
					return $query->row();
				}
			}
			return NULL;
    }

	function sum_capaian_kegiatan_urusan($kd_urusan,$bulan,$ta)
    {
    	$sql="
			SELECT IFNULL(SUM(capaian_".$bulan."),0)  AS capaiank
			FROM ".$this->table_program_kegiatan."
			WHERE kd_urusan = ?
			AND tahun = ?
			AND is_prog_or_keg = ".$this->is_kegiatan."
		";

		$query = $this->db->query($sql, array($kd_urusan,$ta));

		if($query) {
				if($query->num_rows() > 0) {
					return $query->row();
				}
			}
			return NULL;
    }

	function count_program_urusan($kd_urusan,$bulan,$ta)
    {
    	$sql="
			SELECT COUNT(capaian_".$bulan.")  AS countp
			FROM ".$this->table_program_kegiatan."
			WHERE kd_urusan = ?
			AND tahun = ?
			AND is_prog_or_keg = ".$this->is_program."
		";

		$query = $this->db->query($sql, array($kd_urusan,$ta));

		if($query) {
				if($query->num_rows() > 0) {
					return $query->row();
				}
			}
			return NULL;
    }

	function count_kegiatan_urusan($kd_urusan,$bulan,$ta)
    {
    	$sql="
			SELECT COUNT(capaian_".$bulan.")  AS countk
			FROM ".$this->table_program_kegiatan."
			WHERE kd_urusan = ?
			AND tahun = ?
			AND is_prog_or_keg = ".$this->is_kegiatan."
		";

		$query = $this->db->query($sql, array($kd_urusan,$ta));

		if($query) {
				if($query->num_rows() > 0) {
					return $query->row();
				}
			}
			return NULL;
    }

	function sum_capaian_program_bidang($kd_urusan,$kd_bidang,$bulan,$ta)
    {
    	$sql="
			SELECT IFNULL(SUM(capaian_".$bulan."),0)  AS capaianp
			FROM ".$this->table_program_kegiatan."
			WHERE kd_urusan = ?
			AND kd_bidang = ?
			AND tahun = ?
			AND is_prog_or_keg = ".$this->is_program."
		";

		$query = $this->db->query($sql, array($kd_urusan,$kd_bidang,$ta));

		if($query) {
				if($query->num_rows() > 0) {
					return $query->row();
				}
			}
			return NULL;
    }

	function sum_capaian_kegiatan_bidang($kd_urusan,$kd_bidang,$bulan,$ta)
    {
    	$sql="
			SELECT IFNULL(SUM(capaian_".$bulan."),0)  AS capaiank
			FROM ".$this->table_program_kegiatan."
			WHERE kd_urusan = ?
			AND kd_bidang = ?
			AND tahun = ?
			AND is_prog_or_keg = ".$this->is_kegiatan."
		";

		$query = $this->db->query($sql, array($kd_urusan,$kd_bidang,$ta));

		if($query) {
				if($query->num_rows() > 0) {
					return $query->row();
				}
			}
			return NULL;
    }

	function count_program_bidang($kd_urusan,$kd_bidang,$bulan,$ta)
    {
    	$sql="
			SELECT COUNT(capaian_".$bulan.")  AS countp
			FROM ".$this->table_program_kegiatan."
			WHERE kd_urusan = ?
			AND kd_bidang = ?
			AND tahun = ?
			AND is_prog_or_keg = ".$this->is_program."
		";

		$query = $this->db->query($sql, array($kd_urusan,$kd_bidang,$ta));

		if($query) {
				if($query->num_rows() > 0) {
					return $query->row();
				}
			}
			return NULL;
    }

	function count_kegiatan_bidang($kd_urusan,$kd_bidang,$bulan,$ta)
    {
    	$sql="
			SELECT COUNT(capaian_".$bulan.")  AS countk
			FROM ".$this->table_program_kegiatan."
			WHERE kd_urusan = ?
			AND kd_bidang = ?
			AND tahun = ?
			AND is_prog_or_keg = ".$this->is_kegiatan."
		";

		$query = $this->db->query($sql, array($kd_urusan,$kd_bidang,$ta));

		if($query) {
				if($query->num_rows() > 0) {
					return $query->row();
				}
			}
			return NULL;
    }

	function get_skpd($id_bulan,$ta,$id_skpd=NULL){
		$where="";
		if (!empty($id_skpd) && $id_skpd!="all") {
			$where=" WHERE t.id_skpd='". $id_skpd ."'";
		}

		$query = "SELECT t.*,s.nama_skpd FROM (
					SELECT pro.*, SUM(keg.realisasi_".$id_bulan.") AS sumrealisasi, SUM(keg.rencana) AS sumrencana,
							 pro.capaian_".$id_bulan." AS capaian
					  FROM
						(SELECT * FROM tx_cik_prog_keg WHERE is_prog_or_keg=1 AND tahun = ".$ta.") AS pro
					  INNER JOIN
						(SELECT * FROM tx_cik_prog_keg WHERE is_prog_or_keg=2 AND tahun = ".$ta.") AS keg ON keg.parent=pro.id
					  WHERE keg.tahun = ?
					  GROUP BY pro.id_skpd
					  ORDER BY CONVERT(pro.id_skpd, DECIMAL) ASC
					) t
					LEFT JOIN m_skpd s
					on t.id_skpd = s.id_skpd".$where;
		$data = array($ta);
		$result = $this->db->query($query,$data);
		return $result->result();
	}
}
