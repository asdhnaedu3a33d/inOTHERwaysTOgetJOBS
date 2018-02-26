<?php
	$max_col=1;
	$max_col_keg=1;
?>
<thead>
	<tr>
		<th style="display:none;" rowspan="3">Tujuan</th>
		<th style="display:none;" rowspan="3">Sasaran</th>
		<th style="display:none;" rowspan="3">Indikator Sasaran</th>
		<th rowspan="3" colspan="4">Kode</th>
		<th rowspan="3">Program dan Kegiatan</th>
		<th rowspan="3">Indikator Kinerja Program (outcome) dan Kegiatan (output)</th>
		<th rowspan="3">Data Capaian pada Tahun Awal Perencanaan</th>
		<th colspan="15">Tahun Kinerja Program dan Kerangka Pendanaan</th>
		<th rowspan="2" colspan="2">Kondisi Kinerja pada akhir periode</th>
		<th rowspan="3">Unit Kerja SKPD Penanggung Jawab</th>
		<th rowspan="3">Lokasi</th>
	</tr>
	<tr>
		<th colspan="3" bgcolor="#ffbb88">Tahun I</th>
		<th colspan="3" bgcolor="#77ddff">Tahun II</th>
		<th colspan="3" bgcolor="#77ff88">Tahun III</th>
		<th colspan="3" bgcolor="#88ffcc">Tahun IV</th>
		<th colspan="3" bgcolor="#EDFD6D">Tahun V</th>
	</tr>
	<tr>
		<th bgcolor="#ffbb88">Target</th>
		<th bgcolor="#ffbb88">Rp</th>
		<th bgcolor="#ffbb88">Lokasi</th>
		<th bgcolor="#77ddff">Target</th>
		<th bgcolor="#77ddff">Rp</th>
		<th bgcolor="#77ddff">Lokasi</th>
		<th bgcolor="#77ff88">Target</th>
		<th bgcolor="#77ff88">Rp</th>
		<th bgcolor="#77ff88">Lokasi</th>
		<th bgcolor="#88ffcc">Target</th>
		<th bgcolor="#88ffcc">Rp</th>
		<th bgcolor="#88ffcc">Lokasi</th>
		<th bgcolor="#EDFD6D">Target</th>
		<th bgcolor="#EDFD6D">Rp</th>
		<th bgcolor="#EDFD6D">Lokasi</th>
		<th>Target</th>
		<th>Rp</th>
	</tr>
