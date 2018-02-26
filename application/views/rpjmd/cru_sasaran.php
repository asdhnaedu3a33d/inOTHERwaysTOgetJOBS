<script type="text/javascript">
	$(document).ready(function(){
		$('form#sasaran').validate({
			rules: {
				sasaran : "required",
				program : "required",
				pagu_rpjmd : "required",
			}
		});

		$("#simpan").click(function(){
			$('#kebijakan_frame .kebijakan_val').each(function () {
			    $(this).rules('add', {
			        required: true
			    });
			});

		    var valid = $("form#sasaran").valid();
		    if (valid) {

		    	element.parent().next().hide();
		    	$.blockUI({
					css: window._css,
					overlayCSS: window._ovcss
				});

		    	$.ajax({
					type: "POST",
					url: $("form#sasaran").attr("action"),
					data: $("form#sasaran").serialize(),
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
							element.trigger( "click" );
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
		if (!empty($sasaran)){
		    echo "Edit Data RPJMD";
		} else{
		    echo "Input Data RPJMD";
		}
	?>
	</h3>
	</header>
	<div class="module_content">
		<form action="<?php echo site_url('rpjmd/save_sasaran');?>" method="POST" name="sasaran" id="sasaran" accept-charset="UTF-8" enctype="multipart/form-data" >
			<input type="hidden" name="id_sasaran" value="<?php if(!empty($sasaran->id)){echo $sasaran->id;} ?>" />
			<input type="hidden" name="id_rpjmd" value="<?php echo $id_rpjmd; ?>" />
			<input type="hidden" name="id_tujuan" value="<?php echo $tujuan->id; ?>" />
			<table class="fcari" width="100%">
				<tr>
					<td width="20%"><strong>Tujuan</strong></td>
					<td width="80%"><?php echo $tujuan->tujuan; ?></td>
				</tr>
				<tr>
					<td><strong>Sasaran</strong></td>
					<td><textarea class="common" name="sasaran"><?php if(!empty($sasaran->sasaran)){echo $sasaran->sasaran;} ?></textarea></td>
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
