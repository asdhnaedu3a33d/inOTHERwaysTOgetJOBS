<?php
	if (!empty($jendela_kontrol->baru) || !empty($jendela_kontrol->revisi) || (empty($jendela_kontrol->baru) && empty($jendela_kontrol->revisi) && empty($jendela_kontrol->kirim))) {
		$enable_add = TRUE;
		$enable_edit = TRUE;
		$enable_delete = TRUE;
	}elseif (!empty($jendela_kontrol->baru2) || !empty($jendela_kontrol->revisi2)) {
		$enable_add = FALSE;
		$enable_edit = TRUE;
		$enable_delete = FALSE;
	}else{
		$enable_add = FALSE;
		$enable_edit = FALSE;
		$enable_delete = FALSE;
	}
?>
<script type="text/javascript">
	var element_program;
	$(document).ready(function(){
		$(".tbh_program").click(function(){
			var ids = $(this).attr("id-s");
			var idr = $(this).attr("id-r");

			prepare_facebox();
			$.ajax({
				type: "POST",
				url: '<?php echo site_url("renstra/cru_program_skpd"); ?>',
				data: {id_renstra: idr, id_sasaran: ids},
				success: function(msg){
					if (msg!="") {
						$.facebox(msg);
					};
				}
			});
		});

		$(".remove-program").click(function(){
			if (confirm('Apakah anda yakin untuk menghapus data program ini?')) {
				$.blockUI({
					css: window._css,
					overlayCSS: window._ovcss
				});
				$.ajax({
					type: "POST",
					url: '<?php echo site_url("renstra/delete_program"); ?>',
					data: {id_program: $(this).attr("idP")},
					dataType: "json",
					success: function(msg){
						if (msg.success==1) {
							element_sasaran.trigger( "click" );
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

		$(".edit-program").click(function(){
			var ids = $(this).parent().parent().attr("id-s");
			var idr = $(this).parent().parent().attr("id-r");

			prepare_facebox();
			$.blockUI({
				css: window._css,
				overlayCSS: window._ovcss
			});
			$.ajax({
				type: "POST",
				url: '<?php echo site_url("renstra/cru_program_skpd"); ?>',
				data: {id_renstra: idr, id_sasaran: ids, id_program: $(this).attr("idP")},
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

		$("#program td.td-click").click(function(){
			element_program = $(this);
			if($(this).parent().next().is(":visible")){
				$(this).parent().next().fadeOut();
				return false;
			};

			$("tr.tr-frame-pro").hide();
			$.blockUI({
				css: window._css,
				overlayCSS: window._ovcss
			});

			var idr = $(this).parent().attr("id-r");
			var ids = $(this).parent().attr("id-s");
			var idp = $(this).parent().attr("id-p");
			$.ajax({
				type: "POST",
				url: '<?php echo site_url("renstra/get_kegiatan_skpd"); ?>',
				data: {id_renstra: idr, id_program: idp, id_sasaran: ids},
				success: function(msg){
					if (msg!="") {
						element_program.parent().next().children().html(msg);
						element_program.parent().next().fadeIn();
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
<article class="module width_full">
	<header>
 		<h3>
			Program
			<a href="javascript:void(0)" class="icon-remove-sign close-program-frame" title="Tutup Layar Program"></a>
		</h3>
 	</header>
 	<div class="module_content">
		<table id="program" class="table-common" style="width: 99%">
			<thead>
				<tr>
					<th colspan="11">
						Program
						<?php
							if ($enable_add) {
						?>
							<a href="javascript:void(0)" class="icon-plus-sign tbh_program" style="float: right" title="Tambah Program" id-r="<?php echo $id_renstra; ?>" id-s="<?php echo $id_sasaran; ?>"></a>
						<?php
							}
						?>
					</th>
				</tr>
				<tr>
					<th>No</th>
					<th>Kode</th>
					<th>Program</th>
					<th>Indikator</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
			<?php
				if (!empty($program)) {
					$i=0;
					foreach ($program as $row) {
						$indikator_program = $this->m_renstra_trx->get_indikator_prog_keg($row->id);
						$i++;
			?>
				<tr class="tr-click" id-r="<?php echo $row->id_renstra; ?>" id-s="<?php echo $row->id_sasaran; ?>" id-p="<?php echo $row->id; ?>">
					<td class="td-click" width="50px"><?php echo $i; ?></td>
					<td class="td-click"><?php echo $row->kd_urusan.". ".$row->kd_bidang.". ".$row->kd_program; ?></td>
					<td class="td-click"><?php echo $row->nama_prog_or_keg; ?></td>
					<td class="td-click">
			<?php
						$j = 0;
						foreach ($indikator_program as $row1) {
							$j++;
							echo $j.". ".$row1->indikator."<BR>";
						}
			?>
					</td>
					<td align="center" width="50px">
					<?php
						if ($enable_edit && ($row->id_status != 4 || $row->id_status != 7)) {
					?>
						<a href="javascript:void(0)" idP="<?php echo $row->id; ?>" class="icon-pencil edit-program" title="Edit Program"/>
					<?php
						}

						if ($enable_delete && ($row->id_status != 4 || $row->id_status != 7)) {
					?>
						<a href="javascript:void(0)" idP="<?php echo $row->id; ?>" class="icon-remove remove-program" title="Hapus Program"/>
					<?php
						}
					?>
					</td>
				</tr>
				<tr class="tr-frame-pro" id="kegiatan-frame" style="display: none">
					<td colspan="5"></td>
				</tr>
			<?php
					}
				}else{
			?>
				<tr>
					<td colspan="5" align="center">Tidak ada data...</td>
				</tr>
			<?php
				}
			?>
			</tbody>
		</table>
	</div>
</article>
