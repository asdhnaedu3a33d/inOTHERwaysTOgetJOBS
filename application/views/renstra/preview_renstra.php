<article class="module width_full" style="width: 138%; margin-left: -19%;">
	<header>
	  <h3>Preview Renstra</h3>
	</header>
	<div class="module_content";>
		<center><h4><?php echo $renstra_type; ?></h4></center>
		<table class="table-common">
			<?php
				echo $header_renstra;
			?>
		</table>
    <div class="scroll">
  		<table class="table-common">
  			<thead>
  				<tr>
  					<th colspan="29" align="center"><?php echo $renstra_type; ?></th>
  				</tr>
  			</thead>
  			<?php
  				echo $renstra;
  			?>
  		</table>
    </div>		
	</div>
	<footer>
		<div class="submit_link">
			<input type="button" value="Kembali" onclick="history.go(-1)">
		</div>
	</footer>
</article>
