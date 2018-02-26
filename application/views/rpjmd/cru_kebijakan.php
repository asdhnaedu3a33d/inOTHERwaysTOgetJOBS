<script type="text/javascript">
	$(document).ready(function(){

		$('form#kebijakan').validate({
			rules: {
				sasaran : "required",
				kebijakan : "required",
			}
		});

		$("#simpan").click(function(){
			$('#kebijakan_frame .kebijakan_val').each(function () {
			    $(this).rules('add', {
			        required: true
			    });
			});

		    var valid = $("form#kebijakan").valid();
		    if (valid) {
		    	element_strategi.parent().next().hide();
		    	$.blockUI({
					css: window._css,
					overlayCSS: window._ovcss
				});

		    	$.ajax({
					type: "POST",
					url: $("form#kebijakan").attr("action"),
					data: $("form#kebijakan").serialize(),
					dataType: "json",
					success: function(msg){
						if (msg.success==1) {
							$.blockUI({
								message: msg.msg,
								timeout: 2000,
								css: window._css,
								overlayCSS: window._ovcss
							});
							$.facebox.close();
							element_strategi.trigger( "click" );
						};
					}
				});
		    };
		});
	});
</script>
<div style="width: 900px">
	<header>
		<h3>
	<?php
		if (!empty($kebijakan)){
		    echo "Edit Data Arah Kebijakan";
		} else{
		    echo "Input Data Arah Kebijakan";
		}
	?>
	</h3>
	</header>
	<div class="module_content">
		<form action="<?php echo site_url('rpjmd/save_kebijakan');?>" method="POST" name="kebijakan" id="kebijakan" accept-charset="UTF-8" enctype="multipart/form-data" >
			<input type="hidden" name="id_kebijakan" value="<?php if(!empty($kebijakan->id)){echo $kebijakan->id;} ?>" />
			<input type="hidden" name="id_rpjmd" value="<?php echo $id_rpjmd; ?>" />
			<input type="hidden" name="id_tujuan" value="<?php echo $tujuan->id; ?>" />
			<input type="hidden" name="id_sasaran" value="<?php echo $sasaran->id; ?>" />
			<input type="hidden" name="id_strategi" value="<?php echo $strategi->id; ?>" />
			<table class="fcari" width="100%">
				<tr>
					<td width="20%"><strong>Tujuan</strong></td>
					<td width="80%"><?php echo $tujuan->tujuan; ?></td>
				</tr>
				<tr>
					<td><strong>Sasaran</strong></td>
					<td><?php echo $sasaran->sasaran; ?></td>
				</tr>
				<tr>
					<td><strong>Strategi</strong></td>
					<td><?php echo $strategi->strategi; ?></td>
				</tr>
				<tr>
					<td><strong>Arah Kebijakan</strong></td>
					<td><textarea class="common" name="kebijakan"><?php if(!empty($kebijakan->kebijakan)){echo $kebijakan->kebijakan;} ?></textarea></td>
				</tr>
			</table>

		</form>
	</div>
	<footer>
		<div class="submit_link">
  			<input id="simpan" type="button" value="Simpan">
		</div>
	</footer>
</div>
