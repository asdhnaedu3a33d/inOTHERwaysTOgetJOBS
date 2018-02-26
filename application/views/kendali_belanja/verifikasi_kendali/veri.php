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
		$("form#veri_kendali").validate({
			rules: {
			  ket : "required"
			}
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

		$("#simpan").click(function(){
		    var valid = $("form#veri_kendali").valid();
		    if (valid) {
		    	$.blockUI({
					css: window._css,
					overlayCSS: window._ovcss
				});

		    	$.ajax({
					type: "POST",
					url: $("form#veri_kendali").attr("action"),
					data: $("form#veri_kendali").serialize(),
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
	});
</script>
<table class="fcari" width="800px">
	<tbody>
		<tr>
			<td colspan="2">Informasi Program & Kegiatan</td>
		</tr>
		<tr>
			<td width="20%">Program</td>
			<td width="77%"><?php echo $veri->kd_urusan.". ".$veri->kd_bidang.". ".$veri->kd_program." - ".$veri->nama_program; ?></td>
		</tr>
		<tr>
			<td>Kegiatan</td>
			<td><?php echo $veri->kd_urusan.". ".$veri->kd_bidang.". ".$veri->kd_program.". ".$veri->kd_kegiatan." - ".$veri->nama_prog_or_keg; ?></td>
		</tr>
		<tr>
			<td>Kriteria Keberhasilan</td>
			<td>
				<?php
					$i = 0;
					foreach ($kriteria as $row) {
						$i++;
						echo $i.". ".$row->indikator."<BR>";
					}
				?>
			</td>
		</tr>
		<tr>
			<td>Ukuran Keberhasilan</td>
			<td>
				<?php
					$i = 0;
					foreach ($kriteria as $row) {
						$i++;
						echo $i.". ".$row->indikator." ".$row->target." ".$row->satuan_target."<BR>";
					}
				?>
			</td>
		</tr>
		<tr>
			<td>Triwulan</td>
			<td>TW <?php echo $veri->id_triwulan; ?></td>
		</tr>
		<tr>
			<td>Pagu</td>
			<td><?php echo FORMATTING::currency($veri->anggaran); ?></td>
		</tr>
		<tr>
			<td>Capaian (%)</td>
			<td><?php echo $veri->capaian; ?></td>
		</tr>

		<tr>
			<td>Output</td>
			<td>
				<table class="table-common" width="100%">
					<tr>
						<th width="10px;">No.</th>
						<th>Catatan</th>
						<th>Keterangan</th>
						<th>Capaian (%)</th>
					</tr>
				<?php
					$i=0;
					foreach ($output as $row) {
						$i++;
				?>

					<tr>
						<td align="center"><?php echo $i; ?></td>
						<td><?php echo $row->catatan; ?></td>
						<td><?php echo $row->keterangan; ?></td>
						<td><?php echo $row->capaian; ?></td>
					</tr>
				<?php
					}
				?>
				</table>
			</td>
		</tr>
		<tr style="background-color: white;">
			<td colspan="2"><hr></td>
		</tr>
	</tbody>
</table>
<?php
	if (!empty($revisi)) {
?>
<table class="fcari" width="800px">
	<tr style="background-color: #FF8F8F;">
		<th colspan="2">Riwayat Revisi</th>
	</tr>
	<tr style="background-color: #FF8F8F;">
		<th width="20%">Tanggal</th>
		<th>Keterangan</th>
	</tr>
<?php
		foreach ($revisi as $row) {
?>
		<tr>
			<td align="center"><?php echo $row->formated_date; ?></td>
			<td><?php echo $row->ket; ?></td>
		</tr>
<?php
		}
?>
	<tr>
		<td colspan="2"><hr></td>
	</tr>
</table>
<?php
	}
?>
<form id="veri_kendali" name="veri_kendali" method="POST" accept-charset="UTF-8" action="<?php echo site_url('kendali/save_veri'); ?>">
	<input type="hidden" name="id" value="<?php echo $veri->id_tx_dpa_prog_keg_triwulan; ?>">
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
<footer>
	<div class="submit_link" style="display: inline; width: 100%; text-align: right;">
		<input disabled id="simpan" type="button" value="Simpan">
		<input type='button' id="keluar" value='Keluar' />
	</div>
</footer>
