<script type="text/javascript">
	var dt;

	function showDaftarUsulanMusrenbang ( d ) {
	    // `d` is the original data object for the row
	    $.ajaxSetup({
            async: false
        });

	    var output = '<table style="width:100%">'+
	        '<thead>'+
            '<th>Prioritas</th>' +
	        	'<th>Jenis Pekerjaan</th>' +
	        	'<th>Volume Satuan</th>' +
	        	'<th>Jumlah Dana</th>' +
	        	'<th>Lokasi</th>' +
	        	'<th>Nama Desa - Kecamatan</th>' +
	        	'<th>Asal Usulan</th>' +
	        	'<th>Status RKPD</th>' +
	        	'<th>Action</th>' +
	        '</thead>' +
	        '<tbody>';

	    $.ajax({
		 	type: "POST",
		 	url: "<?php echo $url_data_list_musrenbangcam?>",
		 	data: { kode_kegiatan: d.kode_kegiatan,id_skpd:d.id_skpd},
		 	success : function(data){
				console.log(data);
				output += data;
				output += '</tbody></table>';
				//return output;
			},
			error : function(XMLHttpRequest){
                alert(XMLHttpRequest.responseText);
            }

		});

		return output;
		$.ajaxSetup({
            async: true
    	});

	}

	function show_data_musrenbangcam(id_musrenbang){
		window.location = '<?php echo $url_edit_data;?>/' + id_musrenbang;
	}

	function terima_usulan_musrenbangcam(id_musrenbang){
		if (confirm('Apakah anda yakin untuk menyetujui data musrenbang ini?')) {
			$.blockUI({
				message: 'Proses perubahan status sedang dilakukan, mohon ditunggu ...',
				css: window._css,
				overlayCSS: window._ovcss
			});

			$.ajax({
				type: "POST",
				url: '<?php echo $url_terima_usulan_musrenbang; ?>',
				data: {id_musrenbang: id_musrenbang},
				dataType: "json",
				success: function(msg){
					catch_expired_session2(msg);
					$.blockUI({
						message: msg.msg,
						timeout: 2000,
						css: window._css,
						overlayCSS: window._ovcss
					});
          //reload table
          dt.draw();
				}
			});
		};
	}
	function tolak_usulan_musrenbangcam(id_musrenbang){
		//load fancy box
    prepare_facebox();
    $.blockUI({
			css: window._css,
			overlayCSS: window._ovcss
		});
		$.ajax({
			type: "POST",
			url: '<?php echo $url_load_keterangan; ?>',
			data: {id_musrenbang: id_musrenbang},
			success: function(msg){
				if(msg!=""){
				    $.facebox(msg);
            $.blockUI({
							message: msg.msg,
							timeout: 2000,
							css: window._css,
							overlayCSS: window._ovcss
						});
				}
			}
		});


        /*
        if (confirm('Apakah anda yakin untuk menolak data musrenbang ini?')) {
			$.blockUI({
				message: 'Proses perubahan status sedang dilakukan, mohon ditunggu ...',
				css: window._css,
				overlayCSS: window._ovcss
			});

			$.ajax({
				type: "POST",
				url: '<?php echo $url_tolak_usulan_musrenbang; ?>',
				data: {id_musrenbang: id_musrenbang},
				dataType: "json",
				success: function(msg){
					catch_expired_session2(msg);
					$.blockUI({
						message: msg.msg,
						timeout: 2000,
						css: window._css,
						overlayCSS: window._ovcss
					});
                    //reload table
                    dt.draw();
				}
			});
		};*/
	}
	$(document).ready(function(){
	    dt = $("#forum_rkpd").DataTable({
	    	"processing": true,
        	"serverSide": true,
        	"aoColumnDefs": [
                {
                    "bSortable": false,
                    "aTargets": ["no-sort"]
                },
                {
	                "targets": [ 8 ],
	                "visible": false,
	                "searchable": false
	            },
            ],
            "ajax": {
	            "url": "<?php echo $url_load_data; ?>",
	            "type": "POST"
	        },
	        "columns": [
	            {
	                "className":      'details-control',
	                "orderable":      false,
	                "data":           null,
	                "defaultContent": ''
	            },
	            { "data": "no" },
	            { "data": "kode_kegiatan" },
	            { "data": "nama_program_kegiatan" },
	            { "data": "indikator_kinerja" },
	            { "data": "target" },
	            { "data": "nama_skpd" },
	            { "data": "nominal" },
	            { "data": "id_skpd" }
	        ],

	    });

	    $('div.dataTables_filter input').unbind();
	    $("div.dataTables_filter input").keyup( function (e) {
		    if (e.keyCode == 13) {
		        dt.search( this.value ).draw();
		    }
		} );

		$('#forum_rkpd tbody').on('click', 'td.details-control', function () {
	        var tr = $(this).closest('tr');
	        var row = dt.row( tr );

	        if ( row.child.isShown() ) {
	            // This row is already open - close it
	            row.child.hide();
	            tr.removeClass('shown');
	        }
	        else {
	            // Open this row
	            console.log(row.data());
	            row.child( showDaftarUsulanMusrenbang(row.data()) ).show();
	            tr.addClass('shown');
	        }
	    } );
	});

	function ubah_usulan_rkpd(id){
		window.location = '<?php echo $url_edit_data;?>/' + id;
	}

</script>
<style type="text/css">
td.details-control {
    background: url('<?php echo base_url();?>/asset/images/details_open.png') no-repeat center center;
    cursor: pointer;
}
tr.shown td.details-control {
    background: url('<?php echo base_url();?>/asset/images/details_close.png') no-repeat center center;
}
</style>
<article class="module width_full">
	<header>
	  <h3>Tabel Data Usulan RKPD</h3>
	</header>
	<div class="module_content"; style="overflow:auto">
		<table id="forum_rkpd" class="table-common tablesorter" style="width:100%">
			<thead>
				<tr>
					<th style="width:5%"></th>
					<th class="no-sort">No</th>
					<th>Kode Kegiatan</th>
					<th>Nama Program | Nama Kegiatan</th>
          <th>Indikator Kinerja</th>
          <th>Target</th>
					<th>SKPD</th>
					<th>Nominal</th>
					<th>ID SKPD</th>
				</tr>
			</thead>
		</table>
	</div>
</article>
