<script type="text/javascript">
	var dt;
	$(document).ready(function(){
	    dt = $("#pendapatan_table").DataTable({
	    	"processing": true,
        	"serverSide": true,	        	
        	"aoColumnDefs": [                
                {
                    "bSortable": false,
                    "aTargets": ["no-sort"]  
                }
            ],	
            "ajax": {
	            "url": "<?php echo base_url('pendapatan_daerah/load_data'); ?>",
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
</script>
<article class="module width_full">
	<header>
	  <h3>Tabel Data CIK</h3>
	</header>
	<div style='float:right'></div><br>
	<div class="module_content"; style="overflow:auto">
		<table id="pendapatan_table" class="table-common tablesorter" style="width:100%">
			<thead>
				<tr>
					<th rowspan="2" class="no-sort">No</th>	
					<th rowspan="2">jenis pendapatan</th>	
					<!--<th rowspan="2">Urusan / Organisasi Program / Kegiatan Belanja Langsung</th>-->
					<th colspan="2">Realisasi</th>
					<th colspan="3">Proyeksi</th>
				</tr>
				<tr>
					<th>Tahun 2011</th>
					<th>Tahun 2012</th>
					<th>Tahun 2013</th>
					<th>Tahun 2014</th>
					<th>Tahun 2015</th>

				</tr>				
			</thead>
			<tbody>			
			</tbody>
		</table>
</article>