<script type="text/javascript">
	$(document).ready(function () {
		$(".terima-program").click(function(){
			if (confirm('Apakah anda yakin akan menyetujui data program ini?')) {
				$.ajax({
					type: "POST",
					url: '<?php echo site_url("renstra/save_veri_akhir"); ?>',
					data: {id: $(this).attr("id-p"), veri: 'setuju'},
					success: function(msg){
						$.blockUI({
							css: window._css,
							timeout: 2000,
							overlayCSS: window._ovcss
						});

						location.reload();
					}
				});
			}
		});

		$(".tdk_terima-program").click(function(){
			$.ajax({
					type: "POST",
					url: '<?php echo site_url("renstra/veri_view_tdk_setuju"); ?>',
					data: {id: $(this).attr("id-p")},
					success: function(msg){
						prepare_facebox();
						$.facebox(msg);
					}
				});
		});
	});
</script>
<article class="module width_full" style="width: 138%; margin-left: -19%;">
	<header>
	  <h3>Verifikasi Renstra Untuk Renstra Final / Akhir</h3>
	</header>
	<div class="module_content" style="overflow:auto">
    <div class="scroll">
  		<table id="renstra" class="table-common" style="width:99%">
  			<thead>
  				<tr>
  					<th rowspan="3" colspan="2">Kode</th>
  					<th rowspan="3">Bidang Urusan Pemerintah Daerah</th>
  					<th rowspan="3">Indikator Kinerja Program (outcome)</th>
  					<th rowspan="3">Kondisi Kinerja pada Awal RPJMD</th>
  					<th colspan="10">Capaian Kinerja Program dan Kerangka Pendanaan (Rupiah)</th>
  					<th colspan="2" rowspan="2">Kondisi Kinerja pada akhir periode RPJMD</th>
  					<th rowspan="3">SKPD Penanggung Jawab</th>
  					<th rowspan="3">Action</th>
  				</tr>
  				<tr>
  					<th colspan="2">Tahun I</th>
  					<th colspan="2">Tahun II</th>
  					<th colspan="2">Tahun III</th>
  					<th colspan="2">Tahun IV</th>
  					<th colspan="2">Tahun V</th>
  				</tr>
  				<tr>
  					<th>Target</th>
  					<th>Rp</th>
  					<th>Target</th>
  					<th>Rp</th>
  					<th>Target</th>
  					<th>Rp</th>
  					<th>Target</th>
  					<th>Rp</th>
  					<th>Target</th>
  					<th>Rp</th>
  					<th>Target</th>
  					<th>Rp</th>
  				</tr>
  			</thead>
  			<tbody>
  		<?php
  			foreach ($bidang_urusan as $row) {
  				$total_nominal = $row->nominal_1_pro+$row->nominal_2_pro+$row->nominal_3_pro+$row->nominal_4_pro+$row->nominal_5_pro;
  		?>
  				<tr style="font-weight: bold;">
  					<td align="center"><?php echo $row->kd_urusan.".".$row->kd_bidang; ?></td>
  					<td></td>
  					<td><?php echo $row->Nm_Bidang; ?></td>
  					<td></td>
  					<td></td>
  					<td></td>
  					<td align="right"><?php echo Formatting::currency($row->nominal_1_pro); ?></td>
  					<td></td>
  					<td align="right"><?php echo Formatting::currency($row->nominal_2_pro); ?></td>
  					<td></td>
  					<td align="right"><?php echo Formatting::currency($row->nominal_3_pro); ?></td>
  					<td></td>
  					<td align="right"><?php echo Formatting::currency($row->nominal_4_pro); ?></td>
  					<td></td>
  					<td align="right"><?php echo Formatting::currency($row->nominal_5_pro); ?></td>
  					<td></td>
  					<td align="right"><?php echo Formatting::currency($total_nominal); ?></td>
  					<td></td>
  					<td></td>
  				</tr>
  		<?php
  				$bidang_urusan_skpd = $this->m_renstra_trx->get_bidang_urusan_skpd_4_cetak_final($row->kd_urusan, $row->kd_bidang);
  				foreach ($bidang_urusan_skpd as $row1) {
  					$total_nominal = $row1->nominal_1_pro+$row1->nominal_2_pro+$row1->nominal_3_pro+$row1->nominal_4_pro+$row1->nominal_5_pro;
  		?>
  						<tr style="font-weight: bold;">
  							<td align="center"><?php echo $row1->kd_urusan.".".$row1->kd_bidang; ?></td>
  							<td></td>
  							<td><?php echo $row1->nama_skpd; ?></td>
  							<td></td>
  							<td></td>
  							<td></td>
  							<td align="right"><?php echo Formatting::currency($row1->nominal_1_pro); ?></td>
  							<td></td>
  							<td align="right"><?php echo Formatting::currency($row1->nominal_2_pro); ?></td>
  							<td></td>
  							<td align="right"><?php echo Formatting::currency($row1->nominal_3_pro); ?></td>
  							<td></td>
  							<td align="right"><?php echo Formatting::currency($row1->nominal_4_pro); ?></td>
  							<td></td>
  							<td align="right"><?php echo Formatting::currency($row1->nominal_5_pro); ?></td>
  							<td></td>
  							<td align="right"><?php echo Formatting::currency($total_nominal); ?></td>
  							<td></td>
  							<td></td>
  						</tr>
  		<?php
  					$bidang_urusan_skpd_program = $this->m_renstra_trx->get_bidang_urusan_skpd_program_4_cetak_final($row1->kd_urusan, $row1->kd_bidang, $row1->id_skpd);
  					foreach ($bidang_urusan_skpd_program as $row2) {
  						$total_nominal = $row2->nominal_1_pro+$row2->nominal_2_pro+$row2->nominal_3_pro+$row2->nominal_4_pro+$row2->nominal_5_pro;
  						$indikator_program = $this->m_renstra_trx->get_indikator_prog_keg($row2->id, FALSE, TRUE);
  						$temp = $indikator_program->result();
  						$total_temp = $indikator_program->num_rows();
  						?>
  							<tr>
  								<td rowspan="<?php echo $total_temp; ?>" align="center"><?php echo $row2->kd_urusan.".".$row2->kd_bidang; ?></td>
  								<td rowspan="<?php echo $total_temp; ?>" align="center"><?php echo $row2->kd_program; ?></td>
  								<td rowspan="<?php echo $total_temp; ?>"><?php echo $row2->nama_prog_or_keg; ?></td>
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
  								<td align="center">
  									<?php
  										echo $temp[0]->target_1." ".$temp[0]->nama_value;
  									?>
  								</td>
  								<td rowspan="<?php echo $total_temp; ?>" align="right"><?php echo Formatting::currency($row2->nominal_1_pro); ?></td>
  								<td align="center">
  									<?php
  										echo $temp[0]->target_2." ".$temp[0]->nama_value;
  									?>
  								</td>
  								<td rowspan="<?php echo $total_temp; ?>" align="right"><?php echo Formatting::currency($row2->nominal_2_pro); ?></td>
  								<td align="center">
  									<?php
  										echo $temp[0]->target_3." ".$temp[0]->nama_value;
  									?>
  								</td>
  								<td rowspan="<?php echo $total_temp; ?>" align="right"><?php echo Formatting::currency($row2->nominal_3_pro); ?></td>
  								<td align="center">
  									<?php
  										echo $temp[0]->target_4." ".$temp[0]->nama_value;
  									?>
  								</td>
  								<td rowspan="<?php echo $total_temp; ?>" align="right"><?php echo Formatting::currency($row2->nominal_4_pro); ?></td>
  								<td align="center">
  									<?php
  										echo $temp[0]->target_5." ".$temp[0]->nama_value;
  									?>
  								</td>
  								<td rowspan="<?php echo $total_temp; ?>" align="right"><?php echo Formatting::currency($row2->nominal_5_pro); ?></td>
  								<td align="center">
  									<?php
  										echo $temp[0]->target_kondisi_akhir." ".$temp[0]->nama_value;
  									?>
  								</td>
  								<td rowspan="<?php echo $total_temp; ?>" align="right"><?php echo Formatting::currency($total_nominal); ?></td>
  								<td rowspan="<?php echo $total_temp; ?>"><?php echo $row1->nama_skpd; ?></td>
  								<td rowspan="<?php echo $total_temp; ?>" align="center">
  									<a id-p="<?php echo $row2->id; ?>" class="icon-ok-sign terima-program" href="#"></a>
  									<a id-p="<?php echo $row2->id; ?>" class="icon-remove-sign tdk_terima-program" href="#"></a>
  								</td>
  							</tr>
  		<?php
  						if ($total_temp > 1) {
  							for ($i=1; $i < $total_temp; $i++) {
  		?>
  							<tr>
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
  								<td align="center">
  									<?php
  										echo $temp[$i]->target_1." ".$temp[$i]->nama_value;
  									?>
  								</td>
  								<td align="center">
  									<?php
  										echo $temp[$i]->target_2." ".$temp[$i]->nama_value;
  									?>
  								</td>
  								<td align="center">
  									<?php
  										echo $temp[$i]->target_3." ".$temp[$i]->nama_value;
  									?>
  								</td>
  								<td align="center">
  									<?php
  										echo $temp[$i]->target_4." ".$temp[$i]->nama_value;
  									?>
  								</td>
  								<td align="center">
  									<?php
  										echo $temp[$i]->target_5." ".$temp[$i]->nama_value;
  									?>
  								</td>
  								<td align="center">
  									<?php
  										echo $temp[$i]->target_kondisi_akhir." ".$temp[$i]->nama_value;
  									?>
  								</td>
  							</tr>
  		<?php
  							}
  						}
  					}
  				}
  			}
  		?>
  			</tbody>
  		</table>
    </div>
	</div>
	<footer>
		<div class="submit_link">
  			<input type="button" value="Kembali" onclick="history.go(-1)">
		</div>
	</footer>
</article>
