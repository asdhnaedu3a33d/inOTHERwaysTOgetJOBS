<?php
	if (TRUE) {
		$enable_edit = TRUE;
		$enable_delete = TRUE;
	}else{
		$enable_edit = FALSE;
		$enable_delete = FALSE;
	}
?>
<script type="text/javascript">
	var element_strategi;
	$(document).ready(function(){
		$(".tbh_strategi").click(function(){
			var idr = $(this).attr("id-r");
			var idt = $(this).attr("id-t");
			var ids = $(this).attr("id-s");

			prepare_facebox();
			$.ajax({
				type: "POST",
				url: '<?php echo site_url("rpjmd/cru_strategi"); ?>',
				data: {id_rpjmd: idr, id_tujuan: idt, id_sasaran: ids},
				success: function(msg){
					if (msg!="") {
						$.facebox(msg);
					};
				}
			});
		});

		$(".remove-strategi").click(function(){
			if (confirm('Apakah anda yakin untuk menghapus data strategi ini?')) {
				close_all();
				element.parent().next().hide();
				$.blockUI({
					css: window._css,
					overlayCSS: window._ovcss
				});
				$.ajax({
					type: "POST",
					url: '<?php echo site_url("rpjmd/delete_strategi"); ?>',
					data: {id_strategi: $(this).attr("id-st")},
					dataType: "json",
					success: function(msg){
						if (msg.success==1) {
							element.trigger( "click" );
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

		$(".edit-strategi").click(function(){
			close_all();
			var idt = $(this).parent().parent().attr("id-t");
			var idr = $(this).parent().parent().attr("id-r");
			var ids = $(this).parent().parent().attr("id-s");

			prepare_facebox();
			$.blockUI({
				css: window._css,
				overlayCSS: window._ovcss
			});
			$.ajax({
				type: "POST",
				url: '<?php echo site_url("rpjmd/cru_strategi"); ?>',
				data: {id_rpjmd: idr, id_tujuan: idt, id_sasaran: ids, id_strategi: $(this).attr("id-st")},
				success: function(msg){
					if (msg != "") {
						$.facebox(msg);
						$.blockUI({
							message: msg.msg,
							timeout: 2000,
							css: window._css,
							overlayCSS: window._ovcss
						});
					};
				}
			});
		});

		$("#strategi td.td-click").click(function(){
			close_all();
			element_strategi = $(this);
			$.blockUI({
				css: window._css,
				overlayCSS: window._ovcss
			});

			var idt = $(this).parent().attr("id-t");
			var idr = $(this).parent().attr("id-r");
			var ids = $(this).parent().attr("id-s");
			var idst = $(this).parent().attr("id-st");
			$.ajax({
				type: "POST",
				url: '<?php echo site_url("rpjmd/get_kebijakan"); ?>',
				data: {id_rpjmd: idr, id_tujuan: idt, id_sasaran: ids, id_strategi: idst},
				success: function(msg){
					if (msg!="") {
						close_program();
						$("#indikator-frame").html(msg);
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

		<table id="strategi" class="table-common" style="width: 100%">
			<thead>
				<tr>
					<th colspan="10">
						Strategi
						<a href="javascript:void(0)" class="icon-plus-sign tbh_strategi" style="float: right" title="Tambah Strategi" id-r="<?php echo $id_rpjmd; ?>" id-t="<?php echo $id_tujuan; ?>" id-s="<?php echo $id_sasaran; ?>"></a>
					</th>
				</tr>
				<tr>
					<th>No</th>
					<th>Strategi</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
			<?php
				if (!empty($strategi)) {
					$i=0;
					foreach ($strategi as $row) {
						$i++;
			?>
				<tr class="tr-click" id-r="<?php echo $row->id_rpjmd; ?>" id-t="<?php echo $row->id_tujuan; ?>" id-s="<?php echo $row->id_sasaran; ?>" id-st="<?php echo $row->id; ?>">
					<td class="td-click" width="50px"><?php echo $i; ?></td>
					<td class="td-click"><?php echo $row->strategi; ?></td>
					<td align="center" width="50px">
					<?php
						if ($enable_edit) {
					?>
						<a href="javascript:void(0)" id-st="<?php echo $row->id; ?>" class="icon-pencil edit-strategi" title="Edit Strategi"/>
					<?php
						}

						if ($enable_delete) {
					?>
						<a href="javascript:void(0)" id-st="<?php echo $row->id; ?>" class="icon-remove remove-strategi" title="Hapus Strategi"/>
					<?php
						}
					?>
					</td>
				</tr>
			<?php
					}
				}else{
			?>
				<tr>
					<td colspan="10" align="center">Tidak ada data...</td>
				</tr>
			<?php
				}
			?>
			</tbody>
		</table>
