<script type="text/javascript">
	var dt;


	function showDaftarUsulanMusrenbang ( d ) {
	    // `d` is the original data object for the row
	    $.ajaxSetup({
            async: false
        });

	    var output = '<table style="width:100%">'+
	        '<thead>'+
	        	'<th>Jenis Pekerjaan</th>' +
	        	'<th>Volume Satuan</th>' +
	        	'<th>Jumlah Dana</th>' +
	        	'<th>Lokasi</th>' +
	        	'<th>Nama Desa - Kecamatan</th>' +
	        	'<th>Asal Usulan</th>' +
						'<th>Status Usulan</th>' +
						'<th>Keputusan</th>' +
	        	'<th>Action</th>' +
	        '</thead>' +
	        '<tbody>';

	    $.ajax({
		 	type: "POST",
		 	url: "<?php echo $url_data_list_musrenbangcam?>",
		 	data: { kode_kegiatan: d.kode_kegiatan,id_skpd:d.id_skpd},
		 	success : function(data){
				//console.log(data);
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
	$(document).ready(function(){
    dt = $("#musrenbangcam").DataTable({
						"processing": true,
						"serverSide": true,
						"aoColumnDefs": [
							{
								"bSortable": false,
								"aTargets": ["no-sort"]
							},
							{
								"targets": [ 6 ],
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
							{ "data": "total_jumlah_dana" },
							{ "data": "nama_skpd" },
							{ "data": "id_skpd" }
					],
				});

	    $('div.dataTables_filter input').unbind();
	    $("div.dataTables_filter input").keyup( function (e) {
		    if (e.keyCode == 13) {
		        dt.search( this.value ).draw();
		    }
		} );

		$('#musrenbangcam tbody').on('click', 'td.details-control', function () {
	        var tr = $(this).closest('tr');
	        var row = dt.row( tr );

	        if ( row.child.isShown() ) {
	            // This row is already open - close it
	            row.child.hide();
	            tr.removeClass('shown');
	        }
	        else {
	            // Open this row
	            //console.log(row.data());
	            row.child( showDaftarUsulanMusrenbang(row.data()) ).show();
	            tr.addClass('shown');
	        }
	    } );
	});

	function edit_musrenbangcam(id){
		window.location = '<?php echo $url_edit_data;?>/' + id;
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
	}

	function valid_usulan_musrenbangcam(id_musrenbang){
		if (confirm('Apakah anda yakin untuk menyetujui data musrenbang ini?')) {
			$.blockUI({
				message: 'Proses perubahan status sedang dilakukan, mohon ditunggu ...',
				css: window._css,
				overlayCSS: window._ovcss
			});

			$.ajax({
				type: "POST",
				url: '<?php echo $url_valid_usulan_musrenbang; ?>',
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

    function show_gallery(id){
        $.ajax({
            type : 'POST',
            data : {id_musrenbang : id},
            url : '<?php echo $url_show_gallery;?>',
            dataType: 'text',
            complete: function(data) {
                console.log(JSON.parse(data.responseText));
                var img = JSON.parse(data.responseText);
                $.fancybox(img, {
                    padding: 0,

                    openEffect : 'elastic',
                    openSpeed  : 150,

                    closeEffect : 'elastic',
                    closeSpeed  : 150,

                    closeClick : true
                });
            }
        });
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

section#main {
	width: 1200px;
}

.width_full {
	width: 100%;
}

</style>
<article class="module width_full">
	<header>
	  <!-- <h3>Tabel Data Musrenbangcam</h3> -->
	  <h3>Forum SKPD</h3>
	</header>
	<div class="module_content"; style="overflow:auto">
	<div style="float:right">
	<a href="<?php echo site_url('forum_skpd/do_cetak_forum_skpd') ?>"><input style="margin: 3px 10px 0px 0px; float: right;" type="button" value="Cetak Forum SKPD" /></a>
    <a href="<?php echo $url_add_data ?>"><input style="margin: 3px 10px 0 10px; float: right;" type="button" value="Tambah Data Forum SKPD" /></a></div><br>
		<table id="musrenbangcam" class="table-common tablesorter" style="width:100%">
			<thead>
				<tr>
					<th style="width:5%"></th>
					<th class="no-sort">No</th>
					<th>Kode Kegiatan</th>
					<th>Nama Program | Nama Kegiatan</th>
					<th>Total Dana Kegiatan Disetujui</th>
          			<th>Nama SKPD</th>
					<th>id</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
	</div>
</article>
