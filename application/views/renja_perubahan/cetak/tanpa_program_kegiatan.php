<?php
	$max_col_keg=1;
	$tot_nominal=0; 
	$tot_nominal_thndpn=0;
?>
<?php
	if(!empty($ta))
	{
		$tahun_renja = $ta;
	}
	else
	{
		$tahun_renja = $this->session->userdata('t_anggaran_aktif');
	}
?>
<thead>
	<tr>
		<th rowspan="2" colspan="2">Kode</th>
		<th rowspan="2">Program dan Kegiatan</th>
		<th >Renja Tahun <?php echo $tahun_renja?></th>
		<th >Renja Tahun <?php echo $tahun_renja;?> Perubahan</th>
	</tr>
	<tr>
		<th >Kebutuhan Dana/Pagu Indikatif (Rp.)</th>
		<th >Kebutuhan Dana/Pagu Indikatif (Rp.)</th>
	</tr>
</thead>
<tbody>
<?php
	foreach ($urusan as $row_urusan) {
		echo "
		<tr bgcolor=\"#78cbfd\">
			<td>".$row_urusan->kd_urusan."</td>
			<td></td>
			<td >
				<strong>".strtoupper($row_urusan->nama_urusan)."</strong>
			</td>
			<td align=\"right\">".Formatting::currency($row_urusan->sum_nomrenja,2)."</td>
			<td align=\"right\">".Formatting::currency($row_urusan->sum_nomrenja_perubahan,2)."</td>

		</tr>";

		$bidang = $this->db->query("
			SELECT r.*,b.Nm_Bidang AS nama_bidang FROM (
			SELECT
			r.tahun,
			r.id_skpd,
			r.kd_urusan,
			r.kd_bidang,
			SUM(r.nomrenja) AS sum_nomrenja,
			SUM(r.nomrenja_perubahan) AS sum_nomrenja_perubahan
			FROM (
			SELECT
			k.*,
			IF(r.nama_prog_or_keg='',r.nama_prog_or_keg,rp.nama_prog_or_keg) AS nama_prog_or_keg,
			r.id,
			r.penanggung_jawab,
			r.lokasi,
			r.catatan,
			r.id_status,
			r.`nominal` AS nomrenja,
			rp.id_renja,
			rp.`penanggung_jawab` AS penanggung_jawab_perubahan,
			rp.`lokasi` AS lokasi_perubahan ,
			rp.`catatan` AS catatan_perubahan,
			rp.`keterangan` AS keterangan_perubahan,
			rp.`nominal` AS nomrenja_perubahan
			FROM (
			SELECT tahun,kd_urusan,kd_bidang,kd_program,kd_kegiatan,id_skpd FROM t_renja_prog_keg WHERE tahun = '".$ta."' AND kd_kegiatan IS NOT NULL
			UNION
			SELECT tahun,kd_urusan,kd_bidang,kd_program,kd_kegiatan,id_skpd FROM t_renja_prog_keg_perubahan WHERE tahun = '".$ta."' AND kd_kegiatan IS NOT NULL
			) k
			LEFT JOIN t_renja_prog_keg r
			ON k.tahun = r.tahun
			AND k.kd_urusan = r.kd_urusan
			AND k.kd_bidang = r.kd_bidang
			AND k.kd_program = r.kd_program
			AND k.kd_kegiatan = r.kd_kegiatan
			AND k.id_skpd = r.id_skpd
			LEFT JOIN t_renja_prog_keg_perubahan AS rp
			ON k.tahun = rp.tahun
			AND k.kd_urusan = rp.kd_urusan
			AND k.kd_bidang = rp.kd_bidang
			AND k.kd_program = rp.kd_program
			AND k.kd_kegiatan = rp.kd_kegiatan
			AND k.id_skpd = rp.id_skpd
			) r
			WHERE kd_urusan = '".$row_urusan->kd_urusan."'
			GROUP BY kd_bidang
			ORDER BY kd_urusan ASC,kd_bidang ASC
			) r
			LEFT JOIN m_bidang b
			ON r.kd_urusan = b.Kd_Urusan AND r.kd_bidang = b.Kd_Bidang
		")->result();

		foreach ($bidang as $row_bidang) {
			echo "
			<tr bgcolor=\"#00FF33\">
				<td>".$row_urusan->kd_urusan."</td>
				<td>".$row_bidang->kd_bidang."</td>
				<td >
					<strong>".strtoupper($row_bidang->nama_bidang)."</strong>
				</td>
				<td align=\"right\">".Formatting::currency($row_bidang->sum_nomrenja,2)."</td>
				<td align=\"right\">".Formatting::currency($row_bidang->sum_nomrenja_perubahan,2)."</td>
			</tr>";


			$skpd = $this->db->query("
				SELECT r.*,skpd.`nama_skpd` AS nama_skpd FROM (
				SELECT
				r.tahun,
				r.id_skpd,
				r.kd_urusan,
				r.kd_bidang,
				SUM(r.nomrenja) AS sum_nomrenja,
				SUM(r.nomrenja_perubahan) AS sum_nomrenja_perubahan
				FROM (
				SELECT
				k.*,
				IF(r.nama_prog_or_keg='',r.nama_prog_or_keg,rp.nama_prog_or_keg) AS nama_prog_or_keg,
				r.id,
				r.penanggung_jawab,
				r.lokasi,
				r.catatan,
				r.id_status,
				r.`nominal` AS nomrenja,
				rp.id_renja,
				rp.`penanggung_jawab` AS penanggung_jawab_perubahan,
				rp.`lokasi` AS lokasi_perubahan ,
				rp.`catatan` AS catatan_perubahan,
				rp.`keterangan` AS keterangan_perubahan,
				rp.`nominal` AS nomrenja_perubahan
				FROM (
				SELECT tahun,kd_urusan,kd_bidang,kd_program,kd_kegiatan,id_skpd FROM t_renja_prog_keg WHERE tahun = ".$ta." AND kd_kegiatan IS NOT NULL
				UNION
				SELECT tahun,kd_urusan,kd_bidang,kd_program,kd_kegiatan,id_skpd FROM t_renja_prog_keg_perubahan WHERE tahun = ".$ta." AND kd_kegiatan IS NOT NULL
				) k
				LEFT JOIN t_renja_prog_keg r
				ON k.tahun = r.tahun
				AND k.kd_urusan = r.kd_urusan
				AND k.kd_bidang = r.kd_bidang
				AND k.kd_program = r.kd_program
				AND k.kd_kegiatan = r.kd_kegiatan
				AND k.id_skpd = r.id_skpd
				LEFT JOIN t_renja_prog_keg_perubahan AS rp
				ON k.tahun = rp.tahun
				AND k.kd_urusan = rp.kd_urusan
				AND k.kd_bidang = rp.kd_bidang
				AND k.kd_program = rp.kd_program
				AND k.kd_kegiatan = rp.kd_kegiatan
				AND k.id_skpd = rp.id_skpd
				) r
				WHERE kd_urusan = ".$row_urusan->kd_urusan."
				AND kd_bidang = ".$row_bidang->kd_bidang."
				GROUP BY id_skpd
				ORDER BY CONVERT(id_skpd, DECIMAL) ASC
				) r
				LEFT JOIN m_skpd AS skpd
				ON r.id_skpd = skpd.`id_skpd`;
			")->result();

			foreach ($skpd as $row_skpd) {
				$tot_nominal += $row_skpd->sum_nomrenja;
				$tot_nominal_thndpn += $row_skpd->sum_nomrenja_perubahan;
				echo "
				<tr>
					<td colspan=\"3 \">
						<strong>".strtoupper($row_skpd->nama_skpd)."</strong>
					</td>
					<td align=\"right\">".Formatting::currency($row_skpd->sum_nomrenja,2)."</td>
					<td align=\"right\">".Formatting::currency($row_skpd->sum_nomrenja_perubahan,2)."</td>
				</tr>";
				$id_skpd = $row_skpd->id_skpd;
			}
		}
	}
?>
		<tr bgcolor="#999999">
        	<td colspan = "2"></td>
			<td >
            	<strong>
                	Total Nominal Tahun Renja
                </strong>
            </td>
			<td align="right">
            	<strong>
					<?php echo Formatting::currency($tot_nominal,2) ;?>
                <strong>
            </td>
			<td align="right">
            	<strong>
					<?php echo Formatting::currency($tot_nominal_thndpn,2); ?>
                </strong>
            </td>
		</tr>
</tbody>
