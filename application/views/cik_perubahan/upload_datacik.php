<script type="text/javascript">
	$("#simpan").click(function(){
		/*
		$.blockUI({
				css: window._css,
				overlayCSS: window._ovcss
			});

	    	$.ajax({
				type: "POST",
				url: $("form#upload").attr("action"),
				data: $("form#upload").serialize(),
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
					};
				}
			});
			*/
		$("form#upload").submit();
	});
</script>
<div style="width: 900px">
	<header>
		<h3>Upload File CIK Perubahan</h3>
	</header>
    <div class="module_content">
		<form action="<?php echo site_url('cik_perubahan/save_file_upload_cik');?>" method="POST" name="upload" id="upload" accept-charset="UTF-8" enctype="multipart/form-data" >
        <input type="hidden" name="id" value="<?php echo $id; ?>" />
        <input type="hidden" name="bulan" value="<?php echo $bulan; ?>" />
        <table class="fcari" width="100%">
				<tbody>
					<tr>
						<td width="20%">SKPD</td>
						<td width="80%" colspan="2"><?php echo $skpd->nama_skpd; ?></td>
					</tr>
					<tr>
						<td>Kode & Nama Kegiatan</td>
						<td colspan="2"><?php echo $kodefikasi->kd_urusan.". ".$kodefikasi->kd_bidang.". ".$kodefikasi->kd_program.". ".$kodefikasi->kd_kegiatan." - ".$kodefikasi->nama_prog_or_keg; ?></td>
					</tr>
                    <tr>
					  <td colspan="3"></td>
				 	</tr>
                    <tr>
                        <td colspan="2">
                        <?php
                            include_once("file_upload.php");
                        ?>
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
