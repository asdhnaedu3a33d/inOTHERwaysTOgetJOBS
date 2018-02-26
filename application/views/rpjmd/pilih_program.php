<script type="text/javascript">
	var dt;
	$(document).ready(function(){
	    dt = $("#pilih_program").DataTable({
	    	"processing": true,
        	"serverSide": true,	        	
        	"aoColumnDefs": [                
                {
                    "bSortable": false,
                    "aTargets": ["no-sort"]  
                }
            ],
            "ajax": {
	            "url": "<?php echo site_url('rpjmd/get_pilih_program'); ?>",
	            "type": "POST",
	            "data": function ( d ) {
	                d.id_rpjmd = '<?php echo $id_rpjmd; ?>';
	                d.id_sasaran = '<?php echo $id_sasaran; ?>';
	                d.id_indikator = '<?php echo $id_indikator; ?>';
	            }
	        }        	
	    });

	    $('div.dataTables_filter input').unbind();
	    $("div.dataTables_filter input").keyup( function (e) {
		    if (e.keyCode == 13) {
		        dt.search( this.value ).draw();
		    }
		} );
	});

	function pilih_program(id_rpjmd, id_sasaran, id_indikator, id_program_renstra){
		$.ajax({
			type: "POST",
			url: '<?php echo site_url("rpjmd/do_pilih_program"); ?>',
			dataType: "json",			
			data: {id_rpjmd: id_rpjmd, id_sasaran: id_sasaran, id_indikator: id_indikator, id_program_renstra: id_program_renstra},			
			success: function(msg){
				if (msg.success==1) {
					$.blockUI({
						message: msg.msg,
						timeout: 2000,
						css: window._css,
						overlayCSS: window._ovcss
					});
					$.facebox.close();
					element_indikator.trigger( "click" );
				};						
			}
		});
	}
</script>
<div style="width: 1200px">
	<header>
		<h3>
			Pilih Program
		</h3>
	</header>
	<div class="module_content">
		<table id="pilih_program" class="table-common tablesorter" style="width:99%">
			<thead>
				<tr>
					<th class="no-sort" width="25px">No</th>
					<th class="no-sort" width="80px">Kode</th>
					<th>Kegiatan</th>					
					<th>Bidang Urusan</th>					
					<th>SKPD</th>					
					<th class="no-sort" width="10px">Pilih</th>					
				</tr>				
			</thead>
			<tbody>			
			</tbody>
		</table>
	</div>	
</div>