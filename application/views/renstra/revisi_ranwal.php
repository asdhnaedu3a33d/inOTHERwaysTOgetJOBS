<table class="fcari" width="900px">
	<thead>
		<tr>
			<th>Tujuan</th>
			<th>Sasaran</th>
			<th>Kode</th>
			<th>Kegiatan</th>
			<th>Keterangan</th>
		</tr>
	</thead>
	<tbody>
	<?php
		foreach ($revisi as $row) {
	?>
		<tr>
			<td><?php echo $row->tujuan; ?></td>
			<td><?php echo $row->sasaran; ?></td>
			<td><?php echo $row->kd_urusan.". ".$row->kd_bidang.". ".$row->kd_program.". ".$row->kd_kegiatan; ?></td>
			<td><?php echo $row->nama_prog_or_keg; ?></td>
			<td><?php echo $row->ket; ?></td>
		</tr>				
	<?php
		}
	?>		
	</tbody>
</table>