<div style="width: 900px">
	<header>
		<h3>Kirim Data Evaluasi Renja</h3>
	</header>
    <div class="module_content">
        <table id="table_veri" class="table-common" width="99%">
        	<thead>
            	<tr>
                	<th>Keterangan Data</th>
                    <th>Triwulan</th>
                    <th>Jumlah</th>
                    <th align="center" width="10px"><i style="margin:5px" class="icon2-script_gear" title="Aksi"></i></th>
                </tr>
            </thead>
        	<tbody>
            </tbody>
        </table>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        $("#table_veri").on("click", ".send_btn", function(){
                $.blockUI({
                    css: window._css,
                    overlayCSS: window._ovcss
                });

                $.ajax({
                    type: "POST",
                    url: "<?= site_url('evaluasi_renja/send_veri') ?>",
                    data: {triwulan: $(this).attr("tw"), status: $(this).attr("st")},
                    dataType: "json",
                    success: function(msg){
                        if (msg.success==1) {
                            $.blockUI({
                                message: msg.msg,
                                timeout: 2000,
                                css: window._css,
                                overlayCSS: window._ovcss
                            });
                            load_table_veri();
                            reload_table();
                        };
                    }
                });
        });

        load_table_veri();
    });

    function load_table_veri(){
        $.blockUI({
          css: window._css,
          overlayCSS: window._ovcss
        });

        $.ajax({
            type: "POST",
            url: '<?php echo site_url("evaluasi_renja/get_table_veri"); ?>',
            dataType: "json",
            data: {},
            success: function(msg){
                catch_expired_session2(msg);
                $("#table_veri tbody").html(msg.html);
                $.unblockUI();
            }
        });
    }
</script>
