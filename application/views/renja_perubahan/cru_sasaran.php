<script type="text/javascript">	
	$(document).ready(function(){
		$('form#sasaran').validate({
			rules: {
				sasaran : "required",
				indikator : "required",
				satuan : "required",
				triwulan_1 : {
							required:true,
							digits:true
				},
				triwulan_2 : {
							required:true,
							digits:true
				},
				triwulan_3 : {
							required:true,
							digits:true
				},
				triwulan_4 : {
							required:true,
							digits:true
				}
			}			
		});

		$("#simpan").click(function(){			
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
							reload_jendela_kontrol();
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
		    echo "Edit Data Renja";
		} else{
		    echo "Input Data Renja";
		}
	?>
	</h3>
	</header>
	<div class="module_content">
		<form action="<?php echo site_url('renja_perubahan/save_sasaran');?>" method="POST" name="sasaran" id="sasaran" accept-charset="UTF-8" enctype="multipart/form-data" >
			<input type="hidden" name="id_sasaran" value="<?php if(!empty($sasaran->id)){echo $sasaran->id;} ?>" />
			<input type="hidden" name="id_renja" value="<?php echo $id_renja; ?>" />
			<input type="hidden" name="id_tujuan" value="<?php echo $tujuan->id; ?>" />
			<table class="fcari" width="100%">
				<tbody>
					<tr>
						<td width="20%">SKPD</td>
						<td width="80%"><?php echo $skpd->nama_skpd; ?></td>
					</tr>
					<tr>
						<td>Tujuan</td>
						<td><?php echo $tujuan->tujuan; ?></td>
					</tr>
					<tr>
						<td>Sasaran</td>
						<td>
							<textarea class="common" name="sasaran"><?php if(!empty($sasaran->sasaran)){echo $sasaran->sasaran;} ?></textarea>
						</td>
					</tr>
					<tr>
						<td>Indikator Kerja</td>
						<td>
							<textarea class="common" name="indikator"><?php if(!empty($sasaran->indikator)){echo $sasaran->indikator;} ?></textarea>
						</td>
					</tr>
					<tr>
						<td colspan="2"><hr></td>
					</tr>
					<tr>
						<td>Satuan Target</td>
						<td><?php echo $satuan; ?></td>
					</tr>					
					<tr>
						<td>Target Triwulan 1</td>
						<td><input class="common" name="triwulan_1" value="<?php echo (!empty($sasaran->triwulan_1))?$sasaran->triwulan_1:''; ?>"></td>
					</tr>
					<tr>
						<td>Target Triwulan 2</td>
						<td><input class="common" name="triwulan_2" value="<?php echo (!empty($sasaran->triwulan_2))?$sasaran->triwulan_2:''; ?>"></td>
					</tr>
					<tr>
						<td>Target Triwulan 3</td>
						<td><input class="common" name="triwulan_3" value="<?php echo (!empty($sasaran->triwulan_3))?$sasaran->triwulan_3:''; ?>"></td>
					</tr>
					<tr>
						<td>Target Triwulan 4</td>
						<td><input class="common" name="triwulan_4" value="<?php echo (!empty($sasaran->triwulan_4))?$sasaran->triwulan_4:''; ?>"></td>
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