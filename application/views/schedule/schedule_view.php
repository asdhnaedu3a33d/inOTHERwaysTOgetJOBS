<script type="text/javascript">
	var dt;
	$(document).ready(function(){
	    dt = $("#schedule_table").DataTable({
	    	"processing": true,
        	"serverSide": true,
        	"aoColumnDefs": [
                {
                    "bSortable": false,
                    "aTargets": ["no-sort"]
                }
            ],
            "ajax": {
	            "url": "<?php echo base_url('schedule/load_schedule'); ?>",
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

	function edit_schedule(id){
		window.location = '<?php echo base_url('schedule/edit_schedule/');?>' + '/' + id;
	}

	function delete_schedule(id){
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
	  <h3>Tabel Jadwal Kegiatan</h3>
	</header>
	<div class="module_content"; style="overflow:auto">
    <div style='width: 100%; display: table;'>
      <a href="<?php echo site_url('schedule/cru_schedule')?>"><input style="margin: 3px 10px 0 10px; float: right;" type="button" value="Tambah Schedule" /></a>
    </div>
		<table id="schedule_table" class="table-common tablesorter" style="width:100%">
			<thead>
				<tr>
					<th class="no-sort">No</th>
					<th >Tanggal Mulai</th>
					<th >Tanggal Selesai</th>
					<th >Judul Kegiatan</th>
					<th >Informasi</th>
					<th class="no-sort">Action</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
</article>
