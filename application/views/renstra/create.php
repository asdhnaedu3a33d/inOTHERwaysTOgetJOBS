<style type="text/css">
	.misi{
		margin-top: 2px;
		margin-bottom: 2px;
	}

	.tujuan{
		margin-top: 2px;
		margin-bottom: 2px;
	}

	.table-common textarea.common{
		border: none;
		width: 100%;
			-webkit-box-sizing: border-box;
			-moz-box-sizing: border-box;
			box-sizing: border-box;
	}

	.hapus_misi{
		vertical-align: middle;
		text-align: center;
	}
</style>
<script type="text/javascript">
	$(document).ready(function(){
		$('form#renstra').validate({rules: {
			  visi : "required"
			}
		});

		$("#simpan").click(function(){
			$('#misi_frame .misi_val').each(function () {
			    $(this).rules('add', {
			        required: true
			    });
			});

			$('#misi_frame .tujuan_val').each(function () {
			    $(this).rules('add', {
			        required: true
			    });
			});

			$('#misi_frame .indikator_val').each(function () {
			    $(this).rules('add', {
			        required: true
			    });
			});

			$('#misi_frame .satuan_target_val').each(function () {
			    $(this).rules('add', {
			        required: true
			    });
			});

			$('#misi_frame .status_target_val').each(function () {
			    $(this).rules('add', {
			        required: true
			    });
			});

			$('#misi_frame .kategori_target_val').each(function () {
			    $(this).rules('add', {
			        required: true
			    });
			});

			$('#misi_frame .kondisi_awal_val').each(function () {
			    $(this).rules('add', {
			        required: true
			    });
			});

			$('#misi_frame .target_1_val').each(function () {
			    $(this).rules('add', {
			        required: true
			    });
			});

			$('#misi_frame .target_2_val').each(function () {
			    $(this).rules('add', {
			        required: true
			    });
			});

			$('#misi_frame .target_3_val').each(function () {
			    $(this).rules('add', {
			        required: true
			    });
			});

			$('#misi_frame .target_4_val').each(function () {
			    $(this).rules('add', {
			        required: true
			    });
			});

			$('#misi_frame .target_5_val').each(function () {
			    $(this).rules('add', {
			        required: true
			    });
			});

			$('#misi_frame .kondisi_akhir_val').each(function () {
			    $(this).rules('add', {
			        required: true
			    });
			});

		    var valid = $("form#renstra").valid();
		    if (valid) {
		    	$.blockUI({
					css: window._css,
					overlayCSS: window._ovcss
				});
		    	$("form#renstra").submit();
		    };
		});

		$(document).on("click", "#tambah_misi", function(){
			key = $("#misi_frame").attr("key");
			key++;
			$("#misi_frame").attr("key", key);

			var name = "misi["+ key +"]";
			$("#misi_box textarea.misi_val").attr("name", name);
			$("#misi_box a#tambah_tujuan").attr("id-m", key);
			var name = "tujuan["+ key +"][1]";
			$("#misi_box textarea.tujuan_val").attr("name", name);
			var fungsiHitung = "hitungTarget("+ key +",1,1)";

			$("#misi_box a#tambah_indikator").attr("id-m", key);
			$("#misi_box a#tambah_indikator").attr("id-t", "1");
			var name = "indikator["+ key +"][1][1]";
			$("#misi_box textarea.indikator_val").attr("name", name);

			var name = "satuan_target["+ key +"][1][1]";
			$("#misi_box select.satuan_target_val").attr("name", name);
			var name = "status_target["+ key +"][1][1]";
			$("#misi_box select.status_target_val").attr("name", name);
			$("#misi_box select.status_target_val").attr("onchange", fungsiHitung);
			var name = "kategori_target["+ key +"][1][1]";
			$("#misi_box select.kategori_target_val").attr("name", name);
			$("#misi_box select.kategori_target_val").attr("onchange", fungsiHitung);

			var name = "kondisi_awal["+ key +"][1][1]";
			$("#misi_box input.kondisi_awal_val").attr("name", name);
			$("#misi_box input.kondisi_awal_val").attr("oninput", fungsiHitung);
			var name = "target_1["+ key +"][1][1]";
			$("#misi_box input.target_1_val").attr("name", name);
			$("#misi_box input.target_1_val").attr("oninput", fungsiHitung);
			var name = "target_2["+ key +"][1][1]";
			$("#misi_box input.target_2_val").attr("name", name);
			$("#misi_box input.target_2_val").attr("oninput", fungsiHitung);
			var name = "target_3["+ key +"][1][1]";
			$("#misi_box input.target_3_val").attr("name", name);
			$("#misi_box input.target_3_val").attr("oninput", fungsiHitung);
			var name = "target_4["+ key +"][1][1]";
			$("#misi_box input.target_4_val").attr("name", name);
			$("#misi_box input.target_4_val").attr("oninput", fungsiHitung);
			var name = "target_5["+ key +"][1][1]";
			$("#misi_box input.target_5_val").attr("name", name);
			$("#misi_box input.target_5_val").attr("oninput", fungsiHitung);
			var name = "kondisi_akhir["+ key +"][1][1]";
			$("#misi_box input.kondisi_akhir_val").attr("name", name);

			$("#misi_frame").append($("#misi_box").html());
		});


		$(document).on("click", ".hapus_misi", function(){
			$(this).parent().parent().next(".tujuan_frame").remove();
			$(this).parent().parent().remove();
		});

		$(document).on("click", "#tambah_tujuan", function(){
			var id_misi = $(this).attr("id-m");
			var tbody = $(this).parent().parent().parent().next();
			key = tbody.attr("key");
			key++;
			tbody.attr("key", key);
			var fungsiHitung = "hitungTarget("+ id_misi +","+ key +",1)";

			var name = "tujuan["+ id_misi +"]["+ key +"]";
			$("#tujuan_box textarea.tujuan_val").attr("name", name);

			$("#tujuan_box a#tambah_indikator").attr("id-m", id_misi);
			$("#tujuan_box a#tambah_indikator").attr("id-t", key);
			var name = "indikator["+ id_misi +"]["+ key +"][1]";
			$("#tujuan_box textarea.indikator_val").attr("name", name);
			var name = "satuan_target["+ id_misi +"]["+ key +"][1]";
			$("#tujuan_box select.satuan_target_val").attr("name", name);
			var name = "status_target["+ id_misi +"]["+ key +"][1]";
			$("#tujuan_box select.status_target_val").attr("name", name);
			$("#tujuan_box select.status_target_val").attr("onchange", fungsiHitung);
			var name = "kategori_target["+ id_misi +"]["+ key +"][1]";
			$("#tujuan_box select.kategori_target_val").attr("name", name);
			$("#tujuan_box select.kategori_target_val").attr("onchange", fungsiHitung);
			var name = "kondisi_awal["+ id_misi +"]["+ key +"][1]";
			$("#tujuan_box input.kondisi_awal_val").attr("name", name);
			$("#tujuan_box input.kondisi_awal_val").attr("oninput", fungsiHitung);
			var name = "target_1["+ id_misi +"]["+ key +"][1]";
			$("#tujuan_box input.target_1_val").attr("name", name);
			$("#tujuan_box input.target_1_val").attr("oninput", fungsiHitung);
			var name = "target_2["+ id_misi +"]["+ key +"][1]";
			$("#tujuan_box input.target_2_val").attr("name", name);
			$("#tujuan_box input.target_2_val").attr("oninput", fungsiHitung);
			var name = "target_3["+ id_misi +"]["+ key +"][1]";
			$("#tujuan_box input.target_3_val").attr("name", name);
			$("#tujuan_box input.target_3_val").attr("oninput", fungsiHitung);
			var name = "target_4["+ id_misi +"]["+ key +"][1]";
			$("#tujuan_box input.target_4_val").attr("name", name);
			$("#tujuan_box input.target_4_val").attr("oninput", fungsiHitung);
			var name = "target_5["+ id_misi +"]["+ key +"][1]";
			$("#tujuan_box input.target_5_val").attr("name", name);
			$("#tujuan_box input.target_5_val").attr("oninput", fungsiHitung);
			var name = "kondisi_akhir["+ id_misi +"]["+ key +"][1]";
			$("#tujuan_box input.kondisi_akhir_val").attr("name", name);
			tbody.append($("#tujuan_box").html());
		});

		$(document).on("click", ".hapus_tujuan", function(){
			$(this).parent().parent().next(".indikator_frame").remove();
			$(this).parent().parent().remove();
		});

		$(document).on("click", "#tambah_indikator", function(){
			var id_misi = $(this).attr("id-m");
			var id_tujuan = $(this).attr("id-t");
			var tbody = $(this).parent().parent().parent().next();
			key = tbody.attr("key");
			key++;
			tbody.attr("key", key);
			var fungsiHitung = "hitungTarget("+ id_misi +","+ id_tujuan +","+ key +")";

			var name = "indikator["+ id_misi +"]["+ id_tujuan +"]["+ key +"]";
			$("#indikator_box textarea").attr("name", name);

			var name = "satuan_target["+ id_misi +"]["+ id_tujuan +"]["+ key +"]";
			$("#indikator_box select.satuan_target_val").attr("name", name);
			var name = "status_target["+ id_misi +"]["+ id_tujuan +"]["+ key +"]";
			$("#indikator_box select.status_target_val").attr("name", name);
			$("#indikator_box select.status_target_val").attr("onchange", fungsiHitung);
			var name = "kategori_target["+ id_misi +"]["+ id_tujuan +"]["+ key +"]";
			$("#indikator_box select.kategori_target_val").attr("name", name);
			$("#indikator_box select.kategori_target_val").attr("onchange", fungsiHitung);

			var name = "kondisi_awal["+ id_misi +"]["+ id_tujuan +"]["+ key +"]";
			$("#indikator_box input.kondisi_awal_val").attr("name", name);
			$("#indikator_box input.kondisi_awal_val").attr("oninput", fungsiHitung);
			var name = "target_1["+ id_misi +"]["+ id_tujuan +"]["+ key +"]";
			$("#indikator_box input.target_1_val").attr("name", name);
			$("#indikator_box input.target_1_val").attr("oninput", fungsiHitung);
			var name = "target_2["+ id_misi +"]["+ id_tujuan +"]["+ key +"]";
			$("#indikator_box input.target_2_val").attr("name", name);
			$("#indikator_box input.target_2_val").attr("oninput", fungsiHitung);
			var name = "target_3["+ id_misi +"]["+ id_tujuan +"]["+ key +"]";
			$("#indikator_box input.target_3_val").attr("name", name);
			$("#indikator_box input.target_3_val").attr("oninput", fungsiHitung);
			var name = "target_4["+ id_misi +"]["+ id_tujuan +"]["+ key +"]";
			$("#indikator_box input.target_4_val").attr("name", name);
			$("#indikator_box input.target_4_val").attr("oninput", fungsiHitung);
			var name = "target_5["+ id_misi +"]["+ id_tujuan +"]["+ key +"]";
			$("#indikator_box input.target_5_val").attr("name", name);
			$("#indikator_box input.target_5_val").attr("oninput", fungsiHitung);
			var name = "kondisi_akhir["+ id_misi +"]["+ id_tujuan +"]["+ key +"]";
			$("#indikator_box input.kondisi_akhir_val").attr("name", name);
			tbody.append($("#indikator_box").html());
		});

		$(document).on("click", ".hapus_indikator", function(){
			$(this).parent().parent().next(".target_frame").remove();
			$(this).parent().parent().remove();
		});
	});
