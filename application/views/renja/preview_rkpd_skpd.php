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
	  <h3>Preview RKPD Per SKPD</h3>
	</header>
    <div class="module_content">
    <div class="fieldset">
	<table width="99.9%">        
        <td width="50%">
            SKPD : <BR>
            <?= $cmb_skpd ?>
        </td>
        <td width="49%">
            Action : <BR>
            <input type="hidden" id="tahun" value="<?php echo $tahun_renja; ?>"/>
            <input type="button" id="search" class="btn" value="Search"/>
            <input type="button" href="<?= base_url('rkpd_preview/do_cetak_rkpd_skpd') ?>" id="export" class="btn" value="Cetak"/>
        </td>
	</table>
    </div>
    <table id="tabel_rkpd" class="table-display" width="99.7%">
    	<thead>
            <tr>
                <th rowspan="2" colspan="4">Kode</th>
                <th rowspan="2">Program dan Kegiatan</th>
                <th rowspan="2">Indikator Kinerja Program/Kegiatan</th>
                <th colspan="3">Rencana Tahun <?php echo $tahun_renja?></th>
                <th rowspan="2">Catatan</th>
                <th colspan="2">Perkiraan Maju Rencana Tahun <?php echo $tahun_renja+1;?></th>
            </tr>
            <tr>
                <th >Lokasi</th>
                <th >Target Capaian Kinerja</th>
                <th >Kebutuhan Dana/Pagu Indikatif (Rp.)</th>
                <th >Target Capaian Kinerja</th>
                <th >Kebutuhan Dana/Pagu Indikatif (Rp.)</th>
            </tr>
          </thead>
        <tbody id="preview_rkpd">		
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
		var data  = {tahun:$("#tahun").val(), id_skpd:$("#id_skpd").val()};
		$.ajax({
			type: "POST",
			url : "<?php echo site_url('rkpd_preview/get_data_rkpd_skpd')?>",
			data: data,
			success: function(msg){
				$('#preview_rkpd').html(msg);
                $.unblockUI();
			}
		});
    });
	
    $("#export").click(function(event){
        $.blockUI();
        event.preventDefault();        
        
        var link = $(this).attr("href");
        
        var tahun = $("#tahun").val();        
        var id_skpd = $("#id_skpd").val();

        window.open(link+'/'+id_skpd+'/'+tahun);   
        $.unblockUI();
    });		
</script>