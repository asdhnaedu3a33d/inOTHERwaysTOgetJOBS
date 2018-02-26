<script type="text/javascript">
	$(document).ready(function(){
		$('form#approve_renstra').validate({
			rules: {
				ket : "required"
			}
		});

		$(document).on("click", "#kirim", function(){
			var valid = $("form#approve_renstra").valid();
		    if (valid) {
				$.blockUI({
					css: window._css,
					overlayCSS: window._ovcss
				});
				$.ajax({
					type: "POST",
					url: '<?php echo site_url("renstra/do_approve_renstra_kepala_skpd"); ?>',
					data: $("form#approve_renstra").serialize(),
					dataType: "json",
					success: function(msg){
						catch_expired_session2(msg);
						if (msg.success==1) {
							$(location).attr('href', msg.href)
						};
					}
				});
			}
		});
	});
</script>
<form id="approve_renstra" method="POST" name="approve_renstra">
	<input type="hidden" name="id_renstra" value="<?Php echo $id_renstra; ?>">
	<div style="width: 900px">
		<header>
			<h3>
				Verifikasi Renstra
			</h3>
		</header>
		<div class="module_content">
					Apakah anda yakin menyetujui Renstra?<BR>
					Apabila renstra telah disetujui/diverifikasi maka fitur Verifikasi Renstra oleh Kepala SKPD akan di non-aktifkan sehingga Renstra dapat dikirim.
		</div>
</form>
		<br>
		<footer>
			<div class="submit_link">
				<input id="kirim" type="button" value="Kirim" />
			</div>
		</footer>
	</div>
