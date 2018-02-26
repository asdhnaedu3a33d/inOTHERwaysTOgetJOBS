<script>
	$(document).ready(function(){
		prepare_chosen();
		$(document).on("change", "#kd_urusan", function () {
			$.ajax({
				type: "POST",
				url: '<?php echo site_url("common/cmb_bidang"); ?>',
				data: {kd_urusan: $(this).val()},
				success: function(msg){
					$("#cmb-bidang").html(msg);
					$("#multi-skpd").html('');
					prepare_chosen();
				}
			});
		});

		$(document).on("change", "#kd_bidang", function () {
			$.ajax({
				type: "POST",
				url: '<?php echo site_url("common/multi_skpd_bidang"); ?>',
				data: {kd_urusan:$("#kd_urusan").val(), kd_bidang: $(this).val()},
				success: function(msg){
					$("#multi-skpd").html(msg);
					prepare_chosen();
				}
			});
		});

		$('form#urusan').validate({
			rules: {
				kd_urusan : "required",
				kd_bidang : "required",
				nama_bidang_urusan : "required"
			}
		});

		$("#simpan").click(function(){
			$('#kebijakan_frame .kebijakan_val').each(function () {
			    $(this).rules('add', {
			        required: true
			    });
			});

	    var valid = $("form#urusan").valid();
	    if (valid) {
	    	element_program.parent().next().hide();
	    	$.blockUI({
				css: window._css,
				overlayCSS: window._ovcss
			});

	    	$.ajax({
					type: "POST",
					url: $("form#urusan").attr("action"),
					data: $("form#urusan").serialize(),
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
							tab_element = "program";
							element_program.trigger( "click" );
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
		if (!empty($urusan)){
		    echo "Edit Data Urusan";
		} else{
		    echo "Input Data Urusan";
		}
	?>
	</h3>
	</header>
	<div class="module_content">
		<form action="<?php echo site_url('rpjmd/save_urusan');?>" method="POST" name="urusan" id="urusan" accept-charset="UTF-8" enctype="multipart/form-data" >
			<input type="hidden" name="id_urusan" value="<?php if(!empty($urusan->id)){echo $urusan->id;} ?>" />
			<input type="hidden" name="id_rpjmd" value="<?php echo $id_rpjmd; ?>" />
			<input type="hidden" name="id_tujuan" value="<?php echo $tujuan->id; ?>" />
			<input type="hidden" name="id_sasaran" value="<?php echo $sasaran->id; ?>" />
			<input type="hidden" name="id_prog" value="<?php echo $program->id; ?>" />
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
					<td><?php echo $program->nama_prog; ?></td>
				</tr>
				<tr>
					<td><strong>Urusan</strong></td>
					<td><?php echo $kd_urusan; ?></td>
				</tr>
				<tr>
					<td><strong>Bidang</strong></td>
					<td id="cmb-bidang"><?php echo $kd_bidang; ?></td>
				</tr>
				<tr>
					<td><strong>Nama Bidang Urusan</strong></td>
					<td id="multi-skpd">
						<?php if(!empty($skpd_bidang)){echo $skpd_bidang;} ?>
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
