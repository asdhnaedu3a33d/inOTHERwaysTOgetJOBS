
<article class="module width_full">
	<header>
	  <h3 style="text-transform: capitalize;">Hapus Data <?php echo $jenis; ?></h3>
	</header>
	<div class="module_content" style="overflow:auto">
		<table id="bidang_table" class="table-common tablesorter" style="width:100%">
			<thead>
				<tr>
					<th width="40px">No.</th>
					<th>Nama SKPD</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($result as $key => $row): ?>
					<tr>
						<td align="center"><?php echo ($key+1).'.'; ?></td>
						<td><?php echo $row->nama_skpd; ?></td>
						<td align="center">
							<?php if ($cont == 'renstra') {
								echo '<button type="button" id-s="'.$row->id_skpd.'" id="but">Ubah Status</button>';
							}else{
								echo '<button type="button" id-s="'.$row->id_skpd.'" id="but">Hapus Data</button>';
							} ?>
							
						</td>
					</tr>
				<?php endforeach ?>
			</tbody>
		</table>
	</div>
</article>
<script type="text/javascript">
	$(document).on('click', '#but' ,function() {
		var r = confirm("Apakah anda yakin mengapus data ini ?");
	    if (r == true) {
	        $.blockUI({				
				css: window._css,				
				overlayCSS: window._ovcss
			});
			$.ajax({
				type: "POST",
				url: '<?php echo site_url("data/do_hapus"); ?>',
				dataType: 'JSON',
				data: {id_skpd: $(this).attr('id-s'), jenis : '<?php echo $jenis; ?>', cont : '<?php echo $cont; ?>'},
				success: function(msg){
					catch_expired_session2(msg);
					if (msg.success==1) {
						$.blockUI({
							message: msg.msg,
							css: window._css,
							timeout: 5000,
							overlayCSS: window._ovcss
						});
						location.reload();
					}else{
						$.blockUI({
							message: msg.msg,
							css: window._css,
							timeout: 30000,
							overlayCSS: window._ovcss
						});
						location.reload();
					}
				}
			});
	    }
	});
</script>
