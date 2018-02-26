<script type="text/javascript">
	var dt;
	$(document).ready(function(){
	    dt = $("#kegiatan_table").DataTable({
	    	"processing": true,
        	"serverSide": true,
        	"aoColumnDefs": [
                {
                    "bSortable": false,
                    "aTargets": ["no-sort"]
                }
            ],
            "ajax": {
	            "url": "<?php echo base_url('data/load_kegiatan'); ?>",
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

	function edit_kegiatan(id){
		window.location = '<?php echo base_url('data/edit_kegiatan');?>' + '/' + id;
	}

	function delete_kegiatan(id){
		if (confirm('Apakah anda yakin untuk menghapus data kegiatan ini?')) {
			$.blockUI({
				message: 'Proses penghapusan sedang dilakukan, mohon ditunggu ...',
				css: window._css,
				overlayCSS: window._ovcss
			});

			$.ajax({
				type: "POST",
				url: '<?php echo base_url('data/delete_kegiatan'); ?>',
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
	  <h3>Setting Master Kegiatan</h3>
	</header>
	<div class="module_content"; style="overflow:auto">
    <div style='float:right'><a href="<?php echo base_url('data/cru_kegiatan');?>"><button>Tambah Data Kegiatan</button></a></div><br>
		<table id="kegiatan_table" class="table-common tablesorter" style="width:100%">
			<thead>
				<tr>
					<th class="no-sort">No</th>
					<th >Kode Urusan</th>
					<th >Nama Urusan</th>
					<th >Kode Bidang</th>
					<th >Nama Bidang</th>
					<th >Kode Program</th>
					<th >Nama Program</th>
					<th >Kode Kegiatan</th>
					<th >Keteragan Kegiatan</th>
					<th class="no-sort">Action</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
</article>
