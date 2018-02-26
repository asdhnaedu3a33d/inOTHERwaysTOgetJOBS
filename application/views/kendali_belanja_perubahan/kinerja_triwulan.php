<script type="text/javascript">
	$('#nominal').autoNumeric(numOptions);
	prepare_chosen();


	//agar validation tetap aktif untuk chosen dropdown
	$('form#program').validate({

	});

	$("#simpan").click(function(){
		// element.hide();
		element.parent().next().hide();
		// $('#indikator_frame_program .indikator_val').each(function () {
		//     $(this).rules('add', {
		//         required: true
		//     });
		// });

		// $('#indikator_frame_program .target').each(function () {
		//     $(this).rules('add', {
		//         required:true,
		// 		number:true
		//     });
		// });

	    var valid = $("form#program").valid();


	    if (valid) {
			
	    	$.blockUI({
				css: window._css,
				overlayCSS: window._ovcss
			});

	    	$.ajax({
				type: "POST",
				url: $("form#program").attr("action"),
				data: $("form#program").serialize(),
				dataType: "json",
				success: function(msg){
					if (msg.success==1) {
						$.blockUI({
							message: msg.msg,
							timeout: 2000,
							css: window._css,
							overlayCSS: window._ovcss
						});
						$.facebox.close();
						element.trigger( "click" );
						// location.reload();

					};
				}
			});
			

			// $("form#program").submit();
	    };
	});

	$("#tambah_kinerja_triwulan").click(function(){
		key = $("#indikator_frame_program").attr("key");
		key++;
		$("#indikator_frame_program").attr("key", key);

		var name = "catatan["+ key +"]";
		var keterangan = "keterangan["+ key +"]";
		var capaian = "capaian["+ key +"]";

		$("#indikator_box_program texarea").attr("name", name);
		$("#indikator_box_program input#target").attr("name", keterangan);
		$("#indikator_box_program input#satuan_target").attr("name", capaian);
		$("#indikator_frame_program").append($("#indikator_box_program").html());
	});

	$(document).on("click", ".hapus_catatan", function(){
		$(this).parent().parent().remove();
	});
</script>

<div style="width: 900px">
	<header>
		<h3 style="padding:20px">
	<?php
		if (!empty($kinerja_triwulan)){
		    echo "Edit Kinerja Triwulan ";
		} else{
		    echo "Input Kinerja Triwulan ";
		}
	?>
	</h3>
	</header>
	<div class="module_content">
	<!-- <?php
		if (!empty($revisi)) {
	?>
		<table style="margin-bottom: 10px;" class="fcari" width="100%">
			<thead>
				<tr style="background-color: #FF8F8F;">
					<th colspan="2">Riwayat Revisi</th>
				</tr>
				<tr>
					<th bgcolor="#FF8F8F" width="20%">Tanggal</td>
					<th bgcolor="#FF8F8F" width="80%">Keterangan Revisi</td>
				</tr>
			</thead>
			<tbody>
			<?php
				foreach ($revisi as $row) {
			?>
				<tr>
					<td align="center"><?php echo $row->formated_date; ?></td>
					<td><?php echo $row->ket; ?></td>
				</tr>
			<?php
				}
			?>
			</tbody>
		</table>
	<?php
		}
	?> -->
		<form action="<?php echo site_url('kendali_perubahan/save_kinerja_triwulan');?>" method="POST" name="program" id="program" accept-charset="UTF-8" enctype="multipart/form-data" >
			<input type="hidden" name="id_dpa_prog_keg_triwulan" value="<?php if(!empty($id_dpa_prog_keg_triwulan)){echo $id_dpa_prog_keg_triwulan;} ?>" />
			<table class="fcari" width="100%">
				<tbody>
					<tr>
						<!-- <td width="25%">Kinerja Triwulan <a id="tambah_kinerja_triwulan" class="icon-plus-sign" href="javascript:void(0)"></a></td> -->
						<td id="indikator_frame_program" key="<?php echo (!empty($kinerja_triwulan))?$kinerja_triwulan->num_rows():'1'; ?>">
							<?php
								if (!empty($kinerja_triwulan)) {
									$i=0;
									foreach ($kinerja_triwulan->result() as $row) {
										$i++;
							?>
							<input type="hidden" name="id_kinerja_triwulan[<?php echo $i; ?>]" value="<?php echo $row->id; ?>">
							<div style="margin-top:5px">
							  <label>Aksi</label>
							  <div style="width:100%">
									<textarea class="common catatan" name="catatan[<?php echo $i; ?>]" style="width:95%"><?php if(!empty($row->aksi)){echo $row->aksi;} ?></textarea>
								</div>
								<div style="width: 100%;">
									<table class="table-common" width="100%">
										<tr style="width:100%">
											<td>Target</td>
											<td><input style="width: 100%;" type="text" class="keterangan" name="keterangan[<?php echo $i; ?>]" value="<?php echo (!empty($row->target))?$row->target:''; ?>" readonly></td>
										</tr>
										<tr style="width:100%">
											<td>Capaian</td>
                                            <td><input style="width: 100%;" type="text" class="capaian" name="capaian[<?php echo $i; ?>]" value="<?php echo (!empty($row->capaian))?$row->capaian:''; ?>"></td>
										</tr>
									</table>
								</div>
							</div>
							<?php
									}
								}
							?>
						</td>
					</tr>
					<tr>

						<td colspan="2">
						<?php
							include_once("file_upload.php");
						?>
						</td>
					</tr>
				</tbody>
			</table>
		</form>
	</div>
	<footer>
		<div class="submit_link">
  			<input id="simpan" type="button" value="Simpan">
		</div>
	</footer>
</div>
