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
        	<th colspan="12" align="center"><?php echo "LAPORAN KENDALI RENJA TAHUN ".$tahun; ?></th>
        </tr>
        <tr>
        	<th colspan="12" align="left"><?php echo "SKPD : ".$skpd->nama_skpd; ?></th>
        </tr>
    </table>
	<table class="full_width border" style="font-size: 8px; page-break-after:always;">
		<?php
			echo $kendali;
		?>
	</table>		
</div>
<div>
    <?php
		switch(date('F')){
			case 'January';
			default:
				$bulan="Januari";
				break;
			case 'February';
			default:
				$bulan="Februari";
				break;
			case 'March';
			default:
				$bulan="Maret";
				break;
			case 'April';
			default:
				$bulan="April";
				break;
			case 'May';
			default:
				$bulan="Mei";
				break;
			case 'June';
			default:
				$bulan="Juni";
				break;
			case 'July';
			default:
				$bulan="Juli";
				break;
			case 'August';
			default:
				$bulan="Agustus";
				break;
			case 'September';
			default:
				$bulan="September";
				break;
			case 'October';
			default:
				$bulan="Oktober";
				break;
			case 'November';
			default:
				$bulan="November";
				break;
			case 'December';
			default:
				$bulan="Desember";
				break;
		}
		?>
        <br />
        <br />
        <br />
    <table class="full_width no-border" style="font-size: 11px;">
    	<tr>
        	<td style="width:60%">&nbsp;</td>
            <td align="center" style="width:30%"><?php echo "Semarapura, ".date('j')." ".$bulan." ".date('Y'); ?></td>
            <td style="width:10%">&nbsp;</td>
        </tr>
        <tr>
        	<td style="width:60%">&nbsp;</td>
            <td align="center" style="width:30%"><?php echo $skpd->nama_jabatan; ?></td>
            <td style="width:10%">&nbsp;</td>
        </tr>
       	<tr>
        	<td style="width:60%">&nbsp;</td>
            <td style="width:30%">&nbsp;</td>
            <td style="width:10%">&nbsp;</td>
        </tr>
        <tr>
        	<td style="width:60%">&nbsp;</td>
            <td style="width:30%">&nbsp;</td>
            <td style="width:10%">&nbsp;</td>
        </tr>
        <tr>
        	<td style="width:60%">&nbsp;</td>
            <td style="width:30%">&nbsp;</td>
            <td style="width:10%">&nbsp;</td>
        </tr>
        <tr>
        	<td style="width:60%">&nbsp;</td>
            <td style="width:30%">&nbsp;</td>
            <td style="width:10%">&nbsp;</td>
        </tr>
        <tr>
        	<td style="width:60%">&nbsp;</td>
            <td align="center" style="width:30%"><u><?php echo $skpd->kaskpd_nama; ?></u></td>
            <td style="width:10%">&nbsp;</td>
        </tr>
        <tr>
        	<td style="width:60%">&nbsp;</td>
            <td align="center" style="width:30%"><?php echo $skpd->pangkat_golongan; ?></td>
            <td style="width:10%">&nbsp;</td>
        </tr>
        <tr>
        	<td style="width:60%">&nbsp;</td>
            <td align="center" style="width:30%"><?php echo "NIP. ".$skpd->kaskpd_nip; ?></td>
            <td style="width:10%">&nbsp;</td>
        </tr>
	</table>
</div>		
