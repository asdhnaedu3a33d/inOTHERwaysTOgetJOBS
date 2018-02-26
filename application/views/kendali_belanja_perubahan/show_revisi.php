<table class="fcari" width="800px">
	<thead>		
		<tr>
			<th bgcolor="#FF8F8F" width="20%">Tanggal</td>
			<th bgcolor="#FF8F8F" width="80%">Keterangan Revisi</td>			
		</tr>
	</thead>
	<tbody>		
	<?php
		foreach ($revisi as $row) {
	?>
		<tr>
			<td align="center"><?php echo $row->formated_date; ?></td>
			<td><?php echo $row->ket; ?></td>
		</tr>
	<?php
		}
	?>
	</tbody>		
</table>