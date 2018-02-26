<article class="module width_full">
	<header>
	  <h3 style="width: auto;">
	  	Evaluasi Renja <?= $skpd->nama_skpd ?>
	  </h3>
	</header>
  <div class="module_content" style="overflow:auto">
    <div class="form-group text-right">
      <button style="margin: 5px 3px;" type="button" id="veri_btn" value="Verifikasi"><i class='fa fa-check-square-o'></i> Verifikasi</button>
      <button style="margin: 5px 3px;" type="button" id="export_btn" value="Export"><i class='fa fa-file'></i> Export</button>
  	  <button style="margin: 5px 3px;" type="button" id="cetak_btn" value="Cetak"><i class='fa fa-print'></i> Cetak</button>
      <button style="margin: 5px 12px 5px 3px;" type="button" id="preview_btn" value="Preview"><i class='fa fa-sticky-note'></i> Preview</button>
    </div>
    <div id="table-renja"></div>
  </div>
</article>
<script type="text/javascript">
	$(document).ready(function(){
		$("#table-renja").on("click", ".edit-evaluasi", function(){
			//var idr = $(this).parent().parent().attr("id-r");

			prepare_facebox();
			$.blockUI({
				css: window._css,
				overlayCSS: window._ovcss
			});
			$.ajax({
				type: "POST",
				url: '<?php echo site_url("evaluasi_renja/cru_evaluasi_renja"); ?>',
				dataType: "json",
				data: {idr:$(this).attr("idr"), idi: $(this).attr("idi"), tw: $(this).attr("tw")},
				success: function(msg){
					catch_expired_session2(msg);
					$.facebox(msg.html);
					$.unblockUI();
				}
			});
		});

    $("#table-renja").on("click", ".edit-evaluasi-cik", function(){
			//var idr = $(this).parent().parent().attr("id-r");

			prepare_facebox();
			$.blockUI({
				css: window._css,
				overlayCSS: window._ovcss
			});
			$.ajax({
				type: "POST",
				url: '<?php echo site_url("evaluasi_renja/cru_evaluasi_cik"); ?>',
				dataType: "json",
				data: {idr:$(this).attr("idr"), idi: $(this).attr("idi"), tw: $(this).attr("tw")},
				success: function(msg){
					catch_expired_session2(msg);
					$.facebox(msg.html);
					$.unblockUI();
				}
			});
		});

		$("#table-renja").on("click", ".lihat-evaluasi", function(){
			//var idr = $(this).parent().parent().attr("id-r");

			prepare_facebox();
			$.blockUI({
				css: window._css,
				overlayCSS: window._ovcss
			});
			$.ajax({
				type: "POST",
				url: '<?php echo site_url("evaluasi_renja/get_evaluasi_renja"); ?>',
				dataType: "json",
				data: {idev:$(this).attr("idev"), tw: $(this).attr("tw")},
				success: function(msg){
					catch_expired_session2(msg);
					$.facebox(msg.html);
					$.unblockUI();
				}
			});
		});

		$("#veri_btn").click(function(){
			prepare_facebox();
			$.blockUI({
				css: window._css,
				overlayCSS: window._ovcss
			});
			$.ajax({
				type: "POST",
				url: '<?php echo site_url("evaluasi_renja/get_veri"); ?>',
				dataType: "json",
				data: {},
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
				url: '<?php echo site_url("evaluasi_renja/preview_list/2"); ?>',
				dataType: "json",
				data: {},
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
				url: '<?php echo site_url("evaluasi_renja/preview_list/1"); ?>',
				dataType: "json",
				data: {},
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
				url: '<?php echo site_url("evaluasi_renja/preview_list/3"); ?>',
				dataType: "json",
				data: {},
				success: function(msg){
					catch_expired_session2(msg);
					$.facebox(msg.html);
					$.unblockUI();
				}
			});
		});

		reload_table();
	});

	function reload_table(){
		$.blockUI({
			css: window._css,
			overlayCSS: window._ovcss
		});

		$.ajax({
			type: "POST",
			url: '<?php echo site_url("evaluasi_renja/get_table_data"); ?>',
			dataType: "json",
			data: {},
			success: function(msg){
				catch_expired_session2(msg);
				$("#table-renja").html(msg.html);
				$.unblockUI();
			}
		});
	}
</script>
