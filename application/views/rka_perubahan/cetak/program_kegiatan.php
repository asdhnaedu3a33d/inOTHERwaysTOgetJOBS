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
		<th rowspan="2">Catatan</th>
		<th colspan="2">Perkiraan Maju Rencana Tahun <?php echo $tahun_rka+1;?></th>
	</tr>
	<tr>				
		<th >Lokasi</th>
		<th >Target Capaian Kinerja</th>
		<th >Kebutuhan Dana/Pagu Indikatif</th>
		<th >Target Capaian Kinerja</th>
		<th >Kebutuhan Dana/Pagu Indikatif</th>		
	</tr>
</thead>
<tbody>
<?php	
	foreach ($program as $row) {
		$result = $this->m_rka_perubahan->get_kegiatan_skpd_4_cetak($row->id);		
		$kegiatan = $result->result();
		$indikator_program = $this->m_rka_perubahan->get_indikator_prog_keg($row->id, FALSE, TRUE);
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
	<tr>
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
		<td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>"><?php echo $row->lokasi;?></td>
		<td align="center">
			<?php echo $temp[0]->target." ".$temp[0]->satuan_target;?>
		</td>
		<td style="border-bottom: 0;" align="right" rowspan="<?php echo $total_temp; ?>"><?php echo Formatting::currency($row->sum_nominal);?></td>
		<td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>"><?php echo $row->catatan;?></td>
		<td align="center">
			<?php echo $temp[0]->target_thndpn." ".$temp[0]->satuan_target_thndpn;?>
		</td>
		<td style="border-bottom: 0;" align="right" rowspan="<?php echo $total_temp; ?>"><?php echo Formatting::currency($row->sum_nominal_thndpn);?></td>
	</tr>
<?php
		if ($total_for_iteration > 1) {			
			for ($i=1; $i < $total_for_iteration; $i++) { 
				$col_indikator++;
?>
		<tr>
<?php
				if ($go_3_keg && $col_indikator > $max_col_keg) {
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
<?php
				if ($go_3_keg && $col_indikator > $max_col_keg) {
?>
			<td style="border-top: 0;border-bottom: 0;" ></td>
<?php
				}
?>
			<td align="center">
				<?php
					echo $temp[$i]->target." ".$temp[$i]->satuan_target;
				?>
			</td>
<?php
				if ($go_3_keg && $col_indikator > $max_col_keg) {
?>
			<td style="border-top: 0;border-bottom: 0;" ></td>
			<td style="border-top: 0;border-bottom: 0;" ></td>
<?php
				}
?>
			<td align="center">
				<?php
					echo $temp[$i]->target_thndpn." ".$temp[$i]->satuan_target_thndpn;
				?>
			</td>
<?php
				if ($go_3_keg && $col_indikator > $max_col_keg) {
?>
			<td style="border-top: 0;border-bottom: 0;" ></td>
<?php
					}
?>
		</tr>
<?php
		 	}
		 }
		
		foreach ($kegiatan as $row) {						
			$indikator_kegiatan = $this->m_rka_perubahan->get_indikator_prog_keg($row->id, FALSE, TRUE);
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
			<td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>"><?php echo $row->lokasi;?></td>
			<td align="center">
			<?php
				echo $temp[0]->target." ".$temp[0]->satuan_target;
			?>
			</td>
			<td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>" align="right" ><?php echo Formatting::currency($row->nominal);?></td>
			<td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>"><?php echo $row->catatan;?></td>
			<td align="center">
			<?php
				echo $temp[0]->target_thndpn." ".$temp[0]->satuan_target_thndpn;
			?>
			</td>
			<td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>" align="right" ><?php echo Formatting::currency($row->nominal_thndpn);?></td>
		</tr>
<?php
			if ($total_for_iteration > 1) {
				for ($i=1; $i < $total_for_iteration; $i++) { 					
					$col_indikator_keg++;
?>
		<tr>
<?php
					if ($go_3_keg && $col_indikator_keg > $max_col_keg) {
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
<?php
					if ($go_3_keg && $col_indikator_keg > $max_col_keg) {
?>
			<td style="border-top: 0;border-bottom: 0;" ></td>
<?php
					}
?>
			<td align="center">
				<?php
					echo $temp[$i]->target." ".$temp[$i]->satuan_target;
				?>
			</td>
<?php
					if ($go_3_keg && $col_indikator_keg > $max_col_keg) {
?>
			<td style="border-top: 0;border-bottom: 0;" ></td>
			<td style="border-top: 0;border-bottom: 0;" ></td>
<?php
					}
?>
			<td align="center">
				<?php
					echo $temp[$i]->target_thndpn." ".$temp[$i]->satuan_target_thndpn;
				?>
			</td>
<?php
					if ($go_3_keg && $col_indikator_keg > $max_col_keg) {
?>
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