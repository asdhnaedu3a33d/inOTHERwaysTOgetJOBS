<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_evaluasi_rkpd extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_rkpd_urusan_bidang($tahun, $tw)
    {
      $data['kode_urusan'] = $this->db->query("SELECT t_evaluasi_renja_prog_keg.*, m_urusan.Nm_Urusan AS urusan FROM t_evaluasi_renja_prog_keg INNER JOIN t_evaluasi_renja ON t_evaluasi_renja.id_evaluasi_renja_prog_keg=t_evaluasi_renja_prog_keg.id INNER JOIN m_urusan ON m_urusan.Kd_Urusan=t_evaluasi_renja_prog_keg.kd_urusan WHERE t_evaluasi_renja.status=5 AND tahun=? AND triwulan=? GROUP BY kd_urusan;", array($tahun, $tw))
                                      ->result();

      $temp = array();
      $kode_bidang = $this->db->query("SELECT t_evaluasi_renja_prog_keg.*, m_bidang.Nm_Bidang AS bidang FROM t_evaluasi_renja_prog_keg INNER JOIN t_evaluasi_renja ON t_evaluasi_renja.id_evaluasi_renja_prog_keg=t_evaluasi_renja_prog_keg.id INNER JOIN m_bidang ON m_bidang.Kd_Urusan=t_evaluasi_renja_prog_keg.kd_urusan AND m_bidang.Kd_Bidang=t_evaluasi_renja_prog_keg.kd_bidang WHERE t_evaluasi_renja.status=5 AND tahun=? AND triwulan=? GROUP BY kd_urusan, kd_bidang", array($tahun, $tw))
                              ->result();
      foreach ($kode_bidang as $key => $value) {
        $temp[$value->kd_urusan][] = $value;
      }
      $data['kode_bidang'] = $temp;

      return $data;
    }

    public function get_rkpd_skpd($tahun, $tw, $urusan, $bidang)
    {
      return $this->db->query("SELECT t_evaluasi_renja_prog_keg.*, m_skpd.nama_skpd FROM t_evaluasi_renja_prog_keg INNER JOIN t_evaluasi_renja ON t_evaluasi_renja.id_evaluasi_renja_prog_keg=t_evaluasi_renja_prog_keg.id INNER JOIN m_skpd ON m_skpd.id_skpd=t_evaluasi_renja_prog_keg.id_skpd WHERE t_evaluasi_renja.status=5 AND tahun=? AND triwulan=? AND kd_urusan=? AND kd_bidang=? GROUP BY t_evaluasi_renja_prog_keg.id_skpd ORDER BY m_skpd.orderby;", array($tahun, $tw, $urusan, $bidang))
                      ->result();
    }

    public function get_rkpd_prog_keg($tahun, $triwulan, $urusan, $bidang, $id_skpd)
  	{
      $where = array(
  						't_evaluasi_renja_prog_keg.tahun' => $tahun,
  						't_evaluasi_renja_prog_keg.id_skpd' => $id_skpd,
              't_evaluasi_renja_prog_keg.triwulan' => $triwulan,
              't_evaluasi_renja_prog_keg.kd_urusan' => $urusan,
              't_evaluasi_renja_prog_keg.kd_bidang' => $bidang,
              't_evaluasi_renja.status' => 5
  					);

  		$data['evaluasi_prog_keg'] = $this->db->where($where)
                                                  ->select("t_evaluasi_renja_prog_keg.*")
                                									->from("t_evaluasi_renja_prog_keg")
                                                  ->join("t_evaluasi_renja", "t_evaluasi_renja.id_evaluasi_renja_prog_keg=t_evaluasi_renja_prog_keg.id", "inner")
                                                  ->order_by("kd_urusan, kd_bidang, kd_program, kd_kegiatan, is_prog_or_keg")
                                                  ->group_by("t_evaluasi_renja_prog_keg.id")
                                                  ->get()
                                									->result();

  		$indikator = $this->db->where($where)
          									->select("t_evaluasi_renja.*")
          									->select("m_lov.nama_value AS satuan", false)
                            ->from("t_evaluasi_renja")
          									->join("t_evaluasi_renja_prog_keg", "t_evaluasi_renja.id_evaluasi_renja_prog_keg=t_evaluasi_renja_prog_keg.id", "inner")
          									->join("m_lov", "t_evaluasi_renja.satuan=m_lov.kode_value AND nama_app='satuan'", "left")
          									->order_by("t_evaluasi_renja_prog_keg.id")
          									->get()
          									->result();

  		$temp = array();
  		$jumlah = array();
  		foreach ($indikator as $row) {
  			$temp[$row->id_evaluasi_renja_prog_keg][] = $row;

  			if (empty($jumlah[$row->id_evaluasi_renja_prog_keg])) {
  				$jumlah[$row->id_evaluasi_renja_prog_keg]=1;
  			}else{
  				$jumlah[$row->id_evaluasi_renja_prog_keg]++;
  			}
  		}
  		$data['evaluasi'] = $temp;
  		$data['jumlah_evaluasi'] = $jumlah;

      $realisasi = $this->db->where($where)
                            ->select("t_evaluasi_renja_realisasi_kinerja_tw_prog_keg.*")
          									->from("t_evaluasi_renja_prog_keg")
                            ->join("t_evaluasi_renja", "t_evaluasi_renja.id_evaluasi_renja_prog_keg=t_evaluasi_renja_prog_keg.id", "inner")
                            ->join("t_evaluasi_renja_realisasi_kinerja_tw_prog_keg", "t_evaluasi_renja_realisasi_kinerja_tw_prog_keg.id_evaluasi_renja_prog_keg=t_evaluasi_renja_prog_keg.id", "inner")
                            ->order_by("t_evaluasi_renja_prog_keg.id")
                            ->group_by("t_evaluasi_renja_prog_keg.id, t_evaluasi_renja_realisasi_kinerja_tw_prog_keg.id")
                            ->get()
          									->result();

  		$temp = array();
  		foreach ($realisasi as $row) {
  			$temp[$row->id_evaluasi_renja_prog_keg][$row->triwulan] = $row;
  		}
  		$data['realisasi_evaluasi_prog_keg'] = $temp;

  		$realisasi = $this->db->where($where)
          									->select("t_evaluasi_renja_realisasi_kinerja_tw.*")
                            ->from("t_evaluasi_renja")
          									->join("t_evaluasi_renja_prog_keg", "t_evaluasi_renja.id_evaluasi_renja_prog_keg=t_evaluasi_renja_prog_keg.id", "inner")
                            ->join("t_evaluasi_renja_realisasi_kinerja_tw", "t_evaluasi_renja_realisasi_kinerja_tw.id_evaluasi_renja=t_evaluasi_renja.id", "inner")
          									->order_by("t_evaluasi_renja.id")
          									->get()
          									->result();

  		$temp = array();
  		foreach ($realisasi as $row) {
  			$temp[$row->id_evaluasi_renja][$row->triwulan] = $row;
  		}
  		$data['realisasi_evaluasi'] = $temp;

  		return $data;
  	}
}
?>
