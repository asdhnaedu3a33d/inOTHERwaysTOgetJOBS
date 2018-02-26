<article class="module width_full" style="width: 100%;">
	<header>
	  <h3>Preview CIK Perubahan</h3>
	</header>
    <div class="module_content">
    <fieldset>
    <form action="<?php echo site_url('cik_perubahan/do_cetak_preview_pusat')?>" id="cik_form" method="POST">
	<table width="99.9%">
        <td width="5%"> Bulan :</td>
		<td width="25%"><select name="id_bulan" id="id_bulan">
            <option value="1">Januari</option>
            <option value="2">Februari</option>
            <option value="3">Maret</option>
            <option value="4">April</option>
            <option value="5">Mei</option>
            <option value="6">Juni</option>
            <option value="7">Juli</option>
            <option value="8">Agustus</option>
            <option value="9">September</option>
            <option value="10">Oktober</option>
            <option value="11">November</option>
            <option value="12">Desember</option>
        </select>
        </td>
        <td width="5%"> <input type="button" id="search" class="btn" value="Search"/></td>
        <td width="75%"> <input type="button" href="<?= base_url('cik_perubahan/do_export_pusat') ?>" id="export" class="btn" value="Cetak"/></td>
	</table>
    </form>
    </fieldset>
    <table id="tabel_cik" class="table-display" width="99.7%">
    	<thead>
            <tr>
                <th rowspan="2" colspan="4">KODE</th>
                <th rowspan="2">PROGRAM DAN KEGIATAN</th>
                <th colspan="3">ANGGARAN</th>
                <th colspan="4">KELOMPOK INDIKATOR KINERJA PROGRAM (OUTCOME) / INDIKATOR KINERJA KEGIATAN (OUTPUT)</th>
                <th rowspan="2">KET.</th>
            </tr>
            <tr>
                <th>RENCANA (Rp.)</th>
                <th>REALISASI (Rp.)</th>
                <th>CAPAIAN IK (%)</th>
                <th>INDIKATOR/SATUAN</th>
                <th>RENCANA</th>
                <th>REALISASI</th>
                <th>CAPAIAN IK</th>
            </tr>
          </thead>
        <tbody id="preview_cik">		
             	<tr><td colspan='13' align='center'><strong>Belum ada data terpilih..</strong></td></tr>
         </tbody>
	</table>
    </div>
	<footer>
		<div class="submit_link">
			<input type="button" value="Kembali" onclick="history.go(-1)">
		</div>
	</footer>
</article>
<script type="text/javascript">
    $("#search").click(function(){
		$.blockUI();
		var id_bulan = $("#id_bulan").val();
			var data  = {id_bulan:$("#id_bulan").val()};
				$.ajax({
						type: "POST",
						url : "<?php echo site_url('cik_perubahan/get_data_cik_pusat')?>",
						data: data,
						success: function(msg){
							$('#preview_cik').html(msg);
							$.unblockUI();
						}
				});
        });
	
	  $("#cetak").click(function(){
			$("#cik_form").submit();
	  });
	  
	  $("#export").click(function(event){
			event.preventDefault();        
			
			var link = $(this).attr("href");
			
			var bulan = $("select#id_bulan").val();        
	
			window.open(link+'/'+bulan);        
		});			
</script>