<script type="text/javascript">
	var dt;
	$(document).ready(function(){
	    dt = $("#pengendalian_table").DataTable({
	    	"processing": true,
        	"serverSide": true,	        	
        	"aoColumnDefs": [                
                {
                    "bSortable": false,
                    "aTargets": ["no-sort"]  
                }
            ],	
            "ajax": {
	            "url": "<?php echo base_url('kendali_belanja/load_kendali'); ?>",
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

	function edit_kendali_belanja(id){
		window.location = '<?php echo base_url('kendali_belanja/edit_kendali_belanja/');?>' + '/' + id;		
	}

	function delete_kendali_belanja(id){
		if (confirm('Apakah anda yakin untuk menghapus data pengendalian belanja ini?')) {
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
		<h3> FORMAT KENDALI PELAKSANAAN KEGIATAN BELANJA LANGSUNG </h3>
	</header>
	<div style='float:right'><a href="<?php echo site_url('kendali_belanja/cru_kendali_belanja')?>">Tambah Data Kendali Belanja</a></div>
	<h3>
	<div>SKPD   : </div>
	<div>URUSAN : </div>
	</h3>
	<div class="module_content"; style="overflow:auto">
		<table id="pengendalian_table" class="table-common tablesorter" style="width:100%">
		<thead>
				<tr>
					<th rowspan="3" class="no-sort">No</th>	
					<th rowspan="3">KODE</th>
					<th rowspan="3">PROGRAM / KEGIATAN</th>
					<th rowspan="3">KRITERIA KEBERHASILAN</th>
					<th rowspan="3">UKURAN KEBERHASILAN</th>
					<!--<th rowspan="2">Urusan / Organisasi Program / Kegiatan Belanja Langsung</th>-->
					<th colspan="6">KINERJA TRIWULAN</th>
					<th rowspan="3">Keterangan</th>
					<th rowspan="3" class="no-sort">Action</th>
				</tr>
				<tr>
					<th rowspan="2">Tri Wulan</th>
					<th colspan="3">Input</th>
					<th colspan="2">Output</th>
				</tr>
				<tr>
					<th>Pagu</th>
					<th>REALISASI</th>
					<th>Capaian %</th>
					<th>Ukuran Kinerja Triwulan</th>
					<th>Capaian %</th>
				</tr>
			</thead>
			<tbody>			
			</tbody>
		</table>
	</div>
</article>