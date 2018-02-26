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
	var element_sasaran;
	$(document).ready(function(){
		$(".remove-sasaran").click(function(){
			if (confirm('Apakah anda yakin untuk menghapus data sasaran ini?')) {
				close_all();
				element.parent().next().hide();
				$.blockUI({
					css: window._css,
					overlayCSS: window._ovcss
				});
				$.ajax({
					type: "POST",
					url: '<?php echo site_url("rpjmd/delete_sasaran"); ?>',
					data: {id_sasaran: $(this).attr("idS")},
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

		$(".edit-sasaran").click(function(){
			close_all();
			var idt = $(this).parent().parent().attr("id-t");
			var idr = $(this).parent().parent().attr("id-r");

			prepare_facebox();
			$.blockUI({
				css: window._css,
				overlayCSS: window._ovcss
			});
			$.ajax({
				type: "POST",
				url: '<?php echo site_url("rpjmd/cru_sasaran"); ?>',
				data: {id_rpjmd: idr, id_tujuan: idt, id_sasaran: $(this).attr("idS")},
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

		$("#sasaran td.td-click").click(function(){
			close_all();
			element_sasaran = $(this);

			$("tr.tr-frame-strategi").hide();
			$.blockUI({
				css: window._css,
				overlayCSS: window._ovcss
			});

			var idt = $(this).parent().attr("id-t");
			var idr = $(this).parent().attr("id-r");
			var ids = $(this).parent().attr("id-s");
			var idp = $(this).parent().attr("id-p");
			$.ajax({
				type: "POST",
				url: '<?php echo site_url("rpjmd/get_strategi"); ?>',
				data: {id_rpjmd: idr, id_tujuan: idt, id_sasaran: ids, id_program : idp},
				success: function(msg){
					if (msg!="") {
						close_program();
						$("#indikator-frame").html(msg);
						if (tab_element == "") {
							$("a[href='#strategi_tab']").trigger('click');
							tab_element = "program";
						}
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
	<thead>
		<tr>
			<th colspan="10">Sasaran</th>
		</tr>
		<tr>
			<th>No</th>
			<th>Sasaran</th>
			<th>Indikator</th>
			<th>Action</th>
		</tr>
	</thead>
	<tbody>
	<?php
		if (!empty($sasaran)) {
			$i=0;
			foreach ($sasaran as $row) {
				$indikator = $this->m_rpjmd_trx->get_indikator_program_per_sasaran($row->id)->result();
				$i++;
	?>
		<tr class="tr-click" id-r="<?php echo $row->id_rpjmd; ?>" id-t="<?php echo $row->id_tujuan; ?>" id-s="<?php echo $row->id; ?>">
			<td class="td-click" width="50px"><?php echo $i; ?></td>
			<td class="td-click"><?php echo $row->sasaran; ?></td>
			<td class="td-click">
			<?php
						$j = 0;
						foreach ($indikator as $row1) {
							$j++;
							echo $j.". ".$row1->indikator."<BR>";
						}
			?>
			</td>
			<td align="center" width="50px">
			<?php
				if ($enable_edit) {
			?>
				<a href="javascript:void(0)" idS="<?php echo $row->id; ?>" class="icon-pencil edit-sasaran" title="Edit Sasaran"/>
			<?php
				}

				if ($enable_delete) {
			?>
				<a href="javascript:void(0)" idS="<?php echo $row->id; ?>" class="icon-remove remove-sasaran" title="Hapus Sasaran"/>
			<?php
				}
			?>
			</td>
		</tr>
		<tr class="tr-frame-strategi" style="display: none">
			<td colspan="4"></td>
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
