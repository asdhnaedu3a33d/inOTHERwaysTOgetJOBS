<style type="text/css">
	.no-border{
		border-collapse: collapse;
	}

	td.print-no-border{
		border: none;
	}
</style>
<div>
	<table class="full_width no-border" style="font-size: 14px;">
    	<tr>
        	<th colspan="12" align="center"><?php echo "REKAP USULAN TERAKOMODASI PADA RKPD TAHUN ".$ta; ?></th>
        </tr>
    </table>
	<table class="full_width border" style="font-size: 11px;">
		<tr>
			<th colspan="13" align="center"><?php echo $usulan_type; ?></th>
		</tr>
		<?php
			echo $usulan;
		?>
	</table>		
</div>