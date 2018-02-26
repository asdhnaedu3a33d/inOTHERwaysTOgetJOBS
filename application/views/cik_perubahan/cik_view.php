<script type="text/javascript">
	var dt;
	$(document).ready(function(){
	    dt = $("#cik_table").DataTable({
	    	"processing": true,
        	"serverSide": true,	        	
        	"aoColumnDefs": [                
                {
                    "bSortable": false,
                    "aTargets": ["no-sort"]  
                }
            ],	
            "ajax": {
	            "url": "<?php echo base_url('cik_perubahan/load_cik'); ?>",
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

	function edit_cik(id){
		window.location = '<?php echo base_url('cik_perubahan/edit_cik/');?>' + '/' + id;		
	}

	function delete_cik(id){
		if (confirm('Apakah anda yakin untuk menghapus data CIK Perubahan ini?')) {
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
	  <h3>Tabel Data CIK Perubahan</h3>
	</header>
    <div class="module_content"; style="overflow:auto">
    <div style='float:right'>
	<a href="<?php echo base_url('cik_perubahan/cru_cik/') ?>"><input style="margin: 3px 10px 0 10px; float: right;" type="button" value="Tambah Data CIK Perubahan" /></a>
    </div><br> 
		<table id="cik_table" class="table-common tablesorter" style="width:100%">
			<thead>
				<tr>
					<th rowspan="2" class="no-sort">No</th>	
					<th rowspan="2">Kode</th>	
					<th rowspan="2">Bulan</th>
					<!--<th rowspan="2">Urusan / Organisasi Program / Kegiatan Belanja Langsung</th>-->
					<th colspan="3">Anggaran</th>
					<th colspan="4">Kelompok Indikator Kinerja Program (Outcome) / Indikator Kinerja Kegiatan (Output)</th>
					<th rowspan="2">Keterangan</th>
					<th rowspan="2" class="no-sort">Action</th>
				</tr>
				<tr>
					<th>Rencana</th>
					<th>Realisasi</th>
					<th>Capaian IK</th>
					<th>Indikator/Satuan</th>
					<th>Rencana</th>
					<th>Realisasi</th>
					<th>Capaian IK</th>
				</tr>				
			</thead>
			<tbody>			
			</tbody>
		</table>
</article>