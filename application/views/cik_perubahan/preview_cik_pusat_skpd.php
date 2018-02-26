<style type="text/css">
    .common{
        width: 100%
    }
    .fieldset{
        -webkit-border-radius: 5px; 
        -moz-border-radius: 5px; 
        border-radius: 5px; 
        background: #F6F6F6; 
        border: 1px solid #ccc; 
        padding: 1% 0%; 
        margin: 10px 0;
    }

    .module_content{
        overflow-y: initial;
    }
</style>
<article class="module width_full" style="width: 100%;">
	<header>
	  <h3>Preview CIK Perubahan Per SKPD</h3>
	</header>
    <div class="module_content">
    <div class="fieldset">
    <form action="<?php echo site_url('cik_perubahan/do_cetak_preview_pusat')?>" id="cik_form" method="POST">
	<table width="99.9%">        
		<td width="30%">
            Bulan : <BR>
            <select name="id_bulan" id="id_bulan" class="common chosen-select">
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
        <td width="45%">
            SKPD : <BR>
            <?= $cmb_skpd ?>
        </td>
        <td width="25%">
            Action : <BR>
            <input type="button" id="search" class="btn" value="Search"/>
            <input type="button" href="<?= base_url('cik_perubahan/do_export_pusat_skpd') ?>" id="export" class="btn" value="Cetak"/>
        </td>
	</table>
    </form>
    </div>
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
    prepare_chosen();
    $("#search").click(function(){	
        $.blockUI();	
		var data  = {id_bulan:$("#id_bulan").val(), id_skpd:$("#id_skpd").val()};
		$.ajax({
			type: "POST",
			url : "<?php echo site_url('cik_perubahan/get_data_cik_pusat_skpd')?>",
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
        $.blockUI();
        event.preventDefault();        
        
        var link = $(this).attr("href");
        
        var bulan = $("select#id_bulan").val();        
        var id_skpd = $("#id_skpd").val();

        window.open(link+'/'+id_skpd+'/'+bulan);   
        $.unblockUI();
    });		
</script>