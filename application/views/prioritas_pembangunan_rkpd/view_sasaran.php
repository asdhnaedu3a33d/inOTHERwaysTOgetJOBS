<script type="text/javascript">
	var element_sasaran;
	$(document).ready(function() {

		$(".tbh_sasaran").click(function(){
			var idpi = $(this).attr("id-pi");
			close_all();
			prepare_facebox();
			$.ajax({
				type: "POST",
				url: '<?php echo site_url("prioritas_pembangunan_rkpd/cru_sasaran"); ?>',
				data: {idpi : idpi},
				success: function(msg){
					if (msg!="") {
						$.facebox(msg);
					}
				}
			});
		});

		$(".edit_sasaran").click(function(){
			var idpi = $(this).parent().parent().attr("id-pi");
			var ids = $(this).attr("id-s");
			close_all();
			prepare_facebox();
			$.ajax({
				type: "POST",
				url: '<?php echo site_url("prioritas_pembangunan_rkpd/cru_sasaran"); ?>',
				data: {ids : ids, idpi : idpi},
				success: function(msg){
					if (msg!="") {
						$.facebox(msg);
					}
				}
			});
		});

		$(".delete_sasaran").click(function(){
			if (confirm('Apakah anda yakin untuk menghapus data sasaran ini?')) {
				var ids = $(this).attr("id-s");

				element_prioritas.parent().next().hide();
				$.blockUI({
					css: window._css,
					overlayCSS: window._ovcss
				});

				$.ajax({
					type: "POST",
					url: '<?php echo site_url("prioritas_pembangunan_rkpd/delete_sasaran"); ?>',
					data: {ids : ids},
					success: function(msg){
						if (msg!="") {
							$.blockUI({
								message: msg.msg,
								timeout: 2000,
								css: window._css,
								overlayCSS: window._ovcss
							});
							element_prioritas.trigger('click');
						}
					}
				});
			}
		});

		
		$("#sasaran td.td-click").click(function(){
			// $("#program-frame").hide();
			$.blockUI({
				css: window._css,
				overlayCSS: window._ovcss
			});

			element_sasaran = $(this).parent();
			var idpi = $(this).parent().attr("id-pi");
			var ids = $(this).parent().attr("id-s");
			var this_element = $(this);
			$.ajax({
				type: "POST",
				url: '<?php echo site_url("prioritas_pembangunan_rkpd/view_program"); ?>',
				data: {ids : ids, idpi : idpi},
				success: function(msg){
					if (msg!="") {
						$("#program-frame").html(msg);
						element_sasaran = this_element;
						$.blockUI({
							timeout: 1000,
							css: window._css,
							overlayCSS: window._ovcss
						});
					};
				}
			});
		});

	});
</script>

<table id="sasaran" class="table-common" style="width: 100%">
	<tr>
		<th colspan="3">
			Sasaran Pembangunan
			<a href="javascript:void(0)" id-pi="<?php echo $id_prioritas; ?>" class="icon-plus-sign tbh_sasaran" style="float: right" title="Tambah Sasaran"></a>
		</th>
	</tr>
	<tr>
		<th width="40px">No</th>
		<th>Sasaran</th>
		<th width="70px">Action</th>
	</tr>
	<?php if (!empty($sasaran)): ?>
		<?php foreach ($sasaran as $key => $value): ?>
			<tr class="tr-click" id-s="<?php echo $value->id_prio; ?>" id-pi="<?php echo $id_prioritas; ?>">
				<td class="td-click"><?php echo ($key+1).'.'; ?></td>
				<td class="td-click"><?php echo $value->sasaran; ?></td>
				<td align="center">
					<a href="javascript:void(0)" id-s="<?php echo $value->id_prio; ?>" class="icon-pencil edit_sasaran" title="Edit Sasaran"/>
					<a href="javascript:void(0)" id-s="<?php echo $value->id_prio; ?>" class="icon-remove delete_sasaran" title="Hapus Sasaran"/>
				</td>
			</tr>
		<?php endforeach ?>
	<?php else: ?>
		<tr>
			<td colspan="3" align="center"><strong>Tidak Ada Data</strong></td>
		</tr>
	<?php endif ?>
</table>