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
				url: "<?php echo site_url('renstra/do_veri'); ?>",
				data: {id_renstra:$(this).attr("id-r"), id_kegiatan: $(this).attr("id-k")},
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
				url: "<?php echo site_url('renstra/do_veri_prog'); ?>",
				data: {id_renstra:$(this).attr("id-r"), id_program: $(this).attr("id-p")},
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
			$(location).attr('href', '<?php echo site_url("renstra/veri_view"); ?>')
		});

		$("#disapprove_renstra").click(function(){
			prepare_facebox();
			$.blockUI({
				css: window._css,
				overlayCSS: window._ovcss
			});

	    	$.ajax({
				type: "POST",
				url: "<?php echo site_url('renstra/disapprove_renstra'); ?>",
				data: {id_renstra:$(this).attr("id-r")},
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
	  <h3>Verifikasi Renstra Untuk Rancangan Awal (Ranwal)</h3>
	</header>
	<div class="module_content";>
		<table class="fcari" width="100%">
		<?php
			echo $header;
		?>
		</table>
		<div style="overflow:auto">
			<table class="table-common" width="100%">
				<thead>
					<tr>
						<th rowspan="3">Tujuan</th>
						<th rowspan="3">Sasaran</th>
						<th rowspan="3">Indikator Sasaran</th>
						<th rowspan="3" colspan="4">Kode</th>
						<th rowspan="3">Program dan Kegiatan</th>
						<th rowspan="3">Indikator Kinerja Program (outcome) dan Kegiatan (output)</th>
						<th style="display:none;" rowspan="3" >Action</th>
					</tr>
				</thead>
				<tbody>
				<?php
					$counter_tujuan = 0;
					foreach ($program as $row) {
						$result = $this->m_renstra_trx->get_kegiatan_skpd_4_cetak($row->id);
						$total_kegiatan = $result->num_rows();
						$total_row_tujuan = $this->m_renstra_trx->get_kegiatan_berdasarkan_tujuan($row->id_tujuannya)->num_rows();
						
						$kegiatan = $result->result();

						$total_nominal = $row->nominal_1_pro+$row->nominal_2_pro+$row->nominal_3_pro+$row->nominal_4_pro+$row->nominal_5_pro;

						$indikator_sasaran = $this->m_renstra_trx->get_indikator_sasaran($row->id_sasaran);
						$indikator_program = $this->m_renstra_trx->get_indikator_prog_keg($row->id);
				?>
					<tr>
<?php if ($counter_tujuan != $row->id_tujuannya): ?>
	<td rowspan="<?php echo $total_row_tujuan; ?>"><?php echo $row->tujuan; ?></td>
	<?php $counter_tujuan = $row->id_tujuannya; ?>
<?php endif ?>
						

						<td rowspan="<?php echo $total_kegiatan+1; ?>"><?php echo $row->sasaran; ?></td>
						<td rowspan="<?php echo $total_kegiatan+1; ?>">
							<?php
								$i=0;
								foreach ($indikator_sasaran as $row_sasaran) {
									$i++;
									echo $i .". ". $row_sasaran->indikator ."<BR>";
								}
							?>
						</td>
						<td><?php echo $row->kd_urusan; ?></td>
						<td><?php echo $row->kd_bidang; ?></td>
						<td><?php echo $row->kd_program; ?></td>
						<td><?php echo $row->kd_kegiatan; ?></td>
						<td><?php echo $row->nama_prog_or_keg; ?></td>
						<td>
							<?php
								$i=0;
								foreach ($indikator_program as $row_program) {
									$i++;
									echo $i .". ". $row_program->indikator ."<BR>";
								}
							?>
						</td>
						<td style="display:none;" align="center">
				<?php
						if ($row->id_status == 2) {
				?>
							<a href="javascript:void(0)" class="icon-th-large veri_prog" id-r="<?php echo $row->id_renstra; ?>" id-p="<?php echo $row->id; ?>"></a>
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
						foreach ($kegiatan as $row) {
							$indikator_kegiatan = $this->m_renstra_trx->get_indikator_prog_keg($row->id);
				?>
							<tr>
								<td><?php echo $row->kd_urusan; ?></td>
								<td><?php echo $row->kd_bidang; ?></td>
								<td><?php echo $row->kd_program; ?></td>
								<td><?php echo $row->kd_kegiatan; ?></td>
								<td><?php echo $row->nama_prog_or_keg; ?></td>
								<td>
									<?php
										$i=0;
										foreach ($indikator_kegiatan as $row_kegiatan) {
											$i++;
											echo $i .". ". $row_kegiatan->indikator ."<BR>";
										}
									?>
								</td>
								<td style="display:none;" align="center">
				<?php
								if ($row->id_status == 2) {
				?>
									<a href="javascript:void(0)" class="icon-list veri_keg" id-r="<?php echo $row->id_renstra; ?>" id-k="<?php echo $row->id; ?>"></a>
									<a href="<?php echo site_url('renstra/tampilkan/'.$row->id); ?>" target ="_blank" class="icon-file " title="View Detail Belanja"/>  </a>
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
						}
					}
				?>
				</tbody>
			</table>
		</div>
	</div>
	<footer>
		<div class="submit_link">
		</div>
	</footer>
</article>
