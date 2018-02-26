<script type="text/javascript">
	$(document).ready(function(){
		
		$('form#sasaran').validate({
			rules: {
				sasaran : "required",
				satuan : "required",
				target_1 : {
							required:true,
							number:true
				},
				target_2 : {
							required:true,
							number:true
				},
				target_3 : {
							required:true,
							number:true
				},
				target_4 : {
							required:true,
							number:true
				},
				target_5 : {
							required:true,
							number:true
				},
				target_kondisi_akhir: {
							required:true,
							number:true
				},
				kondisi_awal: {
							required:true,
							number:true
				}
			}
		});

		$("#simpan").click(function(){
			$('#indikator_frame_sasaran .indikator_val').each(function () {
			    $(this).rules('add', {
			        required: true
			    });
			});

		    var valid = $("form#sasaran").valid();
		    if (valid) {
		    	element.parent().next().hide();
		    	$.blockUI({
					css: window._css,
					overlayCSS: window._ovcss
				});

		    	$.ajax({
					type: "POST",
					url: $("form#sasaran").attr("action"),
					data: $("form#sasaran").serialize(),
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
							reload_jendela_kontrol();
						};
					}
				});
		    };
		});

		$("#tambah_indikator_sasaran").click(function(){
			key = $("#indikator_frame_sasaran").attr("key");
			key++;
			$("#indikator_frame_sasaran").attr("key", key);

			var name = "indikator["+ key +"]";
			var target_aw = "kondisi_awal["+ key +"]";
			var target_1 = "target_1["+ key +"]";
			var target_2 = "target_2["+ key +"]";
			var target_3 = "target_3["+ key +"]";
			var target_4 = "target_4["+ key +"]";
			var target_5 = "target_5["+ key +"]";
			var target_ah = "target_kondisi_akhir["+ key +"]";
			var satuan_target = "satuan["+ key +"]";
			var kode_positif_negatif = "status_indikator["+ key +"]";
			var kode_kategori_indikator = "kategori_indikator["+ key +"]";
			var fungsiHitung = "hitungTarget("+ key +")";

			$("#indikator_box_sasaran textarea").attr("name", name);
			$("#indikator_box_sasaran input#target_aw").attr("name", target_aw);
			$("#indikator_box_sasaran input#target_aw").attr("oninput", "hitungTarget("+ key +", 0)");
			$("#indikator_box_sasaran input#target_1").attr("name", target_1);
			$("#indikator_box_sasaran input#target_1").attr("oninput", "hitungTarget("+ key +", 1)");
			$("#indikator_box_sasaran input#target_2").attr("name", target_2);
			$("#indikator_box_sasaran input#target_2").attr("oninput", "hitungTarget("+ key +", 2)");
			$("#indikator_box_sasaran input#target_3").attr("name", target_3);
			$("#indikator_box_sasaran input#target_3").attr("oninput", "hitungTarget("+ key +", 3)");
			$("#indikator_box_sasaran input#target_4").attr("name", target_4);
			$("#indikator_box_sasaran input#target_4").attr("oninput", "hitungTarget("+ key +", 4)");
			$("#indikator_box_sasaran input#target_5").attr("name", target_5);
			$("#indikator_box_sasaran input#target_5").attr("oninput", "hitungTarget("+ key +", 5)");
			$("#indikator_box_sasaran input#target_ah").attr("name", target_ah);
			$("#indikator_box_sasaran input#target_ah").attr("oninput", "hitungTarget("+ key +", 5)");
			$("#indikator_box_sasaran input#satuan").attr("name", satuan_target);
			$("#indikator_box_sasaran select#status_indikator").attr("name", kode_positif_negatif);
			$("#indikator_box_sasaran select#status_indikator").attr("onchange", "hitungTarget("+ key +", 5)");
			$("#indikator_box_sasaran select#kategori_indikator").attr("name", kode_kategori_indikator);
			$("#indikator_box_sasaran select#kategori_indikator").attr("onchange", "hitungTarget("+ key +", 5)");

			$("#indikator_frame_sasaran").append($("#indikator_box_sasaran").html());
		});

		$(document).on("click", ".hapus_indikator_sasaran", function(){
			$(this).parent().parent().remove();
		});
	});
