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
<article class="module width_full">
	<header>
 		<h3>
			Verifikasi Data Renstra
		</h3>
 	</header>
	<div class="module_content">
		<table class="fcari" width="97%">
			<tbody>
 					<tr>
 						<td width="20%">SKPD</td>
 						<td width="77%"><?php echo $renstra->nama_skpd; ?></td>
					</tr>
 					<tr>
 						<td>Bidang Koordinasi</td>
 						<td><?php echo $renstra->nama_koor; ?></td>
					</tr>
					<tr>
						<td>Tujuan</td>
						<td><?php echo $renstra->tujuan; ?></td>
					</tr>
					<tr>
						<td>Sasaran</td>
						<td><?php echo $renstra->sasaran; ?></td>
					</tr>
					<tr>
						<td>Indikator Sasaran</td>
						<td><?php echo $renstra->indikator_sasaran; ?></td>
					</tr>
					<tr>
						<td>Target Sasaran<BR>(Outcome & Output)</td>
						<td><?php echo $renstra->target_sasaran; ?></td>
					</tr>
					<tr>
						<td>Data Capaian pada Awal Tahun</td>
						<td><?php echo $renstra->capaian_awal_tahun; ?></td>
					</tr>
					<tr>
						<td>Catatan</td>
						<td>
							<?php echo $renstra->catatan; ?></textarea>
						</td>
					</tr>
					<tr style="background-color: white;">
						<td colspan="2"><hr></td>
					</tr>
					<tr>
						<td>Kode Urusan</td>
						<td><?php echo $renstra->kd_urusan." - ".$renstra->nm_urusan; ?></td>
					</tr>
					<tr>
						<td>Kode Bidang Urusan</td>
						<td><?php echo $renstra->kd_bidang." - ".$renstra->nm_bidang; ?></td>
					</tr>
					<tr>
						<td>Kode Program</td>
						<td><?php echo $renstra->kd_program." - ".$renstra->ket_program; ?></td>
					</tr>
					<tr>
						<td>Kode Kegiatan</td>
						<td><?php echo $renstra->kd_kegiatan." - ".$renstra->ket_kegiatan; ?></td>
					</tr>					
					<tr style="background-color: white;">
						<td></td>
						<td><hr></td>
					</tr>					
					<tr>
						<td>&nbsp;&nbsp;Target 1 (%)</td>
						<td><?php echo $renstra->target_1; ?></td>
					</tr>
					<tr>
						<td>&nbsp;&nbsp;Nominal 1</td>						
						<td>Rp. <?php echo Formatting::currency($renstra->nominal_1); ?></td>
					</tr>					
					<tr style="background-color: white;">
						<td></td>
						<td><hr></td>
					</tr>					
					<tr>
						<td>&nbsp;&nbsp;Target 2 (%)</td>
						<td><?php echo $renstra->target_2; ?></td>
					</tr>
					<tr>
						<td>&nbsp;&nbsp;Nominal 2</td>
						<td>Rp. <?php echo Formatting::currency($renstra->nominal_2); ?></td>
					</tr>					
					<tr style="background-color: white;">
						<td></td>
						<td><hr></td>
					</tr>					
					<tr>
						<td>&nbsp;&nbsp;Target 3 (%)</td>
						<td><?php echo $renstra->target_3; ?></td>
					</tr>
					<tr>
						<td>&nbsp;&nbsp;Nominal 3</td>						
						<td>Rp. <?php echo Formatting::currency($renstra->nominal_3); ?></td>
					</tr>					
					<tr style="background-color: white;">
						<td></td>
						<td><hr></td>
					</tr>					
					<tr>
						<td>&nbsp;&nbsp;Target 4 (%)</td>
						<td><?php echo $renstra->target_4; ?></td>
					</tr>
					<tr>
						<td>&nbsp;&nbsp;Nominal 4</td>						
						<td>Rp. <?php echo Formatting::currency($renstra->nominal_4); ?></td>
					</tr>					
					<tr style="background-color: white;">
						<td></td>
						<td><hr></td>
					</tr>										
					<tr>
						<td>&nbsp;&nbsp;Target 5 (%)</td>
						<td><?php echo $renstra->target_5; ?></td>
					</tr>
					<tr>
						<td>&nbsp;&nbsp;Nominal 5</td>						
						<td>Rp. <?php echo Formatting::currency($renstra->nominal_5); ?></td>
					</tr>					
					<tr style="background-color: white;">
						<td colspan="2"><hr></td>
					</tr>
					<tr>
						<td colspan="2">Kondisi Kinerja pada akhir Tahun</td>
					</tr>
					<tr>
						<td>&nbsp;&nbsp;Target (%)</td>
						<td><?php echo ($renstra->target_1+$renstra->target_2+$renstra->target_3+$renstra->target_4+$renstra->target_5); ?></td>
					</tr>
					<tr>
						<td>&nbsp;&nbsp;Nominal</td>						
						<td>Rp. <?php echo Formatting::currency($renstra->nominal_1+$renstra->nominal_2+$renstra->nominal_3+$renstra->nominal_4+$renstra->nominal_5); ?></td>
					</tr>					
					<tr style="background-color: white;">
						<td colspan="2"><hr></td>
					</tr>
 				</tbody>
		</table>
		
		<form id="veri_renstra" name="veri_renstra" method="POST" accept-charset="UTF-8" action="<?php echo site_url('renstra/save_veri'); ?>">
			<input type="hidden" name="id" value="<?php echo $renstra->id; ?>">
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