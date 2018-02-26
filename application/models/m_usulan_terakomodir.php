<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class M_usulan_terakomodir extends CI_Model
{
	public function __construct()
    {
        parent::__construct();
    }
	
	function get_urusan_usulan($ta){
		//
		$query = "SELECT t.*,u.`Nm_Urusan` AS nama_urusan FROM (
					SELECT *, SUM(jumlah_dana) AS sum_jumlah_dana FROM t_musrenbang
					WHERE tahun = ".$ta."
						AND id_status_usulan >= 3
						AND id_keputusan = 2
						AND flag_delete = 0
					GROUP BY kd_urusan
					ORDER BY kd_urusan ASC,
						kd_bidang ASC,
						kd_program ASC,
						kd_kegiatan ASC
				) AS t
				LEFT JOIN m_urusan AS u ON t.kd_urusan = u.`Kd_Urusan`";
		$result = $this->db->query($query);
		return $result->result();
	}
	
	function get_bidang_usulan($ta,$kode_urusan){
		$query = "
			SELECT t.*, b.Nm_Bidang AS nama_bidang FROM (
				SELECT *, SUM(jumlah_dana) AS sum_jumlah_dana FROM t_musrenbang
				WHERE tahun = ".$ta."
					AND kd_urusan = ".$kode_urusan."
					AND id_status_usulan >= 3
					AND id_keputusan = 2
					AND flag_delete = 0
				GROUP BY kd_bidang
				ORDER BY kd_urusan ASC,
					kd_bidang ASC,
					kd_program ASC,
					kd_kegiatan ASC
			) AS t
			LEFT JOIN m_bidang AS b ON t.kd_urusan = b.Kd_Urusan AND t.kd_bidang = b.Kd_Bidang
		";
		$result = $this->db->query($query);
		return $result->result();
	}
	
	function get_program_usulan($ta,$kode_urusan,$kode_bidang){
		$query = "
			SELECT t.*, p.`Ket_Program` AS nama_program FROM (
				SELECT *, SUM(jumlah_dana) AS sum_jumlah_dana FROM t_musrenbang
				WHERE tahun = ".$ta."
					AND kd_urusan = ".$kode_urusan."
					AND kd_bidang = ".$kode_bidang."
					AND id_status_usulan >= 3
					AND id_keputusan = 2
					AND flag_delete = 0
				GROUP BY kd_program
				ORDER BY kd_urusan ASC,
					kd_bidang ASC,
					kd_program ASC,
					kd_kegiatan ASC
			) AS t
			LEFT JOIN m_program AS p 
				ON t.kd_urusan = p.`Kd_Urusan` 
				AND t.kd_bidang = p.`Kd_Bidang`
				AND t.kd_program = p.`Kd_Prog`;
		";
		$result = $this->db->query($query);
		return $result->result();
	}
	
	function get_kegiatan_usulan($ta,$kode_urusan,$kode_bidang,$kode_program){
		$query = "SELECT t.*, k.`Ket_Kegiatan` AS nama_kegiatan,
						des.`nama_desa` AS nama_desa, kec.`nama_kec` AS nama_kecamatan,
						skpd.`nama_skpd` AS nama_skpd, au.`nama` AS asal_usulan
				  FROM (
						SELECT * FROM t_musrenbang
						WHERE tahun = ".$ta."
							AND kd_urusan = ".$kode_urusan."
							AND kd_bidang = ".$kode_bidang."
							AND kd_program = ".$kode_program."
							AND id_status_usulan >= 3
							AND id_keputusan = 2
							AND flag_delete = 0
						ORDER BY kd_urusan ASC,
							kd_bidang ASC,
							kd_program ASC,
							kd_kegiatan ASC
					) AS t
					LEFT JOIN m_kegiatan AS k 
						ON t.kd_urusan = k.`Kd_Urusan` 
						AND t.kd_bidang = k.`Kd_Bidang`
						AND t.kd_program = k.`Kd_Prog`
						AND t.kd_kegiatan = k.`Kd_Keg`
					LEFT JOIN m_desa AS des
						ON t.id_desa = des.`id_desa`
					LEFT JOIN m_kecamatan AS kec
						ON t.id_kecamatan = kec.`id_kec`
					LEFT JOIN m_skpd AS skpd
						ON t.id_skpd = skpd.`id_skpd`
					LEFT JOIN m_asal_usulan AS au
						ON t.id_asal_usulan = au.`id`;
			";
			$result = $this->db->query($query);
			return $result->result();
	}
}
?>