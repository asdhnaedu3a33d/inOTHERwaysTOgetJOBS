<?php
if (!empty($revisi_rpjmd)) {
	$nominal_1_pro = 0;
	$nominal_2_pro = 0;
	$nominal_3_pro = 0;
	$nominal_4_pro = 0;
	$nominal_5_pro = 0;

	if (!empty($nominal_banding->nominal_1_pro)) {
		$nominal_1_pro = $nominal_banding->nominal_1_pro;
	}
	if (!empty($nominal_banding->nominal_2_pro)) {
		$nominal_2_pro = $nominal_banding->nominal_2_pro;
	}
	if (!empty($nominal_banding->nominal_3_pro)) {
		$nominal_3_pro = $nominal_banding->nominal_3_pro;
	}
	if (!empty($nominal_banding->nominal_4_pro)) {
		$nominal_4_pro = $nominal_banding->nominal_4_pro;
	}
	if (!empty($nominal_banding->nominal_5_pro)) {
		$nominal_5_pro = $nominal_banding->nominal_5_pro;
	}

	$sisa1 = $revisi_rpjmd->nominal_1 - $nominal_1_pro;
	$sisa2 = $revisi_rpjmd->nominal_2 - $nominal_2_pro;
	$sisa3 = $revisi_rpjmd->nominal_3 - $nominal_3_pro;
	$sisa4 = $revisi_rpjmd->nominal_4 - $nominal_4_pro;
	$sisa5 = $revisi_rpjmd->nominal_5 - $nominal_5_pro;
}
?>
<script type="text/javascript">
prepare_chosen();
$('input[name=nominal_1]').autoNumeric(numOptionsNotRound);
$('input[name=nominal_2]').autoNumeric(numOptionsNotRound);
$('input[name=nominal_3]').autoNumeric(numOptionsNotRound);
$('input[name=nominal_4]').autoNumeric(numOptionsNotRound);
$('input[name=nominal_5]').autoNumeric(numOptionsNotRound);

$(document).on("change", "#kd_kegiatan", function () {
	var str = $(this).find('option:selected').text();
	var nm_kegiatan = str.substring(str.indexOf(".")+2);
	$("#nama_prog_or_keg").val(nm_kegiatan);
});

$.validator.addMethod('maxNominal',
function(value, element, params) {
	try {
		value 		= parseFloat($(element).autoNumeric('get'));
		var nil1	= parseFloat(params);
	} catch(e) { alert(e) }

	return this.optional(element) || ( value >0 && value <= nil1);
}, "Mohon masukan nilai yang agar program memiliki nilai yang sama dengan batas yang disetujui (nominal RPJMD), nominal tersisa yang dapat digunakan yaitu {0}."
);

$('form#kegiatan').validate({
	rules: {
		kd_kegiatan : "required",
		indikator_kinerja : "required",
		nominal_1 : {
			required : true,
			<?php
			if (!empty($sisa1)) {
				?>

				maxNominal: function() {
					return Math.min(<?php echo ($sisa1>0)?"'". $sisa1 ."'":"'0'"; ?>)
				}
				<?php
			};
			?>
		},
		nominal_2 : {
			required : true,
			<?php
			if (!empty($sisa2)) {
				?>

				maxNominal: function() {
					return Math.min(<?php echo ($sisa2>0)?"'". $sisa2 ."'":"'0'"; ?>)
				}
				<?php
			};
			?>
		},
		nominal_3 : {
			required : true,
			<?php
			if (!empty($sisa3)) {
				?>

				maxNominal: function() {
					return Math.min(<?php echo ($sisa3>0)?"'". $sisa3 ."'":"'0'"; ?>)
				}
				<?php
			};
			?>
		},
		nominal_4 : {
			required : true,
			<?php
			if (!empty($sisa4)) {
				?>

				maxNominal: function() {
					return Math.min(<?php echo ($sisa4>0)?"'". $sisa4 ."'":"'0'"; ?>)
				}
				<?php
			};
			?>
		},
		nominal_5 : {
			required : true,
			<?php
			if (!empty($sisa5)) {
				?>

				maxNominal: function() {
					return Math.min(<?php echo ($sisa5>0)?"'". $sisa5 ."'":"'0'"; ?>)
				}
				<?php
			};
			?>
		},
		uraian_kegiatan_1 : {
			required : true
		},
		uraian_kegiatan_2 : {
			required : true
		},
		uraian_kegiatan_3 : {
			required : true
		},
		uraian_kegiatan_4 : {
			required : true
		},
		uraian_kegiatan_5 : {
			required : true
		}
	},
	ignore: ":hidden:not(select)"
});

