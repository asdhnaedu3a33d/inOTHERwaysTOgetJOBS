<?php
	$max_col=1;
	$max_col_keg=1;
?>
<thead>
	<tr>
		<th rowspan="3">Tujuan</th>
		<th rowspan="3">Sasaran</th>
		<th rowspan="3">Indikator Sasaran</th>
		<th rowspan="3" colspan="4">Kode</th>
		<th rowspan="3">Program dan Kegiatan</th>
		<th style="display:none;" rowspan="3">Indikator Kinerja Program (outcome) dan Kegiatan (output)</th>		
		<th style="display:none;" rowspan="3">Data Capaian pada Tahun Awal Perencanaan</th>
		<th style="display:none;" colspan="15">Tahun Kinerja Program dan Kerangka Pendanaan</th>		
		<th style="display:none;" rowspan="2" colspan="2">Kondisi Kinerja pada akhir periode</th>		
		<th style="display:none;" rowspan="3">Unit Kerja SKPD Penanggung Jawab</th>
		<th style="display:none;" rowspan="3">Lokasi</th>
	</tr>
	<tr>				
		<th style="display:none;" colspan="3">Tahun I</th>
		<th style="display:none;" colspan="3">Tahun II</th>
		<th style="display:none;" colspan="3">Tahun III</th>
		<th style="display:none;" colspan="3">Tahun IV</th>
		<th style="display:none;" colspan="3">Tahun V</th>		
	</tr>
	<tr>
		<th style="display:none;">Target</th>
		<th style="display:none;">Rp</th>
		<th style="display:none;">Lokasi</th>
		<th style="display:none;">Target</th>
		<th style="display:none;">Rp</th>
		<th style="display:none;">Lokasi</th>
		<th style="display:none;">Target</th>
		<th style="display:none;">Rp</th>
		<th style="display:none;">Lokasi</th>
		<th style="display:none;">Target</th>
		<th style="display:none;">Rp</th>
		<th style="display:none;">Lokasi</th>
		<th style="display:none;">Target</th>
		<th style="display:none;">Rp</th>
		<th style="display:none;">Lokasi</th>
		<th style="display:none;">Target</th>
		<th style="display:none;">Rp</th>		
	</tr>
