<?php
	$tahun_sekarang=$this->session->userdata('t_anggaran_aktif');
	$max_col_keg=1;
?>
<thead>
	<tr>
		<th rowspan="2" colspan="4">KODE</th>
		<th rowspan="2">PROGRAM DAN KEGIATAN</th>
		<th colspan="3">ANGGARAN</th>
		<th colspan="4">KELOMPOK INDIKATOR KINERJA PROGRAM (OUTCOME) / INDIKATOR KINERJA KEGIATAN (OUTPUT)</th>
		<th rowspan="2">KET.</th>
	</tr>
	<tr>
		<th >RENCANA</th>
		<th >REALISASI</th>
		<th >CAPAIAN IK (%)</th>
		<th >INDIKATOR / SATUAN</th>
		<th >RENCANA</th>
		<th >REALISASI</th>
		<th >CAPAIAN IK (%)</th>
	</tr>
</thead>
<tbody>
	<?php 
		foreach ($program as $row) {
			$result = $this->m_cik->get_kegiatan_rekap_cik_4_cetak($row->id,$tahun_sekarang);
			$kegiatan = $result->result();
			$indikator_program = $this->m_cik->get_indikator_prog_keg_rekap($row->id, FALSE, TRUE);
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
	<tr>
		<td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>"><?php echo $row->kd_urusan; ?></td>
		<td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>"><?php echo $row->kd_bidang; ?></td>
		<td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>"><?php echo $row->kd_program; ?></td>
		<td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>"><?php echo $row->kd_kegiatan; ?></td>
		<td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>"><?php echo $row->nama_prog_or_keg; ?></td>
		<td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>">ANGGARAN RENCANA</td>
		<td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>">ANGGARAN REALISASI</td>
		<td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>">%CIK</td>
		<td>
			<?php
				echo $temp[0]->indikator;
			?>
		</td>
		<td>
			<?php
				echo $temp[0]->target." ".$temp[0]->satuan_target;
			?>
		</td>
		<td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>">REALISASI</td>
		<td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>">$CIK</td>
		<td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>">KET</td>
	</tr>
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
				<td>
					<?php
						echo $temp[0]->target." ".$temp[0]->satuan_target;
					?>
				</td>
				<?php
					if ($go_2_keg && $col_indikator > $max_col_keg) {
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
		foreach ($kegiatan as $row) {						
				$indikator_kegiatan = $this->m_cik->get_indikator_prog_keg_rekap($row->id, FALSE, TRUE);
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
			<td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>">ANGGARAN RENCANA</td>
			<td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>">ANGGARAN REALISASI</td>
			<td style="border-bottom: 0;" rowspan="<?php echo $total_temp;?>">%CIK</td>
			<td>
				<?php
					echo $temp[0]->indikator;
				?>
			</td>
			<td>
				<?php
					echo $temp[0]->target." ".$temp[0]->satuan_target;
				?>
			</td>
			<td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>">REALISASI</td>
			<td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>">$CIK</td>
			<td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>">KET</td>
		</tr>
		<?php
			if ($total_for_iteration > 1) {
			for ($i=1; $i < $total_for_iteration; $i++) { 					
				$col_indikator_keg++;
		?>
		<tr>
				<td style="border-top: 0;border-bottom: 0;" ></td>
				<td style="border-top: 0;border-bottom: 0;" ></td>
				<td style="border-top: 0;border-bottom: 0;" ></td>
				<td style="border-top: 0;border-bottom: 0;" ></td>
				<td style="border-top: 0;border-bottom: 0;" ></td>
				<td style="border-top: 0;border-bottom: 0;" ></td>
				<td style="border-top: 0;border-bottom: 0;" ></td>
				<td style="border-top: 0;border-bottom: 0;" ></td>
				<td>
					<?php
						echo $temp[$i]->indikator;
					?>
				</td>
				<td>
					<?php
						echo $temp[$i]->target." ".$temp[$i]->satuan_target;
					?>
				</td>
				<td style="border-top: 0;border-bottom: 0;" ></td>
				<td style="border-top: 0;border-bottom: 0;" ></td>
				<td style="border-top: 0;border-bottom: 0;" ></td>
			</tr>
		<?php
			}
		}
	}
}
		?>
</tbody>