</script>
<article class="module width_full">
 	<header>
 		<h3>
		<?php
			if (!empty($renstra)){
			    echo "Edit Data Renstra";
			} else{
			    echo "Input Data Renstra";
			}
		?>
		</h3>
 	</header>
 	<div class="module_content">
 		<form action="<?php echo site_url('renstra/save_skpd');?>" method="POST" name="renstra" id="renstra" accept-charset="UTF-8" enctype="multipart/form-data" >
 			<input type="hidden" name="id_renstra" value="<?php if(!empty($renstra->id)){echo $renstra->id;} ?>" />
 			<table class="fcari" width="100%">
 				<tbody>
 					<tr>
 						<td width="20%">SKPD</td>
 						<td width="80%"><?php echo $skpd->nama_skpd; ?></td>
					</tr>
					<tr class="bg-warning table-visi">
						<td>Visi</td>
						<td>
							<textarea class="common" name="visi"><?php if(!empty($renstra->visi)){echo $renstra->visi;} ?></textarea>
						</td>
					</tr>
 				</tbody>
 			</table>
			<?php
				if(!empty($renstra)){
			?>
 			<table class="table-common" width="99%">
 				<thead>
 					<tr>
 						<th>Misi <a id="tambah_misi" class="icon-plus-sign" href="javascript:void(0)"></a></th>
 						<th width="50px">Action</th>
 					</tr>
 				</thead>
 				<tbody id="misi_frame" key="<?php echo (!empty($renstra_misi))?$renstra_misi->num_rows():'1'; ?>">
				<?php
					if (!empty($renstra_misi)) {
						$i=0;
						foreach ($renstra_misi->result() as $row) {
							$i++;
				?>
					<input type="hidden" name="id_misi[<?php echo $i; ?>]" value="<?php echo $row->id; ?>">
					<?php
					if ($i > 1) {
						echo "<tr class='bg-info table-misi'>";
					}else {
						echo "<tr class='table-misi'>";
					}
					?>
						<td><textarea class="common misi_val" name="misi[<?php echo $i; ?>]"><?php echo $row->misi; ?></textarea></td>
						<td align="center">
						<?php
		 							if ($i > 1) {
		 				?>
		 							<a class="icon-remove hapus_misi" href="javascript:void(0)"></a>
		 				<?php
									}
		 				?>
						</td>
					</tr>
					<?php
						$renstra_tujuan = $this->m_renstra_trx->get_each_renstra_tujuan($row->id_renstra, $row->id);
					?>
					<tr class="tujuan_frame">
						<td colspan="2">
							<table class="table-common" width="100%">
								<thead>
									<tr>
										<th>Tujuan <a id-m="<?php echo $i; ?>" id="tambah_tujuan" class="icon-plus-sign" href="javascript:void(0)"></a></th>
										<th width="50px">Action</th>
									</tr>
								</thead>
								<tbody key="<?php echo (!empty($renstra_tujuan))?$renstra_tujuan->num_rows():'1'; ?>">
								<?php
									if (!empty($renstra_tujuan)) {
										$j=0;
										foreach ($renstra_tujuan->result() as $row1) {
											$j++;
								?>
									<input type="hidden" name="id_tujuan[<?php echo $i; ?>][<?php echo $j; ?>]" value="<?php echo $row1->id; ?>">
									<?php
									if ($j > 1) {
										echo "<tr class='bg-info table-tujuan'>";
									}else {
										echo "<tr class='table-tujuan'>";
									}
									?>
										<td><textarea class="common tujuan_val" name="tujuan[<?php echo $i; ?>][<?php echo $j; ?>]"><?php echo $row1->tujuan; ?></textarea></td>
										<td align="center">
										<?php
										if ($j > 1) {
										?>
													<a class="icon-remove hapus_tujuan" href="javascript:void(0)"></a>
										<?php
											}
										?>
										</td>
									</tr>
									<?php
					 					$tujuan_indikator = $this->m_renstra_trx->get_each_renstra_indikator_tujuan($row1->id);
					 				?>
									<tr class="indikator_frame">
										<td colspan="2">
											<table class="table-common">
												<thead>
													<tr>
														<th colspan="3">Indikator Kinerja <a id-m="<?php echo $i; ?>" id-t="<?php echo $j; ?>"  id="tambah_indikator" class="icon-plus-sign" href="javascript:void(0)"></a></td>
														<th width="50px">Action</th>
													</tr>
												</thead>
												<tbody key="<?php echo (!empty($tujuan_indikator))?$tujuan_indikator->num_rows():'1'; ?>">
												<?php
													if (!empty($tujuan_indikator)) {
														$k=0;
														foreach ($tujuan_indikator->result() as $row2) {
															$k++;
												?>
												<input type="hidden" name="id_indikator_tujuan[<?php echo $i; ?>][<?php echo $j; ?>][<?php echo $k; ?>]" value="<?php echo $row2->id; ?>">
													<?php
													if ($k > 1) {
														echo "<tr class='bg-info table-indikator'>";
													}else {
														echo "<tr class='table-indikator'>";
													}
													?>
														<td colspan="3"><textarea class="common indikator_val" name="indikator[<?php echo $i; ?>][<?php echo $j; ?>][<?php echo $k; ?>]"><?php echo $row2->indikator; ?></textarea></td>
														<td align="center">
															<?php
																		if ($k > 1) {
															?>
																		<a class="icon-remove hapus_indikator" href="javascript:void(0)"></a>
															<?php
																		}
															?>
														</td>
													</tr>
													<tr class="target_frame">
														<td colspan="4">
															<table class="table-common" width="100%">
																<tr>
																	<td rowspan="3" width="15px"></td>
																	<td width="300px">Satuan</td>
																	<td colspan="2"><?php echo form_dropdown('satuan_target['.  $i .']['. $j .']['. $k .']', $satuan, $row2->satuan_target, 'class="common satuan_target_val" id="satuan_target"'); ?></td>
																</tr>
																<tr>
																	<td rowspan="2">Kategori Indikator</td>
																	<td colspan="2"><?php echo form_dropdown('status_target['.  $i .']['. $j .']['. $k .']', $status_indikator, $row2->status_indikator, 'class="common status_target_val" id="status_target" onchange="hitungTarget('. $i .','. $j .','. $k .')"'); ?></td>
																</tr>
																<tr>
																	<td  colspan="2"><?php echo form_dropdown('kategori_target['.  $i .']['. $j .']['. $k .']', $kategori_indikator, $row2->kategori_indikator, 'class="common kategori_target_val" id="kategori_target" onchange="hitungTarget('. $i .','. $j .','. $k .')"'); ?></td>
																</tr>
																<tr>
																	<td colspan="10">
																		<table class="table-common">
																			<tr>
																				<td>Kondisi Awal</td>
																				<td>Target 1</td>
																				<td>Target 2</td>
																				<td>Target 3</td>
																				<td>Target 4</td>
																				<td>Target 5</td>
																				<td>Kondisi Akhir</td>
																			</tr>
																			<tr>
																				<td><input class="common kondisi_awal_val" name="kondisi_awal[<?php echo $i; ?>][<?php echo $j; ?>][<?php echo $k; ?>]" oninput="hitungTarget(<?php echo "$i, $j, $k"; ?>)" value="<?php echo $row2->kondisi_awal; ?>"></td>
																				<td><input class="common target_1_val" name="target_1[<?php echo $i; ?>][<?php echo $j; ?>][<?php echo $k; ?>]" oninput="hitungTarget(<?php echo "$i, $j, $k"; ?>)" value="<?php echo $row2->target_1; ?>"></td>
																				<td><input class="common target_2_val" name="target_2[<?php echo $i; ?>][<?php echo $j; ?>][<?php echo $k; ?>]" oninput="hitungTarget(<?php echo "$i, $j, $k"; ?>)" value="<?php echo $row2->target_2; ?>"></td>
																				<td><input class="common target_3_val" name="target_3[<?php echo $i; ?>][<?php echo $j; ?>][<?php echo $k; ?>]" oninput="hitungTarget(<?php echo "$i, $j, $k"; ?>)" value="<?php echo $row2->target_3; ?>"></td>
																				<td><input class="common target_4_val" name="target_4[<?php echo $i; ?>][<?php echo $j; ?>][<?php echo $k; ?>]" oninput="hitungTarget(<?php echo "$i, $j, $k"; ?>)" value="<?php echo $row2->target_4; ?>"></td>
																				<td><input class="common target_5_val" name="target_5[<?php echo $i; ?>][<?php echo $j; ?>][<?php echo $k; ?>]" oninput="hitungTarget(<?php echo "$i, $j, $k"; ?>)" value="<?php echo $row2->target_5; ?>"></td>
																				<td><input class="common kondisi_akhir_val" name="kondisi_akhir[<?php echo $i; ?>][<?php echo $j; ?>][<?php echo $k; ?>]" value="<?php echo $row2->kondisi_akhir; ?>" readonly></td>
																			</tr>
																		</table>
																	</td>
																</tr>
															</table>
														</td>
													</tr>
													<?php
														}}
													?>
												</tbody>
											</table>
										</td>
									</tr>
								</tbody>
								<?php
									}}
								?>
							</table>
						</td>
					</tr>
					<?php
						}}
					 ?>
 				</tbody>
 			</table>
			<?php
				}else {
			?>
 			<table class="table-common" width="99%">
 				<thead>
 					<tr>
 						<th>Misi <a id="tambah_misi" class="icon-plus-sign" href="javascript:void(0)"></a></th>
 						<th width="50px">Action</th>
 					</tr>
 				</thead>
 				<tbody id="misi_frame" key="1">
 					<tr class="table-misi">
						<td><textarea class="common misi_val" name="misi[1]"></textarea></td>
						<td align="center"></td>
					</tr>
					<tr class="tujuan_frame">
						<td colspan="2">
							<table class="table-common" width="100%">
								<thead>
									<tr>
										<th>Tujuan <a id-m="1" id="tambah_tujuan" class="icon-plus-sign" href="javascript:void(0)"></a></th>
										<th width="50px">Action</th>
									</tr>
								</thead>
								<tbody key="1">
									<tr class="table-tujuan">
										<td><textarea class="common tujuan_val" name="tujuan[1][1]"></textarea></td>
										<td align="center"></td>
									</tr>
									<tr class="indikator_frame">
										<td colspan="2">
											<table class="table-common">
												<thead>
													<tr>
														<th colspan="3">Indikator Kinerja <a id-m="1" id-t="1" id="tambah_indikator" class="icon-plus-sign" href="javascript:void(0)"></a></td>
														<th width="50px">Action</th>
													</tr>
												</thead>
												<tbody key="1">
													<tr>
														<td colspan="3"><textarea class="common indikator_val" name="indikator[1][1][1]"></textarea></td>
														<td align="center"></td>
													</tr>
													<tr class="target_frame">
														<td colspan="4">
															<table class="table-common" width="100%">
																<tr>
																	<td rowspan="3" width="15px"></td>
																	<td width="300px">Satuan</td>
																	<td colspan="2"><?php echo form_dropdown('satuan_target[1][1][1]', $satuan, '', 'class="common satuan_target_val" id="satuan_target"'); ?></td>
																</tr>
																<tr>
																	<td rowspan="2">Kategori Indikator</td>
																	<td colspan="2"><?php echo form_dropdown('status_target[1][1][1]', $status_indikator, '', 'class="common status_target_val" id="status_target" onchange="hitungTarget(1,1,1)"'); ?></td>
																</tr>
																<tr>
																	<td  colspan="2"><?php echo form_dropdown('kategori_target[1][1][1]', $kategori_indikator, '', 'class="common kategori_target_val" id="kategori_target" onchange="hitungTarget(1,1,1)"'); ?></td>
																</tr>
																<tr>
																	<td colspan="10">
																		<table class="table-common">
																			<tr>
																				<td>Kondisi Awal</td>
																				<td>Target 1</td>
																				<td>Target 2</td>
																				<td>Target 3</td>
																				<td>Target 4</td>
																				<td>Target 5</td>
																				<td>Kondisi Akhir</td>
																			</tr>
																			<tr>
																				<td><input class="common kondisi_awal_val" name="kondisi_awal[1][1][1]" oninput="hitungTarget(1,1,1)"></td>
																				<td><input class="common target_1_val" name="target_1[1][1][1]" oninput="hitungTarget(1,1,1)"></td>
																				<td><input class="common target_2_val" name="target_2[1][1][1]" oninput="hitungTarget(1,1,1)"></td>
																				<td><input class="common target_3_val" name="target_3[1][1][1]" oninput="hitungTarget(1,1,1)"></td>
																				<td><input class="common target_4_val" name="target_4[1][1][1]" oninput="hitungTarget(1,1,1)"></td>
																				<td><input class="common target_5_val" name="target_5[1][1][1]" oninput="hitungTarget(1,1,1)"></td>
																				<td><input class="common kondisi_akhir_val" name="kondisi_akhir[1][1][1]"  readonly></td>
																			</tr>
																		</table>
																	</td>
																</tr>
															</table>
														</td>
													</tr>
												</tbody>
											</table>
										</td>
									</tr>
								</tbody>
							</table>
						</td>
					</tr>
 				</tbody>
 			</table>
			<?php
				}
			 ?>
 		</form>
 	</div>
 	<footer>
		<div class="submit_link">
  			<input type='button' id="simpan" name="simpan" value='Simpan' />
  			<input type="button" value="Back" onclick="history.go(-1)" />
		</div>
	</footer>
