<script type="text/javascript">
	prepare_chosen();
	$('input[name=nominal]').autoNumeric(numOptionsNotRound);
	$('input[name=nominal_thndpn]').autoNumeric(numOptionsNotRound);

	$(document).on("change", "#kd_kegiatan", function () {
		var str = $(this).find('option:selected').text();
		var nm_kegiatan = str.substring(str.indexOf(".")+2);
		$("#nama_prog_or_keg").val(nm_kegiatan);
	});

	$('form#kegiatan').validate({
		rules: {
			kd_kegiatan : "required",
			indikator_kinerja : "required",
			lokasi : {
				required : true,
			},
			lokasi_thndpn : {
				required : true,
			},
			penanggung_jawab: {
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
	    	$('input[name=nominal]').val($('input[name=nominal]').autoNumeric('get'));
			$('input[name=nominal_thndpn]').val($('input[name=nominal_thndpn]').autoNumeric('get'));

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
		var target_thndpn = "target_thndpn["+ key +"]";
		var satuan_target = "satuan_target["+ key +"]";
		var status_target = "status_target["+ key +"]";
		var kategori_target = "kategori_target["+ key +"]";

		$("#indikator_box_kegiatan textarea").attr("name", name);
		$("#indikator_box_kegiatan input#target").attr("name", target);
		$("#indikator_box_kegiatan input#target_thndpn").attr("name", target_thndpn);
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
		$('#nominal_satuan_1').autoNumeric(numOptionsNotRound);
		$('#volume_1').autoNumeric(numOptionsNotRound);
		$('#nominal_satuan_2').autoNumeric(numOptionsNotRound);
		$('#volume_2').autoNumeric(numOptionsNotRound);

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

		prepare_chosen();
		$(document).on("change", "#cb_jenis_belanja_2", function () {
		  $.ajax({
		    type: "POST",
		    url: '<?php echo site_url("common/cmb_kategori_belanja_2"); ?>',
		    data: {cb_jenis_belanja_2: $(this).val()},
		    success: function(msg){
		      $("#combo_kategori_2").html(msg);
		      $("#cb_subkategori_belanja_2").val(" ");
		      $("#cb_belanja_2").val(" ");
		      $("#cb_subkategori_belanja_2").trigger("chosen:updated");
		      $("#cb_belanja_2").trigger("chosen:updated");
		      prepare_chosen();
		    }
		  });
		});
		$(document).on("change", "#cb_kategori_belanja_2", function () {
		  $.ajax({
		    type: "POST",
		    url: '<?php echo site_url("common/cmb_subkategori_belanja_2"); ?>',
		    data: {cb_jenis_belanja_2:$("#cb_jenis_belanja_2").val(), cb_kategori_belanja_2: $(this).val()},
		    success: function(msg){
		      $("#combo_subkategori_2").html(msg);
		      $("#cb_belanja_2").val("");
		      $("#cb_belanja_2").trigger("chosen:updated");
		      prepare_chosen();
		    }
		  });
		});
		$(document).on("change", "#cb_subkategori_belanja_2", function () {
		  $.ajax({
		    type: "POST",
		    url: '<?php echo site_url("common/cmb_belanja_2"); ?>',
		    data: {cb_jenis_belanja_2:$("#cb_jenis_belanja_2").val(), cb_kategori_belanja_2:$("#cb_kategori_belanja_2").val(), cb_subkategori_belanja_2: $(this).val()},
		    success: function(msg){
		      $("#combo_belanja_2").html(msg);
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
     <?php
	if($status == "3"){ ?>
    <table class="table-common" style="width: 99.8%">
    	<thead>
        <tr align="center"><th colspan="2">Revisi</th></tr>
        <tr>
        	<th align="center">No</th>
            <th align="center">Keterangan</th>
        </tr>
        </thead>
        <tbody>
        <?php
		$i=0;
		foreach ($revisi as $row) {
		$i++;
		?>
        <tr>
        	<td align="center"><?php echo $i;?></td>
            <td align="left"><?php echo $row->ket;?></td>
        </tr>
        <?php
		}
		?>
        </tbody>
    </table>
	<?php
    } else if($status == "6") {
	?>
    <table class="table-common" style="width: 99.8%">
    	<thead>
        <tr align="center"><th colspan="3">Revisi</th></tr>
        <tr>
        	<th align="center">No</th>
            <th align="center">Nominal Revisi</th>
            <th align="center">Keterangan</th>
        </tr>
        </thead>
        <tbody>
        <?php
		$i=0;
		foreach ($akhir as $row) {
		$i++;
		?>
        <tr>
        	<td align="center"><?php echo $i;?></td>
            <td align="right"><?php echo Formatting::currency($row->nominal, 2);?></td>
            <td align="left"><?php echo $row->ket_revisi;?></td>
        </tr>
        <?php
		}
		?>
        </tbody>
    </table>
	<?php
    }
	?>
		<form action="<?php echo site_url('renja/save_kegiatan');?>" method="POST" name="kegiatan" id="kegiatan" accept-charset="UTF-8" enctype="multipart/form-data" >
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
						<td width="15%">SKPD</td>
						<td width="85%" colspan="2"><?php echo $skpd->nama_skpd; ?></td>
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
					  <td colspan="3">
                      <?php
					  		$ta=$this->m_settings->get_tahun_anggaran();
							$tahun_n1=0;
							$tahun_n1= $ta+1;
					  ?>
                      <div>
                      <table class="table-common" width="100%">
                      <tr>
												<th align="center" width="20%"></th>
                      	<th align="center" width="40%">Tahun <?php echo $ta;?>
													<input type="hidden" name="cet_tahun_1" value="<?php echo $ta;?>">
												</th>
                        <th align="center" width="40%">Tahun <?php echo $tahun_n1;?>
													<input type="hidden" name="cet_tahun_2" value="<?php echo $tahun_n1;?>">
												</th>
                      </tr>
                      </table>
                      </div>
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
                                            <td bgcolor="#CCCCCC">Target</td>
                                            <td bgcolor="#CCCCCC"><input style="width: 100%;" type="text" class="target" name="target_thndpn[<?php echo $i; ?>]" value="<?php echo (!empty($row->target_thndpn))?$row->target_thndpn:''; ?>"></td>
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
                      <td bgcolor="#CCCCCC">Target</td>
                      <td bgcolor="#CCCCCC"><input style="width: 100%;" type="text" class="target" name="target_thndpn[1]"></td>
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
						<td colspan="2"><hr></td>
					</tr>
					<tr>
						<td>&nbsp;&nbsp;Pagu Indikatif</td>
						<td>Rp. <input type="text" name="nominal" id="nominal" value="<?php if(!empty($kegiatan->nominal)){echo $kegiatan->nominal;}else{echo '0';} ?>" readonly/>
                        </td>
						<td bgcolor="#CCCCCC">Rp. <input type="text" name="nominal_thndpn" id="nominal_thndpn" value="<?php if(!empty($kegiatan->nominal_thndpn)){echo $kegiatan->nominal_thndpn;}else{echo '0';} ?>" readonly/>
                        </td>
					</tr>
					<tr>
						<td>&nbsp;&nbsp;Lokasi</td>
						<td><input class="common" name="lokasi" value="<?php echo (!empty($kegiatan->lokasi))?$kegiatan->lokasi:''; ?>"></td>
                        <td bgcolor="#CCCCCC"><input class="common" name="lokasi_thndpn" value="<?php echo (!empty($kegiatan->lokasi_thndpn))?$kegiatan->lokasi_thndpn:''; ?>"></td>
					</tr>

							<input type="hidden" class="common" name="catatan" style="font-family:Arial, Helvetica, sans-serif" value="<?php echo (!empty($kegiatan->catatan))?$kegiatan->catatan:'-'; ?>" />

                            <input type="hidden" class="common" name="catatan_thndpn" style="font-family:Arial, Helvetica, sans-serif" value="<?php echo (!empty($kegiatan->catatan_thndpn))?$kegiatan->catatan_thndpn:'-'; ?>" />
                    <tr>
						<td>Penanggung Jawab</td>
						<td colspan="2"><input class="common" name="penanggung_jawab" value="<?php echo (!empty($kegiatan->penanggung_jawab))?$kegiatan->penanggung_jawab:''; ?>"></td>
					</tr>
					<tr style="background-color: white;">
						<td colspan="3"><hr>
						<div class="submit_link">
								<input id="simpan" type="button" value="Simpan" style="background-color: #3c8dbc !important; border-color: #367fa9 !important; color: #fff !important;">
							</div>
							<hr>
						</td>
	
					</tr>
					<tr <?php if(empty($kegiatan->id)){echo "style='display: none;'";} ?>>
						<td colspan="3">
							<div class="nav-tabs-custom">
		            <ul class="nav nav-tabs">
		              <li class="active"><a href="#tahun1" data-toggle="tab">Tahun <?php echo $ta;?></a></li>
<?php if($this->m_settings->get_id_tahun() < 5){ ?>
		              <li ><a href="#tahun2" data-toggle="tab">Tahun <?php echo $tahun_n1;?></a></li>
<?php } ?>

		            </ul>
<div class="tab-content">
	<!-- /.tab-pane -->
	<div class="active tab-pane" id="tahun1">
		<div class="tab-pane" id="tahun1">
			<table>

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
				</tr>
				<tr>
					<td>Sub Rincian Belanja</td>
					<td>
				      <input type="text" id="det_uraian_1" name="det_uraian_1" class="common" value="<?php if(!empty($deturaian_1)){echo $deturaian_1;} ?>" />
					</td>
				</tr>
				<tr>
					<td>Volume</td>
					<td>
						<input class="common" type="text" name="volume_1" id="volume_1" value="<?php if(!empty($volume_1)){echo $volume_1;} ?>"/>
					</td>
				</tr>
				<tr>
					<td>Satuan</td>
					<td>
				  <!-- <?php echo form_dropdown('satuan_1', $satuan, NULL, 'class="common " id="satuan_1" name="satuan_1"'); ?> -->
						<input class="common" type="text" name="satuan_1" id="satuan_1" />
					</td>
				</tr>
				<tr>
					<td>Nominal Satuan</td>
					<td>
						<input class="common" type="text" name="nominal_satuan_1" id="nominal_satuan_1" value="<?php if(!empty($nominal_satuan_1)){echo $nominal_satuan_1;} ?>"/>
					</td>
				</tr>
			</table>
			<input type="hidden" id="id_belanja_1" value="">

			<div class="alert alert-warning alert-white rounded" id="cusAlert_1" role="alert" style="display:none;">
		    	<div class="icon"><i class="fa fa-warning"></i></div>
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
															<th>Sub Rincian</th>
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
							                  $vol = Formatting::currency($row->volume, 2);
							                  $nom = Formatting::currency($row->nominal_satuan, 2);
							                  $sub = Formatting::currency($row->subtotal, 2);
							                  $total += $row->subtotal;

										      ?>
										      <tr id="<?php echo $gIndex_1 ?>">
										        <td> <?php echo $gIndex_1 ?> </td>

										        <td> <?php echo $row->kode_jenis_belanja.". ".$row->jenis ?> </td>
										        <td> <?php echo $row->kode_kategori_belanja.". ".$row->kategori ?> </td>
										        <td> <?php echo $row->kode_sub_kategori_belanja.". ".$row->subkategori ?> </td>
										        <td > <?php echo $row->kode_belanja.". ".$row->belanja ?> </td>
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
											</div>
										</div>


										<div class="tab-pane" id="tahun2">
											<table>
												<input type="hidden" id="inIndex_2" name="inIndex_2" value="1"/>
												<input type="hidden" id="tahun_2" name="tahun_2" value=<?php if(!empty($detil_kegiatan_2)){echo $detil_kegiatan_2[0]->tahun;} ?> />
												<input type="hidden" id="isEdit_2" value="0"/>

												<tr>
														<td width="20%">Kelompok Belanja</td>
														<td width="80%" id="combo_jenis_belanja_2">
															<?php echo $cb_jenis_belanja_2; ?>

														</td>
												</tr>
												<tr>
														<td>Jenis Belanja</td>
														<td id="combo_kategori_2">
															<?php echo $cb_kategori_belanja_2; ?>

														</td>
												</tr>
												<tr>
													<td>Obyek Belanja</td>
													<td id="combo_subkategori_2">
															<?php echo $cb_subkategori_belanja_2; ?>
													</td>
												</tr>
												<tr >
													<td>Rincian Obyek</td>
													<td id="combo_belanja_2">
															 <?php echo $cb_belanja_2; ?>
													</td>
												</tr>
												<tr>
													<td>Rincian Belanja</td>
													<td>
																<input type="text" id="uraian_2" name="uraian_2" class="common" value="<?php if(!empty($uraian_2)){echo $uraian_2;} ?>" />
													</td>
												</tr>
												<tr>
													<td>Sumber Dana </td>
													<td id="combo_sumberdana_2">
														<?php echo form_dropdown('sumberdana_2', $sumber_dana, NULL, 'data-placeholder="Pilih Sumber Dana" class="common chosen-select" id="sumberdana_2" name="sumberdana_2"'); ?>
														 <!-- <select id="sumberdana_2" name="sumberdana_2" class="common" >
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
																<input type="text" id="det_uraian_2" name="det_uraian_2" class="common" value="<?php if(!empty($deturaian_2)){echo $deturaian_2;} ?>" />
													</td>
												</tr>
												<tr>
													<td>Volume</td>
													<td><input class="common" type="text" name="volume_2" id="volume_2" value="<?php if(!empty($volume_2)){echo $volume_2;} ?>"/></td>
												</tr>
												<tr>
													<td>Satuan</td>
													<td>
														<input class="common" type="text" name="satuan_2" id="satuan_2" />
														<!-- <?php echo form_dropdown('satuan_2', $satuan, NULL, 'class="common " id="satuan_2" name="satuan_2"'); ?> -->
													</td>
												</tr>
												<tr>
													<td>Nominal Satuan</td>
													<td><input class="common" type="text" name="nominal_satuan_2" id="nominal_satuan_2" value="<?php if(!empty($nominal_satuan_2)){echo $nominal_satuan_2;} ?>"/></td>
												</tr>
											</table>

											<input type="hidden" id="id_belanja_2" value="">

											<div class="alert alert-warning alert-white rounded" id="cusAlert_2" role="alert" style="display:none;">
												<div class="icon">
														<i class="fa fa-warning"></i>
												</div>
												<font color="#d68000" size="4px"> <strong >Perhatian..!! </strong>
													<span id="pesan_2"></span>
												</font>
											</div>

			<div class="submit_link">
	      		<input type='button' id="tambahjnsbelanja" onclick="save_belanja(2, 'jns');" style="cursor:pointer;" value="+ Kelompok Belanja">
      			<input type='button'  id="tambahkatbelanja" onclick="save_belanja(2, 'kat');" style="cursor:pointer;" value='+ Jenis Belanja'>
		      	<input type='button'  id="tambahsubkatbelanja" onclick="save_belanja(2, 'subkat');" style="cursor:pointer;" value='+ Obyek Belanja'>
		      	<input type='button'  id="tambahbelanja" onclick="save_belanja(2, 'belanja');" style="cursor:pointer;" value='+ Rincian Obyek'>
		      	<input type='button'  id="tambahuraian" onclick="save_belanja(2, 'uraian');" style="cursor:pointer;" value='+ Rincian Belanja'>
		      	<input type='button'  id="tambahdeturaian" onclick="save_belanja(2, 'deturaian');" style="cursor:pointer;" value='+ Sub Rincian Belanja'>
			</div>
											<br>
											<table id="listbelanja_2">
												<thead>
												<tr>
													<th>No</th>

													<th>Kelompok Belanja</th>
													<th>Jenis Belanja</th>
													<th>Obyek Belanja</th>
													<th>Rincian Obyek</th>
													<th>Rincian Belanja</th>
													<th>Sumber Dana</th>
													<th>Sub Rincian</th>
													<th>Volume</th>
													<th>Satuan</th>
													<th>Nominal</th>
													<th>Sub Total</th>
													<th colspan="2">Action</th>
												</tr>
											</thead>
											<tbody id="list_tahun_2">
												<?php if(!empty($detil_kegiatan_2)){
													$gIndex_2 = 1;
													$total = 0;
													foreach ($detil_kegiatan_2 as $row) {
															$vol = Formatting::currency($row->volume);
															$nom = Formatting::currency($row->nominal_satuan);
															$sub = Formatting::currency($row->subtotal);
															$total += $row->subtotal;


												?>
												<tr id="<?php echo $gIndex_2 ?>">
													<td> <?php echo $gIndex_2 ?> </td>

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
									        <td> <span id="ubahrowng" class="icon-pencil" onclick="ubahrowng(<?php echo $row->id; ?>, 2)" style="cursor:pointer;" value="ubah" title="Ubah Belanja"></span></td>
									        <td> <span id="hapusrowng" class="icon-remove" onclick="hapusrowng(<?php echo $row->id; ?>, 2)" style="cursor:pointer;" value="hapus" title="Hapus Belanja"></span></td>


													<!-- <td style="display:none;"><input type="text" name="kd_sumber_dana_2[<?php echo $gIndex_2 ?>]" id="kd_sumber_dana_2[<?php echo $gIndex_2 ?>]" value="<?php echo $row->kode_sumber_dana ?>" /> </td>
													<td style="display:none;"><input type="text" name="r_kd_jenis_belanja_2[<?php echo $gIndex_2 ?>]" id="r_kd_jenis_belanja_2[<?php echo $gIndex_2 ?>]" value="<?php echo $row->kode_jenis_belanja ?>" /> </td>
													<td style="display:none;"><input type="text" name="r_kd_kategori_belanja_2[<?php echo $gIndex_2 ?>]" id="r_kd_kategori_belanja_2[<?php echo $gIndex_2 ?>]" value="<?php echo $row->kode_kategori_belanja ?>" /> </td>
													<td style="display:none;"><input type="text" name="r_kd_subkategori_belanja_2[<?php echo $gIndex_2 ?>]" id="r_kd_subkategori_belanja_2[<?php echo $gIndex_2 ?>]" value="<?php echo $row->kode_sub_kategori_belanja ?>" /> </td>
													<td style="display:none;"><input type="text" name="r_kd_belanja_2[<?php echo $gIndex_2 ?>]" id="r_kd_belanja_2[<?php echo $gIndex_2 ?>]" value="<?php echo $row->kode_belanja ?>" /> </td>
													<td style="display:none;"><input type="text" name="r_uraian_2[<?php echo $gIndex_2 ?>]" id="r_uraian_2[<?php echo $gIndex_2 ?>]" value="<?php echo $row->uraian_belanja ?>" /> </td>
													<td style="display:none;"><input type="text" name="r_det_uraian_2[<?php echo $gIndex_2 ?>]" id="r_det_uraian_2[<?php echo $gIndex_2 ?>]" value="<?php echo $row->detil_uraian_belanja ?>" /> </td>
													<td style="display:none;"><input type="text" name="r_volume_2[<?php echo $gIndex_2 ?>]" id="r_volume_2[<?php echo $gIndex_2 ?>]" value="<?php echo str_replace('.','',$vol); ?>" /> </td>
													<td style="display:none;"><input type="text" name="r_satuan_2[<?php echo $gIndex_2 ?>]" id="r_satuan_2[<?php echo $gIndex_2 ?>]" value="<?php echo $row->satuan ?>" /> </td>
													<td style="display:none;"><input type="text" name="r_nominal_satuan_2[<?php echo $gIndex_2 ?>]" id="r_nominal_satuan_2[<?php echo $gIndex_2 ?>]" value="<?php echo str_replace('.','',$nom); ?>" /> </td>
													<td style="display:none;"><input type="text" name="r_subtotal_2[<?php echo $gIndex_2 ?>]" id="r_subtotal_2[<?php echo $gIndex_2 ?>]" value="<?php echo str_replace('.','',$sub); ?>" /> </td> -->
												</tr>

												<?php $gIndex_2++;
													echo "<script> document.getElementById('inIndex_2').value = $gIndex_2; </script> ";
													echo "<script type='text/javascript'>$('input[name=nominal_thndpn]').autoNumeric('set', ".$total.");</script>";
												}} ?>
											</tbody>
											</table>
										</div>
								</div>
							</div>
						</td>
					</tr>
					

				</tbody>
			</table>
		</form>
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
                    <td>Target</td>
					<td><input style="width: 100%;" type="text" class="target" id="target_thndpn" name="target_thndpn[1]"></td>
				</tr>
			</table>
		</div>
	</div>
</div>

<script src="<?php echo base_url('assets/renja/createbelanja_tahun1.js');?>"></script>
<script src="<?php echo base_url('assets/renja/createbelanja_tahun2.js');?>"></script>
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

	function errorMessage_2(clue) {
		var jenis_belanja = $('#cb_jenis_belanja_2').val();
		var kategori_belanja = $('#cb_kategori_belanja_2').val();
		var subkategori_belanja = $('#cb_subkategori_belanja_2').val();
		var kode_belanja = $('#cb_belanja_2').val();
		var uraian = $('#uraian_2').val();
		var det_uraian = $('#det_uraian_2').val();
		var volume = $('#volume_2').val();
		var satuan = $('#satuan_2').val();
		var nominal = $('#nominal_satuan_2').val();
    var sumberdana = $('#sumberdana_2').val();
		eliminationName(jenis_belanja, kategori_belanja, subkategori_belanja, kode_belanja, uraian, det_uraian, volume, satuan, nominal, sumberdana, clue, '#cusAlert_2', 'pesan_2');
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


	  function jenis_belanjanya_2(p_nama, p_jenis) {
	    $.ajax({
	      type: "POST",
	      url: '<?php echo site_url("common/edit_jenis_belanja"); ?>',
	      data: {nama: p_nama, jenis: p_jenis},
	      success: function(msg){
	        $("#combo_jenis_belanja_2").html(msg);
	        prepare_chosen();
	      }
	    });
	  }
	  function kategori_belanjanya_2(p_nama, p_jenis, p_kategori) {
	    $.ajax({
	      type: "POST",
	      url: '<?php echo site_url("common/edit_kategori_belanja"); ?>',
	      data: {nama: p_nama, jenis: p_jenis, kategori: p_kategori},
	      success: function(msg){
	        $("#combo_kategori_2").html(msg);
	        prepare_chosen();
	      }
	    });
	  }
	  function sub_belanjanya_2(p_nama, p_jenis, p_kategori, p_sub) {
	    $.ajax({
	      type: "POST",
	      url: '<?php echo site_url("common/edit_sub_belanja"); ?>',
	      data: {nama: p_nama, jenis: p_jenis, kategori: p_kategori, sub: p_sub},
	      success: function(msg){
	        $("#combo_subkategori_2").html(msg);
	        prepare_chosen();
	      }
	    });
	  }
	  function belanja_belanjanya_2(p_nama, p_jenis, p_kategori, p_sub, p_belanja) {
	    $.ajax({
	      type: "POST",
	      url: '<?php echo site_url("common/edit_belanja_belanja"); ?>',
	      data: {nama: p_nama, jenis: p_jenis, kategori: p_kategori, sub: p_sub, belanja: p_belanja},
	      success: function(msg){
	        $("#combo_belanja_2").html(msg);
	        prepare_chosen();
	      }
	    });
	  }
	  function sumber_dananya_2(p_nama, p_id) {
	    $.ajax({
	      type: "POST",
	      url: '<?php echo site_url("common/edit_sumber_dana"); ?>',
	      data: {nama: p_nama, id: p_id},
	      success: function(msg){
	        $("#combo_sumberdana_2").html(msg);
	        prepare_chosen();
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
			    url: '<?php echo site_url("renja/belanja_kegiatan_save"); ?>',
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
          url: '<?php echo site_url("renja/belanja_kegiatan_edit"); ?>',
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
          url: '<?php echo site_url("renja/belanja_kegiatan_hapus"); ?>',
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
            
          }
      });
  }
</script>
