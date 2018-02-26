<style type="text/css">
	.no-border{
		border-collapse: collapse;
	}

	td.print-no-border{
		border: none;
	}
</style>
<div >
	<div style="page-break-after:always">
    <center><h3>BELUM DITENTUKAN</h3></center>
	<table class="full_width collapse" border="1px" style="font-size: 8px;">		
		<?php
			echo $rekapitulasi1;
		?>
	</table>
	</div>
	<div style="page-break-after:always">
    <center><h4>DITERIMA</h4></center>
	<table class="full_width collapse" border="1px" style="font-size: 8px;">
		<?php
			echo $rekapitulasi2;
		?>
	</table>
	</div>
	<div >
    <center><h4>DITOLAK</h4></center>
	<table class="full_width collapse" border="1px" style="font-size: 8px;">
		<?php
			echo $rekapitulasi3;
		?>
	</table>
	</div>
</div>