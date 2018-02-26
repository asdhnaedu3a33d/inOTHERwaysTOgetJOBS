<article class="module width_full" style="width: 100%;">
	<header>
	  <h3><?php echo "Preview Cetak ".$header_type?></h3>
	</header>
	<div class="module_content";>
		<table class="table-common">
			<thead>
				<tr>
					<th colspan="12" align="center"><?php echo $header_type; ?></th>
				</tr>
			</thead>		
			<?php
				echo $rekapitulasi1;
				echo $rekapitulasi2;
				echo $rekapitulasi3;
			?>
		</table>		
	</div>		
	<footer>
		<div class="submit_link">
			<input type="button" value="Kembali" onclick="history.go(-1)">
		</div>
	</footer>
</article>