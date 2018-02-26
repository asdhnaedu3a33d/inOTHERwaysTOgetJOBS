<script type="text/javascript">
	var element_kegiatan;
	$(document).ready(function() {

		$(".tbh_kegiatan").click(function(){
			var idpi = $(this).attr("id-pi");
			var ids = $(this).attr("id-s");
			var idpr = $(this).attr("id-pr");
			prepare_facebox();
			$.ajax({
				type: "POST",
				url: '<?php echo site_url("prioritas_pembangunan_rkpd/cru_kegiatan"); ?>',
				data: {idpi : idpi, ids : ids, idpr : idpr},
				success: function(msg){
					if (msg!="") {
						$.facebox(msg);
					}
				}
			});
		});

		$(".edit_kegiatan").click(function(){
			var idpi = $(this).parent().parent().attr("id-pi");
			var ids = $(this).parent().parent().attr("id-s");
			var idpr = $(this).parent().parent().attr("id-pr");
			var idk = $(this).attr("id-k");

			prepare_facebox();
			$.ajax({
				type: "POST",
				url: '<?php echo site_url("prioritas_pembangunan_rkpd/cru_kegiatan"); ?>',
				data: {ids : ids, idpi : idpi, idpr : idpr, idk : idk},
				success: function(msg){
					if (msg!="") {
						$.facebox(msg);
					}
				}
			});
		});

		$(".delete_kegiatan").click(function(){
			if (confirm('Apakah anda yakin untuk menghapus data kegiatan ini?')) {
				var idk = $(this).attr("id-k");

		    	element_program.parent().next().hide();
				$.blockUI({
					css: window._css,
					overlayCSS: window._ovcss
				});

				$.ajax({
					type: "POST",
					url: '<?php echo site_url("prioritas_pembangunan_rkpd/delete_kegiatan"); ?>',
					data: {idk : idk},
					success: function(msg){
						if (msg!="") {
							$.blockUI({
								message: msg.msg,
								timeout: 2000,
								css: window._css,
								overlayCSS: window._ovcss
							});
							element_program.trigger('click');
						}
					}
				});
			}
		});

		
		// $("#kegiatan td.td-click").click(function(){
		// 	// $("#program-frame").hide();
		// 	$.blockUI({
		// 		css: window._css,
		// 		overlayCSS: window._ovcss
		// 	});

		// 	element_kegiatan = $(this).parent();
		// 	var idpi = $(this).parent().attr("id-pi");
		// 	var ids = $(this).parent().attr("id-s");
		// 	var this_element = $(this);
		// 	$.ajax({
		// 		type: "POST",
		// 		url: '<?php //echo site_url("prioritas_pembangunan_rkpd/view_program"); ?>',
		// 		data: {ids : ids, idpi : idpi},
		// 		success: function(msg){
		// 			if (msg!="") {
		// 				$("#program-frame").html(msg);
		// 				element_kegiatan = this_element;
		// 				$.blockUI({
		// 					timeout: 1000,
		// 					css: window._css,
		// 					overlayCSS: window._ovcss
		// 				});
		// 			};
		// 		}
		// 	});
		// });

	});
</script>

<table id="kegiatan" class="table-common" style="width: 100%">
	<tr>
		<th colspan="4">
			Kegiatan Pembangunan
			<a href="javascript:void(0)" id-pi="<?php echo $id_prioritas; ?>" id-s="<?php echo $id_sasaran; ?>" id-pr="<?php echo $id_program; ?>" class="icon-plus-sign tbh_kegiatan" style="float: right" title="Tambah Kegiatan"></a>
		</th>
	</tr>
	<tr>
		<th width="40px">No</th>
		<th>Kegiatan</th>
		<th>Indikator</th>
		<th width="70px">Action</th>
	</tr>
	<?php if (!empty($kegiatan)): ?>
		<?php foreach ($kegiatan as $key => $value): ?>
			<tr class="tr-click" id-pi="<?php echo $id_prioritas; ?>" id-s="<?php echo $id_sasaran; ?>" id-pr="<?php echo $id_program; ?>" id-k="<?php echo $value->id_prio; ?>">
				<td class="td-click"><?php echo ($key+1).'.'; ?></td>
				<td class="td-click"><?php echo $value->kegiatan_rkpd; ?></td>
				<td class="td-click">
					<?php 
						$indikator = $this->m_prioritas_pembangunan_rkpd->get_indikator_prog_keg($value->id_prio)->result();
						foreach ($indikator as $key_indikator => $value_indikator) {
							echo ($key_indikator+1).'. '.$value_indikator->indikator.'<br>';
						}
					?>
				</td>
				<td align="center">
					<a href="javascript:void(0)" id-k="<?php echo $value->id_prio; ?>" class="icon-pencil edit_kegiatan" title="Edit Kegiatan"/>
					<a href="javascript:void(0)" id-k="<?php echo $value->id_prio; ?>" class="icon-remove delete_kegiatan" title="Hapus Kegiatan"/>
				</td>
			</tr>
		<?php endforeach ?>
	<?php else: ?>
		<tr>
			<td colspan="4" align="center"><strong>Tidak Ada Data</strong></td>
		</tr>
	<?php endif ?>
</table>