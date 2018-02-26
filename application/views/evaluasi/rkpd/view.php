<article class="module width_full" style="width: 130%; margin-left: -15%;">
	<header>
	  <h3 style="width: auto;">
	  	Evaluasi Renja Pusat
	  </h3>
	</header>
  <div class="module_content" style="overflow:auto">
    <div class="row">
      <div class="col-xs-12 col-sm-4">
        <select data-placeholder="Pilih Triwulan" class="common" id="pilih_tw">
        <?php
          for ($i=1; $i <= 4; $i++) {
        ?>
            <option value="<?= $i ?>">Triwulan <?= $i ?></option>
        <?php
          }
        ?>
        </select>
      </div>
      <div class="col-xs-12 col-sm-8">
    		<button style="margin: 0 5px 0 3px;" id="reload_table" title="Refresh Tabel Evaluasi Renja"><i class="fa fa-refresh"></i> Refresh</button>
        <button style="margin: 0 5px 0 3px;" link="<?= site_url('evaluasi_rkpd/export') ?>" id="export_btn"><i class='fa fa-file'></i> Export</button>
      </div>
    </div>
    <div class="row">
      <div class="col-xs-12 col-sm-12">
        <div style="overflow:auto" id="table-rkpd"></div>
      </div>
    </div>
  </div>
</article>
<script type="text/javascript">
	$(document).ready(function(){
    prepare_chosen();

		$("#export_btn").click(function(){
			prepare_facebox();
			$.blockUI({
				css: window._css,
				overlayCSS: window._ovcss
			});
			var tw = $("#pilih_tw").val();
			window.open($(this).attr("link")+'/'+tw);
			$.unblockUI();
		});

    $("#reload_table").click(function(){
      if ($("#pilih_tw").val() == '') {
        alert('Triwulan belum dipilih ...');
        return false;
      }
      reload_table();
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
			url: '<?php echo site_url("evaluasi_rkpd/get_table_data"); ?>',
			dataType: "json",
			data: {tw: $("#pilih_tw").val()},
			success: function(msg){
				console.log(msg);
				catch_expired_session2(msg);
				$("div#table-rkpd").html(msg.html);
				$.unblockUI();
			}
		});
	}
</script>
