<style type="text/css">
</style>
<script type="text/javascript">
	$(document).ready(function(){
		$(".veri_keg").click(function(){
			prepare_facebox();
			$.blockUI({
				css: window._css,
				overlayCSS: window._ovcss
			});

	    	$.ajax({
				type: "POST",
				url: "<?php echo site_url('ppas_perubahan/do_veri'); ?>",
				data: {id: $(this).attr("id-r"), action: 'keg'},
				success: function(msg){
					$.blockUI({
						message: msg.msg,
						timeout: 2000,
						css: window._css,
						overlayCSS: window._ovcss
					});
					$.facebox(msg);
				}
			});
		});

		$(".veri_prog").click(function(){
			prepare_facebox();
			$.blockUI({
				css: window._css,
				overlayCSS: window._ovcss
			});

	    	$.ajax({
				type: "POST",
				url: "<?php echo site_url('ppas_perubahan/do_veri'); ?>",
				data: {id: $(this).attr("id-r"), action: 'pro'},
				success: function(msg){
					$.blockUI({
						message: msg.msg,
						timeout: 2000,
						css: window._css,
						overlayCSS: window._ovcss
					});
					$.facebox(msg);
				}
			});
		});

		$("#kembali_all").click(function(){
			$(location).attr('href', '<?php echo site_url("ppas_perubahan/veri_view"); ?>')
		});

		$("#disapprove_renja").click(function(){
			prepare_facebox();
			$.blockUI({
				css: window._css,
				overlayCSS: window._ovcss
			});

	    	$.ajax({
				type: "POST",
				url: "<?php echo site_url('ppas_perubahan/disapprove_renja'); ?>",
				data: {ids:$(this).attr("id-s"), idu:$(this).attr("id-u"), idb:$(this).attr("id-b")},
				success: function(msg){
					$.blockUI({
						message: msg.msg,
						timeout: 2000,
						css: window._css,
						overlayCSS: window._ovcss
					});
					$.facebox(msg);
				}
			});
		});
	});
</script>
<article class="module width_full" style="width: 138%; margin-left: -19%;">
	<header>
	  <h3>Verifikasi PPAS Perubahan Untuk Rancangan Awal (Ranwal)</h3>
	</header>
	<div class="module_content";>
		<div style="overflow:auto">
			<table class="table-common" width="100%">
				<thead>
					<tr>
						<th colspan="4">Kode</th>
						<th>Program dan Kegiatan</th>
						<th>Indikator</th>
						<!-- <th>Pagu Indikatif <?php echo $th_anggaran + 1; ?></th> -->
						<th>Target</th>
						<th>Anggaran <?php echo $th_anggaran; ?></th>
						<th>Pagu Indikatif <?php echo $th_anggaran + 1; ?></th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
				<?php
					foreach ($ppas as $row) {
						$indikator_program = $this->m_ppas_perubahan->get_indikator_prog_keg($row->id, FALSE);
						$count_prog = $indikator_program->num_rows();
						$i = 1;
						foreach ($indikator_program->result() as $row_program) {
							if ($i == 1) {
				?>
					<tr style="background-color: #eee;">
						<td rowspan="<?php echo $count_prog ?>"><?php echo $row->kd_urusan; ?></td>
						<td rowspan="<?php echo $count_prog ?>"><?php echo $row->kd_bidang; ?></td>
						<td rowspan="<?php echo $count_prog ?>"><?php echo $row->kd_program; ?></td>
						<td rowspan="<?php echo $count_prog ?>">&nbsp;</td>
						<td rowspan="<?php echo $count_prog ?>"><?php echo $row->nama_prog_or_keg; ?></td>
						<td><?php echo $row_program->indikator; ?></td>
						<td><?php echo $row_program->target." ".$row_program->satuan_target; ?></td>
						<td rowspan="<?php echo $count_prog ?>"><?php echo number_format($row->nom, 2, ',', '.'); ?></td>
						<td rowspan="<?php echo $count_prog ?>"><?php echo number_format($row->nom_thndpn, 2, ',', '.'); ?></td>
						<td rowspan="<?php echo $count_prog ?>" align="center">
					<?php
							if ($row->id_status == 2) {
					?>
								<a href="javascript:void(0)" class="<?php echo ($row->is_prog_or_keg==1)?'icon-th-large veri_prog':'icon-list veri_keg';?>" id-r="<?php echo $row->id; ?>"></a>
					<?php
							}elseif ($row->id_status == 3) {
					?>
								<i style="color:red;">Tidak Disetujui</i>
					<?php
							}elseif ($row->id_status == 4) {
					?>
								<i style="color:black;">Disetujui</i>
					<?php
							}
					?>
							</td>
						</tr>
					<?php
							$i=0;
							}else{
								echo "<tr style='background-color: #eee;'><td>".$row_program->indikator."</td><td>".$row_program->target." ".$row_program->satuan_target."</td></tr>";
							}
						}

						$ppas_kegiatan = $this->m_ppas_perubahan->get_all_kegiatan($row->id, $id_skpd, $th_anggaran);
						foreach ($ppas_kegiatan as $row2) {
							$indikator_kegiatan = $this->m_ppas_perubahan->get_indikator_prog_keg($row2->id, FALSE);
							$count_keg = $indikator_kegiatan->num_rows();
							$j = 1;
							foreach ($indikator_kegiatan->result() as $row_kegiatan) {
								if ($j == 1) {
						?>
							<tr>
								<td rowspan="<?php echo $count_keg ?>"><?php echo $row2->kd_urusan; ?></td>
								<td rowspan="<?php echo $count_keg ?>"><?php echo $row2->kd_bidang; ?></td>
								<td rowspan="<?php echo $count_keg ?>"><?php echo $row2->kd_program; ?></td>
								<td rowspan="<?php echo $count_keg ?>"><?php echo $row2->kd_kegiatan; ?></td>
								<td rowspan="<?php echo $count_keg ?>"><?php echo $row2->nama_prog_or_keg; ?></td>
								<td><?php echo $row_kegiatan->indikator; ?></td>
								<td><?php echo $row_kegiatan->target." ".$row_kegiatan->satuan_target;?></td>
								<td rowspan="<?php echo $count_keg ?>"><?php echo number_format($row2->nominal, 2, ',', '.'); ?></td>
								<td rowspan="<?php echo $count_keg ?>"><?php echo number_format($row2->nominal_thndpn, 2, ',', '.'); ?></td>
								<td rowspan="<?php echo $count_keg ?>" align="center">
							<?php
									if ($row2->id_status == 2) {
							?>
										<a href="javascript:void(0)" class="<?php echo ($row2->is_prog_or_keg==1)?'icon-th-large veri_prog':'icon-list veri_keg';?>" id-r="<?php echo $row2->id; ?>"></a>
							<?php
									}elseif ($row2->id_status == 3) {
							?>
										<i style="color:red;">Tidak Disetujui</i>
							<?php
									}elseif ($row2->id_status == 4) {
							?>
										<i style="color:black;">Disetujui</i>
							<?php
									}
							?>
									</td>
								</tr>
							<?php
									$j=0;
									}else{
										echo "<tr><td>".$row_kegiatan->indikator."</td><td>".$row_kegiatan->target." ".$row_kegiatan->satuan_target."</td></tr>";
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
			<input id-s="<?php echo $id_skpd; ?>" id-u="<?php echo $urusan; ?>" id-b="<?php echo $bidang; ?>" type="button" id="disapprove_renja" value="Tidak Setujui PPAS">
			<input type="button" id="kembali_all" value="Kembali">
		</div>
	</footer>
</article>
