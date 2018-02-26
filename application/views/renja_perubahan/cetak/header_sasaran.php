<thead>
	<tr>
		<th>No</th>
		<th>Sasaran</th>
		<th>Indikator Kinerja</th>
		<th>Triwulan I</th>
		<th>Triwulan II</th>
		<th>Triwulan III</th>
		<th>Triwulan IV</th>
	</tr>
</thead>
<tbody>
<?php
	$i=0;
	foreach ($sasaran as $row) {
		$i++;
?>
	<tr>
		<td align="center"><?php echo $i; ?></td>
		<td><?php echo $row->sasaran; ?></td>
		<td><?php echo $row->indikator; ?></td>
		<td align="center"><?php echo $row->triwulan_1." ".$row->nama_value; ?></td>
		<td align="center"><?php echo $row->triwulan_2." ".$row->nama_value; ?></td>
		<td align="center"><?php echo $row->triwulan_3." ".$row->nama_value; ?></td>
		<td align="center"><?php echo $row->triwulan_4." ".$row->nama_value; ?></td>
	</tr>
<?php					
	}
?>		
</tbody>