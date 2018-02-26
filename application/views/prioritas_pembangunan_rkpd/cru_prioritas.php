<script type="text/javascript">
	$(document).ready(function(){
		prepare_chosen();

		$('form#prioritas').validate({
			rules: {
				id_prioritas : "required",
			},
			ignore: ":hidden:not(select)"
		});

		$("#simpan").click(function(){
		    var valid = $("form#prioritas").valid();
		    if (valid) {
		    	// element.parent().next().hide();
		    	$.blockUI({
					css: window._css,
					overlayCSS: window._ovcss
				});

		    	$.ajax({
					type: "POST",
					url: $("form#prioritas").attr("action"),
					data: $("form#prioritas").serialize(),
					dataType: "json",
					success: function(msg){
						$.blockUI({
							message: msg.msg,
							timeout: 2000,
							css: window._css,
							overlayCSS: window._ovcss
						});
						$.facebox.close();
						location.reload();
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
		if (!empty($prioritas)){
		    echo "Edit Data Prioritas Pembangunan";
		} else{
		    echo "Tambah Data Prioritas Pembangunan";
		}
	?>
	</h3>
	</header>
	<div class="module_content">
		<form action="<?php echo site_url('prioritas_pembangunan_rkpd/save_prioritas');?>" method="POST" name="prioritas" id="prioritas" accept-charset="UTF-8" enctype="multipart/form-data" >
			<input type="hidden" name="id" value="<?php if(!empty($prioritas->id)){echo $prioritas->id;} ?>" />
			<table class="fcari" width="100%">
				<tr>
					<td width="20%"><strong>Prioritas Pembangunan</strong></td>
					<td width="80%">
						<!-- <?php echo $prioritas_combo; ?> -->
						<input type="text" name="id_prioritas" id="id_prioritas" class="common" value="<?php if(!empty($prioritas->id_prioritas)){echo $prioritas->id_prioritas;} ?>">
					</td>
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
