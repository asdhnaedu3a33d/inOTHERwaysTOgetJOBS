<script type="text/javascript">
	$(document).ready(function(){
		prepare_chosen();

		$('form#sasaran').validate({
			rules: {
				id_sasaran : "required",
			},
			ignore: ":hidden:not(select)"
		});

		$("#simpan").click(function(){
		    var valid = $("form#sasaran").valid();
		    if (valid) {
		    	element_prioritas.parent().next().hide();
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
						$.blockUI({
							message: msg.msg,
							timeout: 2000,
							css: window._css,
							overlayCSS: window._ovcss
						});
						$.facebox.close();
						element_prioritas.trigger('click');
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
		    echo "Edit Data Sasaran Pembangunan";
		} else{
		    echo "Tambah Data Sasaran Pembangunan";
		}
	?>
	</h3>
	</header>
	<div class="module_content">
		<form action="<?php echo site_url('prioritas_pembangunan_rkpd/save_sasaran');?>" method="POST" name="sasaran" id="sasaran" accept-charset="UTF-8" enctype="multipart/form-data" >
			<input type="hidden" name="id" value="<?php if(!empty($sasaran->id)){echo $sasaran->id;} ?>" />
			<input type="hidden" name="id_rkpd_prioritas" value="<?php if(!empty($prioritas->id)){echo $prioritas->id;} ?>" />
			<table class="fcari" width="100%">
				<tr>
					<td width="20%"><strong>Prioritas Pembangunan</strong></td>
					<td width="80%"><?php echo $prioritas->id_prioritas; ?></td>
				</tr>
				<tr>
					<td width="20%"><strong>Sasaran Pembangunan</strong></td>
					<td width="80%"><?php echo $sasaran_combo; ?></td>
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
