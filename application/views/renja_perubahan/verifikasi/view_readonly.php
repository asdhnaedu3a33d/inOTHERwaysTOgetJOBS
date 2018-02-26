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
				url: "<?php echo site_url('renja_perubahan/do_veri'); ?>",
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
				url: "<?php echo site_url('renja_perubahan/do_veri'); ?>",
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
			$(location).attr('href', '<?php echo site_url("renja_perubahan/veri_view"); ?>')
		});

		$("#disapprove_renja").click(function(){
			prepare_facebox();
			$.blockUI({
				css: window._css,
				overlayCSS: window._ovcss
			});
	    	
	    	$.ajax({
				type: "POST",
				url: "<?php echo site_url('renja_perubahan/disapprove_renja'); ?>",
				data: {id:$(this).attr("id-r")},				
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
	  <h3>Verifikasi Renja Untuk Rancangan Awal (Ranwal) Perubahan READONLY</h3>
	</header>
	<div class="module_content";>
		<div style="overflow:auto">	
			<table class="table-common" width="100%">
				<thead>
					<tr>						
						<th colspan="4">Kode</th>
						<th>Program dan Kegiatan</th>
						<th>Indikator Kinerja Program (outcome) dan Kegiatan (output)</th>
						<th>Action</th>
					</tr>					
				</thead>
				<tbody>
				<?php
					foreach ($renjas as $row) {
						$indikator_program = $this->m_renja_trx_perubahan->get_indikator_prog_keg_perubahan($row->id);
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
								foreach ($indikator_program as $row_program) {
									$i++;
									echo $i .". ". $row_program->indikator ."<BR>";
								}
							?>
						</td>						
						<td align="center">
				<?php
						if ($row->id_status == 2) {
				?>
							<i style="color:blue;">Sedang Verifikasi Ranwal</i>
				<?php				
						}elseif ($row->id_status == 3) {
				?>
							<i style="color:red;">Revisi / Tidak Disetujui Ranwal</i>
				<?php
						}elseif ($row->id_status == 4) {
				?>
							<i style="color:black;">Telah Diverifikasi Ranwal</i>
                <?php
						}elseif ($row->id_status == 5) {
				?>
							<i style="color:blue;">Telah Direvisi</i>
				<?php
						}elseif ($row->id_status == 6) {
				?>
							<i style="color:red;">Revisi/Tidak Disetujui</i>
				<?php
						}elseif ($row->id_status == 7) {
				?>
							<i style="color:blue;">Telah Diverifikasi Akhir</i>
				<?php
						}
				?>			
						</td>
					</tr>
				<?php
					}
				?>
				</tbody>
			</table>
		</div>		
	</div>		
	<footer>
		<div class="submit_link">
			<input type="button" id="kembali_all" value="Kembali">
		</div>
	</footer>
</article>