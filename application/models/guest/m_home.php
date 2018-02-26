<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_home extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    function get_schedule_all(){
		//$query = "SELECT *, YEAR(date_start) AS tahun_start, MONTH(date_start) AS bulan_start, DAY(date_start) AS day_start, YEAR(date_end) AS tahun_end, MONTH(date_end) AS bulan_end, DAY(date_end) AS day_end FROM t_schedule WHERE YEAR(date_start)=YEAR(DATE_SUB(NOW(),INTERVAL 3 MONTH)) AND YEAR(date_end)=YEAR(DATE_ADD(NOW(),INTERVAL 3 MONTH)) AND MONTH(date_start) >= MONTH(DATE_SUB(NOW(),INTERVAL 3 MONTH)) AND MONTH(date_end) <= MONTH(DATE_ADD(NOW(),INTERVAL 3 MONTH));";
		$query="SELECT id,title, date_start AS start, date_end AS end, CONCAT('javascript:see_schedule(',id,')') AS url FROM t_schedule WHERE date_start >= DATE_FORMAT(DATE_SUB(NOW(),INTERVAL 3 MONTH),'%Y-%m-1') AND date_end <= DATE_FORMAT(DATE_ADD(NOW(),INTERVAL 3 MONTH),'%Y-%m-31');";
		$result = $this->db->query($query);
		return $result->result_array();
	}

	function get_schedule_one($id){
		$query="SELECT * FROM t_schedule WHERE id=?";
		$result = $this->db->query($query, array($id));
		return $result->row();
	}

	function get_berita_all(){
		$query="SELECT * FROM `t_berita` ORDER BY date_cru DESC, id DESC LIMIT 4";
		$result = $this->db->query($query);
		return $result->result();
	}

	function get_berita_one($id){
		$query="SELECT * FROM t_berita WHERE id=?";
		$result = $this->db->query($query, array($id));
		return $result->row();
	}

	function get_file4publik($id_skpd=NULL){
    $where = "id_skpd IS NULL";
    if (!empty($id_skpd)) {
      $where = "id_skpd='". $id_skpd ."'";
    }

		$query="SELECT * FROM `t_file_4_publik` WHERE ". $where ." ORDER BY date_cru DESC, id DESC LIMIT 3";
		$result = $this->db->query($query);
		return $result->result();
	}

	function get_file4publik_one($id){
		$query="SELECT * FROM t_file_4_publik WHERE id=?";
		$result = $this->db->query($query, array($id));
		return $result->row();
	}

	function get_all_berita($search, $start, $length, $order, $order_arr){
		$query = "SELECT *
					FROM
						t_berita
					WHERE
						title LIKE ?
						OR content LIKE ?
					ORDER BY ".$order_arr[$order["column"]]." ".$order["dir"]."
					LIMIT ".$start.", ".$length.";";
		$data = array("%". $search['value'] ."%", "%". $search['value'] ."%");
		$result = $this->db->query($query, $data);
		return $result->result();
	}

	function count_all_berita($search){
		$query = "SELECT COUNT(*) AS count
					FROM
						t_berita
					WHERE
						title LIKE ?
						OR content LIKE ?";
		$data = array("%". $search['value'] ."%", "%". $search['value'] ."%");
		$result = $this->db->query($query, $data);
		$result = $result->row();
		return $result->count;
	}

	function get_all_files($search, $start, $length, $order, $order_arr, $id_skpd=NULL){
    $where = " AND id_skpd IS NULL ";
    if (!empty($id_skpd)) {
      $where = " AND id_skpd='". $id_skpd ."' ";
    }
		$query = "SELECT *
					FROM
						t_file_4_publik
					WHERE
						(title LIKE ?
						OR keterangan LIKE ?)
            ". $where ."
					ORDER BY ".$order_arr[$order["column"]]." ".$order["dir"]."
					LIMIT ".$start.", ".$length.";";
		$data = array("%". $search['value'] ."%", "%". $search['value'] ."%");
		$result = $this->db->query($query, $data);
		return $result->result();
	}

	function count_all_files($search, $id_skpd=NULL){
    $where = " AND id_skpd IS NULL ";
    if (!empty($id_skpd)) {
      $where = " AND id_skpd='". $id_skpd ."' ";
    }
		$query = "SELECT COUNT(*) AS count
					FROM
						t_file_4_publik
					WHERE
						(title LIKE ?
						OR keterangan LIKE ?)". $where;
		$data = array("%". $search['value'] ."%", "%". $search['value'] ."%");
		$result = $this->db->query($query, $data);
		$result = $result->row();
		return $result->count;
	}

	function perbandingan_prokeg($tahun){
		$query = "SELECT GROUP_CONCAT(CASE type WHEN 'program_rkpd' THEN jumlah END) AS program_rkpd, GROUP_CONCAT(CASE type WHEN 'kegiatan_rkpd' THEN jumlah END) AS kegiatan_rkpd, GROUP_CONCAT(CASE type WHEN 'program_apbd' THEN jumlah END) AS program_apbd, GROUP_CONCAT(CASE type WHEN 'kegiatan_apbd' THEN jumlah END) AS kegiatan_apbd FROM (SELECT COUNT(*) AS jumlah, 'program_rkpd' AS type FROM t_renja_prog_keg WHERE is_prog_or_keg=1 AND tahun=? UNION ALL SELECT COUNT(*) AS jumlah, 'kegiatan_rkpd' FROM t_renja_prog_keg WHERE is_prog_or_keg=2 AND tahun=? UNION ALL SELECT COUNT(*) AS jumlah, 'program_apbd' FROM tx_rka_prog_keg WHERE is_prog_or_keg=1 AND tahun=? UNION ALL SELECT COUNT(*) AS jumlah, 'kegiatan_apbd' FROM tx_rka_prog_keg WHERE is_prog_or_keg=2 AND tahun=?) AS vw;";
		$result = $this->db->query($query, array($tahun, $tahun, $tahun, $tahun));
		$result = $result->row();
		return $result;
	}

	function perbandingan_jml_prokeg($tahun){
		$query = "SELECT GROUP_CONCAT(CASE WHEN type='program_rkpd' AND kd_urusan=1 THEN jumlah END) AS program_rkpd_1, GROUP_CONCAT(CASE WHEN type='program_rkpd' AND kd_urusan=2 THEN jumlah END) AS program_rkpd_2, GROUP_CONCAT(CASE WHEN type='kegiatan_rkpd' AND kd_urusan=1 THEN jumlah END) AS kegiatan_rkpd_1, GROUP_CONCAT(CASE WHEN type='kegiatan_rkpd' AND kd_urusan=2 THEN jumlah END) AS kegiatan_rkpd_2, GROUP_CONCAT(CASE WHEN type='program_apbd' AND kd_urusan=1 THEN jumlah END) AS program_apbd_1, GROUP_CONCAT(CASE WHEN type='program_apbd' AND kd_urusan=2 THEN jumlah END) AS program_apbd_2, GROUP_CONCAT(CASE WHEN type='kegiatan_apbd' AND kd_urusan=1 THEN jumlah END) AS kegiatan_apbd_1, GROUP_CONCAT(CASE WHEN type='kegiatan_apbd' AND kd_urusan=2 THEN jumlah END) AS kegiatan_apbd_2 FROM (SELECT COUNT(*) AS jumlah, 'program_rkpd' AS type, kd_urusan FROM t_renja_prog_keg WHERE is_prog_or_keg=1 AND tahun=? GROUP BY kd_urusan UNION ALL SELECT COUNT(*) AS jumlah, 'kegiatan_rkpd', kd_urusan FROM t_renja_prog_keg WHERE is_prog_or_keg=2 AND tahun=? GROUP BY kd_urusan UNION ALL SELECT COUNT(*) AS jumlah, 'program_apbd', kd_urusan FROM tx_rka_prog_keg WHERE is_prog_or_keg=1 AND tahun=? GROUP BY kd_urusan UNION ALL SELECT COUNT(*) AS jumlah, 'kegiatan_apbd', kd_urusan FROM tx_rka_prog_keg WHERE is_prog_or_keg=2 AND tahun=? GROUP BY kd_urusan) AS vw;";
		$result = $this->db->query($query, array($tahun, $tahun, $tahun, $tahun));
		$result = $result->row();
		return $result;
	}

	function list_perbandingan($tahun){
		$query = "SELECT GROUP_CONCAT(CASE WHEN type='rkpd' AND kd_urusan=1 THEN jumlah END) AS rkpd_1, GROUP_CONCAT(CASE WHEN type='rkpd' AND kd_urusan=2 THEN jumlah END) AS rkpd_2, GROUP_CONCAT(CASE WHEN type='apbd' AND kd_urusan=1 THEN jumlah END) AS apbd_1, GROUP_CONCAT(CASE WHEN type='apbd' AND kd_urusan=2 THEN jumlah END) AS apbd_2 FROM (SELECT COUNT(*) AS jumlah, 'rkpd' AS type, kd_urusan FROM t_renja_prog_keg WHERE tahun=? GROUP BY kd_urusan UNION ALL SELECT COUNT(*) AS jumlah, 'apbd', kd_urusan FROM tx_rka_prog_keg WHERE tahun=? GROUP BY kd_urusan) AS vw;";
		$result = $this->db->query($query, array($tahun, $tahun));
		$result = $result->row();
		return $result;
	}

	function get_perbandingan_grafik_per_bid($tahun, $kd_urusan, $kd_bidang){
		$query = "SELECT Nm_Bidang AS nama, SUM(IF(is_prog_or_keg=1 AND rkpd IS NOT NULL AND apbd IS NOT NULL, 1, 0)) AS pro_rkpd_apbd, SUM(IF(is_prog_or_keg=1 AND rkpd IS NOT NULL AND apbd IS NULL, 1, 0)) AS pro_rkpd, SUM(IF(is_prog_or_keg=1 AND rkpd IS NULL AND apbd IS NOT NULL, 1, 0)) AS pro_apbd, SUM(IF(is_prog_or_keg=2 AND rkpd IS NOT NULL AND apbd IS NOT NULL, 1, 0)) AS keg_rkpd_apbd, SUM(IF(is_prog_or_keg=2 AND rkpd IS NOT NULL AND apbd IS NULL, 1, 0)) AS keg_rkpd, SUM(IF(is_prog_or_keg=2 AND rkpd IS NULL AND apbd IS NOT NULL, 1, 0)) AS keg_apbd FROM (
						SELECT
							t_renja_prog_keg.id AS rkpd, tx_rka_prog_keg.id AS apbd, t_renja_prog_keg.kd_urusan, t_renja_prog_keg.kd_bidang, t_renja_prog_keg.kd_program, t_renja_prog_keg.kd_kegiatan, t_renja_prog_keg.is_prog_or_keg
						FROM
							t_renja_prog_keg
						LEFT JOIN
							tx_rka_prog_keg ON tx_rka_prog_keg.id_renja=t_renja_prog_keg.id
						WHERE
							t_renja_prog_keg.tahun=? AND t_renja_prog_keg.kd_urusan=? AND t_renja_prog_keg.kd_bidang=?
						UNION ALL
						SELECT
							t_renja_prog_keg.id AS rkpd, tx_rka_prog_keg.id AS apbd, tx_rka_prog_keg.kd_urusan, tx_rka_prog_keg.kd_bidang, tx_rka_prog_keg.kd_program, t_renja_prog_keg.kd_kegiatan, t_renja_prog_keg.is_prog_or_keg
						FROM
							t_renja_prog_keg
						RIGHT JOIN
							tx_rka_prog_keg ON tx_rka_prog_keg.id_renja=t_renja_prog_keg.id
						WHERE
							tx_rka_prog_keg.tahun=? AND tx_rka_prog_keg.kd_urusan=? AND tx_rka_prog_keg.kd_bidang=? AND t_renja_prog_keg.id IS NULL
					) AS vw
					LEFT JOIN
						m_bidang ON vw.kd_urusan=m_bidang.kd_urusan AND vw.kd_bidang=m_bidang.kd_bidang";
		$result = $this->db->query($query, array($tahun, $kd_urusan, $kd_bidang, $tahun, $kd_urusan, $kd_bidang));
		$result = $result->row();
		return $result;
	}

	function get_value_circle_kendali($triwulan, $tahun, $kd_urusan, $kd_bidang){
		$query = "SELECT SUM(IF(capaian_$triwulan>=100,1,0)) AS kendali4, SUM(IF(capaian_$triwulan>=90 AND capaian_$triwulan<=99,1,0)) AS kendali3, SUM(IF(capaian_$triwulan>=60 AND capaian_$triwulan<=79,1,0)) AS kendali2, SUM(IF(capaian_$triwulan<60 OR capaian_$triwulan IS NULL,1,0)) AS kendali1 FROM tx_dpa_prog_keg WHERE tahun=? AND kd_urusan=? AND kd_bidang=? AND is_prog_or_keg=2;";
		$result = $this->db->query($query, array($tahun, $kd_urusan, $kd_bidang));
		$result = $result->row();
		return $result;
	}

	function get_angaran($tahun){
		$query = "SELECT SUM(nominal_1) AS nominal_1, SUM(nominal_2) AS nominal_2, SUM(nominal_3) AS nominal_3, SUM(nominal_4) AS nominal_4 FROM tx_dpa_prog_keg WHERE is_prog_or_keg=2 AND tahun=?;";
		$result = $this->db->query($query, array($tahun));
		$result = $result->row();
		return $result;
	}

	function get_realisasi($tahun){
		$query = "SELECT SUM(realisasi_1) AS realisasi_1, SUM(realisasi_2) AS realisasi_2, SUM(realisasi_3) AS realisasi_3, SUM(realisasi_4) AS realisasi_4, SUM(realisasi_5) AS realisasi_5, SUM(realisasi_6) AS realisasi_6, SUM(realisasi_7) AS realisasi_7, SUM(realisasi_8) AS realisasi_8, SUM(realisasi_9) AS realisasi_9, SUM(realisasi_10) AS realisasi_10, SUM(realisasi_11) AS realisasi_11, SUM(realisasi_12) AS realisasi_12 FROM tx_cik_prog_keg WHERE is_prog_or_keg=2 AND tahun=?;";
		$result = $this->db->query($query, array($tahun));
		$result = $result->row();
		return $result;
	}
	
	function get_realisasi_perubahan($tahun){
		$query = "SELECT SUM(realisasi_1) AS realisasi_1, SUM(realisasi_2) AS realisasi_2, SUM(realisasi_3) AS realisasi_3, SUM(realisasi_4) AS realisasi_4, SUM(realisasi_5) AS realisasi_5, SUM(realisasi_6) AS realisasi_6, SUM(realisasi_7) AS realisasi_7, SUM(realisasi_8) AS realisasi_8, SUM(realisasi_9) AS realisasi_9, SUM(realisasi_10) AS realisasi_10, SUM(realisasi_11) AS realisasi_11, SUM(realisasi_12) AS realisasi_12 FROM tx_cik_prog_keg_perubahan WHERE is_prog_or_keg=2 AND tahun=?;";
		$result = $this->db->query($query, array($tahun));
		$result = $result->row();
		return $result;
	}	

  function get_skpd_files(){
		$result = $this->db->query("SELECT m_skpd.id_skpd, m_skpd.nama_skpd FROM t_file_4_publik INNER JOIN m_skpd ON t_file_4_publik.id_skpd=m_skpd.id_skpd");
		return $result->result_array();
  }
}
?>