</article>

<div style="display: none">
	<table>
		<tbody id="misi_box">
			<tr class="bg-info table-misi">
				<td><textarea class="common misi_val" name="misi[1]"></textarea></td>
				<td align="center"><a class="icon-remove hapus_misi" href="javascript:void(0)"></a></td>
			</tr>
			<tr class="tujuan_frame">
				<td colspan="2">
					<table class="table-common" width="100%">
						<thead>
							<tr>
								<th>Tujuan <a id-m="1" id="tambah_tujuan" class="icon-plus-sign" href="javascript:void(0)"></a></th>
								<th width="50px">Action</th>
							</tr>
						</thead>
						<tbody key="1">
							<tr>
								<td><textarea class="common tujuan_val" name="tujuan[1][1]"></textarea></td>
								<td align="center"></td>
							</tr>
							<tr class="indikator_frame">
								<td colspan="2">
									<table class="table-common">
										<thead>
											<tr>
												<th colspan="3">Indikator Kinerja <a id-m="1" id-t="1" id="tambah_indikator" class="icon-plus-sign" href="javascript:void(0)"></a></td>
												<th width="50px">Action</th>
											</tr>
										</thead>
										<tbody key="1">
											<tr>
												<td colspan="3"><textarea class="common indikator_val" name="indikator[1][1][1]"></textarea></td>
												<td align="center"></td>
											</tr>
											<tr class="target_frame">
												<td colspan="4">
													<table>
														<tr>
															<td rowspan="3" width="15px"></td>
															<td width="300px">Satuan</td>
															<td colspan="2"><?php echo form_dropdown('satuan_target[1][1][1]', $satuan, '', 'class="common satuan_target_val" id="satuan_target"'); ?></td>
														</tr>
														<tr>
															<td rowspan="2">Kategori Indikator</td>
															<td colspan="2"><?php echo form_dropdown('status_target[1][1][1]', $status_indikator, '', 'class="common status_target_val" id="status_target" onchange="hitungTarget(1,1,1)"'); ?></td>
														</tr>
														<tr>
															<td  colspan="2"><?php echo form_dropdown('kategori_target[1][1][1]', $kategori_indikator, '', 'class="common kategori_target_val" id="kategori_target" onchange="hitungTarget(1,1,1)"'); ?></td>
														</tr>
														<tr>
															<td colspan="10">
																<table class="table-common">
																	<tr>
																		<td>Kondisi Awal</td>
																		<td>Target 1</td>
																		<td>Target 2</td>
																		<td>Target 3</td>
																		<td>Target 4</td>
																		<td>Target 5</td>
																		<td>Kondisi Akhir</td>
																	</tr>
																	<tr>
																		<td><input class="common kondisi_awal_val" name="kondisi_awal[1][1][1]" oninput="hitungTarget(1,1,1)"></td>
																		<td><input class="common target_1_val" name="target_1[1][1][1]" oninput="hitungTarget(1,1,1)"></td>
																		<td><input class="common target_2_val" name="target_2[1][1][1]" oninput="hitungTarget(1,1,1)"></td>
																		<td><input class="common target_3_val" name="target_3[1][1][1]" oninput="hitungTarget(1,1,1)"></td>
																		<td><input class="common target_4_val" name="target_4[1][1][1]" oninput="hitungTarget(1,1,1)"></td>
																		<td><input class="common target_5_val" name="target_5[1][1][1]" oninput="hitungTarget(1,1,1)"></td>
																		<td><input class="common kondisi_akhir_val" name="kondisi_akhir[1][1][1]"  readonly></td>
																	</tr>
																</table>
															</td>
														</tr>
													</table>
												</td>
											</tr>
										</tbody>
									</table>
								</td>
							</tr>
						</tbody>
					</table>
				</td>
			</tr>
		</tbody>
	</table>
