<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_renstra_trx extends CI_Model
{
	var $table = 't_renstra';
	var $table_misi = 't_renstra_misi';
	var $table_tujuan = 't_renstra_tujuan';
	var $table_sasaran = 't_renstra_sasaran';
	var $table_program_kegiatan = 't_renstra_prog_keg';

	var $table_indikator_tujuan = 't_renstra_indikator_tujuan';
	var $table_indikator_sasaran = 't_renstra_indikator_sasaran';
	var $table_indikator_program = 't_renstra_indikator_prog_keg';

	var $is_program = 1;
	var $is_kegiatan = 2;

	var $id_status_baru = "1";
	var $id_status_send = "2";
	var $id_status_revisi = "3";
	var $id_status_approved = "4";
	var $id_status_baru2 = "5";
	var $id_status_revisi2 = "6";
	var $id_status_approved2 = "7";

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
		$this->db->insert($this->table_tujuan, $tujuan);
		return $this->db->insert_id();

		// insert bacth indikator
	}

	private function update_tujuan($tujuan, $id){
		$this->db->where("id", $id);
		$this->db->update($this->table_tujuan, $tujuan);

	}

	private function update_status_after_edit($id_renstra, $id_sasaran=NULL, $id_program=NULL, $id_kegiatan=NULL){
		$proses = $this->cek_proses($id_renstra);

		if(!empty($id_sasaran)) {
			$this->db->where($this->table_program_kegiatan.".id_sasaran", $id_sasaran);
		}elseif (!empty($id_program)) {
			$this->db->where($this->table_program_kegiatan.".id", $id_program);
		}elseif (!empty($id_kegiatan)) {
			$this->db->where($this->table_program_kegiatan.".id", $id_kegiatan);
		}elseif (!empty($id_renstra)) {
			$this->db->where($this->table.".id", $id_renstra);
		}

		if (!empty($proses->proses2) && empty($proses->proses1)) {
			$this->db->where($this->table_program_kegiatan.".id_status <= ".$this->id_status_revisi2);
			$return = $this->db->update($this->table_program_kegiatan." INNER JOIN ". $this->table ." ON ". $this->table_program_kegiatan .".id_renstra=". $this->table .".id", array($this->table_program_kegiatan.'.id_status'=>$this->id_status_baru2));
		}else{
			$this->db->where($this->table_program_kegiatan.".id_status <= ".$this->id_status_revisi);
			$return = $this->db->update($this->table_program_kegiatan." INNER JOIN ". $this->table ." ON ". $this->table_program_kegiatan .".id_renstra=". $this->table .".id", array($this->table_program_kegiatan.'.id_status'=>$this->id_status_baru));
		}
		return $return;
	}

	function cek_proses($id_renstra=NULL, $id_skpd=NULL){
		if (!empty($id_renstra) && !empty($id_skpd)) {
			$where = "t_renstra.id='". $id_renstra ."' AND t_renstra.id_skpd='". $id_skpd ."'";
		}elseif (!empty($id_renstra)) {
			$where = "t_renstra.id='". $id_renstra ."'";
		}elseif (!empty($id_skpd)) {
			$where = "t_renstra.id_skpd='". $id_skpd ."'";
		}

		$query = "SELECT SUM(IF((t_renstra_prog_keg.id_status>=? AND t_renstra_prog_keg.id_status<?), 1, 0)) as proses1, SUM(IF((t_renstra_prog_keg.id_status>=? AND t_renstra_prog_keg.id_status<=?), 1, 0)) as proses2 FROM t_renstra_prog_keg INNER JOIN t_renstra ON t_renstra_prog_keg.id_renstra=t_renstra.id WHERE ".$where;
		$data = array($this->id_status_baru, $this->id_status_approved, $this->id_status_approved, $this->id_status_approved2);
		$result = $this->db->query($query, $data);
		return $result->row();
	}

	function get_one_renstra_skpd($id_skpd, $detail=FALSE){
		$kode_unit_skpd = $this->m_skpd->get_kode_unit($id_skpd);
		if ($kode_unit_skpd!=$id_skpd) {
			$id_skpd= $kode_unit_skpd;
		};
		$this->db->select($this->table.".*");
		$this->db->from($this->table);
		$this->db->where($this->table.".id_skpd", $id_skpd);

		if ($detail) {
			$this->db->select("nama_skpd");
			$this->db->join("m_skpd","t_renstra.id_skpd = m_skpd.id_skpd","inner");
		}


		$result = $this->db->get();
		return $result->row();
	}



	function get_all_renstra_misi($id_renstra, $no_result=FALSE){
		$this->db->from($this->table_misi);
		$this->db->where("id_renstra", $id_renstra);
		$result = $this->db->get();
		if ($no_result) {
			return $result;
		}else{
			return $result->result();
		}
	}

	function get_all_renstra_tujuan($id_renstra, $no_result=FALSE){
		$this->db->from($this->table_tujuan);
		$this->db->where("id_renstra", $id_renstra);
		$result = $this->db->get();
		if ($no_result) {
			return $result;
		}else{
			return $result->result();
		}
	}
	function get_indikator_tujuan_cetak($id){
		$this->db->select($this->table_indikator_tujuan.".*");
		$this->db->where('id_tujuan', $id);
		$this->db->from($this->table_indikator_tujuan);

			$this->db->select("m_lov.nama_value");
			$this->db->join("m_lov",$this->table_indikator_tujuan.".satuan_target = m_lov.kode_value AND kode_app='1'","inner");
		$result = $this->db->get();

			return $result->result();


	}
	function get_indikator_sasaran_cetak($id, $no_result=FALSE){
		$this->db->select($this->table_indikator_sasaran.".*");
		$this->db->where('id_sasaran', $id);
		$this->db->from($this->table_indikator_sasaran);

			// $this->db->select("m_lov.nama_value");
			// $this->db->join("m_lov",$this->table_indikator_sasaran.".satuan_target = m_lov.kode_value AND kode_app='1'","inner");
		$result = $this->db->get();
		if ($no_result) {
			return $result;
		}else{
			return $result->result();
		}


	}
	function get_all_tujuan($id_renstra, $id_misi=NULL, $with_satuan=FALSE){
			$this->db->select($this->table_tujuan.".*");
			$this->db->where('id_renstra', $id_renstra);
			if (!empty($id_misi)) {
				$this->db->where('id_misi', $id_misi);
			}
			$this->db->from($this->table_tujuan);

			if ($with_satuan) {
				$this->db->select("m_lov.nama_value");
				$this->db->join("m_lov",$this->table_tujuan.".satuan = m_lov.kode_value AND kode_app='1'","inner");
			}

			$result = $this->db->get();
			return $result->result();
		}
	function get_each_renstra_tujuan($id_renstra, $id_misi){
		$this->db->from($this->table_tujuan);
		$this->db->where("id_renstra", $id_renstra);
		$this->db->where("id_misi", $id_misi);
		$result = $this->db->get();
		return $result;
	}

	function get_each_renstra_indikator_tujuan($id_tujuan){
		$this->db->from($this->table_indikator_tujuan);
		$this->db->where("id_tujuan", $id_tujuan);
		$result = $this->db->get();
		return $result;
	}

		function get_kegiatan($id_kegiatan, $tahun=NULL, $not_in=NULL){
				$th = "";
				$not = "";
				if (!empty($tahun)) {
					$th = " AND tahun = '".$tahun."' ";
				}
				if(!empty($not_in)){
					$not = " AND id <> '".$not_in."' ";
				}

				$query = "SELECT id ,tahun, id_renstra,
								kode_sumber_dana AS kode_sumber_dana,(
									SELECT sumber_dana FROM m_sumber_dana WHERE id = kode_sumber_dana
								) AS Sumber_dana,
								kode_jenis_belanja AS kode_jenis_belanja, (
									SELECT jenis_belanja FROM m_jenis_belanja WHERE kd_jenis_belanja = kode_jenis_belanja
								) AS jenis_belanja,
								kode_kategori_belanja AS kode_kategori_belanja, (
									SELECT kategori_belanja FROM m_kategori_belanja WHERE kd_jenis_belanja = kode_jenis_belanja AND kd_kategori_belanja = kode_kategori_belanja
								) AS kategori_belanja,
								kode_sub_kategori_belanja AS kode_sub_kategori_belanja,(
									SELECT sub_kategori_belanja FROM m_subkategori_belanja WHERE kd_jenis_belanja = kode_jenis_belanja AND kd_kategori_belanja = kode_kategori_belanja AND kd_subkategori_belanja = kode_sub_kategori_belanja
								) AS sub_kategori_belanja,
								kode_belanja AS kode_belanja,(
									SELECT belanja FROM m_belanja WHERE kd_jenis_belanja = kode_jenis_belanja AND kd_kategori_belanja = kode_kategori_belanja AND kd_subkategori_belanja = kode_sub_kategori_belanja AND kd_belanja = kode_belanja
								) AS belanja,
								uraian_belanja, detil_uraian_belanja, volume, satuan, nominal_satuan, subtotal, id_kegiatan
								FROM t_renstra_belanja_kegiatan
								WHERE id_kegiatan = '$id_kegiatan' ".$th." ".$not." 
								ORDER BY kode_jenis_belanja ASC, kode_kategori_belanja ASC, kode_sub_kategori_belanja ASC, kode_belanja ASC";

		$result = $this->db->query($query);
		return $result->result();
	}

	function get_one_belanja($id_belanja){
		$query = "SELECT id ,tahun, id_renstra,
				kode_sumber_dana AS kode_sumber_dana,(
					SELECT sumber_dana FROM m_sumber_dana WHERE id = kode_sumber_dana
				) AS Sumber_dana,
				kode_jenis_belanja AS kode_jenis_belanja, (
					SELECT jenis_belanja FROM m_jenis_belanja WHERE kd_jenis_belanja = kode_jenis_belanja
				) AS jenis_belanja,
				kode_kategori_belanja AS kode_kategori_belanja, (
					SELECT kategori_belanja FROM m_kategori_belanja WHERE kd_jenis_belanja = kode_jenis_belanja AND kd_kategori_belanja = kode_kategori_belanja
				) AS kategori_belanja,
				kode_sub_kategori_belanja AS kode_sub_kategori_belanja,(
					SELECT sub_kategori_belanja FROM m_subkategori_belanja WHERE kd_jenis_belanja = kode_jenis_belanja AND kd_kategori_belanja = kode_kategori_belanja AND kd_subkategori_belanja = kode_sub_kategori_belanja
				) AS sub_kategori_belanja,
				kode_belanja AS kode_belanja,(
					SELECT belanja FROM m_belanja WHERE kd_jenis_belanja = kode_jenis_belanja AND kd_kategori_belanja = kode_kategori_belanja AND kd_subkategori_belanja = kode_sub_kategori_belanja AND kd_belanja = kode_belanja
				) AS belanja,
				uraian_belanja, detil_uraian_belanja, volume, satuan, nominal_satuan, subtotal, id_kegiatan
				FROM t_renstra_belanja_kegiatan
				WHERE id = '$id_belanja'
				ORDER BY kode_jenis_belanja ASC, kode_kategori_belanja ASC, kode_sub_kategori_belanja ASC, kode_belanja ASC";

		$result = $this->db->query($query);
		return $result->row();
	}

		function get_kegiatan_semuanya($id){


				$query = "SELECT
							kode_sumber_dana AS kode_sumber_dana,(
							SELECT sumber_dana FROM m_sumber_dana WHERE id = kode_sumber_dana
							) AS sumberDana,
							kode_jenis_belanja AS kode_jenis_belanja, (
							SELECT jenis_belanja FROM m_jenis_belanja WHERE kd_jenis_belanja = kode_jenis_belanja
							) AS jenis,
							kode_kategori_belanja AS kode_kategori_belanja, (
							SELECT kategori_belanja FROM m_kategori_belanja WHERE kd_jenis_belanja = kode_jenis_belanja AND kd_kategori_belanja = kode_kategori_belanja
							) AS kategori,
							kode_sub_kategori_belanja AS kode_sub_kategori_belanja,(
							SELECT sub_kategori_belanja FROM m_subkategori_belanja WHERE kd_jenis_belanja = kode_jenis_belanja AND kd_kategori_belanja = kode_kategori_belanja AND kd_subkategori_belanja = kode_sub_kategori_belanja
							) AS subkategori,
							kode_belanja AS kode_belanja,(
							SELECT belanja FROM m_belanja WHERE kd_jenis_belanja = kode_jenis_belanja AND kd_kategori_belanja = kode_kategori_belanja AND kd_subkategori_belanja = kode_sub_kategori_belanja AND kd_belanja = kode_belanja
									) AS belanja,
							uraian_belanja,detil_uraian_belanja,id_kegiatan
							FROM t_renstra_belanja_kegiatan
							WHERE id_kegiatan = '$id'
							GROUP BY kode_sumber_dana, sumberDana,kode_jenis_belanja,jenis,kode_kategori_belanja, kategori, kode_sub_kategori_belanja,
							subkategori, kode_belanja, belanja,uraian_belanja, detil_uraian_belanja
							ORDER BY kode_jenis_belanja ASC, kode_kategori_belanja ASC, kode_sub_kategori_belanja ASC, kode_belanja ASC";

		$result = $this->db->query($query);
		return $result->result();
	}

	function delete_one_kegiatan($id){
		$this->db->query('DELETE FROM t_renstra_belanja_kegiatan WHERE id = "'.$id.'"');
	}

	function cek_belanja_pertahun($id, $tahun){
		$result = $this->db->query("select count(id) as cid from t_renstra_belanja_kegiatan where tahun = '".$tahun."' and id_kegiatan = '".$id."'");
		return $result->row();
	}

	function copy_belanja($id, $tahun){
		$thmin = $tahun - 1;
		$this->db->query("insert into t_renstra_belanja_kegiatan(
		    `t_renstra_belanja_kegiatan`.`tahun`,
		    `t_renstra_belanja_kegiatan`.`kode_urusan`,
		    `t_renstra_belanja_kegiatan`.`kode_bidang`,
		    `t_renstra_belanja_kegiatan`.`kode_program`,
		    `t_renstra_belanja_kegiatan`.`kode_kegiatan`,
		    `t_renstra_belanja_kegiatan`.`id_kegiatan`,
		    `t_renstra_belanja_kegiatan`.`kode_sumber_dana`,
		    `t_renstra_belanja_kegiatan`.`kode_jenis_belanja`,
		    `t_renstra_belanja_kegiatan`.`kode_kategori_belanja`,
		    `t_renstra_belanja_kegiatan`.`kode_sub_kategori_belanja`,
		    `t_renstra_belanja_kegiatan`.`kode_belanja`,
		    `t_renstra_belanja_kegiatan`.`uraian_belanja`,
		    `t_renstra_belanja_kegiatan`.`detil_uraian_belanja`,
		    `t_renstra_belanja_kegiatan`.`volume`,
		    `t_renstra_belanja_kegiatan`.`satuan`,
		    `t_renstra_belanja_kegiatan`.`nominal_satuan`,
		    `t_renstra_belanja_kegiatan`.`subtotal`,
		    `t_renstra_belanja_kegiatan`.`created_date`)
		SELECT 
		    '".$tahun."',
		    `t_renstra_belanja_kegiatan`.`kode_urusan`,
		    `t_renstra_belanja_kegiatan`.`kode_bidang`,
		    `t_renstra_belanja_kegiatan`.`kode_program`,
		    `t_renstra_belanja_kegiatan`.`kode_kegiatan`,
		    `t_renstra_belanja_kegiatan`.`id_kegiatan`,
		    `t_renstra_belanja_kegiatan`.`kode_sumber_dana`,
		    `t_renstra_belanja_kegiatan`.`kode_jenis_belanja`,
		    `t_renstra_belanja_kegiatan`.`kode_kategori_belanja`,
		    `t_renstra_belanja_kegiatan`.`kode_sub_kategori_belanja`,
		    `t_renstra_belanja_kegiatan`.`kode_belanja`,
		    `t_renstra_belanja_kegiatan`.`uraian_belanja`,
		    `t_renstra_belanja_kegiatan`.`detil_uraian_belanja`,
		    `t_renstra_belanja_kegiatan`.`volume`,
		    `t_renstra_belanja_kegiatan`.`satuan`,
		    `t_renstra_belanja_kegiatan`.`nominal_satuan`,
		    `t_renstra_belanja_kegiatan`.`subtotal`,
		    now()
		FROM `sirenbangda_db_sirenbangda_ng`.`t_renstra_belanja_kegiatan` 
		where t_renstra_belanja_kegiatan.id_kegiatan = '".$id."' and tahun = '".$thmin."'");
	}

	function get_nominal_detail_uraian_semuatahun($kode_sumberdana, $kode_jenis_belanja,$kode_kategori_belanja,
		$kode_sub_kategori_belanja,$kode_belanja, $tahun, $detil_uraian_belanja, $id_kegiatan){
		$query="SELECT *,(
		SELECT sumber_dana FROM m_sumber_dana WHERE id = kode_sumber_dana
		) AS sumberdana
		 FROM (`t_renstra_belanja_kegiatan`)
		WHERE `kode_sumber_dana` = '$kode_sumberdana'
		AND `kode_jenis_belanja` = '$kode_jenis_belanja'
		AND `kode_kategori_belanja` = '$kode_kategori_belanja'
		AND `kode_sub_kategori_belanja` = '$kode_sub_kategori_belanja'
		AND `kode_belanja` = '$kode_belanja'
		AND `tahun` = '$tahun'
		AND `detil_uraian_belanja` = '$detil_uraian_belanja'
		AND `id_kegiatan` = '$id_kegiatan'";
		$result = $this->db->query($query);

		//print_r($this->db->last_query());
		// print_r($result->result());
		// exit;
		return $result->result();


	}

	function get_renstra_belanja_per_tahun211($ta, $idK){
		//------- query by deesudi
			$query = $this->db->query("SELECT id ,tahun,
							kode_sumber_dana AS kode_sumber_dana,(
								SELECT sumber_dana FROM m_sumber_dana WHERE id = kode_sumber_dana
							) AS sumberDana,
							kode_jenis_belanja AS kode_jenis_belanja, (
								SELECT jenis_belanja FROM m_jenis_belanja WHERE kd_jenis_belanja = kode_jenis_belanja
							) AS jenis,
							kode_kategori_belanja AS kode_kategori_belanja, (
								SELECT kategori_belanja FROM m_kategori_belanja WHERE kd_jenis_belanja = kode_jenis_belanja AND kd_kategori_belanja = kode_kategori_belanja
							) AS kategori,
							kode_sub_kategori_belanja AS kode_sub_kategori_belanja,(
								SELECT sub_kategori_belanja FROM m_subkategori_belanja WHERE kd_jenis_belanja = kode_jenis_belanja AND kd_kategori_belanja = kode_kategori_belanja AND kd_subkategori_belanja = kode_sub_kategori_belanja
							) AS subkategori,
							kode_belanja AS kode_belanja,(
								SELECT belanja FROM m_belanja WHERE kd_jenis_belanja = kode_jenis_belanja AND kd_kategori_belanja = kode_kategori_belanja AND kd_subkategori_belanja = kode_sub_kategori_belanja AND kd_belanja = kode_belanja
							) AS belanja,(
								SELECT Kd_Fungsi FROM m_bidang WHERE Kd_Urusan = kode_urusan AND Kd_Bidang = kode_bidang
							) AS kode_fungsi,(
								SELECT Nm_Fungsi FROM m_fungsi WHERE Kd_Fungsi = kode_fungsi
							) AS nama_fungsi,(
								SELECT Nm_Urusan FROM m_urusan WHERE Kd_Urusan = kode_urusan
							) AS nama_urusan,(
								SELECT Ket_Program FROM m_program WHERE Kd_Urusan = kode_urusan  AND Kd_Bidang = kode_bidang AND Kd_Prog = kode_program
							) AS nama_program,(
								SELECT Ket_Kegiatan FROM m_kegiatan WHERE Kd_Urusan = kode_urusan AND Kd_Bidang = kode_bidang AND Kd_Prog = kode_program AND Kd_Keg = kode_kegiatan
							) AS nama_kegiatan,(
							SELECT Nm_Bidang FROM m_bidang WHERE Kd_Urusan = kode_urusan AND Kd_Bidang = kode_bidang
							) AS nama_bidang,(
							SELECT id FROM m_tahun_anggaran WHERE tahun_anggaran = '$ta'
							) AS tahun_anggaran,(
							SELECT nominal_1 FROM `t_renstra_prog_keg` WHERE id = '$idK'
							) AS nominal_1,(
							SELECT nominal_2 FROM `t_renstra_prog_keg` WHERE id = '$idK'
							) AS nominal_2,(
							SELECT nominal_3 FROM `t_renstra_prog_keg` WHERE id = '$idK'
							) AS nominal_3,(
							SELECT nominal_4 FROM `t_renstra_prog_keg` WHERE id = '$idK'
							) AS nominal_4,(
							SELECT nominal_5 FROM `t_renstra_prog_keg` WHERE id = '$idK'
							) AS nominal_5,

							uraian_belanja,detil_uraian_belanja,volume,satuan,nominal_satuan,subtotal,tahun,id_kegiatan , kode_urusan , kode_bidang , kode_program, kode_kegiatan
							FROM t_renstra_belanja_kegiatan
							WHERE tahun = '$ta' and id_kegiatan = '$idK'
							ORDER BY kode_jenis_belanja ASC, kode_kategori_belanja ASC, kode_sub_kategori_belanja ASC, kode_belanja ASC");
		return $query->result();
	}

	function get_indikator_keluaran($ta, $idK){
			$query = $this->db->query("SELECT * FROM `t_renstra_indikator_prog_keg` WHERE   `id_prog_keg` = '$idK'");
		return $query->result();
	}

	function get_indikator_capaian( $idP){
			$query = $this->db->query("SELECT * FROM `t_renstra_indikator_prog_keg` WHERE   `id_prog_keg` = '$idP'");
		return $query->result();
	}

	function get_one_renstra_tujuan($id_renstra, $id_tujuan){
		$this->db->from($this->table_tujuan);
		$this->db->where("id_renstra", $id_renstra);
		$this->db->where("id", $id_tujuan);
		$result = $this->db->get();
		return $result->row();
	}

	function get_indikator_tujuan($id, $return_result=TRUE){
		$this->db->select($this->table_indikator_tujuan.".*");
		$this->db->where('id_tujuan', $id);
		$this->db->from($this->table_indikator_tujuan);

		$this->db->select("m_lov.nama_value");
		$this->db->select("m_status_indikator.nama_status_indikator");
		$this->db->select("m_kategori_indikator.nama_kategori_indikator");
		$this->db->join("m_lov",$this->table_indikator_tujuan.".satuan_target = m_lov.kode_value AND kode_app='1'","inner");
		$this->db->join("m_status_indikator",$this->table_indikator_tujuan.".status_indikator = m_status_indikator.kode_status_indikator","inner");
		$this->db->join("m_kategori_indikator",$this->table_indikator_tujuan.".kategori_indikator = m_kategori_indikator.kode_kategori_indikator","inner");

		$result = $this->db->get();
		if ($return_result) {
			return $result->result();
		}else{
			return $result;
		}
	}

	function add_renstra_skpd($data, $misi, $tujuan, $indikator){
		$this->db->trans_strict(FALSE);
		$this->db->trans_start();

		$data = $this->create_history($data);

		$this->db->insert($this->table, $data);
		$id_renstra = $this->db->insert_id();

		$id_misi = array();
		foreach ($misi as $key => $value) {
			$id_misi[$key] = $this->add_misi(array('id_renstra' => $id_renstra, 'misi' => $value));
		}

		$tujuan_batch = array();
		$indikator_batch = array();
		foreach ($misi as $key => $value) {
			foreach ($tujuan['tujuan'][$key] as $key1 => $value1) {
				$this->db->insert($this->table_tujuan, array('id_renstra' => $id_renstra, 'id_misi' => $id_misi[$key], 'tujuan' => $tujuan['tujuan'][$key][$key1]
				));
				$id_tujuan =  $this->db->insert_id();
				foreach ($indikator[$key][$key1] as $key2 => $value2) {
					$this->db->insert('t_renstra_indikator_tujuan', array('id_tujuan' => $id_tujuan, 'indikator' => $indikator[$key][$key1][$key2], 'satuan_target' => $tujuan['satuan_target'][$key][$key1][$key2],
					 	'status_indikator' => $tujuan['status_target'][$key][$key1][$key2], 'kategori_indikator' => $tujuan['kategori_target'][$key][$key1][$key2], 'kondisi_awal' => $tujuan['kondisi_awal'][$key][$key1][$key2],
						'target_1' => $tujuan['target_1'][$key][$key1][$key2], 'target_2' => $tujuan['target_2'][$key][$key1][$key2], 'target_3' => $tujuan['target_3'][$key][$key1][$key2],
						'target_4' => $tujuan['target_4'][$key][$key1][$key2], 'target_5' => $tujuan['target_5'][$key][$key1][$key2], 'kondisi_akhir' => $tujuan['kondisi_akhir'][$key][$key1][$key2]
					));
				}
			}
		}

		$this->db->trans_complete();
		return $this->db->trans_status();
	}

	function edit_renstra_skpd($data, $misi, $tujuan, $indikator, $id_misi_old, $id_tujuan_old, $id_indikator_old, $id_renstra){
		$this->db->trans_strict(FALSE);
		$this->db->trans_start();

		$data = $this->change_history($data);

		$this->db->where('id', $id_renstra);
		$this->db->update($this->table, $data);

		$id_misi = array();
		foreach ($misi as $key => $value) {
			if (!empty($id_misi_old[$key])) {
				$this->update_misi(array('id_renstra' => $id_renstra, 'misi' => $value), $id_misi_old[$key]);
				$id_misi[$key] = $id_misi_old[$key];
				unset($id_misi_old[$key]);
			}else{
				$id_misi[$key] = $this->add_misi(array('id_renstra' => $id_renstra, 'misi' => $value));
			}
		}

		$id_tujuan = array();
		foreach ($misi as $key => $value) {
			foreach ($tujuan[$key] as $key1 => $value1) {
				if (!empty($id_tujuan_old[$key][$key1])) {
					$this->update_tujuan(array('tujuan' => $value1, 'id_misi' => $id_misi[$key]), $id_tujuan_old[$key][$key1]);
					$id_tujuan[$key][$key1] = $id_tujuan_old[$key][$key1];
					unset($id_tujuan_old[$key][$key1]);
				}else{
					$id_tujuan[$key][$key1] = $this->add_tujuan(array('id_renstra' => $id_renstra, 'id_misi' => $id_misi[$key], 'tujuan' => $value1));
				}
			}
		}


		$id_indikator = array();
		foreach ($misi as $key => $value) {
			foreach ($tujuan[$key] as $key1 => $value1) {
				foreach ($indikator['indikator'][$key][$key1] as $key2 => $value2) {
					if (!empty($id_indikator_old[$key][$key1][$key2])) {
						$isinyah = array('indikator' => $indikator['indikator'][$key][$key1][$key2], 'satuan_target' => $indikator['satuan_target'][$key][$key1][$key2], 'status_indikator' => $indikator['status_target'][$key][$key1][$key2],
								'kategori_indikator' => $indikator['kategori_target'][$key][$key1][$key2], 'kondisi_awal' => $indikator['kondisi_awal'][$key][$key1][$key2], 'target_1' => $indikator['target_1'][$key][$key1][$key2],
								'target_2' => $indikator['target_2'][$key][$key1][$key2], 'target_3' => $indikator['target_3'][$key][$key1][$key2], 'target_4' => $indikator['target_4'][$key][$key1][$key2],
							  'target_5' => $indikator['target_5'][$key][$key1][$key2], 'kondisi_akhir' => $indikator['kondisi_akhir'][$key][$key1][$key2]);
						$this->db->where("id", $id_indikator_old[$key][$key1][$key2]);
						$this->db->update($this->table_indikator_tujuan, $isinyah);
						$id_indikator[$key][$key1][$key2] = $id_indikator_old[$key][$key1][$key2];
						unset($id_indikator_old[$key][$key1][$key2]);
					}else{
						$isinyahnyah = array('id_tujuan' => $id_tujuan[$key][$key1], 'indikator' => $indikator['indikator'][$key][$key1][$key2], 'satuan_target' => $indikator['satuan_target'][$key][$key1][$key2],
								'status_indikator' => $indikator['status_target'][$key][$key1][$key2],
								'kategori_indikator' => $indikator['kategori_target'][$key][$key1][$key2], 'kondisi_awal' => $indikator['kondisi_awal'][$key][$key1][$key2], 'target_1' => $indikator['target_1'][$key][$key1][$key2],
								'target_2' => $indikator['target_2'][$key][$key1][$key2], 'target_3' => $indikator['target_3'][$key][$key1][$key2], 'target_4' => $indikator['target_4'][$key][$key1][$key2],
							  'target_5' => $indikator['target_5'][$key][$key1][$key2], 'kondisi_akhir' => $indikator['kondisi_akhir'][$key][$key1][$key2]);
						$this->db->insert($this->table_indikator_tujuan, $isinyahnyah);
					}

				}

			}
		}


		// if (!empty($id_indikator_old)) {
		// 	$this->db->where_in('id', $id_indikator_old);
		// 	$this->db->delete($this->table_indikator_tujuan);
		// }

		$id_tujuan_batchai = array();
		$id_indikator_batchai = array();
	 	foreach ($misi as $key => $value) {
	 		foreach ($id_tujuan_old[$key] as $key1 => $value1) {
	 			$id_tujuan_batchai[] = $value1;
	 		}
	 	}

		$id_indikator_batchai = array();
		foreach ($misi as $key => $value) {
	 		foreach ($tujuan[$key] as $key1 => $value1) {
				foreach ($id_indikator_old[$key][$key1] as $key2 => $value2) {
					$id_indikator_batchai[] = $value2;
				}
	 		}
	 	}

		if (!empty($id_indikator_batchai)) {
			$this->db->where_in('id', $id_indikator_batchai);
			$this->db->delete($this->table_indikator_tujuan);
		}

		if (!empty($id_tujuan_batchai)) {
			$this->db->where_in('id', $id_tujuan_batchai);
			$this->db->delete($this->table_tujuan);
		}

		if (!empty($id_misi_old)) {
			$this->db->where_in('id', $id_misi_old);
			$this->db->delete($this->table_misi);
		}



		$this->update_status_after_edit($id_renstra);

		$this->db->trans_complete();
		return $this->db->trans_status();
	}

	function get_all_sasaran($id_renstra, $id_tujuan=NULL, $with_satuan=FALSE){
		$this->db->select($this->table_sasaran.".*");
		$this->db->where('id_renstra', $id_renstra);
		if (!empty($id_tujuan)) {
			$this->db->where('id_tujuan', $id_tujuan);
		}
		$this->db->from($this->table_sasaran);

		// if ($with_satuan) {
		// 	$this->db->select("m_lov.nama_value");
		// 	$this->db->join("m_lov",$this->table_indikator_sasaran.".satuan_target = m_lov.kode_value AND kode_app='1'","inner");
		// }

		$result = $this->db->get();
		return $result->result();
	}

	function get_indikator_sasaran($id_sasaran, $return_result=TRUE, $satuan=FALSE){
		$this->db->where('id_sasaran', $id_sasaran);
		$this->db->from($this->table_indikator_sasaran);

		if ($satuan) {
			$this->db->select("m_lov.nama_value");
			$this->db->join("m_lov",$this->table_indikator_sasaran.".satuan_target = m_lov.kode_value AND kode_app='1'","inner");

		}

		$result = $this->db->get();
		if ($return_result) {
			return $result->result();
		}else{
			return $result;
		}
	}

	function get_one_sasaran($id_renstra=NULL, $id_tujuan=NULL, $id_sasaran){
		if (!empty($id_renstra)) {
			$this->db->where('id_renstra', $id_renstra);
		}

		if (!empty($id_tujuan)) {
			$this->db->where('id_tujuan', $id_tujuan);
		}

		$this->db->where('id', $id_sasaran);
		$this->db->from($this->table_sasaran);
		$result = $this->db->get();
		return $result->row();
	}

	function add_sasaran_skpd($data, $indikator, $satuan_target, $status_target, $kategori_target, $kondisi_awal, $target_1, $target_2, $target_3, $target_4, $target_5, $target_kondisi_akhir){
		$this->db->trans_strict(FALSE);
		$this->db->trans_start();

		$result = $this->db->insert($this->table_sasaran, $data);

		$id_sasaran = $this->db->insert_id();
		foreach ($indikator as $key => $value) {
			$this->db->insert($this->table_indikator_sasaran, array('id_sasaran' => $id_sasaran, 'indikator' => $value, 'satuan_target' => $satuan_target[$key], 'status_indikator' => $status_target[$key], 'kategori_indikator' => $kategori_target[$key],
			'kondisi_awal' => $kondisi_awal[$key], 'target_1' => $target_1[$key], 'target_2' => $target_2[$key], 'target_3' => $target_3[$key], 'target_4' => $target_4[$key], 'target_5' => $target_5[$key], 'kondisi_akhir' => $target_kondisi_akhir[$key]));
		}

		$this->db->trans_complete();
		return $this->db->trans_status();
	}

	function edit_sasaran_skpd($data, $id_sasaran, $indikator, $id_indikator_sasaran, $satuan_target, $status_target, $kategori_target, $kondisi_awal, $target_1, $target_2, $target_3, $target_4, $target_5, $target_kondisi_akhir){
		$this->db->trans_strict(FALSE);
		$this->db->trans_start();

		$this->db->where('id', $id_sasaran);
		$result = $this->db->update($this->table_sasaran, $data);

		foreach ($indikator as $key => $value) {
			if (!empty($id_indikator_sasaran[$key])) {
				$this->db->where('id', $id_indikator_sasaran[$key]);
				$this->db->where('id_sasaran', $id_sasaran);
				$this->db->update($this->table_indikator_sasaran, array('indikator' => $value, 'satuan_target' => $satuan_target[$key], 'status_indikator' => $status_target[$key], 'kategori_indikator' => $kategori_target[$key],
				'kondisi_awal' => $kondisi_awal[$key], 'target_1' => $target_1[$key], 'target_2' => $target_2[$key], 'target_3' => $target_3[$key], 'target_4' => $target_4[$key], 'target_5' => $target_5[$key], 'kondisi_akhir' => $target_kondisi_akhir[$key]));
				unset($id_indikator_sasaran[$key]);
			}else{
				$this->db->insert($this->table_indikator_sasaran, array('id_sasaran' => $id_sasaran, 'indikator' => $value, 'satuan_target' => $satuan_target[$key], 'status_indikator' => $status_target[$key], 'kategori_indikator' => $kategori_target[$key],
				'kondisi_awal' => $kondisi_awal[$key], 'target_1' => $target_1[$key], 'target_2' => $target_2[$key], 'target_3' => $target_3[$key], 'target_4' => $target_4[$key], 'target_5' => $target_5[$key], 'kondisi_akhir' => $target_kondisi_akhir[$key]));
			}
		}

		if (!empty($id_indikator_sasaran)) {
			$this->db->where_in('id', $id_indikator_sasaran);
			$this->db->delete($this->table_indikator_sasaran);
		}

		$renstra = $this->get_one_sasaran(NULL, NULL, $id_sasaran);
		$this->update_status_after_edit($renstra->id_renstra, $id_sasaran);

		$this->db->trans_complete();
		return $this->db->trans_status();
	}

	function delete_sasaran($id){
		$this->db->where('id', $id);
		$result = $this->db->delete($this->table_sasaran);
		return $result;
	}

	function get_all_program($id_renstra, $id_sasaran){
		$this->db->select($this->table_program_kegiatan.".*");
		$this->db->where('id_renstra', $id_renstra);
		$this->db->where('id_sasaran', $id_sasaran);
		$this->db->where('is_prog_or_keg', $this->is_program);
		$this->db->where('id_skpd >', 0);
		$this->db->from($this->table_program_kegiatan);
		$this->db->order_by('kd_urusan',"asc");
		$this->db->order_by('kd_bidang',"asc");
		$this->db->order_by('kd_program',"asc");

		$result = $this->db->get();
		return $result->result();
	}

	function get_all_program_sub_unit($id_renstra, $id_sasaran, $id_skpd){
		$query = "SELECT * FROM (`$this->table_program_kegiatan`)
						 WHERE id_renstra = '$id_renstra' AND id_sasaran='$id_sasaran'";
		if($this->session->userdata("id_skpd") > 100){
			$query = $query." AND id_skpd in (Select id_skpd from m_asisten_sekda where id_asisten='".$this->session->userdata("id_skpd") ."')";
		}else{
			$query = $query." AND id_skpd = '$id_skpd'";
		}
  	$query = $query." AND `is_prog_or_keg` = $this->is_program AND id_skpd > 0 
							ORDER BY `kd_urusan` asc, `kd_bidang` asc, `kd_program` asc";
		
		$result = $this->db->query($query);
		//print_r($this->db->last_query());
		//exit();
		return $result->result();
	}

	function get_indikator_prog_keg_status_kat($id){
		$query = "SELECT t_renstra_indikator_prog_keg.*, m_status_indikator.`nama_status_indikator` , m_kategori_indikator.`nama_kategori_indikator` , satuan_target as nama_value
							FROM t_renstra_indikator_prog_keg
							INNER JOIN m_status_indikator ON m_status_indikator.`kode_status_indikator` = t_renstra_indikator_prog_keg.`kode_positif_negatif`
							INNER JOIN m_kategori_indikator ON m_kategori_indikator.`kode_kategori_indikator` = t_renstra_indikator_prog_keg.`kode_kategori_indikator`

							 WHERE id_prog_keg = '$id'";
		$result = $this->db->query($query);
		return $result->result();

	}

	function get_indikator_prog_keg($id, $return_result=TRUE, $satuan=FALSE){
		$this->db->select($this->table_indikator_program.".*");
		$this->db->where('id_prog_keg', $id);
		$this->db->from($this->table_indikator_program);

		if ($satuan) {
			$this->db->select("m_lov.nama_value");
			$this->db->join("m_lov",$this->table_indikator_program.".satuan_target = m_lov.kode_value AND kode_app='1'","inner");
		}
		// print_r($this->db->last_query());
		$result = $this->db->get();
		if ($return_result) {
			return $result->result();
		}else{
			return $result;
		}
	}

	function get_one_program($id_renstra=NULL, $id_sasaran=NULL, $id_program, $detail=FALSE){
		if (!empty($id_renstra)) {
			$this->db->where($this->table_program_kegiatan.'.id_renstra', $id_renstra);
		}

		if (!empty($id_sasaran)) {
			$this->db->where($this->table_program_kegiatan.'.id_sasaran', $id_sasaran);
		}

		if ($detail) {
			$this->db->select($this->table_program_kegiatan.".*");
			$this->db->select("nama_skpd");
			$this->db->select("tujuan");
			$this->db->select("sasaran");

			$this->db->join($this->table, $this->table_program_kegiatan.".id_renstra = ".$this->table.".id","inner");
			$this->db->join("m_skpd", $this->table.".id_skpd = m_skpd.id_skpd","inner");
			$this->db->join($this->table_sasaran, $this->table_program_kegiatan.".id_sasaran = ".$this->table_sasaran.".id","inner");
			$this->db->join($this->table_tujuan, $this->table_sasaran.".id_tujuan = ".$this->table_tujuan.".id","inner");

			$this->db->select("m_urusan.Nm_Urusan");
			$this->db->select("m_bidang.Nm_Bidang");
			$this->db->select("m_program.Ket_Program");
			$this->db->join("m_urusan",$this->table_program_kegiatan.".kd_urusan = m_urusan.Kd_Urusan","inner");
			$this->db->join("m_bidang",$this->table_program_kegiatan.".kd_urusan = m_bidang.Kd_Urusan AND ".$this->table_program_kegiatan.".kd_bidang = m_bidang.Kd_Bidang","inner");
			$this->db->join("m_program",$this->table_program_kegiatan.".kd_urusan = m_program.Kd_Urusan AND ".$this->table_program_kegiatan.".kd_bidang = m_program.Kd_Bidang AND ".$this->table_program_kegiatan.".kd_program = m_program.Kd_Prog","inner");
		}

		$this->db->where($this->table_program_kegiatan.'.id', $id_program);
		$this->db->from($this->table_program_kegiatan);
		$result = $this->db->get();
		return $result->row();
	}

	function get_info_tujuan_n_sasaran_n_program($id_tujuan=NULL, $id_sasaran=NULL, $id_program=NULL){
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
		if (!empty($id_program)) {
			$this->db->select($this->table_program_kegiatan.".kd_urusan");
			$this->db->select($this->table_program_kegiatan.".kd_bidang");
			$this->db->select($this->table_program_kegiatan.".kd_program");
			$this->db->select($this->table_program_kegiatan.".nama_prog_or_keg");
			$this->db->join($this->table_program_kegiatan,$this->table_program_kegiatan.".id_sasaran = ". $this->table_sasaran .".id","inner");
			$this->db->where($this->table_program_kegiatan.'.id', $id_program);
		}

		$result = $this->db->get();
		return $result->row();
	}

	function add_program_skpd($data, $indikator, $satuan_target, $status_target, $kategori_target, $kondisi_awal, $target_1, $target_2, $target_3, $target_4, $target_5, $target_kondisi_akhir){
		$this->db->trans_strict(FALSE);
		$this->db->trans_start();

		$add = array('is_prog_or_keg'=> $this->is_program, 'id_skpd' => $this->session->userdata('id_skpd'));
		$data = $this->global_function->add_array($data, $add);

		$this->db->insert($this->table_program_kegiatan, $data);

		$id = $this->db->insert_id();
		foreach ($indikator as $key => $value) {
			$this->db->insert($this->table_indikator_program, array('id_prog_keg' => $id, 'indikator' => $value, 'satuan_target' => $satuan_target[$key], 'kode_positif_negatif' => $status_target[$key], 'kode_kategori_indikator' => $kategori_target[$key],
			'kondisi_awal' => $kondisi_awal[$key], 'target_1' => $target_1[$key], 'target_2' => $target_2[$key], 'target_3' => $target_3[$key], 'target_4' => $target_4[$key], 'target_5' => $target_5[$key], 'target_kondisi_akhir' => $target_kondisi_akhir[$key]));
		}

		$this->db->trans_complete();
		return $this->db->trans_status();
	}

	function edit_program_skpd($data, $id_program, $indikator, $id_indikator_program, $satuan_target, $status_target, $kategori_target, $kondisi_awal, $target_1, $target_2, $target_3, $target_4, $target_5, $target_kondisi_akhir){

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
				$this->db->update($this->table_indikator_program, array('indikator' => $value, 'satuan_target' => $satuan_target[$key], 'kode_positif_negatif' => $status_target[$key], 'kode_kategori_indikator' => $kategori_target[$key],
				'kondisi_awal' => $kondisi_awal[$key], 'target_1' => $target_1[$key], 'target_2' => $target_2[$key], 'target_3' => $target_3[$key], 'target_4' => $target_4[$key], 'target_5' => $target_5[$key], 'target_kondisi_akhir' => $target_kondisi_akhir[$key]));
				unset($id_indikator_program[$key]);
			}else{
				$this->db->insert($this->table_indikator_program, array('id_prog_keg' => $id_program, 'indikator' => $value, 'satuan_target' => $satuan_target[$key], 'kode_positif_negatif' => $status_target[$key], 'kode_kategori_indikator' => $kategori_target[$key],
				'kondisi_awal' => $kondisi_awal[$key], 'target_1' => $target_1[$key], 'target_2' => $target_2[$key], 'target_3' => $target_3[$key], 'target_4' => $target_4[$key], 'target_5' => $target_5[$key], 'target_kondisi_akhir' => $target_kondisi_akhir[$key]));
			}
		}

		if (!empty($id_indikator_program)) {
			$this->db->where_in('id', $id_indikator_program);
			$this->db->delete($this->table_indikator_program);
		}

		$renstra = $this->get_one_program(NULL, NULL, $id_program);
		$this->update_status_after_edit($renstra->id_renstra, NULL, $id_program);

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

	function get_all_kegiatan_sub_unit($id_renstra, $id_sasaran, $id_program, $id_skpd){
		$query = "SELECT * FROM (`$this->table_program_kegiatan`)
						 WHERE id_renstra = '$id_renstra' AND id_sasaran='$id_sasaran' AND parent='$id_program'";
		if($this->session->userdata("id_skpd") > 100){
			$query = $query." AND id_skpd in (Select id_skpd from m_asisten_sekda where id_asisten='".$this->session->userdata("id_skpd") ."')";
		}else{
			$query = $query." AND id_skpd = '$id_skpd'";
		}
		$query = $query." AND `is_prog_or_keg` = $this->is_kegiatan AND id_skpd > 0 
							ORDER BY `kd_urusan` asc, `kd_bidang` asc, `kd_program` asc, kd_kegiatan asc";
		$result = $this->db->query($query);
		return $result->result();
	}

	function get_all_kegiatan($id_renstra, $id_sasaran, $id_program, $with_satuan){
		$this->db->select($this->table_program_kegiatan.".*");
		$this->db->where('id_renstra', $id_renstra);
		$this->db->where('id_sasaran', $id_sasaran);
		$this->db->where('parent', $id_program);
		$this->db->where('is_prog_or_keg', $this->is_kegiatan);
		$this->db->where('id_skpd >', 0);
		$this->db->from($this->table_program_kegiatan);
		$this->db->order_by('kd_urusan',"asc");
		$this->db->order_by('kd_bidang',"asc");
		$this->db->order_by('kd_program',"asc");
		$this->db->order_by('kd_kegiatan',"asc");

		if ($with_satuan) {
			$this->db->select("m_lov.nama_value");
			$this->db->join("m_lov",$this->table_program_kegiatan.".satuan_target = m_lov.kode_value AND kode_app='1'","inner");
		}

		$result = $this->db->get();
		return $result->result();
	}

	function add_kegiatan_skpd($data, $indikator, $satuan_target, $status_target, $kategori_target, $kondisi_awal, $target_1, $target_2, $target_3, $target_4, $target_5, $target_kondisi_akhir){
			$this->db->trans_strict(FALSE);
			$this->db->trans_start();

		$KodeUrusan = $data['kd_urusan'];
		$KodeBidang = $data['kd_bidang'];
		$IdRenstra = $data['id_renstra'];
		$KodeKegiatan = $data['kd_kegiatan'];
		$created_date =  date("d-m-Y_H-i-s");


			 $add = array('is_prog_or_keg'=> $this->is_kegiatan, 'id_status'=> $this->id_status_baru, 'id_skpd' => $this->session->userdata('id_skpd'));
			$data = $this->global_function->add_array($data, $add);
		$this->db->insert($this->table_program_kegiatan, $data);

			$id = $this->db->insert_id();


			foreach ($indikator as $key => $value) {
				$this->db->insert($this->table_indikator_program, array('id_prog_keg' => $id, 'indikator' => $value, 'satuan_target' => $satuan_target[$key], 'kode_positif_negatif' => $status_target[$key], 'kode_kategori_indikator' => $kategori_target[$key],
				'kondisi_awal' => $kondisi_awal[$key], 'target_1' => $target_1[$key], 'target_2' => $target_2[$key], 'target_3' => $target_3[$key], 'target_4' => $target_4[$key], 'target_5' => $target_5[$key], 'target_kondisi_akhir' => $target_kondisi_akhir[$key]));
			}

			// $th_anggaran = $this->m_settings->get_tahun_anggaran_db();

			// $banyakData = count($tahun1['kode_sumber_dana']);
			// for($i =1; $i <= $banyakData; ++$i) {

	  //   		$datatahun1_batch[] =
			// 	 array('id_renstra' => $IdRenstra,
			// 	 		'tahun'=>$th_anggaran[0]->tahun_anggaran,
			// 	 		'kode_urusan'=>$KodeUrusan,
			// 	 		'kode_bidang' => $KodeBidang,
			// 	 		'kode_program' => $kodeProgram,
			// 	 		'id_kegiatan' => $id,
			// 	 		'kode_kegiatan' => $KodeKegiatan,
			// 			 'kode_sumber_dana' => $tahun1['kode_sumber_dana'][$i],
			// 			 'kode_jenis_belanja' => $tahun1['kode_jenis_belanja'][$i],
			// 			 'kode_kategori_belanja' => $tahun1['kode_kategori_belanja'][$i],
			// 			 'kode_sub_kategori_belanja' => $tahun1['kode_sub_kategori_belanja'][$i],
			// 			 'kode_belanja' => $tahun1['kode_belanja'][$i],
			// 			 'uraian_belanja' => $tahun1['uraian_belanja'][$i],
			// 			 'detil_uraian_belanja' => $tahun1['detil_uraian_belanja'][$i],
			// 			 'volume' => $tahun1['volume'][$i],
			// 			 'nominal_satuan' => $tahun1['nominal_satuan'][$i],
			// 			 'satuan' => $tahun1['satuan'][$i],
			// 			 'subtotal' => $tahun1['subtotal'][$i],
			// 			 'created_date' => $created_date
			// 			)	;
			// }


			// $banyakData2 = count($tahun2['kode_sumber_dana']);
			// for($i =1; $i <= $banyakData2; ++$i) {
	  //   		$datatahun2_batch[] =
			// 	 array('id_renstra' => $IdRenstra,
			// 	 		'tahun'=>$th_anggaran[1]->tahun_anggaran,
			// 	 		'kode_urusan'=>$KodeUrusan,
			// 	 		'kode_bidang' => $KodeBidang,
			// 	 		'kode_program' => $kodeProgram,
			// 	 		'id_kegiatan' => $id,
			// 	 		'kode_kegiatan' => $KodeKegiatan,
			// 			 'kode_sumber_dana' => $tahun2['kode_sumber_dana'][$i],
			// 			 'kode_jenis_belanja' => $tahun2['kode_jenis_belanja'][$i],
			// 			 'kode_kategori_belanja' => $tahun2['kode_kategori_belanja'][$i],
			// 			 'kode_sub_kategori_belanja' => $tahun2['kode_sub_kategori_belanja'][$i],
			// 			 'kode_belanja' => $tahun2['kode_belanja'][$i],
			// 			 'uraian_belanja' => $tahun2['uraian_belanja'][$i],
			// 			 'detil_uraian_belanja' => $tahun2['detil_uraian_belanja'][$i],
			// 			 'volume' => $tahun2['volume'][$i],
			// 			 'nominal_satuan' => $tahun2['nominal_satuan'][$i],
			// 			  'satuan' => $tahun2['satuan'][$i],
			// 			 'subtotal' => $tahun2['subtotal'][$i],
			// 			  'created_date' => $created_date
			// 			)	;
			// }

			// $banyakData3 = count($tahun3['kode_sumber_dana']);
			// for($i =1; $i <= $banyakData3; ++$i) {
	  //   		$datatahun3_batch[] =
			// 	 array('id_renstra' => $IdRenstra,
			// 	 		'tahun'=>$th_anggaran[2]->tahun_anggaran,
			// 	 		'kode_urusan'=>$KodeUrusan,
			// 	 		'kode_bidang' => $KodeBidang,
			// 	 		'kode_program' => $kodeProgram,
			// 	 		'id_kegiatan' => $id,
			// 	 		'kode_kegiatan' => $KodeKegiatan,
			// 			 'kode_sumber_dana' => $tahun3['kode_sumber_dana'][$i],
			// 			 'kode_jenis_belanja' => $tahun3['kode_jenis_belanja'][$i],
			// 			 'kode_kategori_belanja' => $tahun3['kode_kategori_belanja'][$i],
			// 			 'kode_sub_kategori_belanja' => $tahun3['kode_sub_kategori_belanja'][$i],
			// 			 'kode_belanja' => $tahun3['kode_belanja'][$i],
			// 			 'uraian_belanja' => $tahun3['uraian_belanja'][$i],
			// 			 'detil_uraian_belanja' => $tahun3['detil_uraian_belanja'][$i],
			// 			 'volume' => $tahun3['volume'][$i],
			// 			 'nominal_satuan' => $tahun3['nominal_satuan'][$i],
			// 			  'satuan' => $tahun3['satuan'][$i],
			// 			 'subtotal' => $tahun3['subtotal'][$i],
			// 			  'created_date' => $created_date
			// 			)	;
			// }

			// 	$banyakData4 = count($tahun4['kode_sumber_dana']);

			// 	for($i =1; $i <= $banyakData4; ++$i) {
	  //   		$datatahun4_batch[] =
			// 	 array('id_renstra' => $IdRenstra,
			// 	 		'tahun'=>$th_anggaran[3]->tahun_anggaran,
			// 	 		'kode_urusan'=>$KodeUrusan,
			// 	 		'kode_bidang' => $KodeBidang,
			// 	 		'kode_program' => $kodeProgram,
			// 	 		'id_kegiatan' => $id,
			// 	 		'kode_kegiatan' => $KodeKegiatan,
			// 			 'kode_sumber_dana' => $tahun4['kode_sumber_dana'][$i],
			// 			 'kode_jenis_belanja' => $tahun4['kode_jenis_belanja'][$i],
			// 			 'kode_kategori_belanja' => $tahun4['kode_kategori_belanja'][$i],
			// 			 'kode_sub_kategori_belanja' => $tahun4['kode_sub_kategori_belanja'][$i],
			// 			 'kode_belanja' => $tahun4['kode_belanja'][$i],
			// 			 'uraian_belanja' => $tahun4['uraian_belanja'][$i],
			// 			 'detil_uraian_belanja' => $tahun4['detil_uraian_belanja'][$i],
			// 			 'volume' => $tahun4['volume'][$i],
			// 			 'nominal_satuan' => $tahun4['nominal_satuan'][$i],
			// 			  'satuan' => $tahun4['satuan'][$i],
			// 			 'subtotal' => $tahun4['subtotal'][$i],
			// 			  'created_date' => $created_date
			// 			)	;
			// }

			// $banyakData5 = count($tahun5['kode_sumber_dana']);
			// 	for($i =1; $i <= $banyakData4; ++$i) {
	  //   		$datatahun5_batch[] =
			// 	 array('id_renstra' => $IdRenstra,
			// 	 		'tahun'=>$th_anggaran[4]->tahun_anggaran,
			// 	 		'kode_urusan'=>$KodeUrusan,
			// 	 		'kode_bidang' => $KodeBidang,
			// 	 		'kode_program' => $kodeProgram,
			// 	 		'id_kegiatan' => $id,
			// 	 		'kode_kegiatan' => $KodeKegiatan,
			// 			 'kode_sumber_dana' => $tahun5['kode_sumber_dana'][$i],
			// 			 'kode_jenis_belanja' => $tahun5['kode_jenis_belanja'][$i],
			// 			 'kode_kategori_belanja' => $tahun5['kode_kategori_belanja'][$i],
			// 			 'kode_sub_kategori_belanja' => $tahun5['kode_sub_kategori_belanja'][$i],
			// 			 'kode_belanja' => $tahun5['kode_belanja'][$i],
			// 			 'uraian_belanja' => $tahun5['uraian_belanja'][$i],
			// 			 'detil_uraian_belanja' => $tahun5['detil_uraian_belanja'][$i],
			// 			 'volume' => $tahun5['volume'][$i],
			// 			 'nominal_satuan' => $tahun5['nominal_satuan'][$i],
			// 			  'satuan' => $tahun1['satuan'][$i],
			// 			 'subtotal' => $tahun5['subtotal'][$i],
			// 			  'created_date' => $created_date
			// 			);
			// }



			// 	$this->db->insert_batch( 't_renstra_belanja_kegiatan', $datatahun1_batch);


			// 	$this->db->insert_batch( 't_renstra_belanja_kegiatan', $datatahun2_batch);

			// 	$this->db->insert_batch( 't_renstra_belanja_kegiatan', $datatahun3_batch);

			// 	$this->db->insert_batch( 't_renstra_belanja_kegiatan', $datatahun4_batch);

			// 	$this->db->insert_batch( 't_renstra_belanja_kegiatan', $datatahun5_batch);



	//print_r($data);
	//exit();



			$this->db->trans_complete();
			return $this->db->trans_status();
		}

		function edit_kegiatan_skpd($data, $id_kegiatan, $indikator, $id_indikator_kegiatan, $satuan_target, $status_target, $kategori_target, $kondisi_awal, $target_1, $target_2, $target_3, $target_4, $target_5, $target_kondisi_akhir){
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
						$this->db->update($this->table_indikator_program, array('indikator' => $value, 'satuan_target' => $satuan_target[$key], 'kode_positif_negatif' => $status_target[$key], 'kode_kategori_indikator' => $kategori_target[$key],
						'kondisi_awal' => $kondisi_awal[$key], 'target_1' => $target_1[$key], 'target_2' => $target_2[$key], 'target_3' => $target_3[$key], 'target_4' => $target_4[$key], 'target_5' => $target_5[$key], 'target_kondisi_akhir' => $target_kondisi_akhir[$key]));
						unset($id_indikator_kegiatan[$key]);
					}else{
						$this->db->insert($this->table_indikator_program, array('id_prog_keg' => $id_kegiatan, 'indikator' => $value, 'satuan_target' => $satuan_target[$key], 'kode_positif_negatif' => $status_target[$key], 'kode_kategori_indikator' => $kategori_target[$key],
						'kondisi_awal' => $kondisi_awal[$key], 'target_1' => $target_1[$key], 'target_2' => $target_2[$key], 'target_3' => $target_3[$key], 'target_4' => $target_4[$key], 'target_5' => $target_5[$key], 'target_kondisi_akhir' => $target_kondisi_akhir[$key]));
					}
				}

				if (!empty($id_indikator_kegiatan)) {
					$this->db->where_in('id', $id_indikator_kegiatan);
					$this->db->delete($this->table_indikator_program);
				}

				$renstra = $this->get_one_kegiatan(NULL, NULL, NULL, $id_kegiatan);
				$this->update_status_after_edit($renstra->id_renstra, NULL, NULL, $id_kegiatan);



			// $KodeUrusan = $data['kd_urusan'];
			// $KodeBidang = $data['kd_bidang'];
			// $IdRenstra = $data['id_renstra'];
			// $KodeKegiatan = $data['kd_kegiatan'];
			// $created_date =  date("d-m-Y_H-i-s");

			// 	$this->db->query("delete from  t_renstra_belanja_kegiatan where id_kegiatan = $id_kegiatan ");

			// 	$th_anggaran = $this->m_settings->get_tahun_anggaran_db();


			// 	$banyakData = count($tahun1['kode_sumber_dana']);
			// 	for($i =1; $i <= $banyakData; ++$i) {
		 //    		$datatahun1_batch[] =
			// 		 array('id_renstra' => $IdRenstra,
			// 		 		'tahun'=>$th_anggaran[0]->tahun_anggaran,
			// 		 		'kode_urusan'=>$KodeUrusan,
			// 		 		'kode_bidang' => $KodeBidang,
			// 		 		'kode_program' => $kodeProgram,
			// 		 		'id_kegiatan' => $id_kegiatan,
			// 		 		'kode_kegiatan' => $KodeKegiatan,
			// 				 'kode_sumber_dana' => $tahun1['kode_sumber_dana'][$i],
			// 				 'kode_jenis_belanja' => $tahun1['kode_jenis_belanja'][$i],
			// 				 'kode_kategori_belanja' => $tahun1['kode_kategori_belanja'][$i],
			// 				 'kode_sub_kategori_belanja' => $tahun1['kode_sub_kategori_belanja'][$i],
			// 				 'kode_belanja' => $tahun1['kode_belanja'][$i],
			// 				 'uraian_belanja' => $tahun1['uraian_belanja'][$i],
			// 				 'detil_uraian_belanja' => $tahun1['detil_uraian_belanja'][$i],
			// 				 'volume' => $tahun1['volume'][$i],
			// 				 'nominal_satuan' => $tahun1['nominal_satuan'][$i],
			// 				 'satuan' => $tahun1['satuan'][$i],
			// 				 'subtotal' => $tahun1['subtotal'][$i],
			// 				  'created_date' => $created_date
			// 				)	;
			// 	}

			// 	$banyakData2 = count($tahun2['kode_sumber_dana']);
			// 	for($i =1; $i <= $banyakData2; ++$i) {
		 //    		$datatahun2_batch[] =
			// 		 array('id_renstra' => $IdRenstra,
			// 		 		'tahun'=>$th_anggaran[1]->tahun_anggaran,
			// 		 		'kode_urusan'=>$KodeUrusan,
			// 		 		'kode_bidang' => $KodeBidang,
			// 		 		'kode_program' => $kodeProgram,
			// 		 		'id_kegiatan' => $id_kegiatan,
			// 		 		'kode_kegiatan' => $KodeKegiatan,
			// 				 'kode_sumber_dana' => $tahun2['kode_sumber_dana'][$i],
			// 				 'kode_jenis_belanja' => $tahun2['kode_jenis_belanja'][$i],
			// 				 'kode_kategori_belanja' => $tahun2['kode_kategori_belanja'][$i],
			// 				 'kode_sub_kategori_belanja' => $tahun2['kode_sub_kategori_belanja'][$i],
			// 				 'kode_belanja' => $tahun2['kode_belanja'][$i],
			// 				 'uraian_belanja' => $tahun2['uraian_belanja'][$i],
			// 				 'detil_uraian_belanja' => $tahun2['detil_uraian_belanja'][$i],
			// 				 'volume' => $tahun2['volume'][$i],
			// 				 'nominal_satuan' => $tahun2['nominal_satuan'][$i],
			// 				  'satuan' => $tahun2['satuan'][$i],
			// 				 'subtotal' => $tahun2['subtotal'][$i],
			// 				  'created_date' => $created_date
			// 				)	;
			// 	}

			// 	$banyakData3 = count($tahun3['kode_sumber_dana']);
			// 	for($i =1; $i <= $banyakData3; ++$i) {
		 //    		$datatahun3_batch[] =
			// 		 array('id_renstra' => $IdRenstra,
			// 		 		'tahun'=>$th_anggaran[2]->tahun_anggaran,
			// 		 		'kode_urusan'=>$KodeUrusan,
			// 		 		'kode_bidang' => $KodeBidang,
			// 		 		'kode_program' => $kodeProgram,
			// 		 		'id_kegiatan' => $id_kegiatan,
			// 		 		'kode_kegiatan' => $KodeKegiatan,
			// 				 'kode_sumber_dana' => $tahun3['kode_sumber_dana'][$i],
			// 				 'kode_jenis_belanja' => $tahun3['kode_jenis_belanja'][$i],
			// 				 'kode_kategori_belanja' => $tahun3['kode_kategori_belanja'][$i],
			// 				 'kode_sub_kategori_belanja' => $tahun3['kode_sub_kategori_belanja'][$i],
			// 				 'kode_belanja' => $tahun3['kode_belanja'][$i],
			// 				 'uraian_belanja' => $tahun3['uraian_belanja'][$i],
			// 				 'detil_uraian_belanja' => $tahun3['detil_uraian_belanja'][$i],
			// 				 'volume' => $tahun3['volume'][$i],
			// 				 'nominal_satuan' => $tahun3['nominal_satuan'][$i],
			// 				  'satuan' => $tahun3['satuan'][$i],
			// 				 'subtotal' => $tahun3['subtotal'][$i],
			// 				  'created_date' => $created_date
			// 				)	;
			// 	}

			// 		$banyakData4 = count($tahun4['kode_sumber_dana']);
			// 	//print_r($banyakData4);
			// 		for($i =1; $i <= $banyakData4; ++$i) {
		 //    		$datatahun4_batch[] =
			// 		 array('id_renstra' => $IdRenstra,
			// 		 		'tahun'=>$th_anggaran[3]->tahun_anggaran,
			// 		 		'kode_urusan'=>$KodeUrusan,
			// 		 		'kode_bidang' => $KodeBidang,
			// 		 		'kode_program' => $kodeProgram,
			// 		 		'id_kegiatan' => $id_kegiatan,
			// 		 		'kode_kegiatan' => $KodeKegiatan,
			// 				 'kode_sumber_dana' => $tahun4['kode_sumber_dana'][$i],
			// 				 'kode_jenis_belanja' => $tahun4['kode_jenis_belanja'][$i],
			// 				 'kode_kategori_belanja' => $tahun4['kode_kategori_belanja'][$i],
			// 				 'kode_sub_kategori_belanja' => $tahun4['kode_sub_kategori_belanja'][$i],
			// 				 'kode_belanja' => $tahun4['kode_belanja'][$i],
			// 				 'uraian_belanja' => $tahun4['uraian_belanja'][$i],
			// 				 'detil_uraian_belanja' => $tahun4['detil_uraian_belanja'][$i],
			// 				 'volume' => $tahun4['volume'][$i],
			// 				 'nominal_satuan' => $tahun4['nominal_satuan'][$i],
			// 				  'satuan' => $tahun4['satuan'][$i],
			// 				 'subtotal' => $tahun4['subtotal'][$i],
			// 				  'created_date' => $created_date
			// 				)	;
			// 	}
			// //	print_r($datatahun4_batch);

			// 	$banyakData5 = count($tahun5['kode_sumber_dana']);
			// 		for($i =1; $i <= $banyakData5; ++$i) {
		 //    		$datatahun5_batch[] =
			// 		 array('id_renstra' => $IdRenstra,
			// 		 		'tahun'=>$th_anggaran[4]->tahun_anggaran,
			// 		 		'kode_urusan'=>$KodeUrusan,
			// 		 		'kode_bidang' => $KodeBidang,
			// 		 		'id_kegiatan' => $id_kegiatan,
			// 		 		'kode_program' => $kodeProgram,
			// 		 		'kode_kegiatan' => $KodeKegiatan,
			// 				 'kode_sumber_dana' => $tahun5['kode_sumber_dana'][$i],
			// 				 'kode_jenis_belanja' => $tahun5['kode_jenis_belanja'][$i],
			// 				 'kode_kategori_belanja' => $tahun5['kode_kategori_belanja'][$i],
			// 				 'kode_sub_kategori_belanja' => $tahun5['kode_sub_kategori_belanja'][$i],
			// 				 'kode_belanja' => $tahun5['kode_belanja'][$i],
			// 				 'uraian_belanja' => $tahun5['uraian_belanja'][$i],
			// 				 'detil_uraian_belanja' => $tahun5['detil_uraian_belanja'][$i],
			// 				 'volume' => $tahun5['volume'][$i],
			// 				 'nominal_satuan' => $tahun5['nominal_satuan'][$i],
			// 				  'satuan' => $tahun1['satuan'][$i],
			// 				 'subtotal' => $tahun5['subtotal'][$i],
			// 				  'created_date' => $created_date
			// 				);
			// 	}


			// 		$this->db->insert_batch( 't_renstra_belanja_kegiatan', $datatahun1_batch);


			// 		$this->db->insert_batch( 't_renstra_belanja_kegiatan', $datatahun2_batch);

			// 		$this->db->insert_batch( 't_renstra_belanja_kegiatan', $datatahun3_batch);

			// 		$this->db->insert_batch( 't_renstra_belanja_kegiatan', $datatahun4_batch);

			// 		$this->db->insert_batch( 't_renstra_belanja_kegiatan', $datatahun5_batch);

				$this->db->trans_complete();
				return $this->db->trans_status();
			}

	function add_belanja_kegiatan($data, $id_belanja){
		if (!empty($id_belanja)) {
			$this->db->where('id', $id_belanja);
			$this->db->update('t_renstra_belanja_kegiatan', $data);
		}else{
			$this->db->insert('t_renstra_belanja_kegiatan', $data);
		}
	}

	function delete_kegiatan($id){
		$this->db->where('id', $id);
		$this->db->where('is_prog_or_keg', $this->is_kegiatan);
		$result = $this->db->delete($this->table_program_kegiatan);
		return $result;
	}

	function get_one_kegiatan($id_renstra=NULL, $id_sasaran=NULL, $id_program=NULL, $id_kegiatan, $detail=FALSE){
		if (!empty($id_renstra)) {
			$this->db->where($this->table_program_kegiatan.'.id_renstra', $id_renstra);
		}

		if (!empty($id_sasaran)) {
			$this->db->where($this->table_program_kegiatan.'.id_sasaran', $id_sasaran);
		}

		if (!empty($id_program)) {
			$this->db->where('parent', $id_program);
		}

		if ($detail) {
			$this->db->select($this->table_program_kegiatan.".*");
			$this->db->select("nama_skpd");
			$this->db->select("tujuan");
			$this->db->select("sasaran");

			$this->db->join($this->table, $this->table_program_kegiatan.".id_renstra = ".$this->table.".id","inner");
			$this->db->join("m_skpd", $this->table.".id_skpd = m_skpd.id_skpd","inner");
			$this->db->join($this->table_sasaran, $this->table_program_kegiatan.".id_sasaran = ".$this->table_sasaran.".id","inner");
			$this->db->join($this->table_tujuan, $this->table_sasaran.".id_tujuan = ".$this->table_tujuan.".id","inner");

			$this->db->select("m_urusan.Nm_Urusan");
			$this->db->select("m_bidang.Nm_Bidang");
			$this->db->select("m_program.Ket_Program");
			$this->db->join("m_urusan",$this->table_program_kegiatan.".kd_urusan = m_urusan.Kd_Urusan","inner");
			$this->db->join("m_bidang",$this->table_program_kegiatan.".kd_urusan = m_bidang.Kd_Urusan AND ".$this->table_program_kegiatan.".kd_bidang = m_bidang.Kd_Bidang","inner");
			$this->db->join("m_program",$this->table_program_kegiatan.".kd_urusan = m_program.Kd_Urusan AND ".$this->table_program_kegiatan.".kd_bidang = m_program.Kd_Bidang AND ".$this->table_program_kegiatan.".kd_program = m_program.Kd_Prog","inner");
		}

		$this->db->where($this->table_program_kegiatan.'.id', $id_kegiatan);
		$this->db->from($this->table_program_kegiatan);
		$result = $this->db->get();
		return $result->row();
	}

	function kirim_renstra($id_skpd){
		$proses = $this->cek_proses(NULL, $id_skpd);

		if (!empty($proses->proses2) && empty($proses->proses1)) {
			$id_status_data = $this->id_status_approved;
		}else{
			$id_status_data = $this->id_status_send;
		}


		$this->db->where($this->table.".id_skpd", $id_skpd);
		$this->db->where($this->table_program_kegiatan.".id_status !=", $this->id_status_approved);
		$return = $this->db->update($this->table_program_kegiatan." INNER JOIN ". $this->table ." ON ". $this->table_program_kegiatan .".id_renstra=". $this->table .".id", array($this->table_program_kegiatan.'.id_status'=>$id_status_data));
		return $return;
	}

	function count_jendela_kontrol($id_skpd){
		$skpd_search="";

		if ($id_skpd!='all') {
			//CEK ID SKPD LOGIN
			if($this->session->userdata("id_skpd") > 100){
				$id_skpdLogin = $this->m_skpd->get_kode_unit_dari_asisten($this->session->userdata("id_skpd"));
			}else {
				$id_skpdLogin = $this->session->userdata("id_skpd");
			}
			$skpd_search="WHERE t_renstra.id_skpd='". $id_skpd ."'";
			if($id_skpd != $id_skpdLogin){
				if($this->session->userdata("id_skpd") > 100){
						$skpd_search = $skpd_search." AND t_renstra_prog_keg.id_skpd in (Select id_skpd from m_asisten_sekda where id_asisten='".$this->session->userdata("id_skpd") ."')";
				}else{
						$skpd_search = $skpd_search." AND (t_renstra_prog_keg.id_skpd=? OR t_renstra_prog_keg.id_skpd='$id_skpd')";
				}
			}
		}


		// $query = "SELECT SUM(IF(t_renstra_prog_keg.id_status=?, 1, 0)) as baru, SUM(IF(t_renstra_prog_keg.id_status>=?, 1, 0)) as kirim, SUM(IF(t_renstra_prog_keg.id_status>?, 1, 0)) as proses, SUM(IF((t_renstra_prog_keg.id_status=? OR t_renstra_prog_keg.id_status=?), 1, 0)) as revisi, SUM(IF(t_renstra_prog_keg.id_status=? OR t_renstra_prog_keg.id_status=?, 1, 0)) as veri, SUM(IF((t_renstra_prog_keg.id_status>=? AND t_renstra_prog_keg.id_status<=?), 1, 0)) as proses1, SUM(IF((t_renstra_prog_keg.id_status>=? AND t_renstra_prog_keg.id_status<=?), 1, 0)) as proses2, SUM(IF(t_renstra_prog_keg.id_status=?, 1, 0)) as baru2 FROM t_renstra_prog_keg INNER JOIN t_renstra ON t_renstra_prog_keg.id_renstra=t_renstra.id WHERE t_renstra.id_skpd=? AND is_prog_or_keg=?";

		$query = "SELECT
						SUM(IF(t_renstra_prog_keg.id_status=?, 1, 0)) as baru,
						SUM(IF(t_renstra_prog_keg.id_status>=?, 1, 0)) as kirim,
						SUM(IF(t_renstra_prog_keg.id_status>?, 1, 0)) as proses,
						SUM(IF(t_renstra_prog_keg.id_status=?, 1, 0)) as revisi,
						SUM(IF(t_renstra_prog_keg.id_status>=?, 1, 0)) as veri,
						SUM(IF(t_renstra_prog_keg.id_status=?, 1, 0)) as baru2,
						SUM(IF(t_renstra_prog_keg.id_status>=?, 1, 0)) as kirim2,
						SUM(IF(t_renstra_prog_keg.id_status>?, 1, 0)) as proses2,
						SUM(IF(t_renstra_prog_keg.id_status=?, 1, 0)) as revisi2,
						SUM(IF(t_renstra_prog_keg.id_status>=?, 1, 0)) as veri2
					FROM
						t_renstra_prog_keg
					INNER JOIN
						t_renstra ON t_renstra_prog_keg.id_renstra=t_renstra.id
					".$skpd_search;


		$data = array(
					$this->id_status_baru,
					$this->id_status_send,
					$this->id_status_send,
					$this->id_status_revisi,
					$this->id_status_approved,
					$this->id_status_baru2,
					$this->id_status_approved,
					$this->id_status_baru2,
					$this->id_status_revisi2,
					$this->id_status_approved2,
					$id_skpdLogin);

		$result = $this->db->query($query, $data);
		// print_r($this->db->last_query());
		// exit;
		return $result->row();
	}

	function get_all_renstra($search, $start, $length, $order, $id_skpd, $order_arr, $status = NULL, $detail = FALSE){
		$this->db->select($this->table_program_kegiatan.".*");
		$this->db->select("status_renstra");
		$this->db->from($this->table_program_kegiatan);
		$this->db->join($this->table,$this->table.".id = ". $this->table_program_kegiatan .".id_renstra","inner");
		$this->db->where($this->table_program_kegiatan.".is_prog_or_keg", $this->is_kegiatan);

		if ($id_skpd != "all") {
			$this->db->where($this->table.".id_skpd", $id_skpd);
		}
		if ($status=="BARU") {
			$this->db->where("id_status", $this->id_status_baru);
		}elseif ($status=="VERIFIKASI") {
			$this->db->where("id_status", $this->id_status_send);
		}elseif ($status=="APPROVED") {
			$this->db->where("id_status", $this->id_status_approved);
		}
		if (!is_null($search)) {
			$this->db->where("(CONCAT(kd_urusan,\".\",kd_bidang,\".\",kd_program,\".\",kd_kegiatan) LIKE '%". $search['value'] ."%' OR nama_prog_or_keg LIKE '%". $search['value'] ."%' OR indikator_kinerja LIKE '%". $search['value'] ."%' OR status_renstra LIKE '%". $search['value'] ."%')");
		}

		if ($detail) {
			$this->db->select("m_bidkoordinasi.nama_koor");
			$this->db->select("m_skpd.nama_skpd");
			$this->db->join("m_skpd",$this->table.".id_skpd = m_skpd.id_skpd","inner");
			$this->db->join("m_bidkoordinasi","m_skpd.id_bidkoor = m_bidkoordinasi.id_bidkoor","inner");
		}

		if (!is_null($length) && !is_null($start)) {
			$this->db->limit($length, $start);
		}
		if (!is_null($order)) {
			$this->db->order_by($order_arr[$order["column"]], $order["dir"]);
		}

		$this->db->join("m_status_renstra",$this->table_program_kegiatan.".id_status = m_status_renstra.id","inner");
		$result = $this->db->get();
		return $result->result();
	}

	function count_all_renstra($search, $id_skpd, $status = NULL){
		$this->db->from($this->table_program_kegiatan);
		$this->db->join($this->table,$this->table.".id = ". $this->table_program_kegiatan .".id_renstra","inner");
		$this->db->where($this->table_program_kegiatan.".is_prog_or_keg", $this->is_kegiatan);

		if ($id_skpd != "all") {
			$this->db->where($this->table.".id_skpd", $id_skpd);
		}
		if ($status=="BARU") {
			$this->db->where("id_status", $this->id_status_baru);
		}elseif ($status=="VERIFIKASI") {
			$this->db->where("id_status", $this->id_status_send);
		}elseif ($status=="APPROVED") {
			$this->db->where("id_status", $this->id_status_approved);
		}
		if (!is_null($search)) {
			$this->db->where("(CONCAT(kd_urusan,\".\",kd_bidang,\".\",kd_program,\".\",kd_kegiatan) LIKE '%". $search['value'] ."%' OR nama_prog_or_keg LIKE '%". $search['value'] ."%' OR indikator_kinerja LIKE '%". $search['value'] ."%' OR status_renstra LIKE '%". $search['value'] ."%')");
		}

		$this->db->join("m_status_renstra",$this->table_program_kegiatan.".id_status = m_status_renstra.id","inner");
		$result = $this->db->count_all_results();
		return $result;
	}

	function get_all_renstra_veri(){
		$query = "SELECT t_renstra.*, m_skpd.*, COUNT(t_renstra.id) AS jum_semua,SUM(IF(t_renstra_prog_keg.id_status=?,1,0)) AS jum_dikirim FROM t_renstra INNER JOIN t_renstra_prog_keg ON t_renstra.id=t_renstra_prog_keg.id_renstra INNER JOIN m_skpd ON t_renstra.id_skpd=m_skpd.id_skpd GROUP BY m_skpd.id_skpd HAVING jum_dikirim>0";
		$data = array($this->id_status_send);
		$result = $this->db->query($query, $data);
		return $result->result();
	}

	function get_program_skpd_4_cetak($id_skpd){
		$id_skpd_unit = $this->m_skpd->get_kode_unit($id_skpd);

		if ($id_skpd_unit==$id_skpd) {
			$query_tambahan_lagi="";
		}else{
			$query_tambahan_lagi=" and id_skpd = $id_skpd";
		}
		
		$query = "SELECT
						tujuan, t_renstra_tujuan.id AS id_tujuannya, t_renstra_sasaran.sasaran, t_renstra_sasaran.id AS id_sasaran, pro.*, SUM(keg.nominal_1) AS nominal_1_pro, SUM(keg.nominal_2) AS nominal_2_pro, SUM(keg.nominal_3) AS nominal_3_pro, SUM(keg.nominal_4) AS nominal_4_pro, SUM(keg.nominal_5) AS nominal_5_pro
					FROM
						(SELECT * FROM t_renstra_prog_keg WHERE is_prog_or_keg=1 AND id_skpd > 0 $query_tambahan_lagi) AS pro
					INNER JOIN
						(SELECT * FROM t_renstra_prog_keg WHERE is_prog_or_keg=2 AND id_skpd > 0 $query_tambahan_lagi) AS keg ON keg.parent=pro.id
					INNER JOIN
						t_renstra_sasaran ON t_renstra_sasaran.id=pro.id_sasaran
					INNER JOIN
						t_renstra_tujuan ON t_renstra_tujuan.id=t_renstra_sasaran.id_tujuan
					INNER JOIN
						t_renstra ON pro.id_renstra=t_renstra.id
					WHERE
						t_renstra.id_skpd='$id_skpd_unit' ";

		if ($id_skpd_unit==$id_skpd) {
			$query_tambahan_unit=" and pro.id_skpd='$id_skpd' ";
		}else{
			$query_tambahan_unit=" and pro.id_skpd='$id_skpd' ";
		}
						
		
		$query_group =" GROUP BY pro.id
					   ORDER BY t_renstra_tujuan.id ASC, kd_urusan ASC, kd_bidang ASC, kd_program ASC";
		
		$query=$query.$query_tambahan_unit.$query_group;
		
		$data = array($id_skpd);
		$result = $this->db->query($query);
		return $result->result();
	}

	function get_kegiatan_skpd_4_cetak($id_program){
		$id_skpd=$this->session->userdata("id_skpd");
		$id_skpd_unit = $this->m_skpd->get_kode_unit($id_skpd);

		$query1 = "SELECT
						t_renstra_prog_keg.*
					FROM
						t_renstra_prog_keg
					WHERE
						parent=? ";
		if ($id_skpd==$id_skpd_unit){
			$queryskpd = ""	;
		}else{
			$queryskpd = " and id_skpd ='$id_skpd'"	;
		};				
		$queryorder=" ORDER BY kd_urusan ASC, kd_kegiatan ASC, kd_program ASC, kd_kegiatan ASC";
		$query = $query1.$queryskpd.$queryorder;
		$data = array($id_program);
		$result = $this->db->query($query, $data);
		return $result;
	}

	function get_kegiatan_berdasarkan_tujuan($id){
		$query = "SELECT t_renstra_prog_keg.* FROM t_renstra_prog_keg
		INNER JOIN t_renstra_sasaran ON t_renstra_sasaran.id = t_renstra_prog_keg.id_sasaran 
		WHERE t_renstra_sasaran.id_tujuan = '".$id."'";

		$result = $this->db->query($query);

		return $result;
	}

	function get_total_kegiatan_dan_indikator($id_program){
		$query = "SELECT
						COUNT(*) AS total
					FROM
						t_renstra_prog_keg
					INNER JOIN
						t_renstra_indikator_prog_keg ON t_renstra_indikator_prog_keg.id_prog_keg=t_renstra_prog_keg.id
					WHERE
						parent=? OR t_renstra_prog_keg.id=?";
		$data = array($id_program, $id_program);
		$result = $this->db->query($query, $data);
		return $result->row();
	}

	function approved_renstra($id_renstra, $id){
		$this->db->where($this->table_program_kegiatan.".id", $id);
		$this->db->where($this->table_program_kegiatan.".id_renstra", $id_renstra);
		$return = $this->db->update($this->table_program_kegiatan, array('id_status'=>$this->id_status_approved));
		return $return;
	}

	function not_approved_renstra($id_renstra, $id, $ket){
		$this->db->trans_strict(FALSE);
		$this->db->trans_start();

		$this->db->where($this->table_program_kegiatan.".id", $id);
		$this->db->where($this->table_program_kegiatan.".id_renstra", $id_renstra);
		$return = $this->db->update($this->table_program_kegiatan, array('id_status'=>$this->id_status_revisi));

		$result = $this->db->insert("t_renstra_revisi", array('id_renstra' => $id_renstra, 'id_kegiatan' => $id, 'ket' => $ket));

		$this->db->trans_complete();
		return $this->db->trans_status();
	}

	function get_revisi_renstra_ranwal($id_renstra){
		$this->db->select("t_renstra_revisi.ket");
		$this->db->select("t_renstra_prog_keg.*");
		$this->db->select("t_renstra_sasaran.sasaran");
		$this->db->select("t_renstra_tujuan.tujuan");
		$this->db->from("t_renstra_prog_keg");

		$this->db->where("t_renstra_revisi.id_renstra", $id_renstra);
		$this->db->where("t_renstra_prog_keg.id_status", $this->id_status_revisi);

		$this->db->join("(SELECT * FROM (SELECT * FROM t_renstra_revisi ORDER BY id DESC) AS t_renstra_revisi GROUP BY id_renstra,id_kegiatan) AS t_renstra_revisi","t_renstra_revisi.id_kegiatan=t_renstra_prog_keg.id","inner");
		$this->db->join("t_renstra_sasaran","t_renstra_sasaran.id=t_renstra_prog_keg.id_sasaran","inner");
		$this->db->join("t_renstra_tujuan","t_renstra_tujuan.id=t_renstra_sasaran.id_tujuan","inner");
		$this->db->order_by("t_renstra_revisi.id", "desc");
		$result = $this->db->get();
		return $result->result();
	}

	function get_revisi_renstra_akhir($id_renstra){
		$query = "SELECT `t_renstra_revisi_keg`.*, `t_renstra_prog_keg`.`kd_urusan`, `t_renstra_prog_keg`.`kd_bidang`, `t_renstra_prog_keg`.`kd_program`, `t_renstra_prog_keg`.`nama_prog_or_keg` FROM (SELECT * FROM `t_renstra_revisi_keg` WHERE id IN (SELECT max(id) FROM t_renstra_revisi_keg GROUP BY id_prog_keg) GROUP BY id_prog_keg) AS `t_renstra_revisi_keg` INNER JOIN `t_renstra_prog_keg` ON `t_renstra_revisi_keg`.`id_prog_keg` = `t_renstra_prog_keg`.`id` WHERE `id_renstra` = ? AND is_revisi_rpjm='0' ORDER BY `t_renstra_revisi_keg`.`id` desc";
		$data = array($id_renstra);
		$result = $this->db->query($query, $data);
		return $result->result();
	}

	function get_revisi_rpjm($id_renstra){
		$query = "SELECT `t_renstra_revisi_keg`.*, `t_renstra_prog_keg`.`kd_urusan`, `t_renstra_prog_keg`.`kd_bidang`, `t_renstra_prog_keg`.`kd_program`, `t_renstra_prog_keg`.`nama_prog_or_keg`, `t_renstra_prog_keg`.`nominal_1_tot` AS nominal_1_sblm, `t_renstra_prog_keg`.`nominal_2_tot` AS nominal_2_sblm, `t_renstra_prog_keg`.`nominal_3_tot` AS nominal_3_sblm, `t_renstra_prog_keg`.`nominal_4_tot` AS nominal_4_sblm, `t_renstra_prog_keg`.`nominal_5_tot` AS nominal_5_sblm FROM (SELECT * FROM `t_renstra_revisi_keg` WHERE id IN (SELECT max(id) FROM t_renstra_revisi_keg GROUP BY id_prog_keg) GROUP BY id_prog_keg) AS `t_renstra_revisi_keg` INNER JOIN (SELECT vw.*, SUM(t_renstra_prog_keg.nominal_1) AS nominal_1_tot, SUM(t_renstra_prog_keg.nominal_2) AS nominal_2_tot, SUM(t_renstra_prog_keg.nominal_3) AS nominal_3_tot, SUM(t_renstra_prog_keg.nominal_4) AS nominal_4_tot, SUM(t_renstra_prog_keg.nominal_5) AS nominal_5_tot FROM (SELECT * FROM t_renstra_prog_keg WHERE is_prog_or_keg=?) AS vw INNER JOIN t_renstra_prog_keg ON vw.id=t_renstra_prog_keg.parent AND t_renstra_prog_keg.is_prog_or_keg=? GROUP BY vw.id) AS `t_renstra_prog_keg` ON `t_renstra_revisi_keg`.`id_prog_keg` = `t_renstra_prog_keg`.`id` WHERE `id_renstra` = ? AND is_revisi_rpjm='1' ORDER BY `t_renstra_revisi_keg`.`id` desc";
		$data = array($this->is_program, $this->is_kegiatan, $id_renstra);
		$result = $this->db->query($query, $data);
		return $result->result();
	}

	function get_all_renstra_veri_akhir(){
		$query = "SELECT COUNT(*) AS jml_data, m_urusan.*, m_bidang.* FROM t_renstra_prog_keg INNER JOIN m_bidang ON (t_renstra_prog_keg.kd_urusan=m_bidang.Kd_Urusan AND t_renstra_prog_keg.kd_bidang=m_bidang.Kd_Bidang) INNER JOIN m_urusan ON t_renstra_prog_keg.kd_urusan=m_urusan.Kd_Urusan WHERE t_renstra_prog_keg.id_status=? AND t_renstra_prog_keg.is_prog_or_keg=? GROUP BY t_renstra_prog_keg.kd_urusan, t_renstra_prog_keg.kd_bidang";
		$data = array($this->id_status_approved, $this->is_kegiatan);
		$result = $this->db->query($query, $data);
		return $result->result();
	}

	function get_bidang_urusan_4_cetak_final($urusan, $bidang){
		$query = "SELECT t_renstra_prog_keg.*, m_bidang.*,SUM(t_renstra_prog_keg.nominal_1) AS nominal_1_pro, SUM(t_renstra_prog_keg.nominal_2) AS nominal_2_pro, SUM(t_renstra_prog_keg.nominal_3) AS nominal_3_pro, SUM(t_renstra_prog_keg.nominal_4) AS nominal_4_pro, SUM(t_renstra_prog_keg.nominal_5) AS nominal_5_pro FROM t_renstra_prog_keg INNER JOIN m_bidang ON (t_renstra_prog_keg.kd_urusan=m_bidang.Kd_Urusan AND t_renstra_prog_keg.kd_bidang=m_bidang.Kd_Bidang) WHERE t_renstra_prog_keg.id_status=? AND t_renstra_prog_keg.is_prog_or_keg=? AND t_renstra_prog_keg.kd_urusan=? AND t_renstra_prog_keg.kd_bidang=? GROUP BY t_renstra_prog_keg.kd_urusan, t_renstra_prog_keg.kd_bidang";
		$data = array($this->id_status_approved, $this->is_kegiatan, $urusan, $bidang);
		$result = $this->db->query($query, $data);
		return $result->result();
	}

	function get_bidang_urusan_skpd_4_cetak_final($urusan, $bidang){
		$query = "SELECT t_renstra_prog_keg.*,m_skpd.*,SUM(t_renstra_prog_keg.nominal_1) AS nominal_1_pro, SUM(t_renstra_prog_keg.nominal_2) AS nominal_2_pro, SUM(t_renstra_prog_keg.nominal_3) AS nominal_3_pro, SUM(t_renstra_prog_keg.nominal_4) AS nominal_4_pro, SUM(t_renstra_prog_keg.nominal_5) AS nominal_5_pro FROM t_renstra_prog_keg INNER JOIN t_renstra ON (t_renstra.id=t_renstra_prog_keg.id_renstra AND t_renstra_prog_keg.is_prog_or_keg=?) INNER JOIN m_skpd ON t_renstra.id_skpd=m_skpd.id_skpd WHERE t_renstra_prog_keg.id_status=? AND t_renstra_prog_keg.is_prog_or_keg=? AND t_renstra_prog_keg.kd_urusan=? AND t_renstra_prog_keg.kd_bidang=? GROUP BY t_renstra_prog_keg.kd_urusan, t_renstra_prog_keg.kd_bidang, m_skpd.id_skpd";
		$data = array($this->is_kegiatan, $this->id_status_approved, $this->is_kegiatan, $urusan, $bidang);
		$result = $this->db->query($query, $data);
		return $result->result();
	}

	function get_bidang_urusan_skpd_program_4_cetak_final($urusan, $bidang, $skpd){
		$query = "SELECT vw1.*,m_skpd.*,SUM(t_renstra_prog_keg.nominal_1) AS nominal_1_pro, SUM(t_renstra_prog_keg.nominal_2) AS nominal_2_pro, SUM(t_renstra_prog_keg.nominal_3) AS nominal_3_pro, SUM(t_renstra_prog_keg.nominal_4) AS nominal_4_pro, SUM(t_renstra_prog_keg.nominal_5) AS nominal_5_pro FROM t_renstra_prog_keg INNER JOIN t_renstra ON (t_renstra.id=t_renstra_prog_keg.id_renstra AND t_renstra_prog_keg.is_prog_or_keg=?) INNER JOIN m_skpd ON t_renstra.id_skpd=m_skpd.id_skpd INNER JOIN t_renstra_prog_keg AS vw1 ON (vw1.id=t_renstra_prog_keg.parent AND vw1.is_prog_or_keg=?) WHERE t_renstra_prog_keg.id_status=? AND t_renstra_prog_keg.is_prog_or_keg=? AND t_renstra_prog_keg.kd_urusan=? AND t_renstra_prog_keg.kd_bidang=? AND t_renstra.id_skpd=? GROUP BY t_renstra_prog_keg.kd_urusan, t_renstra_prog_keg.kd_bidang, m_skpd.id_skpd, t_renstra_prog_keg.kd_program";
		$data = array($this->is_kegiatan, $this->is_program, $this->id_status_approved, $this->is_kegiatan, $urusan, $bidang, $skpd);
		$result = $this->db->query($query, $data);
		return $result->result();
	}

	function get_one_bidang_urusan_skpd_program_final($id_program){
		$query = "SELECT vw1.*,m_bidang.Nm_Bidang,m_skpd.*,SUM(t_renstra_prog_keg.nominal_1) AS nominal_1_pro, SUM(t_renstra_prog_keg.nominal_2) AS nominal_2_pro, SUM(t_renstra_prog_keg.nominal_3) AS nominal_3_pro, SUM(t_renstra_prog_keg.nominal_4) AS nominal_4_pro, SUM(t_renstra_prog_keg.nominal_5) AS nominal_5_pro FROM t_renstra_prog_keg INNER JOIN t_renstra ON (t_renstra.id=t_renstra_prog_keg.id_renstra AND t_renstra_prog_keg.is_prog_or_keg=?) INNER JOIN m_skpd ON t_renstra.id_skpd=m_skpd.id_skpd INNER JOIN t_renstra_prog_keg AS vw1 ON (vw1.id=t_renstra_prog_keg.parent AND vw1.is_prog_or_keg=?) INNER JOIN m_bidang ON m_bidang.Kd_Urusan=vw1.kd_urusan AND m_bidang.Kd_Bidang=vw1.kd_bidang WHERE t_renstra_prog_keg.id_status=? AND t_renstra_prog_keg.is_prog_or_keg=? AND vw1.id=? GROUP BY t_renstra_prog_keg.kd_urusan, t_renstra_prog_keg.kd_bidang, m_skpd.id_skpd, t_renstra_prog_keg.kd_program";
		$data = array($this->is_kegiatan, $this->is_program, $this->id_status_approved, $this->is_kegiatan, $id_program);
		$result = $this->db->query($query, $data);
		return $result->row();
	}

	function get_program_veri_akhir($urusan, $bidang){
		$query = "SELECT * FROM t_renstra_prog_keg INNER JOIN m_bidang ON (t_renstra_prog_keg.kd_urusan=m_bidang.Kd_Urusan AND t_renstra_prog_keg.kd_bidang=m_bidang.Kd_Bidang) INNER JOIN m_urusan ON t_renstra_prog_keg.kd_urusan=m_urusan.Kd_Urusan WHERE t_renstra_prog_keg.id_status=? AND t_renstra_prog_keg.is_prog_or_keg=? GROUP BY t_renstra_prog_keg.kd_urusan, t_renstra_prog_keg.kd_bidang";
		$data = array($this->id_status_approved, $this->is_kegiatan);
		$result = $this->db->query($query, $data);
		return $result->result();
	}

	function approved_veri_akhir_renstra($id){
		$this->db->where($this->table_program_kegiatan.".parent", $id);
		$this->db->or_where($this->table_program_kegiatan.".id", $id);
		$return = $this->db->update($this->table_program_kegiatan, array($this->table_program_kegiatan.'.id_status'=>$this->id_status_approved2));
		return $return;
	}

	function not_approved_veri_akhir_renstra($id, $data){
		$this->db->trans_strict(FALSE);
		$this->db->trans_start();

		$this->db->where($this->table_program_kegiatan.".parent", $id);
		$this->db->or_where($this->table_program_kegiatan.".id", $id);
		$return = $this->db->update($this->table_program_kegiatan, array($this->table_program_kegiatan.'.id_status'=>$this->id_status_revisi2));

		$result = $this->db->insert("t_renstra_revisi_keg", $data);

		$this->db->trans_complete();
		return $this->db->trans_status();
	}

	function revisi_rpjmd($id_program){
		$query = "SELECT t_renstra_revisi_keg.*, t_renstra_prog_keg.kd_urusan, t_renstra_prog_keg.kd_bidang, t_renstra_prog_keg.kd_program, t_renstra_prog_keg.nama_prog_or_keg, id_renstra FROM (SELECT * FROM `t_renstra_revisi_keg` WHERE id IN (SELECT max(id) FROM t_renstra_revisi_keg GROUP BY id_prog_keg) GROUP BY id_prog_keg) AS `t_renstra_revisi_keg` INNER JOIN t_renstra_prog_keg ON t_renstra_revisi_keg.id_prog_keg=t_renstra_prog_keg.id AND t_renstra_prog_keg.is_prog_or_keg=? WHERE t_renstra_prog_keg.id=?";
		$data = array($this->is_program, $id_program);
		$result = $this->db->query($query, $data);
		return $result->row();
	}

	function cek_nominal_banding_dengan_rpjmd($id, $urusan, $bidang, $program, $id_renstra){
		$query = "SELECT SUM(nominal_1) AS nominal_1_pro, SUM(nominal_2) AS nominal_2_pro, SUM(nominal_3) AS nominal_3_pro, SUM(nominal_4) AS nominal_4_pro, SUM(nominal_5) AS nominal_5_pro FROM t_renstra_prog_keg WHERE is_prog_or_keg=? AND id!=? AND kd_urusan=? AND kd_bidang=? AND kd_program=? AND id_renstra=? GROUP BY kd_urusan, kd_bidang, kd_program, id_renstra";
		$data = array($this->is_kegiatan, $id, $urusan, $bidang, $program, $id_renstra);
		$result = $this->db->query($query, $data);
		return $result->row();
	}

	function get_total_nominal_renstra($id_skpd=NULL){
		$this->db->select('COUNT(t_renstra_prog_keg.id) AS count');
		$this->db->select_sum('nominal_1');
		$this->db->select_sum('nominal_2');
		$this->db->select_sum('nominal_3');
		$this->db->select_sum('nominal_4');
		$this->db->select_sum('nominal_5');

		$proses = $this->count_jendela_kontrol($id_skpd);
		if (!empty($proses->veri2)) {
			$this->db->where("id_status", $this->id_status_approved2);
		}else{
			$this->db->where("id_status", $this->id_status_approved);
		}

		if (!is_null($id_skpd) && $id_skpd != "all") {
			$this->db->where("t_renstra.id_skpd", $id_skpd);
		}

		$this->db->where("t_renstra_prog_keg.is_prog_or_keg", $this->is_kegiatan);
		$this->db->from($this->table_program_kegiatan);
		$this->db->join($this->table,$this->table.".id = ". $this->table_program_kegiatan .".id_renstra","inner");

		$result = $this->db->get();
		return $result->row();
	}

	function get_all_skpd(){
		$query = "SELECT id_skpd FROM (SELECT * FROM t_renstra_prog_keg GROUP BY id_renstra) t_renstra_prog_keg INNER JOIN t_renstra ON t_renstra.id=t_renstra_prog_keg.id_renstra";
		$result = $this->db->query($query);
		return $result->result();
	}

	function simpan_revisi($id_skpd, $ket){
		$this->db->trans_strict(FALSE);
		$this->db->trans_start();

		$query = "INSERT INTO t_pengajuan_revisi(id_skpd, keterangan) VALUES (?, ?)";
		$data = array($id_skpd, $ket);
		$result = $this->db->query($query, $data);

		$this->db->trans_complete();
		return $this->db->trans_status();
	}

	function check_revisi($id_skpd){
		$query = "SELECT * FROM t_pengajuan_revisi WHERE id_skpd=? AND status=1";
		$data = array($id_skpd);
		$result = $this->db->query($query, $data);
		return $result->row();
	}

	function get_log_revisi($id_skpd){
		$query = "SELECT * FROM t_pengajuan_revisi WHERE id_skpd=? AND status > 1";
		$data = array($id_skpd);
		$result = $this->db->query($query, $data);
		return $result->result();
	}

	function disapprove_renstra($id_renstra, $ket){
		$this->db->trans_strict(FALSE);
		$this->db->trans_start();

		$query = "INSERT t_renstra_revisi SELECT NULL, id_renstra, id, ? FROM t_renstra_prog_keg WHERE id_renstra=?";
		$data = array($ket, $id_renstra);
		$result = $this->db->query($query, $data);

		$query = "UPDATE t_renstra_prog_keg SET id_status=3 WHERE id_renstra=?";
		$data = array($id_renstra);
		$result = $this->db->query($query, $data);

		$this->db->trans_complete();
		return $this->db->trans_status();
	}

	function approve_renstra_kepala_skpd($id_renstra){
		$this->db->where($this->table_program_kegiatan.".id_renstra", $id_renstra);
		$return = $this->db->update($this->table_program_kegiatan, array('is_verifikasi'=>'1'));
		return $return;
	}

	function approve_one_renstra_kepala_skpd($id){
		$this->db->where($this->table_program_kegiatan.".id", $id);
		$return = $this->db->update($this->table_program_kegiatan, array('is_verifikasi'=>'1'));
		return $return;
	}

	function not_approve_renstra_kepala_skpd($id){
		$this->db->where($this->table_program_kegiatan.".id", $id);
		$return = $this->db->update($this->table_program_kegiatan, array('is_verifikasi'=>'0'));
		return $return;
	}

	function get_verifikasi_kepala_skpd($id_renstra){
		$query = "SELECT count(id) as id from t_renstra_prog_keg where is_verifikasi = 0 and id_renstra = $id_renstra";
		$result = $this->db->query($query);
		return $result->row();
	}

	function get_all_renstra_skpd(){
		//$query = "SELECT t_renstra.*, m_skpd.*, COUNT(t_renstra.id) AS jum_semua,SUM(IF(t_renstra_prog_keg.id_status=?,1,0)) AS jum_dikirim FROM t_renstra INNER JOIN t_renstra_prog_keg ON t_renstra.id=t_renstra_prog_keg.id_renstra INNER JOIN m_skpd ON t_renstra.id_skpd=m_skpd.id_skpd GROUP BY m_skpd.id_skpd HAVING jum_dikirim>0";
		$query = "SELECT t_renstra.*, m_skpd.*, COUNT(t_renstra.id) AS jum_semua FROM t_renstra INNER JOIN t_renstra_prog_keg ON t_renstra.id=t_renstra_prog_keg.id_renstra RIGHT JOIN m_skpd ON t_renstra_prog_keg.id_skpd=m_skpd.id_skpd GROUP BY m_skpd.id_skpd ORDER BY m_skpd.kode_skpd";
		// $data = array($this->id_status_send);
		$result = $this->db->query($query);
		return $result->result();
	}
}
?>
