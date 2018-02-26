<script type="text/javascript">
	var dt;
	$(document).ready(function(){
	    dt = $("#renstra").DataTable({
	    	"processing": true,
        	"serverSide": true,	        	
        	"aoColumnDefs": [                
                {
                    "bSortable": false,
                    "aTargets": ["no-sort"]  
                }
            ],
            "ajax": {
	            "url": "<?php echo site_url('usulanpro/get_data'); ?>",
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

	function edit_renstra(id){
		window.location = '<?php echo site_url("usulanpro/cru")?>/' + id;		
	}

	function delete_renstra(id){
		if (confirm('Apakah anda yakin untuk menghapus data usulan ini?')) {
			$.blockUI({
				message: 'Proses penghapusan sedang dilakukan, mohon ditunggu ...',
				css: window._css,
				overlayCSS: window._ovcss
			});

			$.ajax({
				type: "POST",
				url: '<?php echo site_url("usulanpro/delete"); ?>',			
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
						dt.ajax.reload( null, false );
					};								
				}
			});
		};
	}

	function preview_modal(id){
		$.ajax({
			type: "POST",
			url: '<?php echo site_url("usulanpro/preview_modal"); ?>',			
			data: {id: id},			
			success: function(msg){
				prepare_facebox();
				$.facebox(msg);
			}
		});
	}

	function preview_history(id){
		$.ajax({
			type: "POST",
			url: '<?php echo site_url("usulanpro/preview_history"); ?>',			
			data: {id: id},			
			success: function(msg){
				prepare_facebox();
				$.facebox(msg);
			}
		});
	}
</script>
<article class="module width_full">
	<header>
	  <h3>Tabel Data Usulan</h3>
	</header>
	<div class="module_content"; style="overflow:auto">
		<table id="renstra" class="table-common tablesorter" style="width:150%">
			<thead>
				<tr>
					<th class="no-sort">No</th>
					<th class="no-sort">Action</th>
					<th>SKPD</th>
					<th>Kecamatan</th>
					<th>Desa</th>
					<th>Jenis Pekerjaan</th>					
					<th>Status</th>					
				</tr>				
			</thead>
			<tbody>			
			</tbody>
		</table>
	</div>
</article>