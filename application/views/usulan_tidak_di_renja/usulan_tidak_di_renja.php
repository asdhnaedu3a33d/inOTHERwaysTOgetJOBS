<script type="text/javascript">
	var dt;
	$(document).ready(function(){
	    dt = $("#musrenbangcam").DataTable({
	    	"processing": true,
        	"serverSide": true,
			"stateSave": true,
        	"aoColumnDefs": [
                {
                    "bSortable": false,
                    "aTargets": ["no-sort"]
                }
            ],
            "ajax": {
	            "url": "<?php echo $url_load_data; ?>",
	            "type": "POST"
	        },
			"rowCallback": function( row, data, index ) {
			      $('td:eq(6)', row).html('Rp'+accounting.formatMoney(data[6]));
			},
            "footerCallback": function ( row, data, start, end, display ) {
                var api = this.api(), data;

                // Remove the formatting to get integer data for summation
                var intVal = function ( i ) {
                    return typeof i === 'string' ?
                        i.replace(/[\Rp,]/g, '')*1 :
                        typeof i === 'number' ?
                            i : 0;
                };

                // Total over all pages
				var total = 0;
				$.ajaxSetup({
					async :false
				});
				$.ajax({
					type: "POST",
					url: '<?php echo $url_summary_biaya; ?>',
					dataType: "json",
					success: function(data){
						total = data.total_biaya;
					}
				});
				$.ajaxSetup({
					async :true
				});

                // Total over this page
                pageTotal = api
                    .column( 6, { page: 'current'} )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );

                // Update footer
                $( api.column( 6 ).footer() ).html(
                    'Rp '+accounting.formatMoney(pageTotal) +' (Rp '+ accounting.formatMoney(total) +' total)'
                );
			}
	    });

	    $('div.dataTables_filter input').unbind();
	    $("div.dataTables_filter input").keyup( function (e) {
		    if (e.keyCode == 13) {
		        dt.search( this.value ).draw();
		    }
		} );
	});

	function edit_musrenbangcam(id){
		window.location = '<?php echo $url_edit_data;?>/' + id;
	}

	function delete_musrenbangcam(id){

		if (confirm('Apakah anda yakin untuk menghapus data musrenbang ini?')) {
			$.blockUI({
				message: 'Proses penghapusan sedang dilakukan, mohon ditunggu ...',
				css: window._css,
				overlayCSS: window._ovcss
			});

			$.ajax({
				type: "POST",
				url: '<?php echo $url_delete_data; ?>',
				data: {id_musrenbang: id},
				dataType: "json",
				success: function(msg){
					catch_expired_session2(msg);
					if (msg.errno==0) {
						$.blockUI({
							message: msg.msg,
							timeout: 2000,
							css: window._css,
							overlayCSS: window._ovcss
						});
            //reload table
            dt.draw();
					};
				}
			});
		};
	}
</script>
<article class="module width_full">
	<header>
	  <h3>Tabel Data Usulan yang Tidak Ada di Renja</h3>
	</header>
    <!--<div style='float:right'><a href="<?php echo $url_add_data?>"></a></div><br>-->
	<div class="module_content"; style="overflow:auto">
		<table id="musrenbangcam" class="table-common tablesorter" style="width:100%">
			<thead>
				<tr>
					<th class="no-sort">No</th>
					<th>Kode</th>
					<th>Nama Program | Nama Kegiatan</th>
					<th>Jenis Pekerjaan</th>
					<th>Volume</th>
					<th>Satuan</th>
					<th>Jumlah Dana</th>
					<th>Nama Desa</th>
					<th>Nama SKPD</th>
					<th class="no-sort">Action</th>
				</tr>
			</thead>
			<tfoot>
	            <tr>
	                <th colspan="6" style="text-align:right">Total:</th>
	                <th></th>
					<th></th>
					<th></th>
					<th></th>
	            </tr>
	        </tfoot>
			<tbody>
			</tbody>
		</table>
	</div>
</article>
