<script type="text/javascript">
	$(document).ready(function(){
		$('form#revisi').validate({
			rules: {
				ket : "required"				
			}			
		});
				   
		$(document).on("click", "#kirim", function(){			
			var valid = $("form#revisi").valid();
		    if (valid) {
				$.blockUI({				
					css: window._css,				
					overlayCSS: window._ovcss
				});
				$.ajax({
					type: "POST",
					url: '<?php echo site_url("rpjmd/p_revisi"); ?>',
					data: $("form#revisi").serialize(),
					dataType: "json",
					success: function(msg){
						catch_expired_session2(msg);
						if (msg.success==1) {
							location.reload();
						};
					}
				});
			}
		});		
	});
</script>
<form id="revisi" method="POST" name="revisi">
	<input type="hidden" name="id_skpd" value="<?php echo $id_skpd; ?>" />
	<table class="fcari" width="800px">	
		<tbody>		
			<tr>
				<td width="20%">Keterangan</td>
				<td width="80%">
					<textarea class="common" id="ket" name="ket"></textarea>
				</td>
			</tr>		
		</tbody>	
	</table>	
</form>
<div class="submit_link">
	<input id="kirim" type="button" value="Kirim" />
</div>