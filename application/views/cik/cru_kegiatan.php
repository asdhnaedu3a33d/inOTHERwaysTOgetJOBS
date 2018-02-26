<script type="text/javascript">
	prepare_chosen();
	$('input[name=rencana]').autoNumeric(numOptions);

	$(document).on("change", "#kd_kegiatan", function () {		
		var str = $(this).find('option:selected').text();		
		var nm_kegiatan = str.substring(str.indexOf(".")+2);
		$("#nama_prog_or_keg").val(nm_kegiatan);		
	});

	$('form#kegiatan').validate({
		rules: {				
			kd_kegiatan : "required",
			indikator_kinerja : "required",			
			rencana : {
				required : true,			
			},
			catatan: {
				required : true
			},
			catatan_thndpn: {
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
	    	$('input[name=rencana]').val($('input[name=rencana]').autoNumeric('get'));
			
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

		$("#indikator_box_kegiatan textarea").attr("name", name);
		$("#indikator_box_kegiatan input#target").attr("name", target);
		$("#indikator_box_kegiatan select#satuan_target").attr("name", satuan_target);
		$("#indikator_frame_kegiatan").append($("#indikator_box_kegiatan").html());
	});

	$(document).on("click", ".hapus_indikator_kegiatan", function(){
		$(this).parent().parent().remove();
	});
</script>

<div style="width: 900px">
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
		<form action="<?php echo site_url('cik/save_kegiatan');?>" method="POST" name="kegiatan" id="kegiatan" accept-charset="UTF-8" enctype="multipart/form-data" >
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
					  <td colspan="3">
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
											<td colspan="3"><?php echo form_dropdown('satuan_target['. $i .']', $satuan, $row->satuan_target, 'class="common indikator_val" id="satuan_target"'); ?></td>
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
											<td colspan="3"><?php echo form_dropdown('satuan_target[1]', $satuan, '', 'class="common indikator_val" id="satuan_target"'); ?></td>
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
					<tr style="background-color: white;">
						<td></td>
						<td colspan="2"><hr></td>
					</tr>					
					<tr>
						<td>&nbsp;&nbsp;Rencana Anggaran</td>
						<td>Rp. <input type="text" name="rencana" value="<?php if(!empty($kegiatan->rencana)){echo $kegiatan->rencana;} ?>"/>
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
					<td colspan="3"><?php echo form_dropdown('satuan_target[1]', $satuan, '', 'class="common indikator_val" id="satuan_target"'); ?></td>
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