</thead>
<tbody>
<?php	
	foreach ($program as $row) {
		$result = $this->m_renstra_trx->get_kegiatan_skpd_4_cetak($row->id);
		$total_kegiatan = $this->m_renstra_trx->get_total_kegiatan_dan_indikator($row->id)->total;
		$kegiatan = $result->result();

		$total_nominal = $row->nominal_1_pro+$row->nominal_2_pro+$row->nominal_3_pro+$row->nominal_4_pro+$row->nominal_5_pro;

		$indikator_sasaran = $this->m_renstra_trx->get_indikator_sasaran($row->id_sasaran);
		$indikator_program = $this->m_renstra_trx->get_indikator_prog_keg($row->id, FALSE, TRUE);
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
		<td style="border-bottom: 0;" rowspan="<?php echo $total_kegiatan; ?>"><?php echo $row->tujuan; ?></td>
		<td style="border-bottom: 0;" rowspan="<?php echo $total_kegiatan; ?>"><?php echo $row->sasaran; ?></td>
		<td style="border-bottom: 0;" rowspan="<?php echo $total_kegiatan; ?>">
			<?php
				$i=0;
				foreach ($indikator_sasaran as $row_sasaran) {
					$i++;
					echo $i .". ". $row_sasaran->indikator ."<BR>";
				}
			?>
		</td>
		<td rowspan="<?php echo $total_temp; ?>"><?php echo $row->kd_urusan; ?></td>
		<td rowspan="<?php echo $total_temp; ?>"><?php echo $row->kd_bidang; ?></td>
		<td rowspan="<?php echo $total_temp; ?>"><?php echo $row->kd_program; ?></td>
		<td rowspan="<?php echo $total_temp; ?>"><?php echo $row->kd_kegiatan; ?></td>
		<td rowspan="<?php echo $total_temp; ?>"><?php echo $row->nama_prog_or_keg; ?></td>
		<td style="display:none;">
			<?php
				echo $temp[0]->indikator;
			?>
		</td>		
		<td align="center" style="display:none;">
			<?php
				echo $temp[0]->kondisi_awal." ".$temp[0]->nama_value;
			?>
		</td>
		<td align="center" style="display:none;">
			<?php
				echo $temp[0]->target_1." ".$temp[0]->nama_value;
			?>
		</td>
		<td style="display:none;" align="right" rowspan="<?php echo $total_temp; ?>"><?php echo Formatting::currency($row->nominal_1_pro); ?></td>
		<td style="display:none;" align="center" rowspan="<?php echo $total_temp; ?>"><?php echo $row->lokasi_1; ?></td>
		<td style="display:none;" align="center">
			<?php
				echo $temp[0]->target_2." ".$temp[0]->nama_value;
			?>
		</td>
		<td style="display:none;" align="right" rowspan="<?php echo $total_temp; ?>"><?php echo Formatting::currency($row->nominal_2_pro); ?></td>
		<td style="display:none;" align="center" rowspan="<?php echo $total_temp; ?>"><?php echo $row->lokasi_2; ?></td>
		<td style="display:none;" align="center">
			<?php
				echo $temp[0]->target_3." ".$temp[0]->nama_value;
			?>
		</td>
		<td style="display:none;" align="right" rowspan="<?php echo $total_temp; ?>"><?php echo Formatting::currency($row->nominal_3_pro); ?></td>
		<td style="display:none;" align="center" rowspan="<?php echo $total_temp; ?>"><?php echo $row->lokasi_3; ?></td>
		<td style="display:none;" align="center">
			<?php
				echo $temp[0]->target_4." ".$temp[0]->nama_value;
			?>
		</td>
		<td style="display:none;" align="right" rowspan="<?php echo $total_temp; ?>"><?php echo Formatting::currency($row->nominal_4_pro); ?></td>
		<td style="display:none;" align="center" rowspan="<?php echo $total_temp; ?>"><?php echo $row->lokasi_4; ?></td>
		<td style="display:none;" align="center">
			<?php
				echo $temp[0]->target_5." ".$temp[0]->nama_value;
			?>
		</td>
		<td style="display:none;" align="right" rowspan="<?php echo $total_temp; ?>"><?php echo Formatting::currency($row->nominal_5_pro); ?></td>
		<td style="display:none;" align="center" rowspan="<?php echo $total_temp; ?>"><?php echo $row->lokasi_5; ?></td>
		<td style="display:none;" align="center">
			<?php
				echo $temp[0]->target_kondisi_akhir." ".$temp[0]->nama_value;
			?>
		</td>
		<td style="display:none;" align="right" rowspan="<?php echo $total_temp; ?>"><?php echo Formatting::currency($total_nominal); ?></td>
		<td style="display:none;" rowspan="<?php echo $total_temp; ?>"><?php echo $row->penanggung_jawab; ?></td>
		<td style="display:none;" rowspan="<?php echo $total_temp; ?>"><?php echo $row->lokasi; ?></td>
	</tr>
<?php
		if ($total_temp > 1) {
			$col_indikator++;
			for ($i=1; $i < $total_temp; $i++) { 
?>
		<tr>
			<td style="display:none;">
				<?php
					echo $temp[$i]->indikator;
				?>
			</td>				
			<td style="display:none;" align="center">
				<?php
					echo $temp[$i]->kondisi_awal." ".$temp[$i]->nama_value;
				?>
			</td>
			<td style="display:none;" align="center">
				<?php
					echo $temp[$i]->target_1." ".$temp[$i]->nama_value;
				?>
			</td>
			<td style="display:none;" align="center">
				<?php
					echo $temp[$i]->target_2." ".$temp[$i]->nama_value;
				?>
			</td>
			<td style="display:none;" align="center">
				<?php
					echo $temp[$i]->target_3." ".$temp[$i]->nama_value;
				?>
			</td>
			<td style="display:none;" align="center">
				<?php
					echo $temp[$i]->target_4." ".$temp[$i]->nama_value;
				?>
			</td>
			<td style="display:none;" align="center">
				<?php
					echo $temp[$i]->target_5." ".$temp[$i]->nama_value;
				?>
			</td>
			<td style="display:none;" align="center">
				<?php
					echo $temp[$i]->target_kondisi_akhir." ".$temp[$i]->nama_value;
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
			$indikator_kegiatan = $this->m_renstra_trx->get_indikator_prog_keg($row->id, FALSE, TRUE);
			$temp = $indikator_kegiatan->result();			
			$total_temp = $indikator_kegiatan->num_rows();

			$total_nominal = $row->nominal_1+$row->nominal_2+$row->nominal_3+$row->nominal_4+$row->nominal_5;

			$go_3_keg = FALSE;
			$col_indikator_keg=1;
			$total_for_iteration = $total_temp;
			if ($total_temp > $max_col_keg) {
				$total_temp = $max_col_keg;
				$go_3_keg = TRUE;
			}
?>
			<tr>
<?php
			if ($go_3 && $i_kegiatan>=$max_col) {
?>
				<td style="border-top: 0;border-bottom: 0;" ></td>
				<td style="border-top: 0;border-bottom: 0;" ></td>
				<td style="border-top: 0;border-bottom: 0;" ></td>
<?php
			}
?>
				<td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>"><?php echo $row->kd_urusan; ?></td>
				<td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>"><?php echo $row->kd_bidang; ?></td>
				<td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>"><?php echo $row->kd_program; ?></td>
				<td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>"><?php echo $row->kd_kegiatan; ?></td>
				<td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>"><?php echo $row->nama_prog_or_keg; ?></td>
				<td style="display:none;" >
					<?php
						echo $temp[0]->indikator;
					?>
				</td  >				
				<td style="display:none;" align="center">
					<?php
						echo $temp[0]->kondisi_awal." ".$temp[0]->nama_value;
					?>
				</td>
				<td style="display:none;" align="center">
					<?php
						echo $temp[0]->target_1." ".$temp[0]->nama_value;
					?>
				</td>
				<td style="display:none;" style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>" align="right"><?php echo Formatting::currency($row->nominal_1); ?></td>
				<td style="display:none;" style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>" align="center"><?php echo $row->lokasi_1; ?></td>
				<td style="display:none;" align="center">
					<?php
						echo $temp[0]->target_2." ".$temp[0]->nama_value;
					?>
				</td>
				<td style="display:none;" style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>" align="right"><?php echo Formatting::currency($row->nominal_2); ?></td>
				<td style="display:none;" style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>" align="center"><?php echo $row->lokasi_2; ?></td>
				<td style="display:none;" align="center">
					<?php
						echo $temp[0]->target_3." ".$temp[0]->nama_value;
					?>
				</td>
				<td style="display:none;" style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>" align="right"><?php echo Formatting::currency($row->nominal_3); ?></td>
				<td style="display:none;" style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>" align="center"><?php echo $row->lokasi_3; ?></td>
				<td style="display:none;" align="center">
					<?php
						echo $temp[0]->target_4." ".$temp[0]->nama_value;
					?>
				</td>
				<td style="display:none;" style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>" align="right"><?php echo Formatting::currency($row->nominal_4); ?></td>
				<td style="display:none;" style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>" align="center"><?php echo $row->lokasi_4; ?></td>
				<td style="display:none;" align="center">
					<?php
						echo $temp[0]->target_5." ".$temp[0]->nama_value;
					?>
				</td>
				<td style="display:none;" style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>" align="right"><?php echo Formatting::currency($row->nominal_5); ?></td>
				<td style="display:none;" style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>" align="center"><?php echo $row->lokasi_5; ?></td>
				<td style="display:none;" align="center">
					<?php
						echo $temp[0]->target_kondisi_akhir." ".$temp[0]->nama_value;
					?>
				</td>
				<td style="display:none;" style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>" align="right"><?php echo Formatting::currency($total_nominal); ?></td>
				<td style="display:none;" style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>"><?php echo $row->penanggung_jawab; ?></td>
				<td style="display:none;" style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>"><?php echo $row->lokasi; ?></td>
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
				<td style="border-top: 0;border-bottom: 0;" ></td>
				<td style="border-top: 0;border-bottom: 0;" ></td>
				<td style="border-top: 0;border-bottom: 0;" ></td>
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
				<td style="display:none;" >
					<?php
						echo $temp[$i]->indikator;
					?>
				</td>				
				<td style="display:none;"  align="center">
					<?php
						echo $temp[$i]->kondisi_awal." ".$temp[$i]->nama_value;
					?>
				</td>
				<td style="display:none;" align="center">
					<?php
						echo $temp[$i]->target_1." ".$temp[$i]->nama_value;
					?>
				</td>
<?php
				if ($go_3_keg && $col_indikator_keg>$max_col_keg) {
?>
				<td style="display:none;" style="border-top: 0;border-bottom: 0;" ></td>
				<td style="display:none;" style="border-top: 0;border-bottom: 0;" ></td>
<?php
				}
?>
				<td style="display:none;" align="center">
					<?php
						echo $temp[$i]->target_2." ".$temp[$i]->nama_value;
					?>
				</td>
<?php
				if ($go_3_keg && $col_indikator_keg>$max_col_keg) {
?>
				<td style="display:none;"  style="border-top: 0;border-bottom: 0;" ></td>
				<td style="display:none;"  style="border-top: 0;border-bottom: 0;" ></td>
<?php
				}
?>
				<td style="display:none;" align="center">
					<?php
						echo $temp[$i]->target_3." ".$temp[$i]->nama_value;
					?>
				</td>
<?php
				if ($go_3_keg && $col_indikator_keg>$max_col_keg) {
?>
				<td style="display:none;"  style="border-top: 0;border-bottom: 0;" ></td>
				<td style="display:none;" style="border-top: 0;border-bottom: 0;" ></td>
<?php
				}
?>
				<td style="display:none;" align="center">
					<?php
						echo $temp[$i]->target_4." ".$temp[$i]->nama_value;
					?>
				</td>
<?php
				if ($go_3_keg && $col_indikator_keg>$max_col_keg) {
?>
				<td style="display:none;"  style="border-top: 0;border-bottom: 0;" ></td>
				<td style="display:none;" style="border-top: 0;border-bottom: 0;" ></td>
<?php
				}
?>
				<td style="display:none;" align="center">
					<?php
						echo $temp[$i]->target_5." ".$temp[$i]->nama_value;
					?>
				</td>
<?php
				if ($go_3_keg && $col_indikator_keg>$max_col_keg) {
?>
				<td style="display:none;" style="border-top: 0;border-bottom: 0;" ></td>
				<td style="display:none;" style="border-top: 0;border-bottom: 0;" ></td>
<?php
				}
?>
				<td style="display:none;" align="center">
					<?php
						echo $temp[$i]->target_kondisi_akhir." ".$temp[$i]->nama_value;
					?>
				</td>
<?php
				if ($go_3_keg && $col_indikator_keg>$max_col_keg) {
?>
				<td style="display:none;" style="border-top: 0;border-bottom: 0;" ></td>
				<td style="display:none;" style="border-top: 0;border-bottom: 0;" ></td>
				<td style="display:none;" style="border-top: 0;border-bottom: 0;" ></td>
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