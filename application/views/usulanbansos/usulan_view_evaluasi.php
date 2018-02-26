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

	function edit_usulan_table(id){
		window.location = '<?php echo $url_edit_data;?>/' + id;
	}

</script>
<article class="module width_full">
	<header>
	  <h3>Tabel Data Evaluasi Usulan Hibah/Bansos </h3>
	</header>
	<div class="module_content"; style="overflow:auto">
		<table id="usulan_table" class="table-common tablesorter" style="width:100%">
			<thead>
				<tr>
					<th class="no-sort">No</th>
					<th>Group</th>
                    <th>Nama Dewan</th>
					<th>SKPD Tujuan</th>
					<th>Kecamatan</th>
					<th>Desa</th>
					<th>Jenis Pekerjaan</th>
					<th>Volume</th>
					<th>Jumlah Dana (Rp)</th>
					<th>Lokasi</th>
					<th>Catatan</th>
					<th class="no-sort">Action</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
	</div>
</article>
