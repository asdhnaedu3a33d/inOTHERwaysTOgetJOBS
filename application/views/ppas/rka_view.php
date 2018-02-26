<script type="text/javascript">
	var dt;
	$(document).ready(function(){
	    dt = $("#rka_table").DataTable({
	    	"processing": true,
        	"serverSide": true,
        	"aoColumnDefs": [
                {
                    "bSortable": false,
                    "aTargets": ["no-sort"]
                }
            ],
            "ajax": {
	            "url": "<?php echo base_url('ppas/load_rka'); ?>",
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

	function edit_rka(id){
		window.location = '<?php echo base_url('ppas/edit_rka/');?>' + '/' + id;
	}

	function delete_rka(id){
		if (confirm('Apakah anda yakin untuk menghapus data ppas ini?')) {
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
	  <h3>Tabel Data PPAS</h3>
	</header>
	<div style='float:right'><a href="<?php echo site_url('ppas/cru_rka')?>">Tambah Data RKA</a></div><br>
	<div class="module_content"; style="overflow:auto">
		<table id="rka_table" class="table-common tablesorter" style="width:100%">
			<thead>
				<tr>
					<th  class="no-sort">No</th>
					<th >Kode</th>
					<th >Urusan / Bidang Urusan Pemerintahan Daerah Dan Program / Kegiatan</th>
					<th >Indikator Kinerja Program/Kegiatan</th>
					<th >Tahun Sekarang</th>
					<th >Lokasi</th>
					<th >Target Capaian Kinerja</th>
					<th >Jumlah Dana</th>
					<th >Tahun Mendatang</th>
					<th >Target Capaian</th>
					<th >Jumlah Dana</th>
					<!-- <th rowspan = "2" colspan="2">Kesesuaian</th>
					<th rowspan="3">Hasil Pengendalian</th>
					<th rowspan="3">Tindak Lanjut</th>
					<th rowspan="3">Hasil Tindak Lanjut</th>
					<th rowspan="3">KRITERIA KEBERHASILAN</th>
					<th rowspan="3">UKURAN KEBERHASILAN</th>
					<th rowspan="2">Urusan / Organisasi Program / Kegiatan Belanja Langsung</th>
					<th colspan="6">KINERJA TRIWULAN</th>
					<th rowspan="3">Keterangan</th>-->
					<th class="no-sort">Action</th>

				</tr>
				<!-- <tr>

					<th rowspan="2">Tri Wulan</th>
					<th colspan="3">Input</th>
					<th colspan="2">Output</th>
				</tr> -->
				<!-- <tr>
					<th>Ya</th>
					<th>Tidak</th>
					<th>Pagu</th>
					<th>REALISASI</th>
					<th>Capaian %</th>
					<th>Ukuran Kinerja Triwulan</th>
					<th>Capaian %</th>
				</tr> -->
			</thead>
			<tbody>
			</tbody>
		</table>
</article>
