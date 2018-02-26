<script type="text/javascript">
	var dt;
	$(document).ready(function(){
	    dt = $("#forum_rkpd").DataTable({
	    	"processing": true,
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

	function ubah_usulan_rkpd(id){
		window.location = '<?php echo $url_edit_data;?>/' + id;		
	}

</script>
<article class="module width_full">
	<header>
	  <h3>Tabel Data Usulan RKPD</h3>
	</header>
	<div class="module_content"; style="overflow:auto">
		<table id="forum_rkpd" class="table-common tablesorter" style="width:100%">
			<thead>
				<tr>
					<th class="no-sort">No</th>					
					<th>Kode Kegiatan</th>
					<th>Nama Program | Nama Kegiatan</th>
                    <th>Lokasi</th>
					<th>SKPD</th>
    	            <th>Prioritas</th>
					<th>Status RKPD</th>
                    <th class="no-sort">Action</th>
				</tr>				
			</thead>
		</table>
	</div>
</article>