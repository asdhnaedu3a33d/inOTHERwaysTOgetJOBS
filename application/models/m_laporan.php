<?php

/**
 * Created by PhpStorm.
 * User: Budy
 * Date: 4/9/2018
 * Time: 2:44 PM
 */
class m_laporan extends CI_Model {

    public function getRealisasiUang()
    {
        $sql = "SELECT id_skpd,nama_skpd,SUM(realisasi) AS realisasi,
       SUM(anggaran) AS anggaran,
       CASE WHEN (SUM(realisasi)/SUM(anggaran)*100) IS NULL THEN 0 ELSE (SUM(realisasi)/SUM(anggaran)*100)  END AS persen 
    FROM (SELECT  b.id_skpd,c.nama_skpd,a.`id_triwulan`
       ,CASE WHEN a.`realisasi` IS NULL THEN 0 ELSE a.realisasi END AS realisasi
 ,b.nominal_1 + b.nominal_2 + b.nominal_3 + b.nominal_4 +
 b.nominal_5 + b.nominal_6 + b.nominal_7 + b.nominal_8 +
 b.nominal_9 + b.nominal_10 + b.nominal_11 +b.nominal_12 AS anggaran 
FROM tx_dpa_prog_keg_triwulan a
INNER JOIN tx_dpa_prog_keg b
ON a.`id_dpa_prog_keg` = b.`id`
INNER JOIN m_skpd c 
ON b.id_skpd=c.id_skpd
WHERE b.tahun = 2018 AND a.id_triwulan=12
) AS ref
GROUP BY id_skpd
ORDER BY persen DESC";
        $query = $this->db->query($sql);

        if ($query->num_rows() >= 0)
        {
            foreach ($query->result() as $row)
            {
                $data[] = $row;
            }
            return $data;
        }
        return false;
    }

    public function test2()
    {
        $query = "SELECT nama_koor AS nama, IF(vw.jumlah IS NOT NULL, vw.jumlah, 0) AS jumlah FROM m_bidkoordinasi LEFT JOIN (SELECT m_skpd.id_bidkoor, COUNT(*) AS jumlah FROM t_renstra_prog_keg INNER JOIN t_renstra ON t_renstra_prog_keg.id_renstra=t_renstra.id INNER JOIN m_skpd ON m_skpd.id_skpd=t_renstra.id_skpd INNER JOIN m_bidkoordinasi ON m_skpd.id_bidkoor=m_bidkoordinasi.id_bidkoor WHERE is_prog_or_keg=1 AND id_status=7 GROUP BY m_skpd.id_bidkoor) AS vw ON m_bidkoordinasi.id_bidkoor=vw.id_bidkoor";
        $result = $this->db->query($query);

        return $result->result();
    }

    public function donat1()
    {
        $sql = "SELECT m_urusan.kd_urusan,m_urusan.Nm_Urusan,COUNT(tx_dpa_prog_keg.kd_urusan) FROM tx_dpa_prog_keg
INNER JOIN m_urusan
ON tx_dpa_prog_keg.kd_urusan = m_urusan.Kd_Urusan
WHERE tahun = 2018  AND is_prog_or_keg = 2
AND id IN ( SELECT id_prog_keg FROM tx_dpa_indikator_prog_keg
       WHERE is_prog_or_keg=2 AND id_prog_keg = tx_dpa_prog_keg.id
       AND target > 0)
GROUP BY `tx_dpa_prog_keg`. kd_urusan,m_urusan.Nm_Urusan
HAVING ((SUM(tx_dpa_prog_keg.nominal_1) + SUM(tx_dpa_prog_keg.nominal_2)+ SUM(tx_dpa_prog_keg.nominal_3)+ 
 SUM(tx_dpa_prog_keg.nominal_4) + SUM(tx_dpa_prog_keg.nominal_5)+ SUM(tx_dpa_prog_keg.nominal_6)+
 SUM(tx_dpa_prog_keg.nominal_7) + SUM(tx_dpa_prog_keg.nominal_8)+ SUM(tx_dpa_prog_keg.nominal_9)+
 SUM(tx_dpa_prog_keg.nominal_10) + SUM(tx_dpa_prog_keg.nominal_11)+ SUM(tx_dpa_prog_keg.nominal_12)
 )  > 0)";

        $query = $this->db->query($sql);

        return $query->result();
    }
}