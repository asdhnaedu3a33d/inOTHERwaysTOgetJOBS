<script type="text/javascript">
	$(function() {
		prepare_chosen();
        $('#nominal').autoNumeric(numOptions);
		
        $("#input_renja").validate({
            rules: {
                kd_urusan : "required",
			    kd_bidang : "required",
			    kd_program : "required",
			    tahun : "required",
			    nominal : "required",
			    target : "required"
            },
            messages: {
                kd_urusan : "Mohon Diisi",
			    kd_bidang : "Mohon Diisi",
			    kd_program : "Mohon Diisi",
			    tahun : "Mohon Diisi",
			    nominal : "Mohon Diisi",
			    target : "Mohon Diisi"
            },
			submitHandler: function(form){
				$('#nominal').val($("#nominal").autoNumeric('get'));
				form.submit();
			}
        });		
    });
	
	prepare_chosen();
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

</script>
<article class="module width_full">
	<header>
 		<h3>
		<?php
			if (isset($isEdit) && $isEdit){
			    echo "Edit Data Renja";
			} else{
			    echo "Input Data Renja";
			}
		?>
		</h3>
 	</header>
 	<div class="module-content">
		<form method="post" name='input_renja' id='input_renja' action="<?php echo site_url('renja/save_renja')?>" enctype="multipart/form-data" >
		<input name='call_from' type='hidden' id="call_from" value='<?php echo isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '' ?>'/>
		<input type="hidden" name="id" value="<?php if(!empty($id)){echo $id;} ?>" />
		<input type="hidden" name="is_prog_or_keg" value="1"/>
		<input type="hidden" name="id_status_renja" value="2"/>
	   	  	<table id="renja_input" class="fcari" width="100%">
	   	  		<tr>
			        <td style="width:20%">Urusan Pemerintahan</td>
			        <td style="width:80%">            
			        	<?php echo $kd_urusan; ?>           
			        </td>
		        </tr>
				<tr>
					<td>Bidang Organisasi</td>
					<td id="cmb-bidang">
						<?php echo $kd_bidang; ?>
					</td>
				</tr>
				<tr>
					<td>Program</td>
					<td id="cmb-program">
						<?php echo $kd_program; ?>
					</td>
				</tr>
				<tr>
					<td>Tahun</td>
					<td>
						<input type="text" name="tahun" id="tahun" placeholder="Tahun" 
						value="<?php echo isset($tahun) ? $tahun : ''; ?>" style="width:40%"/>
					</td>
				</tr>
				<tr>
					<td>Indikator Kinerja</td>
					<td>
						<input type="text" name="indikator_kinerja" id="indikator_kinerja" placeholder="Indikator Kinerja" 
						value="<?php echo isset($indikator_kinerja) ? $indikator_kinerja : ''; ?>" style="width:40%"/>
					</td>
				</tr>
				<tr>
					<td>Lokasi</td>
					<td>
						<input type="text" name="lokasi" id="lokasi" placeholder="Lokasi" 
						value="<?php echo isset($lokasi) ? $lokasi : ''; ?>" style="width:40%"/>
					</td>
				</tr>
				<tr>
					<td>Nominal</td>
					<td>
						<input name='nominal' type='text' id="nominal" class='currency'
						value="<?php
						echo isset($nominal) ? $nominal : '0' 
						?>" style="width:40%">
					</td>
				</tr>
				<tr>
					<td>Target</td>
					<td>
						<input type="text" name="target" id="target" placeholder="Target Capaian" 
						value="<?php echo isset($target) ? $target : ''; ?>" style="width:40%"/>
					</td>
				</tr>
				<tr>
					<td>Catatan</td>
					<td>
						<input type="text" name="catatan" id="catatan" placeholder="Catatan" 
						value="<?php echo isset($catatan) ? $catatan : ''; ?>" style="width:70%"/>
					</td>
				</tr>
	   	  	</table>
	   	  	</div>
            <div class="submit_link">  
    			<input type="submit" name="simpan"  id="simpan" value='Simpan'/>
    			<input type="button" value="Keluar" onclick="window.location.href='<?php echo site_url('renja'); ?>'" />
                <input type="button" value="Batal" onclick="window.location='<?php $call_from = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
		          	if(!empty($call_from) && strpos($call_from, 'renja/cru_renja') == FALSE)
		          		echo $call_from;
		          	else 
		          		echo site_url('renja/');
              	?>'"/>
  		    </div>
   		</form> 		
   	</div> 	
   	<footer>
  		
  	</footer>
</article>