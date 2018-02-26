<script type="text/javascript">
	$(function() {
		prepare_chosen();
        $('#pagu_triwulan').autoNumeric(numOptions);
    	$('#realisasi').autoNumeric(numOptions);
		
        $("#kendali_belanja_input").validate({
            rules: {
                kd_urusan : "required",
			    kd_bidang : "required",
			    kd_program : "required",
			    kd_kegiatan : "required",
			    kriteria_keberhasilan : "kriteria_keberhasilan",
			    ukuran_keberhasilan : "ukuran_keberhasilan",
			    triwulan : "triwulan",
			    pagu_triwulan : "pagu_triwulan", 
			    realisasi : "realisasi",
			    capaian_input : "capaian_input",
			    kinerja_output : "kinerja_output",
			    capaian_output : "capaian_output"
            },
            messages: {
                kd_urusan : "Kode Urusan Mohon Diisi",
			    kd_bidang : "Kode Bidang Mohon Diisi",
			    kd_program : "Kode Program Mohon Diisi",
			    kd_kegiatan : "Kode Kegiatan Mohon Diisi",
			    kriteria_keberhasilan : "Kriteria Keberhasilan Mohon Diisi",
			    ukuran_keberhasilan : "Ukuran Keberhasilan Mohon Diisi",
			    triwulan : "Triwulan Mohon Diisi",
			    pagu_triwulan : "Pagu Mohon Diisi", 
			    realisasi : "Realisasi Mohon Diisi",
			    capaian_input : "Persentase Capaian Mohon Diisi",
			    kinerja_output : "Ukuran Kinerja Triwulan Mohon Diisi",
			    capaian_output : "Persentase Capaian Mohon Diisi"
            },
			submitHandler: function(form){
				$('#pagu_triwulan').val($("#pagu_triwulan").autoNumeric('get'));
    			$('#realisasi').val($("#realisasi").autoNumeric('get'));
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

    $(document).on("change", "#kd_kegiatan", function () {   
        var str = $(this).find('option:selected').text();   
        
    });
</script>
<article class="module width_full">
	<header>
 		<h3>
		<?php
			if (isset($isEdit) && $isEdit){
			    echo "Edit Data Pengedalian Belanja Perubahan";
			} else{
			    echo "Input Data Pengedalian Belanja Perubahan";
			}
		?>
		</h3>
 	</header>
 	<div class="module-content">
		<form method="post" name='input_kendali_belanja' id='input_kendali_belanja' action="<?php echo site_url('kendali_belanja_perubahan/save_kendali_belanja')?>" enctype="multipart/form-data" >
		<input name='call_from' type='hidden' id="call_from" value='<?php echo isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '' ?>'/>
		<input type="hidden" name="id_kendali_belanja" value="<?php if(!empty($id_kendali_belanja)){echo $id_kendali_belanja;} ?>" />
	   	  	<table id="kendali_belanja_input" class="fcari" width="100%">
	   	  		<tr>
			        <td style="width:20%">Kode Urusan</td>
			        <td style="width:80%">            
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
					<td>Kriteria Keberhasilan</td>
					<td>
						<input type="text" name="kriteria_keberhasilan" id="kriteria_keberhasilan" placeholder="Kriteria Keberhasilan" 
						value="<?php echo isset($kriteria_keberhasilan) ? $kriteria_keberhasilan : ''; ?>" style="width:40%"/>
					</td>
				</tr>
				<tr>
					<td>Ukuran Keberhasilan</td>
					<td>
						<input type="text" name="ukuran_keberhasilan" id="ukuran_keberhasilan" placeholder="Ukuran Keberhasilan" 
						value="<?php echo isset($ukuran_keberhasilan) ? $ukuran_keberhasilan : ''; ?>" style="width:40%"/>
					</td>
				</tr>
				<tr>
					<td>Triwulan</td>
					<td>
						<input type="text" name="triwulan" id="triwulan" placeholder="Triwulan ke" 
						value="<?php echo isset($triwulan) ? $triwulan : ''; ?>" style="width:40%"/>
					</td>
				</tr>
				<tr>
					<td>Pagu</td>
					<td>
						<input name='pagu_triwulan' type='text' id="pagu_triwulan" class='currency'
						value="<?php
						echo isset($pagu_triwulan) ? $pagu_triwulan : '0' 
						?>" style="width:40%">
					</td>
				</tr>
				<tr>
					<td>Realisasi</td>
					<td>
						<input name='realisasi' type='text' id="realisasi" class='currency'
						value="<?php
						echo isset($realisasi) ? $realisasi : '0' 
						?>" style="width:40%">
					</td>
				</tr>
				<tr>
					<td>Capaian (%)</td>
					<td>
						<input type="text" name="capaian_input" id="capaian_input" placeholder="Persentase Capaian Input" 
						value="<?php echo isset($capaian_input) ? $capaian_input : ''; ?>" style="width:40%"/>
					</td>
				</tr>
				<tr>
					<td>Ukuran Kinerja Triwulan</td>
					<td>
						<input type="text" name="kinerja_output" id="kinerja_output" placeholder="Ukuran Kinerja Triwulan" 
						value="<?php echo isset($ukuran_keberhasilan) ? $ukuran_keberhasilan : ''; ?>" style="width:40%"/>
					</td>
				</tr>
				<tr>
					<td>Capaian (%)</td>
					<td>
						<input type="text" name="capaian_output" id="capaian_output" placeholder="Persentase Capaian Output" 
						value="<?php echo isset($capaian_output) ? $capaian_output : ''; ?>" style="width:40%"/>
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
    			<input type="button" value="Keluar" onclick="window.location.href='<?php echo site_url('kendali_belanja_perubahan'); ?>'" />
                <input type="button" value="Batal" onclick="window.location='<?php $call_from = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
		          	if(!empty($call_from) && strpos($call_from, 'kendali_belanja_perubahan/cru_kendali') == FALSE)
		          		echo $call_from;
		          	else 
		          		echo site_url('kendali_belanja_perubahan/');
              	?>'"/>
  		    </div>
   		</form> 		
   	</div> 	
   	<footer>
  		
  	</footer>
</article>