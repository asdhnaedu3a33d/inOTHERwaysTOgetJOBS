<?php
	$i=0;
	foreach ($skpd as $row) {		
		$i++;
?>
		<tr>
			<td align="center"><?php echo $i; ?></td>
			<td><?php echo $row->nama_skpd; ?></td>
			<td align="center"><?php echo $row->jum_semua; ?></td>
			<td align="center"><a href="javascript:void(0)" onclick="veri_cik(<?php echo $row->id_skpd; ?>,<?php echo $bulan; ?>)" class="icon-edit" title="Verifikasi CIK Perubahan"/></td><input type="hidden" id="bulan" name="bulan" value="<?php echo $bulan;?>" />
		</tr>
<?php
	}
?>
