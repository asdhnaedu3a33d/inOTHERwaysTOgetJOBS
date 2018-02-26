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
				url: "<?php echo site_url('renstra/do_kirim_renstra'); ?>",
				data: {skpd: $(this).attr("idS")},
				dataType: "json",
				success: function(msg){
					if (msg.success==1) {
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
			Pengiriman/Pengajuan Renstra
		</h3>
	</header>
	<div class="module_content">
		Yakin akan mengirim/mengajukan Renstra?<BR>
		Apabila renstra telah dikirim/diajukan maka fitur edit maupun fitur lainnya yang digunakan untuk mengelola Renstra akan di non-aktifkan hingga terdapat proses verifikasi oleh Pihak yang memiliki kendali terhadap proses tersebut.
	</div>
	<footer>
		<div class="submit_link">    			
  			<input idS="<?php echo $skpd; ?>" id="simpan" type="button" value="Kirim">
  			<input id="batal" type="button" value="Batal">
		</div> 
	</footer>
</div>