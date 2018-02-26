<style type="text/css">
	tr.tr-click:hover{		
		background-color: pink;
	}
	td.td-click{
		cursor: pointer;
	}
</style>
<script type="text/javascript">
	$(document).ready(function(){
		$("#simpan").click(function(){			
			$.blockUI({				
				css: window._css,				
				overlayCSS: window._ovcss
			});
			$.ajax({
				type: "POST",
				url: '<?php echo site_url("rpjmd/do_p_revisi"); ?>',
				data: $("form#revisi").serialize(),
				dataType: "json",
				success: function(msg){					
					if (msg.success==1) {
						$.blockUI({
							message: msg.msg,
							css: window._css,
							timeout: 2000,
							overlayCSS: window._ovcss
						});
						location.reload();
					}
				}
			});
		});

		$(".skpd_setuju").click(function(){
			if ($(this).is(':checked')) {
				$(this).parent().find("#skpd_id").prop("disabled", true);
			}else{
				$(this).parent().find("#skpd_id").prop("disabled", false);
			}
		});
	});
</script>
<article class="module width_full">
 	<header>
 		<h3>
			Revisi RPJMD
		</h3>
 	</header>
 	<div class="module_content">
 		<form id="revisi">
			<table class="table-common" style="width: 99%">
				<thead>
					<tr>					
						<th>No.</th>
						<th>SKPD</th>
						<th>Keterangan</th>
						<th>Pilih</th>
					</tr>
				</thead>
				<tbody>
				<?php			
					$i=0;
					foreach ($revisi as $row) {
						$i++;
				?>
					<tr class="tr-click">
						<td class="td-click" width="50px"><?php echo $i; ?></td>
						<td class="td-click"><?php echo $row->nama_skpd; ?></td>
						<td class="td-click"><?php echo $row->keterangan; ?></td>
						<td align="center" width="50px">
							<input class="skpd_setuju" type="checkbox" name="skpd_setuju[<?php echo $i; ?>]" value="<?php echo $row->id; ?>"/>
							<input id="skpd_id" type="hidden" name="skpd_id[<?php echo $i; ?>]" value="<?php echo $row->id; ?>">
						</td>
					</tr>				
				<?php
					}
				?>
				</tbody>
			</table>
			<input type="hidden" name="action" value="<?php echo $action; ?>" />
		</form>			
 	</div>
 	<footer>
 		<div class="submit_link">
 			<input type="button" id="simpan" value="Simpan" />
 		</div>
 	</footer> 	 	
</article>