<script type="text/javascript">
	$(document).ready(function () {
		$("#batal").click(function(){
			$.facebox.close();
		});

		$("#simpan").click(function(){
			$.blockUI({
				css: window._css,
				overlayCSS: window._ovcss
			});
	    	
	    	$.ajax({
				type: "POST",
				url: "<?php echo site_url('cik_perubahan/do_kirim_cik'); ?>",
				data: {id_skpd: $(this).attr("idS"), bulan: $(this).attr("idB")},
				dataType: "json",
				success: function(msg){
					if (msg.success==1) {
						//alert('setan');
						$.blockUI({
							message: msg.msg,
							timeout: 2000,
							css: window._css,
							overlayCSS: window._ovcss
						});
						window.location.reload();						
					};
				}
			});
		});
	});
</script>
<div style="width: 900px">
	<header>
		<h3>
			&nbsp;&nbsp;&nbsp;&nbsp;Pengiriman / Pengajuan Capaian Indikator Kinerja
		</h3>
	</header>
	<div class="module_content">
		Yakin akan mengirim/mengajukan Capaian Indikator Kinerja?<br />
		Apabila CIK Perubahan telah dikirim/diajukan maka fitur edit maupun fitur lainnya yang digunakan untuk mengelola CIK Perubahan akan di non-aktifkan hingga terdapat proses verifikasi oleh Pihak yang memiliki kendali terhadap proses tersebut.
	</div>
	<footer>
		<div class="submit_link">    			
  			<input idS="<?php echo $id_skpd; ?>" idB="<?php echo $bulan; ?>" id="simpan" type="button" value="Kirim">
  			<input id="batal" type="button" value="Batal">
		</div> 
	</footer>
</div>