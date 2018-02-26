<script type="text/javascript">

	$(document).ready(function(){


		$("#cetak-kegiatanPilih").click(function(){
			var idK = $("#jenis").val();
			//alert(idK);
			$.blockUI({
				message: 'Cetak dokumen sedang di proses, mohon ditunggu hingga file terunduh secara otomatis ...',
				css: window._css,
				timeout: 2000,
				overlayCSS: window._ovcss
			});

			var link = "<?php echo site_url('renstra/do_cetak_renstra_byjenis');?>/" + idK;
			$(location).attr('href',link);
		});
	});
</script>


<div style="width: 800px height: 1000px; ">
<table class="fcari" width="800 px" >

	<th > Pilih Jenis Renstra</th>


</table>

        <div class="form-group">
              <select class="form-control"  id="jenis">

				<option  value="TujuanJangkaMenengah">Tujuan Jangka Menengah </option>
				<option  value="SasaranJangkaMenengah">Sasaran Jangka Menengah </option>
				<option  value="KebijakanUmumRenstra">Kebijakan Umum Renstra </option>
				<option  value="TargepaguIndikatifProgramKegiatan">Target & Pagu Indikatif Program/kegiatan</option>

            	</select>
              </div>
            <div class="form-group">
              <div class="submit_link">
		        <input id="cetak-kegiatanPilih" type="button" value="Proses">
		   	 </div>
		   	</div>
   </div>
