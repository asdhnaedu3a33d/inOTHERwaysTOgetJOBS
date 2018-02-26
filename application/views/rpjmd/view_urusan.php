<?php
	if (TRUE) {
		$enable_edit = TRUE;
		$enable_delete = TRUE;
	}else{
		$enable_edit = FALSE;
		$enable_delete = FALSE;
	}
?>
<script>
$(document).ready(function(){
	var element_urusan;
	$(".tbh_urusan").click(function(){
		var idr = $(this).attr("id-r");
		var idt = $(this).attr("id-t");
		var ids = $(this).attr("id-s");
		var idp = $(this).attr("id-p");

		prepare_facebox();
		$.ajax({
			type: "POST",
			url: '<?php echo site_url("rpjmd/cru_urusan"); ?>',
			data: {id_rpjmd: idr, id_tujuan: idt, id_sasaran: ids, id_program : idp},
			success: function(msg){
				if (msg!="") {
					$.facebox(msg);
				};
			}
		});
	});

	$(".edit-urusan").click(function(){
		var idt = $(this).parent().parent().attr("id-t");
		var idr = $(this).parent().parent().attr("id-r");
		var ids = $(this).parent().parent().attr("id-s");
		var idp = $(this).parent().parent().attr("id-p");

		prepare_facebox();
		$.blockUI({
			css: window._css,
			overlayCSS: window._ovcss
		});
		$.ajax({
			type: "POST",
			url: '<?php echo site_url("rpjmd/cru_urusan"); ?>',
			data: {id_rpjmd: idr, id_tujuan: idt, id_sasaran: ids, id_program : idp, id_urusan: $(this).attr("id-ur")},
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

	$(".remove-urusan").click(function(){
		if (confirm('Apakah anda yakin untuk menghapus data urusan ini?')) {
			//close_all();
			element_program.parent().next().hide();
			$.blockUI({
				css: window._css,
				overlayCSS: window._ovcss
			});
			$.ajax({
				type: "POST",
				url: '<?php echo site_url("rpjmd/delete_urusan"); ?>',
				data: {id_urusan: $(this).attr("id-ur")},
				dataType: "json",
				success: function(msg){
					if (msg.success==1) {
						tab_element = "program";
						element_program.trigger( "click" );
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
});
</script>

<table id="urusan" class="table-common" style="width: 100%">
	<thead>
		<tr>
			<th colspan="10">
				Urusan
				<a href="javascript:void(0)" class="icon-plus-sign tbh_urusan" style="float: right" title="Tambah Urusan" id-r="<?php echo $id_rpjmd; ?>" id-t="<?php echo $id_tujuan; ?>" id-s="<?php echo $id_sasaran; ?>" id-p="<?php echo $id_prog; ?>"></a>
			</th>
		</tr>
		<tr>
			<th>No</th>
			<th>Bidang</th>
			<th>SKPD</th>
			<th>Action</th>
		</tr>
	</thead>
	<tbody>
	<?php
		if (!empty($urusan)) {
			$i=0;
			foreach ($urusan as $row2) {
				$skpd_bidang = $this->m_rpjmd_trx->get_all_skpd_bidang($row2->id);
				$i++;
	?>
		<tr class="tr-click" id-r="<?php echo $row2->id_rpjmd; ?>" id-t="<?php echo $row2->id_tujuan; ?>" id-s="<?php echo $row2->id_sasaran; ?>" id-p="<?php echo $row2->id_prog; ?>">
			<td class="td-click" width="50px"><?php echo $i; ?></td>
			<td class="td-click"><?php echo $row2->Nm_Bidang; ?></td>
			<td class="td-click">
			<?php
						$j = 0;
						foreach ($skpd_bidang as $row3) {
							$j++;
							echo $j.". ".$row3->nama_skpd."<BR>";
						}
			?>
			</td>
			<td align="center" width="50px">
			<?php
				if ($enable_edit) {
			?>
				<a href="javascript:void(0)" id-ur="<?php echo $row2->id; ?>" class="icon-pencil edit-urusan" title="Edit Urusan"/>
			<?php
				}

				if ($enable_delete) {
			?>
				<a href="javascript:void(0)" id-ur="<?php echo $row2->id; ?>" class="icon-remove remove-urusan" title="Hapus Urusan"/>
			<?php
				}
			?>
			</td>
		</tr>
		<tr class="tr-frame-urusan" style="display: none">
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
