<script type="text/javascript">
	$(document).ready(function(){
		$('form#disapprove_renstra').validate({
			rules: {
				ket : "required"				
			}			
		});
				   
		$(document).on("click", "#kirim", function(){			
			var valid = $("form#disapprove_renstra").valid();
		    if (valid) {
				$.blockUI({				
					css: window._css,				
					overlayCSS: window._ovcss
				});
				$.ajax({
					type: "POST",
					url: '<?php echo site_url("renstra/do_disapprove_renstra"); ?>',
					data: $("form#disapprove_renstra").serialize(),
					dataType: "json",
					success: function(msg){
						catch_expired_session2(msg);
						if (msg.success==1) {
							$(location).attr('href', msg.href)
						};
					}
				});
			}
		});		
	});
</script>
<form id="disapprove_renstra" method="POST" name="disapprove_renstra">
	<input type="hidden" name="id_renstra" value="<?Php echo $id_renstra; ?>">
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