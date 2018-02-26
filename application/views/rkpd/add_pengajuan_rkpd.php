<script type="text/javascript">
	$(document).ready(function(){
		//$('#jumlah_dana').autoNumeric(numOptions);

		$("form#pengajuan_rkpd").validate({
			rules: {
              prioritas: "required",
              sumber_dana: "required",
			  
			}
	    });

		$("#simpan").click(function(){			
		    var valid = $("form#pengajuan_rkpd").valid();
		    if (valid) {		    	
                //$("#jumlah_dana").val($("#jumlah_dana").autoNumeric('get'));

		    	$("form#pengajuan_rkpd").submit();
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
 		<form action="<?php echo $url_save_data;?>" method="POST" name="pengajuan_rkpd" id="pengajuan_rkpd" accept-charset="UTF-8" enctype="multipart/form-data" >
 			<input type="hidden" name="id_musrenbang" value="<?php if(!empty($id_musrenbang)){echo $id_musrenbang;} ?>" />
 			<input type="hidden" name="id_renstra" value="<?php if(!empty($id_renstra)){echo $id_renstra;} ?>" />
             <table id="input_pengajuan_rkpd" class="fcari" width="100%">                    
    
            <tr>
                <td style="width:20%">Nama Urusan</td>
                <td>
                    <?php if(!empty($nm_urusan)){echo $nm_urusan;} ?>               
                </td>
            </tr>
            <tr>
                <td>Nama Bidang</td>
                <td> 
                    <?php if(!empty($nm_bidang)){echo $nm_bidang;} ?>
                </td>
            </tr>
            <tr>
                <td>Nama Program</td>
                <td>          
                  <?php if(!empty($nm_program)){echo $nm_program;} ?>
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
                    <?php echo isset($jenis_pekerjaan) ? $jenis_pekerjaan : ''; ?>
                </td>
            </tr>
            <tr>
                <td>Volume</td>
                <td>
                   <?php echo isset($volume) ? $volume : ''; ?>
                </td>
            </tr>
            <tr>
                <td>Satuan</td>
                <td>
                  <?php echo isset($satuan) ? $satuan : ''; ?>
                </td>
            </tr>
            <tr>
                <td>Jumlah Dana</td>
                <td>
                    <?php echo isset($jumlah_dana) ? $jumlah_dana : '0' ?>
              </td>
            </tr>
            <tr>
                <td>Lokasi</td>
                <td>
                    <?php echo isset($lokasi) ? $lokasi : ''; ?>
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
  			<input type="button" value="Keluar" onclick="window.location.href='<?php echo site_url('rkpd/pengajuan_rkpd'); ?>'" />
		</div> 
	</footer>
</article>