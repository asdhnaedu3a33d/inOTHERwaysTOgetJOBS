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
				url: "<?php echo site_url('renja/do_veri'); ?>",
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
				url: "<?php echo site_url('renja/do_veri'); ?>",
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
			$(location).attr('href', '<?php echo site_url("renja/veri_view"); ?>')
		});

		$("#disapprove_renja").click(function(){
			prepare_facebox();
			$.blockUI({
				css: window._css,
				overlayCSS: window._ovcss
			});
	    	
	    	$.ajax({
				type: "POST",
				url: "<?php echo site_url('renja/disapprove_renja'); ?>",
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
	  <h3>Verifikasi Renja Untuk Rancangan Awal (Ranwal)</h3>
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
						$indikator_program = $this->m_renja_trx->get_indikator_prog_keg($row->id);
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
					}
				?>
				</tbody>
			</table>
		</div>		
	</div>		
	<footer>
		<div class="submit_link">
			<input id-r="<?php echo $id_skpd; ?>" type="button" id="disapprove_renja" value="Tidak Setujui Renja">
			<input type="button" id="kembali_all" value="Kembali">
		</div>
	</footer>
</article>