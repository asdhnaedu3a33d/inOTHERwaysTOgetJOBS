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
		<th >Rencana Tahun <?php echo $tahun_renja?></th>
		<th >Perkiraan Maju Rencana Tahun <?php echo $tahun_renja+1;?></th>
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
			<td align=\"right\">".Formatting::currency($row_urusan->sum_nominal,2)."</td>
			<td align=\"right\">".Formatting::currency($row_urusan->sum_nominal_thndpn,2)."</td>

		</tr>";

		$bidang = $this->db->query("
			select t.*,b.Nm_Bidang as nama_bidang from (
			SELECT
				pro.kd_urusan,pro.kd_bidang,pro.kd_program,pro.kd_kegiatan,
				SUM(keg.nominal) AS sum_nominal,
				SUM(keg.nominal_thndpn) AS sum_nominal_thndpn
			FROM
				(SELECT * FROM t_renja_prog_keg WHERE is_prog_or_keg=1) AS pro
			INNER JOIN
				(SELECT * FROM t_renja_prog_keg WHERE is_prog_or_keg=2) AS keg ON keg.parent=pro.id
			WHERE
				keg.tahun=".$ta."
			AND keg.kd_urusan = ".$row_urusan->kd_urusan." AND pro.id IN (SELECT id_prog_keg FROM t_renja_indikator_prog_keg WHERE id_prog_keg = pro.id AND (target > 0 OR target_thndpn > 0))
			GROUP BY pro.kd_urusan,kd_bidang
			ORDER BY kd_urusan ASC, kd_bidang ASC, kd_program ASC
			) t
			left join m_bidang b
			on t.kd_urusan = b.Kd_Urusan and t.kd_bidang = b.Kd_Bidang
		")->result();

		foreach ($bidang as $row_bidang) {
			echo "
			<tr bgcolor=\"#00FF33\">
				<td>".$row_urusan->kd_urusan."</td>
				<td>".$row_bidang->kd_bidang."</td>
				<td >
					<strong>".strtoupper($row_bidang->nama_bidang)."</strong>
				</td>
				<td align=\"right\">".Formatting::currency($row_bidang->sum_nominal,2)."</td>
				<td align=\"right\">".Formatting::currency($row_bidang->sum_nominal_thndpn,2)."</td>
			</tr>";


			$skpd = $this->db->query("
			select t.*,b.nama_skpd from (
				SELECT
					pro.kd_urusan,pro.kd_bidang,pro.kd_program,pro.kd_kegiatan,pro.id_skpd,
					SUM(keg.nominal) AS sum_nominal,
					SUM(keg.nominal_thndpn) AS sum_nominal_thndpn
				FROM
					(SELECT * FROM t_renja_prog_keg WHERE is_prog_or_keg=1) AS pro
				INNER JOIN
					(SELECT * FROM t_renja_prog_keg WHERE is_prog_or_keg=2) AS keg ON keg.parent=pro.id
				WHERE
					keg.tahun=".$ta." AND pro.id IN (SELECT id_prog_keg FROM t_renja_indikator_prog_keg WHERE id_prog_keg = pro.id AND (target > 0 OR target_thndpn > 0))
				AND keg.kd_urusan = ".$row_urusan->kd_urusan."
				AND keg.kd_bidang = ".$row_bidang->kd_bidang."
				GROUP BY pro.id_skpd
				ORDER BY CONVERT(pro.id_skpd, DECIMAL) ASC
				) t
				left join m_skpd b
				on b.id_skpd = t.id_skpd
			")->result();

			foreach ($skpd as $row_skpd) {
				$tot_nominal += $row_skpd->sum_nominal;
				$tot_nominal_thndpn += $row_skpd->sum_nominal_thndpn;
				echo "
				<tr>
					<td colspan=\"3 \">
						<strong>".strtoupper($row_skpd->nama_skpd)."</strong>
					</td>
					<td align=\"right\">".Formatting::currency($row_skpd->sum_nominal,2)."</td>
					<td align=\"right\">".Formatting::currency($row_skpd->sum_nominal_thndpn,2)."</td>
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
