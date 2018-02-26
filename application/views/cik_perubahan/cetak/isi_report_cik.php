<thead>
    <tr>
        <th rowspan="2" colspan="4">KODE</th>
        <th rowspan="2">PROGRAM DAN KEGIATAN</th>
        <th colspan="3">ANGGARAN</th>
        <th colspan="4">KELOMPOK INDIKATOR KINERJA PROGRAM (OUTCOME) / INDIKATOR KINERJA KEGIATAN (OUTPUT)</th>
        <th rowspan="2">KET.</th>
    </tr>
    <tr>
        <th>RENCANA (Rp.)</th>
        <th>REALISASI (Rp.)</th>
        <th>CAPAIAN IK</th>
        <th>INDIKATOR/SATUAN</th>
        <th>RENCANA</th>
        <th>REALISASI</th>
        <th>CAPAIAN IK</th>
    </tr>
</thead>
<tbody>
<?php
	$max_col_keg=1;
	$tot_rencana=0; $tot_realisasi=0;
	$p=0;
	foreach($urusan as $row_urusan){
		echo "
		<tr bgcolor=\"#78cbfd\">
			<td>".$row_urusan->kd_urusan." </td>
			<td></td>
			<td></td>
			<td></td>
			<td>
				<strong>".strtoupper($row_urusan->nama_urusan)."</strong>
			</td>
			<td align=\"right\">".Formatting::currency($row_urusan->rencana)."</td>
			<td align=\"right\">".Formatting::currency($row_urusan->realisasi)."</td>
			</td>
			<td colspan=\"6\"></td>
		</tr>";
		$bidang = $this->db->query("
			SELECT pro.*, 
			SUM(keg.realisasi_".$bulan.") AS realisasi, 
			SUM(keg.rencana) AS rencana,
			pro.capaian_".$bulan." AS capaian,
			b.Nm_Bidang AS nama_bidang
			  FROM 
				(SELECT * FROM tx_cik_prog_keg_perubahan WHERE is_prog_or_keg=1) AS pro 
			  INNER JOIN 
				(SELECT * FROM tx_cik_prog_keg_perubahan WHERE is_prog_or_keg=2) AS keg ON keg.parent=pro.id 
			LEFT JOIN m_bidang AS b
			ON pro.kd_urusan = b.Kd_Urusan AND pro.kd_bidang = b.`Kd_Bidang`
			WHERE 
				keg.id_skpd = ".$id_skpd."
			  AND keg.tahun = ".$tahun."
			  AND keg.kd_urusan = ".$row_urusan->kd_urusan."
			  GROUP BY keg.kd_bidang
			  ORDER BY kd_urusan ASC, kd_bidang ASC, kd_program ASC, kd_kegiatan ASC;
		")->result();
		
		foreach ($bidang as $row_bidang) {
			echo "
			<tr bgcolor=\"#00FF33\">
				<td>".$row_urusan->kd_urusan."</td>
				<td>".$row_bidang->kd_bidang."</td>
				<td></td>
				<td></td>
				<td>
					<strong>".strtoupper($row_bidang->nama_bidang)."</strong>
				</td>
				<td align=\"right\">".Formatting::currency($row_bidang->rencana)."</td>
				<td align=\"right\">".Formatting::currency($row_bidang->realisasi)."</td>
				</td>
				<td colspan=\"6\"></td>
			</tr>";
			$program = $this->m_cik_perubahan->get_program_cik_4_cetak($id_skpd,$bulan,$tahun,$row_urusan->kd_urusan,$row_bidang->kd_bidang);
			
	foreach($program as $row){
	if($row->id == ""){
		echo "<tr><td colspan='13' align='center'><strong>Data Belum Terisikan..</strong></td></tr>";
	}else{
		$p++;
		$tot_rencana += $row->rencana;
		$tot_realisasi += $row->realisasi;
		$result = $this->m_cik_perubahan->get_kegiatan_cik_4_report($row_urusan->kd_urusan,$row_bidang->kd_bidang,$row->kd_program,$id_skpd,$bulan,$tahun);
		//echo $this->db->last_query();
		$cik_pro_keg = (empty($row->realisasi))?0:round(($row->realisasi/$row->rencana)*100,2);
		$kegiatan = $result->result();
		$indikator_program = $this->m_cik_perubahan->get_indikator_prog_keg_preview($row->id, $bulan, FALSE, TRUE);
		$temp = $indikator_program->result();
		$total_temp = $indikator_program->num_rows();
		
		$col_indikator=1;
		$go_2_keg = FALSE;
		$total_for_iteration = $total_temp;
		if($total_temp > $max_col_keg){
			$total_temp = $max_col_keg;
			$go_2_keg = TRUE;
		}
?>
<tr bgcolor="#FF9933">
	<td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>"><?php echo $row->kd_urusan; ?></td>
	<td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>"><?php echo $row->kd_bidang; ?></td>
	<td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>"><?php echo $row->kd_program; ?></td>
	<td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>"><?php echo $row->kd_kegiatan; ?></td>
	<td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>"><?php echo $row->nama_prog_or_keg; ?></td>
	<td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>" align="right"><?php echo Formatting::currency($row->rencana); ?>
	</td>
	<td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>" align="right"><?php echo Formatting::currency($row->realisasi); ?>
	</td>
	<td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>" align="right"><?php echo $cik_pro_keg; ?></td>
	<td>
		<?php
			echo $temp[0]->indikator;
		?>
	</td>
	<td align="right">
		<?php
			echo $temp[0]->target;
		?>
	</td>
	<td align="right">
		<?php
			echo (empty($temp[0]->realisasi)) ? 0 :$temp[0]->realisasi;
		?>
	</td>
	<td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>" align="right">
				<?php
					echo $row->capaian;
				?>
			</td>
	<td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>" align="center">-</td>
	<?php
		if ($total_for_iteration > 1) {			
			for ($i=1; $i < $total_for_iteration; $i++) { 
				$col_indikator++;
	?>
		<tr>
			<?php
					if ($go_2_keg && $col_indikator > $max_col_keg) { 
				?>
					<td style="border-top: 0;border-bottom: 0;" ></td>
					<td style="border-top: 0;border-bottom: 0;" ></td>
					<td style="border-top: 0;border-bottom: 0;" ></td>
					<td style="border-top: 0;border-bottom: 0;" ></td>
					<td style="border-top: 0;border-bottom: 0;" ></td>
					<td style="border-top: 0;border-bottom: 0;" ></td>
					<td style="border-top: 0;border-bottom: 0;" ></td>
					<td style="border-top: 0;border-bottom: 0;" ></td>
				<?php
					}
				?>
				<td>
					<?php
						echo $temp[$i]->indikator;
					?>
				</td>
				<td align="right">
					<?php
						echo $temp[$i]->target;
					?>
				</td>
				<td align="right">
					<?php
						echo $temp[$i]->realisasi;
					?>
				</td>
				<td style="border-top: 0;border-bottom: 0;"></td>
			</tr>
		<?php
			}
		}
			foreach ($kegiatan as $row) {
			$indikator_kegiatan = $this->m_cik_perubahan->get_indikator_prog_keg_preview($row->id, $bulan, FALSE, TRUE);
			$temp = $indikator_kegiatan->result();			
			$total_temp = $indikator_kegiatan->num_rows();

			$go_2_keg = FALSE;
			$col_indikator_keg=1;
			$total_for_iteration = $total_temp;
			if ($total_temp > $max_col_keg) {
				$total_temp = $max_col_keg;
				$go_2_keg = TRUE;
			}
		?>
		<tr>
			<td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>"><?php echo $row->kd_urusan; ?></td>
			<td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>"><?php echo $row->kd_bidang; ?></td>
			<td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>"><?php echo $row->kd_program; ?></td>
			<td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>"><?php echo $row->kd_kegiatan; ?></td>
			<td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>"><?php echo $row->nama_prog_or_keg; ?></td>
			<td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>" align="right"><?php echo Formatting::currency($row->rencana); ?></td>
			<td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>" align="right"><?php echo Formatting::currency($row->realisasi); ?></td>
			<td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>" align="right"><?php echo (empty($row->realisasi)) ? 0 :round(($row->realisasi/$row->rencana)*100,2); ?></td>
			<td>
				<?php
					echo $temp[0]->indikator;
				?>
			</td>
			<td align="right">
				<?php
					echo $temp[0]->target;
				?>
			</td>
			<td align="right">
				<?php
					echo (empty($temp[0]->realisasi)) ? 0 :$temp[0]->realisasi;
				?>
			</td>
			<td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>" align="right">
				<?php
					echo $row->capaian;
				?>
			</td>
			<td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>" align="center">-</td>
			
	 <?php
		if ($total_for_iteration > 1) {			
			for ($i=1; $i < $total_for_iteration; $i++) { 
				$col_indikator++;
	?>
		<tr>
			<?php
					if ($go_2_keg && $col_indikator > $max_col_keg) { 
				?>
					<td style="border-top: 0;border-bottom: 0;" ></td>
					<td style="border-top: 0;border-bottom: 0;" ></td>
					<td style="border-top: 0;border-bottom: 0;" ></td>
					<td style="border-top: 0;border-bottom: 0;" ></td>
					<td style="border-top: 0;border-bottom: 0;" ></td>
					<td style="border-top: 0;border-bottom: 0;" ></td>
					<td style="border-top: 0;border-bottom: 0;" ></td>
					<td style="border-top: 0;border-bottom: 0;" ></td>
				<?php
					}
				?>
				<td>
					<?php
						echo $temp[$i]->indikator;
					?>
				</td>
				<td align="right">
					<?php
						echo $temp[$i]->target;
					?>
				</td>
				<td align="right">
					<?php
						echo (empty($temp[$i]->realisasi)) ? 0 :$temp[$i]->realisasi;
					?>
				</td>
				<td style="border-top: 0;border-bottom: 0;" ></td>
				<td style="border-top: 0;border-bottom: 0;" ></td>
				 
			</tr>
<?php
		}
	}
}
	}
}
	}
	}
	
     $sisa = $tot_rencana-$tot_realisasi; 
	?>
    <tr>
    	<td colspan="4">&nbsp;</td>
        <td align="right">JUMLAH&nbsp;&nbsp;&nbsp;</td>
        <td align="right"><?php echo Formatting::currency($tot_rencana); ?></td>
        <td align="right"><?php echo Formatting::currency($tot_realisasi); ?></td>
        <td align="right"><?php echo (empty($tot_realisasi)) ? 0 :round(($tot_realisasi/$tot_rencana)*100,2); ?></td>
        <td colspan="3" align="right">Rata-rata Capaian Program</td>
        <td align="right"><?php echo round($tot_prog,2); ?></td>
        <td >&nbsp;</td>
    </tr>
    <tr>
    	<td colspan="4">&nbsp;</td>
        <td align="right">SISA&nbsp;&nbsp;&nbsp;</td>
        <td colspan="2" align="right"><?php echo Formatting::currency($sisa); ?></td>
        <td align="right"><?php echo (empty($sisa)) ? 0 :round(($sisa/$tot_rencana)*100,2); ?></td>
        <td colspan="3" align="right">Rata-rata Capaian Kegiatan</td>
        <td align="right"><?php echo round($tot_keg,2); ?></td>
        <td >&nbsp;</td>
    </tr>
</tbody>