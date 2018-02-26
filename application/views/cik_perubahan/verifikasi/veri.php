<style type="text/css">
	form{
		color: black;
	}
	.radio-btn{
		width: 97%;
		padding: 10px;
	}
	.radio-btn textarea, .radio-btn .error{
		margin-left: 25px;
		width: 500px;
		height: 100px;
		float: left;
	}
</style>
<script type="text/javascript">
	$(document).ready(function(){
		$("form#veri_cik").validate({
			rules: {
			  ket : "required"
			}
	    });

		$("#simpan").click(function(){
		    var valid = $("form#veri_cik").valid();
		    if (valid) {
		    	$.blockUI({
					css: window._css,
					overlayCSS: window._ovcss
				});

		    	$.ajax({
					type: "POST",
					url: $("form#veri_cik").attr("action"),
					data: $("form#veri_cik").serialize(),
					dataType: "json",
					success: function(msg){
						if (msg.success==1) {
							$.blockUI({
								message: msg.msg,
								timeout: 2000,
								css: window._css,
								overlayCSS: window._ovcss
							});
							location.reload();
							$.facebox.close();
						};
					}
				});
		    };
		});

		$("#keluar").click(function(){
			$.facebox.close();
		});

		$("input[name=veri]").click(function(){
			$("#simpan").attr("disabled", false);
			if($(this).val()=="tdk_setuju"){
				$("#ket").attr("disabled", false);
			}else{
				$("#ket").val("");
				$("#ket").attr("disabled", true);
			}
		});
	});
</script>
<div style="width: 800px;">
	<header>
 		<h3>
			Verifikasi Data CIK Perubahan
		</h3>
 	</header>
	<div class="module_content">
		<table class="fcari" width="100%">
			<tbody>
				<tr>
					<td>Kode</td>
					<td>
						<?php
							echo $cik->kd_urusan.". ".$cik->kd_bidang.". ".$cik->kd_program;
							if (!$program) {
								echo ". ".$cik->kd_kegiatan;
							}
						?>
					</td>
				</tr>
				<tr>
					<td><?php echo ($program)?"Program":"Kegiatan"; ?></td>
					<td><?php echo $cik->nama_prog_or_keg; ?></td>
				</tr>
				<tr>
					<td>Indikator Kinerja<BR>(Target)</td>
					<td>
						<?php
							$i=0;
							foreach ($indikator as $row_indikator) {
								if (!empty((float)$row_indikator->target)) {
									$i++;
									echo $i .". ". $row_indikator->indikator ." (". $row_indikator->target ." ". $row_indikator->nama_value .")";
						?>
								<BR><hr>
						<?php
								}
							}
						?>
					</td>
				</tr>
			<?php
				if (!$program) {
				$realisasi = "realisasi_".$bulan;
			?>
				<tr>
					<td>Rencana</td>
					<td align="right">Rp. <?php echo FORMATTING::currency($cik->rencana); ?></td>
				</tr>
				<tr>
					<td>Realisasi Bulan <?php echo $bulan ?></td>
					<td align="right">Rp. <?php echo FORMATTING::currency($cik->$realisasi); ?></td>
				</tr>
			<?php
				}
			?>
			<tr>
				<td colspan="2">
				<?php
					include_once("file_upload.php");
				?>
				</td>
			</tr>	
			</tbody>
		</table>


		<form id="veri_cik" name="veri_cik" method="POST" accept-charset="UTF-8" action="<?php echo site_url('cik_perubahan/save_veri'); ?>">
			<input type="hidden" name="id" value="<?php echo $cik->id; ?>">
            <input type="hidden" name="bulan" value="<?php echo $bulan; ?>">
			<div class="radio-btn">
				<input type="radio" name="veri" value="setuju"> Disetujui
			</div>
			<div class="radio-btn">
				<input type="radio" name="veri" value="tdk_setuju"> Tidak Disetujui
			</div>
			<div class="radio-btn">
				<textarea disabled id="ket" name="ket"></textarea>
			</div>
		</form>
	</div>
	<footer>
		<div class="submit_link">
			<input disabled type='button' id="simpan" name="simpan" value='Simpan' />
  			<input type='button' id="keluar" name="keluar" value='Keluar' />
		</div>
	</footer>
</div>
