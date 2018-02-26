<script type="text/javascript">
	$(document).ready(function(){
		$('form#disapprove_renja').validate({
			rules: {
				ket : "required"				
			}			
		});
				   
		$(document).on("click", "#kirim", function(){			
			var valid = $("form#disapprove_renja").valid();
		    if (valid) {
				$.blockUI({				
					css: window._css,				
					overlayCSS: window._ovcss
				});
				$.ajax({
					type: "POST",
					url: '<?php echo site_url("kendali_perubahan/do_disapprove_renja"); ?>',
					data: $("form#disapprove_renja").serialize(),
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
<form id="disapprove_renja" method="POST" name="disapprove_renja">
	<input type="hidden" name="id" value="<?Php echo $id; ?>">
	<table class="fcari" width="800px">	
		<tbody>		
			<tr><td>&nbsp;</td></tr>
            <tr>
				<td width="80%" align="center">
                <p>Apakah Anda Yakin Untuk Tidak Menyetujui Keseluruhan Kendali Renja Perubahan Ini?</p>
				</td>
			</tr>
            <tr><td>&nbsp;</td></tr>		
		</tbody>	
	</table>
</form>
<div class="submit_link">
	<input id="kirim" type="button" value="Kirim" />
</div>