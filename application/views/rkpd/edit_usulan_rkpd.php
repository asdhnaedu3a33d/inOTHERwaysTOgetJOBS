<script type="text/javascript">
	$(document).ready(function(){
		//$('#jumlah_dana').autoNumeric(numOptions);

		$("form#edit_usulan_rkpd").validate({
			rules: {
              status: "required",
              
			  
			}
	    });

		$("#simpan").click(function(){			
		    var valid = $("form#edit_usulan_rkpd").valid();
		    if (valid) {		    	
                //$("#jumlah_dana").val($("#jumlah_dana").autoNumeric('get'));

		    	$("form#edit_usulan_rkpd").submit();
		    };
		});
	});
</script>


<article class="module width_full">
    <header>
        <h3>
		Input Data Pengajuan RKPD
		</h3>
 	</header>
 	<div class="module_content">
         <form action="<?php echo $url_save_data;?>" method="POST" name="edit_usulan_rkpd" id="edit_usulan_rkpd" accept-charset="UTF-8" enctype="multipart/form-data" >
            <table id="input_edit_usulan_rkpd" class="fcari" width="100%">                    
            <input type="hidden" name="id_musrenbang" id="id_musrenbang" value="<?php if(!empty($id_musrenbang)){echo $id_musrenbang;} ?>" />
            <tr>
                <td style="width:20%">Nama SKPD</td>
                <td>
                    <?php if(!empty($nama_skpd)){echo $nama_skpd;} ?>               
                </td>
            </tr>
            <tr>
                <td>Kode Urusan</td>
                <td> 
                    <?php if(!empty($urusan)){echo $urusan;} ?>
                </td>
            </tr>
            <tr>
                <td style="width:20%">Nama Urusan</td>
                <td>
                    <?php if(!empty($nm_urusan)){echo $nm_urusan;} ?>               
                </td>
            </tr>
            <tr>
                <td>Kode Bidang</td>
                <td> 
                    <?php if(!empty($bidang)){echo $bidang;} ?>
                </td>
            </tr>
            <tr>
                <td>Nama Bidang</td>
                <td> 
                    <?php if(!empty($nm_bidang)){echo $nm_bidang;} ?>
                </td>
            </tr>
            <tr>
                <td>Kode Program</td>
                <td> 
                    <?php if(!empty($program)){echo $program;} ?>
                </td>
            </tr>
            <tr>
                <td>Nama Program</td>
                <td>          
                  <?php if(!empty($nm_program)){echo $nm_program;} ?>
                </td>
            </tr>
            <tr>
                <td>Kode Kegiatan</td>
                <td> 
                    <?php if(!empty($kegiatan)){echo $kegiatan;} ?>
                </td>
            </tr>
            <tr>
                <td>Nama Kegiatan</td>
                <td>
                    <?php if(!empty($nm_kegiatan)){echo $nm_kegiatan;} ?>        
                </td>
            </tr>
             <tr>
                <td>Jenis Pekerjaan</td>
                <td>
                    <?php if(!empty($jenis_pekerjaan)){echo $jenis_pekerjaan;} ?>        
                </td>
            </tr>
            <tr>
                <td>Jumlah Dana</td>
                <td>
                    <?php if(!empty($jumlah_dana)){echo $jumlah_dana;} ?>        
                </td>
            </tr>
            <tr>
                <td>Volume</td>
                <td>
                    <?php if(!empty($volume)){echo $volume;} ?>        
                </td>
            </tr>
            <tr>
                <td>Satuan</td>
                <td>
                    <?php if(!empty($satuan)){echo $satuan;} ?>        
                </td>
            </tr>
            <tr>
                <td>Lokasi</td>
                <td>
                    <?php echo isset($lokasi) ? $lokasi : ''; ?>
                </td>
            </tr>
            <tr>
                <td>Nama Desa</td>
                <td>
                    <?php echo isset($nama_desa) ? $nama_desa : ''; ?>
                </td>
            </tr>
            <tr>
                <td>Nama Kecamatan</td>
                <td>
                    <?php echo isset($nama_kec) ? $nama_kec : ''; ?>
                </td>
            </tr>
           
            <tr>
                <td>Prioritas</td>
                <td>
                    <select name="prioritas" id="prioritas" style="width: 40%">
                        <?php echo $combo_prioritas;?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Sumber Dana</td>
                <td>
                     <select name="sumberdana" id="sumberdana" style="width: 40%">
                        <?php echo $combo_sumberdana;?>
                    </select>
                </td>
            </tr>
          </table>
 		</form> 		
 	</div> 	
 	<footer>
		<div class="submit_link">  
  			<input type='button' id="simpan" name="simpan" value='Simpan' />
  			<input type="button" value="Keluar" onclick="window.location.href='<?php echo site_url('rkpd/forum_skpd_renja'); ?>'" />
		</div> 
	</footer>
</article>