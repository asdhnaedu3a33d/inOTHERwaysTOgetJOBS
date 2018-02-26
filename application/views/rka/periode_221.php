<script type="text/javascript">

	$(document).ready(function(){


		$("#cetak-kegiatanPilih").click(function(){

			var is_tahun = $('#tahun_anggaran').val();
			var ta = $('#tahun_anggaran').find(":selected").text();
			var idK = $("#idK").val();
			$.blockUI({
				message: 'Cetak dokumen sedang di proses, mohon ditunggu hingga file terunduh secara otomatis ...',
				css: window._css,
				timeout: 2000,
				overlayCSS: window._ovcss
			});
			var link = "<?php echo site_url("rka/cetak_kegiatan"); ?>/" + ta + "/" + is_tahun + "/" + idK;
			$(location).attr('href',link);
		});



	});
</script>


<div style="width: 800px;">
<table class="fcari" width="800 px" >

	<th > Pilih Tahun Anggaran...</th>


</table>
				<?php
          $user = $this->auth->get_user();
          $t_anggaran = $this->m_settings->get_tahun_anggaran_db();
					$ta 		= $this->m_settings->get_tahun_anggaran();
        ?>
						<input type="hidden" name="idK" id="idK" value="<?php echo $id_keg; ?>">
         		<div class="form-group">
							<select class="form-control"  id="tahun_anggaran">
								<option id="ta" value="1" ><?php echo $ta + 0; ?></option>
								<option id="ta" value="0" ><?php echo $ta + 1; ?></option>
              </select>
            </div>

              <div class="submit_link">
						        <input id="cetak-kegiatanPilih" type="button" value="Cetak">
						    </div>
              </div>
