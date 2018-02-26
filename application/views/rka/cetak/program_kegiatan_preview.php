<?php
	$max_col_keg=1;
?>
<thead>
<?php
	if(!empty($ta))
	{
		$tahun_rka = $ta;
	}
	else
	{
		$tahun_rka = $this->session->userdata('t_anggaran_aktif');
	}
?>
	<tr>
		<th rowspan="2" colspan="4">Kode</th>
		<th rowspan="2">Program dan Kegiatan</th>
		<th rowspan="2">Indikator Kinerja Program/Kegiatan</th>
		<th colspan="3">Rencana Tahun <?php echo $tahun_rka?></th>
		<th colspan="2">Perkiraan Maju Rencana Tahun <?php echo $tahun_rka+1;?></th>
	</tr>
	<tr>
		<th >Lokasi</th>
		<th >Target Capaian Kinerja</th>
		<th >Kebutuhan Dana/Pagu Indikatif (Rp.)</th>
		<th >Target Capaian Kinerja</th>
		<th >Kebutuhan Dana/Pagu Indikatif (Rp.)</th>
	</tr>
</thead>
<tbody>
<?php
	foreach ($urusan as $row_urusan) {
		if (($row_urusan->sum_nominal > 0) OR ($row_urusan->sum_nominal_thndpn > 0)) {
		echo "
		<tr bgcolor=\"#78cbfd\">
			<td>".$row_urusan->kd_urusan."</td>
			<td></td>
			<td></td>
			<td></td>
			<td colspan=\"4 \">
				<strong>".strtoupper($row_urusan->nama_urusan)."</strong>
			</td>
			<td align=\"right\">".Formatting::currency($row_urusan->sum_nominal)."</td>
			<td></td>
			<td align=\"right\">".Formatting::currency($row_urusan->sum_nominal_thndpn)."</td>

		</tr>";
		}
		$bidang = $this->db->query("
			SELECT t.*,b.Nm_Bidang as nama_bidang from (
			SELECT
				pro.kd_urusan,pro.kd_bidang,pro.kd_program,pro.kd_kegiatan,
				SUM(keg.nominal) AS sum_nominal,
				SUM(keg.nominal_thndpn) AS sum_nominal_thndpn
			FROM
				(SELECT * FROM tx_rka_prog_keg WHERE is_prog_or_keg=1) AS pro
			INNER JOIN
				(SELECT * FROM tx_rka_prog_keg WHERE is_prog_or_keg=2) AS keg ON keg.parent=pro.id
			WHERE
				keg.id_skpd=".$id_skpd."
				AND keg.tahun=".$ta."
			AND keg.kd_urusan = ".$row_urusan->kd_urusan."
			GROUP BY pro.kd_urusan,kd_bidang
			ORDER BY kd_urusan ASC, kd_bidang ASC, kd_program ASC
			) t
			left join m_bidang b
			on t.kd_urusan = b.Kd_Urusan and t.kd_bidang = b.Kd_Bidang
		")->result();
		
		foreach ($bidang as $row_bidang) {
			if (($row_bidang->sum_nominal > 0) OR ($row_bidang->sum_nominal_thndpn > 0)) {
			echo "
			<tr bgcolor=\"#00FF33\">
				<td>".$row_urusan->kd_urusan."</td>
				<td>".$row_bidang->kd_bidang."</td>
				<td></td>
				<td></td>
				<td colspan=\"4 \">
					<strong>".strtoupper($row_bidang->nama_bidang)."</strong>
				</td>
				<td align=\"right\">".Formatting::currency($row_bidang->sum_nominal)."</td>
				<td></td>
				<td align=\"right\">".Formatting::currency($row_bidang->sum_nominal_thndpn)."</td>
			</tr>";
			}
		$program = $this->m_rka->get_program_skpd_4_cetak($id_skpd,$ta,$row_urusan->kd_urusan,$row_bidang->kd_bidang);

	foreach ($program as $row) {
		$result = $this->m_rka->get_kegiatan_skpd_4_cetak($row->id);
		$kegiatan = $result->result();
		$indikator_program = $this->m_rka->get_indikator_prog_keg($row->id, FALSE, TRUE);
		$temp = $indikator_program->result();
		$total_temp = $indikator_program->num_rows();

		$col_indikator=1;
		$go_3_keg = FALSE;
		$total_for_iteration = $total_temp;
		if ($total_temp > $max_col_keg) {
			$total_temp = $max_col_keg;
			$go_3_keg = TRUE;
		}
?>
	<tr bgcolor="#FF9933">
		<td style="border-bottom: 0;" rowspan="<?php echo $total_for_iteration; ?>"><?php echo $row->kd_urusan; ?></td>
		<td style="border-bottom: 0;" rowspan="<?php echo $total_for_iteration; ?>"><?php echo $row->kd_bidang; ?></td>
		<td style="border-bottom: 0;" rowspan="<?php echo $total_for_iteration; ?>"><?php echo $row->kd_program; ?></td>
		<td style="border-bottom: 0;" rowspan="<?php echo $total_for_iteration; ?>"><?php echo $row->kd_kegiatan; ?></td>
		<td style="border-bottom: 0;" rowspan="<?php echo $total_for_iteration; ?>"><?php echo $row->nama_prog_or_keg; ?></td>
		<td>
			<?php
				echo $temp[0]->indikator;
			?>
		</td>
		<td style="border-bottom: 0;" rowspan="<?php echo $total_for_iteration; ?>"><?php echo $row->lokasi;?></td>
		<td align="center">
			<?php echo $temp[0]->target." ".$temp[0]->satuan_target;?>
		</td>
		<td style="border-bottom: 0;" align="right" rowspan="<?php echo $total_for_iteration; ?>"><?php echo Formatting::currency($row->sum_nominal);?></td>
		<td align="center">
			<?php echo $temp[0]->target_thndpn." ".$temp[0]->satuan_target;?>
		</td>
		<td style="border-bottom: 0;" align="right" rowspan="<?php echo $total_for_iteration; ?>"><?php echo Formatting::currency($row->sum_nominal_thndpn);?></td>
	</tr>
<?php
		if ($total_for_iteration > 1) {
			for ($i=1; $i < $total_for_iteration; $i++) {
				$col_indikator++;
?>
		<tr bgcolor="#FF9933">
			<td>
				<?php
					echo $temp[$i]->indikator;
				?>
			</td>
			<td align="center">
				<?php
					echo $temp[$i]->target." ".$temp[$i]->satuan_target;
				?>
			</td>
			<td align="center">
				<?php
					echo $temp[$i]->target_thndpn." ".$temp[$i]->satuan_target;
				?>
			</td>
		</tr>
<?php
		 	}
		 }

		foreach ($kegiatan as $row) {
			$indikator_kegiatan = $this->m_rka->get_indikator_prog_keg($row->id, FALSE, TRUE);
			$temp = $indikator_kegiatan->result();
			$total_temp = $indikator_kegiatan->num_rows();

			$go_3_keg = FALSE;
			$col_indikator_keg=1;
			$total_for_iteration = $total_temp;
			if ($total_temp > $max_col_keg) {
				$total_temp = $max_col_keg;
				$go_3_keg = TRUE;
			}
?>
		<tr>
			<td style="border-bottom: 0;" rowspan="<?php echo $total_for_iteration; ?>"><?php echo $row->kd_urusan; ?></td>
			<td style="border-bottom: 0;" rowspan="<?php echo $total_for_iteration; ?>"><?php echo $row->kd_bidang; ?></td>
			<td style="border-bottom: 0;" rowspan="<?php echo $total_for_iteration; ?>"><?php echo $row->kd_program; ?></td>
			<td style="border-bottom: 0;" rowspan="<?php echo $total_for_iteration; ?>"><?php echo $row->kd_kegiatan; ?></td>
			<td style="border-bottom: 0;" rowspan="<?php echo $total_for_iteration; ?>"><?php echo $row->nama_prog_or_keg; ?></td>
			<td>
			<?php
				echo $temp[0]->indikator;
			?>
			</td>
			<td style="border-bottom: 0;" rowspan="<?php echo $total_for_iteration; ?>"><?php echo $row->lokasi;?></td>
			<td align="center">
			<?php
				echo $temp[0]->target." ".$temp[0]->satuan_target;
			?>
			</td>
			<td style="border-bottom: 0;" rowspan="<?php echo $total_for_iteration; ?>" align="right" ><?php echo Formatting::currency($row->nominal);?></td>
			<td align="center">
			<?php
				echo $temp[0]->target_thndpn." ".$temp[0]->satuan_target;
			?>
			</td>
			<td style="border-bottom: 0;" rowspan="<?php echo $total_for_iteration; ?>" align="right" ><?php echo Formatting::currency($row->nominal_thndpn);?></td>
		</tr>
<?php
			if ($total_for_iteration > 1) {
				for ($i=1; $i < $total_for_iteration; $i++) {
					$col_indikator_keg++;
?>
		<tr>
			<td>
				<?php
					echo $temp[$i]->indikator;
				?>
			</td>
			<td align="center">
				<?php
					echo $temp[$i]->target." ".$temp[$i]->satuan_target;
				?>
			</td>
			<td align="center">
				<?php
					echo $temp[$i]->target_thndpn." ".$temp[$i]->satuan_target;
				?>
			</td>
		</tr>
<?php
				}
			}
		}
	}
		}
	}
?>
</tbody>
