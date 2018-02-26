<script type="text/javascript">
  $(document).ready(function(){

    $("form#pendapatan").validate({

    });
    $("#simpan").click(function(){
      var valid = $("form#pendapatan").valid();

      $("form#pendapatan").submit();
    });







  });

</script>

<article class="module width_full">
 	<header>
 		<h3>
		<?php
			if (isset($isEdit) && $isEdit){
			    echo "Edit Data Pendapatan";
			} else{
			    echo "Input Data Pendapatan";
			}
		?>
		</h3>
 	</header>
 	<div class="module_content">
    <form action="<?php echo $url_save_data;?>" method="POST" name="pendapatan" id="pendapatan" accept-charset="UTF-8" enctype="multipart/form-data" >
      <input type="hidden" name="id" id="id" value="<?php if(!empty($id)){echo $id;} ?>" />
      <input type="hidden" name="id_jenis_pendapatan" id="id_jenis_pendapatan" value="<?php if(!empty($id_jenis_pendapatan)){echo $id_jenis_pendapatan;} ?>" />
      <input type="hidden" name="tahun" id="tahun" value="<?php if(!empty($tahun)){echo $tahun;} ?>" />
      <table id="musrembang_input" class="fcari" width="100%">
           <tr>
                <td>Nama Jenis Pendapatan</td>
                <td>
                    <input type="text" value="<?php echo $nama ?>" style="width:40%"/>

                </td>
            </tr>

            <tr>
                <td>Realisasi <?php echo ($tahun - 4)?></td>
                <td>
                    <input type="text" name="realisasi_n_3" id="realisasi_n_3" placeholder="Tahun <?php echo ($tahun - 4)?>"
                    value="<?php echo isset($realisasi_n_3) ? $realisasi_n_3 : ''; ?>" style="width:40%"/>
                </td>
            </tr>

            <tr>
                <td>Realisasi <?php echo ($tahun - 3)?></td>
                <td>
                    <input type="text" name="realisasi_n_2" id="realisasi_n_2" placeholder="Tahun <?php echo ($tahun - 3)?>"
                    value="<?php echo isset($realisasi_n_2) ? $realisasi_n_2 : ''; ?>" style="width:40%"/>
                </td>
            </tr>

            <tr>
                <td>Realisasi <?php echo ($tahun - 2)?></td>
                <td>
                    <input type="text" name="realisasi_n_1" id="realisasi_n_1" placeholder="Tahun <?php echo ($tahun -2)?>"
                    value="<?php echo isset($realisasi_n_1) ? $realisasi_n_1 : ''; ?>" style="width:40%"/>
                </td>
            </tr>

            <tr>
                <td>Anggaran <?php echo ($tahun -1)?></td>
                <td>
                    <input type="text" name="anggaran" id="anggaran" placeholder="Tahun <?php echo ($tahun -1)?>"
                    value="<?php echo isset($anggaran) ? $anggaran : ''; ?>" style="width:40%"/>
                </td>
            </tr>

            <tr>
                <td>Proyeksi <?php echo ($tahun)?></td>
                <td>
                    <input type="text" name="proyeksi" id="proyeksi" placeholder="Tahun <?php echo ($tahun)?>"
                    value="<?php echo isset($proyeksi) ? $proyeksi : ''; ?>" style="width:40%"/>
                </td>
            </tr>

          </table>
 		</form>
 	</div>
 	<footer>
		<div class="submit_link">
  			<input type='button' id="simpan" name="simpan" value='Simpan' />
  			<input type="button" value="Keluar" onclick="window.location.href='<?php echo site_url('pendapatan_daerah'); ?>'" />
		</div>
	</footer>
</article>
