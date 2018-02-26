<script type="text/javascript">
	prepare_chosen();
	$('input[name=nominal_1]').autoNumeric(numOptions);
	$('input[name=nominal_2]').autoNumeric(numOptions);
	$('input[name=nominal_3]').autoNumeric(numOptions);
	$('input[name=nominal_4]').autoNumeric(numOptions);
	$('input[name=nominal_5]').autoNumeric(numOptions);
	$('input[name=nominal_6]').autoNumeric(numOptions);
	$('input[name=nominal_7]').autoNumeric(numOptions);
	$('input[name=nominal_8]').autoNumeric(numOptions);
	$('input[name=nominal_9]').autoNumeric(numOptions);
	$('input[name=nominal_10]').autoNumeric(numOptions);
	$('input[name=nominal_11]').autoNumeric(numOptions);
	$('input[name=nominal_12]').autoNumeric(numOptions);

	$('#nominal').autoNumeric(numOptions);

	$('input[name=rcn_target]').autoNumeric(numOptionsNotRound);
	$('input[name=rcn_bobot]').autoNumeric(numOptionsNotRound);
	$('input[name=rcn_anggaran]').autoNumeric(numOptions);

	$(document).on("change", "#kd_kegiatan", function () {
		var str = $(this).find('option:selected').text();
		var nm_kegiatan = str.substring(str.indexOf(".")+2);
		$("#nama_prog_or_keg").val(nm_kegiatan);
	});

	$('form#kegiatan').validate({
		rules: {
			kd_kegiatan : "required",
			indikator_kinerja : "required",
			nominal_1 : {
				required : true,
			},
			nominal_2 : {
				required : true,
			},
			nominal_3 : {
				required : true,
			},
			nominal_4 : {
				required : true,
			},
			nominal_5 : {
				required : true,
			},
			nominal_6 : {
				required : true,
			},
			nominal_7 : {
				required : true,
			},
			nominal_8 : {
				required : true,
			},
			nominal_9 : {
				required : true,
			},
			nominal_10 : {
				required : true,
			},
			nominal_11 : {
				required : true,
			},
			nominal_12 : {
				required : true,
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
			var grand_total = 0;
			for (var i = 1; i < 13; i++) {
				grand_total += parseInt($('input[name=nominal_'+i+']').autoNumeric('get'));
			}
			var tot_belanja = $('#nominal').autoNumeric('get');
			
			if (grand_total > tot_belanja) {
				valid = false;
				alert("Total anggaran kas komulatif harus sama dengan total belanja");
			}

	    if (valid) {
				for (var i = 1; i < 13; i++) {
					$('input[name=nominal_'+i+']').val($('input[name=nominal_'+i+']').autoNumeric('get'));
				}
				$('#nominal').val($('#nominal').autoNumeric('get'));

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
		var target = "target["+ key +"]";
		var satuan_target = "satuan_target["+ key +"]";
		var status_target = "status_target["+ key +"]";
		var kategori_target = "kategori_target["+ key +"]";

		$("#indikator_box_kegiatan textarea").attr("name", name);
		$("#indikator_box_kegiatan input#target").attr("name", target);
		$("#indikator_box_kegiatan input#satuan_target").attr("name", satuan_target);
		$("#indikator_box_kegiatan select#status_target").attr("name", status_target);
		$("#indikator_box_kegiatan select#kategori_target").attr("name", kategori_target);
		$("#indikator_frame_kegiatan").append($("#indikator_box_kegiatan").html());
	});

	$(document).on("click", ".hapus_indikator_kegiatan", function(){
		$(this).parent().parent().remove();
	});

	//baruu untuk tabke uraian belanja
	$(document).ready(function(){
	  $('#nominal_satuan_1').autoNumeric(numOptions);
	  $('#volume_1').autoNumeric(numOptions);
		$('#total_belanja').autoNumeric(numOptions);

		prepare_chosen();
		$(document).on("change", "#cb_jenis_belanja_1", function () {
		  $.ajax({
		    type: "POST",
		    url: '<?php echo site_url("common/cmb_kategori_belanja_1"); ?>',
		    data: {cb_jenis_belanja_1: $(this).val()},
		    success: function(msg){
		      $("#combo_kategori_1").html(msg);
		      $("#cb_subkategori_belanja_1").val(" ");
		      $("#cb_belanja_1").val(" ");
		      $("#cb_subkategori_belanja_1").trigger("chosen:updated");
		      $("#cb_belanja_1").trigger("chosen:updated");
		      prepare_chosen();
		    }
		  });
		});
		$(document).on("change", "#cb_kategori_belanja_1", function () {
		  $.ajax({
		    type: "POST",
		    url: '<?php echo site_url("common/cmb_subkategori_belanja_1"); ?>',
		    data: {cb_jenis_belanja_1:$("#cb_jenis_belanja_1").val(), cb_kategori_belanja_1: $(this).val()},
		    success: function(msg){
		      $("#combo_subkategori_1").html(msg);
		      $("#cb_belanja_1").val("");
		      $("#cb_belanja_1").trigger("chosen:updated");
		      prepare_chosen();
		    }
		  });
		});
		$(document).on("change", "#cb_subkategori_belanja_1", function () {
		  $.ajax({
		    type: "POST",
		    url: '<?php echo site_url("common/cmb_belanja_1"); ?>',
		    data: {cb_jenis_belanja_1:$("#cb_jenis_belanja_1").val(), cb_kategori_belanja_1:$("#cb_kategori_belanja_1").val(), cb_subkategori_belanja_1: $(this).val()},
		    success: function(msg){
		      $("#combo_belanja_1").html(msg);
		      prepare_chosen();
		    }
		  });
		});
	});
</script>

<div style="width: 1200px">
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
		<form action="<?php echo site_url('dpa/save_kegiatan');?>" method="POST" name="kegiatan" id="kegiatan" accept-charset="UTF-8" enctype="multipart/form-data" >
			<input type="hidden" name="id_kegiatan" value="<?php if(!empty($kegiatan->id)){echo $kegiatan->id;} ?>" />
            <input type="hidden" name="id_program" value="<?php if(!empty($id_program)){echo $id_program;} ?>" />
            <input type="hidden" name="id_skpd" value="<?php echo $this->session->userdata("id_skpd"); ?>" />
        	<input type="hidden" name="tahun" value="<?php echo $this->m_settings->get_tahun_anggaran(); ?>" />
			<input type="hidden" name="kd_urusan" value="<?php echo $kodefikasi->kd_urusan; ?>" />
			<input type="hidden" name="kd_bidang" value="<?php echo $kodefikasi->kd_bidang; ?>" />
			<input type="hidden" name="kd_program" value="<?php echo $kodefikasi->kd_program; ?>" />
			<input type="hidden" id="nama_prog_or_keg" name="nama_prog_or_keg" value="<?php echo (!empty($kegiatan->nama_prog_or_keg))?$kegiatan->nama_prog_or_keg:''; ?>" />
			<table class="fcari" width="100%">
				<tbody>
					<tr>
						<td width="20%">SKPD</td>
						<td width="80%" colspan="2"><?php echo $skpd->nama_skpd; ?></td>
					</tr>
					<tr>
						<td>Kode & Nama Program</td>
						<td colspan="2"><?php echo $kodefikasi->kd_urusan.". ".$kodefikasi->kd_bidang.". ".$kodefikasi->kd_program." - ".$kodefikasi->nama_prog_or_keg; ?></td>
					</tr>
					<tr>
						<td>Kegiatan</td>
						<td colspan="2">
							<?php echo $kd_kegiatan; ?>
		    			</td>
					</tr>
					<tr>
						<td>Indikator Kinerja <a id="tambah_indikator_kegiatan" class="icon-plus-sign" href="javascript:void(0)"></a></td>
						<td id="indikator_frame_kegiatan" key="<?php echo (!empty($indikator_kegiatan))?$indikator_kegiatan->num_rows():'1'; ?>" colspan="2">
							<?php
								if (!empty($indikator_kegiatan)) {
									$i=0;
									foreach ($indikator_kegiatan->result() as $row) {
										$i++;
							?>
							<input type="hidden" name="id_indikator_kegiatan[<?php echo $i; ?>]" value="<?php echo $row->id; ?>">
							<div style="width: 100%; margin-top: 10px;">
								<div style="width: 100%;">
									<textarea class="common indikator_val" name="indikator_kinerja[<?php echo $i; ?>]" style="width:95%"><?php if(!empty($row->indikator)){echo $row->indikator;} ?></textarea>
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
											<td>Satuan</td>
											<td colspan="3"><input class="common indikator_val" name="satuan_target[<?php echo $i; ?>]" id="satuan_target" value="<?php echo $row->satuan_target; ?>"></td>
											<!-- <td colspan="3"><?php echo form_dropdown('satuan_target['. $i .']', $satuan, $row->satuan_target, 'class="common indikator_val" id="satuan_target"'); ?></td> -->
										</tr>
										<tr>
											<td rowspan="2">Kategori Indikator</td>
											<td colspan="3"><?php echo form_dropdown('status_target['. $i .']', $status_indikator, $row->status_indikator, 'class="common indikator_val" id="status_target"'); ?></td>
										</tr>
										<tr>
											<td colspan="3"><?php echo form_dropdown('kategori_target['. $i .']', $kategori_indikator, $row->kategori_indikator, 'class="common indikator_val" id="kategori_target"'); ?></td>
										</tr>
										<tr style="width:100%">
											<td>Target</td>
                                            <td><input style="width: 100%;" type="text" class="target" name="target[<?php echo $i; ?>]" value="<?php echo (!empty($row->target))?$row->target:''; ?>"></td>
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
									<textarea class="common indikator_val" name="indikator_kinerja[1]" style="width:95%"></textarea>
								</div>
								<div style="width: 100%;">
									<table class="table-common" width="100%">
										<tr>
											<td>Satuan</td>
											<td colspan="3"><input class="common indikator_val" name="satuan_target[1]" id="satuan_target"></td>
											<!-- <td colspan="3"><?php echo form_dropdown('satuan_target[1]', $satuan, '', 'class="common indikator_val" id="satuan_target"'); ?></td> -->
										</tr>
										<tr>
											<td rowspan="2">Kategori Indikator</td>
											<td colspan="3"><?php echo form_dropdown('status_target[1]', $status_indikator, '', 'class="common indikator_val" id="status_target"'); ?></td>
										</tr>
										<tr>
											<td colspan="3"><?php echo form_dropdown('kategori_target[1]', $kategori_indikator, '', 'class="common indikator_val" id="kategori_target"'); ?></td>
										</tr>
										<tr style="width:100%">
											<td>Target</td>
                                            <td><input style="width: 100%;" type="text" class="target" name="target[1]"></td>
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
						<td>Penanggung Jawab</td>
						<td colspan="2"><input class="common" name="penanggung_jawab" value="<?php echo (!empty($kegiatan->penanggung_jawab))?$kegiatan->penanggung_jawab:''; ?>"></td>
					</tr>
                    <tr >
                    	<?php 
                    		$a_k = array(1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0, 6 => 0, 7 => 0, 8 => 0, 9 => 0, 10 => 0, 11 => 0, 12 => 0);
                    		if (!empty($anggaran_rencana_aksi)) {
                    			foreach ($anggaran_rencana_aksi as $key => $value) {
                    				$a_k[$value->bulan] = $value->anggaran;
                    			}	
                    		}
                    	 ?>
					  <td colspan="3">
                      <div style="display:none;">
                      <table class="table-common" width="100%">
											<tr>
												<td colspan="4" align="center"><strong>ANGGARAN KAS KOMULATIF</strong></td>
											</tr>
	                      <tr bgcolor="#CCCCCC">
	                      	<td align="center" width="25%">Bulan 1</td>
	                        <td align="center" width="25%">Bulan 2</td>
	                        <td align="center" width="25%">Bulan 3</td>
	                        <td align="center" width="25%">Bulan 4</td>
	                      </tr>
											  <tr>
												<?php for($i=0;$i<4;$i++){ ?>
												<td>Rp. <input type="text" id="nominal_<?php echo ($i+1)?>" name="nominal_<?php echo ($i+1)?>" value="<?php echo $a_k[$i+1]; ?>"/>
										    </td>
										    <?php } ?>
											  </tr>
												<tr bgcolor="#CCCCCC">
	                      	<td align="center" width="25%">Bulan 5</td>
	                        <td align="center" width="25%">Bulan 6</td>
	                        <td align="center" width="25%">Bulan 7</td>
	                        <td align="center" width="25%">Bulan 8</td>
	                      </tr>
											  <tr>
												<?php for($i=4;$i<8;$i++){ ?>
												<td>Rp. <input type="text" id="nominal_<?php echo ($i+1)?>" name="nominal_<?php echo ($i+1)?>" value="<?php echo $a_k[$i+1]; ?>"/>
										    </td>
										    <?php } ?>
											  </tr>
												<tr bgcolor="#CCCCCC">
	                      	<td align="center" width="25%">Bulan 9</td>
	                        <td align="center" width="25%">Bulan 10</td>
	                        <td align="center" width="25%">Bulan 11</td>
	                        <td align="center" width="25%">Bulan 12</td>
	                      </tr>
											  <tr>
												<?php for($i=8;$i<12;$i++){ ?>
												<td>Rp. <input type="text" id="nominal_<?php echo ($i+1)?>" name="nominal_<?php echo ($i+1)?>" value="<?php echo $a_k[$i+1]; ?>"/>
										    </td>
										    <?php } ?>
											  </tr>


                      </table>
                      </div>
                      </td>
				 	</tr>

					<tr>
						<td colspan="3" bgcolor="#FFFFFF">
						<div>
						<table class="table-common" width="100%">
							<tr>
								<td colspan="7" align="center"><strong>RENCANA AKSI</strong></td>
							</tr>
							<tr>
								<td>
									<strong>Bulan</strong>
									<select class="" name="rcn_bulan">
										<?php 
										for ($i=1; $i < 13; $i++) {
											echo '<option value="'.$i.'">'.$i.'</option>';
										}
										$unic_for_rcn = uniqid();
										 ?>
									</select>
								</td>
								<td>
									<strong>Aksi</strong>
									<input type="text" name="rcn_aksi" value="">
								</td>
								<td>
									<strong>Target</strong>
									<input type="text" name="rcn_target" value="">
								</td>
								<td>
									<strong>Satuan</strong>
									<input type="text" name="rcn_satuan" value="">
								</td>
								<td>
									<strong>Bobot</strong>
									<input type="text" name="rcn_bobot" value="">
								</td>
								<td>
									<strong>Anggaran</strong>
									<input type="text" name="rcn_anggaran" value="">
								</td>
								<td style="padding-top: 28px !important;" width="5%">
									<button type="button" id="rcn_button" onclick="tambah_rcn_aksi(2)">Tambah</button>
								</td>
							</tr>
							<tr>
								<td colspan="7">&emsp;<input type="hidden" name="rcn_unicid" value="<?php echo $unic_for_rcn;?>"></td>
								<input type="hidden" name="rcn_id">
							</tr>
							<tr>
								<td colspan="7">
									<table>
										<thead>
											<tr>
												<th>Bulan</th>
												<th>Aksi</th>
												<th>Target</th>
												<th>Satuan</th>
												<th>Bobot (%)</th>
												<th>Anggaran</th>
												<th>Akumulasi</th>
												<th>Anggaran Kas</th>
												<th>Target Komulatif</th>
												<th>Bobot Komulatif</th>
												<th>Anggaran Komulatif</th>
												<th style="width: 40px;"></th>
											</tr>
										</thead>
										<tbody id="rcn_tabel">
											<?php if (!empty($rencana_aksi)) {
												$rcn_bln = 0;
												$tot_rcn_bobot_k = 0;
												$tot_rcn_anggaran_k = 0;
												if(!empty($kegiatan->id)){
													$id_progkeg = $kegiatan->id;
												}
												foreach ($rencana_aksi as $row_rcn ) {
													if ($rcn_bln != $row_rcn->bulan) {
														$rcn_bln = $row_rcn->bulan;
														$rcn_per_bulan = $this->db->query("SELECT COUNT(bulan) AS tot_row, SUM(bobot) AS sum_bot, SUM(anggaran) AS sum_ang FROM tx_dpa_rencana_aksi WHERE bulan = '".$rcn_bln."' AND (id_dpa_prog_keg = '".$id_progkeg."' OR id_dpa_prog_keg = '".$unic_for_rcn."')")->row();
														
														$tot_row_rcn = $rcn_per_bulan->tot_row;
														$rcn_akumulasi = $rcn_per_bulan->sum_bot;
														$rcn_anggaran = $rcn_per_bulan->sum_ang;
														
														$tot_rcn_bobot_k += $rcn_akumulasi;
														$tot_rcn_anggaran_k += $rcn_anggaran;

														echo "
															<tr>
																<td style='vertical-align: middle !important;' rowspan='".$tot_row_rcn."'>".$row_rcn->bulan."</td>
																<td>".$row_rcn->aksi."</td>
																<td>".$row_rcn->target."</td>
																<td>".$row_rcn->satuan."</td>
																<td>".$row_rcn->bobot."</td>
																<td>".Formatting::currency($row_rcn->anggaran)."</td>
																<td style='vertical-align: middle !important;' rowspan='".$tot_row_rcn."'>".$rcn_akumulasi."</td>
																<td style='vertical-align: middle !important;' rowspan='".$tot_row_rcn."'>".Formatting::currency($rcn_anggaran)."</td>
																<td>".$row_rcn->kumul."</td>
																<td style='vertical-align: middle !important;' rowspan='".$tot_row_rcn."'>".$tot_rcn_bobot_k."</td>
																<td style='vertical-align: middle !important;' rowspan='".$tot_row_rcn."'>".Formatting::currency($tot_rcn_anggaran_k)."</td>
																<td style='width: 40px;'>
																	<button type='button' onclick='edit_rcn(".$row_rcn->id.")'><i class='fa fa-pencil'></i></button>
																	<button type='button' onclick='hapus_rcn_aksi(2, ".$row_rcn->id.")'><i class='fa fa-remove'></i></button>
																</td>
															</tr>";
													}else{
														echo "
															<tr>
																<td>".$row_rcn->aksi."</td>
																<td>".$row_rcn->target."</td>
																<td>".$row_rcn->satuan."</td>
																<td>".$row_rcn->bobot."</td>
																<td>".Formatting::currency($row_rcn->anggaran)."</td>
																<td>".$row_rcn->kumul."</td>
																<td style='width: 40px;'>
																	<button type='button' onclick='edit_rcn(".$row_rcn->id.")'><i class='fa fa-pencil'></i></button>
																	<button type='button' onclick='hapus_rcn_aksi(2, ".$row_rcn->id.")'><i class='fa fa-remove'></i></button>
																</td>
															</tr>";
													}
													
													
													
												}
											} ?>
										</tbody>

									</table>
								</td>
							</tr>
						</table>
						</div>
						</td>
</tr>

					<tr>
						<?php $gTotal = 0;
							if(!empty($detil_kegiatan_1)){
							foreach ($detil_kegiatan_1 as $row) { $gTotal = $gTotal + $row->subtotal; }} ?>
						<td>Total Anggaran</td>
						<td colspan="3"><input type="text" name="total_belanja" id="nominal" value="<?php echo $gTotal; ?>" readonly> </td>

					</tr>
				</tbody>
			</table>
			<hr>
			<div class="submit_link">
				<input id="simpan" type="button" value="Simpan" style="background-color: #3c8dbc !important; border-color: #367fa9 !important; color: #fff !important;">
			</div>
			<hr>
<div  <?php if(empty($kegiatan->id)){echo "style='display: none;'";} ?>>
			<table>
				<tr>
					<td colspan="2" align="center"><strong>URAIAN BELANJA</strong></td>
				</tr>
				<input type="hidden" id="inIndex_1" name="inIndex_1" value="1"/>
				<input type="hidden" id="tahun_1" name="tahun_1" value=<?php if(!empty($detil_kegiatan_1)){echo $detil_kegiatan_1[0]->tahun;} ?> />
				<input type="hidden" id="isEdit_1" value="0"/>
				<tr>
						<td width="20%">Kelompok Belanja</td>
						<td width="80%" id="combo_jenis_belanja_1">
							<?php echo $cb_jenis_belanja_1; ?>

						</td>
				</tr>
				<tr>
						<td>Jenis Belanja</td>
						<td id="combo_kategori_1">
							<?php echo $cb_kategori_belanja_1; ?>

						</td>
				</tr>
				<tr>
					<td>Obyek Belanja</td>
					<td id="combo_subkategori_1">
							<?php echo $cb_subkategori_belanja_1; ?>
					</td>
				</tr>
				<tr >
					<td>Rincian Obyek</td>
					<td id="combo_belanja_1">
							 <?php echo $cb_belanja_1; ?>
					</td>
				</tr>
				<tr>
					<td>Rincian Belanja</td>
					<td>
								<input type="text" id="uraian_1" name="uraian_1" class="common" value="<?php if(!empty($uraian_1)){echo $uraian_1;} ?>" />
					</td>
				</tr>
				<tr>
					<td>Sumber Dana </td>
					<td id="combo_sumberdana_1">
						<?php echo form_dropdown('sumberdana_1', $sumber_dana, NULL, 'data-placeholder="Pilih Sumber Dana" class="common chosen-select" id="sumberdana_1" name="sumberdana_1"'); ?>
						 <!-- <select id="sumberdana_1" name="sumberdana_1" class="common" >
								<option value="1"  >DAU/PAD</option>
								<option value="2"  >DAU Infrastruktur</option>
								<option value="3"  >DAK</option>
								<option value="4"  >BKK Provisi</option>
								<option value="5"  >BKK Badung</option>

						 </select> -->
				</tr>
				<tr>
					<td>Sub Rincian Belanja</td>
					<td>
								<input type="text" id="det_uraian_1" name="det_uraian_1" class="common" value="<?php if(!empty($deturaian_1)){echo $deturaian_1;} ?>" />
					</td>
				</tr>
				<tr>
					<td>Volume</td>
					<td><input class="common" type="text" name="volume_1" id="volume_1" value="<?php if(!empty($volume_1)){echo $volume_1;} ?>"/></td>
				</tr>
				<tr>
					<td>Satuan</td>
					<td>
						<input class="common" type="text" name="satuan_1" id="satuan_1" />
						<!-- <?php echo form_dropdown('satuan_1', $satuan, NULL, 'class="common " id="satuan_1" name="satuan_1"'); ?> -->
					</td>
				</tr>
				<tr>
					<td>Nominal Satuan</td>
					<td><input class="common" type="text" name="nominal_satuan_1" id="nominal_satuan_1" value="<?php if(!empty($nominal_satuan_1)){echo $nominal_satuan_1;} ?>"/></td>
				</tr>
			</table>

			<input type="hidden" id="id_belanja_1" value="">
			<div class="alert alert-warning alert-white rounded" id="cusAlert_1" role="alert" style="display:none;">
				<div class="icon">
						<i class="fa fa-warning"></i>
				</div>
				<font color="#d68000" size="4px"> <strong >Perhatian..!! </strong>
					<span id="pesan_1"></span>
				</font>
			</div>

			<div class="submit_link">
				<input type='button' id="tambahjnsbelanja" onclick="save_belanja(1, 'jns');" style="cursor:pointer;" value="+ Kelompok Belanja">
      			<input type='button'  id="tambahkatbelanja" onclick="save_belanja(1, 'kat');" style="cursor:pointer;" value='+ Jenis Belanja'>
		      	<input type='button'  id="tambahsubkatbelanja" onclick="save_belanja(1, 'subkat');" style="cursor:pointer;" value='+ Obyek Belanja'>
		      	<input type='button'  id="tambahbelanja" onclick="save_belanja(1, 'belanja');" style="cursor:pointer;" value='+ Rincian Obyek'>
		      	<input type='button'  id="tambahuraian" onclick="save_belanja(1, 'uraian');" style="cursor:pointer;" value='+ Rincian Belanja'>
		      	<input type='button'  id="tambahdeturaian" onclick="save_belanja(1, 'deturaian');" style="cursor:pointer;" value='+ Sub Rincian Belanja'>
			</div>
			<br>
			<table id="listbelanja_1">
				<thead>
				<tr>
					<th>No</th>
					<th>Kelompok Belanja</th>
					<th>Jenis Belanja</th>
					<th>Obyek Belanja</th>
					<th>Rincian Obyek</th>
					<th>Rincian Belanja</th>
					<th>Sumber Dana</th>
					<th>Sub Rincian Belanja</th>
					<th>Volume</th>
					<th>Satuan</th>
					<th>Nominal</th>
					<th>Sub Total</th>
					<th colspan="2">Action</th>
				</tr>
				</thead>
				<tbody id="list_tahun_1">
				<?php if(!empty($detil_kegiatan_1)){
					

					$gIndex_1 = 1;
					$total = 0;
					foreach ($detil_kegiatan_1 as $row) {
							$vol = Formatting::currency($row->volume);
							$nom = Formatting::currency($row->nominal_satuan);
							$sub = Formatting::currency($row->subtotal);
							$total += $row->subtotal;


				?>
				<tr id="<?php echo $gIndex_1 ?>">
					<td> <?php echo $gIndex_1 ?> </td>
					<td> <?php echo $row->kode_jenis_belanja.". ".$row->jenis ?> </td>
					<td> <?php echo $row->kode_kategori_belanja.". ".$row->kategori ?> </td>
					<td> <?php echo $row->kode_sub_kategori_belanja.". ".$row->subkategori ?> </td>
					<td> <?php echo $row->kode_belanja.". ".$row->belanja ?> </td>
					<td> <?php echo $row->uraian_belanja ?> </td>
					<td> <?php echo $row->sumberDana ?> </td>
					<td> <?php echo $row->detil_uraian_belanja ?> </td>
					<td> <?php echo $vol; ?> </td>
					<td> <?php echo $row->satuan ?> </td>
					<td> <?php echo $nom; ?> </td>
					<td> <?php echo $sub; ?> </td>
					<td> <span id="ubahrowng" class="icon-pencil" onclick="ubahrowng(<?php echo $row->id; ?>, 1)" style="cursor:pointer;" value="ubah" title="Ubah Belanja"></span></td>
					<td> <span id="hapusrowng" class="icon-remove" onclick="hapusrowng(<?php echo $row->id; ?>, 1)" style="cursor:pointer;" value="hapus" title="Hapus Belanja"></span></td>

					<!-- <td style="display:none;"><input type="text" name="kd_sumber_dana_1[<?php echo $gIndex_1 ?>]" id="kd_sumber_dana_1[<?php echo $gIndex_1 ?>]" value="<?php echo $row->kode_sumber_dana ?>" /> </td>
					<td style="display:none;"><input type="text" name="r_kd_jenis_belanja_1[<?php echo $gIndex_1 ?>]" id="r_kd_jenis_belanja_1[<?php echo $gIndex_1 ?>]" value="<?php echo $row->kode_jenis_belanja ?>" /> </td>
					<td style="display:none;"><input type="text" name="r_kd_kategori_belanja_1[<?php echo $gIndex_1 ?>]" id="r_kd_kategori_belanja_1[<?php echo $gIndex_1 ?>]" value="<?php echo $row->kode_kategori_belanja ?>" /> </td>
					<td style="display:none;"><input type="text" name="r_kd_subkategori_belanja_1[<?php echo $gIndex_1 ?>]" id="r_kd_subkategori_belanja_1[<?php echo $gIndex_1 ?>]" value="<?php echo $row->kode_sub_kategori_belanja ?>" /> </td>
					<td style="display:none;"><input type="text" name="r_kd_belanja_1[<?php echo $gIndex_1 ?>]" id="r_kd_belanja_1[<?php echo $gIndex_1 ?>]" value="<?php echo $row->kode_belanja ?>" /> </td>
					<td style="display:none;"><input type="text" name="r_uraian_1[<?php echo $gIndex_1 ?>]" id="r_uraian_1[<?php echo $gIndex_1 ?>]" value="<?php echo $row->uraian_belanja ?>" /> </td>
					<td style="display:none;"><input type="text" name="r_det_uraian_1[<?php echo $gIndex_1 ?>]" id="r_det_uraian_1[<?php echo $gIndex_1 ?>]" value="<?php echo $row->detil_uraian_belanja ?>" /> </td>
					<td style="display:none;"><input type="text" name="r_volume_1[<?php echo $gIndex_1 ?>]" id="r_volume_1[<?php echo $gIndex_1 ?>]" value="<?php echo str_replace('.','',$vol); ?>" /> </td>
					<td style="display:none;"><input type="text" name="r_satuan_1[<?php echo $gIndex_1 ?>]" id="r_satuan_1[<?php echo $gIndex_1 ?>]" value="<?php echo $row->satuan ?>" /> </td>
					<td style="display:none;"><input type="text" name="r_nominal_satuan_1[<?php echo $gIndex_1 ?>]" id="r_nominal_satuan_1[<?php echo $gIndex_1 ?>]" value="<?php echo str_replace('.','',$nom); ?>" /> </td>
					<td style="display:none;"><input type="text" name="r_subtotal_1[<?php echo $gIndex_1 ?>]" id="r_subtotal_1[<?php echo $gIndex_1 ?>]" value="<?php echo str_replace('.','',$sub); ?>" /> </td> -->
				</tr>

				<?php $gIndex_1++;
					echo "<script> document.getElementById('inIndex_1').value = $gIndex_1; </script> ";
					echo "<script type='text/javascript'>$('input[name=nominal]').autoNumeric('set', ".$total.");</script>";
				}} ?>
			</tbody>
			</table>
		</form>
	</div>
</div>
	<footer>
	</footer>
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
					<td>Satuan</td>
					<td colspan="3"><input class="common indikator_val" name="satuan_target[1]" id="satuan_target"></td>
					<!-- <td colspan="3"><?php echo form_dropdown('satuan_target[1]', $satuan, '', 'class="common indikator_val" id="satuan_target"'); ?></td> -->
				</tr>
				<tr>
					<td rowspan="2">Kategori Indikator</td>
					<td colspan="3"><?php echo form_dropdown('status_target[1]', $status_indikator, '', 'class="common indikator_val" id="status_target"'); ?></td>
				</tr>
				<tr>
					<td colspan="3"><?php echo form_dropdown('kategori_target[1]', $kategori_indikator, '', 'class="common indikator_val" id="kategori_target"'); ?></td>
				</tr>
				<tr style="width:100%">
					<td>Target</td>
					<td><input style="width: 100%;" type="text" class="target" id="target" name="target[1]"></td>
                </tr>
			</table>
		</div>
	</div>
</div>
<script src="<?php echo base_url('assets/renja/createbelanja_tahun1.js');?>"></script>
<script src="<?php echo base_url('assets/renja/custom-alert.js');?>"></script>
<link href="<?php echo base_url('assets/renja/custom-alert.css') ?>" rel="stylesheet" type="text/css" />

<script>
  function errorMessage_1(clue) {
    var jenis_belanja = $('#cb_jenis_belanja_1').val();
    var kategori_belanja = $('#cb_kategori_belanja_1').val();
    var subkategori_belanja = $('#cb_subkategori_belanja_1').val();
    var kode_belanja = $('#cb_belanja_1').val();
    var uraian = $('#uraian_1').val();
    var det_uraian = $('#det_uraian_1').val();
    var volume = $('#volume_1').val();
    var satuan = $('#satuan_1').val();
    var nominal = $('#nominal_satuan_1').val();
    var sumberdana = $('#sumberdana_1').val();
    eliminationName(jenis_belanja, kategori_belanja, subkategori_belanja, kode_belanja, uraian, det_uraian, volume, satuan, nominal, sumberdana, clue, '#cusAlert_1', 'pesan_1');
  }


  function jenis_belanjanya_1(p_nama, p_jenis) {
    $.ajax({
      type: "POST",
      url: '<?php echo site_url("common/edit_jenis_belanja"); ?>',
      data: {nama: p_nama, jenis: p_jenis},
      success: function(msg){
        $("#combo_jenis_belanja_1").html(msg);
        prepare_chosen();
      }
    });
  }
  function kategori_belanjanya_1(p_nama, p_jenis, p_kategori) {
    $.ajax({
      type: "POST",
      url: '<?php echo site_url("common/edit_kategori_belanja"); ?>',
      data: {nama: p_nama, jenis: p_jenis, kategori: p_kategori},
      success: function(msg){
        $("#combo_kategori_1").html(msg);
        prepare_chosen();
      }
    });
  }
  function sub_belanjanya_1(p_nama, p_jenis, p_kategori, p_sub) {
    $.ajax({
      type: "POST",
      url: '<?php echo site_url("common/edit_sub_belanja"); ?>',
      data: {nama: p_nama, jenis: p_jenis, kategori: p_kategori, sub: p_sub},
      success: function(msg){
        $("#combo_subkategori_1").html(msg);
        prepare_chosen();
      }
    });
  }
  function belanja_belanjanya_1(p_nama, p_jenis, p_kategori, p_sub, p_belanja) {
    $.ajax({
      type: "POST",
      url: '<?php echo site_url("common/edit_belanja_belanja"); ?>',
      data: {nama: p_nama, jenis: p_jenis, kategori: p_kategori, sub: p_sub, belanja: p_belanja},
      success: function(msg){
        $("#combo_belanja_1").html(msg);
        prepare_chosen();
      }
    });
  }
	function sumber_dananya_1(p_nama, p_id) {
    $.ajax({
      type: "POST",
      url: '<?php echo site_url("common/edit_sumber_dana"); ?>',
      data: {nama: p_nama, id: p_id},
      success: function(msg){
        $("#combo_sumberdana_1").html(msg);
        prepare_chosen();
      }
    });
  }

  function edit_rcn(id) {
		$("#rcn_tabel").html("<tr><td colspan='12' align='center'> <i class='fa fa-refresh fa-spin'></i> Mohon menunggu ...</td></tr>");
		var id_dpa = $("input[name=id_kegiatan]").val();
		var uniq_id = $("input[name=rcn_unicid]").val();
		$.ajax({
			type: "POST",
			url: '<?php echo site_url("dpa/one_rencana_aksi"); ?>',
			dataType: "json",
			data: {id_dpa : id_dpa, id_dpa_prog_keg: uniq_id, id: id},
			success: function(data){
				$("input[name=rcn_id]").val(data.id);
				$("select[name=rcn_bulan]").val(data.bulan);
				$("input[name=rcn_aksi]").val(data.aksi);
				$("input[name=rcn_target").autoNumeric('set', data.target);
				$("input[name=rcn_satuan]").val(data.satuan);
				$("input[name=rcn_bobot]").autoNumeric('set', data.bobot);
				$("input[name=rcn_anggaran]").autoNumeric('set', data.anggaran);
			}
		});
		$.ajax({
			type: "POST",
			url: '<?php echo site_url("dpa/edit_rencana_aksi"); ?>',
			dataType: "html",
			data: {id_dpa : id_dpa, id_dpa_prog_keg: uniq_id, id: id, kd_status : 2},
			success: function(data){
				$("#rcn_tabel").html(data);
				$.ajax({
					type: "POST",
					url: '<?php echo site_url("dpa/get_sum_anggaran_per_bulan"); ?>',
					dataType: "json",
					data: {id_dpa : id_dpa, id_dpa_prog_keg: uniq_id},
					success: function(data_rcn){
						// console.log(data_rcn);
						for (var i = data_rcn.length - 1; i >= 0; i--) {
							data_rcn[i];
							$('input[name=nominal_'+data_rcn[i].bulan+']').autoNumeric('set', data_rcn[i].anggaran);
						}
					}
				});
			}
		});
		
	}




	function save_belanja(tahun, clue){
		// var id_renstra = $('input[name="id_renstra"]').val();
		var is_tahun = "";
		var tahun_skr = "";

		var id_kegiatan = $('input[name="id_kegiatan"]').val();
		var id_belanja = $('#id_belanja_'+tahun).val();

		var kd_urusan = $('input[name="kd_urusan"]').val();
		var kd_bidang = $('input[name="kd_bidang"]').val();
		var kd_program = $('input[name="kd_program"]').val();
		var kd_kegiatan = $('#kd_kegiatan').val();

		if (tahun == 1) {
			var lokasi = $('input[name=lokasi]').val();
			is_tahun = 1;
			tahun_skr = $('input[name=tahun]').val();
		}else{
			var lokasi = $('input[name=lokasi_thndpn]').val();
			is_tahun = 0;
			tahun_skr = parseInt($('input[name=tahun]').val()) + 1;
		}
		
		var uraian_kegiatan = "";
		// $('#uraian_kegiatan_'+tahun).val();

		var jenis = $('#cb_jenis_belanja_'+tahun).val();
		var kategori = $('#cb_kategori_belanja_'+tahun).val();
		var subkategori = $('#cb_subkategori_belanja_'+tahun).val();
		var belanja = $('#cb_belanja_'+tahun).val();
		var uraian = $('#uraian_'+tahun).val();
		var sumberdana = $('#sumberdana_'+tahun).val();
		var deturaian = $('#det_uraian_'+tahun).val();
		var volume = $('#volume_'+tahun).autoNumeric('get');
		var satuan = $('#satuan_'+tahun).val();
		var nomsatuan = $('#nominal_satuan_'+tahun).autoNumeric('get');

		var subtotal = parseInt(nomsatuan) * parseInt(volume);

// var status = true;
		var status = eliminationName(jenis, kategori, subkategori, belanja, uraian, deturaian, volume, satuan, nomsatuan, sumberdana, clue, '#cusAlert_'+tahun, 'pesan_'+tahun);

		if (status) {
			$.ajax({
			    type: "POST",
			    url: '<?php echo site_url("dpa/belanja_kegiatan_save"); ?>',
			    dataType: 'html',
			    data: { 
			    // id_renstra : id_renstra,
			    is_tahun_sekarang : is_tahun,
			    tahun : tahun_skr,
			    id_belanja : id_belanja,
			    kode_urusan : kd_urusan,
			    kode_bidang : kd_bidang,
			    kode_program : kd_program,
			    kode_kegiatan : kd_kegiatan,
			    id_keg : id_kegiatan,
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

  function ubahrowng(id_belanja, tahun){
    // var tahun = 1;
    var is_tahun = "";
    var tahun_skr = "";
    if (tahun == 1) {
		is_tahun = 1;
		tahun_skr = $('input[name=tahun]').val();
	}else{
		is_tahun = 0;
		tahun_skr = parseInt($('input[name=tahun]').val()) + 1;
	}

    var check = $('#id_belanja_'+tahun).val();
    var id_kegiatan = $('input[name="id_kegiatan"]').val();

    if (check == '' || check == null) {
      $('#id_belanja_'+tahun).val(id_belanja);

      $.ajax({
          type: "POST",
          url: '<?php echo site_url("dpa/belanja_kegiatan_edit"); ?>',
          dataType: 'json',
          data: {
          id_kegiatan : id_kegiatan,
          id_belanja : id_belanja,
          tahun : tahun,
          ta : tahun_skr,
          is_tahun : is_tahun
          },
          success: function(msg){
            $('#list_tahun_'+tahun).html('');
            
            var no = 1;
            var total = 0;
            for (var i = 0; i < msg.list.length; i++) {
              
              var row = '<tr>';
              row += '<td>'+no+'</td>';
              row += '<td>'+msg.list[i].kode_jenis_belanja+'. '+msg.list[i].jenis_belanja+'</td>';
              row += '<td>'+msg.list[i].kode_kategori_belanja+'. '+msg.list[i].kategori_belanja+'</td>';
              row += '<td>'+msg.list[i].kode_sub_kategori_belanja+'. '+msg.list[i].sub_kategori_belanja+'</td>';
              row += '<td>'+msg.list[i].kode_belanja+'. '+msg.list[i].belanja+'</td>';
              row += '<td>'+msg.list[i].uraian_belanja+'</td>';
              row += '<td>'+msg.list[i].Sumber_dana+'</td>';
              row += '<td>'+msg.list[i].detil_uraian_belanja+'</td>';
              row += '<td>'+parseInt(msg.list[i].volume).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")+'</td>';
              row += '<td>'+msg.list[i].satuan+'</td>';
              row += '<td>'+parseInt(msg.list[i].nominal_satuan).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")+'</td>';
              row += '<td>'+parseInt(msg.list[i].subtotal).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")+'</td>';
              row += "<td><span id='ubahrowng' class='icon-pencil' onclick='ubahrowng("+msg.list[i].id+","+tahun+")' style='cursor:pointer' title='Ubah Belanja'></span></td>";
              row += "<td><span id='hapusrowng' class='icon-remove' onclick='hapusrowng("+msg.list[i].id+","+tahun+")' style='cursor:pointer' title='Hapus Belanja'></span></td>";
              row += '</tr>';
              $('#list_tahun_'+tahun).append(row);
              no++;
              total += parseInt(msg.list[i].subtotal);
            }
            var jenis = msg.edit.kode_jenis_belanja;
            var kategori = msg.edit.kode_kategori_belanja;
            var sub = msg.edit.kode_sub_kategori_belanja;
            var belanja = msg.edit.kode_belanja;
            var sumber_dana = msg.edit.kode_sumber_dana;

            if (tahun == 1) {
	            jenis_belanjanya_1("cb_jenis_belanja_1", jenis);
	            kategori_belanjanya_1("cb_kategori_belanja_1", jenis, kategori);
	            sub_belanjanya_1("cb_subkategori_belanja_1", jenis, kategori, sub);
	            belanja_belanjanya_1("cb_belanja_1", jenis, kategori, sub, belanja);
	            sumber_dananya_1("sumberdana_1", sumber_dana);
	            $('#uraian_1').val(msg.edit.uraian_belanja);
	            $('#det_uraian_1').val(msg.edit.detil_uraian_belanja);
	            $('#volume_1').autoNumeric('set', msg.edit.volume);
	            $('#satuan_1').val(msg.edit.satuan);
	            $('#nominal_satuan_1').autoNumeric('set', msg.edit.nominal_satuan);
	            $('#nominal').autoNumeric('set', total);
        	}else{
        		jenis_belanjanya_2("cb_jenis_belanja_2", jenis);
	            kategori_belanjanya_2("cb_kategori_belanja_2", jenis, kategori);
	            sub_belanjanya_2("cb_subkategori_belanja_2", jenis, kategori, sub);
	            belanja_belanjanya_2("cb_belanja_2", jenis, kategori, sub, belanja);
	            sumber_dananya_2("sumberdana_2", sumber_dana);
	            $('#uraian_2').val(msg.edit.uraian_belanja);
	            $('#det_uraian_2').val(msg.edit.detil_uraian_belanja);
	            $('#volume_2').autoNumeric('set', msg.edit.volume);
	            $('#satuan_2').val(msg.edit.satuan);
	            $('#nominal_satuan_2').autoNumeric('set', msg.edit.nominal_satuan);
	            $('#nominal_thndpn').autoNumeric('set', total);
        	}
        	$('#nominal').autoNumeric('set', total);
            
          }
      });      
    }
  }

  function hapusrowng(id_belanja, tahun){
    var is_tahun = "";
    var tahun_skr = "";
    if (tahun == 1) {
		is_tahun = 1;
		tahun_skr = $('input[name=tahun]').val();
	}else{
		is_tahun = 0;
		tahun_skr = parseInt($('input[name=tahun]').val()) + 1;
	}
    var id_kegiatan = $('input[name="id_kegiatan"]').val();

    $.ajax({
          type: "POST",
          url: '<?php echo site_url("dpa/belanja_kegiatan_hapus"); ?>',
          dataType: 'json',
          data: {
          id_kegiatan : id_kegiatan,
          id_belanja : id_belanja,
          tahun : tahun,
          ta : tahun_skr,
          is_tahun : is_tahun
          },
          success: function(msg){
            $('#list_tahun_'+tahun).html('');
            var no = 1;
            var total = 0;
            for (var i = 0; i < msg.list.length; i++) {
              
              var row = '<tr>';
              row += '<td>'+no+'</td>';
              row += '<td>'+msg.list[i].kode_jenis_belanja+'. '+msg.list[i].jenis_belanja+'</td>';
              row += '<td>'+msg.list[i].kode_kategori_belanja+'. '+msg.list[i].kategori_belanja+'</td>';
              row += '<td>'+msg.list[i].kode_sub_kategori_belanja+'. '+msg.list[i].sub_kategori_belanja+'</td>';
              row += '<td>'+msg.list[i].kode_belanja+'. '+msg.list[i].belanja+'</td>';
              row += '<td>'+msg.list[i].uraian_belanja+'</td>';
              row += '<td>'+msg.list[i].Sumber_dana+'</td>';
              row += '<td>'+msg.list[i].detil_uraian_belanja+'</td>';
              row += '<td>'+parseInt(msg.list[i].volume).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")+'</td>';
              row += '<td>'+msg.list[i].satuan+'</td>';
              row += '<td>'+parseInt(msg.list[i].nominal_satuan).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")+'</td>';
              row += '<td>'+parseInt(msg.list[i].subtotal).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")+'</td>';
              row += "<td><span id='ubahrowng' class='icon-pencil' onclick='ubahrowng("+msg.list[i].id+","+tahun+")' style='cursor:pointer' title='Ubah Belanja'></span></td>";
              row += "<td><span id='hapusrowng' class='icon-remove' onclick='hapusrowng("+msg.list[i].id+","+tahun+")' style='cursor:pointer' title='Hapus Belanja'></span></td>";
              row += '</tr>';
              $('#list_tahun_'+tahun).append(row);
              no++;
              total += parseInt(msg.list[i].subtotal);
            }
            if (tahun == 1) {
				$('#nominal').autoNumeric('set', total);
			}else{
				$('#nominal_thndpn').autoNumeric('set', total);
			}
			$('#nominal').autoNumeric('set', total);
            
          }
      });
  }


</script>
