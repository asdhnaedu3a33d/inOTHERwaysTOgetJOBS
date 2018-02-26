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

	tr.table-misi{
		background-color: #2785D3;
	}

	.table-common tr.table-misi:nth-child(even) {
		background-color: #2785D3;
	}

	tr.table-visi{		
		background-color: #FFF8AA !important;
	}
</style>
<script type="text/javascript">
	$(document).ready(function(){				
		$('form#rpjmd').validate({rules: {
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

		    var valid = $("form#rpjmd").valid();
		    if (valid) {
		    	$.blockUI({
					css: window._css,
					overlayCSS: window._ovcss
				});
		    	$("form#rpjmd").submit();
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
			var indeks = "["+ key +"][1][1]";
			var hitung = "hitungTarget("+ key +",1,1)";
			$("#misi_box a#tambah_indikator").attr("id-m", key);
			$("#misi_box a#tambah_indikator").attr("id-t", '1');

			$("#misi_box textarea.tujuan_val").attr("name", name);

			$("#misi_box textarea.indikator_val").attr("name", "indikator"+indeks);
			$("#misi_box textarea.cara_pengukuran_val").attr("name", "cara_pengukuran"+indeks);
			$("#misi_box input.satuan_target_val").attr("name", "satuan_target"+indeks);

			$("#misi_box select.status_indikator_val").attr("name", "status_indikator"+indeks);
			$("#misi_box select.status_indikator_val").attr("onchange", hitung);
			$("#misi_box select.kategori_indikator_val").attr("name", "kategori_indikator"+indeks);
			$("#misi_box select.kategori_indikator_val").attr("onchange", hitung);

			$("#misi_box input.kondisi_awal_val").attr("name", "kondisi_awal"+indeks);
			$("#misi_box input.kondisi_awal_val").attr("oninput", hitung);
			$("#misi_box input.target_1_val").attr("name", "target_1"+indeks);
			$("#misi_box input.target_1_val").attr("oninput", hitung);
			$("#misi_box input.target_2_val").attr("name", "target_2"+indeks);
			$("#misi_box input.target_2_val").attr("oninput", hitung);
			$("#misi_box input.target_3_val").attr("name", "target_3"+indeks);
			$("#misi_box input.target_3_val").attr("oninput", hitung);
			$("#misi_box input.target_4_val").attr("name", "target_4"+indeks);
			$("#misi_box input.target_4_val").attr("oninput", hitung);
			$("#misi_box input.target_5_val").attr("name", "target_5"+indeks);
			$("#misi_box input.target_5_val").attr("oninput", hitung);
			$("#misi_box input.kondisi_akhir_val").attr("name", "kondisi_akhir"+indeks);
			
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

			var name = "tujuan["+ id_misi +"]["+ key +"]";
			$("#tujuan_box textarea").attr("name", name);
			$("#tujuan_box a#tambah_tujuan").attr("id-m", id_misi);

			var indeks = "["+ id_misi +"]["+ key +"][1]";
			var hitung = "hitungTarget("+ id_misi +","+ key +",1)";
			$("#tujuan_box a#tambah_indikator").attr("id-m", id_misi);
			$("#tujuan_box a#tambah_indikator").attr("id-t", key);

			$("#tujuan_box textarea.indikator_val").attr("name", "indikator"+indeks);
			$("#tujuan_box textarea.cara_pengukuran_val").attr("name", "cara_pengukuran"+indeks);
			$("#tujuan_box input.satuan_target_val").attr("name", "satuan_target"+indeks);

			$("#tujuan_box select.status_indikator_val").attr("name", "status_indikator"+indeks);
			$("#tujuan_box select.status_indikator_val").attr("onchange", hitung);
			$("#tujuan_box select.kategori_indikator_val").attr("name", "kategori_indikator"+indeks);
			$("#tujuan_box select.kategori_indikator_val").attr("onchange", hitung);

			$("#tujuan_box input.kondisi_awal_val").attr("name", "kondisi_awal"+indeks);
			$("#tujuan_box input.kondisi_awal_val").attr("oninput", hitung);
			$("#tujuan_box input.target_1_val").attr("name", "target_1"+indeks);
			$("#tujuan_box input.target_1_val").attr("oninput", hitung);
			$("#tujuan_box input.target_2_val").attr("name", "target_2"+indeks);
			$("#tujuan_box input.target_2_val").attr("oninput", hitung);
			$("#tujuan_box input.target_3_val").attr("name", "target_3"+indeks);
			$("#tujuan_box input.target_3_val").attr("oninput", hitung);
			$("#tujuan_box input.target_4_val").attr("name", "target_4"+indeks);
			$("#tujuan_box input.target_4_val").attr("oninput", hitung);
			$("#tujuan_box input.target_5_val").attr("name", "target_5"+indeks);
			$("#tujuan_box input.target_5_val").attr("oninput", hitung);
			$("#tujuan_box input.kondisi_akhir_val").attr("name", "kondisi_akhir"+indeks);

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

			var indeks = "["+ id_misi +"]["+ id_tujuan +"]["+ key +"]";
			var hitung = "hitungTarget("+ id_misi +","+ id_tujuan +","+ key +")";
			$("#indikator_box textarea.indikator_val").attr("name", "indikator"+indeks);
			$("#indikator_box textarea.cara_pengukuran_val").attr("name", "cara_pengukuran"+indeks);
			$("#indikator_box input.satuan_target_val").attr("name", "satuan_target"+indeks);

			$("#indikator_box select.status_indikator_val").attr("name", "status_indikator"+indeks);
			$("#indikator_box select.status_indikator_val").attr("onchange", hitung);
			$("#indikator_box select.kategori_indikator_val").attr("name", "kategori_indikator"+indeks);
			$("#indikator_box select.kategori_indikator_val").attr("onchange", hitung);

			$("#indikator_box input.kondisi_awal_val").attr("name", "kondisi_awal"+indeks);
			$("#indikator_box input.kondisi_awal_val").attr("oninput", hitung);
			$("#indikator_box input.target_1_val").attr("name", "target_1"+indeks);
			$("#indikator_box input.target_1_val").attr("oninput", hitung);
			$("#indikator_box input.target_2_val").attr("name", "target_2"+indeks);
			$("#indikator_box input.target_2_val").attr("oninput", hitung);
			$("#indikator_box input.target_3_val").attr("name", "target_3"+indeks);
			$("#indikator_box input.target_3_val").attr("oninput", hitung);
			$("#indikator_box input.target_4_val").attr("name", "target_4"+indeks);
			$("#indikator_box input.target_4_val").attr("oninput", hitung);
			$("#indikator_box input.target_5_val").attr("name", "target_5"+indeks);
			$("#indikator_box input.target_5_val").attr("oninput", hitung);
			$("#indikator_box input.kondisi_akhir_val").attr("name", "kondisi_akhir"+indeks);

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
			if (!empty($rpjmd)){
			    echo "Edit Data RPJMD";
			} else{
			    echo "Input Data RPJMD";
			}
		?>
		</h3>
 	</header>
 	<div class="module_content">
 		<form action="<?php echo site_url('rpjmd/save');?>" method="POST" name="rpjmd" id="rpjmd" accept-charset="UTF-8" enctype="multipart/form-data" >
 			<input type="hidden" name="id_rpjmd" value="<?php if(!empty($rpjmd->id)){echo $rpjmd->id;} ?>" />
 			<table class="fcari" width="100%">
 				<tbody> 					 				
					<tr class="table-visi">
						<td>Visi</td>
						<td>
							<textarea class="common" name="visi"><?php if(!empty($rpjmd->visi)){echo $rpjmd->visi;} ?></textarea>
						</td>
					</tr>
 				</tbody>
 			</table>
 			<table class="table-common" width="99%">
 				<thead>
 					<tr>
 						<th>Misi <a id="tambah_misi" class="icon-plus-sign" href="javascript:void(0)"></a></th>
 						<th width="50px">Action</th>
 					</tr>
 				</thead>
 				<tbody id="misi_frame" key="<?php echo (!empty($rpjmd_misi->result()))?$rpjmd_misi->num_rows():'1'; ?>">
 				<?php
					if (!empty($rpjmd_misi->result())) {
						$i=0;
						foreach ($rpjmd_misi->result() as $row) {
							$i++;
				?>
					<input type="hidden" name="id_misi[<?php echo $i; ?>]" value="<?php echo $row->id; ?>">
					<tr class="table-misi">
 						<td>
 							<textarea class="common misi_val" name="misi[<?php echo $i; ?>]"><?php echo $row->misi; ?></textarea>
						</td>
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
 					<tr class="tujuan_frame">
 						<td colspan="2">
 							<table class="table-common" width="100%">
 								<thead>
				 					<tr>
				 						<th>Tujuan <a id-m="<?php echo $i; ?>" id="tambah_tujuan" class="icon-plus-sign" href="javascript:void(0)"></a></th>
				 						<th width="50px">Action</th>
				 					</tr>
				 				</thead>
			 				<?php
			 					$rpjmd_tujuan = $this->m_rpjmd_trx->get_each_rpjmd_tujuan($row->id_rpjmd, $row->id);			 					
			 				?>
				 				<tbody key="<?php echo (!empty($rpjmd_tujuan))?$rpjmd_tujuan->num_rows():'1'; ?>">
				 				<?php
									if (!empty($rpjmd_tujuan)) {
										$j=0;										
										foreach ($rpjmd_tujuan->result() as $row1) {
											$j++;											
								?>
									<input type="hidden" name="id_tujuan[<?php echo $i; ?>][<?php echo $j; ?>]" value="<?php echo $row1->id; ?>">
									<tr  bgcolor="green">
				 						<td>				 							
				 							<textarea class="common tujuan_val" name="tujuan[<?php echo $i; ?>][<?php echo $j; ?>]"><?php echo $row1->tujuan; ?></textarea>
			 							</td>
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
				 					<tr class="indikator_frame">
										<td colspan="2">
											<table class="table-common" width="100%">
				 								<thead>
								 					<tr>
								 						<th >Indikator <a id-m="<?php echo $i; ?>" id-t="<?php echo $j; ?>" id="tambah_indikator" class="icon-plus-sign" href="javascript:void(0)"></a></th>
								 						<th width="50px">Action</th>
								 					</tr>
								 				</thead>
							 				<?php
							 					$rpjmd_indikator = $this->m_rpjmd_trx->get_each_rpjmd_indikator_tujuan($row1->id, FALSE, TRUE);
							 				?>
								 				<tbody key="<?php echo (!empty($rpjmd_indikator))?$rpjmd_indikator->num_rows():'1'; ?>">
								 				<?php
													if (!empty($rpjmd_indikator->result())) {
														$k=0;										
														foreach ($rpjmd_indikator->result() as $row2) {
															$k++;											
												?>
													<input type="hidden" name="id_indikator[<?php echo $i; ?>][<?php echo $j; ?>][<?php echo $k; ?>]" value="<?php echo $row2->id; ?>">
													<tr bgcolor="orange">
								 						<td>				 							
								 							<textarea class="common indikator_val" name="indikator[<?php echo $i; ?>][<?php echo $j; ?>][<?php echo $k; ?>]"><?php echo $row2->indikator; ?></textarea>
							 							</td>
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
														<td colspan="2">
															<table class="table-common" width="100%">
																<tr>
																	<td width="20%">Keterangan</td>
								 									<td><textarea class="common cara_pengukuran_val" name="cara_pengukuran[<?php echo $i; ?>][<?php echo $j; ?>][<?php echo $k; ?>]"><?php echo $row2->cara_pengukuran; ?></textarea></td>
																</tr>
																<tr>
																	<td>Satuan</td>
								 									<td><input type="text" class="common satuan_target_val" name="satuan_target[<?php echo $i; ?>][<?php echo $j; ?>][<?php echo $k; ?>]" value="<?php echo $row2->satuan_target; ?>"></td>
																</tr>
																<tr>
																	<td rowspan="2">Kategori Indikator</td>
								 									<td><?php echo form_dropdown('status_indikator['.$i.']['.$j.']['.$k.']', $status_indikator, $row2->status_indikator, 'class="common status_indikator_val" onchange="hitungTarget('. $i .','. $j .','.$k.')"'); ?></td>
																</tr>
																<tr>
								 									<td><?php echo form_dropdown('kategori_indikator['.  $i .']['. $j .']['.$k.']', $kategori_indikator, $row2->kategori_indikator, 'class="common kategori_indikator_val" onchange="hitungTarget('. $i .','. $j .','.$k.')"'); ?></td>
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
														}
													}

													if(empty($rpjmd_indikator->result())){
												?>
													<tr bgcolor="orange">
								 						<td><textarea class="common indikator_val" name="indikator[<?php echo $i; ?>][<?php echo $j; ?>][1]"></textarea></td>
								 						<td align="center"></td>
								 					</tr>
								 					<tr class="target_frame">
														<td colspan="2">
															<table class="table-common" width="100%">
																<tr>
																	<td width="20%">Keterangan</td>
								 									<td><textarea class="common cara_pengukuran_val" name="cara_pengukuran[<?php echo $i; ?>][<?php echo $j; ?>][1]"></textarea></td>
																</tr>
																<tr>
																	<td>Satuan</td>
								 									<td><input type="text" class="common satuan_target_val" name="satuan_target[<?php echo $i; ?>][<?php echo $j; ?>][1]"></td>
																</tr>
																<tr>
																	<td rowspan="2">Kategori Indikator</td>
								 									<td><?php echo form_dropdown('status_indikator['.$i.']['.$j.'][1]', $status_indikator, NULL, 'class="common status_indikator_val" onchange="hitungTarget('. $i .','. $j .',1)"'); ?></td>
																</tr>
																<tr>
								 									<td><?php echo form_dropdown('kategori_indikator['.  $i .']['. $j .'][1]', $kategori_indikator, NULL, 'class="common kategori_indikator_val" onchange="hitungTarget('. $i .','. $j .',1)"'); ?></td>
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
																				<td><input class="common kondisi_awal_val" name="kondisi_awal[<?php echo $i; ?>][<?php echo $j; ?>][1]" oninput="hitungTarget(<?php echo "$i, $j, 1"; ?>)"></td>
																				<td><input class="common target_1_val" name="target_1[<?php echo $i; ?>][<?php echo $j; ?>][1]" oninput="hitungTarget(<?php echo "$i, $j, 1"; ?>)"></td>
																				<td><input class="common target_2_val" name="target_2[<?php echo $i; ?>][<?php echo $j; ?>][1]" oninput="hitungTarget(<?php echo "$i, $j, 1"; ?>)"></td>
																				<td><input class="common target_3_val" name="target_3[<?php echo $i; ?>][<?php echo $j; ?>][1]" oninput="hitungTarget(<?php echo "$i, $j, 1"; ?>)"></td>
																				<td><input class="common target_4_val" name="target_4[<?php echo $i; ?>][<?php echo $j; ?>][1]" oninput="hitungTarget(<?php echo "$i, $j, 1"; ?>)"></td>
																				<td><input class="common target_5_val" name="target_5[<?php echo $i; ?>][<?php echo $j; ?>][1]" oninput="hitungTarget(<?php echo "$i, $j, 1"; ?>)"></td>
																				<td><input class="common kondisi_akhir_val" name="kondisi_akhir[<?php echo $i; ?>][<?php echo $j; ?>][1]" readonly></td>
																			</tr>
																		</table>
																	</td>
																</tr>
															</table>
														</td>
								 					</tr>
												<?php
													}
												?>
																 					
								 				</tbody>
				 							</table>
										</td>
									</tr>
								<?php											
										}
									}

									if(empty($rpjmd_tujuan)){
								?>
									<tr bgcolor="green">
				 						<td><textarea class="common tujuan_val" name="tujuan[<?php echo $i; ?>][1]"></textarea></td>
				 						<td align="center"></td>
				 					</tr>
				 					<tr>
										<td colspan="2">
											<table class="table-common" width="100%">
				 								<thead>
								 					<tr>
								 						<th >Indikator <a id-m="<?php echo $i; ?>" id-t="1" id="tambah_indikator" class="icon-plus-sign" href="javascript:void(0)"></a></th>
								 						<th width="50px">Action</th>
								 					</tr>
								 				</thead>
								 				<tbody key="1">
													<tr bgcolor="orange">
								 						<td><textarea class="common indikator_val" name="indikator[<?php echo $i; ?>][1][1]"></textarea></td>
								 						<td align="center"></td>
								 					</tr>
								 					<tr class="target_frame">
														<td colspan="2">
															<table class="table-common" width="100%">
																<tr>
																	<td width="20%">Keterangan</td>
								 									<td><textarea class="common cara_pengukuran_val" name="cara_pengukuran[<?php echo $i; ?>][1][1]"></textarea></td>
																</tr>
																<tr>
																	<td>Satuan</td>
								 									<td><input type="text" class="common satuan_target_val" name="satuan_target[<?php echo $i; ?>][1][1]"></td>
																</tr>
																<tr>
																	<td rowspan="2">Kategori Indikator</td>
								 									<td><?php echo form_dropdown('status_indikator['.$i.'][1][1]', $status_indikator, NULL, 'class="common status_indikator_val" onchange="hitungTarget('. $i .',1,1)"'); ?></td>
																</tr>
																<tr>
								 									<td><?php echo form_dropdown('kategori_indikator['.  $i .'][1][1]', $kategori_indikator, NULL, 'class="common kategori_indikator_val" onchange="hitungTarget('. $i .',1,1)"'); ?></td>
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
																				<td><input class="common kondisi_awal_val" name="kondisi_awal[<?php echo $i; ?>][1][1]" oninput="hitungTarget(<?php echo "$i, 1, 1"; ?>)"></td>
																				<td><input class="common target_1_val" name="target_1[<?php echo $i; ?>][1][1]" oninput="hitungTarget(<?php echo "$i, 1, 1"; ?>)"></td>
																				<td><input class="common target_2_val" name="target_2[<?php echo $i; ?>][1][1]" oninput="hitungTarget(<?php echo "$i, 1, 1"; ?>)"></td>
																				<td><input class="common target_3_val" name="target_3[<?php echo $i; ?>][1][1]" oninput="hitungTarget(<?php echo "$i, 1, 1"; ?>)"></td>
																				<td><input class="common target_4_val" name="target_4[<?php echo $i; ?>][1][1]" oninput="hitungTarget(<?php echo "$i, 1, 1"; ?>)"></td>
																				<td><input class="common target_5_val" name="target_5[<?php echo $i; ?>][1][1]" oninput="hitungTarget(<?php echo "$i, 1, 1"; ?>)"></td>
																				<td><input class="common kondisi_akhir_val" name="kondisi_akhir[<?php echo $i; ?>][1][1]" readonly></td>
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
								<?php
									}
								?>
												 					
				 				</tbody>
 							</table>
 						</td>
 					</tr>
				<?php
						}
					}else{
				?>					
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
									<tr  bgcolor="green">
										<td><textarea class="common tujuan_val" name="tujuan[1][1]"></textarea></td>
										<td align="center"></td>
									</tr>
									<tr class="indikator_frame">
										<td colspan="2">
											<table class="table-common" width="100%">
				 								<thead>
								 					<tr>
								 						<th >Indikator <a id-m="1" id-t="1" id="tambah_indikator" class="icon-plus-sign" href="javascript:void(0)"></a></th>
								 						<th width="50px">Action</th>
								 					</tr>
								 				</thead>
								 				<tbody key="1">
													<tr  bgcolor="orange">
								 						<td><textarea class="common indikator_val" name="indikator[1][1][1]"></textarea></td>
								 						<td align="center"></td>
								 					</tr>
								 					<tr class="target_frame">
														<td colspan="2">
															<table class="table-common" width="100%">
																<tr>
																	<td width="20%">Keterangan</td>
								 									<td><textarea class="common cara_pengukuran_val" name="cara_pengukuran[1][1][1]"></textarea></td>
																</tr>
																<tr>
																	<td>Satuan</td>
								 									<td><input type="text" class="common satuan_target_val" name="satuan_target[1][1][1]"></td>
																</tr>
																<tr>
																	<td rowspan="2">Kategori Indikator</td>
								 									<td><?php echo form_dropdown('status_indikator[1][1][1]', $status_indikator, NULL, 'class="common status_indikator_val" onchange="hitungTarget(1,1,1)"'); ?></td>
																</tr>
																<tr>
								 									<td><?php echo form_dropdown('kategori_indikator[1][1][1]', $kategori_indikator, NULL, 'class="common kategori_indikator_val" onchange="hitungTarget(1,1,1)"'); ?></td>
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
																				<td><input class="common kondisi_awal_val" name="kondisi_awal[1][1][1]" oninput="hitungTarget(<?php echo "1, 1, 1"; ?>)"></td>
																				<td><input class="common target_1_val" name="target_1[1][1][1]" oninput="hitungTarget(<?php echo "1, 1, 1"; ?>)"></td>
																				<td><input class="common target_2_val" name="target_2[1][1][1]" oninput="hitungTarget(<?php echo "1, 1, 1"; ?>)"></td>
																				<td><input class="common target_3_val" name="target_3[1][1][1]" oninput="hitungTarget(<?php echo "1, 1, 1"; ?>)"></td>
																				<td><input class="common target_4_val" name="target_4[1][1][1]" oninput="hitungTarget(<?php echo "1, 1, 1"; ?>)"></td>
																				<td><input class="common target_5_val" name="target_5[1][1][1]" oninput="hitungTarget(<?php echo "1, 1, 1"; ?>)"></td>
																				<td><input class="common kondisi_akhir_val" name="kondisi_akhir[1][1][1]" readonly></td>
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
				<?php
					}
				?>				
 				</tbody>
 			</table>
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
			<tr class="table-misi">
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
							<tr bgcolor="green">
								<td><textarea class="common tujuan_val" name="tujuan[1][1]"></textarea></td>
								<td align="center"></td>
							</tr>
							<tr class="indikator_frame">
								<td colspan="2">
									<table class="table-common" width="100%">
		 								<thead>
						 					<tr>
						 						<th >Indikator <a id-m="1" id-t="1" id="tambah_indikator" class="icon-plus-sign" href="javascript:void(0)"></a></th>
						 						<th width="50px">Action</th>
						 					</tr>
						 				</thead>
						 				<tbody key="1">
											<tr bgcolor="orange">
						 						<td><textarea class="common indikator_val" name="indikator[1][1][1]"></textarea></td>
						 						<td align="center"></td>
						 					</tr>
						 					<tr class="target_frame">
												<td colspan="2">
													<table class="table-common" width="100%">
														<tr>
															<td width="20%">Keterangan</td>
						 									<td><textarea class="common cara_pengukuran_val" name="cara_pengukuran[1][1][1]"></textarea></td>
														</tr>
														<tr>
															<td>Satuan</td>
						 									<td><input type="text" class="common satuan_target_val" name="satuan_target[1][1][1]"></td>
														</tr>
														<tr>
															<td rowspan="2">Kategori Indikator</td>
						 									<td><?php echo form_dropdown('status_indikator[1][1][1]', $status_indikator, NULL, 'class="common status_indikator_val" onchange="hitungTarget(1,1,1)"'); ?></td>
														</tr>
														<tr>
						 									<td><?php echo form_dropdown('kategori_indikator[1][1][1]', $kategori_indikator, NULL, 'class="common kategori_indikator_val" onchange="hitungTarget(1,1,1)"'); ?></td>
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
																		<td><input class="common kondisi_awal_val" name="kondisi_awal[1][1][1]" oninput="hitungTarget(<?php echo "1, 1, 1"; ?>)"></td>
																		<td><input class="common target_1_val" name="target_1[1][1][1]" oninput="hitungTarget(<?php echo "1, 1, 1"; ?>)"></td>
																		<td><input class="common target_2_val" name="target_2[1][1][1]" oninput="hitungTarget(<?php echo "1, 1, 1"; ?>)"></td>
																		<td><input class="common target_3_val" name="target_3[1][1][1]" oninput="hitungTarget(<?php echo "1, 1, 1"; ?>)"></td>
																		<td><input class="common target_4_val" name="target_4[1][1][1]" oninput="hitungTarget(<?php echo "1, 1, 1"; ?>)"></td>
																		<td><input class="common target_5_val" name="target_5[1][1][1]" oninput="hitungTarget(<?php echo "1, 1, 1"; ?>)"></td>
																		<td><input class="common kondisi_akhir_val" name="kondisi_akhir[1][1][1]" readonly></td>
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
			<tr bgcolor="green">
				<td><textarea class="common tujuan_val" name="tujuan[1][1]"></textarea></td>
				<td align="center"><a class="icon-remove hapus_tujuan" href="javascript:void(0)"></a></td>
			</tr>
			<tr class="indikator_frame">
				<td colspan="2">
					<table class="table-common" width="100%">
						<thead>
		 					<tr>
		 						<th >Indikator <a id-m="1" id-t="1" id="tambah_indikator" class="icon-plus-sign" href="javascript:void(0)"></a></th>
		 						<th width="50px">Action</th>
		 					</tr>
		 				</thead>
		 				<tbody key="1">
							<tr bgcolor="orange">
		 						<td><textarea class="common indikator_val" name="indikator[1][1][1]"></textarea></td>
		 						<td align="center"></td>
		 					</tr>
		 					<tr  class="target_frame">
								<td colspan="2">
									<table class="table-common" width="100%">
										<tr>
											<td width="20%">Keterangan</td>
		 									<td><textarea class="common cara_pengukuran_val" name="cara_pengukuran[1][1][1]"></textarea></td>
										</tr>
										<tr>
											<td>Satuan</td>
		 									<td><input type="text" class="common satuan_target_val" name="satuan_target[1][1][1]"></td>
										</tr>
										<tr>
											<td rowspan="2">Kategori Indikator</td>
		 									<td><?php echo form_dropdown('status_indikator[1][1][1]', $status_indikator, NULL, 'class="common status_indikator_val" onchange="hitungTarget(1,1,1)"'); ?></td>
										</tr>
										<tr>
		 									<td><?php echo form_dropdown('kategori_indikator[1][1][1]', $kategori_indikator, NULL, 'class="common kategori_indikator_val" onchange="hitungTarget(1,1,1)"'); ?></td>
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
														<td><input class="common kondisi_awal_val" name="kondisi_awal[1][1][1]" oninput="hitungTarget(<?php echo "1, 1, 1"; ?>)"></td>
														<td><input class="common target_1_val" name="target_1[1][1][1]" oninput="hitungTarget(<?php echo "1, 1, 1"; ?>)"></td>
														<td><input class="common target_2_val" name="target_2[1][1][1]" oninput="hitungTarget(<?php echo "1, 1, 1"; ?>)"></td>
														<td><input class="common target_3_val" name="target_3[1][1][1]" oninput="hitungTarget(<?php echo "1, 1, 1"; ?>)"></td>
														<td><input class="common target_4_val" name="target_4[1][1][1]" oninput="hitungTarget(<?php echo "1, 1, 1"; ?>)"></td>
														<td><input class="common target_5_val" name="target_5[1][1][1]" oninput="hitungTarget(<?php echo "1, 1, 1"; ?>)"></td>
														<td><input class="common kondisi_akhir_val" name="kondisi_akhir[1][1][1]" readonly></td>
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
			<tr bgcolor="orange">
				<td><textarea class="common indikator_val" name="indikator[1][1][1]"></textarea></td>
				<td align="center"><a class="icon-remove hapus_indikator" href="javascript:void(0)"></a></td>
			</tr>
			<tr class="target_frame">
				<td colspan="2">
					<table class="table-common" width="100%">
						<tr>
							<td width="20%">Keterangan</td>
								<td><textarea class="common cara_pengukuran_val" name="cara_pengukuran[1][1][1]"></textarea></td>
						</tr>
						<tr>
							<td>Satuan</td>
								<td><input type="text" class="common satuan_target_val" name="satuan_target[1][1][1]"></td>
						</tr>
						<tr>
							<td rowspan="2">Kategori Indikator</td>
								<td><?php echo form_dropdown('status_indikator[1][1][1]', $status_indikator, NULL, 'class="common status_indikator_val" onchange="hitungTarget(1,1,1)"'); ?></td>
						</tr>
						<tr>
								<td><?php echo form_dropdown('kategori_indikator[1][1][1]', $kategori_indikator, NULL, 'class="common kategori_indikator_val" onchange="hitungTarget(1,1,1)"'); ?></td>
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
										<td><input class="common kondisi_awal_val" name="kondisi_awal[1][1][1]" oninput="hitungTarget(<?php echo "1, 1, 1"; ?>)"></td>
										<td><input class="common target_1_val" name="target_1[1][1][1]" oninput="hitungTarget(<?php echo "1, 1, 1"; ?>)"></td>
										<td><input class="common target_2_val" name="target_2[1][1][1]" oninput="hitungTarget(<?php echo "1, 1, 1"; ?>)"></td>
										<td><input class="common target_3_val" name="target_3[1][1][1]" oninput="hitungTarget(<?php echo "1, 1, 1"; ?>)"></td>
										<td><input class="common target_4_val" name="target_4[1][1][1]" oninput="hitungTarget(<?php echo "1, 1, 1"; ?>)"></td>
										<td><input class="common target_5_val" name="target_5[1][1][1]" oninput="hitungTarget(<?php echo "1, 1, 1"; ?>)"></td>
										<td><input class="common kondisi_akhir_val" name="kondisi_akhir[1][1][1]" readonly></td>
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
function hitungTarget(index1,index2,index3){
	var kategori_target = $("select[name='kategori_indikator["+ index1 +"]["+ index2 +"]["+ index3 +"]']").val();
		var status_target = $("select[name='status_indikator["+ index1 +"]["+ index2 +"]["+ index3 +"]']").val();
		var target_aw = $("input[name='kondisi_awal["+ index1 +"]["+ index2 +"]["+ index3 +"]']").val();
		var target_1 = $("input[name='target_1["+ index1 +"]["+ index2 +"]["+ index3 +"]']").val();
		var target_2 = $("input[name='target_2["+ index1 +"]["+ index2 +"]["+ index3 +"]']").val();
		var target_3 = $("input[name='target_3["+ index1 +"]["+ index2 +"]["+ index3 +"]']").val();
		var target_4 = $("input[name='target_4["+ index1 +"]["+ index2 +"]["+ index3 +"]']").val();
		var target_5 = $("input[name='target_5["+ index1 +"]["+ index2 +"]["+ index3 +"]']").val();
	var id_indikator = $("input[name='id_indikator["+ index1 +"]["+ index2 +"]["+ index3 +"]']").val();

	// $('input[name=target_1[1]]').val($('input[name=kondisi_awal[1]]').val());
	// $('input[name='target_1[1]']').val($('input[name='kondisi_awal[1]']').val());
	if(!id_indikator || kategori_target == "3"){
		if (target_5==='0' || target_5==='') {
			if (target_4==='0' || target_4==='') {
				if (target_3==='0' || target_3==='') {
					if (target_2==='0' || target_2==='') {
						if (target_1==='0' || target_1==='') {
							do_hitung(kategori_target, status_target, index1,index2,index3, target_aw);
						}else{
							do_hitung(kategori_target, status_target, index1,index2,index3, target_1);
						}
					}else{
						do_hitung(kategori_target, status_target, index1,index2,index3, target_2);
					}
				}else{
					do_hitung(kategori_target, status_target, index1,index2,index3, target_3);
				}
			}else {
				do_hitung(kategori_target, status_target, index1,index2,index3, target_4);
			}
		}else {
			do_hitung(kategori_target, status_target, index1,index2,index3, target_5);
		}
	}else{
		//alert(target_5);
		if (target_5==='0' || target_5==='') {
			if (target_4==='0' || target_4==='') {
				if (target_3==='0' || target_3==='') {
					if (target_2==='0' || target_2==='') {
						if (target_1==='0' || target_1==='') {
							do_hitung(kategori_target, status_target, index1,index2,index3, target_aw);
						}else{
							do_hitung(kategori_target, status_target, index1,index2,index3, target_1);
						}
					}else{
						do_hitung(kategori_target, status_target, index1,index2,index3, target_2);
					}
				}else{
					do_hitung(kategori_target, status_target, index1,index2,index3, target_3);
				}
			}else {
				do_hitung(kategori_target, status_target, index1,index2,index3, target_4);
			}
		}else {
			do_hitung(kategori_target, status_target, index1,index2,index3, target_5);
		}
	}
}

	function do_hitung(kategori_target, status_target, index1,index2,index3, forakhir){		
		var kategori_target = $("select[name='kategori_indikator["+ index1 +"]["+ index2 +"]["+ index3 +"]']").val();
		var status_target = $("select[name='status_indikator["+ index1 +"]["+ index2 +"]["+ index3 +"]']").val();
		var target_aw = $("input[name='kondisi_awal["+ index1 +"]["+ index2 +"]["+ index3 +"]']").val();
		var target_1 = $("input[name='target_1["+ index1 +"]["+ index2 +"]["+ index3 +"]']").val();
		var target_2 = $("input[name='target_2["+ index1 +"]["+ index2 +"]["+ index3 +"]']").val();
		var target_3 = $("input[name='target_3["+ index1 +"]["+ index2 +"]["+ index3 +"]']").val();
		var target_4 = $("input[name='target_4["+ index1 +"]["+ index2 +"]["+ index3 +"]']").val();
		var target_5 = $("input[name='target_5["+ index1 +"]["+ index2 +"]["+ index3 +"]']").val();


		if (status_target == "1") {
			if (kategori_target == "1") {
				$("input[name='kondisi_akhir["+ index1 +"]["+ index2 +"]["+ index3 +"]']").val(forakhir);
			}else if (kategori_target == "2" ) {
				$("input[name='kondisi_akhir["+ index1 +"]["+ index2 +"]["+ index3 +"]']").val(forakhir);
			}else	if (kategori_target == "3") {
				var hasil = parseFloat(target_aw) + parseFloat(target_1) + parseFloat(target_2) + parseFloat(target_3) + parseFloat(target_4) + parseFloat(target_5);
				$("input[name='kondisi_akhir["+ index1 +"]["+ index2 +"]["+ index3 +"]']").val(hasil);
			}
		}else if (status_target == "2") {
			if (kategori_target == "1") {
				$("input[name='kondisi_akhir["+ index1 +"]["+ index2 +"]["+ index3 +"]']").val(forakhir);
			}else if (kategori_target == "2" ) {
				$("input[name='kondisi_akhir["+ index1 +"]["+ index2 +"]["+ index3 +"]']").val(forakhir);
			}else	if (kategori_target == "3") {
				var hasil = parseFloat(target_aw) - parseFloat(target_1) - parseFloat(target_2) - parseFloat(target_3) - parseFloat(target_4) - parseFloat(target_5);
				$("input[name='kondisi_akhir["+ index1 +"]["+ index2 +"]["+ index3 +"]']").val(hasil);
			}
		}
	}
</script>