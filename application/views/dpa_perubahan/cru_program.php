<script type="text/javascript">
	$('#nominal').autoNumeric(numOptions);

	$('input[name=rcn_target]').autoNumeric(numOptionsNotRound);
	$('input[name=rcn_bobot]').autoNumeric(numOptionsNotRound);
	prepare_chosen();
	$(document).on("change", "#id_prog_rpjmd", function () {
		$.ajax({
			type: "POST",
			url: '<?php echo site_url("common/table_indikator_program_rpjmd"); ?>',
			data: {id_program: $(this).val()},
			success: function(msg){
				$("#indikator-rpjmd").html(msg);
			}
		});
		$.ajax({
			type: "POST",
			url: '<?php echo site_url("common/cmb_program_rpjmd"); ?>',
			dataType : 'json',
			data: {id_program: $(this).val()},
			success: function(msg){
				$("#cmb-urusan").html(msg.kd_urusan);
				prepare_chosen();
				$("#cmb-bidang").html(msg.kd_bidang);
				prepare_chosen();
				$("#cmb-program").html(msg.kd_program);
				prepare_chosen();
			}
		});
		var str = $(this).find('option:selected').text();
		var nama_program = str.substring(str.indexOf(".")+2);
		$("#nama_prog_or_keg").val(nama_program);
	});

	prepare_chosen();
	$(document).on("change", "#kd_urusan", function () {
		$.ajax({
			type: "POST",
			url: '<?php echo site_url("common/cmb_bidang"); ?>',
			data: {kd_urusan: $(this).val()},
			success: function(msg){
				$("#cmb-bidang").html(msg);
				$("#kd_program").val("");
				$("#nama_prog_or_keg").val("");
        		$("#kd_program").trigger("chosen:updated");
				prepare_chosen();
			}
		});
	});

	$(document).on("change", "#kd_bidang", function () {
		$.ajax({
			type: "POST",
			url: '<?php echo site_url("common/cmb_program"); ?>',
			data: {kd_urusan:$("#kd_urusan").val(), kd_bidang: $(this).val()},
			success: function(msg){
				$("#cmb-program").html(msg);
				prepare_chosen();
			}
		});
	});

	$(document).on("change", "#kd_program", function () {
		var str = $(this).find('option:selected').text();
		var nama_program = str.substring(str.indexOf(".")+2);
		$("#nama_prog_or_keg").val(nama_program);
	});

	//agar validation tetap aktif untuk chosen dropdown
	$('form#program').validate({
		rules: {
			kd_urusan : "required",
			kd_bidang : "required",
			kd_program : "required"
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
		$("#indikator_box_program select#satuan_target").attr("name", satuan_target);
		$("#indikator_box_program select#status_target").attr("name", status_target);
		$("#indikator_box_program select#kategori_target").attr("name", kategori_target);
		$("#indikator_frame_program").append($("#indikator_box_program").html());
	});

	$(document).on("click", ".hapus_indikator_program", function(){
		$(this).parent().parent().remove();
	});
</script>

<div style="width: 990px">
	<header>
		<h3 style="padding:20px">
	<?php
		if (!empty($program)){
		    echo "Edit Data Program";
		} else{
		    echo "Input Data Program";
		}
	?>
	</h3>
	</header>
	<div class="module_content">
		<form action="<?php echo site_url('dpa_perubahan/save_program_dpa');?>" method="POST" name="program" id="program" accept-charset="UTF-8" enctype="multipart/form-data" >
        <input type="hidden" name="id_skpd" value="<?php echo $this->session->userdata("id_skpd"); ?>" />
        <input type="hidden" name="tahun" value="<?php echo $this->m_settings->get_tahun_anggaran(); ?>" />
			<input type="hidden" name="id_program" value="<?php if(!empty($program->id)){echo $program->id;} ?>" />
			<input type="hidden" id="nama_prog_or_keg" name="nama_prog_or_keg" value="<?php echo (!empty($program->nama_prog_or_keg))?$program->nama_prog_or_keg:''; ?>" />

			<table class="fcari" width="100%">
				<tbody>
					<tr>
						<td width="15%">SKPD</td>
						<td width="85%"><?php echo $skpd->nama_skpd; ?></td>
					</tr>
					<tr>
						<td>Program RPJMD</td>
						<td>
							<?php echo $id_prog_rpjmd; ?>
							<p id="indikator-rpjmd"><?php
							if (!empty($program->id)) {
								// $dataz['indikator_program_rpjmd'] = $indik_prog_rpjmd->result();
								$dataz['program_rpjmd'] = $this->m_rpjmd_trx->get_one_program_rpjmd_for_me($program->id_prog_rpjmd);
								$dataz['indikator_program_rpjmd'] = $indik_prog_rpjmd;
								$dataz['pagu_sisa'] = $this->m_rpjmd_trx->get_sisa_pagu_rpjmd($program->id_prog_rpjmd);
								$this->load->view('renstra/indikator_program_rpjmd', $dataz);
							} ?></p>

		    		</td>
					</tr>
					<tr>
						<td>Urusan</td>
						<td id="cmb-urusan">
							<?php echo $kd_urusan; ?>
		    			</td>
					</tr>
					<tr>
						<td>Bidang Urusan</td>
						<td id="cmb-bidang">
							<?php echo $kd_bidang; ?>
						</td>
					</tr>
					<tr>
						<td>Program</td>
						<td id="cmb-program">
							<?php echo $kd_program; ?>
						</td>
					</tr>
					<tr>
						<td>Indikator Kinerja <a id="tambah_indikator_program" class="icon-plus-sign" href="javascript:void(0)"></a></td>
						<td id="indikator_frame_program" key="<?php echo (!empty($indikator_program))?$indikator_program->num_rows():'1'; ?>">
							<?php
								if (!empty($indikator_program)) {



									$i=0;
									foreach ($indikator_program->result() as $row) {

										$i++;
							?>
							<input type="hidden" name="id_indikator_program[<?php echo $i; ?>]" value="<?php echo $row->id; ?>">
							<div style="margin-top:5px">
							  <div style="width:100%">
									<textarea class="common indikator_val" name="indikator_kinerja[<?php echo $i; ?>]" style="width:95%"><?php if(!empty($row->indikator)){echo $row->indikator;} ?></textarea>

							<?php
								if ($i != 1) {
							?>
								<a class="icon-remove hapus_indikator_program" href="javascript:void(0)" style="vertical-align: top;"></a>
							<?php
								}
							?>
								</div>
								<div style="width: 100%;">
									<table class="table-common" width="100%">
										<tr style="width:100%">
											<td>Satuan</td>
											<td colspan="3"><input class="common indikator_val" name="satuan_target[<?php echo $i; ?>]" id="satuan_target" value="<?php echo $row->satuan_target; ?>"></td>
											<!-- <td colspan="3"><?php echo form_dropdown('satuan_target['. $i .']', $satuan,  $row->satuan_target, 'class="common indikator_val" id="satuan_target"'); ?></td> -->
										</tr>
										<tr>
											<td rowspan="2">Kategori Indikator</td>
											<td colspan="3"><?php echo form_dropdown('status_target['. $i .']', $status_indikator, $row->status_indikator, 'class="common indikator_val" id="status_target"'); ?></td>
										</tr>
										<tr>
											<td colspan="3"><?php echo form_dropdown('kategori_target['. $i .']', $kategori_indikator , $row->kategori_indikator,  'class="common indikator_val" id="kategori_target"'); ?></td>
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
									<tr style="width:100%">
											<td>Satuan</td>
											<td colspan="3"><input class="common indikator_val" name="satuan_target[1]" id="satuan_target"></td>
											<!-- <td colspan="3"><?php echo form_dropdown('satuan_target[1]', $satuan, '', 'class="common indikator_val" id="satuan_target"'); ?></td> -->
										</tr>
										<tr>
											<td rowspan="2">Kategori Indikator</td>
											<td colspan="3"><?php echo form_dropdown('status_target[1]', $status_indikator, '',  'class="common indikator_val" id="status_target"'); ?></td>
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
						<td colspan="2"><hr></td>
					</tr>
					<tr>
						<td colspan="2">
							<!-- rencana aksi -->
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
									<td style="padding-top: 28px !important;" width="5%">
										<button type="button" id="rcn_button" onclick="tambah_rcn_aksi(1)">Tambah</button>
									</td>
								</tr>
								<tr>
									<td colspan="6">&emsp;<input type="hidden" name="rcn_unicid" value="<?php echo $unic_for_rcn;?>"></td>
									<input type="hidden" name="rcn_id">
								</tr>
								<tr>
									<td colspan="6">
										<table>
											<thead>
												<tr>
													<th>Bulan</th>
													<th>Aksi</th>
													<th>Target</th>
													<th>Satuan</th>
													<th>Bobot (%)</th>
													<!-- <th>Anggaran</th> -->
													<th>Akumulasi</th>
													<!-- <th>Anggaran Kas</th> -->
													<th>Target Komulatif</th>
													<th>Bobot Komulatif</th>
													<!-- <th>Anggaran Komulatif</th> -->
													<th style="width: 40px;"></th>
												</tr>
											</thead>
											<tbody id="rcn_tabel">
												<?php if (!empty($rencana_aksi)) {
													$rcn_bln = 0;
													$tot_rcn_bobot_k = 0;
													$tot_rcn_anggaran_k = 0;
													if(!empty($program->id)){
														$id_progkeg = $program->id;
													}
													foreach ($rencana_aksi as $row_rcn ) {
														if ($rcn_bln != $row_rcn->bulan) {
															$rcn_bln = $row_rcn->bulan;
															$rcn_per_bulan = $this->db->query("SELECT COUNT(bulan) AS tot_row, SUM(bobot) AS sum_bot, SUM(anggaran) AS sum_ang FROM tx_dpa_rencana_aksi_perubahan WHERE bulan = '".$rcn_bln."' AND (id_dpa_prog_keg = '".$id_progkeg."' OR id_dpa_prog_keg = '".$unic_for_rcn."')")->row();
															
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
																	
																	<td style='vertical-align: middle !important;' rowspan='".$tot_row_rcn."'>".$rcn_akumulasi."</td>
																	
																	<td>".$row_rcn->kumul."</td>
																	<td style='vertical-align: middle !important;' rowspan='".$tot_row_rcn."'>".$tot_rcn_bobot_k."</td>
																	
																	<td style='width: 40px;'>
																		<button type='button' onclick='edit_rcn(".$row_rcn->id.")'><i class='fa fa-pencil'></i></button>
																		<button type='button' onclick='hapus_rcn_aksi(1, ".$row_rcn->id.")'><i class='fa fa-remove'></i></button>
																	</td>
																</tr>";
														}else{
															echo "
																<tr>
																	<td>".$row_rcn->aksi."</td>
																	<td>".$row_rcn->target."</td>
																	<td>".$row_rcn->satuan."</td>
																	<td>".$row_rcn->bobot."</td>
																	
																	<td>".$row_rcn->kumul."</td>
																	<td style='width: 40px;'>
																		<button type='button' onclick='edit_rcn(".$row_rcn->id.")'><i class='fa fa-pencil'></i></button>
																		<button type='button' onclick='hapus_rcn_aksi(1, ".$row_rcn->id.")'><i class='fa fa-remove'></i></button>
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
			<textarea class="common indikator_val" name="indikator_kinerja[]" style="width:95%"></textarea>
			<a class="icon-remove hapus_indikator_program" href="javascript:void(0)" style="vertical-align: top;"></a>
		</div>
		<div style="width: 100%;">
			<table class="table-common" width="100%">
				<tr style="width:100%">
					<td>Satuan</td>
					<td colspan="3"><input class="common indikator_val" name="satuan_target[1]" id="satuan_target"></td>
					<!-- <td colspan="3"><?php echo form_dropdown('satuan_target[1]', $satuan, '', 'class="common indikator_val" id="satuan_target"'); ?></td> -->

				</tr>
				<tr>
					<td rowspan="2">Kategori Indikator</td>
					<td colspan="3"><?php echo form_dropdown('status_target[1]', $status_indikator, '',  'class="common indikator_val" id="status_target"'); ?></td>
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
<script type="text/javascript">
	function edit_rcn(id) {
		$("#rcn_tabel").html("<tr><td colspan='12' align='center'> <i class='fa fa-refresh fa-spin'></i> Mohon menunggu ...</td></tr>");
		var id_dpa = $("input[name=id_program]").val();
		var uniq_id = $("input[name=rcn_unicid]").val();
		$.ajax({
			type: "POST",
			url: '<?php echo site_url("dpa_perubahan/one_rencana_aksi"); ?>',
			dataType: "json",
			data: {id_dpa : id_dpa, id_dpa_prog_keg: uniq_id, id: id, kd_status : 1},
			success: function(data){
				$("input[name=rcn_id]").val(data.id);
				$("select[name=rcn_bulan]").val(data.bulan);
				$("input[name=rcn_aksi]").val(data.aksi);
				$("input[name=rcn_target").autoNumeric('set', data.target);
				$("input[name=rcn_satuan]").val(data.satuan);
				$("input[name=rcn_bobot]").autoNumeric('set', data.bobot);
				// $("input[name=rcn_anggaran]").autoNumeric('set', data.anggaran);
			}
		});
		$.ajax({
			type: "POST",
			url: '<?php echo site_url("dpa_perubahan/edit_rencana_aksi"); ?>',
			dataType: "html",
			data: {id_dpa : id_dpa, id_dpa_prog_keg: uniq_id, id: id, kd_status : 1},
			success: function(data){
				$("#rcn_tabel").html(data);
				
			}
		});
	}
</script>
