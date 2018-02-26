<script type="text/javascript">
	$(document).ready(function(){
		prepare_chosen();

		$('form#program').validate({
			rules: {
				id_prog_or_keg : "required",
			},
			ignore: ":hidden:not(select)"
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
		    	close_all();
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
						$.blockUI({
							message: msg.msg,
							timeout: 2000,
							css: window._css,
							overlayCSS: window._ovcss
						});
						$.facebox.close();
						element_sasaran.trigger('click');
					}
				});
		    };
		});

		$("#tambah_indikator_program").click(function(){
			key = $("#indikator_frame_program").attr("key");
			key++;
			$("#indikator_frame_program").attr("key", key);

			var name = "indikator_kinerja["+ key +"]";
			var target = "target["+ key +"]";
			var satuan_target = "satuan_target["+ key +"]";
			var status_target = "status_target["+ key +"]";
			var kategori_target = "kategori_target["+ key +"]";

			$("#indikator_box_program textarea").attr("name", name);
			$("#indikator_box_program input#target").attr("name", target);
			$("#indikator_box_program input#satuan_target").attr("name", satuan_target);
			$("#indikator_box_program select#status_target").attr("name", status_target);
			$("#indikator_box_program select#kategori_target").attr("name", kategori_target);
			$("#indikator_frame_program").append($("#indikator_box_program").html());
		});

		$(document).on("click", ".hapus_indikator_program", function(){
			$(this).parent().parent().remove();
		});
	});
</script>
<div style="width: 900px">
	<header>
		<h3>
	<?php
		if (!empty($program)){
		    echo "Edit Data Program Pembangunan";
		} else{
		    echo "Tambah Data Program Pembangunan";
		}
	?>
	</h3>
	</header>
	<div class="module_content">
		<form action="<?php echo site_url('prioritas_pembangunan_rkpd/save_program');?>" method="POST" name="program" id="program" accept-charset="UTF-8" enctype="multipart/form-data" >
			<input type="hidden" name="id" value="<?php if(!empty($program->id)){echo $program->id;} ?>" />
			<input type="hidden" name="id_rkpd_prioritas" value="<?php if(!empty($prioritas->id_prio)){echo $prioritas->id_prio;} ?>" />
			<input type="hidden" name="id_rkpd_sasaran" value="<?php if(!empty($sasaran->id_prio)){echo $sasaran->id_prio;} ?>" />
			<table class="fcari" width="100%">
				<tr>
					<td width="20%"><strong>Prioritas Pembangunan</strong></td>
					<td width="80%"><?php echo $prioritas->id_prioritas; ?></td>
				</tr>
				<tr>
					<td width="20%"><strong>Sasaran Pembangunan</strong></td>
					<td width="80%"><?php echo $sasaran->sasaran; ?></td>
				</tr>
				<tr>
					<td width="20%"><strong>Program Pembangunan</strong></td>
					<td width="80%"><?php echo $program_combo; ?></td>
				</tr>
				<tr>
					<td>
						<strong>Indikator</strong>
						<a id="tambah_indikator_program" class="icon-plus-sign" href="javascript:void(0)"></a>
					</td>
					<td id="indikator_frame_program" key="<?php echo (!empty($indikator))?$indikator->num_rows():'1'; ?>">
						<?php if (!empty($indikator)): ?>
							<?php foreach ($indikator->result() as $key => $row): ?>
								<?php $i = $key + 1; ?>
								<input type="hidden" name="id_indikator[<?php echo $i; ?>]" value="<?php echo $row->id; ?>">
								<div style="margin-top:5px">
							  		<div style="width:100%">
										<textarea class="common indikator_val" name="indikator_kinerja[<?php echo $i; ?>]" style="width:95%"><?php if(!empty($row->indikator)){echo $row->indikator;} ?></textarea>
										<?php if ($key != 0): ?>
											<a class="icon-remove hapus_indikator_program" href="javascript:void(0)" style="vertical-align: top;"></a>
										<?php endif ?>
									</div>
									<div style="width: 100%;">
										<table class="table-common" width="100%">
											<tr style="width:100%">
												<td>Satuan</td>
												<td>
													<input class="common indikator_val" name="satuan_target[<?php echo $i; ?>]" id="satuan_target" value="<?php echo $row->satuan_target; ?>">
												</td>
											</tr>
											<tr>
												<td rowspan="2">Kategori Indikator</td>
												<td><?php echo form_dropdown('status_target['. $i .']', $status_indikator, $row->status_indikator, 'class="common indikator_val" id="status_target"'); ?></td>
											</tr>
											<tr>
												<td><?php echo form_dropdown('kategori_target['. $i .']', $kategori_indikator, $row->kategori_indikator, 'class="common indikator_val" id="kategori_target"'); ?></td>
											</tr>
											<tr style="width:100%">
												<td>Target</td>
	                                            <td><input style="width: 100%;" type="text" class="target" name="target[<?php echo $i; ?>]" value="<?php echo (!empty($row->target))?$row->target:''; ?>"></td>
											</tr>
										</table>
									</div>
								</div>
							<?php endforeach ?>
						<?php else: ?>
							<div style="margin-top:5px">
						  		<div style="width:100%">
									<textarea class="common indikator_val" name="indikator_kinerja[1]" style="width:95%"></textarea>
								</div>
								<div style="width: 100%;">
									<table class="table-common" width="100%">
										<tr style="width:100%">
											<td>Satuan</td>
											<td>
												<input class="common indikator_val" name="satuan_target[1]" id="satuan_target">
											</td>
										</tr>
										<tr>
											<td rowspan="2">Kategori Indikator</td>
											<td><?php echo form_dropdown('status_target[1]', $status_indikator, NULL, 'class="common indikator_val" id="status_target"'); ?></td>
										</tr>
										<tr>
											<td><?php echo form_dropdown('kategori_target[1]', $kategori_indikator, NULL, 'class="common indikator_val" id="kategori_target"'); ?></td>
										</tr>
										<tr style="width:100%">
											<td>Target</td>
                                            <td><input style="width: 100%;" type="text" class="target" name="target[1]"></td>
										</tr>
									</table>
								</div>
							</div>
						<?php endif ?>
					</td>
				</tr>
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
			<textarea class="common indikator_val" name="indikator_kinerja[]" style="width:95%"></textarea>
			<a class="icon-remove hapus_indikator_program" href="javascript:void(0)" style="vertical-align: top;"></a>
		</div>
		<div style="width: 100%;">
			<table class="table-common" width="100%">
				<tr style="width:100%">
					<td>Satuan</td>
					<td><input class="common indikator_val" name="satuan_target[1]" id="satuan_target"></td>
				</tr>
				<tr>
					<td rowspan="2">Kategori Indikator</td>
					<td>
						<?php echo form_dropdown('status_target[1]', $status_indikator, '', 'class="common indikator_val" id="status_target"'); ?>
					</td>
				</tr>
				<tr>
					<td>
						<?php echo form_dropdown('kategori_target[1]', $kategori_indikator, '', 'class="common indikator_val" id="kategori_target"'); ?>
					</td>
				</tr>
				<tr style="width:100%">
					<td>Target</td>
					<td><input style="width: 100%;" type="text" class="target" id="target" name="target[1]"></td>
				</tr>
			</table>
		</div>
	</div>
</div>
