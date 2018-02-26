<style type="text/css">
	.r-ranwal{
		background-color: #D0BE2D;
		padding-left: 5px;
	}

	.r-akhir{
		background-color: #96CC3F;
		padding-left: 5px;
	}
</style>
<script>
	function tambah_rcn_aksi(kd_status){
			var id_dpa = "";
			var anggaran = 0;
			if (kd_status == 1) {
				$("#rcn_tabel").html("<tr><td colspan='9' align='center'> <i class='fa fa-refresh fa-spin'></i> Mohon menunggu ...</td></tr>");
				id_dpa = $("input[name=id_program]").val();
			}else{
				$("#rcn_tabel").html("<tr><td colspan='12' align='center'> <i class='fa fa-refresh fa-spin'></i> Mohon menunggu ...</td></tr>");
				id_dpa = $("input[name=id_kegiatan]").val();
				anggaran = $("input[name=rcn_anggaran]").autoNumeric('get');
			}

			var uniq_id = $("input[name=rcn_unicid]").val();

			var id = $("input[name=rcn_id]").val();
			var bln = $("select[name=rcn_bulan]").val();
			var aksi = $("input[name=rcn_aksi]").val();
			var satuan = $("input[name=rcn_satuan]").val();
			var bobot = $("input[name=rcn_bobot]").autoNumeric('get');
			var target = $("input[name=rcn_target]").autoNumeric('get');
			// var anggaran = $("input[name=rcn_anggaran]").autoNumeric('get');
			$.ajax({
				type: "POST",
				url: '<?php echo site_url("dpa/save_rencana_aksi"); ?>',
				dataType: "html",
				data: {id_dpa : id_dpa, id_dpa_prog_keg: uniq_id, bulan: bln, aksi: aksi, bobot: bobot, target: target, anggaran: anggaran, satuan: satuan, id: id, kd_status : kd_status},
				success: function(data){
					$("input[name=rcn_id]").val("");
					$("input[name=rcn_aksi]").val("");
					$("input[name=rcn_bobot]").val("");
					$("input[name=rcn_satuan]").val("");
					$("input[name=rcn_target]").val("");
					$("input[name=rcn_anggaran]").val("");
					$("#rcn_tabel").html(data);

					if (kd_status == 2) {
						$.ajax({
							type: "POST",
							url: '<?php echo site_url("dpa/get_sum_anggaran_per_bulan"); ?>',
							dataType: "json",
							data: {id_dpa : id_dpa, id_dpa_prog_keg: uniq_id},
							success: function(data_rcn){
								for (var i = data_rcn.length - 1; i >= 0; i--) {
									data_rcn[i];
									$('input[name=nominal_'+data_rcn[i].bulan+']').autoNumeric('set', data_rcn[i].anggaran);
								}
								
							}
						});
					}
				}
			});
		}
	// $(document).ready(function() {
		// $(document).on("click", "#rcn_button", function() {
		
		function hapus_rcn_aksi(kd_status, id) {
			var id_dpa = "";
			var anggaran = 0;
			
			if (kd_status == 1) {
				$("#rcn_tabel").html("<tr><td colspan='9' align='center'> <i class='fa fa-refresh fa-spin'></i> Mohon menunggu ...</td></tr>");
				id_dpa = $("input[name=id_program]").val();
			}else{
				$("#rcn_tabel").html("<tr><td colspan='12' align='center'> <i class='fa fa-refresh fa-spin'></i> Mohon menunggu ...</td></tr>");
				id_dpa = $("input[name=id_kegiatan]").val();
				anggaran = $("input[name=rcn_anggaran]").autoNumeric('get');
			}

			// $("#rcn_tabel").html("<tr><td colspan='12' align='center'> <i class='fa fa-refresh fa-spin'></i> Mohon menunggu ...</td></tr>");
			// var id = $(this).attr('id-rcn');
			// var id_dpa = $("input[name=id_kegiatan]").val();
			var uniq_id = $("input[name=rcn_unicid]").val();
			$.ajax({
				type: "POST",
				url: '<?php echo site_url("dpa/delete_rencana_aksi"); ?>',
				dataType: "html",
				data: {id_dpa : id_dpa, id_dpa_prog_keg: uniq_id, id: id, kd_status : kd_status},
				success: function(data){
					$("#rcn_tabel").html(data);

					if (kd_status == 2) {
						$.ajax({
							type: "POST",
							url: '<?php echo site_url("dpa/get_sum_anggaran_per_bulan"); ?>',
							dataType: "json",
							data: {id_dpa : id_dpa, id_dpa_prog_keg: uniq_id},
							success: function(data_rcn){
								for (var i = data_rcn.length - 1; i >= 0; i--) {
									data_rcn[i];
									$('input[name=nominal_'+data_rcn[i].bulan+']').autoNumeric('set', data_rcn[i].anggaran);
								}
								
							}
						});
					}
				}
			});
		}
	// })

	$(document).on("click", "#cetak_aksi", function(){
		// prepare_facebox();
		// var link = '<?php //echo site_url("dpa/cetak_form_aksi"); ?>/1';
		// $.facebox({div: link});

		var id = '';
		var aksi = 'rencana_prog';
		var id_skpd = '<?php echo $this->session->userdata("id_skpd"); ?>';
		var t_anggaran = '<?php echo $this->session->userdata("t_anggaran_aktif"); ?>';
		var link = '<?php echo site_url('dpa/cetak_aksi'); ?>/'+id_skpd+'/'+t_anggaran+'/'+aksi+'/'+id;
		
		// alert(link);
		// $.blockUI({
		// 	css: window._css,
		// 	overlayCSS: window._ovcss
		// });

		window.open(link, '_blank');

	});

</script>
<header>
	<h3>

  <?php echo "Jendela Kontrol DPA ".$nama_skpd; ?></h3>
</header>
<div class="module_content">
	<table class="fcari" width="100%">
		<tbody>
			<tr>
				<td align="center" colspan="6">Proses</td>
			</tr>
			<tr align="center">
				<td colspan="3" class="r-ranwal">
					Dokumen Pelaksanaan Anggaran</td>
			</tr>
			<tr>
				<td width="25%" class="r-ranwal">Program & Kegiatan Baru</td>
				<td colspan="2" width="25" class="r-ranwal"><?php echo $jendela_kontrol->baru; ?>
                Data</td>
			</tr>
			<tr>
			    <td class="r-ranwal">Program &amp; Kegiatan Diproses</td>
			    <td colspan="2" class="r-ranwal"><?php echo $jendela_kontrol->proses; ?>
			    Data</td>
		  </tr>
		</tbody>
	</table>
	<!--<table style="font-style: italic; color: #666;">
		<tr>
			<td colspan="2">*Keterangan:</td>
		</tr>
		<tr>
			<td valign="top">1. </td>
			<td>bla bla</td>
		</tr>
		<tr>
			<td valign="top">2. </td>
			<td>bla bla</td>
		</tr>
		<tr>
			<td valign="top">2. </td>
			<td>bla bla.</td>
		</tr>
	</table>-->
</div>
<footer>
	<div class="submit_link">
    <?php if (!$dpa) {?>
    	<input type="button" class="button-action" id="get_rka" value="Ambil Data RKA" />
    <?php }
		else {
	?>
		<input type="button" class="button-action" id="cetak_aksi" value="Cetak Rencana Aksi" />
    	<a href="<?php echo site_url('dpa/preview_dpa'); ?>"><input type="button" value="Lihat DPA" /></a>
    <?php } ?>
	 	<input type="button" value="Back" onclick="history.go(-1)" />
	</div>
</footer>