</script>
<div style="width: 900px">
	<header>
		<h3>
	<?php
		if (!empty($sasaran)){
		    echo "Edit Data Renstra";
		} else{
		    echo "Input Data Renstra";
		}
	?>
	</h3>
	</header>
	<div class="module_content">
		<form action="<?php echo site_url('renstra/save_sasaran');?>" method="POST" name="sasaran" id="sasaran" accept-charset="UTF-8" enctype="multipart/form-data" >
			<input type="hidden" name="id_sasaran" value="<?php if(!empty($sasaran->id)){echo $sasaran->id;} ?>" />
			<input type="hidden" name="id_renstra" value="<?php echo $id_renstra; ?>" />
			<input type="hidden" name="id_tujuan" value="<?php echo $tujuan->id; ?>" />
			<table class="fcari" width="100%">
				<tbody>
					<tr>
						<td width="20%">SKPD</td>
						<td width="80%"><?php echo $skpd->nama_skpd; ?></td>
					</tr>
					<tr>
						<td>Tujuan</td>
						<td><?php echo $tujuan->tujuan; ?></td>
					</tr>
					<tr>
						<td>Sasaran</td>
						<td>
							<textarea class="common" name="sasaran"><?php if(!empty($sasaran->sasaran)){echo $sasaran->sasaran;} ?></textarea>
						</td>
					</tr>
					<tr>
						<td>Indikator Kinerja <a id="tambah_indikator_sasaran" class="icon-plus-sign" href="javascript:void(0)"></a></td>
						<td id="indikator_frame_sasaran" key="<?php echo (!empty($indikator_sasaran))?$indikator_sasaran->num_rows():'1'; ?>">
							<?php
								if (!empty($indikator_sasaran)) {
									$i=0;
									foreach ($indikator_sasaran->result() as $row) {
										$i++;
							?>
							<input type="hidden" name="id_indikator_sasaran[<?php echo $i; ?>]" value="<?php echo $row->id; ?>">
							<div style="width: 100%; margin-top: 10px;">
								<div style="width: 100%;">
									<textarea style="width:95%" class="common indikator_val" name="indikator[<?php echo $i; ?>]"><?php if(!empty($row->indikator)){echo $row->indikator;} ?></textarea>
							<?php
								if ($i != 1) {
							?>
								<a class="icon-remove hapus_indikator_sasaran" href="javascript:void(0)" style="vertical-align: top;"></a>
							<?php
								}
							?>
								</div>
								<div style="width: 100%;">
									<table class="table-common" width="100%">
										<tr>
											<td colspan="2">Satuan</td>
											<td colspan="5"><input class="common indikator_val" name="satuan[<?php echo $i; ?>]" id="satuan" value="<?php echo $row->satuan_target; ?>"></td>
											<!-- <td colspan="5"><?php echo form_dropdown('satuan['. $i .']', $satuan, $row->satuan_target, 'class="common indikator_val" id="satuan"'); ?></td> -->
										</tr>
										<tr>
											<td colspan="2" rowspan="2">Kategori Indikator</td>
											<td colspan="5"><?php echo form_dropdown('status_indikator['. $i .']', $status_indikator, $row->status_indikator, 'class="common indikator_val" id="status_indikator" onchange="hitungTarget('. $i .', 5)"'); ?></td>
										</tr>
										<tr>
											<td colspan="5"><?php echo form_dropdown('kategori_indikator['. $i .']', $kategori_indikator, $row->kategori_indikator, 'class="common indikator_val" id="kategori_indikator" onchange="hitungTarget('. $i .', 5)"'); ?></td>
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
											<td width="14%"><input style="width: 100%;" type="text" class="target" oninput="hitungTarget(<?php echo $i; ?>, 0)" name="kondisi_awal[<?php echo $i; ?>]" value="<?php echo (!empty($row->kondisi_awal))?$row->kondisi_awal:''; ?>"></td>
											<td width="14%"><input style="width: 100%;" type="text" class="target" oninput="hitungTarget(<?php echo $i; ?>, 1)" name="target_1[<?php echo $i; ?>]" value="<?php echo (!empty($row->target_1))?$row->target_1:''; ?>"></td>
											<td width="14%"><input style="width: 100%;" type="text" class="target" oninput="hitungTarget(<?php echo $i; ?>, 2)" name="target_2[<?php echo $i; ?>]" value="<?php echo (!empty($row->target_2))?$row->target_2:''; ?>"></td>
											<td width="14%"><input style="width: 100%;" type="text" class="target" oninput="hitungTarget(<?php echo $i; ?>, 3)" name="target_3[<?php echo $i; ?>]" value="<?php echo (!empty($row->target_3))?$row->target_3:''; ?>"></td>
											<td width="14%"><input style="width: 100%;" type="text" class="target" oninput="hitungTarget(<?php echo $i; ?>, 4)" name="target_4[<?php echo $i; ?>]" value="<?php echo (!empty($row->target_4))?$row->target_4:''; ?>"></td>
											<td width="14%"><input style="width: 100%;" type="text" class="target" oninput="hitungTarget(<?php echo $i; ?>, 5)" name="target_5[<?php echo $i; ?>]" value="<?php echo (!empty($row->target_5))?$row->target_5:''; ?>"></td>
											<td width="14%"><input style="width: 100%;" type="text" class="target" name="target_kondisi_akhir[<?php echo $i; ?>]" value="<?php echo (!empty($row->kondisi_akhir))?$row->kondisi_akhir:''; ?>" readonly></td>
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
									<textarea style="width:95%" class="common indikator_val" name="indikator[1]"></textarea>
								</div>
								<div style="width: 100%;">
									<table class="table-common" width="100%">
										<tr>
											<td colspan="2">Satuan</td>
											<td colspan="5"><input class="common indikator_val" name="satuan[1]" id="satuan"></td>
											<!-- <td colspan="5"><?php echo form_dropdown('satuan[1]', $satuan, '', 'class="common indikator_val" id="satuan"'); ?></td> -->
										</tr>
										<tr>
											<td rowspan="2" colspan="2">Kategori Indikator</td>
											<td colspan="5"><?php echo form_dropdown('status_indikator[1]', $status_indikator, '', 'class="common indikator_val" id="status_indikator" onchange="hitungTarget(1, 5)"'); ?></td>
										</tr>
										<tr>
											<td colspan="5"><?php echo form_dropdown('kategori_indikator[1]', $kategori_indikator, '', 'class="common indikator_val" id="kategori_indikator" onchange="hitungTarget(1, 5)"'); ?></td>
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
											<td width="14%"><input style="width: 100%;" type="text" class="target" id="target_awal[1]" oninput="hitungTarget(1, 0)" name="kondisi_awal[1]"></td>
											<td width="14%"><input style="width: 100%;" type="text" class="target" id="target_1[1]" oninput="hitungTarget(1, 1)" name="target_1[1]"></td>
											<td width="14%"><input style="width: 100%;" type="text" class="target" id="target_2[1]" oninput="hitungTarget(1, 2)" name="target_2[1]"></td>
											<td width="14%"><input style="width: 100%;" type="text" class="target" id="target_3[1]" oninput="hitungTarget(1, 3)" name="target_3[1]"></td>
											<td width="14%"><input style="width: 100%;" type="text" class="target" id="target_4[1]" oninput="hitungTarget(1, 4)" name="target_4[1]"></td>
											<td width="14%"><input style="width: 100%;" type="text" class="target" id="target_5[1]" oninput="hitungTarget(1, 5)" name="target_5[1]"></td>
											<td width="14%"><input style="width: 100%;" type="text" class="target" id="target_akhir[1]"  name="target_kondisi_akhir[1]" readonly></td>
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
<div style="display: none" id="indikator_box_sasaran">
	<div style="width: 100%; margin-top: 15px;">
		<hr>
		<div style="width: 100%;">
			<textarea class="common indikator_val" name="indikator[1]" style="width:95%"></textarea>
			<a class="icon-remove hapus_indikator_sasaran" href="javascript:void(0)" style="vertical-align: top;"></a>
		</div>
		<div style="width: 100%;">
			<table class="table-common" width="100%">
				<tr>
					<td colspan="2">Satuan</td>
					<td colspan="5"><input class="common indikator_val" name="satuan[1]" id="satuan"></td>
					<!-- <td colspan="5"><?php echo form_dropdown('satuan[1]', $satuan, '', 'class="common indikator_val" id="satuan"'); ?></td> -->
				</tr>
				<tr>
					<td rowspan="2" colspan="2">Kategori Indikator</td>
					<td colspan="5"><?php echo form_dropdown('status_indikator[1]', $status_indikator, '', 'class="common indikator_val" id="status_indikator" onchange="hitungTarget(1, 5)"'); ?></td>
				</tr>
				<tr>
					<td colspan="5"><?php echo form_dropdown('kategori_indikator[1]', $kategori_indikator, '', 'class="common indikator_val" id="kategori_indikator" onchange="hitungTarget(1, 5)"'); ?></td>
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
					<td width="14%"><input style="width: 100%;" type="text" class="target" id="target_aw" oninput="hitungTarget(1, 0)" name="kondisi_awal[1]"></td>
					<td width="14%"><input style="width: 100%;" type="text" class="target" id="target_1" oninput="hitungTarget(1, 1)" name="target_1[1]"></td>
					<td width="14%"><input style="width: 100%;" type="text" class="target" id="target_2" oninput="hitungTarget(1, 2)" name="target_2[1]"></td>
					<td width="14%"><input style="width: 100%;" type="text" class="target" id="target_3" oninput="hitungTarget(1, 3)" name="target_3[1]"></td>
					<td width="14%"><input style="width: 100%;" type="text" class="target" id="target_4" oninput="hitungTarget(1, 4)" name="target_4[1]"></td>
					<td width="14%"><input style="width: 100%;" type="text" class="target" id="target_5" oninput="hitungTarget(1, 5)" name="target_5[1]"></td>
					<td width="14%"><input style="width: 100%;" type="text" class="target" id="target_ah" name="target_kondisi_akhir[1]" readonly></td>
				</tr>
			</table>
		</div>
	</div>
</div>
<script type="text/javascript">
function hitungTarget(index, target){
	var kategori_target = $("select[name='kategori_indikator["+ index +"]']").val();
	var status_target = $("select[name='status_indikator["+ index +"]']").val();
	var target_aw = $("input[name='kondisi_awal["+ index +"]']").val();
	var target_1 = $("input[name='target_1["+ index +"]']").val();
	var target_2 = $("input[name='target_2["+ index +"]']").val();
	var target_3 = $("input[name='target_3["+ index +"]']").val();
	var target_4 = $("input[name='target_4["+ index +"]']").val();
	var target_5 = $("input[name='target_5["+ index +"]']").val();
	var id_indikator = $("input[name='id_indikator_sasaran["+ index +"]']").val();

	// $('input[name=target_1[1]]').val($('input[name=kondisi_awal[1]]').val());
	// $('input[name='target_1[1]']').val($('input[name='kondisi_awal[1]']').val());
	if(!id_indikator || kategori_target == "3"){
		if (target_5==='0' || target_5==='') {
			if (target_4==='0' || target_4==='') {
				if (target_3==='0' || target_3==='') {
					if (target_2==='0' || target_2==='') {
						if (target_1==='0' || target_1==='') {
							do_hitung(kategori_target, status_target, index, target_aw);
						}else{
							do_hitung(kategori_target, status_target, index, target_1);
						}
					}else{
						do_hitung(kategori_target, status_target, index, target_2);
					}
				}else{
					do_hitung(kategori_target, status_target, index, target_3);
				}
			}else {
				do_hitung(kategori_target, status_target, index, target_4);
			}
		}else {
			do_hitung(kategori_target, status_target, index, target_5);
		}
	}else{
		//alert(target_5);
		if (target_5==='0' || target_5==='') {
			if (target_4==='0' || target_4==='') {
				if (target_3==='0' || target_3==='') {
					if (target_2==='0' || target_2==='') {
						if (target_1==='0' || target_1==='') {
							do_hitung(kategori_target, status_target, index, target_aw);
						}else{
							do_hitung(kategori_target, status_target, index, target_1);
						}
					}else{
						do_hitung(kategori_target, status_target, index, target_2);
					}
				}else{
					do_hitung(kategori_target, status_target, index, target_3);
				}
			}else {
				do_hitung(kategori_target, status_target, index, target_4);
			}
		}else {
			do_hitung(kategori_target, status_target, index, target_5);
		}
	}
}

	function do_hitung(kategori_target, status_target, index, forakhir){
		var target_aw = $("input[name='kondisi_awal["+ index +"]']").val();
		var target_1 = $("input[name='target_1["+ index +"]']").val();
		var target_2 = $("input[name='target_2["+ index +"]']").val();
		var target_3 = $("input[name='target_3["+ index +"]']").val();
		var target_4 = $("input[name='target_4["+ index +"]']").val();
		var target_5 = $("input[name='target_5["+ index +"]']").val();

		if (status_target == "1") {
			if (kategori_target == "1") {
				$("input[name='target_kondisi_akhir["+ index +"]']").val(forakhir);
			}else if (kategori_target == "2" ) {
				$("input[name='target_kondisi_akhir["+ index +"]']").val(forakhir);
			}else	if (kategori_target == "3") {
				var hasil = parseFloat(target_aw) + parseFloat(target_1) + parseFloat(target_2) + parseFloat(target_3) + parseFloat(target_4) + parseFloat(target_5);
				$("input[name='target_kondisi_akhir["+ index +"]']").val(hasil);
			}
		}else if (status_target == "2") {
			if (kategori_target == "1") {
				$("input[name='target_kondisi_akhir["+ index +"]']").val(forakhir);
			}else if (kategori_target == "2" ) {
				$("input[name='target_kondisi_akhir["+ index +"]']").val(forakhir);
			}else	if (kategori_target == "3") {
				var hasil = parseFloat(target_aw) - parseFloat(target_1) - parseFloat(target_2) - parseFloat(target_3) - parseFloat(target_4) - parseFloat(target_5);
				$("input[name='target_kondisi_akhir["+ index +"]']").val(hasil);
			}
		}
	}
</script>
