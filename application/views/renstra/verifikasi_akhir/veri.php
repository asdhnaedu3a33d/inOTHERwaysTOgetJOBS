<script type="text/javascript">
	$('input[name=nominal_1]').autoNumeric(numOptions);
	$('input[name=nominal_2]').autoNumeric(numOptions);
	$('input[name=nominal_3]').autoNumeric(numOptions);
	$('input[name=nominal_4]').autoNumeric(numOptions);
	$('input[name=nominal_5]').autoNumeric(numOptions);

	$('form#revisi').validate({
		rules: {							
			nominal_1 : {
				required : true
			},
			nominal_2 : {
				required : true
			},
			nominal_3 : {
				required : true
			},
			nominal_4 : {
				required : true
			},
			nominal_5 : {
				required : true
			}
		}			
	});

	$("#simpan").click(function(){
	    var valid = $("form#revisi").valid();
	    if (valid) {
	    	$('input[name=nominal_1]').val($('input[name=nominal_1]').autoNumeric('get'));
			$('input[name=nominal_2]').val($('input[name=nominal_2]').autoNumeric('get'));
			$('input[name=nominal_3]').val($('input[name=nominal_3]').autoNumeric('get'));
			$('input[name=nominal_4]').val($('input[name=nominal_4]').autoNumeric('get'));
			$('input[name=nominal_5]').val($('input[name=nominal_5]').autoNumeric('get'));
			
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
<table class="fcari" width="800px">
	<tbody>
		<tr>
			<td width="20%">Bidang Urusan</td>
			<td width="77%"><?php echo $renstra->Nm_Bidang; ?></td>
		</tr>		
		<tr>
			<td>SKPD</td>
			<td><?php echo $renstra->nama_skpd; ?></td>
		</tr>
		<tr>
			<td>Program</td>
			<td><?php echo $renstra->kd_urusan.".".$renstra->kd_bidang.".".$renstra->kd_program." ".$renstra->nama_prog_or_keg; ?></td>
		</tr>

		<tr>
			<td>Indikator Kinerja Program (outcome)</td>
			<td>
				<?php
					$i=0;
					foreach ($indikator_program as $row_program) {
						$i++;
						echo $i .". ". $row_program->indikator ."<BR>";
				?>
				<table class="table-common" width="100%">
					<tr>
						<th width="14%">Kondisi Awal</th>
						<th width="14%">Target 1</th>
						<th width="14%">Target 2</th>
						<th width="14%">Target 3</th>
						<th width="14%">Target 4</th>
						<th width="14%">Target 5</th>
						<th width="14%">Kondisi Akhir</th>
					</tr>
					<tr>
						<td align="center"><?php echo $row_program->kondisi_awal." ".$row_program->nama_value; ?></td>
						<td align="center"><?php echo $row_program->target_1." ".$row_program->nama_value; ?></td>
						<td align="center"><?php echo $row_program->target_2." ".$row_program->nama_value; ?></td>
						<td align="center"><?php echo $row_program->target_3." ".$row_program->nama_value; ?></td>
						<td align="center"><?php echo $row_program->target_4." ".$row_program->nama_value; ?></td>
						<td align="center"><?php echo $row_program->target_5." ".$row_program->nama_value; ?></td>
						<td align="center"><?php echo $row_program->target_1." ".$row_program->nama_value; ?></td>
					</tr>
				</table>
				<hr>
				<?php
					}
				?>
			</td>
		</tr>		
		<tr style="background-color: white;">			
			<td colspan="2"><hr></td>
		</tr>
	</tbody>
</table>
<form action="<?php echo site_url('renstra/save_veri_akhir');?>" method="POST" name="revisi" id="revisi" accept-charset="UTF-8" enctype="multipart/form-data" >
	<table class="fcari" width="800px">
		<tbody>				
			<tr>
				<th>Nominal 1</th>
				<th>Nominal 2</th>
				<th>Nominal 3</th>
				<th>Nominal 4</th>
				<th>Nominal 5</th>			
			</tr>
			<tr>
				<td align="right">Rp. <?php echo Formatting::currency($renstra->nominal_1_pro); ?></td>
				<td align="right">Rp. <?php echo Formatting::currency($renstra->nominal_2_pro); ?></td>
				<td align="right">Rp. <?php echo Formatting::currency($renstra->nominal_3_pro); ?></td>
				<td align="right">Rp. <?php echo Formatting::currency($renstra->nominal_4_pro); ?></td>
				<td align="right">Rp. <?php echo Formatting::currency($renstra->nominal_5_pro); ?></td>
			</tr>
			<tr>
				<th>Batas Nominal 1</th>
				<th>Batas Nominal 2</th>
				<th>Batas Nominal 3</th>
				<th>Batas Nominal 4</th>
				<th>Batas Nominal 5</th>
			</tr>
			<tr>		
				<td align="center"><input style="text-align: right;" type="text" name="nominal_1" /></td>
				<td align="center"><input style="text-align: right;" type="text" name="nominal_2" /></td>
				<td align="center"><input style="text-align: right;" type="text" name="nominal_3" /></td>
				<td align="center"><input style="text-align: right;" type="text" name="nominal_4" /></td>
				<td align="center"><input style="text-align: right;" type="text" name="nominal_5" /></td>				
			</tr>
			<tr>
				<th>Keterangan 1</th>
				<th>Keterangan 2</th>
				<th>Keterangan 3</th>
				<th>Keterangan 4</th>
				<th>Keterangan 5</th>
			</tr>
			<tr>		
				<td align="center"><textarea name="ket_revisi_1"></textarea></td>
				<td align="center"><textarea name="ket_revisi_2"></textarea></td>
				<td align="center"><textarea name="ket_revisi_3"></textarea></td>
				<td align="center"><textarea name="ket_revisi_4"></textarea></td>
				<td align="center"><textarea name="ket_revisi_5"></textarea></td>
			</tr>
		</tbody>
	</table>
	<input type="hidden" name="veri" value="tdk_setuju" />
	<input type="hidden" name="id" value="<?php echo $renstra->id; ?>" />	
</form>			
<footer>
	<div class="submit_link">    			
		<input id="simpan" type="button" value="Simpan">
	</div> 
</footer>