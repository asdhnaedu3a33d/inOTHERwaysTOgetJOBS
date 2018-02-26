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
        	<th colspan="12" align="center"><?php echo "LAPORAN CAPAIAN INDIKATOR KINERJA BELANJA LANGSUNG"; ?></th>
        </tr>
        <tr>
        	<th colspan="12" align="center"><?php echo "TAHUN ANGGARAN ".$tahun; ?></th>
        </tr>
        <tr>
        	<th colspan="12" align="center"><?php echo ""; ?></th>
        </tr>
        <tr>
        	<th colspan="12" align="center"><?php echo ""; ?></th>
        </tr>
        <tr>
        	<th colspan="12" align="left"><?php echo "SKPD : ".$skpd->nama_skpd;?></th>
        </tr>
    	<tr>
<th colspan="12" align="left">BULAN : <?php 
		switch($bulan){
			case '1';
			default:
				echo "JANUARI";
				break;
			case '2';
			default:
				echo "FEBRUARI";
				break;
			case '3';
			default:
				echo "MARET";
				break;
			case '4';
			default:
				echo "APRIL";
				break;
			case '5';
			default:
				echo "MEI";
				break;
			case '6';
			default:
				echo "JUNI";
				break;
			case '7';
			default:
				echo "JULI";
				break;
			case '8';
			default:
				echo "AGUSTUS";
				break;
			case '9';
			default:
				echo "SEPTEMBER";
				break;
			case '10';
			default:
				echo "OKTOBER";
				break;
			case '11';
			default:
				echo "NOVEMBER";
				break;
			case '12';
			default:
				echo "DESEMBER";
				break;
		}
		?></th>
    </tr>
    </table>
	<table class="full_width border" style="font-size: 11px; page-break-after:always;">
		<?php
			echo $cik;
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
