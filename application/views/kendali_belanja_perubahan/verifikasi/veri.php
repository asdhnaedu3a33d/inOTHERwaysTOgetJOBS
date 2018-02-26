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
		$("form#veri_renja").validate({
			rules: {
			  ket : "required"
			}
	    });

		$("#simpan").click(function(){
		    var valid = $("form#veri_renja").valid();
		    if (valid) {
		    	$.blockUI({
					css: window._css,
					overlayCSS: window._ovcss
				});

		    	$.ajax({
					type: "POST",
					url: $("form#veri_renja").attr("action"),
					data: $("form#veri_renja").serialize(),
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
			Verifikasi Data Kendali Renja Perubahan
		</h3>
 	</header>
	<div class="module_content">
		<table class="fcari" width="100%">
			<tbody>
				<tr>
					<td>Kode</td>
					<td>
						<?php
							echo $renja->kd_urusan.". ".$renja->kd_bidang.". ".$renja->kd_program;
							if (!$program) {
								echo ". ".$renja->kd_kegiatan;
							}
						?>
					</td>
				</tr>
				<tr>
					<td><?php echo ($program)?"Program":"Kegiatan"; ?></td>
					<td><?php echo $renja->nama_prog_or_keg; ?></td>
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
			?>
				<tr>
					<td>Penanggung Jawab</td>
					<td><?php echo $renja->penanggung_jawab; ?></td>
				</tr>
				<tr>
					<td>Lokasi</td>
					<td><?php echo $renja->lokasi; ?></td>
				</tr>
				<tr style="background-color: white;">
					<td colspan="2"><hr></td>
				</tr>
				<tr>
					<td>Nominal</td>
					<td><?php echo FORMATTING::currency($renja->nominal); ?></td>
				</tr>
				<tr>
					<td>Catatan</td>
					<td><?php echo $renja->catatan; ?></td>
				</tr>
			<?php
				}
			?>
			</tbody>
		</table>
		<form id="veri_renja" name="veri_renja" method="POST" accept-charset="UTF-8" action="<?php echo site_url('renja/save_veri'); ?>">
			<input type="hidden" name="id" value="<?php echo $renja->id; ?>">
			<div class="radio">
				<label><input type="radio" name="veri" value="setuju"> Disetujui</label>
			</div>
			<div class="radio">
				<label><input type="radio" name="veri" value="tdk_setuju"> Tidak Disetujui</label>
			</div>
			<div class="form-group">
				<textarea class="form-control" disabled id="ket" name="ket"></textarea>
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