$("#simpan").click(function(){
	$('#indikator_frame_kegiatan .indikator_val').each(function () {
		$(this).rules('add', {
			required: true
		});
	});

	$('#indikator_frame_kegiatan .target').each(function () {
		$(this).rules('add', {
			required:true,
			number:true
		});
	});

	var valid = $("form#kegiatan").valid();
	if (valid) {
		$('input[name=nominal_1]').val($('input[name=nominal_1]').autoNumeric('get'));
		$('input[name=nominal_2]').val($('input[name=nominal_2]').autoNumeric('get'));
		$('input[name=nominal_3]').val($('input[name=nominal_3]').autoNumeric('get'));
		$('input[name=nominal_4]').val($('input[name=nominal_4]').autoNumeric('get'));
		$('input[name=nominal_5]').val($('input[name=nominal_5]').autoNumeric('get'));

		element_program.parent().next().hide();
		$.blockUI({
			css: window._css,
			overlayCSS: window._ovcss
		});

		$.ajax({
			type: "POST",
			url: $("form#kegiatan").attr("action"),
			data: $("form#kegiatan").serialize(),
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
					element_program.trigger( "click" );
					reload_jendela_kontrol();
				};
			}
		});
	};
});

$("#tambah_indikator_kegiatan").click(function(){
	key = $("#indikator_frame_kegiatan").attr("key");
	key++;
	$("#indikator_frame_kegiatan").attr("key", key);

	var name = "indikator_kinerja["+ key +"]";
	var target_aw = "kondisi_awal["+ key +"]";
	var target_1 = "target_1["+ key +"]";
	var target_2 = "target_2["+ key +"]";
	var target_3 = "target_3["+ key +"]";
	var target_4 = "target_4["+ key +"]";
	var target_5 = "target_5["+ key +"]";
	var target_ah = "target_kondisi_akhir["+ key +"]";
	var satuan_target = "satuan_target["+ key +"]";
	var status_target = "status_target["+ key +"]";
	var kategori_target = "kategori_target["+ key +"]";
	var fungsiHitung = "hitungTarget("+ key +")";

	$("#indikator_box_kegiatan textarea").attr("name", name);
	$("#indikator_box_kegiatan input#target_aw").attr("name", target_aw);
	$("#indikator_box_kegiatan input#target_aw").attr("oninput", "hitungTarget("+ key +", 0)");
	$("#indikator_box_kegiatan input#target_1").attr("name", target_1);
	$("#indikator_box_kegiatan input#target_1").attr("oninput", "hitungTarget("+ key +", 1)");
	$("#indikator_box_kegiatan input#target_2").attr("name", target_2);
	$("#indikator_box_kegiatan input#target_2").attr("oninput", "hitungTarget("+ key +", 2)");
	$("#indikator_box_kegiatan input#target_3").attr("name", target_3);
	$("#indikator_box_kegiatan input#target_3").attr("oninput", "hitungTarget("+ key +", 3)");
	$("#indikator_box_kegiatan input#target_4").attr("name", target_4);
	$("#indikator_box_kegiatan input#target_4").attr("oninput", "hitungTarget("+ key +", 4)");
	$("#indikator_box_kegiatan input#target_5").attr("name", target_5);
	$("#indikator_box_kegiatan input#target_5").attr("oninput", "hitungTarget("+ key +", 5)");
	$("#indikator_box_kegiatan input#target_ah").attr("name", target_ah);
	$("#indikator_box_kegiatan input#target_ah").attr("oninput", "hitungTarget("+ key +", 5)");
	$("#indikator_box_kegiatan input#satuan_target").attr("name", satuan_target);
	$("#indikator_box_kegiatan select#status_target").attr("name", status_target);
	$("#indikator_box_kegiatan select#status_target").attr("onchange", "hitungTarget("+ key +", 5)");
	$("#indikator_box_kegiatan select#kategori_target").attr("name", kategori_target);
	$("#indikator_box_kegiatan select#kategori_target").attr("onchange", "hitungTarget("+ key +", 5)");
	$("#indikator_frame_kegiatan").append($("#indikator_box_kegiatan").html());
});

