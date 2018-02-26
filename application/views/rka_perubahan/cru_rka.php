<script type="text/javascript">

$(function() {
        prepare_chosen();
        $('#jumlah_dana_sekarang').autoNumeric(numOptions);
      $('#jumlah_dana_mendatang').autoNumeric(numOptions);
    
        $("#input_rka").validate({
            rules: {
                kd_urusan : "required",
                kd_bidang : "required",
                kd_program : "required",
                kd_kegiatan : "required",
                indikator_capaian : "required",
                tahun_sekarang : "required",
                capaian_sekarang : "required",
                jumlah_dana_sekarang : "required",
                tahun_mendatang : "required",
                capaian_mendatang : "required",
                jumlah_dana_mendatang : "required" 
                // hasil_pengendalian : "required",
                // tindak_lanjut : "required",
                // hasil_tindak_lanjut : "required"
            },
            messages: {
                kd_urusan : "Mohon diisi terlebih dahulu",
                kd_bidang : "Mohon diisi terlebih dahulu",
                kd_program : "Mohon diisi terlebih dahulu",
                kd_kegiatan : "Mohon diisi terlebih dahulu",
                indikator_capaian : "Mohon diisi terlebih dahulu",
                tahun_sekarang : "Mohon diisi terlebih dahulu",
                capaian_sekarang : "Mohon diisi terlebih dahulu",
                jumlah_dana_sekarang : "Mohon diisi terlebih dahulu",
                tahun_mendatang : "Mohon diisi terlebih dahulu",
                capaian_mendatang : "Mohon diisi terlebih dahulu",
                jumlah_dana_mendatang : "Mohon diisi terlebih dahulu" 
            },
      submitHandler: function(form){
        $('#jumlah_dana_sekarang').val($("#jumlah_dana_sekarang").autoNumeric('get'));
          $('#jumlah_dana_mendatang').val($("#jumlah_dana_mendatang").autoNumeric('get'));
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
			    echo "Edit Data RKA Perubahan";
			} else{
			    echo "Input Data RKA Perubahan";
			}
		?>
		</h3>
 	</header>
 	<div class="module_content">
      <form method="post" name='input_rka' id='input_rka' action="<?php echo site_url('rka_perubahan/save_rka')?>" enctype="multipart/form-data" >
      <input name='call_from' type='hidden' id="call_from" value='<?php echo isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '' ?>'/>
   	  <input type="hidden" name="id_rka" value="<?php if(!empty($id_rka)){echo $id_rka;} ?>" />
        <table id="rka_input" class="fcari" width="100%">
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
                  <td>Indikator Capaian Kinerja Program/Kegiatan</td>
                  <td>
                      <input type="text" name="indikator_capaian" id="indikator_capaian" placeholder="Indikator Capaian Kinerja Program/Kegiatan" 
                      value="<?php echo isset($indikator_capaian) ? $indikator_capaian : ''; ?>" style="width:40%"/>
                  </td>
              </tr>
              <tr>
                <td>Tahun Sekarang</td>
                <td>
                      <input type="text" name="tahun_sekarang" id="tahun_sekarang" placeholder="Tahun Sekarang" 
                      value="<?php echo isset($tahun_sekarang) ? $tahun_sekarang : ''; ?>" style="width:40%"/>
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
                <td>Target Capaian Kinerja Sekarang</td>
                <td>
                      <input type="text" name="capaian_sekarang" id="capaian_sekarang" placeholder="Target Capaian Kinerja Sekarang" 
                      value="<?php echo isset($capaian_sekarang) ? $capaian_sekarang : ''; ?>" style="width:40%"/>
                  </td>
              </tr>
              <tr>
                  <td>Dana Sekarang</td>
                  <td>
                      <input name='jumlah_dana_sekarang' type='text' id="jumlah_dana_sekarang" class='currency'
                       value="<?php
                      echo isset($jumlah_dana_sekarang) ? $jumlah_dana_sekarang : '0' 
                    ?>" style="width:40%">
                </td>
              </tr>
              <tr>
                <td>Tahun Mendatang</td>
                <td>
                      <input type="text" name="tahun_mendatang" id="tahun_mendatang" placeholder="Tahun Mendatang" 
                      value="<?php echo isset($tahun_mendatang) ? $tahun_mendatang : ''; ?>" style="width:40%"/>
                  </td>
              </tr>
              <tr>
                  <td>Target Capaian Kinerja Mendatang</td>
                  <td>
                      <input type="text" name="capaian_mendatang" id="capaian_mendatang" placeholder="Target Capaian Kinerja Mendatang" 
                      value="<?php echo isset($capaian_mendatang) ? $capaian_mendatang : ''; ?>" style="width:40%"/>
                  </td>
              </tr>
              <tr>
                  <td>Jumlah Dana Mendatang</td>
                  <td>
                      <input name='jumlah_dana_mendatang' type='text' id="jumlah_dana_mendatang" class='currency'
                       value="<?php
                      echo isset($jumlah_dana_mendatang) ? $jumlah_dana_mendatang : '0' 
                    ?>" style="width:40%">
                </td>
              </tr>
              <!-- <tr>
                  <td>Kesesuaian Ya</td>
                  <td>
                      <input type="text" name="kesesuaian_ya" id="kesesuaian_ya" placeholder="Kesesuaian Ya" 
                      value="<?php echo isset($kesesuaian_ya) ? $kesesuaian_ya : ''; ?>" style="width:40%"/>
                  </td>
              </tr>
              <tr>
                  <td>Kesesuaian Tidak</td>
                  <td>
                      <input type="text" name="kesesuaian_tidak" id="kesesuaian_tidak" placeholder="Kesesuaian Tidak" 
                      value="<?php echo isset($kesesuaian_tidak) ? $kesesuaian_tidak : ''; ?>" style="width:40%"/>
                  </td>
              </tr>
              <tr>
                  <td>Hasil Pengendalian</td>
                  <td>
                      <input type="text" name="hasil_pengendalian" id="hasil_pengendalian" placeholder="Hasil Pengendalian" 
                      value="<?php echo isset($hasil_pengendalian) ? $hasil_pengendalian : ''; ?>" style="width:40%"/>
                  </td>
              </tr>
              <tr>
                  <td>Tindak Lanjut</td>
                  <td>
                      <input type="text" name="tindak_lanjut" id="tindak_lanjut" placeholder="Tindak Lanjut" 
                      value="<?php echo isset($tindak_lanjut) ? $tindak_lanjut : ''; ?>" style="width:40%"/>
                  </td>
              </tr>
              <tr>
                  <td>Hasil Tindak Lanjut</td>
                  <td>
                      <input type="text" name="hasil_tindak_lanjut" id="hasil_tindak_lanjut" placeholder="Hasil Tindak Lanjut" 
                      value="<?php echo isset($hasil_tindak_lanjut) ? $hasil_tindak_lanjut : ''; ?>" style="width:40%"/>
                  </td> -->
              </tr>
              <tr style="background-color: white;">
                <td colspan="2"><hr></td>
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
                      <input type="text" name="triwulan" id="triwulan" placeholder="Triwulan" 
                      value="<?php echo isset($triwulant) ? $triwulan : ''; ?>" style="width:40%"/>
                  </td>
              </tr>
               <tr>
                  <td>Pagu</td>
                  <td>
                      <input type="text" name="pagu" id="pagu" placeholder="pagu" 
                      value="<?php echo isset($pagu) ? $pagu : ''; ?>" style="width:40%"/>
                  </td>
              </tr>
               <tr>
                  <td>Realisasi</td>
                  <td>
                      <input type="text" name="realisasi" id="realisasi" placeholder="Realisasi" 
                      value="<?php echo isset($realisasi) ? $realisasi : ''; ?>" style="width:40%"/>
                  </td>
              </tr>
               <tr>
                  <td>Capaian %</td>
                  <td>
                      <input type="text" name="capaian_triwulan" id="capaian_triwulan" placeholder="Capaian %" 
                      value="<?php echo isset($capaian_triwulan) ? $capaian_triwulan : ''; ?>" style="width:40%"/>
                  </td>
              </tr>
               
              <tr>
                  <td>Ukuran Kinerja Triwulan</td>
                  <td>
                      <input type="text" name="ukuran_kinerja_triwulan" id="ukuran_kinerja_triwulan" placeholder="Ukuran Kinerja Triwulan" 
                      value="<?php echo isset($ukuran_kinerja_triwulan) ? $ukuran_kinerja_triwulan : ''; ?>" style="width:40%"/>
                  </td>
              </tr>
              <tr>
                  <td>Capaian %</td>
                  <td>
                      <input type="text" name="capaian_output_triwulan" id="capaian_output_triwulan" placeholder="Capaian %" 
                      value="<?php echo isset($capaian_output_triwulan) ? $capaian_output_triwulan : ''; ?>" style="width:40%"/>
                  </td>
              </tr>
              <tr>
              <td>Keterangan</td>
                <td>
                  <textarea class="common" name="keterangan"><?php if(!empty($rka->keterangan)){echo $rka->keterangan;} ?></textarea>
                </td>
              </tr>
         </table>
      </div>
          
          <div class="submit_link">  
    			<input type="submit" name="simpan"  id="simpan" value='Simpan'/>
    			<input type="button" value="Keluar" onclick="window.location.href='<?php echo site_url('rka_perubahan'); ?>'" />
                        <input type="button" value="Batal" onclick="window.location='<?php 
              	$call_from = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
              	if(!empty($call_from) && strpos($call_from, 'rka_perubahan/cru_rka') == FALSE)
              		echo $call_from;
              	else 
              		echo site_url('rka_perubahan/');
              ?>'"/>
  		    </div> 
   		</form> 		
   	</div> 	
   	<footer>
  		
  	</footer>
</article>