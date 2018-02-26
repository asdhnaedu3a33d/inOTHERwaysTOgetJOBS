<?php
	//$tahun_sekarang=$this->session->userdata('t_anggaran_aktif'); 
	$nama_skpd = $this->session->userdata('nama_skpd');
	$max_col_keg=1;
?>
			<thead>
				<tr>
					<th rowspan="3" colspan="4">Kode</th>
					<th rowspan="3" >Program dan Kegiatan</th>
					<th rowspan="3" >Kriteria Keberhasilan</th>
					<th rowspan="3" >Ukuran Keberhasilan</th>
					<th colspan="6" >Kinerja Bulanan</th>
					<th rowspan="3" >Ket.</th>
				</tr>
				<tr>
					<th rowspan="2" width="80px">Bulan</th>
					<th colspan="3">Input</th>
					<th colspan="2">Output</th>
				</tr>
				<tr>
					<th> Pagu (Rp.)</th>
					<th> Realisasi   </th>
					<th> Capaian (%) </th>
					<th> Ukuran Kinerja Bulanan   </th>
					<th> Capaian (%) </th>
				</tr>
			</thead>
			<tbody>
				<?php
					foreach ($urusan as $row_urusan)
					 {
					echo "
					<tr style='background-color: #78cbfd !important'>
						<td>".$row_urusan->kd_urusan." </td>
						<td></td>
						<td></td>
						<td></td>
						<td colspan=\"10 \">
							<strong>".strtoupper($row_urusan->nama_urusan)."</strong>
						</td>
					</tr>";
					$bidang = $this->db->query("
						SELECT t.*,b.Nm_Bidang as nama_bidang FROM (
						SELECT	pro.*,
								SUM(keg.nominal_1) AS sum_nominal_1,
								SUM(keg.nominal_2) AS sum_nominal_2,
								SUM(keg.nominal_3) AS sum_nominal_3,
								SUM(keg.nominal_4) AS sum_nominal_4,
								SUM(keg.nominal_5) AS sum_nominal_5,
								SUM(keg.nominal_6) AS sum_nominal_6,
								SUM(keg.nominal_7) AS sum_nominal_7,
								SUM(keg.nominal_8) AS sum_nominal_8,
								SUM(keg.nominal_9) AS sum_nominal_9,
								SUM(keg.nominal_10) AS sum_nominal_10,
								SUM(keg.nominal_11) AS sum_nominal_11,
								SUM(keg.nominal_12) AS sum_nominal_12
							FROM
								(SELECT * FROM tx_dpa_prog_keg WHERE is_prog_or_keg=1) AS pro
							INNER JOIN
								(SELECT * FROM tx_dpa_prog_keg WHERE is_prog_or_keg=2) AS keg ON keg.parent=pro.id
							WHERE
								keg.id_skpd=".$id_skpd."
								AND keg.tahun= ".$ta."
								AND keg.kd_urusan =".$row_urusan->kd_urusan."
							GROUP BY keg.kd_bidang
							ORDER BY kd_urusan ASC, kd_bidang ASC, kd_program ASC, kd_kegiatan ASC
						)t 
						LEFT JOIN m_bidang b
						ON t.kd_urusan = b.Kd_Urusan AND t.kd_bidang = b.Kd_Bidang
					")->result();
					
					foreach ($bidang as $row_bidang) {
						echo "
						<tr style='background-color: #00FF33 !important'>
							<td>".$row_urusan->kd_urusan." </td>
							<td>".$row_bidang->kd_bidang."</td>
							<td></td>
							<td></td>
							<td colspan=\"10 \">
								<strong>".strtoupper($row_bidang->nama_bidang)."</strong>
							</td>
						</tr>";
					
					$program = $this->m_kendali->get_program_dpa_4_cetak($id_skpd,$ta,$row_urusan->kd_urusan,$row_bidang->kd_bidang);
					
					foreach ($program as $row) {
						$result = $this->m_kendali->get_kegiatan_dpa_4_cetak($row->id,$ta);
						$kegiatan = $result->result();
						$indikator_program = $this->m_kendali->get_indikator_prog_keg_dpa($row->id, FALSE, TRUE);
						$temp = $indikator_program->result();
						$total_temp = $indikator_program->num_rows();
						
						$tot_temp = $total_temp;
						$col_indikator=1;
						$go_2_keg = FALSE;
						$total_for_iteration = $total_temp;
						if($total_temp > $max_col_keg){
							$total_temp = $max_col_keg;
							$go_2_keg = TRUE;
						}
				?>
				<tr style="background-color: #FF9933 !important">
					<td style="border-bottom: 0;" rowspan="<?php echo $tot_temp; ?>"><?php echo $row->kd_urusan; ?></td>
					<td style="border-bottom: 0;" rowspan="<?php echo $tot_temp; ?>"><?php echo $row->kd_bidang; ?></td>
					<td style="border-bottom: 0;" rowspan="<?php echo $tot_temp; ?>"><?php echo $row->kd_program; ?></td>
					<td style="border-bottom: 0;" rowspan="<?php echo $tot_temp; ?>"><?php echo $row->kd_kegiatan; ?></td>
					<td style="border-bottom: 0;" rowspan="<?php echo $tot_temp; ?>"><?php echo $row->nama_prog_or_keg; ?></td>
					<td>
						<?php
							echo $temp[0]->indikator;
						?>
					</td>
					<td>
						<?php
							echo $temp[0]->indikator." ".$temp[0]->target." ".$temp[0]->satuan_target;
						?>
					</td>
					<td align="center" style="border-bottom: 0;" rowspan="<?php echo $tot_temp; ?>">
						
                    </td>
					<td align="right" style="border-bottom: 0;" rowspan="<?php echo $tot_temp; ?>">
                    	
                    </td>
					<td align="right" style="border-bottom: 0;" rowspan="<?php echo $tot_temp; ?>">
                    	
                    </td>
					<td align="right" style="border-bottom: 0;" rowspan="<?php echo $tot_temp; ?>">
                    	
                    </td>
					<td style="border-bottom: 0;" rowspan="<?php echo $tot_temp; ?>">
                    	
                    </td>
					<td align="right" style="border-bottom: 0;" rowspan="<?php echo $tot_temp; ?>">
                    	
                    </td>
					<td style="border-bottom: 0;" rowspan="<?php echo $tot_temp; ?>">
                    	
                    </td>
				</tr>
				<?php
					if ($total_for_iteration > 1) {			
						for ($i=1; $i < $total_for_iteration; $i++) { 
							$col_indikator++;
				?>
					<tr style="background-color: #FF9933 !important">
						<?php
								if ($go_2_keg && $col_indikator > $max_col_keg) { 
							?>
								
							<?php
								}
							?>
							<td>
								<?php
									echo $temp[$i]->indikator;
								?>
							</td>
							<td>
								<?php
									echo $temp[$i]->indikator." ".$temp[0]->target." ".$temp[0]->satuan_target;
								?>
							</td>
							<?php
								if ($go_2_keg && $col_indikator > $max_col_keg) {
							?>
								
					<?php
							}
					?>
						</tr>
					<?php
						}
					}
					foreach ($kegiatan as $row) {						
							$indikator_kegiatan = $this->m_kendali->get_indikator_prog_keg_dpa($row->id, FALSE, TRUE);
							$temp = $indikator_kegiatan->result();			
							$total_temp = $indikator_kegiatan->num_rows();

							$tot_temp = $total_temp;
							$go_2_keg = FALSE;
							$col_indikator_keg=1;
							$total_for_iteration = $total_temp;
							if ($total_temp > $max_col_keg) {
								$total_temp = $max_col_keg;
								$go_2_keg = TRUE;
							}
					?>
					<tr>
						<td style="border-bottom: 0;" rowspan="<?php echo $tot_temp; ?>"><?php echo $row->kd_urusan; ?></td>
						<td style="border-bottom: 0;" rowspan="<?php echo $tot_temp; ?>"><?php echo $row->kd_bidang; ?></td>
						<td style="border-bottom: 0;" rowspan="<?php echo $tot_temp; ?>"><?php echo $row->kd_program; ?></td>
						<td style="border-bottom: 0;" rowspan="<?php echo $tot_temp; ?>"><?php echo $row->kd_kegiatan; ?></td>
						<td style="border-bottom: 0;" rowspan="<?php echo $tot_temp; ?>"><?php echo $row->nama_prog_or_keg; ?></td>
						<td>
							<?php
								echo $temp[0]->indikator;
							?>
						</td>
						<td>
							<?php
								echo $temp[0]->indikator." ".$temp[0]->target." ".$temp[0]->satuan_target;
							?>
						</td>
						<td align="center" style="border-bottom: 0;" rowspan="<?php echo $tot_temp; ?>">
                            BLN 1 <br />
                            BLN 2 <br />
                            BLN 3 <br />
                            BLN 4 <br />
                            BLN 5 <br />
                            BLN 6 <br />
                            BLN 7 <br />
                            BLN 8 <br />
                            BLN 9 <br />
                            BLN 10 <br />
                            BLN 11 <br />
                            BLN 12 <br />
                    	</td>
						<td align="right" style="border-bottom: 0;" rowspan="<?php echo $tot_temp; ?>">
                    	<?php echo Formatting::currency($row->nominal_1); ?><br />
                        <?php echo Formatting::currency($row->nominal_2); ?><br />
                        <?php echo Formatting::currency($row->nominal_3); ?><br />
                        <?php echo Formatting::currency($row->nominal_4); ?><br />
                        <?php echo Formatting::currency($row->nominal_5); ?><br />
                        <?php echo Formatting::currency($row->nominal_6); ?><br />
                        <?php echo Formatting::currency($row->nominal_7); ?><br />
                        <?php echo Formatting::currency($row->nominal_8); ?><br />
                        <?php echo Formatting::currency($row->nominal_9); ?><br />
                        <?php echo Formatting::currency($row->nominal_10); ?><br />
                        <?php echo Formatting::currency($row->nominal_11); ?><br />
                        <?php echo Formatting::currency($row->nominal_12); ?><br />
                    </td>
					<td align="right" style="border-bottom: 0;" rowspan="<?php echo $tot_temp; ?>">
                    	0 <br />
                        0 <br />
                        0 <br />
                        0 <br />
                        0 <br />
                        0 <br />
                        0 <br />
                        0 <br />
                        0 <br />
                        0 <br />
                        0 <br />
                        0 <br />
                    </td>
					<td align="right" style="border-bottom: 0;" rowspan="<?php echo $tot_temp; ?>">
                    	<?php echo (empty($row->realisasi))?0:round($row->realisasi/$row->rencana)*100; ?> <br />
                        <?php echo (empty($row->realisasi))?0:round($row->realisasi/$row->rencana)*100; ?> <br />
                        <?php echo (empty($row->realisasi))?0:round($row->realisasi/$row->rencana)*100; ?> <br />
                        <?php echo (empty($row->realisasi))?0:round($row->realisasi/$row->rencana)*100; ?> <br />
                        <?php echo (empty($row->realisasi))?0:round($row->realisasi/$row->rencana)*100; ?> <br />
                        <?php echo (empty($row->realisasi))?0:round($row->realisasi/$row->rencana)*100; ?> <br />
                        <?php echo (empty($row->realisasi))?0:round($row->realisasi/$row->rencana)*100; ?> <br />
                        <?php echo (empty($row->realisasi))?0:round($row->realisasi/$row->rencana)*100; ?> <br />
                        <?php echo (empty($row->realisasi))?0:round($row->realisasi/$row->rencana)*100; ?> <br />
                        <?php echo (empty($row->realisasi))?0:round($row->realisasi/$row->rencana)*100; ?> <br />
                        <?php echo (empty($row->realisasi))?0:round($row->realisasi/$row->rencana)*100; ?> <br />
                        <?php echo (empty($row->realisasi))?0:round($row->realisasi/$row->rencana)*100; ?> <br />
                    </td>
					<td style="border-bottom: 0;" rowspan="<?php echo $tot_temp; ?>">
                    	<?php echo $row->catatan_1; ?><br />
                        <?php echo $row->catatan_2; ?><br />
                        <?php echo $row->catatan_3; ?><br />
                        <?php echo $row->catatan_4; ?><br />
                        <?php echo $row->catatan_5; ?><br />
                        <?php echo $row->catatan_6; ?><br />
                        <?php echo $row->catatan_7; ?><br />
                        <?php echo $row->catatan_8; ?><br />
                        <?php echo $row->catatan_9; ?><br />
                        <?php echo $row->catatan_10; ?><br />
                        <?php echo $row->catatan_11; ?><br />
                        <?php echo $row->catatan_12; ?><br />
                    </td>
					<td align="right" style="border-bottom: 0;" rowspan="<?php echo $tot_temp; ?>">
                    	<?php echo $row->capaian_1; ?><br />
                        <?php echo $row->capaian_2; ?><br />
                        <?php echo $row->capaian_3; ?><br />
                        <?php echo $row->capaian_4; ?><br />
                        <?php echo $row->capaian_5; ?><br />
                        <?php echo $row->capaian_6; ?><br />
                        <?php echo $row->capaian_7; ?><br />
                        <?php echo $row->capaian_8; ?><br />
                        <?php echo $row->capaian_9; ?><br />
                        <?php echo $row->capaian_10; ?><br />
                        <?php echo $row->capaian_11; ?><br />
                        <?php echo $row->capaian_12; ?><br />
                    </td>
					<td style="border-bottom: 0;" rowspan="<?php echo $tot_temp; ?>">
                    	<?php echo $row->ket_1; ?><br />
                        <?php echo $row->ket_2; ?><br />
                        <?php echo $row->ket_3; ?><br />
                        <?php echo $row->ket_4; ?><br />
                        <?php echo $row->ket_5; ?><br />
                        <?php echo $row->ket_6; ?><br />
                        <?php echo $row->ket_7; ?><br />
                        <?php echo $row->ket_8; ?><br />
                        <?php echo $row->ket_9; ?><br />
                        <?php echo $row->ket_10; ?><br />
                        <?php echo $row->ket_11; ?><br />
                        <?php echo $row->ket_12; ?><br />
                    </td>
					</tr>
					<?php
						if ($total_for_iteration > 1) {
						for ($i=1; $i < $total_for_iteration; $i++) { 					
							$col_indikator_keg++;
					?>
						<tr>
						<?php
							if ($go_2_keg && $col_indikator_keg > $max_col_keg) {
						?>
						<?php
							}
						?>
							<td>
								<?php
									echo $temp[$i]->indikator;
								?>
							</td>
							<td>
								<?php
									echo $temp[$i]->indikator." ".$temp[0]->target." ".$temp[0]->satuan_target;
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