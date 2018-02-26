<script type="text/javascript">
	var dt;
	$(document).ready(function(){
	    dt = $("#kendali_skpd").DataTable({
	    	"processing": true,
        	"serverSide": true,	        	
        	"aoColumnDefs": [                
                {
                    "bSortable": false,
                    "aTargets": ["no-sort"]  
                }
            ],	
            "ajax": {
	            "url": "<?php echo base_url('kendali_skpd/load_data'); ?>",
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
	  <h3>Tabel pengendalian pelaksanaan renja skpd</h3>
	</header>
	<h3>PROVINSI/KABUPAEN/KOTA : </h3>
	<h3>SKPD : </h3>
	<h3>PERIODE RENJA SKPD : </h3>
	<h3>PERIODE RKA SKPD : </h3>
		
	<div style='float:right'><a href="<?php echo site_url('')?>">Tambah Data</a></div><br>
	<div class="module_content" style="overflow:auto">
		<table id="kendali_skpd" class="table-common tablesorter" style="width:100%">
			<thead>
				<tr>
					<th rowspan="3" class="no-sort">No</th>	
					<th rowspan="3">Kode</th>	
					<!--<th rowspan="3">Urusan / Bidang Urusan Pemerintahan Daerah Dan Program / Kegiatan</th>-->
					<th rowspan="2" colspan="2">indikator kinerja Program / Kegiatan</th>
					<th colspan="6">rencana tahun(....)</th>
					<th colspan="4">prakiraan rencana tahun (....)</th>
					<th rowspan="2" colspan="2">Kesesuaian</th>
					<th rowspan="3">Hasil Pengendalian</th>
					<th rowspan="3">Tindak Lanjut</th>
					<th rowspan="3">Hasil Tindak Lanjut</th>
					<th rowspan="3" class="no-sort">Action</th>
				</tr>
				<tr>
					<th colspan = "2">lokasi</th>
					<th colspan = "2">target capaian</th>
					<th colspan = "2">dana</th>
					<th colspan = "2">target capaian kinerja</th>
					<th colspan = "2">dana</th>
				</tr>
				<tr>
					<th> renja </th>
					<th> rka   </th>
					<th> renja </th>
					<th> rka   </th>
					<th> renja </th>
					<th> rka   </th>
					<th> renja </th>
					<th> rka   </th>
					<th> renja </th>
					<th> rka   </th>
					<th> renja </th>
					<th> rka   </th>
					<th> ya    </th>
					<th> Tidak </th>
				</tr>
			</thead>
			
		</table>
</article>