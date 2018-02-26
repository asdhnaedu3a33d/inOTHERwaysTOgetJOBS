<script type="text/javascript">

	$(document).ready(function(){


		$("#cetak-kegiatanPilih").click(function(){

			var ta_tahun = $('#tahun_anggaran').find(":selected").text();
			//alert(ta_tahun)

			var ta = $('#tahun_anggaran').val();
			var idK = $('#idk').val();
			$.blockUI({
				message: 'Cetak dokumen sedang di proses, mohon ditunggu hingga file terunduh secara otomatis ...',
				css: window._css,
				timeout: 2000,
				overlayCSS: window._ovcss
			});

			var link = "<?php echo site_url('renja_perubahan/cetak_kegiatan');?>/" + ta + "/" + ta_tahun + "/" + idK ;
			//alert(link);
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
         <?php
         ?>
         		<div class="form-group">
         		<input  type="hidden" id="idk" value ="<?php echo $id_kegiatan?>" />

                            <select class="form-control"  id="tahun_anggaran">
          <?php
         ?>




                                <option id="ta" value="1"><?php echo $this->session->userdata('t_anggaran_aktif')?></option>
                                <option id="ta" value="0"><?php echo $this->session->userdata('t_anggaran_aktif')+1?></option>


                    <?php

                    ?>
                                </select>
              </div>

              <div class="submit_link">
						        <input id="cetak-kegiatanPilih" type="button" value="Cetak">
						    </div>
              </div>