</div>

<div style="display: none">
	<table>
		<tbody id="tujuan_box">
			<tr class="bg-info table-tujuan">
				<td><textarea class="common tujuan_val" name="tujuan[1][1]"></textarea></td>
				<td align="center"><a class="icon-remove hapus_tujuan" href="javascript:void(0)"></a></td>
			</tr>
			<tr class="indikator_frame">
				<td colspan="2">
					<table class="table-common">
						<thead>
							<tr>
								<th colspan="3">Indikator Kinerja <a id-m="1" id-t="1" id="tambah_indikator" class="icon-plus-sign" href="javascript:void(0)"></a></td>
								<th width="50px">Action</th>
							</tr>
						</thead>
						<tbody key="1">
							<tr>
								<td colspan="3"><textarea class="common indikator_val" name="indikator[1][1][1]"></textarea></td>
								<td align="center"></td>
							</tr>
							<tr class="target_frame">
								<td colspan="4">
									<table>
										<tr>
											<td rowspan="3" width="15px"></td>
											<td width="300px">Satuan</td>
											<td colspan="2"><?php echo form_dropdown('satuan_target[1][1][1]', $satuan, '', 'class="common satuan_target_val" id="satuan_target"'); ?></td>
										</tr>
										<tr>
											<td rowspan="2">Kategori Indikator</td>
											<td colspan="2"><?php echo form_dropdown('status_target[1][1][1]', $status_indikator, '', 'class="common status_target_val" id="status_target" onchange="hitungTarget(1,1,1)"'); ?></td>
										</tr>
										<tr>
											<td  colspan="2"><?php echo form_dropdown('kategori_target[1][1][1]', $kategori_indikator, '', 'class="common kategori_target_val" id="kategori_target" onchange="hitungTarget(1,1,1)"'); ?></td>
										</tr>
										<tr>
											<td colspan="10">
												<table class="table-common">
													<tr>
														<td>Kondisi Awal</td>
														<td>Target 1</td>
														<td>Target 2</td>
														<td>Target 3</td>
														<td>Target 4</td>
														<td>Target 5</td>
														<td>Kondisi Akhir</td>
													</tr>
													<tr>
														<td><input class="common kondisi_awal_val" name="kondisi_awal[1][1][1]" oninput="hitungTarget(1,1,1)"></td>
														<td><input class="common target_1_val" name="target_1[1][1][1]" oninput="hitungTarget(1,1,1)"></td>
														<td><input class="common target_2_val" name="target_2[1][1][1]" oninput="hitungTarget(1,1,1)"></td>
														<td><input class="common target_3_val" name="target_3[1][1][1]" oninput="hitungTarget(1,1,1)"></td>
														<td><input class="common target_4_val" name="target_4[1][1][1]" oninput="hitungTarget(1,1,1)"></td>
														<td><input class="common target_5_val" name="target_5[1][1][1]" oninput="hitungTarget(1,1,1)"></td>
														<td><input class="common kondisi_akhir_val" name="kondisi_akhir[1][1][1]"  readonly></td>
													</tr>
												</table>
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</tbody>
					</table>
				</td>
			</tr>
		</tbody>
	</table>
