<article class="module width_full" style="width: 130%; margin-left: -15%;">
	<header>
	  <h3 style="width: auto;">
	  	Evaluasi Renja Pusat
	  </h3>
	</header>
  <div style="padding-top: 10px; padding-left: 22px;">
    <select data-placeholder="Pilih SKPD" style="width: 500px;" class="common chosen-select" id="pilih_skpd">
      <option></option>
    <?php
      foreach ($skpd as $key => $value) {
    ?>
        <option value="<?= $value->id_skpd ?>"><?= $value->nama_skpd ?></option>
    <?php
      }
    ?>
    </select>
    <input style="margin: 5px 0 5px 3px;" type="button" id="export_btn" value="Export" />
    <input style="margin: 5px 0 5px 3px;" type="button" id="cetak_btn" value="Cetak" />
	  <input style="margin: 5px 0 5px 3px;" type="button" id="preview_btn" value="Preview" />
    <button style="margin: 5px 30px 5px 3px; float:right;" id="reload_table" title="Refresh Tabel Evaluasi Renja"><img src="<?= site_url('asset/images/detail2.png') ?>"> Refresh</button>
  </div>
    <div id="table-renja" class="module_content" style="overflow:auto">
      <table class="table-common tablesorter" style="width:99%" >
        <tr>
          <th colspan="4">KODE</th>
          <th>PROGRAM DAN KEGIATAN</th>
          <th>INDIKATOR KINERJA PROGRAM / KEGIATAN</th>
          <th colspan="2">TW1</th>
          <th colspan="2">TW2</th>
          <th colspan="2">TW3</th>
          <th colspan="2">TW4</th>
        </tr>
        <tr>
          <td colspan="13">
            Data SKPD Belum Dipilih
          </td>
        </tr>
      </table>
    </div>
</article>
<script type="text/javascript">
	$(document).ready(function(){
    prepare_chosen();

    $("#table-renja").on("click", ".lihat-evaluasi", function(){
			//var idr = $(this).parent().parent().attr("id-r");

			prepare_facebox();
			$.blockUI({
				css: window._css,
				overlayCSS: window._ovcss
			});
			$.ajax({
				type: "POST",
				url: '<?php echo site_url("evaluasi_renja/get_evaluasi_renja/PUSAT"); ?>',
				dataType: "json",
				data: {idev:$(this).attr("idev"), tw: $(this).attr("tw")},
				success: function(msg){
					catch_expired_session2(msg);
					$.facebox(msg.html);
					$.unblockUI();
				}
			});
		});

    $("#preview_btn").click(function(){
			prepare_facebox();
			$.blockUI({
				css: window._css,
				overlayCSS: window._ovcss
			});
			$.ajax({
				type: "POST",
				url: '<?php echo site_url("evaluasi_renja/pusat_preview_list/2"); ?>',
				dataType: "json",
				data: {id_skpd: $("#pilih_skpd").val()},
				success: function(msg){
					catch_expired_session2(msg);
					$.facebox(msg.html);
					$.unblockUI();
				}
			});
		});

		$("#cetak_btn").click(function(){
			prepare_facebox();
			$.blockUI({
				css: window._css,
				overlayCSS: window._ovcss
			});
			$.ajax({
				type: "POST",
				url: '<?php echo site_url("evaluasi_renja/pusat_preview_list/1"); ?>',
				dataType: "json",
				data: {id_skpd: $("#pilih_skpd").val()},
				success: function(msg){
					catch_expired_session2(msg);
					$.facebox(msg.html);
					$.unblockUI();
				}
			});
		});

		$("#export_btn").click(function(){
			prepare_facebox();
			$.blockUI({
				css: window._css,
				overlayCSS: window._ovcss
			});
			$.ajax({
				type: "POST",
				url: '<?php echo site_url("evaluasi_renja/pusat_preview_list/3"); ?>',
				dataType: "json",
				data: {id_skpd: $("#pilih_skpd").val()},
				success: function(msg){
					catch_expired_session2(msg);
					$.facebox(msg.html);
					$.unblockUI();
				}
			});
		});

    $("#pilih_skpd").change(function(){
      reload_table();
    });

    $("#reload_table").click(function(){
      if ($("#pilih_skpd").val() == '') {
        alert('SKPD belum dipilih ...');
        return false;
      }
      reload_table();
    });
	});

	function reload_table(){
		$.blockUI({
			css: window._css,
			overlayCSS: window._ovcss
		});

		$.ajax({
			type: "POST",
			url: '<?php echo site_url("evaluasi_renja/pusat_get_table_data"); ?>',
			dataType: "json",
			data: {id_skpd: $("#pilih_skpd").val()},
			success: function(msg){
				catch_expired_session2(msg);
				$("#table-renja").html(msg.html);
				$.unblockUI();
			}
		});
	}
</script>
