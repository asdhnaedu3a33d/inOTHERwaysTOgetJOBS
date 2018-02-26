<!-- <thead>

	<tr>
		<th>No</th>
		<th>Sasaran</th>
		<th>Indikator Kinerja</th>
		<th>Kondisi Awal</th>
		<th>Tahun I</th>
		<th>Tahun II</th>
		<th>Tahun III</th>
		<th>Tahun IV</th>
		<th>Tahun V</th>
		<th>Tahun Akhir</th>
	</tr>
</thead>
<tbody>
<?php
	$y=1;
	foreach ($sasaran as $row) {
		?>
	<tr>
		<td align="center"><?php echo $y; ?></td>
		<td><?php echo $row->sasaran; ?></td>
		<?php
		

		$indikator = $this->m_renstra_trx->get_indikator_sasaran_cetak($row->id);


		
		?>
		<td>
		<?php 
		$i=1;
			foreach ($indikator as $row1) {
				

				echo $y .". ". $row1->indikator ."<BR>";
				$i++;
			}

				?>

		</td> 

		<td>
			<?php 
			foreach ($indikator as $row1) {
				 echo number_format($row1->kondisi_awal)." ".$row1->nama_value."<BR>"; 
			}

		?>
		</td>
		<td>
			<?php 
			foreach ($indikator as $row1) {
				 echo number_format($row1->target_1)." ".$row1->nama_value."<BR>"; 
			}

		?>
		</td>
		<td>
			<?php 
			foreach ($indikator as $row1) {
				 echo number_format($row1->target_2)." ".$row1->nama_value."<BR>"; 
			}

		?>
		</td>
		<td>
			<?php 
			foreach ($indikator as $row1) {
				 echo number_format($row1->target_3)." ".$row1->nama_value."<BR>"; 
			}

		?>
		</td>
		<td>
			<?php 
			foreach ($indikator as $row1) {
				 echo number_format($row1->target_4)." ".$row1->nama_value."<BR>"; 
			}

		?>
		</td>
		<td>
			<?php 
			foreach ($indikator as $row1) {
				 echo number_format($row1->target_5)." ".$row1->nama_value."<BR>"; 
			}

		?>
		</td>
		<td>
			<?php 
			foreach ($indikator as $row1) {
				 echo number_format($row1->kondisi_akhir)." ".$row1->nama_value."<BR>"; 
			}

		?>
		</td>
	</tr>

	
<?php
$y++;
	}
	
?>
</tbody>
 -->

 <thead>

	<tr>
		<th>No</th>
		<th>Sasaran</th>
		<th>Indikator Kinerja</th>
		<th>Kondisi Awal</th>
		<th>Tahun I</th>
		<th>Tahun II</th>
		<th>Tahun III</th>
		<th>Tahun IV</th>
		<th>Tahun V</th>
		<th>Tahun Akhir</th>
	</tr>
</thead>
<tbody>
<?php
	foreach ($sasaran as $key => $row) {
		$rowspan_indikator = $this->m_renstra_trx->get_indikator_sasaran_cetak($row->id, TRUE)->num_rows();
		$indikator = $this->m_renstra_trx->get_indikator_sasaran_cetak($row->id);

		foreach ($indikator as $key1 => $row1) {
			if ($key1 == 0) {
?>
		<tr>
			<td align="center" rowspan="<?php echo $rowspan_indikator; ?>"><?php echo ($key+1); ?></td>
			<td rowspan="<?php echo $rowspan_indikator; ?>"><?php echo $row->sasaran; ?></td>
			<td><?php echo $row1->indikator; ?></td>
			<td><?php echo $row1->kondisi_awal." ".$row1->satuan_target; ?></td>
			<td><?php echo $row1->target_1; ?></td>
			<td><?php echo $row1->target_2; ?></td>
			<td><?php echo $row1->target_3; ?></td>
			<td><?php echo $row1->target_4; ?></td>
			<td><?php echo $row1->target_5; ?></td>
			<td><?php echo $row1->kondisi_akhir; ?></td>
		</tr>
<?php }else{ ?>
		<tr>
			<td><?php echo $row1->indikator; ?></td>
			<td><?php echo $row1->kondisi_awal." ".$row1->satuan_target; ?></td>
			<td><?php echo $row1->target_1; ?></td>
			<td><?php echo $row1->target_2; ?></td>
			<td><?php echo $row1->target_3; ?></td>
			<td><?php echo $row1->target_4; ?></td>
			<td><?php echo $row1->target_5; ?></td>
			<td><?php echo $row1->kondisi_akhir; ?></td>
		</tr>
<?php }}} ?>
	
</tbody>