</div>

<div style="display: none">
	<table>
		<tbody id="indikator_box">
			<tr class="bg-info">
				<td colspan="3"><textarea class="common indikator_val" name="indikator[1][1][1]"></textarea></td>
				<td align="center"><a class="icon-remove hapus_indikator" href="javascript:void(0)"></a></td>
			</tr>
			<tr class="target_frame">
				<td colspan="4">
					<table>
						<tr>
							<td rowspan="3" width="15px"></td>
							<td width="300px">Satuan</td>
							<td colspan="2"><?php echo form_dropdown('satuan_target[1][1][1]', $satuan, '', 'class="common satuan_target_val" id="satuan_target"'); ?></td>
						</tr>
						<tr>
							<td rowspan="2">Kategori Indikator</td>
							<td colspan="2"><?php echo form_dropdown('status_target[1][1][1]', $status_indikator, '', 'class="common status_target_val" id="status_target" onchange="hitungTarget(1,1,1)"'); ?></td>
						</tr>
						<tr>
							<td  colspan="2"><?php echo form_dropdown('kategori_target[1][1][1]', $kategori_indikator, '', 'class="common kategori_target_val" id="kategori_target" onchange="hitungTarget(1,1,1)"'); ?></td>
						</tr>
						<tr>
							<td colspan="10">
								<table class="table-common">
									<tr>
										<td>Kondisi Awal</td>
										<td>Target 1</td>
										<td>Target 2</td>
										<td>Target 3</td>
										<td>Target 4</td>
										<td>Target 5</td>
										<td>Kondisi Akhir</td>
									</tr>
									<tr>
										<td><input class="common kondisi_awal_val" name="kondisi_awal[1][1][1]" oninput="hitungTarget(1,1,1)"></td>
										<td><input class="common target_1_val" name="target_1[1][1][1]" oninput="hitungTarget(1,1,1)"></td>
										<td><input class="common target_2_val" name="target_2[1][1][1]" oninput="hitungTarget(1,1,1)"></td>
										<td><input class="common target_3_val" name="target_3[1][1][1]" oninput="hitungTarget(1,1,1)"></td>
										<td><input class="common target_4_val" name="target_4[1][1][1]" oninput="hitungTarget(1,1,1)"></td>
										<td><input class="common target_5_val" name="target_5[1][1][1]" oninput="hitungTarget(1,1,1)"></td>
										<td><input class="common kondisi_akhir_val" name="kondisi_akhir[1][1][1]"  readonly></td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</tbody>
	</table>
