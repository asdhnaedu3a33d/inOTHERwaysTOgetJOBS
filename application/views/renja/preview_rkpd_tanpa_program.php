<script type="text/javascript">
	$(document).ready(function(){
		$(document).on("click", "#cetak", function(){
			$.blockUI({
				message: 'Cetak dokumen sedang di proses, mohon ditunggu hingga file terunduh secara otomatis ...',
				css: window._css,
				timeout: 2000,
				overlayCSS: window._ovcss
			});
			var link = '<?php echo site_url("rkpd_preview/do_cetak_rkpd_tanpa_program"); ?>';
			$(location).attr('href',link);
		});
	});
</script>
<article class="module width_full" style="width: 138%; margin-left: -19%;">
	<header>
	  <h3>Preview RKPD</h3>
	</header>
	<div class="module_content";>
		<table class="table-display">
			<thead>
				<tr>
					<th colspan="5" align="center"><?php echo $rkpd_type; ?></th>
				</tr>
			</thead>		
			<?php
				echo $rkpd;
			?>
		</table>		
	</div>		
	<footer>
		<div class="submit_link">
 			<input id="cetak" type="button" value="Cetak" />
			<input type="button" value="Back" onclick="history.go(-1)" />
		</div>
	</footer>
</article>