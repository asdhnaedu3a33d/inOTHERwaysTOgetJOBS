<style type="text/css">
	form{
		color: black;
	}
	.radio-btn{
		width: 97%;
		padding: 10px;
	}
	.radio-btn textarea, .radio-btn .error{
		margin-left: 25px;
		width: 500px;
		height: 100px;
		float: left;
	}
</style>
<script type="text/javascript">
	$(document).ready(function(){
		$("form#veri_renja").validate({
			rules: {
			  ket : "required"
			}
	    });

		$("#simpan").click(function(){
		    var valid = $("form#veri_renja").valid();
		    if (valid) {
		    	$.blockUI({
					css: window._css,
					overlayCSS: window._ovcss
				});

		    	$.ajax({
					type: "POST",
					url: $("form#veri_renja").attr("action"),
					data: $("form#veri_renja").serialize(),
					dataType: "json",
					success: function(msg){
						if (msg.success==1) {
							$.blockUI({
								message: msg.msg,
								timeout: 2000,
								css: window._css,
								overlayCSS: window._ovcss
							});
							location.reload();
							$.facebox.close();
						};
					}
				});
		    };
		});

		$("#keluar").click(function(){
			$.facebox.close();
		});

		$("input[name=veri]").click(function(){
			$("#simpan").attr("disabled", false);
			if($(this).val()=="tdk_setuju"){
				$("#ket").attr("disabled", false);
			}else{
				$("#ket").val("");
				$("#ket").attr("disabled", true);
			}
		});
	});
</script>
<div style="width: 800px;">
	<header>
 		<h3>
			Verifikasi Data Kendali Renja
		</h3>
 	</header>
	<div class="module_content">
		<table class="fcari" width="100%">
		</table>
		<form id="veri_renja" name="veri_renja" method="POST" accept-charset="UTF-8" action="<?php echo site_url('kendali/save_veri_renja'); ?>">
			<input type="hidden" name="id" value="<?php echo $rka->id; ?>">
			<div class="radio">
				<label><input type="radio" name="veri" value="setuju"> Disetujui</label>
			</div>
			<div class="radio">
				<label><input type="radio" name="veri" value="tdk_setuju"> Tidak Disetujui</label>
			</div>
			<div class="form-group">
				<textarea class="form-control" disabled id="ket" name="ket"></textarea>
			</div>
		</form>
	</div>
	<footer>
		<div class="submit_link">
			<input disabled type='button' id="simpan" name="simpan" value='Simpan' />
  			<input type='button' id="keluar" name="keluar" value='Keluar' />
		</div>
	</footer>
</div>
