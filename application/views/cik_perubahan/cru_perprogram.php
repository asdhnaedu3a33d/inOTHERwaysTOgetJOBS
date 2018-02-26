<script type="text/javascript">

	$('form#kegiatan').validate({
		rules: {				
			realisasi_<?php echo $bulan;?>: {
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

		$('#indikator_frame_kegiatan .real').each(function () {
		    $(this).rules('add', {
		        required:true,
				number:true
		    });
		});
		
	     var valid = $("form#kegiatan").valid();
	    if (valid) {
		
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
					$("input#search").trigger( "click" );
					$.facebox.close();						
				};
			}
		});
		};
	});

</script>

<div style="width: 900px">
	<header>
		<h3>Input Data CIK Perubahan</h3>
	</header>
	<div class="module_content">
		<form action="<?php echo site_url('cik_perubahan/save_program_cik');?>" method="POST" name="kegiatan" id="kegiatan" accept-charset="UTF-8" enctype="multipart/form-data" >
			<input type="hidden" name="id_kegiatan" value="<?php echo $kegiatan->id; ?>" />
            <input type="hidden" name="id_skpd" value="<?php echo $this->session->userdata("id_skpd"); ?>" />
        	<input type="hidden" name="tahun" value="<?php echo $this->m_settings->get_tahun_anggaran(); ?>" />
            <input type="hidden" name="bulan" value="<?php echo $bulan; ?>" />
			<table class="fcari" width="100%">
				<tbody>
                	<?php
						$status = "status_".$bulan;
						$keterangan = "ket_".$bulan;
						if($kodefikasi->$status=='3'){
					?>
							<tr>
                                <td width="20%" style="color:#F00"><strong>Keterangan Revisi</strong></td>
                                <td width="80%" style="color:#F00" colspan="2"><strong><?php echo $kodefikasi->$keterangan; ?></strong></td>
                            </tr>
                    <?php
						}
					?>
                    
					<tr>
						<td width="20%">SKPD</td>
						<td width="80%" colspan="2"><?php echo $skpd->nama_skpd; ?></td>
					</tr>					
					<tr>
						<td>Kode & Nama Program</td>
						<td colspan="2"><?php echo $kodefikasi->kd_urusan.". ".$kodefikasi->kd_bidang.". ".$kodefikasi->kd_program.
						" - ".$kodefikasi->nama_prog_or_keg; ?></td>
					</tr>
					<tr>
					  <td colspan="3">
				 	</tr>					
					<tr>
						<td>Indikator Kinerja</td>
						<td id="indikator_frame_kegiatan" key="<?php echo (!empty($indikator_kegiatan))?$indikator_kegiatan->num_rows():'1'; ?>" colspan="2">
							<?php
								if (!empty($indikator_kegiatan)) {
									$i=0; $kinerja=0; $tot_kinerja=0;
									foreach ($indikator_kegiatan->result() as $row) {
										$i++; //$kinerja += round($row->realisasi/$row->target*100,2);
							?>
							<input type="hidden" name="id_indikator_kegiatan[<?php echo $i; ?>]" value="<?php echo $row->id; ?>"/>
							<div style="width: 100%; margin-top: 10px;">
								<div style="width: 100%;">
									<textarea class="common indikator_val" name="indikator_kinerja[<?php echo $i; ?>]" style="width:99%" readonly="readonly"><?php if(!empty($row->indikator)){echo $row->indikator;} ?></textarea>
							  </div>
								<div style="width: 100%;">
									<table class="table-common" width="100%">
										<tr style="width:100%">
											<td>Target Rencana</td>
                                            <td><input style="width: 100%;" type="text" class="target" name="target[<?php echo $i; ?>]" value="<?php echo (!empty($row->target))?$row->target:''; ?>" readonly="readonly"></td>
                                            <td  bgcolor="#00FF33">Target Realisasi</td>
                                            <td  bgcolor="#00FF33"><input style="width: 100%;" type="text" class="real" name="real_<?php echo $bulan;?>[<?php echo $i; ?>]" value="<?php echo (!empty($row->realisasi))?$row->realisasi:''; ?>"></td>
                                        </tr>
									</table>
								</div>
							</div>
							<?php
									}
								} //$tot_kinerja = $kinerja/$i;
							?>
						</td>						
					</tr>					
					<tr style="background-color: white;">
						<td></td>
						<td colspan="2"><hr></td>
					</tr>
                    <tr>
                    	<td>&nbsp;&nbsp;Capaian IK</td>
                        <td>
                        <input type="text" id="tot_kinerja" name="capaian_<?php echo $bulan;?>" value="<?php echo $tot_kinerja; ?>" readonly="readonly"/> %</td>
                    </tr>					
				</tbody>
			</table>
		</form>
	</div>
    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<em>*** Silahkan inputkan data CIK Perubahan yang sesuai di dalam kolom berwarna hijau</em></p>
	<footer>
		<div class="submit_link">    			
  			<input id="simpan" type="button" value="Simpan">
		</div> 
	</footer>
</div>
<script type="text/javascript">
	$(document).ready(function(){
		$(".real").keyup(function(){
			hitung_cik();
		});

		function hitung_cik()
		{		
			var real, target, kinerja, tot_kinerja=0, count=0;
			$(".target").each(function(){
				count++;
				target = $(this).val();
				real = $(this).parent().parent().find(".real").val();
				kinerja = Math.round((real/target)*10000)/100;
				tot_kinerja+=kinerja;				
			});

			tot_kinerja = Math.round((tot_kinerja/count)*100)/100;
			$("#tot_kinerja").val(tot_kinerja);			
		}

		hitung_cik();
	});
</script>