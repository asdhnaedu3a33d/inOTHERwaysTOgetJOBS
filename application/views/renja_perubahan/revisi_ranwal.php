<table class="table-common" width="800px">
	<thead>
    	<tr>
        	<th align="center">No</th>
            <th align="center">Kode</th>
            <th align="center">Keterangan</th>
        </tr>
    </thead>
	<tbody>
    <?php
	$i=0;
	foreach ($revisi as $row) { 
	$i++;
	?>
		<tr>
			<td><?php echo $i; ?></td >
			<td><?php echo $row->kd_urusan.".".$row->kd_bidang.".".$row->kd_program.".".$row->kd_kegiatan." | ".$row->nama_prog_or_keg;?></td>
            <td><?php echo $row->ket;?></td>
		</tr>
    <?php } ?>				
	</tbody>
</table>