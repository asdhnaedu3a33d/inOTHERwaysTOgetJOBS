<script type="text/javascript">
	$(document).ready(function () {
		$(".terima-program").click(function(){
			if (confirm('Apakah anda yakin akan menyetujui data program ini?')) {				
				$.ajax({
					type: "POST",
					url: '<?php echo site_url("renja_perubahan/save_veri_akhir"); ?>',
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
					url: '<?php echo site_url("renja_perubahan/veri_view_tdk_setuju"); ?>',
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
	  <h3>Verifikasi Renja Perubahan Untuk Renja Final / Akhir</h3>
	</header>
	<div class="module_content"; style="overflow:auto">
		<table id="renja" class="table-common" style="width:99%">
			<thead>
				<tr>
					<th colspan="4">Kode</th>
					<th>Kegiatan</th>
					<th>Indikator Kinerja Program (outcome)</th>
					<th>Target</th>
					<th>Nominal</th>
					<th>Lokasi</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
<?php
		foreach ($renjas as $row) {
			$indikator_program = $this->m_renja_trx_perubahan->get_indikator_prog_keg_perubahan($row->id, FALSE, TRUE);
			//var_dump($indikator_program->result());
			$temp = $indikator_program->result();
			$total_temp = $indikator_program->num_rows();
?>
			<tr>
				<td rowspan="<?php echo $total_temp; ?>" align="center"><?php echo $row->kd_urusan; ?></td>
				<td rowspan="<?php echo $total_temp; ?>" align="center"><?php echo $row->kd_bidang; ?></td>
				<td rowspan="<?php echo $total_temp; ?>" align="center"><?php echo $row->kd_program; ?></td>
				<td rowspan="<?php echo $total_temp; ?>" align="center"><?php echo $row->kd_kegiatan; ?></td>
				<td rowspan="<?php echo $total_temp; ?>"><?php echo $row->nama_prog_or_keg; ?></td>
				<td>
					<?php
						echo $temp[0]->indikator;
					?>
				</td>
				<td align="center">
					<?php
						echo $temp[0]->target." ".$temp[0]->nama_value;
					?>
				</td>
				<td rowspan="<?php echo $total_temp; ?>" align="right"><?php echo Formatting::currency($row->nominal); ?></td>
				<td rowspan="<?php echo $total_temp; ?>"><?php echo $row->lokasi; ?></td>
				<td rowspan="<?php echo $total_temp; ?>" align="center">
					<a id-p="<?php echo $row->id; ?>" class="icon-ok-sign terima-program" href="#"></a>
					<a id-p="<?php echo $row->id; ?>" class="icon-remove-sign tdk_terima-program" href="#"></a>
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
							echo $temp[$i]->target." ".$temp[$i]->nama_value;
						?>
					</td>
				</tr>
<?php
				}
			}
		}
?>
			</tbody>
		</table>
	</div>
	<footer>
		<div class="submit_link">
  			<input type="button" value="Kembali" onclick="history.go(-1)">
		</div>
	</footer>
</article>