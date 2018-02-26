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
		$("form#veri_renstra").validate({
			rules: {			  
			  ket : "required"
			}
	    });

		$("#simpan").click(function(){
		    var valid = $("form#veri_renstra").valid();
		    if (valid) {
		    	$("form#veri_renstra").submit();
		    };
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
<article class="module width_full" style="width: 138%; margin-left: -19%;">
	<header>
	  <h3>Verifikasi Renstra Untuk Rancangan Awal (Ranwal)</h3>
	</header>
	<div class="module_content";>
		<table class="fcari" width="100%">
		<?php
			echo $header;
		?>
		</table>
		<div style="overflow:auto">			
			<table class="table-common">
			<?php
				echo $renstra;
			?>
			</table>
		</div>
		<form id="veri_renstra" name="veri_renstra" method="POST" accept-charset="UTF-8" action="<?php echo site_url('renstra/save_veri'); ?>">
			<input type="hidden" name="id" value="<?php echo $skpd_visi->id; ?>">
			<div class="radio-btn">
				<input type="radio" name="veri" value="setuju"> Disetujui
			</div>
			<div class="radio-btn">
				<input type="radio" name="veri" value="tdk_setuju"> Tidak Disetujui				
			</div>
			<div class="radio-btn">
				<textarea disabled id="ket" name="ket"></textarea>
			</div>
		</form>
	</div>		
	<footer>
		<div class="submit_link">  
			<input disabled type='button' id="simpan" name="simpan" value='Simpan' />
  			<input type='button' id="keluar" name="keluar" value='Keluar' />
		</div> 
	</footer>
</article>