<style type="text/css">
	.no-border{
		border-collapse: collapse;
	}

	td.print-no-border{
		border: none;
	}
</style>
<div style="page-break-after: always;">
	<center><h4><?php echo $renstra_type; ?></h4></center>
	<table class="full_width no-border" style="font-size: 12px;">
		<?php
			echo $header_nama_skpd;
		?>
	</table>	
</div>
<div style="page-break-after: always;">
	
	<table class="full_width no-border" style="font-size: 12px;">
		<?php
			echo $header_visi_misi;
		?>
	</table>	

</div>

	
		

<div style="page-break-after: always;">
	<p> <strong>Tujuan :</strong> </p>
	<table  class="full_width collapse" border="1" style="font-size: 5px;">

		<?php
			echo $header_tujuan_gabung;
		?>
	</table>	
</div>

<div style="page-break-after: always;">
	<p> <strong>Sasaran :</strong> </p>
	<table class="full_width collapse" style="font-size: 5px;">
		<?php
			echo $header_sasaran_gabung;
		?>
	</table>	
</div>

<div>	
	<table class="full_width collapse" border="1" style="font-size: 5px;">
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