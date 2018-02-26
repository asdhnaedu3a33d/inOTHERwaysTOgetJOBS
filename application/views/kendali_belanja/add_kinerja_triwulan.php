<script type="text/javascript">
	$('#nominal').autoNumeric(numOptions);
	prepare_chosen();


	//agar validation tetap aktif untuk chosen dropdown
	$('form#program').validate({

	});

	$("#simpan").click(function(){
		$('#indikator_frame_program .indikator_val').each(function () {
		    $(this).rules('add', {
		        required: true
		    });
		});

		$('#indikator_frame_program .target').each(function () {
		    $(this).rules('add', {
		        required:true,
				number:true
		    });
		});

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
						location.reload();

					};
				}
			});


			//$("form#program").submit();
	    };
	});

	$("#tambah_kinerja_triwulan").click(function(){
		key = $("#indikator_frame_program").attr("key");
		key++;
		$("#indikator_frame_program").attr("key", key);

		var name = "catatan["+ key +"]";
		var keterangan = "keterangan["+ key +"]";
		var capaian = "capaian["+ key +"]";

		$("#indikator_box_program textarea#catatan").attr("name", name);
		$("#indikator_box_program input#keterangan").attr("name", keterangan);
		$("#indikator_box_program input#capaian").attr("name", capaian);
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
	<?php
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
	?>
		<form action="<?php echo site_url('kendali/save_add_kinerja_triwulan');?>" method="POST" name="program" id="program" accept-charset="UTF-8" enctype="multipart/form-data" >
			<input type="hidden" name="id_dpa_prog_keg_triwulan" value="<?php if(!empty($id_dpa_prog_keg_triwulan)){echo $id_dpa_prog_keg_triwulan;} ?>" />
			<table class="fcari" width="100%">
				<tbody>
					<tr>
						<td width="25%">Tambah Kinerja Triwulan <a id="tambah_kinerja_triwulan" class="icon-plus-sign" href="javascript:void(0)"></a></td>
						<td id="indikator_frame_program" key="<?php echo (!empty($kinerja_triwulan))?$kinerja_triwulan->num_rows():'1'; ?>">
							<?php
								if (!empty($kinerja_triwulan)) {
									$i=0;
									foreach ($kinerja_triwulan->result() as $row) {
										$i++;
							?>
							<input type="hidden" name="id_kinerja_triwulan[<?php echo $i; ?>]" value="<?php echo $row->id; ?>">
							<div style="margin-top:5px">
							  <label>Catatan</label>
							  <div style="width:100%">
									<textarea class="common catatan" name="catatan[<?php echo $i; ?>]" style="width:95%"><?php if(!empty($row->catatan)){echo $row->catatan;} ?></textarea>

							<?php
								if ($i != 1) {
							?>
								<a class="icon-remove hapus_catatan" href="javascript:void(0)" style="vertical-align: top;" ></a>
							<?php
								}
							?>		,
								</div>
								<div style="width: 100%;display:none">
									<table class="table-common" width="100%">
										<tr style="width:100%">
											<td>Keterangan</td>
											<td><input style="width: 100%;" type="text" class="keterangan" name="keterangan[<?php echo $i; ?>]" value="<?php echo (!empty($row->keterangan))?$row->keterangan:''; ?>"></td>
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
								}else{
							?>
							<div style="width: 100%; margin-top: 10px;">
								<label>Catatan</label>
								<div style="width: 100%;">
									<textarea class="common catatan" name="catatan[1]" style="width:95%"></textarea>
								</div>
								<div style="width: 100%; display:none" >
									<table class="table-common" width="100%">
										<tr style="width:100%">
											<td>Keterangan</td>
											<td><input style="width: 100%;" type="text" class="target" name="keterangan[1]"></td>
										</tr>
										<tr style="width:100%">
											<td>Capaian</td>
                                            <td><input style="width: 100%;" type="text" class="capaian" name="capaian[1]"></td>

										</tr>
									</table>
								</div>
							</div>
							<?php
								}
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
<div style="display: none" id="indikator_box_program">
	<div style="width: 100%; margin-top: 15px;">
		<hr>
		<div style="width: 100%;">
			<label>catatan</label>
			<textarea class="common catatan" name="catatan[]" id="catatan" style="width:95%"></textarea>
			<a class="icon-remove hapus_catatan" href="javascript:void(0)" style="vertical-align: top;"></a>
		</div>
		<div style="width: 100%;display:none" >
			<table class="table-common" width="100%">
				<tr style="width:100%">
					<td>keterangan</td>
					<td><input style="width: 100%;" type="text" class="common keterangan" id="keterangan" name="keterangan[]"></td>
				</tr>
				<tr style="width:100%">
					<td>Capaian</td>
					<td><input style="width: 100%;" type="text" class="common capaian" id="capaian" name="capaian[]"></td>
				</tr>
			</table>
		</div>
	</div>
</div>
