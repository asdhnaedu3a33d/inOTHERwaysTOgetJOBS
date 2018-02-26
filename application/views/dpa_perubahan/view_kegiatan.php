<script type="text/javascript">
	$(document).ready(function(){
		$(".cetak-kegiatan").click(function(){
			var id = $(this).attr("idK");
			$.blockUI({
				message: 'Cetak dokumen sedang di proses, mohon ditunggu hingga file terunduh secara otomatis ...',
				css: window._css,
				timeout: 2000,
				overlayCSS: window._ovcss
			});
			var link = "<?php echo site_url("dpa_perubahan/cetak_kegiatan"); ?>/"+id;
			$(location).attr('href',link);
		});

		$(".tbh_kegiatan").click(function(){
			var idr = $(this).attr("id-r");

			prepare_facebox();
			$.ajax({
				type: "POST",
				url: '<?php echo site_url("dpa_perubahan/cru_kegiatan_skpd"); ?>',
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
					url: '<?php echo site_url("dpa_perubahan/delete_kegiatan"); ?>',
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
				url: '<?php echo site_url("dpa_perubahan/cru_kegiatan_skpd"); ?>',
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
				url: '<?php echo site_url("dpa_perubahan/preview_kegiatan_dpa"); ?>',
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

		$("#cetak-kegiatanPilih1").click(function(){

			var idK =  $(this).attr("idK");
			$.blockUI({
				message: 'Cetak dokumen sedang di proses, mohon ditunggu hingga file terunduh secara otomatis ...',
				css: window._css,
				timeout: 2000,
				overlayCSS: window._ovcss
			});

			var link = "<?php echo site_url('dpa_perubahan/cetak_kegiatan');?>/"+idK;
			//alert(link);
			$(location).attr('href',link);
		});

		$("#cetak-kegiatanPilih").click(function(){

			var id = $(this).attr("idK");
			$.blockUI({
				message: 'Cetak dokumen sedang di proses, mohon ditunggu hingga file terunduh secara otomatis ...',
				css: window._css,
				timeout: 2000,
				overlayCSS: window._ovcss
			});
			var link = "<?php echo site_url("dpa_perubahan/cetak_kegiatan"); ?>/"+id;
			$(location).attr('href',link);
		});

	});
</script>
<table id="kegiatan" class="table-common" style="width: 99%">
	<thead>
		<tr>
			<th colspan="10">
				Kegiatan
					<a href="javascript:void(0)" class="icon-plus-sign tbh_kegiatan" style="float: right" title="Tambah Kegiatan"
                    id-r="<?php echo $id; ?>"></a>
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
				$indikator_keg = $this->m_dpa_perubahan->get_indikator_prog_keg($row->id);
				$id= $row->id;
				$parent = $row->parent;
				if($row->id_rka != NULL){
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
				<a href="javascript:void(0)" idK="<?php echo $row->id; ?>" class="icon-pencil edit-kegiatan" title="Edit Kegiatan"/>
				<a href="javascript:void(0)" idK="<?php echo $row->id; ?>" class="icon-remove remove-kegiatan" title="Hapus Kegiatan"/>
				<a href="javascript:void(0)" idK="<?php echo $row->id; ?>" class="cetak-kegiatan" title="Cetak Rincian Kegiatan"><i style="color:black;" class="fa fa-book"></i></a>

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
