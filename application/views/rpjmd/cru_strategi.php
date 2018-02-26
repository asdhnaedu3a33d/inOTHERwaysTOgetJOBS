<script type="text/javascript">
	$(document).ready(function(){
		prepare_chosen();

		$('form#strategi').validate({
			rules: {
				sasaran : "required",
				strategi : "required",
				id_program : "required",
				cmb_program : "required",
				id_program_chosen : "required",
			}
		});

		$("#simpan").click(function(){
			$('#kebijakan_frame .kebijakan_val').each(function () {
			    $(this).rules('add', {
			        required: true
			    });
			});

		    var valid = $("form#strategi").valid();
		    if (valid) {
		    	element_sasaran.parent().next().hide();
		    	$.blockUI({
					css: window._css,
					overlayCSS: window._ovcss
				});

		    	$.ajax({
					type: "POST",
					url: $("form#strategi").attr("action"),
					data: $("form#strategi").serialize(),
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
							element_sasaran.trigger( "click" );
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
		if (!empty($strategi)){
		    echo "Edit Data Strategi";
		} else{
		    echo "Input Data Strategi";
		}
	?>
	</h3>
	</header>
	<div class="module_content">
		<form action="<?php echo site_url('rpjmd/save_strategi');?>" method="POST" name="strategi" id="strategi" accept-charset="UTF-8" enctype="multipart/form-data" >
			<input type="hidden" name="id_strategi" value="<?php if(!empty($strategi->id)){echo $strategi->id;} ?>" />
			<input type="hidden" name="id_rpjmd" value="<?php echo $id_rpjmd; ?>" />
			<input type="hidden" name="id_tujuan" value="<?php echo $tujuan->id; ?>" />
			<input type="hidden" name="id_sasaran" value="<?php echo $sasaran->id; ?>" />
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
					<td><strong>Program</strong></td>
					<td><?php echo $cmb_program; ?></td>
				</tr>
				<tr>
					<td><strong>Strategi</strong></td>
					<td><textarea class="common" rows="4" name="strategi"><?php if(!empty($strategi->strategi)){echo $strategi->strategi;} ?></textarea></td>
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
