<script type="text/javascript">
	$('input[name=nominal]').autoNumeric(numOptions);	

	$('form#revisi').validate({
		rules: {							
			nominal : {
				required : true
			}
		}			
	});

	$("#simpan").click(function(){
	    var valid = $("form#revisi").valid();
	    if (valid) {
	    	$('input[name=nominal]').val($('input[name=nominal]').autoNumeric('get'));			
			
	    	$.blockUI({
				css: window._css,
				overlayCSS: window._ovcss
			});
	    	
	    	$.ajax({
				type: "POST",
				url: $("form#revisi").attr("action"),
				data: $("form#revisi").serialize(),
				dataType: "json",
				success: function(msg){
					if (msg.success==1) {
						$.blockUI({
							message: msg.msg,							
							css: window._css,
							overlayCSS: window._ovcss
						});						
						location.reload();
					};
				}
			});
	    };
	});
</script>
<form action="<?php echo site_url('renja/save_veri_akhir');?>" method="POST" name="revisi" id="revisi" accept-charset="UTF-8" enctype="multipart/form-data" >
	<table class="fcari" width="800px">
		<tbody>			
			<tr>
				<td>Program</td>
				<td><?php echo $renja->kd_urusan.".".$renja->kd_bidang.".".$renja->kd_program." ".$renja->nama_prog_or_keg; ?></td>
			</tr>
			<tr>
				<td>Indikator Kinerja Program (outcome)</td>
				<td>
					<?php
						$i=0;
						foreach ($indikator_program as $row_program) {
							if (!empty((float)$row_program->target)) {								
								$i++;
								echo $i .". ". $row_program->indikator . " (" . $row_program->target." ".$row_program->nama_value .")<BR>";
					?>
					<hr>
					<?php
							}
						}
					?>
				</td>
			</tr>
			<tr style="background-color: white;">			
				<td colspan="2"><hr></td>
			</tr>
			<tr>
				<td>Nominal</td>
				<td>Rp. <?php echo Formatting::currency($renja->nominal); ?></td>
			</tr>
			<tr>
				<td>Batas Nominal</td>
				<td><input style="text-align: right;" type="text" name="nominal" /></td>
			</tr>
			<tr>
				<td>Keterangan</td>
				<td><textarea name="ket_revisi"></textarea></td>
			</tr>		
		</tbody>
	</table>	
	<input type="hidden" name="veri" value="tdk_setuju" />
	<input type="hidden" name="id" value="<?php echo $renja->id; ?>" />	
</form>			
<footer>
	<div class="submit_link">    			
		<input id="simpan" type="button" value="Simpan">
	</div> 
</footer>