$(document).on("click", ".hapus_indikator_kegiatan", function(){
	$(this).parent().parent().remove();
});
</script>

<div style="width: 1120px">
	<header>
		<h3>
			<?php
			if (!empty($kegiatan)){
				echo "Edit Data Kegiatan";
			} else{
				echo "Input Data Kegiatan";
			}
			?>
		</h3>
	</header>
	<div class="module_content">
		<form action="<?php echo site_url('renstra/save_kegiatan');?>" method="POST" name="kegiatan" id="kegiatan" accept-charset="UTF-8" enctype="multipart/form-data" >
			<input type="hidden" name="id_kegiatan" value="<?php if(!empty($kegiatan->id)){echo $kegiatan->id;} ?>" />
			<input type="hidden" name="id_renstra" value="<?php echo $id_renstra; ?>" />
			<input type="hidden" name="id_sasaran" value="<?php echo $id_sasaran; ?>" />
			<input type="hidden" name="id_program" value="<?php echo $id_program; ?>" />

			<input type="hidden" name="kd_urusan" value="<?php echo $tujuan_sasaran_n_program->kd_urusan; ?>" />
			<input type="hidden" name="kd_bidang" value="<?php echo $tujuan_sasaran_n_program->kd_bidang; ?>" />
			<input type="hidden" name="kd_program" value="<?php echo $tujuan_sasaran_n_program->kd_program; ?>" />
			<input type="hidden" id="nama_prog_or_keg" name="nama_prog_or_keg" value="<?php echo (!empty($kegiatan->nama_prog_or_keg))?$kegiatan->nama_prog_or_keg:''; ?>" />
			<?php
			if (!empty($revisi_rpjmd)) {
				?>
				<div style="margin-bottom: 10px;">
					<table class="table-common" width="99%">
						<tbody>
							<tr>
								<th colspan="5">Revisi dari RPJMD</th>
							</tr>
							<tr>
								<td colspan="1">Kode</td>
								<td colspan="4"><?php echo $revisi_rpjmd->kd_urusan.".".$revisi_rpjmd->kd_bidang.".".$revisi_rpjmd->kd_program; ?></td>
							</tr>
							<tr>
								<td colspan="1">Nama Program</td>
								<td colspan="4"><?php echo $revisi_rpjmd->nama_prog_or_keg; ?></td>
							</tr>
							<tr>
								<th width="20%">Nominal 1</td>
									<th width="20%">Nominal 2</th>
									<th width="20%">Nominal 3</th>
									<th width="20%">Nominal 4</th>
									<th width="20%">Nominal 5</th>
								</tr>
								<tr>
									<td align="right"><?php echo Formatting::currency($revisi_rpjmd->nominal_1); ?></td>
									<td align="right"><?php echo Formatting::currency($revisi_rpjmd->nominal_2); ?></td>
									<td align="right"><?php echo Formatting::currency($revisi_rpjmd->nominal_3); ?></td>
									<td align="right"><?php echo Formatting::currency($revisi_rpjmd->nominal_4); ?></td>
									<td align="right"><?php echo Formatting::currency($revisi_rpjmd->nominal_5); ?></td>
								</tr>
								<tr>
									<th width="20%">Keterangan 1</td>
										<th width="20%">Keterangan 2</th>
										<th width="20%">Keterangan 3</th>
										<th width="20%">Keterangan 4</th>
										<th width="20%">Keterangan 5</th>
									</tr>
									<tr>
										<td><?php echo $revisi_rpjmd->ket_revisi_1; ?></td>
										<td><?php echo $revisi_rpjmd->ket_revisi_2; ?></td>
										<td><?php echo $revisi_rpjmd->ket_revisi_3; ?></td>
										<td><?php echo $revisi_rpjmd->ket_revisi_4; ?></td>
										<td><?php echo $revisi_rpjmd->ket_revisi_5; ?></td>
									</tr>
									<tr>
										<th width="20%">Sisa 1</td>
											<th width="20%">Sisa 2</th>
											<th width="20%">Sisa 3</th>
											<th width="20%">Sisa 4</th>
											<th width="20%">Sisa 5</th>
										</tr>
										<tr>
											<td align="right" <?php echo ($sisa1<0)?'style="color: red;"':'';?>><?php echo Formatting::currency($sisa1); ?></td>
											<td align="right" <?php echo ($sisa2<0)?'style="color: red;"':'';?>><?php echo Formatting::currency($sisa2); ?></td>
											<td align="right" <?php echo ($sisa3<0)?'style="color: red;"':'';?>><?php echo Formatting::currency($sisa3); ?></td>
											<td align="right" <?php echo ($sisa4<0)?'style="color: red;"':'';?>><?php echo Formatting::currency($sisa4); ?></td>
											<td align="right" <?php echo ($sisa5<0)?'style="color: red;"':'';?>><?php echo Formatting::currency($sisa5); ?></td>
										</tr>
									</tbody>
								</table>
								<i>*Jumlah nominal semua kegiatan dalam 1 program tidak boleh melebihi jumlah yang terdapat pada baris nominal tabel diatas.</i>
								<hr>
							</div>
							<?php
						}
						?>

						<table class="fcari" width="100%">
							<tbody>
								<tr>
									<td width="20%">SKPD</td>
									<td width="80%"><?php echo $skpd->nama_skpd; ?></td>
								</tr>
								<tr>
									<td>Tujuan</td>
									<td><?php echo $tujuan_sasaran_n_program->tujuan; ?></td>
								</tr>
								<tr>
									<td>Sasaran</td>
									<td><?php echo $tujuan_sasaran_n_program->sasaran; ?></td>
								</tr>
								<tr>
									<td>Kode & Nama Program</td>
									<td><?php echo $tujuan_sasaran_n_program->kd_urusan.". ".$tujuan_sasaran_n_program->kd_bidang.". ".$tujuan_sasaran_n_program->kd_program." - ".$tujuan_sasaran_n_program->nama_prog_or_keg; ?></td>
								</tr>
								<tr>
									<td>Kegiatan</td>
									<td>
										<?php echo $kd_kegiatan; ?>
									</td>
								</tr>
								<tr>
									<td>Indikator Kinerja <a id="tambah_indikator_kegiatan" class="icon-plus-sign" href="javascript:void(0)"></a></td>
									<td id="indikator_frame_kegiatan" key="<?php echo (!empty($indikator_kegiatan))?$indikator_kegiatan->num_rows():'1'; ?>">
										<?php
										if (!empty($indikator_kegiatan)) {
											$i=0;
											foreach ($indikator_kegiatan->result() as $row) {
												$i++;
												?>
												<input type="hidden" name="id_indikator_kegiatan[<?php echo $i; ?>]" value="<?php echo $row->id; ?>">
												<div style="width: 100%; margin-top: 10px;">
													<div style="width: 100%;">
														<textarea style="width:95%"  class="common indikator_val" name="indikator_kinerja[<?php echo $i; ?>]"><?php if(!empty($row->indikator)){echo $row->indikator;} ?></textarea>
														<?php
														if ($i != 1) {
															?>
															<a class="icon-remove hapus_indikator_kegiatan" href="javascript:void(0)" style="vertical-align: top;"></a>
															<?php
														}
														?>
													</div>
													<div style="width: 100%;">
														<table class="table-common" width="100%">
															<tr>
																<td colspan="2">Satuan</td>
																<!-- <td colspan="5"><?php echo form_dropdown('satuan_target['. $i .']', $satuan, $row->satuan_target, 'class="common indikator_val" id="satuan_target"'); ?></td> -->
																<td colspan="5"><input class="common indikator_val" name="satuan_target[<?php echo $i; ?>]" id="satuan_target" value="<?php echo $row->satuan_target; ?>"></td>
															</tr>
															<tr>
																<td colspan="2">Kategori Indikator</td>
																<td colspan="2"><?php echo form_dropdown('status_target['. $i .']', $status_indikator, $row->kode_positif_negatif, 'class="common indikator_val" id="status_target" onchange="hitungTarget('. $i .', 5)"'); ?></td>
																<td colspan="3"><?php echo form_dropdown('kategori_target['. $i .']', $kategori_indikator, $row->kode_kategori_indikator, 'class="common indikator_val" id="kategori_target" onchange="hitungTarget('. $i .', 5)"'); ?></td>
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
																<td width="14%"><input style="width: 100%;" type="text" class="target" name="target_kondisi_akhir[<?php echo $i; ?>]" value="<?php echo (!empty($row->target_kondisi_akhir))?$row->target_kondisi_akhir:''; ?>" readonly></td>
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
													<textarea style="width:95%" class="common indikator_val"  name="indikator_kinerja[1]"></textarea>
												</div>
												<div style="width: 100%;">
													<table class="table-common" width="100%">
														<tr>
															<td colspan="2">Satuan</td>
															<!-- <td colspan="5"><?php echo form_dropdown('satuan_target[1]', $satuan, '', 'class="common indikator_val" id="satuan_target"'); ?></td> -->
															<td colspan="5"><input class="common indikator_val" name="satuan_target[1]" id="satuan_target"></td>
														</tr>
														<tr>
															<td colspan="2">Kategori Indikator</td>
															<td colspan="2"><?php echo form_dropdown('status_target[1]', $status_indikator, '', 'class="common indikator_val" id="status_target" onchange="hitungTarget(1, 5)"'); ?></td>
															<td colspan="3"><?php echo form_dropdown('kategori_target[1]', $kategori_indikator, '', 'class="common indikator_val" id="kategori_target" onchange="hitungTarget(1, 5)"'); ?></td>
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
								<tr style="background-color: white;">
									<td></td>
									<td>
										<div class="submit_link">
										<input id="simpan" type="button" value="Simpan" style="background-color: #3c8dbc !important; border-color: #367fa9 !important; color: #fff !important;">
										</div>
									</td>
								</tr>
								<!-- // Tambahkan disini belanja kegiatan -->
								<?php
								//include_once "createBelanja.php";
								?>
								<tr>
									<td></td>
								</tr>

							</table>

							<div class="nav-tabs-custom">
								<ul class="nav nav-tabs">
									<li class="active"><a href="#tahun1" data-toggle="tab" <?php if(empty($kegiatan->id)){echo "style='display:none;'";} ?> >Tahun 1</a></li>
					              	<li><a href="#tahun2" data-toggle="tab" <?php if(empty($kegiatan->id)){echo "style='display:none;'";} ?>>Tahun 2</a></li>
					              	<li><a href="#tahun3" data-toggle="tab" <?php if(empty($kegiatan->id)){echo "style='display:none;'";} ?>>Tahun 3</a></li>
					              	<li><a href="#tahun4" data-toggle="tab" <?php if(empty($kegiatan->id)){echo "style='display:none;'";} ?>>Tahun 4</a></li>
					              	<li><a href="#tahun5" data-toggle="tab" <?php if(empty($kegiatan->id)){echo "style='display:none;'";} ?>>Tahun 5</a></li>
					              	<li <?php if(empty($kegiatan->id)){echo "class='active'";} ?>><a href="#Beranda" data-toggle="tab">Keterangan</a></li>
								</ul>
								<div class="tab-content">
									<!-- /.tab-pane -->
									<?php if(!empty($kegiatan->id)){ ?>
									<div class="active tab-pane" id="tahun1">
										<div class="tab-pane" id="tahun1">
											<?php include_once "createBelanja_tahun1.php";  ?>
										</div>
									</div>
									<div class="tab-pane" id="tahun2">
										<?php include_once "createBelanja_tahun2.php";  ?>
									</div>
									<div class="tab-pane" id="tahun3">
										<?php include_once "createBelanja_tahun3.php";  ?>
									</div>
									<div class="tab-pane" id="tahun4">
										<?php include_once "createBelanja_tahun4.php";  ?>
									</div>
									<div class="tab-pane" id="tahun5">
										<?php include_once "createBelanja_tahun5.php";  ?>
									</div>
									<?php }else{ ?>
										<input type="hidden" id="nominal_1" name="nominal_1" value="0.00"/>
										<input type="hidden" id="nominal_2" name="nominal_2" value="0.00"/>
										<input type="hidden" id="nominal_3" name="nominal_3" value="0.00"/>
										<input type="hidden" id="nominal_4" name="nominal_4" value="0.00"/>
										<input type="hidden" id="nominal_5" name="nominal_5" value="0.00"/>
									<?php } ?>

									<?php if(empty($kegiatan->id)){echo '<div class="active tab-pane" id="Beranda">';} ?>
									<div class="tab-pane" id="Beranda">
										<table>

											<tr>
												<td>Penanggung Jawab</td>
												<td><input class="common" name="penanggung_jawab" value="<?php echo (!empty($kegiatan->penanggung_jawab))?$kegiatan->penanggung_jawab:''; ?>"></td>
											</tr>
											<tr>
												<td>Lokasi</td>
												<td><input class="common" name="lokasin" value="<?php echo (!empty($kegiatan->lokasi))?$kegiatan->lokasi:''; ?>"></td>
											</tr>
										</tbody>

									</table>
									<div class="submit_link">
										<!-- <input id="simpan" type="button" value="Simpan"> -->
									</div>
								</div>
								<?php if(empty($kegiatan->id)){echo '</div>';} ?>		
								<!-- /Beranda -->
							</div>

						</tbody>
					</table>
				</div>  <!-- /Beranda -->
			</div>
			<!-- /.tab-pane -->
		</div>
		<!-- /.tab-content -->
	</div>
	<!-- /.nav-tabs-custom -->
</div>
</div>
</div>



</form>
</div>

</div>
<div style="display: none" id="indikator_box_kegiatan">
	<div style="width: 100%; margin-top: 15px;">
		<hr>
		<div style="width: 100%;">
			<textarea class="common indikator_val" name="indikator_kinerja[]" style="width:95%"></textarea>
			<a class="icon-remove hapus_indikator_kegiatan" href="javascript:void(0)" style="vertical-align: top;"></a>
		</div>
		<div style="width: 100%;">
			<table class="table-common" width="100%">
				<tr>
					<td colspan="2">Satuan</td>
					<!-- <td colspan="5"><?php echo form_dropdown('satuan_target[1]', $satuan, '', 'class="common indikator_val" id="satuan_target"'); ?></td> -->
					<td colspan="5"><input class="common indikator_val" name="satuan_target[1]" id="satuan_target"></td>
				</tr>
				<tr>
					<td colspan="2">Kategori Indikator</td>
					<td colspan="2"><?php echo form_dropdown('status_target[1]', $status_indikator, '', 'class="common indikator_val" id="status_target" onchange="hitungTarget(1, 5)"'); ?></td>
					<td colspan="3"><?php echo form_dropdown('kategori_target[1]', $kategori_indikator, '', 'class="common indikator_val" id="kategori_target" onchange="hitungTarget(1, 5)"'); ?></td>
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
	var kategori_target = $("select[name='kategori_target["+ index +"]']").val();
	var status_target = $("select[name='status_target["+ index +"]']").val();
	var target_aw = $("input[name='kondisi_awal["+ index +"]']").val();
	var target_1 = $("input[name='target_1["+ index +"]']").val();
	var target_2 = $("input[name='target_2["+ index +"]']").val();
	var target_3 = $("input[name='target_3["+ index +"]']").val();
	var target_4 = $("input[name='target_4["+ index +"]']").val();
	var target_5 = $("input[name='target_5["+ index +"]']").val();
	var id_indikator = $("input[name='id_indikator_kegiatan["+ index +"]']").val();

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
			// if (target_5===0) {
			// }elseif(target_4===0) {
			// 	$("input[name='target_kondisi_akhir["+ index +"]']").val(forakhir);
			// }
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

	function save_belanja_renstra(tahun, clue){

		var id_renstra = $('input[name="id_renstra"]').val();
		var id_kegiatan = $('input[name="id_kegiatan"]').val();
		var id_belanja = $('#id_belanja_'+tahun).val();

		var kd_urusan = $('input[name="kd_urusan"]').val();
		var kd_bidang = $('input[name="kd_bidang"]').val();
		var kd_program = $('input[name="kd_program"]').val();
		var kd_kegiatan = $('#kd_kegiatan').val();

		var lokasi = $('#lokasi_'+tahun).val();
		var uraian_kegiatan = $('#uraian_kegiatan_'+tahun).val();

		var jenis = $('#cb_jenis_belanja_'+tahun).val();
		var kategori = $('#cb_kategori_belanja_'+tahun).val();
		var subkategori = $('#cb_subkategori_belanja_'+tahun).val();
		var belanja = $('#cb_belanja_'+tahun).val();
		var uraian = $('#uraian_'+tahun).val();
		var sumberdana = $('#sumberdana_'+tahun).val();
		var deturaian = $('#det_uraian_'+tahun).val();
		var volume = parseFloat($('#volume_'+tahun).autoNumeric('get'));
		var satuan = $('#satuan_'+tahun).val();
		var nomsatuan = parseFloat($('#nominal_satuan_'+tahun).autoNumeric('get'));

		var subtotal = parseFloat(nomsatuan) * parseFloat(volume);

		var status = eliminationName(lokasi, uraian_kegiatan, jenis, kategori, subkategori, belanja, uraian, deturaian, volume, satuan, nomsatuan, sumberdana, clue, '#cusAlert_'+tahun, 'pesan_'+tahun);

		if (status) {
			$.ajax({
			    type: "POST",
			    url: '<?php echo site_url("renstra/belanja_kegiatan_save"); ?>',
			    dataType: 'html',
			    data: {
			    id_renstra : id_renstra,
			    tahun : tahun,
			    id_belanja : id_belanja,
			    kode_urusan : kd_urusan,
			    kode_bidang : kd_bidang,
			    kode_program : kd_program,
			    kode_kegiatan : kd_kegiatan,
			    id_kegiatan : id_kegiatan,
			    kode_jenis_belanja : jenis, 
			    kode_kategori_belanja : kategori,
			    kode_sub_kategori_belanja : subkategori,
			    kode_belanja : belanja,
			    uraian_belanja : uraian,
			    kode_sumber_dana : sumberdana,
			    detil_uraian_belanja : deturaian,
			    volume : volume,
			    satuan : satuan,
			    nominal_satuan : nomsatuan,
			    subtotal : subtotal
			    },
			    success: function(msg){
			    	//console.log(msg);
			    	$('#list_tahun_'+tahun).html(msg);
			    	$('#id_belanja_'+tahun).val('');

			    	clear_belanja(clue, tahun)
			    }
			});
		}

		
	}

	function clear_belanja(clue, tahun){
		if (clue=='all') {
	      	
	    }
	    else if (clue=='jns') {
			document.getElementById("cb_jenis_belanja_"+tahun).value = '';
			$("#cb_jenis_belanja_"+tahun).trigger("chosen:updated");
			document.getElementById("cb_kategori_belanja_"+tahun).value = '';
			$("#cb_kategori_belanja_"+tahun).trigger("chosen:updated");
			document.getElementById("cb_subkategori_belanja_"+tahun).value = '';
			$("#cb_subkategori_belanja_"+tahun).trigger("chosen:updated");
			document.getElementById("cb_belanja_"+tahun).value = '';
			$("#cb_belanja_"+tahun).trigger("chosen:updated");
			document.getElementById("uraian_"+tahun).value = '';
			document.getElementById("det_uraian_"+tahun).value = '';
			document.getElementById("volume_"+tahun).value = '';
			document.getElementById("satuan_"+tahun).value = '';
			document.getElementById("nominal_satuan_"+tahun).value='';
	    }
	    else if (clue=='kat') {
	      document.getElementById("cb_kategori_belanja_"+tahun).value = '';
	      $("#cb_kategori_belanja_"+tahun).trigger("chosen:updated");
	      document.getElementById("cb_subkategori_belanja_"+tahun).value = '';
	      $("#cb_subkategori_belanja_"+tahun).trigger("chosen:updated");
	      document.getElementById("cb_belanja_"+tahun).value = '';
	      $("#cb_belanja_"+tahun).trigger("chosen:updated");
	      document.getElementById("uraian_"+tahun).value = '';
	      document.getElementById("det_uraian_"+tahun).value = '';
	      document.getElementById("volume_"+tahun).value = '';
	      document.getElementById("satuan_"+tahun).value = '';
	      document.getElementById("nominal_satuan_"+tahun).value='';
	    }else if (clue=='subkat') {
	      document.getElementById("cb_subkategori_belanja_"+tahun).value = '';
	      $("#cb_subkategori_belanja_"+tahun).trigger("chosen:updated");
	      document.getElementById("cb_belanja_"+tahun).value = '';
	      $("#cb_belanja_"+tahun).trigger("chosen:updated");
	      document.getElementById("uraian_"+tahun).value = '';
	      document.getElementById("det_uraian_"+tahun).value = '';
	      document.getElementById("volume_"+tahun).value = '';
	      document.getElementById("satuan_"+tahun).value = '';
	      document.getElementById("nominal_satuan_"+tahun).value='';
	    }else if (clue=='belanja') {
	      document.getElementById("cb_belanja_"+tahun).value = '';
	      $("#cb_belanja_"+tahun).trigger("chosen:updated");
	      document.getElementById("uraian_"+tahun).value = '';
	      document.getElementById("det_uraian_"+tahun).value = '';
	      document.getElementById("volume_"+tahun).value = '';
	      document.getElementById("satuan_"+tahun).value = '';
	      document.getElementById("nominal_satuan_"+tahun).value='';
	    }else if (clue=='uraian') {
	      document.getElementById("uraian_"+tahun).value = '';
	      document.getElementById("det_uraian_"+tahun).value = '';
	      document.getElementById("volume_"+tahun).value = '';
	      document.getElementById("satuan_"+tahun).value = '';
	      document.getElementById("nominal_satuan_"+tahun).value='';
	    }else if (clue=='deturaian') {
	      document.getElementById("det_uraian_"+tahun).value = '';
	      document.getElementById("volume_"+tahun).value = '';
	      document.getElementById("satuan_"+tahun).value = '';
	      document.getElementById("nominal_satuan_"+tahun).value='';
	    }
	}

	function copyrowng(tahun) {
		var id_kegiatan = $('input[name="id_kegiatan"]').val();

		$.ajax({
		    type: "POST",
		    url: '<?php echo site_url("renstra/cek_belanja"); ?>',
		    dataType: 'json',
		    data: {
		    tahun : tahun,
		    id_kegiatan : id_kegiatan
		    },
		    success: function(msg){
		    	//console.log(msg);
		    	// $('#list_tahun_'+tahun).html(msg);

		    	if (msg.cid == 0) {
		    		$.ajax({
					    type: "POST",
					    url: '<?php echo site_url("renstra/belanja_copy"); ?>',
					    dataType: 'html',
					    data: {
					    tahun : tahun,
					    id_kegiatan : id_kegiatan
					    },
					    success: function(respon){
					    	//console.log(msg);
					    	$('#list_tahun_'+tahun).html(respon);
					    }
					});
		    	}else{
		    		alert("Data belanja gagal di ambil, belanja pada Tahun "+tahun+" harus dalam kondisi kosong.");
		    	}
		    }
		});
	}

	function float_to_num(value) {
		var result
		value = value.toString();
		value = value.replace(/\./g, ',');
        result = value.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
		return result;
	}
</script>
