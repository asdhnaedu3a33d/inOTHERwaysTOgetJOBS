<script type="text/javascript">
	var dt;
	$(document).ready(function(){
	    dt = $("#pengajuan_rkpd").DataTable({
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

	function add_usulan_rkpd(id){
		window.location = '<?php echo $url_add_data;?>/' + id;		
	}

</script>
<article class="module width_full">
	<header>
	  <h3>Tabel Data Pengajuan Draft RKPD</h3>
	</header>
	<div class="module_content"; style="overflow:auto">
		<table id="pengajuan_rkpd" class="table-common tablesorter" style="width:100%">
			<thead>
				<tr>
					<th class="no-sort">No</th>					
					<th>Kode</th>
					<th>Nama Program | Nama Kegiatan</th>
					<th>Jenis Pekerjaan</th>
					<th>SKPD</th>
                    <th class="no-sort">Action</th>
				</tr>				
			</thead>
			<tbody>			
			</tbody>
		</table>
	</div>
</article>