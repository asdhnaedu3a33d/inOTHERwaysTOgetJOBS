<script type="text/javascript">
	$(document).ready(function(){
		$('#jumlah_dana').autoNumeric(numOptionsNotRound);
        prepare_chosen();
		$("form#musrenbangcam").validate({
			rules: {
        jenis_pekerjaan: "required",
        volume: "required",
        satuan: "required",
        jumlah_dana : "required",

			}
	    });

		$("#simpan").click(function(){
		    var valid = $("form#musrenbangcam").valid();
		    if (valid) {
                $("#jumlah_dana").val($("#jumlah_dana").autoNumeric('get'));

		    	$("form#musrenbangcam").submit();
		    };
		});
	});
</script>
<article class="module width_full">
 	<header>
 		<h3>
		<?php
			if (isset($isEdit) && $isEdit){
			    echo "Edit Data Musrenbangcam";
			} else{
			    echo "Input Data Musrenbangcam";
			}
		?>
		</h3>
 	</header>
    <content>
 	<!-- <div class="module_content"> -->
 		<form action="<?php echo $url_save_data;?>" method="POST" name="musrenbangcam" id="musrenbangcam" accept-charset="UTF-8" enctype="multipart/form-data" >
 			<input type="hidden" name="id_musrenbang" id="id_musrenbang" value="<?php if(!empty($id_musrenbang)){echo $id_musrenbang;} ?>" />
			<input type="hidden" name="id_asal_usulan" id="id_asal_usulan" value="<?php if(!empty($id_asal_usulan)){echo $id_asal_usulan;} ?>" />
			<input type="hidden" name="id_kecamatan" id="id_kecamatan" value="<?php if(!empty($id_kecamatan)){echo $id_kecamatan;} ?>" />
			<table id="musrembang_input" class="fcari" style="max-width: 96%;" align="center">
                <tr>
                    <td width="30%">Jenis Pekerjaan</td>
                    <td width="70%">
                        <input type="text" name="jenis_pekerjaan" id="jenis_pekerjaan" placeholder="Jenis Pekerjaan"
                        value="<?php echo isset($jenis_pekerjaan) ? $jenis_pekerjaan : ''; ?>" style="width:40%"/>
                    </td>
                </tr>
                <tr>
                    <td>Volume</td>
                    <td>
                        <input type="text" name="volume" id="volume" placeholder="Volume"
                        value="<?php echo isset($volume) ? $volume : ''; ?>" style="width:40%"/>
                    </td>
                </tr>
                <tr>
                    <td>Satuan</td>
                    <td>
                        <input type="text" name="satuan" id="satuan" placeholder="Satuan"
                        value="<?php echo isset($satuan) ? $satuan : ''; ?>" style="width:40%"/>
                    </td>
                </tr>
                <tr>
                    <td>Jumlah Dana</td>
                    <td>
                        <input name='jumlah_dana' type='text' id="jumlah_dana" class='currency'
                         value="<?php
                        echo isset($jumlah_dana) ? $jumlah_dana : '0'
                      ?>" style="width:40%">
                  </td>
                </tr>
                <tr>
                    <td>Lokasi</td>
                    <td>
                        <input name='lokasi' type='text' id="lokasi"
                         value="<?php
                        echo isset($lokasi) ? $lokasi : ''
                      ?>" style="width:40%">
                  </td>
                </tr>
                <tr>
                    <td>SKPD Tujuan</td>
                    <td>
                        <?php echo $id_skpd; ?>
                    </td>
                </tr>
            </table>
 		</form>
 	<!-- </div> -->
    </content>
 	<footer>
		<div class="submit_link">
  			<input type='button' id="simpan" name="simpan" value='Simpan' />
  			<input type="button" value="Keluar" onclick="window.location.href='<?php echo site_url('penentuan_skpd'); ?>'" />
		</div>
	</footer>
</article>
