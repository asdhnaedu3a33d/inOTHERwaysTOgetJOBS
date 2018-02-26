<style type="text/css">
	.header_tw{
		color: #1C4675;
		font-size: 13px;
		font-weight: bold;
		text-transform: uppercase;
	}
</style>
<article class="module width_full" style="width: 138%; margin-left: -19%;">
	<header>
	  <h3>Preview Evaluasi Renja</h3>
	</header>
	<div class="module_content";>
		<?php
			echo $evaluasi;
		?>
	</div>
	<footer>
		<div class="submit_link">
    <?php
      if (is_null($skpd_var)) {
    ?>
      <a id="cetak" href="<?= base_url('evaluasi_renja/cetak_evaluasi_renja/'.$triwulan) ?>"><input type="button" value="Cetak"></a>
      <a href="<?= base_url('evaluasi_renja') ?>"><input type="button" value="Kembali"></a>
    <?php
      }
    ?>
		</div>
	</footer>
</article>
<script type="text/javascript">
	$(document).ready(function(){
		$("#cetak").click(function(event){
			event.preventDefault();
			window.open($(this).attr("href"));
		});
	});
</script>
