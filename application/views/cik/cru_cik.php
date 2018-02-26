<script type="text/javascript">
	$(function() {
		prepare_chosen();
        $('#anggaran_rencana').autoNumeric(numOptions);
    	$('#anggaran_realisasi').autoNumeric(numOptions);
		
        $("#input_cik").validate({
            rules: {
                kd_urusan : "required",
			    kd_bidang : "required",
			    kd_program : "required",
			    kd_kegiatan : "required",
			    capaian_sekarang : "required",
			    jumlah_dana_sekarang : "required",
			    capaian_mendatang : "required",
			    jumlah_dana_mendatang : "required", 
			    hasil_pengendalian : "required",
			    tindak_lanjut : "required",
			    hasil_tindak_lanjut : "required"
            },
            messages: {
                kd_urusan : "Mohon Diisi",
			    kd_bidang : "Mohon Diisi",
			    kd_program : "Mohon Diisi",
			    kd_kegiatan : "Mohon Diisi",
			    capaian_sekarang : "Mohon Diisi",
			    jumlah_dana_sekarang : "Mohon Diisi",
			    capaian_mendatang : "Mohon Diisi",
			    jumlah_dana_mendatang : "Mohon Diisi", 
			    hasil_pengendalian : "Mohon Diisi",
			    tindak_lanjut : "Mohon Diisi",
			    hasil_tindak_lanjut : "Mohon Diisi"
            },
			submitHandler: function(form){
				$('#anggaran_rencana').val($("#anggaran_rencana").autoNumeric('get'));
    			$('#anggaran_realisasi').val($("#anggaran_realisasi").autoNumeric('get'));
				form.submit();
			}
        });		
    });
	
	prepare_chosen();

	$(document).on("change", "#id_bulan", function () {    
        $.ajax({
            type: "POST",
            url: '<?php echo site_url("common/cmb_bulan"); ?>',
            data: {id_bulan: $(this).val()},
            success: function(msg){
              prepare_chosen();
            }
        });
    
	
	});

    $(document).on("change", "#kd_urusan", function () {    
        $.ajax({
            type: "POST",
            url: '<?php echo site_url("common/cmb_bidang"); ?>',
            data: {kd_urusan: $(this).val()},
            success: function(msg){
              $("#cmb-bidang").html(msg);       
              $("#kd_program").val("");
              $("#kd_program").trigger("chosen:updated");
              prepare_chosen();
            }
        });
    
	
	});

    $(document).on("change", "#kd_bidang", function () {    
        $.ajax({
            type: "POST",
            url: '<?php echo site_url("common/cmb_program"); ?>',
            data: {kd_urusan:$("#kd_urusan").val(), kd_bidang: $(this).val()},
            success: function(msg){
              $("#cmb-program").html(msg);
              $("#kd_kegiatan").val("");
              $("#kd_kegiatan").trigger("chosen:updated");
              prepare_chosen();
            }
        });
    });

    $(document).on("change", "#kd_program", function () {    
        $.ajax({
            type: "POST",
            url: '<?php echo site_url("common/cmb_kegiatan"); ?>',
            data: {kd_urusan:$("#kd_urusan").val(), kd_bidang:$("#kd_bidang").val(), kd_program: $(this).val()},
            success: function(msg){
              $("#cmb-kegiatan").html(msg);
              prepare_chosen();
            }
        });
    });

    $(document).on("change", "#kd_kegiatan", function () {   
        var str = $(this).find('option:selected').text();   
        prepare_chosen();
    });
</script>

