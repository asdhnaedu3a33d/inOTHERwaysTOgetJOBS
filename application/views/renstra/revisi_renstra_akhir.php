<table class="table-common" width="800px">
	<thead>
		<tr>
			<th colspan="12">List revisi berdasarkan RPJMD dari setiap program</th>
		</tr>
		<tr>
			<th>Kode</th>
			<th>Nama Program</th>
			<th>Nominal 1</td>
			<th>Keterangan 1</td>
			<th>Nominal 2</th>
			<th>Keterangan 2</td>
			<th>Nominal 3</th>
			<th>Keterangan 3</td>
			<th>Nominal 4</th>
			<th>Keterangan 4</td>
			<th>Nominal 5</th>
			<th>Keterangan 5</td>
		</tr>
	</thead>
	<tbody>		
	<?php
		foreach ($revisi as $row) {
	?>
		<tr>
			<td><?php echo $row->kd_urusan.".".$row->kd_bidang.".".$row->kd_program; ?></td>
			<td><?php echo $row->nama_prog_or_keg; ?></td>
			<td align="right"><?php echo Formatting::currency($row->nominal_1); ?></td>
			<td><?php echo $row->ket_revisi_1; ?></td>
			<td align="right"><?php echo Formatting::currency($row->nominal_2); ?></td>
			<td><?php echo $row->ket_revisi_2; ?></td>
			<td align="right"><?php echo Formatting::currency($row->nominal_3); ?></td>
			<td><?php echo $row->ket_revisi_3; ?></td>
			<td align="right"><?php echo Formatting::currency($row->nominal_4); ?></td>
			<td><?php echo $row->ket_revisi_4; ?></td>
			<td align="right"><?php echo Formatting::currency($row->nominal_5); ?></td>
			<td><?php echo $row->ket_revisi_5; ?></td>
		</tr>
	<?php
		}
	?>		
	</tbody>
</table>