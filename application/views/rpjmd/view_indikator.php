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
	var element_indikator;
	$(document).ready(function(){
		$(".tbh_indikator").click(function(){
			var ids = $(this).attr("id-s");
			var idr = $(this).attr("id-r");

			prepare_facebox();
			$.ajax({
				type: "POST",
				url: '<?php echo site_url("rpjmd/cru_indikator"); ?>',
				data: {id_rpjmd: idr, id_sasaran: ids},
				success: function(msg){
					if (msg!="") {						
						$.facebox(msg);
					};	
				}
			});			
		});

		$(".remove-indikator").click(function(){			
			if (confirm('Apakah anda yakin untuk menghapus data indikator ini?')) {				
				$.blockUI({
					css: window._css,
					overlayCSS: window._ovcss
				});
				$.ajax({
					type: "POST",
					url: '<?php echo site_url("rpjmd/delete_indikator"); ?>',
					data: {id_indikator: $(this).attr("idI")},
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
						};	
					}
				});
			}
		});

		$(".edit-indikator").click(function(){
			var ids = $(this).parent().parent().attr("id-s");
			var idr = $(this).parent().parent().attr("id-r");

			prepare_facebox();
			$.blockUI({
				css: window._css,
				overlayCSS: window._ovcss
			});
			$.ajax({
				type: "POST",
				url: '<?php echo site_url("rpjmd/cru_indikator"); ?>',
				data: {id_rpjmd: idr, id_sasaran: ids, id_indikator: $(this).attr("idI")},
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

		$("#indikator td.td-click").click(function(){
			element_indikator = $(this);
			$.blockUI({
				css: window._css,
				overlayCSS: window._ovcss
			});
			
			var idr = $(this).parent().attr("id-r");
			var ids = $(this).parent().attr("id-s");			
			var idi = $(this).parent().attr("id-i");
			$.ajax({
				type: "POST",
				url: '<?php echo site_url("rpjmd/get_program"); ?>',
				data: {id_rpjmd: idr, id_indikator: idi, id_sasaran: ids},				
				success: function(msg){
					if (msg!="") {						
						$("#kegiatan-frame").html(msg);
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
			Indikator
			<a href="javascript:void(0)" class="icon-remove-sign close-indikator-frame" title="Tutup Layar Indikator"></a>
		</h3>			
 	</header>
 	<div class="module_content">
		<table id="indikator" class="table-common" style="width: 99%">
			<thead>
				<tr>
					<th colspan="11">
						Indikator
						<?php
							if ($enable_add) {
						?>
							<a href="javascript:void(0)" class="icon-plus-sign tbh_indikator" style="float: right" title="Tambah Indikator" id-r="<?php echo $id_rpjmd; ?>" id-s="<?php echo $id_sasaran; ?>"></a>
						<?php
							}
						?>
					</th>
				</tr>
				<tr>
					<th>No</th>					
					<th>Indikator</th>
					<th>Kondisi Awal</th>					
					<th>Target Akhir</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
			<?php
				if (!empty($indikator)) {					
					$i=0;
					foreach ($indikator as $row) {
						$i++;
			?>
				<tr class="tr-click" id-r="<?php echo $row->id_rpjmd; ?>" id-s="<?php echo $row->id_sasaran; ?>" id-i="<?php echo $row->id; ?>">
					<td class="td-click" width="50px"><?php echo $i; ?></td>					
					<td class="td-click"><?php echo $row->indikator; ?></td>
					<td class="td-click"><?php echo $row->kondisi_awal." ".$row->nama_value; ?></td>					
					<td align="center" class="td-click"><?php echo $row->kondisi_akhir." ".$row->nama_value; ?></td>
					<td align="center" width="50px">
					<?php
						if ($enable_edit) {
					?>
						<a href="javascript:void(0)" idI="<?php echo $row->id; ?>" class="icon-pencil edit-indikator" title="Edit Indikator"/>
					<?php
						}

						if ($enable_delete) {
					?>
						<a href="javascript:void(0)" idI="<?php echo $row->id; ?>" class="icon-remove remove-indikator" title="Hapus Indikator"/>
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
					<td colspan="11" align="center">Tidak ada data...</td>
				</tr>
			<?php
				}
			?>
			</tbody>
		</table>
	</div>
</article>