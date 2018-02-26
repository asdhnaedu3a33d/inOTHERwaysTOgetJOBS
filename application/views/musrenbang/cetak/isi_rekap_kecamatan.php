<thead>
	<tr>
		<th style="font-size:12px" >No</th>
		<th style="font-size:12px" >JENIS PEKERJAAN</th>
		<th style="font-size:12px" >VOLUME/SATUAN</th>
		<th style="font-size:12px" >LOKASI</th>
		<th style="font-size:12px" >DANA</th>
		<th style="font-size:12px" >NAMA DESA</th>
		<th style="font-size:12px" >SKPD PENANGGUNGJAWAB</th>
		<th style="font-size:12px" >KEPUTUSAN</th>
		<th style="font-size:12px" >ALASAN</th>
	</tr>
</thead>
<tbody>
	<?php
	$i=0;
	$total = 0;
		foreach ($rekap_kecamatan as $row) {
		$i++
	?>
		<tr>
			<td style="font-size:11px" align="center"><?php echo $i; ?></td>
			<td style="font-size:11px" align="left"><?php echo $row->jenis_pekerjaan;?></td>
			<td style="font-size:11px" align="center"><?php echo $row->volume." ".$row->satuan; ?></td>
			<td style="font-size:11px" align="center"><?php echo $row->lokasi; ?></td>
			<td style="font-size:11px" align="right"><?php echo "Rp ".Formatting::currency($row->jumlah_dana); ?></td>
			<td style="font-size:11px" align="center"><?php echo $row->nama_desa; ?></td>
			<td style="font-size:11px" align="center"><?php echo $row->nama_skpd;?></td>
			<td style="font-size:11px" align="center"><?php echo $row->keputusan;?></td>
			<td style="font-size:11px" align="center"><?php echo $row->alasan_keputusan;?></td>
		</tr>
	<?php
		$total = $total + $row->jumlah_dana;
	;
		}
	?>
    	<tr>
        	<td></td>
            <td></td>
            <td></td>
            <td>TOTAL</td>
            <td><?php echo "Rp ".Formatting::currency($total); ?></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
</tbody>	