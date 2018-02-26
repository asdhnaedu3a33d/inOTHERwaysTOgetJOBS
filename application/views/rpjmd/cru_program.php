<script type="text/javascript">
	$(document).ready(function(){
		$('input[name=pagu_tahun_1]').autoNumeric(numOptionsNotRound);
		$('input[name=pagu_tahun_2]').autoNumeric(numOptionsNotRound);
		$('input[name=pagu_tahun_3]').autoNumeric(numOptionsNotRound);
		$('input[name=pagu_tahun_4]').autoNumeric(numOptionsNotRound);
		$('input[name=pagu_tahun_5]').autoNumeric(numOptionsNotRound);
		$('input[name=pagu_rpjmd]').autoNumeric(numOptionsNotRound);
		$('input[name=pagu_renstra]').autoNumeric(numOptionsNotRound);

		$('form#program').validate({
			rules: {
				program : "required",
				program : "required",
				pagu_rpjmd : "required",
			}
		});

		$("#simpan").click(function(){
			$('#kebijakan_frame .kebijakan_val').each(function () {
			    $(this).rules('add', {
			        required: true
			    });
			});

		    var valid = $("form#program").valid();
		    if (valid) {
					$('input[name=pagu_rpjmd]').val($('input[name=pagu_rpjmd]').autoNumeric('get'));
					$('input[name=pagu_renstra]').val($('input[name=pagu_renstra]').autoNumeric('get'));
					$('input[name=pagu_tahun_1]').val($('input[name=pagu_tahun_1]').autoNumeric('get'));
					$('input[name=pagu_tahun_2]').val($('input[name=pagu_tahun_2]').autoNumeric('get'));
					$('input[name=pagu_tahun_3]').val($('input[name=pagu_tahun_3]').autoNumeric('get'));
					$('input[name=pagu_tahun_4]').val($('input[name=pagu_tahun_4]').autoNumeric('get'));
					$('input[name=pagu_tahun_5]').val($('input[name=pagu_tahun_5]').autoNumeric('get'));

		    	element_sasaran.parent().next().hide();
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
							tab_element = "program";
							element_sasaran.trigger( "click" );
						};
					}
				});
		    };
		});

		$("#tambah_indikator_program").click(function(){
			key = $("#indikator_frame_program").attr("key");
			key++;
			$("#indikator_frame_program").attr("key", key);

			var name = "indikator_kinerja["+ key +"]";
			var pengukuran = "cara_pengukuran["+ key +"]";
			var target_aw = "kondisi_awal["+ key +"]";
			var target_1 = "target_1["+ key +"]";
			var target_2 = "target_2["+ key +"]";
			var target_3 = "target_3["+ key +"]";
			var target_4 = "target_4["+ key +"]";
			var target_5 = "target_5["+ key +"]";
			var target_ah = "kondisi_akhir["+ key +"]";
			var satuan_target = "satuan_target["+ key +"]";
			var status_target = "status_target["+ key +"]";
			var kategori_target = "kategori_target["+ key +"]";
			var fungsiHitung = "hitungTarget("+ key +")";

			$("#indikator_box_program textarea.indikator_val").attr("name", name);
			$("#indikator_box_program textarea.pengukuran_val").attr("name", pengukuran);
			$("#indikator_box_program input#target_aw").attr("name", target_aw);
			$("#indikator_box_program input#target_aw").attr("oninput", fungsiHitung);
			$("#indikator_box_program input#target_1").attr("name", target_1);
			$("#indikator_box_program input#target_1").attr("oninput", fungsiHitung);
			$("#indikator_box_program input#target_2").attr("name", target_2);
			$("#indikator_box_program input#target_2").attr("oninput", fungsiHitung);
			$("#indikator_box_program input#target_3").attr("name", target_3);
			$("#indikator_box_program input#target_3").attr("oninput", fungsiHitung);
			$("#indikator_box_program input#target_4").attr("name", target_4);
			$("#indikator_box_program input#target_4").attr("oninput", fungsiHitung);
			$("#indikator_box_program input#target_5").attr("name", target_5);
			$("#indikator_box_program input#target_5").attr("oninput", fungsiHitung);
			$("#indikator_box_program input#target_ah").attr("name", target_ah);
			$("#indikator_box_program input#target_ah").attr("oninput", fungsiHitung);
			$("#indikator_box_program input#satuan_target").attr("name", satuan_target);
			$("#indikator_box_program select#status_target").attr("name", status_target);
			$("#indikator_box_program select#status_target").attr("onchange", fungsiHitung);
			$("#indikator_box_program select#kategori_target").attr("name", kategori_target);
			$("#indikator_box_program select#kategori_target").attr("onchange", fungsiHitung);
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
		    echo "Edit Data RPJMD";
		} else{
		    echo "Input Data RPJMD";
		}
	?>
	</h3>
	</header>
	<div class="module_content">
		<form action="<?php echo site_url('rpjmd/save_program');?>" method="POST" name="program" id="program" accept-charset="UTF-8" enctype="multipart/form-data" >
			<input type="hidden" name="id_program" value="<?php if(!empty($program->id)){echo $program->id;} ?>" />
			<input type="hidden" name="id_rpjmd" value="<?php echo $id_rpjmd; ?>" />
			<input type="hidden" name="id_tujuan" value="<?php echo $tujuan->id; ?>" />
			<input type="hidden" name="id_sasaran" value="<?php echo $sasaran->id; ?>" />
			<table class="fcari" width="100%">
				<tr>
					<td width="20%"><strong>Tujuan</strong></td>
					<td width="80%"><?php echo $tujuan->tujuan; ?></td>
				</tr>
				<tr>
					<td><strong>Sasaran</strong></td>
					<td><?php echo $sasaran->sasaran; ?></td>
				</tr>
				<tr>
					<td><strong>Program</strong></td>
					<td><textarea class="common" name="nama_prog"><?php if(!empty($program->nama_prog)){echo $program->nama_prog;} ?></textarea></td>
				</tr>
				<tr>
					<td><strong>Indikator </strong><a id="tambah_indikator_program" class="icon-plus-sign" href="javascript:void(0)"></a></td>
					<td id="indikator_frame_program" key="<?php echo (!empty($indikator_program))?$indikator_program->num_rows():'1'; ?>" >
						<?php
							if (!empty($indikator_program)) {
								$i=0;
								foreach ($indikator_program->result() as $row) {
									$i++;
						?>
						<input type="hidden" name="id_indikator_program[<?php echo $i; ?>]" value="<?php echo $row->id; ?>">
						<div style="width: 100%; margin-top: 10px;">
							<div style="width: 100%;">
								<textarea style="width:95%" class="common indikator_val" name="indikator_kinerja[<?php echo $i; ?>]"><?php if(!empty($row->indikator)){echo $row->indikator;} ?></textarea>
								<?php
									if ($i != 1) {
								?>
									<a class="icon-remove hapus_indikator_program" href="javascript:void(0)" style="vertical-align: top;"></a>
								<?php
									}
								?>
							</div>
							<div style="width: 100%;">
								<table width="100%" style="border: none;">
									<tr>
										<td colspan="2">Cara Pengukuran</td>
										<td colspan="5">
											<textarea class="common pengukuran_val" name="cara_pengukuran[<?php echo $i; ?>]"><?php if(!empty($row->cara_pengukuran)){echo $row->cara_pengukuran;} ?></textarea>
										</td>
									</tr>
									<tr>
										<td colspan="2">Satuan</td>
										<!-- <td width="70%" colspan="4"><?php echo form_dropdown('satuan_target['. $i .']', $satuan, $row->satuan_target, 'class="common indikator_val" id="satuan_target"'); ?></td> -->
										<td colspan="5"><input class="common indikator_val" name="satuan_target[<?php echo $i; ?>]" id="satuan_target" value="<?php echo $row->satuan_target; ?>"></td>
									</tr>
									<tr>
										<td colspan="2">Kategori Indikator</td>
										<td colspan="2"><?php echo form_dropdown('status_target['. $i .']', $status_indikator, $row->status_indikator, 'class="common indikator_val" id="status_target" onchange="hitungTarget('. $i .')"'); ?></td>
										<td colspan="3"><?php echo form_dropdown('kategori_target['. $i .']', $kategori_indikator, $row->kategori_indikator, 'class="common indikator_val" id="kategori_target" onchange="hitungTarget('. $i .')"'); ?></td>
									</tr>
									<tr>
										<th>Kondisi Awal</th>
										<th>Target 1</th>
										<th>Target 2</th>
										<th>Target 3</th>
										<th>Target 4</th>
										<th>Target 5</th>
										<th>Kondisi Akhir</th>
									</tr>
									<tr>
										<td><input style="width: 100%;" type="text" class="target" id="target_aw" oninput="hitungTarget(<?php echo $i; ?>)" name="kondisi_awal[<?php echo $i; ?>]" value="<?php echo $row->kondisi_awal; ?>"></td>
										<td ><input style="width: 100%;" type="text" class="target" oninput="hitungTarget(<?php echo $i; ?>)" name="target_1[<?php echo $i; ?>]" value="<?php echo (!empty($row->target_1))?$row->target_1:''; ?>"></td>
										<td ><input style="width: 100%;" type="text" class="target" oninput="hitungTarget(<?php echo $i; ?>)" name="target_2[<?php echo $i; ?>]" value="<?php echo (!empty($row->target_2))?$row->target_2:''; ?>"></td>
										<td ><input style="width: 100%;" type="text" class="target" oninput="hitungTarget(<?php echo $i; ?>)" name="target_3[<?php echo $i; ?>]" value="<?php echo (!empty($row->target_3))?$row->target_3:''; ?>"></td>
										<td ><input style="width: 100%;" type="text" class="target" oninput="hitungTarget(<?php echo $i; ?>)" name="target_4[<?php echo $i; ?>]" value="<?php echo (!empty($row->target_4))?$row->target_4:''; ?>"></td>
										<td ><input style="width: 100%;" type="text" class="target" oninput="hitungTarget(<?php echo $i; ?>)" name="target_5[<?php echo $i; ?>]" value="<?php echo (!empty($row->target_5))?$row->target_5:''; ?>"></td>
										<td><input style="width: 100%;" type="text" class="target" id="target_ah" name="kondisi_akhir[<?php echo $i; ?>]" value="<?php echo $row->kondisi_akhir; ?>" readonly></td>
									</tr>
								</table>
							</div>
						</div>
						<?php
								}
							}else{
						?>
						<div style="width: 100%; margin-top: 10px;">
							<div style="width: 100%;">
								<textarea style="width:95%" class="common indikator_val" name="indikator_kinerja[1]"></textarea>
							</div>
							<div style="width: 100%;">
								<table width="100%" style="border: none;">
									<tr>
										<td colspan="2">Cara Pengukuran</td>
										<td colspan="5">
											<textarea class="common pengukuran_val" name="cara_pengukuran[1]"></textarea>
										</td>
									</tr>
									<tr>
										<td colspan="2">Satuan</td>
										<!-- <td width="70%" colspan="4"><?php echo form_dropdown('satuan_target[1]', $satuan, '', 'class="common indikator_val" id="satuan_target"'); ?></td> -->
										<td colspan="5"><input class="common indikator_val" name="satuan_target[1]" id="satuan_target"></td>
									</tr>
									<tr>
										<td colspan="2">Kategori Indikator</td>
										<td colspan="2"><?php echo form_dropdown('status_target[1]', $status_indikator, '', 'class="common indikator_val" id="status_target" onchange="hitungTarget(1)"'); ?></td>
										<td colspan="3"><?php echo form_dropdown('kategori_target[1]', $kategori_indikator, '', 'class="common indikator_val" id="kategori_target" onchange="hitungTarget(1)"'); ?></td>
									</tr>
									<tr>
										<th>Kondisi Awal</th>
										<th>Target 1</th>
										<th>Target 2</th>
										<th>Target 3</th>
										<th>Target 4</th>
										<th>Target 5</th>
										<th>Kondisi Akhir</th>
									</tr>
									<tr>
										<td><input style="width: 100%;" type="text" class="target" id="target_aw" oninput="hitungTarget(1)" name="kondisi_awal[1]"></td>
										<td><input style="width: 100%;" type="text" class="target" id="target_1[1]" oninput="hitungTarget(1)" name="target_1[1]"></td>
										<td><input style="width: 100%;" type="text" class="target" id="target_2[1]" oninput="hitungTarget(1)" name="target_2[1]"></td>
										<td><input style="width: 100%;" type="text" class="target" id="target_3[1]" oninput="hitungTarget(1)" name="target_3[1]"></td>
										<td><input style="width: 100%;" type="text" class="target" id="target_4[1]" oninput="hitungTarget(1)" name="target_4[1]"></td>
										<td><input style="width: 100%;" type="text" class="target" id="target_5[1]" oninput="hitungTarget(1)" name="target_5[1]"></td>
										<td><input style="width: 100%;" type="text" class="target" id="target_ah" name="kondisi_akhir[1]" readonly></td>
									</tr>
								</table>
							</div>
						</div>
						<?php
							}
						?>
					</td>
				</tr>
				<tr>
					<td><strong>Pagu Indikatif Renstra</strong></td>
					<!-- <td><input class="common" name="pagu_renstra" value="<?php if(!empty($program->pagu_renstra)){echo $program->pagu_renstra;}else{echo '0';} ?>" readonly /></td> -->
					<td>
						<table>
							<tr>
								<th>Tahun 1</th>
								<th>Tahun 2</th>
								<th>Tahun 3</th>
								<th>Tahun 4</th>
								<th>Tahun 5</th>
							</tr>
							<tr>
								<td><input type="text" value="0"></td>
								<td><input type="text" value="0"></td>
								<td><input type="text" value="0"></td>
								<td><input type="text" value="0"></td>
								<td><input type="text" value="0"></td>
							</tr>
							<tr>
								<th colspan="5">Total Pagu Renstra</th>
							</tr>
							<tr>
								<td colspan="5"><input class="common" name="pagu_renstra" value="<?php if(!empty($program->pagu_renstra)){echo $program->pagu_renstra;}else{echo '0';} ?>" readonly /></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td><strong>Pagu Indikatif RPJMD</strong></td>
					<!-- <td><input class="common" name="pagu_rpjmd" value="<?php if(!empty($program->pagu_rpjmd)){echo $program->pagu_rpjmd;} ?>" /></td> -->
					<td>
						<table>
							<tr>
								<th>Tahun 1</th>
								<th>Tahun 2</th>
								<th>Tahun 3</th>
								<th>Tahun 4</th>
								<th>Tahun 5</th>
							</tr>
							<tr>
								<td><input type="text" name="pagu_tahun_1" onkeyup="hitung_pagu();" value="<?php if(!empty($program->pagu_tahun_1)){echo $program->pagu_tahun_1;} ?>"></td>
								<td><input type="text" name="pagu_tahun_2" onkeyup="hitung_pagu();" value="<?php if(!empty($program->pagu_tahun_2)){echo $program->pagu_tahun_2;} ?>"></td>
								<td><input type="text" name="pagu_tahun_3" onkeyup="hitung_pagu();" value="<?php if(!empty($program->pagu_tahun_3)){echo $program->pagu_tahun_3;} ?>"></td>
								<td><input type="text" name="pagu_tahun_4" onkeyup="hitung_pagu();" value="<?php if(!empty($program->pagu_tahun_4)){echo $program->pagu_tahun_4;} ?>"></td>
								<td><input type="text" name="pagu_tahun_5" onkeyup="hitung_pagu();" value="<?php if(!empty($program->pagu_tahun_5)){echo $program->pagu_tahun_5;} ?>"></td>
							</tr>
							<tr>
								<th colspan="5">Total Pagu RPJMD</th>
							</tr>
							<tr>
								<td colspan="5"><input type="text" name="pagu_rpjmd"  value="<?php if(!empty($program->pagu_rpjmd)){echo $program->pagu_rpjmd;} ?>"></td>
							</tr>
						</table>
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
			<table width="100%" style="border: none;">
				<tr>
					<td colspan="2">Cara Pengukuran</td>
					<td colspan="5">
						<textarea class="common pengukuran_val" name="cara_pengukuran[1]"></textarea>
					</td>
				</tr>
				<tr>
					<td colspan="2">Satuan</td>
					<!-- <td width="70%" colspan="4"><?php echo form_dropdown('satuan_target[1]', $satuan, '', 'class="common indikator_val" id="satuan_target"'); ?></td> -->
					<td colspan="5"><input class="common indikator_val" name="satuan_target[1]" id="satuan_target"></td>
				</tr>
				<tr>
					<td colspan="2">Kategori Indikator</td>
					<td colspan="2"><?php echo form_dropdown('status_target[1]', $status_indikator, '', 'class="common indikator_val" id="status_target" onchange="hitungTarget(1)"'); ?></td>
					<td colspan="3"><?php echo form_dropdown('kategori_target[1]', $kategori_indikator, '', 'class="common indikator_val" id="kategori_target" onchange="hitungTarget(1)"'); ?></td>
				</tr>
				<tr>
					<th>Kondisi Awal</th>
					<th>Target 1</th>
					<th>Target 2</th>
					<th>Target 3</th>
					<th>Target 4</th>
					<th>Target 5</th>
					<th>Kondisi Akhir</th>
				</tr>
				<tr>
					<td><input style="width: 100%;" type="text" class="target" id="target_aw" oninput="hitungTarget(1)" name="kondisi_awal[1]"></td>
					<td><input style="width: 100%;" type="text" class="target" id="target_1" oninput="hitungTarget(1)" name="target_1[1]"></td>
					<td><input style="width: 100%;" type="text" class="target" id="target_2" oninput="hitungTarget(1)" name="target_2[1]"></td>
					<td><input style="width: 100%;" type="text" class="target" id="target_3" oninput="hitungTarget(1)" name="target_3[1]"></td>
					<td><input style="width: 100%;" type="text" class="target" id="target_4" oninput="hitungTarget(1)" name="target_4[1]"></td>
					<td><input style="width: 100%;" type="text" class="target" id="target_5" oninput="hitungTarget(1)" name="target_5[1]"></td>
					<td><input style="width: 100%;" type="text" class="target" id="target_ah" name="kondisi_akhir[1]" readonly></td>
				</tr>
			</table>
		</div>
	</div>
</div>
<script type="text/javascript">
	function hitungTarget(index){
		var kategori_target = $("select[name='kategori_target["+ index +"]']").val();
		var status_target = $("select[name='status_target["+ index +"]']").val();
		var target_aw = $("input[name='kondisi_awal["+ index +"]']").val();
		var target_1 = $("input[name='target_1["+ index +"]']").val();
		var target_2 = $("input[name='target_2["+ index +"]']").val();
		var target_3 = $("input[name='target_3["+ index +"]']").val();
		var target_4 = $("input[name='target_4["+ index +"]']").val();
		var target_5 = $("input[name='target_5["+ index +"]']").val();


		if (status_target == "1") {
			if (kategori_target == "1") {
				$("input[name='kondisi_akhir["+ index +"]']").val(target_5);
			}else if (kategori_target == "2" ) {
				$("input[name='kondisi_akhir["+ index +"]']").val(target_5);
			}else	if (kategori_target == "3") {
				var hasil = parseFloat(target_aw) + parseFloat(target_1) + parseFloat(target_2) + parseFloat(target_3) + parseFloat(target_4) + parseFloat(target_5);
				$("input[name='kondisi_akhir["+ index +"]']").val(hasil);
			}
		}else if (status_target == "2") {
			if (kategori_target == "1") {
				$("input[name='kondisi_akhir["+ index +"]']").val(target_5);
			}else if (kategori_target == "2" ) {
				$("input[name='kondisi_akhir["+ index +"]']").val(target_5);
			}else	if (kategori_target == "3") {
				var hasil = parseFloat(target_aw) - parseFloat(target_1) - parseFloat(target_2) - parseFloat(target_3) - parseFloat(target_4) - parseFloat(target_5);
				$("input[name='kondisi_akhir["+ index +"]']").val(hasil);
			}
		}
	}

	function hitung_pagu() {
		var th1 = $("input[name='pagu_tahun_1']").autoNumeric('get');
		var th2 = $("input[name='pagu_tahun_2']").autoNumeric('get');
		var th3 = $("input[name='pagu_tahun_3']").autoNumeric('get');
		var th4 = $("input[name='pagu_tahun_4']").autoNumeric('get');
		var th5 = $("input[name='pagu_tahun_5']").autoNumeric('get');
		var total = 0; 

		if (!th1) {th1 = 0;}
		if (!th2) {th2 = 0;}
		if (!th3) {th3 = 0;}
		if (!th4) {th4 = 0;}
		if (!th5) {th5 = 0;}

		total = parseFloat(th1) + parseFloat(th2) + parseFloat(th3) + parseFloat(th4) + parseFloat(th5);
		$("input[name='pagu_rpjmd']").autoNumeric('set', total);
	}
</script>