<article class="module width_full">
	<header>
 		<h3>
		<?php
			if (isset($isEdit) && $isEdit){
			    echo "Edit Data CIK";
			} else{
			    echo "Input Data CIK";
			}
		?>
		</h3>
 	</header>
 	<div class="module-content">
		<form method="post" name='input_cik' id='input_cik' action="<?php echo site_url('cik/save_cik')?>" enctype="multipart/form-data" >
		<input name='call_from' type='hidden' id="call_from" value='<?php echo isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '' ?>'/>
		<input type="hidden" name="id_cik" value="<?php if(!empty($id_cik)){echo $id_cik;} ?>" />
	   	  	<table id="cik_input" class="fcari" width="100%">
	   	  		<tr>
			        <td style="width:20%">Bulan</td>
			        <td style="width:80%">            
			        	<?php echo $id_bulan; ?>           
			        </td>
		        </tr>
		        <tr>
			        <td >Kode Urusan</td>
			        <td >            
			        	<?php echo $kd_urusan; ?>           
			        </td>
		        </tr>
				<tr>
					<td>Kode Bidang</td>
					<td id="cmb-bidang">
						<?php echo $kd_bidang; ?>
					</td>
				</tr>
				<tr>
					<td>Kode Program</td>
					<td id="cmb-program">
						<?php echo $kd_program; ?>
					</td>
				</tr>
				<tr>
					<td>Kode Kegiatan</td>
					<td id="cmb-kegiatan">
						<?php echo $kd_kegiatan; ?>
					</td>
				</tr>
				<tr>
					<td>Anggaran Rencana</td>
					<td>
						<input name='anggaran_rencana' type='text' id="anggaran_rencana" class='currency'
						value="<?php
						echo isset($anggaran_rencana) ? $anggaran_rencana : '0' 
						?>" style="width:40%">
					</td>
				</tr>
				<tr>
					<td>Anggaran Realisasi</td>
					<td>
						<input name='anggaran_realisasi' type='text' id="anggaran_realisasi" class='currency'
						value="<?php
						echo isset($anggaran_realisasi) ? $anggaran_realisasi : '0' 
						?>" style="width:40%">
					</td>
				</tr>
				<tr>
					<td>Anggaran Capaian IK (%)</td>
					<td>
						<input type="text" name="capaian_ik" id="capaian_ik" placeholder="Persentase Capaian IK" 
						value="<?php echo isset($capaian_ik) ? $capaian_ik : ''; ?>" style="width:40%"/>
					</td>
				</tr>
				<tr>
					<td>Indikator/Satuan</td>
					<td>
						<input type="text" name="indikator" id="indikator" placeholder="Indikator/Satuan" 
						value="<?php echo isset($indikator) ? $indikator : ''; ?>" style="width:40%"/>
					</td>
				</tr>
				<tr>
					<td>Indikator Rencana</td>
					<td>
						<input type="text" name="indikator_rencana" id="indikator_rencana" placeholder="Indikator Rencana" 
						value="<?php echo isset($indikator_rencana) ? $indikator_rencana : ''; ?>" style="width:40%"/>
					</td>
				</tr>
				<tr>
					<td>Indikator Realisasi</td>
					<td>
						<input type="text" name="indikator_realisasi" id="indikator_realisasi" placeholder="Indikator Realisasi" 
						value="<?php echo isset($indikator_realisasi) ? $indikator_realisasi : ''; ?>" style="width:40%"/>
					</td>
				</tr>
				<tr>
					<td>Indikator Capaian IK (%)</td>
					<td>
						<input type="text" name="ind_capaian_ik" id="ind_capaian_ik" placeholder="Indikator Persentase Capaian IK" 
						value="<?php echo isset($ind_capaian_ik) ? $ind_capaian_ik : ''; ?>" style="width:40%"/>
					</td>
				</tr>
				<tr>
					<td>Keterangan</td>
					<td>
						<input type="text" name="keterangan" id="keterangan" placeholder="Keterangan" 
						value="<?php echo isset($keterangan) ? $keterangan : ''; ?>" style="width:40%"/>
					</td>
				</tr>
	   	  	</table>
	   	  	</div>
            <div class="submit_link">  
    			<input type="submit" name="simpan"  id="simpan" value='Simpan'/>
    			<input type="button" value="Keluar" onclick="window.location.href='<?php echo site_url('cik'); ?>'" />
                <input type="button" value="Batal" onclick="window.location='<?php $call_from = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
		          	if(!empty($call_from) && strpos($call_from, 'cik/cru_cik') == FALSE)
		          		echo $call_from;
		          	else 
		          		echo site_url('cik/');
              	?>'"/>
  		    </div>
   		</form> 		
   	</div> 	
   	<footer>
  		
  	</footer>
</article>