</thead>
<tbody>
<?php
	$TotalNominal1=0;$TotalNominal2=0;$TotalNominal3=0;$TotalNominal4=0;$TotalNominal5=0;$SumTotalNominal=0;
	foreach ($program as $row) {
		$TotalNominal1 += $row->nominal_1_pro;
		$TotalNominal2 += $row->nominal_2_pro;
		$TotalNominal3 += $row->nominal_3_pro;
		$TotalNominal4 += $row->nominal_4_pro;
		$TotalNominal5 += $row->nominal_5_pro;
		$result = $this->m_renstra_trx->get_kegiatan_skpd_4_cetak($row->id);
		$total_kegiatan = $this->m_renstra_trx->get_total_kegiatan_dan_indikator($row->id)->total;

		$kegiatan = $result->result();

		$total_nominal = $row->nominal_1_pro+$row->nominal_2_pro+$row->nominal_3_pro+$row->nominal_4_pro+$row->nominal_5_pro;
		$SumTotalNominal += $total_nominal;
		$indikator_sasaran = $this->m_renstra_trx->get_indikator_sasaran($row->id_sasaran);
		$indikator_program = $this->m_renstra_trx->get_indikator_prog_keg($row->id, FALSE, FALSE);
		$temp = $indikator_program->result();

		$total_temp = $indikator_program->num_rows();

		$go_3 = FALSE;
		$col_indikator=1;
		if ($total_kegiatan > $max_col) {
			$total_kegiatan = $max_col;
			$go_3 = TRUE;
		}
?>
	<tr>
		<td style="display:none;" style="border-bottom: 0;" rowspan="<?php echo $total_kegiatan; ?>"><?php echo $row->tujuan; ?></td>
		<td style="display:none;" style="border-bottom: 0;" rowspan="<?php echo $total_row_indikator_sasaran; ?>"><?php echo $row->sasaran; ?></td>
		<td style="display:none;" style="border-bottom: 0;" rowspan="<?php echo $total_kegiatan; ?>">
			<?php
				$i=0;
				foreach ($indikator_sasaran as $row_sasaran) {
					$i++;
					echo $i .". ". $row_sasaran->indikator ."<BR>";
				}
			?>
		</td>
		<td bgcolor="#ffbb88" rowspan="<?php echo $total_temp; ?>"><?php echo $row->kd_urusan; ?></td>
		<td bgcolor="#ffbb88" rowspan="<?php echo $total_temp; ?>"><?php echo $row->kd_bidang; ?></td>
		<td bgcolor="#ffbb88" rowspan="<?php echo $total_temp; ?>"><?php echo $row->kd_program; ?></td>
		<td bgcolor="#ffbb88" rowspan="<?php echo $total_temp; ?>"><?php echo $row->kd_kegiatan; ?></td>
		<td bgcolor="#ffbb88" rowspan="<?php echo $total_temp; ?>"><?php echo $row->nama_prog_or_keg; ?></td>
		<td bgcolor="#ffbb88">
			<?php
				echo $temp[0]->indikator;
			?>
		</td>
		<td bgcolor="#ffbb88" align="center">
			<?php
				echo $temp[0]->kondisi_awal." ".$temp[0]->nama_value;
			?>
		</td>
		<td bgcolor="#ffbb88" align="center">
			<?php
				echo $temp[0]->target_1;
			?>
		</td>
		<td bgcolor="#ffbb88" align="right" rowspan="<?php echo $total_temp; ?>"><?php echo Formatting::currency($row->nominal_1_pro); ?></td>
		<td bgcolor="#ffbb88" align="center" rowspan="<?php echo $total_temp; ?>"><?php echo $row->lokasi_1; ?></td>
		<td bgcolor="#77ddff" align="center">
			<?php
				echo $temp[0]->target_2;
			?>
		</td>
		<td bgcolor="#77ddff" align="right" rowspan="<?php echo $total_temp; ?>"><?php echo Formatting::currency($row->nominal_2_pro); ?></td>
		<td bgcolor="#77ddff" align="center" rowspan="<?php echo $total_temp; ?>"><?php echo $row->lokasi_2; ?></td>
		<td bgcolor="#77ff88" align="center">
			<?php
				echo $temp[0]->target_3;
			?>
		</td>
		<td bgcolor="#77ff88" align="right" rowspan="<?php echo $total_temp; ?>"><?php echo Formatting::currency($row->nominal_3_pro); ?></td>
		<td bgcolor="#77ff88" align="center" rowspan="<?php echo $total_temp; ?>"><?php echo $row->lokasi_3; ?></td>
		<td bgcolor="#88ffcc" align="center">
			<?php
				echo $temp[0]->target_4;
			?>
		</td>
		<td bgcolor="#88ffcc" align="right" rowspan="<?php echo $total_temp; ?>"><?php echo Formatting::currency($row->nominal_4_pro); ?></td>
		<td bgcolor="#88ffcc" align="center" rowspan="<?php echo $total_temp; ?>"><?php echo $row->lokasi_4; ?></td>
		<td bgcolor="#EDFD6D" align="center">
			<?php
				echo $temp[0]->target_5;
			?>
		</td>
		<td bgcolor="#EDFD6D" align="right" rowspan="<?php echo $total_temp; ?>"><?php echo Formatting::currency($row->nominal_5_pro); ?></td>
		<td bgcolor="#EDFD6D" align="center" rowspan="<?php echo $total_temp; ?>"><?php echo $row->lokasi_5; ?></td>
		<td align="center">
			<?php
				echo $temp[0]->target_kondisi_akhir;
			?>
		</td>
		<td align="right" rowspan="<?php echo $total_temp; ?>"><?php echo Formatting::currency($total_nominal); ?></td>
		<td rowspan="<?php echo $total_temp; ?>"><?php echo $row->penanggung_jawab; ?></td>
		<td rowspan="<?php echo $total_temp; ?>"><?php echo $row->lokasi; ?></td>
	</tr>
<?php
		if ($total_temp > 1) {
			$col_indikator++;
			for ($i=1; $i < $total_temp; $i++) {
?>
		<tr>
			<td bgcolor="#ffbb88">
				<?php
					echo $temp[$i]->indikator;
				?>
			</td>
			<td bgcolor="#ffbb88" align="center">
				<?php
					echo $temp[$i]->kondisi_awal." ".$temp[$i]->nama_value;
				?>
			</td>
			<td bgcolor="#ffbb88" align="center">
				<?php
					echo $temp[$i]->target_1;
				?>
			</td>
			<td bgcolor="#77ddff" align="center">
				<?php
					echo $temp[$i]->target_2;
				?>
			</td>
			<td bgcolor="#77ff88" align="center">
				<?php
					echo $temp[$i]->target_3;
				?>
			</td>
			<td bgcolor="#88ffcc" align="center">
				<?php
					echo $temp[$i]->target_4;
				?>
			</td>
			<td bgcolor="#EDFD6D" align="center">
				<?php
					echo $temp[$i]->target_5;
				?>
			</td>
			<td align="center">
				<?php
					echo $temp[$i]->target_kondisi_akhir;
				?>
			</td>
		</tr>
<?php
			}
		}

		$i_kegiatan=0;
		foreach ($kegiatan as $row) {
			$i_kegiatan++;
			$col_indikator++;
			$indikator_kegiatan = $this->m_renstra_trx->get_indikator_prog_keg($row->id, FALSE, FALSE);
			$temp = $indikator_kegiatan->result();
			$total_temp = $indikator_kegiatan->num_rows();
			//print_r($total_temp);
			$total_nominal = $row->nominal_1+$row->nominal_2+$row->nominal_3+$row->nominal_4+$row->nominal_5;

			$go_3_keg = FALSE;
			$col_indikator_keg=1;
			$total_for_iteration = $total_temp;
			if ($total_temp > $max_col_keg) {
				//$total_temp = $max_col_keg;
				//$go_3_keg = TRUE;
			}
?>
			<tr>
<?php
			if ($go_3 && $i_kegiatan>=$max_col) {
?>
				<!-- <td style="display:none;" style="border-top: 0;border-bottom: 0;" ></td>
				<td style="display:none;" style="border-top: 0;border-bottom: 0;" ></td>
				<td style="display:none;" style="border-top: 0;border-bottom: 0;" ></td> -->
<?php
			}
?>
				<td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>"><?php echo $row->kd_urusan; ?></td>
				<td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>"><?php echo $row->kd_bidang; ?></td>
				<td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>"><?php echo $row->kd_program; ?></td>
				<td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>"><?php echo $row->kd_kegiatan; ?></td>
				<td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>"><?php echo $row->nama_prog_or_keg; ?></td>
				<td>
					<?php
						echo $temp[0]->indikator;
					?>
				</td>
				<td align="center">
					<?php
						echo $temp[0]->kondisi_awal." ".$temp[0]->nama_value;
					?>
				</td>
				<td align="center" bgcolor="#ffbb88">
					<?php
						echo $temp[0]->target_1;
					?>
				</td>
				<td bgcolor="#ffbb88" style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>" align="right"><?php echo Formatting::currency($row->nominal_1); ?></td>
				<td bgcolor="#ffbb88" style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>" align="center"><?php echo $row->lokasi_1; ?></td>
				<td bgcolor="#77ddff" align="center">
					<?php
						echo $temp[0]->target_2;
					?>
				</td>
				<td bgcolor="#77ddff" style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>" align="right"><?php echo Formatting::currency($row->nominal_2); ?></td>
				<td bgcolor="#77ddff" style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>" align="center"><?php echo $row->lokasi_2; ?></td>
				<td bgcolor="#77ff88" align="center">
					<?php
						echo $temp[0]->target_3;
					?>
				</td>
				<td bgcolor="#77ff88" style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>" align="right"><?php echo Formatting::currency($row->nominal_3); ?></td>
				<td bgcolor="#77ff88" style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>" align="center"><?php echo $row->lokasi_3; ?></td>
				<td bgcolor="#88ffcc" align="center">
					<?php
						echo $temp[0]->target_4;
					?>
				</td>
				<td bgcolor="#88ffcc" style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>" align="right"><?php echo Formatting::currency($row->nominal_4); ?></td>
				<td bgcolor="#88ffcc" style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>" align="center"><?php echo $row->lokasi_4; ?></td>
				<td bgcolor="#EDFD6D" align="center">
					<?php
						echo $temp[0]->target_5;
					?>
				</td>
				<td bgcolor="#EDFD6D" style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>" align="right"><?php echo Formatting::currency($row->nominal_5); ?></td>
				<td bgcolor="#EDFD6D" style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>" align="center"><?php echo $row->lokasi_5; ?></td>
				<td align="center">
					<?php
						echo $temp[0]->target_kondisi_akhir;
					?>
				</td>
				<td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>" align="right"><?php echo Formatting::currency($total_nominal); ?></td>
				<td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>"><?php echo $row->penanggung_jawab; ?></td>
				<td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>"><?php echo $row->lokasi; ?></td>
			</tr>
<?php
			if ($total_for_iteration > 1) {
				for ($i=1; $i < $total_for_iteration; $i++) {
					$col_indikator++;
					$col_indikator_keg++;
?>
			<tr>
<?php
				if ($col_indikator > $max_col) {
?>
				<!-- <td style="border-top: 0;border-bottom: 0;" ></td>
				<td style="border-top: 0;border-bottom: 0;" ></td>
				<td style="border-top: 0;border-bottom: 0;" ></td> -->
<?php
				}

				if ($go_3_keg && $col_indikator_keg>$max_col_keg) {
?>
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
				<td align="center">
					<?php
						echo $temp[$i]->kondisi_awal." ".$temp[$i]->nama_value;
					?>
				</td>
				<td bgcolor="#ffbb88" align="center">
					<?php
						echo $temp[$i]->target_1;
					?>
				</td>
<?php
				if ($go_3_keg && $col_indikator_keg>$max_col_keg) {
?>
				<td style="border-top: 0;border-bottom: 0;" ></td>
				<td style="border-top: 0;border-bottom: 0;" ></td>
<?php
				}
?>
				<td bgcolor="#77ddff" align="center">
					<?php
						echo $temp[$i]->target_2;
					?>
				</td>
<?php
				if ($go_3_keg && $col_indikator_keg>$max_col_keg) {
?>
				<td style="border-top: 0;border-bottom: 0;" ></td>
				<td style="border-top: 0;border-bottom: 0;" ></td>
<?php
				}
?>
				<td bgcolor="#77ff88" align="center">
					<?php
						echo $temp[$i]->target_3;
					?>
				</td>
<?php
				if ($go_3_keg && $col_indikator_keg>$max_col_keg) {
?>
				<td style="border-top: 0;border-bottom: 0;" ></td>
				<td style="border-top: 0;border-bottom: 0;" ></td>
<?php
				}
?>
				<td bgcolor="#88ffcc" align="center">
					<?php
						echo $temp[$i]->target_4;
					?>
				</td>
<?php
				if ($go_3_keg && $col_indikator_keg>$max_col_keg) {
?>
				<td style="border-top: 0;border-bottom: 0;" ></td>
				<td style="border-top: 0;border-bottom: 0;" ></td>
<?php
				}
?>
				<td bgcolor="#EDFD6D" align="center">
					<?php
						echo $temp[$i]->target_5;
					?>
				</td>
<?php
				if ($go_3_keg && $col_indikator_keg>$max_col_keg) {
?>
				<td style="border-top: 0;border-bottom: 0;" ></td>
				<td style="border-top: 0;border-bottom: 0;" ></td>
<?php
				}
?>
				<td align="center">
					<?php
						echo $temp[$i]->target_kondisi_akhir;
					?>
				</td>
<?php
				if ($go_3_keg && $col_indikator_keg>$max_col_keg) {
?>
				<td style="border-top: 0;border-bottom: 0;" ></td>
				<td style="border-top: 0;border-bottom: 0;" ></td>
				<td style="border-top: 0;border-bottom: 0;" ></td>
<?php
				}
?>
			</tr>
<?php
				}
			}
		}
	}
?>
</tbody>
<tfoot>
	<tr>
	<td colspan="7"><strong>TOTAL ANGGARAN</strong></td>
		<td bgcolor="#ffbb88">&nbsp;</td>
		<td bgcolor="#ffbb88"><strong><?php echo Formatting::currency($TotalNominal1);?></strong></td>
		<td bgcolor="#ffbb88">&nbsp;</td>
		<td bgcolor="#77ddff">&nbsp;</td>
		<td bgcolor="#77ddff"><strong><?php echo Formatting::currency($TotalNominal2);?></strong></td>
		<td bgcolor="#77ddff">&nbsp;</td>
		<td bgcolor="#77ff88">&nbsp;</td>
		<td bgcolor="#77ff88"><strong><?php echo Formatting::currency($TotalNominal3);?></strong></td>
		<td bgcolor="#77ff88">&nbsp;</td>
		<td bgcolor="#88ffcc">&nbsp;</td>
		<td bgcolor="#88ffcc"><strong><?php echo Formatting::currency($TotalNominal4);?></strong></td>
		<td bgcolor="#88ffcc">&nbsp;</td>
		<td bgcolor="#EDFD6D">&nbsp;</td>
		<td bgcolor="#EDFD6D"><strong><?php echo Formatting::currency($TotalNominal5);?></strong></td>
		<td bgcolor="#EDFD6D">&nbsp;</td>
		<td >&nbsp;</td>
		<td ><strong><?php echo Formatting::currency($SumTotalNominal);?></strong></td>
		<td >&nbsp;</td>
		<td >&nbsp;</td>
	</tr>

</tfoot>