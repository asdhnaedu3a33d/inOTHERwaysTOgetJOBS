<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class M_kendali_perubahan extends CI_Model
{
	var $table_rka = 'tx_rka_perubahan';
	var $table_dpa = 'tx_dpa_perubahan';
	var $table_program_kegiatan = 'tx_rka_prog_keg_perubahan';
	var $table_indikator_program = 'tx_rka_indikator_prog_keg_perubahan';
	var $table_indikator_program2 = 'tx_dpa_indikator_prog_keg_perubahan';

	var $id_status_baru = "1";
	var $id_status_send = "2";
	var $id_status_revisi = "3";
	var $id_status_approved = "4";

	var $is_program = 1;
	var $is_kegiatan = 2;

	var $history_renja = 'tx_rka_history_perubahan';
	var $history_belanja = 'tx_dpa_history_perubahan';

public function __construct()
{
	parent::__construct();
}
//-----------------------------------------------KENDALI BELANJA----------------------------------------------
	    function get_program_dpa($id_skpd,$tahun)
	    {
	    	$query = "SELECT pro.*
				FROM
					(SELECT * FROM tx_dpa_prog_keg_perubahan WHERE is_prog_or_keg=1) AS pro
				INNER JOIN
					(SELECT * FROM tx_dpa_prog_keg_perubahan WHERE is_prog_or_keg=2) AS keg ON keg.parent=pro.id
				WHERE
					keg.id_skpd=?
				AND keg.tahun =?

				GROUP BY pro.id
				ORDER BY kd_urusan ASC, kd_bidang ASC, kd_program ASC, kd_kegiatan ASC";
			$data = array($id_skpd,$tahun);
			$result = $this->db->query($query,$data);
			return $result->result();
	    }

		function get_program_dpa_4_cetak($id_skpd,$tahun)
	    {
	    	$query = "SELECT pro.*
				FROM
					(SELECT * FROM tx_dpa_prog_keg_perubahan WHERE is_prog_or_keg=1) AS pro
				INNER JOIN
					(SELECT * FROM tx_dpa_prog_keg_perubahan WHERE is_prog_or_keg=2) AS keg ON keg.parent=pro.id
				WHERE
					keg.id_skpd=?
				AND keg.tahun =?

				GROUP BY pro.id
				ORDER BY kd_urusan ASC, kd_bidang ASC, kd_program ASC, kd_kegiatan ASC";
			$data = array($id_skpd,$tahun);
			$result = $this->db->query($query,$data);
			return $result->result();
	    }

	    function get_kegiatan_dpa_4_cetak($id_program,$tahun){
				$query = "SELECT
								tx_dpa_prog_keg_perubahan.*
							FROM tx_dpa_prog_keg_perubahan
							WHERE parent=?
							AND tahun = ?
							ORDER BY kd_urusan, kd_bidang, kd_program, kd_kegiatan ASC";
			$data = array($id_program,$tahun);
			$result = $this->db->query($query, $data);
			return $result;
		}

		function get_total_kegiatan_dan_indikator_dpa($id_program){
			$tahun = $this->session->userdata('t_anggaran_aktif');
			$query = "SELECT
							COUNT(*) AS total
						FROM
							tx_dpa_prog_keg_perubahan
						INNER JOIN
							tx_dpa_indikator_prog_keg_perubahan ON tx_dpa_indikator_prog_keg_perubahan.id_prog_keg=tx_dpa_prog_keg_perubahan.id
						WHERE
							tx_dpa_prog_keg_perubahan.parent=? OR tx_dpa_prog_keg_perubahan.id=?
						AND tahun = ?";
			$data = array($id_program, $id_program, $tahun);
			$result = $this->db->query($query, $data);
			return $result->row();
		}

		function get_indikator_prog_keg_dpa($id, $return_result=TRUE, $satuan=FALSE){
			$this->db->select($this->table_indikator_program2.".*, satuan_target as nama_value");
			$this->db->where('id_prog_keg', $id);
			$this->db->from($this->table_indikator_program2);

			if ($satuan) {
				// $this->db->select("m_lov.nama_value");
				// $this->db->join("m_lov",$this->table_indikator_program2.".satuan_target = m_lov.kode_value AND kode_app='1'","inner");
			}

			$result = $this->db->get();
			if ($return_result) {
				return $result->result();
			}else{
				return $result;
			}
		}
		function get_one_kendali_belanja($id_skpd, $detail=FALSE){
			$this->db->select($this->table_dpa.".*");
			$this->db->from($this->table_dpa);
			$this->db->where($this->table_dpa.".id_skpd", $id_skpd);

			if ($detail) {
				$this->db->select("nama_skpd");
				$this->db->join("m_skpd","tx_dpa_perubahan.id_skpd = m_skpd.id_skpd","inner");
			}

			$result = $this->db->get();
			return $result->row();
		}

//==============================================================================================================
//----------------------------------------------KENDALI RENJA----------------------------------------------------
		function get_program_rka($id_skpd,$tahun)
		{
			$query = "SELECT pro.*,
						   SUM(keg.nominal) AS sum_nominal,
						   SUM(keg.nominal_thndpn) AS sum_nominal_thndpn,
						   SUM(keg.nomrenja) AS sum_nomrenja,
						   SUM(keg.nomrenja_thndpn) AS sum_nomrenja_thndpn
					FROM
						(SELECT a.`id`, a.`tahun`, a.`kd_urusan`, a.`kd_bidang`, a.`kd_program`, a.`kd_kegiatan`, a.`nama_prog_or_keg`,
								a.`nominal`, a.`nominal_thndpn`, b.`nominal` AS nomrenja, b.`nominal_thndpn` AS nomrenja_thndpn, a.`id_skpd`,
								a.kesesuaian,a.hasil_kendali,a.tindak_lanjut,a.hasil_tl,a.id_status
						 FROM tx_rka_prog_keg_perubahan a
						 LEFT JOIN t_renja_prog_keg_perubahan b ON a.`kd_urusan`=b.`kd_urusan`
													  AND a.`kd_bidang`=b.`kd_bidang`
													  AND a.`kd_program`=b.`kd_program`
													  AND a.`kd_kegiatan`=b.`kd_kegiatan`
													  AND a.`is_prog_or_keg`=b.`is_prog_or_keg`
						 WHERE a.is_prog_or_keg=1
						 GROUP BY a.`id`) AS pro
					INNER JOIN
						(SELECT a.`id`, a.`id_skpd`,a.`tahun`, a.`kd_urusan`, a.`kd_bidang`, a.`kd_program`, a.`kd_kegiatan`, a.`parent`,
								a.`nominal`, a.`nominal_thndpn`, b.`nominal` AS nomrenja, b.`nominal_thndpn` AS nomrenja_thndpn
						 FROM tx_rka_prog_keg_perubahan a
						 LEFT JOIN t_renja_prog_keg_perubahan b ON a.`kd_urusan`=b.`kd_urusan`
													  AND a.`kd_bidang`=b.`kd_bidang`
													  AND a.`kd_program`=b.`kd_program`
													  AND a.`kd_kegiatan`=b.`kd_kegiatan`
													  AND a.`is_prog_or_keg`=b.`is_prog_or_keg`
						 WHERE a.is_prog_or_keg=2
						 GROUP BY a.`kd_urusan`, a.`kd_bidang`, a.`kd_program`, a.`kd_kegiatan`,a.`id`) AS keg ON keg.parent=pro.id
					WHERE
						keg.id_skpd=?
					AND keg.tahun = ?
					GROUP BY pro.id
					ORDER BY pro.`kd_urusan` ASC, pro.`kd_bidang` ASC, pro.`kd_program` ASC";
			$data = array($id_skpd,$tahun);
			$result = $this->db->query($query,$data);
			return $result->result();
		}

		function get_program_rka_4_cetak($id_skpd,$tahun,$kd_urusan,$kd_bidang)
		{
			$query = "SELECT pro.*,
						   SUM(keg.nominal) AS sum_nominal,
						   SUM(keg.nominal_thndpn) AS sum_nominal_thndpn,
						   SUM(keg.nomrenja) AS sum_nomrenja,
						   SUM(keg.nomrenja_thndpn) AS sum_nomrenja_thndpn
					FROM
						(SELECT a.`id`, a.`tahun`, a.`kd_urusan`, a.`kd_bidang`, a.`kd_program`, a.`kd_kegiatan`, a.`nama_prog_or_keg`,
								a.`nominal`, a.`nominal_thndpn`, b.`nominal` AS nomrenja, b.`nominal_thndpn` AS nomrenja_thndpn, a.`id_skpd`,
								a.kesesuaian,a.hasil_kendali,a.tindak_lanjut,a.hasil_tl,a.id_status
						 FROM tx_rka_prog_keg_perubahan a
						 LEFT JOIN t_renja_prog_keg_perubahan b ON a.`kd_urusan`=b.`kd_urusan`
													  AND a.`kd_bidang`=b.`kd_bidang`
													  AND a.`kd_program`=b.`kd_program`
													  AND a.`kd_kegiatan`=b.`kd_kegiatan`
													  AND a.`is_prog_or_keg`=b.`is_prog_or_keg`
						 WHERE a.is_prog_or_keg=1
						 GROUP BY a.`id`) AS pro
					INNER JOIN
						(SELECT a.`id`, a.`id_skpd`,a.`tahun`, a.`kd_urusan`, a.`kd_bidang`, a.`kd_program`, a.`kd_kegiatan`, a.`parent`,
								a.`nominal`, a.`nominal_thndpn`, b.`nominal` AS nomrenja, b.`nominal_thndpn` AS nomrenja_thndpn
						 FROM tx_rka_prog_keg_perubahan a
						 LEFT JOIN t_renja_prog_keg_perubahan b ON a.`kd_urusan`=b.`kd_urusan`
													  AND a.`kd_bidang`=b.`kd_bidang`
													  AND a.`kd_program`=b.`kd_program`
													  AND a.`kd_kegiatan`=b.`kd_kegiatan`
													  AND a.`is_prog_or_keg`=b.`is_prog_or_keg`
						 WHERE a.is_prog_or_keg=2
						 GROUP BY a.`kd_urusan`, a.`kd_bidang`, a.`kd_program`, a.`kd_kegiatan`,a.`id`) AS keg ON keg.parent=pro.id
					WHERE
						keg.id_skpd = ?
					AND keg.tahun = ?
					AND keg.kd_urusan = ?
					AND keg.kd_bidang = ?
					GROUP BY pro.id
					ORDER BY pro.`kd_urusan` ASC, pro.`kd_bidang` ASC, pro.`kd_program` ASC";
			$data = array($id_skpd,$tahun,$kd_urusan,$kd_bidang);
			$result = $this->db->query($query,$data);
			return $result->result();
		}

		function get_kegiatan_rka_4_cetak($id_program,$tahun){
			$query = "
						SELECT a.`id`, a.`id_skpd`,a.`tahun`, a.`kd_urusan`, a.`kd_bidang`, a.`kd_program`, a.`kd_kegiatan`, a.`parent`,
						       a.`lokasi`,b.lokasi AS lokasirenja, a.`nama_prog_or_keg`,
							   a.`nominal`, a.`nominal_thndpn`, b.`nominal` AS nomrenja, b.`nominal_thndpn` AS nomrenja_thndpn ,
							   a.kesesuaian,a.hasil_kendali,a.tindak_lanjut,a.hasil_tl,a.id_status
						FROM tx_rka_prog_keg_perubahan a
						LEFT JOIN t_renja_prog_keg_perubahan AS b ON a.`kd_urusan`=b.`kd_urusan`
												  AND a.`kd_bidang`=b.`kd_bidang`
												  AND a.`kd_program`=b.`kd_program`
												  AND a.`kd_kegiatan`=b.`kd_kegiatan`
												  AND a.`is_prog_or_keg`=b.`is_prog_or_keg`
						WHERE a.parent=?
						AND a.tahun= ?
						GROUP BY a.`kd_urusan`, a.`kd_bidang`, a.`kd_program`, a.`kd_kegiatan`,a.`id`

						";
			$data = array($id_program,$tahun);
			$result = $this->db->query($query, $data);
			return $result;
		}

		function get_total_kegiatan_dan_indikator($id_program){
			$tahun = $this->session->userdata('t_anggaran_aktif');
			$query = "SELECT
							COUNT(*) AS total
						FROM
							tx_rka_prog_keg_perubahan
						INNER JOIN
							tx_rka_indikator_prog_keg_perubahan ON tx_rka_indikator_prog_keg_perubahan.id_prog_keg = tx_rka_prog_keg_perubahan.id
						WHERE
							tx_rka_prog_keg_perubahan.parent=? OR tx_rka_prog_keg_perubahan.id=?
						AND tahun = ?";
			$data = array($id_program, $id_program, $tahun);
			$result = $this->db->query($query, $data);
			return $result->row();
		}

		function get_indikator_prog_keg($id, $return_result=TRUE, $satuan=FALSE){
			$data_kode = $this->get_kodefikasi_rka($id);
		    //echo $this->db->last_query();
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

		function get_indikator_prog($id_skpd, $tahun, $kd_urusan, $kd_bidang, $kd_program){
			$query = "
						SELECT *
						FROM (SELECT tx_rka_indikator_prog_keg_perubahan.`id_prog_keg` AS id_rka,
									 tx_rka_indikator_prog_keg_perubahan.`indikator` AS in_rka,
									 tx_rka_indikator_prog_keg_perubahan.`satuan_target` AS satuan_rka,
									 tx_rka_indikator_prog_keg_perubahan.`target` AS target_rka,
									 tx_rka_indikator_prog_keg_perubahan.`target_thndpn` AS target_rka_thndpn,
									 tx_rka_prog_keg_perubahan.`kd_urusan`,tx_rka_prog_keg_perubahan.`kd_bidang`,
									 tx_rka_prog_keg_perubahan.`kd_program`,tx_rka_prog_keg_perubahan.`kd_kegiatan`,
									 tx_rka_prog_keg_perubahan.`id_skpd`,tx_rka_prog_keg_perubahan.`tahun`,
									 tx_rka_prog_keg_perubahan.`is_prog_or_keg`
							  FROM tx_rka_indikator_prog_keg_perubahan
							  INNER JOIN tx_rka_prog_keg_perubahan ON tx_rka_indikator_prog_keg_perubahan.`id_prog_keg` = tx_rka_prog_keg_perubahan.`id`) AS a
						LEFT JOIN (SELECT t_renja_indikator_prog_keg_perubahan.`id_prog_keg` AS id_renja,
										  t_renja_indikator_prog_keg_perubahan.`indikator` AS in_renja,
										  t_renja_indikator_prog_keg_perubahan.`satuan_target`AS satuan_renja,
										  t_renja_indikator_prog_keg_perubahan.`target` AS target_renja,
										  t_renja_indikator_prog_keg_perubahan.`target_thndpn` AS target_renja_thndpn,
										  t_renja_prog_keg_perubahan.`kd_urusan`,
										  t_renja_prog_keg_perubahan.`kd_bidang`,
										  t_renja_prog_keg_perubahan.`kd_program`,
										  t_renja_prog_keg_perubahan.`kd_kegiatan`
								   FROM t_renja_indikator_prog_keg_perubahan
								   INNER JOIN t_renja_prog_keg_perubahan ON t_renja_indikator_prog_keg_perubahan.`id_prog_keg` = t_renja_prog_keg_perubahan.`id`) AS b
						ON a.kd_urusan=b.kd_urusan
						AND a.kd_bidang=b.kd_bidang
						AND a.kd_program=b.kd_program
						WHERE a.id_skpd=?
						AND a.tahun=?
						AND a.kd_urusan LIKE ?
						AND a.kd_bidang LIKE ?
						AND a.kd_program LIKE ?
						AND a.is_prog_or_keg =1
						GROUP BY a.kd_urusan,a.kd_bidang,a.kd_program

						";
			$data = array($id_skpd, $tahun, '%'.$kd_urusan.'%', '%'.$kd_bidang.'%', '%'.$kd_program.'%');
			$result = $this->db->query($query, $data);
			return $result;
		}

		function get_indikator_renja($id){
			$query = "
						SELECT *
						FROM t_renja_indikator_prog_keg_perubahan
						WHERE id_prog_keg = ?
						";
			$data = array($id);
			$result = $this->db->query($query, $data);
			return $result;
		}

		function get_indikator_rka($id){
			$query = "
						SELECT *
						FROM tx_rka_indikator_prog_keg_perubahan
						WHERE id_prog_keg = ?
						";
			$data = array($id);
			$result = $this->db->query($query, $data);
			return $result;
		}

		function get_id_renja($id_skpd, $tahun, $kd_urusan, $kd_bidang, $kd_program){
			$query = "
						SELECT id
						FROM t_renja_prog_keg_perubahan
						WHERE id_skpd=?
						AND tahun=?
						AND kd_urusan = ?
						AND kd_bidang = ?
						AND kd_program = ?
						AND is_prog_or_keg =1
						";
			$data = array($id_skpd, $tahun,$kd_urusan,$kd_bidang,$kd_program);
			$result = $this->db->query($query, $data);
			if($result){
                $result = $result->row();
                return $result->id;
            }
            return 0;
		}

		function get_id_rka($id_skpd, $tahun, $kd_urusan, $kd_bidang, $kd_program){
			$query = "
						SELECT id
						FROM tx_rka_prog_keg_perubahan
						WHERE id_skpd=?
						AND tahun=?
						AND kd_urusan = ?
						AND kd_bidang = ?
						AND kd_program = ?
						AND is_prog_or_keg =1
						";
			$data = array($id_skpd, $tahun,$kd_urusan,$kd_bidang,$kd_program);
			$result = $this->db->query($query, $data);
			 if($result){
                $result = $result->row();
                return $result->id;
            }
            return 0;
		}

		function get_id_renja1($id_skpd, $tahun, $kd_urusan, $kd_bidang, $kd_program, $kd_kegiatan){
			$query = "
						SELECT id
						FROM t_renja_prog_keg_perubahan
						WHERE id_skpd=?
						AND tahun=?
						AND kd_urusan = ?
						AND kd_bidang = ?
						AND kd_program = ?
						AND kd_kegiatan = ?
						AND is_prog_or_keg =2
						";
			$data = array($id_skpd, $tahun,$kd_urusan,$kd_bidang,$kd_program, $kd_kegiatan);
			$result = $this->db->query($query, $data);
			if($result){
                $result = $result->row();
                return $result->id;
            }
            return 0;
		}

		function get_id_rka1($id_skpd, $tahun, $kd_urusan, $kd_bidang, $kd_program, $kd_kegiatan){
			$query = "
						SELECT id
						FROM tx_rka_prog_keg_perubahan
						WHERE id_skpd=?
						AND tahun=?
						AND kd_urusan = ?
						AND kd_bidang = ?
						AND kd_program = ?
						AND kd_kegiatan = ?
						AND is_prog_or_keg =2
						";
			$data = array($id_skpd, $tahun,$kd_urusan,$kd_bidang,$kd_program, $kd_kegiatan);
			$result = $this->db->query($query, $data);
			 if($result){
                $result = $result->row();
                return $result->id;
            }
            return 0;
		}

		function get_indikator_kegiatan($id_skpd, $tahun, $kd_urusan, $kd_bidang, $kd_program, $kd_kegiatan){
			$query = "
						SELECT *
						FROM (SELECT tx_rka_indikator_prog_keg_perubahan.`id_prog_keg` AS id_rka,
									 tx_rka_indikator_prog_keg_perubahan.`indikator` AS in_rka,
									 tx_rka_indikator_prog_keg_perubahan.`satuan_target` AS satuan_rka,
									 tx_rka_indikator_prog_keg_perubahan.`target` AS target_rka,
									 tx_rka_indikator_prog_keg_perubahan.`target_thndpn` AS target_rka_thndpn,
									 tx_rka_prog_keg_perubahan.`kd_urusan`,
									 tx_rka_prog_keg_perubahan.`kd_bidang`,
									 tx_rka_prog_keg_perubahan.`kd_program`,
									 tx_rka_prog_keg_perubahan.`kd_kegiatan`,
									 tx_rka_prog_keg_perubahan.`id_skpd`,
									 tx_rka_prog_keg_perubahan.`tahun`,
									 tx_rka_prog_keg_perubahan.`is_prog_or_keg`
							  FROM tx_rka_indikator_prog_keg_perubahan
							  INNER JOIN tx_rka_prog_keg_perubahan ON tx_rka_indikator_prog_keg_perubahan.`id_prog_keg` = tx_rka_prog_keg_perubahan.`id`) AS a
						LEFT JOIN (SELECT t_renja_indikator_prog_keg_perubahan.`id_prog_keg` AS id_renja,
										  t_renja_indikator_prog_keg_perubahan.`indikator` AS in_renja,
										  t_renja_indikator_prog_keg_perubahan.`satuan_target`AS satuan_renja,
										  t_renja_indikator_prog_keg_perubahan.`target` AS target_renja,
										  t_renja_indikator_prog_keg_perubahan.`target_thndpn` AS target_renja_thndpn,
										  t_renja_prog_keg_perubahan.`kd_urusan`,
										  t_renja_prog_keg_perubahan.`kd_bidang`,
										  t_renja_prog_keg_perubahan.`kd_program`,
										  t_renja_prog_keg_perubahan.`kd_kegiatan`
								   FROM t_renja_indikator_prog_keg_perubahan
								   INNER JOIN t_renja_prog_keg_perubahan ON t_renja_indikator_prog_keg_perubahan.`id_prog_keg`=t_renja_prog_keg_perubahan.`id`) AS b
						ON a.kd_urusan=b.kd_urusan
						AND a.kd_bidang=b.kd_bidang
						AND a.kd_program=b.kd_program
						AND a.kd_kegiatan=b.kd_kegiatan
						WHERE a.id_skpd=?
						AND a.tahun=?
						AND a.kd_urusan LIKE ?
						AND a.kd_bidang LIKE ?
						AND a.kd_program LIKE ?
						AND a.kd_kegiatan LIKE ?
						AND a.is_prog_or_keg =2
						GROUP BY a.kd_urusan,a.kd_bidang,a.kd_program,a.kd_kegiatan

						";
			$data = array($id_skpd, $tahun, '%'.$kd_urusan.'%', '%'.$kd_bidang.'%', '%'.$kd_program.'%', '%'.$kd_kegiatan.'%');
			$result = $this->db->query($query, $data);
			return $result;
		}

		function get_kodefikasi_rka($id) {
			$this->db->select($this->table_rka.".*");
			$this->db->where('id', $id);
			$this->db->from($this->table_rka);

			$result = $this->db->get();
			return $result;
		}

		function get_one_kendali_renja($id_skpd, $detail=FALSE){
			$this->db->select($this->table_rka.".*");
			$this->db->from($this->table_rka);
			$this->db->where($this->table_rka.".id_skpd", $id_skpd);

			if ($detail) {
				$this->db->select("nama_skpd");
				$this->db->join("m_skpd","tx_rka_perubahan.id_skpd = m_skpd.id_skpd","inner");
			}

			$result = $this->db->get();
			return $result->row();
		}

	function get_history($id, $tahun){
			$query = "SELECT a.*,b.status
		              FROM tx_rka_history_perubahan a
					  INNER JOIN m_status_tx b ON a.id_status=b.id
					  INNER JOIN tx_rka_prog_keg_perubahan c ON a.id_rka = c.id
					  WHERE a.id_rka = ?
					  AND c.tahun = ?
				     ";
			$data = array($id, $tahun);
			$result = $this->db->query($query, $data);
			return $result->result();
		}

		function get_kendali_renja($id,$tahun){
			$query = "SELECT id,kesesuaian, hasil_kendali, tindak_lanjut, hasil_tl
		              FROM tx_rka_prog_keg_perubahan
					  WHERE id = ?
					  AND tahun = ?
				     ";
			$data = array($id,$tahun);
			$result = $this->db->query($query, $data);
			if($result){
                $result = $result->row();
                return $result;
            }
            return 0;
		}

		function add_kendali_renja($data, $id){
		$this->db->trans_strict(FALSE);
		$this->db->trans_start();

		$data = $this->global_function->add_array($data);

		$this->db->where('id', $id);
		$result = $this->db->update($this->table_program_kegiatan, $data);

		$kendali = $this->get_one_kendali($id);
		if ($kendali->id_status == '3') {
			$this->update_status($kendali->id,'1');
			$this->add_history_renja($id, $this->id_status_revisi,'data telah direvisi skpd');
		} else {
			$this->add_history_renja($id, $this->id_status_baru);
		}

		$this->db->trans_complete();
		return $this->db->trans_status();
	}

	private function add_history_renja($id_rka, $status, $keterangan=NULL){
		$history = array('id_rka' => $id_rka, 'id_status' => $status, 'create_date'=>date("Y-m-d H:i:s"),
		'user'=>$this->session->userdata('username'));
		if (!empty($keterangan)) {
			$history['keterangan'] = $keterangan;
		}
		$result = $this->db->insert($this->history_renja, $history);
		return $result;
	}

		function get_one_kendali($id){
			$query = "SELECT *
		              FROM tx_rka_prog_keg_perubahan
					  WHERE id = ?
				     ";
			$data = array($id);
			$result = $this->db->query($query, $data);
			if($result){
                $result = $result->row();
                return $result;
            }
            return 0;
	}

		function update_status($id, $id_status)
     {
		$this->db->set('id_status',$id_status);
		$this->db->where('id', $id);
		$result=$this->db->update('tx_rka_prog_keg_perubahan');
		return $result;
	 }

//===================================================================================================================
	//proses verifikasi kendali renja
	function get_all_renja_veri(){
		$ta = $this->m_settings->get_tahun_anggaran();

		$query = "
		SELECT tx_rka_prog_keg_perubahan.*, m_skpd.*, COUNT(tx_rka_prog_keg_perubahan.id) AS jum_semua,
		       SUM(IF(tx_rka_prog_keg_perubahan.id_status=?,1,0)) AS jum_dikirim
	    FROM tx_rka_prog_keg_perubahan
		INNER JOIN m_skpd ON tx_rka_prog_keg_perubahan.id_skpd=m_skpd.id_skpd
		WHERE tx_rka_prog_keg_perubahan.tahun=?
		AND tx_rka_prog_keg_perubahan.`id_status`='2'
		GROUP BY m_skpd.id_skpd";
		$data = array($this->id_status_send, $ta);
		$result = $this->db->query($query, $data);
		return $result->result();
	}

	function get_data_renja($id_skpd){
		$ta = $this->m_settings->get_tahun_anggaran();

		$query = "SELECT pro.*,
						   SUM(keg.nominal) AS sum_nominal,
						   SUM(keg.nominal_thndpn) AS sum_nominal_thndpn,
						   SUM(keg.nomrenja) AS sum_nomrenja,
						   SUM(keg.nomrenja_thndpn) AS sum_nomrenja_thndpn
					FROM
						(SELECT a.`id`, a.`tahun`, a.`kd_urusan`, a.`kd_bidang`, a.`kd_program`, a.`kd_kegiatan`, a.`nama_prog_or_keg`,
								a.`nominal`, a.`nominal_thndpn`, b.`nominal` AS nomrenja, b.`nominal_thndpn` AS nomrenja_thndpn, a.`id_skpd`,
								a.kesesuaian,a.hasil_kendali,a.tindak_lanjut,a.hasil_tl,a.id_status
						 FROM tx_rka_prog_keg_perubahan a
						 LEFT JOIN t_renja_prog_keg_perubahan b ON a.`kd_urusan`=b.`kd_urusan`
													  AND a.`kd_bidang`=b.`kd_bidang`
													  AND a.`kd_program`=b.`kd_program`
													  AND a.`kd_kegiatan`=b.`kd_kegiatan`
													  AND a.`is_prog_or_keg`=b.`is_prog_or_keg`
						 WHERE a.is_prog_or_keg=1
						 GROUP BY a.`id`) AS pro
					INNER JOIN
						(SELECT a.`id`, a.`id_skpd`,a.`tahun`, a.`kd_urusan`, a.`kd_bidang`, a.`kd_program`, a.`kd_kegiatan`, a.`parent`,
								a.`nominal`, a.`nominal_thndpn`, b.`nominal` AS nomrenja, b.`nominal_thndpn` AS nomrenja_thndpn,
								a.kesesuaian,a.hasil_kendali,a.tindak_lanjut,a.hasil_tl,a.id_status
						 FROM tx_rka_prog_keg_perubahan a
						 LEFT JOIN t_renja_prog_keg_perubahan b ON a.`kd_urusan`=b.`kd_urusan`
													  AND a.`kd_bidang`=b.`kd_bidang`
													  AND a.`kd_program`=b.`kd_program`
													  AND a.`kd_kegiatan`=b.`kd_kegiatan`
													  AND a.`is_prog_or_keg`=b.`is_prog_or_keg`
						 WHERE a.is_prog_or_keg=2
						 GROUP BY a.`kd_urusan`, a.`kd_bidang`, a.`kd_program`, a.`kd_kegiatan`,a.`id`) AS keg ON keg.parent=pro.id
					WHERE
						keg.id_skpd=?
					AND keg.tahun = ?
					GROUP BY pro.id
					ORDER BY pro.`kd_urusan`, pro.`kd_bidang`, pro.`kd_program`";
		$result = $this->db->query($query, array($id_skpd, $ta, $this->id_status_send));
		return $result->result();
	}

	/*function disapprove_renja($id){
		$this->db->trans_strict(FALSE);
		$this->db->trans_start();

		$query = "UPDATE tx_rka_prog_keg
		          SET tx_rka_prog_keg.id_status=3
				  WHERE tx_rka_prog_keg.id_skpd=?
				  AND tx_rka_prog_keg.id_status=?";

		$data = array($id, $this->id_status_send);
		$result = $this->db->query($query, $data);
		$this->add_history_renja($id, $this->id_status_revisi,'data tidak valid');

		$this->db->trans_complete();
		return $this->db->trans_status();
	}*/

	//proses verifikasi kendali belanja
	function get_all_belanja_veri(){
		$ta = $this->m_settings->get_tahun_anggaran();

		$query = "
		SELECT tx_dpa_prog_keg_perubahan.*, m_skpd.*, COUNT(tx_dpa_prog_keg_perubahan.id) AS jum_semua,
		       SUM(IF(tx_dpa_prog_keg_perubahan.id_status=?,1,0)) AS jum_dikirim
	    FROM tx_dpa_prog_keg_perubahan
		INNER JOIN m_skpd ON tx_dpa_prog_keg_perubahan.id_skpd=m_skpd.id_skpd
		WHERE tx_dpa_prog_keg_perubahan.is_prog_or_keg=?
		AND tx_dpa_prog_keg_perubahan.tahun=?
		AND tx_dpa_prog_keg_perubahan.`id_status`='2'
		GROUP BY m_skpd.id_skpd";
		$data = array($this->id_status_send, $this->is_kegiatan, $ta);
		$result = $this->db->query($query, $data);
		return $result->result();
	}

	function get_data_belanja($id_skpd){
		$ta = $this->m_settings->get_tahun_anggaran();

		$query = "SELECT pro.*
				FROM
					(SELECT * FROM tx_dpa_prog_keg_perubahan WHERE is_prog_or_keg=1) AS pro
				INNER JOIN
					(SELECT * FROM tx_dpa_prog_keg_perubahan WHERE is_prog_or_keg=2) AS keg ON keg.parent=pro.id
				WHERE
					keg.id_skpd=?
				AND keg.tahun = ?
				GROUP BY pro.id";
		$result = $this->db->query($query, array($id_skpd, $ta, $this->id_status_send));
		return $result->result();
	}

	function disapprove_belanja($id){
		$this->db->trans_strict(FALSE);
		$this->db->trans_start();

		$query = "UPDATE tx_dpa_prog_keg_perubahan
		          SET tx_dpa_prog_keg_perubahan.id_status=3
				  WHERE tx_dpa_prog_keg_perubahan.id_skpd=?
				  AND tx_dpa_prog_keg_perubahan.id_status=?";

		$data = array($id, $this->id_status_send);
		$result = $this->db->query($query, $data);

		$this->db->trans_complete();
		return $this->db->trans_status();
	}
}
?>
