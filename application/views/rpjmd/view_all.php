<?php		
	if (empty($rpjmd)) {
		$enable_add = TRUE;
	}else{
		$enable_add = FALSE;
	}
?>
<script type="text/javascript">
	$(document).ready(function(){
		$("#buat_rpjmd").click(function(){
			window.location = '<?php echo site_url("rpjmd/cru_rpjmd")?>';
		});

		$(".edit").click(function(){
			id_r = $(this).attr("idR");
			window.location = '<?php echo site_url("rpjmd/cru_rpjmd")?>/'+id_r;
		});

		$(".proses").click(function(){
			id_r = $(this).attr("idR");
			window.location = '<?php echo site_url("rpjmd/det_rpjmd")?>/'+id_r;
		});

		$(".remove").click(function(){			
			if (confirm('Apakah anda yakin untuk menghapus data kegiatan ini?')) {
				$.blockUI({
					css: window._css,
					overlayCSS: window._ovcss
				});
				$.ajax({
					type: "POST",
					url: '<?php echo site_url("rpjmd/delete_rpjmd"); ?>',
					data: {id: $(this).attr("idR")},
					dataType: "json",
					success: function(msg){
						if (msg.success==1) {							
							location.reload();
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
<article class="module width_full">
 	<header>
 		<h3>
			List RPJMD
		</h3>
 	</header>
 	<div class="module_content">
 		<?php
			if ($enable_add) {
		?>
			<input id="buat_rpjmd" style="float: right; margin: 10px;" type="button" value="Buat RPJMD" />
		<?php
			}
		?>		
		<table class="table-common" style="width: 99%">
			<thead>
				<tr>
					<th>No</th>
					<th>Visi</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
			<?php
				$i=0;
				foreach ($rpjmd as $row) {
					$i++;
			?>
				<tr>
					<td width="50px"><?php echo $i; ?></td>
					<td><?php echo $row->visi; ?></td>
					<td align="center" width="50px">
						<a idR="<?php echo $row->id; ?>" href="javascript:void(0)" class="icon-file proses" title="Proses RPJMD"/>
						<a idR="<?php echo $row->id; ?>" href="javascript:void(0)" class="icon-edit edit" title="Edit RPJMD"/>
						<a idR="<?php echo $row->id; ?>" href="javascript:void(0)" class="icon-remove remove" title="Delete RPJMD"/>						
					</td>
				</tr>				
			<?php
				}
			?>
			</tbody>
		</table>
 	</div> 	 	
</article>