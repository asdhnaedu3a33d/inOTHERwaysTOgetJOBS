<?php
	if (TRUE) {
		$enable_add = TRUE;
		$enable_edit = TRUE;
		$enable_delete = TRUE;
	}else{
		$enable_add = FALSE;
		$enable_edit = FALSE;
		$enable_delete = FALSE;
	}
?>
<script type="text/javascript">
	var element_kebijakan;
	$(document).ready(function(){
		$(".tbh_kebijakan").click(function(){
			var idr = $(this).attr("id-r");
			var idt = $(this).attr("id-t");
			var ids = $(this).attr("id-s");
			var idst = $(this).attr("id-st");

			prepare_facebox();
			$.ajax({
				type: "POST",
				url: '<?php echo site_url("rpjmd/cru_kebijakan"); ?>',
				data: {id_rpjmd: idr, id_tujuan: idt, id_sasaran: ids, id_strategi: idst},
				success: function(msg){
					if (msg!="") {
						$.facebox(msg);
					};
				}
			});
		});

		$(".remove-kebijakan").click(function(){
			if (confirm('Apakah anda yakin untuk menghapus data kebijakan ini?')) {
				element_strategi.parent().next().hide();
				$.blockUI({
					css: window._css,
					overlayCSS: window._ovcss
				});
				$.ajax({
					type: "POST",
					url: '<?php echo site_url("rpjmd/delete_kebijakan"); ?>',
					data: {id_kebijakan: $(this).attr("id-kb")},
					dataType: "json",
					success: function(msg){
						if (msg.success==1) {
							element_strategi.trigger( "click" );
							$.blockUI({
								message: msg.msg,
								timeout: 2000,
								css: window._css,
								overlayCSS: window._ovcss
							});
						};
					}
				});
			}
		});

		$(".edit-kebijakan").click(function(){
			var idr = $(this).parent().parent().attr("id-r");
			var idt = $(this).parent().parent().attr("id-t");
			var ids = $(this).parent().parent().attr("id-s");
			var idst = $(this).parent().parent().attr("id-st");

			prepare_facebox();
			$.blockUI({
				css: window._css,
				overlayCSS: window._ovcss
			});
			$.ajax({
				type: "POST",
				url: '<?php echo site_url("rpjmd/cru_kebijakan"); ?>',
				data: {id_rpjmd: idr, id_tujuan: idt, id_sasaran: ids, id_strategi: idst, id_kebijakan: $(this).attr("id-kb")},
				success: function(msg){
					if (msg!="") {
						$.facebox(msg);
						$.blockUI({
							timeout: 2000,
							css: window._css,
							overlayCSS: window._ovcss
						});
					};
				}
			});
		});

		$("#kebijakan td.td-click").click(function(){
			// element_kebijakan = $(this);
			// $.blockUI({
			// 	css: window._css,
			// 	overlayCSS: window._ovcss
			// });
			//
			// $("tr.tr-frame-program").hide();
			// $.blockUI({
			// 	css: window._css,
			// 	overlayCSS: window._ovcss
			// });
			//
			// var idt = $(this).parent().attr("id-t");
			// var idk = $(this).parent().attr("id-kb");
			// $.ajax({
			// 	type: "POST",
			// 	url: '<?php echo site_url("rpjmd/get_program"); ?>',
			// 	data: {id_rpjmd: idr, id_kebijakan: idk},
			// 	success: function(msg){
			// 		element_kebijakan.next().children().html(msg);
			// 		element_kebijakan.next().fadeIn();
			// 		element_kebijakan = this_element;
			// 		if (msg!="") {
			// 			$("#kegiatan-frame").html(msg);
			// 			$.blockUI({
			// 				timeout: 1000,
			// 				css: window._css,
			// 				overlayCSS: window._ovcss
			// 			});
			// 		};
			// 	}
			// });
		});
	});
</script>
		<table id="kebijakan" class="table-common" style="width: 99%">
			<thead>
				<tr>
					<th colspan="11">
						Arah Kebijakan
						<?php
							if ($enable_add) {
						?>
							<a href="javascript:void(0)" class="icon-plus-sign tbh_kebijakan" style="float: right" title="Tambah Kebijakan"
							id-r="<?php echo $id_rpjmd; ?>" id-t="<?php echo $id_tujuan; ?>" id-s="<?php echo $id_sasaran; ?>" id-st="<?php echo $id_strategi; ?>" ></a>
						<?php
							}
						?>
					</th>
				</tr>
				<tr>
					<th>No</th>
					<th>Arah Kebijakan</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
			<?php
				if (!empty($kebijakan)) {
					$i=0;
					foreach ($kebijakan as $row) {
						$i++;
			?>
				<tr class="tr-click" id-r="<?php echo $id_rpjmd; ?>" id-t="<?php echo $id_tujuan; ?>" id-s="<?php echo $id_sasaran; ?>" id-st="<?php echo $id_strategi; ?>" id-kb="<?php echo $row->id; ?>">
					<td class="td-click" width="50px"><?php echo $i; ?></td>
					<td class="td-click"><?php echo $row->kebijakan; ?></td>
					<td align="center" width="50px">
					<?php
						if ($enable_edit) {
					?>
						<a href="javascript:void(0)" id-kb="<?php echo $row->id; ?>" class="icon-pencil edit-kebijakan" title="Edit Kebijakan"/>
					<?php
						}

						if ($enable_delete) {
					?>
						<a href="javascript:void(0)" id-kb="<?php echo $row->id; ?>" class="icon-remove remove-kebijakan" title="Hapus Kebijakan"/>
					<?php
						}
					?>
					</td>
				</tr>
				<tr class="tr-frame-program" style="display: none">
					<td colspan="4"></td>
				</tr>
			<?php
					}
				}else{
			?>
				<tr>
					<td colspan="11" align="center">Tidak ada data...</td>
				</tr>
			<?php
				}
			?>
			</tbody>
		</table>
