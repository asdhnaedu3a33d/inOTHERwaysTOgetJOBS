<script type="text/javascript">

	$(document).ready(function(){


		$("#cetak-kegiatanPilih").click(function(){

			var ta = $('#tahun_anggaran').val();
			var idK = $("#idK").val();
			$.blockUI({
				message: 'Cetak dokumen sedang di proses, mohon ditunggu hingga file terunduh secara otomatis ...',
				css: window._css,
				timeout: 2000,
				overlayCSS: window._ovcss
			});

			var link = "<?php echo site_url('renstra/cetak_kegiatan');?>/" + ta + "/" + idK;
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
        ?>

						<input type="hidden" name="id_keg" id="idK" value="<?php echo $id_keg; ?>">
         		<div class="form-group">
              <select class="form-control"  id="tahun_anggaran">
								<?php
	                    foreach ($t_anggaran as $row) {
	              ?>
									<option id="ta" value=<?php echo $row->tahun_anggaran; ?> ><?php echo $row->tahun_anggaran; ?></option>
	              <?php
	              }
	              ?>
            	</select>
              </div>

              <div class="submit_link">
						        <input id="cetak-kegiatanPilih" type="button" value="Cetak">
						    </div>
              </div>
