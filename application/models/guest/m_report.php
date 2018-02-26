<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_report extends CI_Model 
{

    public function __construct()
    {
        parent::__construct();           
    }

    function get_jumlah_kegiatan_per_skpd(){		
		$query = "SELECT nama_koor AS nama, IF(vw.jumlah IS NOT NULL, vw.jumlah, 0) AS jumlah FROM m_bidkoordinasi LEFT JOIN (SELECT m_skpd.id_bidkoor, COUNT(*) AS jumlah FROM t_renstra_prog_keg INNER JOIN t_renstra ON t_renstra_prog_keg.id_renstra=t_renstra.id INNER JOIN m_skpd ON m_skpd.id_skpd=t_renstra.id_skpd INNER JOIN m_bidkoordinasi ON m_skpd.id_bidkoor=m_bidkoordinasi.id_bidkoor WHERE is_prog_or_keg=1 AND id_status=7 GROUP BY m_skpd.id_bidkoor) AS vw ON m_bidkoordinasi.id_bidkoor=vw.id_bidkoor";
		$result = $this->db->query($query);		
		return $result->result();
	}

}
?>