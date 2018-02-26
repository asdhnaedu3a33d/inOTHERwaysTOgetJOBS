<style type="text/css">
	.no-border{
		border-collapse: collapse;
	}

	td.print-no-border{
		border: none;
	}
</style>
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
<div>	
	<table class="full_width border" style="font-size: 8px; page-break-after:always" >
		<tr>
			<th colspan="15" align="center"><?php echo $rkpd_type; ?></th>
		</tr>
        <tr>
            <th rowspan="2" colspan="4">Kode</th>
            <th rowspan="2">Program dan Kegiatan</th>
            <th rowspan="2">Indikator Kinerja Program/Kegiatan</th>
            <th colspan="3">Renja Tahun <?php echo $ta?></th>
            <th rowspan="2">Catatan</th>
            <th colspan="3">Renja Tahun <?php echo $ta;?> Perubahan</th>
            <th rowspan="2">Catatan</th>
            <th rowspan="2">Keterangan Perubahan</th>
        </tr>
        <tr>
            <th >Lokasi</th>
            <th >Target Capaian Kinerja</th>
            <th >Kebutuhan Dana/Pagu Indikatif (Rp.)</th>
            <th >Lokasi</th>
            <th >Target Capaian Kinerja</th>
            <th >Kebutuhan Dana/Pagu Indikatif (Rp.)</th>
        </tr>
		<?php
			echo $rkpd;
		?>
	</table>
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