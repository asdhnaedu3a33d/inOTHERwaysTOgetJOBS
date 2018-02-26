<?php
	$ta=$this->session->userdata('t_anggaran_aktif');
	$tahun_sekarang=$this->session->userdata('t_anggaran_aktif'); 
	$nama_skpd = $this->session->userdata('nama_skpd');
	$max_col_keg=1;
?>
<script type="text/javascript">
	
</script>
<article class="module width_full" style="width: 130%; margin-left: -15%;">
	<header>
		<h3> EVALUASI HASIL RENJA SKPD </h3>
	</header>
	<div class="module_content"; style="overflow:auto">
		<table id="evaluasi_table" class="table-common tablesorter" style="width:100%">
		<thead>
				<tr>
					<th rowspan="3" colspan="4">KODE</th>
					<th rowspan="3">Urusan/Bidang Urusan Pemerintahan Daerah dan Program / Kegiatan</th>
					<th rowspan="3">Indikator Kinerja Program (Outcome) / Kegiatan(Output)</th>
					<th colspan="2" rowspan="2">Target Renstra SKPD pada Tahun <?php echo $ta?> (Akhir Periode Renstra SKPD)</th>
					<th colspan="2" rowspan="2">Realisasi Capaian Kinerja Renstra SKPD s.d. Renja SKPD Tahun Lalu (<?php echo $ta-1?>)</th>
					<th colspan="2" rowspan="2">Target Kinerja dan Anggaran Renja SKPD Tahun berjalan yang dievaluasi</th>
					<th colspan="8">Realisasi Kinerja Pada Triwulan</th>
					<th colspan="2" rowspan="2">Realisasi Capaian Kinerja dan Anggaran Renja SKPD yang Dievaluasi (<?php echo $ta?>)</th>
					<th colspan="2" rowspan="2">Tingkat Capaian Kinerja dan Realisasi Anggaran Renja SKPD Tahun <?php echo $ta?></th>
					<th colspan="2" rowspan="2">Realisasi Kinerja dan Anggaran Renstra SKDP s/d tahun <?php echo $ta?> (akhir Tahun Pelaksanaan Renja SKPD Tahun <?php echo $ta?>)</th>
					<th colspan="2" rowspan="2">Tingkat Capaian Kinerja dan Realisasi Anggaran Renstra SKPD s/d Tahun <?php echo $ta?> (%)</th>
					<th rowspan="3">Unit SKPD Penanggungjawab</th>
					<th rowspan="3">Keterangan</th>
					<th rowspan="3" class="no-sort">Action</th>
				</tr>
				<tr>
					<th colspan="2"> I </th>
					<th colspan="2"> II </th>
					<th colspan="2"> III </th>
					<th colspan="2"> IV </th>
				</tr>
				<tr>
					<th> K </th>
					<th> Rp </th>
					<th> K </th>
					<th> Rp </th>
					<th> K </th>
					<th> Rp </th>
					<th> K </th>
					<th> Rp </th>
					<th> K </th>
					<th> Rp </th>
					<th> K </th>
					<th> Rp </th>
					<th> K </th>
					<th> Rp </th>
					<th> K </th>
					<th> Rp </th>
					<th> K </th>
					<th> Rp </th>
					<th> K </th>
					<th> Rp </th>
					<th> K </th>
					<th> Rp </th>
				</tr>
			</thead>
			<tbody>			
				<?php
					foreach ($program as $row) {
						$result = $this->m_kendali->get_kegiatan_rka_4_cetak($row->id,$tahun_sekarang);
						$kegiatan = $result->result();
						$get_id_renja = $this->m_kendali->get_id_renja($row->id_skpd, $row->tahun, $row->kd_urusan,$row->kd_bidang, $row->kd_program);
						$indikator_program_renja = $this->m_kendali->get_indikator_renja($get_id_renja);
						$temp1 = $indikator_program_renja->result();
						$total_temp = $indikator_program_renja->num_rows();
						
						$col_indikator=1;
						$go_2_keg = FALSE;
						$total_for_iteration = $total_temp;
						if($total_temp > $max_col_keg){
							$total_temp = $max_col_keg;
							$go_2_keg = TRUE;
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
									echo $temp1[0]->indikator;
								?>
							</td>
							<td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>">
								<?php echo $temp1[0]->target." ".$temp1[0]->satuan_target;?>
							</td>
							<td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>">
								<?php echo Formatting::currency($row->sum_nomrenja);?>
							</td>
							<td align="center">
								<?php echo $temp1[0]->target." ".$temp1[0]->satuan_target;?>
							</td>
							<td style="border-bottom: 0;" align="right" rowspan="<?php echo $total_temp; ?>"><?php echo Formatting::currency($row->sum_nomrenja);?></td>
							<td align="center">
								<?php echo $temp1[0]->target_thndpn." ".$temp1[0]->satuan_target;?>
							</td>
							<td style="border-bottom: 0;" align="right" rowspan="<?php echo $total_temp; ?>"><?php echo Formatting::currency($row->sum_nomrenja_thndpn);?></td>
							<td rowspan="<?php echo $total_temp; ?>" style="border-bottom: 0;">&nbsp;</td>
							<td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>">-</td>
							<td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>">-</td>
							<td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>">-</td>
	                        <td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>">
	                        <td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>">
	                        <td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>">
	                        <td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>">
	                        <td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>">
	                        <td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>">
	                        <td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>">
	                        <td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>">
	                        <td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>">
	                        <td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>">
	                        <td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>">
	                        <td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>">
	                        <td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>">
	                        <td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>">
	                        <td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>">
	                        <a href="javascript:void(0)" idP="<?php echo $row->id; ?>" class="icon-pencil edit-program" title="Kendali Program"/>
	                        </td>
						</tr>

						<?php
						foreach ($kegiatan as $row) {
							$get_id_renja = $this->m_kendali->get_id_renja($row->id_skpd, $row->tahun, $row->kd_urusan,$row->kd_bidang, $row->kd_program);
							$indikator_program_renja = $this->m_kendali->get_indikator_renja($get_id_renja);
							$temp1 = $indikator_program_renja->result();
							$total_temp = $indikator_program_renja->num_rows();
							
							$col_indikator=1;
							$go_2_keg = FALSE;
							$total_for_iteration = $total_temp;
							if($total_temp > $max_col_keg){
								$total_temp = $max_col_keg;
								$go_2_keg = TRUE;
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
									echo $temp1[0]->indikator;
								?>
							</td>
							<td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>">
								<?php echo $temp1[0]->target." ".$temp1[0]->satuan_target;?>
							</td>
							<td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>">
								-
							</td>
							<td align="center">
								<?php echo $temp1[0]->target." ".$temp1[0]->satuan_target;?>
							</td>
							<td style="border-bottom: 0;" align="right" rowspan="<?php echo $total_temp; ?>">
								-
							</td>
							<td align="center">
								<?php echo $temp1[0]->target_thndpn." ".$temp1[0]->satuan_target;?>
							</td>
							<td style="border-bottom: 0;" align="right" rowspan="<?php echo $total_temp; ?>">
								-
							</td>
							<td rowspan="<?php echo $total_temp; ?>" style="border-bottom: 0;">&nbsp;</td>
							<td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>">-</td>
							<td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>">-</td>
							<td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>">-</td>
	                        <td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>">
	                        <td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>">
	                        <td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>">
	                        <td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>">
	                        <td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>">
	                        <td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>">
	                        <td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>">
	                        <td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>">
	                        <td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>">
	                        <td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>">
	                        <td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>">
	                        <td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>">
	                        <td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>">
	                        <td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>">
	                        <td style="border-bottom: 0;" rowspan="<?php echo $total_temp; ?>">
	                        <a href="javascript:void(0)" idP="<?php echo $row->id; ?>" class="icon-pencil edit-program" title="Kendali Program"/>
	                        </td>
						</tr>
						
							<?php
						}
					}
				?>
			</tbody>
		</table>
	</div>
</article>