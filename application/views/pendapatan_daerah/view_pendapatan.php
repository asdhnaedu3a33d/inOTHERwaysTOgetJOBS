<script>
function edit_pendapatan(id){
	window.location = '<?php echo $url_edit_data;?>/' + id;
}

function delete_pendapatan(id){
	if (confirm('Apakah anda yakin untuk menghapus data pendapatan ini?')) {
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
				console.log(msg);
				if (msg.success==1) {
					//reload page
					location.reload();
					$.blockUI({
						message: msg.msg,
						timeout: 2000,
						css: window._css,
						overlayCSS: window._ovcss
					});
				};
			}
		});
	};
}
</script>
<article class="module width_full">
	<header>
	  <h3>Tabel Data pendapatan</h3>
	</header>
    <div style='float:right'><a href="<?php echo $url_add_data?>">Tambah Data</a></div><br>
	<div class="module_content">
		<table id="pendapatan_table" class="table-display">
			<thead>
				<tr>
					<th rowspan="2" class="no-sort">No</th>
					<th rowspan="2">jenis pendapatan</th>
					<th colspan="3">Realisasi</th>
					<th>Anggaran</th>
					<th>Proyeksi</th>
					<th rowspan="2">Action</th>
				</tr>
				<tr>
					<th>Tahun <?php echo ($tahun -4) ?></th>
					<th>Tahun <?php echo ($tahun -3) ?></th>
					<th>Tahun <?php echo ($tahun -2) ?></th>
					<th>Tahun <?php echo ($tahun -1) ?></th>
					<th>Tahun <?php echo ($tahun) ?></th>
				</tr>
				<?php echo $data_table_pendaptan?>
			</thead>
			<tfoot>
			<tfoot>
		</table>
</article>
