<script type="text/javascript">
	var dt;
	$(document).ready(function(){
	    dt = $("#file_publik_table").DataTable({
	    	"processing": true,
        	"serverSide": true,
        	"aoColumnDefs": [
                {
                    "bSortable": false,
                    "aTargets": ["no-sort"]
                }
            ],
            "ajax": {
	            "url": "<?php echo base_url('file_publik/load_file_publik'); ?>",
	            "type": "POST"
	        }
	    });

	    $('div.dataTables_filter input').unbind();
	    $("div.dataTables_filter input").keyup( function (e) {
		    if (e.keyCode == 13) {
		        dt.search( this.value ).draw();
		    }
		} );
	});

	function edit_file_publik(id){
		window.location = '<?php echo base_url('file_publik/edit_file_publik/');?>' + '/' + id;
	}

	function delete_file_publik(id){
		if (confirm('Apakah anda yakin untuk menghapus data file ini?')) {
			$.blockUI({
				message: 'Proses penghapusan sedang dilakukan, mohon ditunggu ...',
				css: window._css,
				overlayCSS: window._ovcss
			});

			$.ajax({
				type: "POST",
				url: '<?php echo $url_delete_data; ?>',
				data: {id: id},
				dataType: "json",
				success: function(msg){
					catch_expired_session2(msg);
					if (msg.success==1) {
						$.blockUI({
							message: msg.msg,
							timeout: 2000,
							css: window._css,
							overlayCSS: window._ovcss
						});
                        dt.ajax.reload();

					};
				}
			});
		};
	}

</script>
<article class="module width_full">
	<header>
	  <h3>Tabel File Publik</h3>
	</header>
	<div class="module_content"; style="overflow:auto">
    <div style='width: 100%; display: table;'>
      <a href="<?php echo site_url('file_publik/cru_file_publik')?>"><input style="margin: 3px 10px 0 10px; float: right;" type="button" value="Tambah File Publik" /></a>
    </div>
		<table id="file_publik_table" class="table-common tablesorter" style="width:100%">
			<thead>
				<tr>
					<th  class="no-sort">No</th>
					<th >Title</th>
					<th >Keterangan</th>
					<th >Nama File</th>
					<th class="no-sort">Action</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
</article>
