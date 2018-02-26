<?php
	$max_col_keg=1;
	$tot_rencana=0; $tot_realisasi=0;
	foreach($urusan as $row_urusan){
		if($row_urusan->id == ""){
		echo "<tr><td colspan='13' align='center'><strong>Data Belum Terisikan..</strong></td></tr>";
		}else{
			$p=0;
			$k=0;
			$tot_rencana_urusan=0;
			$tot_realisasi_urusan=0;
			$bidang = $this->m_cik->get_bidang_cik_pusat_4_cetak($row_urusan->kd_urusan, $bulan, $ta);
			$cik_pro_keg_urusan = (empty($row_urusan->sumrealisasi))?0:round(($row_urusan->sumrealisasi/$row_urusan->sumrencana)*100,2);
			$tot_rencana_urusan+=$row_urusan->sumrencana;
			$tot_realisasi_urusan+=$row_urusan->sumrealisasi;
?>
	    <tr bgcolor="#78cbfd">
            <td style="border-bottom: 0;" ><?php echo $row_urusan->kd_urusan; ?></td>
            <td style="border-bottom: 0;" ></td>
            <td style="border-bottom: 0;" ></td>
            <td style="border-bottom: 0;" ></td>
			<td style="border-bottom: 0;" ><?php echo $row_urusan->nama_urusan; ?></td>
            <td style="border-bottom: 0;" align="right"><?php echo Formatting::currency($row_urusan->sumrencana,2); ?>
            </td>
            <td style="border-bottom: 0;" align="right"><?php echo Formatting::currency($row_urusan->sumrealisasi,2); ?>
            </td>
            <td style="border-bottom: 0;" align="right"><?php echo $cik_pro_keg_urusan; ?></td>
            <td colspan="4"></td>
            <td style="border-bottom: 0;" align="center">-</td>
        </tr>
        <?php
			foreach($bidang as $row_bidang){
				$tot_rencana_bidang = 0;
				$tot_realisasi_bidang = 0;
				$p_bidang = 0;
				$k_bidang = 0;
				$skpd = $this->m_cik->get_skpd_cik_pusat_4_cetak($row_urusan->kd_urusan,$row_bidang->kd_bidang,$bulan,$ta);
				$cik_pro_keg_bidang = (empty($row_bidang->sumrealisasi))?0:round(($row_bidang->sumrealisasi/$row_bidang->sumrencana)*100,2);
				$tot_rencana_bidang += $row_bidang->sumrencana;
				$tot_realisasi_bidang += $row_bidang->sumrealisasi;
		?>
        		<tr bgcolor="#00FF33">
                    <td style="border-bottom: 0;" ><?php echo $row_urusan->kd_urusan; ?></td>
                    <td style="border-bottom: 0;" ><?php echo $row_bidang->kd_bidang; ?></td>
                    <td style="border-bottom: 0;" ></td>
                    <td style="border-bottom: 0;" ></td>
                    <td style="border-bottom: 0;" ><?php echo $row_bidang->nama_bidang; ?></td>
                    <td style="border-bottom: 0;" align="right"><?php echo Formatting::currency($row_bidang->sumrencana,2); ?>
                    </td>
                    <td style="border-bottom: 0;" align="right"><?php echo Formatting::currency($row_bidang->sumrealisasi,2); ?>
                    </td>
                    <td style="border-bottom: 0;" align="right"><?php echo $cik_pro_keg_bidang; ?></td>
                    <td colspan="4"></td>
                    <td style="border-bottom: 0;" align="center">-</td>
                </tr>
            <?php
				foreach($skpd as $row_skpd){
					$program = $this->m_cik->get_program_cik_pusat_4_cetak($row_urusan->kd_urusan,$row_bidang->kd_bidang,$row_skpd->id_skpd,$bulan,$ta);
					$cik_pro_keg_skpd = (empty($row_skpd->sumrealisasi))?0:round(($row_skpd->sumrealisasi/$row_skpd->sumrencana)*100,2);
			?>
            		<tr>
                    	<td style="border-bottom: 0;" colspan="5"><strong><?php echo strtoupper($row_skpd->nama_skpd);?></strong></td>
                        <td style="border-bottom: 0;" align="right"><?php echo Formatting::currency($row_skpd->sumrencana,2); ?></td>
                        <td style="border-bottom: 0;" align="right"><?php echo Formatting::currency($row_skpd->sumrealisasi,2); ?></td>
                        <td style="border-bottom: 0;" align="right"><?php echo $cik_pro_keg_skpd; ?></td>
                        <td style="border-bottom: 0;" colspan="5"></td>
                    </tr>
                <?php
					foreach($program as $row_program){
						$p++;
						$p_bidang++;
						$tot_rencana += $row_program->sumrencana;
						$tot_realisasi += $row_program->sumrealisasi;
						$kegiatan = $this->m_cik->get_kegiatan_cik_pusat_4_cetak($row_urusan->kd_urusan,$row_bidang->kd_bidang,$row_program->kd_program,$row_skpd->id_skpd,$bulan, $ta);
						//var_dump($kegiatan);
						$indikator_program = $this->m_cik->get_indikator_prog_keg_preview($row_program->id, $bulan, FALSE, TRUE);
						$temp = $indikator_program->result();
						$total_temp = $indikator_program->num_rows();
						$cik_pro_keg_program = (empty($row_program->sumrealisasi))?0:round(($row_program->sumrealisasi/$row_program->sumrencana)*100,2);
						$col_indikator=1;
						$go_2_keg = FALSE;
						$total_for_iteration = $total_temp;
						if($total_temp > $max_col_keg){
							$total_temp = $max_col_keg;
							$go_2_keg = TRUE;
						}
				?>
                		<tr bgcolor="#FF8000">
                            <td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>"><?php echo $row_urusan->kd_urusan; ?></td>
                            <td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>"><?php echo $row_bidang->kd_bidang; ?></td>
                            <td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>"><?php echo $row_program->kd_program; ?></td>
                            <td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>"></td>
                            <td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>"><?php echo $row_program->nama_prog_or_keg; ?></td>
                            <td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>" align="right"><?php echo Formatting::currency($row_program->sumrencana,2); ?>
                            </td>
                            <td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>" align="right"><?php echo Formatting::currency($row_program->sumrealisasi,2); ?>
                            </td>
                            <td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>" align="right"><?php echo $cik_pro_keg_program; ?></td>
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
                                            echo $row_program->capaian;
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
                                        <td style="border-top: 0;border-bottom: 0;"></td>
                                 </tr>                      
<?php
									}
								}
?>
						</tr>
<?php
							foreach ($kegiatan as $row_kegiatan) {
								$k++;
								$k_bidang++;
								$indikator_kegiatan = $this->m_cik->get_indikator_prog_keg_preview($row_kegiatan->id, $bulan, FALSE, TRUE);
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
                                    <td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>"><?php echo $row_urusan->kd_urusan; ?></td>
                                    <td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>"><?php echo $row_bidang->kd_bidang; ?></td>
                                    <td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>"><?php echo $row_program->kd_program; ?></td>
                                    <td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>"><?php echo $row_kegiatan->kd_kegiatan; ?></td>
                                    <td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>"><?php echo $row_kegiatan->nama_prog_or_keg; ?></td>
                                    <td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>" align="right"><?php echo Formatting::currency($row_kegiatan->rencana,2); ?></td>
                                    <td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>" align="right"><?php echo Formatting::currency($row_kegiatan->realisasi,2); ?></td>
                                    <td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>" align="right"><?php echo (empty($row_kegiatan->realisasi)) ? 0 :round(($row_kegiatan->realisasi/$row_kegiatan->rencana)*100,2); ?></td>
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
                                            echo $row_kegiatan->capaian;
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
				$tot_prog_bidang = $this->m_cik->sum_capaian_program_bidang($row_urusan->kd_urusan,$row_bidang->kd_bidang,$bulan,$ta);
				$count_prog_bidang = $this->m_cik->count_program_bidang($row_urusan->kd_urusan,$row_bidang->kd_bidang,$bulan,$ta);
				$tot_keg_bidang = $this->m_cik->sum_capaian_kegiatan_bidang($row_urusan->kd_urusan,$row_bidang->kd_bidang,$bulan,$ta);
				$count_keg_bidang = $this->m_cik->count_kegiatan_bidang($row_urusan->kd_urusan,$row_bidang->kd_bidang,$bulan,$ta);
				$tot_prog_count_bidang = $tot_prog_bidang->capaianp/$count_prog_bidang->countp;
				$tot_keg_count_bidang = $tot_keg_bidang->capaiank/$count_keg_bidang->countk;
				$sisa_bidang = $tot_rencana_bidang - $tot_realisasi_bidang;
?>
				<tr bgcolor="#999999">
                    <td colspan="4">&nbsp;</td>
                    <td align="right" colspan="2"><strong><?php echo "JUMLAH PROGRAM BIDANG ".strtoupper($row_bidang->nama_bidang) ;?></strong></td>
                    <td align="right" colspan="7"><?php echo $p_bidang." Program"; ?></td>
                </tr>
                <tr bgcolor="#999999">
                    <td colspan="4">&nbsp;</td>
                    <td align="right" colspan="2"><strong><?php echo "JUMLAH KEGIATAN BIDANG ".strtoupper($row_bidang->nama_bidang) ;?></strong></td>
                    <td align="right" colspan="7"><?php echo $k_bidang." Kegiatan"; ?></td>
                </tr>
                <tr bgcolor="#999999">
                    <td colspan="4">&nbsp;</td>
                    <td align="right"><strong>JUMLAH&nbsp;&nbsp;&nbsp;</strong></td>
                    <td align="right"><?php echo Formatting::currency($tot_rencana_bidang); ?></td>
                    <td align="right"><?php echo Formatting::currency($tot_realisasi_bidang); ?></td>
                    <td align="right"><?php echo (empty($tot_realisasi_bidang)) ? 0 :round(($tot_realisasi_bidang/$tot_rencana_bidang)*100,2); ?></td>
                    <td colspan="3" align="right"><strong>Rata-rata Capaian Program</strong></td>
                    <td align="right"><?php echo round($tot_prog_count_bidang,2); ?></td>
                    <td >&nbsp;</td>
                </tr>
                <tr bgcolor="#999999">
                    <td colspan="4">&nbsp;</td>
                    <td align="right"><strong>SISA&nbsp;&nbsp;&nbsp;</strong></td>
                    <td colspan="2" align="right"><?php echo Formatting::currency($sisa_bidang); ?></td>
                    <td align="right"><?php echo (empty($sisa_bidang)) ? 0 :round(($sisa_bidang/$tot_rencana_bidang)*100,2); ?></td>
                    <td colspan="3" align="right"><strong>Rata-rata Capaian Kegiatan<strong></td>
                    <td align="right"><?php echo round($tot_keg_count_bidang,2); ?></td>
                    <td >&nbsp;</td>
                </tr>
<?php
			}
		}
	$tot_prog_urusan = $this->m_cik->sum_capaian_program_urusan($row_urusan->kd_urusan,$bulan,$ta);
	$count_prog_urusan = $this->m_cik->count_program_urusan($row_urusan->kd_urusan,$bulan,$ta);
	$tot_keg_urusan = $this->m_cik->sum_capaian_kegiatan_urusan($row_urusan->kd_urusan,$bulan,$ta);
	$count_keg_urusan = $this->m_cik->count_kegiatan_urusan($row_urusan->kd_urusan,$bulan,$ta);
	$tot_prog_count = $tot_prog_urusan->capaianp/$count_prog_urusan->countp;
	$tot_keg_count = $tot_keg_urusan->capaiank/$count_keg_urusan->countk;
	$sisa_urusan = $tot_rencana_urusan-$tot_realisasi_urusan; 
?>
	<tr bgcolor="#FFFF00">
    	<td colspan="4">&nbsp;</td>
        <td align="right" colspan="2"><strong><?php echo "JUMLAH PROGRAM ".strtoupper($row_urusan->nama_urusan);?></strong></td>
        <td align="right" colspan="7"><?php echo $p." Program"; ?></td>
    </tr>
    <tr bgcolor="#FFFF00">
    	<td colspan="4">&nbsp;</td>
        <td align="right" colspan="2"><strong><?php echo "JUMLAH KEGIATAN ".strtoupper($row_urusan->nama_urusan);?></strong></td>
        <td align="right" colspan="7"><?php echo $k." Kegiatan"; ?></td>
    </tr>
    <tr bgcolor="#FFFF00">
    	<td colspan="4">&nbsp;</td>
        <td align="right"><strong>JUMLAH&nbsp;&nbsp;&nbsp;</strong></td>
        <td align="right"><?php echo Formatting::currency($tot_rencana_urusan); ?></td>
        <td align="right"><?php echo Formatting::currency($tot_realisasi_urusan); ?></td>
        <td align="right"><?php echo (empty($tot_realisasi_urusan)) ? 0 :round(($tot_realisasi_urusan/$tot_rencana_urusan)*100,2); ?></td>
        <td colspan="3" align="right"><strong>Rata-rata Capaian Program</strong></td>
        <td align="right"><?php echo round($tot_prog_count,2); ?></td>
        <td >&nbsp;</td>
    </tr>
    <tr bgcolor="#FFFF00">
    	<td colspan="4">&nbsp;</td>
        <td align="right"><strong>SISA&nbsp;&nbsp;&nbsp;</strong></td>
        <td colspan="2" align="right"><?php echo Formatting::currency($sisa_urusan); ?></td>
        <td align="right"><?php echo (empty($sisa_urusan)) ? 0 :round(($sisa_urusan/$tot_rencana_urusan)*100,2); ?></td>
        <td colspan="3" align="right"><strong>Rata-rata Capaian Kegiatan<strong></td>
        <td align="right"><?php echo round($tot_keg_count,2); ?></td>
        <td >&nbsp;</td>
    </tr>
<?php
	}
	?>
    <tr bgcolor="#78cbfd">
    	<td colspan="4">&nbsp;</td>
        <td align="right"> TOTAL JUMLAH&nbsp;&nbsp;&nbsp;</td>
        <td align="right"><?php echo Formatting::currency($tot_rencana); ?></td>
        <td align="right"><?php echo Formatting::currency($tot_realisasi); ?></td>
        <td align="right"><?php echo (empty($tot_realisasi)) ? 0 :round(($tot_realisasi/$tot_rencana)*100,2); ?></td>
        <td colspan="3" align="right">Rata-rata Capaian Program</td>
        <td align="right"><?php echo round($tot_prog,2); ?></td>
        <td colspan="3">&nbsp;</td>
    </tr>
	<?php $sisa = $tot_rencana-$tot_realisasi; ?>
    <tr bgcolor="#78cbfd">
    	<td colspan="4">&nbsp;</td>
        <td align="right">TOTAL SISA&nbsp;&nbsp;&nbsp;</td>
        <td colspan="2" align="right"><?php echo Formatting::currency($sisa); ?></td>
        <td align="right"><?php echo (empty($sisa)) ? 0 :round(($sisa/$tot_rencana)*100,2); ?></td>
        <td colspan="3" align="right">Rata-rata Capaian Kegiatan</td>
        <td align="right"><?php echo round($tot_keg,2); ?></td>
        <td colspan="3">&nbsp;</td>
    </tr>