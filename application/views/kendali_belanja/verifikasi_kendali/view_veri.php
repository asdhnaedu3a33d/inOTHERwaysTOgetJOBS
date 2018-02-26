<article class="module width_full">
	<header>
	  <h3>Verifikasi Kegiatan Belanja Langsung</h3>
	</header>
	<div class="module_content"; style="overflow:auto">
		<table id="renstra" class="table-common" style="width:99%">
			<thead>
				<tr>
					<th colspan="4">Kode</th>
					<th>Kegiatan</th>
					<th>Triwulan</th>					
					<th>Pagu</th>
					<th>Capaian (%)</th>
					<th>Action</th>
				</tr>				
			</thead>
			<tbody>
	<?php
		if (!empty($kendali)) {
			$i=0;
			foreach ($kendali as $row) {		
				$i++;
	?>
				<tr>
					<td align="center"><?php echo $row->kd_urusan; ?></td>
					<td align="center"><?php echo $row->kd_bidang; ?></td>
					<td align="center"><?php echo $row->kd_program; ?></td>
					<td align="center"><?php echo $row->kd_kegiatan; ?></td>
					<td><?php echo $row->nama_prog_or_keg; ?></td>
					<td align="center">TW <?php echo $row->id_triwulan; ?></td>
					<td align="right"><?php echo FORMATTING::currency($row->anggaran); ?></td>
					<td align="center"><?php echo $row->capaian; ?></td>
					<td align="center"><a href="javascript:void(0)" id-p="<?php echo $row->id_tx_dpa_prog_keg_triwulan; ?>" class="icon-edit veri" title="Verifikasi"/></td>
				</tr>
	<?php
			}
		}else{
	?>
				<tr>
					<td align="center" colspan="9">Tidak ada data. . .</td>
				</tr>
	<?php
		}
	?>
			</tbody>
		</table>
	</div>
	<footer>
		<div class="submit_link">			
			<input type="button" id="kembali_all" value="Kembali">
		</div>
	</footer>
</article>
<script type="text/javascript">
	$(document).ready(function(){
		$("#kembali_all").click(function(){
			$(location).attr('href', '<?php echo site_url("kendali/veri_view"); ?>')
		});
		
		$(".veri").click(function(){
			$.ajax({
				type: "POST",
				url: '<?php echo site_url("kendali/show_view"); ?>',
				data: {id: $(this).attr("id-p")},
				success: function(msg){
					prepare_facebox();
					$.facebox(msg);
				}
			});
		});
	});
</script>