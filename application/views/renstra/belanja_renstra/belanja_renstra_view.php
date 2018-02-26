<script type="text/javascript">
	var dt;
	$(document).ready(function(){
	    dt = $("#usulan_table").DataTable({
	    	"processing": true,
			"stateSave": true,
        	"serverSide": true,
        	"aoColumnDefs": [
                {
                    "bSortable": false,
                    "aTargets": ["no-sort"]
                }
            ],
            "ajax": {
	            "url": "<?php echo $url_load_data; ?>",
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

	function edit_belanja(id){
		window.location = '<?php echo $url_edit_data;?>/' + id;
	}
	function delete_usulan_table(id){

			if (confirm('Apakah anda yakin untuk menghapus data Belanja Renstra ini?')) {
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
							dt.draw();
							$.blockUI({
								message: msg.msg,
								timeout: 2000,
								css: window._css,
								overlayCSS: window._ovcss
							});


						};
					}
				});
			};
		}

</script>
<article class="module width_full">
	<header>
	  <h3>Tabel Data Belanja Renstra </h3>
	</header>
	<div class="module_content"; style="overflow:auto">
    <div style='float:right'>
    	<a href="<?php echo $url_add_data ?>"><input style="margin: 3px 10px 0px 0px; float: right;" type="button" value="Tambah Data Usulan" /></a>
     </div>
		<table id="usulan_table" class="table-common tablesorter" style="width:100%">
			<thead>
				<tr>
					<th class="no-sort">No</th>
					<th>Kode</th>
                    <th>Nama Belanja</th>
					<th>Uraian</th>
					<th>Nominal</th>
					<th class="no-sort"> Action</th>

				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
	</div>
</article>
