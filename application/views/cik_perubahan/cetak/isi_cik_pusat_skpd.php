<?php
	$max_col_keg=1;
	$tot_rencana=0; $tot_realisasi=0;
	$p_total=0;
	$k_total=0;
	foreach ($skpd as $row_skpd){
		$tot_rencana_skpd=0; $tot_realisasi_skpd=0;
		$p=0;
		$k=0;
		$urusan = $this->m_cik_perubahan->get_urusan_cik($bulan,$ta,$row_skpd->id_skpd);
		if($row_skpd->sumrencana!=0){
			$cik_pro_keg_skpd = (empty($row_skpd->sumrealisasi))?0:round(($row_skpd->sumrealisasi/$row_skpd->sumrencana)*100,2);
		} else {
			$cik_pro_keg_skpd = 0;
		}
?>
	<tr bgcolor="#FFFF00">
        <td style="border-bottom: 0;" colspan="13"><strong><?php echo strtoupper($row_skpd->nama_skpd);?></strong></td>
        <?php /*<td style="border-bottom: 0;" align="right"><?php echo Formatting::currency($row_skpd->sumrencana); ?></td>
        <td style="border-bottom: 0;" align="right"><?php echo Formatting::currency($row_skpd->sumrealisasi); ?></td>
        <td style="border-bottom: 0;" align="right"><?php echo $cik_pro_keg_skpd; ?></td>
        <td style="border-bottom: 0;" colspan="5"></td> */?>
    </tr>
<?php
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
			<td align=\"right\">".Formatting::currency($row_urusan->sumrencana,2)."</td>
			<td align=\"right\">".Formatting::currency($row_urusan->sumrealisasi,2)."</td>
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
				keg.id_skpd = ".$row_skpd->id_skpd."
			  AND keg.tahun = ".$ta."
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
				<td align=\"right\">".Formatting::currency($row_bidang->rencana,2)."</td>
				<td align=\"right\">".Formatting::currency($row_bidang->realisasi,2)."</td>
				</td>
				<td colspan=\"6\"></td>
			</tr>";
			$program = $this->m_cik_perubahan->get_program_cik_4_cetak($row_skpd->id_skpd,$bulan,$ta,$row_urusan->kd_urusan,$row_bidang->kd_bidang);
			
	foreach($program as $row){
	if($row->id == ""){
		echo "<tr><td colspan='13' align='center'><strong>Data Belum Terisikan..</strong></td></tr>";
	}else{
		$p++;
		$p_total++;
		$tot_rencana_skpd += $row->rencana;
		$tot_realisasi_skpd += $row->realisasi;
		$tot_rencana += $row->rencana;
		$tot_realisasi += $row->realisasi;
		$result = $this->m_cik_perubahan->get_kegiatan_cik_4_cetak($row_urusan->kd_urusan,$row_bidang->kd_bidang,$row->kd_program,$row_skpd->id_skpd,$bulan,$ta);
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
	<td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>" align="right"><?php echo Formatting::currency($row->rencana,2); ?>
	</td>
	<td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>" align="right"><?php echo Formatting::currency($row->realisasi,2); ?>
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
		<tr bgcolor="#FF9933">
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
                <td style="border-top: 0;border-bottom: 0;"></td>
			</tr>
		<?php
			}
		}
			foreach ($kegiatan as $row) {
			$k++;
			$k_total++;
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
			<td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>" align="right"><?php echo Formatting::currency($row->rencana,2); ?></td>
			<td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>" align="right"><?php echo Formatting::currency($row->realisasi,2); ?></td>
			<?php if($row->rencana!=0){ ?>
            	<td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>" align="right"><?php echo (empty($row->realisasi)) ? 0 :round(($row->realisasi/$row->rencana)*100,2); ?></td>
			<?php } else { ?>
            	<td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>" align="right"><?php echo round(0,2); ?></td>
            <?php } ?>
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
	
	$tot_prog_skpd = $this->m_cik_perubahan->sum_capaian_program($row_skpd->id_skpd,$bulan,$ta);
	$count_prog_skpd = $this->m_cik_perubahan->count_program($row_skpd->id_skpd,$bulan,$ta);
	$tot_keg_skpd = $this->m_cik_perubahan->sum_capaian_kegiatan($row_skpd->id_skpd,$bulan,$ta);
	$count_keg_skpd = $this->m_cik_perubahan->count_kegiatan($row_skpd->id_skpd,$bulan,$ta);
	$tot_prog_count = $tot_prog_skpd->capaianp/$count_prog_skpd->countp;
	$tot_keg_count = $tot_keg_skpd->capaiank/$count_keg_skpd->countk;
	$sisa_skpd = $tot_rencana_skpd-$tot_realisasi_skpd; 
	?>
    <tr bgcolor="#CCCCCC">
    	<td colspan="4">&nbsp;</td>
        <td align="right" colspan="2"><strong>JUMLAH PROGRAM SKPD</strong></td>
        <td align="right" colspan="7"><?php echo $p." Program"; ?></td>
    </tr>
    <tr bgcolor="#CCCCCC">
    	<td colspan="4">&nbsp;</td>
        <td align="right" colspan="2"><strong>JUMLAH KEGIATAN SKPD</strong></td>
        <td align="right" colspan="7"><?php echo $k." Kegiatan"; ?></td>
    </tr>
    <tr bgcolor="#CCCCCC">
    	<td colspan="4">&nbsp;</td>
        <td align="right"><strong>JUMLAH&nbsp;&nbsp;&nbsp;</strong></td>
        <td align="right"><?php echo Formatting::currency($tot_rencana_skpd,2); ?></td>
        <td align="right"><?php echo Formatting::currency($tot_realisasi_skpd,2); ?></td>
        <td align="right"><?php echo (empty($tot_realisasi_skpd)) ? 0 :round(($tot_realisasi_skpd/$tot_rencana_skpd)*100,2); ?></td>
        <td colspan="3" align="right"><strong>Rata-rata Capaian Program</strong></td>
        <td align="right"><?php echo round($tot_prog_count,2); ?></td>
        <td >&nbsp;</td>
    </tr>
    <tr bgcolor="#CCCCCC">
    	<td colspan="4">&nbsp;</td>
        <td align="right"><strong>SISA&nbsp;&nbsp;&nbsp;</strong></td>
        <td colspan="2" align="right"><?php echo Formatting::currency($sisa_skpd,2); ?></td>
        <td align="right"><?php echo (empty($sisa_skpd)) ? 0 :round(($sisa_skpd/$tot_rencana_skpd)*100,2); ?></td>
        <td colspan="3" align="right"><strong>Rata-rata Capaian Kegiatan<strong></td>
        <td align="right"><?php echo round($tot_keg_count,2); ?></td>
        <td >&nbsp;</td>
    </tr>
<?php
	}
	if (!empty($id_skpd) && $id_skpd=="all") {
	$sisa = $tot_rencana-$tot_realisasi;
?>
	<tr bgcolor="#78cbfd">
    	<td colspan="4">&nbsp;</td>
        <td align="right" colspan="2"><strong>TOTAL JUMLAH PROGRAM</strong></td>
        <td align="right" colspan="7"><?php echo $p_total." Program"; ?></td>
    </tr>
    <tr bgcolor="#78cbfd">
    	<td colspan="4">&nbsp;</td>
        <td align="right" colspan="2"><strong>TOTAL JUMLAH KEGIATAN SKPD</strong></td>
        <td align="right" colspan="7"><?php echo $k_total." Kegiatan"; ?></td>
    </tr>
	<tr bgcolor="#78cbfd">
    	<td colspan="4">&nbsp;</td>
        <td align="right">JUMLAH TOTAL&nbsp;&nbsp;&nbsp;</td>
        <td align="right"><?php echo Formatting::currency($tot_rencana,2); ?></td>
        <td align="right"><?php echo Formatting::currency($tot_realisasi,2); ?></td>
        <td align="right"><?php echo (empty($tot_realisasi)) ? 0 :round(($tot_realisasi/$tot_rencana)*100,2); ?></td>
        <td colspan="3" align="right">Rata-rata Capaian Program</td>
        <td align="right"><?php echo round($tot_prog,2); ?></td>
        <td colspan="3">&nbsp;</td>
    </tr>
    <tr bgcolor="#78cbfd">
    	<td colspan="4">&nbsp;</td>
        <td align="right">SISA TOTAL&nbsp;&nbsp;&nbsp;</td>
        <td colspan="2" align="right"><?php echo Formatting::currency($sisa,2); ?></td>
        <td align="right"><?php echo (empty($sisa)) ? 0 :round(($sisa/$tot_rencana)*100,2); ?></td>
        <td colspan="3" align="right">Rata-rata Capaian Kegiatan</td>
        <td align="right"><?php echo round($tot_keg,2); ?></td>
        <td colspan="3">&nbsp;</td>
    </tr>
  <?php }
  ?>