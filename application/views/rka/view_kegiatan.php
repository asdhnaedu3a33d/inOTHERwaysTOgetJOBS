<?php
	if (!empty($jendela_kontrol->baru) || !empty($jendela_kontrol->revisi) || (empty($jendela_kontrol->baru)
	    && empty($jendela_kontrol->revisi) && empty($jendela_kontrol->kirim))) {
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
	$(document).ready(function(){
		$(".cetak-kegiatan").click(function(){
			prepare_facebox();
			$.blockUI({
				css: window._css,
				overlayCSS: window._ovcss
			});
			$.ajax({
				type: "POST",
				url: '<?php echo site_url("rka/preview_periode_221"); ?>',
				data: {id: $(this).attr("idK")},
				success: function(msg){
					if (msg!="") {
						$.facebox(msg);
						$.blockUI({
							timeout: 500,
							css: window._css,
							overlayCSS: window._ovcss
						});
					};
				}
			});
		});
		$(".tbh_kegiatan").click(function(){
			var idr = $(this).attr("id-r");

			prepare_facebox();
			$.ajax({
				type: "POST",
				url: '<?php echo site_url("rka/cru_kegiatan_skpd"); ?>',
				data: {id_program: idr},
				success: function(msg){
					if (msg!="") {
						$.facebox(msg);
					};
				}
			});
		});

		$(".remove-kegiatan").click(function(){
			if (confirm('Apakah anda yakin untuk menghapus data kegiatan ini?')) {
				element_program.parent().next().hide();
				$.blockUI({
					css: window._css,
					overlayCSS: window._ovcss
				});
				$.ajax({
					type: "POST",
					url: '<?php echo site_url("rka/delete_kegiatan"); ?>',
					data: {id: $(this).attr("idK")},
					dataType: "json",
					success: function(msg){
						if (msg.success==1) {
							element_program.trigger( "click" );
							$.blockUI({
								message: msg.msg,
								timeout: 2000,
								css: window._css,
								overlayCSS: window._ovcss
							});
							reload_jendela_kontrol();
						};
					}
				});
			}
		});

		$(".edit-kegiatan").click(function(){
			var idp = $(this).parent().parent().attr("id-p");

			prepare_facebox();
			$.blockUI({
				css: window._css,
				overlayCSS: window._ovcss
			});
			$.ajax({
				type: "POST",
				url: '<?php echo site_url("rka/cru_kegiatan_skpd"); ?>',
				data: {id_program: idp, id: $(this).attr("idK")},
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

		$("#kegiatan td.td-click").click(function(){
			prepare_facebox();
			$.blockUI({
				css: window._css,
				overlayCSS: window._ovcss
			});
			$.ajax({
				type: "POST",
				url: '<?php echo site_url("rka/preview_kegiatan_renja"); ?>',
				data: {id: $(this).parent().attr("id-k")},
				success: function(msg){
					if (msg!="") {
						$.facebox(msg);
						$.blockUI({
							timeout: 500,
							css: window._css,
							overlayCSS: window._ovcss
						});
					};
				}
			});
		});
	});
</script>
<table id="kegiatan" class="table-common" style="width: 99%">
	<thead>
		<tr>
			<th colspan="10">
				Kegiatan
                <?php
					if ($enable_add) {
				?>
					<a href="javascript:void(0)" class="icon-plus-sign tbh_kegiatan" style="float: right" title="Tambah Kegiatan"
                    id-r="<?php echo $id; ?>"></a>
                <?php
					}
				?>
			</th>
		</tr>
		<tr>
			<th width="25px">No</th>
			<th width="80px">Kode</th>
			<th>Kegiatan</th>
			<th>Indikator</th>
			<th>Action</th>
		</tr>
	</thead>
	<tbody>
	<?php
		if (!empty($kegiatan)) {
			$i=0;
			foreach ($kegiatan as $row) {
				$i++;
				$style='';
				$indikator_keg = $this->m_rka->get_indikator_prog_keg($row->id);
				$id= $row->id;
				$parent = $row->parent;
				if($row->id_renja != NULL && $row->id_renja != 0){
						$style='bgcolor="#D0BE2D"';
					} else {
						$style='bgcolor="#96CC3F"';
					}
	?>
		<tr class="tr-click" id-r="<?php echo $row->id ?>" id-p="<?php echo $parent; ?>" id-k="<?php echo $row->id; ?>">
			<td class="td-click" width="50px"><?php echo $i; ?></td>
			<td class="td-click" <?php echo $style;?>><?php echo $row->kd_urusan.". ".$row->kd_bidang.". ".$row->kd_program.". ".$row->kd_kegiatan; ?></td>
			<td class="td-click"><?php echo $row->nama_prog_or_keg; ?></td>
			<td class="td-click">
	<?php
				$j = 0;
				foreach ($indikator_keg as $row1) {
					$j++;
					echo $j.". ".$row1->indikator."<BR>";
				}
	?>
			</td>
			<td align="center" width="50px">
            <?php
				if ($enable_edit) {
			?>
				<a href="javascript:void(0)" idK="<?php echo $row->id; ?>" class="icon-pencil edit-kegiatan" title="Edit Kegiatan"/>
             <?php
				}

				if ($enable_delete) {
			?>
				<a href="javascript:void(0)" idK="<?php echo $row->id; ?>" class="icon-remove remove-kegiatan" title="Hapus Kegiatan"/>
            <?php
				}
			?>
			<a href="javascript:void(0)" idK="<?php echo $row->id; ?>" class="cetak-kegiatan" title="Cetak Rincian Kegiatan"> <i style="color:black;" class="fa fa-book"></i></a>
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
