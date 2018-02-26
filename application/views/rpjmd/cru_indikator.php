<script type="text/javascript">
	//agar validation tetap aktif untuk chosen dropdown	
	$('form#indikator').validate({
		rules: {			
			indikator : "required",
			satuan_target : "required",			
			kondisi_awal : {
						required:true,
						number:true
			},
			kondisi_akhir : {
						required:true,
						number:true
			}				
		},
		ignore: ":hidden:not(select)"			
	});

	$("#simpan").click(function(){
	    var valid = $("form#indikator").valid();
	    if (valid) {		    	
	    	$.blockUI({
				css: window._css,
				overlayCSS: window._ovcss
			});
	    	
	    	$.ajax({
				type: "POST",
				url: $("form#indikator").attr("action"),
				data: $("form#indikator").serialize(),
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
</script>

<div style="width: 900px">
	<header>
		<h3>
	<?php
		if (!empty($indikator)){
		    echo "Edit Data Indikator";
		} else{
		    echo "Input Data Indikator";
		}
	?>
	</h3>
	</header>
	<div class="module_content">
		<form action="<?php echo site_url('rpjmd/save_indikator');?>" method="POST" name="indikator" id="indikator" accept-charset="UTF-8" enctype="multipart/form-data" >
			<input type="hidden" name="id_indikator" value="<?php if(!empty($indikator->id)){echo $indikator->id;} ?>" />
			<input type="hidden" name="id_rpjmd" value="<?php echo $id_rpjmd; ?>" />
			<input type="hidden" name="id_sasaran" value="<?php echo $id_sasaran; ?>" />			
			
			<table class="fcari" width="100%">
				<tbody>					
					<tr>
						<td width="20%">Tujuan</td>
						<td width="80%"><?php echo $tujuan_n_sasaran->tujuan; ?></td>
					</tr>
					<tr>
						<td>Sasaran</td>
						<td><?php echo $tujuan_n_sasaran->sasaran; ?></td>
					</tr>					
					<tr>
						<td>Indikator</td>
						<td>
							<textarea class="common" name="indikator"><?php echo (!empty($indikator->indikator))?$indikator->indikator:''; ?></textarea>
						</td>
					</tr>
					<tr>
						<td>Satuan Target</td>
						<td><?php echo $satuan; ?></td>
					</tr>
					<tr>
						<td>Kondisi Awal</td>
						<td><input class="common" name="kondisi_awal" value="<?php echo (!empty($indikator->kondisi_awal))?$indikator->kondisi_awal:''; ?>"></td>
					</tr>					
					<tr>
						<td>Target Kondisi Akhir</td>
						<td><input class="common" name="kondisi_akhir" value="<?php echo (!empty($indikator->kondisi_akhir))?$indikator->kondisi_akhir:''; ?>"></td>
					</tr>
				</tbody>
			</table>
		</form>
	</div>
	<footer>
		<div class="submit_link">    			
  			<input id="simpan" type="button" value="Simpan">
		</div> 
	</footer>
</div>