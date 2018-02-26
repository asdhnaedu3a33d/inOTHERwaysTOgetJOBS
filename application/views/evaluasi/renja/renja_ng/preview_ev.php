<article class="module width_full" style="width: 100%;">
	<header>
	  <h3>Evaluasi Renja</h3>
	</header>
    <div class="module_content">
    <fieldset>
	<table width="99.9%">
		<td width="25%"><select name="id_triwulan" id="id_triwulan">
            <option value="1">Triwulan 1</option>
            <option value="2">Triwulan 2</option>
            <option value="3">Triwulan 3</option>
            <option value="4">Triwulan 4</option>
        </select>
        </td>
        <td width="75%">
            <button style="margin: 0 5px 0 3px;" id="reload_table" title="Refresh Tabel Evaluasi Renja"><i class="fa fa-refresh"></i> Refresh</button>
            <button style="margin: 0 5px 0 3px;" link="#" id="export_btn"><i class='fa fa-file'></i> Export</button>
        </td>
	</table>
    </fieldset>
    <div class="scroll">
    <span id="nama_triwulan"></span>
    <table id="tabel_ev" class="table-common" style="width: 150% !important; max-width: 150%;">
    	<thead>
            <tr>
                <th rowspan="3">No.</th>
                <th rowspan="3" colspan="4">Kode</th>
                <th rowspan="3">Urusan/Bidang Urusan Pemerintahan Daerah dan Program / Kegiatan</th>
                <th rowspan="3">Indikator Kinerja Program (Outcome) / Kegiatan(Output)</th>
                <th rowspan="2" colspan="2">Target  Renstra SKPD Pada Tahun <?php echo $ta_min; ?> (Akhir Periode Renstra)</th>
                <th rowspan="2" colspan="2">Realisasi Capaian Kinerja Renstra SKPD s./d. Renja SKPD Tahun Lalu (<?php echo $ta_min; ?>)</th>
                <th rowspan="2" colspan="2">Target Kinerja & Anggaran Renja SKPD Tahun Berjalan Yang Dievaluasi <?php echo $ta; ?></th>
                <th colspan="8">Realisasi Kinerja Pada Triwulan</th>
                <th rowspan="2" colspan="2">Realisasi Capaian Kinerja dan Anggaran Renja SKPD Yang Dievaluasi (<?php echo $ta; ?>)</th>
                <th rowspan="2" colspan="2">Tingkat Capaian Kinerja & Anggaran Renja SKPD Yang Dievaluasi (<?php echo $ta; ?>)</th>
                <th rowspan="2" colspan="2">Realisasi Kinerja & Anggaran Renstra SKPD s/d Tahun Berjalan (<?php echo $ta; ?>)</th>
                <th rowspan="2" colspan="2">Tingkat Capaian Kinerja & Realisasi Anggaran Renstra SKPD s/d Tahun <?php echo $ta; ?> (%)</th>
                <th rowspan="3">Unit SKPD Penanggung Jawab</th>
                <th rowspan="2" colspan="3">Ket.</th>
            </tr>
            <tr>
                <th colspan="2">I</th>
                <th colspan="2">II</th>
                <th colspan="2">III</th>
                <th colspan="2">IV</th>
            </tr>
            <tr>
                <th>K</th>
                <th>Rp</th>
                <th>K</th>
                <th>Rp</th>
                <th>K</th>
                <th>Rp</th>
                <th>K</th>
                <th>Rp</th>
                <th>K</th>
                <th>Rp</th>
                <th>K</th>
                <th>Rp</th>
                <th>K</th>
                <th>Rp</th>
                <th>K</th>
                <th>Rp</th>
                <th>K</th>
                <th>Rp</th>
                <th>K</th>
                <th>Rp</th>
                <th>K</th>
                <th>Rp</th>
                <th>5t</th>
                <th>1t</th>
                <th>R</th>
            </tr>
        </thead>
        <tbody id="preview_body">
            <tr>
                <th colspan='33' align='center'><strong>Belum ada data terpilih..</strong></th>
            </tr>
            <!-- <tr>
                <th colspan="23">Total Rata-rata Capaian Kinerja dan Anggaran Dari Seluruh Program (%)</th>
                <th>-</th>
                <th>-</th>
                <th></th>
                <th></th>
                <th>-</th>
                <th>-</th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
            <tr>
                <th colspan="23">Predikat Kinerja Dari Seluruh Program</th>
                <th>-</th>
                <th>-</th>
                <th></th>
                <th></th>
                <th>-</th>
                <th>-</th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
            </tr> -->
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
    $("#reload_table").click(function(){
        $('#preview_body').html("<tr><th colspan='33' align='center'><i class='fa fa-refresh fa-spin'></i>&emsp; Mohon menunggu..</th></tr>");
        var tw_romawi = ["", "Triwulan I", "Triwulan II", "Triwulan III", "Triwulan IV"];
		var id_triwulan = $("#id_triwulan").val();
		$('#nama_triwulan').html(tw_romawi[id_triwulan]);
		$.ajax({
			type: "POST",
			url : "<?php echo site_url('evaluasi_renja/get_data_evaluasi_renja')?>",
			data: {id_triwulan : id_triwulan},
			success: function(msg){
				$('#preview_body').html(msg);
                $('#id_triwulan').val(id_triwulan);
			}
		});
     });


</script>
