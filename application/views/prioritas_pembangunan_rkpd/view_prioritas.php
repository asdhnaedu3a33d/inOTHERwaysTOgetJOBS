<style type="text/css">
	.table-common, .table-display{
			border: 2px solid #000;
	}

	.table-common>thead>tr>th, .table-common>tbody>tr>th, .table-common>tfoot>tr>th, .table-common>thead>tr>td, .table-common>tbody>tr>td, .table-common>tfoot>tr>td ,
	.table-display>thead>tr>th, .table-display>tbody>tr>th, .table-display>tfoot>tr>th, .table-display>thead>tr>td, .table-display>tbody>tr>td, .table-display>tfoot>tr>td
	{
			border: 1px solid #000 !important;
	}
	tr.tr-click:hover{
		background-color: pink;
	}
	td.td-click{
		cursor: pointer;
	}
</style>

<script type="text/javascript">
	var element_prioritas;
	$(document).ready(function() {

		$(".tbh_prioritas").click(function(){
			close_all();
			prepare_facebox();
			$.ajax({
				type: "POST",
				url: '<?php echo site_url("prioritas_pembangunan_rkpd/cru_prioritas"); ?>',
				data: {},
				success: function(msg){
					if (msg!="") {
						$.facebox(msg);
					}
				}
			});
		});

		$(".edit_prioritas").click(function(){
			var idpi = $(this).attr("id-pi");
			close_all();
			prepare_facebox();
			$.ajax({
				type: "POST",
				url: '<?php echo site_url("prioritas_pembangunan_rkpd/cru_prioritas"); ?>',
				data: {idpi : idpi},
				success: function(msg){
					if (msg!="") {
						$.facebox(msg);
					}
				}
			});
		});

		$(".delete_prioritas").click(function(){
			if (confirm('Apakah anda yakin untuk menghapus data prioritas ini?')) {
				var idpi = $(this).attr("id-pi");
				$.blockUI({
					css: window._css,
					overlayCSS: window._ovcss
				});

				$.ajax({
					type: "POST",
					url: '<?php echo site_url("prioritas_pembangunan_rkpd/delete_prioritas"); ?>',
					data: {idpi : idpi},
					success: function(msg){
						if (msg!="") {
							$.blockUI({
								message: msg.msg,
								timeout: 2000,
								css: window._css,
								overlayCSS: window._ovcss
							});
							location.reload();
						}
					}
				});
			}
		});

		
		$("#prioritas td.td-click").click(function(){
			close_all();
			if($(this).parent().next().is(":visible")){
				$(this).parent().next().fadeOut();
				return false;
			};

			$("tr.tr-frame-sasaran").hide();
			$.blockUI({
				css: window._css,
				overlayCSS: window._ovcss
			});

			element_prioritas = $(this).parent();
			var idpi = $(this).parent().attr("id-pi");
			var this_element = $(this);
			$.ajax({
				type: "POST",
				url: '<?php echo site_url("prioritas_pembangunan_rkpd/view_sasaran"); ?>',
				data: {idpi : idpi},
				success: function(msg){
					if (msg!="") {
						element_prioritas.next().children().html(msg);
						element_prioritas.next().fadeIn();
						element_prioritas = this_element;
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

	function close_all(){
		$("#program-frame article").remove();
	}
</script>

<article class="module width_full">
 	<header>
 		<h3>
			Prioritas Pembangunan RKPD
		</h3>
 	</header>
 	<div class="module_content">
		<table id="prioritas" class="table-common" style="width: 100%">
			<tr>
				<th colspan="3">
					Prioritas Pembangunan Daerah
					<a href="javascript:void(0)" class="icon-plus-sign tbh_prioritas" style="float: right" title="Tambah Prioritas"></a>
				</th>
			</tr>
			<tr>
				<th width="40px">No</th>
				<th>Prioritas</th>
				<th width="70px">Action</th>
			</tr>
			<?php if (!empty($prioritas)): ?>
				<?php foreach ($prioritas as $key => $value): ?>
					<tr class="tr-click" id-pi="<?php echo $value->id_prio; ?>">
						<td class="td-click"><?php echo ($key+1).'.'; ?></td>
						<td class="td-click"><?php echo $value->id_prioritas; ?></td>
						<td align="center">
							<a href="javascript:void(0)" id-pi="<?php echo $value->id_prio; ?>" class="icon-pencil edit_prioritas" title="Edit Prioritas Pembangunan"/>
							<a href="javascript:void(0)" id-pi="<?php echo $value->id_prio; ?>" class="icon-remove delete_prioritas" title="Hapus Prioritas Pembangunan"/>
						</td>
					</tr>
					<tr class="tr-frame-sasaran" style="display: none">
						<td colspan="3"></td>
					</tr>
				<?php endforeach ?>
			<?php else: ?>
				<tr>
					<td colspan="3" align="center"><strong>Tidak Ada Data</strong></td>
				</tr>
			<?php endif ?>
		</table>
	</div>
</article>

<div id="program-frame">
</div>