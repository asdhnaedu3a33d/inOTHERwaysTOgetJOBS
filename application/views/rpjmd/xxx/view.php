<script type="text/javascript">
	$(document).ready(function(){
		$(document).on("click", "#cetak", function(){
			$.blockUI({
				message: 'Cetak dokumen sedang di proses, mohon ditunggu hingga file terunduh secara otomatis ...',
				css: window._css,
				timeout: 2000,
				overlayCSS: window._ovcss
			});
			var link = '<?php echo site_url("rpjmd/do_cetak"); ?>';
			$(location).attr('href',link);
		});
	});
</script>
<style type="text/css">
	.title{
		font-size: 14px;
		font-weight: bold;
	}
</style>
<article class="module width_full" style="width: 138%; margin-left: -19%;">
 	<header>
 		<h3>
			Cetak RPJMD
		</h3>
 	</header>
 	<div class="module_content">		
		<?php
			echo $cetak;
		?>		
 	</div>
 	<footer>
 		<div class="submit_link">
 			<input id="cetak" type="button" value="Cetak" />
			<input type="button" value="Back" onclick="history.go(-1)" />
		</div> 
 	</footer>
</article>