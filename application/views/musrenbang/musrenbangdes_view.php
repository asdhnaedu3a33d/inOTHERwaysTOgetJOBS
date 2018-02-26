<script type="text/javascript">
	$(document).ready(function(){
		$('#jumlah_dana').autoNumeric(numOptionsNotRound);

		jQuery.validator.addMethod("kode_autocomplete", function(value, element, params){
		    if ($("input[name="+ params +"]").val()=="") {
		    	return false;
		    }else{
		    	return true;
		    }
		}, "Data tidak valid/belum di pilih, mohon pilih data setelah melakukan pencarian pada kolom ini.");

		$("form#musrenbangdes").validate({
			rules: {
              jenis_pekerjaan: "required",
              volume: "required",
              satuan: "required",
              jumlah_dana : "required",
			  lokasi : "required",

			}
	    });

		$("#simpan").click(function(){
		    var valid = $("form#musrenbangdes").valid();
		    if (valid) {
                $("#jumlah_dana").val($("#jumlah_dana").autoNumeric('get'));

		    	$("form#musrenbangdes").submit();
		    };
		});


	});
</script>
<article class="module width_full">
 	<header>
 		<h3>
		<?php
			if (isset($isEdit) && $isEdit){
			    echo "Edit Data Musrenbangdes";
			} else{
			    echo "Input Data Musrenbangdes";
			}
		?>
		</h3>
 	</header>
 	<div class="module_content">
 		<form action="<?php echo $url_save_data;?>" method="POST" name="musrenbangdes" id="musrenbangdes" accept-charset="UTF-8" enctype="multipart/form-data" >
 			<input type="hidden" name="id_musrenbang" value="<?php if(!empty($id_musrenbang)){echo $id_musrenbang;} ?>" />
            <table id="musrembang_input" class="fcari" width="100%">

            <tr>
                <td style="width:20%">Jenis Pekerjaan</td>
                <td>
                    <input type="text" name="jenis_pekerjaan" id="jenis_pekerjaan" placeholder="Jenis Pekerjaan"
                    value="<?php echo isset($jenis_pekerjaan) ? $jenis_pekerjaan : ''; ?>" style="width:40%"/>
                </td>
            </tr>
            <tr>
                <td style="width:20%">Volume</td>
                <td>
                    <input type="text" name="volume" id="volume" placeholder="Volume"
                    value="<?php echo isset($volume) ? $volume : ''; ?>" style="width:40%"/>
                </td>
            </tr>
            <tr>
                <td style="width:20%">Satuan</td>
                <td>
                    <input type="text" name="satuan" id="satuan" placeholder="Satuan"
                    value="<?php echo isset($satuan) ? $satuan : ''; ?>" style="width:40%"/>
                </td>
            </tr>
            <tr>
                <td style="width:20%">Jumlah Dana</td>
                <td>
                    <input name='jumlah_dana' type='text' id="jumlah_dana" class='currency'
                     value="<?php
                    echo isset($jumlah_dana) ? $jumlah_dana : '0'
                  ?>" style="width:40%">
              </td>
            </tr>
            <tr>
                <td style="width:20%">Lokasi</td>
                <td>
                    <input type="text" name="lokasi" id="lokasi" placeholder="Lokasi"
                    value="<?php echo isset($lokasi) ? $lokasi : ''; ?>" style="width:40%"/>
                </td>
            </tr>
            <tr>
                <td></td>
                <td colspan="2">
                <?php
                    include_once("file_upload.php");
                ?>
                </td>
        	</tr>
          </table>
 		</form>
 	</div>
 	<footer>
		<div class="submit_link">
  			<input type='button' id="simpan" name="simpan" value='Simpan' />
  			<input type="button" value="Keluar" onclick="window.location.href='<?php echo site_url('musrenbangdes'); ?>'" />
		</div>
	</footer>
</article>
