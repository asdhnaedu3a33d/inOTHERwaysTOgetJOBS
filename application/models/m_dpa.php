<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_dpa extends CI_Model
{
	var $table_rka = 't_rka';
	var $table = 'tx_dpa';
	var $table_urusan = 'm_urusan';
	var $table_bidang = 'm_bidang';
	var $table_program = 'm_program';
	var $table_kegiatan = 'm_kegiatan';
	var $primary_rka = 'id_rka';

	var $table_program_kegiatan = 'tx_dpa_prog_keg';
	var $table_indikator_program = 'tx_dpa_indikator_prog_keg';
	var $is_program = 1;
	var $is_kegiatan = 2;

	var $id_status_baru = "1";
	var $id_status_send = "2";
	var $id_status_revisi = "3";
	var $id_status_approved = "4";

	function count_jendela_kontrol($id_skpd,$ta){
		if($this->session->userdata("id_skpd") > 100){
			$id_skpd = $this->session->userdata("id_skpd");
			$search = "AND tx_dpa_prog_keg.id_skpd in (SELECT id_skpd FROM m_asisten_sekda WHERE id_asisten = '$id_skpd')";
		}else {
			$kode_unit = $this->session->userdata("id_skpd");
			if ($id_skpd == $kode_unit) {
				$search = "AND tx_dpa_prog_keg.id_skpd in (SELECT id_skpd FROM m_skpd WHERE kode_unit = '$id_skpd')";
			}else {
				$search = "AND (tx_dpa_prog_keg.id_skpd = '$id_skpd' OR tx_dpa_prog_keg.id_skpd = '$kode_unit')";
			}
		}
		$query = "SELECT 
						SUM(IF(tx_dpa_prog_keg.id_status=?, 1, 0)) as baru,
						SUM(IF(tx_dpa_prog_keg.id_status>=?, 1, 0)) as kirim,
						SUM(IF(tx_dpa_prog_keg.id_status>?, 1, 0)) as proses,
						SUM(IF(tx_dpa_prog_keg.id_status=?, 1, 0)) as revisi,
						SUM(IF(tx_dpa_prog_keg.id_status>=?, 1, 0)) as veri 
					FROM 
						tx_dpa_prog_keg 
					WHERE 
						( id IN (SELECT id FROM tx_dpa_prog_keg WHERE tx_dpa_prog_keg.is_prog_or_keg = 2 and id IN (SELECT id_keg FROM tx_dpa_belanja_kegiatan WHERE subtotal > 0 
						 and tahun='$ta') OR id 
						 IN (SELECT parent FROM tx_dpa_prog_keg WHERE id IN (SELECT id_keg FROM tx_dpa_belanja_kegiatan 
						 WHERE subtotal > 0 and tahun='$ta') GROUP BY parent)))						
						AND tahun = ? ".$search;
		$data = array(
					$this->id_status_baru,
					$this->id_status_send,
					$this->id_status_send,
					$this->id_status_revisi,
					$this->id_status_approved,
					$ta, $this->is_kegiatan);
		$result = $this->db->query($query, $data);
		// print_r($this->db->last_query());
		// exit();
		return $result->row();
	}

	function get_dpa($id_skpd,$ta)
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
	function get_all_program($id_skpd,$ta){
		if ($this->session->userdata("id_skpd") > 100) {
			$id_skpd = $this->session->userdata("id_skpd");
			$query = "SELECT * FROM (`$this->table_program_kegiatan`)
			WHERE `id_skpd` in (SELECT id_skpd FROM m_asisten_sekda WHERE id_asisten = '$id_skpd')
			AND `tahun` = '$ta' AND `is_prog_or_keg` = $this->is_program
			AND id in 
			(SELECT tx_dpa_prog_keg.parent FROM tx_dpa_prog_keg WHERE id IN (SELECT id_keg FROM tx_dpa_belanja_kegiatan WHERE subtotal > 0 and `tahun` = '$ta'  ) 
			GROUP BY parent)
			ORDER BY `kd_urusan` asc, `kd_bidang` asc, `kd_program` asc";

			$result = $this->db->query($query);
		}else {
			$cek = $this->m_skpd->get_kode_unit($id_skpd);
			if ($cek == $id_skpd) {
				$query = "SELECT * FROM (`$this->table_program_kegiatan`)
				WHERE `id_skpd` IN 
				(SELECT id_skpd FROM m_skpd WHERE kode_unit =  '$id_skpd')
				AND `tahun` = '$ta' AND `is_prog_or_keg` = $this->is_program
				AND id in 
				(SELECT tx_dpa_prog_keg.parent FROM tx_dpa_prog_keg WHERE id IN (SELECT id_keg FROM tx_dpa_belanja_kegiatan WHERE subtotal > 0 and `tahun` = '$ta' )
				GROUP BY parent)
				ORDER BY `kd_urusan` asc, `kd_bidang` asc, `kd_program` asc";

				$result = $this->db->query($query);
			}else{

				$query = "SELECT * FROM (`$this->table_program_kegiatan`)
				WHERE `id_skpd` = '$id_skpd'
				AND `tahun` = '$ta' AND `is_prog_or_keg` = $this->is_program
				AND id in 
				(SELECT tx_dpa_prog_keg.parent FROM tx_dpa_prog_keg WHERE id IN (SELECT id_keg FROM tx_dpa_belanja_kegiatan WHERE subtotal > 0 and `tahun` = '$ta' )
				GROUP BY parent)
				ORDER BY `kd_urusan` asc, `kd_bidang` asc, `kd_program` asc";

				$result = $this->db->query($query);
			}
			
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

	function insert_dpa($id_skpd, $ta){
		$created_date = date("Y-m-d H:i:s");
		$created_by = $this->session->userdata('username');
		$this->db->set('id_skpd', $id_skpd);
		$this->db->set('tahun', $ta);
		$this->db->set('created_date', $created_date);
		$this->db->set('created_by', $created_by);
		$this->db->insert('tx_dpa');
		return $this->db->insert_id();
	}

	function get_dpa_belanja_per_tahun($id, $is_tahun){
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
							) AS belanja,
							uraian_belanja,detil_uraian_belanja,volume,satuan,nominal_satuan,subtotal,id_keg
							FROM tx_dpa_belanja_kegiatan
							WHERE tahun = '$is_tahun' AND id_keg = '$id'
							ORDER BY kode_jenis_belanja ASC, kode_kategori_belanja ASC, kode_sub_kategori_belanja ASC, kode_belanja ASC");
		return $query->result();
	}

	function get_dpa_belanja_per_tahun221($ta, $idK){
		//------- query by deesudi
			$query = $this->db->query("SELECT id ,tahun,
							kode_sumber_dana AS kode_sumber_dana,(
								SELECT nama FROM m_sumberdana WHERE id_sumberdana = kode_sumber_dana
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
							SELECT (nominal_1 + nominal_2 + nominal_3 + nominal_4 ) FROM `tx_dpa_prog_keg` WHERE id = '$idK'
							) AS nominal_tahun,

							uraian_belanja,detil_uraian_belanja,volume,satuan,nominal_satuan,subtotal,tahun,id_keg , kode_urusan , kode_bidang , kode_program, kode_kegiatan
							FROM tx_dpa_belanja_kegiatan
							WHERE tahun = '$ta' and id_keg = '$idK'
							ORDER BY kode_jenis_belanja ASC, kode_kategori_belanja ASC, kode_sub_kategori_belanja ASC, kode_belanja ASC");
		return $query->result();
	}

	function get_indikator_keluaran($ta, $idK){
			$query = $this->db->query("SELECT * FROM `tx_dpa_indikator_prog_keg` WHERE   `id_prog_keg` = '$idK'");
		return $query->result();
	}

	function get_indikator_capaian( $idP){
			$query = $this->db->query("SELECT * FROM `tx_dpa_indikator_prog_keg` WHERE   `id_prog_keg` = '$idP'");
		return $query->result();
	}
	function get_nominal_dpa( $Idk,$ta){
			$query = $this->db->query("SELECT  tahun, SUM(subtotal) AS nominal FROM `tx_dpa_belanja_kegiatan` WHERE id_keg = '$Idk' AND tahun = '$ta'");
			$data['tahun_n'] =  $query->row();

			$ta_min  = $ta -1 ;
			$query_min = $this->db->query("SELECT  tahun, SUM(subtotal) AS nominal FROM `tx_dpa_belanja_kegiatan` WHERE id_keg = '$Idk' AND tahun = '$ta_min'");
			$data['tahun_min'] =  $query_min->row();


			$ta_plus  = $ta +1 ;
			$query_plus = $this->db->query("SELECT  tahun, SUM(subtotal) AS nominal FROM `tx_dpa_belanja_kegiatan` WHERE id_keg = '$Idk' AND tahun = '$ta_plus'");
			$data['tahun_plus'] =  $query_plus->row();

			return $data ;
	}


	function import_from_rka($id_skpd, $ta){
		$this->db->trans_strict(FALSE);
		$this->db->trans_start();


		# For program #
		$query="SELECT
					$ta AS tahun,
					tx_rka_prog_keg.id AS id_rka,
					is_prog_or_keg,
					kd_urusan,
					kd_bidang,
					kd_program,
					kd_kegiatan,
					nama_prog_or_keg,
					lokasi,
					penanggung_jawab,
					tx_rka_prog_keg.id_skpd,
					tx_rka_prog_keg.id_prog_rpjmd
				FROM tx_rka_prog_keg WHERE tx_rka_prog_keg.is_prog_or_keg=1 AND tahun=$ta AND tx_rka_prog_keg.id_skpd in (SELECT id_skpd FROM m_skpd WHERE kode_unit = ?)";
		$result = $this->db->query($query, $id_skpd);
		$dpa_baru = $result->result_array();

		foreach ($dpa_baru as $row) {
			$this->db->insert("tx_dpa_prog_keg", $row);
			$new_id = $this->db->insert_id();

			$query = "INSERT INTO tx_dpa_indikator_prog_keg(id_prog_keg, indikator, satuan_target, status_indikator, kategori_indikator, target, id_indikator_rka)
			SELECT ?, indikator, satuan_target, status_indikator, kategori_indikator, target, tx_rka_indikator_prog_keg.id
			FROM tx_rka_indikator_prog_keg WHERE id_prog_keg=?";
			$result = $this->db->query($query, array($new_id, $row['id_rka']));

			# For kegiatan #
			$query="SELECT
					$ta AS tahun,
					tx_rka_prog_keg.id AS id_rka,
					is_prog_or_keg,
					kd_urusan,
					kd_bidang,
					kd_program,
					kd_kegiatan,
					nama_prog_or_keg,
					lokasi,
					penanggung_jawab,
					tx_rka_prog_keg.id_skpd,
					tx_rka_prog_keg.id_prog_rpjmd,
					? AS parent
				FROM tx_rka_prog_keg WHERE tx_rka_prog_keg.is_prog_or_keg=2 AND tahun=$ta AND tx_rka_prog_keg.parent=? AND tx_rka_prog_keg.id_skpd in (SELECT id_skpd FROM m_skpd WHERE kode_unit = ?)";
			$result = $this->db->query($query, array($new_id, $row['id_rka'], $id_skpd));
			$kegiatan_dpa_baru = $result->result_array();

			foreach ($kegiatan_dpa_baru as $row1) {
				$id_rka_nya = $row1['id_rka'];

				$this->db->insert("tx_dpa_prog_keg", $row1);
				$new_id = $this->db->insert_id();

				$query = "INSERT INTO tx_dpa_indikator_prog_keg(id_prog_keg, indikator, satuan_target, status_indikator, kategori_indikator, target, id_indikator_rka)
				SELECT ?, indikator,satuan_target, status_indikator, kategori_indikator, target, tx_rka_indikator_prog_keg.id
				FROM tx_rka_indikator_prog_keg WHERE id_prog_keg=?";
				$result = $this->db->query($query, array($new_id, $row1['id_rka']));

				//isert belanja disini
				$query2 = " INSERT INTO `tx_dpa_belanja_kegiatan`
									( `id_rka`,
										`tahun`,
										`kode_urusan`,
										`kode_bidang`,
										`kode_program`,
										`kode_kegiatan`,
										`kode_sumber_dana`,
										`kode_jenis_belanja`,
										`kode_kategori_belanja`,
										`kode_sub_kategori_belanja`,
										`kode_belanja`,
										`uraian_belanja`,
										`detil_uraian_belanja`,
										`volume`,
										`satuan`,
										`nominal_satuan`,
										`subtotal`,
										`created_date`,
										`id_keg`)
									SELECT
										'$id_rka_nya',
										`tahun`,
										`kode_urusan`,
										`kode_bidang`,
										`kode_program`,
										`kode_kegiatan`,
										`kode_sumber_dana`,
										`kode_jenis_belanja`,
										`kode_kategori_belanja`,
										`kode_sub_kategori_belanja`,
										`kode_belanja`,
										`uraian_belanja`,
										`detil_uraian_belanja`,
										`volume`,
										`satuan`,
										`nominal_satuan`,
										`subtotal`,
										now(),
										$new_id
									FROM tx_rka_belanja_kegiatan
									WHERE id_keg= '$id_rka_nya' AND is_tahun_sekarang = 1
								";
				$result2 = $this->db->query($query2);


			}
		}

		$this->db->trans_complete();
		return $this->db->trans_status();
	}

	function get_indikator_prog_keg($id, $return_result=TRUE, $satuan=FALSE){
		$this->db->select($this->table_indikator_program.".*, satuan_target as nama_value");
		$this->db->where('id_prog_keg', $id);
		$this->db->from($this->table_indikator_program);

		if ($satuan) {
			// $this->db->select("m_lov.nama_value");
			$this->db->select("m_status_indikator.nama_status_indikator");
			$this->db->select("m_kategori_indikator.nama_kategori_indikator");
			// $this->db->join("m_lov",$this->table_indikator_program.".satuan_target = m_lov.kode_value AND kode_app='1'","inner");
			$this->db->join("m_status_indikator",$this->table_indikator_program.".status_indikator = m_status_indikator.kode_status_indikator","inner");
			$this->db->join("m_kategori_indikator",$this->table_indikator_program.".kategori_indikator = m_kategori_indikator.kode_kategori_indikator","inner");
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
			AND `is_prog_or_keg` = $this->is_kegiatan
			AND id in 
			(SELECT tx_dpa_prog_keg.id FROM tx_dpa_prog_keg WHERE id IN (SELECT id_keg FROM tx_dpa_belanja_kegiatan WHERE subtotal > 0 and `tahun` = '$ta' ))
			ORDER BY `kd_urusan` asc, `kd_bidang` asc, `kd_program` asc, `kd_kegiatan` asc";

			$result = $this->db->query($query);
		}else {
			$cek = $this->m_skpd->get_kode_unit($id_skpd);
			if ($cek == $id_skpd) {
				$query = "SELECT * FROM (`$this->table_program_kegiatan`)
				WHERE `id_skpd` in (SELECT id_skpd FROM m_skpd WHERE kode_unit = '$id_skpd')
				AND `tahun` = '$ta' AND parent = $id
				AND `is_prog_or_keg` = $this->is_kegiatan
				AND id in 
				(SELECT tx_dpa_prog_keg.id FROM tx_dpa_prog_keg WHERE id IN (SELECT id_keg FROM tx_dpa_belanja_kegiatan WHERE subtotal > 0 and `tahun` = '$ta' ))
				ORDER BY `kd_urusan` asc, `kd_bidang` asc, `kd_program` asc, `kd_kegiatan` asc";

				$result = $this->db->query($query);
			}else {
				$id_keg = "";
				foreach ($this->db->query("SELECT id_keg FROM tx_dpa_belanja_kegiatan WHERE subtotal > 0 AND `tahun` = '$ta'")->result() as $row) {
					$id_keg[] = $row->id_keg;
				}

				// print_r($id_keg);
				// exit();


				$this->db->select($this->table_program_kegiatan.".*");
				$this->db->where('id_skpd', $id_skpd);
				$this->db->where('tahun', $ta);
				$this->db->where('parent', $id);
				$this->db->where('is_prog_or_keg', $this->is_kegiatan);

				$this->db->where_in('id', $id_keg);
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

	function add_program_skpd($data, $indikator, $satuan_target, $status_indikator, $kategori_indikator, $target, $id_for_rencana){
		$this->db->trans_strict(FALSE);
		$this->db->trans_start();

		$add = array('is_prog_or_keg'=> $this->is_program);
		$data = $this->global_function->add_array($data, $add);

		$this->db->insert($this->table_program_kegiatan, $data);

		$id = $this->db->insert_id();

		$this->db->query('UPDATE tx_dpa_rencana_aksi SET id_dpa_prog_keg = "'.$id.'" WHERE id_dpa_prog_keg = "'.$id_for_rencana.'"');

		foreach ($indikator as $key => $value) {
			$this->db->insert($this->table_indikator_program, array('id_prog_keg' => $id, 'indikator' => $value, 'satuan_target' => $satuan_target[$key],
			'status_indikator' => $status_indikator[$key], 'kategori_indikator' => $kategori_indikator[$key], 'target' => $target[$key]));
		}

		$this->db->trans_complete();
		return $this->db->trans_status();
	}

	function edit_program_skpd($data, $id_program, $indikator, $id_indikator_program, $satuan_target, $status_indikator, $kategori_indikator, $target, $id_for_rencana){
		$this->db->trans_strict(FALSE);
		$this->db->trans_start();

		$add = array('is_prog_or_keg'=> $this->is_program);
		$data = $this->global_function->add_array($data, $add);

		$this->db->where('id', $id_program);
		$result = $this->db->update($this->table_program_kegiatan, $data);

		$this->db->query('UPDATE tx_dpa_rencana_aksi SET id_dpa_prog_keg = "'.$id_program.'" WHERE id_dpa_prog_keg = "'.$id_for_rencana.'"');

		foreach ($indikator as $key => $value) {
			if (!empty($id_indikator_program[$key])) {
				$this->db->where('id', $id_indikator_program[$key]);
				$this->db->where('id_prog_keg', $id_program);
				$this->db->update($this->table_indikator_program, array('indikator' => $value, 'satuan_target' => $satuan_target[$key],
					'status_indikator' => $status_indikator[$key], 'kategori_indikator' => $kategori_indikator[$key], 'target' => $target[$key]));
				unset($id_indikator_program[$key]);
			}else{
				$this->db->insert($this->table_indikator_program, array('id_prog_keg' => $id_program, 'indikator' => $value, 'satuan_target' => $satuan_target[$key],
				'status_indikator' => $status_indikator[$key], 'kategori_indikator' => $kategori_indikator[$key], 'target' => $target[$key]));
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

	function add_kegiatan_skpd($data, $indikator, $satuan_target, $status_indikator, $kategori_indikator, $target, $id_for_rencana){
		$this->db->trans_strict(FALSE);
		$this->db->trans_start();

		$KodeUrusan = $data['kd_urusan'];
		$KodeBidang = $data['kd_bidang'];
		$kodeProgram = $data['kd_program'];
		$KodeKegiatan = $data['kd_kegiatan'];
		$thnskr = $dataKegiatan1['tahun'];
		$created_date =  date("Y-m-d H:i:s");

		$add = array('is_prog_or_keg'=> $this->is_kegiatan, 'id_status'=> $this->id_status_baru);
		$data = $this->global_function->add_array($data, $add);

		$this->db->insert($this->table_program_kegiatan, $data);

		$id = $this->db->insert_id();
		foreach ($indikator as $key => $value) {
			$this->db->insert($this->table_indikator_program, array('id_prog_keg' => $id, 'indikator' => $value, 'satuan_target' => $satuan_target[$key],
			'status_indikator' => $status_indikator[$key], 'kategori_indikator' => $kategori_indikator[$key],'target' => $target[$key]));
		}

		$this->db->query('UPDATE tx_dpa_rencana_aksi SET id_dpa_prog_keg = "'.$id.'" WHERE id_dpa_prog_keg = "'.$id_for_rencana.'"');


		// $banyakData1 = count($dataKegiatan1['kode_sumber_dana']);
		// for($i =1; $i <= $banyakData1; ++$i) {
		// 		$datatahun1_batch[] = array(
		// 			'tahun'=>$thnskr,
		// 			'kode_urusan'=>$KodeUrusan,
		// 			'kode_bidang' => $KodeBidang,
		// 			'kode_program' => $kodeProgram,
		// 			'id_keg' => $id,
		// 			'kode_kegiatan' => $KodeKegiatan,
		// 			'kode_sumber_dana' => $dataKegiatan1['kode_sumber_dana'][$i],
		// 			'kode_jenis_belanja' => $dataKegiatan1['kode_jenis_belanja'][$i],
		// 			'kode_kategori_belanja' => $dataKegiatan1['kode_kategori_belanja'][$i],
		// 			'kode_sub_kategori_belanja' => $dataKegiatan1['kode_sub_kategori_belanja'][$i],
		// 			'kode_belanja' => $dataKegiatan1['kode_belanja'][$i],
		// 			'uraian_belanja' => $dataKegiatan1['uraian_belanja'][$i],
		// 			'detil_uraian_belanja' => $dataKegiatan1['detil_uraian_belanja'][$i],
		// 			'volume' => $dataKegiatan1['volume'][$i],
		// 			'nominal_satuan' => $dataKegiatan1['nominal_satuan'][$i],
		// 			'satuan' => $dataKegiatan1['satuan'][$i],
		// 			'subtotal' => $dataKegiatan1['subtotal'][$i],
		// 			'created_date' => $created_date
		// 			)	;
		// }

		// $this->db->insert_batch('tx_dpa_belanja_kegiatan', $datatahun1_batch);

		$this->db->trans_complete();
		return $this->db->trans_status();
	}

	function edit_kegiatan_skpd($data, $id_kegiatan, $indikator, $id_indikator_kegiatan, $satuan_target, $status_indikator, $kategori_indikator, $target, $id_for_rencana){
		$this->db->trans_strict(FALSE);
		$this->db->trans_start();

		$add = array('is_prog_or_keg'=> $this->is_kegiatan);
		$data = $this->global_function->add_array($data, $add);

		$this->db->where('id', $id_kegiatan);
		$result = $this->db->update($this->table_program_kegiatan, $data);

		$this->db->query('UPDATE tx_dpa_rencana_aksi SET id_dpa_prog_keg = "'.$id_kegiatan.'" WHERE id_dpa_prog_keg = "'.$id_for_rencana.'"');

		foreach ($indikator as $key => $value) {
			if (!empty($id_indikator_kegiatan[$key])) {
				$this->db->where('id', $id_indikator_kegiatan[$key]);
				$this->db->where('id_prog_keg', $id_kegiatan);
				$this->db->update($this->table_indikator_program, array('indikator' => $value, 'satuan_target' => $satuan_target[$key],
				'status_indikator' => $status_indikator[$key], 'kategori_indikator' => $kategori_indikator[$key], 'target' => $target[$key]));
				unset($id_indikator_kegiatan[$key]);
			}else{
				$this->db->insert($this->table_indikator_program, array('id_prog_keg' => $id_kegiatan, 'indikator' => $value, 'satuan_target' => $satuan_target[$key],
				'status_indikator' => $status_indikator[$key], 'kategori_indikator' => $kategori_indikator[$key], 'target' => $target[$key]));
			}
		}

		if (!empty($id_indikator_kegiatan)) {
			$this->db->where_in('id', $id_indikator_kegiatan);
			$this->db->delete($this->table_indikator_program);
		}

		//$renstra = $this->get_one_kegiatan(NULL, NULL, NULL, $id_kegiatan);
		//$this->update_status_after_edit($renstra->id_renstra, NULL, NULL, $id_kegiatan);

		// $KodeUrusan = $data['kd_urusan'];
		// $KodeBidang = $data['kd_bidang'];
		// $kodeProgram = $data['kd_program'];
		// $KodeKegiatan = $data['kd_kegiatan'];
		// $thnskr = $dataKegiatan1['tahun'];
		// $created_date =  date("Y-m-d H:i:s");

		// $this->db->query("delete from tx_dpa_belanja_kegiatan where id_keg = $id_kegiatan ");

		// $banyakData1 = count($dataKegiatan1['kode_sumber_dana']);
		// for($i =1; $i <= $banyakData1; ++$i) {
		// 		$datatahun1_batch[] = array(
		// 			'tahun'=>$thnskr,
		// 			'kode_urusan'=>$KodeUrusan,
		// 			'kode_bidang' => $KodeBidang,
		// 			'kode_program' => $kodeProgram,
		// 			'id_keg' => $id_kegiatan,
		// 			'kode_kegiatan' => $KodeKegiatan,
		// 			'kode_sumber_dana' => $dataKegiatan1['kode_sumber_dana'][$i],
		// 			'kode_jenis_belanja' => $dataKegiatan1['kode_jenis_belanja'][$i],
		// 			'kode_kategori_belanja' => $dataKegiatan1['kode_kategori_belanja'][$i],
		// 			'kode_sub_kategori_belanja' => $dataKegiatan1['kode_sub_kategori_belanja'][$i],
		// 			'kode_belanja' => $dataKegiatan1['kode_belanja'][$i],
		// 			'uraian_belanja' => $dataKegiatan1['uraian_belanja'][$i],
		// 			'detil_uraian_belanja' => $dataKegiatan1['detil_uraian_belanja'][$i],
		// 			'volume' => $dataKegiatan1['volume'][$i],
		// 			'nominal_satuan' => $dataKegiatan1['nominal_satuan'][$i],
		// 			'satuan' => $dataKegiatan1['satuan'][$i],
		// 			'subtotal' => $dataKegiatan1['subtotal'][$i],
		// 			'created_date' => $created_date
		// 			)	;
		// }

		// $this->db->insert_batch('tx_dpa_belanja_kegiatan', $datatahun1_batch);

		$this->db->trans_complete();
		return $this->db->trans_status();
	}

	function delete_kegiatan($id){
		$this->db->where('id', $id);
		$this->db->where('is_prog_or_keg', $this->is_kegiatan);
		$result = $this->db->delete($this->table_program_kegiatan);
		return $result;
	}

	function add_rka()
	{
		$data = $this->global_function->add_array($data, $add);

		$result = $this->db->insert($this->table_rka, $data);
		return $result;
	}

	function get_data($data,$table){
        $this->db->where($data);
        $query = $this->db->get($this->$table);
        return $query->row();
    }

	function get_rka_by_id($id_rka)
	{
		$sql = "
				SELECT *
				FROM t_rka
				WHERE id_rka = ?
			";

		$query = $this->db->query($sql, array($id_rka));

		if($query) {
			if($query->num_rows() > 0) {
				return $query->row();
			}
		}

		return NULL;
	}

	function simpan_rka($data_rka)
	{
		$this->db->trans_strict(FALSE);
		$this->db->trans_start();


		$data_rka->created_date		= Formatting::get_datetime();
		$data_rka->created_by		= $this->session->userdata('username');

		$this->db->set($data_rka);
    	$this->db->insert('t_rka');

		$this->db->trans_complete();
		return $this->db->trans_status();
	}
    function update_rka($data,$id,$table,$primary) {
        $this->db->where($this->$primary,$id);
        return $this->db->update($this->$table,$data);
    }

	function get_data_table($search, $start, $length, $order)
	{
		$order_arr = array('id_rka','kd_urusan','kd_bidang','kd_program','kd_kegiatan');
		$sql = "
			SELECT * FROM (
				SELECT r.`id_rka`,r.`kd_urusan`,r.`kd_bidang`,r.`kd_program`,r.`kd_kegiatan`,r.`indikator_capaian`,r.`tahun_sekarang`,r.`lokasi`,
				r.`capaian_sekarang`,r.`jumlah_dana_sekarang`,r.`tahun_mendatang`,r.`capaian_mendatang`,r.`jumlah_dana_mendatang`,
				u.`Nm_Urusan` AS nm_urusan, b.`Nm_Bidang` AS nm_bidang, p.`Ket_Program` AS ket_program, k.`Ket_Kegiatan` AS ket_kegiatan
				FROM t_rka AS r
				LEFT JOIN m_urusan AS u ON r.`kd_urusan`=u.`Kd_Urusan`
				LEFT JOIN m_bidang AS b ON r.`kd_urusan`=b.`Kd_Urusan`
										AND r.`kd_bidang`=b.`Kd_Bidang`
				LEFT JOIN m_program AS p ON r.`kd_urusan`=p.`Kd_Urusan`
										AND r.`kd_bidang`=p.`Kd_Bidang`
										AND r.`kd_program`=p.`Kd_Prog`
				LEFT JOIN m_kegiatan AS k ON r.`kd_urusan`=k.`kd_urusan`
										AND r.`kd_bidang`=k.`Kd_Bidang`
										AND r.`kd_program`=k.`Kd_Prog`
										AND r.`kd_kegiatan`=k.`Kd_Keg`
				WHERE (r.kd_urusan LIKE '%".$search['value']."%'
				OR r.kd_bidang LIKE '%".$search['value']."%'
				OR r.kd_program LIKE '%".$search['value']."%'
				OR r.kd_kegiatan LIKE '%".$search['value']."%')
			) AS a
			order by ".$order_arr[$order["column"]]." ".$order["dir"]."
            limit $start,$length
		";
		// $sql="
		// 	SELECT * FROM (
		// 	SELECT r.id_rka
		// 	FROM ".$this->table_rka." AS r
		// 		LEFT JOIN
		// 	WHERE (kd_urusan LIKE '%".$search['value']."%'
  //           OR kd_bidang LIKE '%".$search['value']."%'
  //           OR kd_program LIKE '%".$search['value']."%'
  //           OR kd_kegiatan LIKE '%".$search['value']."%')
		// 	) AS a
		// ";
		//$this->db->limit($length, $start);
		//$this->db->order_by($order_arr[$order["column"]], $order["dir"]);

		$result = $this->db->query($sql);
		return $result->result();
	}

	function count_data_table($search, $start, $length, $order)
	{
		$this->db->from($this->table_rka);

		$this->db->like("kd_urusan", $search['value']);
		$this->db->or_like("kd_bidang", $search['value']);
		$this->db->or_like("kd_program", $search['value']);
		$this->db->or_like("kd_kegiatan", $search['value']);

		$result = $this->db->count_all_results();
		return $result;
	}

	function get_data_with_rincian($id_rka,$table)
	{
		$sql="
			SELECT * FROM ".$this->$table."
			WHERE id_rka = ?
		";

		$query = $this->db->query($sql, array($id_rka));

		if($query) {
				if($query->num_rows() > 0) {
					return $query->row();
				}
			}

			return NULL;
	}

    function delete_rka($id){
   	    $this->db->trans_strict(FALSE);
		$this->db->trans_start();

		$this->db->where('id_rka',$id);
        $this->db->delete('t_rka');


		$this->db->trans_complete();

		return $this->db->trans_status();
    }

	function get_program_skpd_4_cetak($id_skpd,$tahun,$kd_urusan,$kd_bidang)
    {
    	$query = "SELECT
						keg.penanggung_jawab, keg.lokasi,
						pro.*,
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
						keg.id_skpd=? AND
						keg.tahun = ? AND
						keg.kd_urusan = ? AND
						keg.kd_bidang = ? 
					GROUP BY pro.id
					having  ((
						SUM(keg.nominal_1) +
						SUM(keg.nominal_2) +
						SUM(keg.nominal_3) +
						SUM(keg.nominal_4) +
						SUM(keg.nominal_5) +
						SUM(keg.nominal_6) +
						SUM(keg.nominal_7) +
						SUM(keg.nominal_8) +
						SUM(keg.nominal_9) +
						SUM(keg.nominal_10) +
						SUM(keg.nominal_11) +
						SUM(keg.nominal_12)
						)>0)
					ORDER BY kd_urusan ASC, kd_bidang ASC, kd_program ASC, kd_kegiatan ASC";
		$data = array($id_skpd,$tahun,$kd_urusan,$kd_bidang);
		$result = $this->db->query($query, $data);
		return $result->result();
    }

    function get_kegiatan_skpd_4_cetak($id_program){
		$query = "SELECT
						tx_dpa_prog_keg.*
					FROM tx_dpa_prog_keg
					WHERE parent=?
					AND (nominal > 0 OR nominal_thndpn > 0)
					AND id IN (SELECT id_prog_keg FROM tx_dpa_indikator_prog_keg
					WHERE id_prog_keg = tx_dpa_prog_keg.id)
					ORDER BY kd_urusan, kd_bidang, kd_program, kd_kegiatan ASC";
		$data = array($id_program);
		$result = $this->db->query($query, $data);
		return $result;
	}

	function get_total_kegiatan_dan_indikator($id_program){
		$query = "SELECT
						COUNT(*) AS total
					FROM
						tx_dpa_prog_keg
					INNER JOIN
						tx_dpa_indikator_prog_keg ON tx_dpa_indikator_prog_keg.id_prog_keg=tx_dpa_prog_keg.id
					WHERE
						tx_dpa_prog_keg.parent=? OR tx_dpa_prog_keg.id=?";
		$data = array($id_program, $id_program);
		$result = $this->db->query($query, $data);
		return $result->row();
	}

	function get_one_dpa_skpd($id_skpd, $detail=FALSE){
		$this->db->select($this->table.".*");
		$this->db->from($this->table);
		$this->db->where($this->table.".id_skpd", $id_skpd);

		if ($detail) {
			$this->db->select("nama_skpd");
			$this->db->join("m_skpd","tx_dpa.id_skpd = m_skpd.id_skpd","inner");
		}

		$result = $this->db->get();
		return $result->row();
	}

	function get_kegiatan_dpa_4_cetak($id_program,$tahun){
		$query = "SELECT
						tx_dpa_prog_keg.*
					FROM tx_dpa_prog_keg
					WHERE 
					 ((
						nominal_1+
						nominal_2+
						nominal_3+
						nominal_4+
						nominal_5+
						nominal_6+
						nominal_7+
						nominal_8+
						nominal_9+
						nominal_10+
						nominal_11+
						nominal_12
					)>0)
					AND parent=?
					AND tahun = ?
					
					ORDER BY kd_urusan, kd_bidang, kd_program, kd_kegiatan ASC";
		$data = array($id_program,$tahun);
		$result = $this->db->query($query, $data);
		return $result;
	}

	function get_indikator_prog_keg_dpa($id, $return_result=TRUE, $satuan=FALSE){
		$this->db->select($this->table_indikator_program2.".*");
		$this->db->where('id_prog_keg', $id);
		$this->db->where('target >', 0);
		$this->db->from($this->table_indikator_program2);

		if ($satuan) {
			$this->db->select("m_lov.nama_value");
			$this->db->join("m_lov",$this->table_indikator_program2.".satuan_target = m_lov.kode_value AND kode_app='1'","inner");
		}

		$result = $this->db->get();
		if ($return_result) {
			return $result->result();
		}else{
			return $result;
		}
	}

	function get_rencana_aksi($uniq_id, $id_dpa=NULL, $id=NULL, $not_in=FALSE){
		$not = "";
		if ($not_in) {
			$not = " AND id <> '".$id."'";
		}

		if (!empty($id) && $not_in==FALSE) {
			return $this->db->query('SELECT * FROM tx_dpa_rencana_aksi WHERE id = "'.$id.'"')->row();
		}else{
			return $this->db->query('SELECT
				(SELECT SUM(target) AS kum FROM tx_dpa_rencana_aksi AS anak 
				WHERE induk.`aksi`= anak.aksi AND( (anak.bulan = induk.bulan AND anak.get_date<= induk.`get_date` ) 
				OR (anak.`bulan` < induk.`bulan`))  
				AND (id_dpa_prog_keg = "'.$id_dpa.'" OR id_dpa_prog_keg = "'.$uniq_id.'") '.$not.' ) AS kumul 
				, induk.*
			FROM tx_dpa_rencana_aksi AS induk WHERE 
			(id_dpa_prog_keg = "'.$id_dpa.'" OR id_dpa_prog_keg = "'.$uniq_id.'") '.$not.' ORDER BY bulan, get_date ASC')->result();	
		}
	}

	function add_rencana_aksi($data){
		$this->db->insert('tx_dpa_rencana_aksi', $data);
	}

	function edit_rencana_aksi($id, $data){
		$this->db->where('id', $id);
		$this->db->update('tx_dpa_rencana_aksi', $data);
	}

	function delete_rencana_aksi($id){
		$this->db->where('id', $id);
		$this->db->delete('tx_dpa_rencana_aksi');
	}

	function get_sum_anggaran_rencana_aksi($uniq_id, $id_dpa = NULL){
		$result = $this->db->query('SELECT bulan, SUM(anggaran) as anggaran FROM tx_dpa_rencana_aksi
				 WHERE id_dpa_prog_keg = "'.$uniq_id.'" OR id_dpa_prog_keg = "'.$id_dpa.'"
				 GROUP BY bulan ORDER BY bulan ASC');
		return $result->result();
	}

	function get_cetak_rencana_program($id, $bulan=NULL){
		$where = "";
		if ($bulan >= 1 && $bulan <= 12) {
			$where = " AND bulan='".$bulan."'";
		}elseif($bulan === 0){
			$where = "GROUP BY bulan";
		}
		$result = $this->db->query("SELECT * FROM tx_dpa_rencana_aksi WHERE id_dpa_prog_keg = '".$id."' ".$where."  ORDER BY bulan,get_date");
		return $result;
	}

	function sum_anggaran_rencana_aksi($id, $bulan){
		$result = $this->db->query("SELECT SUM(anggaran) as sum_ang FROM tx_dpa_rencana_aksi WHERE id_dpa_prog_keg = '$id' AND bulan = '$bulan' ");
		return $result;
	}

	function get_realisasi_dari_triwulan($id, $bulan){
		$result = $this->db->query("SELECT * FROM tx_dpa_prog_keg_triwulan WHERE id_dpa_prog_keg = '$id' AND id_triwulan = '$bulan' ");
		return $result;
	}



	function get_kegiatan($id_kegiatan, $tahun=NULL, $is_tahun=NULL, $not_in=NULL){
				$th = "";
				$not = "";
				if (!empty($tahun)) {
					$th = " AND tahun = '".$tahun."'";
				}
				if(!empty($not_in)){
					$not = " AND id <> '".$not_in."' ";
				}

				$query = "SELECT id ,tahun,
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
								uraian_belanja, detil_uraian_belanja, volume, satuan, nominal_satuan, subtotal, id_keg
								FROM tx_dpa_belanja_kegiatan
								WHERE id_keg = '$id_kegiatan' ".$th." ".$not." 
								ORDER BY kode_jenis_belanja ASC, kode_kategori_belanja ASC, kode_sub_kategori_belanja ASC, kode_belanja ASC";

		$result = $this->db->query($query);
		return $result->result();
	}

	function get_one_belanja($id_belanja){
		$query = "SELECT id ,tahun, 
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
				uraian_belanja, detil_uraian_belanja, volume, satuan, nominal_satuan, subtotal, id_keg
				FROM tx_dpa_belanja_kegiatan
				WHERE id = '$id_belanja'
				ORDER BY kode_jenis_belanja ASC, kode_kategori_belanja ASC, kode_sub_kategori_belanja ASC, kode_belanja ASC";

		$result = $this->db->query($query);
		return $result->row();
	}

	function add_belanja_kegiatan($data, $id_belanja){
		if (!empty($id_belanja)) {
			$this->db->where('id', $id_belanja);
			$this->db->update('tx_dpa_belanja_kegiatan', $data);
		}else{
			$this->db->insert('tx_dpa_belanja_kegiatan', $data);
		}
	}

	function delete_one_kegiatan($id){
		$this->db->query('DELETE FROM tx_dpa_belanja_kegiatan WHERE id = "'.$id.'"');
	}
}
