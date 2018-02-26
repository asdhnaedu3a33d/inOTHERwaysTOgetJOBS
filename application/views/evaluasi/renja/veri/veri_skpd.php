<article class="module width_full" style="width: 130%; margin-left: -15%;">
	<header>
	  <h3 style="width: auto;">
	  	Verifikasi Evaluasi Renja <?= $skpd->nama_skpd ?>
	  </h3>
	  <a href="<?= site_url('evaluasi_renja/veri') ?>"><input style="margin: 5px 3px; float: right;" type="button" value="Kembali" /></a>
	</header>
	<input type="hidden" id="id_skpd" value="<?= $skpd->id_skpd ?>" />
	<input type="hidden" id="triwulan" value="<?= $triwulan ?>" />
	<input type="hidden" id="tahun" value="<?= $tahun ?>" />
  <div id="table-renja" class="module_content" style="overflow:auto">
  </div>
</article>
<script type="text/javascript">
	$(document).ready(function(){
		$("#table-renja").on("click", ".veri", function(){
			prepare_facebox();
			$.blockUI({
				css: window._css,
				overlayCSS: window._ovcss
			});
			$.ajax({
				type: "POST",
				url: '<?php echo site_url("evaluasi_renja/veri_form"); ?>',
				dataType: "json",
				data: {idev:$(this).attr("idev"), tw: $(this).attr("tw")},
				success: function(msg){
					catch_expired_session2(msg);
					$.facebox(msg.html);
					$.unblockUI();
				}
			});
		});

	// 	$("#table-renja").on("click", ".edit-evaluasi", function(){
	// 		//var idr = $(this).parent().parent().attr("id-r");

	// 		prepare_facebox();
	// 		$.blockUI({
	// 			css: window._css,
	// 			overlayCSS: window._ovcss
	// 		});
	// 		$.ajax({
	// 			type: "POST",
	// 			url: '<?php echo site_url("evaluasi_renja/cru_evaluasi_renja"); ?>',
	// 			dataType: "json",
	// 			data: {idr:$(this).attr("idr"), idi: $(this).attr("idi"), tw: $(this).attr("tw")},
	// 			success: function(msg){
	// 				catch_expired_session2(msg);
	// 				$.facebox(msg.html);
	// 				$.unblockUI();
	// 			}
	// 		});
	// 	});

	// 	$("#veri_btn").click(function(){
	// 		prepare_facebox();
	// 		$.blockUI({
	// 			css: window._css,
	// 			overlayCSS: window._ovcss
	// 		});
	// 		$.ajax({
	// 			type: "POST",
	// 			url: '<?php echo site_url("evaluasi_renja/get_veri"); ?>',
	// 			dataType: "json",
	// 			data: {},
	// 			success: function(msg){
	// 				catch_expired_session2(msg);
	// 				$.facebox(msg.html);
	// 				$.unblockUI();
	// 			}
	// 		});
	// 	});

		reload_table();
	});

	function reload_table(){
		$.blockUI({
			css: window._css,
			overlayCSS: window._ovcss
		});

		$.ajax({
			type: "POST",
			url: '<?php echo site_url("evaluasi_renja/get_table_veri_skpd"); ?>',
			dataType: "json",
			data: {id_skpd: $("#id_skpd").val(), triwulan: $("#triwulan").val(), tahun: $("#tahun").val()},
			success: function(msg){
				catch_expired_session2(msg);
				$("#table-renja").html(msg.html);
				$.unblockUI();
			}
		});
	}
</script>
