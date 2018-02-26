<?php
	if (!empty($program)) {
		$enable_add = TRUE;
		$enable_delete = TRUE;
	}else{
		$enable_add = TRUE;		
		$enable_delete = FALSE;
	}
?>
<script type="text/javascript">	
	$(document).ready(function(){
		$(".tbh_program").click(function(){
			var ids = $(this).attr("id-s");
			var idr = $(this).attr("id-r");
			var idi = $(this).attr("id-i");

			prepare_facebox();
			$.ajax({
				type: "POST",
				url: '<?php echo site_url("rpjmd/pilih_program"); ?>',
				data: {id_rpjmd: idr, id_sasaran: ids, id_indikator: idi},
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
					url: '<?php echo site_url("rpjmd/delete_program"); ?>',
					data: {id_program: $(this).attr("idP")},
					dataType: "json",
					success: function(msg){
						if (msg.success==1) {							
							$.blockUI({
								message: msg.msg,
								timeout: 2000,
								css: window._css,
								overlayCSS: window._ovcss
							});
							element_indikator.trigger( "click" );					
						};	
					}
				});
			}
		});		

		$("#program td.td-click").click(function(){
			prepare_facebox();
			$.blockUI({
				css: window._css,
				overlayCSS: window._ovcss
			});
			$.ajax({
				type: "POST",
				url: '<?php echo site_url("rpjmd/preview_program_rpjmd"); ?>',
				data: {id: $(this).parent().attr("id-p")},
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
					<th colspan="10">
						Program
						<?php
							if ($enable_add) {
						?>
							<a href="javascript:void(0)" class="icon-plus-sign tbh_program" style="float: right" title="Tambah Program" id-r="<?php echo $id_rpjmd; ?>" id-s="<?php echo $id_sasaran; ?>" id-i="<?php echo $id_indikator; ?>"></a>
						<?php
							}
						?>						
					</th>
				</tr>
				<tr>
					<th width="25px">No</th>
					<th width="80px">Kode</th>
					<th>Program</th>
					<th>Bidang Urusan</th>
					<th>SKPD Penanggung Jawab</th>					
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
			<?php
				if (!empty($program)) {					
					$i=0;
					foreach ($program as $row) {
						$i++;
			?>
				<tr class="tr-click" id-p="<?php echo $row->id_program_rpjmd; ?>">
					<td class="td-click" width="50px"><?php echo $i; ?></td>
					<td class="td-click"><?php echo $row->kd_urusan.". ".$row->kd_bidang.". ".$row->kd_program; ?></td>
					<td class="td-click"><?php echo $row->nama_prog_or_keg; ?></td>
					<td class="td-click"><?php echo $row->Nm_Bidang; ?></td>
					<td class="td-click"><?php echo $row->nama_skpd; ?></td>
					<td align="center" width="50px">
					<?php						
						if ($enable_delete) {
					?>
						<a href="javascript:void(0)" idP="<?php echo $row->id_program_rpjmd; ?>" class="icon-remove remove-program" title="Hapus Program"/>
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
	</div>
</article>