</div>

<script type="text/javascript">
	function hitungTarget(index, index2, index3){
		var kategori_target = $("select[name='kategori_target["+ index +"]["+ index2 +"]["+ index3 +"]']").val();
		var status_target = $("select[name='status_target["+ index +"]["+ index2 +"]["+ index3 +"]']").val();
		var target_aw = $("input[name='kondisi_awal["+ index +"]["+ index2 +"]["+ index3 +"]']").val();
		var target_1 = $("input[name='target_1["+ index +"]["+ index2 +"]["+ index3 +"]']").val();
		var target_2 = $("input[name='target_2["+ index +"]["+ index2 +"]["+ index3 +"]']").val();
		var target_3 = $("input[name='target_3["+ index +"]["+ index2 +"]["+ index3 +"]']").val();
		var target_4 = $("input[name='target_4["+ index +"]["+ index2 +"]["+ index3 +"]']").val();
		var target_5 = $("input[name='target_5["+ index +"]["+ index2 +"]["+ index3 +"]']").val();

		if (status_target == "1") {
			if (kategori_target == "1") {
				$("input[name='kondisi_akhir["+ index +"]["+ index2 +"]["+ index3 +"]']").val(target_5);
			}else if (kategori_target == "2" ) {
				$("input[name='kondisi_akhir["+ index +"]["+ index2 +"]["+ index3 +"]']").val(target_5);
			}else	if (kategori_target == "3") {
				var hasil = parseFloat(target_aw) + parseFloat(target_1) + parseFloat(target_2) + parseFloat(target_3) + parseFloat(target_4) + parseFloat(target_5);
				$("input[name='kondisi_akhir["+ index +"]["+ index2 +"]["+ index3 +"]']").val(hasil);

			}
		}else if (status_target == "2") {
			if (kategori_target == "1") {
				$("input[name='kondisi_akhir["+ index +"]["+ index2 +"]["+ index3 +"]']").val(target_5);
			}else if (kategori_target == "2" ) {
				$("input[name='kondisi_akhir["+ index +"]["+ index2 +"]["+ index3 +"]']").val(target_5);
			}else	if (kategori_target == "3") {
				var hasil = parseFloat(target_aw) - parseFloat(target_1) - parseFloat(target_2) - parseFloat(target_3) - parseFloat(target_4) - parseFloat(target_5);
				$("input[name='kondisi_akhir["+ index +"]["+ index2 +"]["+ index3 +"]']").val(hasil);
			}
		}

	}
</script>
