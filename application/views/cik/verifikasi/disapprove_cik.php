<script type="text/javascript">
	$(document).ready(function(){
		$('form#disapprove_cik').validate({
			rules: {
				ket : "required"				
			}			
		});
				   
		$(document).on("click", "#kirim", function(){			
			var valid = $("form#disapprove_cik").valid();
		    if (valid) {
				$.blockUI({				
					css: window._css,				
					overlayCSS: window._ovcss
				});
				$.ajax({
					type: "POST",
					url: '<?php echo site_url("cik/do_disapprove_cik"); ?>',
					data: $("form#disapprove_cik").serialize(),
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
<form id="disapprove_cik" method="POST" name="disapprove_cik">
	<input type="hidden" name="id" value="<?php echo $id; ?>">
    <input type="hidden" name="bulan" value="<?php echo $bulan; ?>">
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